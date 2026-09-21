<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
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

        // LOGIKA SORTING
        $dataSponsor = $query->orderBy('urutan', 'asc')
                             ->orderByRaw("FIELD(tier_kelas, 'Utama (Besar)', 'Madya (Sedang)', 'Pratama (Kecil)') ASC")
                             ->paginate(15)->withQueryString();

        return view('admin.sponsor.index', compact('dataSponsor'));
    }

    public function updateUrutan(Request $request)
    {
        $urutans = $request->input('urutan'); 
        $offset = $request->input('offset', 0); 

        if (is_array($urutans)) {
            foreach ($urutans as $index => $id) {
                Sponsor::where('id', $id)->update(['urutan' => $offset + $index + 1]);
            }

            // ===========================================================================
            // [LOG AKTIVITAS] Mengubah Urutan Sponsor/Mitra
            // ===========================================================================
            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'modul'      => 'Sponsor & Mitra',
                'aksi'       => 'Update Urutan',
                'deskripsi'  => 'Admin ' . Auth::user()->name . ' memperbarui urutan tata letak daftar Sponsor/Mitra.',
                'ip_address' => $request->ip(),
            ]);
            // ===========================================================================
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

        $data = $request->except(['_token', 'logo']);

        // Set urutan otomatis ke paling bawah
        $maxUrutan = Sponsor::max('urutan');
        $data['urutan'] = $maxUrutan ? $maxUrutan + 1 : 1;

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('sponsors_logo', 'public');
        }

        $sponsor = Sponsor::create($data);

        // ===========================================================================
        // [LOG AKTIVITAS] Menambahkan Sponsor Baru
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Sponsor & Mitra',
            'aksi'       => 'Create',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menambahkan sponsor/mitra baru: "' . $sponsor->nama_mitra . ' (' . $sponsor->tier_kelas . ')".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

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

        $data = $request->except(['_token', '_method', 'logo', 'remove_logo']);

        if ($request->hasFile('logo')) {
            if ($sponsor->logo) Storage::disk('public')->delete($sponsor->logo);
            $data['logo'] = $request->file('logo')->store('sponsors_logo', 'public');
        } elseif ($request->input('remove_logo') == '1') {
            if ($sponsor->logo) Storage::disk('public')->delete($sponsor->logo);
            $data['logo'] = null;
        }

        $sponsor->update($data);

        // ===========================================================================
        // [LOG AKTIVITAS] Mengupdate Sponsor
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Sponsor & Mitra',
            'aksi'       => 'Update',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' memperbarui data sponsor/mitra "' . $sponsor->nama_mitra . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.sponsor.index')->with('success', 'Data Sponsor/Mitra berhasil diperbarui!');
    }

    public function destroy(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $sponsor = Sponsor::findOrFail($id);
        $namaMitra = $sponsor->nama_mitra; // Simpan nama untuk log
        
        if ($sponsor->logo) Storage::disk('public')->delete($sponsor->logo);
        $sponsor->delete();

        // ===========================================================================
        // [LOG AKTIVITAS] Menghapus Sponsor
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Sponsor & Mitra',
            'aksi'       => 'Delete',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menghapus permanen sponsor/mitra "' . $namaMitra . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Data Mitra beserta logonya telah dihapus permanen!');
    }
}