<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offices', function (Blueprint $table): void {
            $table->string('regcode', 10)->nullable()->after('name');
            $table->string('address', 255)->nullable()->after('regcode');
        });
    }

    public function down(): void
    {
        Schema::table('offices', function (Blueprint $table): void {
            $table->dropColumn(['regcode', 'address']);
        });
    }
};
