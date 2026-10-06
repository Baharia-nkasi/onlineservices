<?php

namespace App\Http\Controllers;

use App\Models\HelpDeskContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelpDeskContactController extends Controller
{
    private function guard(): void
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);
    }

    public function index()
    {
        $this->guard();

        $contacts = HelpDeskContact::orderBy('sort_order')->orderBy('network')->get();

        return view('admin.help-desk.index', compact('contacts'));
    }

    public function store(Request $request)
    {
        $this->guard();

        $validated = $request->validate([
            'network' => ['required', 'string', 'max:80'],
            'phone' => ['required', 'string', 'max:40'],
            'label' => ['nullable', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        HelpDeskContact::create($validated);

        return back()->with('success', __('Help desk contact added successfully.'));
    }

    public function update(Request $request, HelpDeskContact $helpDeskContact)
    {
        $this->guard();

        $validated = $request->validate([
            'network' => ['required', 'string', 'max:80'],
            'phone' => ['required', 'string', 'max:40'],
            'label' => ['nullable', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $helpDeskContact->update($validated);

        return back()->with('success', __('Help desk contact updated successfully.'));
    }

    public function destroy(HelpDeskContact $helpDeskContact)
    {
        $this->guard();

        $helpDeskContact->delete();

        return back()->with('success', __('Help desk contact removed.'));
    }
}
