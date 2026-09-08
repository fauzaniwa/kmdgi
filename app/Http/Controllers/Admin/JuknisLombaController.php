<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JuknisLomba;
use App\Models\EdisiKmdgi;
use App\Models\Kolaborator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class JuknisLombaController extends Controller
{
    public function index(Request $request)
    {
        $semuaEdisi = EdisiKmdgi::orderBy('id', 'desc')->get();
        $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();
        $edisiId = $request->input('edisi_id', $edisiAktif ? $edisiAktif->id : ($semuaEdisi->first()->id ?? null));

        $dataLomba = JuknisLomba::where('edisi_kmdgi_id', $edisiId)->latest()->paginate(10)->withQueryString();

        return view('admin.juknis.index', compact('dataLomba', 'semuaEdisi', 'edisiId'));
    }

    public function create(Request $request)
    {
        $edisiId = $request->input('edisi_id');
        $edisi = EdisiKmdgi::findOrFail($edisiId);
        
        $semuaKolaborator = Kolaborator::where('is_active', 1)->orderBy('urutan')->get();

        return view('admin.juknis.form', compact('edisiId', 'edisi', 'semuaKolaborator'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'edisi_kmdgi_id' => 'required|exists:edisi_kmdgis,id',
            'judul' => 'required|string|max:255',
            'slug' => 'required|string|unique:juknis_lombas,slug',
            'poster' => 'nullable|image|max:3072',
            'file_guidebook' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'file_panduan_online' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'biaya_pendaftaran' => 'required|numeric',
        ]);

        $data = $request->except(['_token', 'poster', 'hadiah', 'file_guidebook', 'file_panduan_online', 'juri_ids']);

        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('lomba_poster', 'public');
        }
        if ($request->hasFile('file_guidebook')) {
            $data['file_guidebook'] = $request->file('file_guidebook')->store('lomba_dokumen', 'public');
        }
        if ($request->hasFile('file_panduan_online')) {
            $data['file_panduan_online'] = $request->file('file_panduan_online')->store('lomba_dokumen', 'public');
        }

        $hadiahData = [];
        if ($request->has('hadiah')) {
            foreach ($request->hadiah as $i => $h) {
                $iconPath = null;
                if ($request->hasFile("hadiah.{$i}.icon")) {
                    $iconPath = $request->file("hadiah.{$i}.icon")->store('lomba_hadiah', 'public');
                }
                $hadiahData[] = [
                    'peringkat' => $h['peringkat'] ?? '',
                    'keterangan' => $h['keterangan'] ?? '',
                    'isi' => $h['isi'] ?? '',
                    'icon' => $iconPath
                ];
            }
        }
        $data['hadiah'] = $hadiahData;

        // Memastikan timeline beserta statusnya terambil utuh
        $data['timeline'] = $request->input('timeline', []);
        
        $data['juri'] = $request->input('juri_ids', []);

        JuknisLomba::create($data);
        return redirect()->route('admin.juknis.index', ['edisi_id' => $request->edisi_kmdgi_id])->with('success', 'Juknis Perlombaan berhasil dibuat!');
    }

    public function edit($id)
    {
        $juknis = JuknisLomba::findOrFail($id);
        $edisiId = $juknis->edisi_kmdgi_id;
        $edisi = EdisiKmdgi::findOrFail($edisiId);
        
        $semuaKolaborator = Kolaborator::where('is_active', 1)->orderBy('urutan')->get();

        return view('admin.juknis.form', compact('juknis', 'edisiId', 'edisi', 'semuaKolaborator'));
    }

    public function update(Request $request, $id)
    {
        $juknis = JuknisLomba::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'slug' => 'required|string|unique:juknis_lombas,slug,' . $id,
            'poster' => 'nullable|image|max:3072',
            'file_guidebook' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'file_panduan_online' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'biaya_pendaftaran' => 'required|numeric',
        ]);

        $data = $request->except(['_token', '_method', 'poster', 'hadiah', 'remove_poster', 'file_guidebook', 'file_panduan_online', 'remove_file_guidebook', 'remove_file_panduan_online', 'juri_ids', 'timeline']);

        if ($request->hasFile('poster')) {
            if ($juknis->poster) Storage::disk('public')->delete($juknis->poster);
            $data['poster'] = $request->file('poster')->store('lomba_poster', 'public');
        } elseif ($request->remove_poster == '1') {
            if ($juknis->poster) Storage::disk('public')->delete($juknis->poster);
            $data['poster'] = null;
        }

        if ($request->hasFile('file_guidebook')) {
            if ($juknis->file_guidebook) Storage::disk('public')->delete($juknis->file_guidebook);
            $data['file_guidebook'] = $request->file('file_guidebook')->store('lomba_dokumen', 'public');
        } elseif ($request->remove_file_guidebook == '1') {
            if ($juknis->file_guidebook) Storage::disk('public')->delete($juknis->file_guidebook);
            $data['file_guidebook'] = null;
        }

        if ($request->hasFile('file_panduan_online')) {
            if ($juknis->file_panduan_online) Storage::disk('public')->delete($juknis->file_panduan_online);
            $data['file_panduan_online'] = $request->file('file_panduan_online')->store('lomba_dokumen', 'public');
        } elseif ($request->remove_file_panduan_online == '1') {
            if ($juknis->file_panduan_online) Storage::disk('public')->delete($juknis->file_panduan_online);
            $data['file_panduan_online'] = null;
        }

        $hadiahData = [];
        if ($request->has('hadiah')) {
            foreach ($request->hadiah as $i => $h) {
                $iconPath = $h['old_icon'] ?? null;
                if ($request->hasFile("hadiah.{$i}.icon")) {
                    if ($iconPath) Storage::disk('public')->delete($iconPath);
                    $iconPath = $request->file("hadiah.{$i}.icon")->store('lomba_hadiah', 'public');
                }
                $hadiahData[] = [
                    'peringkat' => $h['peringkat'] ?? '',
                    'keterangan' => $h['keterangan'] ?? '',
                    'isi' => $h['isi'] ?? '',
                    'icon' => $iconPath
                ];
            }
        }
        $data['hadiah'] = $hadiahData;

        // Memastikan timeline beserta statusnya terambil utuh dan tersimpan
        $data['timeline'] = $request->input('timeline', []);
        
        $data['kategori_peserta'] = $request->input('kategori_peserta', []);
        $data['juri'] = $request->input('juri_ids', []);

        $juknis->update($data);
        return redirect()->route('admin.juknis.index', ['edisi_id' => $juknis->edisi_kmdgi_id])->with('success', 'Juknis Perlombaan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $juknis = JuknisLomba::findOrFail($id);
        
        if ($juknis->poster) Storage::disk('public')->delete($juknis->poster);
        if ($juknis->file_guidebook) Storage::disk('public')->delete($juknis->file_guidebook);
        if ($juknis->file_panduan_online) Storage::disk('public')->delete($juknis->file_panduan_online);

        if (is_array($juknis->hadiah)) {
            foreach ($juknis->hadiah as $h) {
                if (!empty($h['icon'])) Storage::disk('public')->delete($h['icon']);
            }
        }

        $juknis->delete();
        return redirect()->back()->with('success', 'Perlombaan dihapus secara permanen!');
    }
}