<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table): void {
            if (! Schema::hasColumn('service_requests', 'noted_by_signature')) {
                $table->longText('noted_by_signature')->nullable()->after('noted_by_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table): void {
            if (Schema::hasColumn('service_requests', 'noted_by_signature')) {
                $table->dropColumn('noted_by_signature');
            }
        });
    }
};
