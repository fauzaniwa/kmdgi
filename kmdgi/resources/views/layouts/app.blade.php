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
        // Data Library Ikon & Warna SVG Berdasarkan Type Status
        const MODAL_THEMES = {
            success: {
                bg: 'bg-green-50', text: 'text-green-500', btn: 'bg-green-600 hover:bg-green-700 text-white',
                svg: '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />'
            },
            danger: {
                bg: 'bg-red-50', text: 'text-red-500', btn: 'bg-red-500 hover:bg-red-600 text-white',
                svg: '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />'
            },
            warning: {
                bg: 'bg-amber-50', text: 'text-amber-500', btn: 'bg-amber-500 hover:bg-amber-600 text-white',
                svg: '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />'
            },
            info: {
                bg: 'bg-blue-50', text: 'text-blue-500', btn: 'bg-kmdgi-primary hover:bg-kmdgi-hover text-white',
                svg: '<path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />'
            }
        };

        // FUNGSI UTAMA: MENYUNTIKKAN DATA TEKS SEBELUM DI-BUKA
        function openModal(id, triggerElement = null) {
            const container = document.getElementById(id);
            if (!container) return;

            // Jika dipicu oleh sebuah tombol yang membawa atribut data, suntikkan teksnya
            if (triggerElement) {
                const title = triggerElement.getAttribute('data-title') || '';
                const message = triggerElement.getAttribute('data-message') || '';
                const primaryText = triggerElement.getAttribute('data-primary-text') || 'Konfirmasi';
                const secondaryText = triggerElement.getAttribute('data-secondary-text') || '';
                const type = triggerElement.getAttribute('data-type') || container.getAttribute('data-default-type') || 'info';
                const formId = triggerElement.getAttribute('data-form-id') || null;

                // Suntikkan Judul & Pesan ke semua elemen ber-class terkait (Desktop & Mobile)
                container.querySelectorAll('.modal-title').forEach(el => el.innerText = title);
                container.querySelectorAll('.modal-message').forEach(el => el.innerText = message);

                // Konfigurasi Tombol Utama (Primary)
                container.querySelectorAll('.modal-primary-btn').forEach(btn => {
                    btn.innerText = primaryText;
                    btn.className = `modal-primary-btn font-bold py-3.5 md:py-3.5 px-4 rounded-[14px] transition-colors text-sm shadow-sm w-full flex-1 ${MODAL_THEMES[type].btn}`;
                    
                    // Bersihkan listener klik lama agar tidak menumpuk
                    const newBtn = btn.cloneNode(true);
                    btn.parentNode.replaceChild(newBtn, btn);

                    // Jika membawa formId, ikat fungsi submit form
                    if (formId) {
                        newBtn.addEventListener('click', () => {
                            newBtn.innerText = 'Memproses...';
                            document.getElementById(formId).submit();
                        });
                    } else {
                        // Jika tidak ada form, default tutup modal saat diklik
                        newBtn.addEventListener('click', () => closeModal(id));
                    }
                });

                // Konfigurasi Tombol Pembatalan (Secondary)
                container.querySelectorAll('.modal-secondary-btn').forEach(btn => {
                    if (secondaryText) {
                        btn.innerText = secondaryText;
                        btn.classList.remove('hidden');
                    } else {
                        btn.classList.add('hidden');
                    }
                });

                // Suntikkan Tema Warna & Ikon SVG
                container.querySelectorAll('.modal-icon-wrapper').forEach(wrapper => {
                    wrapper.className = `modal-icon-wrapper w-10 h-10 rounded-full flex items-center justify-center mb-5 border border-white shadow-sm flex-shrink-0 ${MODAL_THEMES[type].bg}`;
                });
                container.querySelectorAll('.modal-icon-svg').forEach(svg => {
                    svg.className = `modal-icon-svg w-5 h-5 ${MODAL_THEMES[type].text}`;
                    svg.innerHTML = MODAL_THEMES[type].svg;
                });
            }

            // Jalankan Animasi Terbuka (Sama seperti sebelumnya)
            container.classList.remove('hidden');
            setTimeout(() => {
                container.classList.remove('opacity-0');
                container.classList.add('opacity-100');

                const desktopBox = container.querySelector('.desktop-modal-box');
                if (desktopBox) { desktopBox.classList.remove('scale-95'); desktopBox.classList.add('scale-100'); }

                const mobileSheet = container.querySelector('.mobile-bottom-sheet');
                if (mobileSheet) { mobileSheet.classList.remove('translate-y-full'); mobileSheet.classList.add('translate-y-0'); }
            }, 10);
        }

        function closeModal(id) {
            const container = document.getElementById(id);
            if (!container) return;

            const desktopBox = container.querySelector('.desktop-modal-box');
            if (desktopBox) { desktopBox.classList.remove('scale-100'); desktopBox.classList.add('scale-95'); }

            const mobileSheet = container.querySelector('.mobile-bottom-sheet');
            if (mobileSheet) { mobileSheet.classList.remove('translate-y-0'); mobileSheet.classList.add('translate-y-full'); }

            container.classList.remove('opacity-100');
            container.classList.add('opacity-0');

            setTimeout(() => { container.classList.add('hidden'); }, 300);
        }
    </script>
</head>

<body class="font-sans text-slate-800 antialiased min-h-screen bg-slate-50">

    @yield('content')

    <x-modal id="kmdgi-global-modal" type="info" buttonLayout="horizontal" />

</body>
</html>