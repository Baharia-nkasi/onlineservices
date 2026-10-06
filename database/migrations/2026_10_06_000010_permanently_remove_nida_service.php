<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $serviceId = DB::table('services')
            ->where('slug', 'kitambulisho-cha-taifa-nida')
            ->value('id');

        if (! $serviceId) {
            return;
        }

        $applicationIds = DB::table('applications')
            ->where('service_id', $serviceId)
            ->pluck('id');

        // Notifications are Laravel database-notification payloads, so they
        // do not have a relational FK to applications.
        foreach ($applicationIds as $applicationId) {
            DB::table('notifications')
                ->where('data', 'like', '%"application_id":'.$applicationId.'%')
                ->delete();
        }

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
        // Intentionally irreversible. The NIDA service was explicitly
        // removed from the catalogue and its related application history.
    }
};
