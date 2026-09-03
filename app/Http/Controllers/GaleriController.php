<?php

namespace App\Http\Controllers;

use App\Models\SubmisiKarya;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    public function index(Request $request)
    {
        // Hanya ambil data yang final dan sudah Terverifikasi
        $query = SubmisiKarya::with(['user', 'komentars'])
            ->where('status_draft', 0)
            ->where('status_verifikasi', 'Terverifikasi');

        // Pencarian opsional
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_karya', 'like', "%{$search}%")
                  ->orWhere('kreator_karya', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('institusi', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Sort (Terbaru / Terpopuler)
        if ($request->sort == 'terpopuler') {
            $query->orderByDesc('likes_count');
        } else {
            $query->latest();
        }

        $karyas = $query->paginate(12)->withQueryString();

        return view('galeri.index', compact('karyas'));
    }

    public function show($id)
    {
        $karya = SubmisiKarya::with(['user', 'komentars.user'])->findOrFail($id);
        
        // Proteksi agar karya yang belum diverifikasi tidak bisa ditembak via URL
        if($karya->status_verifikasi !== 'Terverifikasi' || $karya->status_draft !== 0) {
            abort(404, 'Karya tidak ditemukan atau belum dipublikasikan.');
        }

        return view('galeri.show', compact('karya'));
    }

    public function toggleLike($id)
    {
        $karya = SubmisiKarya::findOrFail($id);
        
        // Increment jumlah like. Pastikan ada kolom 'likes_count' di tabel submisi_karyas (integer, default 0)
        $karya->increment('likes_count'); 

        return response()->json([
            'success' => true,
            'likes_count' => $karya->likes_count
        ]);
    }
}