@extends('layouts.app')

@section('title', 'Status Keanggotaan - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col relative">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">
        
        <!-- PANGGIL SIDEBAR UTAMA -->
        @include('partials.sidebar')

        <!-- KONTEN UTAMA: STATUS KEANGGOTAAN -->
        <main class="flex-grow space-y-8 w-full relative z-10">
            
            <!-- Header Halaman -->
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Status Keanggotaan</h1>
                <p class="text-sm text-slate-500 mt-1">Informasi mengenai peran dan status <span class="font-bold text-slate-700">{{ $kampus->nama_institusi }}</span> dalam kontingen KMDGI 16.</p>
            </div>

            <!-- Banner Utama (Identitas Kampus) -->
            <div class="bg-white p-8 md:p-10 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
                
                <!-- Dekorasi -->
                <div class="absolute -top-16 -right-16 w-64 h-64 bg-slate-50 rounded-full blur-3xl pointer-events-none opacity-60"></div>
                
                <!-- Logo Kampus -->
                <div class="w-32 h-32 md:w-40 md:h-40 bg-white border border-slate-100 rounded-3xl p-4 shadow-sm flex items-center justify-center flex-shrink-0 relative z-10">
                    @if($kampus->logo_institusi)
                        <img src="{{ asset('storage/' . $kampus->logo_institusi) }}" alt="Logo {{ $kampus->nama_institusi }}" class="max-w-full max-h-full object-contain drop-shadow-sm">
                    @else
                        <!-- Fallback jika kampus tidak punya logo -->
                        <div class="w-full h-full bg-slate-50 rounded-xl flex items-center justify-center text-slate-300">
                            <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.315 48.315 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Informasi Kampus -->
                <div class="flex-grow text-center md:text-left space-y-4 relative z-10 w-full">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight leading-tight mb-2">{{ $kampus->nama_institusi }}</h2>
                        <div class="flex items-center justify-center md:justify-start gap-1.5 text-sm font-semibold text-slate-500">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            {{ $kampus->lokasi_kota }}
                        </div>
                    </div>

                    <!-- Label Status Keanggotaan -->
                    <div class="pt-2">
                        <span class="text-xs uppercase tracking-wider font-bold text-slate-400 block mb-2">Status Saat Ini:</span>
                        
                        @php
                            $status = strtolower(trim($kampus->status_keanggotaan));
                            $bgColor = 'bg-slate-100';
                            $textColor = 'text-slate-600';
                            $borderColor = 'border-slate-200';
                            
                            // Logika Pewarnaan Berdasarkan Status
                            if(str_contains($status, 'anggota penuh')) {
                                $bgColor = 'bg-emerald-50'; $textColor = 'text-emerald-700'; $borderColor = 'border-emerald-200';
                            } elseif (str_contains($status, 'peninjau')) {
                                $bgColor = 'bg-amber-50'; $textColor = 'text-amber-700'; $borderColor = 'border-amber-200';
                            } elseif (str_contains($status, 'tuan rumah') || str_contains($status, 'aktif')) {
                                $bgColor = 'bg-blue-50'; $textColor = 'text-blue-700'; $borderColor = 'border-blue-200';
                            }
                        @endphp

                        <div class="inline-flex items-center px-4 py-2 rounded-xl border {{ $bgColor }} {{ $borderColor }} {{ $textColor }} font-bold text-sm shadow-sm">
                            <span class="w-2 h-2 rounded-full {{ str_replace('text', 'bg', $textColor) }} mr-2 animate-pulse"></span>
                            {{ $kampus->status_keanggotaan }}
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi Opsional (Misal Hubungi LO Kampus / Website) -->
                @if($kampus->link_wa)
                <div class="flex-shrink-0 w-full md:w-auto relative z-10 pt-4 md:pt-0">
                    <a href="{{ $kampus->link_wa }}" target="_blank" class="w-full md:w-auto flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#1DA851] text-white font-bold px-6 py-3.5 rounded-xl text-sm transition-all shadow-md shadow-[#25D366]/20">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Hubungi LO
                    </a>
                </div>
                @endif

            </div>

            <!-- Detail Peran Anggota -->
            <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
                <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-6">Informasi Personal</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nama Delegasi</p>
                        <p class="text-base font-bold text-slate-800">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Peran dalam Tim</p>
                        <div class="inline-flex items-center gap-1.5 text-sm font-semibold {{ $user->peran_delegasi === 'Ketua' ? 'text-indigo-600' : 'text-slate-700' }}">
                            @if($user->peran_delegasi === 'Ketua')
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            @endif
                            {{ $user->peran_delegasi }}
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Auth Code (Kode Akses Tim)</p>
                        <p class="text-sm font-mono font-bold text-slate-700 bg-slate-100 py-1 px-3 rounded-lg inline-block">{{ $user->auth_code }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tanggal Terdaftar</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $user->created_at->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
            </div>

        </main>
    </div>

    @include('partials.footer')
</div>
@endsection