<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (Schema::hasColumn('service_requests', 'approved_by_signature')) {
            DB::statement('ALTER TABLE `service_requests` MODIFY `approved_by_signature` LONGTEXT NOT NULL');
        }

        if (Schema::hasColumn('service_requests', 'noted_by_signature')) {
            DB::statement('ALTER TABLE `service_requests` MODIFY `noted_by_signature` LONGTEXT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (Schema::hasColumn('service_requests', 'approved_by_signature')) {
            DB::statement('ALTER TABLE `service_requests` MODIFY `approved_by_signature` VARCHAR(255) NOT NULL');
        }

        if (Schema::hasColumn('service_requests', 'noted_by_signature')) {
            DB::statement('ALTER TABLE `service_requests` MODIFY `noted_by_signature` VARCHAR(255) NULL');
        }
    }
};
