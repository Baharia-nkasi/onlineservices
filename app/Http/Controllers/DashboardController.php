<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\HelpDeskContact;
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
            ->whereIn('status', ['pending', 'processing', 'approved', 'completed'])
            ->latest()
            ->take(5)
            ->get();

        $helpDeskContacts = HelpDeskContact::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('network')
            ->get();

        return view('dashboard', compact(
            'totalApplications',
            'pendingApplications',
            'processingApplications',
            'completedApplications',
            'services',
            'recentApplications',
            'helpDeskContacts',
        ));
    }
}
