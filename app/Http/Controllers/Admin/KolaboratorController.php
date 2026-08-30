<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kolaborator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KolaboratorController extends Controller
{
    public function index(Request $request)
    {
        $query = Kolaborator::query();

        // FIX 1: Bungkus pencarian di dalam Closure function() agar orWhere tidak bocor
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('profesi', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('peran_kolaborasi') && $request->peran_kolaborasi !== 'all') {
            $query->where('peran_kolaborasi', $request->peran_kolaborasi);
        }

        // Urutkan berdasarkan urutan custom drag & drop
        $dataKolaborator = $query->orderBy('urutan', 'asc')->paginate(12)->withQueryString();

        return view('admin.kolaborator.index', compact('dataKolaborator'));
    }

    public function updateUrutan(Request $request)
    {
        $urutans = $request->input('urutan'); 
        $offset = $request->input('offset', 0); 

        // FIX: Pastikan input urutans adalah array yang valid
        if (is_array($urutans)) {
            foreach ($urutans as $index => $id) {
                Kolaborator::where('id', $id)->update(['urutan' => $offset + $index + 1]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function create()
    {
        return view('admin.kolaborator.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'             => 'required|string|max:255',
            'profesi'          => 'required|string|max:255',
            'peran_kolaborasi' => 'required|string|max:255',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'detail'           => 'nullable|string',
            'link_instagram'   => 'nullable|string|max:255',
            'link_linkedin'    => 'nullable|string|max:255',
            'link_website'     => 'nullable|string|max:255',
            'is_active'        => 'required|boolean',
        ]);

        // Gunakan except untuk menghindari field ekstra seperti _token
        $data = $request->except(['_token', 'foto']);

        // FIX 2: Set nilai 'urutan' otomatis menjadi nilai terbesar + 1 agar selalu di posisi bawah (aman dari bug order)
        $maxUrutan = Kolaborator::max('urutan');
        $data['urutan'] = $maxUrutan ? $maxUrutan + 1 : 1;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('kolaborator_fotos', 'public');
        }

        Kolaborator::create($data);

        return redirect()->route('admin.kolaborator.index')->with('success', 'Data Kolaborator berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kolaborator = Kolaborator::findOrFail($id);
        return view('admin.kolaborator.form', compact('kolaborator'));
    }

    public function update(Request $request, $id)
    {
        $kolaborator = Kolaborator::findOrFail($id);

        $request->validate([
            'nama'             => 'required|string|max:255',
            'profesi'          => 'required|string|max:255',
            'peran_kolaborasi' => 'required|string|max:255',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'detail'           => 'nullable|string',
            'link_instagram'   => 'nullable|string|max:255',
            'link_linkedin'    => 'nullable|string|max:255',
            'link_website'     => 'nullable|string|max:255',
            'is_active'        => 'required|boolean',
        ]);

        $data = $request->except(['_token', '_method', 'foto', 'remove_foto']);

        // FIX 3: Tambahkan logika penghapusan foto lama jika file baru diupload atau fitur hapus foto dicentang
        if ($request->hasFile('foto')) {
            if ($kolaborator->foto) Storage::disk('public')->delete($kolaborator->foto);
            $data['foto'] = $request->file('foto')->store('kolaborator_fotos', 'public');
        } elseif ($request->input('remove_foto') == '1') {
            if ($kolaborator->foto) Storage::disk('public')->delete($kolaborator->foto);
            $data['foto'] = null;
        }

        $kolaborator->update($data);

        return redirect()->route('admin.kolaborator.index')->with('success', 'Data Kolaborator berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kolaborator = Kolaborator::findOrFail($id);
        
        // Hapus file foto dari storage lokal saat data dihapus
        if ($kolaborator->foto) {
            Storage::disk('public')->delete($kolaborator->foto);
        }
        
        $kolaborator->delete();

        return redirect()->back()->with('success', 'Data Kolaborator beserta fotonya telah dihapus permanen!');
    }
}