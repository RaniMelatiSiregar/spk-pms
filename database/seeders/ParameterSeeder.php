<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parameter;

class ParameterSeeder extends Seeder
{
    public function run(): void
    {
        $harga = [
            ['score'=>5,'operator'=>'<=','min_value'=>null,'max_value'=>180,'description'=>'≤180'],
            ['score'=>4,'operator'=>'between','min_value'=>181,'max_value'=>184,'description'=>'181–184'],
            ['score'=>3,'operator'=>'between','min_value'=>185,'max_value'=>189,'description'=>'185–189'],
            ['score'=>2,'operator'=>'between','min_value'=>190,'max_value'=>194,'description'=>'190–194'],
            ['score'=>1,'operator'=>'>=','min_value'=>195,'max_value'=>null,'description'=>'≥195'],
        ];

        $volume = [
            ['score'=>5,'operator'=>'>=','min_value'=>15000,'max_value'=>null,'description'=>'≥15000'],
            ['score'=>4,'operator'=>'between','min_value'=>10000,'max_value'=>14999,'description'=>'10000–14999'],
            ['score'=>3,'operator'=>'between','min_value'=>7000,'max_value'=>9999,'description'=>'7000–9999'],
            ['score'=>2,'operator'=>'between','min_value'=>4000,'max_value'=>6999,'description'=>'4000–6999'],
            ['score'=>1,'operator'=>'<=','min_value'=>null,'max_value'=>3999,'description'=>'<4000'],
        ];

        $ketepatan = [
            ['score'=>5,'operator'=>'>=','min_value'=>100,'max_value'=>null,'description'=>'≥100%'],
            ['score'=>4,'operator'=>'between','min_value'=>90,'max_value'=>99,'description'=>'90–99%'],
            ['score'=>3,'operator'=>'between','min_value'=>75,'max_value'=>89,'description'=>'75–89%'],
            ['score'=>2,'operator'=>'between','min_value'=>50,'max_value'=>74,'description'=>'50–74%'],
            ['score'=>1,'operator'=>'<=','min_value'=>null,'max_value'=>49,'description'=>'<50%'],
        ];

        $frekuensi = [
            ['score'=>5,'operator'=>'>=','min_value'=>4,'max_value'=>null,'description'=>'≥4 kali'],
            ['score'=>4,'operator'=>'=','min_value'=>3,'max_value'=>3,'description'=>'3 kali'],
            ['score'=>3,'operator'=>'=','min_value'=>2,'max_value'=>2,'description'=>'2 kali'],
            ['score'=>2,'operator'=>'=','min_value'=>1,'max_value'=>1,'description'=>'1 kali'],
            ['score'=>1,'operator'=>'=','min_value'=>0,'max_value'=>0,'description'=>'0 kali'],
        ];

        $map = [
            1 => $harga,
            2 => $volume,
            3 => $ketepatan,
            4 => $frekuensi
        ];

        foreach ($map as $criteria_id => $params) {
            foreach ($params as $p) {
                Parameter::create(array_merge($p, [
                    'criteria_id' => $criteria_id
                ]));
            }
        }
    }
}
