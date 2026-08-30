<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JuknisLomba;
use App\Models\EdisiKmdgi;
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
        return view('admin.juknis.form', compact('edisiId', 'edisi'));
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

        $data = $request->except(['_token', 'poster', 'hadiah', 'juri', 'file_guidebook', 'file_panduan_online']);

        // Upload Poster Utama
        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('lomba_poster', 'public');
        }
        
        // Upload Berkas Dokumen Lomba
        if ($request->hasFile('file_guidebook')) {
            $data['file_guidebook'] = $request->file('file_guidebook')->store('lomba_dokumen', 'public');
        }
        if ($request->hasFile('file_panduan_online')) {
            $data['file_panduan_online'] = $request->file('file_panduan_online')->store('lomba_dokumen', 'public');
        }

        // Proses JSON Hadiah
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

        // Proses JSON Juri
        $juriData = [];
        if ($request->has('juri')) {
            foreach ($request->juri as $i => $j) {
                $fotoPath = null;
                if ($request->hasFile("juri.{$i}.foto")) {
                    $fotoPath = $request->file("juri.{$i}.foto")->store('lomba_juri', 'public');
                }
                $juriData[] = [
                    'nama' => $j['nama'] ?? '',
                    'keterangan' => $j['keterangan'] ?? '',
                    'deskripsi' => $j['deskripsi'] ?? '',
                    'urutan' => $j['urutan'] ?? 99,
                    'foto' => $fotoPath
                ];
            }
            usort($juriData, fn($a, $b) => $a['urutan'] <=> $b['urutan']);
        }
        $data['juri'] = $juriData;

        JuknisLomba::create($data);
        return redirect()->route('admin.juknis.index', ['edisi_id' => $request->edisi_kmdgi_id])->with('success', 'Juknis Perlombaan berhasil dibuat!');
    }

    public function edit($id)
    {
        $juknis = JuknisLomba::findOrFail($id);
        $edisiId = $juknis->edisi_kmdgi_id;
        $edisi = EdisiKmdgi::findOrFail($edisiId);
        return view('admin.juknis.form', compact('juknis', 'edisiId', 'edisi'));
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

        $data = $request->except(['_token', '_method', 'poster', 'hadiah', 'juri', 'remove_poster', 'file_guidebook', 'file_panduan_online', 'remove_file_guidebook', 'remove_file_panduan_online']);

        // --- FIX: BAGIAN YANG TERLEWAT SEBELUMNYA (UPDATE POSTER) ---
        if ($request->hasFile('poster')) {
            if ($juknis->poster) Storage::disk('public')->delete($juknis->poster);
            $data['poster'] = $request->file('poster')->store('lomba_poster', 'public');
        } elseif ($request->remove_poster == '1') {
            if ($juknis->poster) Storage::disk('public')->delete($juknis->poster);
            $data['poster'] = null;
        }
        // -------------------------------------------------------------

        // Update Berkas Guidebook
        if ($request->hasFile('file_guidebook')) {
            if ($juknis->file_guidebook) Storage::disk('public')->delete($juknis->file_guidebook);
            $data['file_guidebook'] = $request->file('file_guidebook')->store('lomba_dokumen', 'public');
        } elseif ($request->remove_file_guidebook == '1') {
            if ($juknis->file_guidebook) Storage::disk('public')->delete($juknis->file_guidebook);
            $data['file_guidebook'] = null;
        }

        // Update Berkas Panduan Online
        if ($request->hasFile('file_panduan_online')) {
            if ($juknis->file_panduan_online) Storage::disk('public')->delete($juknis->file_panduan_online);
            $data['file_panduan_online'] = $request->file('file_panduan_online')->store('lomba_dokumen', 'public');
        } elseif ($request->remove_file_panduan_online == '1') {
            if ($juknis->file_panduan_online) Storage::disk('public')->delete($juknis->file_panduan_online);
            $data['file_panduan_online'] = null;
        }

        // Update Hadiah
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

        // Update Juri
        $juriData = [];
        if ($request->has('juri')) {
            foreach ($request->juri as $i => $j) {
                $fotoPath = $j['old_foto'] ?? null;
                if ($request->hasFile("juri.{$i}.foto")) {
                    if ($fotoPath) Storage::disk('public')->delete($fotoPath);
                    $fotoPath = $request->file("juri.{$i}.foto")->store('lomba_juri', 'public');
                }
                $juriData[] = [
                    'nama' => $j['nama'] ?? '',
                    'keterangan' => $j['keterangan'] ?? '',
                    'deskripsi' => $j['deskripsi'] ?? '',
                    'urutan' => $j['urutan'] ?? 99,
                    'foto' => $fotoPath
                ];
            }
            usort($juriData, fn($a, $b) => $a['urutan'] <=> $b['urutan']);
        }
        $data['juri'] = $juriData;

        // Kosongkan array kategori_peserta & timeline jika dikirim kosong
        if (!$request->has('kategori_peserta')) $data['kategori_peserta'] = [];
        if (!$request->has('timeline')) $data['timeline'] = [];

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
        if (is_array($juknis->juri)) {
            foreach ($juknis->juri as $j) {
                if (!empty($j['foto'])) Storage::disk('public')->delete($j['foto']);
            }
        }

        $juknis->delete();
        return redirect()->back()->with('success', 'Perlombaan dihapus secara permanen!');
    }
}