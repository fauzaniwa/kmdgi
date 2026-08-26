@extends('layouts.app')

@section('title', 'Edit Profil - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">
        
        @include('partials.sidebar')

        <main class="flex-grow space-y-8 w-full">
            
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan Profil</h2>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Perbarui informasi data diri dan kata sandi akun Anda di sini.</p>
                </div>
            </div>

            <!-- Menampilkan Pesan Sukses Jika Ada -->
            @if (session('success') || session('status') === 'profile-updated' || session('status') === 'password-updated')
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="font-semibold text-sm">{{ session('success') ?? 'Perubahan berhasil disimpan.' }}</span>
                </div>
            @endif

            <!-- ========================================== -->
            <!-- FORM INFORMASI PROFIL                      -->
            <!-- ========================================== -->
            <section class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Informasi Dasar</h3>
                
                <!-- Form Update Profil menggunakan rute standar Laravel ProfileController -->
                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('patch')

                    <!-- Foto Profil -->
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                        <div class="relative group cursor-pointer flex-shrink-0">
                            <div class="w-28 h-28 md:w-32 md:h-32 rounded-full bg-slate-200 overflow-hidden border-4 border-white shadow-md flex items-center justify-center relative z-10">
                                @if(Auth::user()->profile_image)
                                    <img id="preview-image" src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Foto Profil" class="w-full h-full object-cover">
                                @else
                                    <img id="preview-image" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=126CFD&color=fff&size=200" alt="Avatar" class="w-full h-full object-cover">
                                @endif
                                
                                <!-- Overlay Hover -->
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                            </div>
                            <!-- Input File tersembunyi, dipicu oleh JS -->
                            <input type="file" name="profile_image" id="profile_image" class="hidden" accept="image/jpeg, image/png, image/jpg, image/webp" onchange="previewImage(event)">
                        </div>
                        
                        <div class="text-center sm:text-left space-y-2 mt-2">
                            <button type="button" onclick="document.getElementById('profile_image').click()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-4 py-2 rounded-xl text-sm transition-colors border border-slate-200 shadow-sm">
                                Pilih Foto Baru
                            </button>
                            <p class="text-xs text-slate-500 font-medium">Format: JPG, PNG, WEBP. Ukuran maks: 2MB.<br>Rasio terbaik 1:1 (Persegi).</p>
                            @error('profile_image')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    <!-- Grid Input Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Nama Lengkap -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary focus:border-transparent transition-all" placeholder="Masukkan nama lengkap Anda">
                            @error('name') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Alamat Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary focus:border-transparent transition-all" placeholder="contoh@email.com">
                            @error('email') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nomor HP/WhatsApp -->
                        <div>
                            <label for="no_hp" class="block text-sm font-semibold text-slate-700 mb-2">Nomor WhatsApp</label>
                            <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', Auth::user()->no_hp) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary focus:border-transparent transition-all" placeholder="08123456789">
                            @error('no_hp') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', Auth::user()->tanggal_lahir) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary focus:border-transparent transition-all">
                            @error('tanggal_lahir') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Profesi (Hanya relevan untuk non-Delegasi) -->
                        @if(Auth::user()->kategori !== 'Delegasi')
                        <div>
                            <label for="profesi" class="block text-sm font-semibold text-slate-700 mb-2">Profesi / Pekerjaan</label>
                            <input type="text" name="profesi" id="profesi" value="{{ old('profesi', Auth::user()->profesi) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary focus:border-transparent transition-all" placeholder="Contoh: Mahasiswa, Freelancer, Dosen">
                            @error('profesi') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        @endif

                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="w-full sm:w-auto bg-kmdgi-primary hover:bg-blue-700 text-white font-bold px-8 py-3.5 rounded-xl text-sm transition-colors shadow-md shadow-kmdgi-primary/20">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </section>

            <!-- ========================================== -->
            <!-- FORM UBAH KATA SANDI                       -->
            <!-- ========================================== -->
            <section class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-900">Keamanan & Kata Sandi</h3>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak demi keamanan.</p>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="space-y-6 max-w-2xl">
                    @csrf
                    @method('put')

                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-slate-700 mb-2">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" required autocomplete="current-password" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all" placeholder="••••••••">
                        @error('current_password', 'updatePassword') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Kata Sandi Baru</label>
                        <input type="password" name="password" id="password" required autocomplete="new-password" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all" placeholder="Minimal 8 karakter">
                        @error('password', 'updatePassword') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all" placeholder="Ulangi kata sandi baru">
                        @error('password_confirmation', 'updatePassword') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-start pt-2">
                        <button type="submit" class="w-full sm:w-auto bg-slate-900 hover:bg-slate-800 text-white font-bold px-8 py-3.5 rounded-xl text-sm transition-colors shadow-md">
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </section>

        </main>
    </div>

    @include('partials.footer')
</div>

<!-- SCRIPT PREVIEW FOTO PROFIL -->
<script>
    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Ganti source gambar pada elemen ID preview-image
                document.getElementById('preview-image').src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection