<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Periode;

class PeriodeSeeder extends Seeder
{
    public function run(): void
    {
        Periode::create([
            'name' => 'Periode Awal',
            'code' => 'PER0001',
            'start_date' => now()->subMonth(),
            'end_date' => now()->addMonth(),
            'is_active' => 1,
            'description' => 'Periode awal sistem',
        ]);
    }
}
