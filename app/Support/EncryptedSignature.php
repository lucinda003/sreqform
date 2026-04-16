<?php

namespace App\Support;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EncryptedSignature
{
    private const DIRECTORY = 'service-request-signatures/';
    private const FORMAT_PREFIX = 'v2|';
    private const MAX_IMAGE_DIMENSION = 1000;
    private const JPEG_QUALITY = 82;
    private const PNG_COMPRESSION = 8;

    public static function storeBinary(string $binary, string $mimeType): string
    {
        [$binary] = self::optimizeBeforeEncryption($binary, $mimeType);

        $path = self::DIRECTORY . Str::uuid()->toString() . '.encsig';

        // Compact encrypted payload for new signatures (keeps backward compatibility in read path).
        Storage::disk('public')->put($path, Crypt::encryptString(self::FORMAT_PREFIX . $binary));

        return $path;
    }

    public static function readBinaryFromPath(?string $signaturePath): ?array
    {
        $path = trim((string) $signaturePath);
        if ($path === '' || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $raw = (string) Storage::disk('public')->get($path);

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

        $mime = Storage::disk('public')->mimeType($path) ?: 'image/png';

        return [
            'mime' => $mime,
            'binary' => $raw,
        ];
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

    private static function optimizeBeforeEncryption(string $binary, string $mimeType): array
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

        if ($longestSide > self::MAX_IMAGE_DIMENSION) {
            $scale = self::MAX_IMAGE_DIMENSION / $longestSide;
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

        ob_start();
        if ($useJpeg) {
            imagejpeg($targetImage, null, self::JPEG_QUALITY);
            $outputMime = 'image/jpeg';
        } else {
            imagepng($targetImage, null, self::PNG_COMPRESSION);
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
}
