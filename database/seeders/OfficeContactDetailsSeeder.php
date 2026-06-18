<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeContactDetailsSeeder extends Seeder
{
    public function run(): void
    {
        $sanLazaroAddress = 'San Lazaro Compound, Rizal Avenue, Sta. Cruz, Manila, Philippines 1003';
        $trunkline = '(02) 8651-7800';

        // Update Central Offices in the 'offices' table
        // Matching based on the exact name in your DohOfficeSeeder
        $updates = [
            'Knowledge Management and Information Technology Service' => [
                'address' => $sanLazaroAddress,
                'phone' => '(02) 781-7673',
            ],
            'Office of the Secretary' => [
                'address' => $sanLazaroAddress,
                'phone' => $trunkline,
            ],
            'Health Emergency Management Bureau' => [
                'address' => $sanLazaroAddress,
                'phone' => '(02) 711-1001',
            ],
            'Bureau of Quarantine' => [
                'address' => '25th & A.C. Delgado St., Port Area, Manila',
                'phone' => '(02) 320-9106',
            ],
            'Food and Drug Administration' => [
                'address' => 'Civic Drive, Filinvest Corporate City, Alabang, Muntinlupa City',
                'phone' => '(02) 8257-1957',
            ],
            'PhilHealth' => [
                'address' => 'Citystate Centre, 709 Shaw Blvd., Pasig City',
                'phone' => '(02) 8441-7444',
            ],
            'National Nutrition Council' => [
                'address' => 'Nutrition Building, 2332 Chino Roces Ave. Extension, Taguig City',
                'phone' => '(02) 8843-0142',
            ],
            'Philippine Institute of Traditional and Alternative Health Care' => [
                'address' => 'PITAHC Building, Matapang St., East Avenue Medical Center Compound, Brgy. Central, Diliman, Quezon City',
                'phone' => '(02) 8282-5193',
            ],
            'Philippine National AIDS Council' => [
                'address' => '3rd Floor, Building 15, San Lazaro Compound, DOH, Rizal Avenue, Sta. Cruz, Manila',
                'phone' => '(02) 743-8301',
            ],
        ];

        foreach ($updates as $name => $data) {
            DB::table('offices')
                ->where('name', $name)
                ->update([
                    'address' => $data['address'],
                    'phone' => $data['phone'],
                ]);
        }
        
        // Also update anything with the specific regcode/acronym for extra safety
        $acronymUpdates = [
            'OSEC' => ['phone' => $trunkline],
            'KMITS' => ['phone' => '(02) 781-7673'],
            'HEMB' => ['phone' => '(02) 711-1001'],
        ];
        
        foreach ($acronymUpdates as $code => $data) {
            DB::table('offices')
                ->where('regcode', $code)
                ->update([
                    'phone' => $data['phone'],
                ]);
        }
    }
}
