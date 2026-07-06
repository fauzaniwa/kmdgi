@extends('layouts.app')

@section('title', 'Beranda - KMDGI 16')

@section('content')
<div class="bg-slate-50 flex flex-col min-h-screen">
    
    @include('partials.navbar')

    <main class="flex-grow flex items-center justify-center p-6 my-10">
        <div class="max-w-4xl w-full bg-white rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-10 md:p-16 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-kmdgi-primary/10 text-kmdgi-primary text-sm font-semibold mb-6">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-kmdgi-primary opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-kmdgi-primary"></span>
                </span>
                Pendaftaran KMDGI Tahun Ini Telah Dibuka
            </div>
            
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight mb-6">
                Wadah Kolaborasi & Inovasi <br class="hidden md:block">
                Mahasiswa Desain Grafis Indonesia
            </h1>
            
            <p class="text-lg text-slate-500 mb-10 max-w-2xl mx-auto leading-relaxed">
                Bergabunglah dengan ribuan mahasiswa desain dari seluruh Indonesia. Tampilkan karyamu, ikuti seminar, dan bangun relasi untuk masa depan kreatifmu.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-4 px-8 rounded-2xl shadow-xl shadow-kmdgi-primary/30 transition-all duration-200 transform hover:-translate-y-1">
                    Mulai Sekarang
                </a>
                <a href="#" class="bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-4 px-8 rounded-2xl border border-slate-200 transition-all duration-200">
                    Lihat Jadwal
                </a>
            </div>
        </div>
    </main>

    @include('partials.footer')

</div>
@endsection