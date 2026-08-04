<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SyaratKetentuan;
use Illuminate\Http\Request;

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

    // [BARU] Menampilkan Halaman Form Tambah Data
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

        SyaratKetentuan::create($request->all());

        return redirect()->route('admin.syarat.index')->with('success', 'Dokumen Syarat & Ketentuan berhasil ditambahkan!');
    }

    // [BARU] Menampilkan Halaman Form Edit Data
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

        return redirect()->route('admin.syarat.index')->with('success', 'Dokumen Syarat & Ketentuan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        SyaratKetentuan::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus permanen!');
    }
}