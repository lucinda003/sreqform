<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeSeeder extends Seeder
{
    public function run(): void
    {
        // Path to the SQL dump file
        $sqlFile = 'C:\\Users\\doh\\Downloads\\free-claude-code-main\\free-claude-code-main\\nhfr_offices_all.sql';

        if (! file_exists($sqlFile)) {
            $this->command->error("SQL file not found: {$sqlFile}");
            return;
        }

        // Truncate existing data
        $this->command->info('Truncating existing offices...');
        DB::table('offices')->truncate();

        $this->command->info('Starting to import offices from SQL file...');

        $content = file_get_contents($sqlFile);
        $lines = explode("\n", $content);

        $batch = [];
        $count = 0;
        $errors = 0;
        $batchSize = 500;

        foreach ($lines as $lineNum => $line) {
            $line = trim($line);

            if (empty($line) || ! str_contains($line, 'INSERT INTO')) {
                continue;
            }

            // Parse: INSERT INTO `offices` (`parent_name`, `name`, `is_active`) VALUES ('val1', 'val2', 'val3')
            if (preg_match("/VALUES\s*\((.*)\)\s*;?\s*$/i", $line, $matches)) {
                $valuesStr = $matches[1];

                // Extract the three values (they are single-quoted)
                if (preg_match_all("/'([^']*(?:''[^']*)*)'/", $valuesStr, $valueMatches)) {
                    $values = array_map(function ($val) {
                        // Unescape single quotes
                        return str_replace("''", "'", $val);
                    }, $valueMatches[1]);

                    if (count($values) >= 3) {
                        $parentName = $values[0];
                        $name = $values[1];
                        $address = $values[2];
                        $address = str_replace(['Building name and #:', 'Zip Code:'], '', $address);
                        $address = preg_replace('/\s*,\s*,+/', ', ', $address);
                        $address = preg_replace('/\s{2,}/', ' ', $address);
                        $address = trim($address, " ,");

                        // Extract regcode from parent_name
                        // Examples: 'REGION I (ILOCOS REGION)' -> 'ILOCOS REGION'
                        // 'NATIONAL CAPITAL REGION (NCR)' -> 'NCR'
                        $regcode = null;
                        if (preg_match('/\(([^)]+)\)$/', $parentName, $rcMatch)) {
                            $code = trim($rcMatch[1]);
                            $regcode = mb_substr($code, 0, 50);
                        }

                        $batch[] = [
                            'parent_name' => mb_substr($parentName, 0, 255),
                            'name' => mb_substr($name, 0, 255),
                            'regcode' => $regcode,
                            'address' => $address,
                            'is_active' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        $count++;

                        // Insert in batches
                        if (count($batch) >= $batchSize) {
                            try {
                                DB::table('offices')->insert($batch);
                                $this->command->info("Imported {$count} offices...");
                            } catch (\Exception $e) {
                                $errors++;
                                if ($errors <= 5) {
                                    $this->command->warn("Batch error near line " . ($lineNum + 1) . ": " . $e->getMessage());
                                }
                            }
                            $batch = [];
                        }
                    }
                }
            }
        }

        // Insert remaining records
        if (! empty($batch)) {
            try {
                DB::table('offices')->insert($batch);
            } catch (\Exception $e) {
                $errors++;
                if ($errors <= 5) {
                    $this->command->warn("Final batch error: " . $e->getMessage());
                }
            }
        }

        $this->command->info("✓ Import complete!");
        $this->command->info("Successfully processed: {$count} office records");
        if ($errors > 0) {
            $this->command->warn("Errors encountered: {$errors}");
        }

        // Final count
        $finalCount = DB::table('offices')->count();
        $this->command->info("Final office count in database: {$finalCount}");
    }
}
