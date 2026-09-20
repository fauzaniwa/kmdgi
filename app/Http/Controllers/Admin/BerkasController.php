<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BerkasDelegasi;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use App\Models\User; // <-- Tambahan untuk mengambil data Ketua Delegasi
use App\Notifications\GeneralNotification; // <-- [NOTIFIKASI] Import Class Notification
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache; 

class BerkasController extends Controller
{
    public function index(Request $request)
    {
        $query = BerkasDelegasi::query();

        if ($request->filled('search')) {
            $query->where('institusi', 'like', '%' . $request->search . '%')
                  ->orWhere('auth_code', 'like', '%' . $request->search . '%');
        }

        $dataBerkas = $query->latest()->paginate(15)->withQueryString();

        // Ambil data dari cache (Sediakan nilai default jika kosong)
        $config = [
            'deadline_pembayaran'       => Cache::get('deadline_pembayaran', '2026-09-30T23:59'),
            'deadline_formulir'         => Cache::get('deadline_formulir', '2026-10-15T23:59'),
            'text_bukti_pembayaran'     => Cache::get('text_bukti_pembayaran', 'Struk/Resi transfer pendaftaran delegasi (Format: JPG/PNG/PDF, Max 5MB).'),
            'text_formulir_pendaftaran' => Cache::get('text_formulir_pendaftaran', 'Formulir resmi bertanda tangan Ketua Delegasi (Format: PDF, Max 5MB).'),
            'text_buku_panduan'         => Cache::get('text_buku_panduan', 'Pahami seluruh aturan, timeline, dan syarat berkas yang harus diunggah.'),
            'file_buku_panduan'         => Cache::get('file_buku_panduan', null)
        ];

        return view('admin.berkas.index', compact('dataBerkas', 'config'));
    }

    // Fungsi menyimpan konfigurasi teks, deadline, dan Buku Panduan
    public function updateConfig(Request $request)
    {
        $request->validate([
            'deadline_pembayaran'       => 'required|date',
            'deadline_formulir'         => 'required|date',
            'text_bukti_pembayaran'     => 'required|string',
            'text_formulir_pendaftaran' => 'required|string',
            'text_buku_panduan'         => 'required|string',
            'file_buku_panduan'         => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        // Simpan teks & deadline ke Cache
        Cache::put('deadline_pembayaran', $request->deadline_pembayaran, now()->addYear());
        Cache::put('deadline_formulir', $request->deadline_formulir, now()->addYear());
        Cache::put('text_bukti_pembayaran', $request->text_bukti_pembayaran, now()->addYear());
        Cache::put('text_formulir_pendaftaran', $request->text_formulir_pendaftaran, now()->addYear());
        Cache::put('text_buku_panduan', $request->text_buku_panduan, now()->addYear());

        // Handle upload file Buku Panduan
        if ($request->hasFile('file_buku_panduan')) {
            $oldFile = Cache::get('file_buku_panduan');
            if ($oldFile) {
                Storage::disk('public')->delete($oldFile);
            }
            $path = $request->file('file_buku_panduan')->store('panduan', 'public');
            Cache::put('file_buku_panduan', $path, now()->addYear());
        }

        // ===========================================================================
        // [LOG AKTIVITAS] Mencatat Perubahan Konfigurasi Berkas
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Manajemen Berkas',
            'aksi'       => 'Update',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' telah memperbarui deadline dan konfigurasi teks pendaftaran.',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Konfigurasi teks, deadline, dan file Panduan berhasil diperbarui!');
    }

    public function uploadKwitansi(Request $request, $id) 
    { 
        $request->validate([
            'kwitansi' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        
        $berkas = BerkasDelegasi::findOrFail($id);
        
        if ($request->hasFile('kwitansi')) {
            if ($berkas->kwitansi) Storage::disk('public')->delete($berkas->kwitansi);
            $berkas->kwitansi = $request->file('kwitansi')->store('berkas_tim/kwitansi', 'public');
            $berkas->save();
        }

        // ===========================================================================
        // [LOG AKTIVITAS] Mencatat Upload Kwitansi
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Manajemen Berkas',
            'aksi'       => 'Upload',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' mengunggah e-Kwitansi untuk delegasi ' . $berkas->institusi . '.',
            'ip_address' => $request->ip(),
        ]);

        // [NOTIFIKASI] Beritahu Ketua Delegasi
        $ketuaDelegasi = User::where('institusi', $berkas->institusi)->where('peran_delegasi', 'Ketua')->first();
        if ($ketuaDelegasi) {
            $ketuaDelegasi->notify(new GeneralNotification(
                'e-Kwitansi Diterbitkan', 
                "e-Kwitansi pembayaran delegasi {$berkas->institusi} telah diterbitkan oleh panitia. Silakan unduh di halaman pemberkasan Anda.", 
                'success', 
                route('delegasi.berkas') // Asumsi rute halaman berkas delegasi
            ));
        }
        // ===========================================================================

        return redirect()->back()->with('success', 'Kwitansi untuk ' . $berkas->institusi . ' berhasil diunggah!');
    }

    public function resetBerkas(Request $request, $id) 
    { 
        $berkas = BerkasDelegasi::findOrFail($id);
        $jenis = $request->input('jenis');
        $namaBerkasString = '';

        if ($jenis === 'pembayaran' && $berkas->bukti_pembayaran) {
            Storage::disk('public')->delete($berkas->bukti_pembayaran);
            $berkas->bukti_pembayaran = null;
            $namaBerkasString = 'Bukti Pembayaran';
        } elseif ($jenis === 'formulir' && $berkas->formulir_pendaftaran) {
            Storage::disk('public')->delete($berkas->formulir_pendaftaran);
            $berkas->formulir_pendaftaran = null;
            $namaBerkasString = 'Formulir Pendaftaran';
        }
        
        $berkas->save();

        // ===========================================================================
        // [LOG AKTIVITAS] Mencatat Penghapusan Berkas (Reset)
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Manajemen Berkas',
            'aksi'       => 'Delete',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' mereset/menolak ' . $namaBerkasString . ' dari delegasi ' . $berkas->institusi . '.',
            'ip_address' => $request->ip(),
        ]);

        // [NOTIFIKASI] Beritahu Ketua Delegasi agar segera mengunggah ulang
        $ketuaDelegasi = User::where('institusi', $berkas->institusi)->where('peran_delegasi', 'Ketua')->first();
        if ($ketuaDelegasi) {
            $ketuaDelegasi->notify(new GeneralNotification(
                'Berkas Ditolak/Direset', 
                "{$namaBerkasString} delegasi Anda telah ditolak/direset oleh panitia. Silakan periksa kembali dan unggah ulang berkas yang benar.", 
                'danger', // Menggunakan warna merah (danger) 
                route('delegasi.berkas') 
            ));
        }
        // ===========================================================================

        return redirect()->back()->with('success', 'Berkas berhasil direset.');
    }
}