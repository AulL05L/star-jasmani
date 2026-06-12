<?php

namespace Database\Seeders;

use App\Models\Athlete;
use App\Models\KebugaranPeriod;
use App\Models\KebugaranScore;
use App\Models\KebugaranSession;
use App\Models\User;
use Illuminate\Database\Seeder;

class KebugaranDummySeeder extends Seeder
{
    public function run(): void
    {
        // ── Akun 1: Pria ──────────────────────────────────────────────────────
        $user1 = User::create([
            'name'      => 'Budi Santoso',
            'email'     => 'budi.kebugaran@test.com',
            'password'  => 'kebugaran123',
            'role'      => 'member',
            'is_active' => true,
        ]);

        $athlete1 = Athlete::create([
            'user_id'    => $user1->id,
            'gender'     => 'pria',
            'program'    => 'kebugaran',
            'birth_date' => '2000-03-15',
            'height_cm'  => 172,
            'weight_kg'  => 68,
            'phone'      => '08123456789',
        ]);

        $period1 = KebugaranPeriod::create([
            'athlete_id' => $athlete1->id,
            'name'       => 'Periode 1 — Jun 2025',
            'start_date' => '2025-06-01',
            'end_date'   => '2025-08-31',
            'notes'      => 'Program kebugaran dasar 3 bulan',
        ]);

        $this->seedSessions($period1->id, [
            ['number' => 1, 'date' => '2025-06-10', 'scores' => [
                'bmi' => 23.0, 'komposisi_otot' => 38.5, 'komposisi_lemak' => 22.1,
                'push_up' => 18, 'sit_up' => 22, 'squat' => 28, 'sit_and_reach' => 25,
            ]],
            ['number' => 2, 'date' => '2025-07-10', 'scores' => [
                'bmi' => 22.8, 'komposisi_otot' => 40.1, 'komposisi_lemak' => 20.5,
                'push_up' => 24, 'sit_up' => 29, 'squat' => 35, 'sit_and_reach' => 28,
            ]],
            ['number' => 3, 'date' => '2025-08-10', 'scores' => [
                'bmi' => 22.5, 'komposisi_otot' => 42.3, 'komposisi_lemak' => 18.9,
                'push_up' => 31, 'sit_up' => 36, 'squat' => 42, 'sit_and_reach' => 32,
            ]],
        ]);

        $this->command->info("✓ Budi Santoso — {$user1->email} | password: kebugaran123");

        // ── Akun 2: Wanita ────────────────────────────────────────────────────
        $user2 = User::create([
            'name'      => 'Sari Dewi',
            'email'     => 'sari.kebugaran@test.com',
            'password'  => 'kebugaran123',
            'role'      => 'member',
            'is_active' => true,
        ]);

        $athlete2 = Athlete::create([
            'user_id'    => $user2->id,
            'gender'     => 'wanita',
            'program'    => 'kebugaran',
            'birth_date' => '2001-07-20',
            'height_cm'  => 158,
            'weight_kg'  => 53,
            'phone'      => '08198765432',
        ]);

        $period2 = KebugaranPeriod::create([
            'athlete_id' => $athlete2->id,
            'name'       => 'Periode 1 — Jun 2025',
            'start_date' => '2025-06-01',
            'end_date'   => '2025-08-31',
        ]);

        $this->seedSessions($period2->id, [
            ['number' => 1, 'date' => '2025-06-12', 'scores' => [
                'bmi' => 21.2, 'komposisi_otot' => 32.0, 'komposisi_lemak' => 29.5,
                'push_up' => 10, 'sit_up' => 18, 'squat' => 20, 'sit_and_reach' => 30,
            ]],
            ['number' => 2, 'date' => '2025-07-12', 'scores' => [
                'bmi' => 21.0, 'komposisi_otot' => 33.8, 'komposisi_lemak' => 27.2,
                'push_up' => 14, 'sit_up' => 23, 'squat' => 27, 'sit_and_reach' => 34,
            ]],
            ['number' => 3, 'date' => '2025-08-12', 'scores' => [
                'bmi' => 20.8, 'komposisi_otot' => 35.5, 'komposisi_lemak' => 25.1,
                'push_up' => 18, 'sit_up' => 28, 'squat' => 33, 'sit_and_reach' => 38,
            ]],
        ]);

        $this->command->info("✓ Sari Dewi — {$user2->email} | password: kebugaran123");
    }

    private function seedSessions(int $periodId, array $sessions): void
    {
        foreach ($sessions as $s) {
            $session = KebugaranSession::create([
                'period_id'      => $periodId,
                'session_number' => $s['number'],
                'date'           => $s['date'],
            ]);
            foreach ($s['scores'] as $param => $value) {
                KebugaranScore::create([
                    'session_id' => $session->id,
                    'parameter'  => $param,
                    'value'      => $value,
                ]);
            }
        }
    }
}
