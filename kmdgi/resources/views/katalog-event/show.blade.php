@extends('layouts.app')
@section('title', $event->judul . ' - KMDGI 16')

<!-- ========================================== -->
<!-- META TAGS UNTUK PREVIEW SOSIAL MEDIA       -->
<!-- ========================================== -->
@section('meta')
<meta property="og:title" content="{{ $event->judul }} - KMDGI 16">
<meta property="og:description" content="{{ Str::limit(strip_tags($event->deskripsi), 150) }}">
<meta property="og:image" content="{{ $event->poster ? asset('storage/' . $event->poster) : asset('images/default-hero.jpg') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $event->judul }} - KMDGI 16">
<meta name="twitter:description" content="{{ Str::limit(strip_tags($event->deskripsi), 150) }}">
<meta name="twitter:image" content="{{ $event->poster ? asset('storage/' . $event->poster) : asset('images/default-hero.jpg') }}">
@endsection

@section('content')
<div class="bg-white min-h-screen flex flex-col relative font-sans">
    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 flex-grow">

        <!-- BACK BUTTON & ERROR NOTIFICATION -->
        <a href="{{ route('katalog.event') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 font-medium text-sm mb-6 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali
        </a>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-xl text-sm font-semibold shadow-sm mb-8 flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            {{ $errors->first() }}
        </div>
        @endif

        <!-- ========================================== -->
        <!-- MAIN CONTENT (KIRI: TEKS, KANAN: POSTER)   -->
        <!-- ========================================== -->
        <div class="flex flex-col-reverse lg:flex-row gap-12 lg:gap-16 items-start">

            <!-- BAGIAN KIRI (Info Acara) -->
            <div class="w-full lg:w-1/2 flex flex-col gap-6">

                <div>
                    <h1 class="text-3xl md:text-[2.5rem] font-black text-slate-900 leading-[1.2] tracking-tight mb-4">
                        {{ $event->judul }}
                    </h1>

                    <!-- FITUR SHARE KE MEDIA SOSIAL -->
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bagikan:</span>
                        
                        <!-- Copy Link -->
                        <button onclick="copyToClipboard('{{ url()->current() }}')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-slate-200 transition-colors" title="Salin Tautan">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                        </button>
                        
                        <!-- WhatsApp -->
                        <a href="https://api.whatsapp.com/send?text={{ urlencode('Yuk ikutan acara ' . $event->judul . ' di KMDGI 16! Cek detailnya disini: ' . url()->current()) }}" target="_blank" class="w-8 h-8 rounded-full bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-100 transition-colors" title="Bagikan ke WhatsApp">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        </a>

                        <!-- X / Twitter -->
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode('Yuk ikutan acara ' . $event->judul . ' di KMDGI 16!') }}&url={{ urlencode(url()->current()) }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-100 text-slate-900 flex items-center justify-center hover:bg-slate-200 transition-colors" title="Bagikan ke X/Twitter">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        
                        <!-- Notifikasi Copy -->
                        <span id="copy-toast" class="opacity-0 transition-opacity duration-300 text-[10px] font-bold text-[#1A68FF] bg-blue-50 px-2 py-1 rounded-md">
                            Tautan Disalin!
                        </span>
                    </div>
                </div>

                <!-- Harga & Tombol Daftar -->
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mt-2">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 mb-1">Harga</span>
                        <h3 class="text-2xl font-black text-slate-900">{{ $event->harga_tiket == 0 ? 'FREE' : 'Rp ' . number_format($event->harga_tiket, 0, ',', '.') }}</h3>
                    </div>

                    @if(!Auth::check())
                    <a href="{{ route('login') }}" class="w-full sm:w-auto bg-[#1A68FF] hover:bg-blue-700 text-white text-center font-semibold py-3 px-8 rounded-xl text-sm transition-colors">
                        Masuk untuk Daftar
                    </a>
                    @else
                    @if($tiketSaya)
                    @if($tiketSaya->status === 'Menunggu Konfirmasi')
                    <a href="{{ route('dashboard') }}" class="block w-full sm:w-auto text-center bg-amber-50 text-amber-600 border border-amber-200 font-semibold py-3 px-6 rounded-xl text-sm transition-colors">Menunggu Konfirmasi</a>
                    @else
                    <a href="{{ route('dashboard') }}" class="w-full sm:w-auto bg-emerald-50 text-emerald-600 border border-emerald-200 font-semibold py-3 px-6 rounded-xl text-sm transition-colors flex items-center justify-center gap-2 hover:bg-emerald-100"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg> Tiket Terdaftar</a>
                    @endif
                    @elseif($isPast)
                    <button disabled class="w-full sm:w-auto bg-slate-100 text-slate-400 font-semibold py-3 px-6 rounded-xl text-sm cursor-not-allowed border border-slate-200">Telah Berakhir</button>
                    @elseif($isFull)
                    <button disabled class="w-full sm:w-auto bg-red-50 text-red-500 font-semibold py-3 px-6 rounded-xl text-sm cursor-not-allowed border border-red-100">Kuota Habis</button>
                    @else
                    <button onclick="openDaftarModal()" class="w-full sm:w-auto bg-[#1A68FF] hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-xl text-sm transition-colors shadow-md shadow-blue-500/20">Daftar sebagai Peserta</button>
                    @endif
                    @endif
                </div>

                <!-- ========================================== -->
                <!-- PROFIL KOLABORATOR (DESAIN LIST)           -->
                <!-- ========================================== -->
                @php $kolaborators = $event->getKolaboratorTerkait(); @endphp
                @if($kolaborators->count() > 0)
                <div class="mt-8">
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Kolaborator Acara</span>
                    <div class="flex flex-col gap-3">
                        @foreach($kolaborators as $kolab)
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all hover:shadow-sm">
                            
                            <!-- Bagian Kiri: Area Klik ke Profil Kolaborator -->
                            <a href="{{ url('/kolaborator/' . \Illuminate\Support\Str::slug($kolab->nama)) }}" class="flex items-center gap-4 flex-grow group">
                                <div class="w-14 h-14 bg-slate-200 rounded-full overflow-hidden flex-shrink-0 border-2 border-transparent group-hover:border-[#1A68FF] transition-colors">
                                    @if($kolab->foto)
                                    <img src="{{ asset('storage/' . $kolab->foto) }}" alt="{{ $kolab->nama }}" class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 text-lg font-black bg-slate-200">{{ substr($kolab->nama, 0, 1) }}</div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-base group-hover:text-[#1A68FF] transition-colors">{{ $kolab->nama }}</h4>
                                    <p class="text-xs text-slate-500">{{ $kolab->profesi ?? 'Narasumber' }}</p>
                                </div>
                            </a>

                            <!-- Bagian Kanan: Aksi (Instagram & Link Profil) -->
                            <div class="flex items-center gap-2">
                                @if($kolab->link_instagram)
                                <a href="{{ $kolab->link_instagram }}" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full border border-blue-100 text-blue-600 bg-white hover:bg-blue-50 transition-colors" title="Instagram">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363-.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                @endif
                                <a href="{{ url('/kolaborator/' . \Illuminate\Support\Str::slug($kolab->nama)) }}" class="flex items-center gap-1.5 border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 hover:text-slate-900 hover:border-slate-300 px-4 py-2.5 rounded-full text-xs font-semibold transition-all shadow-sm">
                                    Lihat Profil
                                </a>
                            </div>

                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Detail Lokasi Waktu -->
                <div class="border border-slate-100 rounded-2xl p-5 space-y-4 mt-6">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span class="text-sm font-medium text-slate-800">{{ $event->jam_pelaksanaan ? \Carbon\Carbon::parse($event->jam_pelaksanaan)->format('H.i') : '-' }} WIB</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <span class="text-sm font-medium text-slate-800">{{ $event->tanggal_pelaksanaan ? \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->locale('id')->translatedFormat('d F Y') : '-' }}</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-slate-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6.75h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                        </svg>
                        <span class="text-sm font-medium text-slate-800 leading-relaxed">{{ $event->lokasi ?? 'Lokasi Menyusul' }}</span>
                    </div>
                </div>

                <!-- TABS: Deskripsi & Ketentuan -->
                <div class="mt-4">
                    <div class="flex border-b border-slate-200">
                        <button onclick="switchTab('deskripsi')" id="tab-deskripsi" class="flex-1 py-3 text-sm font-bold border-b-2 border-slate-900 text-slate-900 flex items-center justify-center gap-2 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg> Deskripsi
                        </button>
                        <button onclick="switchTab('ketentuan')" id="tab-ketentuan" class="flex-1 py-3 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-600 flex items-center justify-center gap-2 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                            </svg> Ketentuan
                        </button>
                    </div>

                    <div id="content-deskripsi" class="pt-6 prose prose-slate max-w-none text-sm leading-relaxed text-slate-600 block">
                        {!! $event->deskripsi !!}
                    </div>

                    <div id="content-ketentuan" class="pt-6 prose prose-slate max-w-none text-sm leading-relaxed text-slate-600 hidden">
                        @if($event->ketentuan)
                        {!! $event->ketentuan !!}
                        @else
                        <p class="italic text-slate-400">Tidak ada ketentuan khusus untuk acara ini.</p>
                        @endif
                    </div>
                </div>

            </div>

            <!-- BAGIAN KANAN (Poster Besar dengan Fitur Modal Popup) -->
            <div class="w-full lg:w-1/2 flex justify-end">
                <div onclick="openPosterModal()" class="w-full lg:max-w-md aspect-[3/4] md:aspect-[4/5] bg-slate-900 rounded-[2rem] overflow-hidden shadow-2xl relative sticky top-32 group cursor-pointer border border-slate-100">
                    @if($event->poster)
                        <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->judul }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        
                        <!-- Overlay Hover untuk memperjelas bahwa gambar bisa diklik (Zoom In) -->
                        <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <div class="bg-white/20 backdrop-blur-md p-4 rounded-full text-white shadow-lg border border-white/30 transform scale-75 group-hover:scale-100 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                </svg>
                            </div>
                        </div>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-800">
                            <svg class="w-12 h-12 mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <span class="font-semibold text-sm tracking-wider uppercase">Poster Tidak Tersedia</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- ========================================== -->
        <!-- ACARA LAINNYA (RANDOM EVENT)               -->
        <!-- ========================================== -->
        @if(isset($eventLainnya) && $eventLainnya->count() > 0)
        
        <!-- Tarik Data Kepemilikan Tiket Sekali Saja -->
        @php
            $myTicketsOther = collect();
            if(Auth::check()) {
                $myTicketsOther = \App\Models\TiketPeserta::where('user_id', Auth::id())
                                ->whereNotNull('event_kmdgi_id')
                                ->get()
                                ->keyBy('event_kmdgi_id');
            }
        @endphp

        <div class="mt-32 border-t border-slate-100 pt-16">
            <h2 class="text-3xl font-black text-slate-900 text-center mb-10">Acara Lainnya</h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @foreach($eventLainnya as $other)
                
                @php
                    $tiketLain = $myTicketsOther->get($other->id);
                    $isOtherRegistered = !is_null($tiketLain);
                    
                    $otherPesertaCount = \App\Models\TiketPeserta::where('event_kmdgi_id', $other->id)->count();
                    $isOtherFull = ($other->kuota > 0 && $otherPesertaCount >= $other->kuota);
                    
                    $isOtherPast = false;
                    if($other->tanggal_pelaksanaan) {
                        $waktuAcaraLain = \Carbon\Carbon::parse($other->tanggal_pelaksanaan . ' ' . ($other->jam_pelaksanaan ?? '00:00:00'));
                        $isOtherPast = $waktuAcaraLain->isPast();
                    }
                @endphp

                <!-- Card Clean Design Horizontal -->
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col sm:flex-row h-full group hover:shadow-xl transition-all duration-300">

                    <!-- Bagian Kiri: Poster -->
                    <div class="w-full sm:w-[240px] lg:w-[220px] xl:w-[260px] aspect-[4/3] sm:aspect-auto bg-slate-100 flex-shrink-0 relative overflow-hidden border-b sm:border-b-0 sm:border-r border-slate-100">
                        @if($other->poster)
                            <img src="{{ asset('storage/' . $other->poster) }}" alt="{{ $other->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 text-xs bg-slate-200">
                                Poster Belum Tersedia
                            </div>
                        @endif

                        <!-- Badge Overlay (Jika Berakhir/Habis) -->
                        @if($isOtherPast)
                            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] flex items-center justify-center z-10 pointer-events-none">
                                <span class="bg-slate-100 text-slate-600 px-4 py-1.5 rounded-full font-bold text-[10px] uppercase tracking-widest flex items-center shadow-lg border border-white/20">
                                    Telah Berakhir
                                </span>
                            </div>
                        @elseif($isOtherFull && !$isOtherRegistered)
                            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] flex items-center justify-center z-10 pointer-events-none">
                                <span class="bg-red-500 text-white px-4 py-1.5 rounded-full font-bold text-[10px] uppercase tracking-widest flex items-center shadow-lg border border-white/20">
                                    Kuota Habis
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Bagian Kanan: Informasi & Tombol -->
                    <div class="p-5 md:p-6 flex flex-col justify-between flex-grow">
                        
                        <!-- Header & Info Utama -->
                        <div>
                            <!-- Kategori & Tag Harga -->
                            <div class="flex flex-wrap gap-2 mb-3">
                                @if(is_array($other->kategori_peserta) && count($other->kategori_peserta) > 0)
                                    @foreach($other->kategori_peserta as $kat)
                                        <span class="px-2 py-0.5 rounded-full border border-emerald-200 text-emerald-600 bg-emerald-50 text-[10px] font-bold uppercase tracking-wider">{{ $kat }}</span>
                                    @endforeach
                                @else
                                    <span class="px-2 py-0.5 rounded-full border border-emerald-200 text-emerald-600 bg-emerald-50 text-[10px] font-bold uppercase tracking-wider">Umum</span>
                                @endif
                                
                                <span class="px-2 py-0.5 rounded-full border border-blue-200 text-[#1A68FF] bg-blue-50 text-[10px] font-bold uppercase tracking-wider whitespace-nowrap">
                                    {{ $other->harga_tiket == 0 ? 'FREE' : 'Rp ' . number_format($other->harga_tiket, 0, ',', '.') }}
                                </span>
                            </div>

                            <h4 class="text-lg md:text-xl font-bold text-slate-900 leading-snug mb-2 line-clamp-2" title="{{ $other->judul }}">
                                {{ $other->judul }}
                            </h4>
                            
                            <p class="text-xs text-slate-500 line-clamp-1 mb-4">
                                @php $otherKolabs = $other->getKolaboratorTerkait()->pluck('nama')->join(', '); @endphp
                                {{ $otherKolabs ?: 'Narasumber Umum' }}
                            </p>

                            <!-- Waktu & Lokasi -->
                            <div class="space-y-1.5 mb-5">
                                <div class="flex items-center gap-2 text-xs text-slate-700 font-medium">
                                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    {{ $other->jam_pelaksanaan ? \Carbon\Carbon::parse($other->jam_pelaksanaan)->format('H.i') : '-' }} WIB
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-700 font-medium">
                                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                    {{ $other->tanggal_pelaksanaan ? \Carbon\Carbon::parse($other->tanggal_pelaksanaan)->locale('id')->translatedFormat('d F Y') : 'Menyusul' }}
                                </div>
                            </div>
                        </div>

                        <!-- Baris Tombol Aksi Bawah (Logic Cerdas) -->
                        <div class="border-t border-slate-100 pt-4 mt-auto">
                            @if(!Auth::check())
                                <a href="{{ route('login') }}" class="block w-full text-center bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs md:text-sm transition-colors shadow-sm">
                                    Masuk untuk Daftar
                                </a>
                            @else
                                @if($tiketLain)
                                    @if($tiketLain->status === 'Menunggu Konfirmasi')
                                        <a href="{{ route('dashboard') }}" class="block w-full text-center bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-200 font-bold py-2.5 px-6 rounded-xl text-xs md:text-sm transition-colors shadow-sm">
                                            Menunggu Konfirmasi
                                        </a>
                                    @else
                                        <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-2 w-full bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border border-emerald-200 font-bold py-2.5 px-6 rounded-xl text-xs md:text-sm transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                            Tiket Terdaftar
                                        </a>
                                    @endif
                                @elseif($isOtherPast)
                                    <button disabled class="block w-full bg-slate-100 text-slate-400 font-bold py-2.5 px-6 rounded-xl text-xs md:text-sm cursor-not-allowed border border-slate-200">
                                        Telah Berakhir
                                    </button>
                                @elseif($isOtherFull)
                                    <button disabled class="block w-full bg-red-50 text-red-500 font-bold py-2.5 px-6 rounded-xl text-xs md:text-sm cursor-not-allowed border border-red-100">
                                        Kuota Habis
                                    </button>
                                @else
                                    <a href="{{ route('katalog.event.show', $other->slug) }}" class="block w-full text-center bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs md:text-sm transition-colors shadow-sm shadow-blue-500/20">
                                        Lihat Detail & Daftar
                                    </a>
                                @endif
                            @endif
                        </div>
                        
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
    @include('partials.footer')
