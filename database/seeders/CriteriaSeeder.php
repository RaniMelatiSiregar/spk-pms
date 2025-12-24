<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Criteria;
use Illuminate\Support\Str;

class CriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $periode_id = 1; 

        $datas = [
            [
                'code'       => 'C1',
                'name'       => 'Harga (Rp/kg)',
                'type'       => 'Cost',
                'weight'     => 0.30,
            ],
            [
                'code'       => 'C2',
                'name'       => 'Volume Pasokan (kg/bulan)',
                'type'       => 'Benefit',
                'weight'     => 0.25,
            ],
            [
                'code'       => 'C3',
                'name'       => 'Ketepatan Waktu (%)',
                'type'       => 'Benefit',
                'weight'     => 0.25,
            ],
            [
                'code'       => 'C4',
                'name'       => 'Frekuensi Pengiriman (kali/bulan)',
                'type'       => 'Benefit',
                'weight'     => 0.20,
            ],
        ];

        foreach ($datas as $d) {
            Criteria::create([
                'periode_id' => $periode_id,
                'code'       => $d['code'],
                'name'       => $d['name'],
                'type'       => $d['type'],
                'weight'     => $d['weight'],
                'slug'       => Str::slug($d['name']),
            ]);
        }
    }
}
