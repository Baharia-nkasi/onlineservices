<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    /**
     * Show the application form for a service.
     */
    public function create(Service $service)
    {
        return view('applications.create', [
            'service' => $service,
        ]);
    }

    /**
     * Store a new application.
     */
    public function store(Request $request, Service $service)
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        Application::create([
            'user_id' => Auth::id(),
            'service_id' => $service->id,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('services.index')
            ->with('success', 'Application submitted successfully.');
    }
}