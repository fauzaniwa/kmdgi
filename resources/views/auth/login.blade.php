@extends('layouts.app')

@section('title', 'Masuk - KMDGI 16')

@section('content')
<div class="bg-white grid grid-cols-1 lg:grid-cols-2 min-h-screen relative">
    
    <div class="flex flex-col justify-between p-6 sm:p-10 md:p-16 min-h-screen">
        
        <div class="flex items-center">
            <a href="{{ url('/') }}" class="flex items-center">
                <img src="{{ asset('images/logo-desktop.png') }}" alt="Logo KMDGI 16" class="h-10 w-auto object-contain">
            </a>
        </div>

        <div class="w-full max-w-md mx-auto my-12">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Masuk</h1>
            <p class="text-slate-500 text-sm mb-8">Selamat datang kembali guys!</p>

            <form method="POST" action="{{ route('login-proses') }}" class="space-y-5">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-800 mb-2">Email<span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required 
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
                    <a href="{{ route('password.request') }}" class="font-medium text-kmdgi-primary hover:text-kmdgi-hover transition-colors">Lupa Kata Sandi</a>
                </div>

                <p class="text-xs text-slate-500 text-center leading-relaxed px-2 py-2">
                    Dengan melanjutkan, kamu menyetujui 
                    <button type="button" onclick="openLegalModal('modal-syarat')" class="text-kmdgi-primary hover:text-blue-800 underline font-medium focus:outline-none transition-colors">Syarat dan Ketentuan</button> 
                    serta 
                    <button type="button" onclick="openLegalModal('modal-privasi')" class="text-kmdgi-primary hover:text-blue-800 underline font-medium focus:outline-none transition-colors">Kebijakan Privasi</button> 
                    kami.
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

        <div class="flex items-center justify-between text-xs text-slate-500 pt-6 border-t border-slate-100">
            <span>&copy; 2026 KMDGI 16</span>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-kmdgi-primary transition-colors">Instagram</a>
                <a href="#" class="hover:text-kmdgi-primary transition-colors">TikTok</a>
            </div>
        </div>
    </div>

    <div class="hidden lg:block relative bg-kmdgi-bgRight overflow-hidden">
        <img src="{{ asset('images/login-illustration.png') }}" alt="Maskot KMDGI 16" class="absolute inset-0 w-full h-full object-cover object-center">
    </div>

</div>

<!-- Modal Syarat & Ketentuan (Tetap Sama) -->
<div id="modal-syarat" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-slate-900/80 backdrop-blur-sm transition-all duration-300 opacity-0 pointer-events-none">
    <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl flex flex-col max-h-[85vh] transform transition-transform duration-300 scale-95" id="content-syarat">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 shrink-0">
            <h3 class="text-lg font-black text-slate-900">Syarat dan Ketentuan</h3>
            <button type="button" onclick="closeLegalModal('modal-syarat')" class="text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-full p-2 transition-colors focus:outline-none">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="p-6 md:p-8 overflow-y-auto font-medium text-slate-600 text-sm md:text-[15px] leading-relaxed prose prose-slate max-w-none">
            @if($syarat)
                <h4 class="text-xl font-bold text-slate-900 mb-4">{{ $syarat->judul }}</h4>
                {!! $syarat->konten !!}
            @else
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" /></svg>
                    <p class="text-slate-500">Syarat dan Ketentuan saat ini belum tersedia.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Privasi (Tetap Sama) -->
<div id="modal-privasi" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-slate-900/80 backdrop-blur-sm transition-all duration-300 opacity-0 pointer-events-none">
    <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl flex flex-col max-h-[85vh] transform transition-transform duration-300 scale-95" id="content-privasi">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 shrink-0">
            <h3 class="text-lg font-black text-slate-900">Kebijakan Privasi</h3>
            <button type="button" onclick="closeLegalModal('modal-privasi')" class="text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-full p-2 transition-colors focus:outline-none">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="p-6 md:p-8 overflow-y-auto font-medium text-slate-600 text-sm md:text-[15px] leading-relaxed prose prose-slate max-w-none">
            @if($privasi)
                <h4 class="text-xl font-bold text-slate-900 mb-4">{{ $privasi->judul }}</h4>
                {!! $privasi->konten !!}
            @else
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" /></svg>
                    <p class="text-slate-500">Kebijakan Privasi saat ini belum tersedia.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL FEEDBACK LOGIN (ERROR ATAU SUCCESS)  -->
<!-- ========================================== -->
@if(session('error_modal') || session('success') || $errors->any())
<div id="modal-feedback" class="fixed inset-0 z-[150] flex items-center justify-center p-4 sm:p-6 bg-slate-900/80 backdrop-blur-sm transition-all duration-300">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl flex flex-col p-6 sm:p-8 text-center transform transition-transform duration-300 scale-100" id="content-feedback">
        
        @if(session('success'))
            <!-- Modal Success -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-emerald-100 mb-5 shrink-0">
                <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Berhasil!</h3>
            <p class="text-slate-500 text-sm mb-6">{{ session('success') }}</p>
            <button type="button" onclick="closeFeedbackModal()" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-3 px-4 rounded-xl transition-colors">
                Lanjutkan
            </button>
        @else
            <!-- Modal Error (Email/Password Salah) -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-5 shrink-0">
                <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Login Gagal</h3>
            <p class="text-slate-500 text-sm mb-6">
                {{ session('error_modal') ?? $errors->first() }}
            </p>
            <button type="button" onclick="closeFeedbackModal()" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 px-4 rounded-xl transition-colors">
                Coba Lagi
            </button>
        @endif

    </div>
</div>
<script>
    // Animasi Penutupan Modal Feedback
    function closeFeedbackModal() {
        const modal = document.getElementById('modal-feedback');
        const content = document.getElementById('content-feedback');
        
        modal.classList.add('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        
        // Hapus elemen dari DOM setelah animasi transisi selesai (300ms)
        setTimeout(() => { modal.remove(); }, 300);
    }
</script>
@endif
<!-- ========================================== -->


<script>
    // JS Bawaan Modal Legalitas
    function openLegalModal(modalId) {
        const modal = document.getElementById(modalId);
        const content = modal.querySelector('div[id^="content-"]'); 
        modal.classList.remove('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
        document.body.style.overflow = 'hidden';
    }

    function closeLegalModal(modalId) {
        const modal = document.getElementById(modalId);
        const content = modal.querySelector('div[id^="content-"]');
        modal.classList.add('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        document.body.style.overflow = 'auto';
    }

    window.addEventListener('click', function(e) {
        if (e.target.id === 'modal-syarat') { closeLegalModal('modal-syarat'); } 
        else if (e.target.id === 'modal-privasi') { closeLegalModal('modal-privasi'); }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLegalModal('modal-syarat');
            closeLegalModal('modal-privasi');
            if(document.getElementById('modal-feedback')) closeFeedbackModal();
        }
    });
</script>

@endsection