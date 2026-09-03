@extends('layouts.app')
@section('title', 'Status Perlombaan - KMDGI 16')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen flex flex-col">
    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8 font-sans relative">
        @include('partials.sidebar')

        <main class="flex-grow w-full pb-20">
            <div class="max-w-4xl mx-auto">

                @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-bold border border-emerald-100 flex items-center justify-between shadow-sm">
                    {{ session('success') }}
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
                </div>
                @endif

                <div class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] mb-8 relative overflow-hidden">
                    <div class="absolute -right-16 -top-16 w-64 h-64 bg-gradient-to-br from-indigo-50 to-purple-50/30 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10">
                        <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight mb-2">Status Perlombaan</h1>
                        <p class="text-sm text-slate-500 max-w-xl">Pantau status pendaftaran, verifikasi pembayaran, dan kelengkapan submisi karya Anda di sini.</p>
                    </div>
                </div>

                @if($pendaftaran->isEmpty())
                <div class="bg-white border border-slate-200 rounded-[2rem] p-12 flex flex-col items-center justify-center text-center shadow-sm">
                    <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mb-4 border border-indigo-100">
                        <svg class="w-10 h-10 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Pendaftaran</h3>
                    <p class="text-sm text-slate-500 max-w-sm">Anda belum mendaftar perlombaan apapun. Kunjungi halaman Kompetisi untuk mendaftar.</p>
                    <a href="/#kompetisi" class="mt-6 bg-[#1A68FF] text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-blue-700 transition-colors shadow-md shadow-blue-500/20">Cari Lomba</a>
                </div>
                @else
                <div class="space-y-6">
                    @foreach($pendaftaran as $data)
                    <div class="bg-white rounded-[1.5rem] border border-slate-200 overflow-hidden shadow-sm flex flex-col md:flex-row">
                        <!-- Poster Preview -->
                        <div class="w-full md:w-48 aspect-[4/3] md:aspect-auto bg-slate-100 flex-shrink-0 relative">
                            @if($data->lomba && $data->lomba->poster)
                            <img src="{{ asset('storage/'.$data->lomba->poster) }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold text-xs uppercase">No Poster</div>
                            @endif
                        </div>

                        <!-- Data Body -->
                        <div class="p-6 md:p-8 flex-grow flex flex-col justify-between">
                            <div class="mb-5">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-1 rounded-md border border-slate-200 uppercase tracking-wider">{{ $data->kategori_pendaftar }}</span>
                                    <span class="text-xs text-slate-400 font-medium">{{ $data->created_at->translatedFormat('d M Y') }}</span>
                                </div>
                                <h3 class="text-xl font-black text-slate-900 leading-tight mb-1">
                                    {{ $data->lomba->judul ?? 'Lomba Dihapus' }}
                                </h3>
                                <p class="text-[13px] text-slate-500">Tim: <span class="font-bold text-slate-800">{{ $data->nama_tim_peserta }}</span> ({{ $data->institusi_asal }})</p>
                            </div>

                            <!-- Badges Status -->
                            <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-slate-100 mt-auto">

                                <!-- Status Pembayaran -->
                                <div class="flex-1">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Status Pembayaran</p>
                                    @if($data->status_pembayaran === 'Lunas')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Pembayaran Tervalidasi
                                    </span>
                                    @elseif($data->status_pembayaran === 'Gratis')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Lomba Gratis
                                    </span>
                                    @elseif($data->status_pembayaran === 'Menunggu Validasi')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu Validasi Panitia
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-red-50 text-red-600 border border-red-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Pembayaran Ditolak
                                    </span>
                                    @endif
                                </div>

                                <!-- Status Karya -->
                                <div class="flex-1">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Kelengkapan Karya</p>
                                    @if($data->status_karya === 'Terkirim')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-[#1A68FF]/10 text-[#1A68FF] border border-[#1A68FF]/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#1A68FF]"></span> Karya Telah Diunggah
                                    </span>
                                    @elseif($data->status_karya === 'Diskualifikasi')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-red-50 text-red-600 border border-red-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Didiskualifikasi
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Belum Mengunggah Karya
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Action Kanan -->
                        @if($data->lomba)
                        <div class="bg-slate-50 md:w-48 p-6 flex flex-col justify-center gap-3 border-l border-slate-100">
                            <a href="{{ route('kompetisi.show', $data->lomba->slug) }}" class="w-full text-center bg-white border border-slate-200 hover:border-[#1A68FF] text-slate-700 hover:text-[#1A68FF] font-bold text-xs py-3 px-4 rounded-xl transition-colors shadow-sm">
                                Detail Lomba
                            </a>

                            <!-- UBAH BAGIAN INI: Menjadi tombol Kelola Berkas / Unggah Karya -->
                            <a href="{{ route('peserta.lomba.edit', $data->id) }}" class="w-full text-center {{ $data->status_karya === 'Belum Mengumpulkan' ? 'bg-[#1A68FF] hover:bg-blue-700 text-white shadow-md shadow-blue-500/20' : 'bg-slate-800 hover:bg-slate-900 text-white shadow-md' }} font-bold text-xs py-3 px-4 rounded-xl transition-colors">
                                {{ $data->status_karya === 'Belum Mengumpulkan' ? 'Unggah Karya' : 'Kelola Berkas' }}
                            </a>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

            </div>
        </main>
    </div>
</div>
@include('partials.footer')
@endsection