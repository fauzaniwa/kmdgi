@extends('layouts.app')

@section('title', 'Dashboard - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">
        
        @include('partials.sidebar')

        <main class="flex-grow space-y-8 w-full">
            
            <!-- ========================================== -->
            <!-- HEADER PROFILE SECTION                     -->
            <!-- ========================================== -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
                <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                    
                    <div class="w-16 h-16 rounded-full bg-slate-200 overflow-hidden border-2 border-white shadow-md flex-shrink-0">
                        @if(Auth::user()->profile_image)
                            <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Foto Profil {{ Auth::user()->name }}" class="w-full h-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=126CFD&color=fff" alt="Avatar" class="w-full h-full object-cover">
                        @endif
                    </div>
                    
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 leading-tight">{{ Auth::user()->name }}</h2>
                        <p class="text-xs text-slate-400 font-medium capitalize mt-0.5">
                            @if(Auth::user()->role === 'peserta')
                                {{ Auth::user()->kategori }} Peserta 
                                @if(Auth::user()->kategori === 'Delegasi')
                                    &bull; {{ Auth::user()->peran_delegasi }}
                                @endif
                            @else
                                {{ Auth::user()->role }}
                            @endif
                        </p>

                        <!-- TAMPILAN BADGE NAMA KAMPUS/INSTITUSI -->
                        @if(Auth::user()->institusi)
                            <div class="inline-flex items-center gap-1.5 mt-2 px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 text-[11px] font-bold tracking-wide">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.315 48.315 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                                </svg>
                                <span class="truncate max-w-[200px] md:max-w-md">{{ Auth::user()->institusi }}</span>
                            </div>
                        @endif

                    </div>
                </div>
                
                <a href="{{ route('profile.edit') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 border border-slate-200 hover:border-kmdgi-primary hover:text-kmdgi-primary text-slate-600 font-semibold px-4 py-2.5 rounded-xl text-sm transition-colors bg-white shadow-sm flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit Profile
                </a>
            </div>

            <!-- ========================================== -->
            <!-- BANNER KHUSUS KETUA DELEGASI (AUTH CODE)   -->
            <!-- ========================================== -->
            @if(Auth::user()->kategori === 'Delegasi' && Auth::user()->peran_delegasi === 'Ketua')
                <section class="bg-indigo-50 border border-indigo-200 rounded-[2rem] p-6 shadow-sm relative overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0 border border-indigo-200">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-indigo-900">Auth Code Tim Delegasi</h3>
                            <p class="text-sm text-indigo-700 mt-0.5 leading-relaxed">Bagikan kode unik ini kepada anggota tim Anda agar mereka dapat bergabung dengan delegasi <strong>{{ Auth::user()->institusi }}</strong>.</p>
                        </div>
                    </div>
                    
                    <div class="relative z-10 flex items-center justify-between gap-3 w-full md:w-auto bg-white p-2 rounded-xl border border-indigo-100 shadow-sm flex-shrink-0">
                        <code class="font-mono text-lg font-black text-indigo-600 px-3 tracking-widest" id="authCodeText">{{ Auth::user()->auth_code }}</code>
                        <button onclick="copyAuthCode()" class="bg-indigo-600 hover:bg-indigo-700 text-white p-2.5 rounded-lg transition-colors flex items-center justify-center" title="Salin Kode">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                </section>
            @endif

            <!-- ========================================== -->
            <!-- PERINGATAN LENGKAPI DATA ANGGOTA DELEGASI  -->
            <!-- ========================================== -->
            @if(Auth::user()->kategori === 'Delegasi' && Auth::user()->peran_delegasi === 'Anggota Delegasi')
                @if(empty(Auth::user()->institusi) || empty(Auth::user()->auth_code))
                    <section class="bg-blue-50 border border-blue-200 rounded-[2rem] p-6 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>
                        
                        <!-- Alert Pesan Error jika Auth Code Salah (Dikirim dari ProfileController) -->
                        @if($errors->has('auth_code'))
                            <div class="mb-4 bg-red-100 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm font-semibold relative z-10">
                                {{ $errors->first('auth_code') }}
                            </div>
                        @endif

                        <div class="flex flex-col md:flex-row items-start md:items-center gap-4 relative z-10">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0 border border-blue-200">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="flex-grow">
                                <h3 class="text-lg font-bold text-blue-900">Lengkapi Data Delegasi Anda</h3>
                                <p class="text-sm text-blue-700 mt-0.5 leading-relaxed">Anda belum terhubung dengan institusi/kampus. Silakan pilih kampus dan masukkan Auth Code yang diberikan oleh Ketua Delegasi Anda.</p>
                            </div>
                        </div>

                        <form action="{{ route('profile.update') }}" method="POST" class="mt-5 flex flex-col sm:flex-row gap-3 relative z-10">
                            @csrf
                            @method('PATCH')
                            
                            <div class="w-full sm:w-1/2">
                                <select name="institusi" required class="w-full px-4 py-3 rounded-xl border border-blue-200 text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#1A68FF]/30 focus:border-[#1A68FF] transition-all bg-white text-sm">
                                    <option value="">-- Pilih Institusi / Kampus --</option>
                                    @foreach(\App\Models\Kampus::orderBy('nama_institusi', 'asc')->get() as $kampus)
                                        <option value="{{ $kampus->nama_institusi }}">{{ $kampus->nama_institusi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full sm:w-1/2">
                                <input type="text" name="auth_code" required placeholder="Masukkan Auth Code..." class="w-full px-4 py-3 rounded-xl border border-blue-200 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1A68FF]/30 focus:border-[#1A68FF] transition-all bg-white text-sm">
                            </div>
                            <button type="submit" class="w-full sm:w-auto bg-[#1A68FF] hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl transition-colors shadow-sm whitespace-nowrap text-sm">
                                Verifikasi
                            </button>
                        </form>
                    </section>
                @endif
            @endif

            <!-- Notifikasi Pesan Sukses -->
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
            @endif

            <!-- ========================================== -->
            <!-- TIKET PAMERAN SECTION                      -->
            <!-- ========================================== -->
            <section class="space-y-4">
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Tiket Pameran</h3>
                <p class="text-xs text-slate-500 -mt-2">Gunakan tiket ini untuk masuk ke area pameran.</p>
                
                <div class="bg-yellow-50/60 border border-yellow-100 rounded-2xl p-4 flex gap-3 items-start text-xs text-amber-800 leading-relaxed">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p>Tunjukkan ke panitia di pintu dan naikkan kecerahan layar Anda.</p>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] flex flex-col md:flex-row items-center gap-6">
                    <div class="w-32 h-32 bg-slate-100 flex items-center justify-center rounded-2xl flex-shrink-0 p-2 border border-slate-200">
                        <!-- Nanti diganti dinamis dengan kode auth/tiket dari user -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=FD90184J9RC" alt="QR Code" class="w-full h-full object-contain">
                    </div>
                    <div class="flex-grow text-center md:text-left space-y-2 w-full">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Ticket Code</span>
                        <h4 class="text-xl font-black text-slate-900 tracking-tight">FD90184J9RC</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">*Siapkan QR Code Anda sebelum mendekati area pintu masuk.<br>*Satu QR Code hanya berlaku untuk 1 (satu) orang pengunjung yang terdaftar.</p>
                    </div>
                    <button class="w-full md:w-auto bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold px-5 py-3 rounded-xl text-xs flex items-center justify-center gap-2 transition-all shadow-md shadow-kmdgi-primary/10 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        Unduh Tiket
                    </button>
                </div>
            </section>

            <!-- ========================================== -->
            <!-- TIKET SEMINAR & WORKSHOP SECTION           -->
            <!-- ========================================== -->
            <section class="space-y-4">
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Tiket Seminar dan Workshop</h3>
                <p class="text-xs text-slate-500 -mt-2">Gunakan tiket ini untuk mengikuti sesi seminar dan workshop.</p>
                
                <div class="bg-yellow-50/60 border border-yellow-100 rounded-2xl p-4 flex gap-3 items-start text-xs text-amber-800 leading-relaxed">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p>Tunjukkan halaman ini kepada panitia sebelum memasuki ruangan sesi kegiatan.</p>
                </div>

                <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] flex flex-col md:flex-row gap-5 items-center">
                    <img src="https://via.placeholder.com/120x120/126CFD/FFFFFF?text=Shigeo+Fukuda" alt="Event Poster" class="w-full md:w-28 h-28 object-cover rounded-2xl flex-shrink-0">
                    <div class="flex-grow space-y-1.5 text-center md:text-left w-full">
                        <h4 class="font-bold text-slate-900 text-base leading-snug">The Art of Trickery: Mematahkan Logika Visual Bersama Shigeo Fukuda</h4>
                        <div class="flex flex-wrap justify-center md:justify-start gap-x-4 gap-y-1 text-slate-400 text-xs">
                            <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 13.30 - 15.30 WIB</span>
                            <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> 17 September 2026</span>
                        </div>
                        <p class="text-xs text-slate-500 flex items-center justify-center md:justify-start gap-1"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Ruang Smartclass, Gd. FPSD Baru Lt. 2, Universitas Pendidikan Indonesia</p>
                        <div class="text-[10px] text-slate-400 pt-1 font-semibold">Ticket Code: <span class="text-slate-700">FD90184J9RC</span> &bull; Milik: <span class="text-slate-700">{{ Auth::user()->name }}</span></div>
                    </div>
                    <div class="flex flex-col gap-2 w-full md:w-auto flex-shrink-0">
                        <button class="w-full md:w-auto bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold px-4 py-2.5 rounded-xl text-xs whitespace-nowrap transition-colors">Unduh Tiket</button>
                        <button class="w-full md:w-auto border border-slate-200 text-slate-600 font-semibold px-4 py-2.5 rounded-xl text-xs whitespace-nowrap bg-white hover:bg-slate-50 transition-colors">Detail Kegiatan</button>
                    </div>
                </div>
            </section>
        </main>
    </div>

    @include('partials.footer')
</div>

<script>
    // Fungsi untuk menyalin Auth Code ke clipboard pengguna
    function copyAuthCode() {
        const authCode = document.getElementById('authCodeText').innerText;
        navigator.clipboard.writeText(authCode).then(() => {
            alert('Kode tim ' + authCode + ' berhasil disalin! Silakan bagikan ke anggota tim delegasi Anda.');
        }).catch(err => {
            console.error('Gagal menyalin text: ', err);
            alert('Maaf, fitur salin gagal. Silakan salin secara manual.');
        });
    }
</script>
@endsection