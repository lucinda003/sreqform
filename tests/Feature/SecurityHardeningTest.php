<?php

namespace Tests\Feature;

use App\Models\Office;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Support\EncryptedSignature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_counts_are_scoped_for_non_admin_users(): void
    {
        $owner = User::factory()->create([
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $other = User::factory()->create([
            'department' => 'FINANCE',
            'department_status' => 'approved',
        ]);

        $this->createServiceRequest($owner, [
            'request_date' => now()->toDateString(),
        ]);

        $this->createServiceRequest($other, [
            'request_date' => now()->toDateString(),
        ]);

        $response = $this
            ->actingAs($owner)
            ->get('/dashboard');

        $response->assertOk();
        $response->assertViewHas('totalRequests', 1);
        $response->assertViewHas('todayRequests', 1);
        $response->assertViewHas('thisWeekRequests', 1);
    }

    public function test_public_track_messages_endpoint_is_rate_limited(): void
    {
        $owner = User::factory()->create([
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($owner, [
            'email_address' => 'owner@example.com',
        ]);

        $uri = '/track-your-request/' . $serviceRequest->reference_code . '/messages';

        for ($i = 0; $i < 60; $i++) {
            $this->get($uri)->assertStatus(403);
        }

        $this->get($uri)->assertStatus(429);
    }

    public function test_public_track_activity_requires_verified_track_access(): void
    {
        $owner = User::factory()->create([
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($owner, [
            'email_address' => 'owner@example.com',
            'action_logs' => [[
                'action_date' => '2026-06-08',
                'action_time' => '10:43',
                'action_taken' => 'Checked request details.',
                'action_officer' => 'Assigned Admin',
            ]],
        ]);

        $this
            ->getJson(route('service-requests.track.activity', ['referenceCode' => $serviceRequest->reference_code]))
            ->assertForbidden();
    }

    public function test_public_track_activity_returns_rendered_action_updates_for_verified_requester(): void
    {
        $owner = User::factory()->create([
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($owner, [
            'email_address' => 'owner@example.com',
            'status' => 'checking',
            'checking_at' => '2026-06-08 11:30:00',
            'action_logs' => [
                [
                    'action_date' => '2026-06-08',
                    'action_time' => '10:43',
                    'action_taken' => 'First action update.',
                    'action_officer' => 'Assigned Admin',
                ],
                [
                    'action_date' => '2026-06-08',
                    'action_time' => '11:15',
                    'action_taken' => 'Latest action update.',
                    'action_officer' => 'Second Admin',
                ],
            ],
        ]);
        $trackSessionKey = strtoupper(str_replace('-', '', $serviceRequest->reference_code));

        $response = $this
            ->withSession([
                'track_access' => [
                    $trackSessionKey => now()->addHour()->timestamp,
                ],
            ])
            ->getJson(route('service-requests.track.activity', ['referenceCode' => $serviceRequest->reference_code]))
            ->assertOk()
            ->assertJsonPath('updated_at', $serviceRequest->updated_at?->toIso8601String());

        $html = (string) $response->json('html');
        $statusHtml = (string) $response->json('status_html');

        $this->assertStringContainsString('Latest action update.', $html);
        $this->assertStringContainsString('Handled by Second Admin', $html);
        $this->assertStringContainsString('Checking', $statusHtml);
        $this->assertStringNotContainsString('First action update.', $html);
    }

    public function test_public_track_activity_returns_received_update_when_no_action_taken_exists(): void
    {
        $receiver = User::factory()->create([
            'name' => 'Receiving Officer',
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);
        $owner = User::factory()->create([
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($owner, [
            'email_address' => 'owner@example.com',
            'status' => 'checking',
            'received_by_user_id' => $receiver->id,
            'received_at' => '2026-06-08 09:45:00',
            'action_logs' => [[
                'date' => '2026-06-08',
                'time' => '09:45',
                'action_taken' => null,
            ]],
        ]);
        $trackSessionKey = strtoupper(str_replace('-', '', $serviceRequest->reference_code));

        $response = $this
            ->withSession([
                'track_access' => [
                    $trackSessionKey => now()->addHour()->timestamp,
                ],
            ])
            ->getJson(route('service-requests.track.activity', ['referenceCode' => $serviceRequest->reference_code]))
            ->assertOk();

        $html = (string) $response->json('html');

        $this->assertStringContainsString('Receiving Officer received your request and is now checking it.', $html);
    }

    public function test_admin_management_routes_require_superadmin_department(): void
    {
        $regularAdmin = User::factory()->create([
            'role' => 'admin',
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $superAdmin = User::factory()->create([
            'role' => 'super admin',
            'department' => 'ADMIN',
            'department_status' => 'approved',
        ]);

        $this
            ->actingAs($regularAdmin)
            ->get(route('admin.management.index'))
            ->assertForbidden();

        $this
            ->actingAs($regularAdmin)
            ->get(route('admin.offices.index'))
            ->assertForbidden();

        $this
            ->actingAs($regularAdmin)
            ->get(route('admin.application-systems.index'))
            ->assertForbidden();

        $this
            ->actingAs($superAdmin)
            ->get(route('admin.management.index'))
            ->assertOk();
    }

    public function test_public_office_search_endpoint_is_rate_limited(): void
    {
        Office::create([
            'name' => 'Sample Health Facility',
            'parent_name' => 'REGION X (NORTHERN MINDANAO)',
            'region' => 'REGION X (NORTHERN MINDANAO)',
            'address' => 'Sample Address',
            'is_active' => true,
        ]);

        for ($i = 0; $i < 60; $i++) {
            $this
                ->withServerVariables(['REMOTE_ADDR' => '203.0.113.77'])
                ->getJson(route('offices.search', ['q' => 'Sample']))
                ->assertOk();
        }

        $this
            ->withServerVariables(['REMOTE_ADDR' => '203.0.113.77'])
            ->getJson(route('offices.search', ['q' => 'Sample']))
            ->assertStatus(429);
    }

    public function test_capture_email_store_cannot_overwrite_existing_email(): void
    {
        $owner = User::factory()->create([
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($owner, [
            'email_address' => 'owner@example.com',
        ]);

        $url = URL::signedRoute('service-requests.capture-email.store', [
            'serviceRequest' => $serviceRequest->id,
        ]);

        $response = $this->post($url, [
            'email_address' => 'attacker@example.com',
        ]);

        $response->assertRedirect(route('service-requests.track', [
            'reference_code' => $serviceRequest->reference_code,
        ]));

        $response->assertSessionHasErrors(['email_address']);

        $serviceRequest->refresh();
        $this->assertSame('owner@example.com', $serviceRequest->email_address);
    }

    public function test_send_track_access_code_without_saved_email_does_not_issue_capture_link(): void
    {
        $owner = User::factory()->create([
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($owner, [
            'email_address' => null,
        ]);

        $response = $this->post('/track-your-request/' . $serviceRequest->reference_code . '/verify/send-code');

        $response->assertRedirect(route('service-requests.track', [
            'reference_code' => $serviceRequest->reference_code,
        ]));

        $response->assertSessionHasErrors(['code']);
    }

    public function test_store_rejects_duplicate_submission_token(): void
    {
        $departmentUser = User::factory()->create([
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $submissionToken = (string) Str::uuid();
        $payload = $this->validStorePayload($departmentUser, $submissionToken);

        $firstResponse = $this
            ->withSession(['service_request_submission_token' => $submissionToken])
            ->post(route('service-requests.store'), $payload);

        $firstResponse->assertStatus(302);
        $this->assertSame(1, ServiceRequest::query()->count());

        $secondResponse = $this->post(route('service-requests.store'), $payload);

        $secondResponse->assertRedirect(route('service-requests.create'));
        $secondResponse->assertSessionHasErrors(['form']);

        $this->assertSame(1, ServiceRequest::query()->count());
    }

    public function test_authenticated_edit_page_has_expected_csp_font_sources(): void
    {
        $admin = User::factory()->create([
            'department' => 'ADMIN',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($admin, [
            'received_by_user_id' => $admin->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('service-requests.edit', ['serviceRequest' => $serviceRequest->id]));

        $response->assertOk();

        $csp = (string) $response->headers->get('Content-Security-Policy', '');

        $this->assertStringContainsString("style-src 'self' 'unsafe-inline' https://fonts.googleapis.com", $csp);
        $this->assertStringContainsString("style-src-elem 'self' 'unsafe-inline' https://fonts.googleapis.com", $csp);
        $this->assertStringContainsString("font-src 'self' data: https://fonts.gstatic.com", $csp);
    }

    public function test_approved_assigned_request_moves_out_of_assigned_tab_and_into_archive(): void
    {
        $user = User::factory()->create([
            'role' => 'technical support',
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $approvedRequest = $this->createServiceRequest($user, [
            'reference_code' => 'SRF-ARCHIVE-APPROVED',
            'assigned_to_user_id' => $user->id,
            'approved_by_user_id' => $user->id,
            'status' => 'approved',
        ]);

        $activeAssignedRequest = $this->createServiceRequest($user, [
            'reference_code' => 'SRF-ASSIGNED-ACTIVE',
            'assigned_to_user_id' => $user->id,
            'assigned_by_user_id' => $user->id,
            'status' => 'checking',
        ]);

        $this
            ->actingAs($user)
            ->get(route('service-requests.index', ['assigned' => 'me']))
            ->assertOk()
            ->assertSee($activeAssignedRequest->reference_code)
            ->assertDontSee($approvedRequest->reference_code);

        $this
            ->actingAs($user)
            ->get(route('service-requests.index', ['status' => 'archived']))
            ->assertOk()
            ->assertSee($approvedRequest->reference_code);
    }

    public function test_assigned_tab_shows_assigner_name_and_role(): void
    {
        $assignee = User::factory()->create([
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);
        $assigner = User::factory()->create([
            'name' => 'Assigning Supervisor',
            'role' => 'supervisor',
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $this->createServiceRequest($assignee, [
            'reference_code' => 'SRF-ASSIGNED-BY',
            'assigned_to_user_id' => $assignee->id,
            'assigned_by_user_id' => $assigner->id,
            'status' => 'checking',
        ]);

        $this
            ->actingAs($assignee)
            ->get(route('service-requests.index', ['assigned' => 'me']))
            ->assertOk()
            ->assertSee('Assigned By')
            ->assertSee('Assigning Supervisor')
            ->assertSee('supervisor');
    }

    public function test_cross_department_assigned_request_is_visible_to_assignee(): void
    {
        $owner = User::factory()->create([
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);
        $assignee = User::factory()->create([
            'department' => 'HR',
            'department_status' => 'approved',
        ]);
        $assigner = User::factory()->create([
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($owner, [
            'reference_code' => 'SRF-CROSS-DEPT-ASSIGNED',
            'department_code' => 'KMITS',
            'received_by_user_id' => $owner->id,
            'assigned_to_user_id' => $assignee->id,
            'assigned_by_user_id' => $assigner->id,
            'status' => 'checking',
        ]);

        $this
            ->actingAs($assignee)
            ->get(route('service-requests.index', ['assigned' => 'me']))
            ->assertOk()
            ->assertSee($serviceRequest->reference_code);

        $this
            ->actingAs($assignee)
            ->get(route('service-requests.edit', $serviceRequest))
            ->assertOk();
    }

    public function test_active_request_can_be_received_and_moves_to_receive_tab(): void
    {
        $user = User::factory()->create([
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($user, [
            'reference_code' => 'SRF-RECEIVE-ME',
            'received_by_user_id' => null,
            'assigned_to_user_id' => null,
            'status' => 'pending',
        ]);

        $this
            ->actingAs($user)
            ->get(route('service-requests.index'))
            ->assertOk()
            ->assertSee($serviceRequest->reference_code)
            ->assertSee('Receive')
            ->assertDontSee('class="auth-link">Open</a>', false);

        $this
            ->actingAs($user)
            ->patch(route('service-requests.receive', $serviceRequest))
            ->assertRedirect(route('service-requests.index', ['received' => 'me']));

        $serviceRequest->refresh();
        $this->assertSame($user->id, (int) $serviceRequest->received_by_user_id);
        $this->assertSame($user->id, (int) $serviceRequest->assigned_to_user_id);

        $this
            ->actingAs($user)
            ->get(route('service-requests.index'))
            ->assertOk()
            ->assertDontSee($serviceRequest->reference_code);

        $this
            ->actingAs($user)
            ->get(route('service-requests.index', ['received' => 'me']))
            ->assertOk()
            ->assertSee($serviceRequest->reference_code)
            ->assertSee('Open');
    }

    public function test_action_log_officer_defaults_to_current_user_name_when_row_is_complete(): void
    {
        $user = User::factory()->create([
            'name' => 'Current Action User',
            'role' => 'technical support',
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($user, [
            'status' => 'checking',
            'received_by_user_id' => $user->id,
        ]);
        $expectedRedirect = route('service-requests.edit', $serviceRequest);

        $this
            ->actingAs($user)
            ->put(route('service-requests.update', $serviceRequest), [
                'action_log_date' => [now()->toDateString()],
                'action_log_time' => ['08:30'],
                'action_log_action_date' => [now()->toDateString()],
                'action_log_action_time' => ['09:00'],
                'action_log_action_taken' => ['Checked network request.'],
                'action_log_action_officer' => [''],
            ])
            ->assertRedirect($expectedRedirect);

        $serviceRequest->refresh();

        $this->assertSame('Current Action User', $serviceRequest->action_logs[0]['action_officer'] ?? null);
        $this->assertSame($user->id, (int) ($serviceRequest->action_logs[0]['action_user_id'] ?? 0));
    }

    public function test_action_log_officer_is_not_defaulted_when_row_is_incomplete(): void
    {
        $user = User::factory()->create([
            'name' => 'Current Action User',
            'role' => 'technical support',
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($user, [
            'status' => 'checking',
            'received_by_user_id' => $user->id,
        ]);
        $expectedRedirect = route('service-requests.edit', $serviceRequest);

        $this
            ->actingAs($user)
            ->put(route('service-requests.update', $serviceRequest), [
                'action_log_date' => [now()->toDateString()],
                'action_log_time' => ['08:30'],
                'action_log_action_date' => [''],
                'action_log_action_time' => [''],
                'action_log_action_taken' => [''],
                'action_log_action_officer' => [''],
            ])
            ->assertRedirect($expectedRedirect);

        $serviceRequest->refresh();

        $this->assertSame('', (string) ($serviceRequest->action_logs[0]['action_officer'] ?? ''));
        $this->assertSame($user->id, (int) ($serviceRequest->action_logs[0]['action_user_id'] ?? 0));
    }

    public function test_reassigned_incomplete_action_log_row_can_be_completed_by_new_assignee(): void
    {
        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);
        $admin = User::factory()->create([
            'name' => 'Assigned Admin',
            'role' => 'admin',
            'department' => 'ADMIN',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($supervisor, [
            'status' => 'checking',
            'received_by_user_id' => $supervisor->id,
            'assigned_to_user_id' => $admin->id,
            'assigned_by_user_id' => $supervisor->id,
            'action_logs' => [[
                'date' => '2026-06-02',
                'time' => '11:56',
                'action_date' => null,
                'action_time' => null,
                'action_taken' => null,
                'action_officer' => null,
                'signature' => null,
                'signature_user_id' => null,
                'action_user_id' => $supervisor->id,
            ]],
        ]);
        $expectedRedirect = route('service-requests.edit', $serviceRequest);

        $this
            ->actingAs($admin)
            ->put(route('service-requests.update', $serviceRequest), [
                'action_log_date' => ['2026-06-02'],
                'action_log_time' => ['11:56'],
                'action_log_action_date' => ['2026-06-03'],
                'action_log_action_time' => ['09:30'],
                'action_log_action_taken' => ['Completed reassigned work.'],
                'action_log_action_officer' => [''],
            ])
            ->assertRedirect($expectedRedirect);

        $serviceRequest->refresh();

        $this->assertSame('2026-06-03', $serviceRequest->action_logs[0]['action_date'] ?? null);
        $this->assertSame('09:30', $serviceRequest->action_logs[0]['action_time'] ?? null);
        $this->assertSame('Completed reassigned work.', $serviceRequest->action_logs[0]['action_taken'] ?? null);
        $this->assertSame('Assigned Admin', $serviceRequest->action_logs[0]['action_officer'] ?? null);
        $this->assertSame($admin->id, (int) ($serviceRequest->action_logs[0]['action_work_user_id'] ?? 0));
    }

    public function test_received_date_and_time_are_not_overwritten_by_another_user(): void
    {
        $receiver = User::factory()->create([
            'role' => 'supervisor',
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);
        $admin = User::factory()->create([
            'name' => 'Assigned Admin',
            'role' => 'admin',
            'department' => 'ADMIN',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($receiver, [
            'status' => 'checking',
            'received_by_user_id' => $receiver->id,
            'assigned_to_user_id' => $admin->id,
            'assigned_by_user_id' => $receiver->id,
            'action_logs' => [[
                'date' => '2026-06-02',
                'time' => '11:56',
                'action_date' => null,
                'action_time' => null,
                'action_taken' => null,
                'action_officer' => null,
                'signature' => null,
                'signature_user_id' => null,
                'action_user_id' => $receiver->id,
            ]],
        ]);

        $this
            ->actingAs($admin)
            ->put(route('service-requests.update', $serviceRequest), [
                'action_log_date' => ['2026-06-04'],
                'action_log_time' => ['12:00'],
                'action_log_action_date' => ['2026-06-03'],
                'action_log_action_time' => ['09:30'],
                'action_log_action_taken' => ['Completed after receiving.'],
                'action_log_action_officer' => [''],
            ])
            ->assertRedirect(route('service-requests.edit', $serviceRequest));

        $serviceRequest->refresh();

        $this->assertSame('2026-06-02', $serviceRequest->action_logs[0]['date'] ?? null);
        $this->assertSame('11:56', $serviceRequest->action_logs[0]['time'] ?? null);
        $this->assertSame('2026-06-03', $serviceRequest->action_logs[0]['action_date'] ?? null);
        $this->assertSame('09:30', $serviceRequest->action_logs[0]['action_time'] ?? null);
        $this->assertSame('Completed after receiving.', $serviceRequest->action_logs[0]['action_taken'] ?? null);
    }

    public function test_action_work_fields_are_not_overwritten_by_another_user(): void
    {
        $receiver = User::factory()->create([
            'role' => 'supervisor',
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);
        $admin = User::factory()->create([
            'name' => 'Assigned Admin',
            'role' => 'admin',
            'department' => 'ADMIN',
            'department_status' => 'approved',
        ]);
        $otherAdmin = User::factory()->create([
            'name' => 'Other Admin',
            'role' => 'admin',
            'department' => 'ADMIN',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($receiver, [
            'status' => 'checking',
            'received_by_user_id' => $receiver->id,
            'assigned_to_user_id' => $otherAdmin->id,
            'assigned_by_user_id' => $receiver->id,
            'action_logs' => [[
                'date' => '2026-06-02',
                'time' => '11:56',
                'action_date' => '2026-06-03',
                'action_time' => '09:30',
                'action_taken' => 'Completed by original assignee.',
                'action_officer' => 'Assigned Admin',
                'signature' => null,
                'signature_user_id' => null,
                'action_user_id' => $receiver->id,
                'action_work_user_id' => $admin->id,
            ]],
        ]);

        $this
            ->actingAs($otherAdmin)
            ->put(route('service-requests.update', $serviceRequest), [
                'action_log_date' => ['2026-06-02'],
                'action_log_time' => ['11:56'],
                'action_log_action_date' => ['2026-06-04'],
                'action_log_action_time' => ['10:45'],
                'action_log_action_taken' => ['Overwritten by other admin.'],
                'action_log_action_officer' => ['Other Admin'],
            ])
            ->assertRedirect(route('service-requests.edit', $serviceRequest));

        $serviceRequest->refresh();

        $this->assertSame('2026-06-03', $serviceRequest->action_logs[0]['action_date'] ?? null);
        $this->assertSame('09:30', $serviceRequest->action_logs[0]['action_time'] ?? null);
        $this->assertSame('Completed by original assignee.', $serviceRequest->action_logs[0]['action_taken'] ?? null);
        $this->assertSame('Assigned Admin', $serviceRequest->action_logs[0]['action_officer'] ?? null);
        $this->assertSame($admin->id, (int) ($serviceRequest->action_logs[0]['action_work_user_id'] ?? 0));
    }

    public function test_action_work_owner_can_edit_their_action_work_fields(): void
    {
        $receiver = User::factory()->create([
            'role' => 'supervisor',
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);
        $admin = User::factory()->create([
            'name' => 'Assigned Admin',
            'role' => 'admin',
            'department' => 'ADMIN',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($receiver, [
            'status' => 'checking',
            'received_by_user_id' => $receiver->id,
            'assigned_to_user_id' => $admin->id,
            'assigned_by_user_id' => $receiver->id,
            'action_logs' => [[
                'date' => '2026-06-02',
                'time' => '11:56',
                'action_date' => '2026-06-03',
                'action_time' => '09:30',
                'action_taken' => 'Original action work.',
                'action_officer' => 'Assigned Admin',
                'signature' => null,
                'signature_user_id' => null,
                'action_user_id' => $receiver->id,
                'action_work_user_id' => $admin->id,
            ]],
        ]);

        $this
            ->actingAs($admin)
            ->put(route('service-requests.update', $serviceRequest), [
                'action_log_date' => ['2026-06-04'],
                'action_log_time' => ['12:00'],
                'action_log_action_date' => ['2026-06-05'],
                'action_log_action_time' => ['14:15'],
                'action_log_action_taken' => ['Updated own action work.'],
                'action_log_action_officer' => ['Assigned Admin'],
            ])
            ->assertRedirect(route('service-requests.edit', $serviceRequest));

        $serviceRequest->refresh();

        $this->assertSame('2026-06-02', $serviceRequest->action_logs[0]['date'] ?? null);
        $this->assertSame('11:56', $serviceRequest->action_logs[0]['time'] ?? null);
        $this->assertSame('2026-06-05', $serviceRequest->action_logs[0]['action_date'] ?? null);
        $this->assertSame('14:15', $serviceRequest->action_logs[0]['action_time'] ?? null);
        $this->assertSame('Updated own action work.', $serviceRequest->action_logs[0]['action_taken'] ?? null);
        $this->assertSame($admin->id, (int) ($serviceRequest->action_logs[0]['action_work_user_id'] ?? 0));
    }

    public function test_reassigned_incomplete_signed_action_log_row_can_still_be_completed(): void
    {
        $signaturePath = 'service-request-signatures/' . Str::uuid()->toString() . '.encsig';
        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);
        $admin = User::factory()->create([
            'name' => 'Assigned Admin',
            'role' => 'admin',
            'department' => 'ADMIN',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($supervisor, [
            'status' => 'checking',
            'received_by_user_id' => $supervisor->id,
            'assigned_to_user_id' => $admin->id,
            'assigned_by_user_id' => $supervisor->id,
            'action_logs' => [[
                'date' => '2026-06-02',
                'time' => '11:56',
                'action_date' => null,
                'action_time' => null,
                'action_taken' => null,
                'action_officer' => null,
                'signature' => $signaturePath,
                'signature_user_id' => $supervisor->id,
                'action_user_id' => $supervisor->id,
            ]],
        ]);
        $expectedRedirect = route('service-requests.edit', $serviceRequest);

        $this
            ->actingAs($admin)
            ->put(route('service-requests.update', $serviceRequest), [
                'action_log_date' => ['2026-06-02'],
                'action_log_time' => ['11:56'],
                'action_log_action_date' => ['2026-06-03'],
                'action_log_action_time' => ['09:30'],
                'action_log_action_taken' => ['Completed signed partial row.'],
                'action_log_action_officer' => [''],
            ])
            ->assertRedirect($expectedRedirect);

        $serviceRequest->refresh();

        $this->assertSame('2026-06-03', $serviceRequest->action_logs[0]['action_date'] ?? null);
        $this->assertSame('09:30', $serviceRequest->action_logs[0]['action_time'] ?? null);
        $this->assertSame('Completed signed partial row.', $serviceRequest->action_logs[0]['action_taken'] ?? null);
        $this->assertSame('Assigned Admin', $serviceRequest->action_logs[0]['action_officer'] ?? null);
        $this->assertSame($signaturePath, $serviceRequest->action_logs[0]['signature'] ?? null);
        $this->assertSame($supervisor->id, (int) ($serviceRequest->action_logs[0]['signature_user_id'] ?? 0));
    }

    public function test_complete_action_log_row_is_not_overwritten_after_reassignment(): void
    {
        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);
        $admin = User::factory()->create([
            'name' => 'Assigned Admin',
            'role' => 'admin',
            'department' => 'ADMIN',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($supervisor, [
            'status' => 'checking',
            'received_by_user_id' => $supervisor->id,
            'assigned_to_user_id' => $admin->id,
            'assigned_by_user_id' => $supervisor->id,
            'action_logs' => [[
                'date' => '2026-06-02',
                'time' => '11:56',
                'action_date' => '2026-06-03',
                'action_time' => '09:30',
                'action_taken' => 'Original completed work.',
                'action_officer' => 'Original Officer',
                'signature' => null,
                'signature_user_id' => null,
                'action_user_id' => $supervisor->id,
            ]],
        ]);
        $expectedRedirect = route('service-requests.edit', $serviceRequest);

        $this
            ->actingAs($admin)
            ->put(route('service-requests.update', $serviceRequest), [
                'action_log_date' => ['2026-06-04'],
                'action_log_time' => ['12:00'],
                'action_log_action_date' => ['2026-06-05'],
                'action_log_action_time' => ['13:00'],
                'action_log_action_taken' => ['Overwritten work.'],
                'action_log_action_officer' => ['Assigned Admin'],
            ])
            ->assertRedirect($expectedRedirect);

        $serviceRequest->refresh();

        $this->assertSame('2026-06-02', $serviceRequest->action_logs[0]['date'] ?? null);
        $this->assertSame('11:56', $serviceRequest->action_logs[0]['time'] ?? null);
        $this->assertSame('2026-06-03', $serviceRequest->action_logs[0]['action_date'] ?? null);
        $this->assertSame('09:30', $serviceRequest->action_logs[0]['action_time'] ?? null);
        $this->assertSame('Original completed work.', $serviceRequest->action_logs[0]['action_taken'] ?? null);
        $this->assertSame('Original Officer', $serviceRequest->action_logs[0]['action_officer'] ?? null);
    }

    public function test_supervisor_receive_sets_noted_by_name_when_blank(): void
    {
        $supervisor = User::factory()->create([
            'name' => 'Receiving Supervisor',
            'role' => 'supervisor',
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($supervisor, [
            'received_by_user_id' => null,
            'assigned_to_user_id' => null,
            'noted_by_name' => null,
            'status' => 'pending',
        ]);

        $this
            ->actingAs($supervisor)
            ->patch(route('service-requests.receive', $serviceRequest))
            ->assertRedirect(route('service-requests.index', ['received' => 'me']));

        $serviceRequest->refresh();

        $this->assertSame('Receiving Supervisor', $serviceRequest->noted_by_name);
    }

    public function test_assigning_to_supervisor_sets_noted_by_name_without_overwriting_existing_value(): void
    {
        $handler = User::factory()->create([
            'role' => 'technical support',
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);
        $supervisor = User::factory()->create([
            'name' => 'Assigned Supervisor',
            'role' => 'supervisor',
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($handler, [
            'received_by_user_id' => $handler->id,
            'assigned_to_user_id' => $handler->id,
            'noted_by_name' => null,
            'status' => 'checking',
        ]);

        $this
            ->actingAs($handler)
            ->patch(route('service-requests.assign', $serviceRequest), [
                'assigned_to_user_id' => $supervisor->id,
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $serviceRequest->refresh();

        $this->assertSame('Assigned Supervisor', $serviceRequest->noted_by_name);

        $otherSupervisor = User::factory()->create([
            'name' => 'Other Supervisor',
            'role' => 'supervisor',
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $serviceRequest->forceFill([
            'received_by_user_id' => $supervisor->id,
            'assigned_to_user_id' => $supervisor->id,
            'noted_by_name' => 'Existing Supervisor',
        ])->save();

        $this
            ->actingAs($supervisor)
            ->patch(route('service-requests.assign', $serviceRequest), [
                'assigned_to_user_id' => $otherSupervisor->id,
            ])
            ->assertRedirect();

        $serviceRequest->refresh();

        $this->assertSame('Existing Supervisor', $serviceRequest->noted_by_name);
    }

    public function test_signature_paths_reject_traversal_and_non_signature_names(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $validPath = 'service-request-signatures/' . Str::uuid()->toString() . '.encsig';
        $traversalPath = 'service-request-signatures/../../other-file.encsig';
        $wrongNamePath = 'service-request-signatures/not-a-uuid.encsig';

        Storage::disk('local')->put($wrongNamePath, 'payload');

        $this->assertTrue(EncryptedSignature::isSignaturePath($validPath));
        $this->assertFalse(EncryptedSignature::isSignaturePath($traversalPath));
        $this->assertFalse(EncryptedSignature::isSignaturePath($wrongNamePath));
        $this->assertNull(EncryptedSignature::readBinaryFromPath($traversalPath));

        EncryptedSignature::deletePath($traversalPath);
        EncryptedSignature::deletePath($wrongNamePath);

        Storage::disk('local')->assertExists($wrongNamePath);
    }

    public function test_unsafe_existing_action_signature_path_is_not_preserved_on_save(): void
    {
        $user = User::factory()->create([
            'role' => 'technical support',
            'department' => 'KMITS',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($user, [
            'status' => 'checking',
            'received_by_user_id' => $user->id,
            'assigned_to_user_id' => $user->id,
            'action_logs' => [[
                'date' => '2026-06-02',
                'time' => '11:56',
                'action_date' => '2026-06-03',
                'action_time' => '09:30',
                'action_taken' => 'Existing action.',
                'action_officer' => 'Current Action User',
                'signature' => 'service-request-signatures/../../unsafe.encsig',
                'signature_user_id' => $user->id,
                'action_user_id' => $user->id,
                'action_work_user_id' => $user->id,
            ]],
        ]);

        $this
            ->actingAs($user)
            ->put(route('service-requests.update', $serviceRequest), [
                'action_log_date' => ['2026-06-02'],
                'action_log_time' => ['11:56'],
                'action_log_action_date' => ['2026-06-03'],
                'action_log_action_time' => ['09:30'],
                'action_log_action_taken' => ['Existing action.'],
                'action_log_action_officer' => ['Current Action User'],
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $serviceRequest->refresh();

        $this->assertSame('', (string) ($serviceRequest->action_logs[0]['signature'] ?? ''));
    }

    private function createServiceRequest(User $owner, array $overrides = []): ServiceRequest
    {
        return ServiceRequest::query()->create(array_merge([
            'reference_code' => 'SRF-TEST-' . strtoupper(Str::random(10)),
            'request_date' => now()->toDateString(),
            'department_code' => (string) ($owner->department ?: 'KMITS'),
            'request_category' => 'GENERAL',
            'application_system_name' => 'System Alpha',
            'contact_last_name' => 'Doe',
            'contact_first_name' => 'Jane',
            'office' => 'Main Office',
            'address' => '123 Test Street',
            'mobile_no' => '09171234567',
            'email_address' => 'owner.' . strtolower(Str::random(6)) . '@example.com',
            'description_request' => 'Testing security hardening behavior.',
            'approved_by_name' => 'Approver Name',
            'approved_by_signature' => '',
            'approved_by_position' => 'Manager',
            'approved_date' => now()->toDateString(),
            'status' => 'pending',
            'user_id' => $owner->id,
        ], $overrides));
    }

    private function validStorePayload(User $departmentUser, string $submissionToken): array
    {
        return [
            'submission_token' => $submissionToken,
            'department_user_id' => (string) $departmentUser->id,
            'department_code' => (string) ($departmentUser->department ?: 'KMITS'),
            'request_date' => now()->toDateString(),
            'request_category' => 'Technical Assistance',
            'application_system_name' => 'System Alpha',
            'expected_completion_date' => now()->addDays(3)->toDateString(),
            'expected_completion_time' => '10:00',
            'contact_last_name' => 'Doe',
            'contact_first_name' => 'Jane',
            'contact_middle_name' => 'Q',
            'contact_suffix_name' => '',
            'office' => 'Main Office',
            'address' => '123 Test Street',
            'landline' => '1234567',
            'mobile_no' => '09171234567',
            'email_address' => 'owner@example.com',
            'description_request' => 'Testing duplicate submission protection.',
            'approved_by_name' => 'Approver Name',
            'approved_by_position' => 'Manager',
            'approved_date' => now()->toDateString(),
            'kmits_date' => now()->toDateString(),
            'time_received' => now()->format('H:i'),
            'approved_by_signature_mode' => 'draw',
            'approved_by_signature_drawn' => '',
        ];
    }
}
