<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeskripsiKarya;
use App\Models\EdisiKmdgi;

class DeskripsiKaryaSeeder extends Seeder
{
    public function run(): void
    {
        $edisi = EdisiKmdgi::where('nama_edisi', 'KMDGI 16')->first();
        if (!$edisi) return;

        $kategoriList = ['Tematik', 'Simbiotik', 'Simbolik'];

        foreach ($kategoriList as $kategori) {
            DeskripsiKarya::create([
                'edisi_kmdgi_id' => $edisi->id,
                'kategori_karya' => $kategori,
                'deskripsi' => "<p>Ini adalah rancangan panduan dasar untuk kategori karya <strong>{$kategori}</strong> pada KMDGI 16.</p>",
                'general_aturan' => '<ul><li>Peserta adalah mahasiswa aktif.</li><li>Karya orisinal.</li></ul>',
                'ketentuan_karya' => '<p>Ukuran karya A2, dikirim dalam bentuk cetak dan digital.</p>',
                'teknis_pelaksanaan' => '<p>Pengumpulan karya selambat-lambatnya 1 November 2026.</p>',
                'sistem_penilaian' => '<p>Inovasi: 40%, Eksekusi Visual: 30%, Relevansi Tema: 30%</p>',
                'nominasi_kriteria' => '<ul><li>Karya Tematik Terbaik</li><li>Favorit Pengunjung</li></ul>',
            ]);
        }
    }
}