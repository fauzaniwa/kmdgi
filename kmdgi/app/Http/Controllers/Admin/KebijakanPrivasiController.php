<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KebijakanPrivasi;
use Illuminate\Http\Request;

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

        KebijakanPrivasi::create($request->all());

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

        return redirect()->route('admin.kebijakan.index')->with('success', 'Dokumen Kebijakan Privasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        KebijakanPrivasi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus permanen!');
    }
}