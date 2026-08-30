@extends('layouts.app')

@section('title', 'Jadwal Acara - KMDGI 16')

@section('content')
<div class="bg-white min-h-screen flex flex-col relative font-sans">
    
    @include('partials.navbar')

    <!-- ========================================== -->
    <!-- HERO HEADER                                -->
    <!-- ========================================== -->
    <div class="w-full bg-slate-900 pt-32 pb-16 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#1A68FF]/30 rounded-full blur-[100px] pointer-events-none"></div>
            <div class="absolute top-20 right-0 w-64 h-64 bg-[#C4F03B]/20 rounded-full blur-[80px] pointer-events-none"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight mb-4">
                Katalog <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#C4F03B] to-[#1A68FF]">Acara</span>
            </h1>
            <p class="text-slate-300 text-sm md:text-lg max-w-2xl mx-auto mb-1 font-medium">
                Temukan dan daftarkan dirimu ke berbagai acara Seminar, Talkshow, dan Workshop inspiratif KMDGI 16.
            </p>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MAIN CONTENT AREA                          -->
    <!-- ========================================== -->
    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow py-12 md:py-16">
        
        <h2 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight mb-8">Jadwal</h2>

        <!-- NOTIFIKASI -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-xl flex items-center gap-3 shadow-sm mb-8">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-xl text-sm font-bold shadow-sm mb-8 flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <!-- FILTER & SEARCH BAR (Desain Baru & Realtime) -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-12">
            <form id="searchForm" action="{{ route('katalog.event') }}" method="GET" class="w-full md:max-w-md relative flex items-center">
                <svg class="w-5 h-5 text-slate-400 absolute left-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="text" id="searchInput" name="search" value="{{ request('search') }}" placeholder="Cari acara..." class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#1A68FF]/50 focus:border-[#1A68FF] bg-white text-sm font-medium transition-all shadow-sm">
            </form>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <div class="relative w-full md:w-40 flex-shrink-0">
                    <select id="sortSelect" form="searchForm" name="sort" class="w-full appearance-none bg-white border border-slate-200 text-slate-700 py-3 pl-4 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1A68FF]/50 focus:border-[#1A68FF] text-sm font-medium shadow-sm cursor-pointer">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- AREA KONTEN YANG AKAN DI-REFRESH OLEH AJAX/SUBMIT -->
        <div id="event-list-container">
            @if($events->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-10">
                    @foreach($events as $event)
                        @php
                            $isRegistered = in_array($event->id, $myEventIds ?? []);
                            
                            $pesertaCount = \App\Models\TiketPeserta::where('event_kmdgi_id', $event->id)->count();
                            $isFull = ($event->kuota > 0 && $pesertaCount >= $event->kuota);
                            
                            $isPast = false;
                            if($event->tanggal_pelaksanaan) {
                                $waktuAcara = \Carbon\Carbon::parse($event->tanggal_pelaksanaan . ' ' . ($event->jam_pelaksanaan ?? '00:00:00'));
                                $isPast = $waktuAcara->isPast();
                            }
                        @endphp

                        <!-- CARD DESAIN BARU -->
                        <div class="flex flex-col sm:flex-row gap-5 w-full bg-white group">
                            
                            <!-- Poster Acara (Kiri) -->
                            <div class="w-full sm:w-[240px] md:w-[260px] aspect-[4/3] sm:aspect-square rounded-2xl overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-100 shadow-sm relative">
                                @if($event->poster)
                                    <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->judul }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 p-4">
                                        <svg class="w-8 h-8 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        <span class="text-[10px] font-semibold">No Image</span>
                                    </div>
                                @endif

                                <!-- OVERLAY HOVER MENGARAHKAN KE DETAIL -->
                                <a href="{{ route('katalog.event.show', $event->slug) }}" class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity z-10"></a>
                            </div>

                            <!-- Info Acara (Kanan) -->
                            <div class="flex-grow flex flex-col pt-1">
                                
                                <!-- Badges -->
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @if(is_array($event->kategori_peserta) && count($event->kategori_peserta) > 0)
                                        @foreach($event->kategori_peserta as $kat)
                                            <span class="px-3 py-1 bg-white border border-emerald-200 text-emerald-700 text-[10px] font-semibold rounded-full">{{ $kat }}</span>
                                        @endforeach
                                    @else
                                        <span class="px-3 py-1 bg-white border border-emerald-200 text-emerald-700 text-[10px] font-semibold rounded-full">Umum</span>
                                    @endif
                                    
                                    <span class="px-3 py-1 bg-white border border-[#1A68FF]/30 text-[#1A68FF] text-[10px] font-semibold rounded-full whitespace-nowrap">
                                        {{ $event->kuota == 0 ? 'Tak Terbatas' : $event->kuota . ' Orang' }}
                                    </span>
                                </div>
                                
                                <!-- Judul -->
                                <a href="{{ route('katalog.event.show', $event->slug) }}">
                                    <h3 class="text-xl font-bold text-slate-900 leading-snug mb-2 line-clamp-3 hover:text-[#1A68FF] transition-colors" title="{{ $event->judul }}">
                                        {{ $event->judul }}
                                    </h3>
                                </a>
                                
                                <!-- Narasumber -->
                                <p class="text-[13px] text-slate-700 font-medium line-clamp-1 mb-4">
                                    @php 
                                        $eventKolaborators = $event->getKolaboratorTerkait();
                                        $kolabNames = $eventKolaborators->pluck('nama')->join(', ');
                                    @endphp
                                    {{ $kolabNames ?: 'Narasumber Umum' }} <span class="text-slate-500 font-normal">- {{ $eventKolaborators->first()->profesi ?? 'Profesional' }}</span>
                                </p>

                                <!-- Detail Ikonografi -->
                                <div class="space-y-2 mb-6">
                                    <div class="flex items-center gap-2.5 text-[13px] text-slate-700">
                                        <svg class="w-4 h-4 text-slate-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                        {{ $event->jam_pelaksanaan ? \Carbon\Carbon::parse($event->jam_pelaksanaan)->format('H.i') : '-' }} WIB
                                    </div>
                                    <div class="flex items-center gap-2.5 text-[13px] text-slate-700">
                                        <svg class="w-4 h-4 text-slate-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                                        {{ $event->tanggal_pelaksanaan ? \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->locale('id')->translatedFormat('d F Y') : 'Menyusul' }}
                                    </div>
                                    <div class="flex items-start gap-2.5 text-[13px] text-slate-700">
                                        <svg class="w-4 h-4 text-slate-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="line-clamp-2 leading-relaxed">{{ $event->lokasi ?? 'Lokasi Menyusul' }}</span>
                                    </div>
                                </div>

                                <!-- TOMBOL AKSI CERDAS -->
                                <div class="mt-auto">
                                    @if(!Auth::check())
                                        <a href="{{ route('login') }}" class="block w-full sm:w-3/4 bg-slate-900 hover:bg-slate-800 text-white text-center font-semibold py-2.5 rounded-[0.5rem] text-sm transition-colors">
                                            Masuk
                                        </a>
                                    @else
                                        @php 
                                            $tiketUser = \App\Models\TiketPeserta::where('user_id', Auth::id())->where('event_kmdgi_id', $event->id)->first();
                                        @endphp

                                        @if($tiketUser)
                                            @if($tiketUser->status === 'Menunggu Konfirmasi')
                                                <button disabled class="w-full sm:w-3/4 bg-amber-50 text-amber-600 border border-amber-200 font-semibold py-2.5 rounded-[0.5rem] text-sm cursor-not-allowed">
                                                    Menunggu Konfirmasi
                                                </button>
                                            @else
                                                <button disabled class="w-full sm:w-3/4 bg-emerald-50 text-emerald-600 border border-emerald-200 font-semibold py-2.5 rounded-[0.5rem] text-sm flex items-center justify-center gap-1.5 cursor-not-allowed">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Tiket Dimiliki
                                                </button>
                                            @endif
                                        @elseif($isPast)
                                            <button disabled class="w-full sm:w-3/4 bg-slate-100 text-slate-400 font-semibold py-2.5 rounded-[0.5rem] text-sm cursor-not-allowed border border-slate-200">
                                                Telah Berakhir
                                            </button>
                                        @elseif($isFull)
                                            <button disabled class="w-full sm:w-3/4 bg-red-50 text-red-500 font-semibold py-2.5 rounded-[0.5rem] text-sm cursor-not-allowed border border-red-200">
                                                Kuota Habis
                                            </button>
                                        @else
                                            <a href="{{ route('katalog.event.show', $event->slug) }}" class="block w-full sm:w-3/4 bg-[#1A68FF] hover:bg-blue-700 text-white text-center font-semibold py-2.5 rounded-[0.5rem] text-sm transition-colors shadow-sm shadow-[#1A68FF]/30">
                                                Daftar
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-16 flex justify-center">
                    {{ $events->links() }}
                </div>
            @else
                <!-- Tampilan Kosong Pencarian -->
                <div class="bg-white p-12 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] text-center w-full max-w-2xl mx-auto flex flex-col items-center justify-center mt-8">
                    <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-5">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-800 mb-2">Acara Tidak Ditemukan</h4>
                    <p class="text-sm text-slate-500 leading-relaxed">Kami belum memiliki jadwal acara yang sesuai dengan pencarian Anda, atau mungkin acara belum dipublikasikan oleh panitia.</p>
                    <a href="{{ route('katalog.event') }}" class="mt-6 inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-6 py-2.5 rounded-full text-sm transition-colors">
                        Reset Pencarian
                    </a>
                </div>
            @endif
        </div>
    </div>

    @include('partials.footer')
</div>

<script>
    // ==========================================
    // LOGIKA PENCARIAN REAL-TIME (DEBOUNCE)
    // ==========================================
    let searchTimeout;
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    const sortSelect = document.getElementById('sortSelect');

    // Menunggu pengguna selesai mengetik (500ms) sebelum submit
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            searchForm.submit();
        }, 500); 
    });

    // Otomatis submit saat dropdown 'Sort By' diubah
    sortSelect.addEventListener('change', function() {
        searchForm.submit();
    });
</script>
@endsection