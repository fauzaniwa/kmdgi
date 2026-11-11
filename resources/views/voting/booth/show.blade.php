@extends('layouts.app')

@section('title', $booth->judul_karya_tematik . ' - Voting Most Popular Artwork KMDGI 16')

@php
    $daftarVotes = isset($initialVotes)
        ? $initialVotes
        : $booth->votes()->select(['id', 'booth_delegasi_id', 'user_id', 'created_at'])->with(['user:id,name,institusi,kategori'])->latest()->take(25)->get();
    $totalVotesCount = (int) ($booth->votes_count ?? 0);
    $hasMoreVotes = $totalVotesCount > $daftarVotes->count();
    $disqualifiedMap = $disqualifiedMap ?? \Illuminate\Support\Facades\Cache::get('booth_disqualified_map', []);
    $isBoothDisqualified = isset($disqualifiedMap[$booth->id]);
    $alasanDiskualifikasi = $isBoothDisqualified
        ? ($disqualifiedMap[$booth->id]['alasan'] ?? 'Didiskualifikasi oleh Panitia KMDGI 16 karena pelanggaran ketentuan Most Popular Artwork.')
        : null;
@endphp

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col font-sans">
    @include('partials.navbar')

    <div class="max-w-4xl w-full mx-auto px-4 sm:px-6 py-10 flex-grow space-y-8">

        <!-- 1. HEADER MOST POPULAR ARTWORK -->
        <div class="bg-gradient-to-br from-[#126CFD] to-blue-800 text-white rounded-[2rem] p-6 md:p-10 text-center relative overflow-hidden shadow-xl shadow-blue-500/15">
            <span class="inline-block px-4 py-1 rounded-full bg-lime-400 text-slate-950 text-xs font-black uppercase tracking-widest mb-3">
                TEMATIK — KMDGI 16
            </span>
            <h1 class="text-2xl md:text-4xl font-black tracking-tight uppercase">MOST POPULAR ARTWORK</h1>
            <p class="text-sm md:text-base text-blue-100 mt-2 font-medium">
                Berikan suaramu untuk karya Tematik favoritmu!
            </p>
            <div class="mt-4 inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm px-4 py-1.5 rounded-xl text-xs font-bold text-white">
                <span>Batas Voting: {{ $status['waktuTutup']->translatedFormat('d F Y, H:i') }} WIB</span>
            </div>
        </div>

        <!-- Notifikasi Berhasil Vote -->
        @if(session('vote_success'))
        <div class="bg-emerald-50 border-2 border-emerald-300 text-emerald-900 p-6 rounded-[2rem] text-center shadow-sm space-y-1">
            <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center mx-auto mb-2 shadow-md">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <h3 class="text-lg font-black">Vote berhasil!</h3>
            <p class="text-xs md:text-sm text-emerald-700 font-medium">
                Terima kasih sudah memberikan suara untuk Most Popular Artwork KMDGI 16.
            </p>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 p-5 rounded-2xl text-xs font-bold text-center">
            {{ $errors->first() }}
        </div>
        @endif

        @if($isBoothDisqualified)
        <div class="bg-red-50 border-2 border-red-200 text-red-900 p-6 rounded-[2rem] flex items-start gap-4 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-red-600 text-white flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
            <div>
                <span class="inline-block px-2.5 py-0.5 rounded-md bg-red-600 text-white text-[10px] font-black uppercase tracking-wider mb-1">
                    Pemberitahuan Resmi Panitia
                </span>
                <h3 class="text-base md:text-lg font-black text-red-900">Karya Booth Ini Telah Didiskualifikasi</h3>
                <p class="text-xs md:text-sm text-red-700 font-semibold mt-0.5 leading-relaxed">
                    {{ $alasanDiskualifikasi }}
                </p>
                <p class="text-[11px] text-red-600/80 mt-1">
                    *Total perolehan suara tetap ditampilkan sebagai catatan transparansi, namun karya ini tidak lagi dapat menerima suara baru.
                </p>
            </div>
        </div>
        @endif

        <!-- KARTU UTAMA BOOTH -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 md:p-8 space-y-8">

            <!-- 2. IDENTITAS BOOTH & SATU TOMBOL LIHAT DAFTAR PEMILIH -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-200 p-2 flex items-center justify-center flex-shrink-0 overflow-hidden">
                        @if($logoKampus)
                            <img src="{{ asset('storage/' . $logoKampus) }}" alt="{{ $booth->institusi }}" class="w-full h-full object-contain">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($booth->institusi) }}&background=E1EDFF&color=126CFD" class="w-full h-full object-contain rounded-xl">
                        @endif
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs md:text-sm font-black text-[#126CFD] uppercase tracking-wider">
                                {{ $booth->institusi }}
                            </span>
                            @if($isBoothDisqualified)
                            <span class="px-2.5 py-0.5 rounded-full bg-red-600 text-white text-[10px] font-black uppercase tracking-wider">
                                🚫 Didiskualifikasi
                            </span>
                            @endif
                        </div>
                        <h2 class="text-xl md:text-2xl font-black text-slate-900 leading-snug mt-0.5">
                            “{{ $booth->judul_karya_tematik }}”
                        </h2>
                        @if($booth->kreator_karya)
                            <p class="text-xs text-slate-400 font-semibold mt-1">Kreator / Tim: {{ $booth->kreator_karya }}</p>
                        @endif
                    </div>
                </div>

                <!-- SATU TOMBOL: KLIK UNTUK MELIHAT NAMA ORANG YANG MELAKUKAN VOTING -->
                <button type="button"
                        onclick="openPublicVotersModal()"
                        class="bg-blue-50/80 hover:bg-[#126CFD] text-[#126CFD] hover:text-white border border-blue-200 px-5 py-3 rounded-2xl text-center flex-shrink-0 transition-all group cursor-pointer shadow-sm">
                    <span class="block text-[10px] font-bold text-slate-400 group-hover:text-blue-100 uppercase tracking-wider">Total Suara</span>
                    <span class="text-2xl font-black block">{{ number_format($totalVotesCount) }}</span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-extrabold underline mt-0.5">
                        👥 Lihat Pemilih
                    </span>
                </button>
            </div>

            <!-- 3. FOTO KARYA (3 PENGAMBILAN FOTO BOOTH) -->
            @php
                $fotos = array_values(array_filter([
                    $booth->foto_utama,
                    $booth->foto_pendukung_1,
                    $booth->foto_pendukung_2,
                ]));
            @endphp
            <div class="space-y-3">
                <div class="w-full aspect-[16/10] rounded-2xl overflow-hidden bg-slate-100 border border-slate-200">
                    <img id="main-booth-photo" src="{{ asset('storage/' . ($fotos[0] ?? '')) }}" alt="{{ $booth->judul_karya_tematik }}" class="w-full h-full object-cover">
                </div>

                @if(count($fotos) > 1)
                <div class="grid grid-cols-3 gap-3">
                    @foreach($fotos as $idx => $foto)
                    <button type="button" onclick="document.getElementById('main-booth-photo').src='{{ asset('storage/' . $foto) }}'" class="aspect-[16/10] rounded-xl overflow-hidden border-2 border-slate-200 hover:border-[#126CFD] focus:border-[#126CFD] transition-all">
                        <img src="{{ asset('storage/' . $foto) }}" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- 4. DESKRIPSI KARYA -->
            <div class="space-y-2 pt-2 border-t border-slate-100">
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Deskripsi Karya Instalasi</h3>
                <div class="text-sm text-slate-600 leading-relaxed whitespace-pre-line bg-slate-50/70 p-5 rounded-2xl border border-slate-100">
                    {{ $booth->deskripsi_singkat }}
                </div>
            </div>

            <!-- 5. TOMBOL VOTING -->
            <div class="pt-4 border-t border-slate-100">
                @if($isBoothDisqualified)
                    <div class="w-full py-4 px-6 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-center font-bold text-sm">
                        🚫 Karya booth ini telah didiskualifikasi oleh panitia (Total {{ number_format($totalVotesCount) }} suara tetap tercatat).
                    </div>
                @elseif($existingVote)
                    <div class="w-full py-4 px-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-center font-bold text-sm">
                        ✓ Anda telah memberikan suara pada sesi Most Popular Artwork KMDGI 16.
                    </div>
                @elseif(!$status['isOpen'])
                    <div class="w-full py-4 px-6 rounded-2xl bg-slate-100 border border-slate-200 text-slate-500 text-center font-bold text-sm">
                        Sesi voting saat ini sedang ditutup atau berada di luar jadwal voting.
                    </div>
                @else
                    <button type="button"
                            onclick="openVoteConfirmModal()"
                            class="w-full py-4 px-8 rounded-2xl bg-[#126CFD] hover:bg-blue-700 text-white font-black text-base shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5">
                        BERIKAN SUARA (VOTE KARYA INI)
                    </button>
                @endif

                <div class="text-center mt-4">
                    <a href="{{ route('voting.booth.index') }}" class="text-xs font-bold text-slate-500 hover:text-[#126CFD]">
                        ← Lihat Seluruh Nominasi Booth Kampus Lainnya
                    </a>
                </div>
            </div>

        </div>
    </div>

    @include('partials.footer')
</div>

<!-- ========================================================================= -->
<!-- MODAL DAFTAR NAMA PEMILIH (DEFAULT 25 DATA + LOAD MORE, TANPA KONTAK)     -->
<!-- ========================================================================= -->
<div id="publicVotersModal" class="fixed inset-0 z-[200] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[2rem] p-6 md:p-8 max-w-lg w-full max-h-[85vh] flex flex-col shadow-2xl space-y-4">
        <div class="flex items-start justify-between border-b border-slate-100 pb-4">
            <div>
                <span class="text-[10px] font-black uppercase tracking-wider text-[#126CFD] bg-blue-50 px-2.5 py-1 rounded-full">
                    Daftar Pendukung Karya
                </span>
                <h3 class="text-lg font-black text-slate-900 mt-1">Orang yang Melakukan Voting ({{ number_format($totalVotesCount) }})</h3>
                <p class="text-xs text-slate-500">{{ $booth->institusi }} — “{{ $booth->judul_karya_tematik }}”</p>
            </div>
            <button type="button" onclick="closePublicVotersModal()" class="p-2 rounded-xl bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div id="public-voters-container" class="overflow-y-auto divide-y divide-slate-100 pr-1 flex-grow">
            @forelse($daftarVotes as $vote)
                @php
                    $voterName = $vote->user->name ?? 'Pengunjung KMDGI';
                    $voterInstitusi = $vote->user->institusi ?? ($vote->user->kategori ?? 'Umum');
                    $words = preg_split('/\s+/', trim($voterName), -1, PREG_SPLIT_NO_EMPTY);
                    $initials = strtoupper(substr($words[0] ?? 'P', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                @endphp
                <div class="py-3 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-full bg-blue-50 text-[#126CFD] border border-blue-100 flex items-center justify-center font-black text-xs flex-shrink-0 select-none">
                            {{ $initials }}
                        </div>
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-slate-900 block truncate">{{ $voterName }}</span>
                            <span class="text-[11px] text-slate-400 block truncate">{{ $voterInstitusi }}</span>
                        </div>
                    </div>
                    <span class="text-[10px] font-semibold text-slate-400 flex-shrink-0">
                        {{ $vote->created_at ? $vote->created_at->translatedFormat('d M Y, H:i') : '' }}
                    </span>
                </div>
            @empty
                <div class="py-10 text-center text-xs text-slate-400 font-semibold">
                    Belum ada suara yang masuk untuk booth ini. Jadilah pemberi suara pertama!
                </div>
            @endforelse
        </div>

        <!-- Tombol Load More (Muncul jika suara > 25) -->
        <div id="public-voters-load-more-wrap" class="{{ $hasMoreVotes ? '' : 'hidden' }} pt-1">
            <button type="button"
                    id="btn-public-load-more"
                    onclick="loadMorePublicVoters()"
                    class="w-full py-2.5 px-4 rounded-xl bg-blue-50 hover:bg-[#126CFD] text-[#126CFD] hover:text-white border border-blue-200 font-extrabold text-xs transition-all cursor-pointer">
                Muat Lebih Banyak (25 Berikutnya)
            </button>
        </div>

        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
            <span>
                Menampilkan <strong id="public-voters-loaded-count" class="text-slate-700">{{ $daftarVotes->count() }}</strong> dari <strong class="text-slate-700">{{ number_format($totalVotesCount) }}</strong> suara
            </span>
            <button type="button" onclick="closePublicVotersModal()" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI VOTE SESUAI KETENTUAN -->
<div id="voteConfirmModal" class="fixed inset-0 z-[200] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[2rem] p-6 md:p-8 max-w-md w-full shadow-2xl text-center space-y-5">
        <div class="w-14 h-14 rounded-2xl bg-blue-50 text-[#126CFD] flex items-center justify-center mx-auto">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
            </svg>
        </div>

        <div class="space-y-2">
            <h3 class="text-xl font-black text-slate-900">Konfirmasi Vote</h3>
            <p class="text-xs text-slate-500">Kamu akan memberikan 1 suara untuk:</p>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-sm font-black text-slate-900">
                {{ $booth->institusi }} — “{{ $booth->judul_karya_tematik }}”
            </div>
            <p class="text-[11px] font-semibold text-amber-600 pt-1">
                *Setiap pengunjung hanya dapat memberikan satu suara.
            </p>
        </div>

        <form action="{{ route('voting.booth.vote', \Illuminate\Support\Str::slug($booth->institusi)) }}" method="POST" class="flex flex-col sm:flex-row gap-3 pt-2">
            @csrf
            <button type="button" onclick="closeVoteConfirmModal()" class="flex-1 py-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs">
                Batal
            </button>
            <button type="submit" class="flex-1 py-3.5 rounded-xl bg-[#126CFD] hover:bg-blue-700 text-white font-black text-xs uppercase tracking-wider shadow-md shadow-blue-500/20">
                KONFIRMASI VOTE
            </button>
        </form>
    </div>
</div>

<script>
    let nextPublicVotersPage = 2;
    let isLoadingPublicVoters = false;
    let loadedPublicVotersCount = {{ $daftarVotes->count() }};

    function escapeHtmlPublic(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function openPublicVotersModal() {
        const modal = document.getElementById('publicVotersModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closePublicVotersModal() {
        const modal = document.getElementById('publicVotersModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    function openVoteConfirmModal() {
        const modal = document.getElementById('voteConfirmModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeVoteConfirmModal() {
        const modal = document.getElementById('voteConfirmModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function loadMorePublicVoters() {
        if (isLoadingPublicVoters) return;
        isLoadingPublicVoters = true;

        const btn = document.getElementById('btn-public-load-more');
        const wrap = document.getElementById('public-voters-load-more-wrap');
        const container = document.getElementById('public-voters-container');
        const countEl = document.getElementById('public-voters-loaded-count');

        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Memuat data...';
        }

        const url = new URL(window.location.href);
        url.searchParams.set('ajax_voters', '1');
        url.searchParams.set('page', String(nextPublicVotersPage));

        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(res => {
            const items = res.data || [];
            if (items.length > 0 && container) {
                const fragment = document.createDocumentFragment();
                items.forEach(item => {
                    const row = document.createElement('div');
                    row.className = 'py-3 flex items-center justify-between gap-3';
                    row.innerHTML = `
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-full bg-blue-50 text-[#126CFD] border border-blue-100 flex items-center justify-center font-black text-xs flex-shrink-0 select-none">
                                ${escapeHtmlPublic(item.initials || 'P')}
                            </div>
                            <div class="min-w-0">
                                <span class="text-xs font-bold text-slate-900 block truncate">${escapeHtmlPublic(item.name)}</span>
                                <span class="text-[11px] text-slate-400 block truncate">${escapeHtmlPublic(item.institusi)}</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 flex-shrink-0">
                            ${escapeHtmlPublic(item.waktu)}
                        </span>
                    `;
                    fragment.appendChild(row);
                });
                container.appendChild(fragment);

                loadedPublicVotersCount += items.length;
                if (countEl) countEl.innerText = loadedPublicVotersCount;
            }

            if (res.has_more) {
                nextPublicVotersPage = res.next_page || (nextPublicVotersPage + 1);
                if (btn) {
                    btn.disabled = false;
                    btn.innerText = 'Muat Lebih Banyak (25 Berikutnya)';
                }
            } else if (wrap) {
                wrap.classList.add('hidden');
            }
        })
        .catch(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerText = 'Muat Lebih Banyak (25 Berikutnya)';
            }
        })
        .finally(() => {
            isLoadingPublicVoters = false;
        });
    }
</script>
@endsection
