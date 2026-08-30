<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SponsorSeeder extends Seeder
{
    public function run(): void
    {
        $dataSponsor = [
            [
                'urutan'      => 1,
                'nama_mitra'  => 'Telkom Indonesia',
                'logo'        => 'sponsors_logo/default-telkom.png', // Hanya sebagai dummy string
                'deskripsi'   => '<p>Sponsor Utama (Title Sponsor) acara KMDGI 16.</p>',
                'kategori'    => 'Sponsor',
                'tier_kelas'  => 'Utama (Besar)',
                'link_tautan' => 'https://telkom.co.id',
                'is_active'   => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'urutan'      => 2,
                'nama_mitra'  => 'Wacom Indonesia',
                'logo'        => 'sponsors_logo/default-wacom.png',
                'deskripsi'   => '<p>Mitra perangkat keras dan penyedia hadiah alat gambar digital.</p>',
                'kategori'    => 'Partnership',
                'tier_kelas'  => 'Madya (Sedang)',
                'link_tautan' => 'https://wacom.com',
                'is_active'   => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'urutan'      => 3,
                'nama_mitra'  => 'DesainGrafis.ID',
                'logo'        => 'sponsors_logo/default-dgid.png',
                'deskripsi'   => '<p>Platform publikasi media kolaborator.</p>',
                'kategori'    => 'Media Partner',
                'tier_kelas'  => 'Pratama (Kecil)',
                'link_tautan' => 'https://desaingrafis.co.id',
                'is_active'   => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
        ];

        DB::table('sponsors')->insert($dataSponsor);
    }
}