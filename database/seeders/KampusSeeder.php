<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kampus;
use App\Models\EdisiKmdgi;
use Carbon\Carbon;

class KampusSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil Edisi KMDGI yang sudah dibuat oleh EdisiKmdgiSeeder
        $edisi16 = EdisiKmdgi::where('nama_edisi', 'KMDGI 16')->first();
        $edisi15 = EdisiKmdgi::where('nama_edisi', 'KMDGI 15')->first();

        // 2. Data Kampus beserta status riwayatnya
        $dataKampus = [
            [
                'nama_institusi' => 'Universitas Pendidikan Indonesia',
                'lokasi_kota'    => 'Bandung',
                'medsos_kampus'  => 'upi_official',
                'ig_prodi'       => 'dkv_upi',
                'riwayat_edisi'  => [
                    '16' => 'Anggota',
                    '15' => 'Peninjau 1' // Contoh riwayat historis
                ],
            ],
            [
                'nama_institusi' => 'Institut Teknologi Sepuluh Nopember',
                'lokasi_kota'    => 'Surabaya',
                'medsos_kampus'  => 'its_campus',
                'ig_prodi'       => 'despro_its',
                'riwayat_edisi'  => [
                    '16' => 'Peninjau 1',
                    '15' => 'Peninjau 2'
                ],
            ],
            [
                'nama_institusi' => 'Institut Kesenian Jakarta',
                'lokasi_kota'    => 'Jakarta Pusat',
                'medsos_kampus'  => 'ikj_official',
                'ig_prodi'       => 'senirupa_ikj',
                'riwayat_edisi'  => [
                    '16' => 'Peninjau 2'
                ],
            ],
            [
                'nama_institusi' => 'Institut Teknologi Bandung',
                'lokasi_kota'    => 'Bandung',
                'medsos_kampus'  => 'itb1920',
                'ig_prodi'       => 'fsrditb',
                'riwayat_edisi'  => [
                    '16' => 'Anggota',
                    '15' => 'Anggota'
                ],
            ],
            [
                'nama_institusi' => 'Institut Seni Indonesia Yogyakarta',
                'lokasi_kota'    => 'Yogyakarta',
                'medsos_kampus'  => 'isiyogyakarta_official',
                'ig_prodi'       => 'dkv.isi.yk',
                'riwayat_edisi'  => [
                    '16' => 'Anggota',
                    '15' => 'Anggota'
                ],
            ],
            [
                'nama_institusi' => 'Universitas Sebelas Maret',
                'lokasi_kota'    => 'Surakarta',
                'medsos_kampus'  => 'uns.official',
                'ig_prodi'       => 'dkvuns',
                'riwayat_edisi'  => [
                    '16' => 'Anggota'
                ],
            ],
            [
                'nama_institusi' => 'Universitas Telkom',
                'lokasi_kota'    => 'Bandung',
                'medsos_kampus'  => 'telkomuniversity',
                'ig_prodi'       => 'dkvtelkom',
                'riwayat_edisi'  => [
                    '16' => 'Peninjau 1',
                    '15' => 'Peninjau 2'
                ],
            ],
        ];

        // 3. Eksekusi Pembuatan Kampus & Penyambungan ke Pivot
        foreach ($dataKampus as $data) {
            $riwayat = $data['riwayat_edisi'];
            unset($data['riwayat_edisi']); // Buang dari array karena tidak ada di tabel kampus

            // Simpan data Kampus
            $kampus = Kampus::create($data);

            // Sambungkan ke Edisi 16
            if (isset($riwayat['16']) && $edisi16) {
                $kampus->edisi()->attach($edisi16->id, ['status_keanggotaan' => $riwayat['16']]);
            }

            // Sambungkan ke Edisi 15
            if (isset($riwayat['15']) && $edisi15) {
                $kampus->edisi()->attach($edisi15->id, ['status_keanggotaan' => $riwayat['15']]);
            }
        }
    }
}