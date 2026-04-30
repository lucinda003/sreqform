<?php

namespace Tests\Feature;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_locked_profile_signature_cannot_be_changed_by_profile_update(): void
    {
        $user = User::factory()->create([
            'profile_signature' => 'service-request-signatures/existing.encsig',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'profile_signature_drawn' => 'data:image/png;base64,' . base64_encode('changed'),
                'profile_signature_clear' => '1',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertSame('service-request-signatures/existing.encsig', $user->refresh()->profile_signature);
    }

    public function test_profile_signature_unlock_code_can_be_sent(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'signature-owner@example.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('profile.signature.send-code'));

        $response
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status', 'Signature unlock code sent to your email.')
            ->assertRedirect('/profile');

        $this->assertTrue(Cache::has('profile-signature-code:' . $user->id));
    }

    public function test_profile_signature_unlock_code_requires_valid_email(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'signature-owner@example.com',
        ]);

        $user->forceFill(['email' => ''])->save();

        $response = $this
            ->actingAs($user)
            ->post(route('profile.signature.send-code'));

        $response
            ->assertSessionHasErrors('profile_signature_code')
            ->assertRedirect('/profile');

        $this->assertFalse(Cache::has('profile-signature-code:' . $user->id));
    }

    public function test_profile_signature_unlock_timer_is_visible_when_unlocked(): void
    {
        $user = User::factory()->create();
        $unlockedUntil = now()->addMinutes(15)->timestamp;

        $response = $this
            ->actingAs($user)
            ->withSession(['profile_signature_unlocked_until:' . $user->id => $unlockedUntil])
            ->get('/profile');

        $response
            ->assertOk()
            ->assertSee('id="profile-signature-unlock-timer"', false)
            ->assertSee('data-unlocked-until="' . $unlockedUntil . '"', false)
            ->assertSee('Unlocked: --:-- left');
    }

    public function test_print_view_can_use_saved_profile_signature(): void
    {
        $profileSignature = 'data:image/png;base64,' . base64_encode('profile-signature');
        $user = User::factory()->create([
            'department' => 'KMITS',
            'department_status' => 'approved',
            'profile_signature' => $profileSignature,
        ]);

        $serviceRequest = ServiceRequest::create([
            'reference_code' => 'REQ-PRINT-SIG',
            'request_date' => now()->toDateString(),
            'department_code' => 'KMITS',
            'contact_last_name' => 'Doe',
            'contact_first_name' => 'Jane',
            'office' => 'KMITS',
            'address' => 'DOH',
            'mobile_no' => '09171234567',
            'description_request' => 'Test request',
            'approved_by_name' => 'Approver',
            'approved_by_signature' => '',
            'approved_by_position' => 'Supervisor',
            'approved_date' => now()->toDateString(),
            'status' => 'checking',
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('service-requests.print', $serviceRequest));

        $response
            ->assertOk()
            ->assertSee('id="use-profile-signature"', false)
            ->assertSee('Use My Saved Signature')
            ->assertSee(base64_encode('profile-signature'), false);
    }

    public function test_edit_print_preview_shows_saved_profile_signature_button(): void
    {
        $user = User::factory()->create([
            'department' => 'KMITS',
            'department_status' => 'approved',
            'profile_signature' => 'data:image/png;base64,' . base64_encode('profile-signature'),
        ]);

        $serviceRequest = ServiceRequest::create([
            'reference_code' => 'REQ-EDIT-PRINT-SIG',
            'request_date' => now()->toDateString(),
            'department_code' => 'KMITS',
            'contact_last_name' => 'Doe',
            'contact_first_name' => 'Jane',
            'office' => 'KMITS',
            'address' => 'DOH',
            'mobile_no' => '09171234567',
            'description_request' => 'Test request',
            'approved_by_name' => 'Approver',
            'approved_by_signature' => '',
            'approved_by_position' => 'Supervisor',
            'approved_date' => now()->toDateString(),
            'status' => 'checking',
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('service-requests.edit', $serviceRequest));

        $response
            ->assertOk()
            ->assertSee('id="print-preview-profile-signature"', false)
            ->assertSee('Use My Saved Signature');
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
