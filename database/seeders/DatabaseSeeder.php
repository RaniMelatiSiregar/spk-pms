<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            PeriodeSeeder::class,      
            CriteriaSeeder::class,   
            ParameterSeeder::class,    
            SupplierSeeder::class,   
        ]);
    }
}