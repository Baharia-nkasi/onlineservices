<?php

use App\\Models\\Service;
use App\\Models\\ServiceDocument;
use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Support\\Facades\\DB;

return new class extends Migration
{
    private const SERVICE_SLUG = 'erita-nakala-cheti-kilichopotea';

    private const REQUIREMENTS = [
        ['name' => 'Cheti cha Zamani', 'required' => true],
        ['name' => 'Kitambulisho cha Taifa', 'required' => true],
        ['name' => 'Kitambulisho cha Mpiga Kura', 'required' => false],
        ['name' => 'Barua ya Utambulisho kutoka kwa Afisa Mtendaji', 'required' => true],
        ['name' => 'Cheti cha Kumaliza Shule (Leaving School)', 'required' => true],
    ];

    public function up(): void
    {
        DB::transaction(function (): void {
            $service = Service::where('slug', self::SERVICE_SLUG)->first();

            if (! $service) {
                return;
            }

            $allowedNames = array_column(self::REQUIREMENTS, 'name');

            // Retire and soft-delete every old or unexpected catalogue item.
            // Existing application records remain intact for audit/history.
            ServiceDocument::withTrashed()
                ->where('service_id', $service->id)
                ->whereNotIn('name', $allowedNames)
                ->get()
                ->each(function (ServiceDocument $document): void {
                    if ($document->is_active) {
                        $document->is_active = false;
                        $document->save();
                    }

                    if (! $document->trashed()) {
                        $document->delete();
                    }
                });

            foreach (self::REQUIREMENTS as $index => $requirement) {
                $document = ServiceDocument::withTrashed()->firstOrNew([
                    'service_id' => $service->id,
                    'name' => $requirement['name'],
                ]);

                if ($document->exists && $document->trashed()) {
                    $document->restore();
                }

                $document->fill([
                    'description' => null,
                    'is_required' => $requirement['required'],
                    'requirement_type' => $requirement['required'] ? 'required' : 'optional',
                    'requirement_group' => null,
                    'minimum_required' => 1,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);
                $document->save();
            }
        });
    }

    public function down(): void
    {
        DB::transaction(function (): void {
            $service = Service::where('slug', self::SERVICE_SLUG)->first();

            if (! $service) {
                return;
            }

            // Remove the four requirements introduced by this migration and
            // restore the prior catalogue entries for rollback. Keep NIDA,
            // which existed before this migration, in place.
            ServiceDocument::where('service_id', $service->id)
                ->whereIn('name', [
                    'Cheti cha Zamani',
                    'Kitambulisho cha Mpiga Kura',
                    'Barua ya Utambulisho kutoka kwa Afisa Mtendaji',
                    'Cheti cha Kumaliza Shule (Leaving School)',
                ])
                ->get()
                ->each(function (ServiceDocument $document): void {
                    $document->is_active = false;
                    $document->save();
                    $document->delete();
                });

            ServiceDocument::withTrashed()
                ->where('service_id', $service->id)
                ->whereIn('name', [
                    'Namba ya cheti cha zamani',
                    'Nakala ya cheti',
                    'Ripoti ya polisi',
                    'Cheti kilichoharibika',
                    'Nyaraka zinazothibitisha taarifa za cheti',
                    'Taarifa za kuzaliwa au kifo',
                    'Namba ya simu',
                ])
                ->get()
                ->each(function (ServiceDocument $document): void {
                    if ($document->trashed()) {
                        $document->restore();
                    }

                    $document->is_active = true;
                    $document->save();
                });
        });
    }
};
