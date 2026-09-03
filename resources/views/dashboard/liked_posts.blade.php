@extends('layouts.app')
@section('title', 'Karya Disukai - KMDGI 16')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen flex flex-col">
    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8 font-sans relative">
        <div id="animation-layer" class="fixed inset-0 pointer-events-none z-50 overflow-hidden"></div>

        @include('partials.sidebar')

        <main class="flex-grow w-full pb-20">
            <div class="max-w-5xl mx-auto">

                <div class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] mb-8 relative overflow-hidden">
                    <div class="absolute -right-16 -top-16 w-64 h-64 bg-gradient-to-br from-rose-50 to-pink-50/30 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10">
                        <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight mb-2">Karya Disukai</h1>
                        <p class="text-sm text-slate-500 max-w-xl">Daftar koleksi karya apresiasi Anda yang telah disukai di seluruh galeri pameran KMDGI 16.</p>
                    </div>
                </div>

                @if($karyas->isEmpty())
                <div class="bg-white border border-slate-200 rounded-[2rem] p-12 flex flex-col items-center justify-center text-center shadow-sm">
                    <div class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mb-4 border border-rose-100">
                        <svg class="w-10 h-10 text-rose-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Karya yang Disukai</h3>
                    <p class="text-sm text-slate-500 max-w-sm">Anda belum menyukai karya apapun. Silakan jelajahi Galeri Karya dan berikan apresiasi Anda!</p>
                    <a href="{{ route('katalog.karya.index') }}" class="mt-6 bg-[#1A68FF] text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-blue-700 transition-colors shadow-md shadow-blue-500/20">Jelajahi Galeri</a>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($karyas as $item)
                    @php $slug = \Illuminate\Support\Str::slug($item->judul_karya); @endphp
                    <div class="group flex flex-col border border-slate-100 rounded-[1.5rem] bg-white hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300">
                        
                        <a href="{{ route('katalog.karya.show', $slug) }}" class="relative block aspect-[4/3] rounded-t-[1.5rem] overflow-hidden bg-slate-100 flex-shrink-0">
                            <img src="{{ asset('storage/' . $item->thumbnail_karya) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-md px-3 py-1 rounded-lg shadow-sm">
                                <span class="text-xs font-bold text-blue-600 capitalize">{{ $item->kategori_karya }}</span>
                            </div>
                        </a>

                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-4">
                                    <button onclick="likeKarya({{ $item->id }}, this, true)" class="flex items-center gap-1.5 text-red-500 hover:text-red-600 transition-colors focus:outline-none relative">
                                        <svg class="w-5 h-5 transition-transform active:scale-75" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                                        <span class="text-xs font-bold" id="like-count-{{ $item->id }}">{{ $item->likes_count ?? 0 }}</span>
                                    </button>
                                    
                                    <a href="{{ route('katalog.karya.show', $slug) }}#komentar" class="flex items-center gap-1.5 text-slate-500 hover:text-blue-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.069C10.22 20.187 11.1 20.25 12 20.25Z" /></svg>
                                        <span class="text-xs font-bold">{{ $item->komentars_count ?? 0 }}</span>
                                    </a>
                                </div>
                            </div>

                            <a href="{{ route('katalog.karya.show', $slug) }}" class="block flex-grow">
                                <h2 class="text-lg font-bold text-slate-900 leading-tight mb-2 line-clamp-2 hover:underline">{{ $item->judul_karya }}</h2>
                                <p class="text-[13px] text-slate-600 line-clamp-2 leading-relaxed mb-4">{{ $item->kreator_karya }}</p>
                            </a>

                            <div class="flex items-center gap-2 pt-4 border-t border-slate-50 mt-auto">
                                @php 
                                    $logoPath = $kampusLogos[$item->user->institusi] ?? null;
                                    $defaultLogo = 'https://ui-avatars.com/api/?name='.urlencode($item->user->institusi ?? 'U').'&background=f1f5f9';
                                @endphp
                                <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden border border-slate-200 bg-white">
                                    <img src="{{ $logoPath ? asset('storage/' . $logoPath) : $defaultLogo }}" class="w-full h-full object-cover">
                                </div>
                                <span class="text-xs font-bold text-slate-800 line-clamp-1">{{ $item->user->institusi ?? 'Umum' }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-10">
                    {{ $karyas->links() }}
                </div>
                @endif
            </div>
        </main>
    </div>
    @include('partials.footer')
</div>

<style>
    .floating-heart {
        position: absolute;
        animation: floatUp 1.2s ease-out forwards;
        color: #ef4444;
        font-size: 24px;
        pointer-events: none;
    }
    @keyframes floatUp {
        0% { transform: translateY(0) scale(1); opacity: 1; }
        100% { transform: translateY(-80px) scale(1.5); opacity: 0; }
    }
</style>

<script>
    // Copy fungsi Like yang sama seperti di katalog
    function likeKarya(id, btnElement, isLoggedIn) {
        fetch(`/katalog-karya/${id}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById(`like-count-${id}`).innerText = data.likes;
                if(data.status === 'liked') {
                    btnElement.classList.remove('text-slate-500');
                    btnElement.classList.add('text-red-500');
                    btnElement.querySelector('svg').setAttribute('fill', 'currentColor');
                } else {
                    btnElement.classList.remove('text-red-500');
                    btnElement.classList.add('text-slate-500');
                    btnElement.querySelector('svg').setAttribute('fill', 'none');
                }
            }
        }).catch(err => console.error(err));
    }
</script>
@endsection