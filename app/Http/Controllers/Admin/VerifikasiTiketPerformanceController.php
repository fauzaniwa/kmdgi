<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TiketPeserta;
use App\Models\Penampil;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use App\Notifications\GeneralNotification; // <-- [NOTIFIKASI] Import Notifikasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Facades\Storage;

class VerifikasiTiketPerformanceController extends Controller
{
    public function index(Request $request)
    {
        // Hanya tampilkan status 'Menunggu Konfirmasi'
        $query = TiketPeserta::with(['user', 'penampil'])
            ->whereNotNull('penampil_id')
            ->where('status', 'Menunggu Konfirmasi');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_tiket', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('penampil_id') && $request->penampil_id !== 'all') {
            $query->where('penampil_id', $request->penampil_id);
        }

        if ($request->filled('sort') && $request->sort === 'terlama') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $tiketPerformances = $query->paginate(15)->withQueryString();
        $listPenampil = Penampil::orderBy('nama_penampil', 'asc')->get();

        return view('admin.verifikasi_performance.index', compact('tiketPerformances', 'listPenampil'));
    }

    public function approve(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $tiket = TiketPeserta::with(['user', 'penampil'])->findOrFail($id);
        $tiket->update(['status' => 'Aktif']);

        // ===========================================================================
        // [NOTIFIKASI] Kirim Notifikasi Bahwa Tiket Performance Telah Disetujui (Aktif)
        // ===========================================================================
        if ($tiket->user) {
            $namaPenampil = $tiket->penampil ? $tiket->penampil->nama_penampil : 'Penampil/Artis';
            $tiket->user->notify(new GeneralNotification(
                'Tiket Performance Berhasil Diverifikasi!',
                "Pembayaran Anda untuk konser/performance \"{$namaPenampil}\" telah dikonfirmasi oleh panitia. Tiket Anda kini berstatus Aktif.",
                'success',
                route('dashboard')
            ));
        }
        // ===========================================================================

        // ===========================================================================
        // [LOG AKTIVITAS] Menyetujui/Verifikasi Tiket Performance
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Verifikasi Tiket Performance',
            'aksi'       => 'Approve',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menyetujui verifikasi tiket performance (' . $tiket->kode_tiket . ') atas nama ' . ($tiket->user->name ?? 'User') . '.',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Tiket atas nama ' . ($tiket->user->name ?? 'User') . ' berhasil diverifikasi. Data telah dipindahkan ke daftar Peserta.');
    }

    public function destroy(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $tiket = TiketPeserta::with(['user', 'penampil'])->findOrFail($id);
        $namaUser = $tiket->user ? $tiket->user->name : 'User';
        $kodeTiket = $tiket->kode_tiket;

        if ($tiket->bukti_pembayaran) {
            Storage::disk('public')->delete($tiket->bukti_pembayaran);
        }

        $tiket->delete();

        // ===========================================================================
        // [NOTIFIKASI] Kirim Notifikasi Bahwa Pengajuan Tiket Performance Ditolak
        // ===========================================================================
        if ($tiket->user) {
            $namaPenampil = $tiket->penampil ? $tiket->penampil->nama_penampil : 'Penampil/Artis';
            $tiket->user->notify(new GeneralNotification(
                'Pengajuan Tiket Performance Ditolak',
                "Mohon maaf, bukti pembayaran untuk performance \"{$namaPenampil}\" ditolak atau tidak valid oleh panitia.",
                'danger',
                route('dashboard')
            ));
        }
        // ===========================================================================

        // ===========================================================================
        // [LOG AKTIVITAS] Menolak/Menghapus Pengajuan Tiket Performance
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Verifikasi Tiket Performance',
            'aksi'       => 'Reject',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menolak dan menghapus pengajuan tiket performance (' . $kodeTiket . ') milik ' . $namaUser . '.',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Data pengajuan tiket berhasil ditolak dan dihapus.');
    }
}