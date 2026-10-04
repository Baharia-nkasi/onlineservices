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
        // Make sure the application belongs to the logged-in customer
        abort_if($application->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'document_name' => [
                'required',
                'string',
                'max:255',
            ],

            'document' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        $allowed = $application->service->documents()
            ->where('is_active', true)
            ->pluck('name')
            ->map(fn ($name) => mb_strtolower(trim($name)))
            ->all();

        if ($allowed && ! in_array(mb_strtolower(trim($validated['document_name'])), $allowed, true)) {
            return back()->withErrors([
                'document_name' => 'Please select a document from the requirements for this service.',
            ])->withInput();
        }

        $file = $request->file('document');

        // Application documents are private and must never be exposed through /storage.
        $path = $file->store('application-documents', 'local');

        ApplicationDocument::create([
            'application_id' => $application->id,
            'document_name' => $validated['document_name'],
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
        // Make sure the document belongs to the logged-in customer's application
        abort_if(
            $document->application->user_id !== Auth::id(),
            403
        );

        Storage::disk('local')->delete($document->file_path);

        $document->delete();

        return redirect()
            ->route(
                'customer.applications.show',
                $document->application_id
            )
            ->with('success', 'Document deleted successfully.');
    }
}