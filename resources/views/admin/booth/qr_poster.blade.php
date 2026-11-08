<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poster QR A4 - {{ $booth->institusi }} - KMDGI 16</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Library QR Code dengan modul titik & pil membulat sesuai desain -->
    <script src="https://unpkg.com/qr-code-styling@1.6.0-rc.1/lib/qr-code-styling.js"></script>
    <!-- Library Export ke Gambar PNG A4 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0f172a;
        }

        /* Ukuran Presisi Kertas A4 Portrait (210mm x 297mm) */
        .a4-sheet {
            width: 210mm;
            height: 297mm;
            position: relative;
            overflow: hidden;
            background-color: #1d70f2;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.55);
        }

        #qr-canvas-container canvas,
        #qr-canvas-container svg {
            width: 100% !important;
            height: 100% !important;
            display: block;
        }

        @page {
            size: A4 portrait;
            margin: 0;
        }

        @media print {
            body {
                background: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .a4-sheet {
                box-shadow: none !important;
                margin: 0 !important;
                width: 210mm !important;
                height: 297mm !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center py-8">

    @php
        $templatePath = file_exists(public_path('images/template_qr.jpg'))
            ? asset('images/template_qr.jpg')
            : asset('template_qr.jpg');
    @endphp

    <!-- Toolbar Aksi (Tidak ikut tercetak saat Print/PDF) -->
    <div class="no-print fixed top-4 z-50 bg-slate-900/95 backdrop-blur-md border border-white/15 px-5 py-3 rounded-2xl shadow-2xl flex flex-wrap items-center justify-center gap-3 text-white">
        <div class="pr-3 border-r border-white/15">
            <span class="text-xs font-extrabold block text-lime-400">Poster QR Booth (Ukuran A4)</span>
            <span class="text-[11px] text-slate-300">{{ $booth->institusi }}</span>
        </div>

        <button onclick="downloadPosterPNG()" id="btn-download-png" class="bg-lime-400 hover:bg-lime-300 text-slate-950 font-extrabold px-4 py-2 rounded-xl text-xs transition-all">
            ⬇ Unduh Poster QR (PNG A4)
        </button>

        <button onclick="window.print()" class="bg-[#126CFD] hover:bg-blue-500 text-white font-extrabold px-4 py-2 rounded-xl text-xs transition-all">
            🖨️ Print / Simpan PDF (A4)
        </button>

        <a href="{{ $templatePath }}" download="template_qr_kmdgi16.png" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold px-4 py-2 rounded-xl text-xs transition-all">
            🖼 Unduh Template Kosong
        </a>

        <!-- Toggle aktif secara default (checked) -->
        <label class="inline-flex items-center gap-2 text-[11px] text-slate-300 cursor-pointer pl-2 border-l border-white/15">
            <input type="checkbox" id="toggle-footer-text" checked onchange="document.getElementById('tagline-bawah').classList.toggle('hidden', !this.checked)" class="rounded text-lime-400">
            <span>Tampilkan Teks Bawah</span>
        </label>
    </div>

    <!-- ========================================================== -->
    <!-- KANVAS POSTER A4 BERBASIS GAMBAR template_qr.jpg           -->
    <!-- ========================================================== -->
    <div id="poster-a4" class="a4-sheet select-none mt-16">

        <!-- 1. Background Image: template_qr.jpg -->
        <img src="{{ $templatePath }}"
             alt="Template QR A4"
             crossorigin="anonymous"
             class="absolute inset-0 w-full h-full object-fill z-0 pointer-events-none">

        <!-- 2. Posisi QR Code Presisi di Tengah Lingkaran Putih (Center Y = 40.3%) -->
        <div class="absolute z-10 flex items-center justify-center"
             style="top: 40.3%; left: 50%; transform: translate(-50%, -50%); width: 112mm; height: 112mm;">
            <div id="qr-canvas-container" class="w-full h-full flex items-center justify-center"></div>
        </div>

        <!-- 3. Posisi Teks (JUDUL KARYA) & Nama Perguruan Tinggi di Area Trapesium Hitam -->
        <!-- Area Trapesium berada di top: 72.2% s/d 86.5% (tinggi 14.3%) dan lebar 74% di tengah -->
        <div class="absolute inset-x-0 z-10 flex flex-col items-center justify-center text-center mx-auto"
             style="top: 72.2%; height: 14.3%; width: 74%;">
            
            @php
                $judulKarya = $booth->judul_karya_tematik ?: '(JUDUL KARYA)';
                $lenJudul = mb_strlen($judulKarya);
                // Penyesuaian ukuran font otomatis agar tidak keluar dari trapesium
                $fontJudulClass = $lenJudul > 55 ? 'text-[16pt]' : ($lenJudul > 32 ? 'text-[19pt]' : 'text-[23pt]');
                
                $lenKampus = mb_strlen($booth->institusi);
                $fontKampusClass = $lenKampus > 40 ? 'text-[14.5pt]' : 'text-[18pt]';
            @endphp

            <h1 class="{{ $fontJudulClass }} font-extrabold text-white tracking-wide leading-[1.18] break-words line-clamp-2">
                {{ $judulKarya }}
            </h1>

            <h2 class="{{ $fontKampusClass }} font-bold text-white mt-2 leading-snug tracking-normal">
                {{ $booth->institusi }}
            </h2>
        </div>

        <!-- 4. Teks Kalimat Ajakan Bawah (Aktif secara default) -->
        <div id="tagline-bawah"
             class="absolute inset-x-0 z-10 flex flex-col items-center justify-center text-center px-12"
             style="top: 87.5%; height: 10.5%;">
            <p class="text-[14.5pt] font-medium leading-relaxed text-white/95">
                Pilih karya instalasi favoritmu dan berikan suaramu untuk<br>
                nominasi <span class="font-extrabold text-[#84cc16]">Most Popular Artwork!</span>
            </p>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const qrCode = new QRCodeStyling({
                width: 900,
                height: 900,
                type: "canvas",
                data: "{{ $voteUrl }}",
                margin: 0,
                qrOptions: {
                    errorCorrectionLevel: "M"
                },
                dotsOptions: {
                    color: "#232323",
                    type: "extra-rounded"
                },
                backgroundOptions: {
                    color: "transparent"
                },
                cornersSquareOptions: {
                    color: "#232323",
                    type: "extra-rounded"
                },
                cornersDotOptions: {
                    color: "#232323",
                    type: "dot"
                }
            });

            qrCode.append(document.getElementById("qr-canvas-container"));
        });

        function downloadPosterPNG() {
            const btn = document.getElementById('btn-download-png');
            const originalText = btn.innerText;
            btn.innerText = 'Memproses Gambar...';
            btn.disabled = true;

            const element = document.getElementById('poster-a4');
            html2canvas(element, {
                scale: 2.5,
                useCORS: true,
                allowTaint: true,
                backgroundColor: null
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'Poster_QR_A4_{{ \Illuminate\Support\Str::slug($booth->institusi) }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();

                btn.innerText = originalText;
                btn.disabled = false;
            });
        }
    </script>
</body>
</html>