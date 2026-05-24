<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Password is auto-hashed by the model cast ('password' => 'hashed')
        // Set ADMIN_EMAIL and ADMIN_PASSWORD in .env before running in production
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@starjasmani.com')],
            [
                'name'      => 'Admin Star Jasmani',
                'password'  => env('ADMIN_PASSWORD', 'Ch@ngeMe!2025#Prod'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );
    }
}
