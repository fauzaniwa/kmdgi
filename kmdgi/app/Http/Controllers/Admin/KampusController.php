<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kampus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KampusController extends Controller
{
    // Mengambil data terfilter untuk disajikan ke index view
    public function index(Request $request)
    {
        $query = Kampus::query();

        // Fitur 1: Pencarian berdasarkan nama kampus atau kota lokasi
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_institusi', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi_kota', 'like', '%' . $request->search . '%');
            });
        }

        // Fitur 2: Filter berdasarkan Opsi Cluster Keanggotaan KMDGI
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status_keanggotaan', $request->status);
        }

        // Mengurutkan dari yang terbaru dan membagi halaman data (Pagination)
        $dataKampus = $query->latest()->paginate(10)->withQueryString();

        return view('admin.kampus.index', compact('dataKampus'));
    }

    // Memproses pembuatan data baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_institusi'     => 'required|string|max:255',
            'logo_institusi'     => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'lokasi_kota'        => 'required|string|max:100',
            'status_keanggotaan' => 'required|in:Anggota,Peninjau 1,Peninjau 2',
        ]);

        $data = $request->all();

        // Upload logo jika melampirkan berkas file
        if ($request->hasFile('logo_institusi')) {
            $data['logo_institusi'] = $request->file('logo_institusi')->store('logos', 'public');
        }

        Kampus::create($data);

        return redirect()->back()->with('success', 'Data kampus berhasil ditambahkan!');
    }

    // Memproses pembaharuan data yang sudah ada
    public function update(Request $request, $id)
    {
        $kampus = Kampus::findOrFail($id);

        $request->validate([
            'nama_institusi'     => 'required|string|max:255',
            'logo_institusi'     => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'lokasi_kota'        => 'required|string|max:100',
            'status_keanggotaan' => 'required|in:Anggota,Peninjau 1,Peninjau 2',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo_institusi')) {
            // Hapus berkas file logo lama di storage server jika ada
            if ($kampus->logo_institusi) {
                Storage::disk('public')->delete($kampus->logo_institusi);
            }
            $data['logo_institusi'] = $request->file('logo_institusi')->store('logos', 'public');
        }

        $kampus->update($data);

        return redirect()->back()->with('success', 'Data kampus berhasil diperbarui!');
    }

    // Memproses penghapusan data secara aman melalui KMDGI Global Modal
    public function destroy($id)
    {
        $kampus = Kampus::findOrFail($id);

        // Hapus file logo fisik dari server storage
        if ($kampus->logo_institusi) {
            Storage::disk('public')->delete($kampus->logo_institusi);
        }

        $kampus->delete();

        return redirect()->back()->with('success', 'Data kampus beserta seluruh berkas logo terkait telah dihapus permanen!');
    }
}