<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache; // <-- Import Cache diletakkan di sini (di luar class)
use App\Models\User;
use App\Models\Kampus;
use App\Models\BerkasDelegasi;
use Carbon\Carbon;

class DelegasiController extends Controller
{
    /**
     * Menampilkan daftar anggota tim (Hanya untuk Ketua)
     */
    public function manageTim()
    {
        $user = Auth::user();

        // Keamanan: Hanya Ketua Delegasi yang boleh masuk ke halaman ini
        if ($user->kategori !== 'Delegasi' || $user->peran_delegasi !== 'Ketua') {
            abort(403, 'Akses Ditolak. Hanya Ketua Delegasi yang dapat mengakses halaman Manajemen Tim.');
        }

        // Ambil daftar user yang satu institusi dan menggunakan auth_code yang sama
        // KECUALI dirinya sendiri (Ketua)
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

        // Keamanan ekstra
        if ($user->kategori !== 'Delegasi' || $user->peran_delegasi !== 'Ketua') {
            return redirect()->back()->withErrors(['Akses ditolak.']);
        }

        // Cari anggota spesifik yang terkait dengan ketua ini
        $anggota = User::where('institusi', $user->institusi)
                       ->where('auth_code', $user->auth_code)
                       ->where('id', $id)
                       ->firstOrFail();

        // Validasi input
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $anggota->id,
            'no_hp' => 'nullable|string|max:20',
        ]);

        // Simpan pembaruan
        $anggota->name = $request->name;
        $anggota->email = $request->email;
        $anggota->no_hp = $request->no_hp;
        $anggota->save();

        return redirect()->back()->with('success', 'Data profil ' . $anggota->name . ' berhasil diperbarui.');
    }

    /**
     * Mengeluarkan anggota dari Tim (Reset institusi dan auth_code-nya)
     */
    public function removeMember($id)
    {
        $user = Auth::user();

        // Keamanan ekstra
        if ($user->kategori !== 'Delegasi' || $user->peran_delegasi !== 'Ketua') {
            return redirect()->back()->withErrors(['Akses ditolak.']);
        }

        // Cari anggota spesifik yang terkait dengan ketua ini
        $anggota = User::where('institusi', $user->institusi)
                       ->where('auth_code', $user->auth_code)
                       ->where('id', $id)
                       ->firstOrFail();

        // Menghapus keterkaitan (Keluarkan dari Tim)
        $anggota->institusi = null;
        $anggota->auth_code = null;
        $anggota->save();

        return redirect()->back()->with('success', $anggota->name . ' telah berhasil dikeluarkan dari kontingen delegasi.');
    }

    /**
     * Menampilkan status keanggotaan institusi delegasi
     */
    public function statusKampus()
    {
        $user = Auth::user();

        // Keamanan: Hanya yang terdaftar sebagai delegasi yang bisa akses
        if ($user->kategori !== 'Delegasi') {
            abort(403, 'Akses Ditolak. Anda tidak terdaftar sebagai peserta Delegasi Kampus.');
        }

        // Ambil data kampus sesuai dengan field 'institusi' yang ada pada profil user
        $kampus = Kampus::where('nama_institusi', $user->institusi)->first();

        // Jika data kampus belum diisi atau tidak ditemukan di database
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

        // Pastikan user sudah punya auth_code (sudah join tim)
        if (empty($user->auth_code)) {
            return redirect()->route('dashboard')->withErrors(['Anda harus melengkapi data institusi dan Auth Code terlebih dahulu.']);
        }

        // Cari atau buat record berkas untuk tim ini berdasarkan auth_code
        $berkas = BerkasDelegasi::firstOrCreate(
            ['auth_code' => $user->auth_code],
            ['institusi' => $user->institusi]
        );

        // Ambil konfigurasi dari Cache (Diatur oleh Admin)
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
            'bukti_pembayaran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
            'formulir_pendaftaran' => 'nullable|file|mimes:pdf|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            if ($berkas->bukti_pembayaran) {
                Storage::disk('public')->delete($berkas->bukti_pembayaran);
            }
            $berkas->bukti_pembayaran = $request->file('bukti_pembayaran')->store('berkas_tim/pembayaran', 'public');
        }

        if ($request->hasFile('formulir_pendaftaran')) {
            if ($berkas->formulir_pendaftaran) {
                Storage::disk('public')->delete($berkas->formulir_pendaftaran);
            }
            $berkas->formulir_pendaftaran = $request->file('formulir_pendaftaran')->store('berkas_tim/formulir', 'public');
        }

        $berkas->save();

        return redirect()->back()->with('success', 'Berkas berhasil diunggah dan disinkronisasi ke seluruh tim!');
    }
}