</div>

<!-- ========================================== -->
<!-- MODAL FULLSCREEN POSTER (POPUP CENTER)     -->
<!-- ========================================== -->
<div id="posterModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-8 bg-slate-900/90 backdrop-blur-md transition-all duration-300 opacity-0 pointer-events-none">
    <div class="relative max-w-4xl w-full max-h-[90vh] flex flex-col items-center justify-center transform transition-all scale-95" id="posterModalContent">
        
        <!-- Tombol Close Modal Poster -->
        <button type="button" onclick="closePosterModal()" class="absolute -top-12 right-0 md:-right-12 text-slate-400 hover:text-white bg-slate-800 hover:bg-red-500 rounded-full p-2.5 transition-colors shadow-lg z-20 focus:outline-none">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        
        <!-- Gambar Poster Modal -->
        @if($event->poster)
        <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->judul }}" class="max-w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl relative z-10">
        @endif
        
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL PENDAFTARAN & PEMBAYARAN             -->
<!-- ========================================== -->
<div id="daftarModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-8 bg-slate-900/80 backdrop-blur-sm transition-all duration-300 opacity-0 pointer-events-none overflow-y-auto">
    <div class="bg-white rounded-[2rem] overflow-hidden w-full max-w-xl shadow-2xl transform transition-all scale-95 my-auto" id="daftarModalContent">

        <div class="bg-slate-50 border-b border-slate-100 px-8 py-5 flex justify-between items-center">
            <h3 class="text-lg font-black text-slate-900">Konfirmasi Pendaftaran</h3>
            <button type="button" onclick="closeDaftarModal()" class="text-slate-400 hover:text-red-500 bg-white border border-slate-200 p-2 rounded-full transition-colors"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg></button>
        </div>

        <form action="{{ route('katalog.event.daftar', $event->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf

            <p class="text-sm text-slate-600 mb-6">Anda akan mendaftar untuk acara <strong>{{ $event->judul }}</strong>.</p>

            @if($event->harga_tiket > 0)
            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 mb-6 text-center">
                <h4 class="text-indigo-900 font-bold mb-2">Total Pembayaran</h4>
                <span class="text-3xl font-black text-indigo-700 block mb-4">Rp {{ number_format($event->harga_tiket, 0, ',', '.') }}</span>

                <p class="text-xs text-indigo-600 mb-4">Silakan scan QRIS di bawah ini untuk melakukan pembayaran.</p>

                <!-- DUMMY QRIS -->
                <div class="w-48 h-48 mx-auto bg-white p-2 rounded-xl shadow-sm border border-indigo-100 mb-4">
                    <img src="{{ asset('images/qrpayment/qr.png') }}" alt="QRIS" class="w-full h-full object-contain" onerror="this.onerror=null;this.src='https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=QRIS_DUMMY_KMDGI16';">
                </div>

                <div class="text-left mt-6">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Upload Bukti Transfer <span class="text-red-500">*</span></label>

                    <!-- KOTAK PREVIEW GAMBAR -->
                    <div id="preview-container" class="hidden mb-4 relative rounded-xl border border-slate-200 overflow-hidden w-full max-w-[200px] bg-slate-100 shadow-inner group">
                        <img id="image-preview" src="" class="w-full object-contain" alt="Preview Bukti">
                        <!-- Tombol Hapus Preview -->
                        <button type="button" onclick="hapusPreview()" class="absolute top-2 right-2 bg-red-500/90 hover:bg-red-600 text-white rounded-full p-1.5 shadow-md transition-colors backdrop-blur-sm opacity-0 group-hover:opacity-100">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- INPUT FILE -->
                    <input type="file" id="file-upload" name="bukti_pembayaran" accept=".jpg,.jpeg,.png" required onchange="previewImage(event)" class="w-full text-sm text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200 cursor-pointer focus:outline-none transition-colors">
                    <p class="text-[10px] text-slate-500 mt-2 font-medium">Format: JPG/PNG. Maksimal ukuran file: 2MB.</p>
                </div>
            </div>
            @else
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 mb-6 flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-emerald-900 font-bold">Acara Ini Gratis</h4>
                    <p class="text-xs text-emerald-700 mt-0.5">Tiket Anda akan langsung aktif setelah menekan tombol konfirmasi.</p>
                </div>
            </div>
            @endif

            <div class="flex gap-3">
                <button type="button" onclick="closeDaftarModal()" class="w-1/3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3.5 rounded-xl transition-colors text-sm">Batal</button>
                <button type="submit" class="w-2/3 bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-md transition-colors text-sm">Konfirmasi Pendaftaran</button>
            </div>
        </form>
    </div>
