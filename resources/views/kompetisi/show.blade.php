@extends('layouts.app')
@section('title', $lomba->judul . ' - KMDGI 16')

@section('meta')
    <meta property="og:title" content="{{ $lomba->judul }} - KMDGI 16">
    <meta property="og:description" content="{{ Str::limit(strip_tags($lomba->deskripsi), 150) }}">
    <meta property="og:image" content="{{ asset('storage/' . $lomba->poster) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
@endsection

@section('content')
<div class="bg-white min-h-screen pb-24">
    @include('partials.navbar')

    <!-- HEADER HERO SECTION -->
    <section class="bg-[#F4F7FF] py-12 md:py-20 rounded-b-[3rem] border-b border-blue-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col-reverse md:flex-row items-center gap-12">
            
            <div class="w-full md:w-1/2 flex flex-col justify-center">
                <nav class="text-xs font-bold text-slate-500 mb-6 flex gap-2">
                    <a href="/" class="hover:text-blue-600">Beranda</a> /
                    <a href="/#kompetisi" class="hover:text-blue-600">Kompetisi</a> /
                    <span class="text-slate-900 truncate">{{ $lomba->judul }}</span>
                </nav>

                <h1 class="text-3xl md:text-5xl font-black text-slate-900 leading-tight tracking-tight mb-4">
                    {{ $lomba->judul }}
                </h1>
                
                <div class="mb-6">
                    <span class="inline-block bg-white text-slate-800 font-black text-lg px-4 py-2 rounded-xl shadow-sm border border-slate-200 uppercase tracking-widest">
                        {{ $lomba->biaya_pendaftaran > 0 ? 'Rp ' . number_format($lomba->biaya_pendaftaran, 0, ',', '.') : 'FREE' }}
                    </span>
                </div>

                <div class="text-slate-600 leading-relaxed mb-8 text-sm md:text-base line-clamp-4">
                    {!! $lomba->deskripsi !!}
                </div>

                <div class="flex gap-4 items-center">
                    <a href="{{ route('login') }}" class="bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-3.5 px-8 rounded-xl transition-colors shadow-md shadow-blue-500/20 text-center">
                        Daftar Lomba
                    </a>
                    @if($lomba->file_guidebook)
                    <a href="{{ asset('storage/' . $lomba->file_guidebook) }}" target="_blank" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold py-3.5 px-6 rounded-xl transition-colors shadow-sm text-center flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                        Guidebook
                    </a>
                    @endif
                </div>
            </div>

            <div class="w-full md:w-1/2 flex justify-center md:justify-end">
                <div class="w-full max-w-sm aspect-[4/5] rounded-[2rem] overflow-hidden shadow-2xl shadow-blue-900/20 border-4 border-white transform rotate-2 hover:rotate-0 transition-transform duration-500">
                    <img src="{{ asset('storage/' . $lomba->poster) }}" alt="{{ $lomba->judul }}" class="w-full h-full object-cover">
                </div>
            </div>

        </div>
    </section>

    <!-- COUNTDOWN SECTION DYNAMIC -->
    <section class="max-w-3xl mx-auto px-4 -mt-10 relative z-20">
        <div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 p-8 text-center" id="countdown-wrapper">
            <h3 id="cd-title" class="text-lg font-black text-slate-900 mb-6 text-center line-clamp-1">Memuat Jadwal...</h3>
            <div class="flex justify-center gap-4 md:gap-8">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-blue-50 text-[#1A68FF] rounded-2xl flex items-center justify-center font-black text-2xl md:text-4xl shadow-inner mb-2" id="cd-days">00</div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Hari</span>
                </div>
                <div class="text-2xl md:text-4xl font-black text-slate-300 mt-3">:</div>
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-blue-50 text-[#1A68FF] rounded-2xl flex items-center justify-center font-black text-2xl md:text-4xl shadow-inner mb-2" id="cd-hours">00</div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Jam</span>
                </div>
                <div class="text-2xl md:text-4xl font-black text-slate-300 mt-3">:</div>
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-blue-50 text-[#1A68FF] rounded-2xl flex items-center justify-center font-black text-2xl md:text-4xl shadow-inner mb-2" id="cd-mins">00</div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Menit</span>
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-20 space-y-24">

        <!-- TIMELINE SECTION -->
        @if(is_array($timeline) && count($timeline) > 0)
        <section>
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-black text-slate-900">Jadwal Pelaksanaan</h2>
            </div>
            <div class="max-w-2xl mx-auto relative border-l-2 border-slate-200 ml-4 md:ml-auto">
                @foreach($timeline as $fase)
                <div class="mb-10 ml-8 relative">
                    <span class="absolute -left-[43px] top-1 w-5 h-5 bg-white border-4 border-slate-300 rounded-full flex items-center justify-center"></span>
                    <div class="flex flex-col md:flex-row md:items-start md:gap-8">
                        <div class="text-slate-500 font-bold text-sm w-32 flex-shrink-0 mt-0.5">
                            {{ date('d F Y', strtotime($fase['tanggal'])) }}
                        </div>
                        <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl flex-grow mt-2 md:mt-0 shadow-sm">
                            <h4 class="font-bold text-slate-900 text-[15px] mb-1">{{ $fase['head'] }}</h4>
                            <p class="text-sm text-slate-600">{{ $fase['deskripsi'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- JUARA & HADIAH SECTION -->
        @php 
            $hadiahs = is_string($lomba->hadiah) ? json_decode($lomba->hadiah, true) : ($lomba->hadiah ?? []); 
        @endphp
        @if(is_array($hadiahs) && count($hadiahs) > 0)
        <section>
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-black text-slate-900">Juara & Hadiah</h2>
            </div>
            
            <div class="flex flex-wrap justify-center gap-6 md:gap-8">
                @foreach($hadiahs as $index => $hadiah)
                <div class="w-full md:w-64 flex flex-col items-center {{ str_contains(strtolower($hadiah['peringkat']), '1') ? 'order-first md:order-none md:scale-110 z-10 mx-4' : '' }}">
                    <h4 class="font-black text-slate-900 mb-4">{{ $hadiah['peringkat'] }}</h4>
                    <div class="w-full aspect-[4/5] bg-amber-50 rounded-[2rem] border-2 border-amber-100 p-6 flex flex-col items-center justify-center shadow-lg mb-6 relative overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-white/90 z-10"></div>
                        @if(!empty($hadiah['icon']))
                        <img src="{{ asset('storage/' . $hadiah['icon']) }}" class="w-full h-full object-cover absolute inset-0 z-0 opacity-80 group-hover:scale-105 transition-transform duration-500">
                        @else
                        <svg class="w-20 h-20 text-amber-300 relative z-20 mb-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L3 7v10l9 5 9-5V7l-9-5z"/></svg>
                        @endif
                        
                        <div class="relative z-20 text-center mt-auto pb-2">
                            <h5 class="font-black text-slate-900 text-lg leading-tight">{{ $hadiah['keterangan'] }}</h5>
                        </div>
                    </div>
                    <div class="text-center text-sm font-bold text-slate-600 px-4">
                        {!! str_replace('+', '<br>+ ', e($hadiah['isi'])) !!}
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- SYARAT & KETENTUAN (TABS) -->
        <section>
            <div class="flex overflow-x-auto border-b border-slate-200 mb-8 custom-scrollbar hide-scroll-mobile">
                <button class="tab-btn active px-6 py-4 text-sm font-black whitespace-nowrap border-b-2 border-[#1A68FF] text-[#1A68FF]" onclick="openTab(event, 'tab-syarat')">Syarat Lomba</button>
                <button class="tab-btn px-6 py-4 text-sm font-bold whitespace-nowrap border-b-2 border-transparent text-slate-500 hover:text-slate-800" onclick="openTab(event, 'tab-ketentuan')">Ketentuan Lomba</button>
                <button class="tab-btn px-6 py-4 text-sm font-bold whitespace-nowrap border-b-2 border-transparent text-slate-500 hover:text-slate-800" onclick="openTab(event, 'tab-teknik')">Teknik Pelaksanaan</button>
                <button class="tab-btn px-6 py-4 text-sm font-bold whitespace-nowrap border-b-2 border-transparent text-slate-500 hover:text-slate-800" onclick="openTab(event, 'tab-khusus')">Ketentuan Khusus</button>
            </div>

            <div class="bg-slate-50 border border-slate-100 rounded-[2rem] p-6 md:p-10">
                <div id="tab-syarat" class="tab-content block">
                    <h3 class="text-lg font-black text-slate-900 mb-4">Syarat Peserta</h3>
                    <div class="text-sm text-slate-600 leading-relaxed quill-content">
                        {!! $lomba->syarat ?? '<p class="italic text-slate-400">Belum ada data persyaratan.</p>' !!}
                    </div>
                </div>
                <div id="tab-ketentuan" class="tab-content hidden">
                    <h3 class="text-lg font-black text-slate-900 mb-4">Ketentuan Orisinalitas & Lomba</h3>
                    <div class="text-sm text-slate-600 leading-relaxed quill-content">
                        {!! $lomba->ketentuan ?? '<p class="italic text-slate-400">Belum ada data ketentuan.</p>' !!}
                    </div>
                </div>
                <div id="tab-teknik" class="tab-content hidden">
                    <h3 class="text-lg font-black text-slate-900 mb-4">Teknik Pelaksanaan & Pengumpulan</h3>
                    <div class="text-sm text-slate-600 leading-relaxed quill-content">
                        {!! $lomba->teknik_pelaksanaan ?? '<p class="italic text-slate-400">Belum ada data teknik pelaksanaan.</p>' !!}
                    </div>
                </div>
                <div id="tab-khusus" class="tab-content hidden">
                    <h3 class="text-lg font-black text-slate-900 mb-4">Ketentuan Khusus Penilaian</h3>
                    <div class="text-sm text-slate-600 leading-relaxed quill-content">
                        {!! $lomba->ketentuan_khusus ?? '<p class="italic text-slate-400">Belum ada data ketentuan khusus.</p>' !!}
                    </div>
                </div>
            </div>
        </section>

        <!-- DEWAN JURI SECTION -->
        @if($dewanJuri && $dewanJuri->count() > 0)
        <section>
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-black text-slate-900">Dewan Juri</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($dewanJuri as $juri)
                <div class="bg-slate-900 rounded-[2rem] overflow-hidden relative group aspect-[3/4]">
                    @if($juri->foto)
                    <img src="{{ asset('storage/' . $juri->foto) }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-all duration-500 group-hover:scale-105">
                    @else
                    <div class="w-full h-full bg-slate-800"></div>
                    @endif
                    
                    <div class="absolute inset-0 pointer-events-none opacity-50 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48cGF0aCBkPSJNMCA1MCBDIDI1IDAsIDc1IDEwMCwgMTAwIDUwIiBzdHJva2U9IiNGRjZCOUUiIHN0cm9rZS13aWR0aD0iMiIgZmlsbD0ibm9uZSIvPjwvc3ZnPg==')] bg-no-repeat bg-center bg-cover"></div>
                    
                    <div class="absolute bottom-0 inset-x-0 bg-[#1A68FF] p-4 text-center transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <h4 class="text-white font-black text-sm line-clamp-1">{{ $juri->nama }}</h4>
                        <p class="text-blue-200 text-[10px] font-medium truncate">{{ $juri->peran_kolaborasi }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

    </div>
</div>

@include('partials.footer')

<style>
    .quill-content ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
    .quill-content ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem; }
    .quill-content p { margin-bottom: 0.75rem; }
    .quill-content h1, .quill-content h2, .quill-content h3 { font-weight: 900; color: #0f172a; margin-top: 1.5rem; margin-bottom: 0.5rem; }
    .hide-scroll-mobile::-webkit-scrollbar { display: none; }
</style>

<script>
    // --- DYNAMIC CASCADING COUNTDOWN LOGIC ---
    const timelineData = @json($timeline);
    let intervalTimer;

    function startDynamicCountdown() {
        if (!timelineData || timelineData.length === 0) {
            document.getElementById('countdown-wrapper').classList.add('hidden');
            return;
        }

        // Urutkan timeline berdasarkan tanggal dari yang paling awal
        timelineData.sort((a, b) => new Date(a.tanggal) - new Date(b.tanggal));

        intervalTimer = setInterval(function() {
            const now = new Date().getTime();
            let targetPhase = null;

            // Cari fase timeline yang masa berlakunya masih di masa depan
            // (Kita asumsikan tenggat waktu setiap fase adalah pukul 23:59:59 WIB / GMT+7)
            for (let i = 0; i < timelineData.length; i++) {
                let phaseTime = new Date(timelineData[i].tanggal + "T23:59:59+07:00").getTime(); 
                
                if (phaseTime > now) {
                    targetPhase = {
                        title: timelineData[i].head,
                        time: phaseTime
                    };
                    break;
                }
            }

            // Jika semua tanggal telah lewat
            if (!targetPhase) {
                clearInterval(intervalTimer);
                document.getElementById("cd-title").innerHTML = "Rangkaian Lomba Telah Selesai";
                document.getElementById("cd-days").innerHTML = "00";
                document.getElementById("cd-hours").innerHTML = "00";
                document.getElementById("cd-mins").innerHTML = "00";
                return;
            }

            // Update Label Judul ke target terdekat
            document.getElementById("cd-title").innerHTML = "Menuju: " + targetPhase.title;

            // Kalkulasi Jarak Waktu
            const distance = targetPhase.time - now;
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

            // Tempel ke HTML
            document.getElementById("cd-days").innerHTML = days < 10 ? '0' + days : days;
            document.getElementById("cd-hours").innerHTML = hours < 10 ? '0' + hours : hours;
            document.getElementById("cd-mins").innerHTML = minutes < 10 ? '0' + minutes : minutes;

        }, 1000);
    }

    // Jalankan timer saat halaman dimuat
    startDynamicCountdown();

    // --- TAB LOGIC ---
    function openTab(evt, tabName) {
        let i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tab-btn");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" font-black border-[#1A68FF] text-[#1A68FF]", " font-bold border-transparent text-slate-500 hover:text-slate-800");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className = evt.currentTarget.className.replace(" font-bold border-transparent text-slate-500 hover:text-slate-800", " font-black border-[#1A68FF] text-[#1A68FF]");
    }
</script>
@endsection