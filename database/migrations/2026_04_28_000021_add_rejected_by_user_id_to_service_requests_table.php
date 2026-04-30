<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->foreignId('rejected_by_user_id')->nullable()->after('approved_by_user_id')->constrained('users')->nullOnDelete();
        });

        // Backfill: For requests already rejected, set rejected_by_user_id to the request creator (user_id)
        // This preserves the audit trail for historical rejected requests
        DB::table('service_requests')
            ->where('status', 'rejected')
            ->whereNull('rejected_by_user_id')
            ->whereNotNull('user_id')
            ->update(['rejected_by_user_id' => DB::raw('user_id')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['rejected_by_user_id']);
            $table->dropColumn('rejected_by_user_id');
        });
    }
};
