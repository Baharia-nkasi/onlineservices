<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $serviceId = DB::table('services')
            ->where('slug', 'erita-uthibitisho-wa-cheti')
            ->value('id');

        if (! $serviceId) {
            return;
        }

        // Remove notifications that belong to applications of this service.
        // Use a simple text match because notification data is stored as the
        // Laravel database-notification payload, not a relational FK.
        $applicationIds = DB::table('applications')
            ->where('service_id', $serviceId)
            ->pluck('id');

        foreach ($applicationIds as $applicationId) {
            DB::table('notifications')
                ->where('data', 'like', '%"application_id":'.$applicationId.'%')
                ->delete();
        }

        // application_documents and service_documents have CASCADE FKs,
        // but removing the dependent rows explicitly keeps this migration
        // deterministic and leaves no orphaned records.
        if ($applicationIds->isNotEmpty()) {
            DB::table('application_documents')
                ->whereIn('application_id', $applicationIds)
                ->delete();

            DB::table('applications')
                ->whereIn('id', $applicationIds)
                ->delete();
        }

        DB::table('service_documents')
            ->where('service_id', $serviceId)
            ->delete();

        DB::table('services')
            ->where('id', $serviceId)
            ->delete();
    }

    public function down(): void
    {
        // Intentionally irreversible: the service and its application history
        // were explicitly requested to be permanently cleaned.
    }
};
