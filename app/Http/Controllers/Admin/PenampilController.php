<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penampil;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Facades\Storage;

class PenampilController extends Controller
{
    public function index(Request $request)
    {
        $query = Penampil::query();

        if ($request->filled('search')) {
            $query->where('nama_penampil', 'like', '%' . $request->search . '%')
                ->orWhere('lokasi_tampil', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori_penampil') && $request->kategori_penampil !== 'all') {
            $query->where('kategori_penampil', $request->kategori_penampil);
        }

        // LOGIKA SORTING: Prioritaskan Urutan Custom (1, 2, 3), 
        // Jika masih default (9999), urutkan berdasarkan jadwal terdekat
        $dataPenampil = $query->orderBy('urutan', 'asc')
            ->orderBy('tanggal_tampil', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->paginate(10)->withQueryString();

        return view('admin.penampil.index', compact('dataPenampil'));
    }

    public function updateUrutan(Request $request)
    {
        $urutans = $request->input('urutan'); // Array dari ID penampil yang digeser
        $offset = $request->input('offset', 0); // Menangani paginasi (halaman 1 vs halaman 2)

        foreach ($urutans as $index => $id) {
            Penampil::where('id', $id)->update([
                'urutan' => $offset + $index + 1
            ]);
        }

        // ===========================================================================
        // [LOG AKTIVITAS] Mengubah Urutan Penampil
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Manajemen Penampil',
            'aksi'       => 'Update Urutan',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' memperbarui urutan daftar penampil/artis.',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return response()->json(['success' => true]);
    }

    public function create()
    {
        return view('admin.penampil.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_penampil'      => 'required|string|max:255',
            'kategori_penampil'  => 'required|string|max:255',
            'logo_penampil'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cover_penampil'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'tanggal_tampil'     => 'required|date',
            'jam_mulai'          => 'required',
            'jam_selesai'        => 'required',
            'lokasi_tampil'      => 'required|string|max:255',
            'deskripsi_penampil' => 'required|string',
            'medsos_penampil'    => 'nullable|string|max:255',
            'kategori_penonton'  => 'required|in:Umum,Delegasi,Semua',
            'tipe_pendaftaran'   => 'required|in:Gratis,Berbayar',
            'harga_tiket'        => 'nullable|numeric|min:0',
            'link_pendaftaran'   => 'nullable|url|max:255',
            'asal_penampil'      => 'nullable|string|max:255',
            'tahun_dibentuk'     => 'nullable|string|max:50',
            'genre_musik'        => 'nullable|string|max:255',
            'embed_spotify'      => 'nullable|string',
            'is_active'          => 'required|boolean',
        ]);

        $data = $request->all();

        if ($request->tipe_pendaftaran === 'Gratis') {
            $data['harga_tiket'] = null;
        }

        if ($request->hasFile('logo_penampil')) {
            $data['logo_penampil'] = $request->file('logo_penampil')->store('penampil_logos', 'public');
        }
        if ($request->hasFile('cover_penampil')) {
            $data['cover_penampil'] = $request->file('cover_penampil')->store('penampil_covers', 'public');
        }

        $penampil = Penampil::create($data);

        // ===========================================================================
        // [LOG AKTIVITAS] Menambahkan Penampil Baru
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Manajemen Penampil',
            'aksi'       => 'Create',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menambahkan data penampil baru: "' . $penampil->nama_penampil . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.penampil.index')->with('success', 'Data Penampil berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $penampil = Penampil::findOrFail($id);
        return view('admin.penampil.form', compact('penampil'));
    }

    public function update(Request $request, $id)
    {
        $penampil = Penampil::findOrFail($id);

        $request->validate([
            'nama_penampil'      => 'required|string|max:255',
            'kategori_penampil'  => 'required|string|max:255',
            'logo_penampil'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cover_penampil'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'tanggal_tampil'     => 'required|date',
            'jam_mulai'          => 'required',
            'jam_selesai'        => 'required',
            'lokasi_tampil'      => 'required|string|max:255',
            'deskripsi_penampil' => 'required|string',
            'medsos_penampil'    => 'nullable|string|max:255',
            'kategori_penonton'  => 'required|in:Umum,Delegasi,Semua',
            'tipe_pendaftaran'   => 'required|in:Gratis,Berbayar',
            'harga_tiket'        => 'nullable|numeric|min:0',
            'link_pendaftaran'   => 'nullable|url|max:255',
            'asal_penampil'      => 'nullable|string|max:255',
            'tahun_dibentuk'     => 'nullable|string|max:50',
            'genre_musik'        => 'nullable|string|max:255',
            'embed_spotify'      => 'nullable|string',
            'is_active'          => 'required|boolean',
        ]);

        $data = $request->all();

        if ($request->tipe_pendaftaran === 'Gratis') {
            $data['harga_tiket'] = null;
        }

        // Gambar Logo
        if ($request->hasFile('logo_penampil')) {
            if ($penampil->logo_penampil) Storage::disk('public')->delete($penampil->logo_penampil);
            $data['logo_penampil'] = $request->file('logo_penampil')->store('penampil_logos', 'public');
        }

        // Gambar Cover
        if ($request->hasFile('cover_penampil')) {
            if ($penampil->cover_penampil) Storage::disk('public')->delete($penampil->cover_penampil);
            $data['cover_penampil'] = $request->file('cover_penampil')->store('penampil_covers', 'public');
        }

        $penampil->update($data);

        // ===========================================================================
        // [LOG AKTIVITAS] Mengupdate Penampil
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Manajemen Penampil',
            'aksi'       => 'Update',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' memperbarui data penampil "' . $penampil->nama_penampil . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.penampil.index')->with('success', 'Data Penampil berhasil diperbarui!');
    }

    public function destroy(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $penampil = Penampil::findOrFail($id);
        $namaPenampil = $penampil->nama_penampil; // Simpan nama untuk log

        if ($penampil->logo_penampil) Storage::disk('public')->delete($penampil->logo_penampil);
        if ($penampil->cover_penampil) Storage::disk('public')->delete($penampil->cover_penampil);

        $penampil->delete();

        // ===========================================================================
        // [LOG AKTIVITAS] Menghapus Penampil
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Manajemen Penampil',
            'aksi'       => 'Delete',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menghapus permanen data penampil "' . $namaPenampil . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Data Penampil beserta gambarnya telah dihapus permanen!');
    }
}