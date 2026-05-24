<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            InstitutionSeeder::class,
            BatchSeeder::class,
            AdminSeeder::class,
            AthleteSeeder::class,
        ]);
    }
}
