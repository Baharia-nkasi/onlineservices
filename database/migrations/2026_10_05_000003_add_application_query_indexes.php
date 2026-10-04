<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'applications_user_status_index');
            $table->index(['service_id', 'status'], 'applications_service_status_index');
        });

        Schema::table('application_documents', function (Blueprint $table) {
            $table->index(['application_id', 'status'], 'application_documents_application_status_index');
        });

        Schema::table('service_documents', function (Blueprint $table) {
            $table->index(['service_id', 'is_active', 'requirement_group'], 'service_documents_service_active_group_index');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('applications_user_status_index');
            $table->dropIndex('applications_service_status_index');
        });

        Schema::table('application_documents', function (Blueprint $table) {
            $table->dropIndex('application_documents_application_status_index');
        });

        Schema::table('service_documents', function (Blueprint $table) {
            $table->dropIndex('service_documents_service_active_group_index');
        });
    }
};
