<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offices', function (Blueprint $table): void {
            $table->string('parent_name')->default('DOH CENTRAL OFFICE')->after('id');
            $table->dropUnique('offices_name_unique');
            $table->unique(['parent_name', 'name'], 'offices_parent_name_name_unique');
        });
    }

    public function down(): void
    {
        Schema::table('offices', function (Blueprint $table): void {
            $table->dropUnique('offices_parent_name_name_unique');
            $table->unique('name');
            $table->dropColumn('parent_name');
        });
    }
};
