<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;

class DohOfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offices = [
            ['name' => 'Office of the Secretary', 'acronym' => 'OSEC'],
            ['name' => 'Communication Office', 'acronym' => null],
            ['name' => 'Legal Service', 'acronym' => null],
            ['name' => 'Internal Audit Service', 'acronym' => null],
            ['name' => 'Administrative Service', 'acronym' => null],
            ['name' => 'Financial and Management Service', 'acronym' => null],
            ['name' => 'Supply Chain Management Service', 'acronym' => 'SCMS'],
            ['name' => 'Procurement Service', 'acronym' => null],
            ['name' => 'Knowledge Management and Information Technology Service', 'acronym' => 'KMITS'],
            ['name' => 'Health Emergency Management Bureau', 'acronym' => 'HEMB'],
            ['name' => 'Health Facility Development Bureau', 'acronym' => 'HFDB'],
            ['name' => 'Health Facilities and Services Regulatory Bureau', 'acronym' => 'HFSRB'],
            ['name' => 'Bureau of Quarantine', 'acronym' => 'BOQ'],
            ['name' => 'Bureau of International Health Cooperation', 'acronym' => 'BIHC'],
            ['name' => 'Bureau of Local Health Systems Development', 'acronym' => 'BLHSD'],
            ['name' => 'Health Human Resource Development Bureau', 'acronym' => 'HHRDB'],
            ['name' => 'Health Policy Development and Planning Bureau', 'acronym' => 'HPDPB'],
            ['name' => 'Epidemiology Bureau', 'acronym' => 'EB'],
            ['name' => 'Disease Prevention and Control Bureau', 'acronym' => 'DPCB'],
            ['name' => 'Health Promotion Bureau', 'acronym' => 'HPB'],
            ['name' => 'Pharmaceutical Division', 'acronym' => null],
            ['name' => 'Malasakit Program Office', 'acronym' => null],
            ['name' => 'Public-Private Partnership Program Management Office', 'acronym' => 'PPP-PMO'],
            ['name' => 'PhilHealth', 'acronym' => null],
            ['name' => 'Food and Drug Administration', 'acronym' => 'FDA'],
            ['name' => 'National Nutrition Council', 'acronym' => 'NNC'],
            ['name' => 'Philippine National AIDS Council', 'acronym' => 'PNAC'],
            ['name' => 'Philippine Institute of Traditional and Alternative Health Care', 'acronym' => 'PITAHC'],
        ];

        foreach ($offices as $office) {
            $name = $office['name'];
            $acronym = $office['acronym'];
            $isAttachedAgency = str_starts_with($name, 'Phil')
                || in_array($acronym, ['FDA', 'NNC', 'PNAC', 'PITAHC'], true);

            Office::query()->updateOrCreate(
                ['name' => $name],
                [
                    'parent_name' => $isAttachedAgency
                        ? 'Attached / Related DOH Agencies'
                        : 'DOH Central / Inside DOH',
                    'regcode' => $acronym,
                    'address' => 'Department of Health',
                    'facility_type' => $acronym !== null ? $acronym : 'DOH Office',
                    'classification' => 'DOH Office',
                    'region' => 'National',
                    'is_active' => true,
                ]
            );
        }
    }
}
