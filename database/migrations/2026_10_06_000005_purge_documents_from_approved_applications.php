<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Approved applications no longer retain customer-uploaded files.
     * This also cleans records that were approved before the new workflow
     * was introduced.
     */
    public function up(): void
    {
        $documents = DB::table('application_documents')
            ->join('applications', 'applications.id', '=', 'application_documents.application_id')
            ->where('applications.status', 'approved')
            ->get([
                'application_documents.id',
                'application_documents.file_path',
            ]);

        DB::transaction(function () use ($documents) {
            if ($documents->isEmpty()) {
                return;
            }

            DB::table('application_documents')
                ->whereIn('id', $documents->pluck('id')->all())
                ->delete();
        });

        foreach ($documents as $document) {
            $path = $document->file_path;

            if ($path && ! str_starts_with($path, 'database://')) {
                Storage::disk('local')->delete($path);
            }
        }
    }

    /**
     * The cleanup is intentionally irreversible because approved uploads
     * are no longer part of the application's retained document record.
     */
    public function down(): void
    {
        // Nothing to restore: the uploaded document content was intentionally purged.
    }
};
