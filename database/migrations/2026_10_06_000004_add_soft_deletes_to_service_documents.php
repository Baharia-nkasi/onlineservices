<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_documents', function (Blueprint $table) {
            $table->softDeletes();
            $table->index(['service_id', 'is_active', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::table('service_documents', function (Blueprint $table) {
            $table->dropIndex(['service_id', 'is_active', 'deleted_at']);
            $table->dropSoftDeletes();
        });
    }
};
