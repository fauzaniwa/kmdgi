@extends('layouts.app')
@section('title', 'Buat Kata Sandi Baru - KMDGI 16')
@section('content')
<div class="bg-white grid grid-cols-1 lg:grid-cols-2 min-h-screen relative">
    <div class="flex flex-col justify-between p-6 sm:p-10 md:p-16 min-h-screen">
        <div class="flex items-center">
            <a href="{{ url('/') }}" class="flex items-center"><img src="{{ asset('images/logo-desktop.png') }}" class="h-10 w-auto"></a>
        </div>
        <div class="w-full max-w-md mx-auto my-12">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Kata Sandi Baru</h1>
            <p class="text-slate-500 text-sm mb-8">Buat kata sandi baru yang kuat (minimal 8 karakter) untuk mengamankan akun Anda.</p>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-2">Kata Sandi Baru</label>
                    <input type="password" name="password" required 
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary" placeholder="••••••••">
                    @error('password') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-2">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" required 
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary" placeholder="••••••••">
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-4 rounded-xl transition-colors shadow-sm">
                        Simpan & Masuk
                    </button>
                </div>
            </form>
        </div>
        <div class="border-t border-slate-100 pt-6"></div>
    </div>
    <div class="hidden lg:block relative bg-kmdgi-bgRight overflow-hidden">
        <img src="{{ asset('images/login-illustration.png') }}" class="absolute inset-0 w-full h-full object-cover object-center">
    </div>
</div>
@endsection