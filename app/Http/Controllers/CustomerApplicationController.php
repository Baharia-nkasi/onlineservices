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
        $applications = Application::with('service')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.applications.index', [
            'applications' => $applications,
        ]);
    }

    /**
     * Display a customer's application details.
     */
    public function show(Application $application)
    {
        abort_if($application->user_id !== Auth::id(), 403);

        $application->load([
    'service',
    'documents',
]);

        return view('customer.applications.show', [
            'application' => $application,
        ]);
    }
}