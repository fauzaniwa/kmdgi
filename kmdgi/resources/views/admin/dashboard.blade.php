@extends('layouts.app')

@section('title', 'Admin Dashboard - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">
        
        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full">
            
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4 text-center sm:text-left flex-col sm:flex-row w-full">
                    <div class="w-14 h-14 rounded-full bg-kmdgi-primary/10 flex items-center justify-center text-kmdgi-primary font-bold text-lg border border-kmdgi-primary/20 flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 leading-tight">{{ auth()->user()->name }}</h2>
                        <p class="text-xs text-slate-400 font-medium capitalize mt-1">Sesi Akses: <span class="text-kmdgi-primary font-semibold">{{ auth()->user()->role }}</span></p>
                    </div>
                </div>
            </div>

            <section class="space-y-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight">Dashboard Data Preview</h3>
                    <p class="text-xs text-slate-400">Ringkasan berkas operasional pendaftaran KMDGI 16.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                        <span class="text-xs font-semibold text-slate-400 block mb-1">Total Kampus Terdaftar</span>
                        <div class="text-3xl font-black text-slate-900 tracking-tight">24 <span class="text-xs text-slate-400 font-normal">Institusi</span></div>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                        <span class="text-xs font-semibold text-slate-400 block mb-1">Total User Akun</span>
                        <div class="text-3xl font-black text-slate-900 tracking-tight">1,420 <span class="text-xs text-slate-400 font-normal">Peserta</span></div>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                        <span class="text-xs font-semibold text-slate-400 block mb-1">Karya Masuk</span>
                        <div class="text-3xl font-black text-kmdgi-primary tracking-tight">186 <span class="text-xs text-slate-400 font-normal">Karya</span></div>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-[2rem] border border-slate-100 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.005)] min-h-[300px] flex items-center justify-center text-center">
                <div class="max-w-sm space-y-2">
                    <div class="text-slate-300 inline-block p-4 bg-slate-50 rounded-full mb-2">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" /></svg>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Pilih Menu untuk Memulai Kontrol</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">Gunakan menu navigasi panel kiri untuk mengelola data master, regulasi legal, verifikasi tiket masuk, hingga monitoring log sistem.</p>
                </div>
            </section>

        </main>
    </div>

    @include('partials.footer')
</div>
@endsection