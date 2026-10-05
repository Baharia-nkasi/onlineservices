<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class ApplicationDocumentController extends Controller
{
    /**
     * Upload a document for an application.
     *
     * Application files are stored in PostgreSQL so they survive Render
     * container restarts and deployments. The 5 MB upload limit keeps this
     * suitable for the current document workflow.
     */
    public function store(Request $request, Application $application)
    {
        abort_unless(Auth::check() && $application->user_id === Auth::id(), 403);
        abort_if(
            in_array($application->status, ['completed', 'rejected'], true),
            422,
            'Documents cannot be changed after this application is completed or rejected.'
        );

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

        if ($existing && $existing->status !== 'rejected' && $existing->hasAvailableFile()) {
            return back()->withErrors([
                'document_name' => 'This document has already been uploaded and is under review or approved.',
            ])->withInput();
        }

        $fileName = basename($file->getClientOriginalName());
        $fileName = preg_replace('/[\\r\\n"]+/', '', $fileName) ?: 'uploaded-document';
        $fileType = $file->getMimeType();
        $fileSize = $file->getSize();
        $fileContent = file_get_contents($file->getRealPath());

        if ($fileContent === false) {
            return back()->withErrors([
                'document' => 'The uploaded file could not be read. Please try again.',
            ])->withInput();
        }

        $storedPath = 'database://application-documents/'.bin2hex(random_bytes(16));

        try {
            if ($existing) {
                $originalPath = $existing->file_path;

                $existing->update([
                    'file_name' => $fileName,
                    'file_path' => $storedPath,
                    'file_content' => $fileContent,
                    'file_type' => $fileType,
                    'file_size' => $fileSize,
                    'status' => 'pending',
                    'notes' => null,
                ]);

                if ($originalPath && ! str_starts_with($originalPath, 'database://')) {
                    Storage::disk('local')->delete($originalPath);
                }
            } else {
                $application->documents()->create([
                    'document_name' => $requirement->name,
                    'file_name' => $fileName,
                    'file_path' => $storedPath,
                    'file_content' => $fileContent,
                    'file_type' => $fileType,
                    'file_size' => $fileSize,
                    'status' => 'pending',
                ]);
            }
        } catch (QueryException $exception) {
            if ($exception->getCode() === '23505') {
                return back()->withErrors([
                    'document_name' => 'This document has already been uploaded. Please refresh the application and try again.',
                ])->withInput();
            }

            throw $exception;
        }

        return redirect()
            ->route('customer.applications.show', $application)
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * View an uploaded document only when the current user is authorized.
     *
     * New uploads are served directly from PostgreSQL. Legacy uploads are
     * still supported when their old local file is available.
     */
    public function download(ApplicationDocument $document)
    {
        $application = $document->application;
        $user = Auth::user();

        abort_unless(
            $user && ($application->user_id === $user->id || $user->isAdmin()),
            403
        );

        $fileName = preg_replace('/[\\r\\n"]+/', '', basename($document->file_name)) ?: 'document';
        $contentType = $document->file_type ?: 'application/octet-stream';

        if ($document->file_content !== null) {
            return response($document->file_content, 200, [
                'Content-Type' => $contentType,
                'Content-Length' => (string) strlen($document->file_content),
                'Content-Disposition' => 'inline; filename="'.$fileName.'"',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store',
            ]);
        }

        if ($document->file_path && Storage::disk('local')->exists($document->file_path)) {
            return Storage::disk('local')->response(
                $document->file_path,
                $fileName,
                [
                    'Content-Type' => $contentType,
                    'Content-Disposition' => 'inline',
                    'X-Content-Type-Options' => 'nosniff',
                    'Cache-Control' => 'private, no-store',
                ]
            );
        }

        return redirect()
            ->route(
                $user->isAdmin() ? 'admin.applications.show' : 'customer.applications.show',
                $application
            )
            ->withErrors([
                'document' => 'This uploaded file is no longer available on the server. Please upload the document again.',
            ]);
    }

    /**
     * Delete an uploaded document.
     */
    public function destroy(ApplicationDocument $document)
    {
        abort_unless(Auth::check() && $document->application->user_id === Auth::id(), 403);
        abort_if($document->status !== 'pending', 422, 'Only pending documents can be deleted.');
        abort_if(
            in_array($document->application->status, ['completed', 'rejected'], true),
            422,
            'Documents cannot be changed after this application is completed or rejected.'
        );

        $applicationId = $document->application_id;

        if ($document->file_path && ! str_starts_with($document->file_path, 'database://')) {
            Storage::disk('local')->delete($document->file_path);
        }

        $document->delete();

        return redirect()
            ->route('customer.applications.show', $applicationId)
            ->with('success', 'Document deleted successfully.');
    }
}
