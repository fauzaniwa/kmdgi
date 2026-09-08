@extends('layouts.app')
@section('title', 'Beranda - KMDGI 16')

@section('content')

@php
$bgImage = $header && $header->gambar_background ? asset('storage/' . $header->gambar_background) : asset('images/default-hero.jpg');
$bgVideo = $header && $header->video_background ? asset('storage/' . $header->video_background) : null;
@endphp

<!-- CSS Kustom Khusus Animasi -->
<style>
    .marquee-container {
        width: 100%;
        overflow: hidden;
        display: flex;
    }

    .marquee-track {
        display: flex;
        gap: 2rem;
        animation: scroll-marquee 30s linear infinite;
    }

    .marquee-container:hover .marquee-track {
        animation-play-state: paused;
    }

    @keyframes scroll-marquee {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    /* Menyembunyikan Scrollbar pada Slider Dokumentasi */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<div class="relative w-full min-h-screen bg-white font-sans flex flex-col overflow-x-hidden">

    <!-- NAVBAR -->
    <div class="absolute top-0 left-0 w-full z-50">
        @include('partials.navbar')
    </div>

    <!-- ========================================== -->
    <!-- 1. HERO SECTION (HEADER)                   -->
    <!-- ========================================== -->
    <section class="relative w-full min-h-screen flex flex-col justify-center items-center">
        <div class="absolute inset-0 z-0">
            @if($bgVideo)
            <video autoplay loop muted playsinline class="w-full h-full object-cover object-center">
                <source src="{{ $bgVideo }}" type="video/mp4">
            </video>
            @else
            <img src="{{ $bgImage }}" alt="Background KMDGI" class="w-full h-full object-cover object-center opacity-100" />
            @endif
        </div>

        <div class="relative z-10 w-full max-w-5xl mx-auto px-4 md:px-6 pt-32 pb-20 text-center flex flex-col items-center">
            @if($header && $header->judul)
            <h1 class="text-4xl md:text-6xl lg:text-[4.5rem] font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6 drop-shadow-sm">
                {!! $header->judul !!}
            </h1>
            @endif

            @if($header && $header->deskripsi)
            <p class="text-base md:text-xl text-slate-600 mb-10 max-w-2xl leading-relaxed font-medium">
                {!! $header->deskripsi !!}
            </p>
            @endif

            @if($header && $header->is_active_countdown && $header->waktu_countdown)
            <div class="mb-12 flex items-center justify-center gap-2 md:gap-4" id="countdown-wrapper" data-time="{{ $header->waktu_countdown }}">
                <div class="w-[72px] h-[85px] md:w-28 md:h-[105px] bg-[#EEF2F6]/90 backdrop-blur-md rounded-2xl flex flex-col items-center justify-center shadow-sm">
                    <span class="text-3xl md:text-5xl font-black text-kmdgi-primary" id="cd-hari">00</span>
                    <span class="text-[10px] md:text-xs font-bold text-slate-400 mt-1 uppercase">Hari</span>
                </div>
                <span class="text-slate-300 text-2xl md:text-4xl font-bold mb-4">:</span>
                <div class="w-[72px] h-[85px] md:w-28 md:h-[105px] bg-[#EEF2F6]/90 backdrop-blur-md rounded-2xl flex flex-col items-center justify-center shadow-sm">
                    <span class="text-3xl md:text-5xl font-black text-kmdgi-primary" id="cd-jam">00</span>
                    <span class="text-[10px] md:text-xs font-bold text-slate-400 mt-1 uppercase">Jam</span>
                </div>
                <span class="text-slate-300 text-2xl md:text-4xl font-bold mb-4">:</span>
                <div class="w-[72px] h-[85px] md:w-28 md:h-[105px] bg-[#EEF2F6]/90 backdrop-blur-md rounded-2xl flex flex-col items-center justify-center shadow-sm">
                    <span class="text-3xl md:text-5xl font-black text-kmdgi-primary" id="cd-menit">00</span>
                    <span class="text-[10px] md:text-xs font-bold text-slate-400 mt-1 uppercase">Menit</span>
                </div>
                <span class="text-slate-300 text-2xl md:text-4xl font-bold mb-4">:</span>
                <div class="w-[72px] h-[85px] md:w-28 md:h-[105px] bg-[#EEF2F6]/90 backdrop-blur-md rounded-2xl flex flex-col items-center justify-center shadow-sm">
                    <span class="text-3xl md:text-5xl font-black text-kmdgi-primary" id="cd-detik">00</span>
                    <span class="text-[10px] md:text-xs font-bold text-slate-400 mt-1 uppercase">Detik</span>
                </div>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 w-full sm:w-auto">
                <a href="{{ $header->link_tombol_utama ?? route('register') }}" class="w-full sm:w-auto inline-flex justify-center items-center bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-3.5 px-10 rounded-full shadow-lg shadow-kmdgi-primary/20 transition-transform transform hover:-translate-y-1 text-sm md:text-base">
                    {{ $header->teks_tombol_utama ?? 'Dashboard' }}
                </a>

                @if($header && $header->teks_tombol_sekunder)
                <a href="{{ $header->link_tombol_sekunder }}" class="w-full sm:w-auto inline-flex justify-center items-center bg-white hover:bg-slate-50 text-kmdgi-primary font-bold py-3.5 px-10 rounded-full border border-slate-200 transition-transform transform hover:-translate-y-1 text-sm md:text-base gap-2 shadow-sm">
                    @if(stripos($header->teks_tombol_sekunder, 'instagram') !== false)
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363-.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                    </svg>
                    @endif
                    {{ $header->teks_tombol_sekunder }}
                </a>
                @endif
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- 2. SECTION KOMPETISI (LOMBA)               -->
    <!-- ========================================== -->
    @if(isset($lombas) && $lombas->count() > 0)
    <section class="py-16 md:py-24 bg-white relative z-20" id="kompetisi">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 md:mb-20">
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight mb-4">Ajang Kompetisi</h2>
                <p class="text-slate-500 max-w-2xl mx-auto text-sm md:text-lg">Tunjukkan bakat terbaikmu dan jadilah juara di ajang kompetisi bergengsi berskala nasional ini.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-10 gap-y-16 lg:gap-y-20">
                @foreach($lombas as $lomba)

                @php
                $isOpenLomba = true;
                $timelineLomba = is_string($lomba->timeline) ? json_decode($lomba->timeline, true) : ($lomba->timeline ?? []);
                if (is_array($timelineLomba) && count($timelineLomba) > 0) {
                foreach ($timelineLomba as $fase) {
                if (isset($fase['status']) && $fase['status'] === 'batas_pendaftaran') {
                if (time() > strtotime($fase['tanggal'] . ' 23:59:59')) {
                $isOpenLomba = false;
                }
                }
                }
                }
                @endphp

                <div class="relative w-full pt-6">
                    <div class="absolute top-0 left-0 flex items-end">
                        <div class="bg-kmdgi-primary h-8 w-32 md:w-36 rounded-t-[1rem] relative z-20"></div>
                        <div class="bg-[#C4F03B] h-6 w-16 md:w-20 rounded-t-[0.75rem] -ml-5 relative z-10"></div>
                        <div class="bg-[#FF6B9E] h-4 w-12 md:w-16 rounded-t-[0.5rem] -ml-5 relative z-0"></div>
                    </div>

                    <div class="relative z-20 bg-kmdgi-primary rounded-b-[2rem] rounded-tr-[2rem] rounded-tl-none p-5 md:p-6 flex flex-col md:flex-row gap-6 shadow-2xl shadow-kmdgi-primary/20 h-full">
                        <div class="w-full md:w-[45%] flex-shrink-0">
                            <div class="w-full aspect-[3/4] rounded-2xl overflow-hidden bg-slate-900 border border-white/10 shadow-inner relative group cursor-pointer">
                                @if($lomba->poster)
                                <img src="{{ asset('storage/' . $lomba->poster) }}" alt="Poster {{ $lomba->judul }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm p-4 text-center border-2 border-dashed border-slate-600 m-2 rounded-xl">Poster Belum Diunggah</div>
                                @endif
                            </div>
                        </div>

                        <div class="w-full md:w-[55%] flex flex-col justify-between pt-1">
                            <div>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @if(is_array($lomba->kategori_peserta))
                                    @foreach($lomba->kategori_peserta as $kat)
                                    <span class="bg-white text-kmdgi-primary text-[10px] md:text-xs font-bold px-3 py-1 rounded-full shadow-sm">{{ $kat }}</span>
                                    @endforeach
                                    @endif
                                </div>
                                <h3 class="text-white font-extrabold text-2xl md:text-3xl leading-tight mb-3 tracking-tight">
                                    {{ $lomba->judul }}
                                </h3>
                                <p class="text-blue-100 text-xs md:text-sm leading-relaxed line-clamp-4 mb-8">
                                    {{ Str::limit(strip_tags($lomba->deskripsi), 160) }}
                                </p>
                            </div>

                            @if($isOpenLomba)
                            <a href="{{ url('/kompetisi/' . $lomba->slug) }}" class="w-full bg-white hover:bg-slate-50 text-kmdgi-primary text-center font-bold py-3.5 rounded-xl shadow-md transition-transform transform hover:-translate-y-0.5 text-sm md:text-base">
                                Detail & Daftar Lomba
                            </a>
                            @else
                            <div class="flex flex-col gap-2.5">
                                <div class="w-full bg-white/10 text-blue-100 border border-white/20 text-center font-bold py-2.5 rounded-xl text-xs md:text-sm flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" />
                                    </svg>
                                    Pendaftaran Telah Ditutup
                                </div>
                                <a href="{{ url('/kompetisi/' . $lomba->slug) }}" class="w-full bg-white hover:bg-slate-50 text-kmdgi-primary text-center font-bold py-2.5 rounded-xl shadow-md transition-colors text-sm md:text-base">
                                    Lihat Detail Lomba
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- ========================================== -->
    <!-- 3. SECTION IDENTITAS & VIDEO               -->
    <!-- ========================================== -->
    <section class="relative py-24 md:py-32 bg-[#1a1a1a] overflow-hidden">
        <div class="absolute inset-0 z-0">
            <picture>
                <source media="(min-width: 768px)" srcset="{{ asset('images/bg-abstract-tube-desktop.png') }}">
                <img src="{{ asset('images/bg-abstract-tube-mobile.png') }}" alt="Background Abstract KMDGI" class="w-full h-full object-cover object-center opacity-60 mix-blend-screen" />
            </picture>
            <div class="absolute inset-0 bg-gradient-to-b from-[#1a1a1a]/80 via-transparent to-[#1a1a1a]/80"></div>
        </div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center flex flex-col items-center">
            <h2 class="text-3xl md:text-5xl lg:text-[3.5rem] font-extrabold text-white tracking-tight leading-[1.2] mb-6">
                Menyuarakan Identitas<br class="hidden md:block"> Melalui Desain
            </h2>
            <p class="text-sm md:text-base lg:text-lg text-slate-300 max-w-4xl mx-auto leading-relaxed mb-12 md:mb-16 font-medium">
                Tema "HAYUK!" hadir sebagai sapaan sekaligus ajakan bagi mahasiswa desain untuk bergerak bersama dalam merespons berbagai permasalahan yang terjadi di sekitar mereka saat ini. Melalui tema ini, KMDGI 16 mengajak setiap mahasiswa untuk membawa pengalaman, keresahan, dan cara pandangnya masing-masing, untuk kemudian dipertemukan, dibagikan, dan dipahami bersama mahasiswa desain lainnya.
            </p>
            <div class="w-full max-w-4xl mx-auto relative aspect-video rounded-[2rem] overflow-hidden bg-black shadow-[0_0_50px_rgba(0,0,0,0.5)] border border-white/10 group">
                <iframe class="absolute inset-0 w-full h-full" src="https://www.youtube.com/embed/YOUR_YOUTUBE_ID?rel=0&modestbranding=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- 4. SECTION PANDUAN REGISTRASI AKUN         -->
    <!-- ========================================== -->
    <section class="py-20 md:py-32 bg-slate-50 relative z-20 border-t border-slate-200/50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-[2.5rem] font-black text-slate-900 tracking-tight mb-4">Panduan Registrasi Akun</h2>
            <p class="text-slate-500 max-w-2xl mx-auto text-sm md:text-base mb-16 font-medium">
                Langkah awal untuk pengguna baru sebelum mengakses seluruh fitur KMDGI.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-6 lg:gap-10 text-left">
                <!-- CARD LANGKAH 1 -->
                <div class="relative w-full pt-5 group">
                    <div class="absolute top-0 left-0 flex items-end">
                        <div class="bg-kmdgi-primary h-6 w-20 lg:w-24 rounded-t-lg relative z-20 transition-colors"></div>
                        <div class="bg-[#FF6B9E] h-4 w-10 lg:w-12 rounded-t-md -ml-4 relative z-10 transition-transform group-hover:-translate-y-1"></div>
                        <div class="bg-[#C4F03B] h-3 w-8 lg:w-10 rounded-t-sm -ml-4 relative z-0 transition-transform group-hover:-translate-y-2"></div>
                    </div>
                    <div class="relative z-20 bg-kmdgi-primary rounded-b-2xl rounded-tr-2xl rounded-tl-none p-6 md:p-8 shadow-xl shadow-kmdgi-primary/20 h-full flex flex-col transition-transform transform group-hover:-translate-y-1">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="text-3xl md:text-4xl font-black text-white drop-shadow-md">1</span>
                            <h4 class="text-sm md:text-base font-bold text-white leading-tight">Klik Daftar / Register</h4>
                        </div>
                        <p class="text-xs md:text-sm text-blue-100 leading-relaxed font-medium">
                            Masuk ke halaman registrasi di pojok kanan atas portal KMDGI Web.
                        </p>
                    </div>
                </div>

                <!-- CARD LANGKAH 2 -->
                <div class="relative w-full pt-5 group">
                    <div class="absolute top-0 left-0 flex items-end">
                        <div class="bg-kmdgi-primary h-6 w-20 lg:w-24 rounded-t-lg relative z-20 transition-colors"></div>
                        <div class="bg-[#C4F03B] h-4 w-10 lg:w-12 rounded-t-md -ml-4 relative z-10 transition-transform group-hover:-translate-y-1"></div>
                        <div class="bg-[#FF6B9E] h-3 w-8 lg:w-10 rounded-t-sm -ml-4 relative z-0 transition-transform group-hover:-translate-y-2"></div>
                    </div>
                    <div class="relative z-20 bg-kmdgi-primary rounded-b-2xl rounded-tr-2xl rounded-tl-none p-6 md:p-8 shadow-xl shadow-kmdgi-primary/20 h-full flex flex-col transition-transform transform group-hover:-translate-y-1">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="text-3xl md:text-4xl font-black text-white drop-shadow-md">2</span>
                            <h4 class="text-sm md:text-base font-bold text-white leading-tight">Isi Data Diri</h4>
                        </div>
                        <p class="text-xs md:text-sm text-blue-100 leading-relaxed font-medium">
                            Lengkapi data identitas, email aktif, institusi/kampus, dan buat kata sandi.
                        </p>
                    </div>
                </div>

                <!-- CARD LANGKAH 3 -->
                <div class="relative w-full pt-5 group">
                    <div class="absolute top-0 left-0 flex items-end">
                        <div class="bg-kmdgi-primary h-6 w-20 lg:w-24 rounded-t-lg relative z-20 transition-colors"></div>
                        <div class="bg-[#FF6B9E] h-4 w-10 lg:w-12 rounded-t-md -ml-4 relative z-10 transition-transform group-hover:-translate-y-1"></div>
                        <div class="bg-[#C4F03B] h-3 w-8 lg:w-10 rounded-t-sm -ml-4 relative z-0 transition-transform group-hover:-translate-y-2"></div>
                    </div>
                    <div class="relative z-20 bg-kmdgi-primary rounded-b-2xl rounded-tr-2xl rounded-tl-none p-6 md:p-8 shadow-xl shadow-kmdgi-primary/20 h-full flex flex-col transition-transform transform group-hover:-translate-y-1">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="text-3xl md:text-4xl font-black text-white drop-shadow-md">3</span>
                            <h4 class="text-sm md:text-base font-bold text-white leading-tight">Masuk Kembali</h4>
                        </div>
                        <p class="text-xs md:text-sm text-blue-100 leading-relaxed font-medium">
                            Login kembali untuk mulai mendaftar lomba, acara, atau mengunggah karya.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-16 md:mt-20">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 text-kmdgi-primary font-bold text-sm md:text-base hover:text-blue-800 transition-colors group">
                    Daftar/Masuk
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- SECTION DOKUMENTASI (CTA GALLERY SLIDER)   -->
    <!-- ========================================== -->
    @if(isset($dokumentasis) && $dokumentasis->count() > 0)
    <section class="py-20 md:py-28 bg-[#111111] relative z-20 border-t border-white/5 overflow-hidden" id="dokumentasi">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 mb-10 md:mb-16">
            <div class="flex flex-col md:flex-row justify-between items-center md:items-end gap-6 text-center md:text-left">
                <div>
                    <h2 class="text-3xl md:text-5xl font-black text-white tracking-tight mb-4">Momen Berharga</h2>
                    <p class="text-slate-400 text-sm md:text-base max-w-xl">Intip keseruan, proses, dan setiap memori penting dalam perjalanan KMDGI 16.</p>
                </div>
                <a href="{{ route('dokumentasi.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-[#1A68FF] text-white hover:bg-blue-700 font-bold text-sm transition-all group flex-shrink-0 shadow-lg shadow-blue-500/20">
                    Lihat Galeri Penuh
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Slider Container -->
        <div class="relative w-full pb-8 z-10">
            <!-- Fade Edges -->
            <div class="absolute top-0 left-0 w-8 md:w-32 h-full bg-gradient-to-r from-[#111111] to-transparent z-20 pointer-events-none"></div>
            <div class="absolute top-0 right-0 w-8 md:w-32 h-full bg-gradient-to-l from-[#111111] to-transparent z-20 pointer-events-none"></div>

            <div class="flex overflow-x-auto snap-x snap-mandatory gap-4 md:gap-6 px-4 md:px-[10vw] no-scrollbar" id="doc-gallery-track">
                @foreach($dokumentasis->take(5) as $doc)
                @php
                $ytId = '';
                if($doc->tipe_media == 'Video YouTube' && $doc->video_url) {
                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $doc->video_url, $match);
                $ytId = $match[1] ?? '';
                }
                $imageSrc = '';
                if($doc->tipe_media == 'Foto' || $doc->tipe_media == 'Video Upload') {
                $imageSrc = asset('storage/' . $doc->file_path);
                } elseif($doc->tipe_media == 'Video YouTube' && $ytId) {
                $imageSrc = "https://img.youtube.com/vi/{$ytId}/maxresdefault.jpg";
                }
                @endphp

                <div class="snap-center shrink-0 w-[85vw] md:w-[60vw] lg:w-[50vw] aspect-[4/3] md:aspect-[16/9] relative rounded-3xl overflow-hidden group border border-white/10 shadow-2xl bg-slate-800 transition-transform duration-500 cursor-pointer" onclick="window.location.href='{{ route('dokumentasi.index') }}'">

                    @if($imageSrc)
                    <img src="{{ $imageSrc }}" alt="{{ $doc->judul }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-500 group-hover:scale-105">
                    @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-500">Media Tidak Tersedia</div>
                    @endif

                    @if($doc->tipe_media != 'Foto')
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="bg-white/20 backdrop-blur-md w-16 h-16 rounded-full flex items-center justify-center text-white shadow-lg border border-white/30">
                            <svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                        </div>
                    </div>
                    @endif

                    <div class="absolute inset-0 bg-gradient-to-t from-[#111111] via-transparent to-transparent opacity-90 flex flex-col justify-end p-6 md:p-8">
                        <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                            <span class="text-[#FF6B9E] font-bold text-[10px] md:text-xs uppercase tracking-widest mb-2 block">{{ $doc->kategori_kegiatan }}</span>
                            <h3 class="text-white font-black text-xl md:text-3xl leading-tight truncate">{{ $doc->judul }}</h3>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Indicators / Controls -->
            <div class="flex items-center justify-center gap-3 mt-8">
                @foreach($dokumentasis->take(5) as $index => $doc)
                <button type="button" class="w-2.5 h-2.5 rounded-full transition-colors duration-300 bg-white/20 hover:bg-white/50 doc-indicator" data-index="{{ $index }}"></button>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Script Autoplay & Scroll JS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const track = document.getElementById('doc-gallery-track');
            const indicators = document.querySelectorAll('.doc-indicator');
            if (!track || indicators.length === 0) return;

            let currentIndex = 0;
            const totalSlides = indicators.length;
            let autoPlayInterval;

            function updateIndicators(index) {
                indicators.forEach((ind, i) => {
                    if (i === index) {
                        ind.classList.remove('bg-white/20');
                        ind.classList.add('bg-white', 'w-8');
                    } else {
                        ind.classList.remove('bg-white', 'w-8');
                        ind.classList.add('bg-white/20');
                    }
                });
            }

            function scrollToSlide(index) {
                const slides = track.children;
                if (slides[index]) {
                    const scrollLeft = slides[index].offsetLeft - (track.clientWidth / 2) + (slides[index].clientWidth / 2);
                    track.scrollTo({
                        left: scrollLeft,
                        behavior: 'smooth'
                    });
                    currentIndex = index;
                    updateIndicators(currentIndex);
                }
            }

            function nextSlide() {
                currentIndex = (currentIndex + 1) % totalSlides;
                scrollToSlide(currentIndex);
            }

            // Inisialisasi awal
            updateIndicators(0);

            // Autoplay setiap 4 detik
            autoPlayInterval = setInterval(nextSlide, 4000);

            // Klik indicator
            indicators.forEach((ind) => {
                ind.addEventListener('click', (e) => {
                    clearInterval(autoPlayInterval);
                    const idx = parseInt(e.target.getAttribute('data-index'));
                    scrollToSlide(idx);
                    autoPlayInterval = setInterval(nextSlide, 4000);
                });
            });

            // Pause autoplay saat hover
            track.addEventListener('mouseenter', () => clearInterval(autoPlayInterval));
            track.addEventListener('mouseleave', () => {
                autoPlayInterval = setInterval(nextSlide, 4000);
            });
        });
    </script>
    @endif

    <!-- ========================================== -->
    <!-- 5. SECTION ACARA (EVENT)                   -->
    <!-- ========================================== -->
    @if(isset($events) && $events->count() > 0)
    <section class="py-20 md:py-28 bg-white relative z-20" id="acara">

        @php
        $myTickets = collect();
        if(Auth::check()) {
        $myTickets = \App\Models\TiketPeserta::where('user_id', Auth::id())
        ->whereNotNull('event_kmdgi_id')
        ->get()
        ->keyBy('event_kmdgi_id');
        }
        @endphp

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex justify-between items-end mb-10 md:mb-14">
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">Acara</h2>
                <a href="{{ route('katalog.event') }}" class="text-sm font-semibold text-[#1A68FF] hover:text-blue-800 flex items-center gap-1.5 transition-colors">
                    Lihat Selengkapnya
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @foreach($events as $event)

                @php
                $tiketSaya = $myTickets->get($event->id);
                $isRegistered = !is_null($tiketSaya);

                $pesertaCount = \App\Models\TiketPeserta::where('event_kmdgi_id', $event->id)->count();
                $isFull = ($event->kuota > 0 && $pesertaCount >= $event->kuota);

                $isPast = false;
                if($event->tanggal_pelaksanaan) {
                $waktuAcara = \Carbon\Carbon::parse($event->tanggal_pelaksanaan . ' ' . ($event->jam_pelaksanaan ?? '00:00:00'));
                $isPast = $waktuAcara->isPast();
                }
                @endphp

                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col sm:flex-row h-full group hover:shadow-xl transition-all duration-300">
                    <div class="w-full sm:w-[240px] lg:w-[220px] xl:w-[260px] aspect-[4/3] sm:aspect-auto bg-slate-100 flex-shrink-0 relative overflow-hidden border-b sm:border-b-0 sm:border-r border-slate-100">
                        @if($event->poster)
                        <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 text-xs bg-slate-200">
                            Poster Belum Tersedia
                        </div>
                        @endif

                        @if($isPast)
                        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] flex items-center justify-center z-10 pointer-events-none">
                            <span class="bg-slate-100 text-slate-600 px-4 py-1.5 rounded-full font-bold text-[10px] uppercase tracking-widest flex items-center shadow-lg border border-white/20">
                                Telah Berakhir
                            </span>
                        </div>
                        @elseif($isFull && !$isRegistered)
                        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] flex items-center justify-center z-10 pointer-events-none">
                            <span class="bg-red-500 text-white px-4 py-1.5 rounded-full font-bold text-[10px] uppercase tracking-widest flex items-center shadow-lg border border-white/20">
                                Kuota Habis
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="p-5 md:p-6 flex flex-col justify-between flex-grow">
                        <div>
                            <div class="flex flex-wrap gap-2 mb-3">
                                @if(is_array($event->kategori_peserta) && count($event->kategori_peserta) > 0)
                                @foreach($event->kategori_peserta as $kat)
                                <span class="px-2 py-0.5 rounded-full border border-emerald-200 text-emerald-600 bg-emerald-50 text-[10px] font-bold uppercase tracking-wider">{{ $kat }}</span>
                                @endforeach
                                @else
                                <span class="px-2 py-0.5 rounded-full border border-emerald-200 text-emerald-600 bg-emerald-50 text-[10px] font-bold uppercase tracking-wider">Umum</span>
                                @endif

                                <span class="px-2 py-0.5 rounded-full border border-blue-200 text-[#1A68FF] bg-blue-50 text-[10px] font-bold uppercase tracking-wider whitespace-nowrap">
                                    {{ $event->harga_tiket == 0 ? 'FREE' : 'Rp ' . number_format($event->harga_tiket, 0, ',', '.') }}
                                </span>
                            </div>

                            <h4 class="text-lg md:text-xl font-bold text-slate-900 leading-snug mb-2 line-clamp-2" title="{{ $event->judul }}">
                                {{ $event->judul }}
                            </h4>

                            <p class="text-xs text-slate-500 line-clamp-1 mb-4">
                                @php
                                $eventKolaborators = $event->getKolaboratorTerkait();
                                $kolabNames = $eventKolaborators->pluck('nama')->join(', ');
                                @endphp
                                {{ $kolabNames ?: 'Narasumber Umum' }}
                            </p>

                            <div class="space-y-1.5 mb-5">
                                <div class="flex items-center gap-2 text-xs text-slate-700 font-medium">
                                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    {{ $event->tanggal_pelaksanaan ? \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->locale('id')->translatedFormat('d M Y') : 'Menyusul' }} • {{ $event->jam_pelaksanaan ? \Carbon\Carbon::parse($event->jam_pelaksanaan)->format('H.i') : '-' }} WIB
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-700 font-medium">
                                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                    <span class="truncate">{{ $event->lokasi ?? 'Lokasi Menyusul' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-4 mt-auto">
                            @if(!Auth::check())
                            <a href="{{ route('login') }}" class="block w-full text-center bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs md:text-sm transition-colors shadow-sm">
                                Masuk untuk Daftar
                            </a>
                            @else
                            @if($tiketSaya)
                            @if($tiketSaya->status === 'Menunggu Konfirmasi')
                            <a href="{{ route('dashboard') }}" class="block w-full text-center bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-200 font-bold py-2.5 px-6 rounded-xl text-xs md:text-sm transition-colors shadow-sm">
                                Menunggu Konfirmasi
                            </a>
                            @else
                            <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-2 w-full bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border border-emerald-200 font-bold py-2.5 px-6 rounded-xl text-xs md:text-sm transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Tiket Terdaftar
                            </a>
                            @endif
                            @elseif($isPast)
                            <button disabled class="block w-full bg-slate-100 text-slate-400 font-bold py-2.5 px-6 rounded-xl text-xs md:text-sm cursor-not-allowed border border-slate-200">
                                Telah Berakhir
                            </button>
                            @elseif($isFull)
                            <button disabled class="block w-full bg-red-50 text-red-500 font-bold py-2.5 px-6 rounded-xl text-xs md:text-sm cursor-not-allowed border border-red-100">
                                Kuota Habis
                            </button>
                            @else
                            <a href="{{ route('katalog.event.show', $event->slug) }}" class="block w-full text-center bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs md:text-sm transition-colors shadow-sm shadow-blue-500/20">
                                Lihat Detail & Daftar
                            </a>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                <a href="{{ route('katalog.event') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-full border-2 border-[#1A68FF] text-[#1A68FF] hover:bg-[#1A68FF] hover:text-white font-bold text-sm transition-colors group">
                    Lihat Seluruh Acara
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>

        </div>
    </section>
    @endif

    <!-- ========================================== -->
    <!-- 6. SECTION PERFORMANCE (PENAMPIL)          -->
    <!-- ========================================== -->
    @if(isset($penampils) && $penampils->count() > 0)
    <section class="py-20 md:py-28 bg-[#FFF5F6] relative z-20 border-t border-slate-100" id="performance">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-10 md:mb-14">Performance</h2>

            @foreach($penampils as $tanggal => $group)
            <div class="mb-14">
                <div class="flex items-center gap-4 mb-6 md:mb-8">
                    <h3 class="text-lg md:text-2xl font-bold text-slate-900 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                    </h3>
                    <div class="h-px bg-slate-300 flex-grow hidden md:block"></div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-2 gap-4 md:gap-x-12 md:gap-y-10">
                    @foreach($group as $penampil)
                    <a href="{{ route('performance.show', \Illuminate\Support\Str::slug($penampil->nama_penampil)) }}" class="flex flex-col md:flex-row gap-3 md:gap-6 w-full group">
                        <div class="w-full md:w-[200px] lg:w-[240px] aspect-square flex-shrink-0 overflow-hidden rounded-2xl md:rounded-[1.5rem] shadow-sm relative border border-slate-200 bg-white">
                            @if($penampil->cover_penampil)
                            <img src="{{ asset('storage/' . $penampil->cover_penampil) }}" alt="{{ $penampil->nama_penampil }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @elseif($penampil->logo_penampil)
                            <img src="{{ asset('storage/' . $penampil->logo_penampil) }}" alt="{{ $penampil->nama_penampil }}" class="w-full h-full object-contain p-4 transition-transform duration-500 group-hover:scale-105">
                            @else
                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400 text-[10px] md:text-xs font-semibold">No Image</div>
                            @endif
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
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

                                <h4 class="text-base md:text-2xl font-black text-slate-900 leading-snug mb-2 md:mb-4 group-hover:text-[#1A68FF] transition-colors line-clamp-2">
                                    {{ $penampil->nama_penampil }}
                                </h4>

                                <div class="space-y-1 md:space-y-2 mb-4">
                                    <div class="flex items-center gap-1.5 md:gap-2 text-[9px] md:text-xs text-slate-700 font-medium">
                                        <svg class="w-3 h-3 md:w-4 md:h-4 text-slate-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        {{ $penampil->jam_mulai ? \Carbon\Carbon::parse($penampil->jam_mulai)->format('H.i') : '-' }} - {{ $penampil->jam_selesai ? \Carbon\Carbon::parse($penampil->jam_selesai)->format('H.i') : '-' }} WIB
                                    </div>
                                    <div class="flex items-center gap-1.5 md:gap-2 text-[9px] md:text-xs text-slate-700 font-medium">
                                        <svg class="w-3 h-3 md:w-4 md:h-4 text-slate-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                        </svg>
                                        {{ $penampil->tanggal_tampil ? \Carbon\Carbon::parse($penampil->tanggal_tampil)->locale('id')->translatedFormat('d F Y') : '-' }}
                                    </div>
                                </div>
                            </div>

                            @if(strtolower($penampil->tipe_pendaftaran) !== 'gratis' && $penampil->harga_tiket > 0 && $penampil->link_pendaftaran)
                            <button class="w-full md:w-[75%] bg-[#1A68FF] text-white text-center font-bold py-2 md:py-2.5 rounded-lg md:rounded-xl text-[10px] md:text-xs transition-colors shadow-sm group-hover:bg-blue-700">
                                Daftar
                            </button>
                            @else
                            <button class="w-full md:w-[75%] bg-[#F1F5F9] text-slate-400 border border-slate-200 text-center font-bold py-2 md:py-2.5 rounded-lg md:rounded-xl text-[10px] md:text-xs transition-colors">
                                Daftar
                            </button>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="mt-10 flex justify-center">
                <a href="{{ route('performance.index') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-full border-2 border-slate-300 text-slate-700 hover:border-[#1A68FF] hover:bg-[#1A68FF] hover:text-white font-semibold text-sm transition-all group">
                    Lihat Selengkapnya
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>

        </div>
    </section>
    @endif

    <!-- ========================================== -->
    <!-- 7. SECTION KOLABORATOR                     -->
    <!-- ========================================== -->
    @if(isset($kolaborators) && $kolaborators->count() > 0)
    <section class="py-20 md:py-28 bg-white relative z-20 border-t border-slate-100" id="kolaborator">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">Profil Kolaborator</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @foreach($kolaborators->take(3) as $kolaborator)
                <a href="{{ url('/kolaborator/' . \Illuminate\Support\Str::slug($kolaborator->nama)) }}" class="group relative w-full aspect-[3/4] rounded-[1.5rem] md:rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 bg-slate-900 block border border-slate-100/50">

                    @if($kolaborator->foto)
                    <img src="{{ asset('storage/' . $kolaborator->foto) }}" alt="{{ $kolaborator->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90 group-hover:opacity-100">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-slate-800 text-slate-400 text-6xl font-black group-hover:scale-105 transition-transform duration-700">
                        {{ strtoupper(substr($kolaborator->nama, 0, 1)) }}
                    </div>
                    @endif

                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                    <div class="absolute -bottom-2 left-0 w-full flex flex-col transition-transform duration-300 transform group-hover:-translate-y-2">

                        <div class="flex items-end pl-0">
                            <div class="bg-[#1A68FF] group-hover:bg-blue-700 h-8 md:h-10 w-[45%] rounded-tr-[1.25rem] relative z-20 transition-colors duration-300"></div>
                            <div class="bg-[#FF6B9E] h-5 md:h-7 w-12 md:w-16 rounded-tr-lg -ml-4 md:-ml-6 relative z-10 transition-transform duration-300 group-hover:-translate-y-1.5 transform -skew-x-[20deg] origin-bottom-left"></div>
                            <div class="bg-[#C4F03B] h-3 md:h-5 w-10 md:w-14 rounded-tr-md -ml-3 md:-ml-5 relative z-0 transition-transform duration-300 group-hover:-translate-y-3 transform -skew-x-[20deg] origin-bottom-left"></div>
                        </div>

                        <div class="bg-[#1A68FF] group-hover:bg-blue-700 w-full px-5 pt-4 pb-6 md:px-6 md:pt-5 md:pb-8 z-20 relative flex flex-col justify-end transition-colors duration-300">
                            <h3 class="text-white font-bold text-lg md:text-xl truncate mb-1">
                                {{ $kolaborator->nama }}
                            </h3>
                            <p class="text-blue-100/90 text-[11px] md:text-xs font-medium truncate">
                                {{ $kolaborator->profesi }}
                            </p>
                        </div>

                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-10 flex justify-center">
                <a href="{{ url('/kolaborator') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full border border-[#1A68FF] text-[#1A68FF] hover:bg-blue-50 font-semibold text-sm transition-colors">
                    Lihat Lainnya
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>

        </div>
    </section>
    @endif

    <!-- ========================================== -->
    <!-- 8. SECTION MITRA & SPONSOR                 -->
    <!-- ========================================== -->
    @if(isset($sponsors) && $sponsors->count() > 0)
    <section class="py-16 md:py-24 bg-slate-50 relative z-20 border-t border-slate-200" id="sponsor">
        <div class="max-w-full mx-auto text-center overflow-hidden">

            <h2 class="text-3xl md:text-[2.5rem] font-black text-slate-900 tracking-tight mb-12 md:mb-16">Mitra dan Sponsor</h2>

            @foreach($sponsors as $kategori => $groupSponsor)
            <div class="mb-12 md:mb-16 w-full">
                <h3 class="text-sm md:text-base font-bold text-slate-600 mb-6 md:mb-8 lowercase">{{ $kategori }} oleh:</h3>

                <div class="marquee-container relative">
                    <div class="absolute top-0 left-0 w-12 md:w-32 h-full bg-gradient-to-r from-slate-50 to-transparent z-10 pointer-events-none"></div>
                    <div class="absolute top-0 right-0 w-12 md:w-32 h-full bg-gradient-to-l from-slate-50 to-transparent z-10 pointer-events-none"></div>

                    <div class="marquee-track">
                        <div class="flex items-center gap-8 md:gap-14 flex-shrink-0 px-4">
                            @for($i = 0; $i < 4; $i++)
                                @foreach($groupSponsor as $sponsor)
                                @if($sponsor->link_tautan)
                                <a href="{{ $sponsor->link_tautan }}" target="_blank" class="w-24 md:w-32 lg:w-40 h-12 md:h-16 lg:h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-300 hover:scale-110 flex-shrink-0 relative z-20">
                                    <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->nama_mitra }}" class="max-w-full max-h-full object-contain" title="{{ $sponsor->nama_mitra }}">
                                </a>
                                @else
                                <div class="w-24 md:w-32 lg:w-40 h-12 md:h-16 lg:h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-300 hover:scale-110 flex-shrink-0 relative z-20">
                                    <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->nama_mitra }}" class="max-w-full max-h-full object-contain" title="{{ $sponsor->nama_mitra }}">
                                </div>
                                @endif
                                @endforeach
                                @endfor
                        </div>

                        <div class="flex items-center gap-8 md:gap-14 flex-shrink-0 px-4">
                            @for($i = 0; $i < 4; $i++)
                                @foreach($groupSponsor as $sponsor)
                                @if($sponsor->link_tautan)
                                <a href="{{ $sponsor->link_tautan }}" target="_blank" class="w-24 md:w-32 lg:w-40 h-12 md:h-16 lg:h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-300 hover:scale-110 flex-shrink-0 relative z-20">
                                    <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->nama_mitra }}" class="max-w-full max-h-full object-contain" title="{{ $sponsor->nama_mitra }}">
                                </a>
                                @else
                                <div class="w-24 md:w-32 lg:w-40 h-12 md:h-16 lg:h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-300 hover:scale-110 flex-shrink-0 relative z-20">
                                    <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->nama_mitra }}" class="max-w-full max-h-full object-contain" title="{{ $sponsor->nama_mitra }}">
                                </div>
                                @endif
                                @endforeach
                                @endfor
                        </div>
                    </div>
                </div>

            </div>
            @endforeach

        </div>
    </section>
    @endif

    <!-- ========================================== -->
    <!-- 9. SECTION FAQ (SERING DITANYAKAN)         -->
    <!-- ========================================== -->
    @if(isset($faqs) && $faqs->count() > 0)
    <section class="py-20 md:py-28 bg-white relative z-20 border-t border-slate-100" id="faq">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-10 md:mb-14">
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">FAQ <span class="font-semibold text-slate-700 md:text-[2.5rem]">(Sering Ditanyakan)</span></h2>
            </div>

            <div class="bg-[#FDF7F8] md:bg-[#FDF7F8] rounded-[2rem] p-6 md:p-10 shadow-sm border border-slate-100">
                @foreach($faqs as $faq)
                <div class="faq-item border-b border-slate-200/70 last:border-none">
                    <button onclick="toggleFaq(this)" class="w-full py-5 md:py-6 flex justify-between items-center text-left focus:outline-none group">
                        <span class="text-sm md:text-base font-bold text-slate-800 group-hover:text-kmdgi-primary transition-colors pr-6">
                            {{ $faq->pertanyaan }}
                        </span>
                        <svg class="w-5 h-5 text-slate-600 transition-transform duration-300 transform faq-icon flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                        <div class="pb-5 md:pb-6 text-sm md:text-base text-slate-600 leading-relaxed font-medium">
                            {!! $faq->jawaban !!}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>
    @endif

