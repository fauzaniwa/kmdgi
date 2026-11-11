@extends('layouts.app')

@section('title', 'Upload Booth Delegasi - KMDGI 16')

@php
    $daftarVotesDelegasi = isset($initialVotes)
        ? $initialVotes
        : ($booth ? $booth->votes()->select(['id', 'booth_delegasi_id', 'user_id', 'created_at'])->with(['user:id,name,institusi,kategori'])->latest()->take(25)->get() : collect());
    $totalVotesDelegasi = (int) ($booth->votes_count ?? 0);
    $hasMoreDelegasiVotes = $totalVotesDelegasi > $daftarVotesDelegasi->count();
@endphp

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <!-- Layout 2 Kolom dengan Sidebar seperti halaman Delegasi lainnya -->
    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8 font-sans">

        <!-- Memanggil Sidebar Delegasi / User -->
        @if(View::exists('partials.sidebar-delegasi'))
        @include('partials.sidebar-delegasi')
        @elseif(View::exists('partials.sidebar'))
        @include('partials.sidebar')
        @endif

        <main class="flex-grow space-y-6 w-full">

            <!-- Flash Message Sukses -->
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 flex-shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            @endif

            <!-- Error Validasi -->
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <span class="text-sm font-black">Mohon Periksa Kembali:</span>
                </div>
                <ul class="text-xs list-disc list-inside pl-9 space-y-1 font-medium">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Header & Status Voting Card -->
            <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2.5">
                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-blue-50 text-[#1A68FF] border border-blue-100">
                            Most Popular Artwork — {{ $edisiAktif->nama_edisi ?? 'KMDGI 16' }}
                        </span>

                        @if($booth)

                        @if($booth->status_draft)
                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                            Draft Tersimpan
                        </span>
                        @elseif($booth->status_verifikasi === 'Terverifikasi')
                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-200">
                            Terverifikasi (Tampil di Publik)
                        </span>
                        @elseif($booth->status_verifikasi === 'Ditolak')
                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-red-50 text-red-600 border border-red-200">
                            Perlu Revisi / Ditolak
                        </span>
                        @else
                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-amber-50 text-amber-600 border border-amber-200">
                            Menunggu Kurasi Admin
                        </span>
                        @endif

                        @endif
                    </div>

                    <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Upload Booth Delegasi</h1>
                    <p class="text-xs md:text-sm text-slate-500 mt-1">
                        Unggah dokumentasi final booth kontingen Anda untuk dikurasi Admin dan diikutsertakan dalam voting Most Popular Artwork.
                    </p>
                </div>

                <!-- Kotak Perolehan Nilai Voting + SATU TOMBOL LIHAT NAMA PEMILIH -->
                <button type="button"
                        onclick="openDelegasiVotersModal()"
                        class="flex items-center gap-4 bg-gradient-to-br from-blue-50 to-indigo-50/60 hover:from-blue-100/80 hover:to-indigo-100/60 border border-blue-200 px-6 py-4 rounded-2xl flex-shrink-0 text-left transition-all cursor-pointer group">
                    <div class="w-12 h-12 rounded-xl bg-[#1A68FF] text-white flex items-center justify-center shadow-md shadow-blue-500/20">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Perolehan Voting</span>
                        <span class="text-2xl font-black text-slate-900">{{ number_format($totalVotesDelegasi) }} <span class="text-xs font-bold text-[#1A68FF]">Suara</span></span>
                        <span class="block text-[11px] font-extrabold text-[#1A68FF] underline mt-0.5">👥 Lihat Pemilih</span>
                    </div>
                </button>
            </div>

            <!-- Informasi Jadwal Buka & Batas Akhir Voting -->
            @if(isset($jadwal))
            <div class="p-4 px-5 rounded-2xl border {{ $jadwal['isOpen'] ? 'bg-blue-50/60 border-blue-200 text-slate-700' : 'bg-amber-50 border-amber-200 text-amber-800' }} flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 {{ $jadwal['isOpen'] ? 'text-[#1A68FF]' : 'text-amber-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <div>
                        <span class="font-bold">Periode Upload & Voting:</span>
                        {{ $jadwal['waktuBuka']->translatedFormat('d M Y, H:i') }} WIB — {{ $jadwal['waktuTutup']->translatedFormat('d M Y, H:i') }} WIB
                    </div>
                </div>
                <span class="px-3 py-1 rounded-lg font-extrabold uppercase tracking-wider text-[10px] w-fit {{ $jadwal['isOpen'] ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-200/70 text-amber-800' }}">
                    {{ $jadwal['isOpen'] ? 'Sesi Dibuka' : 'Sesi Terkunci / Di Luar Jadwal' }}
                </span>
            </div>
            @endif

            @if(!empty($isLocked) && $isLocked)
            <div class="bg-emerald-50 border-2 border-emerald-200 p-5 rounded-2xl flex items-start gap-4 text-emerald-800 shadow-sm">
                <svg class="w-6 h-6 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider">Karya Telah Terkurasi — Mode Edit Dikunci</h3>
                    <p class="text-xs mt-1 leading-relaxed">
                        Dokumentasi Booth Delegasi Anda telah <strong>Terverifikasi</strong> oleh Admin dan siap ditampilkan secara serentak pada halaman voting Most Popular Artwork. Data sudah dikunci dan tidak dapat diubah lagi.
                    </p>
                </div>
            </div>
            @elseif($booth && !$booth->status_draft && $booth->status_verifikasi === 'Menunggu')
            <div class="bg-amber-50 border border-amber-200 p-4 rounded-2xl flex items-center gap-3 text-amber-800 text-xs font-semibold">
                <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                <span>Karya Anda sedang dalam tahap <strong>Menunggu Verifikasi</strong>. Anda masih dapat melakukan perubahan data sebelum dikurasi oleh Admin.</span>
            </div>
            @endif

            <!-- ================================================================= -->
            <!-- PANEL CETAK QR CODE BOOTH & UNDUH TEMPLATE QR A4 (SISI DELEGASI)  -->
            <!-- ================================================================= -->
            @php
            $templateQrUrl = file_exists(public_path('images/template_qr.png'))
            ? asset('images/template_qr.png')
            : asset('template_qr.png');
            @endphp
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex flex-col md:flex-row md:items-center justify-between gap-5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-lime-100 text-lime-700 flex items-center justify-center flex-shrink-0 border border-lime-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-black text-slate-900">Poster QR Code Voting Booth (Kertas A4)</h2>
                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">
                            Cetak Poster QR Code otomatis yang sudah terisi judul karya dan nama kampus Anda, atau unduh file mentah <strong>Template QR A4</strong> untuk kebutuhan cetak mandiri di booth pameran.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 flex-shrink-0">
                    <!-- Tombol Unduh Template QR Kosong -->
                    <a href="{{ $templateQrUrl }}"
                        download="template_qr_kmdgi16.png"
                        class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs transition-colors">
                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Unduh Template QR
                    </a>

                    <!-- Tombol Print / Download QR Code Karya Otomatis -->
                    @if($booth)
                    <a href="{{ route('delegasi.booth.qr_poster') }}"
                        target="_blank"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#1A68FF] hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                        </svg>
                        Print / Unduh QR Karya (A4)
                    </a>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-4 py-3 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs cursor-not-allowed" title="Simpan draft atau unggah data booth terlebih dahulu">
                        🔒 Print QR Karya (Isi Form Dahulu)
                    </span>
                    @endif
                </div>
            </div>

            <!-- Form Utama -->
            <form action="{{ route('delegasi.booth.store') }}" method="POST" enctype="multipart/form-data" id="boothForm" class="space-y-6">
                @csrf
                <input type="hidden" name="status_draft" id="status_draft" value="0">
                <input type="hidden" name="remove_foto_utama" id="remove_foto_utama" value="0">
                <input type="hidden" name="remove_foto_1" id="remove_foto_1" value="0">
                <input type="hidden" name="remove_foto_2" id="remove_foto_2" value="0">

                <!-- BAGIAN 1: IDENTITAS KAMPUS, JUDUL KARYA TEMATIK & KREATOR / TIM -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] space-y-6">
                    <h2 class="text-lg font-black text-slate-900 border-b border-slate-100 pb-4">1. Identitas Kampus, Judul & Tim Kreator</h2>

                    <!-- Nama Perguruan Tinggi (Readonly dari User) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Perguruan Tinggi</label>
                        <div class="flex items-center gap-3.5 px-4 py-3.5 bg-slate-100/80 border border-slate-200 rounded-xl">
                            <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 p-1 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                @if($kampus && $kampus->logo_institusi)
                                <img src="{{ asset('storage/' . $kampus->logo_institusi) }}" alt="Logo" class="w-full h-full object-contain">
                                @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->institusi) }}&background=E1EDFF&color=126CFD" alt="Logo" class="w-full h-full object-contain rounded">
                                @endif
                            </div>
                            <div class="flex-grow">
                                <input type="text" value="{{ $user->institusi }}" readonly class="w-full bg-transparent font-bold text-slate-800 text-sm focus:outline-none cursor-not-allowed">
                                <span class="text-[11px] text-slate-400 block">Diambil otomatis dari profil institusi delegasi Anda</span>
                            </div>
                        </div>
                    </div>

                    <!-- Judul Karya Tematik (Maks 100 Karakter) -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="judul_karya_tematik" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Judul Karya Tematik <span class="text-red-500">*</span>
                            </label>
                            <span id="judul-counter" class="text-[11px] font-bold text-slate-400">0 / 100 Karakter</span>
                        </div>
                        <input type="text"
                            name="judul_karya_tematik"
                            id="judul_karya_tematik"
                            maxlength="100"
                            value="{{ old('judul_karya_tematik', $booth->judul_karya_tematik ?? '') }}"
                            placeholder="Masukkan judul karya tematik booth kontingen Anda..."
                            oninput="updateJudulCounter()"
                            class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:outline-none focus:border-[#1A68FF] focus:bg-white transition-all">
                    </div>

                    <!-- Kreator / Tim (Dipisah Koma) -->
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-2">
                            <label for="kreator_karya" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Kreator / Tim Pembuat <span class="text-red-500">*</span>
                            </label>
                            <span class="text-[11px] font-semibold text-slate-400">Pisahkan dengan tanda koma ( , ) untuk banyak nama</span>
                        </div>
                        <input type="text"
                            name="kreator_karya"
                            id="kreator_karya"
                            value="{{ old('kreator_karya', $booth->kreator_karya ?? '') }}"
                            placeholder="Contoh: Budi Santoso, Siti Aminah, Raka Pratama"
                            oninput="renderCreatorTags()"
                            class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:outline-none focus:border-[#1A68FF] focus:bg-white transition-all">

                        <!-- Preview Chips Nama Kreator -->
                        <div id="creator-tags-preview" class="flex flex-wrap gap-1.5 mt-2.5"></div>
                    </div>
                </div>

                <!-- BAGIAN 2: FOTO FULL BOOTH (3 PENGAMBILAN FOTO - JPG/PNG) -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] space-y-6">
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
                            <h2 class="text-lg font-black text-slate-900">2. Dokumentasi Foto Full Booth</h2>
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-[#1A68FF] bg-blue-50 px-3 py-1 rounded-lg w-fit">
                                Format Wajib: JPG / PNG (Maks. 5MB/Foto)
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-3 leading-relaxed">
                            Foto utama wajib menampilkan keseluruhan booth dalam kondisi final setelah dekorasi selesai dan siap dinilai. Wajib menampilkan <strong>3 (tiga) pengambilan foto</strong> berikut:
                        </p>
                    </div>

                    <!-- A. FOTO UTAMA - TAMPAK DEPAN -->
                    <div class="p-5 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                            <div>
                                <span class="inline-block text-[10px] font-black uppercase tracking-wider bg-[#1A68FF] text-white px-2.5 py-0.5 rounded-md mb-1">Foto A (Thumbnail Utama)</span>
                                <h3 class="text-sm font-extrabold text-slate-900">Foto Utama - Tampak Depan <span class="text-red-500">*</span></h3>
                            </div>
                            <ul class="text-[11px] text-slate-500 list-disc list-inside space-y-0.5">
                                <li>Foto keseluruhan booth dari tampak depan.</li>
                                <li>Booth harus terlihat utuh dan selesai dihias.</li>
                                <li>Menjadi thumbnail/cover utama di halaman karya web.</li>
                            </ul>
                        </div>

                        <div class="relative w-full aspect-[16/9] max-h-80 border-2 border-dashed border-slate-300 hover:border-[#1A68FF] rounded-2xl bg-white flex flex-col items-center justify-center overflow-hidden group cursor-pointer transition-colors"
                            onclick="document.getElementById('input_foto_utama').click()">

                            <div id="placeholder_utama" class="flex flex-col items-center text-center p-6 {{ ($booth && $booth->foto_utama) ? 'hidden' : '' }}">
                                <div class="w-12 h-12 rounded-full bg-blue-50 text-[#1A68FF] flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                    </svg>
                                </div>
                                <p class="text-xs font-bold text-slate-700">Klik untuk mengunggah Foto Utama (Tampak Depan)</p>
                                <p class="text-[11px] text-slate-400 mt-1">Hanya file .JPG atau .PNG</p>
                            </div>

                            <img id="preview_utama"
                                src="{{ ($booth && $booth->foto_utama) ? asset('storage/' . $booth->foto_utama) : '' }}"
                                class="{{ ($booth && $booth->foto_utama) ? '' : 'hidden' }} w-full h-full object-cover" />
                        </div>

                        <input type="file" name="foto_utama" id="input_foto_utama" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="hidden" onchange="previewImage(this, 'preview_utama', 'placeholder_utama', 'btn_remove_utama', 'remove_foto_utama')">

                        <div class="flex justify-end">
                            <button type="button" id="btn_remove_utama" onclick="removeImage('input_foto_utama', 'preview_utama', 'placeholder_utama', 'btn_remove_utama', 'remove_foto_utama')" class="{{ ($booth && $booth->foto_utama) ? '' : 'hidden' }} text-xs font-bold text-red-500 hover:text-red-700">
                                Hapus / Ganti Foto Utama
                            </button>
                        </div>
                    </div>

                    <!-- GRID FOTO PENDUKUNG 1 & 2 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- B. FOTO PENDUKUNG 1 -->
                        <div class="p-5 rounded-2xl bg-slate-50/70 border border-slate-200/80 flex flex-col justify-between space-y-4">
                            <div>
                                <span class="inline-block text-[10px] font-black uppercase tracking-wider bg-slate-800 text-white px-2.5 py-0.5 rounded-md mb-1">Foto B</span>
                                <h3 class="text-sm font-extrabold text-slate-900">Foto Pendukung 1 <span class="text-red-500">*</span></h3>
                                <ul class="text-[11px] text-slate-500 list-disc list-inside mt-2 space-y-1">
                                    <li>Sudut pengambilan berbeda dari foto utama.</li>
                                    <li>Bisa agak diagonal dari depan, close-up area tertentu, atau bagian booth yang tidak terlalu terlihat di foto utama.</li>
                                </ul>
                            </div>

                            <div>
                                <div class="relative w-full aspect-[4/3] border-2 border-dashed border-slate-300 hover:border-[#1A68FF] rounded-2xl bg-white flex flex-col items-center justify-center overflow-hidden group cursor-pointer transition-colors"
                                    onclick="document.getElementById('input_foto_1').click()">

                                    <div id="placeholder_1" class="flex flex-col items-center text-center p-4 {{ ($booth && $booth->foto_pendukung_1) ? 'hidden' : '' }}">
                                        <svg class="w-8 h-8 text-slate-400 group-hover:text-[#1A68FF] mb-2 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                        </svg>
                                        <p class="text-xs font-bold text-slate-700">Unggah Foto Pendukung 1</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">Format JPG / PNG</p>
                                    </div>

                                    <img id="preview_1"
                                        src="{{ ($booth && $booth->foto_pendukung_1) ? asset('storage/' . $booth->foto_pendukung_1) : '' }}"
                                        class="{{ ($booth && $booth->foto_pendukung_1) ? '' : 'hidden' }} w-full h-full object-cover" />
                                </div>

                                <input type="file" name="foto_pendukung_1" id="input_foto_1" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="hidden" onchange="previewImage(this, 'preview_1', 'placeholder_1', 'btn_remove_1', 'remove_foto_1')">

                                <div class="flex justify-end mt-2">
                                    <button type="button" id="btn_remove_1" onclick="removeImage('input_foto_1', 'preview_1', 'placeholder_1', 'btn_remove_1', 'remove_foto_1')" class="{{ ($booth && $booth->foto_pendukung_1) ? '' : 'hidden' }} text-xs font-bold text-red-500 hover:text-red-700">
                                        Hapus Foto
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- C. FOTO PENDUKUNG 2 -->
                        <div class="p-5 rounded-2xl bg-slate-50/70 border border-slate-200/80 flex flex-col justify-between space-y-4">
                            <div>
                                <span class="inline-block text-[10px] font-black uppercase tracking-wider bg-slate-800 text-white px-2.5 py-0.5 rounded-md mb-1">Foto C</span>
                                <h3 class="text-sm font-extrabold text-slate-900">Foto Pendukung 2 <span class="text-red-500">*</span></h3>
                                <ul class="text-[11px] text-slate-500 list-disc list-inside mt-2 space-y-1">
                                    <li>Menampilkan detail/elemen penting lainnya.</li>
                                    <li>Bisa berupa elemen interaktif, instalasi, artwork, atau bagian yang menjadi highlight konsep.</li>
                                </ul>
                            </div>

                            <div>
                                <div class="relative w-full aspect-[4/3] border-2 border-dashed border-slate-300 hover:border-[#1A68FF] rounded-2xl bg-white flex flex-col items-center justify-center overflow-hidden group cursor-pointer transition-colors"
                                    onclick="document.getElementById('input_foto_2').click()">

                                    <div id="placeholder_2" class="flex flex-col items-center text-center p-4 {{ ($booth && $booth->foto_pendukung_2) ? 'hidden' : '' }}">
                                        <svg class="w-8 h-8 text-slate-400 group-hover:text-[#1A68FF] mb-2 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                        </svg>
                                        <p class="text-xs font-bold text-slate-700">Unggah Foto Pendukung 2</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">Format JPG / PNG</p>
                                    </div>

                                    <img id="preview_2"
                                        src="{{ ($booth && $booth->foto_pendukung_2) ? asset('storage/' . $booth->foto_pendukung_2) : '' }}"
                                        class="{{ ($booth && $booth->foto_pendukung_2) ? '' : 'hidden' }} w-full h-full object-cover" />
                                </div>

                                <input type="file" name="foto_pendukung_2" id="input_foto_2" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="hidden" onchange="previewImage(this, 'preview_2', 'placeholder_2', 'btn_remove_2', 'remove_foto_2')">

                                <div class="flex justify-end mt-2">
                                    <button type="button" id="btn_remove_2" onclick="removeImage('input_foto_2', 'preview_2', 'placeholder_2', 'btn_remove_2', 'remove_foto_2')" class="{{ ($booth && $booth->foto_pendukung_2) ? '' : 'hidden' }} text-xs font-bold text-red-500 hover:text-red-700">
                                        Hapus Foto
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- BAGIAN 3: DESKRIPSI SINGKAT (MAKS 150 KATA) -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
                        <div>
                            <h2 class="text-lg font-black text-slate-900">3. Deskripsi Singkat Booth <span class="text-red-500">*</span></h2>
                            <p class="text-xs text-slate-500 mt-0.5">Berisikan konsep, keterkaitan dengan tema <strong>HAYUK</strong>, dan elemen utama instalasi.</p>
                        </div>
                        <span id="word-counter" class="text-xs font-extrabold px-3 py-1 rounded-lg bg-slate-100 text-slate-600 w-fit">
                            0 / 150 Kata
                        </span>
                    </div>

                    <textarea name="deskripsi_singkat"
                        id="deskripsi_singkat"
                        rows="6"
                        placeholder="Ceritakan konsep booth, keterkaitannya dengan tema besar HAYUK, serta elemen utama instalasi yang ditonjolkan..."
                        oninput="updateWordCounter()"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 leading-relaxed focus:outline-none focus:border-[#1A68FF] focus:bg-white transition-all resize-y">{{ old('deskripsi_singkat', $booth->deskripsi_singkat ?? '') }}</textarea>
                    <p id="word-error" class="hidden text-xs font-bold text-red-500">Jumlah kata melebihi batas maksimal 150 kata!</p>
                </div>

                <!-- TOMBOL AKSI: SIMPAN DRAFT & SUBMIT KE ANTREAN KURASI -->
                @if(empty($isLocked) || !$isLocked)
                <!-- TOMBOL AKSI: HANYA MUNCUL JIKA BELUM TERKURASI -->
                <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-2">
                    <button type="button"
                        onclick="submitForm(1)"
                        class="w-full sm:w-auto px-8 py-4 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm transition-colors">
                        Simpan sebagai Draft
                    </button>
                    <button type="button"
                        id="btn-submit-final"
                        onclick="submitForm(0)"
                        class="w-full sm:w-auto px-10 py-4 rounded-xl bg-[#1A68FF] hover:bg-blue-700 text-white font-bold text-sm shadow-lg shadow-blue-500/20 transition-all">
                        {{ $booth && !$booth->status_draft ? 'Simpan Perubahan Data' : 'Unggah & Kirim ke Kurasi Admin' }}
                    </button>
                </div>
                @endif
            </form>

        </main>
    </div>

    @include('partials.footer')
</div>

<!-- ========================================================================= -->
<!-- MODAL DAFTAR PEMILIH BOOTH SISI DELEGASI (DEFAULT 25 DATA + LOAD MORE)    -->
<!-- ========================================================================= -->
<div id="delegasiVotersModal" class="fixed inset-0 z-[220] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[2rem] p-6 md:p-8 max-w-lg w-full max-h-[85vh] flex flex-col shadow-2xl space-y-4">
        <div class="flex items-start justify-between border-b border-slate-100 pb-4">
            <div>
                <span class="text-[10px] font-black uppercase tracking-wider text-[#1A68FF] bg-blue-50 px-2.5 py-1 rounded-full">
                    Pendukung Booth Kontingen Anda
                </span>
                <h3 class="text-lg font-black text-slate-900 mt-1">
                    Daftar Pemilih ({{ number_format($totalVotesDelegasi) }} Suara)
                </h3>
                <p class="text-xs text-slate-500">{{ $user->institusi }}</p>
            </div>
            <button type="button" onclick="closeDelegasiVotersModal()" class="p-2 rounded-xl bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div id="delegasi-voters-container" class="overflow-y-auto divide-y divide-slate-100 pr-1 flex-grow">
            @if($daftarVotesDelegasi->count() > 0)
                @foreach($daftarVotesDelegasi as $vote)
                    @php
                        $voterName = $vote->user->name ?? 'Pengunjung KMDGI';
                        $voterInstitusi = $vote->user->institusi ?? ($vote->user->kategori ?? 'Umum');
                        $words = preg_split('/\s+/', trim($voterName), -1, PREG_SPLIT_NO_EMPTY);
                        $initials = strtoupper(substr($words[0] ?? 'P', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                    @endphp
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-full bg-blue-50 text-[#1A68FF] border border-blue-100 flex items-center justify-center font-black text-xs flex-shrink-0 select-none">
                                {{ $initials }}
                            </div>
                            <div class="min-w-0">
                                <span class="text-xs font-bold text-slate-900 block truncate">{{ $voterName }}</span>
                                <span class="text-[11px] text-slate-400 block truncate">{{ $voterInstitusi }}</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 flex-shrink-0">
                            {{ $vote->created_at ? $vote->created_at->format('d M Y, H:i') : '' }}
                        </span>
                    </div>
                @endforeach
            @else
                <div class="py-10 text-center text-xs text-slate-400 font-semibold">
                    Belum ada suara yang masuk untuk booth kontingen Anda.
                </div>
            @endif
        </div>

        <!-- Tombol Load More (Muncul jika suara > 25) -->
        <div id="delegasi-voters-load-more-wrap" class="{{ $hasMoreDelegasiVotes ? '' : 'hidden' }} pt-1">
            <button type="button"
                    id="btn-delegasi-load-more"
                    onclick="loadMoreDelegasiVoters()"
                    class="w-full py-2.5 px-4 rounded-xl bg-blue-50 hover:bg-[#1A68FF] text-[#1A68FF] hover:text-white border border-blue-200 font-extrabold text-xs transition-all cursor-pointer">
                Muat Lebih Banyak (25 Berikutnya)
            </button>
        </div>

        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
            <span>
                Menampilkan <strong id="delegasi-voters-loaded-count" class="text-slate-700">{{ $daftarVotesDelegasi->count() }}</strong> dari <strong class="text-slate-700">{{ number_format($totalVotesDelegasi) }}</strong> suara
            </span>
            <button type="button" onclick="closeDelegasiVotersModal()" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- LOADING PANEL OVERLAY SAAT KIRIM DATA      -->
<!-- ========================================== -->
<div id="loading-overlay" class="fixed inset-0 z-[250] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[2rem] p-8 max-w-sm w-full mx-4 shadow-2xl flex flex-col items-center text-center">
        <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center mb-4">
            <svg class="w-9 h-9 text-[#1A68FF] animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        <h3 id="loading-title" class="text-lg font-black text-slate-900 mb-1">Mengunggah Data Booth...</h3>
        <p class="text-xs text-slate-500 leading-relaxed">Mohon tunggu dan jangan tutup halaman ini, sistem sedang memproses foto dokumentasi kontingen Anda.</p>
    </div>
</div>

<script>
    let nextDelegasiVotersPage = 2;
    let isLoadingDelegasiVoters = false;
    let loadedDelegasiVotersCount = {{ $daftarVotesDelegasi->count() }};

    function escapeHtmlDelegasi(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function openDelegasiVotersModal() {
        const modal = document.getElementById('delegasiVotersModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeDelegasiVotersModal() {
        const modal = document.getElementById('delegasiVotersModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function loadMoreDelegasiVoters() {
        if (isLoadingDelegasiVoters) return;
        isLoadingDelegasiVoters = true;

        const btn = document.getElementById('btn-delegasi-load-more');
        const wrap = document.getElementById('delegasi-voters-load-more-wrap');
        const container = document.getElementById('delegasi-voters-container');
        const countEl = document.getElementById('delegasi-voters-loaded-count');

        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Memuat data...';
        }

        const url = new URL(window.location.href);
        url.searchParams.set('ajax_voters', '1');
        url.searchParams.set('page', String(nextDelegasiVotersPage));

        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(res => {
            const items = res.data || [];
            if (items.length > 0 && container) {
                const fragment = document.createDocumentFragment();
                items.forEach(item => {
                    const row = document.createElement('div');
                    row.className = 'py-3 flex items-center justify-between gap-3';
                    row.innerHTML = `
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-full bg-blue-50 text-[#1A68FF] border border-blue-100 flex items-center justify-center font-black text-xs flex-shrink-0 select-none">
                                ${escapeHtmlDelegasi(item.initials || 'P')}
                            </div>
                            <div class="min-w-0">
                                <span class="text-xs font-bold text-slate-900 block truncate">${escapeHtmlDelegasi(item.name)}</span>
                                <span class="text-[11px] text-slate-400 block truncate">${escapeHtmlDelegasi(item.institusi)}</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 flex-shrink-0">
                            ${escapeHtmlDelegasi(item.waktu)}
                        </span>
                    `;
                    fragment.appendChild(row);
                });
                container.appendChild(fragment);

                loadedDelegasiVotersCount += items.length;
                if (countEl) countEl.innerText = loadedDelegasiVotersCount;
            }

            if (res.has_more) {
                nextDelegasiVotersPage = res.next_page || (nextDelegasiVotersPage + 1);
                if (btn) {
                    btn.disabled = false;
                    btn.innerText = 'Muat Lebih Banyak (25 Berikutnya)';
                }
            } else if (wrap) {
                wrap.classList.add('hidden');
            }
        })
        .catch(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerText = 'Muat Lebih Banyak (25 Berikutnya)';
            }
        })
        .finally(() => {
            isLoadingDelegasiVoters = false;
        });
    }

    // 1. Counter Karakter Judul (Maks 100 Karakter)
    function updateJudulCounter() {
        const input = document.getElementById('judul_karya_tematik');
        const counter = document.getElementById('judul-counter');
        const len = input.value.length;
        counter.innerText = `${len} / 100 Karakter`;
        counter.className = len >= 100 ?
            'text-[11px] font-bold text-amber-600' :
            'text-[11px] font-bold text-slate-400';
    }

    // 2. Render Preview Tags Kreator (Dipisah Koma)
    function renderCreatorTags() {
        const input = document.getElementById('kreator_karya');
        const container = document.getElementById('creator-tags-preview');
        const names = input.value.split(',').map(s => s.trim()).filter(Boolean);

        container.innerHTML = '';
        names.forEach(name => {
            const badge = document.createElement('span');
            badge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-blue-50 text-[#1A68FF] border border-blue-100';
            badge.innerText = name;
            container.appendChild(badge);
        });
    }

    // 3. Counter Kata Deskripsi Singkat (Maks 150 Kata)
    function countWords(str) {
        const trimmed = str.trim();
        if (!trimmed) return 0;
        return trimmed.split(/\s+/).length;
    }

    function updateWordCounter() {
        const textarea = document.getElementById('deskripsi_singkat');
        const counter = document.getElementById('word-counter');
        const errorMsg = document.getElementById('word-error');
        const btnSubmit = document.getElementById('btn-submit-final');

        const words = countWords(textarea.value);
        counter.innerText = `${words} / 150 Kata`;

        if (words > 150) {
            counter.className = 'text-xs font-extrabold px-3 py-1 rounded-lg bg-red-100 text-red-600 w-fit';
            errorMsg.classList.remove('hidden');
            btnSubmit.disabled = true;
            btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            counter.className = 'text-xs font-extrabold px-3 py-1 rounded-lg bg-slate-100 text-slate-600 w-fit';
            errorMsg.classList.add('hidden');
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    // 4. Validasi Format JPG/PNG & Live Preview
    function previewImage(input, previewId, placeholderId, removeBtnId, removeFlagId) {
        const file = input.files[0];
        if (!file) return;

        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            alert('Peringatan: Format foto wajib JPG atau PNG!');
            input.value = '';
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert('Peringatan: Ukuran foto maksimal 5MB!');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);
            const removeBtn = document.getElementById(removeBtnId);

            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
            removeBtn.classList.remove('hidden');
            document.getElementById(removeFlagId).value = '0';
        };
        reader.readAsDataURL(file);
    }

    function removeImage(inputId, previewId, placeholderId, removeBtnId, removeFlagId) {
        document.getElementById(inputId).value = '';
        const preview = document.getElementById(previewId);
        preview.src = '';
        preview.classList.add('hidden');
        document.getElementById(placeholderId).classList.remove('hidden');
        document.getElementById(removeBtnId).classList.add('hidden');
        document.getElementById(removeFlagId).value = '1';
    }

    // 5. Submit Handler + Tampilkan Loading Panel
    function submitForm(isDraft) {
        const words = countWords(document.getElementById('deskripsi_singkat').value);
        if (words > 150) {
            alert('Deskripsi singkat tidak boleh lebih dari 150 kata.');
            return;
        }

        document.getElementById('status_draft').value = isDraft;

        // Tampilkan Loading Overlay
        const overlay = document.getElementById('loading-overlay');
        const title = document.getElementById('loading-title');
        title.innerText = isDraft === 1 ? 'Menyimpan Draft Booth...' : 'Mengirim Data ke Kurasi Admin...';
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');

        document.getElementById('boothForm').submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateJudulCounter();
        renderCreatorTags();
        updateWordCounter();
    });

    @if(!empty($isLocked) && $isLocked)
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#boothForm input, #boothForm textarea, #boothForm button').forEach(el => {
            el.disabled = true;
            el.classList.add('cursor-not-allowed', 'opacity-75');
        });
    });
    @endif
</script>

@endsection
