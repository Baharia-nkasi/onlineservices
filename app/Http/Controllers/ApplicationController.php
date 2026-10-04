<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    public function create(Service $service)
    {
        abort_unless($service->is_active, 404);

        $service->load(['documents' => fn ($query) => $query->where('is_active', true)]);

        return view('applications.create', compact('service'));
    }

    public function store(Request $request, Service $service)
    {
        abort_unless($service->is_active, 404);

        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $application = Application::create([
            'user_id' => Auth::id(),
            'service_id' => $service->id,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('customer.applications.show', $application)
            ->with('success', 'Application submitted successfully. Please upload the required documents.');
    }
}
