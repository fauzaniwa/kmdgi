<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    public function index(Request $request)
    {
        $query = Sponsor::query();

        if ($request->filled('search')) {
            $query->where('nama_mitra', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori', $request->kategori);
        }

        // LOGIKA SORTING: Prioritas Utama pada kolom Urutan Custom, lalu ditata berdasarkan Tier secara fallback
        $dataSponsor = $query->orderBy('urutan', 'asc')
                             ->orderByRaw("FIELD(tier_kelas, 'Utama (Besar)', 'Madya (Sedang)', 'Pratama (Kecil)') ASC")
                             ->paginate(15)->withQueryString();

        return view('admin.sponsor.index', compact('dataSponsor'));
    }

    public function updateUrutan(Request $request)
    {
        $urutans = $request->input('urutan'); 
        $offset = $request->input('offset', 0); 

        foreach ($urutans as $index => $id) {
            Sponsor::where('id', $id)->update(['urutan' => $offset + $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function create()
    {
        return view('admin.sponsor.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mitra'  => 'required|string|max:255',
            'logo'        => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'kategori'    => 'required|string|max:255',
            'tier_kelas'  => 'required|string|max:255',
            'link_tautan' => 'nullable|url|max:255',
            'deskripsi'   => 'nullable|string',
            'is_active'   => 'required|boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('sponsors_logo', 'public');
        }

        Sponsor::create($data);

        return redirect()->route('admin.sponsor.index')->with('success', 'Data Sponsor/Mitra berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $sponsor = Sponsor::findOrFail($id);
        return view('admin.sponsor.form', compact('sponsor'));
    }

    public function update(Request $request, $id)
    {
        $sponsor = Sponsor::findOrFail($id);

        $request->validate([
            'nama_mitra'  => 'required|string|max:255',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'kategori'    => 'required|string|max:255',
            'tier_kelas'  => 'required|string|max:255',
            'link_tautan' => 'nullable|url|max:255',
            'deskripsi'   => 'nullable|string',
            'is_active'   => 'required|boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            if ($sponsor->logo) Storage::disk('public')->delete($sponsor->logo);
            $data['logo'] = $request->file('logo')->store('sponsors_logo', 'public');
        }

        $sponsor->update($data);

        return redirect()->route('admin.sponsor.index')->with('success', 'Data Sponsor/Mitra berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $sponsor = Sponsor::findOrFail($id);
        
        if ($sponsor->logo) Storage::disk('public')->delete($sponsor->logo);
        $sponsor->delete();

        return redirect()->back()->with('success', 'Data Mitra beserta logonya telah dihapus permanen!');
    }
}