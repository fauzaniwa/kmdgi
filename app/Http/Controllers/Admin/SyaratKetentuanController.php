<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SyaratKetentuan;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Str;

class SyaratKetentuanController extends Controller
{
    public function index(Request $request)
    {
        $query = SyaratKetentuan::query();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('konten', 'like', '%' . $request->search . '%');
        }

        $dataSyarat = $query->latest()->paginate(10)->withQueryString();

        return view('admin.syarat.index', compact('dataSyarat'));
    }

    // Menampilkan Halaman Form Tambah Data
    public function create()
    {
        return view('admin.syarat.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'konten'    => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        $syarat = SyaratKetentuan::create($request->all());

        // ===========================================================================
        // [LOG AKTIVITAS] Menambahkan Dokumen Syarat & Ketentuan Baru
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Syarat & Ketentuan',
            'aksi'       => 'Create',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menambahkan dokumen Syarat & Ketentuan baru berjudul "' . $syarat->judul . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.syarat.index')->with('success', 'Dokumen Syarat & Ketentuan berhasil ditambahkan!');
    }

    // Menampilkan Halaman Form Edit Data
    public function edit($id)
    {
        $syarat = SyaratKetentuan::findOrFail($id);
        return view('admin.syarat.form', compact('syarat'));
    }

    public function update(Request $request, $id)
    {
        $syarat = SyaratKetentuan::findOrFail($id);

        $request->validate([
            'judul'     => 'required|string|max:255',
            'konten'    => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        $syarat->update($request->all());

        // ===========================================================================
        // [LOG AKTIVITAS] Mengupdate Dokumen Syarat & Ketentuan
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Syarat & Ketentuan',
            'aksi'       => 'Update',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' memperbarui isi dokumen Syarat & Ketentuan "' . $syarat->judul . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.syarat.index')->with('success', 'Dokumen Syarat & Ketentuan berhasil diperbarui!');
    }

    public function destroy(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $syarat = SyaratKetentuan::findOrFail($id);
        $judulSyarat = $syarat->judul; // Simpan judul untuk log
        
        $syarat->delete();

        // ===========================================================================
        // [LOG AKTIVITAS] Menghapus Dokumen Syarat & Ketentuan
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Syarat & Ketentuan',
            'aksi'       => 'Delete',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menghapus permanen dokumen Syarat & Ketentuan "' . $judulSyarat . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Data berhasil dihapus permanen!');
    }
}