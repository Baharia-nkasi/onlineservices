<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->isAdmin() || Auth::user()->isCustomer(), 403);

        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $userId = Auth::id();

        $totalApplications = Application::where('user_id', $userId)->count();
        $pendingApplications = Application::where('user_id', $userId)->where('status', 'pending')->count();
        $processingApplications = Application::where('user_id', $userId)->where('status', 'processing')->count();
        $completedApplications = Application::where('user_id', $userId)->where('status', 'completed')->count();

        $services = Service::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        $recentApplications = Application::with('service')
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'processing', 'completed'])
            ->latest()
            ->take(5)
            ->get();

        // Customer-facing updates: completed/rejected applications and document review results.
        $recentCompletedApplications = Application::with(['service.documents' => fn ($query) => $query->where('is_active', true)])
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->latest('updated_at')
            ->take(3)
            ->get();

        $recentRejectedApplications = Application::with('service')
            ->where('user_id', $userId)
            ->where('status', 'rejected')
            ->latest('updated_at')
            ->take(3)
            ->get();

        $recentDocumentUpdates = \App\Models\ApplicationDocument::with('application.service')
            ->whereHas('application', fn ($query) => $query->where('user_id', $userId))
            ->whereIn('status', ['approved', 'rejected'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalApplications',
            'pendingApplications',
            'processingApplications',
            'completedApplications',
            'services',
            'recentApplications',
            'recentCompletedApplications',
            'recentRejectedApplications',
            'recentDocumentUpdates'
        ));
    }
}
