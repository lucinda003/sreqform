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
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code')->unique();
            $table->date('request_date');
            $table->string('department_code', 30);
            $table->string('contact_last_name');
            $table->string('contact_first_name');
            $table->string('contact_middle_name')->nullable();
            $table->string('office');
            $table->string('address');
            $table->string('landline')->nullable();
            $table->string('fax_no')->nullable();
            $table->string('mobile_no');
            $table->text('description_request');
            $table->string('approved_by_name');
            $table->string('approved_by_signature');
            $table->string('approved_by_position');
            $table->date('approved_date');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
