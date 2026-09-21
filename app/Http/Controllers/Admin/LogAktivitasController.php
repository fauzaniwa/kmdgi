<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = LogAktivitas::with('user');

        // Filter Pencarian (Nama Admin atau Deskripsi Log)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('modul', 'like', "%{$search}%")
                  ->orWhere('aksi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Berdasarkan Modul
        if ($request->filled('modul') && $request->modul !== 'all') {
            $query->where('modul', $request->modul);
        }

        // Filter Berdasarkan Aksi
        if ($request->filled('aksi') && $request->aksi !== 'all') {
            $query->where('aksi', $request->aksi);
        }

        $dataLog = $query->latest()->paginate(15)->withQueryString();

        // Ambil daftar unik modul dan aksi untuk opsi dropdown filter
        $listModul = LogAktivitas::select('modul')->distinct()->orderBy('modul', 'asc')->pluck('modul');
        $listAksi = LogAktivitas::select('aksi')->distinct()->orderBy('aksi', 'asc')->pluck('aksi');

        return view('admin.log_aktivitas.index', compact('dataLog', 'listModul', 'listAksi'));
    }
}