<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekeningPembayaran;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Facades\Storage;

class RekeningPembayaranController extends Controller
{
    public function index()
    {
        $rekenings = RekeningPembayaran::latest()->get();
        return view('admin.rekening.index', compact('rekenings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank'      => 'required|string|max:255',
            'atas_nama'      => 'required|string|max:255',
            'nomor_rekening' => 'nullable|string|max:255',
            'logo_bank'      => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'qr_code'        => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
            'is_active'      => 'required|boolean',
        ]);

        $data = $request->except(['logo_bank', 'qr_code']);

        if ($request->hasFile('logo_bank')) {
            $data['logo_bank'] = $request->file('logo_bank')->store('rekening_logo', 'public');
        }

        if ($request->hasFile('qr_code')) {
            $data['qr_code'] = $request->file('qr_code')->store('rekening_qris', 'public');
        }

        $rekening = RekeningPembayaran::create($data);

        // ===========================================================================
        // [LOG AKTIVITAS] Menambahkan Rekening Pembayaran Baru
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Rekening Pembayaran',
            'aksi'       => 'Create',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menambahkan tujuan pembayaran baru: "' . $rekening->nama_bank . ' (' . $rekening->nomor_rekening . ')".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.rekening.index')->with('success', 'Tujuan pembayaran berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rekening = RekeningPembayaran::findOrFail($id);

        $request->validate([
            'nama_bank'      => 'required|string|max:255',
            'atas_nama'      => 'required|string|max:255',
            'nomor_rekening' => 'nullable|string|max:255',
            'logo_bank'      => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'qr_code'        => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
            'is_active'      => 'required|boolean',
        ]);

        $data = $request->except(['logo_bank', 'qr_code']);

        if ($request->hasFile('logo_bank')) {
            if ($rekening->logo_bank) Storage::disk('public')->delete($rekening->logo_bank);
            $data['logo_bank'] = $request->file('logo_bank')->store('rekening_logo', 'public');
        }

        if ($request->hasFile('qr_code')) {
            if ($rekening->qr_code) Storage::disk('public')->delete($rekening->qr_code);
            $data['qr_code'] = $request->file('qr_code')->store('rekening_qris', 'public');
        }

        $rekening->update($data);

        // ===========================================================================
        // [LOG AKTIVITAS] Mengupdate Rekening Pembayaran
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Rekening Pembayaran',
            'aksi'       => 'Update',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' memperbarui data tujuan pembayaran: "' . $rekening->nama_bank . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.rekening.index')->with('success', 'Tujuan pembayaran berhasil diperbarui!');
    }

    public function destroy(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $rekening = RekeningPembayaran::findOrFail($id);
        $namaBank = $rekening->nama_bank; // Simpan nama bank untuk log
        
        if ($rekening->logo_bank) Storage::disk('public')->delete($rekening->logo_bank);
        if ($rekening->qr_code) Storage::disk('public')->delete($rekening->qr_code);
        
        $rekening->delete();

        // ===========================================================================
        // [LOG AKTIVITAS] Menghapus Rekening Pembayaran
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Rekening Pembayaran',
            'aksi'       => 'Delete',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menghapus permanen tujuan pembayaran: "' . $namaBank . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.rekening.index')->with('success', 'Tujuan pembayaran berhasil dihapus.');
    }
}