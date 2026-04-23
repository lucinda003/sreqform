<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@.com',
            'password' => Hash::make('password'),
            'department' => 'ADMIN',
            'department_status' => 'approved',
            'email_verified_at' => now(),
        ]);
    }
}
