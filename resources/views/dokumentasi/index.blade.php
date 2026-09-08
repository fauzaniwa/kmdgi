@extends('layouts.app')
@section('title', 'Galeri Dokumentasi - KMDGI 16')

@section('content')
<div class="bg-[#FFFDFD] min-h-screen flex flex-col font-sans">
    @include('partials.navbar')

    <!-- HERO SECTION -->
    <section class="relative w-full pt-32 pb-16 md:pt-40 md:pb-24 bg-slate-900 overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-20">
            <div class="absolute w-96 h-96 bg-[#1A68FF] rounded-full blur-[100px] -top-20 -left-20"></div>
            <div class="absolute w-96 h-96 bg-[#FF6B9E] rounded-full blur-[120px] bottom-0 right-0"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight mb-4">Galeri Dokumentasi</h1>
            <p class="text-slate-300 max-w-2xl mx-auto text-sm md:text-lg">Kilas balik perjalanan, keseruan, dan berbagai momen penting dalam rangkaian kegiatan KMDGI 16.</p>
        </div>
    </section>

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-grow">
        
        <!-- FILTER KATEGORI -->
        <div class="flex flex-wrap items-center justify-center gap-3 mb-12">
            <a href="{{ route('dokumentasi.index') }}" class="px-5 py-2 rounded-full text-sm font-bold transition-all {{ request('kategori') == null || request('kategori') == 'all' ? 'bg-[#1A68FF] text-white shadow-md' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">Semua Momen</a>
            
            @foreach($kategoris as $kat)
            <a href="{{ route('dokumentasi.index', ['kategori' => $kat]) }}" class="px-5 py-2 rounded-full text-sm font-bold transition-all {{ request('kategori') == $kat ? 'bg-[#1A68FF] text-white shadow-md' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                {{ $kat }}
            </a>
            @endforeach
        </div>

        <!-- MASONRY GRID -->
        <div class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">
            @forelse($dokumentasis as $item)
                @php
                    $ytId = '';
                    if($item->tipe_media == 'Video YouTube' && $item->video_url) {
                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $item->video_url, $match);
                        $ytId = $match[1] ?? '';
                    }
                @endphp

                <div class="break-inside-avoid group cursor-pointer" onclick="openDocModal({{ json_encode($item) }}, '{{ $ytId }}')">
                    <div class="relative rounded-[1.5rem] overflow-hidden bg-slate-100 shadow-sm border border-slate-200 group-hover:shadow-xl transition-all duration-300">
                        
                        <!-- Thumbnail -->
                        @if($item->tipe_media == 'Foto' || $item->tipe_media == 'Video Upload')
                            <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->judul }}" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                        @elseif($item->tipe_media == 'Video YouTube' && $ytId)
                            <img src="https://img.youtube.com/vi/{{ $ytId }}/hqdefault.jpg" alt="{{ $item->judul }}" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                        @else
                            <div class="w-full aspect-video flex items-center justify-center bg-slate-800 text-slate-400">Media Error</div>
                        @endif

                        <!-- Badge Icon Media -->
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm p-2 rounded-full shadow-sm text-slate-700">
                            @if($item->tipe_media == 'Foto')
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            @else
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                            @endif
                        </div>

                        <!-- Overlay Hover -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-5">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-300 mb-1">{{ \Carbon\Carbon::parse($item->tanggal_kegiatan)->locale('id')->translatedFormat('d M Y') }} • {{ $item->kategori_kegiatan }}</span>
                            <h3 class="text-white font-bold text-lg leading-tight">{{ $item->judul }}</h3>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    <p class="text-slate-500 font-medium">Belum ada dokumentasi pada kategori ini.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $dokumentasis->links() }}
        </div>
    </div>
    
    @include('partials.footer')
</div>

