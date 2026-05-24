<?php

namespace Database\Seeders;

use App\Models\Batch;
use Illuminate\Database\Seeder;

class BatchSeeder extends Seeder
{
    public function run(): void
    {
        Batch::firstOrCreate(
            ['name' => 'Batch 1', 'year' => 2025],
            [
                'institution_code' => 'POLRI',
                'description'      => 'Angkatan pertama tahun 2025',
                'max_parameters'   => 4,
                'started_at'       => '2025-01-01',
                'ended_at'         => '2025-12-31',
            ]
        );

        Batch::firstOrCreate(
            ['name' => 'Batch 2', 'year' => 2025],
            [
                'institution_code' => 'POLRI',
                'description'      => 'Angkatan kedua tahun 2025',
                'max_parameters'   => 4,
                'started_at'       => '2025-07-01',
                'ended_at'         => '2025-12-31',
            ]
        );
    }
}
