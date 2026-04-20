<?php

use App\Models\ServiceRequest;
use App\Support\EncryptedSignature;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('media:scan-orphans {--json : Output machine-readable JSON summary}', function (): void {
    $disk = Storage::disk('public');

    $referencedPhotoPaths = [];
    $referencedSignaturePaths = [];
    $missingPhotoReferences = [];
    $missingSignatureReferences = [];

    ServiceRequest::query()
        ->select(['id', 'reference_code', 'description_photos', 'approved_by_signature'])
        ->orderBy('id')
        ->chunkById(200, function ($requests) use (
            $disk,
            &$referencedPhotoPaths,
            &$referencedSignaturePaths,
            &$missingPhotoReferences,
            &$missingSignatureReferences
        ): void {
            foreach ($requests as $serviceRequest) {
                $signaturePath = trim((string) ($serviceRequest->approved_by_signature ?? ''));
                if ($signaturePath !== '') {
                    $referencedSignaturePaths[$signaturePath] = true;

                    if (! $disk->exists($signaturePath)) {
                        $missingSignatureReferences[] = [
                            'service_request_id' => (int) $serviceRequest->id,
                            'reference_code' => (string) $serviceRequest->reference_code,
                            'path' => $signaturePath,
                        ];
                    }
                }

                foreach ((array) $serviceRequest->description_photos as $photoPathValue) {
                    $photoPath = trim((string) $photoPathValue);
                    if ($photoPath === '') {
                        continue;
                    }

                    $referencedPhotoPaths[$photoPath] = true;

                    if (! $disk->exists($photoPath)) {
                        $missingPhotoReferences[] = [
                            'service_request_id' => (int) $serviceRequest->id,
                            'reference_code' => (string) $serviceRequest->reference_code,
                            'path' => $photoPath,
                        ];
                    }
                }
            }
        });

    $storedPhotoFiles = [];
    $storedSignatureFiles = [];

    try {
        $storedPhotoFiles = $disk->allFiles('service-request-photos');
    } catch (\Throwable $exception) {
        $storedPhotoFiles = [];
    }

    try {
        $storedSignatureFiles = $disk->allFiles('service-request-signatures');
    } catch (\Throwable $exception) {
        $storedSignatureFiles = [];
    }

    $orphanPhotoFiles = array_values(array_filter(
        $storedPhotoFiles,
        static fn (string $path): bool => ! isset($referencedPhotoPaths[$path])
    ));
    $orphanSignatureFiles = array_values(array_filter(
        $storedSignatureFiles,
        static fn (string $path): bool => ! isset($referencedSignaturePaths[$path])
    ));

    $summary = [
        'referenced_photo_paths' => count($referencedPhotoPaths),
        'referenced_signature_paths' => count($referencedSignaturePaths),
        'stored_photo_files' => count($storedPhotoFiles),
        'stored_signature_files' => count($storedSignatureFiles),
        'missing_photo_references' => count($missingPhotoReferences),
        'missing_signature_references' => count($missingSignatureReferences),
        'orphan_photo_files' => count($orphanPhotoFiles),
        'orphan_signature_files' => count($orphanSignatureFiles),
    ];

    if ((bool) $this->option('json')) {
        $this->line((string) json_encode([
            'summary' => $summary,
            'missing_photo_references' => $missingPhotoReferences,
            'missing_signature_references' => $missingSignatureReferences,
            'orphan_photo_files' => $orphanPhotoFiles,
            'orphan_signature_files' => $orphanSignatureFiles,
        ], JSON_PRETTY_PRINT));

        return;
    }

    $this->info('Media orphan scan complete.');
    $this->table(
        ['Metric', 'Count'],
        [
            ['Referenced photo paths', $summary['referenced_photo_paths']],
            ['Referenced signature paths', $summary['referenced_signature_paths']],
            ['Stored photo files', $summary['stored_photo_files']],
            ['Stored signature files', $summary['stored_signature_files']],
            ['Missing photo references', $summary['missing_photo_references']],
            ['Missing signature references', $summary['missing_signature_references']],
            ['Orphan photo files', $summary['orphan_photo_files']],
            ['Orphan signature files', $summary['orphan_signature_files']],
        ]
    );

    if ($missingPhotoReferences !== []) {
        $this->warn('Sample missing photo references:');
        $this->table(
            ['Service Request ID', 'Reference Code', 'Path'],
            array_slice($missingPhotoReferences, 0, 10)
        );
    }

    if ($missingSignatureReferences !== []) {
        $this->warn('Sample missing signature references:');
        $this->table(
            ['Service Request ID', 'Reference Code', 'Path'],
            array_slice($missingSignatureReferences, 0, 10)
        );
    }

    if ($orphanPhotoFiles !== []) {
        $this->warn('Sample orphan photo files:');
        $this->table(
            ['Path'],
            array_map(static fn (string $path): array => [$path], array_slice($orphanPhotoFiles, 0, 10))
        );
    }

    if ($orphanSignatureFiles !== []) {
        $this->warn('Sample orphan signature files:');
        $this->table(
            ['Path'],
            array_map(static fn (string $path): array => [$path], array_slice($orphanSignatureFiles, 0, 10))
        );
    }
})->purpose('Scan service request photos/signatures for missing references and orphan files');

