<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TiketPeserta;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // <-- WAJIB IMPORT CARBON

class ScanQRController extends Controller
{
    // Menampilkan Halaman Scanner Fullscreen
    public function index()
    {
        return view('admin.scan_qr.index');
    }

    // Memproses Data Hasil Scan via AJAX (Scanner & Input Manual)
    public function process(Request $request)
    {
        $request->validate([
            'kode_tiket' => 'required|string'
        ]);

        $kodeTiket = strtoupper(trim($request->kode_tiket));
        
        // Cari tiket berdasarkan kode beserta relasinya
        $tiket = TiketPeserta::with(['user', 'event', 'penampil'])->where('kode_tiket', $kodeTiket)->first();

        // 1. Validasi: Tiket tidak ditemukan
        if (!$tiket) {
            return response()->json(['success' => false, 'message' => 'Tiket dengan kode ' . $kodeTiket . ' tidak ditemukan di sistem.']);
        }

        // 2. Validasi: Tiket belum dibayar/diverifikasi
        if ($tiket->status !== 'Aktif') {
            return response()->json(['success' => false, 'message' => 'Tiket ini berstatus "' . $tiket->status . '". Pembayaran belum diverifikasi.']);
        }

        // 3. Validasi: Tiket sudah di-scan sebelumnya (Peserta sudah masuk)
        if (!is_null($tiket->waktu_kehadiran)) {
            return response()->json(['success' => false, 'message' => 'Tiket ini sudah digunakan untuk masuk pada ' . date('d M Y, H:i', strtotime($tiket->waktu_kehadiran)) . '.']);
        }

        // 4. VALIDASI WAKTU ACARA & PERFORMANCE
        $now = Carbon::now();
        $namaAcara = 'KMDGI 16';

        if ($tiket->jenis_tiket === 'Event') {
            if (!$tiket->event) {
                return response()->json(['success' => false, 'message' => 'Data rincian event tidak ditemukan.']);
            }
            
            $namaAcara = $tiket->event->judul;
            $eventDate = Carbon::parse($tiket->event->tanggal_pelaksanaan);
            
            // Cek Kesesuaian Hari
            if ($now->format('Y-m-d') !== $eventDate->format('Y-m-d')) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Tiket Event tidak berlaku hari ini. Jadwal acara adalah ' . $eventDate->translatedFormat('d F Y') . '.'
                ]);
            }

            // Opsional: Jika Anda ingin membatasi scan masuk hanya 2 jam sebelum jam_pelaksanaan
            if ($tiket->event->jam_pelaksanaan) {
                $startTime = Carbon::parse($tiket->event->tanggal_pelaksanaan . ' ' . $tiket->event->jam_pelaksanaan)->subHours(2);
                if ($now->lt($startTime)) {
                    return response()->json(['success' => false, 'message' => 'Akses ditolak. Tiket hanya dapat di-scan 2 jam sebelum acara dimulai (' . $startTime->format('H:i') . ').']);
                }
            }

        } elseif ($tiket->jenis_tiket === 'Performance') {
            if (!$tiket->penampil) {
                return response()->json(['success' => false, 'message' => 'Data rincian penampil tidak ditemukan.']);
            }
            
            $namaAcara = 'Konser: ' . $tiket->penampil->nama_penampil;
            $perfDate = Carbon::parse($tiket->penampil->tanggal_tampil);
            
            // Cek Kesesuaian Hari
            if ($now->format('Y-m-d') !== $perfDate->format('Y-m-d')) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Tiket Performance tidak berlaku hari ini. Jadwal tampil adalah ' . $perfDate->translatedFormat('d F Y') . '.'
                ]);
            }
            
            // Cek Toleransi Jam Mulai & Jam Selesai
            if ($tiket->penampil->jam_mulai && $tiket->penampil->jam_selesai) {
                $startTime = Carbon::parse($tiket->penampil->tanggal_tampil . ' ' . $tiket->penampil->jam_mulai)->subHours(2);
                $endTime = Carbon::parse($tiket->penampil->tanggal_tampil . ' ' . $tiket->penampil->jam_selesai);
                
                if ($now->lt($startTime) || $now->gt($endTime)) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Sesi belum dimulai atau sudah berakhir. Waktu scan valid: ' . $startTime->format('H:i') . ' s/d ' . $endTime->format('H:i')
                    ]);
                }
            }

        } elseif ($tiket->jenis_tiket === 'Pameran') {
            $namaAcara = 'Pameran KMDGI 16';
            // Tiket Pameran dapat diakses kapan saja, tidak ada validasi waktu.
        }

        // JIKA SEMUA VALID -> TANDAI SEBAGAI HADIR
        $tiket->waktu_kehadiran = $now;
        $tiket->save();

        // Catat ke Log Aktivitas
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Scan Kehadiran',
            'aksi'       => 'Validasi Masuk',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' memvalidasi tiket masuk (' . $kodeTiket . ') milik ' . ($tiket->user->name ?? 'User') . '.',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'nama_peserta' => $tiket->user->name ?? 'Anonim',
                'institusi'    => $tiket->user->institusi ?? 'Umum',
                'kode_tiket'   => $tiket->kode_tiket,
                'jenis_tiket'  => $tiket->jenis_tiket,
                'nama_acara'   => $namaAcara,
                'waktu'        => $now->format('d M Y, H:i:s')
            ]
        ]);
    }
}