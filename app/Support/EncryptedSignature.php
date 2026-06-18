<?php

namespace App\Support;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EncryptedSignature
{
    private const DIRECTORY = 'service-request-signatures/';
    private const DISK = 'local';
    private const LEGACY_DISK = 'public';
    private const FORMAT_PREFIX = 'v2|';
    private const MAX_IMAGE_DIMENSION = 1000;
    private const JPEG_QUALITY = 82;
    private const PNG_COMPRESSION = 8;

    public static function storeBinary(string $binary, string $mimeType): string
    {
        [$binary] = self::optimizeImageBinary(
            $binary,
            $mimeType,
            self::MAX_IMAGE_DIMENSION,
            self::JPEG_QUALITY,
            self::PNG_COMPRESSION
        );

        $path = self::DIRECTORY . Str::uuid()->toString() . '.encsig';

        // Compact encrypted payload for new signatures. Store privately; read path keeps legacy public support.
        Storage::disk(self::DISK)->put($path, Crypt::encryptString(self::FORMAT_PREFIX . $binary));

        return $path;
    }

    public static function readBinaryFromPath(?string $signaturePath): ?array
    {
        $path = trim((string) $signaturePath);
        $diskName = self::diskContaining($path);
        if ($diskName === null) {
            return null;
        }

        $disk = Storage::disk($diskName);
        $raw = (string) $disk->get($path);

        if (str_ends_with(strtolower($path), '.encsig')) {
            try {
                $decrypted = Crypt::decryptString($raw);

                if (str_starts_with($decrypted, self::FORMAT_PREFIX)) {
                    $binary = substr($decrypted, strlen(self::FORMAT_PREFIX));
                    if ($binary === '') {
                        return null;
                    }

                    return [
                        'mime' => self::detectImageMime($binary),
                        'binary' => $binary,
                    ];
                }

                // Backward-compatible decoder for older JSON+base64 encrypted payloads.
                $decoded = json_decode($decrypted, true, 512, JSON_THROW_ON_ERROR);
                $mime = trim((string) ($decoded['mime'] ?? 'image/png'));
                $binary = base64_decode((string) ($decoded['data'] ?? ''), true);

                if ($binary === false || $binary === '') {
                    return null;
                }

                return [
                    'mime' => $mime !== '' ? $mime : 'image/png',
                    'binary' => $binary,
                ];
            } catch (\Throwable $exception) {
                return null;
            }
        }

        $mime = $disk->mimeType($path) ?: 'image/png';

        return [
            'mime' => $mime,
            'binary' => $raw,
        ];
    }

    public static function deletePath(?string $signaturePath): void
    {
        $path = trim((string) $signaturePath);

        if (! self::isSignaturePath($path)) {
            return;
        }

        foreach ([self::DISK, self::LEGACY_DISK] as $diskName) {
            $disk = Storage::disk($diskName);
            if ($disk->exists($path)) {
                $disk->delete($path);
            }
        }
    }

    public static function dataUriFromPath(?string $signaturePath): string
    {
        $decoded = self::readBinaryFromPath($signaturePath);
        if (! is_array($decoded)) {
            return '';
        }

        $mime = trim((string) ($decoded['mime'] ?? 'image/png'));
        $binary = (string) ($decoded['binary'] ?? '');

        if ($binary === '') {
            return '';
        }

        return 'data:' . ($mime !== '' ? $mime : 'image/png') . ';base64,' . base64_encode($binary);
    }

    private static function detectImageMime(string $binary): string
    {
        if (function_exists('finfo_open') && function_exists('finfo_buffer')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo !== false) {
                $mime = (string) (finfo_buffer($finfo, $binary) ?: '');

                if (str_starts_with($mime, 'image/')) {
                    return $mime;
                }
            }
        }

        if (function_exists('getimagesizefromstring')) {
            $imageInfo = @getimagesizefromstring($binary);
            if (is_array($imageInfo)) {
                $mime = trim((string) ($imageInfo['mime'] ?? ''));
                if ($mime !== '' && str_starts_with($mime, 'image/')) {
                    return $mime;
                }
            }
        }