Artisan::command('media:backfill-metadata {--dry-run : Preview only, do not update records}', function (): void {
    if (! Schema::hasColumn('service_requests', 'description_photo_metadata')
        || ! Schema::hasColumn('service_requests', 'approved_signature_metadata')) {
        $this->error('Metadata columns are missing. Run migrations first.');

        return;
    }

    $disk = Storage::disk('public');
    $updated = 0;
    $processed = 0;
    $dryRun = (bool) $this->option('dry-run');

    ServiceRequest::query()
        ->select(['id', 'description_photos', 'approved_by_signature', 'description_photo_metadata', 'approved_signature_metadata'])
        ->orderBy('id')
        ->chunkById(200, function ($requests) use (&$updated, &$processed, $dryRun, $disk): void {
            foreach ($requests as $serviceRequest) {
                $processed++;

                $descriptionMetadata = null;
                $photoPaths = array_values(array_filter(
                    array_map(static fn ($path): string => trim((string) $path), (array) $serviceRequest->description_photos),
                    static fn (string $path): bool => $path !== ''
                ));

                if ($photoPaths !== []) {
                    $descriptionMetadata = [];

                    foreach ($photoPaths as $path) {
                        if (! $disk->exists($path)) {
                            $descriptionMetadata[] = [
                                'path' => $path,
                                'exists' => false,
                            ];

                            continue;
                        }

                        $binary = (string) $disk->get($path);
                        $descriptionMetadata[] = [
                            'path' => $path,
                            'exists' => true,
                            'mime_type' => (string) ($disk->mimeType($path) ?: 'application/octet-stream'),
                            'size_bytes' => strlen($binary),
                            'sha256' => hash('sha256', $binary),
                        ];
                    }
                }

                $signaturePath = trim((string) ($serviceRequest->approved_by_signature ?? ''));
                $signatureMetadata = null;

                if ($signaturePath !== '') {
                    $decodedSignature = EncryptedSignature::readBinaryFromPath($signaturePath);

                    if (is_array($decodedSignature)) {
                        $signatureBinary = (string) ($decodedSignature['binary'] ?? '');
                        $signatureMime = trim((string) ($decodedSignature['mime'] ?? 'image/png'));

                        if ($signatureBinary !== '') {
                            $signatureMetadata = [
                                'path' => $signaturePath,
                                'exists' => true,
                                'mime_type' => $signatureMime !== '' ? $signatureMime : 'image/png',
                                'size_bytes' => strlen($signatureBinary),
                                'sha256' => hash('sha256', $signatureBinary),
                            ];
                        } else {
                            $signatureMetadata = [
                                'path' => $signaturePath,
                                'exists' => false,
                            ];
                        }
                    } else {
                        $signatureMetadata = [
                            'path' => $signaturePath,
                            'exists' => false,
                        ];
                    }
                }

                $photoChanged = $serviceRequest->description_photo_metadata !== $descriptionMetadata;
                $signatureChanged = $serviceRequest->approved_signature_metadata !== $signatureMetadata;

                if (! $photoChanged && ! $signatureChanged) {
                    continue;
                }

                $updated++;

                if ($dryRun) {
                    continue;
                }

                $serviceRequest->forceFill([
                    'description_photo_metadata' => $descriptionMetadata,
                    'approved_signature_metadata' => $signatureMetadata,
                ])->save();
            }
        });

    $this->info('Media metadata backfill complete.');
    $this->line('Processed: ' . $processed);
    $this->line('Would update / Updated: ' . $updated . ($dryRun ? ' (dry-run)' : ''));
})->purpose('Backfill media metadata columns for existing service requests');