</div>

<script>
    // FUNGSI COPY TO CLIPBOARD
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            const toast = document.getElementById('copy-toast');
            toast.classList.remove('opacity-0');
            setTimeout(() => {
                toast.classList.add('opacity-0');
            }, 2000);
        }).catch(err => {
            console.error('Gagal menyalin text: ', err);
        });
    }

    // FUNGSI TABS DESKRIPSI & KETENTUAN
    function switchTab(tabId) {
        const btnDeskripsi = document.getElementById('tab-deskripsi');
        const btnKetentuan = document.getElementById('tab-ketentuan');
        const contentDeskripsi = document.getElementById('content-deskripsi');
        const contentKetentuan = document.getElementById('content-ketentuan');

        if(tabId === 'deskripsi') {
            btnDeskripsi.classList.add('border-slate-900', 'text-slate-900');
            btnDeskripsi.classList.remove('border-transparent', 'text-slate-400');
            btnKetentuan.classList.add('border-transparent', 'text-slate-400');
            btnKetentuan.classList.remove('border-slate-900', 'text-slate-900');
            
            contentDeskripsi.classList.remove('hidden');
            contentDeskripsi.classList.add('block');
            contentKetentuan.classList.remove('block');
            contentKetentuan.classList.add('hidden');
        } else {
            btnKetentuan.classList.add('border-slate-900', 'text-slate-900');
            btnKetentuan.classList.remove('border-transparent', 'text-slate-400');
            btnDeskripsi.classList.add('border-transparent', 'text-slate-400');
            btnDeskripsi.classList.remove('border-slate-900', 'text-slate-900');
            
            contentKetentuan.classList.remove('hidden');
            contentKetentuan.classList.add('block');
            contentDeskripsi.classList.remove('block');
            contentDeskripsi.classList.add('hidden');
        }
    }

    // FUNGSI MODAL POSTER FULLSCREEN
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

    // Menutup modal poster jika mengeklik area background gelap
    document.getElementById('posterModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePosterModal();
        }
    });

    // FUNGSI MODAL DAFTAR
    function openDaftarModal() {
        const modal = document.getElementById('daftarModal');
        const content = document.getElementById('daftarModalContent');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-95');
    }
    function closeDaftarModal() {
        const modal = document.getElementById('daftarModal');
        const content = document.getElementById('daftarModalContent');
        modal.classList.add('opacity-0', 'pointer-events-none');
        content.classList.add('scale-95');
        // Reset form jika ditutup
        hapusPreview(); 
    }

    // FUNGSI PREVIEW GAMBAR BUKTI TRANSFER
    function previewImage(event) {
        const input = event.target;
        const container = document.getElementById('preview-container');
        const image = document.getElementById('image-preview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                image.src = e.target.result;
                container.classList.remove('hidden'); // Munculkan kotak preview
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // FUNGSI HAPUS PREVIEW & RESET INPUT
    function hapusPreview() {
        const input = document.getElementById('file-upload');
        const container = document.getElementById('preview-container');
        const image = document.getElementById('image-preview');

        if(input) input.value = ""; // Reset input file
        if(image) image.src = "";   // Kosongkan sumber gambar
        if(container) container.classList.add('hidden'); // Sembunyikan kotak
    }
</script>
@endsection