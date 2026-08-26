@extends('layouts.app')

@section('title', 'Berkas Delegasi - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col relative">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">
        
        <!-- SIDEBAR -->
        <aside class="hidden lg:flex flex-col w-full lg:w-[260px] flex-shrink-0 bg-white">
            <div class="flex gap-2 mb-6 px-2">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-kmdgi-primary bg-white px-3 py-2 rounded-xl border border-slate-100 shadow-sm transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg> Kembali
                </a>
            </div>

            <div class="px-2 space-y-1 font-sans">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 py-3 px-4 rounded-2xl transition-all duration-200 text-slate-800 hover:text-kmdgi-primary">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
                    <span class="font-medium text-[15px]">Dashboard</span>
                </a>

                @if(Auth::user()->kategori === 'Delegasi')
                <div class="w-full">
                    <button class="w-full flex items-center justify-between py-3 px-4 {{ Route::is('delegasi.*') ? 'text-kmdgi-primary' : 'text-slate-800' }} hover:text-kmdgi-primary transition-all dropdown-btn focus:outline-none">
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" /></svg>
                            <span class="font-medium text-[15px]">Delegasi</span>
                        </div>
                        <svg class="w-5 h-5 transition-transform duration-200 arrow {{ Route::is('delegasi.*') ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    <div class="pl-[3.5rem] pr-4 py-1 flex flex-col space-y-1 {{ Route::is('delegasi.*') ? '' : 'hidden' }} submenu-list">
                        <a href="{{ route('delegasi.status') }}" class="block px-4 py-2.5 text-sm font-medium {{ Route::is('delegasi.status') ? 'bg-kmdgi-primary text-white rounded-xl text-center shadow-sm' : 'text-slate-600 hover:text-kmdgi-primary text-left' }} transition-colors">Status</a>
                        
                        @if(Auth::user()->peran_delegasi === 'Ketua')
                            <a href="{{ route('delegasi.tim') }}" class="block px-4 py-2.5 text-sm font-medium {{ Route::is('delegasi.tim') ? 'bg-kmdgi-primary text-white rounded-xl text-center shadow-sm' : 'text-slate-600 hover:text-kmdgi-primary text-left' }} transition-colors">Manage Tim</a>
                        @endif
                        
                        <!-- Rute Berkas Aktif -->
                        <a href="{{ route('delegasi.berkas') }}" class="block px-4 py-2.5 text-sm font-medium {{ Route::is('delegasi.berkas') ? 'bg-kmdgi-primary text-white rounded-xl text-center shadow-sm' : 'text-slate-600 hover:text-kmdgi-primary text-left' }} transition-colors">Berkas</a>
                    </div>
                </div>
                @endif
                <!-- ... sisa menu ... -->
            </div>
        </aside>

        <!-- KONTEN UTAMA: BERKAS TIM -->
        <main class="flex-grow space-y-6 w-full relative z-10">
            
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Berkas Pendaftaran</h1>
                <p class="text-sm text-slate-500 mt-1">Satu ruang kerja tim. Berkas yang diunggah akan otomatis terlihat oleh seluruh anggota <span class="font-bold text-slate-700">{{ $user->institusi }}</span>.</p>
            </div>

            <!-- Notifikasi Pesan Sukses -->
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-2xl text-sm font-medium shadow-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <!-- Banner Deadline -->
            <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-orange-900 leading-tight">Deadline Pengumpulan Berkas</h4>
                        <p class="text-sm text-orange-700 mt-0.5">Segera unggah berkas Anda sebelum waktu berakhir.</p>
                    </div>
                </div>
                <div class="bg-white px-4 py-2 rounded-xl border border-orange-200 shadow-sm text-center flex-shrink-0 w-full md:w-auto">
                    <span class="block text-[10px] font-bold text-orange-400 uppercase tracking-wider">Batas Waktu</span>
                    <span class="block text-sm font-black text-orange-600">{{ $deadline->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>
            </div>

            <!-- GRID BERKAS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- 1. BUKTI PEMBAYARAN -->
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] flex flex-col h-full">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Bukti Pembayaran</h3>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Struk/Resi transfer pendaftaran delegasi (Format: JPG/PNG/PDF, Max 5MB).</p>
                        </div>
                    </div>
                    
                    <div class="mt-auto">
                        @if($berkas->bukti_pembayaran)
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded-md">Telah Diunggah</span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ asset('storage/' . $berkas->bukti_pembayaran) }}" target="_blank" class="w-1/2 flex items-center justify-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 rounded-xl text-xs transition-colors"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg> Lihat</a>
                                <button onclick="document.getElementById('upload_pembayaran').click()" class="w-1/2 flex items-center justify-center gap-1.5 border border-slate-200 hover:border-blue-500 hover:text-blue-600 text-slate-600 font-semibold py-2.5 rounded-xl text-xs transition-colors">Ganti File</button>
                            </div>
                        @else
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-2.5 py-1 bg-red-100 text-red-700 text-[10px] font-bold uppercase tracking-wider rounded-md">Belum Diunggah</span>
                            </div>
                            <button onclick="document.getElementById('upload_pembayaran').click()" class="w-full flex items-center justify-center gap-2 bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                Unggah Berkas
                            </button>
                        @endif
                        
                        <form action="{{ route('delegasi.berkas.upload') }}" method="POST" enctype="multipart/form-data" class="hidden" id="form_pembayaran">
                            @csrf
                            <input type="file" id="upload_pembayaran" name="bukti_pembayaran" accept=".jpg,.jpeg,.png,.pdf" onchange="document.getElementById('form_pembayaran').submit()">
                        </form>
                    </div>
                </div>

                <!-- 2. FORMULIR PENDAFTARAN -->
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] flex flex-col h-full">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Formulir Pendaftaran</h3>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Formulir resmi bertanda tangan Ketua Delegasi (Format: PDF, Max 5MB).</p>
                        </div>
                    </div>
                    
                    <div class="mt-auto">
                        @if($berkas->formulir_pendaftaran)
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded-md">Telah Diunggah</span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ asset('storage/' . $berkas->formulir_pendaftaran) }}" target="_blank" class="w-1/2 flex items-center justify-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 rounded-xl text-xs transition-colors"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg> Lihat</a>
                                <button onclick="document.getElementById('upload_formulir').click()" class="w-1/2 flex items-center justify-center gap-1.5 border border-slate-200 hover:border-purple-500 hover:text-purple-600 text-slate-600 font-semibold py-2.5 rounded-xl text-xs transition-colors">Ganti File</button>
                            </div>
                        @else
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-2.5 py-1 bg-red-100 text-red-700 text-[10px] font-bold uppercase tracking-wider rounded-md">Belum Diunggah</span>
                            </div>
                            <button onclick="document.getElementById('upload_formulir').click()" class="w-full flex items-center justify-center gap-2 bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                Unggah Berkas
                            </button>
                        @endif
                        
                        <form action="{{ route('delegasi.berkas.upload') }}" method="POST" enctype="multipart/form-data" class="hidden" id="form_formulir">
                            @csrf
                            <input type="file" id="upload_formulir" name="formulir_pendaftaran" accept=".pdf" onchange="document.getElementById('form_formulir').submit()">
                        </form>
                    </div>
                </div>

                <!-- 3. KWITANSI PENDAFTARAN (DARI ADMIN) -->
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] flex flex-col h-full relative overflow-hidden group">
                    <div class="flex items-start gap-4 mb-4 relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Kwitansi & Verifikasi</h3>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Bukti sah pendaftaran yang dikeluarkan oleh sistem Admin KMDGI.</p>
                        </div>
                    </div>
                    
                    <div class="mt-auto relative z-10">
                        @if($berkas->kwitansi)
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded-md">Tersedia</span>
                            </div>
                            <a href="{{ asset('storage/' . $berkas->kwitansi) }}" download class="w-full flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 rounded-xl text-sm transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                Unduh Kwitansi
                            </a>
                        @else
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider rounded-md">Belum Tersedia</span>
                            </div>
                            <button disabled class="w-full flex items-center justify-center gap-2 bg-slate-100 text-slate-400 font-bold py-3 rounded-xl text-sm cursor-not-allowed">
                                Menunggu Verifikasi Admin
                            </button>
                        @endif
                    </div>
                </div>

                <!-- 4. PANDUAN DELEGASI -->
                <div class="bg-slate-900 p-6 rounded-[2rem] shadow-xl flex flex-col h-full relative overflow-hidden">
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                    
                    <div class="flex items-start gap-4 mb-4 relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-white/10 text-white flex items-center justify-center flex-shrink-0 backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Buku Panduan Delegasi</h3>
                            <p class="text-[11px] text-slate-400 mt-0.5 leading-relaxed">Pahami seluruh aturan, timeline, dan syarat berkas yang harus diunggah.</p>
                        </div>
                    </div>
                    
                    <div class="mt-auto relative z-10">
                        <!-- Nanti href ini diganti dengan link file panduan yang dikelola Admin -->
                        <a href="#" target="_blank" class="w-full flex items-center justify-center gap-2 bg-white hover:bg-slate-100 text-slate-900 font-bold py-3 rounded-xl text-sm transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                            Baca Panduan
                        </a>
                    </div>
                </div>

            </div>

        </main>
    </div>

    @include('partials.footer')
</div>

<script>
    document.querySelectorAll('.dropdown-btn').forEach(button => {
        button.addEventListener('click', () => {
            const submenu = button.nextElementSibling;
            const arrow = button.querySelector('.arrow');
            submenu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        });
    });
</script>
@endsection