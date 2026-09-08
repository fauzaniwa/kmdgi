@extends('layouts.app')

@section('title', 'Dashboard - KMDGI 16')

@section('content')

@php
    // Mencegah error "Undefined variable $header" akibat navbar/layout yang membutuhkan variabel ini
    $header = $header ?? null;
@endphp

<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">
        
        @include('partials.sidebar')

        <main class="flex-grow space-y-8 w-full">
            
            <!-- HEADER PROFILE SECTION -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
                <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                    <div class="w-16 h-16 rounded-full bg-slate-200 overflow-hidden border-2 border-white shadow-md flex-shrink-0">
                        @if(Auth::user()->profile_image)
                            <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Foto Profil {{ Auth::user()->name }}" class="w-full h-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=126CFD&color=fff" alt="Avatar" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 leading-tight">{{ Auth::user()->name }}</h2>
                        <p class="text-xs text-slate-400 font-medium capitalize mt-0.5">
                            @if(Auth::user()->role === 'peserta')
                                {{ Auth::user()->kategori }} Peserta 
                                @if(Auth::user()->kategori === 'Delegasi')
                                    &bull; {{ Auth::user()->peran_delegasi }}
                                @endif
                            @else
                                {{ Auth::user()->role }}
                            @endif
                        </p>
                        @if(Auth::user()->institusi)
                            <div class="inline-flex items-center gap-1.5 mt-2 px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 text-[11px] font-bold tracking-wide">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.315 48.315 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" /></svg>
                                <span class="truncate max-w-[200px] md:max-w-md">{{ Auth::user()->institusi }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 border border-slate-200 hover:border-kmdgi-primary hover:text-kmdgi-primary text-slate-600 font-semibold px-4 py-2.5 rounded-xl text-sm transition-colors bg-white shadow-sm flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    Edit Profile
                </a>
            </div>

            <!-- ========================================================================= -->
            <!-- ALERT FORM: JIKA DELEGASI BELUM TERHUBUNG DENGAN KAMPUS & AUTH CODE       -->
            <!-- ========================================================================= -->
            @if(Auth::user()->kategori === 'Delegasi' && empty(Auth::user()->auth_code))
                <div class="bg-amber-50 border-2 border-amber-200 rounded-[2rem] p-6 shadow-sm">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 hidden sm:flex">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" /></svg>
                        </div>
                        <div class="flex-grow w-full">
                            <h3 class="text-lg font-bold text-amber-900 tracking-tight">Menunggu Sinkronisasi Tim Delegasi</h3>
                            <p class="text-sm text-amber-700 mt-1 mb-4 leading-relaxed font-medium">Akun delegasi Anda belum terhubung dengan institusi/kampus manapun. Silakan masukkan nama kampus beserta <strong>Auth Code</strong> yang diberikan oleh Ketua Delegasi kampus Anda untuk membuka seluruh fitur pengunggahan karya.</p>
                            
                            @if ($errors->has('auth_code'))
                                <div class="bg-red-50 text-red-600 text-xs font-bold px-3 py-2 rounded-lg border border-red-100 mb-4 inline-block">
                                    {{ $errors->first('auth_code') }}
                                </div>
                            @endif

                            <form action="{{ route('profile.update') }}" method="POST" class="flex flex-col lg:flex-row items-stretch gap-3 relative z-20">
                                @csrf
                                @method('PATCH')
                                
                                <!-- Custom Dropdown Kampus -->
                                <div class="w-full lg:w-2/5 relative" id="custom-select-wrapper">
                                    <input type="hidden" name="institusi" id="institusi" value="{{ old('institusi') }}">
                                    
                                    <button type="button" id="custom-select-button" class="w-full px-4 py-2.5 rounded-xl border border-amber-300 text-sm text-amber-900 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 bg-white flex justify-between items-center text-left">
                                        <span id="custom-select-text" class="truncate pr-4 {{ old('institusi') ? 'text-amber-900 font-semibold' : 'text-amber-500/70' }}">
                                            {{ old('institusi') ?? 'Pilih Kampus Delegasi...' }}
                                        </span>
                                        <svg class="w-5 h-5 text-amber-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    
                                    <div id="custom-select-dropdown" class="absolute z-50 w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-2xl hidden flex-col max-h-80 overflow-hidden transform opacity-0 scale-95 transition-all duration-200">
                                        <div class="p-3 border-b border-slate-100 bg-slate-50/50">
                                            <div class="relative">
                                                <svg class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 1116.65 1.65a7.5 7.5 0 010 15z" />
                                                </svg>
                                                <input type="text" id="custom-select-search" placeholder="Cari nama kampus..." class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
                                            </div>
                                        </div>
                                        <ul id="custom-select-options" class="overflow-y-auto flex-1 p-2 space-y-0.5 custom-scrollbar">
                                            @forelse($dataKampus ?? [] as $kampus)
                                                <li data-value="{{ $kampus->nama_institusi }}" class="select-option px-3 py-2.5 rounded-lg text-sm text-slate-700 hover:bg-amber-50 hover:text-amber-600 cursor-pointer transition-colors font-medium">
                                                    {{ $kampus->nama_institusi }}
                                                </li>
                                            @empty
                                                <li class="px-3 py-2.5 text-sm text-slate-400">Data kampus belum tersedia.</li>
                                            @endforelse
                                        </ul>
                                        <div id="custom-select-empty" class="hidden p-6 text-center">
                                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" />
                                            </svg>
                                            <p class="text-sm text-slate-400">Kampus tidak ditemukan.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Dropdown Kampus -->

                                <div class="w-full lg:w-2/5">
                                    <input type="text" name="auth_code" required placeholder="Auth Code (Misal: KMDGIABCD)" value="{{ old('auth_code') }}" class="w-full px-4 py-2.5 rounded-xl border border-amber-300 text-sm text-amber-900 placeholder-amber-500/70 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 bg-white uppercase">
                                </div>
                                
                                <div class="w-full lg:w-auto flex-shrink-0">
                                    <button type="submit" class="w-full lg:w-auto bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 px-6 rounded-xl transition-colors text-sm shadow-sm h-full whitespace-nowrap">
                                        Hubungkan Tim
                                    </button>
                                </div>
                            </form>
                            
                            <p class="text-[10px] text-amber-600/70 mt-3 font-semibold relative z-10">*Jika Anda belum memiliki ketua, minta satu perwakilan dari kampus Anda untuk mendaftar sebagai Ketua Delegasi dan mengklaim nama kampus terlebih dahulu.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- FLASH MESSAGE BERHASIL -->
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm mb-6">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                <span class="text-sm font-semibold">{{ session('success') }}</span>
            </div>
            @endif


            <!-- TIKET PAMERAN SECTION -->
            @php
                $tiketPameran = \App\Models\TiketPeserta::where('user_id', Auth::id())->where('jenis_tiket', 'Pameran')->first();
            @endphp

            @if($tiketPameran)
            <section class="space-y-4">
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Tiket Pameran</h3>
                <p class="text-xs text-slate-500 -mt-2">Gunakan tiket ini untuk masuk ke area pameran.</p>
                
                @if($tiketPameran->status === 'Menunggu Konfirmasi')
                    <div class="qr-card bg-amber-50/50 p-6 rounded-[2rem] border border-amber-100 shadow-sm flex flex-col md:flex-row items-center gap-6">
                        <div class="w-32 h-32 bg-white flex flex-col items-center justify-center rounded-2xl flex-shrink-0 p-4 border border-amber-200 shadow-inner">
                            <svg class="w-8 h-8 text-amber-400 mb-2 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="text-[10px] font-black text-amber-600 uppercase text-center leading-tight">Menunggu<br>Verifikasi</span>
                        </div>
                        <div class="flex-grow text-center md:text-left space-y-2 w-full">
                            <span class="text-xs font-bold text-amber-500 uppercase tracking-wider block">Status Tiket</span>
                            <h4 class="text-xl font-black text-slate-900 tracking-tight">Dalam Pengecekan Admin</h4>
                            <p class="text-xs text-slate-500 leading-relaxed max-w-md">Terima kasih. Pembayaran Anda sedang diperiksa oleh panitia. QR Code tiket akan otomatis muncul di sini setelah diverifikasi.</p>
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50/60 border border-yellow-100 rounded-2xl p-4 flex gap-3 items-start text-xs text-amber-800 leading-relaxed">
                        <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p>Naikkan kecerahan layar HP Anda secara manual sebelum men-scan tiket di pintu masuk.</p>
                    </div>

                    <div class="qr-card bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] flex flex-col md:flex-row items-center gap-6">
                        <div class="w-32 h-32 bg-white flex items-center justify-center rounded-2xl flex-shrink-0 p-2 border border-slate-200">
                            <div class="qr-render" data-kode="{{ $tiketPameran->kode_tiket }}"></div>
                        </div>
                        <div class="flex-grow text-center md:text-left space-y-2 w-full">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Ticket Code</span>
                            <h4 class="text-xl font-black text-slate-900 tracking-tight">{{ $tiketPameran->kode_tiket }}</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">*Satu QR Code hanya berlaku untuk 1 (satu) orang pengunjung yang terdaftar.</p>
                        </div>
                        <div class="flex flex-col gap-2 w-full md:w-auto flex-shrink-0">
                            <button onclick="showFullscreenQR('{{ $tiketPameran->kode_tiket }}', 'Tiket Pameran KMDGI 16')" class="w-full text-center bg-slate-900 hover:bg-slate-800 text-white font-bold px-5 py-3 rounded-xl text-xs transition-colors shadow-sm">
                                Tampilkan Penuh
                            </button>
                            <button onclick="downloadQR(this, 'Tiket-Pameran-{{ Auth::user()->name }}')" class="w-full flex items-center justify-center gap-2 border border-slate-200 hover:border-kmdgi-primary text-slate-700 hover:text-kmdgi-primary font-bold px-5 py-3 rounded-xl text-xs transition-colors bg-white">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4-4m4 4V4" /></svg>
                                Unduh Gambar
                            </button>
                        </div>
                    </div>
                @endif
            </section>
            @endif

            <!-- TIKET SEMINAR & WORKSHOP SECTION -->
            @php
                $tiketEvents = \App\Models\TiketPeserta::with('event')->where('user_id', Auth::id())->whereIn('jenis_tiket', ['Seminar', 'Workshop'])->get();
            @endphp

            <section class="space-y-4">
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Tiket Seminar dan Workshop</h3>
                <p class="text-xs text-slate-500 -mt-2">Gunakan tiket ini untuk mengikuti sesi yang Anda daftar.</p>
                
                @if($tiketEvents->count() > 0)
                    <div class="space-y-4">
                        @foreach($tiketEvents as $tiketExt)
                            @php $eventTerkait = $tiketExt->event; @endphp
                            
                            @if($eventTerkait)
                                @if($tiketExt->status === 'Menunggu Konfirmasi')
                                    <div class="qr-card bg-amber-50/50 p-5 rounded-[2rem] border border-amber-100 shadow-sm flex flex-col md:flex-row gap-5 items-center">
                                        <div class="w-full md:w-28 h-28 bg-white rounded-2xl flex-shrink-0 flex flex-col items-center justify-center p-2 border border-amber-200 shadow-inner">
                                            <svg class="w-7 h-7 text-amber-400 mb-1 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            <span class="text-[8px] font-black text-amber-600 uppercase text-center leading-tight">Menunggu<br>Verifikasi</span>
                                        </div>
                                        <div class="flex-grow space-y-1.5 text-center md:text-left w-full">
                                            <div class="inline-block px-2.5 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-bold uppercase tracking-wider rounded-md mb-1">Dalam Pengecekan</div>
                                            <h4 class="font-bold text-slate-900 text-base leading-snug">{{ $eventTerkait->judul }}</h4>
                                            <p class="text-xs text-slate-500 max-w-sm mt-1">Status pembayaran Anda sedang ditinjau. Harap tunggu hingga QR Code otomatis dirilis di sini.</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="qr-card bg-white p-5 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] flex flex-col md:flex-row gap-5 items-center">
                                        <div class="w-full md:w-28 h-28 bg-white rounded-2xl flex-shrink-0 flex items-center justify-center p-2 border border-slate-200">
                                            <div class="qr-render" data-kode="{{ $tiketExt->kode_tiket }}"></div>
                                        </div>
                                        <div class="flex-grow space-y-1.5 text-center md:text-left w-full">
                                            <div class="inline-block px-2.5 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider rounded-md mb-1">{{ $tiketExt->jenis_tiket }}</div>
                                            <h4 class="font-bold text-slate-900 text-base leading-snug">{{ $eventTerkait->judul }}</h4>
                                            <div class="flex flex-wrap justify-center md:justify-start gap-x-4 gap-y-1 text-slate-400 text-xs">
                                                <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> {{ $eventTerkait->jam_pelaksanaan ?? 'Menyusul' }}</span>
                                                <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> {{ $eventTerkait->tanggal_pelaksanaan ? \Carbon\Carbon::parse($eventTerkait->tanggal_pelaksanaan)->translatedFormat('d F Y') : 'Menyusul' }}</span>
                                            </div>
                                            <div class="text-[10px] text-slate-400 pt-1 font-semibold">Ticket Code: <span class="text-slate-700">{{ $tiketExt->kode_tiket }}</span></div>
                                        </div>
                                        <div class="flex flex-col gap-2 w-full md:w-auto flex-shrink-0">
                                            <button onclick="showFullscreenQR('{{ $tiketExt->kode_tiket }}', '{{ addslashes($eventTerkait->judul) }}')" class="w-full text-center bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-colors">Tampilkan Penuh</button>
                                            <button onclick="downloadQR(this, 'Tiket-{{ $tiketExt->jenis_tiket }}-{{ Auth::user()->name }}')" class="w-full flex items-center justify-center gap-2 border border-slate-200 text-slate-700 hover:text-kmdgi-primary font-bold px-4 py-2.5 rounded-xl text-xs bg-white transition-colors">Unduh Gambar</button>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] text-center flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" /></svg>
                        </div>
                        <h4 class="text-base font-bold text-slate-700">Belum Ada Tiket Seminar/Workshop</h4>
                        <p class="text-sm text-slate-500 mt-1 max-w-sm">Anda belum mendaftar untuk sesi Seminar maupun Workshop.</p>
                    </div>
                @endif
            </section>

            <!-- TIKET PERFORMANCE SECTION -->
            @php
                $tiketPerformances = \App\Models\TiketPeserta::with('penampil')->where('user_id', Auth::id())->where('jenis_tiket', 'Performance')->get();
            @endphp

            <section class="space-y-4">
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Tiket Performance</h3>
                <p class="text-xs text-slate-500 -mt-2">Gunakan tiket ini untuk menghadiri panggung pertunjukan dan konser.</p>
                
                @if($tiketPerformances->count() > 0)
                    <div class="space-y-4">
                        @foreach($tiketPerformances as $tiketPerf)
                            @php $penampilTerkait = $tiketPerf->penampil; @endphp
                            
                            @if($penampilTerkait)
                                @if($tiketPerf->status === 'Menunggu Konfirmasi')
                                    <div class="qr-card bg-amber-50/50 p-5 rounded-[2rem] border border-amber-100 shadow-sm flex flex-col md:flex-row gap-5 items-center">
                                        <div class="w-full md:w-28 h-28 bg-white rounded-2xl flex-shrink-0 flex flex-col items-center justify-center p-2 border border-amber-200 shadow-inner">
                                            <svg class="w-7 h-7 text-amber-400 mb-1 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            <span class="text-[8px] font-black text-amber-600 uppercase text-center leading-tight">Menunggu<br>Verifikasi</span>
                                        </div>
                                        <div class="flex-grow space-y-1.5 text-center md:text-left w-full">
                                            <div class="inline-block px-2.5 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-bold uppercase tracking-wider rounded-md mb-1">Dalam Pengecekan</div>
                                            <h4 class="font-bold text-slate-900 text-base leading-snug">{{ $penampilTerkait->nama_penampil }}</h4>
                                            <p class="text-xs text-slate-500 max-w-sm mt-1">Bukti pembayaran tiket performance Anda sedang diverifikasi oleh panitia.</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="qr-card bg-white p-5 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] flex flex-col md:flex-row gap-5 items-center">
                                        <div class="w-full md:w-28 h-28 bg-white rounded-2xl flex-shrink-0 flex items-center justify-center p-2 border border-slate-200">
                                            <div class="qr-render" data-kode="{{ $tiketPerf->kode_tiket }}"></div>
                                        </div>
                                        <div class="flex-grow space-y-1.5 text-center md:text-left w-full">
                                            <div class="inline-block px-2.5 py-0.5 bg-purple-50 text-purple-600 text-[10px] font-bold uppercase tracking-wider rounded-md mb-1">Performance ({{ $penampilTerkait->kategori_penampil }})</div>
                                            <h4 class="font-bold text-slate-900 text-base leading-snug">{{ $penampilTerkait->nama_penampil }}</h4>
                                            <div class="flex flex-wrap justify-center md:justify-start gap-x-4 gap-y-1 text-slate-400 text-xs">
                                                <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> {{ $penampilTerkait->jam_mulai ? \Carbon\Carbon::parse($penampilTerkait->jam_mulai)->format('H:i') : 'Menyusul' }} WIB</span>
                                                <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> {{ $penampilTerkait->tanggal_tampil ? \Carbon\Carbon::parse($penampilTerkait->tanggal_tampil)->translatedFormat('d F Y') : 'Menyusul' }}</span>
                                            </div>
                                            <div class="text-[10px] text-slate-400 pt-1 font-semibold">Ticket Code: <span class="text-slate-700">{{ $tiketPerf->kode_tiket }}</span></div>
                                        </div>
                                        <div class="flex flex-col gap-2 w-full md:w-auto flex-shrink-0">
                                            <button onclick="showFullscreenQR('{{ $tiketPerf->kode_tiket }}', '{{ addslashes($penampilTerkait->nama_penampil) }}')" class="w-full text-center bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-colors">Tampilkan Penuh</button>
                                            <button onclick="downloadQR(this, 'Tiket-Performance-{{ Auth::user()->name }}')" class="w-full flex items-center justify-center gap-2 border border-slate-200 text-slate-700 hover:text-kmdgi-primary font-bold px-4 py-2.5 rounded-xl text-xs bg-white transition-colors">Unduh Gambar</button>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] text-center flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9l10.5-3m0 6.553v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 11-.99-3.467l2.31-.66a2.25 2.25 0 001.632-2.163zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 01-.99-3.467l2.31-.66A2.25 2.25 0 009 15.53V5.25" /></svg>
                        </div>
                        <h4 class="text-base font-bold text-slate-700">Belum Ada Tiket Performance</h4>
                        <p class="text-sm text-slate-500 mt-1 max-w-sm">Anda belum mendaftar untuk menghadiri konser atau pertunjukan seni.</p>
                    </div>
                @endif
            </section>
        </main>
    </div>
    @include('partials.footer')
