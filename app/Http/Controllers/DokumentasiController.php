<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumentasi;

class DokumentasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Dokumentasi::where('is_active', 1);

        // Filter Kategori
        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori_kegiatan', $request->kategori);
        }

        $dokumentasis = $query->orderBy('tanggal_kegiatan', 'desc')->paginate(15)->withQueryString();
        
        // Ambil daftar kategori unik untuk menu filter
        $kategoris = Dokumentasi::where('is_active', 1)
            ->select('kategori_kegiatan')
            ->distinct()
            ->pluck('kategori_kegiatan');

        return view('dokumentasi.index', compact('dokumentasis', 'kategoris'));
    }
}