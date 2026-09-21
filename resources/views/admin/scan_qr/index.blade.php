@extends('layouts.app')
@section('title', 'Scanner Kehadiran - KMDGI 16')

@section('content')
<!-- Kontainer Fullscreen dengan background gelap -->
<div class="relative h-screen w-full bg-slate-950 overflow-hidden flex flex-col font-sans">
    
    <!-- Audio Assets (Pastikan link audio ini tidak diblokir browser, atau gunakan file lokal) -->
    <audio id="audio-success" src="https://assets.mixkit.co/active_storage/sfx/2013/2013-preview.mp3" preload="auto"></audio>
    <audio id="audio-error" src="https://assets.mixkit.co/active_storage/sfx/2954/2954-preview.mp3" preload="auto"></audio>

    <!-- Header Transparan (Tombol Kembali Dinamis) -->
    <div class="absolute top-0 left-0 right-0 z-40 p-4 sm:p-6 flex justify-between items-center bg-gradient-to-b from-slate-950/90 to-transparent">
        
        @php
            $backRoute = Auth::user()->role === 'super admin' ? route('superadmin.dashboard') : route('admin.dashboard');
        @endphp
        
        <a href="{{ $backRoute }}" class="flex items-center justify-center w-12 h-12 bg-white/10 hover:bg-white/25 backdrop-blur-md rounded-full text-white transition-all">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        </a>
        <div class="text-center">
            <h1 class="text-white font-black text-lg tracking-wide uppercase">KMDGI 16 Scanner</h1>
            <p class="text-slate-400 text-xs font-bold mt-0.5">Arahkan QR Code ke Layar</p>
        </div>
        <div class="w-12 h-12"></div> <!-- Spacer untuk alignment tengah -->
    </div>

    <!-- Area Kamera Scanner -->
    <div class="flex-grow w-full h-full flex items-center justify-center relative">
        <!-- Kotak Bantuan Visual Scan (Frame) -->
        <div class="absolute inset-0 z-30 pointer-events-none flex items-center justify-center">
            <div class="w-64 h-64 sm:w-80 sm:h-80 border-4 border-kmdgi-primary/80 rounded-[2rem] relative">
                <!-- Sudut-sudut ornamen UI -->
                <div class="absolute -top-1 -left-1 w-8 h-8 border-t-4 border-l-4 border-white rounded-tl-[2rem]"></div>
                <div class="absolute -top-1 -right-1 w-8 h-8 border-t-4 border-r-4 border-white rounded-tr-[2rem]"></div>
                <div class="absolute -bottom-1 -left-1 w-8 h-8 border-b-4 border-l-4 border-white rounded-bl-[2rem]"></div>
                <div class="absolute -bottom-1 -right-1 w-8 h-8 border-b-4 border-r-4 border-white rounded-br-[2rem]"></div>
            </div>
        </div>
        
        <!-- ID Div untuk injeksi kamera murni -->
        <div id="reader" class="w-full h-full object-cover [&>video]:object-cover [&>video]:w-full [&>video]:h-full"></div>
    </div>

    <!-- Footer: Input Manual (Menggunakan DIV, bukan FORM agar loading global tidak terpicu) -->
    <div class="absolute bottom-0 left-0 right-0 z-40 p-6 sm:p-8 bg-gradient-to-t from-slate-950 via-slate-950/90 to-transparent">
        <div id="manual-scan-container" class="max-w-md mx-auto w-full relative">
            <div class="flex gap-2 bg-white/10 backdrop-blur-md p-2 rounded-2xl border border-white/20">
                <input type="text" id="manual_kode_tiket" placeholder="KETIK KODE MANUAL (Cth: KM16...)" 
                    class="w-full bg-transparent px-4 py-3 text-white placeholder-slate-400 font-bold uppercase tracking-widest focus:outline-none" autocomplete="off">
                <button type="button" id="btn-manual-scan" class="bg-kmdgi-primary hover:bg-blue-600 text-white font-bold px-6 py-3 rounded-xl transition-colors shrink-0 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- POP-UP MODAL (Digunakan untuk Loading State & Hasil Scan) -->
    <div id="scan-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/85 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300 px-4">
        <div id="scan-content" class="w-full max-w-sm bg-white rounded-[2rem] shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col">
            <!-- Konten Modal Di-inject via JS -->
        </div>
    </div>

</div>

