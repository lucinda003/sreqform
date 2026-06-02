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
            $table->date('approved_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('service_requests')
            ->whereNull('approved_date')
            ->update(['approved_date' => now()->toDateString()]);

        Schema::table('service_requests', function (Blueprint $table): void {
            $table->date('approved_date')->nullable(false)->change();
        });
    }
};
