<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventKmdgi;
use App\Models\EdisiKmdgi;
use App\Models\TiketPeserta;
use App\Models\RekeningPembayaran;
use App\Models\User; // <-- Tambahan untuk mengambil data Admin
use App\Notifications\GeneralNotification; // <-- Tambahan untuk Notifikasi
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification; // <-- Tambahan untuk Notifikasi Massal
use Illuminate\Support\Str;

class KatalogEventController extends Controller
{
    /**
     * Halaman Katalog Acara
     */
    public function index(Request $request)
    {
        $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();

        // Siapkan Query Event
        $query = EventKmdgi::query();

        if ($edisiAktif) {
            $query->where('edisi_kmdgi_id', $edisiAktif->id);
        }

        // Fitur Pencarian Acara
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }

        $events = $query->latest()->paginate(12)->withQueryString();

        // CEK KEPEMILIKAN TIKET: Kumpulkan ID event mana saja yang sudah dimiliki tiketnya oleh user login
        $myEventIds = [];
        if (Auth::check()) {
            $myEventIds = TiketPeserta::where('user_id', Auth::id())
                ->whereNotNull('event_kmdgi_id')
                ->pluck('event_kmdgi_id')
                ->toArray();
        }

        // Kirim variabel $myEventIds ke view
        return view('katalog-event.index', compact('events', 'edisiAktif', 'myEventIds'));
    }

    /**
     * Halaman Detail Event
     */
    public function show($slug)
    {
        $event = EventKmdgi::where('slug', $slug)->firstOrFail();

        $tiketSaya = null;
        if (Auth::check()) {
            $tiketSaya = TiketPeserta::where('user_id', Auth::id())->where('event_kmdgi_id', $event->id)->first();
        }

        $pesertaCount = TiketPeserta::where('event_kmdgi_id', $event->id)->count();
        $isFull = ($event->kuota > 0 && $pesertaCount >= $event->kuota);

        $isPast = false;
        if ($event->tanggal_pelaksanaan) {
            $waktuAcara = \Carbon\Carbon::parse($event->tanggal_pelaksanaan . ' ' . ($event->jam_pelaksanaan ?? '00:00:00'));
            $isPast = $waktuAcara->isPast();
        }

        // Ambil 2 event acak lainnya di edisi yang sama
        $eventLainnya = EventKmdgi::where('edisi_kmdgi_id', $event->edisi_kmdgi_id)
            ->where('id', '!=', $event->id)
            ->inRandomOrder()
            ->take(2)
            ->get();

        // Ambil Data Rekening & QRIS dari database (Hanya yang Aktif)
        $rekenings = RekeningPembayaran::where('is_active', 1)->get();

        return view('katalog-event.show', compact('event', 'tiketSaya', 'isFull', 'isPast', 'eventLainnya', 'rekenings'));
    }

    /**
     * Proses Pendaftaran & Upload Bukti (Jika Berbayar)
     */
    public function daftarTiket(Request $request, $id)
    {
        $event = EventKmdgi::findOrFail($id);
        $user = Auth::user();

        // Validasi Upload jika event berbayar
        if ($event->harga_tiket > 0) {
            $request->validate([
                'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:3072'
            ], [
                'bukti_pembayaran.required' => 'Anda wajib mengunggah bukti pembayaran.',
                'bukti_pembayaran.image' => 'File harus berupa gambar (JPG/PNG).',
                'bukti_pembayaran.max' => 'Ukuran file maksimal 3MB.'
            ]);
        }

        $sudahPunya = TiketPeserta::where('user_id', $user->id)->where('event_kmdgi_id', $event->id)->exists();
        if ($sudahPunya) return redirect()->back()->withErrors(['Anda sudah terdaftar di acara ini.']);

        // Generate Kode Unik
        do {
            $kodeTiket = 'KM16' . strtoupper(Str::random(5)) . 'DGI';
        } while (TiketPeserta::where('kode_tiket', $kodeTiket)->exists());

        $jenis = (stripos($event->judul, 'workshop') !== false) ? 'Workshop' : 'Seminar';

        $status = 'Aktif';
        $buktiPath = null;

        // Jika berbayar, simpan gambar dan ubah status
        if ($event->harga_tiket > 0 && $request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_tiket', 'public');
            $status = 'Menunggu Konfirmasi';
        }

        TiketPeserta::create([
            'user_id'          => $user->id,
            'event_kmdgi_id'   => $event->id,
            'jenis_tiket'      => $jenis,
            'kode_tiket'       => $kodeTiket,
            'status'           => $status,
            'bukti_pembayaran' => $buktiPath,
        ]);

        // ===========================================================================
        // [NOTIFIKASI] Pendaftaran Tiket
        // ===========================================================================
        if ($status === 'Aktif') {
            // Acara Gratis -> Tiket Langsung Aktif
            $user->notify(new GeneralNotification(
                'Tiket Acara Terbit',
                "Pendaftaran acara \"{$event->judul}\" berhasil. Tiket elektronik Anda telah diterbitkan dan siap digunakan.",
                'success',
                route('dashboard')
            ));
        } else {
            // Acara Berbayar -> Status Menunggu Konfirmasi
            
            // 1. Kirim notif ke Peserta
            $user->notify(new GeneralNotification(
                'Pembayaran Dalam Tinjauan',
                "Bukti pembayaran Anda untuk acara \"{$event->judul}\" sedang diverifikasi oleh panitia. Anda akan menerima notifikasi jika tiket sudah aktif.",
                'info',
                route('dashboard')
            ));

            // 2. Kirim notif peringatan ke Admin & Super Admin untuk melakukan verifikasi
            $admins = User::whereIn('role', ['super admin', 'admin'])->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new GeneralNotification(
                    'Verifikasi Pembayaran Tiket Baru',
                    "Peserta {$user->name} baru saja mengunggah bukti pembayaran tiket untuk acara \"{$event->judul}\". Silakan verifikasi pembayaran tersebut.",
                    'warning',
                    url('/admin/verifikasi/event') // Sesuaikan dengan route halaman verifikasi tiket admin Anda
                ));
            }
        }
        // ===========================================================================

        $pesan = $status === 'Aktif'
            ? 'Berhasil! Anda telah terdaftar. Tiket elektronik Anda telah diterbitkan di Dashboard.'
            : 'Pendaftaran berhasil diajukan! Panitia akan memverifikasi bukti pembayaran Anda segera.';

        return redirect()->route('dashboard')->with('success', $pesan);
    }
}