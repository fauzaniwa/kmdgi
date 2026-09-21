<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SejarahKmdgi;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Facades\Storage;

class SejarahKmdgiController extends Controller
{
    public function index(Request $request)
    {
        $query = SejarahKmdgi::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('tahun', 'like', '%' . $request->search . '%');
        }

        // Diurutkan berdasarkan Tahun agar berurutan secara histori
        $dataSejarah = $query->orderBy('tahun', 'asc')->paginate(15)->withQueryString();

        return view('admin.sejarah.index', compact('dataSejarah'));
    }

    public function create()
    {
        return view('admin.sejarah.form');
    }

    public function store(Request $request)
    {
        // Karena ini array, kita validasi isinya jika ada
        $sejarahData = $request->input('sejarah');
        
        $insertedCount = 0;

        foreach ($sejarahData as $index => $item) {
            // Lewati blok form yang kosong (Jika admin hanya mengisi 2 dari 5 form)
            if (empty($item['tahun']) || empty($item['title'])) {
                continue;
            }

            $newData = [
                'tahun' => $item['tahun'],
                'title' => $item['title'],
                'description' => $item['description'] ?? '',
            ];

            // Proses 5 Gambar untuk tiap blok
            for ($i = 1; $i <= 5; $i++) {
                $fileKey = "image_{$i}";
                // Dalam Laravel, array file diakses menggunakan notasi dot
                if ($request->hasFile("sejarah.{$index}.{$fileKey}")) {
                    $file = $request->file("sejarah.{$index}.{$fileKey}");
                    $newData[$fileKey] = $file->store('sejarah_kmdgi', 'public');
                }
            }

            SejarahKmdgi::create($newData);
            $insertedCount++;
        }

        if ($insertedCount == 0) {
            return redirect()->back()->withErrors(['msg' => 'Minimal satu data sejarah harus diisi.']);
        }

        // ===========================================================================
        // [LOG AKTIVITAS] Menambahkan Data Sejarah Baru
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Sejarah KMDGI',
            'aksi'       => 'Create',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' berhasil menambahkan ' . $insertedCount . ' data lini masa sejarah KMDGI baru.',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.sejarah.index')->with('success', "Berhasil menambahkan {$insertedCount} data sejarah KMDGI sekaligus!");
    }

    public function edit($id)
    {
        $sejarah = SejarahKmdgi::findOrFail($id);
        return view('admin.sejarah.form', compact('sejarah'));
    }

    public function update(Request $request, $id)
    {
        $sejarah = SejarahKmdgi::findOrFail($id);

        $request->validate([
            'tahun' => 'required|string',
            'title' => 'required|string',
        ]);

        $data = $request->only(['tahun', 'title', 'description']);

        for ($i = 1; $i <= 5; $i++) {
            $field = 'image_' . $i;
            $removeField = 'remove_image_' . $i;

            if ($request->hasFile($field)) {
                if ($sejarah->$field) Storage::disk('public')->delete($sejarah->$field);
                $data[$field] = $request->file($field)->store('sejarah_kmdgi', 'public');
            } elseif ($request->input($removeField) == '1') {
                if ($sejarah->$field) Storage::disk('public')->delete($sejarah->$field);
                $data[$field] = null;
            }
        }

        $sejarah->update($data);

        // ===========================================================================
        // [LOG AKTIVITAS] Mengupdate Sejarah KMDGI
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Sejarah KMDGI',
            'aksi'       => 'Update',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' memperbarui data sejarah tahun ' . $sejarah->tahun . ' ("' . $sejarah->title . '").',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.sejarah.index')->with('success', 'Data sejarah berhasil diperbarui!');
    }

    public function destroy(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $sejarah = SejarahKmdgi::findOrFail($id);
        $tahunSejarah = $sejarah->tahun;
        $judulSejarah = $sejarah->title;
        
        // Hapus semua gambar terkait
        for ($i = 1; $i <= 5; $i++) {
            $field = 'image_' . $i;
            if ($sejarah->$field) Storage::disk('public')->delete($sejarah->$field);
        }

        $sejarah->delete();

        // ===========================================================================
        // [LOG AKTIVITAS] Menghapus Sejarah KMDGI
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Sejarah KMDGI',
            'aksi'       => 'Delete',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menghapus permanen data sejarah tahun ' . $tahunSejarah . ' ("' . $judulSejarah . '").',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Data sejarah telah dihapus permanen!');
    }
}