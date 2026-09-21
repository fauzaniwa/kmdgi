<?php

namespace App\Http\Controllers;

use App\Models\JuknisLomba;
use App\Models\Kolaborator;
use App\Models\PesertaLomba;
use App\Models\User; // <-- Tambahan untuk notifikasi Admin
use App\Notifications\GeneralNotification; // <-- Tambahan Notifikasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification; // <-- Tambahan Notifikasi Massal
use App\Models\RekeningPembayaran;

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

        $rekenings = RekeningPembayaran::where('is_active', 1)->get();

        return view('kompetisi.daftar', compact('lomba', 'user', 'targetCountdown', 'rekenings'));
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

        // ===========================================================================
        // [NOTIFIKASI] Pendaftaran Lomba Berhasil
        // ===========================================================================
        $urlTujuanAdmin = url('/admin/lomba/peserta'); // Sesuaikan dengan route admin Anda

        if ($data['status_pembayaran'] === 'Menunggu Validasi') {
            // Notifikasi untuk Lomba Berbayar
            $user->notify(new GeneralNotification(
                'Pendaftaran Lomba Sedang Diproses',
                "Pendaftaran tim {$request->nama_tim_peserta} untuk lomba \"{$lomba->judul_lomba}\" telah kami terima. Kami sedang memverifikasi bukti pembayaran Anda.",
                'info',
                route('peserta.status-lomba')
            ));

            $admins = User::whereIn('role', ['super admin', 'admin'])->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new GeneralNotification(
                    'Verifikasi Pembayaran Lomba',
                    "Tim {$request->nama_tim_peserta} ({$user->name}) mendaftar lomba \"{$lomba->judul_lomba}\" dan telah mengunggah bukti pembayaran.",
                    'warning',
                    $urlTujuanAdmin
                ));
            }
        } else {
            // Notifikasi untuk Lomba Gratis
            $user->notify(new GeneralNotification(
                'Pendaftaran Lomba Berhasil',
                "Selamat! Tim {$request->nama_tim_peserta} telah berhasil terdaftar pada perlombaan \"{$lomba->judul_lomba}\". Silakan pantau status karya Anda di Dashboard.",
                'success',
                route('peserta.status-lomba')
            ));
        }

        // Jika user langsung mengumpulkan karya saat mendaftar
        if ($data['status_karya'] === 'Terkirim') {
            $admins = User::whereIn('role', ['super admin', 'admin'])->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new GeneralNotification(
                    'Karya Lomba Baru Masuk',
                    "Tim {$request->nama_tim_peserta} telah mengunggah karya untuk lomba \"{$lomba->judul_lomba}\".",
                    'info',
                    $urlTujuanAdmin
                ));
            }
        }
        // ===========================================================================

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

        $rekenings = RekeningPembayaran::where('is_active', 1)->get();

        return view('dashboard.edit_pendaftaran', compact('pendaftaran', 'lomba', 'targetCountdown', 'rekenings'));
    }

    // FUNGSI UNTUK UPDATE DATA / UNGGAH KARYA MENYUSUL
    public function updateDaftar(Request $request, $id)
    {
        $pendaftaran = PesertaLomba::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $user = Auth::user();
        
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

        $adaUpdateBayar = false;
        $adaUpdateKarya = false;

        // Update Bukti Bayar
        if ($request->hasFile('bukti_pembayaran')) {
            if ($pendaftaran->bukti_pembayaran) Storage::disk('public')->delete($pendaftaran->bukti_pembayaran);
            $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('lomba_pembayaran', 'public');
            $data['status_pembayaran'] = 'Menunggu Validasi'; // Reset status validasi
            $adaUpdateBayar = true;
        }

        // Update File Karya
        if ($request->hasFile('file_karya')) {
            if ($pendaftaran->file_karya) Storage::disk('public')->delete($pendaftaran->file_karya);
            $data['file_karya'] = $request->file('file_karya')->store('lomba_karya', 'public');
            $data['status_karya'] = 'Terkirim';
            $adaUpdateKarya = true;
        } elseif ($request->filled('link_karya') || $request->filled('judul_karya')) {
            // Jika status karya sebelumnya belum terkirim, maka ini dianggap update karya
            if ($pendaftaran->status_karya !== 'Terkirim') {
                $adaUpdateKarya = true;
            }
            $data['status_karya'] = 'Terkirim';
        }

        $pendaftaran->update($data);

        // ===========================================================================
        // [NOTIFIKASI] Pembaruan Data / Pengumpulan Karya Menyusul
        // ===========================================================================
        $urlTujuanAdmin = url('/admin/perlombaan/peserta'); // Sesuaikan dengan route admin Anda

        if ($adaUpdateBayar || $adaUpdateKarya) {
            $admins = User::whereIn('role', ['super admin', 'admin'])->get();
            
            if ($admins->count() > 0) {
                if ($adaUpdateBayar) {
                    Notification::send($admins, new GeneralNotification(
                        'Pembaruan Bukti Pembayaran Lomba',
                        "Tim {$request->nama_tim_peserta} ({$user->name}) telah memperbarui/mengunggah ulang bukti pembayaran. Silakan periksa kembali.",
                        'warning',
                        $urlTujuanAdmin
                    ));
                }

                if ($adaUpdateKarya) {
                    Notification::send($admins, new GeneralNotification(
                        'Pengumpulan/Pembaruan Karya Lomba',
                        "Tim {$request->nama_tim_peserta} telah mengunggah/memperbarui file karya lomba mereka.",
                        'info',
                        $urlTujuanAdmin
                    ));
                }
            }
        }
        // ===========================================================================

        return redirect()->route('peserta.status-lomba')->with('success', 'Data tim dan karya berhasil diperbarui!');
    }
}