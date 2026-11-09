<?php

namespace App\Http\Controllers;

use App\Models\SubmisiKarya;
use App\Models\BoothDelegasi;
use App\Models\BoothVote;
use App\Models\EdisiKmdgi;
use App\Models\KaryaKomentar;
use App\Models\KaryaLike;
use App\Models\Kampus;
use App\Notifications\GeneralNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class KatalogKaryaController extends Controller
{
    /**
     * Mengambil status jadwal voting & data klasemen Top Vote Karya Tematik (Murni via user_id)
     */
    private function getTematikLeaderboardData(Request $request)
    {
        $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();

        $waktuBuka         = Carbon::parse(Cache::get('booth_waktu_buka', '2026-11-09T08:00'));
        $waktuTutup        = Carbon::parse(Cache::get('booth_waktu_tutup', '2026-11-10T12:00'));
        $isManualClosed    = Cache::get('booth_manual_closed', false);
        
        // KRUSIAL: Default adalah FALSE. Jika Admin belum mempublikasikan serentak, data ditutup!
        $isVotingPublished = (bool) Cache::get('booth_voting_published', false);

        $now = now();
        $isVotingOpen = $isVotingPublished && !$isManualClosed && $now->between($waktuBuka, $waktuTutup);

        // Cek murni dari tabel booth_votes berdasarkan user_id yang sedang login
        $votedBoothId = Auth::check()
            ? BoothVote::where('user_id', Auth::id())->value('booth_delegasi_id')
            : null;

        // Jika Admin belum mempublikasikan voting kepada umum, kosongkan data klasemen
        if (!$isVotingPublished) {
            $leaderboardBooths = collect();
            $totalSuaraTematik = 0;
            $totalKaryaTematik = 0;
        } else {
            $leaderboardBooths = BoothDelegasi::withCount('votes')
                ->where('status_draft', 0)
                ->where('status_verifikasi', 'Terverifikasi')
                ->when($edisiAktif, fn($q) => $q->where('edisi_kmdgi_id', $edisiAktif->id))
                ->orderBy('votes_count', 'desc')
                ->orderBy('updated_at', 'asc')
                ->get();

            $totalSuaraTematik = $leaderboardBooths->sum('votes_count');
            $totalKaryaTematik = $leaderboardBooths->count();
        }

        return compact(
            'waktuBuka',
            'waktuTutup',
            'isVotingOpen',
            'isVotingPublished',
            'votedBoothId',
            'leaderboardBooths',
            'totalSuaraTematik',
            'totalKaryaTematik'
        );
    }

    public function index(Request $request)
    {
        $viewMode = $request->input('view', 'karya'); // default: karya
        $kategori = $request->input('kategori', 'all');
        $tematikData = $this->getTematikLeaderboardData($request);

        // =========================================================================
        // JIKA FILTER KATEGORI ADALAH "Tematik", CEK APAKAH SUDAH DIRILIS OLEH ADMIN
        // =========================================================================
        if (strtolower($kategori) === 'tematik') {
            // Jika Admin belum menentukan karya dipublish ke umum, tutup datanya
            if (!$tematikData['isVotingPublished']) {
                $totalKarya = 0;
                $kampusData = collect();

                if ($viewMode === 'kampus') {
                    $groupedKaryas = collect();
                    return view('katalog.karya.index', array_merge(
                        compact('groupedKaryas', 'kampusData', 'viewMode', 'kategori', 'totalKarya'),
                        $tematikData
                    ));
                } else {
                    $karyas = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);
                    return view('katalog.karya.index', array_merge(
                        compact('karyas', 'kampusData', 'viewMode', 'kategori', 'totalKarya'),
                        $tematikData
                    ));
                }
            }

            $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();

            $query = BoothDelegasi::with(['user'])
                ->withCount('votes')
                ->where('status_draft', 0)
                ->where('status_verifikasi', 'Terverifikasi')
                ->when($edisiAktif, fn($q) => $q->where('edisi_kmdgi_id', $edisiAktif->id));

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('judul_karya_tematik', 'like', "%{$search}%")
                      ->orWhere('kreator_karya', 'like', "%{$search}%")
                      ->orWhere('institusi', 'like', "%{$search}%");
                });
            }

            if ($request->sort == 'terpopuler') {
                $query->orderBy('votes_count', 'desc');
            } else {
                $query->latest();
            }

            $totalKarya = $query->count();

            if ($viewMode === 'kampus') {
                $allBooths = $query->get();
                $groupedKaryas = $allBooths->groupBy(fn($item) => $item->institusi ?? 'Umum');
                $kampusData = Kampus::whereIn('nama_institusi', $groupedKaryas->keys())->get()->keyBy('nama_institusi');

                return view('katalog.karya.index', array_merge(
                    compact('groupedKaryas', 'kampusData', 'viewMode', 'kategori', 'totalKarya'),
                    $tematikData
                ));
            } else {
                $karyas = $query->paginate(12)->withQueryString();
                $namaInstitusiList = $karyas->pluck('institusi')->filter()->unique();
                $kampusData = Kampus::whereIn('nama_institusi', $namaInstitusiList)->get()->keyBy('nama_institusi');

                return view('katalog.karya.index', array_merge(
                    compact('karyas', 'kampusData', 'viewMode', 'kategori', 'totalKarya'),
                    $tematikData
                ));
            }
        }

        // =========================================================================
        // MODE REGULER (Semua Kategori, Simbolik, Simbiotik)
        // =========================================================================
        $query = SubmisiKarya::with(['user'])
            ->withCount('komentars')
            ->withCount('likes')
            ->where('status_verifikasi', 'Terverifikasi')
            ->where('status_draft', 0);

        // Jika Admin belum mempublikasikan Tematik secara serentak, sembunyikan juga Tematik dari "Semua Kategori"
        if (!$tematikData['isVotingPublished']) {
            $query->whereRaw('LOWER(kategori_karya) != ?', ['tematik']);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_karya', 'like', "%{$search}%")
                  ->orWhere('kreator_karya', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('institusi', 'like', "%{$search}%");
                  });
            });
        }

        if ($kategori !== 'all') {
            $query->where('kategori_karya', $kategori);
        }

        if ($request->sort == 'terpopuler') {
            $query->orderBy('likes_count', 'desc');
        } else {
            $query->latest();
        }

        $totalKarya = $query->count();

        if ($viewMode === 'kampus') {
            $allKaryas = $query->get();
            $groupedKaryas = $allKaryas->groupBy(fn($item) => $item->user->institusi ?? 'Umum');
            $kampusData = Kampus::whereIn('nama_institusi', $groupedKaryas->keys())->get()->keyBy('nama_institusi');

            return view('katalog.karya.index', array_merge(
                compact('groupedKaryas', 'kampusData', 'viewMode', 'kategori', 'totalKarya'),
                $tematikData
            ));
        } else {
            $karyas = $query->paginate(12)->withQueryString();
            $namaInstitusiList = $karyas->pluck('user.institusi')->filter()->unique();
            $kampusData = Kampus::whereIn('nama_institusi', $namaInstitusiList)->get()->keyBy('nama_institusi');

            return view('katalog.karya.index', array_merge(
                compact('karyas', 'kampusData', 'viewMode', 'kategori', 'totalKarya'),
                $tematikData
            ));
        }
    }

    /**
     * Menampilkan Detail Karya (Menghindari 404 pada Sesi Booth yang Sedang Dikurasi / Voting Belum Dibuka)
     */
    public function show(Request $request, $slug)
    {
        $tematikData = $this->getTematikLeaderboardData($request);
        $isAdmin = Auth::check() && in_array(Auth::user()->role, ['super admin', 'admin']);
        $isFromVotingRoute = $request->routeIs('voting.booth.*') || str_starts_with($request->path(), 'voting');

        // 1. Cari di seluruh BoothDelegasi (termasuk yang masih Draft / Menunggu Kurasi agar tidak 404 saat QR di-scan)
        $semuaBooth = BoothDelegasi::with(['user'])
            ->withCount('votes')
            ->get();

        $booth = $semuaBooth->first(function ($item) use ($slug) {
            return Str::slug($item->institusi) === $slug || ($item->judul_karya_tematik && Str::slug($item->judul_karya_tematik) === $slug);
        });

        if ($booth) {
            $sudahSiapTampil = ($booth->status_draft == 0 && $booth->status_verifikasi === 'Terverifikasi' && $tematikData['isVotingPublished']);

            // PROTEKSI: Jika belum diverifikasi Admin ATAU sesi voting belum dibuka serentak (dan bukan Admin yang login)
            if (!$sudahSiapTampil && !$isAdmin) {
                return response()->view('katalog.karya.kurasi', [
                    'namaInstitusi' => $booth->institusi,
                    'judulBooth'    => $booth->judul_karya_tematik,
                    'waktuBuka'     => $tematikData['waktuBuka'],
                    'waktuTutup'    => $tematikData['waktuTutup'],
                ]);
            }

            $isBoothTematik = true;

            $karya = $booth;
            $karya->judul_karya     = $booth->judul_karya_tematik;
            $karya->kategori_karya  = 'Tematik';
            $karya->thumbnail_karya = $booth->foto_utama;
            $karya->media_karya     = array_values(array_filter([$booth->foto_pendukung_1, $booth->foto_pendukung_2]));
            $karya->deskripsi_karya = $booth->deskripsi_singkat;
            $karya->likes_count     = $booth->votes_count;

            $komentars = KaryaKomentar::with(['user', 'replies.user'])
                ->where('booth_delegasi_id', $booth->id)
                ->whereNull('parent_id')
                ->latest()
                ->get();

            $kampusObj = Kampus::where('nama_institusi', $booth->institusi)->first();
            $logoKampus = $kampusObj->logo_institusi ?? null;
            $kotaKampus = $kampusObj->lokasi_kota ?? 'Indonesia';
            $igKampus   = $kampusObj->medsos_kampus ?? null;

            $karyaLainnya = $tematikData['isVotingPublished']
                ? BoothDelegasi::with(['user'])
                    ->withCount('votes')
                    ->where('status_verifikasi', 'Terverifikasi')
                    ->where('status_draft', 0)
                    ->where('id', '!=', $booth->id)
                    ->inRandomOrder()
                    ->take(3)
                    ->get()
                : collect();

            $namaInstitusiLain = $karyaLainnya->pluck('institusi')->filter()->unique();
            $kampusLogosLain = Kampus::whereIn('nama_institusi', $namaInstitusiLain)->pluck('logo_institusi', 'nama_institusi');

            return view('katalog.karya.show', array_merge(
                compact('karya', 'isBoothTematik', 'komentars', 'logoKampus', 'kotaKampus', 'igKampus', 'karyaLainnya', 'kampusLogosLain'),
                $tematikData
            ));
        }

        // 2. Jika slug adalah nama Kampus terdaftar yang belum mengunggah Booth atau diakses via /voting/{slug}
        $kampusTerdaftar = Kampus::all()->first(fn($k) => Str::slug($k->nama_institusi) === $slug);
        if ($kampusTerdaftar || $isFromVotingRoute) {
            return response()->view('katalog.karya.kurasi', [
                'namaInstitusi' => $kampusTerdaftar->nama_institusi ?? null,
                'judulBooth'    => null,
                'waktuBuka'     => $tematikData['waktuBuka'],
                'waktuTutup'    => $tematikData['waktuTutup'],
            ]);
        }

        // 3. Jika bukan BoothDelegasi, cari di SubmisiKarya reguler (termasuk cek jika masih dikurasi)
        $semuaKarya = SubmisiKarya::with(['user'])->withCount('likes')->get();

        $karya = $semuaKarya->first(function ($item) use ($slug) {
            return Str::slug($item->judul_karya) === $slug;
        });

        if (!$karya) {
            abort(404, 'Karya tidak ditemukan atau belum dipublikasikan.');
        }

        $karyaRegulerSiap = ($karya->status_draft == 0 && $karya->status_verifikasi === 'Terverifikasi');
        $isTematikBelumBuka = (strtolower($karya->kategori_karya) === 'tematik' && !$tematikData['isVotingPublished']);

        if ((!$karyaRegulerSiap || $isTematikBelumBuka) && !$isAdmin) {
            return response()->view('katalog.karya.kurasi', [
                'namaInstitusi' => $karya->user->institusi ?? null,
                'judulBooth'    => $karya->judul_karya,
                'waktuBuka'     => $tematikData['waktuBuka'],
                'waktuTutup'    => $tematikData['waktuTutup'],
            ]);
        }

        $isBoothTematik = false;
        $karya->increment('views_count');

        $komentars = KaryaKomentar::with(['user', 'replies.user'])
            ->where('submisi_karya_id', $karya->id)
            ->whereNull('parent_id')
            ->latest()
            ->get();

        $kampusObj = Kampus::where('nama_institusi', $karya->user->institusi)->first();
        $logoKampus = $kampusObj->logo_institusi ?? null;
        $kotaKampus = $kampusObj->lokasi_kota ?? 'Indonesia';
        $igKampus   = $kampusObj->medsos_kampus ?? null;

        $karyaLainnya = SubmisiKarya::with(['user'])
            ->withCount(['komentars', 'likes'])
            ->where('status_verifikasi', 'Terverifikasi')
            ->where('status_draft', 0)
            ->where('id', '!=', $karya->id)
            ->when(!$tematikData['isVotingPublished'], fn($q) => $q->whereRaw('LOWER(kategori_karya) != ?', ['tematik']))
            ->inRandomOrder()
            ->take(3)
            ->get();

        $namaInstitusiLain = $karyaLainnya->pluck('user.institusi')->filter()->unique();
        $kampusLogosLain = Kampus::whereIn('nama_institusi', $namaInstitusiLain)->pluck('logo_institusi', 'nama_institusi');

        return view('katalog.karya.show', array_merge(
            compact('karya', 'isBoothTematik', 'komentars', 'logoKampus', 'kotaKampus', 'igKampus', 'karyaLainnya', 'kampusLogosLain'),
            $tematikData
        ));
    }

    /**
     * Eksekusi Pemberian Vote Booth Tematik (Wajib Login & Murni via user_id)
     */
    public function voteBooth(Request $request, $id)
    {
        if (!Auth::check()) {
            $msg = 'Silakan login terlebih dahulu untuk memberikan suara.';
            return $request->expectsJson()
                ? response()->json(['success' => false, 'require_login' => true, 'message' => $msg], 401)
                : redirect()->route('login')->withErrors([$msg]);
        }

        $tematikData = $this->getTematikLeaderboardData($request);

        if (!$tematikData['isVotingPublished']) {
            $msg = 'Karya Tematik belum dipublikasikan untuk sesi voting umum karena masih dalam tahap verifikasi Admin.';
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg], 403)
                : redirect()->back()->withErrors([$msg]);
        }

        if (!$tematikData['isVotingOpen']) {
            $msg = 'Sesi voting belum dibuka atau telah melewati batas waktu.';
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg], 403)
                : redirect()->back()->withErrors([$msg]);
        }

        if ($tematikData['votedBoothId']) {
            $msg = 'Setiap akun hanya dapat memberikan satu suara.';
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : redirect()->back()->withErrors([$msg]);
        }

        $booth = BoothDelegasi::where('status_draft', 0)
            ->where('status_verifikasi', 'Terverifikasi')
            ->findOrFail($id);

        BoothVote::create([
            'booth_delegasi_id' => $booth->id,
            'user_id'           => Auth::id(),
        ]);

        $totalVotes = BoothVote::where('booth_delegasi_id', $booth->id)->count();
        $booth->update(['votes_count' => $totalVotes]);

        if ($request->expectsJson()) {
            return response()->json([
                'success'  => true,
                'votes'    => $totalVotes,
                'booth_id' => $booth->id,
                'message'  => 'Suaramu telah terekam, terima kasih!'
            ]);
        }

        return redirect()->back()->with('vote_success', true);
    }

    public function voteBoothBySlug(Request $request, $slug)
    {
        $booth = BoothDelegasi::where('status_draft', 0)
            ->where('status_verifikasi', 'Terverifikasi')
            ->get()
            ->first(fn($item) => Str::slug($item->institusi) === $slug || Str::slug($item->judul_karya_tematik) === $slug);

        if (!$booth) abort(404);
        return $this->voteBooth($request, $booth->id);
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

                if ($karya->user_id !== $userId) {
                    $likerName = Auth::user()->name;
                    $targetUrl = route('katalog.karya.show', Str::slug($karya->judul_karya));

                    $karya->user->notify(new GeneralNotification(
                        'Karya Anda Disukai',
                        "Karya Anda \"{$karya->judul_karya}\" baru saja disukai oleh {$likerName}.",
                        'info',
                        $targetUrl
                    ));
                }
            }
        } else {
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
            'likes'   => KaryaLike::where('submisi_karya_id', $karya->id)->count(),
            'status'  => $status
        ]);
    }

    public function recordShare($id)
    {
        $karya = SubmisiKarya::find($id);
        if ($karya) {
            $karya->increment('shares_count');
            return response()->json(['success' => true, 'shares_count' => $karya->shares_count]);
        }
        return response()->json(['success' => true, 'shares_count' => 1]);
    }
}