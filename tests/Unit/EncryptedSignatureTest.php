<?php

namespace Tests\Unit;

use App\Support\EncryptedSignature;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EncryptedSignatureTest extends TestCase
{
    public function test_new_signatures_are_stored_on_private_local_disk(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $path = EncryptedSignature::storeBinary($this->pngBinary(), 'image/png');

        $this->assertStringStartsWith('service-request-signatures/', $path);
        $this->assertStringEndsWith('.encsig', $path);

        Storage::disk('local')->assertExists($path);
        Storage::disk('public')->assertMissing($path);

        $decoded = EncryptedSignature::readBinaryFromPath($path);

        $this->assertIsArray($decoded);
        $this->assertSame('image/png', $decoded['mime']);
        $this->assertNotSame('', $decoded['binary']);
    }

    public function test_legacy_public_signature_files_remain_readable_and_deletable(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $path = EncryptedSignature::storeBinary($this->pngBinary(), 'image/png');
        $payload = Storage::disk('local')->get($path);

        Storage::disk('local')->delete($path);
        Storage::disk('public')->put($path, $payload);

        $decoded = EncryptedSignature::readBinaryFromPath($path);

        $this->assertIsArray($decoded);
        $this->assertSame('image/png', $decoded['mime']);

        EncryptedSignature::deletePath($path);

        Storage::disk('local')->assertMissing($path);
        Storage::disk('public')->assertMissing($path);
    }

    private function pngBinary(): string
    {
        return (string) base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAFgwJ/lZ0f1wAAAABJRU5ErkJggg==',
            true
        );
    }
}
