<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder {
    public function run() {
        Admin::updateOrCreate(
            ['email' => 'admin@pms.local'],
            [
                'name' => 'Admin PMS',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
