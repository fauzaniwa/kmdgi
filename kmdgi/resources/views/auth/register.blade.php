@extends('layouts.app')

@section('title', 'Daftar Akun - KMDGI 16')

@section('content')
<div class="bg-white flex flex-col lg:flex-row min-h-screen relative">

    <div class="hidden lg:flex flex-col w-[320px] bg-[#F8FAFC] border-r border-slate-100 p-8 flex-shrink-0 min-h-screen sticky top-0">
        <a href="{{ url('/') }}" class="mb-12">
            <img src="{{ asset('images/logo-desktop.png') }}" alt="Logo KMDGI 16" class="h-10 w-auto object-contain">
        </a>

        <div class="flex-grow flex flex-col space-y-8">
            <div id="nav-step-1" class="flex gap-4 items-start transition-opacity duration-300 opacity-100">
                <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-kmdgi-primary shadow-sm flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Kategori Peserta</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pilih jenis kepesertaan</p>
                </div>
            </div>

            <div id="nav-step-2" class="flex gap-4 items-start transition-opacity duration-300 opacity-40">
                <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 shadow-sm flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Detail Delegasi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Ketua atau anggota</p>
                </div>
            </div>

            <div id="nav-step-3" class="flex gap-4 items-start transition-opacity duration-300 opacity-40">
                <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 shadow-sm flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Informasi Pribadi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Profil dan detail kontak</p>
                </div>
            </div>

            <div id="nav-step-4" class="flex gap-4 items-start transition-opacity duration-300 opacity-40">
                <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 shadow-sm flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Buat Kata Sandi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Amankan akun Anda</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between text-xs text-slate-500 pt-6">
            <span>&copy; 2026 KMDGI 16</span>
            <div class="flex items-center gap-3">
                <a href="#" class="hover:text-kmdgi-primary transition-colors">IG</a>
                <a href="#" class="hover:text-kmdgi-primary transition-colors">TK</a>
            </div>
        </div>
    </div>

    <div class="flex-grow flex flex-col p-6 sm:p-10 md:p-16 relative w-full h-full min-h-screen">

        <div class="flex items-center justify-center lg:hidden mb-8">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo-desktop.png') }}" alt="Logo KMDGI 16" class="h-8 w-auto object-contain">
            </a>
        </div>

        <div class="w-full max-w-md mx-auto flex-grow flex flex-col justify-center pb-20 lg:pb-0">

            <form id="registerForm" method="POST" action="{{ route('register-proses') }}" enctype="multipart/form-data">
                @csrf

                <!-- STEP 1: Kategori -->
                <div id="step-1" class="form-step transition-all duration-300">
                    <div class="text-center mb-8">
                        <div class="inline-flex w-12 h-12 rounded-xl bg-white border border-slate-200 items-center justify-center text-kmdgi-primary shadow-sm mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-2">Kategori Peserta</h2>
                        <p class="text-sm text-slate-500">Pilih jenis kepesertaan untuk memulai pendaftaran</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="kategori" class="block text-sm font-semibold text-slate-800 mb-2">Pilih kategori mendaftar<span class="text-red-500">*</span></label>
                            <select id="kategori" name="kategori" required class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all appearance-none bg-white">
                                <option value="Delegasi">Delegasi</option>
                                <option value="Umum">Umum</option>
                            </select>
                        </div>
                        <button type="button" onclick="nextStep()" class="w-full bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-4 rounded-xl transition-colors shadow-sm mt-4">
                            Selanjutnya
                        </button>
                    </div>
                </div>

                <!-- STEP 2: Detail Delegasi -->
                <div id="step-2" class="form-step hidden transition-all duration-300">
                    <div class="text-center mb-8">
                        <div class="inline-flex w-12 h-12 rounded-xl bg-white border border-slate-200 items-center justify-center text-kmdgi-primary shadow-sm mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-2">Detail Delegasi</h2>
                        <p class="text-sm text-slate-500">Lengkapi informasi delegasi kampus Anda</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="peran_delegasi" class="block text-sm font-semibold text-slate-800 mb-2">Peran Delegasi<span class="text-red-500">*</span></label>
                            <select id="peran_delegasi" name="peran_delegasi" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all appearance-none bg-white">
                                <option value="Anggota Delegasi">Anggota Delegasi</option>
                                <option value="Ketua">Ketua</option>
                            </select>
                        </div>

                        <div class="relative">
                            <label for="institusi" class="block text-sm font-semibold text-slate-800 mb-2">Institusi / Kampus<span class="text-red-500">*</span></label>

                            <div class="relative" id="custom-select-wrapper">
                                <input type="hidden" name="institusi" id="institusi">
                                <button type="button" id="custom-select-button" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all bg-white flex justify-between items-center text-left">
                                    <span id="custom-select-text" class="text-slate-500 truncate pr-4">Pilih Kampus...</span>
                                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="custom-select-dropdown" class="absolute z-10 w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-xl hidden flex-col max-h-80 overflow-hidden transform opacity-0 scale-95 transition-all duration-200">
                                    <div class="p-3 border-b border-slate-100 bg-slate-50/50">
                                        <div class="relative">
                                            <svg class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 1116.65 1.65a7.5 7.5 0 010 15z" />
                                            </svg>
                                            <input type="text" id="custom-select-search" placeholder="Ketik nama kampus..." class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-kmdgi-primary focus:ring-1 focus:ring-kmdgi-primary transition-all">
                                        </div>
                                    </div>
                                    <ul id="custom-select-options" class="overflow-y-auto flex-1 p-2 space-y-0.5 custom-scrollbar">
                                        @foreach($dataKampus ?? [] as $kampus)
                                        <li data-value="{{ $kampus->nama_institusi }}" class="select-option px-3 py-2.5 rounded-lg text-sm text-slate-700 hover:bg-blue-50 hover:text-kmdgi-primary cursor-pointer transition-colors font-medium">
                                            {{ $kampus->nama_institusi }}
                                        </li>
                                        @endforeach
                                    </ul>
                                    <div id="custom-select-empty" class="hidden p-6 text-center">
                                        <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" />
                                        </svg>
                                        <p class="text-sm text-slate-400">Kampus tidak ditemukan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="auth_code_div">
                            <label for="auth_code" class="block text-sm font-semibold text-slate-800 mb-2">Auth Code<span class="text-red-500">*</span></label>
                            <input type="text" id="auth_code" name="auth_code" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all" placeholder="Masukkan kode dari ketua delegasi...">
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="button" onclick="prevStep()" class="w-1/3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-4 rounded-xl transition-colors">
                                Kembali
                            </button>
                            <button type="button" onclick="nextStep()" class="w-2/3 bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-4 rounded-xl transition-colors shadow-sm">
                                Selanjutnya
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: Informasi Pribadi -->
                <div id="step-3" class="form-step hidden transition-all duration-300">
                    <div class="text-center mb-6">
                        <div class="inline-flex w-12 h-12 rounded-xl bg-white border border-slate-200 items-center justify-center text-kmdgi-primary shadow-sm mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-2">Informasi Pribadi</h2>
                        <p class="text-sm text-slate-500">Lengkapi data diri Anda di bawah ini</p>
                    </div>

                    <!-- Upload Foto Profil (Opsional) -->
                    <div class="flex flex-col items-center sm:items-start sm:flex-row gap-5 mb-5">
                        <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-full border-2 border-dashed border-slate-300 hover:border-kmdgi-primary bg-slate-50 flex flex-shrink-0 items-center justify-center overflow-hidden group cursor-pointer transition-colors" onclick="document.getElementById('input-profile').click()">
                            <div id="profile-placeholder" class="flex flex-col items-center pointer-events-none relative z-10 transition-opacity">
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-kmdgi-primary transition-colors flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                </svg>
                            </div>
                            <img id="profile-preview" src="" class="hidden absolute inset-0 w-full h-full object-cover z-20" />
                        </div>
                        <input type="file" name="profile_image" id="input-profile" accept="image/jpeg,image/png,image/jpg" class="hidden" onchange="previewProfileImage(this)">
                        <div class="text-center sm:text-left pt-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Foto Profil (Opsional)</label>
                            <p class="text-[11px] text-slate-400">Format JPG/PNG max 2MB.<br>Saran: 1:1 (Persegi).</p>
                            <button type="button" id="btn-remove-profile" class="hidden mt-1.5 text-[11px] font-bold text-red-500 hover:text-red-600 transition-colors" onclick="removeProfilePreview()">Hapus Gambar</button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="nama" class="block text-sm font-semibold text-slate-800 mb-1.5">Nama Lengkap<span class="text-red-500">*</span></label>
                            <input type="text" id="nama" name="nama" required class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all" placeholder="Masukkan nama sesuai identitas">
                        </div>

                        <!-- Field Profesi Khusus UMUM -->
                        <div id="profesi_div" class="hidden">
                            <label for="profesi" class="block text-sm font-semibold text-slate-800 mb-1.5">Profesi / Pekerjaan Saat Ini<span class="text-red-500">*</span></label>
                            <input type="text" id="profesi" name="profesi" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all" placeholder="Misal: Mahasiswa ITB, Freelance Designer">
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-semibold text-slate-800 mb-1.5">Tanggal Lahir<span class="text-red-500">*</span></label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" required class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all">
                        </div>

                        <div>
                            <label for="no_hp" class="block text-sm font-semibold text-slate-800 mb-1.5">No. Handphone/Whatsapp<span class="text-red-500">*</span></label>
                            <input type="tel" id="no_hp" name="no_hp" required class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all" placeholder="08xxxxxxxxxx">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-800 mb-1.5">Email<span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" required class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all" placeholder="nama@email.com">
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="button" onclick="prevStep()" class="w-1/3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-4 rounded-xl transition-colors">
                                Kembali
                            </button>
                            <button type="button" onclick="nextStep()" class="w-2/3 bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-4 rounded-xl transition-colors shadow-sm">
                                Selanjutnya
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 4: Buat Kata Sandi -->
                <div id="step-4" class="form-step hidden transition-all duration-300">
                    <div class="text-center mb-8">
                        <div class="inline-flex w-12 h-12 rounded-xl bg-white border border-slate-200 items-center justify-center text-kmdgi-primary shadow-sm mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-2">Buat Kata Sandi</h2>
                        <p class="text-sm text-slate-500">Gunakan kata sandi yang kuat dan mudah diingat</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-800 mb-1.5">Kata Sandi<span class="text-red-500">*</span></label>
                            <input type="password" id="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all" placeholder="Minimal 8 karakter">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-800 mb-1.5">Konfirmasi Kata Sandi<span class="text-red-500">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all" placeholder="Ulangi kata sandi">
                        </div>

                        <div class="pt-2">
                            <label class="flex items-start cursor-pointer">
                                <input type="checkbox" required class="mt-1 w-4 h-4 rounded border-slate-300 text-kmdgi-primary focus:ring-kmdgi-primary/30 flex-shrink-0">
                                <span class="ml-2 text-xs text-slate-500 leading-relaxed">
                                    Dengan melanjutkan, kamu menyetujui <a href="#" class="text-kmdgi-primary underline font-medium">Syarat dan Ketentuan</a> serta <a href="#" class="text-kmdgi-primary underline font-medium">Kebijakan Privasi</a> kami.
                                </span>
                            </label>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="button" onclick="prevStep()" class="w-1/3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-4 rounded-xl transition-colors">
                                Kembali
                            </button>
                            <button type="submit" class="w-2/3 bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-4 rounded-xl transition-colors shadow-sm">
                                Daftar Sekarang
                            </button>
                        </div>
                    </div>
                </div>

            </form>

            <div class="flex lg:hidden justify-center items-center gap-2 mt-10" id="mobile-dots">
                <span class="dot w-2 h-2 rounded-full bg-kmdgi-primary transition-colors"></span>
                <span class="dot w-2 h-2 rounded-full bg-slate-200 transition-colors"></span>
                <span class="dot w-2 h-2 rounded-full bg-slate-200 transition-colors"></span>
                <span class="dot w-2 h-2 rounded-full bg-slate-200 transition-colors"></span>
            </div>

            <p class="text-center text-sm text-slate-500 mt-8">
                Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-kmdgi-primary hover:underline">Masuk di sini</a>
            </p>

        </div>

        <div class="lg:hidden flex items-center justify-between text-xs text-slate-500 pt-6 border-t border-slate-100 absolute bottom-6 left-6 right-6">
            <span>&copy; 2026 KMDGI 16</span>
            <div class="flex items-center gap-3">
                <a href="#" class="hover:text-kmdgi-primary transition-colors">IG</a>
                <a href="#" class="hover:text-kmdgi-primary transition-colors">TK</a>
            </div>
        </div>

    </div>
