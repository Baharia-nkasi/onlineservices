<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class ApplicationDocumentController extends Controller
{
    /**
     * Upload a document for an application.
     */
    public function store(Request $request, Application $application)
    {
        abort_unless(Auth::check() && $application->user_id === Auth::id(), 403);
        abort_if(in_array($application->status, ['completed', 'rejected'], true), 422, 'Documents cannot be changed after this application is completed or rejected.');

        $validated = $request->validate([
            'document_name' => ['required', 'string', 'max:255'],
            'document' => ['required', File::types(['pdf', 'jpg', 'jpeg', 'png'])->max(5 * 1024)],
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

        $file = $request->file('document');

        if ($existing && $existing->status !== 'rejected') {
            return back()->withErrors([
                'document_name' => 'This document has already been uploaded and is under review or approved.',
            ])->withInput();
        }

        $path = $file->store('application-documents', 'local');

        if ($existing) {
            Storage::disk('local')->delete($existing->file_path);

            $existing->update([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'status' => 'pending',
                'notes' => null,
            ]);
        } else {
            $application->documents()->create([
                'document_name' => $requirement->name,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'status' => 'pending',
            ]);
        }

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
        abort_unless(Auth::check() && $document->application->user_id === Auth::id(), 403);
        abort_if($document->status !== 'pending', 422, 'Only pending documents can be deleted.');
        abort_if(in_array($document->application->status, ['completed', 'rejected'], true), 422, 'Documents cannot be changed after this application is completed or rejected.');

        $applicationId = $document->application_id;

        Storage::disk('local')->delete($document->file_path);
        $document->delete();

        return redirect()
            ->route('customer.applications.show', $applicationId)
            ->with('success', 'Document deleted successfully.');
    }
}