<!-- Script HTML5 QRCode -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let isProcessing = false;
    const modal = document.getElementById('scan-modal');
    const modalContent = document.getElementById('scan-content');
    
    const manualInput = document.getElementById('manual_kode_tiket');
    const btnManualScan = document.getElementById('btn-manual-scan');
    
    // Inisiasi Audio
    const audioSuccess = document.getElementById('audio-success');
    const audioError = document.getElementById('audio-error');

    // Menjalankan Kamera Otomatis dengan API Murni Html5Qrcode
    const html5QrCode = new Html5Qrcode("reader");
    const config = { fps: 15, qrbox: { width: 300, height: 300 }, aspectRatio: window.innerWidth / window.innerHeight };
    
    // Mulai kamera belakang (environment)
    html5QrCode.start({ facingMode: "environment" }, config, (decodedText) => {
        if(!isProcessing) processTicket(decodedText);
    }).catch(err => {
        console.log("Kamera tidak diizinkan atau tidak ditemukan.", err);
    });

    // Menangani Penekanan Tombol Input Manual
    function handleManualInput() {
        const code = manualInput.value.trim();
        if(code !== '') {
            if(!isProcessing) processTicket(code);
            manualInput.value = '';
            manualInput.blur();
        }
    }

    // Eksekusi ketika tombol panah (submit manual) ditekan
    btnManualScan.addEventListener('click', handleManualInput);

    // Eksekusi ketika tombol "Enter" di keyboard ditekan
    manualInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Cegah fungsi default browser
            handleManualInput();
        }
    });

    // Fungsi Logika Utama Tiket
    function processTicket(kodeTiket) {
        isProcessing = true; // Kunci scanner agar tidak dobel scan
        
        // 1. Render UI Loading di dalam Modal
        modalContent.innerHTML = `
            <div class="flex flex-col items-center justify-center p-10 bg-white">
                <svg class="animate-spin h-14 w-14 text-kmdgi-primary mb-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                <h3 class="font-bold text-slate-800 text-lg animate-pulse">Memverifikasi Tiket...</h3>
                <p class="text-slate-400 text-sm mt-1 font-mono tracking-wider">${kodeTiket}</p>
            </div>
        `;
        
        // Tampilkan Modal Loading
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);

        // 2. Fetch ke Server
        fetch("{{ route('admin.scan-qr.process') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({ kode_tiket: kodeTiket })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Audio Sukses
                audioSuccess.currentTime = 0; audioSuccess.play().catch(()=>{});
                
                // Ubah konten Modal menjadi Sukses
                modalContent.innerHTML = `
                    <div class="bg-emerald-500 p-8 flex flex-col items-center justify-center text-white shrink-0 relative">
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mb-4 shadow-lg relative z-10">
                            <svg class="w-10 h-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        </div>
                        <h2 class="text-2xl font-black tracking-tight relative z-10">AKSES DIIZINKAN</h2>
                        <span class="bg-emerald-600 px-3 py-1 rounded-full text-xs font-bold mt-2 relative z-10 uppercase tracking-widest">${data.data.jenis_tiket}</span>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 text-center">
                            <p class="text-xs text-slate-400 font-bold uppercase mb-1">Nama Peserta</p>
                            <p class="font-black text-lg text-slate-800 leading-tight">${data.data.nama_peserta}</p>
                            <p class="text-sm text-kmdgi-primary font-bold mt-0.5">${data.data.institusi}</p>
                        </div>
                        <div class="flex justify-between items-center text-sm font-semibold text-slate-600 px-2">
                            <span>Acara:</span> <span class="text-right text-slate-900">${data.data.nama_acara}</span>
                        </div>
                        <button onclick="closeModal()" class="w-full mt-4 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 rounded-xl transition-all uppercase tracking-wider text-sm shadow-md">Tutup & Lanjut</button>
                    </div>
                `;
            } else {
                // Audio Error
                audioError.currentTime = 0; audioError.play().catch(()=>{});

                // Ubah konten Modal menjadi Error
                modalContent.innerHTML = `
                    <div class="bg-red-500 p-8 flex flex-col items-center justify-center text-white shrink-0 relative">
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mb-4 shadow-lg relative z-10">
                            <svg class="w-10 h-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                        </div>
                        <h2 class="text-2xl font-black tracking-tight relative z-10">AKSES DITOLAK</h2>
                    </div>
                    <div class="p-6">
                        <div class="bg-red-50 p-4 rounded-2xl border border-red-100 mb-6 text-center">
                            <p class="text-red-600 font-semibold text-sm leading-relaxed">${data.message}</p>
                        </div>
                        <button onclick="closeModal()" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-xl transition-all uppercase tracking-wider text-sm shadow-md">Tutup</button>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error("Fetch Error:", error);
            modalContent.innerHTML = `
                <div class="p-8 text-center bg-white">
                    <p class="text-red-500 font-bold mb-6">Terjadi kesalahan jaringan atau koneksi.</p>
                    <button onclick="closeModal()" class="w-full bg-slate-900 text-white px-6 py-3.5 rounded-xl font-bold uppercase text-sm">Tutup</button>
                </div>
            `;
        });
    }

    // Fungsi Menutup Modal & Membuka Kunci Scanner Kembali
    window.closeModal = function() {
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            isProcessing = false; // Scanner siap digunakan lagi!
        }, 300);
    }
});
</script>

<style>
/* Reset khusus untuk reader agar rapi & imersif */
#reader img { display: none !important; }
#reader video { 
    object-fit: cover; 
    width: 100vw; 
    height: 100vh; 
    position: absolute; 
    top: 0; 
    left: 0; 
}
/* Menyembunyikan elemen bawaan html5-qrcode yang tidak diperlukan */
#qr-canvas, #reader__dashboard_section_csr, #reader__dashboard_section_swaplink { display: none !important; }
</style>
@endsection