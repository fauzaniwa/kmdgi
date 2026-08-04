<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KolaboratorSeeder extends Seeder
{
    public function run(): void
    {
        $dataKolaborator = [
            [
                'urutan'           => 1,
                'nama'             => 'Eka Sofyan Rizal',
                'profesi'          => 'Visual Artist & Dosen IKJ',
                'peran_kolaborasi' => 'Kurator',
                'foto'             => null,
                'detail'           => '<p>Seorang praktisi seni visual yang telah aktif di industri selama 15 tahun. Memiliki pengalaman kuratorial pada pameran nasional dan internasional.</p>',
                'link_instagram'   => 'ekasofyanrizal',
                'link_linkedin'    => 'https://linkedin.com/in/ekasofyan',
                'link_website'     => 'https://ekasofyan.com',
                'is_active'        => 1,
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ],
            [
                'urutan'           => 2,
                'nama'             => 'Nadia K.',
                'profesi'          => 'Type Designer',
                'peran_kolaborasi' => 'Dewan Juri',
                'foto'             => null,
                'detail'           => '<p>Fokus pada perancangan huruf-huruf nusantara. Telah memenangkan berbagai penghargaan typography.</p>',
                'link_instagram'   => 'nadiatype',
                'link_linkedin'    => null,
                'link_website'     => null,
                'is_active'        => 1,
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ],
        ];

        DB::table('kolaborators')->insert($dataKolaborator);
    }
}