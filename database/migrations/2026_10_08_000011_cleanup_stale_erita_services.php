<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Permanently remove stale eRITA verification data and collapse the
     * legacy duplicate lost-certificate service into the canonical service.
     *
     * This is intentionally irreversible.
     */
    public function up(): void
    {
        $this->removeServiceBySlug('erita-kuhakiki-vyeti', [
            'erita-Kuhakiki-Vyeti',
        ]);

        $canonical = DB::table('services')
            ->where('slug', 'erita-nakala-cheti-kilichopotea')
            ->first();

        $legacy = DB::table('services')
            ->whereIn('slug', ['erita-nakala-Vyeti-VIlivyopotea'])
            ->first();

        if ($legacy) {
            $legacyApplicationIds = DB::table('applications')
                ->where('service_id', $legacy->id)
                ->pluck('id');

            $legacyFilePaths = $legacyApplicationIds->isEmpty()
                ? collect()
                : DB::table('application_documents')
                    ->whereIn('application_id', $legacyApplicationIds)
                    ->whereNotNull('file_path')
                    ->pluck('file_path')
                    ->filter(fn ($path) => is_string($path) && $path !== '');

            DB::transaction(function () use ($canonical, $legacy, $legacyApplicationIds) {
                if ($canonical && $legacyApplicationIds->isNotEmpty()) {
                    DB::table('applications')
                        ->whereIn('id', $legacyApplicationIds)
                        ->update(['service_id' => $canonical->id]);
                }

                DB::table('service_documents')
                    ->where('service_id', $legacy->id)
                    ->delete();

                DB::table('services')
                    ->where('id', $legacy->id)
                    ->delete();
            });

            foreach ($legacyFilePaths as $filePath) {
                if (! str_starts_with($filePath, 'database://')) {
                    Storage::disk('local')->delete($filePath);
                }
            }
        }
    }

    private function removeServiceBySlug(string $canonicalSlug, array $legacySlugs): void
    {
        $slugs = array_unique(array_merge([$canonicalSlug], $legacySlugs));

        foreach ($slugs as $slug) {
            $service = DB::table('services')->where('slug', $slug)->first();

            if (! $service) {
                continue;
            }

            $applicationIds = DB::table('applications')
                ->where('service_id', $service->id)
                ->pluck('id');

            $filePaths = $applicationIds->isEmpty()
                ? collect()
                : DB::table('application_documents')
                    ->whereIn('application_id', $applicationIds)
                    ->whereNotNull('file_path')
                    ->pluck('file_path')
                    ->filter(fn ($path) => is_string($path) && $path !== '');

            DB::transaction(function () use ($service, $applicationIds) {
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
                        DB::table('notifications')
                            ->whereIn('id', $notificationIds)
                            ->delete();
                    }

                    DB::table('application_documents')
                        ->whereIn('application_id', $applicationIds)
                        ->delete();

                    DB::table('applications')
                        ->whereIn('id', $applicationIds)
                        ->delete();
                }

                DB::table('service_documents')
                    ->where('service_id', $service->id)
                    ->delete();

                DB::table('services')
                    ->where('id', $service->id)
                    ->delete();
            });

            foreach ($filePaths as $filePath) {
                if (! str_starts_with($filePath, 'database://')) {
                    Storage::disk('local')->delete($filePath);
                }
            }
        }
    }

    public function down(): void
    {
        // Intentionally irreversible.
    }
};
