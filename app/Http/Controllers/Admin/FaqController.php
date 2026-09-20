<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Str;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('pertanyaan', 'like', '%' . $request->search . '%')
                  ->orWhere('jawaban', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori', $request->kategori);
        }

        $dataFaqs = $query->latest()->paginate(10)->withQueryString();

        return view('admin.faqs.index', compact('dataFaqs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban'    => 'required|string',
            'kategori'   => 'required|string|max:100',
            'is_active'  => 'required|boolean',
        ]);

        $faq = Faq::create($request->all());

        // ===========================================================================
        // [LOG AKTIVITAS] Menambahkan FAQ Baru
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'FAQ',
            'aksi'       => 'Create',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menambahkan FAQ baru: "' . Str::limit($faq->pertanyaan, 50) . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Data F&Q berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban'    => 'required|string',
            'kategori'   => 'required|string|max:100',
            'is_active'  => 'required|boolean',
        ]);

        $faq->update($request->all());

        // ===========================================================================
        // [LOG AKTIVITAS] Mengupdate FAQ
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'FAQ',
            'aksi'       => 'Update',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' memperbarui data FAQ: "' . Str::limit($faq->pertanyaan, 50) . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Data F&Q berhasil diperbarui!');
    }

    public function destroy(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $faq = Faq::findOrFail($id);
        $pertanyaan = $faq->pertanyaan; // Simpan teks pertanyaan untuk log
        $faq->delete();

        // ===========================================================================
        // [LOG AKTIVITAS] Menghapus FAQ
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'FAQ',
            'aksi'       => 'Delete',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menghapus permanen FAQ: "' . Str::limit($pertanyaan, 50) . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Data F&Q berhasil dihapus permanen!');
    }
}