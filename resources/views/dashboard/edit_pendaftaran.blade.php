@extends('layouts.app')
@section('title', 'Kelola Berkas Lomba')

@section('content')
<div class="bg-slate-50 min-h-screen pb-20 flex flex-col">
    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8 font-sans">
        @include('partials.sidebar')

        <main class="flex-grow w-full">
            <div class="max-w-3xl mx-auto">
                
                <div class="mb-8">
                    <a href="{{ route('peserta.status-lomba') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-[#1A68FF] transition-colors mb-4">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                        Kembali ke Status
                    </a>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Kelola Berkas & Karya</h1>
                    <p class="text-slate-500 mt-2">Perbarui data, unggah susulan, atau revisi karya untuk <strong class="text-[#1A68FF]">{{ $lomba->judul }}</strong>.</p>
                </div>

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-2xl shadow-sm mb-6">
                    <h4 class="font-bold mb-1">Gagal Menyimpan Perubahan!</h4>
                    <ul class="text-sm list-disc list-inside">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('peserta.lomba.update', $pendaftaran->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- IDENTITAS PENDAFTAR -->
                    <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3 mb-6">1. Identitas Peserta / Tim</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Tim / Peserta</label>
                                <input type="text" name="nama_tim_peserta" value="{{ old('nama_tim_peserta', $pendaftaran->nama_tim_peserta) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#1A68FF] focus:bg-white transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Institusi Asal</label>
                                <input type="text" name="institusi_asal" value="{{ old('institusi_asal', $pendaftaran->institusi_asal) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#1A68FF] focus:bg-white transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori Pendaftar</label>
                                <select name="kategori_pendaftar" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#1A68FF] cursor-pointer">
                                    @foreach(is_array($lomba->kategori_peserta) ? $lomba->kategori_peserta : [] as $kat)
                                        <option value="{{ $kat }}" {{ $pendaftaran->kategori_pendaftar == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No WhatsApp</label>
                                <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp', $pendaftaran->no_whatsapp) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#1A68FF] focus:bg-white transition-colors">
                            </div>
                        </div>
                    </div>

                    <!-- PEMBAYARAN -->
                    <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm">
                        <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-3">
                            <h3 class="text-lg font-bold text-slate-800">2. Pembayaran Pendaftaran</h3>
                            <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-1 rounded">{{ $pendaftaran->status_pembayaran }}</span>
                        </div>
                        
                        @if($lomba->biaya_pendaftaran > 0)
                            @if($pendaftaran->bukti_pembayaran)
                            <div class="flex flex-col sm:flex-row items-center justify-between border border-slate-200 rounded-xl p-4 bg-slate-50 mb-4 gap-4">
                                <div class="flex items-center gap-3 w-full">
                                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center font-bold text-xs shrink-0">IMG</div>
                                    <div class="truncate">
                                        <p class="text-sm font-bold text-slate-800 truncate">Bukti Pembayaran Tersimpan</p>
                                        <a href="{{ asset('storage/'.$pendaftaran->bukti_pembayaran) }}" target="_blank" class="text-[11px] font-bold text-[#1A68FF] hover:underline">Lihat File Bukti</a>
                                    </div>
                                </div>
                                <label class="text-xs font-bold text-slate-500 bg-white border border-slate-200 px-4 py-2 rounded-lg cursor-pointer hover:bg-slate-100 whitespace-nowrap shrink-0 transition-colors">
                                    Revisi / Ganti File
                                    <input type="file" name="bukti_pembayaran" accept="image/*" class="hidden" onchange="previewUpdate(this, 'nama-bayar')">
                                </label>
                            </div>
                            <p id="nama-bayar" class="text-[11px] font-bold text-[#1A68FF] hidden mb-2 ml-1">File baru dipilih, siap diunggah.</p>
                            @else
                            <label class="w-full border-2 border-dashed border-amber-300 rounded-xl p-8 flex flex-col items-center justify-center cursor-pointer hover:border-amber-500 transition-colors bg-amber-50">
                                <input type="file" name="bukti_pembayaran" accept="image/*" class="hidden" required onchange="previewUpdate(this, 'teks-unggah-bayar')">
                                <div class="text-amber-600 font-bold text-sm mb-1" id="teks-unggah-bayar">⚠️ Klik untuk Unggah Bukti Bayar</div>
                                <p class="text-xs text-amber-600/70">JPG, PNG (Maks 3MB)</p>
                            </label>
                            @endif
                        @else
                            <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100 text-center"><span class="text-sm font-bold text-emerald-600">Lomba Gratis, Tidak Perlu Bukti Bayar.</span></div>
                        @endif
                    </div>

                    <!-- SUBMISI KARYA -->
                    <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm">
                        <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-3">
                            <h3 class="text-lg font-bold text-slate-800">3. Submisi Karya</h3>
                            <span class="bg-blue-50 text-[#1A68FF] text-[10px] font-bold px-2 py-1 rounded">{{ $pendaftaran->status_karya }}</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Karya</label>
                                <input type="text" name="judul_karya" value="{{ old('judul_karya', $pendaftaran->judul_karya) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#1A68FF] focus:bg-white" placeholder="Masukkan judul karya">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kreator / Tim</label>
                                <input type="text" name="kreator_karya" value="{{ old('kreator_karya', $pendaftaran->kreator_karya) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#1A68FF] focus:bg-white" placeholder="Cth: Budi, Siti">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Makna Karya</label>
                                <textarea name="deskripsi_karya" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#1A68FF] focus:bg-white" placeholder="Ceritakan konsep karya...">{{ old('deskripsi_karya', $pendaftaran->deskripsi_karya) }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tautan Eksternal (G-Drive / Portofolio)</label>
                                <input type="url" name="link_karya" value="{{ old('link_karya', $pendaftaran->link_karya) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#1A68FF] focus:bg-white" placeholder="https://drive.google.com/...">
                            </div>
                        </div>

                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">File Karya Unggahan Asli</label>
                        @if($pendaftaran->file_karya)
                            <div class="flex flex-col sm:flex-row items-center justify-between border border-slate-200 rounded-xl p-4 bg-slate-50 mb-2 gap-4">
                                <div class="flex items-center gap-3 w-full">
                                    <div class="w-10 h-10 bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center font-bold text-xs shrink-0">ZIP</div>
                                    <div class="truncate">
                                        <p class="text-sm font-bold text-slate-800 truncate">Karya Telah Terunggah</p>
                                        <a href="{{ asset('storage/'.$pendaftaran->file_karya) }}" download class="text-[11px] font-bold text-[#1A68FF] hover:underline">Unduh File Saat Ini</a>
                                    </div>
                                </div>
                                <label class="text-xs font-bold text-slate-500 bg-white border border-slate-200 px-4 py-2 rounded-lg cursor-pointer hover:bg-slate-100 whitespace-nowrap shrink-0 transition-colors">
                                    Ganti File Baru
                                    <input type="file" name="file_karya" accept=".zip,.rar,.pdf" class="hidden" onchange="previewUpdate(this, 'nama-karya')">
                                </label>
                            </div>
                            <p id="nama-karya" class="text-[11px] font-bold text-[#1A68FF] hidden ml-1">File karya baru dipilih, siap diunggah.</p>
                        @else
                            <label class="w-full border-2 border-dashed border-slate-300 rounded-xl p-8 flex flex-col items-center justify-center cursor-pointer hover:border-[#1A68FF] transition-colors bg-white">
                                <input type="file" name="file_karya" accept=".zip,.rar,.pdf" class="hidden" onchange="previewUpdate(this, 'teks-unggah-karya')">
                                <div class="w-10 h-10 border border-blue-100 rounded-lg flex items-center justify-center text-[#1A68FF] mb-2 bg-blue-50">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                                </div>
                                <div class="text-slate-700 font-bold text-sm mb-1" id="teks-unggah-karya">Klik di sini untuk mengunggah karya</div>
                                <p class="text-xs text-slate-400">ZIP, RAR, atau PDF (Maks 10MB)</p>
                            </label>
                        @endif
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-4 px-12 rounded-xl transition-all shadow-lg shadow-blue-500/30 w-full md:w-auto text-center transform hover:-translate-y-1">
                            Simpan Perubahan Berkas
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>
</div>

<script>
    function previewUpdate(input, elementId) {
        if(input.files && input.files[0]) {
            const el = document.getElementById(elementId);
            el.innerText = "Terpilih: " + input.files[0].name;
            if(el.classList.contains('hidden')) {
                el.classList.remove('hidden');
            }
            if(el.classList.contains('text-amber-600') || el.classList.contains('text-slate-700')) {
                el.classList.remove('text-amber-600', 'text-slate-700');
                el.classList.add('text-[#1A68FF]');
            }
        }
    }
</script>
@endsection