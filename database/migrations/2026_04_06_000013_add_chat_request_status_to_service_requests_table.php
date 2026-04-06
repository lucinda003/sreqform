<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table): void {
            $table->string('contact_chat_status', 20)->nullable()->after('status');
            $table->timestamp('contact_chat_requested_at')->nullable()->after('contact_chat_status');
            $table->timestamp('contact_chat_decided_at')->nullable()->after('contact_chat_requested_at');
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table): void {
            $table->dropColumn([
                'contact_chat_status',
                'contact_chat_requested_at',
                'contact_chat_decided_at',
            ]);
        });
    }
};