        return 'image/png';
    }

    private static function diskContaining(string $path): ?string
    {
        if (! self::isSignaturePath($path)) {
            return null;
        }

        foreach ([self::DISK, self::LEGACY_DISK] as $diskName) {
            if (Storage::disk($diskName)->exists($path)) {
                return $diskName;
            }
        }

        return null;
    }

    public static function isSignaturePath(string $path): bool
    {
        $normalizedPath = trim(str_replace('\\', '/', $path));

        return $normalizedPath !== ''
            && str_starts_with($normalizedPath, self::DIRECTORY)
            && ! str_contains($normalizedPath, '..')
            && preg_match('/^service-request-signatures\/[0-9a-fA-F-]+\.encsig$/', $normalizedPath) === 1;
    }

    public static function optimizeImageBinary(
        string $binary,
        string $mimeType,
        int $maxImageDimension,
        int $jpegQuality,
        int $pngCompression
    ): array
    {
        if (! function_exists('imagecreatefromstring') || ! function_exists('imagecopyresampled')) {
            return [$binary, $mimeType];
        }

        $sourceImage = @imagecreatefromstring($binary);
        if ($sourceImage === false) {
            return [$binary, $mimeType];
        }

        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);
        if ($sourceWidth <= 0 || $sourceHeight <= 0) {
            imagedestroy($sourceImage);

            return [$binary, $mimeType];
        }

        $targetImage = $sourceImage;
        $longestSide = max($sourceWidth, $sourceHeight);
        $maxDimension = max(1, $maxImageDimension);

        if ($longestSide > $maxDimension) {
            $scale = $maxDimension / $longestSide;
            $targetWidth = max(1, (int) floor($sourceWidth * $scale));
            $targetHeight = max(1, (int) floor($sourceHeight * $scale));

            $resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);

            $transparent = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
            imagefill($resizedImage, 0, 0, $transparent);

            imagecopyresampled(
                $resizedImage,
                $sourceImage,
                0,
                0,
                0,
                0,
                $targetWidth,
                $targetHeight,
                $sourceWidth,
                $sourceHeight
            );

            imagedestroy($sourceImage);
            $targetImage = $resizedImage;
        }

        $normalizedMime = strtolower(trim($mimeType));
        $useJpeg = str_contains($normalizedMime, 'jpeg') || str_contains($normalizedMime, 'jpg');
        $safeJpegQuality = max(1, min(100, $jpegQuality));
        $safePngCompression = max(0, min(9, $pngCompression));

        ob_start();
        if ($useJpeg) {
            imagejpeg($targetImage, null, $safeJpegQuality);
            $outputMime = 'image/jpeg';
        } else {
            imagepng($targetImage, null, $safePngCompression);
            $outputMime = 'image/png';
        }
        $encoded = (string) ob_get_clean();

        imagedestroy($targetImage);

        if ($encoded === '') {
            return [$binary, $mimeType];
        }

        if (strlen($encoded) >= strlen($binary)) {
            return [$binary, $mimeType];
        }

        return [$encoded, $outputMime];
    }

    public static function optimizePhotoBinary(string $binary, string $mimeType): array
    {
        [$bestBinary, $bestMime] = self::optimizeImageBinary($binary, $mimeType, 1280, 75, 9);

        if (! function_exists('imagecreatefromstring') || ! function_exists('imagewebp')) {
            return [$bestBinary, $bestMime];
        }

        $image = @imagecreatefromstring($bestBinary);
        if ($image === false) {
            return [$bestBinary, $bestMime];
        }

        imagealphablending($image, true);
        imagesavealpha($image, true);

        ob_start();
        imagewebp($image, null, 80);
        $webpBinary = (string) ob_get_clean();
        imagedestroy($image);

        if ($webpBinary !== '' && strlen($webpBinary) < strlen($bestBinary)) {
            return [$webpBinary, 'image/webp'];
        }

        return [$bestBinary, $bestMime];
    }
}
