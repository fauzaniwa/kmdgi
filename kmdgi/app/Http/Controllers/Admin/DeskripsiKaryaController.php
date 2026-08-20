<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeskripsiKarya;
use App\Models\EdisiKmdgi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeskripsiKaryaController extends Controller
{
    public function edit($kategori, Request $request)
    {
        $kategoriLabel = ucfirst($kategori); // tematik -> Tematik
        
        // Proteksi URL
        if (!in_array($kategoriLabel, ['Tematik', 'Simbiotik', 'Simbolik'])) {
            abort(404);
        }

        $semuaEdisi = EdisiKmdgi::orderBy('id', 'desc')->get();
        if ($semuaEdisi->isEmpty()) {
            return redirect()->route('edisi.index')->withErrors(['msg' => 'Silakan buat Tema & Identitas Edisi terlebih dahulu.']);
        }

        // Cari Edisi mana yang sedang dipilih Admin. Default: Edisi yang Active di website.
        $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();
        $edisiId = $request->input('edisi_id', $edisiAktif ? $edisiAktif->id : $semuaEdisi->first()->id);

        // Ambil data Karya (Jika belum ada di DB, buatkan template kosong sementara di memori)
        $karya = DeskripsiKarya::firstOrNew([
            'edisi_kmdgi_id' => $edisiId,
            'kategori_karya' => $kategoriLabel
        ]);

        return view('admin.deskripsi_karya.form', compact('karya', 'kategori', 'kategoriLabel', 'semuaEdisi', 'edisiId'));
    }

    public function update($kategori, Request $request)
    {
        $kategoriLabel = ucfirst($kategori);

        $request->validate([
            'edisi_kmdgi_id' => 'required|exists:edisi_kmdgis,id',
            'thumbnail'      => 'nullable|image|max:3072',
        ]);

        $data = $request->except(['_token', '_method', 'thumbnail', 'remove_thumbnail']);
        $data['kategori_karya'] = $kategoriLabel;

        // Cari record lama untuk keperluan hapus gambar
        $karya = DeskripsiKarya::where('edisi_kmdgi_id', $request->edisi_kmdgi_id)
                               ->where('kategori_karya', $kategoriLabel)->first();

        if ($request->hasFile('thumbnail')) {
            if ($karya && $karya->thumbnail) Storage::disk('public')->delete($karya->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('deskripsi_karya', 'public');
        } elseif ($request->remove_thumbnail == '1') {
            if ($karya && $karya->thumbnail) Storage::disk('public')->delete($karya->thumbnail);
            $data['thumbnail'] = null;
        }

        // UpdateOrCreate: Jika sudah ada di DB diupdate, jika belum dibuat baru
        DeskripsiKarya::updateOrCreate(
            ['edisi_kmdgi_id' => $request->edisi_kmdgi_id, 'kategori_karya' => $kategoriLabel],
            $data
        );

        return redirect()->back()->with('success', "Data Karya {$kategoriLabel} untuk Edisi tersebut berhasil disimpan!");
    }
}