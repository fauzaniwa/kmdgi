@extends('layouts.app')
@section('title', 'Tentang KMDGI - Forum Komunikasi Mahasiswa Desain Grafis Indonesia')

@section('content')

<!-- Menyimpan Array Gambar Pendahuluan untuk keperluan Lightbox JS -->
@php
    $galleryImages = [];
    if($about) {
        if($about->image_1) $galleryImages[] = asset('storage/'.$about->image_1);
        if($about->image_2) $galleryImages[] = asset('storage/'.$about->image_2);
        if($about->image_3) $galleryImages[] = asset('storage/'.$about->image_3);
        if($about->image_4) $galleryImages[] = asset('storage/'.$about->image_4);
        if($about->image_5) $galleryImages[] = asset('storage/'.$about->image_5);
    }
@endphp

<!-- CSS Kustom untuk Efek Animasi & Line Timeline -->
<style>
    .timeline-container {
        position: relative;
    }
    .timeline-line {
        position: absolute;
        top: 24px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: #f1f5f9; /* slate-100 */
        z-index: 0;
    }
    .hide-scroll::-webkit-scrollbar {
        display: none;
    }
    .hide-scroll {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    /* Scroll Behavior untuk Smooth Scrolling ke Anchor */
    html {
        scroll-behavior: smooth;
    }
</style>

<div class="bg-white min-h-screen flex flex-col font-sans">
    @include('partials.navbar')

    <!-- ========================================== -->
    <!-- 1. HEADER HERO                             -->
    <!-- ========================================== -->
    <section class="relative w-full h-[35vh] md:h-[45vh] bg-slate-900 overflow-hidden flex items-end">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/bg-abstract-tube-desktop.png') }}" class="w-full h-full object-cover opacity-60 mix-blend-screen" alt="Abstract Header">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
        </div>
        <div class="relative z-10 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 pb-10 md:pb-16">
            <h1 class="text-3xl md:text-5xl lg:text-[4rem] font-black text-white tracking-tight leading-none drop-shadow-md">
                Tentang KMDGI
            </h1>
        </div>
    </section>

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24 space-y-24 md:space-y-32">

        <!-- ========================================== -->
        <!-- 2. PENDAHULUAN (Kolom Teks & Kolase Gambar)-->
        <!-- ========================================== -->
        <section id="pendahuluan" class="flex flex-col lg:flex-row gap-12 lg:gap-16 items-start">
            
            <div class="w-full lg:w-3/5">
                <h2 class="text-2xl md:text-[2rem] font-black text-slate-900 mb-6 md:mb-8">Pendahuluan</h2>
                @if($about && $about->description)
                <div class="prose prose-slate prose-p:leading-relaxed prose-p:text-slate-600 prose-p:text-sm md:prose-p:text-[15px] prose-p:mb-5 max-w-none font-medium">
                    {!! $about->description !!}
                </div>
                @else
                <p class="text-slate-500 italic">Data pendahuluan belum ditambahkan oleh Admin.</p>
                @endif
            </div>

            <!-- Grid Kolase Foto -->
            <div class="w-full lg:w-2/5">
                <div class="grid grid-cols-2 grid-rows-3 gap-3 md:gap-4 relative h-[400px] md:h-[500px]">
                    @if(count($galleryImages) > 0)
                        
                        @isset($galleryImages[0])
                        <div onclick="openLightbox(0)" class="col-span-1 row-span-2 rounded-[2rem] overflow-hidden shadow-sm bg-slate-100 transition-transform duration-300 hover:scale-[1.03] hover:z-10 relative border border-slate-200 cursor-pointer group">
                            <img src="{{ $galleryImages[0] }}" alt="Dokumentasi 1" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"><svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg></div>
                        </div>
                        @endisset
                        
                        @isset($galleryImages[1])
                        <div onclick="openLightbox(1)" class="col-span-1 row-span-1 rounded-[1.5rem] overflow-hidden shadow-sm bg-slate-100 transition-transform duration-300 hover:scale-[1.03] hover:z-10 relative border border-slate-200 cursor-pointer group">
                            <img src="{{ $galleryImages[1] }}" alt="Dokumentasi 2" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"><svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg></div>
                        </div>
                        @endisset

                        @isset($galleryImages[2])
                        <div onclick="openLightbox(2)" class="col-span-1 row-span-1 rounded-[1.5rem] overflow-hidden shadow-sm bg-slate-100 transition-transform duration-300 hover:scale-[1.03] hover:z-10 relative border border-slate-200 cursor-pointer group">
                            <img src="{{ $galleryImages[2] }}" alt="Dokumentasi 3" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"><svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg></div>
                        </div>
                        @endisset

                        @isset($galleryImages[3])
                        <div onclick="openLightbox(3)" class="col-span-1 row-span-1 rounded-[1.5rem] overflow-hidden shadow-sm bg-slate-100 transition-transform duration-300 hover:scale-[1.03] hover:z-10 relative border border-slate-200 cursor-pointer group">
                            <img src="{{ $galleryImages[3] }}" alt="Dokumentasi 4" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"><svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg></div>
                        </div>
                        @endisset

                        @isset($galleryImages[4])
                        <div onclick="openLightbox(4)" class="col-span-1 row-span-1 rounded-[1.5rem] overflow-hidden shadow-sm bg-slate-100 transition-transform duration-300 hover:scale-[1.03] hover:z-10 relative border border-slate-200 cursor-pointer group">
                            <img src="{{ $galleryImages[4] }}" alt="Dokumentasi 5" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"><svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg></div>
                        </div>
                        @endisset

                    @else
                        <!-- Fallback jika tidak ada gambar sama sekali -->
                        <div class="col-span-2 row-span-3 rounded-[2rem] overflow-hidden bg-slate-50 flex items-center justify-center text-slate-400 text-sm font-medium border-2 border-dashed border-slate-200">
                            Belum ada foto dokumentasi
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- ========================================== -->
        <!-- 3. SEJARAH PENYELENGGARAAN (Timeline)      -->
        <!-- ========================================== -->
        <section id="sejarah" class="w-full">
            <h2 class="text-2xl md:text-[2rem] font-black text-slate-900 mb-10 md:mb-16">Sejarah Penyelenggaraan</h2>
            
            @if(isset($sejarahList) && $sejarahList->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-y-12 gap-x-6 relative timeline-container">
                    <!-- Garis Penghubung Horizontal (Z-Index 0) -->
                    <div class="timeline-line hidden md:block"></div>

                    @foreach($sejarahList as $sejarah)
                    <div class="relative z-10 flex flex-col group">
                        
                        <!-- Dot Indicator -->
                        <div class="flex items-center mb-6">
                            <div class="w-3.5 h-3.5 rounded-full bg-slate-200 border-2 border-white ring-2 ring-slate-100 group-hover:bg-[#FF6B9E] group-hover:ring-[#FF6B9E]/30 transition-all duration-300"></div>
                            <!-- Garis mobile connector (opsional) -->
                            <div class="h-px bg-slate-100 flex-grow md:hidden ml-4"></div>
                        </div>

                        <!-- Konten Data -->
                        <div class="pr-4 md:pr-8">
                            <h3 class="text-xl md:text-2xl font-black text-slate-900 mb-2 transition-colors">{{ $sejarah->tahun }}</h3>
                            <h4 class="text-sm font-bold text-[#1A68FF] mb-2">{{ $sejarah->title }}</h4>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed line-clamp-3">
                                {{ strip_tags($sejarah->description) }}
                            </p>
                        </div>

                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-slate-50 p-8 rounded-2xl border border-slate-100 text-center">
                    <p class="text-slate-500 font-medium">Data sejarah penyelenggaraan belum tersedia.</p>
                </div>
            @endif
        </section>


        <!-- ========================================== -->
        <!-- MULAILAH KONTEN DINAMIS BERDASARKAN EDISI  -->
        <!-- ========================================== -->
        
        <div id="edisi-kmdgi" class="scroll-mt-32">
            <!-- NAVIGASI TAB EDISI KMDGI (Filter Dinamis) -->
            @if(isset($semuaEdisi) && $semuaEdisi->count() > 0)
            <div class="flex overflow-x-auto hide-scroll border-b border-slate-200 gap-6 md:gap-10 pb-0.5 mb-16">
                @foreach($semuaEdisi as $edisi)
                    @php
                        // Menentukan edisi mana yang sedang aktif tampil
                        // Berdasarkan URL Parameter (?edisi=) atau fallback edisiAktif yang dikirim controller
                        $isActiveTab = ($edisiAktif && $edisiAktif->id == $edisi->id);
                    @endphp
                    <a href="{{ route('tentang-kami', ['edisi' => $edisi->id]) }}#edisi-kmdgi" 
                       class="whitespace-nowrap pb-4 text-sm md:text-base font-bold transition-all relative outline-none flex items-center gap-2 {{ $isActiveTab ? 'text-slate-900' : 'text-slate-400 hover:text-slate-600' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                        {{ $edisi->nama_edisi }}
                        
                        <!-- Indicator Bar Bawah Aktif -->
                        <div class="absolute bottom-0 left-0 w-full h-[3px] bg-slate-900 rounded-t-sm transition-opacity {{ $isActiveTab ? 'opacity-100' : 'opacity-0' }}"></div>
                    </a>
                @endforeach
            </div>
            @endif

            @if($edisiAktif)
                <div class="space-y-24 md:space-y-32">
                    
                    <!-- ========================================== -->
                    <!-- 4. TEMA KMDGI (Latar Belakang & Visual)    -->
                    <!-- ========================================== -->
                    <section id="tema" class="flex flex-col gap-10 lg:gap-16">
                        <div class="w-full">
                            <h2 class="text-2xl md:text-[2rem] font-black text-slate-900 mb-6 md:mb-8">Latar Belakang Tema</h2>
                            @if($edisiAktif->lb_deskripsi)
                                <div class="prose prose-slate prose-p:leading-relaxed prose-p:text-slate-600 prose-p:text-sm md:prose-p:text-[15px] prose-p:mb-5 max-w-none font-medium columns-1 md:columns-2 gap-10">
                                    {!! $edisiAktif->lb_deskripsi !!}
                                </div>
                            @else
                                <p class="text-slate-500 italic">Data Latar Belakang untuk edisi ini belum dikonfigurasi.</p>
                            @endif
                        </div>

                        <div class="w-full">
                            <h2 class="text-2xl md:text-[2rem] font-black text-slate-900 mb-8 md:mb-10">Tema KMDGI {{ str_replace('KMDGI', '', $edisiAktif->nama_edisi) }}</h2>
                            @if($edisiAktif->tema_logo)
                                <div class="w-full aspect-[21/9] md:aspect-[3/1] bg-slate-50 border border-slate-100 rounded-[2rem] flex items-center justify-center p-8 mb-10 overflow-hidden shadow-sm">
                                    <img src="{{ asset('storage/'.$edisiAktif->tema_logo) }}" alt="Logo Tema KMDGI" class="w-full h-full object-contain mix-blend-multiply">
                                </div>
                            @endif
                            @if($edisiAktif->tema_deskripsi)
                                <div class="prose prose-slate prose-p:leading-relaxed prose-p:text-slate-600 prose-p:text-sm md:prose-p:text-[15px] prose-p:mb-5 max-w-none font-medium columns-1 md:columns-2 gap-10">
                                    {!! $edisiAktif->tema_deskripsi !!}
                                </div>
                            @endif
                        </div>
                    </section>

                    <!-- ========================================== -->
                    <!-- 5. KAMPUS DELEGASI (Tabs Regional)         -->
                    <!-- ========================================== -->
                    <section id="delegasi" class="w-full">
                        <h2 class="text-2xl md:text-[2rem] font-black text-slate-900 mb-8">Data Kampus (Anggota & Peninjau)</h2>

                        @if(isset($kampusDelegasi) && $kampusDelegasi->count() > 0)
                            <!-- Navigasi Tabs Kota/Regional -->
                            <div class="flex overflow-x-auto hide-scroll border-b border-slate-200 mb-8 gap-6 md:gap-10 pb-1">
                                @php $firstCity = true; @endphp
                                @foreach($kampusDelegasi->keys() as $kota)
                                    <button class="tab-btn whitespace-nowrap pb-3 text-sm md:text-base font-bold transition-all relative outline-none {{ $firstCity ? 'text-slate-900' : 'text-slate-400 hover:text-slate-600' }}" onclick="openCityTab('{{ Str::slug($kota) }}', this)">
                                        {{ $kota }}
                                        <div class="tab-indicator absolute bottom-0 left-0 w-full h-[3px] bg-slate-900 rounded-t-sm transition-opacity {{ $firstCity ? 'opacity-100' : 'opacity-0' }}"></div>
                                    </button>
                                    @php $firstCity = false; @endphp
                                @endforeach
                            </div>

                            <!-- Konten List Kampus per Kota -->
                            @php $firstContent = true; @endphp
                            @foreach($kampusDelegasi as $kota => $kampuses)
                                <div id="city-{{ Str::slug($kota) }}" class="tab-content transition-opacity duration-300 {{ $firstContent ? 'block opacity-100' : 'hidden opacity-0' }}">
                                    <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-4 gap-x-8">
                                        @foreach($kampuses as $kampus)
                                        <li class="flex items-start justify-between gap-3 border-b border-slate-100 pb-3 last:border-0 last:pb-0">
                                            <div class="flex items-start gap-3 pr-4">
                                                <span class="text-[#1A68FF] mt-0.5">•</span>
                                                <span class="text-sm md:text-[15px] font-medium text-slate-700 leading-snug">{{ $kampus->nama_institusi }}</span>
                                            </div>
                                            <span class="text-[10px] font-bold px-2 py-1 rounded-md border border-slate-200 text-slate-500 whitespace-nowrap bg-slate-50">
                                                {{ $kampus->pivot->status_keanggotaan }}
                                            </span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @php $firstContent = false; @endphp
                            @endforeach
                        @else
                            <div class="bg-slate-50 p-8 rounded-2xl border border-slate-100 text-center">
                                <p class="text-slate-500 font-medium">Data kampus (Anggota/Peninjau) pada Edisi ini belum tersedia.</p>
                            </div>
                        @endif
                    </section>

                    <!-- ========================================== -->
                    <!-- 6. JENIS KARYA (Cards)                     -->
                    <!-- ========================================== -->
                    <section id="jenis-karya" class="w-full">
                        <h2 class="text-2xl md:text-[2rem] font-black text-slate-900 mb-8 md:mb-12">Jenis Karya Pameran</h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-6 lg:gap-10">
                            
                            <!-- Karya Tematik -->
                            <div class="flex flex-col group">
                                <div class="w-full aspect-[4/3] rounded-2xl md:rounded-3xl overflow-hidden bg-slate-100 mb-5 relative border border-slate-200">
                                    @if($karyaTematik && $karyaTematik->thumbnail)
                                        <img src="{{ asset('storage/'.$karyaTematik->thumbnail) }}" alt="Karya Tematik" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold text-sm bg-slate-100">Karya Tematik</div>
                                    @endif
                                </div>
                                <h3 class="text-xl font-black text-slate-900 mb-3">Karya Tematik</h3>
                                <div class="text-[13px] md:text-sm text-slate-600 font-medium leading-relaxed prose prose-slate">
                                    @if($karyaTematik && $karyaTematik->deskripsi)
                                        {!! $karyaTematik->deskripsi !!}
                                    @else
                                        <p>Deskripsi karya tematik belum ditambahkan pada edisi ini.</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Karya Simbiotik -->
                            <div class="flex flex-col group">
                                <div class="w-full aspect-[4/3] rounded-2xl md:rounded-3xl overflow-hidden bg-slate-100 mb-5 relative border border-slate-200">
                                    @if($karyaSimbiotik && $karyaSimbiotik->thumbnail)
                                        <img src="{{ asset('storage/'.$karyaSimbiotik->thumbnail) }}" alt="Karya Simbiotik" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold text-sm bg-slate-100">Karya Simbiotik</div>
                                    @endif
                                </div>
                                <h3 class="text-xl font-black text-slate-900 mb-3">Karya Simbiotik</h3>
                                <div class="text-[13px] md:text-sm text-slate-600 font-medium leading-relaxed prose prose-slate">
                                    @if($karyaSimbiotik && $karyaSimbiotik->deskripsi)
                                        {!! $karyaSimbiotik->deskripsi !!}
                                    @else
                                        <p>Deskripsi karya simbiotik belum ditambahkan pada edisi ini.</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Karya Simbolik -->
                            <div class="flex flex-col group">
                                <div class="w-full aspect-[4/3] rounded-2xl md:rounded-3xl overflow-hidden bg-slate-100 mb-5 relative border border-slate-200">
                                    @if($karyaSimbolik && $karyaSimbolik->thumbnail)
                                        <img src="{{ asset('storage/'.$karyaSimbolik->thumbnail) }}" alt="Karya Simbolik" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold text-sm bg-slate-100">Karya Simbolik</div>
                                    @endif
                                </div>
                                <h3 class="text-xl font-black text-slate-900 mb-3">Karya Simbolik</h3>
                                <div class="text-[13px] md:text-sm text-slate-600 font-medium leading-relaxed prose prose-slate">
                                    @if($karyaSimbolik && $karyaSimbolik->deskripsi)
                                        {!! $karyaSimbolik->deskripsi !!}
                                    @else
                                        <p>Deskripsi karya simbolik belum ditambahkan pada edisi ini.</p>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </section>

                </div>
            @else
                <!-- Fallback jika tidak ada satupun data edisi kmdgi -->
                <div class="bg-red-50 text-red-600 p-8 rounded-2xl border border-red-100 text-center mt-10">
                    <h3 class="font-bold text-lg mb-2">Oops! Edisi Tidak Ditemukan</h3>
                    <p class="text-sm font-medium">Data tema, kampus delegasi, dan jenis karya untuk Edisi KMDGI yang Anda pilih tidak tersedia atau belum dikonfigurasi.</p>
                </div>
            @endif

        </div>

    </div>

    @include('partials.footer')
</div>

<!-- ========================================== -->
<!-- LIGHTBOX MODAL UNTUK GALERI GAMBAR         -->
<!-- ========================================== -->
<div id="lightboxModal" class="fixed inset-0 z-[120] hidden bg-slate-900/95 flex flex-col items-center justify-center backdrop-blur-md transition-opacity duration-300 opacity-0 pointer-events-none">
    
    <!-- Tombol Tutup -->
    <button onclick="closeLightbox()" class="absolute top-6 right-6 md:top-8 md:right-8 text-white/50 hover:text-white bg-white/10 hover:bg-white/20 rounded-full p-2 transition-colors z-50">
        <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <div class="relative w-full max-w-5xl h-[70vh] md:h-[85vh] flex items-center justify-center px-4 md:px-16 group">
        <!-- Gambar Pembesaran -->
        <img id="lightboxImage" src="" alt="Zoomed" class="max-w-full max-h-full object-contain drop-shadow-2xl transition-transform duration-300 transform scale-95">
        
        <!-- Indikator Angka -->
        <div class="absolute bottom-[-40px] left-1/2 transform -translate-x-1/2 text-white/70 font-medium text-sm tracking-widest bg-black/40 px-4 py-1 rounded-full backdrop-blur-sm">
            <span id="lightboxCounter">1</span> / <span id="lightboxTotal"></span>
        </div>

        <!-- Tombol Prev -->
        <button onclick="changeLightboxImage(-1)" class="absolute left-4 md:left-0 top-1/2 transform -translate-y-1/2 text-white/50 hover:text-white bg-black/40 hover:bg-black/60 p-3 md:p-4 rounded-full transition-all md:opacity-0 md:group-hover:opacity-100 z-50">
            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        </button>

        <!-- Tombol Next -->
        <button onclick="changeLightboxImage(1)" class="absolute right-4 md:right-0 top-1/2 transform -translate-y-1/2 text-white/50 hover:text-white bg-black/40 hover:bg-black/60 p-3 md:p-4 rounded-full transition-all md:opacity-0 md:group-hover:opacity-100 z-50">
            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        </button>
    </div>
</div>

<script>
    // --- FUNGSI TABS KAMPUS DELEGASI ---
    function openCityTab(cityId, element) {
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(c => {
            c.classList.remove('block', 'opacity-100');
            c.classList.add('hidden', 'opacity-0');
        });

        const btns = document.querySelectorAll('.tab-btn');
        btns.forEach(b => {
            b.classList.remove('text-slate-900');
            b.classList.add('text-slate-400');
            b.querySelector('.tab-indicator').classList.replace('opacity-100', 'opacity-0');
        });

        const targetContent = document.getElementById('city-' + cityId);
        if(targetContent) {
            targetContent.classList.remove('hidden');
            setTimeout(() => targetContent.classList.replace('opacity-0', 'opacity-100'), 20);
        }

        element.classList.remove('text-slate-400');
        element.classList.add('text-slate-900');
        element.querySelector('.tab-indicator').classList.replace('opacity-0', 'opacity-100');
    }

    // --- FUNGSI LIGHTBOX IMAGE GALLERY ---
    const galleryImages = @json($galleryImages);
    let currentImageIndex = 0;
    const lightboxModal = document.getElementById('lightboxModal');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxCounter = document.getElementById('lightboxCounter');
    
    // Inisialisasi Total Gambar
    if(document.getElementById('lightboxTotal')) {
        document.getElementById('lightboxTotal').innerText = galleryImages.length;
    }

    function openLightbox(index) {
        if(galleryImages.length === 0) return;
        currentImageIndex = index;
        updateLightboxContent();
        
        lightboxModal.classList.remove('hidden');
        // Transisi Smooth
        setTimeout(() => {
            lightboxModal.classList.remove('opacity-0', 'pointer-events-none');
            lightboxImage.classList.remove('scale-95');
            lightboxImage.classList.add('scale-100');
        }, 10);
        document.body.style.overflow = 'hidden'; // Matikan scroll belakang
    }

    function closeLightbox() {
        lightboxModal.classList.add('opacity-0', 'pointer-events-none');
        lightboxImage.classList.remove('scale-100');
        lightboxImage.classList.add('scale-95');
        setTimeout(() => {
            lightboxModal.classList.add('hidden');
        }, 300);
        document.body.style.overflow = 'auto'; // Nyalakan scroll belakang
    }

    function changeLightboxImage(step) {
        currentImageIndex += step;
        // Logic loop index gambar
        if (currentImageIndex < 0) currentImageIndex = galleryImages.length - 1;
        if (currentImageIndex >= galleryImages.length) currentImageIndex = 0;
        
        // Efek kedip tipis saat gambar ganti
        lightboxImage.style.opacity = 0.5;
        setTimeout(() => {
            updateLightboxContent();
            lightboxImage.style.opacity = 1;
        }, 150);
    }

    function updateLightboxContent() {
        lightboxImage.src = galleryImages[currentImageIndex];
        lightboxCounter.innerText = currentImageIndex + 1;
    }

    // Navigasi Keyboard (Kiri, Kanan, Escape)
    document.addEventListener('keydown', function(e) {
        if(!lightboxModal.classList.contains('hidden')) {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') changeLightboxImage(-1);
            if (e.key === 'ArrowRight') changeLightboxImage(1);
        }
    });

    // Menutup modal lightbox saat klik di luar gambar
    lightboxModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeLightbox();
        }
    });
</script>

@endsection