<!-- ========================================== -->
<!-- MODAL DETAIL DOKUMENTASI & MEDIA PLAYER    -->
<!-- ========================================== -->
<div id="docModal" class="fixed inset-0 z-[120] flex items-center justify-center p-4 sm:p-6 bg-slate-900/95 backdrop-blur-md transition-all duration-300 opacity-0 pointer-events-none">
    <div class="relative w-full max-w-5xl bg-white rounded-[2rem] overflow-hidden shadow-2xl transform transition-all scale-95 flex flex-col lg:flex-row max-h-[90vh]" id="docModalContent">
        
        <button type="button" onclick="closeDocModal()" class="absolute top-4 right-4 z-50 text-slate-400 hover:text-red-500 bg-white/80 backdrop-blur-sm hover:bg-white rounded-full p-2 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>

        <!-- Kiri: Media Container -->
        <div class="w-full lg:w-[65%] bg-slate-900 flex items-center justify-center relative overflow-hidden" id="modal-media-container" style="min-h: 300px;">
            <!-- Diisi secara dinamis lewat JS -->
        </div>

        <!-- Kanan: Detail Container -->
        <div class="w-full lg:w-[35%] bg-white p-6 md:p-8 flex flex-col h-full overflow-y-auto">
            <span id="modal-kategori" class="text-[10px] font-bold text-[#1A68FF] uppercase tracking-widest mb-2 block">Kategori</span>
            <h3 id="modal-judul" class="text-xl md:text-2xl font-black text-slate-900 leading-tight mb-3">Judul Momen</h3>
            
            <div class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-6 pb-6 border-b border-slate-100">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                <span id="modal-tanggal">Tanggal</span>
            </div>

            <div id="modal-deskripsi" class="prose prose-slate text-sm text-slate-600 leading-relaxed font-medium">
                <!-- Deskripsi masuk sini -->
            </div>
        </div>
    </div>
</div>

<script>
    function openDocModal(data, ytId) {
        const modal = document.getElementById('docModal');
        const content = document.getElementById('docModalContent');
        const mediaContainer = document.getElementById('modal-media-container');
        
        // Isi Teks
        document.getElementById('modal-kategori').innerText = data.kategori_kegiatan;
        document.getElementById('modal-judul').innerText = data.judul;
        
        // Format Tanggal
        const dateObj = new Date(data.tanggal_kegiatan);
        document.getElementById('modal-tanggal').innerText = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        
        document.getElementById('modal-deskripsi').innerHTML = data.deskripsi || '<i class="text-slate-400">Tidak ada deskripsi.</i>';

        // Tentukan Media Render
        mediaContainer.innerHTML = ''; // Clear lama
        
        if (data.tipe_media === 'Foto') {
            mediaContainer.innerHTML = `<img src="/storage/${data.file_path}" class="w-full h-full object-contain" alt="${data.judul}">`;
        } else if (data.tipe_media === 'Video Upload') {
            mediaContainer.innerHTML = `
                <video controls autoplay class="w-full h-full object-contain">
                    <source src="/storage/${data.file_path}" type="video/mp4">
                    Browser Anda tidak mendukung tag video.
                </video>`;
        } else if (data.tipe_media === 'Video YouTube' && ytId) {
            mediaContainer.innerHTML = `
                <iframe class="w-full h-full aspect-video" src="https://www.youtube.com/embed/${ytId}?autoplay=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            `;
        }

        // Tampilkan Modal
        modal.classList.remove('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-95');
        document.body.style.overflow = 'hidden';
    }

    function closeDocModal() {
        const modal = document.getElementById('docModal');
        const content = document.getElementById('docModalContent');
        const mediaContainer = document.getElementById('modal-media-container');

        modal.classList.add('opacity-0', 'pointer-events-none');
        content.classList.add('scale-95');
        document.body.style.overflow = 'auto';

        // Hentikan video/youtube saat ditutup
        setTimeout(() => { mediaContainer.innerHTML = ''; }, 300);
    }

    // Tutup jika area luar diklik
    document.getElementById('docModal').addEventListener('click', function(e) {
        if (e.target === this) closeDocModal();
    });
</script>
@endsection