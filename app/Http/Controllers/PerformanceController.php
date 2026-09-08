<?php

namespace App\Http\Controllers;

use App\Models\Penampil;
use App\Models\TiketPeserta;
use App\Models\RekeningPembayaran; // <-- Model Rekening Tujuan ditambahkan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PerformanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Penampil::where('is_active', true);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_penampil', 'like', '%' . $request->search . '%')
                    ->orWhere('lokasi_tampil', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori_penampil', $request->kategori);
        }

        $dataPenampil = $query->orderBy('tanggal_tampil', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->paginate(12)
            ->withQueryString();

        return view('performance.index', compact('dataPenampil'));
    }

    public function show($slug)
    {
        $semuaPenampil = Penampil::where('is_active', true)->get();

        $penampil = $semuaPenampil->first(function ($item) use ($slug) {
            return Str::slug($item->nama_penampil) === $slug;
        });

        if (!$penampil) {
            abort(404, 'Data Penampil tidak ditemukan.');
        }

        // Cek kepemilikan tiket user yang sedang login
        $tiketSaya = null;
        if (Auth::check()) {
            $tiketSaya = TiketPeserta::where('user_id', Auth::id())
                ->where('penampil_id', $penampil->id)
                ->first();
        }

        // Ambil Data Rekening & QRIS dari database (Hanya yang Aktif)
        $rekenings = RekeningPembayaran::where('is_active', 1)->get();

        // Teruskan data rekenings ke view
        return view('performance.show', compact('penampil', 'tiketSaya', 'rekenings'));
    }

    /**
     * Proses Pendaftaran & Upload Bukti (Jika Berbayar)
     */
    public function daftarTiket(Request $request, $slug) 
    {
        // 1. Cari data penampil berdasarkan slug
        $semuaPenampil = Penampil::where('is_active', true)->get();
        $penampil = $semuaPenampil->first(function ($item) use ($slug) {
            return Str::slug($item->nama_penampil) === $slug;
        });

        // Jika tidak ditemukan, batalkan
        if (!$penampil) {
            abort(404, 'Data Penampil tidak ditemukan.');
        }

        $user = Auth::user();

        // 2. Validasi Upload jika event berbayar
        if ($penampil->harga_tiket > 0 && strtolower($penampil->tipe_pendaftaran) !== 'gratis') {
            $request->validate([
                'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:3072' // Diperbarui menjadi 3MB agar selaras
            ], [
                'bukti_pembayaran.required' => 'Anda wajib mengunggah bukti pembayaran.',
                'bukti_pembayaran.image' => 'File harus berupa gambar (JPG/PNG).',
                'bukti_pembayaran.max' => 'Ukuran file maksimal 3MB.'
            ]);
        }

        // 3. Cek apakah user sudah punya tiket ini
        $sudahPunya = TiketPeserta::where('user_id', $user->id)->where('penampil_id', $penampil->id)->exists();
        if ($sudahPunya) {
            return redirect()->back()->withErrors(['Anda sudah terdaftar di acara ini.']);
        }

        // 4. Generate Kode Unik
        do {
            $kodeTiket = 'KM16' . strtoupper(Str::random(5)) . 'DGI';
        } while (TiketPeserta::where('kode_tiket', $kodeTiket)->exists());

        $status = 'Aktif';
        $buktiPath = null;

        // 5. Jika berbayar, simpan gambar dan ubah status
        if ($penampil->harga_tiket > 0 && $request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_tiket', 'public');
            $status = 'Menunggu Konfirmasi';
        }

        // 6. Simpan Tiket
        TiketPeserta::create([
            'user_id'          => $user->id,
            'penampil_id'      => $penampil->id,
            'jenis_tiket'      => 'Performance',
            'kode_tiket'       => $kodeTiket,
            'status'           => $status,
            'bukti_pembayaran' => $buktiPath,
        ]);

        $pesan = $status === 'Aktif'
            ? 'Berhasil! Anda telah terdaftar. Tiket elektronik Anda telah diterbitkan di Dashboard.'
            : 'Pendaftaran berhasil diajukan! Panitia akan memverifikasi bukti pembayaran Anda segera.';

        return redirect()->route('dashboard')->with('success', $pesan);
    }
}