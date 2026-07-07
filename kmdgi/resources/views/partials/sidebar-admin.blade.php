<aside class="hidden lg:flex flex-col w-full lg:w-[280px] flex-shrink-0 bg-white">
    <div class="px-2 py-4 space-y-1 font-sans">

        <div class="px-4 py-3 mb-6 bg-slate-50 rounded-2xl border border-slate-100">
            <span class="text-[10px] uppercase tracking-wider font-bold text-slate-400 block mb-0.5">Akses Panel</span>
            <span class="text-sm font-bold text-kmdgi-primary capitalize">{{ auth()->user()->role }}</span>
        </div>

        <a href="#" class="flex items-center gap-4 py-3 px-4 rounded-2xl transition-all duration-200 {{ Route::is('admin.dashboard') ? 'text-kmdgi-primary bg-kmdgi-primary/5' : 'text-slate-800 hover:text-kmdgi-primary' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
            </svg>
            <span class="font-medium text-[15px]">Dashboard</span>
        </a>

        @if(in_array(auth()->user()->role, ['super admin', 'admin']))
        <a href="#" class="flex items-center gap-4 py-3 px-4 rounded-2xl text-slate-800 hover:text-kmdgi-primary transition-all">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
            </svg>
            <span class="font-medium text-[15px]">Scan QR Code</span>
        </a>
        @endif

        @if(in_array(auth()->user()->role, ['super admin', 'admin']))
        <div class="pt-6 pb-2 px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Data Master</div>

        <a href="#" class="flex items-center gap-4 py-3 px-4 rounded-2xl text-slate-800 hover:text-kmdgi-primary transition-all">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6.75h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
            </svg>
            <span class="font-medium text-[15px]">Data Kampus</span>
        </a>

        <a href="#" class="flex items-center gap-4 py-3 px-4 rounded-2xl text-slate-800 hover:text-kmdgi-primary transition-all">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            <span class="font-medium text-[15px]">Data User</span>
        </a>
        @endif

        <div class="pt-6 pb-2 px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Konten & Acara</div>

        <a href="#" class="flex items-center gap-4 py-3 px-4 rounded-2xl text-slate-800 hover:text-kmdgi-primary transition-all">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
            </svg>
            <span class="font-medium text-[15px]">Data Event</span>
        </a>

        <a href="#" class="flex items-center gap-4 py-3 px-4 rounded-2xl text-slate-800 hover:text-kmdgi-primary transition-all">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
            </svg>
            <span class="font-medium text-[15px]">Data Penampil</span>
        </a>

        <div class="w-full">
            <button class="w-full flex items-center justify-between py-3 px-4 text-slate-800 hover:text-kmdgi-primary transition-all admin-dropdown-toggle">
                <div class="flex items-center gap-4">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                    </svg>
                    <span class="font-medium text-[15px]">Data KMDGI</span>
                </div>
                <svg class="w-5 h-5 transition-transform duration-200 arrow" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>
            <div class="pl-[3.5rem] pr-4 py-2 flex flex-col space-y-1 hidden dropdown-container">
                <a href="#" class="block px-4 py-2.5 text-sm font-medium {{ Route::is('admin.about') ? 'bg-kmdgi-primary text-white rounded-xl text-center shadow-sm' : 'text-slate-600 hover:text-kmdgi-primary text-left' }} transition-colors">About KMDGI</a>
                <a href="#" class="block px-4 py-2.5 text-sm font-medium {{ Route::is('admin.sejarah') ? 'bg-kmdgi-primary text-white rounded-xl text-center shadow-sm' : 'text-slate-600 hover:text-kmdgi-primary text-left' }} transition-colors">Sejarah KMDGI</a>
                <a href="#" class="block px-4 py-2.5 text-sm font-medium {{ Route::is('admin.latarbelakang') ? 'bg-kmdgi-primary text-white rounded-xl text-center shadow-sm' : 'text-slate-600 hover:text-kmdgi-primary text-left' }} transition-colors">Latar Belakang</a>
                <a href="#" class="block px-4 py-2.5 text-sm font-medium {{ Route::is('admin.tema') ? 'bg-kmdgi-primary text-white rounded-xl text-center shadow-sm' : 'text-slate-600 hover:text-kmdgi-primary text-left' }} transition-colors">Tema KMDGI</a>
            </div>
        </div>

        <div class="w-full">
            <button class="w-full flex items-center justify-between py-3 px-4 text-slate-800 hover:text-kmdgi-primary transition-all admin-dropdown-toggle">
                <div class="flex items-center gap-4">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <span class="font-medium text-[15px]">Deskripsi Karya</span>
                </div>
                <svg class="w-5 h-5 transition-transform duration-200 arrow" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>
            <div class="pl-[3.5rem] pr-4 py-2 flex flex-col space-y-1 hidden dropdown-container">
                <a href="#" class="block px-4 py-2.5 text-sm font-medium {{ Route::is('admin.tematik') ? 'bg-kmdgi-primary text-white rounded-xl text-center shadow-sm' : 'text-slate-600 hover:text-kmdgi-primary text-left' }} transition-colors">Karya Tematik</a>
                <a href="#" class="block px-4 py-2.5 text-sm font-medium {{ Route::is('admin.simbiotik') ? 'bg-kmdgi-primary text-white rounded-xl text-center shadow-sm' : 'text-slate-600 hover:text-kmdgi-primary text-left' }} transition-colors">Karya Simbiotik</a>
                <a href="#" class="block px-4 py-2.5 text-sm font-medium {{ Route::is('admin.simbolik') ? 'bg-kmdgi-primary text-white rounded-xl text-center shadow-sm' : 'text-slate-600 hover:text-kmdgi-primary text-left' }} transition-colors">Karya Simbolik</a>
            </div>
        </div>

        <a href="#" class="flex items-center gap-4 py-3 px-4 rounded-2xl text-slate-800 hover:text-kmdgi-primary transition-all">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
            </svg>
            <span class="font-medium text-[15px]">Dokumentasi</span>
        </a>

        <a href="#" class="flex items-center gap-4 py-3 px-4 rounded-2xl text-slate-800 hover:text-kmdgi-primary transition-all">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
            </svg>
            <span class="font-medium text-[15px]">F&Q</span>
        </a>

        @if(auth()->user()->role === 'super admin')
        <div class="pt-6 pb-2 px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Sistem & Regulasi</div>

        <a href="#" class="flex items-center gap-4 py-3 px-4 rounded-2xl text-slate-800 hover:text-kmdgi-primary transition-all">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            <span class="font-medium text-[15px]">Syarat dan Ketentuan</span>
        </a>

        <a href="#" class="flex items-center gap-4 py-3 px-4 rounded-2xl text-slate-800 hover:text-kmdgi-primary transition-all">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
            </svg>
            <span class="font-medium text-[15px]">Kebijakan Privasi</span>
        </a>

        <a href="#" class="flex items-center gap-4 py-3 px-4 rounded-2xl text-slate-800 hover:text-kmdgi-primary transition-all">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
            </svg>
            <span class="font-medium text-[15px]">Log Admin System</span>
        </a>
        @endif

        <div class="pt-6 pb-2">
            <hr class="border-t border-slate-100">
        </div>

        <form id="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="button"
                onclick="openModal('kmdgi-global-modal', this)"
                data-title="Keluar dari Panel Akun?"
                data-message="Apakah Anda yakin ingin mengakhiri sesi pendaftaran ini? Anda harus memasukkan kata sandi kembali untuk mengakses dasbor."
                data-type="warning"
                data-primary-text="Keluar"
                data-secondary-text="Batalkan"
                data-form-id="logout-form"
                class="w-full flex items-center gap-4 py-3 px-4 text-red-500 hover:bg-red-50 rounded-2xl transition-all text-left focus:outline-none">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                </svg>
                <span class="font-medium text-[15px]">Log Out Panel</span>
            </button>
        </form>
    </div>
</aside>

<script>
    document.querySelectorAll('.admin-dropdown-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const container = btn.nextElementSibling;
            const arrow = btn.querySelector('.arrow');

            // Toggle visibility
            container.classList.toggle('hidden');

            // Animasi panah chevron (menghadap atas jika terbuka, bawah jika tertutup)
            arrow.classList.toggle('rotate-180');
        });
    });
</script>