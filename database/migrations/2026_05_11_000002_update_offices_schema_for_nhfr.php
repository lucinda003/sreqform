<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->dropUnique('offices_parent_name_name_unique');
        });

        DB::statement('ALTER TABLE offices MODIFY regcode VARCHAR(50) NULL');
        DB::statement('ALTER TABLE offices MODIFY address TEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE offices MODIFY regcode VARCHAR(10) NULL');
        DB::statement('ALTER TABLE offices MODIFY address VARCHAR(255) NULL');

        Schema::table('offices', function (Blueprint $table) {
            $table->unique(['parent_name', 'name'], 'offices_parent_name_name_unique');
        });
    }
};
