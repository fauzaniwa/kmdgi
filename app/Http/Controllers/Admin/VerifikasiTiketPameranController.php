<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TiketPeserta;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use App\Notifications\GeneralNotification; // <-- [NOTIFIKASI] Import Notifikasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Facades\Storage;

class VerifikasiTiketPameranController extends Controller
{
    public function index(Request $request)
    {
        // Hanya ambil tiket dengan jenis 'Pameran' dan berstatus 'Menunggu Konfirmasi'
        $query = TiketPeserta::with(['user'])
            ->where('jenis_tiket', 'Pameran')
            ->where('status', 'Menunggu Konfirmasi');

        // Pencarian berdasarkan Kode Tiket atau Nama Peserta
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_tiket', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Pengurutan (Terbaru/Terlama)
        if ($request->filled('sort') && $request->sort === 'terlama') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $tiketPameran = $query->paginate(15)->withQueryString();

        return view('admin.verifikasi_pameran.index', compact('tiketPameran'));
    }

    public function approve(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $tiket = TiketPeserta::with(['user'])->findOrFail($id);
        $tiket->update(['status' => 'Aktif']);
        
        // ===========================================================================
        // [NOTIFIKASI] Kirim Notifikasi Bahwa Tiket Pameran Telah Disetujui (Aktif)
        // ===========================================================================
        if ($tiket->user) {
            $tiket->user->notify(new GeneralNotification(
                'Tiket Pameran Berhasil Diverifikasi!',
                "Pembayaran Anda untuk Tiket Pameran KMDGI telah dikonfirmasi oleh panitia. Tiket Anda kini berstatus Aktif.",
                'success',
                route('dashboard')
            ));
        }
        // ===========================================================================

        // ===========================================================================
        // [LOG AKTIVITAS] Menyetujui/Verifikasi Tiket Pameran
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Verifikasi Tiket Pameran',
            'aksi'       => 'Approve',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menyetujui verifikasi tiket pameran (' . $tiket->kode_tiket . ') atas nama ' . ($tiket->user->name ?? 'User') . '.',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Tiket Pameran atas nama ' . ($tiket->user->name ?? 'User') . ' berhasil diverifikasi. Data telah dipindahkan ke daftar Peserta Pameran.');
    }

    public function destroy(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $tiket = TiketPeserta::with(['user'])->findOrFail($id);
        $namaUser = $tiket->user ? $tiket->user->name : 'User';
        $kodeTiket = $tiket->kode_tiket;
        
        if ($tiket->bukti_pembayaran) {
            Storage::disk('public')->delete($tiket->bukti_pembayaran);
        }
        
        $tiket->delete();
        
        // ===========================================================================
        // [NOTIFIKASI] Kirim Notifikasi Bahwa Pengajuan Tiket Pameran Ditolak
        // ===========================================================================
        if ($tiket->user) {
            $tiket->user->notify(new GeneralNotification(
                'Pengajuan Tiket Pameran Ditolak',
                "Mohon maaf, bukti pembayaran untuk Tiket Pameran ditolak atau tidak valid oleh panitia.",
                'danger',
                route('dashboard')
            ));
        }
        // ===========================================================================

        // ===========================================================================
        // [LOG AKTIVITAS] Menolak/Menghapus Pengajuan Tiket Pameran
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Verifikasi Tiket Pameran',
            'aksi'       => 'Reject',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menolak dan menghapus pengajuan tiket pameran (' . $kodeTiket . ') milik ' . $namaUser . '.',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Data pengajuan tiket pameran berhasil ditolak dan dihapus.');
    }
}