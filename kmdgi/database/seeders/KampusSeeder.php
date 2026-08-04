<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataKampus = [
            [
                'nama_institusi'     => 'Universitas Pendidikan Indonesia',
                'logo_institusi'     => null,
                'lokasi_kota'        => 'Bandung',
                'medsos_kampus'      => 'upi_official',
                'ig_prodi'           => 'dkv_upi',
                'status_keanggotaan' => 'Anggota',
                'created_at'         => Carbon::now(),
                'updated_at'         => Carbon::now(),
            ],
            [
                'nama_institusi'     => 'Institut Teknologi Sepuluh Nopember',
                'logo_institusi'     => null,
                'lokasi_kota'        => 'Surabaya',
                'medsos_kampus'      => 'its_campus',
                'ig_prodi'           => 'despro_its',
                'status_keanggotaan' => 'Peninjau 1',
                'created_at'         => Carbon::now(),
                'updated_at'         => Carbon::now(),
            ],
            [
                'nama_institusi'     => 'Institut Kesenian Jakarta',
                'logo_institusi'     => null,
                'lokasi_kota'        => 'Jakarta Pusat',
                'medsos_kampus'      => 'ikj_official',
                'ig_prodi'           => 'senirupa_ikj',
                'status_keanggotaan' => 'Peninjau 2',
                'created_at'         => Carbon::now(),
                'updated_at'         => Carbon::now(),
            ],
            [
                'nama_institusi'     => 'Institut Teknologi Bandung',
                'logo_institusi'     => null,
                'lokasi_kota'        => 'Bandung',
                'medsos_kampus'      => 'itb1920',
                'ig_prodi'           => 'fsrditb',
                'status_keanggotaan' => 'Anggota',
                'created_at'         => Carbon::now(),
                'updated_at'         => Carbon::now(),
            ],
            [
                'nama_institusi'     => 'Institut Seni Indonesia Yogyakarta',
                'logo_institusi'     => null,
                'lokasi_kota'        => 'Yogyakarta',
                'medsos_kampus'      => 'isiyogyakarta_official',
                'ig_prodi'           => 'dkv.isi.yk',
                'status_keanggotaan' => 'Anggota',
                'created_at'         => Carbon::now(),
                'updated_at'         => Carbon::now(),
            ],
            [
                'nama_institusi'     => 'Universitas Sebelas Maret',
                'logo_institusi'     => null,
                'lokasi_kota'        => 'Surakarta',
                'medsos_kampus'      => 'uns.official',
                'ig_prodi'           => 'dkvuns',
                'status_keanggotaan' => 'Anggota',
                'created_at'         => Carbon::now(),
                'updated_at'         => Carbon::now(),
            ],
            [
                'nama_institusi'     => 'Universitas Telkom',
                'logo_institusi'     => null,
                'lokasi_kota'        => 'Bandung',
                'medsos_kampus'      => 'telkomuniversity',
                'ig_prodi'           => 'dkvtelkom',
                'status_keanggotaan' => 'Peninjau 1',
                'created_at'         => Carbon::now(),
                'updated_at'         => Carbon::now(),
            ],
        ];

        DB::table('kampus')->insert($dataKampus);
    }
}