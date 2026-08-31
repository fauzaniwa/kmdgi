<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KaryaKomentar;
use App\Models\LaporanKomentar;
use Illuminate\Http\Request;

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

    public function destroy($id)
    {
        $komentar = KaryaKomentar::findOrFail($id);
        $komentar->delete(); // Laporan otomatis terhapus karena Cascade
        
        return redirect()->back()->with('success', 'Komentar berhasil dihapus secara permanen.');
    }

    public function dismissReport($id)
    {
        LaporanKomentar::where('karya_komentar_id', $id)->delete();
        
        return redirect()->back()->with('success', 'Laporan berhasil diabaikan. Status komentar kembali aman.');
    }
}