@extends('layouts.app')

@section('title', $karya->judul_karya . ' - KMDGI 16')

@section('meta')
    <meta property="og:title" content="{{ $karya->judul_karya }} - KMDGI 16">
    <meta property="og:description" content="{{ Str::limit(strip_tags($karya->deskripsi_karya), 150) }}">
    <meta property="og:image" content="{{ asset('storage/' . $karya->thumbnail_karya) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $karya->judul_karya }} - KMDGI 16">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($karya->deskripsi_karya), 150) }}">
    <meta name="twitter:image" content="{{ asset('storage/' . $karya->thumbnail_karya) }}">
@endsection

@section('content')
<div class="bg-white min-h-screen">
    @include('partials.navbar')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-sans">
        
        <nav class="text-xs font-bold text-slate-500 mb-8 flex gap-2">
            <a href="/" class="hover:text-blue-600">Beranda</a> /
            <a href="{{ route('katalog.karya.index') }}" class="hover:text-blue-600">Galeri Karya</a> /
            <span class="text-slate-900">Detail Karya</span>
        </nav>

        <div class="flex flex-col lg:flex-row gap-12 border-b border-slate-100 pb-16">
            
            <!-- KOLOM KIRI: IMAGE GALLERY -->
            <div class="w-full lg:w-7/12 space-y-4">
                
                @php
                    $mediaTambahan = is_string($karya->media_karya) ? json_decode($karya->media_karya, true) : ($karya->media_karya ?? []);
                    $semuaMedia = array_merge([$karya->thumbnail_karya], is_array($mediaTambahan) ? array_filter($mediaTambahan) : []);
                @endphp

                <!-- Gambar Utama Dinamis (Support Image & Video) -->
                <div id="main-media-container" class="relative w-full aspect-[4/3] rounded-3xl overflow-hidden bg-slate-50 border border-slate-200 cursor-zoom-in group" onclick="openLightbox(currentMediaIndex)">
                    <!-- Content diinjeksi via JavaScript -->
                </div>

                @if(count($semuaMedia) > 1)
                <div class="flex gap-3 overflow-x-auto pb-2 custom-scrollbar">
                    @foreach($semuaMedia as $index => $media)
                        <button onclick="changeMainMedia({{ $index }})" class="relative flex-shrink-0 w-24 h-24 rounded-xl overflow-hidden border-2 border-transparent focus:border-blue-500 transition-all hover:opacity-80 bg-slate-100 group">
                            @if(in_array(strtolower(pathinfo($media, PATHINFO_EXTENSION)), ['mp4', 'webm', 'mov']))
                                <video src="{{ asset('storage/' . $media) }}" class="w-full h-full object-cover"></video>
                                <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            @else
                                <img src="{{ asset('storage/' . $media) }}" class="w-full h-full object-cover">
                            @endif
                        </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- KOLOM KANAN: INFORMASI KARYA -->
            <div class="w-full lg:w-5/12 flex flex-col">
                
                <div class="flex items-start justify-between mb-4">
                    <span class="border border-blue-200 text-blue-600 bg-blue-50 px-4 py-1.5 rounded-full text-xs font-bold capitalize">
                        {{ $karya->kategori_karya }}
                    </span>
                    <button onclick="openShareModal({{ $karya->id }}, '{{ addslashes($karya->judul_karya) }}', '{{ url()->current() }}')" class="p-2 text-slate-400 hover:text-slate-800 transition-colors rounded-full hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" /></svg>
                    </button>
                </div>

                <div class="flex items-start justify-between gap-6 mb-8">
                    <h1 class="text-2xl md:text-[28px] font-black text-slate-900 leading-tight tracking-tight">{{ $karya->judul_karya }}</h1>
                    
                    @php 
                        $isLiked = Auth::check() ? $karya->likes()->where('user_id', Auth::id())->exists() : false; 
                    @endphp
                    <div class="flex-shrink-0 flex flex-col items-center justify-center bg-blue-50/80 rounded-2xl w-[60px] h-[68px] cursor-pointer hover:bg-blue-100 transition-colors group" onclick="likeKaryaDetail({{ $karya->id }}, this, {{ Auth::check() ? 'true' : 'false' }})">
                        <svg class="w-6 h-6 {{ $isLiked ? 'text-blue-500 fill-current' : 'text-blue-500 group-hover:fill-current' }} mb-1 transition-colors" fill="{{ $isLiked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                        <span class="text-xs font-black text-blue-600" id="detail-like-count">{{ $karya->likes_count ?? $karya->likes ?? 0 }}</span>
                    </div>
                </div>

                <!-- Info Identitas Kampus -->
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 rounded-full shadow-sm flex items-center justify-center text-slate-500 overflow-hidden bg-slate-100 border border-slate-200">
                        @if($logoKampus)
                            <img src="{{ asset('storage/' . $logoKampus) }}" class="w-full h-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($karya->user->institusi ?? 'KMDGI') }}&background=f1f5f9" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <p class="font-black text-slate-900 text-sm">{{ $karya->user->institusi ?? 'Institusi Umum' }}</p>
                        <p class="text-xs text-slate-500 font-medium">Delegasi KMDGI 16</p>
                    </div>
                </div>

                <!-- Kreator List Bertingkat -->
                <div class="mb-8">
                    <h3 class="text-xs font-black text-slate-900 mb-3 uppercase tracking-wider">Kreator</h3>
                    <div class="text-sm text-slate-600 leading-relaxed font-medium space-y-1">
                        @php $kreators = explode(',', $karya->kreator_karya); @endphp
                        @foreach(array_map('trim', $kreators) as $kreator)
                            <p>{{ $kreator }}</p>
                        @endforeach
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="border-t border-slate-100 pt-8">
                    <h3 class="text-xs font-black text-slate-900 mb-4 uppercase tracking-wider">Deskripsi</h3>
                    <div class="text-[13px] text-slate-600 leading-loose text-justify">
                        {!! nl2br(e($karya->deskripsi_karya)) !!}
                    </div>
                </div>

                <div class="mt-8 flex gap-4 text-xs font-medium text-slate-400">
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg> <span id="views-count-label">{{ $karya->views_count ?? 0 }}</span> Dilihat</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" /></svg> <span id="shares-count-label">{{ $karya->shares_count ?? 0 }}</span> Dibagikan</span>
                </div>
            </div>
        </div>

        <!-- SECTION KOMENTAR -->
        <div class="mt-12 max-w-4xl mx-auto" id="komentar">
            <h2 class="text-xl font-bold text-slate-900 mb-6">Komentar ({{ $komentars->count() ?? 0 }})</h2>
            
            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-bold border border-emerald-100">
                {{ session('success') }}
            </div>
            @endif
            @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl text-sm font-bold border border-red-100">
                {{ $errors->first() }}
            </div>
            @endif

            @auth
            <form action="{{ route('delegasi.submisi.komentar.store') }}" method="POST" class="mb-12">
                @csrf
                <input type="hidden" name="submisi_karya_id" value="{{ $karya->id }}">
                <div class="relative">
                    <input type="text" name="isi_komentar" placeholder="Tambahkan Komentar..." class="w-full px-5 py-4 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#1A68FF] transition-colors pr-28 shadow-sm" required>
                    <button type="submit" class="absolute right-2 top-2 bg-[#1A68FF] hover:bg-blue-700 text-white text-xs font-bold px-5 py-2.5 rounded-lg transition-colors">
                        Komentar
                    </button>
                </div>
            </form>
            @else
            <div class="mb-12 p-6 bg-slate-50 border border-slate-100 rounded-2xl text-center">
                <p class="text-sm text-slate-500">Silakan <a href="{{ route('login') }}" class="text-[#1A68FF] font-bold hover:underline">Login</a> terlebih dahulu untuk meninggalkan komentar.</p>
            </div>
            @endauth

            <div class="space-y-8">
                @foreach($komentars as $komentar)
                <div class="flex gap-4">
                    <div class="w-10 h-10 bg-slate-200 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-slate-600 text-sm overflow-hidden border border-white shadow-sm">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($komentar->user->name ?? 'U') }}&background=random" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-baseline justify-between gap-3 mb-1">
                            <div class="flex items-baseline gap-3">
                                <h4 class="font-bold text-slate-900 text-[13px]">{{ $komentar->user->name ?? 'Anonim' }}</h4>
                                <span class="text-[10px] font-medium text-slate-400">{{ $komentar->created_at->translatedFormat('d M Y H:i') }}</span>
                            </div>
                            
                            <!-- Action Kanan Komentar: Laporkan ATAU Hapus (Jika Milik Sendiri) -->
                            @auth
                                <div class="flex items-center gap-2">
                                    @if(Auth::id() === $komentar->user_id)
                                        <button onclick="openDeleteConfirmModal({{ $komentar->id }})" class="text-slate-300 hover:text-red-500 transition-colors" title="Hapus Komentar Anda">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    @else
                                        <button onclick="openReportModal({{ $komentar->id }})" class="text-slate-300 hover:text-red-500 transition-colors" title="Laporkan Komentar">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5" /></svg>
                                        </button>
                                    @endif
                                </div>
                            @endauth
                        </div>
                        <p class="text-[13px] text-slate-700 leading-relaxed mb-2">{{ $komentar->isi_komentar }}</p>
                        
                        <!-- Tombol Balas -->
                        <button onclick="document.getElementById('reply-form-{{$komentar->id}}').classList.toggle('hidden')" class="text-[11px] font-bold text-slate-400 hover:text-blue-500 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg> Reply
                        </button>

                        <!-- Form Reply Nested -->
                        @auth
                        <form id="reply-form-{{$komentar->id}}" action="{{ route('delegasi.submisi.komentar.store') }}" method="POST" class="mt-3 hidden relative">
                            @csrf
                            <input type="hidden" name="submisi_karya_id" value="{{ $karya->id }}">
                            <input type="hidden" name="parent_id" value="{{ $komentar->id }}">
                            <input type="text" name="isi_komentar" placeholder="Balas {{ $komentar->user->name ?? 'Anonim' }}..." class="w-full px-4 py-2 border border-slate-200 rounded-lg text-[13px] focus:outline-none focus:border-[#1A68FF] transition-colors pr-20" required>
                            <button type="submit" class="absolute right-1.5 top-1.5 bg-slate-800 text-white text-[10px] font-bold px-3 py-1.5 rounded transition-colors hover:bg-black">Kirim</button>
                        </form>
                        @endauth

                        <!-- NESTED REPLIES -->
                        @if($komentar->replies->count() > 0)
                        <div class="mt-4 space-y-4 border-l-2 border-slate-100 pl-4">
                            @foreach($komentar->replies as $reply)
                            <div class="flex gap-3">
                                <div class="w-8 h-8 bg-slate-200 rounded-full flex-shrink-0 overflow-hidden border border-white shadow-sm">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name ?? 'U') }}&background=random" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-baseline justify-between gap-2 mb-0.5">
                                        <div class="flex items-baseline gap-2">
                                            <h4 class="font-bold text-slate-900 text-xs">{{ $reply->user->name ?? 'Anonim' }}</h4>
                                            <span class="text-[9px] text-slate-400">{{ $reply->created_at->translatedFormat('d M Y H:i') }}</span>
                                        </div>
                                        
                                        <!-- Action Kanan Reply: Laporkan ATAU Hapus -->
                                        @auth
                                            <div class="flex items-center gap-2">
                                                @if(Auth::id() === $reply->user_id)
                                                    <button onclick="openDeleteConfirmModal({{ $reply->id }})" class="text-slate-300 hover:text-red-500 transition-colors" title="Hapus Balasan Anda">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                    </button>
                                                @else
                                                    <button onclick="openReportModal({{ $reply->id }})" class="text-slate-300 hover:text-red-500 transition-colors" title="Laporkan Balasan">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5" /></svg>
                                                    </button>
                                                @endif
                                            </div>
                                        @endauth
                                    </div>
                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $reply->isi_komentar }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- KARYA LAINNYA -->
        @if($karyaLainnya->count() > 0)
        <div class="mt-24 pt-10 border-t border-slate-100">
            <h2 class="text-2xl font-black text-slate-900 mb-8 text-center">Lihat Karya Mahasiswa Lainnya!</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($karyaLainnya as $item)
                @php $slugLain = \Illuminate\Support\Str::slug($item->judul_karya); @endphp
                <div class="group flex flex-col border border-slate-100 rounded-[1.5rem] bg-white hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300">
                    <a href="{{ route('katalog.karya.show', $slugLain) }}" class="relative block aspect-[4/3] rounded-t-[1.5rem] overflow-hidden bg-slate-100 flex-shrink-0">
                        <img src="{{ asset('storage/' . $item->thumbnail_karya) }}" alt="{{ $item->judul_karya }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-md px-2.5 py-1 rounded-md shadow-sm">
                            <span class="text-[10px] font-bold text-blue-600 capitalize">{{ $item->kategori_karya }}</span>
                        </div>
                    </a>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <span class="flex items-center gap-1 text-slate-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg> <span class="text-[11px] font-bold">{{ $item->likes_count ?? $item->likes ?? 0 }}</span></span>
                                <span class="flex items-center gap-1 text-slate-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.069C10.22 20.187 11.1 20.25 12 20.25Z" /></svg> <span class="text-[11px] font-bold">{{ $item->komentars_count ?? 0 }}</span></span>
                            </div>
                        </div>
                        <a href="{{ route('katalog.karya.show', $slugLain) }}" class="block flex-grow">
                            <h2 class="text-[15px] font-bold text-slate-900 leading-tight mb-1 line-clamp-2 hover:underline">{{ $item->judul_karya }}</h2>
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-3">{{ $item->kreator_karya }}</p>
                        </a>
                        <div class="flex items-center gap-2 pt-3 border-t border-slate-50 mt-auto">
                            @php 
                                $logoLain = $kampusLogosLain[$item->user->institusi] ?? null;
                            @endphp
                            <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden bg-white border border-slate-200">
                                <img src="{{ $logoLain ? asset('storage/' . $logoLain) : 'https://ui-avatars.com/api/?name='.urlencode($item->user->institusi ?? 'U').'&background=f1f5f9' }}" class="w-full h-full object-cover">
                            </div>
                            <span class="text-[10px] font-bold text-slate-800 line-clamp-1">{{ $item->user->institusi ?? 'Umum' }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

<!-- Modal Lightbox Galeri Ber-Navigasi -->
<div id="lightbox" class="fixed inset-0 z-[200] bg-black/95 hidden items-center justify-center p-4 backdrop-blur-md transition-opacity opacity-0" onclick="closeLightbox()">
    <button class="absolute left-4 md:left-10 text-white/50 hover:text-white transition-colors bg-black/50 hover:bg-black/80 rounded-full p-2 focus:outline-none" onclick="prevMedia(event)">
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
    </button>
    <div id="lightbox-content-container" class="max-w-full max-h-full flex items-center justify-center"></div>
    <button class="absolute right-4 md:right-10 text-white/50 hover:text-white transition-colors bg-black/50 hover:bg-black/80 rounded-full p-2 focus:outline-none" onclick="nextMedia(event)">
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
    </button>
    <button class="absolute top-6 right-6 text-white/50 hover:text-red-500 bg-black/50 hover:bg-black/80 rounded-full p-2 transition-colors focus:outline-none" onclick="closeLightbox(event)">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
    </button>
</div>

<!-- MODAL SHARE CUSTOM -->
<div id="shareModal" class="fixed inset-0 z-[200] bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4 transition-opacity opacity-0 duration-300">
    <div class="absolute inset-0" onclick="closeShareModal()"></div>
    <div class="relative bg-white rounded-[2rem] p-6 md:p-8 max-w-sm w-full shadow-2xl transform scale-95 transition-transform duration-300" id="shareContent">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-black text-slate-900">Bagikan Karya</h3>
            <button type="button" onclick="closeShareModal()" class="text-slate-400 hover:text-red-500 focus:outline-none">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        
        <div class="grid grid-cols-4 gap-4 mb-6">
            <a href="#" id="share-wa" target="_blank" class="social-share-link flex flex-col items-center gap-2 group">
                <div class="w-12 h-12 rounded-full bg-[#25D366] text-white flex items-center justify-center shadow-md group-hover:-translate-y-1 transition-transform">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0C5.383 0 0 5.383 0 12.031c0 2.124.553 4.195 1.603 6.012L.47 24l6.115-1.586a11.968 11.968 0 005.446 1.31h.005C18.683 23.724 24 18.341 24 11.693 24 5.045 18.683 0 12.031 0zm0 21.724c-1.8 0-3.56-.484-5.111-1.401l-.367-.217-3.799.986.996-3.704-.238-.378A9.97 9.97 0 012.031 12.03c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10zm5.488-7.502c-.3-.15-1.782-.88-2.059-.98-.277-.1-.478-.15-.678.15-.2.3-.777.98-.952 1.18-.175.2-.35.225-.65.075-2.008-1.006-3.418-2.618-3.957-3.548-.175-.3-.018-.462.132-.612.135-.135.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525-.075-.15-.678-1.631-.928-2.235-.24-.59-.485-.51-.678-.52-.187-.01-.4-.01-.6-.01-.2 0-.525.075-.8.375-.275.3-.105.735.45 1.5 1.488 2.055 3.018 3.905 4.708 5.6 1.69 1.695 3.518 2.65 5.518 3.325.562.188 1.07.16 1.468.1.442-.067 1.353-.555 1.543-1.09.19-.535.19-.995.132-1.09-.058-.095-.208-.145-.508-.295z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-slate-600">WhatsApp</span>
            </a>
            <a href="#" id="share-fb" target="_blank" class="social-share-link flex flex-col items-center gap-2 group">
                <div class="w-12 h-12 rounded-full bg-[#1877F2] text-white flex items-center justify-center shadow-md group-hover:-translate-y-1 transition-transform">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.407.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.593 1.323-1.325V1.325C24 .593 23.407 0 22.675 0z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-slate-600">Facebook</span>
            </a>
            <a href="#" id="share-tw" target="_blank" class="social-share-link flex flex-col items-center gap-2 group">
                <div class="w-12 h-12 rounded-full bg-black text-white flex items-center justify-center shadow-md group-hover:-translate-y-1 transition-transform">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 22.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-slate-600">X / Twitter</span>
            </a>
            <button type="button" id="copy-link-btn" class="social-share-link flex flex-col items-center gap-2 group focus:outline-none">
                <div class="w-12 h-12 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center shadow-md group-hover:-translate-y-1 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                </div>
                <span class="text-[10px] font-bold text-slate-600">Salin Link</span>
            </button>
        </div>
        
        <div class="relative">
            <input type="text" id="share-url-input" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-xs text-slate-500 bg-slate-50 focus:outline-none pr-4" readonly>
        </div>
    </div>
</div>

<!-- FORM HIDDEN UNTUK DELETE KOMENTAR -->
<form id="delete-komentar-form" action="{{ route('delegasi.submisi.komentar.destroy') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
    <input type="hidden" name="komentar_id" id="delete_komentar_id">
</form>

<!-- MODAL CONFIRM DELETE KOMENTAR -->
<div id="deleteConfirmModal" class="fixed inset-0 z-[200] bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4 transition-opacity opacity-0 duration-300">
    <div class="absolute inset-0" onclick="closeDeleteConfirmModal()"></div>
    <div class="relative bg-white rounded-[2rem] p-6 md:p-8 max-w-sm w-full shadow-2xl transform scale-95 transition-transform duration-300 text-center" id="deleteConfirmContent">
        <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-red-50">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg>
        </div>
        <h3 class="text-lg font-black text-slate-900 mb-2">Hapus Komentar?</h3>
        <p class="text-[13px] text-slate-500 leading-relaxed mb-6">Apakah Anda yakin ingin menghapus komentar ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex gap-3">
            <button type="button" onclick="closeDeleteConfirmModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3.5 rounded-xl transition-colors text-sm">Batal</button>
            <button type="button" onclick="executeDeleteKomentar()" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3.5 rounded-xl transition-colors shadow-md shadow-red-500/20 text-sm">Hapus</button>
        </div>
    </div>
</div>

<!-- MODAL REPORT KOMENTAR -->
<div id="reportModal" class="fixed inset-0 z-[200] bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4 transition-opacity opacity-0 duration-300">
    <div class="absolute inset-0" onclick="closeReportModal()"></div>
    <div class="relative bg-white rounded-[2rem] p-6 md:p-8 max-w-md w-full shadow-2xl transform scale-95 transition-transform duration-300" id="reportContent">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-black text-slate-900">Laporkan Komentar</h3>
            <button type="button" onclick="closeReportModal()" class="text-slate-400 hover:text-red-500 focus:outline-none">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        
        <form id="form-report-komentar" action="{{ route('delegasi.submisi.komentar.report') }}" method="POST">
            @csrf
            <input type="hidden" name="komentar_id" id="report_komentar_id">
            <input type="hidden" name="alasan" id="final_alasan">
            
            <p class="text-xs text-slate-500 mb-4 font-medium">Pilih alasan mengapa komentar ini bermasalah:</p>
            
            <div class="space-y-3 mb-6">
                <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-colors">
                    <input type="radio" name="alasan_radio" value="Komentar mengandung Spam / Iklan." class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-slate-300" onchange="toggleCustomReason(false)" required>
                    <span class="text-sm text-slate-700 font-bold">Spam / Iklan</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-colors">
                    <input type="radio" name="alasan_radio" value="Mengandung Ujaran Kebencian / SARA." class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-slate-300" onchange="toggleCustomReason(false)">
                    <span class="text-sm text-slate-700 font-bold">Ujaran Kebencian / SARA</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-colors">
                    <input type="radio" name="alasan_radio" value="Pelecehan atau Perundungan (Cyberbullying)." class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-slate-300" onchange="toggleCustomReason(false)">
                    <span class="text-sm text-slate-700 font-bold">Pelecehan / Bullying</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-colors">
                    <input type="radio" name="alasan_radio" value="other" class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-slate-300" onchange="toggleCustomReason(true)">
                    <span class="text-sm text-slate-700 font-bold">Lainnya...</span>
                </label>
            </div>

            <div id="custom-reason-container" class="hidden mb-6">
                <textarea id="custom-reason-input" rows="3" placeholder="Tuliskan keterangan lebih lanjut..." class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-colors resize-none disabled:bg-slate-100 disabled:text-transparent" disabled></textarea>
            </div>

            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3.5 rounded-xl transition-colors shadow-md text-sm">
                Kirim Laporan
            </button>
        </form>
    </div>
</div>

<!-- MODAL LOGIN PROMPT -->
<div id="loginPromptModal" class="fixed inset-0 z-[200] bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4 transition-opacity opacity-0 duration-300">
    <div class="absolute inset-0" onclick="closeLoginPrompt()"></div>
    <div class="relative bg-white rounded-[2rem] p-8 max-w-sm w-full shadow-2xl transform scale-95 transition-transform duration-300 text-center" id="loginPromptContent">
        <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-blue-50">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
        </div>
        <h3 class="text-lg font-black text-slate-900 mb-2">Akses Terbatas</h3>
        <p class="text-[13px] text-slate-500 leading-relaxed mb-6">Silakan masuk ke akun Anda terlebih dahulu untuk dapat berinteraksi dan menyukai karya ini.</p>
        <div class="flex flex-col gap-3">
            <a href="{{ route('login') }}" class="w-full bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition-colors shadow-md shadow-blue-500/20 text-sm">Login Sekarang</a>
            <button type="button" onclick="closeLoginPrompt()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3.5 rounded-xl transition-colors text-sm">Nanti Saja</button>
        </div>
    </div>
</div>

@include('partials.footer')

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .pop-heart { animation: pop 0.4s ease forwards; }
    @keyframes pop { 0% { transform: scale(1); } 50% { transform: scale(1.4); } 100% { transform: scale(1); } }
</style>

<script>
    // --- MANAJEMEN GALERI & LIGHTBOX ---
    const mediaGallery = [
        @foreach($semuaMedia as $media)
            @php $ext = strtolower(pathinfo($media, PATHINFO_EXTENSION)); @endphp
            { type: '{{ in_array($ext, ["mp4", "webm", "mov"]) ? "video" : "image" }}', url: '{{ asset("storage/" . $media) }}' },
        @endforeach
    ];
    let currentMediaIndex = 0;

    function changeMainMedia(index) {
        currentMediaIndex = index;
        const mainContainer = document.getElementById('main-media-container');
        const media = mediaGallery[index];
        
        mainContainer.innerHTML = '';
        if(media.type === 'video') {
            mainContainer.innerHTML = `
                <video src="${media.url}" class="w-full h-full object-contain bg-black" controls autoplay muted></video>
                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white font-bold text-xs pointer-events-none">Perbesar Video</div>
            `;
        } else {
            mainContainer.innerHTML = `
                <img src="${media.url}" class="w-full h-full object-contain bg-white transition-all duration-300">
                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white font-bold text-xs pointer-events-none">Klik untuk memperbesar</div>
            `;
        }
    }
    if(mediaGallery.length > 0) changeMainMedia(0);

    function updateLightboxContent() {
        const container = document.getElementById('lightbox-content-container');
        const media = mediaGallery[currentMediaIndex];
        if (media.type === 'video') {
            container.innerHTML = `<video src="${media.url}" controls autoplay class="max-w-full max-h-[85vh] object-contain rounded-xl shadow-2xl" onclick="event.stopPropagation()"></video>`;
        } else {
            container.innerHTML = `<img src="${media.url}" class="max-w-full max-h-[85vh] object-contain rounded-xl shadow-2xl select-none" onclick="event.stopPropagation()">`;
        }
    }

    function openLightbox(index = null) {
        if(index !== null) currentMediaIndex = index;
        updateLightboxContent();
        const lb = document.getElementById('lightbox');
        lb.classList.remove('hidden');
        lb.classList.add('flex');
        setTimeout(() => lb.classList.remove('opacity-0'), 10);
    }

    function closeLightbox(e) {
        if(e) e.stopPropagation();
        const lb = document.getElementById('lightbox');
        document.getElementById('lightbox-content-container').innerHTML = ''; 
        lb.classList.add('opacity-0');
        setTimeout(() => {
            lb.classList.add('hidden');
            lb.classList.remove('flex');
        }, 300);
    }

    function nextMedia(e) {
        if (e) e.stopPropagation();
        currentMediaIndex = (currentMediaIndex + 1) % mediaGallery.length;
        updateLightboxContent();
        changeMainMedia(currentMediaIndex);
    }

    function prevMedia(e) {
        if (e) e.stopPropagation();
        currentMediaIndex = (currentMediaIndex - 1 + mediaGallery.length) % mediaGallery.length;
        updateLightboxContent();
        changeMainMedia(currentMediaIndex); 
    }

    // --- MODAL LOGIN ---
    function openLoginPrompt() {
        const modal = document.getElementById('loginPromptModal');
        const content = document.getElementById('loginPromptContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeLoginPrompt() {
        const modal = document.getElementById('loginPromptModal');
        const content = document.getElementById('loginPromptContent');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    // --- MODAL SHARE CUSTOM ---
    let currentShareId = null;
    function openShareModal(id, title, url) {
        currentShareId = id;
        const modal = document.getElementById('shareModal');
        const content = document.getElementById('shareContent');
        
        const textToShare = `Lihat karya luar biasa "${title}" di Galeri Pameran KMDGI 16! \n\n`;
        const encodedText = encodeURIComponent(textToShare);
        const encodedUrl = encodeURIComponent(url);
        
        document.getElementById('share-url-input').value = url;
        document.getElementById('share-wa').href = `https://api.whatsapp.com/send?text=${encodedText}${encodedUrl}`;
        document.getElementById('share-fb').href = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
        document.getElementById('share-tw').href = `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedText}`;
        
        document.getElementById('copy-link-btn').onclick = function() {
            navigator.clipboard.writeText(textToShare + url).then(() => {
                alert('Teks ajakan dan tautan berhasil disalin!');
                triggerRecordShare(currentShareId);
            });
        };

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeShareModal() {
        const modal = document.getElementById('shareModal');
        const content = document.getElementById('shareContent');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function triggerRecordShare(id) {
        fetch(`/katalog-karya/${id}/share`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(res => res.json()).then(data => {
            if(data.success) {
                const viewsLabel = document.getElementById('shares-count-label');
                if(viewsLabel) viewsLabel.innerText = data.shares_count;
            }
        }).catch(err => console.log(err));
    }

    document.querySelectorAll('.social-share-link').forEach(link => {
        link.addEventListener('click', () => {
            if(currentShareId) triggerRecordShare(currentShareId);
        });
    });

    // --- REPORT & DELETE KOMENTAR ---
    function openDeleteConfirmModal(id) {
        document.getElementById('delete_komentar_id').value = id;
        const modal = document.getElementById('deleteConfirmModal');
        const content = document.getElementById('deleteConfirmContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeDeleteConfirmModal() {
        const modal = document.getElementById('deleteConfirmModal');
        const content = document.getElementById('deleteConfirmContent');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('delete_komentar_id').value = '';
        }, 300);
    }

    function executeDeleteKomentar() {
        document.getElementById('delete-komentar-form').submit();
    }

    function openReportModal(komentarId) {
        document.getElementById('report_komentar_id').value = komentarId;
        const modal = document.getElementById('reportModal');
        const content = document.getElementById('reportContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeReportModal() {
        const modal = document.getElementById('reportModal');
        const content = document.getElementById('reportContent');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('custom-reason-input').value = '';
            document.getElementById('custom-reason-input').disabled = true;
            document.getElementById('custom-reason-container').classList.add('hidden');
        }, 300);
    }

    function toggleCustomReason(showCustom) {
        const container = document.getElementById('custom-reason-container');
        const input = document.getElementById('custom-reason-input');
        if(showCustom) {
            container.classList.remove('hidden');
            input.disabled = false;
            input.required = true;
        } else {
            container.classList.add('hidden');
            input.disabled = true;
            input.required = false;
        }
    }

    document.getElementById('form-report-komentar').addEventListener('submit', function(e) {
        e.preventDefault();
        const radios = document.getElementsByName('alasan_radio');
        let selectedReason = '';
        for (const radio of radios) {
            if (radio.checked) {
                selectedReason = radio.value;
                break;
            }
        }
        
        if (selectedReason === 'other') {
            selectedReason = document.getElementById('custom-reason-input').value;
        }
        
        document.getElementById('final_alasan').value = selectedReason;
        this.submit();
    });

    // --- LIKE ACTION ---
    function likeKaryaDetail(id, btnElement, isLoggedIn) {
        if(!isLoggedIn) {
            openLoginPrompt();
            return;
        }

        const svg = btnElement.querySelector('svg');
        svg.classList.add('pop-heart');
        setTimeout(() => svg.classList.remove('pop-heart'), 400);

        fetch(`/katalog-karya/${id}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                document.getElementById('detail-like-count').innerText = data.likes;
                if(data.status === 'liked') {
                    svg.classList.remove('text-blue-500', 'group-hover:text-red-500', 'group-hover:fill-current');
                    svg.classList.add('text-blue-500');
                    svg.setAttribute('fill', 'currentColor');
                } else {
                    svg.classList.remove('text-blue-500', 'group-hover:text-red-500', 'group-hover:fill-current');
                    svg.classList.add('text-blue-500', 'group-hover:text-red-500');
                    svg.setAttribute('fill', 'none');
                }
            }
        });
    }
</script>
@endsection