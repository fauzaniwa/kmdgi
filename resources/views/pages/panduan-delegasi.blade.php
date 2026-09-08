@extends('layouts.app')
@section('title', 'Panduan Delegasi - KMDGI 16')

@section('content')

<!-- CSS Kustom untuk merender hasil HTML dari Text Box (Quill) agar tampil sempurna -->
<style>
    .quill-content h2 { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-top: 1.5rem; margin-bottom: 1rem; }
    .quill-content h3 { font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-top: 1.25rem; margin-bottom: 0.75rem; }
    .quill-content p { font-size: 0.95rem; font-weight: 500; color: #475569; line-height: 1.7; margin-bottom: 1rem; }
    .quill-content ul { list-style-type: disc; margin-left: 1.5rem; margin-bottom: 1rem; color: #475569; font-weight: 500;}
    .quill-content ol { list-style-type: decimal; margin-left: 1.5rem; margin-bottom: 1rem; color: #475569; font-weight: 500;}
    .quill-content li { margin-bottom: 0.5rem; line-height: 1.6; }
    .quill-content a { color: #1A68FF; text-decoration: underline; font-weight: 600; }
    .quill-content strong { font-weight: 800; color: #0f172a; }
</style>

<div class="bg-white min-h-screen flex flex-col font-sans">
    
    @include('partials.navbar')

    <!-- ========================================== -->
    <!-- HERO SECTION                               -->
    <!-- ========================================== -->
    <section class="relative w-full pt-32 pb-20 md:pt-40 md:pb-28 bg-slate-900 overflow-hidden flex items-center justify-center">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/bg-abstract-tube-desktop.png') }}" class="w-full h-full object-cover opacity-40 mix-blend-screen" alt="Abstract Header">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
        </div>
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <span class="text-[#FF6B9E] font-bold tracking-widest uppercase text-xs md:text-sm mb-3 block">Informasi & Regulasi</span>
            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight leading-tight mb-6">
                {{ $panduan->hero_title ?? 'Panduan Delegasi KMDGI' }}
            </h1>
            <p class="text-slate-300 text-sm md:text-lg font-medium leading-relaxed max-w-2xl mx-auto">
                {{ $panduan->hero_subtitle ?? 'Pahami tata cara pendaftaran, syarat keanggotaan, serta petunjuk teknis pengumpulan karya pameran untuk delegasi kampus.' }}
            </p>
        </div>
    </section>

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24 space-y-20">

        <!-- ========================================== -->
        <!-- 1. KETENTUAN UMUM & ALUR DELEGASI TIM     -->
        <!-- ========================================== -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Kolom Kiri: Alur & Syarat Delegasi dari Admin (Dinamis) -->
            <div class="lg:col-span-2 space-y-8">
                
                <div>
                    <span class="text-[11px] font-bold text-[#1A68FF] uppercase tracking-widest block mb-1">Ketentuan Pokok</span>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight mb-4">
                        {{ $panduan->alur_title ?? 'Struktur & Ketentuan Tim Delegasi' }}
                    </h2>
                    <p class="text-slate-600 text-sm md:text-base leading-relaxed font-medium">
                        {{ $panduan->alur_deskripsi ?? 'Setiap perguruan tinggi yang berpartisipasi pada ajang KMDGI mengirimkan satu kontingen resmi yang dikoordinasikan secara terpusat oleh Ketua Delegasi.' }}
                    </p>
                </div>

                <!-- 4 Poin Ketentuan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- POIN 1 -->
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1A68FF] flex items-center justify-center font-black mb-4">1</div>
                        <h3 class="font-bold text-slate-900 text-base mb-2">{{ $panduan->alur_1_title ?? 'Struktur Ketua & Anggota' }}</h3>
                        <p class="text-xs md:text-sm text-slate-600 leading-relaxed font-medium">
                            {{ $panduan->alur_1_desc ?? 'Ketua Delegasi memegang Auth Code tim dan berwenang memvalidasi profil, mengatur keikutsertaan anggota, serta mengonfirmasi submisi akhir perwakilan kampus.' }}
                        </p>
                    </div>

                    <!-- POIN 2 -->
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black mb-4">2</div>
                        <h3 class="font-bold text-slate-900 text-base mb-2">{{ $panduan->alur_2_title ?? 'Hak Akses Kategori Karya' }}</h3>
                        <p class="text-xs md:text-sm text-slate-600 leading-relaxed font-medium">
                            {{ $panduan->alur_2_desc ?? 'Kampus berstatus Anggota / Penuh berhak mengirimkan seluruh kategori (Tematik, Simbiotik, Simbolik). Status Peninjau 1 hanya dapat berpartisipasi pada Simbiotik & Simbolik.' }}
                        </p>
                    </div>

                    <!-- POIN 3 -->
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-black mb-4">3</div>
                        <h3 class="font-bold text-slate-900 text-base mb-2">{{ $panduan->alur_3_title ?? 'Sinkronisasi Satu Berkas' }}</h3>
                        <p class="text-xs md:text-sm text-slate-600 leading-relaxed font-medium">
                            {{ $panduan->alur_3_desc ?? 'Pengunggahan berkas administrasi dan karya tim menggunakan sistem kolaboratif. Seluruh anggota yang tergabung dengan kode otentikasi tim dapat melihat progres yang sama.' }}
                        </p>
                    </div>

                    <!-- POIN 4 -->
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-black mb-4">4</div>
                        <h3 class="font-bold text-slate-900 text-base mb-2">{{ $panduan->alur_4_title ?? 'Spesifikasi Media Karya' }}</h3>
                        <p class="text-xs md:text-sm text-slate-600 leading-relaxed font-medium">
                            {{ $panduan->alur_4_desc ?? 'Wajib melampirkan thumbnail representatif, tautan/file master (ZIP/PDF max 20MB), serta hingga 8 dokumentasi foto/video pendukung untuk materi kurasi dan katalog pameran.' }}
                        </p>
                    </div>
                </div>

                <!-- Konten Regulasi Detail (Text Box dari Admin) -->
                @if(isset($panduan) && $panduan->petunjuk_teknis)
                <div class="bg-slate-50 border border-slate-100 rounded-[2rem] p-8 md:p-10 shadow-sm quill-content">
                    {!! $panduan->petunjuk_teknis !!}
                </div>
                @endif

                <!-- Konten Regulasi Tambahan Pameran (Opsional) -->
                @if(isset($panduan) && !empty(strip_tags($panduan->petunjuk_pameran)))
                <div class="bg-white border-2 border-slate-100 rounded-[2rem] p-8 md:p-10 quill-content">
                    <h3 class="font-bold text-slate-900 text-xl mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                        Petunjuk Tambahan Pameran
                    </h3>
                    {!! $panduan->petunjuk_pameran !!}
                </div>
                @endif

            </div>

            <!-- Kolom Kanan: Timeline & Berkas Administrasi (1 Kolom) -->
            <div class="space-y-6 lg:sticky lg:top-28">
                
                <!-- Card Timeline Berkas -->
                <div class="bg-[#111111] text-white rounded-[2rem] p-6 md:p-8 shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#1A68FF] rounded-full blur-[70px] opacity-30 pointer-events-none"></div>

                    <h3 class="text-xl font-black mb-6 flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-[#FF6B9E]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        Batas Waktu Berkas
                    </h3>

                    <div class="space-y-5">
                        <div class="border-b border-white/10 pb-4">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Bukti Pembayaran Delegasi</span>
                            <p class="text-sm font-bold text-white mb-1">{{ $configBerkas['deadline_pembayaran']->locale('id')->translatedFormat('l, d F Y') }}</p>
                            <p class="text-[11px] text-slate-400">{{ $configBerkas['text_bukti_pembayaran'] }}</p>
                        </div>

                        <div class="border-b border-white/10 pb-4">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Formulir Pendaftaran Resmi</span>
                            <p class="text-sm font-bold text-white mb-1">{{ $configBerkas['deadline_formulir']->locale('id')->translatedFormat('l, d F Y') }}</p>
                            <p class="text-[11px] text-slate-400">{{ $configBerkas['text_formulir_pendaftaran'] }}</p>
                        </div>
                    </div>

                    @if($configBerkas['file_buku_panduan'])
                    <div class="mt-6 pt-2">
                        <a href="{{ asset('storage/' . $configBerkas['file_buku_panduan']) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white font-bold py-3 px-4 rounded-xl text-xs transition-colors border border-white/20">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                            Unduh Buku Panduan Resmi (.PDF)
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Card Akses Manajemen Kontingen -->
                <div class="bg-blue-50 border border-blue-100 rounded-[2rem] p-6 text-slate-800">
                    <h4 class="font-black text-slate-900 text-base mb-2">
                        {{ $panduan->akun_title ?? 'Sudah Punya Akun Delegasi?' }}
                    </h4>
                    <p class="text-xs text-slate-600 leading-relaxed mb-4 font-medium">
                        {{ $panduan->akun_desc ?? 'Masuk langsung ke panel kontingen untuk mengecek status verifikasi kampus, mengelola anggota tim, atau mengunggah formulir.' }}
                    </p>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('delegasi.status') }}" class="w-full text-center bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition-colors shadow-sm">
                            Cek Status Kampus
                        </a>
                        <a href="{{ route('delegasi.berkas') }}" class="w-full text-center bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold py-2.5 px-4 rounded-xl text-xs transition-colors">
                            Unggah Berkas Kontingen
                        </a>
                    </div>
                </div>

            </div>

        </section>

        <!-- ========================================== -->
        <!-- 2. PILIH SUBMISI KARYA PAMERAN (CTA CARDS) -->
        <!-- ========================================== -->
        <section class="border-t border-slate-100 pt-16">
            <div class="text-center mb-12 md:mb-16">
                <span class="text-[11px] font-bold text-[#1A68FF] uppercase tracking-widest block mb-2">Katalog Kategori</span>
                <h2 class="text-3xl md:text-[2.5rem] font-black text-slate-900 tracking-tight mb-4">Submisi Karya Pameran</h2>
                <p class="text-slate-500 max-w-2xl mx-auto text-sm md:text-base font-medium">
                    Pilih kategori karya di bawah ini untuk melihat juknis teknis spesifik dan diarahkan langsung ke formulir pengumpulan karya delegasi kampus Anda.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- 1. Card Karya Tematik -->
                <div class="bg-white border border-slate-200 rounded-[2rem] overflow-hidden group hover:shadow-2xl hover:border-[#1A68FF] transition-all duration-300 flex flex-col h-full">
                    <div class="aspect-[4/3] bg-slate-100 relative overflow-hidden">
                        @if(isset($karyaTematik) && $karyaTematik->thumbnail)
                            <img src="{{ asset('storage/'.$karyaTematik->thumbnail) }}" alt="Karya Tematik" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold text-sm bg-slate-100 border-b border-slate-200">Karya Tematik</div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                        <div class="absolute bottom-5 left-6 right-6">
                            <span class="bg-[#1A68FF] text-white text-[10px] font-black uppercase px-2.5 py-1 rounded-md tracking-wider mb-1.5 inline-block">Respon Tema</span>
                            <h3 class="text-2xl font-black text-white drop-shadow-md">Karya Tematik</h3>
                        </div>
                    </div>
                    <div class="p-6 md:p-8 flex flex-col flex-grow justify-between">
                        <div class="prose prose-sm text-slate-600 line-clamp-3 mb-6 font-medium text-xs md:text-sm">
                            @if(isset($karyaTematik) && $karyaTematik->deskripsi)
                                {!! strip_tags($karyaTematik->deskripsi) !!}
                            @else
                                Karya visual pameran yang merespons langsung gagasan dan permasalahan pada tema besar KMDGI.
                            @endif
                        </div>

                        <div class="border-t border-slate-100 pt-5 mt-auto">
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-4 font-semibold">
                                <span>Izin Peserta:</span>
                                <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">Anggota Penuh</span>
                            </div>
                            <a href="{{ route('delegasi.submisi.panduan', 'tematik') }}" class="w-full flex items-center justify-center gap-2 bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-3.5 px-6 rounded-xl transition-all shadow-md shadow-blue-500/20 group-hover:-translate-y-0.5 text-xs md:text-sm">
                                Petunjuk & Submisi Tematik
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 2. Card Karya Simbiotik -->
                <div class="bg-white border border-slate-200 rounded-[2rem] overflow-hidden group hover:shadow-2xl hover:border-[#1A68FF] transition-all duration-300 flex flex-col h-full">
                    <div class="aspect-[4/3] bg-slate-100 relative overflow-hidden">
                        @if(isset($karyaSimbiotik) && $karyaSimbiotik->thumbnail)
                            <img src="{{ asset('storage/'.$karyaSimbiotik->thumbnail) }}" alt="Karya Simbiotik" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold text-sm bg-slate-100 border-b border-slate-200">Karya Simbiotik</div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                        <div class="absolute bottom-5 left-6 right-6">
                            
                            <h3 class="text-2xl font-black text-white drop-shadow-md">Karya Simbiotik</h3>
                        </div>
                    </div>
                    <div class="p-6 md:p-8 flex flex-col flex-grow justify-between">
                        <div class="prose prose-sm text-slate-600 line-clamp-3 mb-6 font-medium text-xs md:text-sm">
                            @if(isset($karyaSimbiotik) && $karyaSimbiotik->deskripsi)
                                {!! strip_tags($karyaSimbiotik->deskripsi) !!}
                            @else
                                Karya kolaborasi antarinstitusi yang mengeksplorasi sinergi perspektif desain yang saling terhubung.
                            @endif
                        </div>

                        <div class="border-t border-slate-100 pt-5 mt-auto">
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-4 font-semibold">
                                <span>Izin Peserta:</span>
                                <span class="text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">Anggota & Peninjau 1</span>
                            </div>
                            <a href="{{ route('delegasi.submisi.panduan', 'simbiotik') }}" class="w-full flex items-center justify-center gap-2 bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-3.5 px-6 rounded-xl transition-all shadow-md shadow-blue-500/20 group-hover:-translate-y-0.5 text-xs md:text-sm">
                                Petunjuk & Submisi Simbiotik
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 3. Card Karya Simbolik -->
                <div class="bg-white border border-slate-200 rounded-[2rem] overflow-hidden group hover:shadow-2xl hover:border-[#1A68FF] transition-all duration-300 flex flex-col h-full">
                    <div class="aspect-[4/3] bg-slate-100 relative overflow-hidden">
                        @if(isset($karyaSimbolik) && $karyaSimbolik->thumbnail)
                            <img src="{{ asset('storage/'.$karyaSimbolik->thumbnail) }}" alt="Karya Simbolik" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold text-sm bg-slate-100 border-b border-slate-200">Karya Simbolik</div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                        <div class="absolute bottom-5 left-6 right-6">
                        
                            <h3 class="text-2xl font-black text-white drop-shadow-md">Karya Simbolik</h3>
                        </div>
                    </div>
                    <div class="p-6 md:p-8 flex flex-col flex-grow justify-between">
                        <div class="prose prose-sm text-slate-600 line-clamp-3 mb-6 font-medium text-xs md:text-sm">
                            @if(isset($karyaSimbolik) && $karyaSimbolik->deskripsi)
                                {!! strip_tags($karyaSimbolik->deskripsi) !!}
                            @else
                                Karya instalasi atau identitas visual khas dari masing-masing kampus delegasi.
                            @endif
                        </div>

                        <div class="border-t border-slate-100 pt-5 mt-auto">
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-4 font-semibold">
                                <span>Izin Peserta:</span>
                                <span class="text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">Anggota & Peninjau 1</span>
                            </div>
                            <a href="{{ route('delegasi.submisi.panduan', 'simbolik') }}" class="w-full flex items-center justify-center gap-2 bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-3.5 px-6 rounded-xl transition-all shadow-md shadow-blue-500/20 group-hover:-translate-y-0.5 text-xs md:text-sm">
                                Petunjuk & Submisi Simbolik
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </div>

    @include('partials.footer')
</div>
@endsection