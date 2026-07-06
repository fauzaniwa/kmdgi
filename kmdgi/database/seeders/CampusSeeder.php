<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampusSeeder extends Seeder
{
    public function run(): void
    {
        $campuses = [
            ['name' => 'Universitas Pendidikan Indonesia'],
            ['name' => 'Institut Teknologi Bandung'],
            ['name' => 'Institut Seni Indonesia Yogyakarta'],
            ['name' => 'Universitas Sebelas Maret'],
            ['name' => 'Universitas Negeri Malang'],
            ['name' => 'Institut Seni Indonesia Denpasar'],
            ['name' => 'Universitas Trisakti'],
            ['name' => 'Universitas Telkom'],
        ];

        DB::table('campuses')->insert($campuses);
    }
}