<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_documents', function (Blueprint $table) {
            $table->string('requirement_type')->default('single')->after('is_required');
            $table->string('requirement_group')->nullable()->after('requirement_type');
            $table->unsignedInteger('minimum_required')->default(1)->after('requirement_group');
        });
    }

    public function down(): void
    {
        Schema::table('service_documents', function (Blueprint $table) {
            $table->dropColumn(['requirement_type', 'requirement_group', 'minimum_required']);
        });
    }
};
