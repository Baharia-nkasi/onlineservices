<?php

use IlluminateDatabaseMigrationsMigration;
use IlluminateSupportFacadesDB;

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

        // The admin explicitly requested a clean removal of this retired
        // service, including its old application history.
        $applicationIds = DB::table('applications')
            ->where('service_id', $serviceId)
            ->pluck('id');

        if ($applicationIds->isNotEmpty()) {
            $notifications = DB::table('notifications')->get(['id', 'data']);

            $notificationIds = $notifications
                ->filter(function ($notification) use ($applicationIds) {
                    $data = json_decode((string) $notification->data, true);

                    return isset($data['application_id'])
                        && $applicationIds->contains((int) $data['application_id']);
                })
                ->pluck('id');

            if ($notificationIds->isNotEmpty()) {
                DB::table('notifications')->whereIn('id', $notificationIds)->delete();
            }
        }

        // application_documents cascade from applications; requirements are
        // exclusive to the service and can be removed with it.
        DB::table('applications')->where('service_id', $serviceId)->delete();
        DB::table('service_documents')->where('service_id', $serviceId)->delete();
        DB::table('services')->where('id', $serviceId)->delete();
    }

    public function down(): void
    {
        // Intentionally irreversible: the removed service and its application
        // history were explicitly requested to be permanently cleaned.
    }
};
