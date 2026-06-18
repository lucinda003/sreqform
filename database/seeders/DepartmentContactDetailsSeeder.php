<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentContactDetailsSeeder extends Seeder
{
    public function run(): void
    {
        $sanLazaroAddress = 'San Lazaro Compound, Rizal Avenue, Sta. Cruz, Manila, Philippines 1003';
        $trunkline = '(02) 8651-7800';

        $updates = [
            'KMITS' => [
                'address' => $sanLazaroAddress,
                'landline' => '(02) 781-7673',
            ],
            'OSEC' => [
                'address' => $sanLazaroAddress,
                'landline' => $trunkline,
            ],
            'HEMB' => [
                'address' => $sanLazaroAddress,
                'landline' => '(02) 711-1001',
            ],
            'PNAC' => [
                'address' => '3rd Floor, Building 15, San Lazaro Compound, DOH, Rizal Avenue, Sta. Cruz, Manila',
                'landline' => '(02) 743-8301',
            ],
            'BOQ' => [
                'address' => '25th & A.C. Delgado St., Port Area, Manila',
                'landline' => '(02) 320-9106',
            ],
            'FDA' => [
                'address' => 'Civic Drive, Filinvest Corporate City, Alabang, Muntinlupa City',
                'landline' => '(02) 8257-1957',
            ],
            'PhilHealth' => [
                'address' => 'Citystate Centre, 709 Shaw Blvd., Pasig City',
                'landline' => '(02) 8441-7444',
            ],
            'NNC' => [
                'address' => 'Nutrition Building, 2332 Chino Roces Ave. Extension, Taguig City',
                'landline' => '(02) 8843-0142',
            ],
            'PITAHC' => [
                'address' => 'PITAHC Building, Matapang St., East Avenue Medical Center Compound, Brgy. Central, Diliman, Quezon City',
                'landline' => '(02) 8282-5193',
            ],
        ];

        foreach ($updates as $code => $data) {
            DB::table('department_codes')
                ->where('code', $code)
                ->update([
                    'address' => $data['address'],
                    'landline' => $data['landline'],
                    'updated_at' => now(),
                ]);
        }

        // Set default address for other DOH Central offices we haven't specified
        DB::table('department_codes')
            ->whereNull('address')
            ->update([
                'address' => $sanLazaroAddress,
                'landline' => $trunkline,
                'updated_at' => now(),
            ]);
    }
}
