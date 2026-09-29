@extends('layouts.app')
@section('title', 'Peta Interaktif - KMDGI 16')
@section('meta')
<!-- Standard SEO Meta Tags -->
<meta name="description" content="Jelajahi venue dan denah lokasi pameran KMDGI 16 secara interaktif dalam bentuk 3D. Temukan panggung utama, booth pameran delegasi, hingga pasar kreatif.">
<meta name="keywords" content="Peta 3D KMDGI 16, Denah KMDGI 16, Interactive Map KMDGI, Venue KMDGI 16">

<!-- Open Graph / Facebook / WhatsApp -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="Peta Interaktif 3D - KMDGI 16">
<meta property="og:description" content="Jelajahi venue dan denah lokasi pameran KMDGI 16 secara interaktif dalam bentuk 3D. Temukan panggung utama, booth pameran delegasi, hingga pasar kreatif.">
<meta property="og:image" content="{{ asset('images/map-placeholder.png') }}">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="Peta Interaktif 3D - KMDGI 16">
<meta name="twitter:description" content="Jelajahi venue dan denah lokasi pameran KMDGI 16 secara interaktif dalam bentuk 3D. Temukan panggung utama, booth pameran delegasi, hingga pasar kreatif.">
<meta name="twitter:image" content="{{ asset('images/map-placeholder.png') }}">
@endsection
@section('content')
<div class="bg-white min-h-screen pb-20">
    <!-- Navbar -->
    @include('partials.navbar')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 font-sans relative">

        <!-- Tombol Back -->
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-[#1A68FF] transition-colors mb-6 group">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Beranda
        </a>

        <!-- Header Halaman -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Peta Interaktif KMDGI 16</h1>
                <p class="text-slate-500 mt-2 text-sm md:text-base max-w-2xl">
                    Jelajahi denah lokasi pameran secara interaktif. Temukan area panggung utama, ruang pameran delegasi, hingga tenant kuliner.
                </p>
            </div>
        </div>

        <!-- 
          CONTAINER MAPS 3D 
          ID "map-3d-container" ini nanti yang akan Anda panggil di JavaScript (Three.js / WebGL) 
          untuk me-render scene Blender Anda.
        -->
        <div class="relative w-full aspect-[4/3] md:aspect-[21/9] bg-slate-50 border border-slate-200 rounded-[1.5rem] overflow-hidden mb-10 shadow-sm flex items-center justify-center group" id="map-3d-container">

            <!-- Placeholder sebelum 3D di-load (Opsional: bisa pakai gambar denah 2D sementara) -->
            <img src="{{ asset('images/map-placeholder.jpg') }}" alt="Denah KMDGI 16" class="w-full h-full object-cover opacity-50">

            <!-- Overlay Informasi (Bisa dihilangkan saat script 3D sudah berjalan) -->
            
            <div id="map-loading-overlay" class="absolute inset-0 flex flex-col items-center justify-center bg-white/40 backdrop-blur-sm z-20 transition-opacity duration-500">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-md mb-4 animate-bounce">
                    <svg class="w-8 h-8 text-[#1A68FF]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="bg-white/90 text-slate-800 text-sm font-bold px-6 py-2 rounded-full border border-slate-200 shadow-sm">
                    Interactive 3D Map Loading...
                </span>
            </div>

            <!-- Canvas untuk WebGL / Three.js nanti letakkan di sini -->
            <!-- <canvas id="webgl-canvas" class="absolute inset-0 w-full h-full"></canvas> -->
        </div>

        <!-- Legenda / Keterangan Area -->
        <h3 class="text-xl font-bold text-slate-900 mb-6">Area Utama Pameran</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">

            <!-- Card 1 -->
            <div class="border border-slate-100 rounded-[1.5rem] bg-white p-6 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-black text-lg mb-4">
                    1
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-2">Panggung Utama</h4>
                <p class="text-[13px] text-slate-500 leading-relaxed">Pusat seremoni pembukaan, pertunjukan musik, dan malam penganugerahan (Awarding Night).</p>
            </div>

            <!-- Card 2 -->
            <div class="border border-slate-100 rounded-[1.5rem] bg-white p-6 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300">
                <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center font-black text-lg mb-4">
                    2
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-2">Hall Delegasi</h4>
                <p class="text-[13px] text-slate-500 leading-relaxed">Area eksibisi yang memamerkan karya visual dari seluruh kampus delegasi se-Indonesia.</p>
            </div>

            <!-- Card 3 -->
            <div class="border border-slate-100 rounded-[1.5rem] bg-white p-6 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300">
                <div class="w-10 h-10 bg-pink-50 text-pink-600 rounded-xl flex items-center justify-center font-black text-lg mb-4">
                    3
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-2">Pasar Kreatif</h4>
                <p class="text-[13px] text-slate-500 leading-relaxed">Kawasan komersial untuk tenant FnB, merchandise resmi KMDGI, dan booth kolaborator.</p>
            </div>

            <!-- Card 4 -->
            <div class="border border-slate-100 rounded-[1.5rem] bg-white p-6 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300">
                <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center font-black text-lg mb-4">
                    4
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-2">Pusat Bantuan</h4>
                <p class="text-[13px] text-slate-500 leading-relaxed">Titik registrasi ulang, penukaran tiket, pos medis (P3K), dan pusat informasi pengunjung.</p>
            </div>

        </div>

        <!-- Google Maps (Penunjuk Jalan Real) -->
        <div class="border border-slate-100 rounded-[1.5rem] bg-white p-6 md:p-8 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Petunjuk Arah (Google Maps)</h3>
                    <p class="text-[13px] md:text-sm text-slate-500 mt-1">Gunakan rute ini untuk menuju lokasi acara secara langsung.</p>
                </div>
                <a href="https://maps.google.com" target="_blank" class="inline-flex items-center gap-2 bg-[#1A68FF] hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-md shadow-blue-500/20 self-start md:self-auto">
                    Buka di Aplikasi
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>

            <!-- Frame Embed -->
            <div class="w-full h-[300px] rounded-xl overflow-hidden border border-slate-200 bg-slate-100">
                <!-- Ganti src di bawah dengan embed link asli dari tempat acara -->
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3264.931742959797!2d107.58950847403462!3d-6.861295967126702!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6b943c2c5ff%3A0xee36226510a79e76!2sUniversitas%20Pendidikan%20Indonesia!5e1!3m2!1sid!2sid!4v1790669411465!5m2!1sid!2sid"
                    class="w-full h-full border-0"
                    allowfullscreen=""
                    loading="lazy">
                </iframe>
            </div>
        </div>

    </div>
</div>

<!-- Footer -->
@include('partials.footer')

<!-- SCRIPT IMPORT MAP UNTUK THREE.JS -->
<script type="importmap">
    {
    "imports": {
      "three": "https://unpkg.com/three@0.160.0/build/three.module.js",
      "three/addons/": "https://unpkg.com/three@0.160.0/examples/jsm/"
    }
  }
</script>

<!-- SCRIPT LOGIKA 3D -->
<script type="module">
    import * as THREE from 'three';
    // Import OrbitControls agar map bisa di-drag, zoom, dan rotate oleh user
    import {
        OrbitControls
    } from 'three/addons/controls/OrbitControls.js';
    // Import GLTFLoader untuk memanggil file .glb dari Blender
    import {
        GLTFLoader
    } from 'three/addons/loaders/GLTFLoader.js';

    // 1. Inisialisasi Wadah (Container)
    const container = document.getElementById('map-3d-container');
    const overlayInfo = document.getElementById('map-loading-overlay'); // Kita tambahkan ID ini ke div overlay loading

    // 2. Setup Scene (Dunia 3D)
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0xf8fafc); // Warna background menyesuaikan tema Tailwind (slate-50)

    // 3. Setup Kamera (Sudut Pandang)
    // Perspektif kamera (Field of View, Aspek Rasio, Jarak Dekat, Jarak Jauh)
    const camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 1000);
    camera.position.set(10, 10, 15); // Posisi awal kamera (X, Y, Z)

    // 4. Setup Renderer (Mesin Penampil)
    const renderer = new THREE.WebGLRenderer({
        antialias: true,
        alpha: true
    });
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.setPixelRatio(window.devicePixelRatio);

    // Jadikan canvas absolut agar menutupi placeholder
    renderer.domElement.style.position = 'absolute';
    renderer.domElement.style.top = '0';
    renderer.domElement.style.left = '0';
    renderer.domElement.style.width = '100%';
    renderer.domElement.style.height = '100%';
    renderer.domElement.style.zIndex = '10'; // Taruh di atas gambar placeholder

    // Masukkan canvas ke dalam container HTML kita
    container.appendChild(renderer.domElement);

    // 5. Setup Kontrol (Navigasi Mouse/Touch)
    const controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true; // Bikin pergerakan kamera lebih halus (smooth)
    controls.dampingFactor = 0.05;
    controls.maxPolarAngle = Math.PI / 2 - 0.05; // Batasi agar kamera tidak bisa tembus ke bawah tanah

    // 6. Setup Pencahayaan (Lighting)
    // Cahaya merata ke semua sisi
    const ambientLight = new THREE.AmbientLight(0xffffff, 1.5);
    scene.add(ambientLight);

    // Cahaya mirip matahari (berarah)
    const directionalLight = new THREE.DirectionalLight(0xffffff, 2);
    directionalLight.position.set(5, 10, 7.5);
    scene.add(directionalLight);

    // 7. Load File 3D dari Blender
    const loader = new GLTFLoader();

    // Ganti URL ini sesuai dengan lokasi file .glb Anda di folder public Laravel
    loader.load(
        "{{ asset('models/map-kmdgi.glb') }}", // Path file 3D
        function(gltf) {
            // Jika berhasil dimuat:
            const model = gltf.scene;

            // Pusatkan posisi model di tengah-tengah dunia 3D
            model.position.set(0, 0, 0);
            scene.add(model);

            // Sembunyikan UI "Loading..." setelah model muncul
            if (overlayInfo) {
                overlayInfo.style.opacity = '0';
                setTimeout(() => overlayInfo.style.display = 'none', 500);
            }
        },
        function(xhr) {
            // Proses loading (opsional: jika ingin bikin persentase loading)
            console.log((xhr.loaded / xhr.total * 100) + '% loaded');
        },
        function(error) {
            // Jika gagal/error
            console.error('Terjadi kesalahan saat memuat model 3D', error);
            if (overlayInfo) overlayInfo.innerHTML = '<span class="bg-red-500 text-white px-4 py-2 rounded-full">Gagal memuat 3D Map</span>';
        }
    );

    // 8. Animasi Loop (Wajib agar layar terus me-render pergerakan)
    function animate() {
        requestAnimationFrame(animate);
        controls.update(); // Update kontrol (damping)
        renderer.render(scene, camera);
    }
    animate();

    // 9. Handle Resize (Jika ukuran browser berubah/di HP)
    window.addEventListener('resize', () => {
        if (!container) return;
        const width = container.clientWidth;
        const height = container.clientHeight;

        camera.aspect = width / height;
        camera.updateProjectionMatrix();
        renderer.setSize(width, height);
    });
</script>

@endsection