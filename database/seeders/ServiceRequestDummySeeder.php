<?php

namespace Database\Seeders;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ServiceRequestDummySeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake();
        $total = 150;
        $categories = [
            'Software Support',
            'Hardware Support',
            'Network Concern',
            'Account Access',
            'Data Request',
        ];
        $systems = [
            'KMITS Service Request',
            'Electronic Medical Records',
            'Procurement Tracking',
            'HR Information System',
            'Inventory Monitoring',
        ];
        $offices = [
            'Regional Office',
            'Finance Division',
            'Administrative Division',
            'Planning Unit',
            'ICT Unit',
        ];
        $statusPool = ['pending', 'checking', 'approved'];
        $actionPool = [
            'Ticket reviewed and queued for processing.',
            'Issue replicated and triaged by KMITS.',
            'Configuration updated and verified.',
            'Follow-up sent to requesting office.',
            'Resolution confirmed with requester.',
        ];

        $userIds = User::query()->pluck('id')->all();
        $sequence = (int) ServiceRequest::query()->max('id');

        for ($i = 0; $i < $total; $i++) {
            $requestDate = Carbon::instance($faker->dateTimeBetween('-180 days', 'now'))->startOfDay();
            $status = $faker->randomElement($statusPool);

            $pendingAt = $requestDate->copy()->setTime(
                $faker->numberBetween(8, 16),
                $faker->randomElement([0, 10, 20, 30, 40, 50])
            );

            $checkingAt = null;
            $approvedAt = null;
            $completedAt = null;

            if (in_array($status, ['checking', 'approved'], true)) {
                $checkingAt = $pendingAt->copy()->addHours($faker->numberBetween(1, 36));
            }

            if ($status === 'approved') {
                $approvedAt = ($checkingAt ?? $pendingAt)->copy()->addHours($faker->numberBetween(2, 48));
                $completedAt = $approvedAt->copy();
            }

            $sequence++;
            $referenceCode = sprintf('SRF-%s-%05d', $requestDate->format('dmY'), $sequence);
            while (ServiceRequest::query()->where('reference_code', $referenceCode)->exists()) {
                $sequence++;
                $referenceCode = sprintf('SRF-%s-%05d', $requestDate->format('dmY'), $sequence);
            }

            $chatStatus = null;
            $chatRequestedAt = null;
            $chatDecidedAt = null;

            if (in_array($status, ['pending', 'checking'], true) && $faker->boolean(45)) {
                $chatStatus = $faker->randomElement(['pending', 'accepted', 'rejected']);
                $chatRequestedAt = $pendingAt->copy()->addHours($faker->numberBetween(1, 10));

                if (in_array($chatStatus, ['accepted', 'rejected'], true)) {
                    $chatDecidedAt = $chatRequestedAt->copy()->addHours($faker->numberBetween(1, 24));
                }
            }

            $actionLogs = null;
            if ($status !== 'pending') {
                $actionMoment = ($checkingAt ?? $pendingAt)->copy();
                $actionLogs = [[
                    'date' => $actionMoment->toDateString(),
                    'time' => $actionMoment->format('H:i'),
                    'action_date' => $actionMoment->toDateString(),
                    'action_time' => $actionMoment->format('H:i'),
                    'action_taken' => $faker->randomElement($actionPool),
                    'action_officer' => $faker->name(),
                ]];
            }

            ServiceRequest::query()->create([
                'reference_code' => $referenceCode,
                'request_date' => $requestDate->toDateString(),
                'department_code' => 'ADMIN',
                'request_category' => $faker->randomElement($categories),
                'application_system_name' => $faker->randomElement($systems),
                'expected_completion_date' => $requestDate->copy()->addDays($faker->numberBetween(1, 20))->toDateString(),
                'expected_completion_time' => $faker->time('H:i'),
                'contact_last_name' => $faker->lastName(),
                'contact_first_name' => $faker->firstName(),
                'contact_middle_name' => $faker->boolean(55) ? $faker->firstName() : null,
                'contact_suffix_name' => $faker->boolean(12) ? $faker->randomElement(['Jr.', 'Sr.', 'III']) : null,
                'office' => $faker->randomElement($offices),
                'address' => $faker->streetAddress(),
                'landline' => $faker->boolean(55) ? $faker->numerify('02#######') : null,
                'fax_no' => $faker->boolean(25) ? $faker->numerify('02#######') : null,
                'mobile_no' => $faker->numerify('09#########'),
                'email_address' => $faker->safeEmail(),
                'description_request' => $faker->sentence(14),
                'description_photos' => null,
                'status' => $status,
                'contact_chat_status' => $chatStatus,
                'contact_chat_requested_at' => $chatRequestedAt,
                'contact_chat_decided_at' => $chatDecidedAt,
                'pending_at' => $pendingAt,
                'checking_at' => $checkingAt,
                'approved_at' => $approvedAt,
                'completed_at' => $completedAt,
                'approved_by_name' => $faker->name(),
                'approved_by_signature' => '',
                'approved_by_position' => $faker->randomElement(['Chief, KMITS', 'IT Officer III', 'Systems Analyst']),
                'approved_date' => $requestDate->copy()->addDays($faker->numberBetween(0, 3))->toDateString(),
                'kmits_date' => $requestDate->copy()->addDays($faker->numberBetween(0, 4))->toDateString(),
                'time_received' => $pendingAt->format('H:i'),
                'actions_taken' => $status === 'pending' ? null : $faker->randomElement($actionPool),
                'action_logs' => $actionLogs,
                'noted_by_name' => $status === 'pending' ? null : $faker->name(),
                'noted_by_position' => $status === 'pending' ? null : 'KMITS Staff',
                'noted_by_date_signed' => $status === 'pending'
                    ? null
                    : (($completedAt ?? $checkingAt ?? $pendingAt)->toDateString()),
                'user_id' => $userIds !== [] ? $faker->randomElement($userIds) : null,
            ]);
        }

        $this->command?->info("Seeded {$total} dummy service requests.");
    }
}