<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offices', function (Blueprint $table): void {
            if (! Schema::hasColumn('offices', 'licensing_status')) {
                $table->string('licensing_status')->nullable()->after('address');
            }
            if (! Schema::hasColumn('offices', 'license_date')) {
                $table->date('license_date')->nullable()->after('licensing_status');
            }
            if (! Schema::hasColumn('offices', 'facility_type')) {
                $table->string('facility_type')->nullable()->after('license_date');
            }
            if (! Schema::hasColumn('offices', 'classification')) {
                $table->string('classification')->nullable()->after('facility_type');
            }
            if (! Schema::hasColumn('offices', 'street')) {
                $table->string('street')->nullable()->after('classification');
            }
            if (! Schema::hasColumn('offices', 'building')) {
                $table->string('building')->nullable()->after('street');
            }
            if (! Schema::hasColumn('offices', 'region')) {
                $table->string('region')->nullable()->after('building');
            }
            if (! Schema::hasColumn('offices', 'province')) {
                $table->string('province')->nullable()->after('region');
            }
            if (! Schema::hasColumn('offices', 'city')) {
                $table->string('city')->nullable()->after('province');
            }
            if (! Schema::hasColumn('offices', 'barangay')) {
                $table->string('barangay')->nullable()->after('city');
            }
            if (! Schema::hasColumn('offices', 'phone')) {
                $table->string('phone')->nullable()->after('barangay');
            }
        });
    }

    public function down(): void
    {
        Schema::table('offices', function (Blueprint $table): void {
            foreach ([
                'phone',
                'barangay',
                'city',
                'province',
                'region',
                'building',
                'street',
                'classification',
                'facility_type',
                'license_date',
                'licensing_status',
            ] as $column) {
                if (Schema::hasColumn('offices', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
