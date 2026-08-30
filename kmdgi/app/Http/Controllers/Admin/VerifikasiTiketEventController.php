<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TiketPeserta;
use App\Models\EventKmdgi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerifikasiTiketEventController extends Controller
{
    public function index(Request $request)
    {
        // FIX: Hanya ambil tiket yang statusnya masih 'Menunggu Konfirmasi'
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

    public function approve($id)
    {
        $tiket = TiketPeserta::findOrFail($id);
        $tiket->update(['status' => 'Aktif']);
        return redirect()->back()->with('success', 'Tiket atas nama ' . $tiket->user->name . ' berhasil diverifikasi. Data telah dipindahkan ke daftar Peserta.');
    }

    public function destroy($id)
    {
        $tiket = TiketPeserta::findOrFail($id);
        if ($tiket->bukti_pembayaran) {
            Storage::disk('public')->delete($tiket->bukti_pembayaran);
        }
        $tiket->delete();
        return redirect()->back()->with('success', 'Data pengajuan tiket berhasil ditolak dan dihapus.');
    }
}