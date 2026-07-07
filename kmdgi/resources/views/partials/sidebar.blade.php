<aside class="hidden lg:flex flex-col w-full lg:w-[260px] flex-shrink-0 bg-white">

    <div class="flex gap-2 mb-6 px-2">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-kmdgi-primary bg-white px-3 py-2 rounded-xl border border-slate-100 shadow-sm transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg> Kembali
        </a>
        <a href="{{ url('/') }}" class="p-2 bg-white border border-slate-100 rounded-xl shadow-sm text-slate-500 hover:text-kmdgi-primary transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
        </a>
    </div>

    <div class="px-2 space-y-1 font-sans">

        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-4 py-3 px-4 rounded-2xl transition-all duration-200 {{ Route::is('dashboard') ? 'text-kmdgi-primary bg-kmdgi-primary/5' : 'text-slate-800 hover:text-kmdgi-primary' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
            </svg>
            <span class="font-medium text-[15px]">Dashboard</span>
        </a>

        <a href="#"
            class="flex items-center gap-4 py-3 px-4 rounded-2xl transition-all duration-200 {{ Route::is('liked-posts') ? 'text-kmdgi-primary bg-kmdgi-primary/5' : 'text-slate-800 hover:text-kmdgi-primary' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
            </svg>
            <span class="font-medium text-[15px]">Liked Post</span>
        </a>

        @if(Auth::user()->kategori === 'Delegasi')
        <div class="w-full">
            <button class="w-full flex items-center justify-between py-3 px-4 text-slate-800 hover:text-kmdgi-primary transition-all dropdown-btn focus:outline-none">
                <div class="flex items-center gap-4">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                    </svg>
                    <span class="font-medium text-[15px]">Delegasi</span>
                </div>
                <svg class="w-5 h-5 transition-transform duration-200 arrow" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>
            <div class="pl-[3.5rem] pr-4 py-1 flex flex-col space-y-1 hidden submenu-list">
                <a href="#" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-kmdgi-primary text-left transition-colors">Status</a>
                <a href="#" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-kmdgi-primary text-left transition-colors">Manage Tim</a>
                <a href="#" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-kmdgi-primary text-left transition-colors">Berkas</a>
            </div>
        </div>

        <div class="w-full">
            <button class="w-full flex items-center justify-between py-3 px-4 text-slate-800 hover:text-kmdgi-primary transition-all dropdown-btn focus:outline-none">
                <div class="flex items-center gap-4">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <span class="font-medium text-[15px]">Submisi Karya</span>
                </div>
                <svg class="w-5 h-5 transition-transform duration-200 arrow" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>
            <div class="pl-[3.5rem] pr-4 py-1 flex flex-col space-y-1 hidden submenu-list">
                <a href="#" class="block px-4 py-2.5 text-sm font-medium {{ Route::is('karya.tematik') ? 'bg-kmdgi-primary text-white rounded-xl text-center shadow-sm' : 'text-slate-600 hover:text-kmdgi-primary text-left' }} transition-colors">Karya Tematik</a>
                <a href="#" class="block px-4 py-2.5 text-sm font-medium {{ Route::is('karya.simbiotik') ? 'bg-kmdgi-primary text-white rounded-xl text-center shadow-sm' : 'text-slate-600 hover:text-kmdgi-primary text-left' }} transition-colors">Karya Simbiotik</a>
                <a href="#" class="block px-4 py-2.5 text-sm font-medium {{ Route::is('karya.simbolik') ? 'bg-kmdgi-primary text-white rounded-xl text-center shadow-sm' : 'text-slate-600 hover:text-kmdgi-primary text-left' }} transition-colors">Karya Simbolik</a>
            </div>
        </div>
        @endif

        <a href="#"
            class="flex items-center gap-4 py-3 px-4 rounded-2xl transition-all duration-200 {{ Route::is('setting') ? 'text-kmdgi-primary bg-kmdgi-primary/5' : 'text-slate-800 hover:text-kmdgi-primary' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
            </svg>
            <span class="font-medium text-[15px]">Setting</span>
        </a>

        <div class="py-4">
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
    document.querySelectorAll('.dropdown-btn').forEach(button => {
        button.addEventListener('click', () => {
            const submenu = button.nextElementSibling;
            const arrow = button.querySelector('.arrow');

            submenu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        });
    });
</script>