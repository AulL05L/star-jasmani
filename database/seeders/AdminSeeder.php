<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminSeeder extends Seeder
{
    /**
     * Membuat akun admin pertama.
     *
     * NILAINYA DIBACA LEWAT config(), BUKAN env().
     *
     * Sesudah `php artisan config:cache` Laravel tidak lagi memuat .env, sehingga
     * setiap panggilan env() di luar berkas config/ mengembalikan nilai bawaannya.
     * Versi sebelumnya memanggil env('ADMIN_PASSWORD', 'Ch@ngeMe!2025#Prod') di sini,
     * jadi pada produksi yang konfigurasinya sudah dibekukan akun admin terpasang
     * dengan sandi yang tertulis di repo ini — tanpa satu pun galat, tanpa satu pun
     * peringatan, dan baru ketahuan bila ada yang memeriksanya.
     */
    public function run(): void
    {
        $email = (string) config('app.admin_email');
        $sandi = config('app.admin_password');

        if (blank($sandi)) {
            if (app()->environment('production')) {
                throw new RuntimeException(
                    'ADMIN_PASSWORD wajib diisi di produksi. Penyemaian dihentikan supaya '
                    .'akun admin tidak terpasang dengan sandi bawaan. Bila variabelnya SUDAH '
                    .'terisi di .env, susun ulang cache konfigurasi lebih dulu dengan '
                    .'`php artisan config:cache` — yang terbaca adalah nilai yang dibekukan '
                    .'cache lama, bukan isi .env hari ini.'
                );
            }

            // Di luar produksi sandi bawaan tetap boleh ada: yang berbahaya hanyalah
            // ia sampai ke produksi, dan jalur itu sudah ditutup penjagaan di atas.
            $sandi = 'admin12345';
        }

        // Kata sandi di-hash otomatis oleh cast 'password' => 'hashed' pada model.
        User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin Star Jasmani',
                'password' => $sandi,
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}
