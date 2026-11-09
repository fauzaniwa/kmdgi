@extends('layouts.app')

@section('title', 'Tahap Kurasi & Persiapan Voting - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col justify-between font-sans">
    @include('partials.navbar')

    <main class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 py-16">
        <div class="max-w-xl w-full">

            <!-- Ornamen Folder Tab Khas KMDGI 16 -->
            <div class="relative w-full pt-7">
                <div class="absolute top-0 left-0 flex items-end select-none pointer-events-none">
                    <div class="bg-[#1A68FF] h-8 w-36 md:w-44 rounded-t-[1.25rem] relative z-20"></div>
                    <div class="bg-[#C4F03B] h-6 w-16 md:w-20 rounded-t-[0.85rem] -ml-5 relative z-10"></div>
                    <div class="bg-[#FF6B9E] h-4 w-12 md:w-16 rounded-t-[0.6rem] -ml-5 relative z-0"></div>
                </div>

                <!-- Card Informasi -->
                <div class="relative z-20 bg-white border border-slate-200/80 rounded-b-[2rem] rounded-tr-[2rem] rounded-tl-none p-8 md:p-12 shadow-[0_20px_50px_rgba(0,0,0,0.06)] text-center">

                    <!-- Icon Gembok / Kurasi -->
                    <div class="w-16 h-16 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center mx-auto mb-5 shadow-sm">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>

                    <!-- Badge Status -->
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-[#1A68FF] text-[11px] font-extrabold uppercase tracking-widest mb-4">
                        <span class="w-2 h-2 rounded-full bg-[#1A68FF] animate-pulse"></span>
                        Most Popular Artwork — Tematik
                    </div>

                    <!-- Judul Utama -->
                    <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight leading-snug mb-3">
                        Katalog Sedang Tahap Kurasi & Sesi Voting Belum Dibuka
                    </h1>

                    @if(!empty($namaInstitusi))
                        <div class="inline-block bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-2 mb-4">
                            <span class="text-xs font-extrabold text-[#1A68FF] uppercase tracking-wider block">
                                {{ $namaInstitusi }}
                            </span>
                            @if(!empty($judulBooth))
                                <span class="text-xs font-bold text-slate-700">“{{ $judulBooth }}”</span>
                            @endif
                        </div>
                    @endif

                    <!-- Deskripsi Penjelasan -->
                    <p class="text-xs md:text-sm text-slate-500 leading-relaxed max-w-md mx-auto mb-6 font-medium">
                        Dokumentasi karya instalasi booth delegasi saat ini sedang dalam proses verifikasi oleh tim kurator KMDGI 16. Seluruh karya akan ditampilkan secara serentak saat sesi voting resmi dibuka.
                    </p>

                    <!-- Info Jadwal Voting (Jika Tersedia)
                    @if(isset($waktuBuka) && isset($waktuTutup))
                        <div class="bg-[#F2F7FF] border border-blue-100 rounded-2xl p-4 mb-8 text-left flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-[#1A68FF] text-white flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-extrabold text-[#1A68FF] uppercase tracking-wider block">Jadwal Sesi Voting</span>
                                <span class="text-xs font-bold text-slate-800 block mt-0.5">
                                    {{ $waktuBuka->translatedFormat('d M Y, H:i') }} — {{ $waktuTutup->translatedFormat('d M Y, H:i') }} WIB
                                </span>
                            </div>
                        </div>
                    @endif -->

                    <!-- Tombol Aksi -->
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="{{ route('katalog.karya.index') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl border-2 border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs md:text-sm transition-all">
                            Lihat Galeri Karya Lainnya
                        </a>
                        <a href="{{ url('/') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl bg-[#1A68FF] hover:bg-blue-700 text-white font-bold text-xs md:text-sm shadow-lg shadow-blue-500/20 transition-all transform hover:-translate-y-0.5">
                            Ke Beranda Utama
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </main>

    @include('partials.footer')
</div>
@endsection