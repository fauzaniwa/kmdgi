<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventKmdgi;
use App\Models\EdisiKmdgi;
use App\Models\Kolaborator;
use Carbon\Carbon;

class EventKmdgiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil Edisi Aktif
        $edisi = EdisiKmdgi::where('is_active', 1)->first() ?? EdisiKmdgi::first();
        if (!$edisi) return;

        // 2. Ambil Data Kolaborator (Narasumber/Juri)
        $eka = Kolaborator::where('nama', 'Eka Sofyan Rizal')->first();
        $nadia = Kolaborator::where('nama', 'Nadia K.')->first();

        // Siapkan ID Kolaborator ke dalam Array (Bisa salah satu atau keduanya)
        $kolaboratorIdsTalkshow = [];
        if ($eka) $kolaboratorIdsTalkshow[] = (string) $eka->id;
        if ($nadia) $kolaboratorIdsTalkshow[] = (string) $nadia->id;

        $kolaboratorIdsWorkshop = [];
        if ($nadia) $kolaboratorIdsWorkshop[] = (string) $nadia->id;

        // 3. Masukkan Data Event
        $dataEvent = [
            [
                'edisi_kmdgi_id'      => $edisi->id,
                'judul'               => 'Talkshow Nasional: "Menyuarakan Identitas Melalui Desain"',
                'slug'                => 'talkshow-nasional-menyuarakan-identitas-melalui-desain',
                'poster'              => null, // Bisa diisi path gambar jika ada
                'harga_tiket'         => 0, // Gratis
                'kuota'               => 500,
                'kategori_peserta'    => ['Umum', 'Delegasi', 'Peninjau 1', 'Peninjau 2'],
                'kolaborator_ids'     => $kolaboratorIdsTalkshow,
                'tanggal_pelaksanaan' => '2026-10-20',
                'jam_pelaksanaan'     => '09:00',
                'lokasi'              => 'Main Hall Kampus / Zoom',
                'deskripsi'           => '<p>Talkshow ini akan membahas bagaimana elemen visual dan karya desain dapat menjadi media untuk menyuarakan keresahan, identitas, dan solusi atas permasalahan sosial yang ada di Nusantara.</p>',
                'ketentuan'           => '<p>Peserta diharapkan hadir 15 menit sebelum acara dimulai. Membawa e-ticket atau bukti pendaftaran yang sah.</p>',
                'is_active'           => 1,
                'created_at'          => Carbon::now(),
                'updated_at'          => Carbon::now(),
            ],
            [
                'edisi_kmdgi_id'      => $edisi->id,
                'judul'               => 'Workshop Typography: Meracik Huruf Nusantara',
                'slug'                => 'workshop-typography-meracik-huruf-nusantara',
                'poster'              => null,
                'harga_tiket'         => 150000, // Berbayar
                'kuota'               => 50,     // Terbatas
                'kategori_peserta'    => ['Umum', 'Delegasi'],
                'kolaborator_ids'     => $kolaboratorIdsWorkshop,
                'tanggal_pelaksanaan' => '2026-10-21',
                'jam_pelaksanaan'     => '13:00',
                'lokasi'              => 'Studio Mac Lab, Gedung DKV',
                'deskripsi'           => '<p>Praktik langsung mendesain huruf (Type Design) yang mengambil inspirasi dari aksara-aksara lokal Indonesia menggunakan *software* modern.</p>',
                'ketentuan'           => '<p>Wajib membawa laptop masing-masing yang sudah ter-install software desain vektor (Illustrator / Glyphs).</p>',
                'is_active'           => 1,
                'created_at'          => Carbon::now(),
                'updated_at'          => Carbon::now(),
            ]
        ];

        foreach ($dataEvent as $event) {
            EventKmdgi::create($event);
        }
    }
}