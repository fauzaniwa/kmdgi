@extends('layouts.app')
@section('title', 'Galeri Karya - KMDGI 16')

@section('content')
<div class="bg-white min-h-screen pb-20">
    @include('partials.navbar')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 font-sans relative">
        <div id="animation-layer" class="fixed inset-0 pointer-events-none z-50 overflow-hidden"></div>

        <!-- Header Judul & Total Karya -->
        <div class="flex items-end justify-between mb-6">
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Galeri Karya</h1>
            <span class="text-sm font-medium text-slate-500 hidden sm:block">Total {{ $totalKarya }} Karya</span>
        </div>

        <!-- Form Filter Utama -->
        <form method="GET" action="{{ route('katalog.karya.index') }}" id="filterForm">
            <!-- Hidden inputs untuk menyimpan state view & kategori -->
            <input type="hidden" name="view" value="{{ $viewMode }}" id="viewInput">
            <input type="hidden" name="kategori" value="{{ $kategori }}" id="kategoriInput">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <!-- Search Bar -->
                <div class="relative w-full md:max-w-md">
                    <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Kriyasana Mahasiswa..." class="pl-11 pr-4 py-3 border border-slate-200 rounded-xl text-sm w-full focus:outline-none focus:border-[#1A68FF] bg-white transition-all shadow-sm" onchange="this.form.submit()">
                </div>
                
                <!-- Toggle View & Sort -->
                <div class="flex items-center gap-3 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
                    
                    <!-- Toggle Karya vs Kampus -->
                    <div class="flex items-center bg-white border border-slate-200 rounded-xl p-1.5 shrink-0 shadow-sm">
                        <!-- Mode Karya -->
                        <button type="button" onclick="setViewMode('karya')" class="flex items-center gap-2 px-4 py-1.5 rounded-lg text-sm transition-colors {{ $viewMode == 'karya' ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-500 hover:text-slate-900 font-semibold' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
                            Karya
                        </button>
                        <!-- Mode Kampus -->
                        <button type="button" onclick="setViewMode('kampus')" class="flex items-center gap-2 px-4 py-1.5 rounded-lg text-sm transition-colors {{ $viewMode == 'kampus' ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-500 hover:text-slate-900 font-semibold' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                            Kampus
                        </button>
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="relative shrink-0">
                        <select name="sort" onchange="this.form.submit()" class="pl-4 pr-10 py-3 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:outline-none bg-white cursor-pointer shadow-sm appearance-none">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="terpopuler" {{ request('sort') == 'terpopuler' ? 'selected' : '' }}>Terpopuler</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" /></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Kategori (Semua, Simbolik, Simbiotik, Tematik) -->
            <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 scrollbar-hide">
                <button type="button" onclick="setKategori('all')" class="px-5 py-2 rounded-full text-sm transition-colors whitespace-nowrap {{ $kategori == 'all' ? 'bg-slate-800 text-white font-bold' : 'bg-white border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50' }}">Semua Kategori</button>
                <button type="button" onclick="setKategori('Simbolik')" class="px-5 py-2 rounded-full text-sm transition-colors whitespace-nowrap {{ $kategori == 'Simbolik' ? 'bg-slate-800 text-white font-bold' : 'bg-white border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50' }}">Simbolik</button>
                <button type="button" onclick="setKategori('Simbiotik')" class="px-5 py-2 rounded-full text-sm transition-colors whitespace-nowrap {{ $kategori == 'Simbiotik' ? 'bg-slate-800 text-white font-bold' : 'bg-white border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50' }}">Simbiotik</button>
                <button type="button" onclick="setKategori('Tematik')" class="px-5 py-2 rounded-full text-sm transition-colors whitespace-nowrap {{ $kategori == 'Tematik' ? 'bg-slate-800 text-white font-bold' : 'bg-white border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50' }}">Tematik</button>
            </div>
        </form>

        <!-- ============================================== -->
        <!-- RENDER DATA BERDASARKAN MODE VIEW              -->
        <!-- ============================================== -->

        @if($viewMode === 'kampus')
            <!-- -------------------------------------- -->
            <!-- MODE KAMPUS (Group Berdasarkan Kampus) -->
            <!-- -------------------------------------- -->
            @forelse($groupedKaryas as $institusi => $items)
                <div class="mb-14">
                    <!-- Kampus Header Bar -->
                    @php 
                        $kampus = $kampusData[$institusi] ?? null;
                        $logoPath = $kampus ? $kampus->logo_institusi : null;
                        $kota = $kampus ? $kampus->lokasi_kota : 'Indonesia';
                        $defaultLogo = 'https://ui-avatars.com/api/?name='.urlencode($institusi ?? 'U').'&background=f8fafc&color=334155';
                    @endphp
                    
                    <div class="bg-slate-50/70 border border-slate-100 rounded-[1.5rem] p-4 md:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 flex items-center justify-center flex-shrink-0 overflow-hidden bg-white">
                                <img src="{{ $logoPath ? asset('storage/' . $logoPath) : $defaultLogo }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="text-lg md:text-xl font-bold text-slate-900 leading-tight">{{ $institusi }}</h3>
                                <p class="text-[13px] text-slate-500">{{ $kota }}</p>
                            </div>
                        </div>
                        
                        <!-- Tombol Ikuti Kampus (Interaktif UX) -->
                        <button class="shrink-0 flex items-center justify-center gap-2 border border-[#1A68FF] text-[#1A68FF] hover:bg-blue-50 px-5 py-2.5 rounded-xl text-sm font-bold transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Ikuti
                        </button>
                    </div>

                    <!-- Grid Karya Internal Kampus -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                        @foreach($items as $item)
                            @php $slug = \Illuminate\Support\Str::slug($item->judul_karya); @endphp
                            <!-- Render Card Karya -->
                            <div class="group flex flex-col border border-slate-200 rounded-[1.5rem] bg-white hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 p-3">
                                <a href="{{ route('katalog.karya.show', $slug) }}" class="relative block aspect-[4/3] rounded-[1rem] overflow-hidden bg-slate-100 flex-shrink-0">
                                    <img src="{{ asset('storage/' . $item->thumbnail_karya) }}" alt="{{ $item->judul_karya }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-lg shadow-sm">
                                        <span class="text-[11px] font-bold text-blue-600 capitalize">{{ $item->kategori_karya }}</span>
                                    </div>
                                </a>

                                <div class="p-3 pt-4 flex flex-col flex-grow">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0 overflow-hidden bg-white">
                                            <img src="{{ $logoPath ? asset('storage/' . $logoPath) : $defaultLogo }}" class="w-full h-full object-cover">
                                        </div>
                                        <span class="text-xs font-semibold text-slate-800 line-clamp-1">{{ $item->user->institusi ?? 'Umum' }}</span>
                                    </div>

                                    <a href="{{ route('katalog.karya.show', $slug) }}" class="block flex-grow mb-4">
                                        <h2 class="text-[17px] font-bold text-slate-900 leading-snug mb-1 line-clamp-2 group-hover:underline">{{ $item->judul_karya }}</h2>
                                        <p class="text-[13px] text-slate-500 line-clamp-1">{{ $item->kreator_karya }}</p>
                                    </a>

                                    <div class="flex items-center justify-between mb-5 mt-auto">
                                        <div class="flex items-center gap-4">
                                            @php
                                                $isLiked = Auth::check() ? \App\Models\KaryaLike::where('submisi_karya_id', $item->id)->where('user_id', Auth::id())->exists() : session()->has('liked_karya_' . $item->id);
                                            @endphp
                                            <button onclick="likeKarya({{ $item->id }}, this, {{ Auth::check() ? 'true' : 'false' }})" class="flex items-center gap-1.5 {{ $isLiked ? 'text-red-500' : 'text-slate-600' }} hover:text-red-500 transition-colors focus:outline-none relative">
                                                <svg class="w-5 h-5 transition-transform active:scale-75" fill="{{ $isLiked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                                                <span class="text-sm font-bold" id="like-count-{{ $item->id }}">{{ $item->likes_count ?? 0 }}</span>
                                            </button>
                                            <a href="{{ route('katalog.karya.show', $slug) }}#komentar" class="flex items-center gap-1.5 text-slate-600 hover:text-blue-500 transition-colors">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.069C10.22 20.187 11.1 20.25 12 20.25Z" /></svg>
                                                <span class="text-sm font-bold">{{ $item->komentars_count ?? 0 }}</span>
                                            </a>
                                        </div>
                                        <button onclick="openShareModal({{ $item->id }}, '{{ addslashes($item->judul_karya) }}', '{{ route('katalog.karya.show',$slug) }}')" class="text-slate-600 hover:text-slate-900 transition-colors focus:outline-none">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" /></svg>
                                        </button>
                                    </div>

                                    <a href="{{ route('katalog.karya.show', $slug) }}" class="w-full flex items-center justify-center gap-2 bg-[#F4F8FF] hover:bg-[#E6EFFF] text-[#1A68FF] font-bold py-2.5 rounded-xl text-[13px] transition-colors">
                                        Lihat detail karya
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" /></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <hr class="mt-14 border-slate-200/60">
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-slate-500">Belum ada karya yang dipublikasikan pada kategori ini.</p>
                </div>
            @endforelse

        @else
            <!-- -------------------------------------- -->
            <!-- MODE KARYA (Grid Biasa 12 Item/Page)   -->
            <!-- -------------------------------------- -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @forelse($karyas as $item)
                @php $slug = \Illuminate\Support\Str::slug($item->judul_karya); @endphp
                <div class="group flex flex-col border border-slate-200 rounded-[1.5rem] bg-white hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 p-3">
                    <a href="{{ route('katalog.karya.show', $slug) }}" class="relative block aspect-[4/3] rounded-[1rem] overflow-hidden bg-slate-100 flex-shrink-0">
                        <img src="{{ asset('storage/' . $item->thumbnail_karya) }}" alt="{{ $item->judul_karya }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-lg shadow-sm">
                            <span class="text-[11px] font-bold text-blue-600 capitalize">{{ $item->kategori_karya }}</span>
                        </div>
                    </a>

                    <div class="p-3 pt-4 flex flex-col flex-grow">
                        <div class="flex items-center gap-2 mb-3">
                            @php 
                                $kampus = $kampusData[$item->user->institusi] ?? null;
                                $logoPath = $kampus ? $kampus->logo_institusi : null;
                                $defaultLogo = 'https://ui-avatars.com/api/?name='.urlencode($item->user->institusi ?? 'U').'&background=f8fafc&color=334155';
                            @endphp
                            <div class="w-5 h-5 flex items-center justify-center flex-shrink-0 overflow-hidden bg-white">
                                <img src="{{ $logoPath ? asset('storage/' . $logoPath) :$defaultLogo }}" class="w-full h-full object-cover">
                            </div>
                            <span class="text-xs font-semibold text-slate-800 line-clamp-1">{{ $item->user->institusi ?? 'Umum' }}</span>
                        </div>

                        <a href="{{ route('katalog.karya.show', $slug) }}" class="block flex-grow mb-4">
                            <h2 class="text-[17px] font-bold text-slate-900 leading-snug mb-1 line-clamp-2 group-hover:underline">{{ $item->judul_karya }}</h2>
                            <p class="text-[13px] text-slate-500 line-clamp-1">{{ $item->kreator_karya }}</p>
                        </a>

                        <div class="flex items-center justify-between mb-5 mt-auto">
                            <div class="flex items-center gap-4">
                                @php
                                    $isLiked = Auth::check() ? \App\Models\KaryaLike::where('submisi_karya_id', $item->id)->where('user_id', Auth::id())->exists() : session()->has('liked_karya_' .$item->id);
                                @endphp
                                <button onclick="likeKarya({{ $item->id }}, this, {{ Auth::check() ? 'true' : 'false' }})" class="flex items-center gap-1.5 {{ $isLiked ? 'text-red-500' : 'text-slate-600' }} hover:text-red-500 transition-colors focus:outline-none relative">
                                    <svg class="w-5 h-5 transition-transform active:scale-75" fill="{{ $isLiked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                                    <span class="text-sm font-bold" id="like-count-{{ $item->id }}">{{ $item->likes_count ?? 0 }}</span>
                                </button>
                                <a href="{{ route('katalog.karya.show', $slug) }}#komentar" class="flex items-center gap-1.5 text-slate-600 hover:text-blue-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.069C10.22 20.187 11.1 20.25 12 20.25Z" /></svg>
                                    <span class="text-sm font-bold">{{ $item->komentars_count ?? 0 }}</span>
                                </a>
                            </div>
                            <button onclick="openShareModal({{ $item->id }}, '{{ addslashes($item->judul_karya) }}', '{{ route('katalog.karya.show',$slug) }}')" class="text-slate-600 hover:text-slate-900 transition-colors focus:outline-none">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" /></svg>
                            </button>
                        </div>

                        <a href="{{ route('katalog.karya.show', $slug) }}" class="w-full flex items-center justify-center gap-2 bg-[#F4F8FF] hover:bg-[#E6EFFF] text-[#1A68FF] font-bold py-2.5 rounded-xl text-[13px] transition-colors">
                            Lihat detail karya
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" /></svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-slate-500">Belum ada karya yang dipublikasikan pada kategori ini.</p>
                </div>
                @endforelse
            </div>
            
            <!-- Tampilkan pagination hanya jika di mode Karya -->
            @if($karyas->hasPages())
            <div class="mt-10">
                {{ $karyas->links() }}
            </div>
            @endif
        @endif
        
    </div>
</div>

<!-- SISA SCRIPT MODAL (Share & Login) dan SCRIPT JS sama persis seperti sebelumnya -->

<!-- MODAL SHARE CUSTOM -->
<div id="shareModal" class="fixed inset-0 z-[200] bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4 transition-opacity opacity-0 duration-300">
    <div class="absolute inset-0" onclick="closeShareModal()"></div>
    <div class="relative bg-white rounded-[2rem] p-6 md:p-8 max-w-sm w-full shadow-2xl transform scale-95 transition-transform duration-300" id="shareContent">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-black text-slate-900">Bagikan Karya</h3>
            <button type="button" onclick="closeShareModal()" class="text-slate-400 hover:text-red-500 focus:outline-none">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        
        <div class="grid grid-cols-4 gap-4 mb-6">
            <a href="#" id="share-wa" target="_blank" class="social-share-link flex flex-col items-center gap-2 group">
                <div class="w-12 h-12 rounded-full bg-[#25D366] text-white flex items-center justify-center shadow-md group-hover:-translate-y-1 transition-transform">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0C5.383 0 0 5.383 0 12.031c0 2.124.553 4.195 1.603 6.012L.47 24l6.115-1.586a11.968 11.968 0 005.446 1.31h.005C18.683 23.724 24 18.341 24 11.693 24 5.045 18.683 0 12.031 0zm0 21.724c-1.8 0-3.56-.484-5.111-1.401l-.367-.217-3.799.986.996-3.704-.238-.378A9.97 9.97 0 012.031 12.03c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10zm5.488-7.502c-.3-.15-1.782-.88-2.059-.98-.277-.1-.478-.15-.678.15-.2.3-.777.98-.952 1.18-.175.2-.35.225-.65.075-2.008-1.006-3.418-2.618-3.957-3.548-.175-.3-.018-.462.132-.612.135-.135.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525-.075-.15-.678-1.631-.928-2.235-.24-.59-.485-.51-.678-.52-.187-.01-.4-.01-.6-.01-.2 0-.525.075-.8.375-.275.3-.105.735.45 1.5 1.488 2.055 3.018 3.905 4.708 5.6 1.69 1.695 3.518 2.65 5.518 3.325.562.188 1.07.16 1.468.1.442-.067 1.353-.555 1.543-1.09.19-.535.19-.995.132-1.09-.058-.095-.208-.145-.508-.295z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-slate-600">WhatsApp</span>
            </a>
            <a href="#" id="share-fb" target="_blank" class="social-share-link flex flex-col items-center gap-2 group">
                <div class="w-12 h-12 rounded-full bg-[#1877F2] text-white flex items-center justify-center shadow-md group-hover:-translate-y-1 transition-transform">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.407.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.593 1.323-1.325V1.325C24 .593 23.407 0 22.675 0z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-slate-600">Facebook</span>
            </a>
            <a href="#" id="share-tw" target="_blank" class="social-share-link flex flex-col items-center gap-2 group">
                <div class="w-12 h-12 rounded-full bg-black text-white flex items-center justify-center shadow-md group-hover:-translate-y-1 transition-transform">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 22.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-slate-600">X / Twitter</span>
            </a>
            <button type="button" id="copy-link-btn" class="social-share-link flex flex-col items-center gap-2 group focus:outline-none">
                <div class="w-12 h-12 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center shadow-md group-hover:-translate-y-1 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                </div>
                <span class="text-[10px] font-bold text-slate-600">Salin Link</span>
            </button>
        </div>
        
        <div class="relative">
            <input type="text" id="share-url-input" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-xs text-slate-500 bg-slate-50 focus:outline-none pr-4" readonly>
        </div>
    </div>
</div>

<!-- MODAL LOGIN PROMPT -->
<div id="loginPromptModal" class="fixed inset-0 z-[200] bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4 transition-opacity opacity-0 duration-300">
    <div class="absolute inset-0" onclick="closeLoginPrompt()"></div>
    <div class="relative bg-white rounded-[2rem] p-8 max-w-sm w-full shadow-2xl transform scale-95 transition-transform duration-300 text-center" id="loginPromptContent">
        <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-blue-50">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
        </div>
        <h3 class="text-lg font-black text-slate-900 mb-2">Akses Terbatas</h3>
        <p class="text-[13px] text-slate-500 leading-relaxed mb-6">Silakan masuk ke akun Anda terlebih dahulu untuk dapat menyukai karya ini.</p>
        <div class="flex flex-col gap-3">
            <a href="{{ route('login') }}" class="w-full bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition-colors shadow-md shadow-blue-500/20 text-sm">Login Sekarang</a>
            <button type="button" onclick="closeLoginPrompt()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3.5 rounded-xl transition-colors text-sm">Nanti Saja</button>
        </div>
    </div>
</div>

@include('partials.footer')

<style>
    .floating-heart {
        position: absolute;
        animation: floatUp 1.2s ease-out forwards;
        color: #ef4444;
        font-size: 24px;
        pointer-events: none;
    }
    @keyframes floatUp {
        0% { transform: translateY(0) scale(1); opacity: 1; }
        100% { transform: translateY(-80px) scale(1.5); opacity: 0; }
    }
    /* Sembunyikan scrollbar untuk kategori pills */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
    // JS Functions untuk Submit Filter Form secara Dinamis
    function setViewMode(mode) {
        document.getElementById('viewInput').value = mode;
        document.getElementById('filterForm').submit();
    }

    function setKategori(kat) {
        document.getElementById('kategoriInput').value = kat;
        document.getElementById('filterForm').submit();
    }

    // Modal Handlers (Login Prompt)
    function openLoginPrompt() {
        const modal = document.getElementById('loginPromptModal');
        const content = document.getElementById('loginPromptContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeLoginPrompt() {
        const modal = document.getElementById('loginPromptModal');
        const content = document.getElementById('loginPromptContent');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    // Modal Handlers (Share Modal)
    let currentShareId = null;
    function openShareModal(id, title, url) {
        currentShareId = id;
        const modal = document.getElementById('shareModal');
        const content = document.getElementById('shareContent');
        
        const textToShare = `Lihat karya luar biasa "${title}" di Galeri Pameran KMDGI 16! \n\n`;
        const encodedText = encodeURIComponent(textToShare);
        const encodedUrl = encodeURIComponent(url);
        
        document.getElementById('share-url-input').value = url;
        document.getElementById('share-wa').href = `https://api.whatsapp.com/send?text=${encodedText}${encodedUrl}`;
        document.getElementById('share-fb').href = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
        document.getElementById('share-tw').href = `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedText}`;
        
        document.getElementById('copy-link-btn').onclick = function() {
            navigator.clipboard.writeText(textToShare + url).then(() => {
                alert('Teks ajakan dan tautan berhasil disalin!');
                triggerRecordShare(currentShareId);
            });
        };

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeShareModal() {
        const modal = document.getElementById('shareModal');
        const content = document.getElementById('shareContent');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    // API Hit Handlers
    function triggerRecordShare(id) {
        fetch(`/katalog-karya/${id}/share`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).catch(err => console.log(err));
    }

    document.querySelectorAll('.social-share-link').forEach(link => {
        link.addEventListener('click', () => {
            if(currentShareId) triggerRecordShare(currentShareId);
        });
    });

    function likeKarya(id, btnElement, isLoggedIn) {
        if(!isLoggedIn) {
            openLoginPrompt();
            return;
        }

        fetch(`/katalog-karya/${id}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById(`like-count-${id}`).innerText = data.likes;
                if(data.status === 'liked') {
                    const rect = btnElement.getBoundingClientRect();
                    const layer = document.getElementById('animation-layer');
                    
                    const heart = document.createElement('div');
                    heart.innerHTML = '❤️';
                    heart.className = 'floating-heart';
                    heart.style.left = (rect.left + (rect.width/2) - 12) + 'px';
                    heart.style.top = (rect.top - 10) + 'px';
                    
                    layer.appendChild(heart);
                    setTimeout(() => heart.remove(), 1200); 

                    btnElement.classList.remove('text-slate-600');
                    btnElement.classList.add('text-red-500');
                    btnElement.querySelector('svg').setAttribute('fill', 'currentColor');
                } else {
                    btnElement.classList.remove('text-red-500');
                    btnElement.classList.add('text-slate-600');
                    btnElement.querySelector('svg').setAttribute('fill', 'none');
                }
            }
        }).catch(err => console.error(err));
    }
</script>
@endsection