@extends('layouts.app')
@section('title', 'Verifikasi OTP - KMDGI 16')
@section('content')
<div class="bg-white grid grid-cols-1 lg:grid-cols-2 min-h-screen relative">
    <div class="flex flex-col justify-between p-6 sm:p-10 md:p-16 min-h-screen">
        <div class="flex items-center">
            <a href="{{ url('/') }}" class="flex items-center"><img src="{{ asset('images/logo-desktop.png') }}" class="h-10 w-auto"></a>
        </div>
        <div class="w-full max-w-md mx-auto my-12 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-50 text-kmdgi-primary mb-6">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Cek Email Kamu</h1>
            <p class="text-slate-500 text-sm mb-8">Kami telah mengirimkan 6 digit kode OTP ke email <br><span class="font-bold text-slate-700">{{ session('reset_email') }}</span></p>

            <form method="POST" action="{{ route('password.verify-otp.post') }}" class="space-y-6">
                @csrf
                <div>
                    <input type="text" name="otp" id="otp" required maxlength="6"
                        class="w-full text-center tracking-[1em] font-mono text-2xl px-4 py-4 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all" 
                        placeholder="••••••">
                </div>
                <button type="submit" class="w-full bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-4 rounded-xl transition-colors shadow-sm">
                    Verifikasi OTP
                </button>
            </form>
        </div>
        <div class="border-t border-slate-100 pt-6"></div>
    </div>
    <div class="hidden lg:block relative bg-kmdgi-bgRight overflow-hidden">
        <img src="{{ asset('images/login-illustration.png') }}" class="absolute inset-0 w-full h-full object-cover object-center">
    </div>
</div>

<!-- Modal Feedback Error OTP -->
@if(session('error_modal') || $errors->any())
<div id="modal-feedback" class="fixed inset-0 z-[150] flex items-center justify-center p-4 sm:p-6 bg-slate-900/80 backdrop-blur-sm transition-all duration-300">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl flex flex-col p-6 sm:p-8 text-center" id="content-feedback">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-5 shrink-0">
            <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-2">Verifikasi Gagal</h3>
        <p class="text-slate-500 text-sm mb-6">{{ session('error_modal') ?? $errors->first() }}</p>
        <button onclick="document.getElementById('modal-feedback').remove()" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 px-4 rounded-xl transition-colors">Coba Lagi</button>
    </div>
</div>
@endif
@endsection