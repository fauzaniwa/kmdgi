<?php

namespace App\Http\Controllers;

use App\Models\JuknisLomba;
use App\Models\Kolaborator;
use App\Models\PesertaLomba;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KompetisiController extends Controller
{
    public function index()
    {
        $lombas = JuknisLomba::where('is_active', 1)->latest()->get();
        return view('kompetisi.index', compact('lombas'));
    }

    public function show($slug)
    {
        $lomba = JuknisLomba::where('slug', $slug)->where('is_active', 1)->firstOrFail();

        $juriIds = is_array($lomba->juri) ? $lomba->juri : [];
        $dewanJuri = Kolaborator::whereIn('id', $juriIds)->get();

        $targetCountdown = null;
        $isOpen = true;
        $timeline = is_string($lomba->timeline) ? json_decode($lomba->timeline, true) : ($lomba->timeline ?? []);
        
        if (is_array($timeline) && count($timeline) > 0) {
            foreach ($timeline as $fase) {
                if (!$targetCountdown && isset($fase['tanggal']) && strtotime($fase['tanggal'] . ' 23:59:59') > time()) {
                    $targetCountdown = $fase['tanggal'];
                }
                if (isset($fase['status']) && $fase['status'] === 'batas_pendaftaran') {
                    if (time() > strtotime($fase['tanggal'] . ' 23:59:59')) {
                        $isOpen = false;
                    }
                }
            }
            if (!$targetCountdown) {
                $targetCountdown = end($timeline)['tanggal'] ?? null;
            }
        }

        $sudahDaftar = false;
        if (Auth::check()) {
            $sudahDaftar = PesertaLomba::where('juknis_lomba_id', $lomba->id)->where('user_id', Auth::id())->exists();
        }

        return view('kompetisi.show', compact('lomba', 'dewanJuri', 'timeline', 'targetCountdown', 'sudahDaftar', 'isOpen'));
    }

    // FUNGSI UNTUK MENAMPILKAN FORM DAFTAR BARU
    public function daftar($slug)
    {
        $lomba = JuknisLomba::where('slug', $slug)->where('is_active', 1)->firstOrFail();
        $user = Auth::user();

        if (PesertaLomba::where('juknis_lomba_id', $lomba->id)->where('user_id', $user->id)->exists()) {
            return redirect()->route('kompetisi.show', $lomba->slug)->with('success', 'Anda sudah terdaftar pada perlombaan ini.');
        }

        $isOpen = true;
        $targetCountdown = null;
        $timeline = is_string($lomba->timeline) ? json_decode($lomba->timeline, true) : ($lomba->timeline ?? []);
        
        if (is_array($timeline) && count($timeline) > 0) {
            foreach ($timeline as $fase) {
                if (isset($fase['status']) && $fase['status'] === 'batas_pendaftaran') {
                    $targetCountdown = $fase['tanggal'];
                    if (time() > strtotime($fase['tanggal'] . ' 23:59:59')) {
                        $isOpen = false;
                    }
                }
            }
        }

        if (!$isOpen) {
            return redirect()->route('kompetisi.show', $lomba->slug)->withErrors(['Pendaftaran untuk perlombaan ini telah ditutup.']);
        }

        // PERBAIKAN ERROR: mengirim targetCountdown ke view
        return view('kompetisi.daftar', compact('lomba', 'user', 'targetCountdown'));
    }

    // FUNGSI UNTUK MENYIMPAN DAFTAR BARU
    public function storeDaftar(Request $request, $slug)
    {
        $lomba = JuknisLomba::where('slug', $slug)->where('is_active', 1)->firstOrFail();
        $user = Auth::user();

        if (PesertaLomba::where('juknis_lomba_id', $lomba->id)->where('user_id', $user->id)->exists()) {
            return redirect()->route('peserta.status-lomba');
        }

        $rules = [
            'nama_tim_peserta' => 'required|string|max:255',
            'institusi_asal'   => 'required|string|max:255',
            'kategori_pendaftar'=> 'required|string',
            'no_whatsapp'      => 'required|string|max:20',
            'judul_karya'      => 'nullable|string|max:255',
            'kreator_karya'    => 'nullable|string|max:255',
            'deskripsi_karya'  => 'nullable|string',
            'link_karya'       => 'nullable|url|max:255',
            'file_karya'       => 'nullable|file|mimes:zip,rar,pdf|max:10240',
        ];

        if ($lomba->biaya_pendaftaran > 0) {
            $rules['bukti_pembayaran'] = 'required|image|mimes:jpeg,png,jpg|max:3072';
        }

        $request->validate($rules);
        $data = $request->only(['nama_tim_peserta', 'institusi_asal', 'kategori_pendaftar', 'no_whatsapp', 'judul_karya', 'kreator_karya', 'deskripsi_karya', 'link_karya']);
        $data['juknis_lomba_id'] = $lomba->id;
        $data['user_id'] = $user->id;

        if ($lomba->biaya_pendaftaran > 0) {
            $data['status_pembayaran'] = 'Menunggu Validasi';
            if ($request->hasFile('bukti_pembayaran')) {
                $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('lomba_pembayaran', 'public');
            }
        } else {
            $data['status_pembayaran'] = 'Gratis';
        }

        if ($request->filled('judul_karya') || $request->hasFile('file_karya') || $request->filled('link_karya')) {
            $data['status_karya'] = 'Terkirim';
            if ($request->hasFile('file_karya')) {
                $data['file_karya'] = $request->file('file_karya')->store('lomba_karya', 'public');
            }
        } else {
            $data['status_karya'] = 'Belum Mengumpulkan';
        }

        PesertaLomba::create($data);
        return redirect()->route('peserta.status-lomba')->with('success', 'Berhasil mendaftar perlombaan!');
    }

    // FUNGSI UNTUK MENAMPILKAN HALAMAN MANAGE/EDIT KARYA
    public function editDaftar($id)
    {
        $pendaftaran = PesertaLomba::where('id', $id)->where('user_id', Auth::id())->with('lomba')->firstOrFail();
        $lomba = $pendaftaran->lomba;
        
        $targetCountdown = null;
        $timeline = is_string($lomba->timeline) ? json_decode($lomba->timeline, true) : ($lomba->timeline ?? []);
        if (is_array($timeline) && count($timeline) > 0) {
            foreach ($timeline as $fase) {
                if (isset($fase['status']) && $fase['status'] === 'batas_pendaftaran') {
                    $targetCountdown = $fase['tanggal'];
                }
            }
        }

        return view('dashboard.edit_pendaftaran', compact('pendaftaran', 'lomba', 'targetCountdown'));
    }

    // FUNGSI UNTUK UPDATE DATA / UNGGAH KARYA MENYUSUL
    public function updateDaftar(Request $request, $id)
    {
        $pendaftaran = PesertaLomba::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        $rules = [
            'nama_tim_peserta' => 'required|string|max:255',
            'institusi_asal'   => 'required|string|max:255',
            'kategori_pendaftar'=> 'required|string',
            'no_whatsapp'      => 'required|string|max:20',
            'judul_karya'      => 'nullable|string|max:255',
            'kreator_karya'    => 'nullable|string|max:255',
            'deskripsi_karya'  => 'nullable|string',
            'link_karya'       => 'nullable|url|max:255',
            'file_karya'       => 'nullable|file|mimes:zip,rar,pdf|max:10240',
        ];

        if ($request->hasFile('bukti_pembayaran')) {
            $rules['bukti_pembayaran'] = 'image|mimes:jpeg,png,jpg|max:3072';
        }

        $request->validate($rules);
        $data = $request->only(['nama_tim_peserta', 'institusi_asal', 'kategori_pendaftar', 'no_whatsapp', 'judul_karya', 'kreator_karya', 'deskripsi_karya', 'link_karya']);

        // Update Bukti Bayar
        if ($request->hasFile('bukti_pembayaran')) {
            if ($pendaftaran->bukti_pembayaran) Storage::disk('public')->delete($pendaftaran->bukti_pembayaran);
            $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('lomba_pembayaran', 'public');
            $data['status_pembayaran'] = 'Menunggu Validasi'; // Reset status validasi
        }

        // Update File Karya
        if ($request->hasFile('file_karya')) {
            if ($pendaftaran->file_karya) Storage::disk('public')->delete($pendaftaran->file_karya);
            $data['file_karya'] = $request->file('file_karya')->store('lomba_karya', 'public');
            $data['status_karya'] = 'Terkirim';
        } elseif ($request->filled('link_karya') || $request->filled('judul_karya')) {
            $data['status_karya'] = 'Terkirim';
        }

        $pendaftaran->update($data);
        return redirect()->route('peserta.status-lomba')->with('success', 'Data tim dan karya berhasil diperbarui!');
    }
}