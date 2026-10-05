<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class ApplicationDocumentController extends Controller
{
    public function store(Request $request, Application $application)
    {
        abort_unless(Auth::check() && $application->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'document_name' => ['required', 'string', 'max:255'],
            'document' => ['required', File::types(['pdf', 'jpg', 'jpeg', 'png'])->max(5 * 1024)],
        ]);

        $documentName = trim($validated['document_name']);
        $file = $request->file('document');
        $fileContent = file_get_contents($file->getRealPath());

        if ($fileContent === false) {
            return back()->withErrors([
                'document' => __('The uploaded file could not be read. Please try again.'),
            ])->withInput();
        }

        $fileName = basename($file->getClientOriginalName());
        $fileName = preg_replace('/[\r\n"]+/', '', $fileName) ?: 'uploaded-document';
        $fileType = $file->getMimeType();
        $fileSize = $file->getSize();
        $storedPath = 'database://application-documents/'.bin2hex(random_bytes(16));

        try {
            $originalPath = null;

            DB::transaction(function () use (
                $application,
                $documentName,
                $fileName,
                $storedPath,
                $fileContent,
                $fileType,
                $fileSize,
                &$originalPath
            ) {
                $lockedApplication = Application::query()->lockForUpdate()->findOrFail($application->id);

                abort_unless($lockedApplication->user_id === Auth::id(), 403);
                abort_if(in_array($lockedApplication->status, ['completed', 'rejected'], true), 422,
                    __('Documents cannot be changed after this application is completed or rejected.'));

                $requirement = $lockedApplication->service->documents()
                    ->where('is_active', true)
                    ->whereRaw('LOWER(TRIM(name)) = ?', [mb_strtolower($documentName)])
                    ->lockForUpdate()
                    ->first();

                if (! $requirement) {
                    abort(422, __('Please select a valid document requirement for this service.'));
                }

                $existing = $lockedApplication->documents()
                    ->whereRaw('LOWER(document_name) = ?', [mb_strtolower($documentName)])
                    ->lockForUpdate()
                    ->first();

                if ($existing && $existing->status !== 'rejected' && $existing->hasAvailableFile()) {
                    abort(422, __('This document has already been uploaded and is under review or approved.'));
                }

                if ($existing) {
                    $originalPath = $existing->file_path;
                    $existing->update([
                        'file_name' => $fileName,
                        'file_path' => $storedPath,
                        'file_content' => null,
                        'file_type' => $fileType,
                        'file_size' => $fileSize,
                        'status' => 'pending',
                        'notes' => null,
                    ]);
                    $this->storeBinaryContent($existing, $fileContent);
                } else {
                    $newDocument = $lockedApplication->documents()->create([
                        'document_name' => $requirement->name,
                        'file_name' => $fileName,
                        'file_path' => $storedPath,
                        'file_content' => null,
                        'file_type' => $fileType,
                        'file_size' => $fileSize,
                        'status' => 'pending',
                    ]);
                    $this->storeBinaryContent($newDocument, $fileContent);
                }
            });

            if ($originalPath && ! str_starts_with($originalPath, 'database://')) {
                Storage::disk('local')->delete($originalPath);
            }
        } catch (QueryException $exception) {
            if ($exception->getCode() === '23505') {
                return back()->withErrors([
                    'document_name' => __('This document has already been uploaded. Please refresh the application and try again.'),
                ])->withInput();
            }

            report($exception);
            return back()->withErrors([
                'document' => __('The document could not be saved. Please try again.'),
            ])->withInput();
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withErrors([
                'document' => __('The document could not be saved. Please try again.'),
            ])->withInput();
        }

        return redirect()->route('customer.applications.show', $application)
            ->with('success', __('Document uploaded successfully.'));
    }

    /**
     * Inline viewing for both the owning customer and an authorized admin.
     */
    public function view(ApplicationDocument $document)
    {
        return $this->serve($document, false);
    }

    /**
     * Forced download for both the owning customer and an authorized admin.
     */
    public function download(ApplicationDocument $document)
    {
        return $this->serve($document, true);
    }

    private function storeBinaryContent(ApplicationDocument $document, string $content): void
    {
        $pdo = DB::connection()->getPdo();
        $statement = $pdo->prepare('UPDATE application_documents SET file_content = ? WHERE id = ?');
        $statement->bindValue(1, $content, \PDO::PARAM_LOB);
        $statement->bindValue(2, $document->getKey(), \PDO::PARAM_INT);
        $statement->execute();
    }

    private function serve(ApplicationDocument $document, bool $download)
    {
        $application = $document->application;
        $user = Auth::user();

        abort_unless($user && ($application->user_id === $user->id || $user->isAdmin()), 403);

        $fileName = preg_replace('/[\r\n"]+/', '', basename($document->file_name)) ?: 'document';
        $contentType = $document->file_type ?: 'application/octet-stream';
        $disposition = $download ? 'attachment' : 'inline';

        $fileContent = $document->binaryContent();

        if ($fileContent !== null) {
            return response($fileContent, 200, [
                'Content-Type' => $contentType,
                'Content-Length' => (string) strlen($fileContent),
                'Content-Disposition' => $disposition.'; filename="'.$fileName.'"',
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
                    'Content-Disposition' => $disposition,
                    'X-Content-Type-Options' => 'nosniff',
                    'Cache-Control' => 'private, no-store',
                ]
            );
        }

        $route = $user->isAdmin() ? 'admin.applications.show' : 'customer.applications.show';

        return redirect()->route($route, $application)->withErrors([
            'document' => __('This uploaded file is no longer available on the server. Please upload the document again.'),
        ]);
    }

    public function destroy(ApplicationDocument $document)
    {
        abort_unless(Auth::check() && $document->application->user_id === Auth::id(), 403);

        $applicationId = $document->application_id;
        $oldPath = null;

        DB::transaction(function () use ($document, &$oldPath) {
            $lockedApplication = Application::query()->lockForUpdate()->findOrFail($document->application_id);
            $lockedDocument = $lockedApplication->documents()->lockForUpdate()->findOrFail($document->id);

            abort_unless($lockedApplication->user_id === Auth::id(), 403);
            abort_if($lockedDocument->status !== 'pending', 422, __('Only pending documents can be deleted.'));
            abort_if(in_array($lockedApplication->status, ['completed', 'rejected'], true), 422,
                __('Documents cannot be changed after this application is completed or rejected.'));

            $oldPath = $lockedDocument->file_path;
            $lockedDocument->delete();
        });

        if ($oldPath && ! str_starts_with($oldPath, 'database://')) {
            Storage::disk('local')->delete($oldPath);
        }

        return redirect()->route('customer.applications.show', $applicationId)
            ->with('success', __('Document deleted successfully.'));
    }
}
