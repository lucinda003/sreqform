<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_request_counters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedBigInteger('next_sequence');
            $table->timestamps();
        });

        $nextSequence = ((int) DB::table('service_requests')->max('id')) + 1;
        $now = now();

        DB::table('service_request_counters')->insert([
            'name' => 'service_requests',
            'next_sequence' => max(1, $nextSequence),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_request_counters');
    }
};
