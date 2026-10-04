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

        $applications = Application::with(['user', 'service', 'documents'])
            ->latest()
            ->paginate(15);

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

        $application->update(['status' => $validated['status']]);

        return back()->with('success', 'Application status updated.');
    }

    public function updateDocumentStatus(Request $request, ApplicationDocument $document)
    {
        $this->guard();

        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $document->update($validated);

        return back()->with('success', 'Document status updated.');
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

        return back()->with('success', 'Service settings updated.');
    }
}