</div>

<style>
    /* Styling agar scrollbar pada dropdown terlihat elegan */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
</style>

<script>
    let currentStep = 1;
    const totalSteps = 4;

    function updateUI() {
        const kategori = document.getElementById('kategori').value;
        const isUmum = (kategori === 'Umum');

        // Logika Input Khusus (Munculkan Profesi jika UMUM)
        const profesiDiv = document.getElementById('profesi_div');
        const profesiInput = document.getElementById('profesi');
        if (isUmum) {
            profesiDiv.classList.remove('hidden');
            profesiInput.setAttribute('required', 'required');
            
            // Hapus req dropdown jika Umum
            document.getElementById('institusi').removeAttribute('required');
        } else {
            profesiDiv.classList.add('hidden');
            profesiInput.removeAttribute('required');
            
            // Aktifkan kembali req dropdown jika Delegasi
            document.getElementById('institusi').setAttribute('required', 'required');
        }

        // 1. Sembunyikan semua step form
        document.querySelectorAll('.form-step').forEach(el => el.classList.add('hidden'));

        // 2. Tampilkan step yang aktif
        document.getElementById(`step-${currentStep}`).classList.remove('hidden');

        // 3. Update warna Sidebar (Desktop)
        for (let i = 1; i <= totalSteps; i++) {
            const nav = document.getElementById(`nav-step-${i}`);
            const icon = nav.querySelector('div.w-10');

            nav.classList.remove('opacity-100');
            nav.classList.add('opacity-40');
            icon.classList.remove('text-kmdgi-primary');
            icon.classList.add('text-slate-400');

            if (i === currentStep) {
                nav.classList.remove('opacity-40');
                nav.classList.add('opacity-100');
                icon.classList.remove('text-slate-400');
                icon.classList.add('text-kmdgi-primary');
            }

            if (i === 2) {
                if (isUmum && currentStep > 1) {
                    nav.style.display = 'none'; 
                } else {
                    nav.style.display = 'flex';
                }
            }
        }

        // 4. Update Dots (Mobile)
        const dots = document.querySelectorAll('.dot');
        dots.forEach((dot, index) => {
            let actualStep = index + 1;
            dot.classList.remove('bg-kmdgi-primary');
            dot.classList.add('bg-slate-200');
            dot.style.display = 'block';

            if (isUmum && actualStep === 2) {
                dot.style.display = 'none';
            }

            if (actualStep === currentStep) {
                dot.classList.remove('bg-slate-200');
                dot.classList.add('bg-kmdgi-primary');
            }
        });
    }

    function nextStep() {
        const kategori = document.getElementById('kategori').value;

        if (currentStep === 1) {
            currentStep = (kategori === 'Umum') ? 3 : 2;
        } else if (currentStep < totalSteps) {
            currentStep++;
        }

        updateUI();
        window.scrollTo({ top: 0, behavior: 'smooth' }); 
    }

    function prevStep() {
        const kategori = document.getElementById('kategori').value;

        if (currentStep === 3) {
            currentStep = (kategori === 'Umum') ? 1 : 2;
        } else if (currentStep > 1) {
            currentStep--;
        }

        updateUI();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Fungsi Preview Foto Profil
    function previewProfileImage(input) {
        const preview = document.getElementById('profile-preview');
        const placeholder = document.getElementById('profile-placeholder');
        const removeBtn = document.getElementById('btn-remove-profile');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('opacity-0');
                removeBtn.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeProfilePreview() {
        const input = document.getElementById('input-profile');
        const preview = document.getElementById('profile-preview');
        const placeholder = document.getElementById('profile-placeholder');
        const removeBtn = document.getElementById('btn-remove-profile');

        input.value = ""; 
        preview.src = "";
        preview.classList.add('hidden');
        placeholder.classList.remove('opacity-0');
        removeBtn.classList.add('hidden');
    }

    // Event Listener untuk Auth Code based on Peran Delegasi
    document.getElementById('peran_delegasi').addEventListener('change', function(e) {
        const authDiv = document.getElementById('auth_code_div');
        const authInput = document.getElementById('auth_code');

        if (e.target.value === 'Anggota Delegasi') {
            authDiv.classList.remove('hidden');
            authInput.setAttribute('required', 'required');
        } else {
            authDiv.classList.add('hidden');
            authInput.removeAttribute('required');
            authInput.value = ''; 
        }
    });

    // Custom Select / Dropdown Pencarian JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('custom-select-wrapper');
        const button = document.getElementById('custom-select-button');
        const buttonText = document.getElementById('custom-select-text');
        const dropdown = document.getElementById('custom-select-dropdown');
        const searchInput = document.getElementById('custom-select-search');
        const optionsList = document.getElementById('custom-select-options');
        const options = optionsList.querySelectorAll('.select-option');
        const hiddenInput = document.getElementById('institusi');
        const emptyState = document.getElementById('custom-select-empty');

        function toggleDropdown(forceClose = false) {
            if (forceClose || !dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('opacity-100', 'scale-100');
                dropdown.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                    dropdown.classList.remove('flex');
                }, 200); 
            } else {
                dropdown.classList.remove('hidden');
                dropdown.classList.add('flex');
                setTimeout(() => {
                    dropdown.classList.remove('opacity-0', 'scale-95');
                    dropdown.classList.add('opacity-100', 'scale-100');
                    searchInput.focus(); 
                }, 10);
            }
        }

        button.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleDropdown();
        });

        searchInput.addEventListener('input', function() {
            const filter = searchInput.value.toLowerCase();
            let hasVisibleOptions = false;

            options.forEach(option => {
                const text = option.textContent.toLowerCase();
                if (text.includes(filter)) {
                    option.style.display = 'block';
                    hasVisibleOptions = true;
                } else {
                    option.style.display = 'none';
                }
            });

            if (hasVisibleOptions) {
                emptyState.classList.add('hidden');
                optionsList.classList.remove('hidden');
            } else {
                emptyState.classList.remove('hidden');
                optionsList.classList.add('hidden');
            }
        });

        options.forEach(option => {
            option.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                const text = this.textContent.trim();

                buttonText.textContent = text;
                buttonText.classList.remove('text-slate-500');
                buttonText.classList.add('text-slate-900', 'font-semibold');

                hiddenInput.value = value;
                toggleDropdown(true);

                searchInput.value = '';
                options.forEach(opt => opt.style.display = 'block');
                emptyState.classList.add('hidden');
                optionsList.classList.remove('hidden');
            });
        });

        dropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        document.addEventListener('click', function(e) {
            if (!wrapper.contains(e.target) && !dropdown.classList.contains('hidden')) {
                toggleDropdown(true);
            }
        });

        updateUI(); // Set tampilan awal saat pertama kali diload
    });
</script>
@endsection