<?php

namespace App\Http\Controllers;

use App\Models\Kolaborator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KolaboratorController extends Controller
{
    /**
     * Menampilkan daftar semua Kolaborator (Katalog Publik)
     */
    public function index(Request $request)
    {
        $query = Kolaborator::where('is_active', true);

        // Filter Pencarian (Nama atau Profesi)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('profesi', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Peran Kolaborasi
        if ($request->filled('peran') && $request->peran !== 'all') {
            $query->where('peran_kolaborasi', $request->peran);
        }

        // Eksekusi dengan Pagination
        $kolaborators = $query->orderBy('urutan', 'asc')->paginate(12)->withQueryString();

        return view('kolaborator.index', compact('kolaborators'));
    }

    /**
     * Menampilkan detail spesifik satu Kolaborator berdasarkan Slug Nama
     */
    public function show($slug)
    {
        // Ambil semua data kolaborator yang aktif
        $semuaKolaborator = Kolaborator::where('is_active', true)->get();

        // Cari kolaborator yang hasil konversi namanya (slug) cocok dengan URL
        $kolaborator = $semuaKolaborator->first(function ($item) use ($slug) {
            return Str::slug($item->nama) === $slug;
        });

        // Jika tidak ada nama yang cocok, tampilkan halaman 404
        if (!$kolaborator) {
            abort(404, 'Profil Kolaborator tidak ditemukan.');
        }

        // Ambil data kolaborator lainnya secara acak (maksimal 4) untuk section bawah
        $kolaboratorLainnya = Kolaborator::where('is_active', true)
            ->where('id', '!=', $kolaborator->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Kembalikan ke halaman view show.blade.php yang baru saja dibuat
        return view('kolaborator.show', compact('kolaborator', 'kolaboratorLainnya'));
    }
}
