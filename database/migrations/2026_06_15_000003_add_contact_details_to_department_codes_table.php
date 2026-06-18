<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('department_codes', function (Blueprint $table) {
            if (!Schema::hasColumn('department_codes', 'address')) {
                $table->text('address')->nullable()->after('office_name');
            }
            if (!Schema::hasColumn('department_codes', 'landline')) {
                $table->string('landline', 50)->nullable()->after('address');
            }
            if (!Schema::hasColumn('department_codes', 'mobile')) {
                $table->string('mobile', 50)->nullable()->after('landline');
            }
        });
    }

    public function down(): void
    {
        Schema::table('department_codes', function (Blueprint $table) {
            $table->dropColumn(['address', 'landline', 'mobile']);
        });
    }
};
