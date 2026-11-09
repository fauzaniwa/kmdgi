<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code', '404') - Laman Belum Tersedia | KMDGI 16</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col justify-between text-slate-900 overflow-x-hidden">

    <!-- Navbar (Opsi aman jika error 500 tidak memuat database) -->
    @php
        $code = trim($__env->yieldContent('code', '404'));
    @endphp
    @if(!in_array($code, ['500', '503', '505']) && View::exists('partials.navbar'))
        @include('partials.navbar')
    @else
        <div class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
            <a href="{{ url('/') }}">
                @if(file_exists(public_path('images/logo-footer.png')))
                    <img src="{{ asset('images/logo-footer.png') }}" alt="KMDGI 16" class="h-12 w-auto object-contain">
                @else
                    <span class="text-2xl font-black text-[#1A68FF] tracking-tighter">KMDGI 16!</span>
                @endif
            </a>
        </div>
    @endif

    <!-- Konten Utama Error -->
    <main class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 py-16">
        <div class="max-w-xl w-full">

            <!-- Ornamen Folder Tab Khas KMDGI 16 -->
            <div class="relative w-full pt-7">
                <div class="absolute top-0 left-0 flex items-end select-none pointer-events-none">
                    <div class="bg-[#1A68FF] h-8 w-36 md:w-44 rounded-t-[1.25rem] relative z-20"></div>
                    <div class="bg-[#C4F03B] h-6 w-16 md:w-20 rounded-t-[0.85rem] -ml-5 relative z-10"></div>
                    <div class="bg-[#FF6B9E] h-4 w-12 md:w-16 rounded-t-[0.6rem] -ml-5 relative z-0"></div>
                </div>

                <!-- Card Utama -->
                <div class="relative z-20 bg-white border border-slate-200/80 rounded-b-[2rem] rounded-tr-[2rem] rounded-tl-none p-8 md:p-12 shadow-[0_20px_50px_rgba(0,0,0,0.06)] text-center">

                    <!-- Badge Kode Error -->
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-[#1A68FF] text-xs font-extrabold uppercase tracking-widest mb-6">
                        <span class="w-2 h-2 rounded-full bg-[#1A68FF] animate-ping"></span>
                        Error @yield('code', '404')
                    </div>

                    <!-- Angka Kode Error Besar -->
                    <h1 class="text-7xl md:text-8xl font-black text-slate-900 tracking-tighter leading-none mb-4">
                        @yield('code', '404')
                    </h1>

                    <!-- Judul & Keterangan Seragam -->
                    <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight mb-3">
                        @yield('title', 'Laman Belum Tersedia')
                    </h2>

                    <p class="text-xs md:text-sm text-slate-500 leading-relaxed max-w-md mx-auto mb-8 font-medium">
                        @yield('message', 'Maaf, laman yang Anda tuju saat ini belum tersedia, sedang dalam tahap pemeliharaan, atau tautan yang Anda akses telah berpindah.')
                    </p>

                    <!-- Pesan Khusus dari abort(403, 'Pesan...') jika ada -->
                    @if(isset($exception) && $exception->getMessage() && !in_array($code, ['500']))
                        <div class="mb-8 p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold">
                            {{ $exception->getMessage() }}
                        </div>
                    @endif

                    <!-- Tombol Navigasi -->
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl border-2 border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs md:text-sm transition-all">
                            ← Kembali Sebelumnya
                        </a>
                        <a href="{{ url('/') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl bg-[#1A68FF] hover:bg-blue-700 text-white font-bold text-xs md:text-sm shadow-lg shadow-blue-500/20 transition-all transform hover:-translate-y-0.5">
                            Ke Beranda Utama
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <!-- Footer Sederhana -->
    <div class="text-center py-6 text-xs text-slate-400 font-medium">
        &copy; 2026 KMDGI 16. All rights reserved.
    </div>

</body>
</html>