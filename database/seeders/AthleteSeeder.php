<?php

namespace Database\Seeders;

use App\Models\Athlete;
use App\Models\Batch;
use App\Models\User;
use Illuminate\Database\Seeder;

class AthleteSeeder extends Seeder
{
    public function run(): void
    {
        $batch1 = Batch::where('name', 'Batch 1')->where('year', 2025)->first();

        // Member 1 — Pria
        $user1 = User::create([
            'name'      => 'Budi Santoso',
            'email'     => 'budi@starjasmani.com',
            'password'  => 'member12345',
            'role'      => 'member',
            'is_active' => true,
        ]);
        Athlete::create([
            'user_id'            => $user1->id,
            'gender'             => 'pria',
            'birth_date'         => '2000-05-15',
            'phone'              => '081234567890',
            'height_cm'          => 172,
            'weight_kg'          => 68,
            'target_institution' => 'POLRI',
            'batch'              => 'Batch 1',
            'batch_id'           => $batch1?->id,
        ]);

        // Member 2 — Wanita
        $user2 = User::create([
            'name'      => 'Siti Rahayu',
            'email'     => 'siti@starjasmani.com',
            'password'  => 'member12345',
            'role'      => 'member',
            'is_active' => true,
        ]);
        Athlete::create([
            'user_id'            => $user2->id,
            'gender'             => 'wanita',
            'birth_date'         => '2001-08-20',
            'phone'              => '081234567891',
            'height_cm'          => 158,
            'weight_kg'          => 52,
            'target_institution' => 'POLRI',
            'batch'              => 'Batch 1',
            'batch_id'           => $batch1?->id,
        ]);

        // Member 3 — Pria
        $user3 = User::create([
            'name'      => 'Rizky Pratama',
            'email'     => 'rizky@starjasmani.com',
            'password'  => 'member12345',
            'role'      => 'member',
            'is_active' => true,
        ]);
        Athlete::create([
            'user_id'            => $user3->id,
            'gender'             => 'pria',
            'birth_date'         => '1999-03-10',
            'phone'              => '081234567892',
            'height_cm'          => 175,
            'weight_kg'          => 72,
            'target_institution' => 'POLRI',
            'batch'              => 'Batch 1',
            'batch_id'           => $batch1?->id,
        ]);
    }
}
