<aside class="hidden lg:flex flex-col w-full lg:w-[260px] flex-shrink-0">
    <div class="flex gap-2 mb-6">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-kmdgi-primary bg-white px-3 py-2 rounded-xl border border-slate-100 shadow-sm transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg> Kembali
        </a>
        <a href="{{ url('/') }}" class="p-2 bg-white border border-slate-100 rounded-xl shadow-sm text-slate-500 hover:text-kmdgi-primary transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 p-4 shadow-[0_8px_30px_rgb(0,0,0,0.02)] space-y-1">
        
        <a href="{{ route('dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ Route::is('dashboard') ? 'bg-kmdgi-primary text-white shadow-md shadow-kmdgi-primary/20' : 'text-slate-600 hover:text-kmdgi-primary hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
            Dashboard
        </a>

        <a href="#" 
           class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ Route::is('liked-posts') ? 'bg-kmdgi-primary text-white shadow-md shadow-kmdgi-primary/20' : 'text-slate-600 hover:text-kmdgi-primary hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
            Liked Post
        </a>

        @if(Auth::user()->kategori === 'Delegasi')
        <div class="pt-1">
            <button class="w-full flex items-center justify-between px-4 py-3 text-slate-600 hover:text-kmdgi-primary hover:bg-slate-50 rounded-2xl font-medium text-sm transition-all dropdown-btn">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l5-2 5 2z" /></svg>
                    Delegasi
                </span>
                <svg class="w-4 h-4 transition-transform duration-200 arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div class="pl-8 flex flex-col space-y-2 mt-2 hidden submenu-list">
                 <a href="#" class="text-xs font-medium text-slate-500 hover:text-kmdgi-primary py-1 block">Detail Tim</a>
            </div>
        </div>

        <div class="pt-1">
            <button class="w-full flex items-center justify-between px-4 py-3 text-slate-600 hover:text-kmdgi-primary hover:bg-slate-50 rounded-2xl font-medium text-sm transition-all dropdown-btn">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    Submisi Karya
                </span>
                <svg class="w-4 h-4 transition-transform duration-200 arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div class="pl-8 flex flex-col space-y-2 mt-2 hidden submenu-list">
                <a href="#" class="text-xs font-medium {{ Route::is('karya.tematik') ? 'text-kmdgi-primary font-bold' : 'text-slate-500 hover:text-kmdgi-primary' }} py-1 block">Karya Tematik</a>
                <a href="#" class="text-xs font-medium {{ Route::is('karya.simbiotik') ? 'text-kmdgi-primary font-bold' : 'text-slate-500 hover:text-kmdgi-primary' }} py-1 block">Karya Simbiotik</a>
                <a href="#" class="text-xs font-medium {{ Route::is('karya.simbolik') ? 'text-kmdgi-primary font-bold' : 'text-slate-500 hover:text-kmdgi-primary' }} py-1 block">Karya Simbolik</a>
            </div>
        </div>
        @endif

        <a href="#" 
           class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200 {{ Route::is('setting') ? 'bg-kmdgi-primary text-white shadow-md shadow-kmdgi-primary/20' : 'text-slate-600 hover:text-kmdgi-primary hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            Setting
        </a>
        
        <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-slate-100">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-2xl font-semibold text-sm transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3 3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                Log Out
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