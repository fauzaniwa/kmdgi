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
        $dataEdisi = EdisiKmdgi::orderBy('id', 'desc')->get();

        return view('admin.kampus.index', compact('dataKampus', 'dataEdisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_institusi' => 'required|string|max:255',
            'logo_institusi' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'lokasi_kota'    => 'required|string|max:100',
            'medsos_kampus'  => 'nullable|string|max:255',
            'ig_prodi'       => 'nullable|string|max:255',
            'link_wa'        => 'nullable|url|max:255',
            'riwayat_status' => 'nullable|array',
        ]);

        $riwayatStatus = $request->input('riwayat_status', []);

        // 1. Cari Edisi KMDGI yang sedang Aktif
        $edisiAktif = EdisiKmdgi::where('is_active', true)->first();
        
        // 2. Tentukan status keanggotaan berdasarkan riwayat
        $statusKeanggotaan = 'Tidak Terdaftar';
        
        if ($edisiAktif) {
            // Ambil dari input form untuk edisi aktif (jika ada)
            $statusKeanggotaan = $riwayatStatus[$edisiAktif->id] ?? 'Tidak Terdaftar';

            // 3. Jika di edisi aktif masih "Tidak Terdaftar", cari dari riwayat edisi sebelumnya
            if ($statusKeanggotaan === 'Tidak Terdaftar') {
                // Urutkan riwayat dari ID terbesar (terbaru) ke terkecil
                krsort($riwayatStatus);

                foreach ($riwayatStatus as $edisiId => $status) {
                    // Cari status di edisi masa lalu (ID < Edisi Aktif) yang bukan "Tidak Terdaftar"
                    if ($edisiId < $edisiAktif->id && $status !== 'Tidak Terdaftar' && !empty($status)) {
                        $statusKeanggotaan = $status;
                        break; // Berhenti pencarian karena sudah menemukan status terakhir yang valid
                    }
                }
            }
        }

        $logoPath = null;
        if ($request->hasFile('logo_institusi')) {
            $logoPath = $request->file('logo_institusi')->store('kampus_logo', 'public');
        }

        Kampus::create([
            'nama_institusi'    => $request->nama_institusi,
            'logo_institusi'    => $logoPath,
            'lokasi_kota'       => $request->lokasi_kota,
            'medsos_kampus'     => $request->medsos_kampus,
            'ig_prodi'          => $request->ig_prodi,
            'link_wa'           => $request->link_wa,
            'status_keanggotaan'=> $statusKeanggotaan, // Tersinkronisasi dengan histori terakhir
            'riwayat_status'    => $riwayatStatus,
        ]);

        return redirect()->route('admin.kampus.index')->with('success', 'Data kampus berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kampus = Kampus::findOrFail($id);

        $request->validate([
            'nama_institusi' => 'required|string|max:255',
            'logo_institusi' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'lokasi_kota'    => 'required|string|max:100',
            'medsos_kampus'  => 'nullable|string|max:255',
            'ig_prodi'       => 'nullable|string|max:255',
            'link_wa'        => 'nullable|url|max:255',
            'riwayat_status' => 'nullable|array',
        ]);

        $riwayatStatus = $request->input('riwayat_status', []);

        // 1. Cari Edisi KMDGI yang sedang Aktif
        $edisiAktif = EdisiKmdgi::where('is_active', true)->first();
        
        // 2. Tentukan status keanggotaan berdasarkan riwayat
        $statusKeanggotaan = 'Tidak Terdaftar';

        if ($edisiAktif) {
            // Ambil dari input form untuk edisi aktif (jika ada)
            $statusKeanggotaan = $riwayatStatus[$edisiAktif->id] ?? 'Tidak Terdaftar';

            // 3. Jika di edisi aktif masih "Tidak Terdaftar", cari dari riwayat edisi sebelumnya
            if ($statusKeanggotaan === 'Tidak Terdaftar') {
                // Urutkan riwayat dari ID terbesar (terbaru) ke terkecil
                krsort($riwayatStatus);

                foreach ($riwayatStatus as $edisiId => $status) {
                    // Cari status di edisi masa lalu (ID < Edisi Aktif) yang bukan "Tidak Terdaftar"
                    if ($edisiId < $edisiAktif->id && $status !== 'Tidak Terdaftar' && !empty($status)) {
                        $statusKeanggotaan = $status;
                        break; // Berhenti pencarian karena sudah menemukan status terakhir yang valid
                    }
                }
            }
        }

        if ($request->hasFile('logo_institusi')) {
            if ($kampus->logo_institusi && Storage::disk('public')->exists($kampus->logo_institusi)) {
                Storage::disk('public')->delete($kampus->logo_institusi);
            }
            $kampus->logo_institusi = $request->file('logo_institusi')->store('kampus_logo', 'public');
        }

        $kampus->update([
            'nama_institusi'    => $request->nama_institusi,
            'lokasi_kota'       => $request->lokasi_kota,
            'medsos_kampus'     => $request->medsos_kampus,
            'ig_prodi'          => $request->ig_prodi,
            'link_wa'           => $request->link_wa,
            'status_keanggotaan'=> $statusKeanggotaan, // Tersinkronisasi dengan histori terakhir
            'riwayat_status'    => $riwayatStatus,
        ]);

        return redirect()->route('admin.kampus.index')->with('success', 'Data kampus berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kampus = Kampus::findOrFail($id);

        if ($kampus->logo_institusi && Storage::disk('public')->exists($kampus->logo_institusi)) {
            Storage::disk('public')->delete($kampus->logo_institusi);
        }

        $kampus->delete();

        return redirect()->route('admin.kampus.index')->with('success', 'Data kampus berhasil dihapus.');
    }
}