<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SyaratKetentuanSeeder extends Seeder
{
    public function run(): void
    {
        $dataSyarat = [
            [
                'judul' => 'Syarat dan Ketentuan Peserta (Delegasi)',
                'konten' => '<h2>1. Persyaratan Pendaftaran</h2>
                             <p>Peserta yang berhak mendaftar sebagai delegasi resmi harus memenuhi ketentuan sebagai berikut:</p>
                             <ul>
                                <li>Berstatus sebagai mahasiswa aktif Desain Komunikasi Visual atau Desain Grafis dari institusi terkait.</li>
                                <li>Mendapatkan surat tugas / persetujuan dari pihak Fakultas atau Universitas.</li>
                                <li>Satu institusi hanya dapat mengirimkan maksimal 15 orang delegasi (Termasuk Ketua Delegasi).</li>
                             </ul>
                             <h2>2. Kode Autentikasi (Auth Code)</h2>
                             <p>Setiap anggota delegasi wajib memasukkan <strong>Auth Code</strong> yang hanya bisa didapatkan melalui Ketua Delegasi yang telah tervalidasi oleh panitia KMDGI pusat.</p>',
                'is_active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Kebijakan Privasi Pengguna',
                'konten' => '<h2>1. Pengumpulan Data</h2>
                             <p>Seluruh data pribadi (seperti nomor telepon, email, tanggal lahir) yang dikumpulkan melalui formulir pendaftaran ini hanya akan digunakan untuk keperluan:</p>
                             <ol>
                                <li>Verifikasi identitas pada saat registrasi ulang di venue pameran.</li>
                                <li>Pembuatan e-certificate atau sertifikat fisik.</li>
                                <li>Komunikasi darurat / penting terkait jadwal acara.</li>
                             </ol>
                             <p>Kami <strong>berkomitmen</strong> penuh untuk tidak menyebarluaskan, menjual, atau mengeksploitasi data sensitif Anda kepada pihak ketiga mana pun.</p>',
                'is_active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('syarat_ketentuan')->insert($dataSyarat);
    }
}