</div>

<!-- ========================================== -->
<!-- MODAL FULLSCREEN QR CODE                   -->
<!-- ========================================== -->
<div id="qrFullscreenModal" class="fixed inset-0 z-[100] bg-white hidden flex-col items-center justify-center">
    
    <!-- Tombol Tutup -->
    <button onclick="closeFullscreenQR()" class="absolute top-6 right-6 p-3 bg-slate-100 text-slate-600 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors focus:outline-none">
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <div class="text-center px-4 w-full max-w-lg">
        <h2 id="qrFullscreenTitle" class="text-2xl md:text-3xl font-black text-slate-900 mb-2 leading-tight">Nama Acara</h2>
        <p class="text-slate-500 text-sm mb-10">Tunjukkan QR Code ini kepada panitia di pintu masuk.</p>
        
        <div class="inline-block bg-white p-4 md:p-8 rounded-3xl border-2 border-slate-100 shadow-2xl mb-8">
            <div id="qrFullscreenRender" class="flex items-center justify-center"></div>
        </div>
        
        <div class="bg-slate-100 py-3 px-6 rounded-xl inline-block">
            <p class="text-xs text-slate-500 uppercase tracking-widest font-bold mb-1">Kode Tiket</p>
            <p id="qrFullscreenSubtitle" class="text-2xl font-mono font-black text-slate-900 tracking-wider">KM16XXXXXDGI</p>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #fcd34d; border-radius: 10px; } /* Warna amber-300 */