</div>

@include('partials.footer')

<script>
    // Script Accordion FAQ (Vanilla JS)
    function toggleFaq(button) {
        const content = button.nextElementSibling;
        const icon = button.querySelector('.faq-icon');
        const allContents = document.querySelectorAll('.faq-content');
        const allIcons = document.querySelectorAll('.faq-icon');

        allContents.forEach((el, index) => {
            if (el !== content) {
                el.style.maxHeight = null;
                allIcons[index].style.transform = 'rotate(0deg)';
            }
        });

        if (content.style.maxHeight) {
            content.style.maxHeight = null;
            icon.style.transform = 'rotate(0deg)';
        } else {
            content.style.maxHeight = content.scrollHeight + "px";
            icon.style.transform = 'rotate(180deg)';
        }
    }

    // Countdown Script
    document.addEventListener('DOMContentLoaded', () => {
        const wrapper = document.getElementById('countdown-wrapper');
        if (!wrapper) return;

        const targetDate = new Date(wrapper.getAttribute('data-time')).getTime();

        const timer = setInterval(() => {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance < 0) {
                clearInterval(timer);
                ['hari', 'jam', 'menit', 'detik'].forEach(id => {
                    document.getElementById('cd-' + id).innerText = "00";
                });
                return;
            }

            const d = Math.floor(distance / (1000 * 60 * 60 * 24));
            const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('cd-hari').innerText = d.toString().padStart(2, '0');
            document.getElementById('cd-jam').innerText = h.toString().padStart(2, '0');
            document.getElementById('cd-menit').innerText = m.toString().padStart(2, '0');
            document.getElementById('cd-detik').innerText = s.toString().padStart(2, '0');
        }, 1000);
    });
</script>
@endsection