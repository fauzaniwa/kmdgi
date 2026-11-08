@extends('layouts.app')

@section('title', 'Nominasi Most Popular Artwork (Tematik) - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col font-sans">
    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-grow space-y-8">

        <!-- Header -->
        <div class="bg-gradient-to-br from-[#126CFD] to-blue-800 text-white rounded-[2rem] p-8 md:p-12 text-center shadow-xl shadow-blue-500/15">
            <span class="inline-block px-4 py-1 rounded-full bg-lime-400 text-slate-950 text-xs font-black uppercase tracking-widest mb-3">
                TEMATIK — KMDGI 16
            </span>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase">MOST POPULAR ARTWORK</h1>
            <p class="text-sm md:text-base text-blue-100 mt-2 max-w-xl mx-auto">
                Pilih karya instalasi booth favoritmu dan berikan 1 suaramu sebelum {{ $status['waktuTutup']->translatedFormat('d F Y \p\u\k\u\l H:i') }} WIB!
            </p>
        </div>

        @if(!$status['isVotingPublished'])
            <div class="bg-white rounded-[2rem] border border-slate-100 p-16 text-center space-y-3">
                <h2 class="text-xl font-black text-slate-900">Karya Sedang Dalam Tahap Kurasi Panitia</h2>
                <p class="text-xs md:text-sm text-slate-500 max-w-md mx-auto">
                    Seluruh nominasi karya instalasi Tematik akan ditampilkan secara serentak setelah proses verifikasi selesai.
                </p>
            </div>
        @else
            <!-- Grid Daftar Booth -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($booths as $item)
                @php
                    $logo = $kampusLogos[$item->institusi] ?? null;
                    $slugKampus = \Illuminate\Support\Str::slug($item->institusi);
                @endphp
                <div class="group bg-white rounded-[1.75rem] border border-slate-200/80 p-3 flex flex-col hover:shadow-xl transition-all">
                    <a href="{{ route('voting.booth.show', $slugKampus) }}" class="relative block aspect-[4/3] rounded-2xl overflow-hidden bg-slate-100">
                        <img src="{{ asset('storage/' . $item->foto_utama) }}" alt="{{ $item->judul_karya_tematik }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-md px-3 py-1 rounded-xl shadow-sm flex items-center gap-1.5">
                            <span class="text-xs font-black text-[#126CFD]">{{ number_format($item->votes_count) }}</span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Suara</span>
                        </div>
                    </a>

                    <div class="p-3 pt-4 flex flex-col flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-5 h-5 rounded-full overflow-hidden border border-slate-200 bg-white flex-shrink-0">
                                <img src="{{ $logo ? asset('storage/' . $logo) : 'https://ui-avatars.com/api/?name='.urlencode($item->institusi).'&background=f8fafc&color=334155' }}" class="w-full h-full object-cover">
                            </div>
                            <span class="text-xs font-bold text-slate-700 line-clamp-1">{{ $item->institusi }}</span>
                        </div>

                        <a href="{{ route('voting.booth.show', $slugKampus) }}" class="block mb-4 flex-grow">
                            <h2 class="text-lg font-black text-slate-900 leading-snug line-clamp-2 group-hover:text-[#126CFD] transition-colors">
                                “{{ $item->judul_karya_tematik }}”
                            </h2>
                            <p class="text-xs text-slate-500 line-clamp-2 mt-1">{{ $item->deskripsi_singkat }}</p>
                        </a>

                        <a href="{{ route('voting.booth.show', $slugKampus) }}" class="w-full py-3 rounded-xl bg-[#F4F8FF] hover:bg-[#126CFD] text-[#126CFD] hover:text-white font-extrabold text-xs text-center transition-colors">
                            Lihat Booth & Berikan Suara →
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-16 text-center text-sm text-slate-400 font-bold">
                    Belum ada karya booth yang ditampilkan.
                </div>
                @endforelse
            </div>
        @endif

    </div>

    @include('partials.footer')
</div>
@endsection