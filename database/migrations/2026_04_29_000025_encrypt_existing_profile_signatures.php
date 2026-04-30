<?php

use App\Support\EncryptedSignature;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'profile_signature')) {
            return;
        }

        DB::table('users')
            ->whereNotNull('profile_signature')
            ->select(['id', 'profile_signature'])
            ->orderBy('id')
            ->chunkById(100, function ($users): void {
                foreach ($users as $user) {
                    $signature = trim((string) ($user->profile_signature ?? ''));

                    if ($signature === '' || str_starts_with($signature, 'service-request-signatures/')) {
                        continue;
                    }

                    $decoded = $this->decodeImageDataUri($signature);
                    if (! is_array($decoded)) {
                        continue;
                    }

                    $path = EncryptedSignature::storeBinary(
                        (string) ($decoded['binary'] ?? ''),
                        (string) ($decoded['mime'] ?? 'image/png')
                    );

                    if ($path === '') {
                        continue;
                    }

                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['profile_signature' => $path]);
                }
            });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'profile_signature')) {
            return;
        }

        DB::table('users')
            ->whereNotNull('profile_signature')
            ->select(['id', 'profile_signature'])
            ->orderBy('id')
            ->chunkById(100, function ($users): void {
                foreach ($users as $user) {
                    $path = trim((string) ($user->profile_signature ?? ''));

                    if ($path === '' || ! str_starts_with($path, 'service-request-signatures/')) {
                        continue;
                    }

                    $dataUri = EncryptedSignature::dataUriFromPath($path);
                    if ($dataUri === '') {
                        continue;
                    }

                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['profile_signature' => $dataUri]);

                    Storage::disk('public')->delete($path);
                }
            });
    }

    private function decodeImageDataUri(?string $value): ?array
    {
        $raw = trim((string) $value);
        if ($raw === '') {
            return null;
        }

        if (preg_match('/^data:(image\/[a-zA-Z0-9.+-]+);base64,(.+)$/s', $raw, $matches) !== 1) {
            return null;
        }

        $binary = base64_decode((string) $matches[2], true);
        if ($binary === false || $binary === '') {
            return null;
        }

        return [
            'mime' => strtolower(trim((string) $matches[1])) ?: 'image/png',
            'binary' => $binary,
        ];
    }
};
