<?php

namespace App\Http\Controllers;

use App\Models\DeskripsiKarya;
use App\Models\EdisiKmdgi;
use App\Models\PanduanDelegasi;
use App\Models\Kampus;
use App\Models\SubmisiKarya;
use App\Models\KaryaKomentar;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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

        $deskripsiKarya = DeskripsiKarya::where('edisi_kmdgi_id', $edisiAktif->id)
            ->whereIn('kategori_karya', [$kategori, ucfirst($kategori)])
            ->first();

        $userIdsSatuKampus = User::where('institusi', $user->institusi)->pluck('id');

        $draft = SubmisiKarya::whereIn('user_id', $userIdsSatuKampus)
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

        $request->validate([
            'judul_karya'     => $isDraft ? 'nullable|string' : 'required|string',
            'kreator_karya'   => $isDraft ? 'nullable|string' : 'required|string',
            'deskripsi_karya' => $isDraft ? 'nullable|string' : 'required|string',
            'thumbnail_karya' => 'nullable|image|mimes:jpeg,png,jpg,svg,gif|max:5120',
            'link_karya'      => $isDraft ? 'nullable|url' : 'required_without:file_karya|nullable|url',
            'file_karya'      => $isDraft ? 'nullable|file' : 'required_without:link_karya|nullable|file|mimes:zip,pdf,jpeg,png,jpg,svg,gif|max:20480',

            // Validasi file baru berbentuk array
            'media_baru.*'    => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,webm,mov|max:20480',
        ], [
            'link_karya.required_without' => 'Anda harus mengisi Tautan Karya ATAU mengunggah File Karya.',
            'file_karya.required_without' => 'Anda harus mengunggah File Karya ATAU mengisi Tautan Karya.',
            'media_baru.*.mimes'          => 'Format media tambahan harus berupa gambar atau video yang valid.',
        ]);

        $userIdsSatuKampus = User::where('institusi', $user->institusi)->pluck('id');

        $submisi = SubmisiKarya::whereIn('user_id', $userIdsSatuKampus)
            ->where('edisi_kmdgi_id', $edisiAktif->id)
            ->where('kategori_karya', $kategori)
            ->first();

        if (!$submisi) {
            $submisi = new SubmisiKarya();
            $submisi->edisi_kmdgi_id = $edisiAktif->id;
            $submisi->kategori_karya = $kategori;
        }

        $submisi->user_id         = $user->id;
        $submisi->judul_karya     = $request->judul_karya;
        $submisi->kreator_karya   = $request->kreator_karya;
        $submisi->deskripsi_karya = $request->deskripsi_karya;
        $submisi->link_karya      = $request->link_karya;
        $submisi->status_draft    = $isDraft;

        // Reset status_verifikasi agar masuk kembali ke antrean admin saat diedit
        $submisi->status_verifikasi = 'Menunggu';

        // ---------------------------------------------------------
        // LOGIKA PENGHAPUSAN DAN UPLOAD THUMBNAIL UTAMA
        // ---------------------------------------------------------
        if ($request->hasFile('thumbnail_karya')) {
            if ($submisi->thumbnail_karya) Storage::disk('public')->delete($submisi->thumbnail_karya);
            $submisi->thumbnail_karya = $request->file('thumbnail_karya')->store('submisi/thumbnail', 'public');
        } elseif ($request->input('remove_thumbnail') == '1') {
            if ($submisi->thumbnail_karya) Storage::disk('public')->delete($submisi->thumbnail_karya);
            $submisi->thumbnail_karya = null;
        }

        // ---------------------------------------------------------
        // LOGIKA PENGHAPUSAN DAN UPLOAD FILE KARYA (ZIP/PDF)
        // ---------------------------------------------------------
        if ($request->hasFile('file_karya')) {
            if ($submisi->file_karya) Storage::disk('public')->delete($submisi->file_karya);
            $submisi->file_karya = $request->file('file_karya')->store('submisi/karya', 'public');
        } elseif ($request->input('remove_file') == '1') {
            if ($submisi->file_karya) Storage::disk('public')->delete($submisi->file_karya);
            $submisi->file_karya = null;
        }

        // ---------------------------------------------------------
        // LOGIKA PENYIMPANAN GALERI MEDIA (ARRAY)
        // ---------------------------------------------------------
        $currentMedia = is_string($submisi->media_karya) ? json_decode($submisi->media_karya, true) : ($submisi->media_karya ?? []);
        if (!is_array($currentMedia)) $currentMedia = [];

        if ($request->has('remove_existing')) {
            foreach ($request->remove_existing as $idx => $flag) {
                if ($flag == '1' && isset($currentMedia[$idx])) {
                    Storage::disk('public')->delete($currentMedia[$idx]);
                    unset($currentMedia[$idx]);
                }
            }
        }

        $currentMedia = array_values($currentMedia);

        if ($request->hasFile('media_baru')) {
            foreach ($request->file('media_baru') as $file) {
                if (count($currentMedia) < 8) {
                    $currentMedia[] = $file->store('submisi/media_tambahan', 'public');
                }
            }
        }

        $submisi->media_karya = $currentMedia;
        $submisi->save();

        // ===========================================================================
        // [NOTIFIKASI] Submisi Karya Resmi (Bukan Draft)
        // ===========================================================================
        if (!$isDraft) {
            // 1. Beritahu Admin & Super Admin bahwa ada karya masuk kurasi
            $admins = User::whereIn('role', ['super admin', 'admin'])->get();
            if ($admins->count() > 0) {
                $pesanAdmin = "Kontingen {$user->institusi} ({$user->name}) telah mengunggah karya kategori " . ucfirst($kategori) . " berjudul \"{$submisi->judul_karya}\". Silakan periksa antrean kurasi.";
                
                Notification::send($admins, new GeneralNotification(
                    'Submisi Karya Baru Masuk',
                    $pesanAdmin,
                    'info',
                    route('admin.verifikasi_karya.index', $kategori)
                ));
            }

            // 2. Beritahu Peserta Delegasi bahwa karyanya berhasil masuk antrean kurasi
            $user->notify(new GeneralNotification(
                'Submisi Karya Berhasil Terkirim',
                "Karya Anda berjudul \"{$submisi->judul_karya}\" untuk kategori " . ucfirst($kategori) . " berhasil diunggah dan sedang dalam antrean kurasi kurator KMDGI.",
                'success',
                route('delegasi.submisi.panduan', $kategori)
            ));
        }
        // ===========================================================================

        $pesan = $isDraft
            ? 'Draft Karya ' . ucfirst($kategori) . ' berhasil disimpan!'
            : 'Submisi Karya ' . ucfirst($kategori) . ' berhasil diunggah dan terkirim ke antrean kurasi!';

        return redirect()->route('delegasi.submisi.panduan', $kategori)->with('success', $pesan);
    }

    public function karyaKampus()
    {
        $user = Auth::user();
        $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();

        if (!$edisiAktif) {
            return redirect()->back()->withErrors(['Sistem belum memiliki Edisi KMDGI yang aktif.']);
        }

        $userIdsSatuKampus = User::where('institusi', $user->institusi)->pluck('id');

        $kumpulanKarya = SubmisiKarya::whereIn('user_id', $userIdsSatuKampus)
            ->where('edisi_kmdgi_id', $edisiAktif->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('delegasi.submisi.karya_kampus', compact('kumpulanKarya', 'user'));
    }

    public function storeKomentar(Request $request)
    {
        $request->validate([
            'submisi_karya_id' => 'required|exists:submisi_karyas,id',
            'isi_komentar'     => 'required|string|max:1000',
            'parent_id'        => 'nullable|exists:karya_komentars,id'
        ]);

        $currentUser = Auth::user();

        $komentar = KaryaKomentar::create([
            'submisi_karya_id' => $request->submisi_karya_id,
            'user_id'          => $currentUser->id,
            'parent_id'        => $request->parent_id,
            'isi_komentar'     => $request->isi_komentar,
        ]);

        // ===========================================================================
        // [NOTIFIKASI] Komentar Baru & Balasan (Reply)
        // ===========================================================================
        $submisi = SubmisiKarya::with('user')->find($request->submisi_karya_id);
        
        $targetUrl = isset($submisi->slug) 
            ? route('katalog.karya.show', $submisi->slug) 
            : url('/katalog-karya/' . $submisi->id);

        $notifiedUserIds = [];

        // 1. Jika ini merupakan balasan komentar (Reply)
        if ($request->filled('parent_id')) {
            $parentKomentar = KaryaKomentar::with('user')->find($request->parent_id);
            if ($parentKomentar && $parentKomentar->user && $parentKomentar->user_id !== $currentUser->id) {
                $parentKomentar->user->notify(new GeneralNotification(
                    'Balasan Komentar Baru',
                    "{$currentUser->name} membalas komentar Anda pada karya \"{$submisi->judul_karya}\": \"{$komentar->isi_komentar}\"",
                    'info',
                    $targetUrl
                ));
                $notifiedUserIds[] = $parentKomentar->user_id;
            }
        }

        // 2. Kirim notifikasi ke Pemilik Karya (jika yang berkomentar bukan pemilik karya itu sendiri)
        if ($submisi && $submisi->user && $submisi->user_id !== $currentUser->id) {
            // Hindari pengiriman ganda jika pemilik karya adalah orang yang sama dengan yang dibalas
            if (!in_array($submisi->user_id, $notifiedUserIds)) {
                $submisi->user->notify(new GeneralNotification(
                    'Komentar Baru pada Karya Anda',
                    "{$currentUser->name} memberikan komentar pada karya Anda \"{$submisi->judul_karya}\": \"{$komentar->isi_komentar}\"",
                    'info',
                    $targetUrl
                ));
            }
        }
        // ===========================================================================

        return redirect()->back()->with('success', 'Komentar berhasil dikirim!');
    }

    public function reportKomentar(Request $request)
    {
        $request->validate([
            'komentar_id' => 'required|exists:karya_komentars,id',
            'alasan'      => 'required|string|max:1000',
        ]);

        $currentUser = Auth::user();

        DB::table('laporan_komentars')->insert([
            'karya_komentar_id' => $request->komentar_id,
            'user_id'           => $currentUser->id,
            'alasan'            => $request->alasan,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // ===========================================================================
        // [NOTIFIKASI] Laporan Komentar Masuk ke Admin & Super Admin
        // ===========================================================================
        $admins = User::whereIn('role', ['super admin', 'admin'])->get();
        if ($admins->count() > 0) {
            Notification::send($admins, new GeneralNotification(
                'Laporan Komentar Masuk',
                "Pengguna {$currentUser->name} melaporkan sebuah komentar karya. Alasan: \"{$request->alasan}\". Mohon segera ditinjau.",
                'warning',
                route('admin.komentar.index')
            ));
        }
        // ===========================================================================

        return redirect()->back()->with('success', 'Laporan Anda telah diterima dan akan ditinjau oleh Admin.');
    }

    public function destroyKomentar(Request $request)
    {
        $request->validate(['komentar_id' => 'required|exists:karya_komentars,id']);

        $komentar = KaryaKomentar::findOrFail($request->komentar_id);

        if ($komentar->user_id == Auth::id()) {
            $komentar->delete();
            return redirect()->back()->with('success', 'Komentar Anda berhasil dihapus.');
        }

        return redirect()->back()->withErrors(['Akses ditolak. Anda tidak memiliki izin menghapus komentar ini.']);
    }
}