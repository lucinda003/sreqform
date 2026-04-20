<?php

namespace Tests\Feature;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_authenticated_edit_page_has_expected_csp_font_sources(): void
    {
        $admin = User::factory()->create([
            'department' => 'ADMIN',
            'department_status' => 'approved',
        ]);

        $serviceRequest = $this->createServiceRequest($admin);

        $response = $this
            ->actingAs($admin)
            ->get(route('service-requests.edit', ['serviceRequest' => $serviceRequest->id]));

        $response->assertOk();

        $csp = (string) $response->headers->get('Content-Security-Policy', '');

        $this->assertStringContainsString("style-src 'self' 'unsafe-inline' https://fonts.googleapis.com", $csp);
        $this->assertStringContainsString("style-src-elem 'self' 'unsafe-inline' https://fonts.googleapis.com", $csp);
        $this->assertStringContainsString("font-src 'self' data: https://fonts.gstatic.com", $csp);
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
}
