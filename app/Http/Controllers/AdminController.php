<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Service;
use App\Models\ServiceDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Notifications\ApplicationStatusUpdated;

class AdminController extends Controller
{
    private function guard(): void
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);
    }

    /**
     * Keep active service-document positions continuous after an item is removed.
     * Soft-deleted/inactive historical versions are intentionally excluded.
     */
    private function normalizeServiceDocumentOrder(Service $service): void
    {
        $documents = $service->documents()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        foreach ($documents as $index => $document) {
            $expectedOrder = $index + 1;

            if ((int) $document->sort_order !== $expectedOrder) {
                $document->update(['sort_order' => $expectedOrder]);
            }
        }
    }

    public function index()
    {
        $this->guard();

        $validated = request()->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:pending,processing,rejected'],
        ]);

        // The dashboard is an action queue: only applications that still need admin work appear here.
        // Completed and rejected applications remain available through My Applications/customer history and stats.
        $applications = Application::with(['user', 'service', 'documents'])
            ->whereIn('status', ['pending', 'processing'])
            ->when($validated['q'] ?? null, function ($query, $search) {
                $search = trim($search);

                if ($search === '') {
                    return;
                }

                $searchLower = mb_strtolower($search);

                $query->where(function ($query) use ($search, $searchLower) {
                    $query->where('id', is_numeric($search) ? (int) $search : -1)
                        ->orWhereHas('user', function ($user) use ($searchLower) {
                            $user->whereRaw('LOWER(name) LIKE ?', ["%{$searchLower}%"])
                                ->orWhereRaw('LOWER(email) LIKE ?', ["%{$searchLower}%"]);
                        })
                        ->orWhereHas('service', function ($service) use ($searchLower) {
                            $service->whereRaw('LOWER(name) LIKE ?', ["%{$searchLower}%"]);
                        });
                });
            })
            ->when($validated['status'] ?? null, function ($query, $status) {
                // Keep the admin work queue limited to actionable states.
                if (in_array($status, ['pending', 'processing'], true)) {
                    $query->where('status', $status);
                }
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'applications' => Application::count(),
            'pending' => Application::where('status', 'pending')->count(),
            'processing' => Application::where('status', 'processing')->count(),
            'completed' => Application::where('status', 'completed')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
            'documents' => ApplicationDocument::count(),
            'services' => Service::where('is_active', true)->count(),
        ];

        return view('admin.dashboard', compact('applications', 'stats'));
    }

    public function showApplication(Application $application)
    {
        $this->guard();

        $application->load(['user', 'service.documents', 'documents']);

        return view('admin.application-show', compact('application'));
    }

    public function updateApplicationStatus(Request $request, Application $application)
    {
        $this->guard();

        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,approved,completed,rejected'],
            'approval_remark' => [
                'nullable', 'string', 'max:2000',
                Rule::requiredIf($validated['status'] === 'rejected'),
            ],
        ]);

        $next = $validated['status'];
        $approvalRemark = trim((string) ($validated['approval_remark'] ?? ''));
        $notificationData = null;
        $purgedDocumentPaths = [];

        $error = DB::transaction(function () use ($application, $next, $approvalRemark, &$notificationData, &$purgedDocumentPaths) {
            // Serialize admin status changes so two admins cannot complete/reject
            // the same application against stale document/status data.
            $lockedApplication = Application::query()->lockForUpdate()->findOrFail($application->id);
            $current = $lockedApplication->status;

            $allowed = [
                'pending' => ['pending', 'processing', 'approved', 'rejected'],
                'processing' => ['processing', 'approved', 'completed', 'rejected'],
                'approved' => ['approved', 'completed'],
                'completed' => ['completed'],
                'rejected' => ['rejected', 'processing'],
            ];

            if (! in_array($next, $allowed[$current] ?? [], true)) {
                return __('Invalid status transition from :current to :next.', [
                    'current' => $current,
                    'next' => $next,
                ]);
            }

            // Document review must finish before the first approval because
            // approval immediately purges uploaded documents. A later remark-only
            // approval update does not need the documents to still exist.
            if (
                ($next === 'approved' && $current !== 'approved')
                || ($next === 'completed' && $current !== 'approved')
            ) {
                $lockedApplication->load(['service.documents', 'documents']);

                $requirements = $lockedApplication->effectiveServiceRequirements();

                $missingRequired = $requirements
                    ->where('is_required', true)
                    ->whereNull('requirement_group')
                    ->filter(fn ($requirement) => ! $lockedApplication->documents->contains(
                        fn ($document) => mb_strtolower($document->document_name) === mb_strtolower($requirement->name)
                            && $document->status === 'approved'
                            && $document->hasAvailableFile()
                    ));

                $grouped = $requirements
                    ->where('is_required', true)
                    ->whereNotNull('requirement_group')
                    ->groupBy('requirement_group');

                $missingGroups = $grouped->filter(function ($requirements) use ($lockedApplication) {
                    $minimum = max(1, (int) $requirements->max('minimum_required'));
                    $approved = $lockedApplication->documents
                        ->where('status', 'approved')
                        ->filter(fn ($document) => $requirements->contains(
                            fn ($requirement) => mb_strtolower($document->document_name) === mb_strtolower($requirement->name)
                                && $document->hasAvailableFile()
                        ))
                        ->count();

                    return $approved < $minimum;
                });

                if ($missingRequired->isNotEmpty() || $missingGroups->isNotEmpty()) {
                    return __('This application cannot be completed until all required documents and requirement groups have approved documents.');
                }
            }

            $previousStatus = $lockedApplication->status;
            $previousRemark = (string) $lockedApplication->approval_remark;
            $nextRemark = in_array($next, ['approved', 'rejected'], true)
                ? ($approvalRemark !== '' ? $approvalRemark : $lockedApplication->approval_remark)
                : $lockedApplication->approval_remark;

            $lockedApplication->update([
                'status' => $next,
                'approval_remark' => $nextRemark,
            ]);

            // Approval is the end of the document-review phase. Once the admin
            // approves the application, remove every uploaded file immediately
            // from the application so neither side can keep accessing old uploads.
            if ($next === 'approved' && $current !== 'approved') {
                $documents = $lockedApplication->documents()->lockForUpdate()->get();

                foreach ($documents as $uploadedDocument) {
                    if ($uploadedDocument->file_path && ! str_starts_with($uploadedDocument->file_path, 'database://')) {
                        $purgedDocumentPaths[] = $uploadedDocument->file_path;
                    }

                    $uploadedDocument->delete();
                }
            }

            $notificationData = [
                'previous_status' => $previousStatus,
                'status' => $next,
                'remark_changed' => $previousRemark !== (string) $nextRemark,
                'remark' => $nextRemark,
            ];

            return null;
        });

        if ($error) {
            return back()->withErrors(['status' => $error]);
        }

        // Database deletion is already committed. Remove any legacy local files
        // outside the database as the final cleanup step.
        foreach (array_unique($purgedDocumentPaths) as $path) {
            Storage::disk('local')->delete($path);
        }

        if (
            $notificationData
            && $application->user
            && $application->user->isCustomer()
            && in_array($notificationData['status'], ['processing', 'approved', 'rejected'], true)
            && (
                $notificationData['previous_status'] !== $notificationData['status']
                || ($notificationData['status'] === 'approved' && $notificationData['remark_changed'])
            )
        ) {
            $application->loadMissing('service');

            $application->user->notify(new ApplicationStatusUpdated(
                $application,
                $notificationData['status'],
                $notificationData['remark'],
                $notificationData['status'] === 'approved' && $notificationData['remark_changed'],
            ));
        }

        return back()->with('success', __('Application status updated.'));
    }

    /**
     * Delete a customer-uploaded document when an admin confirms it is wrong.
     * Completed applications remain locked.
     */
    public function destroyDocument(ApplicationDocument $document)
    {
        $this->guard();

        $oldPath = null;

        $error = DB::transaction(function () use ($document, &$oldPath) {
            $application = Application::query()->lockForUpdate()->findOrFail($document->application_id);
            $lockedDocument = $application->documents()->lockForUpdate()->findOrFail($document->id);

            if (in_array($application->status, ['approved', 'completed', 'rejected'], true)) {
                return __('Documents for an approved, completed or rejected application are locked and cannot be deleted.');
            }

            $oldPath = $lockedDocument->file_path;
            $lockedDocument->delete();

            return null;
        });

        if ($error) {
            return back()->withErrors(['document' => $error]);
        }

        if ($oldPath && ! str_starts_with($oldPath, 'database://')) {
            Storage::disk('local')->delete($oldPath);
        }

        return back()->with('success', __('Document deleted. The customer can upload a new file again.'));
    }

    public function updateDocumentStatus(Request $request, ApplicationDocument $document)
    {
        $this->guard();

        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,approved,rejected'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $error = DB::transaction(function () use ($document, $validated) {
            $application = Application::query()->lockForUpdate()->findOrFail($document->application_id);
            $lockedDocument = $application->documents()->lockForUpdate()->findOrFail($document->id);

            if (in_array($application->status, ['approved', 'completed', 'rejected'], true)) {
                return __('Documents for an approved, completed or rejected application are locked and cannot be changed.');
            }

            if ($validated['status'] === 'approved' && ! $lockedDocument->hasAvailableFile()) {
                return __('This document cannot be approved because the uploaded file is unavailable. Ask the customer to re-upload it.');
            }

            $lockedDocument->update($validated);

            return null;
        });

        if ($error) {
            return back()->withErrors(['status' => $error]);
        }

        return back()->with('success', __('Document status updated.'));
    }

    public function showService(Service $service)
    {
        $this->guard();

        $service->load('documents');
        $service->loadCount('applications');
        $service->loadCount(['documents as active_documents_count' => fn ($query) => $query->where('is_active', true)]);

        return view('admin.services.show', compact('service'));
    }

    public function services(Request $request)
    {
        $this->guard();

        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $search = trim((string) ($validated['q'] ?? ''));

        $services = Service::withCount('applications')
            ->withCount(['documents as active_documents_count' => fn ($query) => $query->where('is_active', true)])
            ->when($search !== '', function ($query) use ($search) {
                $searchLower = mb_strtolower($search);

                $query->where(function ($query) use ($searchLower) {
                    $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchLower}%"])
                        ->orWhereRaw('LOWER(slug) LIKE ?', ["%{$searchLower}%"])
                        ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchLower}%"]);
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.services.index', compact('services', 'search'));
    }

    public function storeService(Request $request)
    {
        $this->guard();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:services,slug'],
            'description' => ['nullable', 'string', 'max:5000'],
            'image_url' => ['nullable', 'string', 'max:2048', 'regex:/^https?:\/\//i'],
            'government_fee' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'service_fee' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'is_active' => ['required', 'boolean'],
        ]);

        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', __('Service created successfully.'));
    }

    public function updateService(Request $request, Service $service)
    {
        $this->guard();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:services,slug,'.$service->id],
            'description' => ['nullable', 'string', 'max:5000'],
            'image_url' => ['nullable', 'string', 'max:2048', 'regex:/^https?:\/\//i'],
            'is_active' => ['required', 'boolean'],
            'government_fee' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'service_fee' => ['required', 'numeric', 'min:0', 'max:9999999999'],
        ]);

        $service->update($validated);

        return back()->with('success', __('Service settings updated.'));
    }

    public function storeServiceDocument(Request $request, Service $service)
    {
        $this->guard();

        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('service_documents', 'name')
                    ->where(fn ($query) => $query
                        ->where('service_id', $service->id)
                        ->where('is_active', true)
                        ->whereNull('deleted_at')),
            ],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_required' => ['required', 'boolean'],
            'requirement_type' => ['required', 'in:single,choose_one,choose_many'],
            'requirement_group' => ['nullable', 'string', 'max:100', 'regex:/^[A-Za-z0-9_-]+$/'],
            'minimum_required' => ['required', 'integer', 'min:1', 'max:100'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:10000'],
            'is_active' => ['required', 'boolean'],
        ]);

        if ($validated['requirement_type'] === 'single') {
            $validated['requirement_group'] = null;
            $validated['minimum_required'] = 1;
        } elseif (blank($validated['requirement_group'])) {
            return back()->withErrors([
                'requirement_group' => __('A requirement group is required for choose-one or choose-many rules.'),
            ])->withInput()->withFragment('document-requirements');
        }

        $service->documents()->create($validated);

        return back()->with('success', __('Document requirement added successfully.'))
            ->withFragment('document-requirements');
    }

    public function updateServiceDocument(Request $request, ServiceDocument $document)
    {
        $this->guard();

        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('service_documents', 'name')
                    ->where(fn ($query) => $query
                        ->where('service_id', $document->service_id)
                        ->whereNull('deleted_at'))
                    ->ignore($document->id),
            ],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_required' => ['required', 'boolean'],
            'requirement_type' => ['required', 'in:single,choose_one,choose_many'],
            'requirement_group' => ['nullable', 'string', 'max:100', 'regex:/^[A-Za-z0-9_-]+$/'],
            'minimum_required' => ['required', 'integer', 'min:1', 'max:100'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:10000'],
            'is_active' => ['required', 'boolean'],
        ]);

        $validated['requirement_group'] = filled($validated['requirement_group'] ?? null)
            ? $validated['requirement_group']
            : null;

        if ($validated['requirement_type'] === 'single') {
            $validated['requirement_group'] = null;
            $validated['minimum_required'] = 1;
        } elseif (blank($validated['requirement_group'])) {
            return back()->withErrors([
                'requirement_group' => __('A requirement group is required for choose-one or choose-many rules.'),
            ])->withInput();
        }

        $serviceHasApplications = $document->service->applications()->exists();

        $protectedFieldsChanged =
            $document->name !== $validated['name']
            || (bool) $document->is_required !== (bool) $validated['is_required']
            || $document->requirement_type !== $validated['requirement_type']
            || $document->requirement_group !== $validated['requirement_group']
            || (int) $document->minimum_required !== (int) $validated['minimum_required'];

        if ($serviceHasApplications && $protectedFieldsChanged) {
            /*
             * The old requirement must remain available to historical applications.
             * Create a new version for future applications, then soft-delete the old
             * catalog entry. This removes the previous blocking error while preserving
             * the requirement that applied to existing applications.
             */
            $newRequirementId = null;

            DB::transaction(function () use ($document, $validated, &$newRequirementId) {
                // Retire the previous version from the active catalogue and soft-delete
                // it so the same requirement name can be reused without losing history.
                $document->update([
                    'is_active' => false,
                ]);
                $document->delete();

                $newRequirement = $validated;
                $newRequirement['service_id'] = $document->service_id;

                $created = ServiceDocument::create($newRequirement);
                $newRequirementId = $created->id;
            });

            return back()->with('success', __('Requirement updated for future applications. The previous version remains preserved for existing application history.'))
                ->withFragment($newRequirementId ? 'document-requirement-'.$newRequirementId : 'document-requirements');
        }

        $document->update($validated);

        return back()->with('success', __('Document requirement updated successfully.'))
            ->withFragment('document-requirements');
    }

    public function destroyServiceDocument(ServiceDocument $document)
    {
        $this->guard();

        $service = $document->service;
        $serviceHasApplications = $service->applications()->exists();

        /*
         * Soft-delete the requirement so historical applications keep their
         * original requirement definition. Then compact the active catalogue
         * numbering so admins never see gaps such as 1, 2, 4, 5 after a delete.
         */
        DB::transaction(function () use ($document, $service) {
            $document->delete();
            $this->normalizeServiceDocumentOrder($service);
        });

        if ($serviceHasApplications) {
            return back()->with('success', __('Requirement removed from the active catalogue. Existing application history has been preserved and the remaining requirements were automatically renumbered.'))
                ->withFragment('document-requirements');
        }

        return back()->with('success', __('Document requirement deleted successfully and the remaining requirements were automatically renumbered.'))
            ->withFragment('document-requirements');
    }

    public function destroyService(Service $service)
    {
        $this->guard();

        /*
         * This is an intentional hard-delete action for catalogue cleanup.
         * A service and everything that belongs exclusively to it is removed
         * together: applications, uploaded document rows, notifications tied
         * to those applications, and document-requirement versions.
         *
         * The confirmation in the admin UI makes the destructive nature clear.
         */
        $applicationIds = $service->applications()->pluck('id');

        DB::transaction(function () use ($service, $applicationIds) {
            // Remove notification records that point to applications of this service.
            // Notification data is polymorphic JSON/text, so match application IDs
            // safely in PHP instead of depending on database-specific JSON syntax.
            if ($applicationIds->isNotEmpty()) {
                $notifications = DB::table('notifications')->get(['id', 'data']);

                $notificationIds = $notifications
                    ->filter(function ($notification) use ($applicationIds) {
                        $data = json_decode((string) $notification->data, true);

                        return isset($data['application_id'])
                            && $applicationIds->contains((int) $data['application_id']);
                    })
                    ->pluck('id');

                if ($notificationIds->isNotEmpty()) {
                    DB::table('notifications')->whereIn('id', $notificationIds)->delete();
                }
            }

            // application_documents and applications use cascadeOnDelete,
            // so deleting the service removes all dependent application history.
            $service->documents()->withTrashed()->forceDelete();
            $service->forceDelete();
        });

        return redirect()->route('admin.services.index')
            ->with('success', __('Service and its related application history were permanently deleted. The catalogue is now clean for new services.'));
    }
}
