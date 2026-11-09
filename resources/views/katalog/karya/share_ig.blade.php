<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Instagram - {{ $karya->judul }} - KMDGI 16</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- QR Code Styling Library -->
    <script src="https://unpkg.com/qr-code-styling@1.6.0-rc.1/lib/qr-code-styling.js"></script>
    <!-- Html2Canvas untuk unduh gambar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0f172a;
        }
        /* Rasio 16:9 Story Format (1080 x 1920 pixels ratio) */
        .ig-story-card {
            width: 405px;
            height: 720px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #0D63F8 0%, #1554c7 50%, #08328c 100%);
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4">

    <!-- Toolbar Atas (Tidak ikut terunduh) -->
    <div class="no-print mb-6 bg-slate-900/90 backdrop-blur-md border border-white/15 px-6 py-3 rounded-2xl shadow-2xl flex flex-wrap items-center justify-center gap-4 text-white">
        <div>
            <span class="text-xs font-extrabold block text-lime-400">Instagram Story Template (16:9)</span>
            <span class="text-[11px] text-slate-300">Siap unduh untuk dibagikan ke Instagram</span>
        </div>
        <button onclick="downloadStoryImage()" id="btn-download" class="bg-lime-400 hover:bg-lime-300 text-slate-950 font-extrabold px-5 py-2.5 rounded-xl text-xs transition-all shadow-md">
            ⬇ Unduh Gambar Story (PNG)
        </button>
        <button onclick="copyDefaultCaption()" class="bg-[#126CFD] hover:bg-blue-500 text-white font-extrabold px-5 py-2.5 rounded-xl text-xs transition-all shadow-md">
            📋 Salin Caption Default
        </button>
        <a href="{{ url()->previous() }}" class="bg-white/10 hover:bg-white/20 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-all">
            ← Kembali
        </a>
    </div>

    <!-- ========================================== -->
    <!-- KARTU TEMPLATE INSTAGRAM STORY (16:9)      -->
    <!-- ========================================== -->
    <div id="ig-story-container" class="ig-story-card flex flex-col justify-between p-6 text-white select-none">

        <!-- Ornamen Header / Logo KMDGI -->
        <div class="flex items-center justify-between z-10">
            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/20">
                <span class="w-2 h-2 rounded-full bg-lime-400 animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-widest text-white">KMDGI 16 • HAWK!</span>
            </div>
            <span class="text-xs font-extrabold uppercase tracking-widest text-blue-200">#MostPopularArtwork</span>
        </div>

        <!-- Bagian Tengah: Foto Karya Utama -->
        <div class="relative z-10 w-full aspect-[4/4] rounded-3xl overflow-hidden bg-slate-900 shadow-2xl border-4 border-white/20 my-auto">
            <img src="{{ $karya->thumb }}" alt="{{ $karya->judul }}" crossorigin="anonymous" class="w-full h-full object-cover">
            <div class="absolute top-3 right-3 bg-black/60 backdrop-blur-md px-3 py-1 rounded-full border border-white/20">
                <span class="text-[10px] font-bold text-lime-300 uppercase">{{ $karya->kategori }}</span>
            </div>
        </div>

        <!-- Bagian Bawah: Informasi Detail, Kampus, & QR Code -->
        <div class="relative z-10 bg-slate-950/70 backdrop-blur-md rounded-3xl p-5 border border-white/15 flex items-end justify-between gap-4">
            
            <!-- Info Teks (Judul, Kreator, Kampus) -->
            <div class="space-y-1.5 min-w-0 pr-2">
                <h1 class="text-lg font-black tracking-tight leading-snug line-clamp-2 text-white">
                    “{{ $karya->judul }}”
                </h1>
                <div class="flex items-center gap-2 pt-0.5">
                    @if($karya->logo)
                        <div class="w-5 h-5 rounded-full bg-white p-0.5 flex-shrink-0 overflow-hidden flex items-center justify-center">
                            <img src="{{ $karya->logo }}" crossorigin="anonymous" class="w-full h-full object-contain">
                        </div>
                    @endif
                    <span class="text-xs font-extrabold text-blue-300 truncate">{{ $karya->institusi }}</span>
                </div>
                <p class="text-[11px] text-slate-300 truncate">Kreator: {{ $karya->kreator }}</p>
                <div class="pt-2 flex items-center gap-1.5 text-[10px] font-extrabold text-lime-400">
                    <span>Scan QR atau kunjungi link untuk vote!</span>
                </div>
            </div>

            <!-- QR Code di Pojok Kanan Bawah -->
            <div class="bg-white p-2 rounded-2xl shadow-lg flex-shrink-0 flex items-center justify-center">
                <div id="story-qr-code" class="w-20 h-20"></div>
            </div>

        </div>

    </div>

    <!-- Hidden Caption Default untuk Disalin -->
    <textarea id="default-caption" class="hidden">Halo teman-teman! 👋 

Dukung karya luar biasa berjudul "={{ $karya->judul }}" oleh {{ $karya->kreator }} dari {{ $karya->institusi }} dalam ajang Most Popular Artwork KMDGI 16! 🎨✨

Satu akun, satu suara emas dari kamu sangat berarti untuk menentukan pemenang People's Choice Award 2026. 

Yuk berikan suara terbaikmu melalui tautan berikut:
{{ $karya->url }}

#KMDGI16 #MostPopularArtwork #DesainGrafis #KMDGI #HAYUK</textarea>

    <script>
        // Render QR Code untuk Story IG
        document.addEventListener('DOMContentLoaded', function () {
            const qrCode = new QRCodeStyling({
                width: 250,
                height: 250,
                type: "canvas",
                data: "{{ $karya->url }}",
                margin: 0,
                dotsOptions: {
                    color: "#111827",
                    type: "extra-rounded"
                },
                backgroundOptions: {
                    color: "#ffffff"
                },
                cornersSquareOptions: {
                    color: "#111827",
                    type: "extra-rounded"
                }
            });

            qrCode.append(document.getElementById("story-qr-code"));
        });

        // Fungsi Salin Caption Default
        function copyDefaultCaption() {
            const textarea = document.getElementById('default-caption');
            textarea.classList.remove('hidden');
            textarea.select();
            document.execCommand('copy');
            textarea.classList.add('hidden');
            alert('✓ Caption default berhasil disalin! Silakan tempel (paste) saat mengunggah story Instagram.');
        }

        // Fungsi Unduh Gambar Story 16:9
        function downloadStoryImage() {
            const btn = document.getElementById('btn-download');
            const originalText = btn.innerText;
            btn.innerText = 'Memproses Gambar...';
            btn.disabled = true;

            const element = document.getElementById('ig-story-container');
            html2canvas(element, {
                scale: 3,
                useCORS: true,
                allowTaint: true,
                backgroundColor: null
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'IG_Story_KMDGI16_{{ \Illuminate\Support\Str::slug($karya->judul) }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();

                btn.innerText = originalText;
                btn.disabled = false;
            }).catch(err => {
                console.error(err);
                alert('Gagal mengunduh gambar.');
                btn.innerText = originalText;
                btn.disabled = false;
            });
        }
    </script>
</body>
</html>