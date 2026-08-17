<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenampilSeeder extends Seeder
{
    public function run(): void
    {
        $dataPenampil = [
            // Skenario 1: Guest Speaker / Talkshow
            [
                'urutan'             => 1,
                'nama_penampil'      => 'Talkshow: Masa Depan Desain Grafis Indonesia',
                'kategori_penampil'  => 'Guest Speaker',
                'logo_penampil'      => null,
                'cover_penampil'     => null,
                
                // Data Band di-null-kan agar struktur array sama
                'asal_penampil'      => null,
                'tahun_dibentuk'     => null,
                'genre_musik'        => null,
                'embed_spotify'      => null,
                
                'tanggal_tampil'     => '2026-11-15',
                'jam_mulai'          => '10:00:00',
                'jam_selesai'        => '12:00:00',
                'lokasi_tampil'      => 'Main Hall Exhibition',
                'deskripsi_penampil' => '<p>Talkshow interaktif yang akan membahas arah pergerakan desain grafis dan industri kreatif di Indonesia.</p>
                                         <ul>
                                            <li>Sesi 1: Pergeseran Tren Visual 2027</li>
                                            <li>Sesi 2: Bertahan sebagai Studio Independen</li>
                                         </ul>',
                'medsos_penampil'    => '@kmdgi16',
                'kategori_penonton'  => 'Semua',
                'tipe_pendaftaran'   => 'Berbayar',
                'harga_tiket'        => 50000,
                'link_pendaftaran'   => 'https://loket.com/event/kmdgi16-talkshow',
                'is_active'          => 1,
                'created_at'         => Carbon::now(),
                'updated_at'         => Carbon::now(),
            ],

            // Skenario 2: Workshop
            [
                'urutan'             => 2,
                'nama_penampil'      => 'Workshop: Eksplorasi Tipografi Vernakular',
                'kategori_penampil'  => 'Workshop',
                'logo_penampil'      => null,
                'cover_penampil'     => null,
                
                // Data Band di-null-kan
                'asal_penampil'      => null,
                'tahun_dibentuk'     => null,
                'genre_musik'        => null,
                'embed_spotify'      => null,
                
                'tanggal_tampil'     => '2026-11-16',
                'jam_mulai'          => '13:00:00',
                'jam_selesai'        => '15:30:00',
                'lokasi_tampil'      => 'Ruang Kelas A - FSRD ITB',
                'deskripsi_penampil' => '<p>Workshop eksklusif yang mengajak para delegasi mahasiswa untuk turun ke jalan merekam tipografi.</p>',
                'medsos_penampil'    => '@kamengski',
                'kategori_penonton'  => 'Delegasi',
                'tipe_pendaftaran'   => 'Gratis',
                'harga_tiket'        => null,
                'link_pendaftaran'   => null,
                'is_active'          => 1,
                'created_at'         => Carbon::now(),
                'updated_at'         => Carbon::now(),
            ],

            // Skenario 3: Konser Penutup / Band
            [
                'urutan'             => 3,
                'nama_penampil'      => 'Hindia & Lomba Sihir',
                'kategori_penampil'  => 'Band/Musisi',
                'logo_penampil'      => null,
                'cover_penampil'     => null,
                
                // Data Band Terisi
                'asal_penampil'      => 'Jakarta, Indonesia',
                'tahun_dibentuk'     => '2019',
                'genre_musik'        => 'Indie Pop / Alternative Rock',
                'embed_spotify'      => '<iframe style="border-radius:12px" src="https://open.spotify.com/embed/artist/3Bynx7w4HWez6G3HhKne1V?utm_source=generator" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>',
                
                'tanggal_tampil'     => '2026-11-17',
                'jam_mulai'          => '19:30:00',
                'jam_selesai'        => '22:00:00',
                'lokasi_tampil'      => 'Outdoor Stage Lapangan Rumput',
                'deskripsi_penampil' => '<p>Malam puncak penutupan acara KMDGI 16. Mari merayakan euforia kreativitas bersama penampilan spesial dari Hindia dan Lomba Sihir.</p>',
                'medsos_penampil'    => '@wordfangs',
                'kategori_penonton'  => 'Semua',
                'tipe_pendaftaran'   => 'Berbayar',
                'harga_tiket'        => 150000,
                'link_pendaftaran'   => 'https://loket.com/event/kmdgi16-closing',
                'is_active'          => 1,
                'created_at'         => Carbon::now(),
                'updated_at'         => Carbon::now(),
            ],
        ];

        DB::table('penampils')->insert($dataPenampil);
    }
}