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
            ['email' => 'admin@norsu.edu.ph'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'coordinator@norsu.edu.ph'],
            [
                'name' => 'OJT Coordinator',
                'password' => Hash::make('password123'),
                'role' => 'coordinator',
            ]
        );
    }
}
