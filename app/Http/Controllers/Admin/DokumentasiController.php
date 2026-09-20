<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Dokumentasi::query();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori_kegiatan', $request->kategori);
        }

        $dataDokumentasi = $query->latest('tanggal_kegiatan')->paginate(12)->withQueryString();

        return view('admin.dokumentasi.index', compact('dataDokumentasi'));
    }

    public function create()
    {
        return view('admin.dokumentasi.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'             => 'required|string|max:255',
            'kategori_kegiatan' => 'required|string|max:255',
            'tanggal_kegiatan'  => 'required|date',
            'tipe_media'        => 'required|in:Foto,Video Upload,Video YouTube',
            'file_media'        => 'nullable|file|mimes:jpeg,png,jpg,webp,mp4,mov|max:20480', // Max 20MB
            'video_url'         => 'nullable|url|max:255',
            'deskripsi'         => 'nullable|string',
            'is_active'         => 'required|boolean',
        ]);

        $data = $request->all();

        // Kosongkan field yang tidak relevan sesuai tipe
        if ($request->tipe_media === 'Video YouTube') {
            $data['file_path'] = null;
        } else {
            $data['video_url'] = null;
            if ($request->hasFile('file_media')) {
                $folder = ($request->tipe_media === 'Foto') ? 'dokumentasi/foto' : 'dokumentasi/video';
                $data['file_path'] = $request->file('file_media')->store($folder, 'public');
            }
        }

        $dokumentasi = Dokumentasi::create($data);

        // ===========================================================================
        // [LOG AKTIVITAS] Menambah Dokumentasi
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Dokumentasi',
            'aksi'       => 'Create',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menambahkan dokumentasi baru berjudul "' . $dokumentasi->judul . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.dokumentasi.index')->with('success', 'Dokumentasi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $dokumentasi = Dokumentasi::findOrFail($id);
        return view('admin.dokumentasi.form', compact('dokumentasi'));
    }

    public function update(Request $request, $id)
    {
        $dokumentasi = Dokumentasi::findOrFail($id);

        $request->validate([
            'judul'             => 'required|string|max:255',
            'kategori_kegiatan' => 'required|string|max:255',
            'tanggal_kegiatan'  => 'required|date',
            'tipe_media'        => 'required|in:Foto,Video Upload,Video YouTube',
            'file_media'        => 'nullable|file|mimes:jpeg,png,jpg,webp,mp4,mov|max:20480',
            'video_url'         => 'nullable|url|max:255',
            'deskripsi'         => 'nullable|string',
            'is_active'         => 'required|boolean',
        ]);

        $data = $request->all();

        if ($request->tipe_media === 'Video YouTube') {
            if ($dokumentasi->file_path) Storage::disk('public')->delete($dokumentasi->file_path);
            $data['file_path'] = null;
        } else {
            $data['video_url'] = null;
            if ($request->hasFile('file_media')) {
                if ($dokumentasi->file_path) Storage::disk('public')->delete($dokumentasi->file_path);
                $folder = ($request->tipe_media === 'Foto') ? 'dokumentasi/foto' : 'dokumentasi/video';
                $data['file_path'] = $request->file('file_media')->store($folder, 'public');
            }
        }

        $dokumentasi->update($data);

        // ===========================================================================
        // [LOG AKTIVITAS] Mengupdate Dokumentasi
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Dokumentasi',
            'aksi'       => 'Update',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' memperbarui data dokumentasi "' . $dokumentasi->judul . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.dokumentasi.index')->with('success', 'Dokumentasi berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $dokumentasi = Dokumentasi::findOrFail($id);
        $judulDokumentasi = $dokumentasi->judul; // Simpan judul sebelum dihapus untuk log
        
        if ($dokumentasi->file_path) {
            Storage::disk('public')->delete($dokumentasi->file_path);
        }
        $dokumentasi->delete();

        // ===========================================================================
        // [LOG AKTIVITAS] Menghapus Dokumentasi
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Dokumentasi',
            'aksi'       => 'Delete',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menghapus permanen dokumentasi "' . $judulDokumentasi . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Dokumentasi telah dihapus permanen!');
    }
}