</style>

<!-- Impor Library QRCode.js melalui CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    // JS UNTUK CUSTOM SELECT DROPDOWN KAMPUS
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('custom-select-wrapper');
        const button = document.getElementById('custom-select-button');
        const buttonText = document.getElementById('custom-select-text');
        const dropdown = document.getElementById('custom-select-dropdown');
        const searchInput = document.getElementById('custom-select-search');
        const optionsList = document.getElementById('custom-select-options');
        const hiddenInput = document.getElementById('institusi');
        const emptyState = document.getElementById('custom-select-empty');
        
        if(wrapper) {
            const options = optionsList.querySelectorAll('.select-option');

            function toggleDropdown(forceClose = false) {
                if (forceClose || !dropdown.classList.contains('hidden')) {
                    dropdown.classList.remove('opacity-100', 'scale-100');
                    dropdown.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        dropdown.classList.add('hidden');
                        dropdown.classList.remove('flex');
                    }, 200); 
                } else {
                    dropdown.classList.remove('hidden');
                    dropdown.classList.add('flex');
                    setTimeout(() => {
                        dropdown.classList.remove('opacity-0', 'scale-95');
                        dropdown.classList.add('opacity-100', 'scale-100');
                        searchInput.focus(); 
                    }, 10);
                }
            }

            button.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleDropdown();
            });

            searchInput.addEventListener('input', function() {
                const filter = searchInput.value.toLowerCase();
                let hasVisibleOptions = false;

                options.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    if (text.includes(filter)) {
                        option.style.display = 'block';
                        hasVisibleOptions = true;
                    } else {
                        option.style.display = 'none';
                    }
                });

                if (hasVisibleOptions) {
                    emptyState.classList.add('hidden');
                    optionsList.classList.remove('hidden');
                } else {
                    emptyState.classList.remove('hidden');
                    optionsList.classList.add('hidden');
                }
            });

            options.forEach(option => {
                option.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    const text = this.textContent.trim();

                    buttonText.textContent = text;
                    buttonText.classList.remove('text-amber-500/70');
                    buttonText.classList.add('text-amber-900', 'font-semibold');

                    hiddenInput.value = value;
                    toggleDropdown(true);

                    searchInput.value = '';
                    options.forEach(opt => opt.style.display = 'block');
                    emptyState.classList.add('hidden');
                    optionsList.classList.remove('hidden');
                });
            });

            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            document.addEventListener('click', function(e) {
                if (!wrapper.contains(e.target) && !dropdown.classList.contains('hidden')) {
                    toggleDropdown(true);
                }
            });
        }
    });

    // 1. Render Semua QR Code Secara Lokal di Halaman
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.qr-render').forEach(container => {
            const kode = container.getAttribute('data-kode');
            new QRCode(container, {
                text: kode,
                width: 100,
                height: 100,
                colorDark : "#0f172a",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        });
    });

    // 2. Fungsi Mengunduh QR Code
    function downloadQR(btnElement, filename) {
        const card = btnElement.closest('.qr-card');
        const canvas = card.querySelector('.qr-render canvas');
        const img = card.querySelector('.qr-render img');
        
        let dataUrl = '';
        if(canvas) {
            dataUrl = canvas.toDataURL("image/png");
        } else if (img && img.src) {
            dataUrl = img.src;
        } else {
            alert('Gagal mengambil data gambar. Silakan muat ulang halaman.');
            return;
        }

        const link = document.createElement('a');
        link.href = dataUrl;
        link.download = filename + '.png';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // 3. Fungsi Fullscreen & Wake Lock
    let wakeLock = null;

    async function showFullscreenQR(kode, acara) {
        const modal = document.getElementById('qrFullscreenModal');
        const renderArea = document.getElementById('qrFullscreenRender');
        
        document.getElementById('qrFullscreenTitle').innerText = acara;
        document.getElementById('qrFullscreenSubtitle').innerText = kode;

        renderArea.innerHTML = '';
        new QRCode(renderArea, {
            text: kode,
            width: Math.min(window.innerWidth - 80, 300), 
            height: Math.min(window.innerWidth - 80, 300),
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        try {
            if (modal.requestFullscreen) {
                await modal.requestFullscreen();
            } else if (modal.webkitRequestFullscreen) {
                await modal.webkitRequestFullscreen();
            } else if (modal.msRequestFullscreen) {
                await modal.msRequestFullscreen();
            }
        } catch (err) {
            console.log("Browser menolak fullscreen: ", err);
        }

        try {
            if ('wakeLock' in navigator) {
                wakeLock = await navigator.wakeLock.request('screen');
            }
        } catch (err) {
            console.log("Wake Lock API tidak didukung: ", err);
        }
    }

    function closeFullscreenQR() {
        const modal = document.getElementById('qrFullscreenModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        if (document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement) {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }

        if (wakeLock !== null) {
            wakeLock.release().then(() => {
                wakeLock = null;
            });
        }
    }
</script>
@endsection