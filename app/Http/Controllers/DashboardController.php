<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the customer dashboard.
     */
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $userId = Auth::id();

        // Customer applications
        $applications = Application::with('service')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        // Application statistics
        $totalApplications = $applications->count();

        $pendingApplications = $applications
            ->where('status', 'pending')
            ->count();

        $processingApplications = $applications
            ->where('status', 'processing')
            ->count();

        $completedApplications = $applications
            ->where('status', 'completed')
            ->count();

        // Active services
        $services = Service::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        // Recent applications
        $recentApplications = $applications->take(5);

        return view('dashboard', compact(
            'totalApplications',
            'pendingApplications',
            'processingApplications',
            'completedApplications',
            'services',
            'recentApplications'
        ));
    }
}