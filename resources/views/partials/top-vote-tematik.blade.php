@if(isset($leaderboardBooths) && $leaderboardBooths->count() > 0)
<section class="mt-16 mb-8 bg-white border border-slate-200/80 rounded-[2rem] p-6 md:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.03)] font-sans">
    
    <!-- Header Top Vote & Countdown Timer -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Top Vote Karya Tematik!</h2>
            <p class="text-xs md:text-sm text-slate-400 font-medium mt-1">Karya paling disukai</p>
        </div>

        <!-- Countdown Box (Hari : Jam : Menit) -->
        <div class="flex items-center justify-center md:justify-end gap-2 md:gap-3"
             id="tematik-countdown"
             data-deadline="{{ isset($waktuTutup) ? $waktuTutup->toIso8601String() : '2026-11-10T12:00:00' }}">
            <div class="w-16 h-16 md:w-20 md:h-20 bg-[#EEF4FF] rounded-2xl flex flex-col items-center justify-center">
                <span class="text-xl md:text-3xl font-black text-[#1A68FF]" id="tv-hari">00</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase mt-0.5">Hari</span>
            </div>
            <span class="text-slate-300 font-black text-xl">:</span>
            <div class="w-16 h-16 md:w-20 md:h-20 bg-[#EEF4FF] rounded-2xl flex flex-col items-center justify-center">
                <span class="text-xl md:text-3xl font-black text-[#1A68FF]" id="tv-jam">00</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase mt-0.5">Jam</span>
            </div>
            <span class="text-slate-300 font-black text-xl">:</span>
            <div class="w-16 h-16 md:w-20 md:h-20 bg-[#EEF4FF] rounded-2xl flex flex-col items-center justify-center">
                <span class="text-xl md:text-3xl font-black text-[#1A68FF]" id="tv-menit">00</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase mt-0.5">Menit</span>
            </div>
        </div>
    </div>

    @php
        $topThree = $leaderboardBooths->take(3);
        $otherRanks = $leaderboardBooths->slice(3, 10)->values();
        $leftColumn = $otherRanks->slice(0, 5);
        $rightColumn = $otherRanks->slice(5, 5);
    @endphp

    <!-- Konten Utama Klasemen: Kiri (Top 3 dengan Thumbnail), Kanan (Peringkat 04 - 13) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- KOLOM KIRI: TOP 1 - 3 -->
        <div class="lg:col-span-5 space-y-4">
            @foreach($topThree as $idx => $top)
                @php
                    $rank = str_pad($idx + 1, 2, '0', STR_PAD_LEFT);
                    $slugTop = \Illuminate\Support\Str::slug($top->institusi);
                @endphp
                <a href="{{ route('voting.booth.show', $slugTop) }}"
                   class="flex items-center gap-4 p-4 rounded-2xl transition-all {{ $idx === 0 ? 'bg-[#F2F7FF] border border-blue-200/80 shadow-sm' : 'bg-white hover:bg-slate-50 border border-slate-100' }}">
                    <span class="text-lg md:text-xl font-black {{ $idx === 0 ? 'text-[#1A68FF]' : 'text-slate-700' }} w-7 text-center flex-shrink-0">
                        {{ $rank }}
                    </span>

                    <div class="w-16 h-20 md:w-20 md:h-24 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200">
                        <img src="{{ asset('storage/' . $top->foto_utama) }}" alt="{{ $top->judul_karya_tematik }}" class="w-full h-full object-cover">
                    </div>

                    <div class="flex-grow min-w-0">
                        @if($idx === 0)
                        <span class="inline-flex items-center gap-1 text-[10px] font-extrabold text-[#1A68FF] uppercase tracking-wider mb-1">
                            👑 TERATAS SAAT INI
                        </span>
                        @endif
                        <h3 class="text-sm md:text-base font-black text-slate-900 truncate">{{ $top->judul_karya_tematik }}</h3>
                        <p class="text-xs text-slate-400 truncate mt-0.5">{{ $top->institusi }}</p>
                        <p class="text-xs font-extrabold text-[#1A68FF] mt-2">
                            {{ number_format($top->votes_count, 0, ',', '.') }} suara
                        </p>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- KOLOM KANAN: PERINGKAT LAINNYA (2 KOLOM) -->
        <div class="lg:col-span-7 grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach([$leftColumn, $rightColumn] as $colItems)
                @if($colItems->count() > 0)
                <div>
                    <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase tracking-wider pb-2.5 mb-2 border-b border-slate-100">
                        <span>Peringkat Lainnya</span>
                        <span>Suara</span>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($colItems as $item)
                            @php
                                $realRank = $leaderboardBooths->search(fn($b) => $b->id === $item->id) + 1;
                                $slugItem = \Illuminate\Support\Str::slug($item->institusi);
                            @endphp
                            <a href="{{ route('voting.booth.show', $slugItem) }}" class="py-3 flex items-center justify-between gap-3 hover:bg-slate-50/80 px-2 rounded-lg transition-colors">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="text-xs font-bold text-slate-400 w-5">{{ str_pad($realRank, 2, '0', STR_PAD_LEFT) }}</span>
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-extrabold text-slate-800 truncate">{{ $item->judul_karya_tematik }}</h4>
                                        <p class="text-[11px] text-slate-400 truncate">{{ $item->institusi }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-black text-slate-700 flex-shrink-0">{{ number_format($item->votes_count, 0, ',', '.') }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach
        </div>

    </div>

    <!-- Footer Meta Info & Disclaimer -->
    <div class="mt-8 pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-[11px] text-slate-400 font-medium">
        <span class="flex items-center gap-1.5">
            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            Diperbarui {{ now()->translatedFormat('d F Y, H.i') }} WIB
        </span>
        <div>
            <span>{{ $totalKaryaTematik ?? 0 }} karya</span>
            <span class="mx-2">|</span>
            <span class="font-bold text-slate-600">{{ number_format($totalSuaraTematik ?? 0, 0, ',', '.') }} suara terkumpul</span>
        </div>
    </div>

    <div class="mt-4 bg-slate-50 border border-slate-100 rounded-2xl p-4">
        <p class="text-xs font-extrabold text-slate-800">Suaramu ikut menentukan.</p>
        <p class="text-[11px] text-slate-500 mt-0.5">Peringkat ini bersifat sementara. Hasil akhir diumumkan setelah periode voting selesai.</p>
    </div>
</section>

<script>
    (function initTematikCountdown() {
        const el = document.getElementById('tematik-countdown');
        if (!el) return;
        const deadline = new Date(el.getAttribute('data-deadline')).getTime();

        function updateTimer() {
            const now = new Date().getTime();
            const diff = deadline - now;
            if (diff <= 0) {
                document.getElementById('tv-hari').innerText = '00';
                document.getElementById('tv-jam').innerText = '00';
                document.getElementById('tv-menit').innerText = '00';
                return;
            }
            const d = Math.floor(diff / (1000 * 60 * 60 * 24));
            const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

            document.getElementById('tv-hari').innerText = String(d).padStart(2, '0');
            document.getElementById('tv-jam').innerText = String(h).padStart(2, '0');
            document.getElementById('tv-menit').innerText = String(m).padStart(2, '0');
        }
        updateTimer();
        setInterval(updateTimer, 30000);
    })();
</script>
@endif