<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EdisiKmdgi;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Facades\Storage;

class EdisiKmdgiController extends Controller
{
    public function index()
    {
        $dataEdisi = EdisiKmdgi::latest()->paginate(10);
        return view('admin.edisi.index', compact('dataEdisi'));
    }

    public function create()
    {
        return view('admin.edisi.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_edisi'      => 'required|string|max:255',
            'is_active'       => 'required|boolean',
            'lb_title'        => 'nullable|string|max:255',
            'lb_deskripsi'    => 'nullable|string',
            'lb_image'        => 'nullable|image|max:3072',
            'tema_title'      => 'nullable|string|max:255',
            'tema_deskripsi'  => 'nullable|string',
            'tema_logo'       => 'nullable|image|max:2048',
            'tema_image'      => 'nullable|image|max:3072',
        ]);

        $data = $request->all();

        // Jika diset Aktif, nonaktifkan edisi lain
        if ($data['is_active'] == 1) {
            EdisiKmdgi::query()->update(['is_active' => 0]);
        }

        // Upload Gambar
        if ($request->hasFile('lb_image')) $data['lb_image'] = $request->file('lb_image')->store('kmdgi_edisi', 'public');
        if ($request->hasFile('tema_logo')) $data['tema_logo'] = $request->file('tema_logo')->store('kmdgi_edisi', 'public');
        if ($request->hasFile('tema_image')) $data['tema_image'] = $request->file('tema_image')->store('kmdgi_edisi', 'public');

        $edisi = EdisiKmdgi::create($data);

        // ===========================================================================
        // [LOG AKTIVITAS] Menambahkan Edisi KMDGI Baru
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Edisi KMDGI',
            'aksi'       => 'Create',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' membuat Edisi KMDGI baru: "' . $edisi->nama_edisi . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.edisi.index')->with('success', 'Edisi baru berhasil dibuat!');
    }

    public function edit($id)
    {
        $edisi = EdisiKmdgi::findOrFail($id);
        return view('admin.edisi.form', compact('edisi'));
    }

    public function update(Request $request, $id)
    {
        $edisi = EdisiKmdgi::findOrFail($id);

        $data = $request->except(['_token', '_method']);

        // Logika Unik (Hanya 1 yang boleh aktif)
        if ($request->is_active == 1) {
            EdisiKmdgi::where('id', '!=', $id)->update(['is_active' => 0]);
        }

        // Upload / Hapus Gambar Latar Belakang
        if ($request->hasFile('lb_image')) {
            if ($edisi->lb_image) Storage::disk('public')->delete($edisi->lb_image);
            $data['lb_image'] = $request->file('lb_image')->store('kmdgi_edisi', 'public');
        } elseif ($request->remove_lb_image == '1') {
            if ($edisi->lb_image) Storage::disk('public')->delete($edisi->lb_image);
            $data['lb_image'] = null;
        }

        // Upload / Hapus Gambar Tema
        if ($request->hasFile('tema_logo')) {
            if ($edisi->tema_logo) Storage::disk('public')->delete($edisi->tema_logo);
            $data['tema_logo'] = $request->file('tema_logo')->store('kmdgi_edisi', 'public');
        } elseif ($request->remove_tema_logo == '1') {
            if ($edisi->tema_logo) Storage::disk('public')->delete($edisi->tema_logo);
            $data['tema_logo'] = null;
        }

        if ($request->hasFile('tema_image')) {
            if ($edisi->tema_image) Storage::disk('public')->delete($edisi->tema_image);
            $data['tema_image'] = $request->file('tema_image')->store('kmdgi_edisi', 'public');
        } elseif ($request->remove_tema_image == '1') {
            if ($edisi->tema_image) Storage::disk('public')->delete($edisi->tema_image);
            $data['tema_image'] = null;
        }

        $edisi->update($data);

        // ===========================================================================
        // [LOG AKTIVITAS] Mengupdate Edisi KMDGI
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Edisi KMDGI',
            'aksi'       => 'Update',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' memperbarui data Edisi KMDGI "' . $edisi->nama_edisi . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.edisi.index')->with('success', 'Edisi berhasil diperbarui!');
    }

    public function setActive(Request $request, $id) // <-- Tambahkan parameter Request
    {
        EdisiKmdgi::query()->update(['is_active' => 0]);
        $edisi = EdisiKmdgi::findOrFail($id);
        $edisi->update(['is_active' => 1]);

        // ===========================================================================
        // [LOG AKTIVITAS] Set Active Edisi (Sangat Krusial)
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Edisi KMDGI',
            'aksi'       => 'Set Active',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' MENGUBAH TEMA WEBSITE UTAMA ke Edisi "' . $edisi->nama_edisi . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Edisi yang dipilih berhasil diaktifkan ke Website!');
    }

    public function destroy(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $edisi = EdisiKmdgi::findOrFail($id);
        $namaEdisi = $edisi->nama_edisi; // Simpan nama edisi untuk log
        
        if ($edisi->lb_image) Storage::disk('public')->delete($edisi->lb_image);
        if ($edisi->tema_logo) Storage::disk('public')->delete($edisi->tema_logo);
        if ($edisi->tema_image) Storage::disk('public')->delete($edisi->tema_image);
        
        $edisi->delete();

        // ===========================================================================
        // [LOG AKTIVITAS] Menghapus Edisi KMDGI
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Edisi KMDGI',
            'aksi'       => 'Delete',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menghapus permanen Edisi KMDGI "' . $namaEdisi . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Data edisi KMDGI dihapus permanen!');
    }
}