@extends('layouts.app')

@section('title', 'Kolaborator - KMDGI 16')

@section('content')
<div class="bg-white min-h-screen flex flex-col relative font-sans">
    
    @include('partials.navbar')

    <!-- ========================================== -->
    <!-- HERO HEADER                                -->
    <!-- ========================================== -->
    <div class="w-full bg-slate-900 pt-32 pb-16 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#1A68FF]/30 rounded-full blur-[100px] pointer-events-none"></div>
            <div class="absolute top-20 right-0 w-64 h-64 bg-[#FF6B9E]/20 rounded-full blur-[80px] pointer-events-none"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight mb-4">
                Profil <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF6B9E] to-[#1A68FF]">Kolaborator</span>
            </h1>
            <p class="text-slate-300 text-sm md:text-lg max-w-2xl mx-auto mb-1 font-medium">
                Mengenal lebih dekat para tokoh inspiratif, narasumber, dan pelaku kreatif yang turut menyukseskan KMDGI 16.
            </p>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MAIN CONTENT AREA                          -->
    <!-- ========================================== -->
    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow py-12 md:py-16">

        <!-- FILTER & SEARCH BAR (Real-time) -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-12">
            <form id="searchForm" action="{{ route('kolaborator.index') }}" method="GET" class="w-full md:max-w-md relative flex items-center">
                <svg class="w-5 h-5 text-slate-400 absolute left-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="text" id="searchInput" name="search" value="{{ request('search') }}" placeholder="Cari nama atau profesi..." class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#1A68FF]/50 focus:border-[#1A68FF] bg-white text-sm font-medium transition-all shadow-sm">
            </form>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <div class="relative w-full md:w-48 flex-shrink-0">
                    <select id="filterSelect" form="searchForm" name="peran" class="w-full appearance-none bg-white border border-slate-200 text-slate-700 py-3 pl-4 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1A68FF]/50 focus:border-[#1A68FF] text-sm font-medium shadow-sm cursor-pointer">
                        <option value="all" {{ request('peran') == 'all' ? 'selected' : '' }}>Semua Peran</option>
                        <option value="Narasumber" {{ request('peran') == 'Narasumber' ? 'selected' : '' }}>Narasumber</option>
                        <option value="Juri" {{ request('peran') == 'Juri' ? 'selected' : '' }}>Juri</option>
                        <option value="Guest Star" {{ request('peran') == 'Guest Star' ? 'selected' : '' }}>Guest Star</option>
                        <option value="Moderator" {{ request('peran') == 'Moderator' ? 'selected' : '' }}>Moderator</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- DAFTAR KOLABORATOR (GRID) -->
        @if($kolaborators->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
                @foreach($kolaborators as $kolaborator)
                <a href="{{ url('/kolaborator/' . \Illuminate\Support\Str::slug($kolaborator->nama)) }}" class="group relative w-full aspect-[3/4] rounded-[1.5rem] md:rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 bg-slate-900 block border border-slate-200">
                    
                    @if($kolaborator->foto)
                        <img src="{{ asset('storage/' . $kolaborator->foto) }}" alt="{{ $kolaborator->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90 group-hover:opacity-100">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-slate-800 text-slate-400 text-6xl font-black group-hover:scale-105 transition-transform duration-700">
                            {{ strtoupper(substr($kolaborator->nama, 0, 1)) }}
                        </div>
                    @endif
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-300"></div>

                    <!-- Elemen Dekoratif Tab Warna KMDGI -->
                    <div class="absolute bottom-0 left-0 w-full flex flex-col transition-transform duration-300 transform group-hover:-translate-y-1">
                        
                        <div class="flex items-end pl-0">
                            <div class="bg-[#1A68FF] group-hover:bg-blue-700 h-8 md:h-10 w-[45%] rounded-tr-[1.25rem] relative z-20 transition-colors duration-300"></div>
                            <div class="bg-[#FF6B9E] h-5 md:h-7 w-12 md:w-16 rounded-tr-lg -ml-4 md:-ml-6 relative z-10 transition-transform duration-300 group-hover:-translate-y-1.5 transform -skew-x-[20deg] origin-bottom-left"></div>
                            <div class="bg-[#C4F03B] h-3 md:h-5 w-10 md:w-14 rounded-tr-md -ml-3 md:-ml-5 relative z-0 transition-transform duration-300 group-hover:-translate-y-3 transform -skew-x-[20deg] origin-bottom-left"></div>
                        </div>
                        
                        <div class="bg-[#1A68FF] group-hover:bg-blue-700 w-full px-5 pt-4 pb-6 md:px-6 md:pt-5 md:pb-8 z-20 relative flex flex-col justify-end transition-colors duration-300">
                            <span class="inline-block px-2 py-0.5 bg-white/20 backdrop-blur-sm text-white text-[9px] font-bold tracking-widest uppercase rounded-full w-fit mb-2 border border-white/30">
                                {{ $kolaborator->peran_kolaborasi }}
                            </span>
                            <h3 class="text-white font-bold text-lg md:text-xl truncate mb-1">
                                {{ $kolaborator->nama }}
                            </h3>
                            <p class="text-blue-100/90 text-xs md:text-sm font-medium truncate">
                                {{ $kolaborator->profesi }}
                            </p>
                        </div>

                    </div>
                </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-16 flex justify-center">
                {{ $kolaborators->links() }}
            </div>
        @else
            <!-- Tampilan Kosong Pencarian -->
            <div class="bg-white p-12 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] text-center w-full max-w-2xl mx-auto flex flex-col items-center justify-center mt-8">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-5">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <h4 class="text-xl font-bold text-slate-800 mb-2">Kolaborator Tidak Ditemukan</h4>
                <p class="text-sm text-slate-500 leading-relaxed">Kami tidak menemukan profil kolaborator yang sesuai dengan kata kunci atau filter pencarian Anda.</p>
                <a href="{{ route('kolaborator.index') }}" class="mt-6 inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-6 py-2.5 rounded-full text-sm transition-colors">
                    Reset Pencarian
                </a>
            </div>
        @endif

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
    const filterSelect = document.getElementById('filterSelect');

    // Menunggu pengguna selesai mengetik (500ms) sebelum submit otomatis
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            searchForm.submit();
        }, 500); 
    });

    // Otomatis submit saat dropdown filter diubah
    filterSelect.addEventListener('change', function() {
        searchForm.submit();
    });
</script>
@endsection