<?php

namespace App\Http\Controllers;

use App\Models\BoothDelegasi;
use App\Models\BoothVote;
use App\Models\EdisiKmdgi;
use App\Models\Kampus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PublicBoothVotingController extends Controller
{
    private function getVotingStatus()
    {
        $waktuBuka         = Carbon::parse(Cache::get('booth_waktu_buka', '2026-11-09T08:00'));
        $waktuTutup        = Carbon::parse(Cache::get('booth_waktu_tutup', '2026-11-10T12:00'));
        $waktuAwarding     = Carbon::parse(Cache::get('booth_waktu_awarding', '2026-11-11T19:00'));
        $isManualClosed    = Cache::get('booth_manual_closed', false);
        $isVotingPublished = Cache::get('booth_voting_published', false);

        $now = now();
        $isOpen = $isVotingPublished && !$isManualClosed && $now->between($waktuBuka, $waktuTutup);

        return compact('waktuBuka', 'waktuTutup', 'waktuAwarding', 'isManualClosed', 'isVotingPublished', 'isOpen', 'now');
    }

    /**
     * Cek apakah user sudah pernah memberikan 1 suara (Murni berdasarkan user_id di tabel booth_votes)
     */
    private function getExistingVote()
    {
        if (!Auth::check()) {
            return null;
        }

        return BoothVote::where('user_id', Auth::id())->first();
    }

    /**
     * Cari data Booth berdasarkan Slug Nama Kampus (contoh: universitas-pendidikan-indonesia)
     * Tanpa eager load seluruh votes sekaligus agar tetap ringan (hanya ambil 25 data saat diperlukan)
     */
    private function findBoothBySlug($slug)
    {
        $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();

        $semuaBooth = BoothDelegasi::withCount('votes')
            ->where('status_draft', 0)
            ->where('status_verifikasi', 'Terverifikasi')
            ->when($edisiAktif, fn($q) => $q->where('edisi_kmdgi_id', $edisiAktif->id))
            ->get();

        return $semuaBooth->first(function ($item) use ($slug) {
            return Str::slug($item->institusi) === $slug;
        });
    }

    /**
     * Halaman Katalog Semua Booth Tematik (/voting)
     */
    public function index(Request $request)
    {
        $status = $this->getVotingStatus();
        $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();

        $booths = collect();
        $kampusLogos = collect();

        if ($status['isVotingPublished']) {
            $query = BoothDelegasi::withCount('votes')
                ->where('status_draft', 0)
                ->where('status_verifikasi', 'Terverifikasi')
                ->when($edisiAktif, fn($q) => $q->where('edisi_kmdgi_id', $edisiAktif->id));

            if ($request->filled('search')) {
                $s = $request->search;
                $query->where(function ($q) use ($s) {
                    $q->where('institusi', 'like', "%{$s}%")
                      ->orWhere('judul_karya_tematik', 'like', "%{$s}%");
                });
            }

            $booths = $query->orderBy('institusi', 'asc')->get();
            $kampusLogos = Kampus::whereIn('nama_institusi', $booths->pluck('institusi'))->pluck('logo_institusi', 'nama_institusi');
        }

        $existingVote = $this->getExistingVote();
        $votedBoothId = $existingVote ? $existingVote->booth_delegasi_id : null;

        return view('voting.booth.index', compact('booths', 'kampusLogos', 'status', 'existingVote', 'votedBoothId'));
    }

    /**
     * Halaman Spesifik Booth Berdasarkan Slug Nama Kampus (/voting/{slug})
     */
    public function show(Request $request, $slug)
    {
        $status = $this->getVotingStatus();
        $booth = $this->findBoothBySlug($slug);

        if (!$booth) {
            abort(404, 'Booth delegasi kampus tidak ditemukan atau belum terverifikasi.');
        }

        // Jika Admin belum mengaktifkan toggle rilis serentak, hanya Admin yang bisa pratinjau
        if (!$status['isVotingPublished'] && (!Auth::check() || !in_array(Auth::user()->role, ['super admin', 'admin']))) {
            abort(403, 'Halaman voting Most Popular Artwork belum dibuka oleh panitia.');
        }

        // Jika request AJAX untuk Load More (25 data per halaman)
        if ($request->boolean('ajax_voters')) {
            $page = max(1, (int) $request->get('page', 1));
            $paginator = $booth->votes()
                ->select(['id', 'booth_delegasi_id', 'user_id', 'created_at'])
                ->with(['user:id,name,institusi,kategori'])
                ->latest()
                ->paginate(25, ['*'], 'page', $page);

            $items = $paginator->getCollection()->map(function ($v) {
                $name = $v->user->name ?? 'Pengunjung KMDGI';
                $words = preg_split('/\s+/', trim($name), -1, PREG_SPLIT_NO_EMPTY);
                $initials = strtoupper(substr($words[0] ?? 'P', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));

                return [
                    'name'      => $name,
                    'initials'  => $initials,
                    'institusi' => $v->user->institusi ?? ($v->user->kategori ?? 'Umum'),
                    'waktu'     => $v->created_at ? $v->created_at->translatedFormat('d M Y, H:i') : '',
                ];
            })->values();

            return response()->json([
                'data'      => $items,
                'has_more'  => $paginator->hasMorePages(),
                'next_page' => $paginator->currentPage() + 1,
                'total'     => $paginator->total(),
            ]);
        }

        // Default: hanya muat 25 pemilih terbaru agar ringan
        $initialVotes = $booth->votes()
            ->select(['id', 'booth_delegasi_id', 'user_id', 'created_at'])
            ->with(['user:id,name,institusi,kategori'])
            ->latest()
            ->take(25)
            ->get();

        $logoKampus = Kampus::where('nama_institusi', $booth->institusi)->value('logo_institusi');
        $existingVote = $this->getExistingVote();
        $votedBoothId = $existingVote ? $existingVote->booth_delegasi_id : null;

        return view('voting.booth.show', compact('booth', 'initialVotes', 'logoKampus', 'status', 'existingVote', 'votedBoothId'));
    }

    /**
     * Eksekusi Pemberian 1 Suara (/voting/{slug}/vote)
     */
    public function storeVote(Request $request, $slug)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['Silakan login terlebih dahulu untuk memberikan suara.']);
        }

        $status = $this->getVotingStatus();

        if (!$status['isVotingPublished']) {
            return redirect()->back()->withErrors(['Sesi voting belum dirilis oleh panitia.']);
        }

        if (!$status['isOpen']) {
            if ($status['now']->lt($status['waktuBuka'])) {
                return redirect()->back()->withErrors([
                    'Sesi voting baru akan dibuka pada ' . $status['waktuBuka']->translatedFormat('d F Y \p\u\k\u\l H:i') . ' WIB.'
                ]);
            }
            return redirect()->back()->withErrors([
                'Sesi voting Most Popular Artwork telah ditutup pada ' . $status['waktuTutup']->translatedFormat('d F Y \p\u\k\u\l H:i') . ' WIB.'
            ]);
        }

        $booth = $this->findBoothBySlug($slug);
        if (!$booth) {
            abort(404, 'Booth delegasi tidak ditemukan.');
        }

        // Cek apakah user_id ini sudah ada di tabel booth_votes
        $existingVote = $this->getExistingVote();
        if ($existingVote) {
            return redirect()->back()->withErrors(['Akun Anda sudah pernah memberikan satu suara pada periode voting ini.']);
        }

        BoothVote::create([
            'booth_delegasi_id' => $booth->id,
            'user_id'           => Auth::id(),
            'ip_address'        => $request->ip(),
            'user_agent'        => $request->userAgent(),
        ]);

        // Sinkronisasi kolom votes_count
        $totalVotes = BoothVote::where('booth_delegasi_id', $booth->id)->count();
        $booth->update(['votes_count' => $totalVotes]);

        return redirect()->back()->with('vote_success', true);
    }
}
