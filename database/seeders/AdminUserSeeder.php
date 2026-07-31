<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'norsubscojt@gmail.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('norsubscojt2026'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'coordinator@norsu.edu.ph'],
            [
                'name' => 'OJT Coordinator',
                'password' => Hash::make('norsubscojt2026'),
                'role' => 'coordinator',
            ]
        );
    }
}
