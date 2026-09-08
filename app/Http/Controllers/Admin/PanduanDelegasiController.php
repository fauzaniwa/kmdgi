<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PanduanDelegasi;
use Illuminate\Http\Request;

class PanduanDelegasiController extends Controller
{
    public function edit()
    {
        $panduan = PanduanDelegasi::first();
        if (!$panduan) {
            $panduan = PanduanDelegasi::create([
                'hero_title' => 'Panduan Delegasi KMDGI',
                'hero_subtitle' => 'Pahami tata cara pendaftaran, syarat keanggotaan, serta petunjuk teknis pengumpulan karya pameran untuk delegasi kampus.',
                'alur_title' => 'Struktur & Ketentuan Tim Delegasi',
                'alur_deskripsi' => 'Setiap perguruan tinggi yang berpartisipasi pada ajang KMDGI mengirimkan satu kontingen resmi yang dikoordinasikan secara terpusat oleh Ketua Delegasi.',
                'alur_1_title' => 'Struktur Ketua & Anggota',
                'alur_1_desc' => 'Ketua Delegasi memegang Auth Code tim dan berwenang memvalidasi profil, mengatur keikutsertaan anggota, serta mengonfirmasi submisi akhir perwakilan kampus.',
                'alur_2_title' => 'Hak Akses Kategori Karya',
                'alur_2_desc' => 'Kampus berstatus Anggota / Penuh berhak mengirimkan seluruh kategori (Tematik, Simbiotik, Simbolik). Status Peninjau 1 hanya dapat berpartisipasi pada Simbiotik & Simbolik.',
                'alur_3_title' => 'Sinkronisasi Satu Berkas',
                'alur_3_desc' => 'Pengunggahan berkas administrasi dan karya tim menggunakan sistem kolaboratif. Seluruh anggota yang tergabung dengan kode otentikasi tim dapat melihat progres yang sama.',
                'alur_4_title' => 'Spesifikasi Media Karya',
                'alur_4_desc' => 'Wajib melampirkan thumbnail representatif, tautan/file master (ZIP/PDF max 20MB), serta hingga 8 dokumentasi foto/video pendukung untuk materi kurasi dan katalog pameran.',
                'akun_title' => 'Sudah Punya Akun Delegasi?',
                'akun_desc' => 'Masuk langsung ke panel kontingen untuk mengecek status verifikasi kampus, mengelola anggota tim, atau mengunggah formulir.',
                'petunjuk_teknis' => '',
                'petunjuk_pameran' => '',
            ]);
        }

        return view('admin.panduan.index', compact('panduan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string',
            'alur_title' => 'required|string|max:255',
            'alur_1_title' => 'required|string|max:255',
            'petunjuk_teknis'  => 'required|string',
        ]);

        $panduan = PanduanDelegasi::first();
        
        $panduan->update($request->all());

        return redirect()->back()->with('success', 'Seluruh konten Panduan Delegasi berhasil diperbarui!');
    }
}