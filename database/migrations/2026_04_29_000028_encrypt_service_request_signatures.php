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
        if (! Schema::hasTable('service_requests')) {
            return;
        }

        DB::table('service_requests')
            ->select(['id', 'approved_by_signature', 'approved_signature_metadata', 'noted_by_signature', 'action_logs'])
            ->orderBy('id')
            ->chunkById(100, function ($serviceRequests): void {
                foreach ($serviceRequests as $serviceRequest) {
                    $updates = [];

                    $approvedSignature = trim((string) ($serviceRequest->approved_by_signature ?? ''));
                    $encryptedApprovedSignature = $this->encryptDataUriSignature($approvedSignature);
                    if ($encryptedApprovedSignature !== null) {
                        $updates['approved_by_signature'] = $encryptedApprovedSignature;

                        if (Schema::hasColumn('service_requests', 'approved_signature_metadata')) {
                            $updates['approved_signature_metadata'] = json_encode(
                                $this->metadataForEncryptedSignature($encryptedApprovedSignature),
                                JSON_THROW_ON_ERROR
                            );
                        }
                    }

                    if (Schema::hasColumn('service_requests', 'noted_by_signature')) {
                        $notedSignature = trim((string) ($serviceRequest->noted_by_signature ?? ''));
                        $encryptedNotedSignature = $this->encryptDataUriSignature($notedSignature);
                        if ($encryptedNotedSignature !== null) {
                            $updates['noted_by_signature'] = $encryptedNotedSignature;
                        }
                    }

                    $actionLogs = $this->decodeActionLogs($serviceRequest->action_logs ?? null);
                    if ($actionLogs !== null) {
                        $logsChanged = false;

                        foreach ($actionLogs as &$row) {
                            if (! is_array($row)) {
                                continue;
                            }

                            $rowSignature = trim((string) ($row['signature'] ?? ''));
                            $encryptedRowSignature = $this->encryptDataUriSignature($rowSignature);
                            if ($encryptedRowSignature === null) {
                                continue;
                            }

                            $row['signature'] = $encryptedRowSignature;
                            $logsChanged = true;
                        }
                        unset($row);

                        if ($logsChanged) {
                            $updates['action_logs'] = json_encode($actionLogs, JSON_THROW_ON_ERROR);
                        }
                    }

                    if ($updates !== []) {
                        DB::table('service_requests')
                            ->where('id', $serviceRequest->id)
                            ->update($updates);
                    }
                }
            });
    }

    public function down(): void
    {
        if (! Schema::hasTable('service_requests')) {
            return;
        }

        DB::table('service_requests')
            ->select(['id', 'approved_by_signature', 'approved_signature_metadata', 'noted_by_signature', 'action_logs'])
            ->orderBy('id')
            ->chunkById(100, function ($serviceRequests): void {
                foreach ($serviceRequests as $serviceRequest) {
                    $updates = [];

                    $approvedPath = trim((string) ($serviceRequest->approved_by_signature ?? ''));
                    $approvedDataUri = $this->dataUriFromEncryptedSignature($approvedPath);
                    if ($approvedDataUri !== null) {
                        $updates['approved_by_signature'] = $approvedDataUri;

                        if (Schema::hasColumn('service_requests', 'approved_signature_metadata')) {
                            $decoded = $this->decodeImageDataUri($approvedDataUri);
                            $updates['approved_signature_metadata'] = json_encode(
                                $this->metadataForDataUri($decoded),
                                JSON_THROW_ON_ERROR
                            );
                        }
                    }

                    if (Schema::hasColumn('service_requests', 'noted_by_signature')) {
                        $notedPath = trim((string) ($serviceRequest->noted_by_signature ?? ''));
                        $notedDataUri = $this->dataUriFromEncryptedSignature($notedPath);
                        if ($notedDataUri !== null) {
                            $updates['noted_by_signature'] = $notedDataUri;
                        }
                    }

                    $actionLogs = $this->decodeActionLogs($serviceRequest->action_logs ?? null);
                    if ($actionLogs !== null) {
                        $logsChanged = false;

                        foreach ($actionLogs as &$row) {
                            if (! is_array($row)) {
                                continue;
                            }

                            $rowPath = trim((string) ($row['signature'] ?? ''));
                            $rowDataUri = $this->dataUriFromEncryptedSignature($rowPath);
                            if ($rowDataUri === null) {
                                continue;
                            }

                            $row['signature'] = $rowDataUri;
                            $logsChanged = true;
                        }
                        unset($row);

                        if ($logsChanged) {
                            $updates['action_logs'] = json_encode($actionLogs, JSON_THROW_ON_ERROR);
                        }
                    }

                    if ($updates !== []) {
                        DB::table('service_requests')
                            ->where('id', $serviceRequest->id)
                            ->update($updates);
                    }
                }
            });
    }

    private function encryptDataUriSignature(string $signature): ?string
    {
        if ($signature === '' || str_starts_with($signature, 'service-request-signatures/')) {
            return null;
        }

        $decoded = $this->decodeImageDataUri($signature);
        if (! is_array($decoded)) {
            return null;
        }

        return EncryptedSignature::storeBinary(
            (string) ($decoded['binary'] ?? ''),
            (string) ($decoded['mime'] ?? 'image/png')
        );
    }

    private function dataUriFromEncryptedSignature(string $path): ?string
    {
        if ($path === '' || ! str_starts_with($path, 'service-request-signatures/')) {
            return null;
        }

        $dataUri = EncryptedSignature::dataUriFromPath($path);
        if ($dataUri === '') {
            return null;
        }

        Storage::disk('public')->delete($path);

        return $dataUri;
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

    private function decodeActionLogs(mixed $actionLogs): ?array
    {
        if (is_array($actionLogs)) {
            return $actionLogs;
        }

        $raw = trim((string) $actionLogs);
        if ($raw === '') {
            return null;
        }

        try {
            $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable) {
            return null;
        }

        return is_array($decoded) ? $decoded : null;
    }

    private function metadataForEncryptedSignature(string $path): array
    {
        $decoded = EncryptedSignature::readBinaryFromPath($path);
        $binary = is_array($decoded) ? (string) ($decoded['binary'] ?? '') : '';
        $mime = is_array($decoded) ? trim((string) ($decoded['mime'] ?? 'image/png')) : 'image/png';

        return [
            'path' => $path,
            'exists' => $binary !== '',
            'mime_type' => $mime !== '' ? $mime : 'image/png',
            'size_bytes' => strlen($binary),
            'sha256' => $binary !== '' ? hash('sha256', $binary) : null,
        ];
    }

    private function metadataForDataUri(?array $decoded): ?array
    {
        if (! is_array($decoded)) {
            return null;
        }

        $binary = (string) ($decoded['binary'] ?? '');
        $mime = trim((string) ($decoded['mime'] ?? 'image/png'));

        return [
            'source' => 'database',
            'exists' => $binary !== '',
            'mime_type' => $mime !== '' ? $mime : 'image/png',
            'size_bytes' => strlen($binary),
            'sha256' => $binary !== '' ? hash('sha256', $binary) : null,
        ];
    }
};
