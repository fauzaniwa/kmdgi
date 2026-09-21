<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KaryaKomentar;
use App\Models\LaporanKomentar;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Str;

class KomentarController extends Controller
{
    public function index()
    {
        // Menarik SEMUA komentar.
        // Diurutkan berdasarkan jumlah laporan (terbanyak di atas), lalu berdasarkan yang terbaru.
        $komentars = KaryaKomentar::with(['user', 'submisiKarya', 'laporans.user'])
            ->withCount('laporans') 
            ->orderByDesc('laporans_count') 
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.komentar.index', compact('komentars'));
    }

    public function destroy(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $komentar = KaryaKomentar::with('user', 'submisiKarya')->findOrFail($id);
        
        $isiKomentar = $komentar->isi_komentar;
        $pemilikKomentar = $komentar->user ? $komentar->user->name : 'Pengguna Tidak Dikenal';
        $judulKarya = $komentar->submisiKarya ? $komentar->submisiKarya->judul_karya : 'Karya';

        $komentar->delete(); // Laporan otomatis terhapus karena Cascade

        // ===========================================================================
        // [LOG AKTIVITAS] Menghapus Komentar (Moderasi)
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Moderasi Komentar',
            'aksi'       => 'Delete',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menghapus komentar oleh "' . $pemilikKomentar . '" pada karya "' . $judulKarya . '" karena melanggar aturan. Isi: "' . Str::limit($isiKomentar, 40) . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================
        
        return redirect()->back()->with('success', 'Komentar berhasil dihapus secara permanen.');
    }

    public function dismissReport(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $komentar = KaryaKomentar::with('submisiKarya')->findOrFail($id);
        $judulKarya = $komentar->submisiKarya ? $komentar->submisiKarya->judul_karya : 'Karya';

        LaporanKomentar::where('karya_komentar_id', $id)->delete();
        
        // ===========================================================================
        // [LOG AKTIVITAS] Mengabaikan Laporan Komentar (Dismiss)
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Moderasi Komentar',
            'aksi'       => 'Dismiss Report',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' mengabaikan laporan pelanggaran pada sebuah komentar di karya "' . $judulKarya . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Laporan berhasil diabaikan. Status komentar kembali aman.');
    }
}