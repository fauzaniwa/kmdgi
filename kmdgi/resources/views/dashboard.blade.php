@extends('layouts.app')

@section('title', 'Dashboard - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">
        
        @include('partials.sidebar')

        <main class="flex-grow space-y-8 w-full">
            
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
                <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                    <div class="w-16 h-16 rounded-full bg-slate-200 overflow-hidden border-2 border-white shadow-md flex-shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=126CFD&color=fff" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 leading-tight">{{ Auth::user()->name }}</h2>
                        <p class="text-xs text-slate-400 font-medium capitalize mt-0.5">{{ Auth::user()->kategori }} Peserta</p>
                    </div>
                </div>
                <button class="w-full sm:w-auto inline-flex items-center justify-center gap-2 border border-slate-200 hover:border-kmdgi-primary hover:text-kmdgi-primary text-slate-600 font-semibold px-4 py-2.5 rounded-xl text-sm transition-colors bg-white shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    Edit Profile
                </button>
            </div>

            <section class="space-y-4">
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Tiket Pameran</h3>
                <p class="text-xs text-slate-500 -mt-2">Gunakan tiket ini untuk masuk ke area pameran.</p>
                
                <div class="bg-yellow-50/60 border border-yellow-100 rounded-2xl p-4 flex gap-3 items-start text-xs text-amber-800 leading-relaxed">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p>Tunjukkan ke panitia di pintu dan naikkan kecerahan layar Anda.</p>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] flex flex-col md:flex-row items-center gap-6">
                    <div class="w-32 h-32 bg-slate-100 flex items-center justify-center rounded-2xl flex-shrink-0 p-2 border border-slate-200">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=FD90184J9RC" alt="QR Code" class="w-full h-full object-contain">
                    </div>
                    <div class="flex-grow text-center md:text-left space-y-2 w-full">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Ticket Code</span>
                        <h4 class="text-xl font-black text-slate-900 tracking-tight">FD90184J9RC</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">*Siapkan QR Code Anda sebelum mendekati area pintu masuk.<br>*Satu QR Code hanya berlaku untuk 1 (satu) orang pengunjung yang terdaftar.</p>
                    </div>
                    <button class="w-full md:w-auto bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold px-5 py-3 rounded-xl text-xs flex items-center justify-center gap-2 transition-all shadow-md shadow-kmdgi-primary/10 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        Unduh Tiket
                    </button>
                </div>
            </section>

            <section class="space-y-4">
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Tiket Seminar dan Workshop</h3>
                <p class="text-xs text-slate-500 -mt-2">Gunakan tiket ini untuk mengikuti sesi seminar dan workshop.</p>
                
                <div class="bg-yellow-50/60 border border-yellow-100 rounded-2xl p-4 flex gap-3 items-start text-xs text-amber-800 leading-relaxed">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p>Tunjukkan halaman ini kepada panitia sebelum memasuki ruangan sesi kegiatan.</p>
                </div>

                <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] flex flex-col md:flex-row gap-5 items-center">
                    <img src="https://via.placeholder.com/120x120/126CFD/FFFFFF?text=Shigeo+Fukuda" alt="Event Poster" class="w-full md:w-28 h-28 object-cover rounded-2xl flex-shrink-0">
                    <div class="flex-grow space-y-1.5 text-center md:text-left w-full">
                        <h4 class="font-bold text-slate-900 text-base leading-snug">The Art of Trickery: Mematahkan Logika Visual Bersama Shigeo Fukuda</h4>
                        <div class="flex flex-wrap justify-center md:justify-start gap-x-4 gap-y-1 text-slate-400 text-xs">
                            <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 13.30 - 15.30 WIB</span>
                            <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> 17 September 2026</span>
                        </div>
                        <p class="text-xs text-slate-500 flex items-center justify-center md:justify-start gap-1"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Ruang Smartclass, Gd. FPSD Baru Lt. 2, Universitas Pendidikan Indonesia</p>
                        <div class="text-[10px] text-slate-400 pt-1 font-semibold">Ticket Code: <span class="text-slate-700">FD90184J9RC</span> &bull; Milik: <span class="text-slate-700">{{ Auth::user()->name }}</span></div>
                    </div>
                    <div class="flex flex-col gap-2 w-full md:w-auto flex-shrink-0">
                        <button class="w-full md:w-auto bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold px-4 py-2.5 rounded-xl text-xs whitespace-nowrap transition-colors">Unduh Tiket</button>
                        <button class="w-full md:w-auto border border-slate-200 text-slate-600 font-semibold px-4 py-2.5 rounded-xl text-xs whitespace-nowrap bg-white hover:bg-slate-50 transition-colors">Detail Kegiatan</button>
                    </div>
                </div>
            </section>
        </main>
    </div>

    @include('partials.footer')
</div>
@endsection