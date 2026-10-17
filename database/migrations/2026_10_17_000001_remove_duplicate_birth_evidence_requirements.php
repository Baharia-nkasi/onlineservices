<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Older birth-certificate requirements overlapped with the newer
     * birth_evidence choice group. Keep the grouped versions for new
     * applications and soft-delete only the duplicate legacy entries.
     * Soft deletion preserves historical requirement snapshots.
     */
    public function up(): void
    {
        $duplicateIds = DB::table('service_documents as legacy')
            ->join('services as service', 'service.id', '=', 'legacy.service_id')
            ->where('service.slug', 'erita-cheti-cha-kuzaliwa')
            ->whereNull('legacy.deleted_at')
            ->whereNull('legacy.requirement_group')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('service_documents as grouped')
                    ->whereColumn('grouped.service_id', 'legacy.service_id')
                    ->whereRaw('LOWER(TRIM(grouped.name)) = LOWER(TRIM(legacy.name))')
                    ->where('grouped.requirement_group', 'birth_evidence')
                    ->where('grouped.is_active', true)
                    ->whereNull('grouped.deleted_at');
            })
            ->select('legacy.id');

        DB::table('service_documents')
            ->whereIn('id', $duplicateIds)
            ->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Intentionally irreversible: restoring these duplicates would make
        // the customer upload form show duplicate document choices again.
    }
};
