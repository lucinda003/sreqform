<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->string('request_category', 100)->nullable()->after('department_code');
            $table->string('application_system_name')->nullable()->after('request_category');
            $table->date('expected_completion_date')->nullable()->after('application_system_name');
            $table->time('expected_completion_time')->nullable()->after('expected_completion_date');
            $table->string('contact_suffix_name', 100)->nullable()->after('contact_middle_name');
            $table->string('email_address')->nullable()->after('mobile_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn([
                'request_category',
                'application_system_name',
                'expected_completion_date',
                'expected_completion_time',
                'contact_suffix_name',
                'email_address',
            ]);
        });
    }
};
