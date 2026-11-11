<?php

namespace App\Http\Controllers;

use App\Models\BoothDelegasi;
use App\Models\EdisiKmdgi;
use App\Models\Kampus;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class DelegasiBoothController extends Controller
{
    private function getScheduleConfig()
    {
        $waktuBuka      = Carbon::parse(Cache::get('booth_waktu_buka', '2026-11-09T08:00'));
        $waktuTutup     = Carbon::parse(Cache::get('booth_waktu_tutup', '2026-11-10T12:00'));
        $waktuAwarding  = Carbon::parse(Cache::get('booth_waktu_awarding', '2026-11-11T19:00'));
        $isManualClosed = Cache::get('booth_manual_closed', false);

        $now = now();
        $isOpen = !$isManualClosed && $now->between($waktuBuka, $waktuTutup);

        return compact('waktuBuka', 'waktuTutup', 'waktuAwarding', 'isManualClosed', 'isOpen', 'now');
    }

    public function formBooth()
    {
        $user = Auth::user();

        if ($user->kategori !== 'Delegasi') {
            abort(403, 'Akses Ditolak. Hanya peserta Delegasi Kampus yang dapat mengakses halaman ini.');
        }

        $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();
        if (!$edisiAktif) {
            return redirect()->back()->withErrors(['Sistem belum memiliki Edisi KMDGI yang aktif.']);
        }

        $kampus = Kampus::where('nama_institusi', $user->institusi)->first();
        if (!$kampus) {
            return redirect()->route('dashboard')->withErrors(['Data Institusi Anda tidak terdaftar di sistem.']);
        }

        $userIdsSatuKampus = User::where('institusi', $user->institusi)->pluck('id');

        $booth = BoothDelegasi::with([
                'votes' => fn($q) => $q->latest(),
                'votes.user:id,name,institusi,kategori,profile_image'
            ])
            ->withCount('votes')
            ->whereIn('user_id', $userIdsSatuKampus)
            ->where('edisi_kmdgi_id', $edisiAktif->id)
            ->first();

        $jadwal = $this->getScheduleConfig();

        // Flag kunci edit jika sudah Terverifikasi oleh Admin
        $isLocked = $booth && !$booth->status_draft && $booth->status_verifikasi === 'Terverifikasi';

        return view('delegasi.booth.form', compact('user', 'kampus', 'edisiAktif', 'booth', 'jadwal', 'isLocked'));
    }

    public function storeBooth(Request $request)
    {
        $user = Auth::user();

        if ($user->kategori !== 'Delegasi') {
            abort(403, 'Akses Ditolak.');
        }

        $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();
        if (!$edisiAktif) {
            return redirect()->back()->withErrors(['Sistem belum memiliki Edisi KMDGI yang aktif.']);
        }

        $userIdsSatuKampus = User::where('institusi', $user->institusi)->pluck('id');

        $booth = BoothDelegasi::whereIn('user_id', $userIdsSatuKampus)
            ->where('edisi_kmdgi_id', $edisiAktif->id)
            ->first();

        // PROTEKSI UTAMA: Jika sudah Terverifikasi (Terkurasi), tolak segala bentuk edit!
        if ($booth && !$booth->status_draft && $booth->status_verifikasi === 'Terverifikasi') {
            return redirect()->back()->withErrors([
                'Akses Edit Dikunci: Karya Booth Anda sudah melalui tahap kurasi (Terverifikasi) oleh Admin dan tidak dapat diubah lagi.'
            ]);
        }

        $jadwal = $this->getScheduleConfig();

        if (!$jadwal['isOpen']) {
            if ($jadwal['now']->lt($jadwal['waktuBuka'])) {
                return redirect()->back()->withErrors([
                    'Sistem upload karya dan voting baru akan dibuka pada ' . $jadwal['waktuBuka']->translatedFormat('d F Y \p\u\k\u\l H:i') . ' WIB.'
                ]);
            }
            return redirect()->back()->withErrors([
                'Batas waktu unggah karya dan voting telah berakhir pada ' . $jadwal['waktuTutup']->translatedFormat('d F Y \p\u\k\u\l H:i') . ' WIB.'
            ]);
        }

        $isUpdate = $booth !== null;
        $isDraft = $request->input('status_draft') == '1';

        $hasFotoUtama = $booth && !empty($booth->foto_utama) && $request->input('remove_foto_utama') != '1';
        $hasFoto1     = $booth && !empty($booth->foto_pendukung_1) && $request->input('remove_foto_1') != '1';
        $hasFoto2     = $booth && !empty($booth->foto_pendukung_2) && $request->input('remove_foto_2') != '1';

        $request->validate([
            'judul_karya_tematik' => ($isDraft ? 'nullable' : 'required') . '|string|max:100',
            'kreator_karya'       => ($isDraft ? 'nullable' : 'required') . '|string',
            'foto_utama'          => (($isDraft || $hasFotoUtama) ? 'nullable' : 'required') . '|image|mimes:jpeg,jpg,png|max:5120',
            'foto_pendukung_1'    => (($isDraft || $hasFoto1) ? 'nullable' : 'required') . '|image|mimes:jpeg,jpg,png|max:5120',
            'foto_pendukung_2'    => (($isDraft || $hasFoto2) ? 'nullable' : 'required') . '|image|mimes:jpeg,jpg,png|max:5120',
            'deskripsi_singkat'   => [
                $isDraft ? 'nullable' : 'required',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $words = preg_split('/\s+/', trim($value), -1, PREG_SPLIT_NO_EMPTY);
                        if (count($words) > 150) {
                            $fail('Deskripsi singkat maksimal 150 kata (Saat ini: ' . count($words) . ' kata).');
                        }
                    }
                }
            ],
        ]);

        if (!$booth) {
            $booth = new BoothDelegasi();
            $booth->edisi_kmdgi_id = $edisiAktif->id;
            $booth->user_id        = $user->id;
            $booth->institusi      = $user->institusi;
        }

        $booth->judul_karya_tematik = $request->judul_karya_tematik;
        $booth->kreator_karya       = $request->kreator_karya;
        $booth->deskripsi_singkat   = $request->deskripsi_singkat;
        $booth->status_draft        = $isDraft ? 1 : 0;
        $booth->status_verifikasi   = 'Menunggu';

        if ($request->hasFile('foto_utama')) {
            if ($booth->foto_utama && Storage::disk('public')->exists($booth->foto_utama)) {
                Storage::disk('public')->delete($booth->foto_utama);
            }
            $booth->foto_utama = $request->file('foto_utama')->store('submisi/booth', 'public');
        } elseif ($request->input('remove_foto_utama') == '1') {
            if ($booth->foto_utama && Storage::disk('public')->exists($booth->foto_utama)) {
                Storage::disk('public')->delete($booth->foto_utama);
            }
            $booth->foto_utama = null;
        }

        if ($request->hasFile('foto_pendukung_1')) {
            if ($booth->foto_pendukung_1 && Storage::disk('public')->exists($booth->foto_pendukung_1)) {
                Storage::disk('public')->delete($booth->foto_pendukung_1);
            }
            $booth->foto_pendukung_1 = $request->file('foto_pendukung_1')->store('submisi/booth', 'public');
        } elseif ($request->input('remove_foto_1') == '1') {
            if ($booth->foto_pendukung_1 && Storage::disk('public')->exists($booth->foto_pendukung_1)) {
                Storage::disk('public')->delete($booth->foto_pendukung_1);
            }
            $booth->foto_pendukung_1 = null;
        }

        if ($request->hasFile('foto_pendukung_2')) {
            if ($booth->foto_pendukung_2 && Storage::disk('public')->exists($booth->foto_pendukung_2)) {
                Storage::disk('public')->delete($booth->foto_pendukung_2);
            }
            $booth->foto_pendukung_2 = $request->file('foto_pendukung_2')->store('submisi/booth', 'public');
        } elseif ($request->input('remove_foto_2') == '1') {
            if ($booth->foto_pendukung_2 && Storage::disk('public')->exists($booth->foto_pendukung_2)) {
                Storage::disk('public')->delete($booth->foto_pendukung_2);
            }
            $booth->foto_pendukung_2 = null;
        }

        $booth->save();

        if (!$isDraft) {
            $judulNotifAdmin = $isUpdate ? 'Pembaruan Submisi Booth Delegasi' : 'Submisi Booth Delegasi Baru Masuk';
            $aksiPesan = $isUpdate ? 'memperbarui data booth' : 'mengunggah data booth';

            $admins = User::whereIn('role', ['super admin', 'admin'])->get();
            if ($admins->count() > 0) {
                $pesanAdmin = "Kontingen {$user->institusi} ({$user->name}) telah {$aksiPesan} Tematik berjudul \"{$booth->judul_karya_tematik}\". Silakan periksa antrean kurasi.";
                Notification::send($admins, new GeneralNotification(
                    $judulNotifAdmin,
                    $pesanAdmin,
                    'info',
                    route('admin.booth.index')
                ));
            }
        }

        $pesan = $isDraft
            ? 'Draft Booth Delegasi berhasil disimpan!'
            : ($isUpdate
                ? 'Perubahan data Booth berhasil disimpan dan menunggu verifikasi Admin!'
                : 'Submisi Booth Delegasi berhasil dikirim dan menunggu verifikasi Admin!');

        return redirect()->route('delegasi.booth.index')->with('success', $pesan);
    }

    /**
     * Halaman Cetak / Download Poster QR Code Ukuran Kertas A4 (Sisi Delegasi)
     */
    public function printQrPoster()
    {
        $user = Auth::user();

        if ($user->kategori !== 'Delegasi') {
            abort(403, 'Akses Ditolak.');
        }

        $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();
        if (!$edisiAktif) {
            return redirect()->route('delegasi.booth.index')->withErrors(['Sistem belum memiliki Edisi KMDGI yang aktif.']);
        }

        $userIdsSatuKampus = User::where('institusi', $user->institusi)->pluck('id');

        $booth = BoothDelegasi::whereIn('user_id', $userIdsSatuKampus)
            ->where('edisi_kmdgi_id', $edisiAktif->id)
            ->first();

        if (!$booth) {
            return redirect()->route('delegasi.booth.index')
                ->withErrors(['Silakan simpan atau unggah data booth Anda terlebih dahulu sebelum mencetak QR Code.']);
        }

        $voteUrl = route('voting.booth.show', \Illuminate\Support\Str::slug($booth->institusi));

        return view('admin.booth.qr_poster', compact('booth', 'voteUrl'));
    }
}
