<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class CustomerApplicationController extends Controller
{
    /**
     * Display the customer's applications.
     */
    public function index()
    {
        $user = Auth::user();

        abort_unless($user?->isAdmin() || $user?->isCustomer(), 403);

        $applications = Application::with(['service', 'documents'])
            ->when($user->isCustomer(), fn ($query) => $query->where('user_id', $user->id))
            ->latest()
            ->paginate(10);

        return view('customer.applications.index', [
            'applications' => $applications,
        ]);
    }

    /**
     * Display a customer's application details.
     */
    public function show(Application $application)
    {
        $user = Auth::user();

        abort_unless($user?->isAdmin() || $user?->isCustomer(), 403);
        abort_if($user->isCustomer() && $application->user_id !== $user->id, 403);

        if ($user->isAdmin()) {
            return app(AdminController::class)->showApplication($application);
        }

        $application->load([
            'service',
            'documents',
        ]);

        return view('customer.applications.show', [
            'application' => $application,
        ]);
    }
}