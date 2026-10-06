<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)
            ->withCount(['documents as active_documents_count' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('name')
            ->get();

        return view('services.index', compact('services'));
    }
}
