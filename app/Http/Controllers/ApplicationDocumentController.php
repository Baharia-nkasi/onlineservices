<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationDocumentController extends Controller
{
    /**
     * Upload a document for an application.
     */
    public function store(Request $request, Application $application)
    {
        abort_if($application->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'document_name' => ['required', 'string', 'max:255'],
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $documentName = trim($validated['document_name']);

        $requirement = $application->service->documents()
            ->where('is_active', true)
            ->get()
            ->first(fn ($document) => mb_strtolower(trim($document->name)) === mb_strtolower($documentName));

        if (! $requirement) {
            return back()->withErrors([
                'document_name' => 'Please select a valid document requirement for this service.',
            ])->withInput();
        }

        $existing = $application->documents()
            ->whereRaw('LOWER(document_name) = ?', [mb_strtolower($documentName)])
            ->first();

        if ($existing) {
            return back()->withErrors([
                'document_name' => 'This document has already been uploaded. Delete the existing file before uploading a new one.',
            ])->withInput();
        }

        $file = $request->file('document');
        $path = $file->store('application-documents', 'local');

        $application->documents()->create([
            'document_name' => $requirement->name,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'status' => 'pending',
        ]);

        return redirect()
            ->route('customer.applications.show', $application)
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * View an uploaded document only when the current user is authorized.
     */
    public function download(ApplicationDocument $document)
    {
        $application = $document->application;
        $user = Auth::user();

        abort_unless(
            $user && ($application->user_id === $user->id || $user->isAdmin()),
            403
        );

        abort_unless(Storage::disk('local')->exists($document->file_path), 404);

        return Storage::disk('local')->response(
            $document->file_path,
            $document->file_name,
            ['Content-Disposition' => 'inline']
        );
    }

    /**
     * Delete an uploaded document.
     */
    public function destroy(ApplicationDocument $document)
    {
        abort_if($document->application->user_id !== Auth::id(), 403);

        $applicationId = $document->application_id;

        Storage::disk('local')->delete($document->file_path);
        $document->delete();

        return redirect()
            ->route('customer.applications.show', $applicationId)
            ->with('success', 'Document deleted successfully.');
    }
}
