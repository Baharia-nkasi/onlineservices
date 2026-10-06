<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // This service already has customer application history, so it must
        // remain as a historical record instead of being hard-deleted.
        DB::table('services')
            ->where('slug', 'erita-uthibitisho-wa-cheti')
            ->update(['is_active' => false]);
    }

    public function down(): void
    {
        DB::table('services')
            ->where('slug', 'erita-uthibitisho-wa-cheti')
            ->update(['is_active' => true]);
    }
};
