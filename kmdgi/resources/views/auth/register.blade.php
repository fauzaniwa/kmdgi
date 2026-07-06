@extends('layouts.app')

@section('title', 'Daftar Akun - KMDGI 16')

@section('content')
<div class="bg-white grid grid-cols-1 lg:grid-cols-2 min-h-screen">
    <!-- Kiri: Area Form Pendaftaran -->
    <div class="flex flex-col justify-between p-6 sm:p-10 md:p-16 min-h-screen">
        
        <!-- Header -->
        <div class="flex items-center">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-kmdgi-primary rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-md">K</div>
                <div class="leading-none">
                    <span class="font-black text-xl tracking-tight text-kmdgi-primary block">KMDGI</span>
                    <span class="text-[9px] text-slate-500 font-medium tracking-widest uppercase block">Kriyasana Mahasiswa Desain Grafis Indonesia</span>
                </div>
            </div>
        </div>

        <!-- Main: Form Register -->
        <div class="w-full max-w-md mx-auto my-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Daftar</h1>
            <p class="text-slate-500 text-sm mb-6">Mulai langkah kreatifmu bersama KMDGI!</p>

            <form method="POST" action="{{ route('register-proses') }}" class="space-y-4">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-800 mb-1.5">Nama Lengkap<span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all" 
                        placeholder="Masukkan nama lengkap anda...">
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-800 mb-1.5">Email<span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" required 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all" 
                        placeholder="Masukkan email anda...">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-800 mb-1.5">Kata Sandi<span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password" required 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all" 
                        placeholder="Buat kata sandi...">
                </div>

                <p class="text-xs text-slate-500 text-center leading-relaxed py-2">
                    Dengan mendaftar, kamu menyetujui <a href="#" class="text-kmdgi-primary underline font-medium">Syarat dan Ketentuan</a> serta <a href="#" class="text-kmdgi-primary underline font-medium">Kebijakan Privasi</a> kami.
                </p>

                <div class="space-y-3 pt-1">
                    <button type="submit" class="w-full bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-4 rounded-xl transition-colors shadow-sm">
                        Daftar Sekarang
                    </button>
                    <a href="{{ route('login') }}" class="block w-full text-center border border-kmdgi-primary text-kmdgi-primary hover:bg-kmdgi-primary/5 font-semibold py-3 px-4 rounded-xl transition-colors">
                        Sudah punya akun? Masuk
                    </a>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between text-xs text-slate-500 pt-6 border-t border-slate-100">
            <span>&copy; 2026 KMDGI 16</span>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-kmdgi-primary transition-colors">Instagram</a>
                <a href="#" class="hover:text-kmdgi-primary transition-colors">TikTok</a>
            </div>
        </div>
    </div>

    <!-- Kanan: Dekorasi -->
    <div class="hidden lg:flex bg-kmdgi-bgRight items-center justify-center p-12 relative overflow-hidden">
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <div class="w-[80%] h-[80%] border-[40px] border-blue-200/40 rounded-full animate-[spin_30s_linear_infinite]"></div>
        </div>
        <div class="relative z-10 text-center">
            <img src="https://via.placeholder.com/400x400/126CFD/FFFFFF?text=Registrasi+KMDGI" alt="Ilustrasi KMDGI 16" class="max-w-md mx-auto object-contain">
        </div>
    </div>
</div>
@endsection