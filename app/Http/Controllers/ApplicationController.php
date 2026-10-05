<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $application = DB::transaction(function () use ($service, $validated) {
            $user = Auth::user()->newQuery()->lockForUpdate()->findOrFail(Auth::id());

            $existing = Application::where('user_id', $user->id)
                ->where('service_id', $service->id)
                ->whereIn('status', ['pending', 'processing'])
                ->latest()
                ->first();

            if ($existing) {
                return $existing;
            }

            return Application::create([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        if ($application->wasRecentlyCreated) {
            return redirect()
                ->route('customer.applications.show', $application)
                ->with('success', __('Application submitted successfully. Please upload the required documents.'));
        }

        return redirect()
            ->route('customer.applications.show', $application)
            ->with('success', __('You already have an active application for this service.'));
    }
}
