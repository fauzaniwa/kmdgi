<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PesertaLomba;
use App\Models\JuknisLomba;
use App\Models\User;
use Carbon\Carbon;

class PesertaLombaSeeder extends Seeder
{
    public function run(): void
    {
        $lombaPoster = JuknisLomba::where('slug', 'lomba-desain-poster-nasional')->first();
        $lombaMaskot = JuknisLomba::where('slug', 'kompetisi-maskot-kmdgi')->first();
        $userDelegasi = User::where('email', 'akbar@kmdgi.com')->first();
        $userUmum = User::where('email', 'budi@kmdgi.com')->first();

        if (!$lombaPoster || !$lombaMaskot || !$userDelegasi || !$userUmum) return;

        $dataPeserta = [
            // Skenario 1: Budi (Umum) daftar Lomba Poster (Belum Ngumpulin Karya, Menunggu Validasi TF)
            [
                'juknis_lomba_id'    => $lombaPoster->id,
                'user_id'            => $userUmum->id,
                'nama_tim_peserta'   => 'Budi Creative Studio',
                'institusi_asal'     => 'Freelance / Umum',
                'kategori_pendaftar' => 'Umum',
                'no_whatsapp'        => '6289876543210',
                
                'status_pembayaran'  => 'Menunggu Validasi',
                'bukti_pembayaran'   => 'dummy_struk.jpg',
                
                'judul_karya'        => null,
                'kreator_karya'      => null,
                'deskripsi_karya'    => null,
                'file_karya'         => null,
                'link_karya'         => null,
                'status_karya'       => 'Belum Mengumpulkan',
                
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],

            // Skenario 2: Akbar (Delegasi) daftar Lomba Maskot (Gratis, Sudah Mengumpulkan Karya Link)
            [
                'juknis_lomba_id'    => $lombaMaskot->id,
                'user_id'            => $userDelegasi->id,
                'nama_tim_peserta'   => 'UPI Hore Team',
                'institusi_asal'     => 'Universitas Pendidikan Indonesia',
                'kategori_pendaftar' => 'Delegasi',
                'no_whatsapp'        => '6281234567890',
                
                'status_pembayaran'  => 'Gratis',
                'bukti_pembayaran'   => null,
                
                'judul_karya'        => 'Si Gi - Sang Ksatria Budaya',
                'kreator_karya'      => 'Akbar & Dinda',
                'deskripsi_karya'    => 'Maskot ini terinspirasi dari hewan endemik Indonesia yang melambangkan kelincahan dalam beradaptasi di era digital.',
                'file_karya'         => null,
                'link_karya'         => 'https://drive.google.com/drive/folders/dummy-link-karya',
                'status_karya'       => 'Terkirim',
                
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],

            // Skenario 3: Akbar daftar Lomba Poster (Lunas, File Upload Langsung)
            [
                'juknis_lomba_id'    => $lombaPoster->id,
                'user_id'            => $userDelegasi->id,
                'nama_tim_peserta'   => 'Delegasi UPI 1',
                'institusi_asal'     => 'Universitas Pendidikan Indonesia',
                'kategori_pendaftar' => 'Delegasi',
                'no_whatsapp'        => '6281234567890',
                
                'status_pembayaran'  => 'Lunas',
                'bukti_pembayaran'   => 'dummy_struk_lunas.jpg',
                
                'judul_karya'        => 'Simpul Nusantara',
                'kreator_karya'      => 'M. Akbar',
                'deskripsi_karya'    => 'Poster tipografi bergaya kontemporer dengan perpaduan elemen ukiran nusantara.',
                'file_karya'         => 'dummy_poster_peserta.jpg', // Simulasi File Upload
                'link_karya'         => null,
                'status_karya'       => 'Terkirim',
                
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($dataPeserta as $data) {
            PesertaLomba::create($data);
        }
    }
}