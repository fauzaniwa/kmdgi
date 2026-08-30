<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PanduanDelegasi;
use Illuminate\Http\Request;

class PanduanDelegasiController extends Controller
{
    // Tampilkan Halaman Form Panduan
    public function edit()
    {
        // Ambil data pertama. Jika tidak ada, otomatis buat 1 baris kosong.
        $panduan = PanduanDelegasi::first();
        if (!$panduan) {
            $panduan = PanduanDelegasi::create([
                'petunjuk_teknis' => '',
                'petunjuk_pameran' => '',
            ]);
        }

        return view('admin.panduan.index', compact('panduan'));
    }

    // Proses Penyimpanan/Pembaruan
    public function update(Request $request)
    {
        $request->validate([
            'petunjuk_teknis'  => 'required|string',
            'petunjuk_pameran' => 'required|string',
        ]);

        $panduan = PanduanDelegasi::first();
        
        $panduan->update([
            'petunjuk_teknis'  => $request->petunjuk_teknis,
            'petunjuk_pameran' => $request->petunjuk_pameran,
        ]);

        return redirect()->back()->with('success', 'Dokumen Panduan Delegasi berhasil diperbarui!');
    }
}