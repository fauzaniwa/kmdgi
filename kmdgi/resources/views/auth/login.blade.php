@extends('layouts.app')

@section('title', 'Masuk - KMDGI 16')

@section('content')
<div class="bg-white grid grid-cols-1 lg:grid-cols-2 min-h-screen">
    <!-- Kiri: Area Form -->
    <div class="flex flex-col justify-between p-6 sm:p-10 md:p-16 min-h-screen">
        
        <!-- Header: Logo KMDGI -->
        <div class="flex items-center">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-kmdgi-primary rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-md">K</div>
                <div class="leading-none">
                    <span class="font-black text-xl tracking-tight text-kmdgi-primary block">KMDGI</span>
                    <span class="text-[9px] text-slate-500 font-medium tracking-widest uppercase block">Kriyasana Mahasiswa Desain Grafis Indonesia</span>
                </div>
            </div>
        </div>

        <!-- Main: Form Content -->
        <div class="w-full max-w-md mx-auto my-12">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Masuk</h1>
            <p class="text-slate-500 text-sm mb-8">Selamat datang kembali guys!</p>

            <form method="POST" action="{{ route('login-proses') }}" class="space-y-5">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-800 mb-2">Email<span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" required 
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all" 
                        placeholder="Masukkan email anda...">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-800 mb-2">Kata Sandi<span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password" required 
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all" 
                        placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center cursor-pointer select-none text-slate-600">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-kmdgi-primary focus:ring-kmdgi-primary/30 mr-2">
                        Ingat Saya
                    </label>
                    <a href="#" class="font-medium text-kmdgi-primary hover:text-kmdgi-hover transition-colors">Lupa Kata Sandi</a>
                </div>

                <p class="text-xs text-slate-500 text-center leading-relaxed px-2 py-2">
                    Dengan melanjutkan, kamu menyetujui <a href="#" class="text-kmdgi-primary underline font-medium">Syarat dan Ketentuan</a> serta <a href="#" class="text-kmdgi-primary underline font-medium">Kebijakan Privasi</a> kami.
                </p>

                <div class="space-y-3 pt-2">
                    <button type="submit" class="w-full bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-4 rounded-xl transition-colors shadow-sm">
                        Masuk
                    </button>
                    <a href="{{ route('register') }}" class="block w-full text-center border border-kmdgi-primary text-kmdgi-primary hover:bg-kmdgi-primary/5 font-semibold py-3 px-4 rounded-xl transition-colors">
                        Daftar
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

    <!-- Kanan: Ilustrasi -->
    <div class="hidden lg:flex bg-kmdgi-bgRight items-center justify-center p-12 relative overflow-hidden">
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <div class="w-[80%] h-[80%] border-[40px] border-pink-200/40 rounded-full animate-[spin_20s_linear_infinite]"></div>
        </div>
        <div class="relative z-10 text-center">
            <img src="https://via.placeholder.com/400x400/126CFD/FFFFFF?text=Maskot+KMDGI" alt="Maskot KMDGI 16" class="max-w-md mx-auto object-contain">
        </div>
    </div>
</div>
@endsection