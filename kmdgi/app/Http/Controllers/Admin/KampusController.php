<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kampus;
use App\Models\EdisiKmdgi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KampusController extends Controller
{
    public function index(Request $request)
    {
        $query = Kampus::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_institusi', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi_kota', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status_keanggotaan', $request->status);
        }

        $dataKampus = $query->latest()->paginate(10)->withQueryString();
        
        // Mengambil seluruh data Edisi KMDGI untuk ditampilkan di Modal Form
        $dataEdisi = EdisiKmdgi::orderBy('id', 'desc')->get();

        return view('admin.kampus.index', compact('dataKampus', 'dataEdisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_institusi' => 'required|string|max:255',
            'logo_institusi' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'lokasi_kota'    => 'required|string|max:100',
            'link_wa'        => 'nullable|url|max:255',
            'riwayat_status' => 'nullable|array', // Validasi array status dari form
        ]);

        $data = $request->all();

        // 1. Tentukan status_keanggotaan (Cache) berdasarkan Edisi yang Aktif saat ini
        $activeEdisi = EdisiKmdgi::where('is_active', 1)->first();
        if ($activeEdisi && isset($data['riwayat_status'][$activeEdisi->id])) {
            $data['status_keanggotaan'] = $data['riwayat_status'][$activeEdisi->id];
        } else {
            $data['status_keanggotaan'] = 'Tidak Terdaftar';
        }

        if ($request->hasFile('logo_institusi')) {
            $data['logo_institusi'] = $request->file('logo_institusi')->store('logos', 'public');
        }

        Kampus::create($data);

        return redirect()->back()->with('success', 'Data kampus berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $kampus = Kampus::findOrFail($id);

        $request->validate([
            'nama_institusi' => 'required|string|max:255',
            'logo_institusi' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'lokasi_kota'    => 'required|string|max:100',
            'link_wa'        => 'nullable|url|max:255',
            'riwayat_status' => 'nullable|array',
        ]);

        $data = $request->all();

        // Sama seperti create, perbarui status utama berdasarkan Edisi Aktif
        $activeEdisi = EdisiKmdgi::where('is_active', 1)->first();
        if ($activeEdisi && isset($data['riwayat_status'][$activeEdisi->id])) {
            $data['status_keanggotaan'] = $data['riwayat_status'][$activeEdisi->id];
        } else {
            $data['status_keanggotaan'] = 'Tidak Terdaftar';
        }

        if ($request->hasFile('logo_institusi')) {
            if ($kampus->logo_institusi) Storage::disk('public')->delete($kampus->logo_institusi);
            $data['logo_institusi'] = $request->file('logo_institusi')->store('logos', 'public');
        }

        $kampus->update($data);

        return redirect()->back()->with('success', 'Data kampus berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kampus = Kampus::findOrFail($id);
        if ($kampus->logo_institusi) Storage::disk('public')->delete($kampus->logo_institusi);
        $kampus->delete();

        return redirect()->back()->with('success', 'Data kampus telah dihapus permanen!');
    }
}