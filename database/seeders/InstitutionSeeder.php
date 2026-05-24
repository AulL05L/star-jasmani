<?php

namespace Database\Seeders;

use App\Models\Institution;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    public function run(): void
    {
        $institutions = [
            ['code' => 'POLRI',  'name' => 'Kepolisian Negara Republik Indonesia', 'ukg_weight' => 80.00, 'renang_weight' => 20.00],
            ['code' => 'TNI-AD', 'name' => 'Tentara Nasional Indonesia Angkatan Darat', 'ukg_weight' => 80.00, 'renang_weight' => 20.00],
            ['code' => 'TNI-AL', 'name' => 'Tentara Nasional Indonesia Angkatan Laut', 'ukg_weight' => 70.00, 'renang_weight' => 30.00],
            ['code' => 'TNI-AU', 'name' => 'Tentara Nasional Indonesia Angkatan Udara', 'ukg_weight' => 80.00, 'renang_weight' => 20.00],
        ];

        foreach ($institutions as $data) {
            Institution::firstOrCreate(['code' => $data['code']], $data);
        }
    }
}
