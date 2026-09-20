<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Kampus;
use App\Models\BerkasDelegasi;
use App\Notifications\GeneralNotification;
use Carbon\Carbon;

class DelegasiController extends Controller
{
    /**
     * Menampilkan daftar anggota tim (Hanya untuk Ketua)
     */
    public function manageTim()
    {
        $user = Auth::user();

        if ($user->kategori !== 'Delegasi' || $user->peran_delegasi !== 'Ketua') {
            abort(403, 'Akses Ditolak. Hanya Ketua Delegasi yang dapat mengakses halaman Manajemen Tim.');
        }

        $anggotaTim = User::where('institusi', $user->institusi)
            ->where('auth_code', $user->auth_code)
            ->where('id', '!=', $user->id)
            ->where('peran_delegasi', 'Anggota Delegasi')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('delegasi.manage-tim', compact('anggotaTim'));
    }

    /**
     * Memperbarui data profil anggota tim (Oleh Ketua)
     */
    public function updateMember(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->kategori !== 'Delegasi' || $user->peran_delegasi !== 'Ketua') {
            return redirect()->back()->withErrors(['Akses ditolak.']);
        }

        $anggota = User::where('institusi', $user->institusi)
            ->where('auth_code', $user->auth_code)
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $anggota->id,
            'no_hp' => 'nullable|string|max:20',
        ]);

        $anggota->name = $request->name;
        $anggota->email = $request->email;
        $anggota->no_hp = $request->no_hp;
        $anggota->save();

        // ===========================================================================
        // [NOTIFIKASI] 
        // ===========================================================================

        // 1. Beritahu Admin
        $admins = User::whereIn('role', ['super admin', 'admin'])->get();
        if ($admins->count() > 0) {
            $pesanAdmin = "Ketua Delegasi dari {$user->institusi} ({$user->name}) telah memperbarui data anggota bernama {$anggota->name}.";
            Notification::send($admins, new GeneralNotification('Pembaruan Data Anggota Delegasi', $pesanAdmin, 'info', url('/')));
        }

        // 2. Beritahu Anggota yang datanya diubah
        $anggota->notify(new GeneralNotification(
            'Profil Anda Diperbarui',
            'Data profil Anda (Nama/Email/No HP) telah diperbarui oleh Ketua Delegasi.',
            'info',
            route('dashboard')
        ));
        // ===========================================================================

        return redirect()->back()->with('success', 'Data profil ' . $anggota->name . ' berhasil diperbarui.');
    }

    /**
     * Mengeluarkan anggota dari Tim (Reset institusi dan auth_code-nya)
     */
    public function removeMember($id)
    {
        $user = Auth::user();

        if ($user->kategori !== 'Delegasi' || $user->peran_delegasi !== 'Ketua') {
            return redirect()->back()->withErrors(['Akses ditolak.']);
        }

        $anggota = User::where('institusi', $user->institusi)
            ->where('auth_code', $user->auth_code)
            ->where('id', $id)
            ->firstOrFail();

        $namaInstitusi = $anggota->institusi;
        $namaAnggota = $anggota->name;

        $anggota->institusi = null;
        $anggota->auth_code = null;
        $anggota->save();

        // ===========================================================================
        // [NOTIFIKASI] 
        // ===========================================================================

        // 1. Beritahu Admin
        $admins = User::whereIn('role', ['super admin', 'admin'])->get();
        if ($admins->count() > 0) {
            $pesanAdmin = "Ketua Delegasi {$namaInstitusi} telah mengeluarkan {$namaAnggota} dari kontingen mereka.";
            Notification::send($admins, new GeneralNotification('Perubahan Formasi Kontingen', $pesanAdmin, 'warning', url('/')));
        }

        // 2. Beritahu Anggota yang dikeluarkan
        $anggota->notify(new GeneralNotification(
            'Status Keanggotaan Berubah',
            "Anda telah dikeluarkan dari kontingen delegasi {$namaInstitusi} oleh Ketua Delegasi. Silakan hubungi ketua tim Anda untuk info lebih lanjut.",
            'warning',
            route('dashboard')
        ));
        // ===========================================================================

        return redirect()->back()->with('success', $namaAnggota . ' telah berhasil dikeluarkan dari kontingen delegasi.');
    }

    /**
     * Menampilkan status keanggotaan institusi delegasi
     */
    public function statusKampus()
    {
        $user = Auth::user();

        if ($user->kategori !== 'Delegasi') {
            abort(403, 'Akses Ditolak. Anda tidak terdaftar sebagai peserta Delegasi Kampus.');
        }

        $kampus = Kampus::where('nama_institusi', $user->institusi)->first();

        if (!$kampus) {
            return redirect()->route('dashboard')->withErrors([
                'auth_code' => 'Anda belum terhubung ke institusi manapun atau data kampus Anda belum tersedia di sistem. Silakan lengkapi data delegasi Anda terlebih dahulu.'
            ]);
        }

        return view('delegasi.status', compact('kampus', 'user'));
    }

    /**
     * Menampilkan halaman Berkas Tim
     */
    public function berkasTim()
    {
        $user = Auth::user();

        if ($user->kategori !== 'Delegasi') {
            abort(403, 'Akses Ditolak.');
        }

        if (empty($user->auth_code)) {
            return redirect()->route('dashboard')->withErrors(['Anda harus melengkapi data institusi dan Auth Code terlebih dahulu.']);
        }

        $berkas = BerkasDelegasi::firstOrCreate(
            ['auth_code' => $user->auth_code],
            ['institusi' => $user->institusi]
        );

        $config = [
            'deadline_pembayaran'       => Carbon::parse(Cache::get('deadline_pembayaran', '2026-09-30T23:59')),
            'deadline_formulir'         => Carbon::parse(Cache::get('deadline_formulir', '2026-10-15T23:59')),
            'text_bukti_pembayaran'     => Cache::get('text_bukti_pembayaran', 'Struk/Resi transfer pendaftaran delegasi (Format: JPG/PNG/PDF, Max 5MB).'),
            'text_formulir_pendaftaran' => Cache::get('text_formulir_pendaftaran', 'Formulir resmi bertanda tangan Ketua Delegasi (Format: PDF, Max 5MB).'),
            'text_buku_panduan'         => Cache::get('text_buku_panduan', 'Pahami seluruh aturan, timeline, dan syarat berkas yang harus diunggah.'),
            'file_buku_panduan'         => Cache::get('file_buku_panduan', null)
        ];

        return view('delegasi.berkas', compact('user', 'berkas', 'config'));
    }

    /**
     * Memproses upload berkas oleh anggota/ketua
     */
    public function uploadBerkas(Request $request)
    {
        $user = Auth::user();
        $berkas = BerkasDelegasi::where('auth_code', $user->auth_code)->firstOrFail();

        $request->validate([
            'bukti_pembayaran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'formulir_pendaftaran' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $fileUploaded = false;

        if ($request->hasFile('bukti_pembayaran')) {
            if ($berkas->bukti_pembayaran) {
                Storage::disk('public')->delete($berkas->bukti_pembayaran);
            }
            $berkas->bukti_pembayaran = $request->file('bukti_pembayaran')->store('berkas_tim/pembayaran', 'public');
            $fileUploaded = true;
        }

        if ($request->hasFile('formulir_pendaftaran')) {
            if ($berkas->formulir_pendaftaran) {
                Storage::disk('public')->delete($berkas->formulir_pendaftaran);
            }
            $berkas->formulir_pendaftaran = $request->file('formulir_pendaftaran')->store('berkas_tim/formulir', 'public');
            $fileUploaded = true;
        }

        $berkas->save();

        // ===========================================================================
        // [NOTIFIKASI] 
        // ===========================================================================
        if ($fileUploaded) {
            $admins = User::whereIn('role', ['super admin', 'admin'])->get();

            if ($admins->count() > 0) {
                $pesanAdmin = "Tim dari institusi {$user->institusi} baru saja mengunggah/memperbarui berkas pendaftaran mereka. Silakan lakukan pengecekan.";

                Notification::send($admins, new GeneralNotification(
                    'Pembaruan Berkas Delegasi',
                    $pesanAdmin,
                    'success',
                    route('admin.berkas.index') // Sesuaikan dengan route name admin Anda
                ));
            }
        }
        // ===========================================================================

        return redirect()->back()->with('success', 'Berkas berhasil diunggah dan disinkronisasi ke seluruh tim!');
    }
}
