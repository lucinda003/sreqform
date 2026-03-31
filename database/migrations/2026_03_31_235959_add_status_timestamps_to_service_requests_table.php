<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table): void {
            $table->timestamp('pending_at')->nullable()->after('status');
            $table->timestamp('checking_at')->nullable()->after('pending_at');
            $table->timestamp('approved_at')->nullable()->after('checking_at');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->timestamp('completed_at')->nullable()->after('rejected_at');
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table): void {
            $table->dropColumn(['pending_at', 'checking_at', 'approved_at', 'rejected_at', 'completed_at']);
        });
    }
};
