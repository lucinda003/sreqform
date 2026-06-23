<?php

namespace App\Console\Commands;

use App\Models\ServiceRequest;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('service-requests:update-ongoing-status')]
#[Description('Update approved requests with action logs to ongoing status')]
class UpdateApprovedToOngoingStatus extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for checking/approved requests with filled action logs...');

        $requests = ServiceRequest::whereIn('status', ['checking', 'approved'])
            ->whereNotNull('action_logs')
            ->get();

        $updatedCount = 0;

        foreach ($requests as $request) {
            $actionLogs = is_array($request->action_logs) ? $request->action_logs : [];
            $hasFilledActionLog = false;

            foreach ($actionLogs as $log) {
                $actionDate = trim((string) ($log['action_date'] ?? ''));
                $actionTime = trim((string) ($log['action_time'] ?? ''));
                $actionTaken = trim((string) ($log['action_taken'] ?? ''));
                $actionOfficer = trim((string) ($log['action_officer'] ?? ''));

                if ($actionDate !== '' || $actionTime !== '' || $actionTaken !== '' || $actionOfficer !== '') {
                    $hasFilledActionLog = true;
                    break;
                }
            }

            if ($hasFilledActionLog) {
                $request->update([
                    'status' => 'ongoing',
                    'ongoing_at' => $request->checking_at ?? $request->approved_at ?? now(),
                ]);
                $updatedCount++;
                $this->line("Updated: {$request->reference_code}");
            }
        }

        $this->info("✓ Updated {$updatedCount} request(s) to 'ongoing' status.");
        
        return 0;
    }
}
