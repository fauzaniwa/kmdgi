<nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            <a href="{{ url('/') }}" class="flex-shrink-0 flex items-center">
                <img src="{{ asset('images/logo-desktop.png') }}" alt="KMDGI Desktop" class="hidden md:block h-10 w-auto object-contain">
                
                <img src="{{ asset('images/logo-mobile.png') }}" alt="KMDGI Mobile" class="block md:hidden h-8 w-auto object-contain">
            </a>

            <div class="hidden md:flex space-x-8">
                <a href="#" class="text-slate-600 hover:text-kmdgi-primary font-medium transition-colors duration-200">Jadwal</a>
                <a href="#" class="text-slate-600 hover:text-kmdgi-primary font-medium transition-colors duration-200">Galeri Karya</a>
                <a href="#" class="text-slate-600 hover:text-kmdgi-primary font-medium transition-colors duration-200">Tentang KMDGI</a>
                <a href="#" class="text-slate-600 hover:text-kmdgi-primary font-medium transition-colors duration-200">Panduan Delegasi</a>
            </div>

            <div class="hidden md:flex items-center gap-4">
                @auth
                    <button class="relative p-2 text-slate-400 hover:text-kmdgi-primary bg-slate-50 hover:bg-kmdgi-primary/10 rounded-full transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-1.5 right-2 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></span>
                    </button>
                    
                    <div class="flex items-center gap-3 pl-2 border-l border-slate-200">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-slate-900 leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 capitalize">{{ Auth::user()->role }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden border-2 border-white shadow-sm cursor-pointer hover:ring-2 hover:ring-kmdgi-primary/50 transition-all">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=126CFD&color=fff" alt="Avatar" class="w-full h-full object-cover">
                        </div>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-kmdgi-primary font-semibold px-2 py-2 transition-colors duration-200">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-2.5 px-5 rounded-2xl shadow-lg shadow-kmdgi-primary/25 transition-all duration-200 transform hover:-translate-y-0.5">Daftar</a>
                @endguest
            </div>

            <div class="flex items-center md:hidden">
                <button id="hamburger-btn" class="text-slate-900 hover:text-kmdgi-primary focus:outline-none p-2 transition-colors">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>
</nav>

<div id="mobile-sidebar" class="fixed inset-0 z-[60] bg-white transform translate-x-full transition-transform duration-300 ease-in-out hidden flex-col">
    <div class="flex justify-between items-center px-4 h-20 border-b border-slate-100">
        <img src="{{ asset('images/logo-mobile.png') }}" alt="KMDGI Mobile" class="h-8 w-auto object-contain">
        <button id="close-sidebar-btn" class="text-slate-900 hover:text-kmdgi-primary p-2 transition-colors">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    
    <div class="flex flex-col px-6 py-8 overflow-y-auto h-full justify-between">
        <div class="flex flex-col space-y-6">
            <a href="#" class="text-2xl font-bold text-slate-800 hover:text-kmdgi-primary transition-colors">Jadwal</a>
            <a href="#" class="text-2xl font-bold text-slate-800 hover:text-kmdgi-primary transition-colors">Galeri Karya</a>
            <a href="#" class="text-2xl font-bold text-slate-800 hover:text-kmdgi-primary transition-colors">Tentang KMDGI</a>
            <a href="#" class="text-2xl font-bold text-slate-800 hover:text-kmdgi-primary transition-colors">Panduan Delegasi</a>
        </div>

        <div class="pt-10 border-t border-slate-100 mt-10">
            @auth
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full bg-slate-200 overflow-hidden border-2 border-white shadow-sm">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=126CFD&color=fff" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="text-base font-bold text-slate-900">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-slate-500 capitalize">{{ Auth::user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center text-red-500 font-bold py-4 rounded-2xl bg-red-50 hover:bg-red-100 transition-colors">
                        Keluar Akun
                    </button>
                </form>
            @endauth

            @guest
                <div class="flex flex-col gap-4">
                    <a href="{{ route('login') }}" class="w-full text-center text-kmdgi-primary border border-kmdgi-primary font-bold py-4 rounded-2xl hover:bg-kmdgi-primary/5 transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="w-full text-center bg-kmdgi-primary text-white font-bold py-4 rounded-2xl shadow-lg shadow-kmdgi-primary/25 hover:bg-kmdgi-hover transition-colors">Daftar Sekarang</a>
                </div>
            @endguest
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const closeSidebarBtn = document.getElementById('close-sidebar-btn');
        const mobileSidebar = document.getElementById('mobile-sidebar');

        // Buka Sidebar
        hamburgerBtn.addEventListener('click', () => {
            mobileSidebar.classList.remove('hidden');
            mobileSidebar.classList.add('flex');
            
            // Jeda sejenak agar browser merender penghapusan 'hidden' sebelum memulai animasi transisi
            setTimeout(() => {
                mobileSidebar.classList.remove('translate-x-full');
                mobileSidebar.classList.add('translate-x-0');
            }, 10);
        });

        // Tutup Sidebar
        closeSidebarBtn.addEventListener('click', () => {
            mobileSidebar.classList.remove('translate-x-0');
            mobileSidebar.classList.add('translate-x-full');
            
            // Tunggu animasi slide-out selesai (300ms) sebelum menyembunyikan elemen
            setTimeout(() => {
                mobileSidebar.classList.add('hidden');
                mobileSidebar.classList.remove('flex');
            }, 300);
        });
    });
</script>