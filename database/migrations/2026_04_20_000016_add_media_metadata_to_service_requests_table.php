<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table): void {
            if (! Schema::hasColumn('service_requests', 'description_photo_metadata')) {
                $table->json('description_photo_metadata')->nullable()->after('description_photos');
            }

            if (! Schema::hasColumn('service_requests', 'approved_signature_metadata')) {
                $table->json('approved_signature_metadata')->nullable()->after('approved_by_signature');
            }
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table): void {
            if (Schema::hasColumn('service_requests', 'description_photo_metadata')) {
                $table->dropColumn('description_photo_metadata');
            }

            if (Schema::hasColumn('service_requests', 'approved_signature_metadata')) {
                $table->dropColumn('approved_signature_metadata');
            }
        });
    }
};
