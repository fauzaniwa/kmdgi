<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HeaderPublicSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('header_publics')->insert([
            'judul'                => 'Selamat Datang di KMDGI 16',
            'deskripsi'            => 'Kumpul Mahasiswa Desain Grafis Indonesia. Wadah kolaborasi, pameran, dan perayaan insan kreatif dari seluruh penjuru nusantara.',
            'gambar_background'    => null, 
            'video_background'     => null,
            'waktu_countdown'      => '2026-11-15 10:00:00', // Sesuai jadwal pameran
            'is_active_countdown'  => 1,
            'teks_tombol_utama'    => 'Daftar Sebagai Delegasi',
            'link_tombol_utama'    => '/register',
            'teks_tombol_sekunder' => 'Lihat Agenda',
            'link_tombol_sekunder' => '#agenda-section',
            'created_at'           => Carbon::now(),
            'updated_at'           => Carbon::now(),
        ]);
    }
}