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

class AdminController extends Controller
{
    private function guard(): void
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);
    }

    public function index()
    {
        $this->guard();

        $validated = request()->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:pending,processing'],
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
        ]);

        $next = $validated['status'];
        $error = DB::transaction(function () use ($application, $next) {
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

            if ($next === 'completed') {
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

            $lockedApplication->update(['status' => $next]);

            return null;
        });

        if ($error) {
            return back()->withErrors(['status' => $error]);
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

            if (in_array($application->status, ['completed', 'rejected'], true)) {
                return __('Documents for a completed or rejected application are locked and cannot be deleted.');
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
            'status' => ['required', 'in:pending,approved,rejected'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $error = DB::transaction(function () use ($document, $validated) {
            $application = Application::query()->lockForUpdate()->findOrFail($document->application_id);
            $lockedDocument = $application->documents()->lockForUpdate()->findOrFail($document->id);

            if (in_array($application->status, ['completed', 'rejected'], true)) {
                return __('Documents for a completed or rejected application are locked and cannot be changed.');
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
                Rule::unique('service_documents', 'name')->where(fn ($query) => $query->where('service_id', $service->id)),
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
            ])->withInput();
        }

        $service->documents()->create($validated);

        return back()->with('success', __('Document requirement added successfully.'));
    }

    public function updateServiceDocument(Request $request, ServiceDocument $document)
    {
        $this->guard();

        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('service_documents', 'name')
                    ->where(fn ($query) => $query->where('service_id', $document->service_id))
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

        if ($validated['requirement_type'] === 'single') {
            $validated['requirement_group'] = null;
            $validated['minimum_required'] = 1;
        } elseif (blank($validated['requirement_group'])) {
            return back()->withErrors([
                'requirement_group' => __('A requirement group is required for choose-one or choose-many rules.'),
            ])->withInput();
        }

        $serviceHasApplications = $document->service->applications()->exists();

        if ($serviceHasApplications) {
            $protectedFieldsChanged =
                $document->name !== $validated['name']
                || (bool) $document->is_required !== (bool) $validated['is_required']
                || $document->requirement_type !== $validated['requirement_type']
                || $document->requirement_group !== ($validated['requirement_group'] ?? null)
                || (int) $document->minimum_required !== (int) $validated['minimum_required'];

            if ($protectedFieldsChanged) {
                return back()->withErrors([
                    'document' => __('This requirement cannot change its name or completion rules because the service already has customer applications. Deactivate it instead and create a new requirement for future applications.'),
                ]);
            }
        }

        $document->update($validated);

        return back()->with('success', __('Document requirement updated successfully.'));
    }

    public function destroyServiceDocument(ServiceDocument $document)
    {
        $this->guard();

        if ($document->service->applications()->exists()) {
            return back()->withErrors([
                'document' => __('This requirement cannot be deleted because the service has customer applications. Deactivate it instead to preserve application history.'),
            ]);
        }

        $document->delete();

        return back()->with('success', __('Document requirement deleted successfully.'));
    }

    public function destroyService(Service $service)
    {
        $this->guard();

        if ($service->is_active) {
            return back()->withErrors([
                'service' => __('Only deactivated services can be deleted. Deactivate the service first.'),
            ]);
        }

        if ($service->applications()->exists()) {
            return back()->withErrors([
                'service' => __('This service cannot be deleted because it has customer applications. Keep it deactivated to preserve application history.'),
            ]);
        }

        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', __('Service deleted successfully.'));
    }
}
