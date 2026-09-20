<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KMDGI 16')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"SN Pro"', '"Source Sans 3"', 'sans-serif'],
                    },
                    colors: {
                        kmdgi: {
                            primary: '#126CFD',
                            hover: '#0D55D0',
                            bgRight: '#F0F6FF',
                            primaryblack: '#2B2B2B',
                            50: '#F0F6FF',
                            100: '#E1EDFF',
                            200: '#BDDAFF',
                        }
                    }
                }
            }
        }
    </script>

    <script>
        const MODAL_THEMES = {
            success: {
                bg: 'bg-green-50',
                text: 'text-green-500',
                btn: 'bg-green-600 hover:bg-green-700 text-white',
                svg: '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />'
            },
            danger: {
                bg: 'bg-red-50',
                text: 'text-red-500',
                btn: 'bg-red-500 hover:bg-red-600 text-white',
                svg: '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />'
            },
            warning: {
                bg: 'bg-amber-50',
                text: 'text-amber-500',
                btn: 'bg-amber-500 hover:bg-amber-600 text-white',
                svg: '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />'
            },
            info: {
                bg: 'bg-blue-50',
                text: 'text-blue-500',
                btn: 'bg-kmdgi-primary hover:bg-kmdgi-hover text-white',
                svg: '<path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />'
            }
        };

        // --- GLOBAL LOADING LOGIC DIPERBAIKI ---
        function showGlobalLoading() {
            const overlay = document.getElementById('kmdgi-loading-overlay');
            const box = overlay.querySelector('.loading-box');
            if (!overlay) return;

            // Hapus hidden dan paksa jadi flex agar penengah layar berfungsi
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');

            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
            }, 10);
        }

        function hideGlobalLoading() {
            const overlay = document.getElementById('kmdgi-loading-overlay');
            const box = overlay.querySelector('.loading-box');
            if (!overlay) return;

            overlay.classList.add('opacity-0');
            box.classList.remove('scale-100');
            box.classList.add('scale-95');

            setTimeout(() => {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex'); // Kembalikan state awal
            }, 300);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (this.checkValidity()) {
                        showGlobalLoading();
                    }
                });
            });
        });

        // --- GLOBAL CONFIRMATION MODAL LOGIC ---
        function openModal(id, triggerElement = null) {
            const container = document.getElementById(id);
            if (!container) return;

            if (triggerElement) {
                const title = triggerElement.getAttribute('data-title') || '';
                const message = triggerElement.getAttribute('data-message') || '';
                const primaryText = triggerElement.getAttribute('data-primary-text') || 'Konfirmasi';
                const secondaryText = triggerElement.getAttribute('data-secondary-text') || '';
                const type = triggerElement.getAttribute('data-type') || container.getAttribute('data-default-type') || 'info';
                const formId = triggerElement.getAttribute('data-form-id') || null;

                container.querySelectorAll('.modal-title').forEach(el => el.innerText = title);
                container.querySelectorAll('.modal-message').forEach(el => el.innerText = message);

                container.querySelectorAll('.modal-primary-btn').forEach(btn => {
                    btn.innerText = primaryText;
                    btn.className = `modal-primary-btn font-bold py-3.5 md:py-3.5 px-4 rounded-[14px] transition-colors text-sm shadow-sm w-full flex-1 ${MODAL_THEMES[type].btn}`;

                    const newBtn = btn.cloneNode(true);
                    btn.parentNode.replaceChild(newBtn, btn);

                    if (formId) {
                        newBtn.addEventListener('click', () => {
                            const targetForm = document.getElementById(formId);
                            if (targetForm) {
                                closeModal(id);
                                setTimeout(() => {
                                    showGlobalLoading();
                                    targetForm.submit();
                                }, 300);
                            }
                        });
                    } else {
                        newBtn.addEventListener('click', () => closeModal(id));
                    }
                });

                container.querySelectorAll('.modal-secondary-btn').forEach(btn => {
                    if (secondaryText) {
                        btn.innerText = secondaryText;
                        btn.classList.remove('hidden');
                    } else {
                        btn.classList.add('hidden');
                    }
                });

                container.querySelectorAll('.modal-icon-wrapper').forEach(wrapper => {
                    wrapper.className = `modal-icon-wrapper w-10 h-10 rounded-full flex items-center justify-center mb-5 border border-white shadow-sm flex-shrink-0 ${MODAL_THEMES[type].bg}`;
                });
                container.querySelectorAll('.modal-icon-svg').forEach(svg => {
                    svg.className = `modal-icon-svg w-5 h-5 ${MODAL_THEMES[type].text}`;
                    svg.innerHTML = MODAL_THEMES[type].svg;
                });
            }

            container.classList.remove('hidden');
            setTimeout(() => {
                container.classList.remove('opacity-0');
                container.classList.add('opacity-100');

                const desktopBox = container.querySelector('.desktop-modal-box');
                if (desktopBox) {
                    desktopBox.classList.remove('scale-95');
                    desktopBox.classList.add('scale-100');
                }

                const mobileSheet = container.querySelector('.mobile-bottom-sheet');
                if (mobileSheet) {
                    mobileSheet.classList.remove('translate-y-full');
                    mobileSheet.classList.add('translate-y-0');
                }
            }, 10);
        }

        function closeModal(id) {
            const container = document.getElementById(id);
            if (!container) return;

            const desktopBox = container.querySelector('.desktop-modal-box');
            if (desktopBox) {
                desktopBox.classList.remove('scale-100');
                desktopBox.classList.add('scale-95');
            }

            const mobileSheet = container.querySelector('.mobile-bottom-sheet');
            if (mobileSheet) {
                mobileSheet.classList.remove('translate-y-0');
                mobileSheet.classList.add('translate-y-full');
            }

            container.classList.remove('opacity-100');
            container.classList.add('opacity-0');

            setTimeout(() => {
                container.classList.add('hidden');
            }, 300);
        }
    </script>
