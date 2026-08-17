<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeaderPublic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeaderPublicController extends Controller
{
    // Menampilkan Form Pengaturan
    public function edit()
    {
        // Ambil data pertama. Jika tabel kosong, otomatis buat 1 baris kosong.
        $header = HeaderPublic::first();
        if (!$header) {
            $header = HeaderPublic::create([
                'judul' => 'KMDGI 16',
                'is_active_countdown' => 0
            ]);
        }

        return view('admin.header.index', compact('header'));
    }

    // Memproses Pembaruan Data
    public function update(Request $request)
    {
        $request->validate([
            'judul'                => 'required|string|max:255',
            'deskripsi'            => 'nullable|string',
            'gambar_background'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Max 5MB
            'video_background'     => 'nullable|url',
            'waktu_countdown'      => 'nullable|date',
            'is_active_countdown'  => 'required|boolean',
            'teks_tombol_utama'    => 'nullable|string|max:100',
            'link_tombol_utama'    => 'nullable|string|max:255',
            'teks_tombol_sekunder' => 'nullable|string|max:100',
            'link_tombol_sekunder' => 'nullable|string|max:255',
        ]);

        $header = HeaderPublic::first();
        $data = $request->all();

        // Cek dan proses upload gambar background
        if ($request->hasFile('gambar_background')) {
            if ($header->gambar_background) Storage::disk('public')->delete($header->gambar_background);
            $data['gambar_background'] = $request->file('gambar_background')->store('header_images', 'public');
        }

        $header->update($data);

        return redirect()->back()->with('success', 'Tampilan Landing Page Header berhasil diperbarui!');
    }
}