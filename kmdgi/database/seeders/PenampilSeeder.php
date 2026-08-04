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
            // Skenario 1: Guest Speaker / Talkshow (Berbayar untuk Semua)
            [
                'nama_penampil'      => 'Talkshow: Masa Depan Desain Grafis Indonesia',
                'kategori_penampil'  => 'Guest Speaker',
                'logo_penampil'      => null,
                'cover_penampil'     => null,
                'tanggal_tampil'     => '2026-11-15',
                'jam_mulai'          => '10:00:00',
                'jam_selesai'        => '12:00:00',
                'lokasi_tampil'      => 'Main Hall Exhibition',
                'deskripsi_penampil' => '<p>Talkshow interaktif yang akan membahas arah pergerakan desain grafis dan industri kreatif di Indonesia. Mengundang praktisi desain ternama dan studio independen.</p>
                                         <ul>
                                            <li>Sesi 1: Pergeseran Tren Visual 2027</li>
                                            <li>Sesi 2: Bertahan sebagai Studio Independen</li>
                                            <li>Tanya Jawab (Q&A)</li>
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

            // Skenario 2: Workshop (Gratis Khusus Delegasi Resmi)
            [
                'nama_penampil'      => 'Workshop: Eksplorasi Tipografi Vernakular',
                'kategori_penampil'  => 'Workshop',
                'logo_penampil'      => null,
                'cover_penampil'     => null,
                'tanggal_tampil'     => '2026-11-16',
                'jam_mulai'          => '13:00:00',
                'jam_selesai'        => '15:30:00',
                'lokasi_tampil'      => 'Ruang Kelas A - FSRD ITB',
                'deskripsi_penampil' => '<p>Workshop eksklusif yang mengajak para delegasi mahasiswa untuk turun ke jalan, merekam tipografi vernakular di sekitar Bandung, dan mereplikasinya menjadi <em>typeface</em> digital.</p>
                                         <p><strong>Syarat Peserta:</strong></p>
                                         <ol>
                                            <li>Wajib membawa laptop yang sudah ter-install Adobe Illustrator / Glyphs.</li>
                                            <li>Membawa kamera / smartphone untuk dokumentasi.</li>
                                         </ol>',
                'medsos_penampil'    => '@kamengski',
                'kategori_penonton'  => 'Delegasi',
                'tipe_pendaftaran'   => 'Gratis',
                'harga_tiket'        => null,
                'link_pendaftaran'   => null, // Gratis dan khusus delegasi tidak butuh link loket
                'is_active'          => 1,
                'created_at'         => Carbon::now(),
                'updated_at'         => Carbon::now(),
            ],

            // Skenario 3: Konser Penutup / Band (Berbayar, Akses Semua)
            [
                'nama_penampil'      => 'Hindia & Lomba Sihir',
                'kategori_penampil'  => 'Band/Musisi',
                'logo_penampil'      => null,
                'cover_penampil'     => null,
                'tanggal_tampil'     => '2026-11-17',
                'jam_mulai'          => '19:30:00',
                'jam_selesai'        => '22:00:00',
                'lokasi_tampil'      => 'Outdoor Stage Lapangan Rumput',
                'deskripsi_penampil' => '<p>Malam puncak penutupan acara KMDGI 16. Mari merayakan euforia kreativitas bersama penampilan spesial dari <strong>Hindia</strong> dan <strong>Lomba Sihir</strong>.</p>
                                         <p><br></p>
                                         <p><em>*Penukaran tiket fisik dapat dilakukan mulai pukul 15.00 WIB di loket utama.</em></p>',
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