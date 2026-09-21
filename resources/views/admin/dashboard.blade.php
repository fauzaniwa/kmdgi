@extends('layouts.app')

@section('title', 'Admin Dashboard - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full">

            <!-- HEADER USER -->
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

            <!-- SECTION STATISTIK REAL-TIME -->
            <section class="space-y-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight">Dashboard Data Preview</h3>
                    <p class="text-xs text-slate-400">Ringkasan aktivitas operasional, pendaftaran, dan tiket KMDGI 16 secara real-time.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                    <!-- 1. Total Kampus -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col justify-between hover:border-kmdgi-primary/30 transition-colors">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Kampus Terdaftar</span>
                            <div class="p-2 bg-blue-50 text-blue-500 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-slate-900 tracking-tight">
                            {{ number_format(\App\Models\Kampus::count(), 0, ',', '.') }}
                            <span class="text-xs text-slate-400 font-normal">Institusi</span>
                        </div>
                    </div>

                    <!-- 2. Total User Akun -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col justify-between hover:border-kmdgi-primary/30 transition-colors">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Total Pengguna</span>
                            <div class="p-2 bg-indigo-50 text-indigo-500 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-slate-900 tracking-tight">
                            {{ number_format(\App\Models\User::count(), 0, ',', '.') }}
                            <span class="text-xs text-slate-400 font-normal">Akun</span>
                        </div>
                    </div>

                    <!-- 3. Karya Lomba Masuk -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col justify-between hover:border-kmdgi-primary/30 transition-colors">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Karya Lomba Masuk</span>
                            <div class="p-2 bg-emerald-50 text-emerald-500 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-emerald-500 tracking-tight">
                            {{ number_format(\App\Models\PesertaLomba::whereNotNull('file_karya')->count(), 0, ',', '.') }}
                            <span class="text-xs text-slate-400 font-normal">Karya</span>
                        </div>
                    </div>

                    <!-- 4. Tiket Terverifikasi -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col justify-between hover:border-kmdgi-primary/30 transition-colors">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Tiket Terverifikasi (Aktif)</span>
                            <div class="p-2 bg-purple-50 text-purple-500 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-purple-600 tracking-tight">
                            {{ number_format(\App\Models\TiketPeserta::where('status', 'Aktif')->count(), 0, ',', '.') }}
                            <span class="text-xs text-slate-400 font-normal">Peserta</span>
                        </div>
                    </div>

                    <!-- 5. Kehadiran (Hasil Scan) -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col justify-between hover:border-kmdgi-primary/30 transition-colors">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Total Kehadiran (Scan)</span>
                            <div class="p-2 bg-teal-50 text-teal-500 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-teal-500 tracking-tight">
                            {{ number_format(\App\Models\TiketPeserta::whereNotNull('waktu_kehadiran')->count(), 0, ',', '.') }}
                            <span class="text-xs text-slate-400 font-normal">Hadir</span>
                        </div>
                    </div>

                    <!-- 6. Laporan Komentar -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col justify-between hover:border-kmdgi-primary/30 transition-colors">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Laporan Komentar Masuk</span>
                            <div class="p-2 bg-rose-50 text-rose-500 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-rose-500 tracking-tight">
                            {{ number_format(\App\Models\LaporanKomentar::count(), 0, ',', '.') }}
                            <span class="text-xs text-slate-400 font-normal">Laporan</span>
                        </div>
                    </div>

                </div>
            </section>

            <!-- SECTION ACTIONABLE & LIVE FEED -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- KOLOM KIRI: BUTUH TINDAKAN (PENDING APPROVALS) -->
                <section class="bg-white rounded-[2rem] border border-slate-100 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-slate-900">Butuh Tindakan Admin</h3>
                        <span class="px-3 py-1 bg-amber-50 text-amber-600 font-bold text-[10px] rounded-full uppercase tracking-wider border border-amber-100">Prioritas</span>
                    </div>

                    <div class="space-y-4 flex-grow">
                        <!-- Item: Tiket Event Menunggu -->
                        @php $pendingEvent = \App\Models\TiketPeserta::where('jenis_tiket', 'Event')->where('status', 'Menunggu Konfirmasi')->count(); @endphp
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:border-amber-200 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">Verifikasi Tiket Event</h4>
                                    <p class="text-xs text-slate-400">Pembayaran baru menunggu dicek</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.verifikasi.event.index') }}" class="flex items-center gap-3">
                                <span class="font-black text-amber-500">{{ $pendingEvent }}</span>
                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>

                        <!-- Item: Tiket Performance Menunggu -->
                        @php $pendingPerf = \App\Models\TiketPeserta::where('jenis_tiket', 'Performance')->where('status', 'Menunggu Konfirmasi')->count(); @endphp
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:border-amber-200 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">Verifikasi Tiket Konser</h4>
                                    <p class="text-xs text-slate-400">Pembayaran baru menunggu dicek</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.verifikasi.performance.index') }}" class="flex items-center gap-3">
                                <span class="font-black text-amber-500">{{ $pendingPerf }}</span>
                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>

                        <!-- Item: Verifikasi Lomba -->
                        @php $pendingLomba = \App\Models\PesertaLomba::where('status_pembayaran', 'Menunggu Validasi')->count(); @endphp
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:border-amber-200 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">Validasi Peserta Lomba</h4>
                                    <p class="text-xs text-slate-400">Tim lomba baru menunggu validasi</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.peserta_lomba.index') }}" class="flex items-center gap-3">
                                <span class="font-black text-amber-500">{{ $pendingLomba }}</span>
                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </section>

                <!-- KOLOM KANAN: LIVE FEED SCAN KEHADIRAN TERBARU -->
                <section class="bg-white rounded-[2rem] border border-slate-100 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-slate-900">Live: Kehadiran Terbaru</h3>
                        <a href="{{ route('admin.scan-qr.index') }}" class="text-xs text-kmdgi-primary font-bold hover:underline flex items-center gap-1">
                            Buka Scanner
                        </a>
                    </div>

                    <div class="flex-grow">
                        @php
                        $recentScans = \App\Models\TiketPeserta::with('user')
                        ->whereNotNull('waktu_kehadiran')
                        ->orderBy('waktu_kehadiran', 'desc')
                        ->take(4)
                        ->get();
                        @endphp

                        @if($recentScans->isEmpty())
                        <div class="h-full flex flex-col items-center justify-center text-center py-8">
                            <div class="text-slate-200 mb-3">
                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-600 text-sm">Belum Ada Data Masuk</h4>
                            <p class="text-xs text-slate-400">Peserta yang di-scan hari ini akan muncul di sini secara real-time.</p>
                        </div>
                        @else
                        <div class="relative border-l-2 border-slate-100 ml-3 space-y-6">
                            @foreach($recentScans as $scan)
                            <div class="relative pl-6">
                                <!-- Dot Timeline -->
                                <span class="absolute -left-[5px] top-1 w-2 h-2 rounded-full bg-emerald-500 ring-4 ring-emerald-50"></span>

                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm leading-tight">{{ $scan->user->name ?? 'Anonim' }}</p>
                                        <p class="text-xs text-slate-500">{{ $scan->jenis_tiket }} • <span class="uppercase tracking-wider font-mono text-kmdgi-primary">{{ $scan->kode_tiket }}</span></p>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400">{{ \Carbon\Carbon::parse($scan->waktu_kehadiran)->diffForHumans() }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </section>

            </div>

            <section class="bg-white rounded-[2rem] border border-slate-100 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.005)] min-h-[300px] flex items-center justify-center text-center">
                <div class="max-w-sm space-y-2">
                    <div class="text-slate-300 inline-block p-4 bg-slate-50 rounded-full mb-2">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                        </svg>
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