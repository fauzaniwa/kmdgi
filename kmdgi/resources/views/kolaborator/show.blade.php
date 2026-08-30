@extends('layouts.app')
@section('title', $kolaborator->nama . ' - KMDGI 16')

<!-- ========================================== -->
<!-- META TAGS UNTUK PREVIEW SOSIAL MEDIA       -->
<!-- ========================================== -->
@section('meta')
<meta property="og:title" content="{{ $kolaborator->nama }} - Kolaborator KMDGI 16">
<meta property="og:description" content="{{ Str::limit(strip_tags($kolaborator->detail ?? $kolaborator->profesi), 150) }}">
<meta property="og:image" content="{{ $kolaborator->foto ? asset('storage/' . $kolaborator->foto) : asset('images/default-hero.jpg') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="profile">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $kolaborator->nama }} - Kolaborator KMDGI 16">
<meta name="twitter:description" content="{{ Str::limit(strip_tags($kolaborator->detail ?? $kolaborator->profesi), 150) }}">
<meta name="twitter:image" content="{{ $kolaborator->foto ? asset('storage/' . $kolaborator->foto) : asset('images/default-hero.jpg') }}">
@endsection

@section('content')
<div class="bg-white min-h-screen flex flex-col relative font-sans overflow-x-hidden">
    
    <!-- NAVBAR -->
    <div class="absolute top-0 left-0 w-full z-50">
        @include('partials.navbar')
    </div>

    <!-- ========================================== -->
    <!-- HERO SECTION (DARK BACKGROUND - WELCOME STYLE) -->
    <!-- ========================================== -->
    <section class="relative pt-32 pb-20 md:pt-40 md:pb-32 bg-[#1a1a1a] overflow-hidden">
        
        <!-- Background Abstract Tube (Mengambil dari welcome.blade.php) -->
        <div class="absolute inset-0 z-0">
            <picture>
                <source media="(min-width: 768px)" srcset="{{ asset('images/bg-abstract-tube-desktop.png') }}">
                <img src="{{ asset('images/bg-abstract-tube-mobile.png') }}" alt="Background Abstract KMDGI" class="w-full h-full object-cover object-center opacity-40 mix-blend-screen" onerror="this.style.display='none'" />
            </picture>
            <div class="absolute inset-0 bg-gradient-to-b from-[#1a1a1a]/90 via-[#1a1a1a]/60 to-[#1a1a1a]"></div>
        </div>

        <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- BREADCRUMB -->
            <div class="text-xs md:text-sm text-slate-400 font-semibold tracking-wide mb-8 md:mb-12">
                <a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a> 
                <span class="mx-2">/</span> 
                <a href="{{ url('/kolaborator') }}" class="hover:text-white transition-colors">Kolaborator</a> 
                <span class="mx-2">/</span> 
                <span class="text-white">{{ $kolaborator->nama }}</span>
            </div>

            <!-- KONTEN HERO -->
            <div class="flex flex-col-reverse md:flex-row gap-12 md:gap-16 items-center md:items-start justify-between">
                
                <!-- Kiri: Teks & Aksi -->
                <div class="w-full md:w-1/2 lg:w-3/5 flex flex-col gap-2 text-center md:text-left">
                    
                    <div class="inline-block px-4 py-1.5 bg-white/10 backdrop-blur-sm text-white text-xs font-bold tracking-widest uppercase rounded-full w-fit mb-4 border border-white/20 mx-auto md:mx-0">
                        {{ $kolaborator->peran_kolaborasi }}
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-[4rem] font-black text-white leading-[1.1] tracking-tight mb-4 drop-shadow-lg">
                        {{ $kolaborator->nama }}
                    </h1>
                    
                    <p class="text-base md:text-xl text-slate-300 font-medium mb-8 leading-relaxed max-w-xl mx-auto md:mx-0">
                        {{ $kolaborator->profesi }}
                    </p>

                    <!-- Tombol Sosial Media & Bagikan (Rounded Full Style) -->
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mt-2">
                        
                        @if($kolaborator->link_instagram)
                        <a href="{{ $kolaborator->link_instagram }}" target="_blank" class="inline-flex justify-center items-center bg-white hover:bg-slate-100 text-slate-900 font-bold py-3.5 px-8 rounded-full transition-transform transform hover:-translate-y-1 text-sm shadow-sm gap-2">
                            <svg class="w-5 h-5 text-[#E1306C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                            Instagram
                        </a>
                        @endif

                        @if($kolaborator->link_linkedin)
                        <a href="{{ $kolaborator->link_linkedin }}" target="_blank" class="inline-flex justify-center items-center bg-transparent hover:bg-white/10 text-white font-bold py-3.5 px-6 rounded-full border border-white/30 transition-transform transform hover:-translate-y-1 text-sm shadow-sm gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                        @endif

                        @if($kolaborator->link_website)
                        <a href="{{ $kolaborator->link_website }}" target="_blank" class="inline-flex justify-center items-center bg-transparent hover:bg-white/10 text-white font-bold py-3.5 px-6 rounded-full border border-white/30 transition-transform transform hover:-translate-y-1 text-sm shadow-sm gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                        </a>
                        @endif

                        <div class="h-8 w-px bg-white/20 mx-2 hidden sm:block"></div>

                        <!-- Tombol Share -->
                        <div class="flex items-center gap-2 relative">
                            <button onclick="copyToClipboard('{{ url()->current() }}')" class="w-12 h-12 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-white/20 border border-white/10 transition-colors" title="Salin Tautan">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </button>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode('Lihat profil ' . $kolaborator->nama . ' di KMDGI 16! Cek disini: ' . url()->current()) }}" target="_blank" class="w-12 h-12 rounded-full bg-[#25D366]/20 text-[#25D366] flex items-center justify-center hover:bg-[#25D366]/30 border border-[#25D366]/30 transition-colors" title="Bagikan ke WhatsApp">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            </a>
                            
                            <!-- Notifikasi Copy -->
                            <span id="copy-toast" class="opacity-0 pointer-events-none transition-opacity duration-300 text-[10px] font-bold text-white bg-[#1A68FF] px-3 py-1.5 rounded-md absolute -bottom-10 left-1/2 transform -translate-x-1/2 whitespace-nowrap shadow-lg">
                                Tautan Disalin!
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Foto Kolaborator -->
                <div class="w-full md:w-1/2 lg:w-2/5 flex justify-center md:justify-end">
                    <div class="w-full max-w-[280px] sm:max-w-[320px] aspect-[4/5] rounded-[2rem] overflow-hidden shadow-2xl relative border-4 border-[#1e1e1e]/50 ring-1 ring-white/10 group cursor-pointer" onclick="openPosterModal()">
                        @if($kolaborator->foto)
                            <img src="{{ asset('storage/' . $kolaborator->foto) }}" alt="{{ $kolaborator->nama }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            
                            <!-- Overlay Klik Gambar -->
                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <div class="bg-white/20 backdrop-blur-md p-4 rounded-full text-white shadow-lg border border-white/30 transform scale-75 group-hover:scale-100 transition-transform duration-300">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                    </svg>
                                </div>
                            </div>
                        @else
                            <div class="w-full h-full bg-slate-800 flex items-center justify-center text-slate-500 font-bold text-4xl">
                                {{ strtoupper(substr($kolaborator->nama, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- CONTENT SECTION (DESKRIPSI / LATAR BELAKANG) -->
    <!-- ========================================== -->
    <div class="bg-white py-16 md:py-24 relative z-20 flex-grow">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($kolaborator->detail)
                <!-- Menggunakan Tailwind Typography (Prose) untuk merender Tag HTML -->
                <div class="prose prose-slate prose-lg max-w-none 
                            prose-headings:font-black prose-headings:text-slate-900 prose-headings:mb-4 prose-headings:tracking-tight
                            prose-h2:text-3xl prose-h2:md:text-4xl prose-h2:mt-12
                            prose-p:text-base prose-p:md:text-lg prose-p:text-slate-600 prose-p:leading-relaxed prose-p:font-medium
                            prose-li:text-base prose-li:md:text-lg prose-li:text-slate-600 prose-li:font-medium
                            prose-a:text-[#1A68FF] hover:prose-a:text-blue-800 transition-colors">
                    {!! $kolaborator->detail !!}
                </div>
            @else
                <div class="text-center py-12 border-2 border-dashed border-slate-200 rounded-[2rem]">
                    <p class="text-slate-400 font-medium">Belum ada detail informasi latar belakang atau riwayat untuk kolaborator ini.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ========================================== -->
    <!-- PROFIL KOLABORATOR LAINNYA                 -->
    <!-- ========================================== -->
    @if(isset($kolaboratorLainnya) && $kolaboratorLainnya->count() > 0)
    <section class="py-20 md:py-28 bg-slate-50 relative z-20 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">Profil Kolaborator Lainnya</h2>
            </div>

            <!-- TAMPILAN MENIRU "WELCOME.BLADE.PHP" SECTION 7 -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
                @foreach($kolaboratorLainnya as $other)
                <a href="{{ url('/kolaborator/' . \Illuminate\Support\Str::slug($other->nama)) }}" class="group relative w-full aspect-[3/4] rounded-[1.5rem] md:rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 bg-slate-900 block border border-slate-200">
                    
                    @if($other->foto)
                        <img src="{{ asset('storage/' . $other->foto) }}" alt="{{ $other->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90 group-hover:opacity-100">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-slate-800 text-slate-400 text-6xl font-black group-hover:scale-105 transition-transform duration-700">
                            {{ strtoupper(substr($other->nama, 0, 1)) }}
                        </div>
                    @endif
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-300"></div>

                    <!-- Elemen Dekoratif Tab Warna KMDGI -->
                    <div class="absolute -bottom-2 left-0 w-full flex flex-col transition-transform duration-300 transform group-hover:-translate-y-2">
                        
                        <div class="flex items-end pl-0">
                            <div class="bg-[#1A68FF] group-hover:bg-blue-700 h-8 md:h-10 w-[45%] rounded-tr-[1.25rem] relative z-20 transition-colors duration-300"></div>
                            <div class="bg-[#FF6B9E] h-5 md:h-7 w-12 md:w-16 rounded-tr-lg -ml-4 md:-ml-6 relative z-10 transition-transform duration-300 group-hover:-translate-y-1.5 transform -skew-x-[20deg] origin-bottom-left"></div>
                            <div class="bg-[#C4F03B] h-3 md:h-5 w-10 md:w-14 rounded-tr-md -ml-3 md:-ml-5 relative z-0 transition-transform duration-300 group-hover:-translate-y-3 transform -skew-x-[20deg] origin-bottom-left"></div>
                        </div>
                        
                        <div class="bg-[#1A68FF] group-hover:bg-blue-700 w-full px-5 pt-4 pb-6 md:px-6 md:pt-5 md:pb-8 z-20 relative flex flex-col justify-end transition-colors duration-300">
                            <h3 class="text-white font-bold text-lg md:text-xl truncate mb-1">
                                {{ $other->nama }}
                            </h3>
                            <p class="text-blue-100/90 text-[11px] md:text-xs font-medium truncate">
                                {{ $other->profesi }}
                            </p>
                        </div>

                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                <a href="{{ url('/kolaborator') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full border-2 border-slate-300 text-slate-700 hover:bg-slate-900 hover:text-white hover:border-slate-900 font-bold text-sm transition-colors group">
                    Lihat Semua Kolaborator 
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                </a>
            </div>

        </div>
    </section>
    @endif

    @include('partials.footer')
</div>

<!-- ========================================== -->
<!-- MODAL FULLSCREEN FOTO KOLABORATOR          -->
<!-- ========================================== -->
<div id="posterModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-8 bg-[#1a1a1a]/95 backdrop-blur-sm transition-all duration-300 opacity-0 pointer-events-none">
    <div class="relative max-w-4xl w-full max-h-[90vh] flex flex-col items-center justify-center transform transition-all scale-95" id="posterModalContent">
        
        <!-- Tombol Close Modal -->
        <button type="button" onclick="closePosterModal()" class="absolute -top-12 right-0 md:-right-12 text-slate-400 hover:text-white bg-slate-800 hover:bg-red-500 rounded-full p-2.5 transition-colors shadow-lg z-20 focus:outline-none">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        
        <!-- Gambar Foto Modal -->
        @if($kolaborator->foto)
            <img src="{{ asset('storage/' . $kolaborator->foto) }}" alt="{{ $kolaborator->nama }}" class="max-w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl relative z-10">
        @endif
        
    </div>
</div>

<script>
    // FUNGSI COPY TO CLIPBOARD
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            const toast = document.getElementById('copy-toast');
            toast.classList.remove('opacity-0', 'pointer-events-none');
            setTimeout(() => {
                toast.classList.add('opacity-0', 'pointer-events-none');
            }, 2000);
        }).catch(err => {
            console.error('Gagal menyalin text: ', err);
        });
    }

    // FUNGSI MODAL FOTO FULLSCREEN
    function openPosterModal() {
        const modal = document.getElementById('posterModal');
        const content = document.getElementById('posterModalContent');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-95');
        document.body.style.overflow = 'hidden'; // Mencegah background scroll
    }

    function closePosterModal() {
        const modal = document.getElementById('posterModal');
        const content = document.getElementById('posterModalContent');
        modal.classList.add('opacity-0', 'pointer-events-none');
        content.classList.add('scale-95');
        document.body.style.overflow = 'auto'; // Mengembalikan scroll
    }

    // Menutup modal foto jika mengeklik area background gelap
    document.getElementById('posterModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePosterModal();
        }
    });
</script>
@endsection