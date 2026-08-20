<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JuknisLomba;
use App\Models\EdisiKmdgi;
use Carbon\Carbon;

class JuknisLombaSeeder extends Seeder
{
    public function run(): void
    {
        $edisi = EdisiKmdgi::where('nama_edisi', 'KMDGI 16')->first();
        if (!$edisi) return;

        $lomba = [
            [
                'edisi_kmdgi_id'     => $edisi->id,
                'judul'              => 'Lomba Desain Poster Nasional',
                'slug'               => 'lomba-desain-poster-nasional',
                'deskripsi'          => '<p>Lomba perancangan poster untuk merespon fenomena digitalisasi yang memengaruhi budaya Nusantara.</p>',
                'kategori_peserta'   => ['Umum', 'Delegasi'],
                'biaya_pendaftaran'  => 50000,
                'timeline' => [
                    ['tanggal' => '2026-09-01', 'head' => 'Pendaftaran Dibuka', 'deskripsi' => 'Pengisian form dan pembayaran'],
                    ['tanggal' => '2026-10-15', 'head' => 'Batas Submisi', 'deskripsi' => 'Tenggat waktu pengumpulan G-Drive'],
                ],
                'hadiah' => [
                    ['peringkat' => 'Juara 1', 'keterangan' => 'Uang Tunai & Sertifikat', 'isi' => 'Rp 3.000.000 + Merchandise', 'icon' => null],
                    ['peringkat' => 'Juara 2', 'keterangan' => 'Uang Tunai & Sertifikat', 'isi' => 'Rp 2.000.000', 'icon' => null],
                ],
                'juri' => [
                    ['nama' => 'Eka Sofyan Rizal', 'keterangan' => 'Ketua ADGI', 'deskripsi' => 'Praktisi dan akademisi DKV', 'urutan' => 1, 'foto' => null],
                ],
                'syarat'             => '<p>Wajib WNI dan melampirkan KTM / KTP.</p>',
                'is_active'          => 1,
            ],
            [
                'edisi_kmdgi_id'     => $edisi->id,
                'judul'              => 'Kompetisi Maskot KMDGI',
                'slug'               => 'kompetisi-maskot-kmdgi',
                'deskripsi'          => '<p>Sayembara pembuatan maskot resmi untuk KMDGI selanjutnya.</p>',
                'kategori_peserta'   => ['Delegasi'],
                'biaya_pendaftaran'  => 0, // Gratis
                'timeline'           => [],
                'hadiah'             => [],
                'juri'               => [],
                'is_active'          => 1,
            ]
        ];

        foreach ($lomba as $data) {
            JuknisLomba::create($data);
        }
    }
}