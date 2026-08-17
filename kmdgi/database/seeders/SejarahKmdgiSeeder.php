<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SejarahKmdgiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'tahun'       => '1993',
                'title'       => 'KMDGI 1 - Universitas Trisakti Jakarta',
                'description' => '<p>KMDGI pertama kali diinisiasi dan diselenggarakan di kampus Universitas Trisakti, Jakarta. Melibatkan berbagai kampus di Pulau Jawa.</p>',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'tahun'       => '1995',
                'title'       => 'KMDGI 2 - Institut Teknologi Bandung (ITB)',
                'description' => '<p>Mulai merumuskan AD/ART organisasi dan melahirkan logo KMDGI yang masih menjadi cikal bakal logo hingga saat ini.</p>',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
        ];

        DB::table('sejarah_kmdgis')->insert($data);
    }
}