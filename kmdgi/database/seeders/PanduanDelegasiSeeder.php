<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PanduanDelegasiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('panduan_delegasi')->insert([
            'petunjuk_teknis' => '<h2>A. Ketentuan Kehadiran</h2>
                                  <p>Delegasi wajib hadir pada waktu yang telah ditetapkan panitia KMDGI 16. Keterlambatan tanpa konfirmasi akan mengakibatkan pengurangan poin penilaian disiplin institusi.</p>
                                  <h2>B. Berkas Administrasi</h2>
                                  <ul>
                                    <li>Membawa cetak Surat Tugas dari Fakultas/Program Studi (Berstempel basah).</li>
                                    <li>Membawa pas foto fisik berukuran 3x4 (2 lembar).</li>
                                  </ul>',
            
            'petunjuk_pameran' => '<h2>A. Standar Ukuran Karya</h2>
                                   <p>Setiap delegasi wajib mengikuti standar format display karya berikut:</p>
                                   <ol>
                                     <li><strong>Karya Cetak (2D):</strong> Ukuran maksimal A1 (Portrait/Landscape), dipasang pada media rigid board (MDF/Impraboard).</li>
                                     <li><strong>Karya Instalasi (3D):</strong> Dimensi maksimal 1x1x1 meter. Pihak delegasi bertanggung jawab atas keamanan instalasi karyanya sendiri.</li>
                                   </ol>
                                   <h2>B. Pemuatan Deskripsi Karya</h2>
                                   <p>Delegasi wajib menyertakan kartu deskripsi karya (Artwork Tag) dengan mencantumkan: Judul Karya, Nama Pembuat, Konsep Singkat, dan Asal Institusi.</p>',
            
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}