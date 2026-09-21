@extends('layouts.app')
@section('title', 'Lupa Kata Sandi - KMDGI 16')
@section('content')
<div class="bg-white grid grid-cols-1 lg:grid-cols-2 min-h-screen relative">
    <div class="flex flex-col justify-between p-6 sm:p-10 md:p-16 min-h-screen">
        <div class="flex items-center">
            <a href="{{ url('/') }}" class="flex items-center">
                <img src="{{ asset('images/logo-desktop.png') }}" alt="Logo KMDGI 16" class="h-10 w-auto object-contain">
            </a>
        </div>
        <div class="w-full max-w-md mx-auto my-12">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Lupa Kata Sandi?</h1>
            <p class="text-slate-500 text-sm mb-8">Masukkan email yang terdaftar pada akun Anda. Kami akan mengirimkan kode OTP untuk mereset kata sandi.</p>

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-800 mb-2">Email Anda</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all" 
                        placeholder="Masukkan alamat email...">
                    @error('email')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-3 pt-4">
                    <button type="submit" class="w-full bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-4 rounded-xl transition-colors shadow-sm">
                        Kirim Kode OTP
                    </button>
                    <a href="{{ route('login') }}" class="block w-full text-center border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold py-3 px-4 rounded-xl transition-colors">
                        Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-between text-xs text-slate-500 pt-6 border-t border-slate-100">
            <span>&copy; 2026 KMDGI 16</span>
        </div>
    </div>
    <div class="hidden lg:block relative bg-kmdgi-bgRight overflow-hidden">
        <img src="{{ asset('images/login-illustration.png') }}" alt="Maskot KMDGI 16" class="absolute inset-0 w-full h-full object-cover object-center">
    </div>
</div>
@endsection