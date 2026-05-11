<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('service_requests', 'mobile_no')) {
            DB::statement('ALTER TABLE service_requests MODIFY mobile_no VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('service_requests', 'mobile_no')) {
            DB::statement("UPDATE service_requests SET mobile_no = '' WHERE mobile_no IS NULL");
            DB::statement("ALTER TABLE service_requests MODIFY mobile_no VARCHAR(255) NOT NULL DEFAULT ''");
        }
    }
};
