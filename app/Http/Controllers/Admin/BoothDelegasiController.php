<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoothDelegasi;
use App\Models\BoothVote;
use App\Models\EdisiKmdgi;
use App\Models\Kampus;
use App\Models\LogAktivitas;
use App\Notifications\GeneralNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BoothDelegasiController extends Controller
{
    public function index(Request $request)
    {
        $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();

        // Daftar booth yang didiskualifikasi disimpan di Cache permanen (tanpa menghapus votes_count)
        // Format: [ booth_id => ['alasan' => '...', 'waktu' => '...', 'admin' => '...'] ]
        $disqualifiedMap = Cache::get('booth_disqualified_map', []);
        $disqualifiedIds = array_map('intval', array_keys($disqualifiedMap));

        // =========================================================================
        // 0. ENDPOINT AJAX RINGAN: LOAD PEMILIH 25 DATA PER HALAMAN (?ajax_voters=1)
        // =========================================================================
        if ($request->boolean('ajax_voters')) {
            $perPage = 25;
            $page    = max(1, (int) $request->get('page', 1));
            $boothId = $request->get('booth_id');
            $keyword = trim((string) $request->get('q', ''));

            $votesQuery = BoothVote::select(['id', 'booth_delegasi_id', 'user_id', 'created_at'])
                ->with([
                    'user:id,name,email,no_hp,institusi,kategori,profesi',
                    'booth:id,institusi,judul_karya_tematik,edisi_kmdgi_id'
                ])
                ->whereHas('booth', function ($q) use ($edisiAktif) {
                    if ($edisiAktif) {
                        $q->where('edisi_kmdgi_id', $edisiAktif->id);
                    }
                });

            if (!empty($boothId)) {
                $votesQuery->where('booth_delegasi_id', $boothId);
            }

            if ($keyword !== '') {
                $votesQuery->where(function ($q) use ($keyword) {
                    $q->whereHas('user', function ($u) use ($keyword) {
                        $u->where('name', 'like', "%{$keyword}%")
                          ->orWhere('email', 'like', "%{$keyword}%")
                          ->orWhere('no_hp', 'like', "%{$keyword}%")
                          ->orWhere('institusi', 'like', "%{$keyword}%");
                    })->orWhereHas('booth', function ($b) use ($keyword) {
                        $b->where('institusi', 'like', "%{$keyword}%")
                          ->orWhere('judul_karya_tematik', 'like', "%{$keyword}%");
                    });
                });
            }

            $paginator = $votesQuery->latest()->paginate($perPage, ['*'], 'page', $page);

            $items = $paginator->getCollection()->map(function ($v) {
                return [
                    'name'      => $v->user->name ?? 'User Terhapus',
                    'email'     => $v->user->email ?? '-',
                    'no_hp'     => $v->user->no_hp ?? '-',
                    'institusi' => $v->user->institusi ?? '-',
                    'kategori'  => $v->user->kategori ?? 'Umum',
                    'profesi'   => $v->user->profesi ?? '-',
                    'booth'     => $v->booth->institusi ?? '-',
                    'judul'     => $v->booth->judul_karya_tematik ?? '-',
                    'waktu'     => $v->created_at ? $v->created_at->format('d M Y, H:i') . ' WIB' : '-',
                ];
            })->values();

            return response()->json([
                'data'         => $items,
                'current_page' => $paginator->currentPage(),
                'next_page'    => $paginator->currentPage() + 1,
                'has_more'     => $paginator->hasMorePages(),
                'total'        => $paginator->total(),
            ]);
        }

        $config = [
            'waktu_buka'          => Carbon::parse(Cache::get('booth_waktu_buka', '2026-11-09T08:00')),
            'waktu_tutup'         => Carbon::parse(Cache::get('booth_waktu_tutup', '2026-11-10T12:00')),
            'waktu_awarding'      => Carbon::parse(Cache::get('booth_waktu_awarding', '2026-11-11T19:00')),
            'is_manual_closed'    => Cache::get('booth_manual_closed', false),
            'is_voting_published' => Cache::get('booth_voting_published', false), // <-- Master Toggle Rilis Serentak
        ];

        $now = now();
        $isVotingOpen = $config['is_voting_published'] && !$config['is_manual_closed'] && $now->between($config['waktu_buka'], $config['waktu_tutup']);

        // Query utama tabel Booth (ringan, hanya menghitung jumlah votes tanpa memuat ribuan baris vote sekaligus)
        $query = BoothDelegasi::with(['user'])
            ->withCount('votes')
            ->when($edisiAktif, function ($q) use ($edisiAktif) {
                $q->where('edisi_kmdgi_id', $edisiAktif->id);
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('institusi', 'like', "%{$search}%")
                    ->orWhere('judul_karya_tematik', 'like', "%{$search}%")
                    ->orWhere('kreator_karya', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'Draft') {
                $query->where('status_draft', 1);
            } elseif ($request->status === 'Diskualifikasi') {
                $query->where('status_draft', 0)->whereIn('id', !empty($disqualifiedIds) ? $disqualifiedIds : [0]);
            } elseif ($request->status === 'Terverifikasi') {
                $query->where('status_draft', 0)
                      ->where('status_verifikasi', 'Terverifikasi')
                      ->when(!empty($disqualifiedIds), fn($q) => $q->whereNotIn('id', $disqualifiedIds));
            } else {
                $query->where('status_draft', 0)->where('status_verifikasi', $request->status);
            }
        }

        $sort = $request->get('sort', 'terbanyak');
        if ($sort === 'terbaru') {
            $query->orderBy('updated_at', 'desc');
        } elseif ($sort === 'terlama') {
            $query->orderBy('updated_at', 'asc');
        } else {
            $query->orderBy('votes_count', 'desc')->orderBy('updated_at', 'asc');
        }

        // =========================================================================
        // 1. EXPORT EXCEL DAFTAR PEMILIH & KONTAK (?export=voters)
        // =========================================================================
        if ($request->get('export') === 'voters') {
            $boothId = $request->get('booth_id');
            $keyword = trim((string) $request->get('q', ''));
            $boothFilter = null;

            $votesQuery = BoothVote::with([
                'user:id,name,email,no_hp,institusi,kategori,profesi',
                'booth:id,institusi,judul_karya_tematik,edisi_kmdgi_id'
            ])->whereHas('booth', function ($q) use ($edisiAktif) {
                if ($edisiAktif) {
                    $q->where('edisi_kmdgi_id', $edisiAktif->id);
                }
            });

            if (!empty($boothId)) {
                $boothFilter = BoothDelegasi::find($boothId);
                $votesQuery->where('booth_delegasi_id', $boothId);
            }

            if ($keyword !== '') {
                $votesQuery->where(function ($q) use ($keyword) {
                    $q->whereHas('user', function ($u) use ($keyword) {
                        $u->where('name', 'like', "%{$keyword}%")
                          ->orWhere('email', 'like', "%{$keyword}%")
                          ->orWhere('no_hp', 'like', "%{$keyword}%")
                          ->orWhere('institusi', 'like', "%{$keyword}%");
                    })->orWhereHas('booth', function ($b) use ($keyword) {
                        $b->where('institusi', 'like', "%{$keyword}%")
                          ->orWhere('judul_karya_tematik', 'like', "%{$keyword}%");
                    });
                });
            }

            $dataVoters = $votesQuery->latest()->get();

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'modul'      => 'Most Popular Artwork (Booth)',
                'aksi'       => 'Export Data Pemilih (Voters)',
                'deskripsi'  => 'Admin ' . Auth::user()->name . ' mengunduh data nama & kontak pemilih (voters) Booth Tematik' . ($boothFilter ? ' untuk ' . $boothFilter->institusi : ' (Semua Booth)') . '.',
                'ip_address' => $request->ip(),
            ]);

            $slugLabel = $boothFilter ? '_' . \Illuminate\Support\Str::slug($boothFilter->institusi) : '_Semua_Booth';
            $fileName = 'Data_Pemilih_Voting_Booth' . $slugLabel . '_' . date('Y-m-d_H-i') . '.xls';

            $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            $html .= '<head><meta http-equiv="content-type" content="text/html; charset=UTF-8">';
            $html .= '<style>
                        table { border-collapse: collapse; width: 100%; }
                        th, td { border: 1px solid #cbd5e1; padding: 8px 12px; font-family: Arial, sans-serif; font-size: 11pt; vertical-align: middle; }
                        th { background-color: #126CFD; color: #ffffff; font-weight: bold; text-align: center; }
                      </style></head>';
            $html .= '<body><table><thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap Pemilih</th>
                            <th>Email</th>
                            <th>No. HP / WhatsApp</th>
                            <th>Kategori Akun</th>
                            <th>Asal Institusi / Kampus</th>
                            <th>Profesi</th>
                            <th>Memilih Booth (Perguruan Tinggi)</th>
                            <th>Judul Karya Tematik</th>
                            <th>Waktu Voting</th>
                        </tr>
                      </thead><tbody>';

            $no = 1;
            foreach ($dataVoters as $vote) {
                $voter = $vote->user;
                $boothItem = $vote->booth;
                $noHp = $voter->no_hp ?? '-';
                // Gunakan format teks di Excel agar angka 0 di depan nomor HP tidak hilang
                $noHpFormatted = $noHp !== '-' ? '="' . htmlspecialchars($noHp) . '"' : '-';

                $html .= '<tr>';
                $html .= '<td align="center">' . $no++ . '</td>';
                $html .= '<td><b>' . htmlspecialchars($voter->name ?? 'User Terhapus') . '</b></td>';
                $html .= '<td>' . htmlspecialchars($voter->email ?? '-') . '</td>';
                $html .= '<td align="center">' . $noHpFormatted . '</td>';
                $html .= '<td align="center">' . htmlspecialchars($voter->kategori ?? '-') . '</td>';
                $html .= '<td>' . htmlspecialchars($voter->institusi ?? '-') . '</td>';
                $html .= '<td>' . htmlspecialchars($voter->profesi ?? '-') . '</td>';
                $html .= '<td><b>' . htmlspecialchars($boothItem->institusi ?? '-') . '</b></td>';
                $html .= '<td>' . htmlspecialchars($boothItem->judul_karya_tematik ?? '-') . '</td>';
                $html .= '<td align="center">' . ($vote->created_at ? $vote->created_at->format('d M Y, H:i:s') . ' WIB' : '-') . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody></table></body></html>';

            return response($html, 200, [
                "Content-Type"        => "application/vnd.ms-excel; charset=utf-8",
                "Content-Disposition" => "attachment; filename=\"$fileName\"",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ]);
        }

        // =========================================================================
        // 2. EXPORT EXCEL REKAP KLASEMEN BOOTH (?export=true)
        // =========================================================================
        if ($request->get('export') == 'true') {
            $dataExport = $query->get();

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'modul'      => 'Most Popular Artwork (Booth)',
                'aksi'       => 'Export Excel',
                'deskripsi'  => 'Admin ' . Auth::user()->name . ' mengunduh rekapitulasi hasil voting Booth Tematik ke Excel.',
                'ip_address' => $request->ip(),
            ]);

            $fileName = 'Rekap_Voting_Booth_Tematik_KMDGI16_' . date('Y-m-d_H-i') . '.xls';

            $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            $html .= '<head><meta http-equiv="content-type" content="text/html; charset=UTF-8">';
            $html .= '<style>
                        table { border-collapse: collapse; width: 100%; }
                        th, td { border: 1px solid #cbd5e1; padding: 8px 12px; font-family: Arial, sans-serif; font-size: 11pt; vertical-align: middle; }
                        th { background-color: #126CFD; color: #ffffff; font-weight: bold; text-align: center; }
                      </style></head>';
            $html .= '<body><table><thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Nama Perguruan Tinggi</th>
                            <th>Judul Karya Tematik</th>
                            <th>Kreator / Tim</th>
                            <th>Jumlah Suara (Vote)</th>
                            <th>Status Submisi / Kurasi</th>
                            <th>Keterangan Diskualifikasi</th>
                            <th>Waktu Upload</th>
                            <th>Deskripsi Singkat</th>
                        </tr>
                      </thead><tbody>';

            $rank = 1;
            foreach ($dataExport as $item) {
                $isItemDisqualified = isset($disqualifiedMap[$item->id]);
                $alasanDq = $isItemDisqualified ? ($disqualifiedMap[$item->id]['alasan'] ?? 'Didiskualifikasi') : '-';
                $statusTeks = $item->status_draft
                    ? 'Draft'
                    : ($isItemDisqualified ? 'Didiskualifikasi' : $item->status_verifikasi);
                $rankLabel = $isItemDisqualified ? 'DISKUALIFIKASI' : '#' . $rank++;

                $html .= '<tr>';
                $html .= '<td align="center"><b>' . $rankLabel . '</b></td>';
                $html .= '<td>' . htmlspecialchars($item->institusi) . '</td>';
                $html .= '<td>' . htmlspecialchars($item->judul_karya_tematik ?? '-') . '</td>';
                $html .= '<td>' . htmlspecialchars($item->kreator_karya ?? '-') . '</td>';
                $html .= '<td align="center"><b>' . $item->votes_count . '</b></td>';
                $html .= '<td align="center">' . htmlspecialchars($statusTeks) . '</td>';
                $html .= '<td>' . htmlspecialchars($alasanDq) . '</td>';
                $html .= '<td align="center">' . $item->updated_at->format('d M Y, H:i:s') . ' WIB</td>';
                $html .= '<td>' . htmlspecialchars($item->deskripsi_singkat ?? '-') . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody></table></body></html>';

            return response($html, 200, [
                "Content-Type"        => "application/vnd.ms-excel; charset=utf-8",
                "Content-Disposition" => "attachment; filename=\"$fileName\"",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ]);
        }

        $baseStatsQuery = BoothDelegasi::when($edisiAktif, fn($q) => $q->where('edisi_kmdgi_id', $edisiAktif->id))->where('status_draft', 0);
        $totalKampus         = Kampus::count();
        $totalSubmisi        = (clone $baseStatsQuery)->count();
        $totalTerverifikasi  = (clone $baseStatsQuery)->where('status_verifikasi', 'Terverifikasi')->when(!empty($disqualifiedIds), fn($q) => $q->whereNotIn('id', $disqualifiedIds))->count();
        $totalMenunggu       = (clone $baseStatsQuery)->where('status_verifikasi', 'Menunggu')->count();
        $totalDiskualifikasi = !empty($disqualifiedIds) ? (clone $baseStatsQuery)->whereIn('id', $disqualifiedIds)->count() : 0;
        $totalSuaraMasuk     = BoothVote::when($edisiAktif, function ($q) use ($edisiAktif) {
            $q->whereHas('booth', fn($b) => $b->where('edisi_kmdgi_id', $edisiAktif->id));
        })->count();

        $perPage = $request->get('per_page', 10);
        $limit = ($perPage === 'all') ? max($query->count(), 1) : (int) $perPage;

        $dataBooth = $query->paginate($limit)->withQueryString();

        $namaInstitusiList = $dataBooth->pluck('institusi')->filter()->unique();
        $kampusLogos = Kampus::whereIn('nama_institusi', $namaInstitusiList)->pluck('logo_institusi', 'nama_institusi');

        return view('admin.booth.index', compact(
            'dataBooth',
            'kampusLogos',
            'config',
            'isVotingOpen',
            'totalKampus',
            'totalSubmisi',
            'totalTerverifikasi',
            'totalMenunggu',
            'totalDiskualifikasi',
            'totalSuaraMasuk',
            'sort',
            'disqualifiedMap'
        ));
    }

    /**
     * Master Toggle: Rilis / Sembunyikan Seluruh Karya Terverifikasi ke Halaman Voting Serentak
     */
    public function togglePublishVoting(Request $request)
    {
        $currentState = Cache::get('booth_voting_published', false);
        $newState = !$currentState;

        Cache::forever('booth_voting_published', $newState);

        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Most Popular Artwork (Booth)',
            'aksi'       => 'Toggle Rilis Voting',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' mengubah status rilis serentak halaman voting menjadi: ' . ($newState ? 'AKTIF (DITAMPILKAN)' : 'NONAKTIF (DISEMBUNYIKAN)'),
            'ip_address' => $request->ip(),
        ]);

        $pesan = $newState
            ? 'Seluruh karya booth yang Terverifikasi kini resmi DITAMPILKAN SERENTAK di halaman voting publik!'
            : 'Halaman voting publik berhasil DISEMBUNYIKAN sementara dari pengunjung.';

        return redirect()->back()->with('success', $pesan);
    }

    /**
     * Halaman Cetak / Download Poster QR Code Ukuran Kertas A4
     */
    public function printQrPoster($id)
    {
        $booth = BoothDelegasi::findOrFail($id);
        // Menggunakan slug dari nama institusi/kampus: /voting/nama-kampus
        $voteUrl = route('voting.booth.show', \Illuminate\Support\Str::slug($booth->institusi));

        return view('admin.booth.qr_poster', compact('booth', 'voteUrl'));
    }

    public function updateConfig(Request $request)
    {
        $request->validate([
            'waktu_buka'       => 'required|date',
            'waktu_tutup'      => 'required|date|after:waktu_buka',
            'waktu_awarding'   => 'required|date',
            'is_manual_closed' => 'nullable|boolean',
        ], [
            'waktu_tutup.after' => 'Batas akhir voting harus lebih akhir dari waktu buka rilis.',
        ]);

        Cache::forever('booth_waktu_buka', $request->waktu_buka);
        Cache::forever('booth_waktu_tutup', $request->waktu_tutup);
        Cache::forever('booth_waktu_awarding', $request->waktu_awarding);
        Cache::forever('booth_manual_closed', $request->boolean('is_manual_closed'));

        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Most Popular Artwork (Booth)',
            'aksi'       => 'Update Jadwal',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' memperbarui jadwal rilis dan batas waktu voting Booth Tematik.',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Pengaturan jadwal rilis dan batas waktu voting berhasil diperbarui!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi'     => 'required|in:Menunggu,Terverifikasi,Ditolak,Diskualifikasi',
            'alasan_diskualifikasi' => 'nullable|string|max:300',
        ]);

        $booth = BoothDelegasi::findOrFail($id);
        $disqualifiedMap = Cache::get('booth_disqualified_map', []);

        if ($request->status_verifikasi === 'Diskualifikasi') {
            // Pertahankan status_verifikasi = 'Terverifikasi' di DB agar tetap tampil di halaman publik,
            // dan PERTAHANKAN seluruh total vote (votes_count & BoothVote tidak dihapus).
            $booth->status_verifikasi = 'Terverifikasi';
            $booth->status_draft = 0;
            $booth->save();

            $alasan = trim((string) $request->input('alasan_diskualifikasi', ''));
            if ($alasan === '') {
                $alasan = 'Didiskualifikasi oleh Panitia KMDGI 16 karena pelanggaran ketentuan Most Popular Artwork.';
            }

            $disqualifiedMap[$booth->id] = [
                'alasan' => $alasan,
                'waktu'  => now()->format('d M Y, H:i') . ' WIB',
                'admin'  => Auth::user()->name ?? 'Admin',
            ];
            Cache::forever('booth_disqualified_map', $disqualifiedMap);

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'modul'      => 'Most Popular Artwork (Booth)',
                'aksi'       => 'Diskualifikasi Booth',
                'deskripsi'  => 'Admin ' . Auth::user()->name . ' mendiskualifikasi booth ' . $booth->institusi . ' (Total suara tetap dipertahankan: ' . $booth->votes_count . ' suara). Keterangan: ' . $alasan,
                'ip_address' => $request->ip(),
            ]);

            if ($booth->user) {
                $booth->user->notify(new GeneralNotification(
                    'Pemberitahuan Diskualifikasi Booth Delegasi',
                    "Booth Tematik \"{$booth->judul_karya_tematik}\" dari {$booth->institusi} dinyatakan DIDISKUALIFIKASI oleh Panitia. Keterangan: {$alasan}",
                    'warning',
                    route('delegasi.booth.index')
                ));
            }

            return redirect()->back()->with('success', 'Booth ' . $booth->institusi . ' telah diatur menjadi DIDISKUALIFIKASI (Total ' . number_format($booth->votes_count) . ' suara tetap dipertahankan & keterangan tampil di halaman umum).');
        }

        // Jika diubah kembali ke Terverifikasi / Menunggu / Ditolak, hapus dari daftar diskualifikasi jika sebelumnya didiskualifikasi
        $wasDisqualified = isset($disqualifiedMap[$booth->id]);
        if ($wasDisqualified) {
            unset($disqualifiedMap[$booth->id]);
            Cache::forever('booth_disqualified_map', $disqualifiedMap);

            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'modul'      => 'Most Popular Artwork (Booth)',
                'aksi'       => 'Cabut Diskualifikasi Booth',
                'deskripsi'  => 'Admin ' . Auth::user()->name . ' mencabut status diskualifikasi untuk booth ' . $booth->institusi . ' dan mengubah status menjadi ' . $request->status_verifikasi . '.',
                'ip_address' => $request->ip(),
            ]);
        }

        $booth->status_verifikasi = $request->status_verifikasi;
        $booth->status_draft = 0;
        $booth->save();

        if ($booth->user) {
            $tipeNotif = $request->status_verifikasi === 'Terverifikasi' ? 'success' : ($request->status_verifikasi === 'Ditolak' ? 'warning' : 'info');
            $pesanNotif = $request->status_verifikasi === 'Terverifikasi'
                ? "Selamat! Booth Tematik \"{$booth->judul_karya_tematik}\" dari {$booth->institusi} telah TERVERIFIKASI (Terkurasi). Data karya kini telah dikunci dan siap mengikuti voting serentak."
                : "Status kurasi Booth Tematik \"{$booth->judul_karya_tematik}\" Anda diubah menjadi: {$request->status_verifikasi}.";

            $booth->user->notify(new GeneralNotification(
                'Status Kurasi Booth Delegasi',
                $pesanNotif,
                $tipeNotif,
                route('delegasi.booth.index')
            ));
        }

        return redirect()->back()->with('success', 'Status booth ' . $booth->institusi . ' berhasil diperbarui menjadi ' . $request->status_verifikasi . '!');
    }

    public function resetVotes(Request $request, $id)
    {
        $booth = BoothDelegasi::findOrFail($id);
        BoothVote::where('booth_delegasi_id', $booth->id)->delete();
        $booth->update(['votes_count' => 0]);

        return redirect()->back()->with('success', 'Perolehan suara untuk ' . $booth->institusi . ' berhasil di-reset menjadi 0.');
    }

    public function destroy(Request $request, $id)
    {
        $booth = BoothDelegasi::findOrFail($id);
        $namaInstitusi = $booth->institusi;

        $disqualifiedMap = Cache::get('booth_disqualified_map', []);
        if (isset($disqualifiedMap[$booth->id])) {
            unset($disqualifiedMap[$booth->id]);
            Cache::forever('booth_disqualified_map', $disqualifiedMap);
        }

        foreach (['foto_utama', 'foto_pendukung_1', 'foto_pendukung_2'] as $field) {
            if ($booth->$field && Storage::disk('public')->exists($booth->$field)) {
                Storage::disk('public')->delete($booth->$field);
            }
        }

        $booth->delete();

        return redirect()->back()->with('success', 'Data booth delegasi ' . $namaInstitusi . ' berhasil dihapus permanen.');
    }
}
