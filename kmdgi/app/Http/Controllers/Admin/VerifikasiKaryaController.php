<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubmisiKarya;
use Illuminate\Http\Request;

class VerifikasiKaryaController extends Controller
{
    /**
     * Menampilkan Daftar Submisi Karya Berdasarkan Kategori
     */
    public function index(Request $request, $kategori)
    {
        $kategori = strtolower($kategori);
        $kategoriValid = ['tematik', 'simbiotik', 'simbolik'];

        if (!in_array($kategori, $kategoriValid)) {
            abort(404, 'Kategori karya tidak ditemukan.');
        }

        // Ambil data submisi yang sudah final (status_draft = 0)
        $query = SubmisiKarya::with('user')
            ->where('kategori_karya', $kategori)
            ->where('status_draft', 0);

        // Fitur Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_karya', 'like', "%{$search}%")
                  ->orWhere('kreator_karya', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('institusi', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Berdasarkan Status Verifikasi
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status_verifikasi', $request->status);
        }

        $dataSubmisi = $query->latest()->paginate(12)->withQueryString();

        return view('admin.verifikasi_karya.index', compact('kategori', 'dataSubmisi'));
    }

    /**
     * Mengupdate Status Verifikasi dan Catatan Revisi
     */
    public function updateStatus(Request $request, $id)
    {
        // 1. Tambahkan validasi untuk catatan_revisi
        $request->validate([
            'status_verifikasi' => 'required|in:Terverifikasi,Revisi,Ditolak',
            'catatan_revisi'    => 'required_if:status_verifikasi,Revisi,Ditolak|nullable|string|max:2000'
        ], [
            // Custom pesan error jika catatan revisi kosong saat ditolak/revisi
            'catatan_revisi.required_if' => 'Catatan revisi wajib diisi jika status diubah menjadi Revisi atau Ditolak.'
        ]);

        $submisi = SubmisiKarya::findOrFail($id);
        $submisi->status_verifikasi = $request->status_verifikasi;
        
        // 2. Logika penyimpanan catatan revisi
        if ($request->status_verifikasi === 'Terverifikasi') {
            // Jika karya diterima/terverifikasi, bersihkan catatan revisinya (jika sebelumnya ada)
            $submisi->catatan_revisi = null;
        } else {
            // Jika statusnya Revisi atau Ditolak, simpan catatan dari form
            $submisi->catatan_revisi = $request->catatan_revisi;
        }

        $submisi->save();

        return redirect()->back()->with('success', 'Status karya "' . $submisi->judul_karya . '" berhasil diubah menjadi ' . $request->status_verifikasi . '.');
    }
}