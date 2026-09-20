<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KebijakanPrivasi;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Str;

class KebijakanPrivasiController extends Controller
{
    public function index(Request $request)
    { 
        $query = KebijakanPrivasi::query();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('konten', 'like', '%' . $request->search . '%');
        }

        $dataKebijakan = $query->latest()->paginate(10)->withQueryString();

        return view('admin.kebijakan.index', compact('dataKebijakan'));
    }

    public function create()
    {
        return view('admin.kebijakan.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'konten'    => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        $kebijakan = KebijakanPrivasi::create($request->all());

        // ===========================================================================
        // [LOG AKTIVITAS] Menambah Kebijakan Privasi
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Kebijakan Privasi',
            'aksi'       => 'Create',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menambahkan pasal/dokumen baru berjudul "' . $kebijakan->judul . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.kebijakan.index')->with('success', 'Dokumen Kebijakan Privasi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kebijakan = KebijakanPrivasi::findOrFail($id);
        return view('admin.kebijakan.form', compact('kebijakan'));
    }

    public function update(Request $request, $id)
    {
        $kebijakan = KebijakanPrivasi::findOrFail($id);

        $request->validate([
            'judul'     => 'required|string|max:255',
            'konten'    => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        $kebijakan->update($request->all());

        // ===========================================================================
        // [LOG AKTIVITAS] Mengupdate Kebijakan Privasi
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Kebijakan Privasi',
            'aksi'       => 'Update',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' memperbarui isi dokumen Kebijakan Privasi "' . $kebijakan->judul . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.kebijakan.index')->with('success', 'Dokumen Kebijakan Privasi berhasil diperbarui!');
    }

    public function destroy(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $kebijakan = KebijakanPrivasi::findOrFail($id);
        $judulKebijakan = $kebijakan->judul; // Simpan judul untuk log
        
        $kebijakan->delete();

        // ===========================================================================
        // [LOG AKTIVITAS] Menghapus Kebijakan Privasi
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Kebijakan Privasi',
            'aksi'       => 'Delete',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menghapus permanen pasal Kebijakan Privasi "' . $judulKebijakan . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Data berhasil dihapus permanen!');
    }
}