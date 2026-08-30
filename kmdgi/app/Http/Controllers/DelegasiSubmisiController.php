<?php

namespace App\Http\Controllers;

use App\Models\DeskripsiKarya;
use App\Models\EdisiKmdgi;
use App\Models\PanduanDelegasi;
use App\Models\Kampus;
use App\Models\SubmisiKarya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DelegasiSubmisiController extends Controller
{
    public function panduan($kategori)
    {
        $kategori = strtolower($kategori);
        $kategoriValid = ['tematik', 'simbiotik', 'simbolik'];

        if (!in_array($kategori, $kategoriValid)) {
            abort(404, 'Kategori karya tidak ditemukan.');
        }

        $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();
        $panduanDelegasi = PanduanDelegasi::first();

        $deskripsiKarya = null;
        if ($edisiAktif) {
            $deskripsiKarya = DeskripsiKarya::where('edisi_kmdgi_id', $edisiAktif->id)
                ->whereIn('kategori_karya', [$kategori, ucfirst($kategori)])
                ->first();
        }

        return view('delegasi.submisi.panduan', compact('kategori', 'deskripsiKarya', 'panduanDelegasi'));
    }

    public function formDaftar($kategori)
    {
        $kategori = strtolower($kategori);
        $user = Auth::user();
        $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();

        if (!$edisiAktif) return redirect()->back()->withErrors(['Sistem belum memiliki Edisi KMDGI yang aktif.']);

        $kampus = Kampus::where('nama_institusi', $user->institusi)->first();
        if (!$kampus) return redirect()->back()->withErrors(['Data Institusi Anda tidak terdaftar di sistem.']);

        $pivotKampus = $kampus->edisi()->where('edisi_kmdgi_id', $edisiAktif->id)->first();
        $statusKampus = $pivotKampus ? strtolower($pivotKampus->pivot->status_keanggotaan) : null;

        if (!$statusKampus) return redirect()->back()->withErrors(['Kampus Anda belum dikonfirmasi pada Edisi KMDGI ini.']);

        if ($kategori === 'tematik' && str_contains($statusKampus, 'peninjau 1')) {
            return redirect()->route('delegasi.submisi.panduan', $kategori)
                ->withErrors(['Akses Ditolak: Status kampus Anda (' . ucwords($statusKampus) . ') hanya diizinkan untuk mendaftar karya Simbolik dan Simbiotik.']);
        }

        // AMBIL DATA DESKRIPSI KARYA (PANDUAN / GUIDEBOOK)
        $deskripsiKarya = DeskripsiKarya::where('edisi_kmdgi_id', $edisiAktif->id)
            ->whereIn('kategori_karya', [$kategori, ucfirst($kategori)])
            ->first();

        // AMBIL DATA DRAFT
        $draft = SubmisiKarya::where('user_id', $user->id)
            ->where('kategori_karya', $kategori)
            ->where('edisi_kmdgi_id', $edisiAktif->id)
            ->first();

        return view('delegasi.submisi.form', compact('kategori', 'kampus', 'statusKampus', 'draft', 'deskripsiKarya'));
    }

    public function storeDaftar(Request $request, $kategori)
    {
        $kategori = strtolower($kategori);
        $user = Auth::user();
        $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();

        $isDraft = $request->input('status_draft') == '1';

        // Validasi: Jika bukan draft, data teks wajib diisi
        $request->validate([
            'judul_karya' => $isDraft ? 'nullable|string' : 'required|string',
            'kreator_karya' => $isDraft ? 'nullable|string' : 'required|string',
            'deskripsi_karya' => $isDraft ? 'nullable|string' : 'required|string',
            'thumbnail_karya' => 'nullable|image|mimes:jpeg,png,jpg,svg,gif|max:5120',
            'link_karya' => $isDraft ? 'nullable|url' : 'required_without:file_karya|nullable|url',
            'file_karya' => $isDraft ? 'nullable|file' : 'required_without:link_karya|nullable|file|mimes:zip,pdf,jpeg,png,jpg,svg,gif|max:20480',
        ], [
            'link_karya.required_without' => 'Anda harus mengisi Tautan Karya ATAU mengunggah File Karya.',
            'file_karya.required_without' => 'Anda harus mengunggah File Karya ATAU mengisi Tautan Karya.',
        ]);

        // Cari atau buat record baru
        $submisi = SubmisiKarya::firstOrNew([
            'user_id' => $user->id,
            'edisi_kmdgi_id' => $edisiAktif->id,
            'kategori_karya' => $kategori,
        ]);

        $submisi->judul_karya = $request->judul_karya;
        $submisi->kreator_karya = $request->kreator_karya;
        $submisi->deskripsi_karya = $request->deskripsi_karya;
        $submisi->link_karya = $request->link_karya;
        $submisi->status_draft = $isDraft;

        // Upload Thumbnail
        if ($request->hasFile('thumbnail_karya')) {
            if ($submisi->thumbnail_karya) Storage::disk('public')->delete($submisi->thumbnail_karya);
            $submisi->thumbnail_karya = $request->file('thumbnail_karya')->store('submisi/thumbnail', 'public');
        } elseif (!$isDraft && !$submisi->thumbnail_karya) {
            return back()->withInput()->withErrors(['Thumbnail wajib diunggah.']);
        }

        // Upload File Karya (Opsional)
        if ($request->hasFile('file_karya')) {
            if ($submisi->file_karya) Storage::disk('public')->delete($submisi->file_karya);
            $submisi->file_karya = $request->file('file_karya')->store('submisi/karya', 'public');
        }

        $submisi->save();

        $pesan = $isDraft
            ? 'Draft Karya ' . ucfirst($kategori) . ' berhasil disimpan!'
            : 'Submisi Karya ' . ucfirst($kategori) . ' berhasil diunggah dan terkirim!';

        return redirect()->route('delegasi.submisi.panduan', $kategori)->with('success', $pesan);
    }
}