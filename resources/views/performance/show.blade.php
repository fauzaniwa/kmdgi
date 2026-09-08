@extends('layouts.app')
@section('title', $penampil->nama_penampil . ' - KMDGI 16')

@section('meta')
<meta property="og:title" content="{{ $penampil->nama_penampil }} - Performance KMDGI 16">
<meta property="og:description" content="{{ Str::limit(strip_tags($penampil->deskripsi_penampil), 150) }}">
<meta property="og:image" content="{{ $penampil->cover_penampil ? asset('storage/' . $penampil->cover_penampil) : asset('images/default-hero.jpg') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $penampil->nama_penampil }} - Performance KMDGI 16">
<meta name="twitter:description" content="{{ Str::limit(strip_tags($penampil->deskripsi_penampil), 150) }}">
<meta name="twitter:image" content="{{ $penampil->cover_penampil ? asset('storage/' . $penampil->cover_penampil) : asset('images/default-hero.jpg') }}">
@endsection

@section('content')
<div class="bg-[#FFFDFD] min-h-screen flex flex-col relative font-sans overflow-x-hidden">
    @include('partials.navbar')

    <!-- HERO SECTION -->
    <section class="relative w-full h-[50vh] md:h-[65vh] bg-slate-900">
        @if($penampil->cover_penampil)
            <img src="{{ asset('storage/' . $penampil->cover_penampil) }}" alt="Cover {{ $penampil->nama_penampil }}" class="w-full h-full object-cover object-top opacity-90" />
        @else
            <div class="w-full h-full flex items-center justify-center bg-slate-800">
                <span class="text-4xl md:text-6xl font-black text-slate-700 opacity-50">{{ strtoupper(substr($penampil->nama_penampil, 0, 1)) }}</span>
            </div>
        @endif
        
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>

        <a href="{{ route('performance.index') }}" class="absolute top-24 md:top-32 left-4 sm:left-6 lg:left-12 inline-flex items-center gap-2 text-white/80 hover:text-white font-medium text-sm transition-colors bg-black/20 px-4 py-2 rounded-full backdrop-blur-md border border-white/10 z-20">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali
        </a>
    </section>

    <!-- KONTEN UTAMA -->
    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 relative z-10 -mt-24 md:-mt-48 pb-24 flex-grow">
        
        <!-- Pesan Error/Sukses -->
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-xl text-sm font-semibold shadow-sm mb-8 flex items-center gap-3 w-full lg:w-[55%]">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            {{ $errors->first() }}
        </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-10 lg:gap-16 items-start">

            <!-- BAGIAN KIRI -->
            <div class="w-full lg:w-[55%] flex flex-col">
                <h1 class="text-4xl md:text-[3rem] font-black text-white tracking-tight leading-[1.1] mb-6 drop-shadow-lg">
                    {{ $penampil->nama_penampil }}
                </h1>

                <!-- Card Jadwal & Lokasi -->
                <div class="bg-[#FFFDFD] rounded-[1.5rem] p-6 md:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-slate-100 mb-10 w-full md:max-w-md">
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <svg class="w-5 h-5 text-slate-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span class="text-sm font-bold text-slate-800 tracking-wide">
                                {{ $penampil->jam_mulai ? \Carbon\Carbon::parse($penampil->jam_mulai)->format('H.i') : '-' }} - {{ $penampil->jam_selesai ? \Carbon\Carbon::parse($penampil->jam_selesai)->format('H.i') : '-' }} WIB
                            </span>
                        </div>
                        <div class="flex items-center gap-4">
                            <svg class="w-5 h-5 text-slate-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm font-bold text-slate-800 tracking-wide">
                                {{ $penampil->tanggal_tampil ? \Carbon\Carbon::parse($penampil->tanggal_tampil)->locale('id')->translatedFormat('d F Y') : '-' }}
                            </span>
                        </div>
                        <div class="flex items-start gap-4">
                            <svg class="w-5 h-5 text-slate-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            <span class="text-sm font-bold text-slate-800 tracking-wide leading-relaxed">
                                {{ $penampil->lokasi_tampil ?? '-' }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Harga & Aksi Tombol (Hanya muncul jika berbayar/tidak gratis) -->
                    @php
                        $isFree = (strtolower($penampil->tipe_pendaftaran) == 'gratis' || $penampil->harga_tiket == 0);
                    @endphp

                    <div class="border-t border-slate-200 mt-6 pt-6">
                        <div class="flex justify-between items-end mb-4">
                            <span class="text-xs font-bold text-slate-400 uppercase">Harga Tiket</span>
                            <span class="text-xl font-black text-[#1A68FF]">{{ $isFree ? 'FREE' : 'Rp ' . number_format($penampil->harga_tiket, 0, ',', '.') }}</span>
                        </div>

                        @if($isFree)
                            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-bold p-3.5 rounded-xl text-center">
                                Penampilan ini terbuka untuk umum secara gratis tanpa registrasi tiket.
                            </div>
                        @else
                            @if(!Auth::check())
                                <a href="{{ route('login') }}" class="block w-full bg-[#1A68FF] text-white text-center font-bold py-3 rounded-xl shadow-md hover:bg-blue-700 transition-colors">
                                    Masuk untuk Beli Tiket
                                </a>
                            @else
                                @if(isset($tiketSaya) && $tiketSaya)
                                    @if($tiketSaya->status === 'Menunggu Konfirmasi')
                                        <button disabled class="w-full bg-amber-50 text-amber-600 border border-amber-200 font-bold py-3 rounded-xl cursor-not-allowed">
                                            Menunggu Konfirmasi
                                        </button>
                                    @else
                                        <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-2 w-full bg-emerald-50 text-emerald-600 border border-emerald-200 font-bold py-3 rounded-xl hover:bg-emerald-100 transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                            Tiket Terdaftar
                                        </a>
                                    @endif
                                @else
                                    <button onclick="openDaftarModal()" class="block w-full bg-[#1A68FF] text-white text-center font-bold py-3 rounded-xl shadow-md shadow-blue-500/20 hover:bg-blue-700 transition-colors">
                                        Daftar / Beli Tiket
                                    </button>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Section Tentang -->
                <div class="mb-10">
                    <h3 class="text-xl md:text-2xl font-black text-slate-900 mb-6">Tentang</h3>
                    <div class="grid grid-cols-3 gap-4 border-b border-slate-200 pb-6">
                        <div>
                            <p class="text-xs font-bold text-slate-500 mb-1.5">Dari</p>
                            <p class="text-sm font-bold text-slate-900 leading-snug">{{ $penampil->asal_penampil ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 mb-1.5">Dibentuk</p>
                            <p class="text-sm font-bold text-slate-900 leading-snug">{{ $penampil->tahun_dibentuk ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 mb-1.5">Genre</p>
                            <p class="text-sm font-bold text-slate-900 leading-snug">{{ $penampil->genre_musik ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Section Latar Belakang -->
                <div>
                    <h3 class="text-xl md:text-2xl font-black text-slate-900 mb-4">Latar Belakang</h3>
                    <div class="prose prose-slate max-w-none text-sm md:text-[15px] text-slate-700 leading-relaxed font-medium">
                        {!! $penampil->deskripsi_penampil !!}
                    </div>
                </div>

            </div>

            <!-- BAGIAN KANAN: Spotify Embeds -->
            <div class="w-full lg:w-[45%] lg:mt-[4.5rem]">
                @if($penampil->embed_spotify)
                    <div class="w-full space-y-6 flex flex-col items-start lg:items-end">
                        <div class="w-full lg:max-w-md [&>iframe]:w-full [&>iframe]:rounded-2xl [&>iframe]:shadow-sm">
                            {!! $penampil->embed_spotify !!}
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    @include('partials.footer')
</div>

<!-- ========================================== -->
<!-- MODAL PENDAFTARAN & PEMBAYARAN (Hanya Berbayar) -->
<!-- ========================================== -->
@if(!$isFree)

@php
    // Pembagian Data Rekening QRIS dan Rekening Bank
    $qrisItems = isset($rekenings) ? $rekenings->filter(function($r) {
        return !empty($r->qr_code) || str_contains(strtoupper($r->nama_bank), 'QRIS');
    }) : collect();

    $bankItems = isset($rekenings) ? $rekenings->reject(function($r) {
        return !empty($r->qr_code) || str_contains(strtoupper($r->nama_bank), 'QRIS');
    }) : collect();
@endphp

<div id="daftarModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-8 bg-slate-900/80 backdrop-blur-sm transition-all duration-300 opacity-0 pointer-events-none overflow-y-auto">
    <div class="bg-white rounded-[2rem] overflow-hidden w-full max-w-xl shadow-2xl transform transition-all scale-95 my-auto" id="daftarModalContent">

        <div class="bg-slate-50 border-b border-slate-100 px-6 sm:px-8 py-5 flex justify-between items-center sticky top-0 z-20">
            <h3 class="text-lg font-black text-slate-900">Pembayaran Tiket</h3>
            <button type="button" onclick="closeDaftarModal()" class="text-slate-400 hover:text-red-500 bg-white border border-slate-200 p-2 rounded-full transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form action="{{ route('performance.daftar', \Illuminate\Support\Str::slug($penampil->nama_penampil)) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8">
            @csrf

            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 mb-8 text-center shadow-inner">
                <h4 class="text-indigo-900 font-bold mb-1">Total Pembayaran</h4>
                <span class="text-3xl sm:text-4xl font-black text-indigo-700 block drop-shadow-sm">Rp {{ number_format($penampil->harga_tiket, 0, ',', '.') }}</span>
                <p class="text-xs text-indigo-600 mt-2 font-medium">Tiket: <strong>{{ $penampil->nama_penampil }}</strong></p>
            </div>

            <!-- TUJUAN TRANSFER DINAMIS -->
            <div class="mb-8">
                <h4 class="font-bold text-slate-800 text-sm mb-4">Pilih Metode Transfer</h4>

                @if(isset($rekenings) && $rekenings->count() > 0)
                    
                    <!-- 1. TAMPILAN UTAMA: QRIS -->
                    @if($qrisItems->count() > 0)
                        <div class="space-y-4 mb-6">
                            @foreach($qrisItems as $qris)
                            <div class="bg-gradient-to-br from-blue-50/70 via-indigo-50/40 to-white rounded-2xl border-2 border-blue-200/80 p-5 shadow-sm flex flex-col items-center text-center relative overflow-hidden">
                                <div class="flex items-center justify-between w-full mb-4">
                                    <div class="flex items-center gap-2">
                                        <span class="bg-[#1A68FF] text-white text-[10px] font-black uppercase px-2.5 py-1 rounded-md tracking-wider">QRIS</span>
                                        <h4 class="font-extrabold text-slate-800 text-sm">{{ $qris->nama_bank }}</h4>
                                    </div>
                                    @if($qris->logo_bank)
                                    <img src="{{ asset('storage/'.$qris->logo_bank) }}" class="h-5 w-auto object-contain" alt="Logo">
                                    @endif
                                </div>

                                @if($qris->qr_code)
                                <div class="w-40 h-40 bg-white rounded-2xl p-2 border border-slate-200 shadow-md flex items-center justify-center mb-4">
                                    <img src="{{ asset('storage/'.$qris->qr_code) }}" alt="QRIS {{ $qris->nama_bank }}" class="w-full h-full object-contain">
                                </div>
                                @endif

                                <div class="space-y-1">
                                    <p class="text-[10px] text-slate-500 font-medium">Atas Nama Penerima:</p>
                                    <p class="text-sm font-black text-slate-800">{{ $qris->atas_nama }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- 2. DROPDOWN / ACCORDION: TRANSFER BANK -->
                    @if($bankItems->count() > 0)
                        <div class="space-y-2">
                            @foreach($bankItems as $index => $bank)
                            <div class="border border-slate-200 rounded-xl overflow-hidden bg-white transition-colors hover:border-slate-300">
                                <button type="button" onclick="togglePaymentAccordion({{ $index }})" class="w-full px-4 py-3.5 flex items-center justify-between text-left focus:outline-none bg-slate-50/50 hover:bg-slate-50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        @if($bank->logo_bank)
                                        <div class="w-7 h-7 rounded bg-white border border-slate-100 p-1 flex items-center justify-center shrink-0">
                                            <img src="{{ asset('storage/'.$bank->logo_bank) }}" class="w-full h-full object-contain" alt="{{ $bank->nama_bank }}">
                                        </div>
                                        @else
                                        <div class="w-7 h-7 rounded bg-blue-50 text-[#1A68FF] font-black text-[10px] flex items-center justify-center shrink-0">
                                            {{ substr($bank->nama_bank, 0, 2) }}
                                        </div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-slate-800 text-xs leading-tight">{{ $bank->nama_bank }}</p>
                                            <p class="text-[10px] text-slate-400">A.N: {{ $bank->atas_nama }}</p>
                                        </div>
                                    </div>
                                    <svg id="arrow-acc-{{ $index }}" class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>

                                <div id="content-acc-{{ $index }}" class="hidden px-4 py-3 border-t border-slate-100 bg-white">
                                    <span class="text-[10px] font-medium text-slate-400 block mb-1">Nomor Rekening Tujuan:</span>
                                    <div class="flex items-center justify-between bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                        <span class="font-mono text-sm font-extrabold text-[#1A68FF] tracking-wider">{{ $bank->nomor_rekening ?? '-' }}</span>
                                        @if($bank->nomor_rekening)
                                        <button type="button" onclick="navigator.clipboard.writeText('{{ $bank->nomor_rekening }}'); alert('Nomor Rekening Disalin!');" class="text-[10px] font-bold text-slate-600 hover:text-[#1A68FF] bg-white border border-slate-200 px-2 py-1 rounded shadow-sm hover:bg-blue-50 transition-colors">
                                            Salin
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif

                @else
                    <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl text-center text-amber-600 text-xs font-bold">
                        Data pembayaran belum dikonfigurasi. Silakan hubungi admin.
                    </div>
                @endif
            </div>

            <div class="text-left mt-6 border-t border-slate-100 pt-6">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-3">Upload Bukti Transfer <span class="text-red-500">*</span></label>

                <div id="preview-container" class="hidden mb-4 relative rounded-xl border border-slate-200 overflow-hidden w-full max-w-[200px] bg-slate-100 shadow-inner group">
                    <img id="image-preview" src="" class="w-full object-contain" alt="Preview Bukti">
                    <button type="button" onclick="hapusPreview()" class="absolute top-2 right-2 bg-red-500/90 hover:bg-red-600 text-white rounded-full p-1.5 shadow-md transition-colors backdrop-blur-sm opacity-0 group-hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <input type="file" id="file-upload" name="bukti_pembayaran" accept=".jpg,.jpeg,.png" required onchange="previewImage(event)" class="w-full text-sm text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200 cursor-pointer focus:outline-none transition-colors">
                <p class="text-[10px] text-slate-500 mt-2 font-medium">Format: JPG/PNG. Maksimal ukuran file: 3MB.</p>
            </div>

            <div class="flex gap-3 mt-8">
                <button type="button" onclick="closeDaftarModal()" class="w-1/3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3.5 rounded-xl transition-colors text-sm">Batal</button>
                <button type="submit" class="w-2/3 bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-md transition-colors text-sm">Konfirmasi Beli</button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
    function openDaftarModal() {
        const modal = document.getElementById('daftarModal');
        if(!modal) return;
        const content = document.getElementById('daftarModalContent');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-95');
    }
    
    function closeDaftarModal() {
        const modal = document.getElementById('daftarModal');
        if(!modal) return;
        const content = document.getElementById('daftarModalContent');
        modal.classList.add('opacity-0', 'pointer-events-none');
        content.classList.add('scale-95');
        hapusPreview(); 
    }

    function togglePaymentAccordion(index) {
        const content = document.getElementById('content-acc-' + index);
        const arrow = document.getElementById('arrow-acc-' + index);

        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            arrow.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        }
    }

    function previewImage(event) {
        const input = event.target;
        const container = document.getElementById('preview-container');
        const image = document.getElementById('image-preview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                image.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function hapusPreview() {
        const input = document.getElementById('file-upload');
        const container = document.getElementById('preview-container');
        const image = document.getElementById('image-preview');

        if(input) input.value = ""; 
        if(image) image.src = "";   
        if(container) container.classList.add('hidden');
    }
</script>
@endsection