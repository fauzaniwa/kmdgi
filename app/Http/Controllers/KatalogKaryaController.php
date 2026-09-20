<?php

namespace App\Http\Controllers;

use App\Models\SubmisiKarya;
use App\Models\KaryaKomentar;
use App\Models\KaryaLike;
use App\Models\Kampus;
use App\Notifications\GeneralNotification; // <-- [NOTIFIKASI] Import Notifikasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KatalogKaryaController extends Controller
{
    public function index(Request $request)
    {
        $query = SubmisiKarya::with(['user'])
            ->withCount('komentars')
            ->withCount('likes')
            ->where('status_verifikasi', 'Terverifikasi')
            ->where('status_draft', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_karya', 'like', "%{$search}%")
                  ->orWhere('kreator_karya', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('institusi', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->sort == 'terpopuler') {
            $query->orderBy('likes_count', 'desc');
        } else {
            $query->latest();
        }

        $karyas = $query->paginate(12)->withQueryString();

        $namaInstitusiList = $karyas->pluck('user.institusi')->filter()->unique();
        $kampusLogos = Kampus::whereIn('nama_institusi', $namaInstitusiList)->pluck('logo_institusi', 'nama_institusi');

        return view('katalog.karya.index', compact('karyas', 'kampusLogos'));
    }

    public function show($slug)
    {
        // Tarik semua karya yang sudah rilis
        $semuaKarya = SubmisiKarya::with(['user'])->withCount('likes')
            ->where('status_verifikasi', 'Terverifikasi')
            ->where('status_draft', 0)
            ->get();

        // Cari karya yang slug judulnya cocok dengan URL
        $karya = $semuaKarya->first(function ($item) use ($slug) {
            return Str::slug($item->judul_karya) === $slug;
        });
        
        if (!$karya) {
            abort(404, 'Karya tidak ditemukan atau belum dipublikasikan.');
        }

        // Catat Views
        $karya->increment('views_count');

        // Tarik Komentar + Balasan
        $komentars = KaryaKomentar::with(['user', 'replies.user'])
            ->where('submisi_karya_id', $karya->id)
            ->whereNull('parent_id')
            ->latest()
            ->get();

        $logoKampus = Kampus::where('nama_institusi', $karya->user->institusi)->value('logo_institusi');

        $karyaLainnya = SubmisiKarya::with(['user'])
            ->withCount(['komentars', 'likes'])
            ->where('status_verifikasi', 'Terverifikasi')
            ->where('status_draft', 0)
            ->where('id', '!=', $karya->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        $namaInstitusiLain = $karyaLainnya->pluck('user.institusi')->filter()->unique();
        $kampusLogosLain = Kampus::whereIn('nama_institusi', $namaInstitusiLain)->pluck('logo_institusi', 'nama_institusi');

        return view('katalog.karya.show', compact('karya', 'komentars', 'logoKampus', 'karyaLainnya', 'kampusLogosLain'));
    }

    public function like($id)
    {
        $karya = SubmisiKarya::findOrFail($id);
        $userId = Auth::id();
        $sessionKey = 'liked_karya_' . $karya->id;
        $status = '';

        if ($userId) {
            $existingLike = KaryaLike::where('submisi_karya_id', $karya->id)->where('user_id', $userId)->first();
            if ($existingLike) {
                $existingLike->delete();
                $status = 'unliked';
            } else {
                KaryaLike::create(['submisi_karya_id' => $karya->id, 'user_id' => $userId]);
                $status = 'liked';

                // ===========================================================================
                // [NOTIFIKASI] Beritahu Pemilik Karya jika di-Like oleh User Login
                // ===========================================================================
                if ($karya->user_id !== $userId) { // Jangan kirim notif jika like karya sendiri
                    $likerName = Auth::user()->name;
                    $targetUrl = route('katalog.karya.show', Str::slug($karya->judul_karya));

                    $karya->user->notify(new GeneralNotification(
                        'Karya Anda Disukai',
                        "Karya Anda \"{$karya->judul_karya}\" baru saja disukai oleh {$likerName}.",
                        'info',
                        $targetUrl
                    ));
                }
                // ===========================================================================
            }
        } else {
            // Logika untuk Guest (Tanpa Notifikasi)
            if (!session()->has($sessionKey)) {
                KaryaLike::create(['submisi_karya_id' => $karya->id, 'user_id' => null]);
                session()->put($sessionKey, true);
                $status = 'liked';
            } else {
                KaryaLike::where('submisi_karya_id', $karya->id)->whereNull('user_id')->latest()->delete();
                session()->forget($sessionKey);
                $status = 'unliked';
            }
        }

        return response()->json([
            'success' => true, 
            'likes' => KaryaLike::where('submisi_karya_id', $karya->id)->count(), 
            'status' => $status
        ]);
    }

    public function recordShare($id)
    {
        $karya = SubmisiKarya::findOrFail($id);
        $karya->increment('shares_count');

        // Secara UX, membagikan (Share) biasanya tidak memicu Notifikasi ke pemilik karya 
        // karena hanya klik tombol (Guest pun bisa). Namun jika ingin, Anda bisa tambahkan di sini.

        return response()->json([
            'success' => true,
            'shares_count' => $karya->shares_count
        ]);
    }
}