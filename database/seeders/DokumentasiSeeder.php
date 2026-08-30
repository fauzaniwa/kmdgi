<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DokumentasiSeeder extends Seeder
{
    public function run(): void
    {
        $dataDokumentasi = [
            [
                'judul'             => 'Euforia Malam Puncak KMDGI 16',
                'deskripsi'         => '<p>Ribuan delegasi berkumpul merayakan malam penutupan.</p>',
                'kategori_kegiatan' => 'Malam Puncak',
                'tanggal_kegiatan'  => '2026-11-17',
                'tipe_media'        => 'Foto',
                'file_path'         => 'dokumentasi/foto/dummy1.jpg',
                'video_url'         => null,
                'is_active'         => 1,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'judul'             => 'Official Aftermovie KMDGI 16 Bandung',
                'deskripsi'         => '<p>Video rangkuman kegiatan dari Pra-Event hingga acara utama yang dilaksanakan selama 3 hari berturut-turut.</p>',
                'kategori_kegiatan' => 'Malam Puncak',
                'tanggal_kegiatan'  => '2026-11-20',
                'tipe_media'        => 'Video YouTube',
                'file_path'         => null,
                'video_url'         => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Dummy Link
                'is_active'         => 1,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
        ];

        DB::table('dokumentasis')->insert($dataDokumentasi);
    }
}