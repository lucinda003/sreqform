<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('service_requests', 'assigned_by_user_id')) {
                $table
                    ->foreignId('assigned_by_user_id')
                    ->nullable()
                    ->after('assigned_to_user_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            if (Schema::hasColumn('service_requests', 'assigned_by_user_id')) {
                $table->dropForeignKeyIfExists(['assigned_by_user_id']);
                $table->dropColumn('assigned_by_user_id');
            }
        });
    }
};
