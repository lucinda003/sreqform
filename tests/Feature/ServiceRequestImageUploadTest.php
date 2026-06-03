<?php

namespace Tests\Feature;

use App\Http\Controllers\ServiceRequestController;
use Illuminate\Http\UploadedFile;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Integration tests for service request image upload and signature validation.
 * 
 * Tests the complete flow of:
 * - Uploading images with validation
 * - Creating data URIs with validation
 * - Rejecting oversized/corrupted images
 * - Signature verification
 */
class ServiceRequestImageUploadTest extends TestCase
{
    /**
     * Test that valid image upload is accepted by the upload validator.
     */
    public function test_valid_image_upload_accepted(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'srs-valid-png-');
        $this->assertIsString($path);
        file_put_contents($path, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=',
            true
        ));

        $file = new UploadedFile($path, 'valid.png', 'image/png', null, true);

        $this->assertTrue($this->invokeImageUploadValidation($file));
    }

    /**
     * Test that oversized decoded data URIs are rejected.
     */
    public function test_oversized_image_rejected(): void
    {
        $binary = hex2bin('89504E470D0A1A0A') . str_repeat("\x00", 5242881);
        $dataUri = 'data:image/png;base64,' . base64_encode($binary);

        $this->assertNull($this->invokeDecodeImageDataUri($dataUri));
    }

    /**
     * Test that invalid MIME types are rejected by the upload validator.
     */
    public function test_invalid_mime_type_rejected(): void
    {
        $file = UploadedFile::fake()->create('shell.php', 1, 'application/x-php');

        $this->assertFalse($this->invokeImageUploadValidation($file));
    }

    /**
     * Test that drawn signature data URIs validate correctly.
     */
    public function test_drawn_signature_validation(): void
    {
        $binary = hex2bin('89504E470D0A1A0A') . str_repeat("\x00", 128);
        $dataUri = 'data:image/png;base64,' . base64_encode($binary);

        $decoded = $this->invokeDecodeImageDataUri($dataUri);

        $this->assertIsArray($decoded);
        $this->assertSame('image/png', $decoded['mime']);
        $this->assertSame($binary, $decoded['binary']);
    }

    /**
     * Test that malicious base64 payloads are rejected.
     */
    public function test_malicious_base64_rejected(): void
    {
        $payload = base64_encode('<?php echo "pwned";');
        $dataUri = 'data:image/png;base64,' . $payload;

        $this->assertNull($this->invokeDecodeImageDataUri($dataUri));
    }

    /**
     * Test that MIME spoofing is rejected.
     */
    public function test_spoofed_data_uri_rejected(): void
    {
        $jpegBinary = hex2bin('FFD8FFF0') . str_repeat("\x00", 128);
        $dataUri = 'data:image/png;base64,' . base64_encode($jpegBinary);

        $this->assertNull($this->invokeDecodeImageDataUri($dataUri));
    }

    private function invokeDecodeImageDataUri(string $dataUri): ?array
    {
        $method = new ReflectionMethod(ServiceRequestController::class, 'decodeImageDataUri');
        $method->setAccessible(true);

        return $method->invoke(new ServiceRequestController(), $dataUri);
    }

    private function invokeImageUploadValidation(mixed $file): bool
    {
        $method = new ReflectionMethod(ServiceRequestController::class, 'isValidImageUpload');
        $method->setAccessible(true);

        return (bool) $method->invoke(new ServiceRequestController(), $file);
    }
}
