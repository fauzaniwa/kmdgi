<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TiketPeserta;
use App\Models\Penampil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerifikasiTiketPerformanceController extends Controller
{
    public function index(Request $request)
    {
        // FIX: Hanya tampilkan status 'Menunggu Konfirmasi'
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