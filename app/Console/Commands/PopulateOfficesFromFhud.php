<?php

namespace App\Console\Commands;

use App\Models\Office;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateOfficesFromFhud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'office:populate-from-fhud {--truncate : Truncate existing offices before populating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate offices table from oston_dbo.fhud_hospital table';

    public function handle(): int
    {
        $shouldTruncate = $this->option('truncate');

        if ($shouldTruncate) {
            if ($this->confirm('Are you sure you want to truncate the offices table? (y/n)')) {
                Office::truncate();
                $this->info('Offices table truncated.');
            } else {
                $this->info('Truncation cancelled.');

                return self::FAILURE;
            }
        }

        try {
            // Query from the oston_dbo database
            $hospitals = DB::connection('oston')
                ->table('fhud_hospital')
                ->whereNotNull('hfhudname')
                ->orderBy('hfhudname')
                ->get(['hfhudname', 'regcode', 'address']);

            $this->info("Found {$hospitals->count()} hospitals to import.");

            if ($hospitals->count() === 0) {
                $this->warn('No hospitals found in oston_dbo.fhud_hospital table.');

                return self::FAILURE;
            }

            $bar = $this->output->createProgressBar($hospitals->count());
            $bar->start();

            $officeCount = 0;
            $skippedCount = 0;
            $duplicateCount = 0;
            $chunkSize = 100;
            $chunk = [];
            $seenNames = [];

            foreach ($hospitals as $hospital) {
                $name = trim((string) ($hospital->hfhudname ?? ''));
                $regcode = trim((string) ($hospital->regcode ?? ''));
                $address = trim((string) ($hospital->address ?? ''));

                // Handle character encoding issues from latin1 source
                $name = mb_convert_encoding($name, 'UTF-8', 'latin1');
                $address = mb_convert_encoding($address, 'UTF-8', 'latin1');

                if ($name === '') {
                    $bar->advance();

                    continue;
                }

                // Skip duplicate names in the source data
                if (in_array($name, $seenNames, true)) {
                    $duplicateCount++;
                    $bar->advance();

                    continue;
                }

                $seenNames[] = $name;

                // Check if office already exists in database
                if (Office::where('name', $name)->exists()) {
                    $skippedCount++;
                    $bar->advance();

                    continue;
                }

                $chunk[] = [
                    'name' => $name,
                    'regcode' => $regcode !== '' ? $regcode : null,
                    'address' => $address !== '' ? $address : null,
                    'parent_name' => 'DOH',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($chunk) >= $chunkSize) {
                    Office::insert($chunk);
                    $officeCount += count($chunk);
                    $chunk = [];
                }

                $bar->advance();
            }

            // Insert remaining records
            if (count($chunk) > 0) {
                Office::insert($chunk);
                $officeCount += count($chunk);
            }

            $bar->finish();
            $this->newLine();

            $this->info("Successfully imported {$officeCount} offices.");
            $this->info("Skipped {$skippedCount} offices (already existed).");
            $this->info("Skipped {$duplicateCount} duplicate names from source data.");

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error during import: ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}
