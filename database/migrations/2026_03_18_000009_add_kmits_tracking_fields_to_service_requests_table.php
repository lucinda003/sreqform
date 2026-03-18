<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->time('time_received')->nullable()->after('kmits_date');
            $table->text('actions_taken')->nullable()->after('time_received');
            $table->json('action_logs')->nullable()->after('actions_taken');
            $table->string('noted_by_name')->nullable()->after('action_logs');
            $table->string('noted_by_position')->nullable()->after('noted_by_name');
            $table->date('noted_by_date_signed')->nullable()->after('noted_by_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn([
                'time_received',
                'actions_taken',
                'action_logs',
                'noted_by_name',
                'noted_by_position',
                'noted_by_date_signed',
            ]);
        });
    }
};
