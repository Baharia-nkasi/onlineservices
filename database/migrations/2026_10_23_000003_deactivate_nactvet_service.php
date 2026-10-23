<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Support\\Facades\\DB;

return new class extends Migration
{
    /**
     * Hide NACTVET from the public catalogue while preserving any application
     * history that may reference this service.
     */
    public function up(): void
    {
        DB::table('services')
            ->where('slug', 'huduma-za-nactvet')
            ->update([
                'is_active' => false,
                'updated_at' => now(),
            ]);
    }

    /**
     * Intentionally do not reactivate the service on rollback. An administrator
     * should make the explicit decision to reintroduce it.
     */
    public function down(): void
    {
        // No automatic reactivation.
    }
};
