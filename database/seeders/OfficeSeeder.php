<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OfficeSeeder extends Seeder
{
    public function run(): void
    {
        $jsonFile = 'C:\\Users\\doh\\Downloads\\free-claude-code-main\\free-claude-code-main\\nhfr_facilities_all.json';

        if (! file_exists($jsonFile)) {
            $this->command->error("JSON file not found: {$jsonFile}");
            return;
        }

        $this->command->warn('This will truncate existing offices and import NHFR facilities from JSON.');
        $this->command->info('Truncating existing offices...');
        DB::table('offices')->truncate();

        $batch = [];
        $count = 0;
        $batchSize = 500;
        $now = now();

        $this->command->info('Streaming NHFR JSON file...');
        foreach ($this->recordsFromJsonArray($jsonFile) as $record) {
            $name = $this->clean($record['name'] ?? '');
            if ($name === '') {
                continue;
            }

            $region = $this->clean($record['region'] ?? '');
            $payload = [
                'parent_name' => $region !== '' ? mb_substr($region, 0, 255) : null,
                'name' => mb_substr($name, 0, 255),
                'regcode' => $this->clean($record['code'] ?? '') ?: null,
                'address' => $this->buildAddress($record),
                'licensing_status' => $this->nullableString($record['licensingStatus'] ?? null),
                'license_date' => $this->nullableDate($record['licenseDate'] ?? null),
                'facility_type' => $this->nullableString($record['facilityType'] ?? null),
                'classification' => $this->nullableString($record['classification'] ?? null),
                'street' => $this->nullableString($record['street'] ?? null),
                'building' => $this->nullableString($record['building'] ?? null),
                'region' => $region !== '' ? mb_substr($region, 0, 255) : null,
                'province' => $this->nullableString($record['province'] ?? null),
                'city' => $this->nullableString($record['city'] ?? null),
                'barangay' => $this->nullableString($record['barangay'] ?? null),
                'phone' => $this->nullableString($record['phone'] ?? null),
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $batch[] = $payload;
            $count++;

            if (count($batch) >= $batchSize) {
                DB::table('offices')->insert($batch);
                $this->command->info("Imported {$count} offices...");
                $batch = [];
            }
        }

        if ($batch !== []) {
            DB::table('offices')->insert($batch);
        }

        $this->command->info("Import complete. Final office count: " . DB::table('offices')->count());
    }

    private function recordsFromJsonArray(string $jsonFile): \Generator
    {
        $handle = fopen($jsonFile, 'rb');
        if ($handle === false) {
            return;
        }

        $buffer = '';
        $depth = 0;
        $inString = false;
        $escaped = false;
        $capturing = false;

        try {
            while (($chunk = fread($handle, 8192)) !== false && $chunk !== '') {
                $length = strlen($chunk);

                for ($index = 0; $index < $length; $index++) {
                    $char = $chunk[$index];

                    if ($capturing) {
                        $buffer .= $char;
                    }

                    if ($inString) {
                        if ($escaped) {
                            $escaped = false;
                            continue;
                        }

                        if ($char === '\\') {
                            $escaped = true;
                            continue;
                        }

                        if ($char === '"') {
                            $inString = false;
                        }

                        continue;
                    }

                    if ($char === '"') {
                        $inString = true;
                        continue;
                    }

                    if ($char === '{') {
                        if (! $capturing) {
                            $capturing = true;
                            $buffer = '{';
                        }

                        $depth++;
                        continue;
                    }

                    if ($char === '}') {
                        $depth--;

                        if ($capturing && $depth === 0) {
                            $record = json_decode($buffer, true);
                            if (is_array($record)) {
                                yield $record;
                            }

                            $buffer = '';
                            $capturing = false;
                        }
                    }
                }
            }
        } finally {
            fclose($handle);
        }
    }

    private function clean(mixed $value): string
    {
        $cleaned = trim(preg_replace('/\s+/', ' ', (string) $value) ?? '');
        $cleaned = trim($cleaned, " \t\n\r\0\x0B,");

        $cleaned = preg_replace('/^(Building name and #:|Zip Code:)\s*/i', '', $cleaned) ?? '';
        $cleaned = trim($cleaned, " \t\n\r\0\x0B,");

        if (in_array(strtolower($cleaned), ['building name and #:', 'zip code:'], true)) {
            return '';
        }

        return $cleaned;
    }

    private function nullableString(mixed $value): ?string
    {
        $cleaned = $this->clean($value);
        return $cleaned !== '' ? mb_substr($cleaned, 0, 255) : null;
    }

    private function nullableDate(mixed $value): ?string
    {
        $cleaned = $this->clean($value);
        if ($cleaned === '') {
            return null;
        }

        try {
            return Carbon::parse($cleaned)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    private function buildAddress(array $record): string
    {
        return collect([
            $record['building'] ?? null,
            $record['street'] ?? null,
            $record['barangay'] ?? null,
            $record['city'] ?? null,
            $record['province'] ?? null,
            $record['region'] ?? null,
        ])
            ->map(fn ($value): string => $this->clean($value))
            ->filter()
            ->unique()
            ->implode(', ');
    }
}
