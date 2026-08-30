@extends('layouts.app')

@section('title', 'Manajemen Header Publik - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3 mb-1">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" /></svg>
                    <span class="text-sm font-bold">Gagal Menyimpan Data!</span>
                </div>
                <ul class="text-xs list-disc list-inside pl-9 mt-1 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Pengaturan Header Website</h2>
                    <p class="text-sm text-slate-500 mt-1">Atur teks utama, gambar latar, hitung mundur (countdown), dan tombol aksi (CTA).</p>
                </div>
            </div>

            <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <form action="{{ route('admin.header.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Area 1: Teks Utama -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">1. Teks & Identitas Utama</h3>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Utama (Headline) <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" value="{{ old('judul', $header->judul) }}" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-xl text-lg font-bold focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all" placeholder="Cth: KMDGI 16: Simpul Kolaborasi">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi / Sub-Headline</label>
                            <textarea name="deskripsi" rows="3" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all custom-scrollbar" placeholder="Cth: Mari bergabung bersama ribuan insan kreatif dari seluruh penjuru Nusantara...">{{ old('deskripsi', $header->deskripsi) }}</textarea>
                        </div>
                    </div>

                    <!-- Area 2: Media Latar Belakang -->
                    <div class="space-y-4 pt-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">2. Latar Belakang (Background)</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Image Background -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Gambar Background (Opsional, 16:9)</label>
                                <div class="relative w-full aspect-video border-2 border-dashed border-slate-300 hover:border-kmdgi-primary rounded-xl bg-slate-50 flex items-center justify-center p-4 group cursor-pointer transition-colors overflow-hidden" onclick="document.getElementById('input-bg').click()">
                                    <div id="bg-placeholder" class="flex flex-col items-center text-center pointer-events-none relative z-10 transition-opacity {{ $header->gambar_background ? 'opacity-0' : '' }}">
                                        <svg class="w-8 h-8 text-slate-400 mb-2 group-hover:text-kmdgi-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                                        <span class="text-xs font-bold text-slate-500">Pilih Gambar Latar Utama</span>
                                    </div>
                                    <img id="bg-preview" src="{{ $header->gambar_background ? asset('storage/' . $header->gambar_background) : '' }}" class="absolute inset-0 w-full h-full object-cover z-20 {{ $header->gambar_background ? '' : 'hidden' }}" />
                                </div>
                                <input type="file" name="gambar_background" id="input-bg" accept="image/*" class="hidden" onchange="previewBg(this)">
                                <p class="text-[10px] text-slate-400 mt-2 text-center">Max 5MB. Disarankan ukuran 1920x1080px agar tidak pecah.</p>
                            </div>

                            <!-- Video Background -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Video Background URL (Opsional)</label>
                                <input type="url" name="video_background" value="{{ old('video_background', $header->video_background) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all" placeholder="Cth: https://youtube.com/watch?v=...">
                                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                    Jika Anda memasukkan Tautan Video (YouTube/MP4), maka Video ini akan diputar sebagai latar belakang alih-alih Gambar Background.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Area 3: Tombol Aksi -->
                    <div class="space-y-4 pt-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">3. Tombol Call-to-Action (CTA)</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="p-5 bg-blue-50/50 border border-blue-100 rounded-2xl space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Teks Tombol Utama</label>
                                    <input type="text" name="teks_tombol_utama" value="{{ old('teks_tombol_utama', $header->teks_tombol_utama) }}" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all" placeholder="Cth: Daftar Sekarang">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Link / Tautan Tombol Utama</label>
                                    <input type="text" name="link_tombol_utama" value="{{ old('link_tombol_utama', $header->link_tombol_utama) }}" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition-all" placeholder="Cth: /register atau https://...">
                                </div>
                            </div>
                            
                            <div class="p-5 bg-slate-50/50 border border-slate-200 rounded-2xl space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Teks Tombol Sekunder</label>
                                    <input type="text" name="teks_tombol_sekunder" value="{{ old('teks_tombol_sekunder', $header->teks_tombol_sekunder) }}" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-slate-400 transition-all" placeholder="Cth: Lihat Panduan">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Link / Tautan Tombol Sekunder</label>
                                    <input type="text" name="link_tombol_sekunder" value="{{ old('link_tombol_sekunder', $header->link_tombol_sekunder) }}" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-slate-400 transition-all" placeholder="Cth: /panduan-delegasi">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Area 4: Hitung Mundur -->
                    <div class="space-y-4 pt-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">4. Sistem Hitung Mundur (Countdown)</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Tanggal & Jam Target</label>
                                <!-- Kita menggunakan format datetime-local -->
                                <input type="datetime-local" name="waktu_countdown" value="{{ old('waktu_countdown', $header->waktu_countdown ? \Carbon\Carbon::parse($header->waktu_countdown)->format('Y-m-d\TH:i') : '') }}" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Tampilan Countdown <span class="text-red-500">*</span></label>
                                <select name="is_active_countdown" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary cursor-pointer">
                                    <option value="1" {{ old('is_active_countdown', $header->is_active_countdown) == 1 ? 'selected' : '' }}>Aktif (Tampilkan Jam Hitung Mundur)</option>
                                    <option value="0" {{ old('is_active_countdown', $header->is_active_countdown) == 0 ? 'selected' : '' }}>Nonaktif (Sembunyikan)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="inline-flex justify-center bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-4 px-10 rounded-xl transition-colors text-sm shadow-md shadow-kmdgi-primary/20">
                            Simpan Pengaturan Header
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>

    @include('partials.footer')
</div>

<script>
    function previewBg(input) {
        const preview = document.getElementById('bg-preview');
        const placeholder = document.getElementById('bg-placeholder');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('opacity-0');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection