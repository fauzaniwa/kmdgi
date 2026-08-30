<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EdisiKmdgiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_edisi'     => 'KMDGI 16',
                'is_active'      => 1,
                
                'lb_title'       => 'Menghadapi Era Digitalisasi Modern',
                'lb_deskripsi'   => '<p>Latar belakang diangkatnya tema ini karena keresahan para insan desain nusantara terhadap masifnya digitalisasi...</p>',
                
                'tema_title'     => 'SIMPUL KOLABORASI',
                'tema_deskripsi' => '<p>Simpul diartikan sebagai ikatan atau titik temu. KMDGI hadir untuk menyatukan perbedaan ideologi dari berbagai instansi desain.</p>',
                
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'nama_edisi'     => 'KMDGI 15', // Arsip / Historis untuk pembuktian multi data
                'is_active'      => 0,
                
                'lb_title'       => 'Pasca Pandemi dan Pemulihan',
                'lb_deskripsi'   => '<p>Sebagai ajang temu kangen kembali setelah 2 tahun absen karena pandemi...</p>',
                
                'tema_title'     => 'SINTAS',
                'tema_deskripsi' => '<p>Sintas, untuk bertahan hidup dan bangkit dari keterpurukan.</p>',
                
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
        ];

        DB::table('edisi_kmdgis')->insert($data);
    }
}