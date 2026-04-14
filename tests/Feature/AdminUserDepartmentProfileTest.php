<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserDepartmentProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_created_user_keeps_department_on_profile(): void
    {
        $admin = User::factory()->create([
            'department' => 'ADMIN',
            'department_status' => 'approved',
        ]);

        $response = $this
            ->actingAs($admin)
            ->post('/admin/users', [
                'name' => 'KMITS Staff',
                'email' => 'kmits.staff@example.com',
                'password' => 'password123',
                'department_code' => 'KMITS',
                'department_status' => 'approved',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/admin/users');

        $createdUser = User::query()->where('email', 'kmits.staff@example.com')->first();

        $this->assertNotNull($createdUser);
        $this->assertSame('KMITS', $createdUser->department);

        $profileResponse = $this
            ->actingAs($createdUser)
            ->get('/profile');

        $profileResponse
            ->assertOk()
            ->assertSee('value="KMITS"', false)
            ->assertDontSee('value="ADMIN"', false);
    }

    public function test_admin_can_create_user_with_pending_department_status(): void
    {
        $admin = User::factory()->create([
            'department' => 'ADMIN',
            'department_status' => 'approved',
        ]);

        $response = $this
            ->actingAs($admin)
            ->post('/admin/users', [
                'name' => 'Pending Staff',
                'email' => 'pending.staff@example.com',
                'password' => 'password123',
                'department_code' => 'MOTION',
                'department_status' => 'pending',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/admin/users');

        $createdUser = User::query()->where('email', 'pending.staff@example.com')->first();

        $this->assertNotNull($createdUser);
        $this->assertSame('MOTION', $createdUser->department);
        $this->assertSame('pending', $createdUser->department_status);
    }
}
