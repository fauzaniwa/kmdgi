<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TiketPeserta;
use Illuminate\Http\Request;
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

    public function approve($id)
    {
        $tiket = TiketPeserta::findOrFail($id);
        $tiket->update(['status' => 'Aktif']);
        
        return redirect()->back()->with('success', 'Tiket Pameran atas nama ' . $tiket->user->name . ' berhasil diverifikasi. Data telah dipindahkan ke daftar Peserta Pameran.');
    }

    public function destroy($id)
    {
        $tiket = TiketPeserta::findOrFail($id);
        
        if ($tiket->bukti_pembayaran) {
            Storage::disk('public')->delete($tiket->bukti_pembayaran);
        }
        
        $tiket->delete();
        
        return redirect()->back()->with('success', 'Data pengajuan tiket pameran berhasil ditolak dan dihapus.');
    }
}