<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offices', function (Blueprint $table) {
            if (! Schema::hasColumn('offices', 'parent_name')) {
                $table->string('parent_name')->nullable()->after('id');
            }
            if (! Schema::hasColumn('offices', 'regcode')) {
                $table->string('regcode')->nullable()->after('parent_name');
            }
            if (! Schema::hasColumn('offices', 'address')) {
                $table->text('address')->nullable()->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('offices', function (Blueprint $table) {
            if (Schema::hasColumn('offices', 'parent_name')) {
                $table->dropColumn('parent_name');
            }
            if (Schema::hasColumn('offices', 'regcode')) {
                $table->dropColumn('regcode');
            }
            if (Schema::hasColumn('offices', 'address')) {
                $table->dropColumn('address');
            }
        });
    }
};
