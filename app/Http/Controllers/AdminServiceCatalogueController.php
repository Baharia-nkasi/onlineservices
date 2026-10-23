<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminServiceCatalogueController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);

        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $search = trim((string) ($validated['q'] ?? ''));

        // Show active AND deactivated services to admins so a deactivated
        // service can be permanently removed when it has no application history.
        $services = Service::query()
            ->with('documents')
            ->withCount('applications')
            ->withCount(['documents as active_documents_count' => fn ($query) => $query->where('is_active', true)])
            ->when($search !== '', function ($query) use ($search) {
                $searchLower = mb_strtolower($search);

                $query->where(function ($query) use ($searchLower) {
                    $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchLower}%"])
                        ->orWhereRaw('LOWER(slug) LIKE ?', ["%{$searchLower}%"])
                        ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchLower}%"]);
                });
            })
            ->latest()
            ->get();

        return view('admin.services.index', compact('services', 'search'));
    }
}
