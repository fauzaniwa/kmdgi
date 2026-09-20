<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeskripsiKarya;
use App\Models\EdisiKmdgi;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Facades\Storage;

class DeskripsiKaryaController extends Controller
{
    public function edit($kategori, Request $request)
    {
        $kategoriLabel = ucfirst($kategori); // tematik -> Tematik

        if (!in_array($kategoriLabel, ['Tematik', 'Simbiotik', 'Simbolik'])) {
            abort(404);
        }

        $semuaEdisi = EdisiKmdgi::orderBy('id', 'desc')->get();
        if ($semuaEdisi->isEmpty()) {
            return redirect()->route('admin.edisi.index')->withErrors(['msg' => 'Silakan buat Tema & Identitas Edisi terlebih dahulu.']);
        }

        $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();
        $edisiId = $request->input('edisi_id', $edisiAktif ? $edisiAktif->id : $semuaEdisi->first()->id);

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
            'edisi_kmdgi_id'      => 'required|exists:edisi_kmdgis,id',
            'thumbnail'           => 'nullable|image|max:3072',
            'file_guidebook'      => 'nullable|mimes:pdf,doc,docx|max:5120',
            'file_panduan_online' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'deadline'            => 'nullable|date', // Tambahan validasi untuk deadline (tanggal & jam)
        ]);

        // Mengambil semua inputan kecuali yang berkaitan dengan file khusus
        $data = $request->except([
            '_token', 
            '_method', 
            'thumbnail', 
            'remove_thumbnail', 
            'file_guidebook', 
            'remove_file_guidebook', 
            'file_panduan_online', 
            'remove_file_panduan_online', 
            'berkas_lainnya'
        ]);
        
        $data['kategori_karya'] = $kategoriLabel;

        $karya = DeskripsiKarya::where('edisi_kmdgi_id', $request->edisi_kmdgi_id)
                               ->where('kategori_karya', $kategoriLabel)->first();

        // 1. Upload Thumbnail
        if ($request->hasFile('thumbnail')) {
            if ($karya && $karya->thumbnail) Storage::disk('public')->delete($karya->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('deskripsi_karya', 'public');
        } elseif ($request->remove_thumbnail == '1') {
            if ($karya && $karya->thumbnail) Storage::disk('public')->delete($karya->thumbnail);
            $data['thumbnail'] = null;
        }

        // 2. Upload Guidebook
        if ($request->hasFile('file_guidebook')) {
            if ($karya && $karya->file_guidebook) Storage::disk('public')->delete($karya->file_guidebook);
            $data['file_guidebook'] = $request->file('file_guidebook')->store('deskripsi_karya_docs', 'public');
        } elseif ($request->remove_file_guidebook == '1') {
            if ($karya && $karya->file_guidebook) Storage::disk('public')->delete($karya->file_guidebook);
            $data['file_guidebook'] = null;
        }

        // 3. Upload Panduan Online
        if ($request->hasFile('file_panduan_online')) {
            if ($karya && $karya->file_panduan_online) Storage::disk('public')->delete($karya->file_panduan_online);
            $data['file_panduan_online'] = $request->file('file_panduan_online')->store('deskripsi_karya_docs', 'public');
        } elseif ($request->remove_file_panduan_online == '1') {
            if ($karya && $karya->file_panduan_online) Storage::disk('public')->delete($karya->file_panduan_online);
            $data['file_panduan_online'] = null;
        }

        // 4. Upload Berkas Lainnya (Array Dinamis)
        $berkasData = [];
        if ($request->has('berkas_lainnya')) {
            foreach ($request->berkas_lainnya as $i => $b) {
                $filePath = $b['old_file'] ?? null;

                // Jika ada file baru di-upload, timpa yang lama
                if ($request->hasFile("berkas_lainnya.{$i}.file")) {
                    if ($filePath) Storage::disk('public')->delete($filePath);
                    $filePath = $request->file("berkas_lainnya.{$i}.file")->store('deskripsi_karya_docs', 'public');
                }

                // Simpan asalkan file tersebut ada (baik file lama maupun baru diupload)
                if ($filePath || !empty($b['nama'])) {
                    $berkasData[] = [
                        'nama' => $b['nama'] ?? 'Berkas Pendukung',
                        'file' => $filePath
                    ];
                }
            }
        }
        $data['berkas_lainnya'] = empty($berkasData) ? null : $berkasData;

        DeskripsiKarya::updateOrCreate(
            ['edisi_kmdgi_id' => $request->edisi_kmdgi_id, 'kategori_karya' => $kategoriLabel],
            $data
        );

        // ===========================================================================
        // [LOG AKTIVITAS] Mencatat Perubahan Deskripsi Karya
        // ===========================================================================
        // Cek nama edisi untuk log yang lebih spesifik
        $edisiInfo = EdisiKmdgi::find($request->edisi_kmdgi_id);
        $namaEdisi = $edisiInfo ? $edisiInfo->tema_kmdgi : 'Edisi Tidak Diketahui';

        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Deskripsi Karya',
            'aksi'       => 'Update',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' telah memperbarui deskripsi dan aturan karya kategori ' . $kategoriLabel . ' untuk edisi "' . $namaEdisi . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', "Data Karya {$kategoriLabel} untuk Edisi tersebut berhasil disimpan!");
    }
}