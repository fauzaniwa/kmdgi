<!-- Wrapper Footer dengan margin 0 di mobile agar edge-to-edge -->
<div class="px-0 md:px-6 lg:px-8 pb-0 md:pb-6 mt-auto">
    <!-- Di mobile hanya rounded di atas, di desktop (md) rounded seluruhnya -->
    <footer class="text-white relative overflow-hidden rounded-t-[2rem] md:rounded-[2rem] shadow-2xl" style="background-color: {{ $footerSetting->bg_color ?? '#262626' }};">

        <!-- Dekorasi Desktop -->
        @if(isset($footerSetting) && $footerSetting->decoration_desktop)
            <img src="{{ asset('storage/' . $footerSetting->decoration_desktop) }}" alt="Dekorasi Footer" class="hidden md:block absolute top-0 right-0 h-full w-[45%] object-cover object-left pointer-events-none opacity-20">
        @else
            <img src="{{ asset('images/footer-decoration.png') }}" alt="Dekorasi Footer Default" class="hidden md:block absolute top-0 right-0 h-full w-[45%] object-cover object-left pointer-events-none opacity-20">
        @endif

        <!-- Dekorasi Mobile -->
        @if(isset($footerSetting) && $footerSetting->decoration_mobile)
            <img src="{{ asset('storage/' . $footerSetting->decoration_mobile) }}" alt="Dekorasi Footer" class="block md:hidden absolute top-0 right-[-10%] w-[70%] max-w-[250px] object-contain pointer-events-none opacity-20">
        @else
            <img src="{{ asset('images/footer-decoration.png') }}" alt="Dekorasi Footer Default" class="block md:hidden absolute top-0 right-[-10%] w-[70%] max-w-[250px] object-contain pointer-events-none opacity-20">
        @endif

        <div class="max-w-7xl mx-auto px-6 md:px-10 lg:px-12 pt-10 md:pt-16 pb-8 md:pb-10 relative z-10">

            <div class="flex flex-col lg:flex-row justify-between gap-10 lg:gap-8">

                <!-- Bagian Kiri: Logo & Alamat -->
                <div class="w-full lg:w-1/3 flex flex-col gap-5">
                    <a href="{{ url('/') }}" class="w-max">
                        @if(isset($footerSetting) && $footerSetting->logo)
                            <img src="{{ asset('storage/' . $footerSetting->logo) }}" alt="Logo Footer" class="h-14 md:h-20 w-auto object-contain">
                        @else
                            <img src="{{ asset('images/logo-footer.png') }}" alt="KMDGI 16 Default" class="h-14 md:h-20 w-auto object-contain">
                        @endif
                    </a>
                    <p class="text-[12px] md:text-sm text-white/90 leading-relaxed max-w-sm font-medium">
                        KMDGI 16 at<br>
                        Universitas Pendidikan Indonesia<br>
                        Jl. Dr. Setiabudi No.229, Isola, Kec. Sukasari, Kota<br>
                        Bandung, Jawa Barat 40154
                    </p>
                </div>

                <!-- Bagian Kanan: 3 Kolom Links -->
                <div class="w-full lg:w-auto grid grid-cols-2 md:grid-cols-3 gap-y-8 gap-x-4 md:gap-16 lg:gap-24">

                    <!-- Kolom 1: Menu -->
                    <div class="flex flex-col space-y-2.5 md:space-y-3.5">
                        <h4 class="font-bold text-[13px] md:text-base text-blue-500 mb-0.5 md:mb-1">Menu</h4>
                        @if(isset($footerSetting) && is_array($footerSetting->menu_links))
                            @foreach($footerSetting->menu_links as $link)
                                <a href="{{ url($link['url']) }}" class="text-white/80 hover:text-white transition-colors text-[12px] md:text-sm font-medium">{{ $link['title'] }}</a>
                            @endforeach
                        @else
                            <a href="{{ route('katalog.karya.index') }}" class="text-white/80 hover:text-white transition-colors text-[12px] md:text-sm font-medium">Karya</a>
                            <a href="{{ route('home') }}#acara" class="text-white/80 hover:text-white transition-colors text-[12px] md:text-sm font-medium">Acara</a>
                            <a href="{{ route('home') }}#lomba" class="text-white/80 hover:text-white transition-colors text-[12px] md:text-sm font-medium">Lomba</a>
                            <a href="{{ route('tentang-kami') }}" class="text-white/80 hover:text-white transition-colors text-[12px] md:text-sm font-medium">Tentang KMDGI</a>
                            <a href="{{ route('panduan-delegasi') }}" class="text-white/80 hover:text-white transition-colors text-[12px] md:text-sm font-medium">Panduan Delegasi</a>
                        @endif
                    </div>

                    <!-- Kolom 2: Profile -->
                    <div class="flex flex-col space-y-2.5 md:space-y-3.5">
                        <h4 class="font-bold text-[13px] md:text-base text-pink-500 mb-0.5 md:mb-1">Profile</h4>
                        @if(isset($footerSetting) && is_array($footerSetting->profile_links))
                            @foreach($footerSetting->profile_links as $link)
                                <a href="{{ url($link['url']) }}" class="text-white/80 hover:text-white transition-colors text-[12px] md:text-sm font-medium">{{ $link['title'] }}</a>
                            @endforeach
                        @else
                            <a href="{{ route('dashboard') }}" class="text-white/80 hover:text-white transition-colors text-[12px] md:text-sm font-medium">Dashboard</a>
                            <a href="{{ route('home') }}#lomba" class="text-white/80 hover:text-white transition-colors text-[12px] md:text-sm font-medium">Lomba</a>
                            <a href="{{ route('liked-posts') }}" class="text-white/80 hover:text-white transition-colors text-[12px] md:text-sm font-medium">Postingan disukai</a>
                            <a href="{{ route('delegasi.status') }}" class="text-white/80 hover:text-white transition-colors text-[12px] md:text-sm font-medium">Delegasi</a>
                            <a href="{{ route('delegasi.submisi.karya') }}" class="text-white/80 hover:text-white transition-colors text-[12px] md:text-sm font-medium">Submisi karya</a>
                            <a href="{{ route('profile.edit') }}" class="text-white/80 hover:text-white transition-colors text-[12px] md:text-sm font-medium">Setting</a>
                        @endif
                    </div>

                    <!-- Kolom 3: Others -->
                    <div class="flex flex-col space-y-2.5 md:space-y-3.5 col-span-2 md:col-span-1">
                        <h4 class="font-bold text-[13px] md:text-base text-lime-500 mb-0.5 md:mb-1">Others</h4>
                        <a href="#" class="text-white/80 hover:text-white transition-colors text-[12px] md:text-sm font-medium">Syarat dan Ketentuan</a>
                        <a href="#" class="text-white/80 hover:text-white transition-colors text-[12px] md:text-sm font-medium">Pelindungan data</a>
                        <a href="#" class="text-white/80 hover:text-white transition-colors text-[12px] md:text-sm font-medium">Lupa Password</a>
                    </div>

                </div>
            </div>

            <!-- Garis Pembatas -->
            <hr class="border-t border-white/20 my-6 md:my-8">

            <!-- Footer Bottom -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-8 md:gap-0">
                
                <!-- Credits -->
                <div class="flex items-start gap-8 md:gap-12 w-full md:w-auto">
                    <div class="flex flex-col gap-1">
                        <span class="text-[11px] md:text-[13px] text-white/80 font-medium">Powered by:</span>
                        <button type="button" onclick="openCreditModal()" class="font-bold text-white text-[11px] md:text-sm text-left hover:text-blue-400 transition-colors focus:outline-none">
                            DangDang Studio
                        </button> 
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[11px] md:text-[13px] text-white/80 font-medium">UI/UX Design:</span>
                        <button type="button" onclick="openCreditModal()" class="font-bold text-white text-[11px] md:text-sm text-left hover:text-blue-400 transition-colors focus:outline-none">
                            M Fawwaz Azhar
                        </button>
                    </div>
                </div>

                <!-- Copyright & Social Media -->
                <div class="flex flex-col md:items-end gap-5 w-full md:w-auto">
                    <!-- Ikon Sosial Media yang Dirapikan & WhatsApp Dihapus -->
                    <div class="flex items-center gap-5">
                        <!-- Instagram -->
                        @if(isset($footerSetting) && $footerSetting->link_instagram)
                        <a href="{{ $footerSetting->link_instagram }}" target="_blank" aria-label="Instagram" class="text-white/80 hover:text-white transition-transform hover:scale-110">
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2zm-.2 1.5A4.3 4.3 0 0 0 3.5 7.8v8.4A4.3 4.3 0 0 0 7.8 20.5h8.4a4.3 4.3 0 0 0 4.3-4.3V7.8a4.3 4.3 0 0 0-4.3-4.3H7.6zm4.4 3.5a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 1.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7zm5.3-2a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4z" />
                            </svg>
                        </a>
                        @endif

                        <!-- TikTok -->
                        @if(isset($footerSetting) && $footerSetting->link_tiktok)
                        <a href="{{ $footerSetting->link_tiktok }}" target="_blank" aria-label="TikTok" class="text-white/80 hover:text-white transition-transform hover:scale-110">
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 15.68l.01.2a6.29 6.29 0 0 0 11.23 2.1l.14-.23.1-.21V8.65a8.28 8.28 0 0 0 4.39 1.56V6.76a5 5 0 0 1-1.28-.07z" />
                            </svg>
                        </a>
                        @endif
                    </div>
                    
                    <p class="text-[11px] md:text-[13px] text-white/50 font-medium">
                        {{ $footerSetting->copyright_text ?? '© 2026 KMDGI 16. All rights reserved.' }}
                    </p>
                </div>

            </div>
        </div>
    </footer>