</head>

<body class="font-sans text-slate-800 antialiased min-h-screen bg-slate-50">

    @yield('content')

    <x-modal id="kmdgi-global-modal" type="info" buttonLayout="horizontal" />

    <!-- ============================================== -->
    <!-- GLOBAL LOADING OVERLAY (DIPERBAIKI)            -->
    <!-- ============================================== -->
    <div id="kmdgi-loading-overlay" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/50 backdrop-blur-[2px] opacity-0 transition-opacity duration-300 pointer-events-auto">
        <!-- Kotak Loading dengan dimensi pas dan berada tepat di tengah -->
        <div class="bg-white p-8 rounded-[2rem] shadow-2xl flex flex-col items-center justify-center gap-5 transform scale-95 transition-transform duration-300 loading-box min-w-[220px] min-h-[180px] max-w-[80vw]">

            <div class="relative w-14 h-14 flex items-center justify-center">
                <!-- Ring Spinner -->
                <svg class="animate-spin absolute inset-0 w-full h-full text-kmdgi-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3.5"></circle>
                    <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <!-- Indikator Tengah -->
                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center relative z-10">
                    <div class="w-2.5 h-2.5 bg-kmdgi-primary rounded-full animate-pulse"></div>
                </div>
            </div>

            <div class="text-center">
                <h3 class="text-base font-bold text-slate-900 leading-tight">Memproses Data</h3>
                <p class="text-xs text-slate-500 mt-1">Mohon tunggu sebentar...</p>
            </div>

        </div>
    </div>

    @auth
    <!-- Container untuk Pop-Up (Toast) Notifikasi -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-[9999] flex flex-col gap-3 pointer-events-none"></div>

    <!-- PUSHER JS (Tanpa perlu NPM/Vite) -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Konfigurasi Kredensial Pusher (Perbaikan spasi pada Blade directives)
            var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                authEndpoint: '/broadcasting/auth', // Menggunakan auth bawaan Laravel
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            });

            // 2. Subscribe ke Private Channel milik User ini
            var channel = pusher.subscribe('private-App.Models.User.{{ Auth::id() }}');

            // 3. Dengarkan event notifikasi dari Laravel
            channel.bind('Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', function(data) {

                // Saat ada event masuk, panggil fungsi untuk membuat Pop-Up Toast
                showRealtimeToast(data.title, data.message, data.type, data.url);

                // Opsional: Ubah warna dot merah di navbar secara otomatis
                const notifDot = document.querySelector('a[href="{{ route('notifikasi.index') }}"] span');
                if (notifDot) {
                    notifDot.classList.remove('hidden'); // Memunculkan dot merah
                }
            });

            // 4. Fungsi membuat elemen UI Toast menggunakan Tailwind
            function showRealtimeToast(title, message, type, url) {
                const container = document.getElementById('toast-container');

                // Tentukan warna ikon berdasarkan tipe notifikasi
                let iconColor = 'text-blue-500';
                let bgColor = 'bg-blue-100';
                let svgIcon = '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';

                if (type === 'success') {
                    iconColor = 'text-emerald-500';
                    bgColor = 'bg-emerald-100';
                    svgIcon = '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                } else if (type === 'warning') {
                    iconColor = 'text-amber-500';
                    bgColor = 'bg-amber-100';
                    svgIcon = '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
                }

                // Membangun elemen HTML
                const toast = document.createElement('div');
                // Tambahkan class pointer-events-auto agar toast bisa di-klik meskipun container-nya pointer-events-none
                toast.className = 'w-80 bg-white shadow-2xl rounded-2xl border border-slate-100 p-4 transform transition-all duration-300 translate-y-10 opacity-0 flex items-start gap-4 pointer-events-auto';

                let urlAction = url ? `<a href="${url}" class="text-xs font-bold text-kmdgi-primary mt-2 inline-block hover:underline">Lihat Detail &rarr;</a>` : '';

                toast.innerHTML = `
                    <div class="flex-shrink-0 w-10 h-10 ${bgColor} ${iconColor} rounded-full flex items-center justify-center">
                        ${svgIcon}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-slate-900">${title}</h4>
                        <p class="text-xs text-slate-500 mt-0.5 line-clamp-2">${message}</p>
                        ${urlAction}
                    </div>
                    <button class="text-slate-400 hover:text-red-500 flex-shrink-0 focus:outline-none" onclick="this.parentElement.remove()">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                `;

                container.appendChild(toast);

                // Animasi masuk (slide in dari bawah)
                // Menggunakan requestAnimationFrame untuk memastikan browser me-render DOM sebelum animasi
                requestAnimationFrame(() => {
                    setTimeout(() => {
                        toast.classList.remove('translate-y-10', 'opacity-0');
                    }, 10);
                });

                // Hilang otomatis setelah 6 detik
                setTimeout(() => {
                    toast.classList.add('translate-y-10', 'opacity-0');
                    setTimeout(() => toast.remove(), 300);
                }, 6000);
            }
        });
    </script>
    @endauth
</body>

</html>