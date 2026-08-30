@extends('layouts.app')
@section('title', 'Performance - KMDGI 16')

@section('content')
<div class="bg-[#FFF5F6] min-h-screen flex flex-col relative font-sans">
    
    @include('partials.navbar')

    <!-- HERO HEADER -->
    <div class="w-full bg-slate-900 pt-32 pb-16 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#1A68FF]/30 rounded-full blur-[100px] pointer-events-none"></div>
            <div class="absolute top-20 right-0 w-64 h-64 bg-[#C4F03B]/20 rounded-full blur-[80px] pointer-events-none"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight mb-4">
                Line Up <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#C4F03B] to-[#1A68FF]">Performance</span>
            </h1>
            <p class="text-slate-300 text-sm md:text-lg max-w-2xl mx-auto mb-1 font-medium">
                Saksikan penampilan spesial dari musisi dan kreator terbaik di panggung KMDGI 16.
            </p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow py-12 md:py-16">

        <!-- FILTER & SEARCH BAR (Real-time) -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-12">
            <form id="searchForm" action="{{ route('performance.index') }}" method="GET" class="w-full md:max-w-md relative flex items-center">
                <svg class="w-5 h-5 text-slate-400 absolute left-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="text" id="searchInput" name="search" value="{{ request('search') }}" placeholder="Cari penampil..." class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#1A68FF]/50 focus:border-[#1A68FF] bg-white text-sm font-medium transition-all shadow-sm">
            </form>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <div class="relative w-full md:w-48 flex-shrink-0">
                    <select id="filterSelect" form="searchForm" name="kategori" class="w-full appearance-none bg-white border border-slate-200 text-slate-700 py-3 pl-4 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1A68FF]/50 focus:border-[#1A68FF] text-sm font-medium shadow-sm cursor-pointer">
                        <option value="all" {{ request('kategori') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                        <option value="Guest Star" {{ request('kategori') == 'Guest Star' ? 'selected' : '' }}>Guest Star</option>
                        <option value="Lokal" {{ request('kategori') == 'Lokal' ? 'selected' : '' }}>Artis Lokal</option>
                        <option value="Seni Budaya" {{ request('kategori') == 'Seni Budaya' ? 'selected' : '' }}>Seni Budaya</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        @if($dataPenampil->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-x-10 md:gap-y-10">
                @foreach($dataPenampil as $penampil)
                <a href="{{ route('performance.show', \Illuminate\Support\Str::slug($penampil->nama_penampil)) }}" class="flex flex-col md:flex-row gap-3 md:gap-5 w-full group">
                    <div class="w-full md:w-[180px] lg:w-[200px] aspect-square flex-shrink-0 overflow-hidden rounded-2xl md:rounded-[1.5rem] shadow-sm relative border border-slate-200 bg-white">
                        @if($penampil->cover_penampil)
                            <img src="{{ asset('storage/' . $penampil->cover_penampil) }}" alt="{{ $penampil->nama_penampil }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @elseif($penampil->logo_penampil)
                            <img src="{{ asset('storage/' . $penampil->logo_penampil) }}" alt="{{ $penampil->nama_penampil }}" class="w-full h-full object-contain p-4 transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400 text-[10px] md:text-xs">No Image</div>
                        @endif
                    </div>

                    <div class="flex-grow flex flex-col justify-between py-1">
                        <div>
                            <div class="flex flex-wrap gap-2 mb-2 md:mb-3">
                                <span class="px-2.5 py-0.5 rounded-full border border-slate-200 text-slate-600 bg-white text-[9px] md:text-[10px] font-bold tracking-wide">
                                    {{ $penampil->kategori_penonton ?? 'Umum' }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full border border-blue-200 text-[#1A68FF] bg-blue-50 text-[9px] md:text-[10px] font-bold tracking-wide">
                                    {{ (strtolower($penampil->tipe_pendaftaran) == 'gratis' || $penampil->harga_tiket == 0) ? 'Free' : 'Rp ' . number_format($penampil->harga_tiket, 0, ',', '.') }}
                                </span>
                            </div>
                            <h4 class="text-base md:text-xl font-black text-slate-900 leading-snug mb-2 md:mb-4 group-hover:text-[#1A68FF] transition-colors line-clamp-2">
                                {{ $penampil->nama_penampil }}
                            </h4>
                            <div class="space-y-1 md:space-y-2 mb-4">
                                <div class="flex items-center gap-1.5 md:gap-2 text-[9px] md:text-xs text-slate-700 font-medium">
                                    <svg class="w-3 h-3 md:w-4 md:h-4 text-slate-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                    {{ $penampil->tanggal_tampil ? \Carbon\Carbon::parse($penampil->tanggal_tampil)->locale('id')->translatedFormat('d F Y') : '-' }}
                                </div>
                                <div class="flex items-start gap-1.5 md:gap-2 text-[9px] md:text-xs text-slate-700 font-medium">
                                    <svg class="w-3 h-3 md:w-4 md:h-4 text-slate-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                                    <span class="line-clamp-1 leading-relaxed">{{ $penampil->lokasi_tampil ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-16 flex justify-center">
                {{ $dataPenampil->links() }}
            </div>
        @else
            <div class="bg-white p-12 rounded-[2rem] border border-slate-100 shadow-sm text-center w-full max-w-2xl mx-auto flex flex-col items-center justify-center mt-8">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-5">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <h4 class="text-xl font-bold text-slate-800 mb-2">Penampil Tidak Ditemukan</h4>
                <p class="text-sm text-slate-500">Kami belum memiliki data yang sesuai dengan pencarian Anda.</p>
                <a href="{{ route('performance.index') }}" class="mt-6 inline-flex bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-6 py-2.5 rounded-full text-sm">Reset Pencarian</a>
            </div>
        @endif

    </div>
    @include('partials.footer')
</div>

<script>
    let searchTimeout;
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    const filterSelect = document.getElementById('filterSelect');

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() { searchForm.submit(); }, 500); 
    });

    filterSelect.addEventListener('change', function() { searchForm.submit(); });
</script>
@endsection