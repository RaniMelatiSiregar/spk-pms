<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Periode;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $periode = Periode::first();
        if (!$periode) return;

        $data = [
            ['code'=>'SUP001','name'=>'CV Putra Muara','location'=>'Muara','price_per_kg'=>185,'volume_per_month'=>10000,'on_time_percent'=>95,'freq_per_month'=>3],
            ['code'=>'SUP002','name'=>'UD Sari Kayu','location'=>'Tanjung','price_per_kg'=>180,'volume_per_month'=>15000,'on_time_percent'=>100,'freq_per_month'=>4],
            ['code'=>'SUP003','name'=>'PT Karet Jaya','location'=>'Riau','price_per_kg'=>190,'volume_per_month'=>8000,'on_time_percent'=>90,'freq_per_month'=>3],
            ['code'=>'SUP004','name'=>'CV Lestari','location'=>'Siak','price_per_kg'=>195,'volume_per_month'=>6000,'on_time_percent'=>85,'freq_per_month'=>2],
            ['code'=>'SUP005','name'=>'UD Makmur','location'=>'Tebing','price_per_kg'=>200,'volume_per_month'=>10000,'on_time_percent'=>70,'freq_per_month'=>2],
            ['code'=>'SUP006','name'=>'PT Muara Indah','location'=>'Dumai','price_per_kg'=>175,'volume_per_month'=>16000,'on_time_percent'=>98,'freq_per_month'=>4],
            ['code'=>'SUP007','name'=>'CV Jaya Abadi','location'=>'Pekan','price_per_kg'=>182,'volume_per_month'=>9000,'on_time_percent'=>88,'freq_per_month'=>2],
            ['code'=>'SUP008','name'=>'UD Sejahtera','location'=>'Bengkalis','price_per_kg'=>188,'volume_per_month'=>7000,'on_time_percent'=>75,'freq_per_month'=>2],
            ['code'=>'SUP009','name'=>'PT Kayu Prima','location'=>'Pelalawan','price_per_kg'=>170,'volume_per_month'=>17000,'on_time_percent'=>100,'freq_per_month'=>4],
            ['code'=>'SUP010','name'=>'CV Mitra Karet','location'=>'Kampar','price_per_kg'=>195,'volume_per_month'=>5000,'on_time_percent'=>60,'freq_per_month'=>1],
            ['code'=>'SUP011','name'=>'UD Kencana','location'=>'Rokan','price_per_kg'=>190,'volume_per_month'=>4000,'on_time_percent'=>80,'freq_per_month'=>1],
            ['code'=>'SUP012','name'=>'PT Sumber','location'=>'Siantan','price_per_kg'=>183,'volume_per_month'=>12000,'on_time_percent'=>92,'freq_per_month'=>3],
            ['code'=>'SUP013','name'=>'CV Agro','location'=>'Langgam','price_per_kg'=>179,'volume_per_month'=>14000,'on_time_percent'=>97,'freq_per_month'=>4],
            ['code'=>'SUP014','name'=>'UD Sentosa','location'=>'Mandau','price_per_kg'=>186,'volume_per_month'=>7500,'on_time_percent'=>89,'freq_per_month'=>2],
            ['code'=>'SUP015','name'=>'PT Setia','location'=>'Rupat','price_per_kg'=>205,'volume_per_month'=>3000,'on_time_percent'=>45,'freq_per_month'=>0],
            ['code'=>'SUP016','name'=>'CV Barokah','location'=>'Bengkalis','price_per_kg'=>177,'volume_per_month'=>15500,'on_time_percent'=>99,'freq_per_month'=>4],
            ['code'=>'SUP017','name'=>'UD Sumber Rejeki','location'=>'Pelalawan','price_per_kg'=>192,'volume_per_month'=>8500,'on_time_percent'=>87,'freq_per_month'=>2],
            ['code'=>'SUP018','name'=>'PT Karya','location'=>'Siak','price_per_kg'=>181,'volume_per_month'=>11000,'on_time_percent'=>94,'freq_per_month'=>3],
            ['code'=>'SUP019','name'=>'CV Makmur Jaya','location'=>'Tebing','price_per_kg'=>199,'volume_per_month'=>3500,'on_time_percent'=>55,'freq_per_month'=>1],
            ['code'=>'SUP020','name'=>'UD Prima Kayu','location'=>'Dumai','price_per_kg'=>184,'volume_per_month'=>9800,'on_time_percent'=>91,'freq_per_month'=>3],
        ];

        foreach ($data as $item) {
            Supplier::create(array_merge($item, [
                'periode_id' => $periode->id
            ]));
        }
    }
}