</div>

<!-- Elemen Modal Credit -->
<div id="creditModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden bg-black/70 backdrop-blur-sm transition-opacity opacity-0 duration-300">
    <div class="bg-[#1a1a1a] border border-white/10 rounded-2xl p-6 md:p-8 max-w-md w-full mx-4 relative shadow-2xl transform scale-95 transition-transform duration-300" id="creditModalContent">
        
        <button type="button" onclick="closeCreditModal()" class="absolute top-4 right-4 text-white/50 hover:text-white transition-colors focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <h3 class="text-xl md:text-2xl font-bold text-white mb-6">Credits</h3>

        <div class="space-y-4 text-white/90 text-sm md:text-base">
            <div class="flex flex-col gap-1">
                <span class="text-white/60">System and Product by :</span>
                <a href="https://studiodangdang.com" target="_blank" rel="noopener noreferrer" class="font-bold text-blue-400 hover:text-blue-300 transition-colors w-fit">
                    DangDang Studio
                </a>
            </div>
            
            <div class="flex flex-col gap-1">
                <span class="text-white/60">UI/UX Designer :</span>
                <a href="https://www.linkedin.com/in/fawwazazhar" target="_blank" rel="noopener noreferrer" class="font-bold text-blue-400 hover:text-blue-300 transition-colors w-fit">
                    M Fawwaz Azhar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Script Modal -->
<script>
    function openCreditModal() {
        const modal = document.getElementById('creditModal');
        const modalContent = document.getElementById('creditModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeCreditModal() {
        const modal = document.getElementById('creditModal');
        const modalContent = document.getElementById('creditModalContent');
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }

    window.addEventListener('click', function(e) {
        const modal = document.getElementById('creditModal');
        if (e.target === modal) {
            closeCreditModal();
        }
    });

    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('creditModal');
            if (!modal.classList.contains('hidden')) {
                closeCreditModal();
            }
        }
    });
</script>