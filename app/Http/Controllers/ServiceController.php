<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)
            ->with(['documents' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')])
            ->latest()
            ->get();

        return view('services.index', compact('services'));
    }
}
