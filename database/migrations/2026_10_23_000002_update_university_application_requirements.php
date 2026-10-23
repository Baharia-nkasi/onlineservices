<?php

use App\Models\Service;
use App\Models\ServiceDocument;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const SERVICE_SLUG = 'maombi-ya-chuo-kikuu';

    private const REQUIREMENTS = [
        ['name' => 'Kitambulisho cha Taifa au Namba ya Nida', 'required' => true],
        ['name' => 'Cheti Cha Form Four (Index Number)', 'required' => true],
        ['name' => 'Cheti Cha Form Six (Index Number)', 'required' => true],
        ['name' => 'Cheti cha Stashahada (Diploma Certificate)', 'required' => true],
        ['name' => 'Nambari ya Uthibitishaji wa Tuzo (AVN)', 'required' => true],
    ];

    private const PREVIOUS_REQUIREMENTS = [
        'Kitambulisho cha Taifa',
        'Cheti cha kuzaliwa',
        'Picha ya pasipoti',
        'Cheti au matokeo ya Kidato cha Nne',
        'Cheti au matokeo ya Kidato cha Sita',
        'Cheti cha Stashahada',
        'Nakala ya matokeo ya Stashahada',
        'Vyeti vingine vya kitaaluma',
        'Namba ya mtihani ya Kidato cha Nne',
        'Namba ya mtihani ya Kidato cha Sita',
        'Taarifa za mzazi au mlezi',
        'Nyaraka za uhamisho',
    ];

    public function up(): void
    {
        DB::transaction(function (): void {
            $service = Service::where('slug', self::SERVICE_SLUG)->first();

            if (! $service) {
                return;
            }

            $allowedNames = array_column(self::REQUIREMENTS, 'name');

            // Remove unexpected items from the active catalogue without
            // hard-deleting records that may be referenced by past applications.
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

            ServiceDocument::where('service_id', $service->id)
                ->whereIn('name', array_column(self::REQUIREMENTS, 'name'))
                ->get()
                ->each(function (ServiceDocument $document): void {
                    $document->is_active = false;
                    $document->save();
                    $document->delete();
                });

            foreach (self::PREVIOUS_REQUIREMENTS as $name) {
                $document = ServiceDocument::withTrashed()
                    ->where('service_id', $service->id)
                    ->where('name', $name)
                    ->first();

                if (! $document) {
                    continue;
                }

                if ($document->trashed()) {
                    $document->restore();
                }

                $document->is_active = true;
                $document->save();
            }
        });
    }
};
