<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'status' => ['nullable', 'in:pending,processing,completed,rejected'],
        ]);

        $applications = Application::with(['user', 'service', 'documents'])
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
            ->when($validated['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
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
            'status' => ['required', 'in:pending,processing,completed,rejected'],
        ]);

        $current = $application->status;
        $next = $validated['status'];
        $allowed = [
            'pending' => ['pending', 'processing', 'rejected'],
            'processing' => ['processing', 'completed', 'rejected'],
            'completed' => ['completed'],
            'rejected' => ['rejected', 'processing'],
        ];

        if (! in_array($next, $allowed[$current] ?? [], true)) {
            return back()->withErrors([
                'status' => "Invalid status transition from {$current} to {$next}.",
            ]);
        }

        if ($validated['status'] === 'completed') {
            $application->load(['service.documents', 'documents']);

            $missingRequired = $application->service->documents
                ->where('is_active', true)
                ->where('is_required', true)
                ->whereNull('requirement_group')
                ->filter(fn ($requirement) => ! $application->documents->contains(
                    fn ($document) => mb_strtolower($document->document_name) === mb_strtolower($requirement->name)
                        && $document->status === 'approved'
                ));

            $grouped = $application->service->documents
                ->where('is_active', true)
                ->whereNotNull('requirement_group')
                ->groupBy('requirement_group');

            $missingGroups = $grouped->filter(function ($requirements) use ($application) {
                $minimum = max(1, (int) $requirements->max('minimum_required'));
                $approved = $application->documents
                    ->where('status', 'approved')
                    ->filter(fn ($document) => $requirements->contains(
                        fn ($requirement) => mb_strtolower($document->document_name) === mb_strtolower($requirement->name)
                    ))
                    ->count();

                return $approved < $minimum;
            });

            if ($missingRequired->isNotEmpty() || $missingGroups->isNotEmpty()) {
                return back()->withErrors([
                    'status' => __('This application cannot be completed until all required documents and requirement groups have approved documents.'),
                ]);
            }
        }

        $application->update(['status' => $validated['status']]);

        return back()->with('success', __('Application status updated.'));
    }

    /**
     * Delete a customer-uploaded document when an admin confirms it is wrong.
     * Completed applications remain locked.
     */
    public function destroyDocument(ApplicationDocument $document)
    {
        $this->guard();

        $application = $document->application;

        if ($application->status === 'completed') {
            return back()->withErrors([
                'document' => __('Documents for a completed application are locked and cannot be deleted.'),
            ]);
        }

        if ($document->file_path && ! str_starts_with($document->file_path, 'database://')) {
            \Illuminate\Support\Facades\Storage::disk('local')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', __('Document deleted. The customer can upload a new file again.'));
    }

    public function updateDocumentStatus(Request $request, ApplicationDocument $document)
    {
        $this->guard();

        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($document->application->status === 'completed') {
            return back()->withErrors([
                'status' => __('Documents for a completed application are locked and cannot be changed.'),
            ]);
        }

        $document->update($validated);

        return back()->with('success', __('Document status updated.'));
    }

    public function updateService(Request $request, Service $service)
    {
        $this->guard();

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
            'service_fee' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'government_fee' => ['required', 'numeric', 'min:0', 'max:9999999999'],
        ]);

        $service->update($validated);

        return back()->with('success', __('Service settings updated.'));
    }
}
