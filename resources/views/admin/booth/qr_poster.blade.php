<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poster QR A4 - {{ $booth->institusi }} - KMDGI 16</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800;900&display=swap" rel="stylesheet">
    <!-- Library QR Code dengan modul titik & pil membulat -->
    <script src="https://unpkg.com/qr-code-styling@1.6.0-rc.1/lib/qr-code-styling.js"></script>
    <!-- Library Export ke Gambar PNG A4 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0f172a;
        }

        /* Ukuran Presisi Kertas A4 Portrait */
        .a4-sheet {
            width: 210mm;
            height: 297mm;
            position: relative;
            overflow: hidden;
            background-color: #1554c7;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.55);
        }

        #qr-canvas-container canvas,
        #qr-canvas-container svg {
            width: 100% !important;
            height: 100% !important;
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
    <div class="no-print fixed top-5 z-50 bg-slate-900/95 backdrop-blur-md border border-white/15 px-5 py-3 rounded-2xl shadow-2xl flex flex-wrap items-center justify-center gap-3 text-white">
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

        <a href="{{ $templatePath }}" download="template_qr_kmdgi16.jpg" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold px-4 py-2 rounded-xl text-xs transition-all">
            🖼 Unduh Template Kosong
        </a>
    </div>

    <!-- ========================================================== -->
    <!-- KANVAS POSTER A4 BERBASIS GAMBAR template_qr.png           -->
    <!-- ========================================================== -->
    <div id="poster-a4" class="a4-sheet select-none mt-14">

        <!-- 1. Background Image: template_qr.png -->
        <img src="{{ $templatePath }}"
             alt="Template QR A4"
             crossorigin="anonymous"
             class="absolute inset-0 w-full h-full object-cover z-0 pointer-events-none">

        <!-- 2. Posisi QR Code di Tengah Lingkaran Putih Template -->
        <div class="absolute inset-x-0 z-10 flex flex-col items-center justify-center" style="top: 19.5%; height: 44%;">
            <div id="qr-canvas-container" class="w-[95mm] h-[95mm] flex items-center justify-center"></div>
        </div>

        <!-- 3. Posisi Judul Karya & Nama Perguruan Tinggi di Area Bawah Template -->
        <div class="absolute inset-x-0 z-10 flex flex-col items-center justify-start text-center px-[14%]" style="top: 72.5%;">
            <h1 class="text-[22pt] font-extrabold text-white uppercase tracking-wide leading-tight break-words drop-shadow-sm">
                {{ $booth->judul_karya_tematik ?: '(JUDUL KARYA)' }}
            </h1>
            <h2 class="text-[17.5pt] font-bold text-white/95 mt-1.5 leading-snug">
                {{ $booth->institusi }}
            </h2>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const qrCode = new QRCodeStyling({
                width: 720,
                height: 720,
                type: "canvas",
                data: "{{ $voteUrl }}",
                margin: 0,
                qrOptions: {
                    errorCorrectionLevel: "M"
                },
                dotsOptions: {
                    color: "#222222",
                    type: "extra-rounded"
                },
                backgroundOptions: {
                    color: "transparent"
                },
                cornersSquareOptions: {
                    color: "#222222",
                    type: "extra-rounded"
                },
                cornersDotOptions: {
                    color: "#222222",
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