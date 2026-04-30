<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table): void {
            $table->foreignId('approved_by_user_id')
                ->nullable()
                ->after('approved_by_position')
                ->constrained('users')
                ->nullOnDelete();
        });

        DB::table('service_requests')
            ->where('status', 'approved')
            ->whereNull('approved_by_user_id')
            ->whereNotNull('user_id')
            ->update(['approved_by_user_id' => DB::raw('user_id')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('approved_by_user_id');
        });
    }
};
