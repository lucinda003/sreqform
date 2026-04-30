<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('service_requests', 'received_by_user_id')) {
                $table
                    ->foreignId('received_by_user_id')
                    ->nullable()
                    ->after('assigned_by_user_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('service_requests', 'received_at')) {
                $table->timestamp('received_at')->nullable()->after('received_by_user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            if (Schema::hasColumn('service_requests', 'received_by_user_id')) {
                $table->dropForeignKeyIfExists(['received_by_user_id']);
                $table->dropColumn('received_by_user_id');
            }

            if (Schema::hasColumn('service_requests', 'received_at')) {
                $table->dropColumn('received_at');
            }
        });
    }
};
