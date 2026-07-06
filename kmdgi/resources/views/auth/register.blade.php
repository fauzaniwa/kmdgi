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
                    <p class="text-xs text-slate-500 mt-0.5">Nama, email, dan no. HP</p>
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

            <form id="registerForm" method="POST" action="{{ route('register-proses') }}">
                @csrf

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

                        <div>
                            <label for="institusi" class="block text-sm font-semibold text-slate-800 mb-2">Institusi / Kampus<span class="text-red-500">*</span></label>
                            <select id="institusi" name="institusi" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all appearance-none bg-white">
                                <option value="">Pilih Kampus...</option>
                                @foreach($campuses as $campus)
                                <option value="{{ $campus->name }}">{{ $campus->name }}</option>
                                @endforeach
                            </select>
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

                <div id="step-3" class="form-step hidden transition-all duration-300">
                    <div class="text-center mb-8">
                        <div class="inline-flex w-12 h-12 rounded-xl bg-white border border-slate-200 items-center justify-center text-kmdgi-primary shadow-sm mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-2">Informasi Pribadi</h2>
                        <p class="text-sm text-slate-500">Lengkapi data diri Anda di bawah ini</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="nama" class="block text-sm font-semibold text-slate-800 mb-1.5">Nama Lengkap<span class="text-red-500">*</span></label>
                            <input type="text" id="nama" name="nama" required class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all" placeholder="Masukkan nama sesuai identitas">
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

<script>
    let currentStep = 1;
    const totalSteps = 4;

    function updateUI() {
        const kategori = document.getElementById('kategori').value;
        const isUmum = (kategori === 'Umum');

        // 1. Sembunyikan semua step form
        document.querySelectorAll('.form-step').forEach(el => el.classList.add('hidden'));

        // 2. Tampilkan step yang aktif
        document.getElementById(`step-${currentStep}`).classList.remove('hidden');

        // 3. Update warna Sidebar (Desktop)
        for (let i = 1; i <= totalSteps; i++) {
            const nav = document.getElementById(`nav-step-${i}`);
            const icon = nav.querySelector('div.w-10');

            // Reset state
            nav.classList.remove('opacity-100');
            nav.classList.add('opacity-40');
            icon.classList.remove('text-kmdgi-primary');
            icon.classList.add('text-slate-400');

            // Aktifkan jika ini currentStep
            if (i === currentStep) {
                nav.classList.remove('opacity-40');
                nav.classList.add('opacity-100');
                icon.classList.remove('text-slate-400');
                icon.classList.add('text-kmdgi-primary');
            }

            // Logika khusus Sidebar: Jika pilih UMUM, coret/pudarkan total step 2
            if (i === 2) {
                if (isUmum && currentStep > 1) {
                    nav.style.display = 'none'; // Sembunyikan visual step 2 di sidebar jika Umum
                } else {
                    nav.style.display = 'flex';
                }
            }
        }

        // 4. Update Dots (Mobile)
        const dots = document.querySelectorAll('.dot');
        dots.forEach((dot, index) => {
            let actualStep = index + 1;

            // Reset
            dot.classList.remove('bg-kmdgi-primary');
            dot.classList.add('bg-slate-200');
            dot.style.display = 'block';

            // Jika Kategori Umum, hilangkan 1 dot (karena hanya 3 step)
            if (isUmum && actualStep === 2) {
                dot.style.display = 'none';
            }

            // Warnai dot aktif
            if (actualStep === currentStep) {
                dot.classList.remove('bg-slate-200');
                dot.classList.add('bg-kmdgi-primary');
            }
        });
    }

    function nextStep() {
        // Logika Lompat Step berdasarkan Kategori
        const kategori = document.getElementById('kategori').value;

        if (currentStep === 1) {
            // Jika Umum, lompat langsung ke step 3 (Info Pribadi)
            currentStep = (kategori === 'Umum') ? 3 : 2;
        } else if (currentStep < totalSteps) {
            currentStep++;
        }

        updateUI();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        }); // Scroll ke atas saat ganti step
    }

    function prevStep() {
        const kategori = document.getElementById('kategori').value;

        if (currentStep === 3) {
            // Jika kembali dari step 3, dan kategorinya Umum, langsung ke step 1
            currentStep = (kategori === 'Umum') ? 1 : 2;
        } else if (currentStep > 1) {
            currentStep--;
        }

        updateUI();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
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
            authInput.value = ''; // Kosongkan nilainya
        }
    });

    // Inisialisasi tampilan awal
    document.addEventListener("DOMContentLoaded", function() {
        updateUI();
    });
</script>
@endsection