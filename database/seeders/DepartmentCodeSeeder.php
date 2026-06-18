<?php

namespace Database\Seeders;

use App\Models\DepartmentCode;
use Illuminate\Database\Seeder;

class DepartmentCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            // DOH Central / Inside DOH
            ['office_name' => 'Office of the Secretary',                                          'code' => 'OSEC'],
            ['office_name' => 'Communication Office',                                             'code' => null],
            ['office_name' => 'Legal Service',                                                    'code' => null],
            ['office_name' => 'Internal Audit Service',                                           'code' => null],
            ['office_name' => 'Administrative Service',                                           'code' => null],
            ['office_name' => 'Financial and Management Service',                                 'code' => null],
            ['office_name' => 'Supply Chain Management Service',                                  'code' => 'SCMS'],
            ['office_name' => 'Procurement Service',                                              'code' => null],
            ['office_name' => 'Knowledge Management and Information Technology Service',          'code' => 'KMITS'],
            ['office_name' => 'Health Emergency Management Bureau',                               'code' => 'HEMB'],
            ['office_name' => 'Health Facility Development Bureau',                               'code' => 'HFDB'],
            ['office_name' => 'Health Facilities and Services Regulatory Bureau',                 'code' => 'HFSRB'],
            ['office_name' => 'Bureau of Quarantine',                                             'code' => 'BOQ'],
            ['office_name' => 'Bureau of International Health Cooperation',                       'code' => 'BIHC'],
            ['office_name' => 'Bureau of Local Health Systems Development',                       'code' => 'BLHSD'],
            ['office_name' => 'Health Human Resource Development Bureau',                         'code' => 'HHRDB'],
            ['office_name' => 'Health Policy Development and Planning Bureau',                    'code' => 'HPDPB'],
            ['office_name' => 'Epidemiology Bureau',                                              'code' => 'EB'],
            ['office_name' => 'Disease Prevention and Control Bureau',                            'code' => 'DPCB'],
            ['office_name' => 'Health Promotion Bureau',                                          'code' => 'HPB'],
            ['office_name' => 'Pharmaceutical Division',                                          'code' => null],
            ['office_name' => 'Malasakit Program Office',                                         'code' => null],
            ['office_name' => 'Public-Private Partnership Program Management Office',             'code' => 'PPP-PMO'],

            // Attached / Related DOH Agencies
            ['office_name' => 'PhilHealth',                                                       'code' => null],
            ['office_name' => 'Food and Drug Administration',                                     'code' => 'FDA'],
            ['office_name' => 'National Nutrition Council',                                       'code' => 'NNC'],
            ['office_name' => 'Philippine National AIDS Council',                                 'code' => 'PNAC'],
            ['office_name' => 'Philippine Institute of Traditional and Alternative Health Care',  'code' => 'PITAHC'],
        ];

        foreach ($departments as $department) {
            DepartmentCode::query()->updateOrCreate(
                ['office_name' => $department['office_name']],
                ['code' => $department['code']]
            );
        }
    }
}
