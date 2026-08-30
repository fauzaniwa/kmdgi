<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KebijakanPrivasiSeeder extends Seeder
{
    public function run(): void
    {
        $dataPrivasi = [
            [
                'judul' => 'Pengumpulan dan Penggunaan Data',
                'konten' => '<h2>1. Data Apa Saja yang Kami Kumpulkan?</h2>
                             <p>Saat Anda membuat akun atau mendaftar di KMDGI 16, kami mengumpulkan informasi identitas dasar seperti:</p>
                             <ul>
                                <li>Nama Lengkap dan Alamat Email</li>
                                <li>Nomor Handphone/WhatsApp</li>
                                <li>Informasi Institusi dan Profesi</li>
                             </ul>
                             <h2>2. Bagaimana Data Ini Digunakan?</h2>
                             <p>Data tersebut digunakan semata-mata untuk mengidentifikasi kepesertaan Anda, menerbitkan sertifikat, dan memberikan pembaruan krusial terkait jadwal pameran dan kompetisi.</p>',
                'is_active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Keamanan dan Perlindungan Informasi',
                'konten' => '<h2>Sistem Perlindungan Keamanan</h2>
                             <p>Panitia KMDGI 16 menerapkan protokol keamanan ketat, termasuk <em>hashing</em> kata sandi Anda menggunakan standar enkripsi terkini. Kami tidak memiliki akses untuk membaca teks asli dari kata sandi Anda.</p>
                             <p>Kami <strong>tidak akan</strong> memperjualbelikan atau memberikan informasi pribadi Anda kepada perusahaan iklan pihak ketiga mana pun tanpa persetujuan tertulis dari Anda.</p>',
                'is_active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('kebijakan_privasi')->insert($dataPrivasi);
    }
}