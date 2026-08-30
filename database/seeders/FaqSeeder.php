<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataFaqs = [
            [
                'pertanyaan' => 'Apa itu KMDGI?',
                'jawaban'    => 'KMDGI (Kumpul Mahasiswa Desain Grafis Indonesia) adalah wadah pertemuan dua tahunan bagi mahasiswa Desain Grafis / Desain Komunikasi Visual seluruh Indonesia untuk bertukar pikiran, berdiskusi, dan memamerkan karya kreatif.',
                'kategori'   => 'Umum',
                'is_active'  => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pertanyaan' => 'Bagaimana cara mendaftar sebagai delegasi kampus?',
                'jawaban'    => 'Pendaftaran delegasi dikoordinasikan oleh Ketua Delegasi dari masing-masing kampus. Ketua akan mendapatkan Auth Code khusus yang nantinya dibagikan dan digunakan oleh Anggota Delegasi saat melakukan registrasi akun di website.',
                'kategori'   => 'Sistem Pendaftaran',
                'is_active'  => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pertanyaan' => 'Apakah peserta dari luar kampus (masyarakat umum) diperbolehkan mendaftar?',
                'jawaban'    => 'Tentu saja! KMDGI 16 terbuka untuk umum. Anda dapat mendaftar pada halaman registrasi dengan memilih kategori "Peserta Umum" dan melengkapi data profil serta profesi Anda saat ini.',
                'kategori'   => 'Sistem Pendaftaran',
                'is_active'  => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pertanyaan' => 'Apa saja ketentuan dan tema untuk pengumpulan karya pameran?',
                'jawaban'    => 'Karya yang dikumpulkan harus sesuai dengan tema besar KMDGI 16. Detail lengkap mengenai format file, resolusi, ukuran fisik, dan tenggat waktu (deadline) pengumpulan dapat dibaca pada buku panduan (Guidebook) yang tersedia di Dashboard Delegasi.',
                'kategori'   => 'Ketentuan Karya',
                'is_active'  => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pertanyaan' => 'Berapa batas maksimal jumlah delegasi yang bisa dikirimkan per kampus?',
                'jawaban'    => 'Jumlah maksimal anggota delegasi resmi yang dapat didaftarkan oleh setiap institusi/kampus adalah 15 orang (sudah termasuk 1 orang Ketua Delegasi).',
                'kategori'   => 'Delegasi & Kampus',
                'is_active'  => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pertanyaan' => 'Apakah pendaftaran KMDGI dipungut biaya?',
                'jawaban'    => 'Informasi mengenai biaya pendaftaran (Registration Fee) baik untuk delegasi maupun peserta umum akan diumumkan secara terpisah melalui Instagram resmi KMDGI dan halaman Dashboard akun Anda.',
                'kategori'   => 'Umum',
                'is_active'  => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pertanyaan' => 'Contoh FAQ yang sedang di-draft atau disembunyikan?',
                'jawaban'    => 'Ini adalah contoh data F&Q yang sengaja disembunyikan (is_active = 0). Pertanyaan ini tidak akan muncul di halaman depan pengunjung/peserta, tetapi tetap ada di database admin.',
                'kategori'   => 'Umum',
                'is_active'  => 0, // Status Draft / Disembunyikan
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('faqs')->insert($dataFaqs);
    }
}