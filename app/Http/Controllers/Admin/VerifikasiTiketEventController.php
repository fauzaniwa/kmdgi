<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TiketPeserta;
use App\Models\EventKmdgi;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use App\Notifications\GeneralNotification; // <-- [NOTIFIKASI] Import Notifikasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Facades\Storage;

class VerifikasiTiketEventController extends Controller
{
    public function index(Request $request)
    {
        // Hanya ambil tiket yang statusnya masih 'Menunggu Konfirmasi'
        $query = TiketPeserta::with(['user', 'event'])
            ->whereNotNull('event_kmdgi_id')
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

        if ($request->filled('event_id') && $request->event_id !== 'all') {
            $query->where('event_kmdgi_id', $request->event_id);
        }

        if ($request->filled('jenis') && $request->jenis !== 'all') {
            $query->where('jenis_tiket', $request->jenis);
        }

        if ($request->filled('sort') && $request->sort === 'terlama') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $tiketEvents = $query->paginate(15)->withQueryString();
        $listEvents = EventKmdgi::orderBy('judul', 'asc')->get();

        return view('admin.verifikasi_event.index', compact('tiketEvents', 'listEvents'));
    }

    public function approve(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $tiket = TiketPeserta::with(['user', 'event'])->findOrFail($id);
        $tiket->update(['status' => 'Aktif']);

        // ===========================================================================
        // [NOTIFIKASI] Kirim Notifikasi Bahwa Tiket Event Telah Disetujui (Aktif)
        // ===========================================================================
        if ($tiket->user) {
            $namaEvent = $tiket->event ? $tiket->event->judul : 'Event KMDGI';
            $tiket->user->notify(new GeneralNotification(
                'Tiket Event Berhasil Diverifikasi!',
                "Pembayaran Anda untuk event \"{$namaEvent}\" telah dikonfirmasi oleh panitia. Tiket Anda kini berstatus Aktif.",
                'success',
                route('dashboard') // Sesuaikan route tujuan user
            ));
        }
        // ===========================================================================

        // ===========================================================================
        // [LOG AKTIVITAS] Menyetujui/Verifikasi Tiket Event
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Verifikasi Tiket Event',
            'aksi'       => 'Approve',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menyetujui verifikasi tiket (' . $tiket->kode_tiket . ') atas nama ' . ($tiket->user->name ?? 'User') . '.',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Tiket atas nama ' . ($tiket->user->name ?? 'User') . ' berhasil diverifikasi. Data telah dipindahkan ke daftar Peserta.');
    }

    public function destroy(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $tiket = TiketPeserta::with(['user', 'event'])->findOrFail($id);
        $namaUser = $tiket->user ? $tiket->user->name : 'User';
        $kodeTiket = $tiket->kode_tiket;

        if ($tiket->bukti_pembayaran) {
            Storage::disk('public')->delete($tiket->bukti_pembayaran);
        }

        $tiket->delete();

        // ===========================================================================
        // [NOTIFIKASI] Kirim Notifikasi Bahwa Pengajuan Tiket Ditolak
        // ===========================================================================
        if ($tiket->user) {
            $namaEvent = $tiket->event ? $tiket->event->judul : 'Event KMDGI';
            $tiket->user->notify(new GeneralNotification(
                'Pengajuan Tiket Event Ditolak',
                "Mohon maaf, bukti pembayaran untuk event \"{$namaEvent}\" ditolak atau tidak valid oleh panitia.",
                'danger',
                route('dashboard')
            ));
        }
        // ===========================================================================

        // ===========================================================================
        // [LOG AKTIVITAS] Menolak/Menghapus Pengajuan Tiket Event
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Verifikasi Tiket Event',
            'aksi'       => 'Reject',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menolak dan menghapus pengajuan tiket (' . $kodeTiket . ') milik ' . $namaUser . '.',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Data pengajuan tiket berhasil ditolak dan dihapus.');
    }
}