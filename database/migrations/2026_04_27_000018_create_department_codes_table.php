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
        Schema::create('department_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->timestamps();
        });

        $now = now();

        $seedCodes = DB::table('users')
            ->whereNotNull('department')
            ->pluck('department')
            ->map(fn (mixed $department): string => strtoupper(trim((string) $department)))
            ->filter(fn (string $department): bool => $department !== '')
            ->unique()
            ->values();

        if ($seedCodes->isNotEmpty()) {
            DB::table('department_codes')->insert(
                $seedCodes
                    ->map(fn (string $code): array => [
                        'code' => $code,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])
                    ->all()
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_codes');
    }
};
