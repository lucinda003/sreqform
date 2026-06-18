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
        // Truncate existing data first
        DB::table('department_codes')->truncate();

        Schema::table('department_codes', function (Blueprint $table) {
            // Add office_name column for the full name
            $table->string('office_name', 255)->after('id');

            // Revert code back to varchar(30) — just for acronyms now
            $table->string('code', 30)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('department_codes', function (Blueprint $table) {
            $table->dropColumn('office_name');
            $table->string('code', 255)->nullable(false)->change();
        });
    }
};