Artisan::command('media:cleanup-orphans {--dry-run : Show what would be deleted without removing files} {--json : Output machine-readable JSON summary}', function (): void {
    $disk = Storage::disk('public');
    $dryRun = (bool) $this->option('dry-run');

    $referencedPhotoPaths = [];
    $referencedSignaturePaths = [];

    ServiceRequest::query()
        ->select(['id', 'description_photos', 'approved_by_signature'])
        ->orderBy('id')
        ->chunkById(200, function ($requests) use (&$referencedPhotoPaths, &$referencedSignaturePaths): void {
            foreach ($requests as $serviceRequest) {
                $signaturePath = trim((string) ($serviceRequest->approved_by_signature ?? ''));
                if ($signaturePath !== '') {
                    $referencedSignaturePaths[$signaturePath] = true;
                }

                foreach ((array) $serviceRequest->description_photos as $photoPathValue) {
                    $photoPath = trim((string) $photoPathValue);
                    if ($photoPath !== '') {
                        $referencedPhotoPaths[$photoPath] = true;
                    }
                }
            }
        });

    $storedPhotoFiles = [];
    $storedSignatureFiles = [];

    try {
        $storedPhotoFiles = $disk->allFiles('service-request-photos');
    } catch (\Throwable $exception) {
        $storedPhotoFiles = [];
    }

    try {
        $storedSignatureFiles = $disk->allFiles('service-request-signatures');
    } catch (\Throwable $exception) {
        $storedSignatureFiles = [];
    }

    $orphanPhotoFiles = array_values(array_filter(
        $storedPhotoFiles,
        static fn (string $path): bool => ! isset($referencedPhotoPaths[$path])
    ));
    $orphanSignatureFiles = array_values(array_filter(
        $storedSignatureFiles,
        static fn (string $path): bool => ! isset($referencedSignaturePaths[$path])
    ));

    $deletedPhotoFiles = 0;
    $deletedSignatureFiles = 0;
    $failedDeletes = [];

    if (! $dryRun) {
        foreach ($orphanPhotoFiles as $path) {
            try {
                if ($disk->exists($path) && $disk->delete($path)) {
                    $deletedPhotoFiles++;
                }
            } catch (\Throwable $exception) {
                $failedDeletes[] = [
                    'path' => $path,
                    'error' => $exception->getMessage(),
                ];
            }
        }

        foreach ($orphanSignatureFiles as $path) {
            try {
                if ($disk->exists($path) && $disk->delete($path)) {
                    $deletedSignatureFiles++;
                }
            } catch (\Throwable $exception) {
                $failedDeletes[] = [
                    'path' => $path,
                    'error' => $exception->getMessage(),
                ];
            }
        }
    }

    $summary = [
        'dry_run' => $dryRun,
        'orphan_photo_files' => count($orphanPhotoFiles),
        'orphan_signature_files' => count($orphanSignatureFiles),
        'deleted_photo_files' => $deletedPhotoFiles,
        'deleted_signature_files' => $deletedSignatureFiles,
        'failed_deletes' => count($failedDeletes),
    ];

    if ((bool) $this->option('json')) {
        $this->line((string) json_encode([
            'summary' => $summary,
            'orphan_photo_files' => $orphanPhotoFiles,
            'orphan_signature_files' => $orphanSignatureFiles,
            'failed_deletes' => $failedDeletes,
        ], JSON_PRETTY_PRINT));

        return;
    }

    $this->info('Media orphan cleanup complete.');
    $this->table(
        ['Metric', 'Count'],
        [
            ['Dry run', $dryRun ? 'yes' : 'no'],
            ['Orphan photo files', $summary['orphan_photo_files']],
            ['Orphan signature files', $summary['orphan_signature_files']],
            ['Deleted photo files', $summary['deleted_photo_files']],
            ['Deleted signature files', $summary['deleted_signature_files']],
            ['Failed deletes', $summary['failed_deletes']],
        ]
    );

    if ($orphanPhotoFiles !== []) {
        $this->warn('Sample orphan photo files:');
        $this->table(
            ['Path'],
            array_map(static fn (string $path): array => [$path], array_slice($orphanPhotoFiles, 0, 10))
        );
    }

    if ($orphanSignatureFiles !== []) {
        $this->warn('Sample orphan signature files:');
        $this->table(
            ['Path'],
            array_map(static fn (string $path): array => [$path], array_slice($orphanSignatureFiles, 0, 10))
        );
    }
})->purpose('Delete orphaned service request photos/signatures not referenced by any record');

Schedule::command('media:scan-orphans --json')->dailyAt('02:30');
