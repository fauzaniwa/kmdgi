<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventKmdgi;
use App\Models\EdisiKmdgi;
use App\Models\Kolaborator;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EventKmdgiController extends Controller
{
    public function index(Request $request)
    {
        $semuaEdisi = EdisiKmdgi::orderBy('id', 'desc')->get();
        $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();
        $edisiId = $request->input('edisi_id', $edisiAktif ? $edisiAktif->id : ($semuaEdisi->first()->id ?? null));

        $dataEvent = EventKmdgi::where('edisi_kmdgi_id', $edisiId)->latest()->paginate(10)->withQueryString();

        return view('admin.event.index', compact('dataEvent', 'semuaEdisi', 'edisiId'));
    }

    public function create(Request $request)
    {
        $edisiId = $request->input('edisi_id');
        $edisi = EdisiKmdgi::findOrFail($edisiId);
        $semuaKolaborator = Kolaborator::where('is_active', 1)->orderBy('urutan')->get();

        return view('admin.event.form', compact('edisiId', 'edisi', 'semuaKolaborator'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'edisi_kmdgi_id' => 'required|exists:edisi_kmdgis,id',
            'judul'          => 'required|string|max:255',
            'slug'           => 'required|string|unique:event_kmdgis,slug',
            'poster'         => 'nullable|image|max:3072',
            'harga_tiket'    => 'required|numeric',
            'kuota'          => 'required|integer|min:0',
        ]);

        $data = $request->except(['_token', 'poster']);

        // Upload Poster
        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('event_poster', 'public');
        }

        if (!$request->has('kategori_peserta')) $data['kategori_peserta'] = [];
        if (!$request->has('kolaborator_ids')) $data['kolaborator_ids'] = [];

        $event = EventKmdgi::create($data);

        // ===========================================================================
        // [LOG AKTIVITAS] Membuat Event Baru
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Katalog Event',
            'aksi'       => 'Create',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' membuat event baru berjudul "' . $event->judul . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.event.index', ['edisi_id' => $request->edisi_kmdgi_id])->with('success', 'Event berhasil dibuat!');
    }

    public function edit($id)
    {
        $event = EventKmdgi::findOrFail($id);
        $edisiId = $event->edisi_kmdgi_id;
        $edisi = EdisiKmdgi::findOrFail($edisiId);
        $semuaKolaborator = Kolaborator::where('is_active', 1)->orderBy('urutan')->get();

        return view('admin.event.form', compact('event', 'edisiId', 'edisi', 'semuaKolaborator'));
    }

    public function update(Request $request, $id)
    {
        $event = EventKmdgi::findOrFail($id);

        $request->validate([
            'judul'       => 'required|string|max:255',
            'slug'        => 'required|string|unique:event_kmdgis,slug,' . $id,
            'poster'      => 'nullable|image|max:3072',
            'harga_tiket' => 'required|numeric',
            'kuota'       => 'required|integer|min:0',
        ]);

        $data = $request->except(['_token', '_method', 'poster', 'remove_poster']);

        // Update Poster
        if ($request->hasFile('poster')) {
            if ($event->poster) Storage::disk('public')->delete($event->poster);
            $data['poster'] = $request->file('poster')->store('event_poster', 'public');
        } elseif ($request->remove_poster == '1') {
            if ($event->poster) Storage::disk('public')->delete($event->poster);
            $data['poster'] = null;
        }

        if (!$request->has('kategori_peserta')) $data['kategori_peserta'] = [];
        if (!$request->has('kolaborator_ids')) $data['kolaborator_ids'] = [];

        $event->update($data);

        // ===========================================================================
        // [LOG AKTIVITAS] Mengupdate Event
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Katalog Event',
            'aksi'       => 'Update',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' memperbarui data event "' . $event->judul . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->route('admin.event.index', ['edisi_id' => $event->edisi_kmdgi_id])->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $event = EventKmdgi::findOrFail($id);
        $judulEvent = $event->judul; // Simpan judul untuk log
        
        if ($event->poster) Storage::disk('public')->delete($event->poster);
        $event->delete();

        // ===========================================================================
        // [LOG AKTIVITAS] Menghapus Event
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Katalog Event',
            'aksi'       => 'Delete',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menghapus permanen event "' . $judulEvent . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Event dihapus secara permanen!');
    }
}