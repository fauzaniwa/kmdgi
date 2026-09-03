@extends('layouts.app')
@section('title', 'Pendaftaran Lomba - KMDGI 16')

@section('content')
<div class="bg-white min-h-screen flex flex-col">
    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8 font-sans">
        @include('partials.sidebar')

        <main class="flex-grow w-full pb-20">
            <div class="max-w-3xl mx-auto">
                
                <!-- Title & Guide Section -->
                <div class="mb-10 border-b border-slate-200 pb-8">
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-3">Panduan & Pendaftaran Lomba</h1>
                    <p class="text-[15px] text-slate-600 mb-6">Pahami syarat, ketentuan, serta alur pendaftaran lomba untuk mempermudah proses pengiriman berkas dan karya tim kamu.</p>
                    
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('kompetisi.show', $lomba->slug) }}" class="bg-[#1A68FF] text-white font-medium text-[13px] px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            Baca Panduan Online
                        </a>
                        @if($lomba->file_guidebook)
                        <a href="{{ asset('storage/' . $lomba->file_guidebook) }}" target="_blank" class="bg-blue-50 text-[#1A68FF] font-medium text-[13px] px-6 py-3 rounded-lg hover:bg-blue-100 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                            Unduh Guidebook
                        </a>
                        @endif
                    </div>
                </div>

                @if($errors->any())
                <div class="bg-red-50 text-red-600 px-5 py-4 rounded-xl mb-8 border border-red-100 text-sm">
                    <ul class="list-disc pl-5">@foreach($errors->all() as $err) <li>{{ $error }}</li> @endforeach</ul>
                </div>
                @endif

                <form action="{{ route('kompetisi.store_daftar', $lomba->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-12">
                    @csrf

                    <!-- Identitas Dasar (Dibutuhkan DB) -->
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-slate-900">Data Tim Peserta</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-800 mb-2">Nama Tim / Peserta<span class="text-red-500">*</span></label>
                                <input type="text" name="nama_tim_peserta" value="{{ old('nama_tim_peserta', $user->name) }}" required class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#1A68FF]" placeholder="Masukkan nama tim">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-800 mb-2">Institusi Asal<span class="text-red-500">*</span></label>
                                <input type="text" name="institusi_asal" value="{{ old('institusi_asal', $user->institusi) }}" required class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#1A68FF]">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-800 mb-2">Kategori Pendaftar<span class="text-red-500">*</span></label>
                                <select name="kategori_pendaftar" required class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#1A68FF]">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach(is_array($lomba->kategori_peserta) ? $lomba->kategori_peserta : [] as $kat)
                                        <option value="{{ $kat }}">{{ $kat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-800 mb-2">No WhatsApp<span class="text-red-500">*</span></label>
                                <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp', $user->no_whatsapp) }}" required class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#1A68FF]">
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-200">

                    <!-- SECTION PEMBAYARAN -->
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-slate-900">Pembayaran Biaya Pendaftaran</h2>
                            <div class="flex gap-2">
                                <span class="border border-amber-200 text-amber-700 text-xs px-3 py-1 rounded-full">Deadline: {{ $targetCountdown ? date('d F Y', strtotime($targetCountdown)) : '-' }}</span>
                                <span class="border border-emerald-200 text-emerald-700 text-xs px-3 py-1 rounded-full">Wajib</span>
                            </div>
                        </div>
                        <p class="text-[15px] text-slate-600 mb-4">Unggah bukti transfer resmi pendaftaran lomba untuk diverifikasi oleh panitia.</p>

                        <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 flex items-start gap-3 mb-6">
                            <svg class="w-5 h-5 text-slate-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                            <p class="text-sm text-slate-700">Pastikan nominal transfer sesuai dengan biaya pendaftaran cabang lomba yang dipilih <strong class="text-slate-900">({{ $lomba->biaya_pendaftaran > 0 ? 'Rp '.number_format($lomba->biaya_pendaftaran,0,',','.') : 'Gratis' }})</strong>.</p>
                        </div>

                        @if($lomba->biaya_pendaftaran > 0)
                            <!-- File Upload Area (Empty State) -->
                            <label id="drop-area-bayar" class="w-full border-2 border-dashed border-slate-300 rounded-xl p-10 flex flex-col items-center justify-center cursor-pointer hover:border-[#1A68FF] transition-colors bg-white">
                                <input type="file" name="bukti_pembayaran" id="input-bayar" accept="image/*" class="hidden" required onchange="handleFileSelect(this, 'bayar')">
                                <div class="w-12 h-12 border border-blue-100 rounded-lg flex items-center justify-center text-[#1A68FF] mb-4 bg-blue-50">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                                </div>
                                <p class="text-[15px] text-slate-800 mb-1"><span class="font-bold">Klik untuk unggah</span> atau seret dan lepas</p>
                                <p class="text-[13px] text-slate-500">SVG, PNG, JPG, atau GIF (maks. 5mb)</p>
                            </label>

                            <!-- File Upload Area (Filled State) -->
                            <div id="file-state-bayar" class="w-full border border-slate-200 rounded-xl p-5 hidden bg-white">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-4 w-full">
                                        <div class="w-10 h-10 bg-red-50 text-red-500 rounded flex items-center justify-center flex-shrink-0 font-bold text-xs">IMG</div>
                                        <div class="w-full">
                                            <p id="filename-bayar" class="text-sm font-bold text-slate-800 truncate mb-1">Bukti_Pembayaran.jpg</p>
                                            <div class="flex items-center text-xs text-slate-500 gap-2 mb-2">
                                                <span id="filesize-bayar">0 KB dari 5MB</span>
                                                <span>|</span>
                                                <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Selesai</span>
                                            </div>
                                            <div class="w-full bg-slate-100 rounded-full h-1.5"><div class="bg-[#1A68FF] h-1.5 rounded-full w-full"></div></div>
                                        </div>
                                    </div>
                                    <button type="button" onclick="removeFile('bayar')" class="text-slate-400 hover:text-red-500 p-2"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                </div>
                            </div>
                        @else
                            <div class="w-full border-2 border-dashed border-emerald-300 rounded-xl p-10 flex flex-col items-center justify-center bg-emerald-50">
                                <p class="text-emerald-700 font-bold">Lomba ini Gratis. Tidak perlu bukti pembayaran.</p>
                            </div>
                        @endif
                    </div>

                    <hr class="border-slate-200">

                    <!-- SECTION SUBMISI KARYA -->
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-slate-900">Submisi Karya {{ $lomba->judul }}</h2>
                            <div class="flex gap-2">
                                <span class="border border-slate-200 text-slate-500 text-xs px-3 py-1 rounded-full">Bisa Menyusul</span>
                            </div>
                        </div>
                        <p class="text-[15px] text-slate-600 mb-4">Lengkapi informasi detail terkait karya lomba Anda. Data dan informasi ini akan ditampilkan secara publik pada galeri pameran serta katalog resmi KMDGI 16.</p>

                        <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 flex items-start gap-3 mb-6">
                            <svg class="w-5 h-5 text-slate-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                            <p class="text-sm text-slate-700">Periksa kembali penulisan judul dan nama kreator. Data ini akan dicetak pada sertifikat resmi dan dipublikasikan di galeri pameran online.</p>
                        </div>

                        <div class="space-y-5 mb-8">
                            <div>
                                <label class="block text-sm font-bold text-slate-800 mb-2">Judul Karya<span class="text-red-500">*</span></label>
                                <input type="text" name="judul_karya" value="{{ old('judul_karya') }}" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#1A68FF]" placeholder="Masukkan judul karya Anda...">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-800 mb-2">Kreator<span class="text-red-500">*</span></label>
                                <input type="text" name="kreator_karya" value="{{ old('kreator_karya') }}" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#1A68FF]" placeholder="Contoh: Budi Susanto, Siti Aminah">
                                <p class="text-[11px] text-slate-400 mt-1.5 italic">Tuliskan nama lengkap kreator. Jika lebih dari satu orang, pisahkan dengan tanda koma (,).</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-800 mb-2">Deskripsi Karya<span class="text-red-500">*</span></label>
                                <textarea name="deskripsi_karya" rows="4" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#1A68FF] resize-y" placeholder="Ceritakan makna, proses kreatif, dan pesan yang ingin disampaikan dari karya ini..."></textarea>
                                <p class="text-[11px] text-slate-400 mt-1.5 italic">Ceritakan karya Anda semenarik mungkin. (Maksimal 300 kata).</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-800 mb-2">Tautan Eksternal (G-Drive / Portofolio)</label>
                                <input type="url" name="link_karya" value="{{ old('link_karya') }}" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#1A68FF]" placeholder="https://drive.google.com/...">
                            </div>
                        </div>

                        <!-- File Karya -->
                        <label class="block text-sm font-bold text-slate-800 mb-2">File Karya<span class="text-red-500">*</span></label>
                        <label id="drop-area-karya" class="w-full border-2 border-dashed border-slate-300 rounded-xl p-10 flex flex-col items-center justify-center cursor-pointer hover:border-[#1A68FF] transition-colors bg-white">
                            <input type="file" name="file_karya" id="input-karya" accept=".zip,.rar,.pdf" class="hidden" onchange="handleFileSelect(this, 'karya')">
                            <div class="w-12 h-12 border border-blue-100 rounded-lg flex items-center justify-center text-[#1A68FF] mb-4 bg-blue-50">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                            </div>
                            <p class="text-[15px] text-slate-800 mb-1"><span class="font-bold">Klik untuk unggah</span> atau seret dan lepas</p>
                            <p class="text-[13px] text-slate-500">ZIP, RAR, atau PDF (maks. 10mb)</p>
                        </label>

                        <!-- File Karya (Filled State) -->
                        <div id="file-state-karya" class="w-full border border-slate-200 rounded-xl p-5 hidden bg-white">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-10 h-10 bg-slate-100 text-slate-500 rounded flex items-center justify-center flex-shrink-0 font-bold text-xs">DOC</div>
                                    <div class="w-full">
                                        <p id="filename-karya" class="text-sm font-bold text-slate-800 truncate mb-1">File_Karya.zip</p>
                                        <div class="flex items-center text-xs text-slate-500 gap-2 mb-2">
                                            <span id="filesize-karya">0 KB dari 10MB</span>
                                            <span>|</span>
                                            <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Selesai</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-1.5"><div class="bg-[#1A68FF] h-1.5 rounded-full w-full"></div></div>
                                    </div>
                                </div>
                                <button type="button" onclick="removeFile('karya')" class="text-slate-400 hover:text-red-500 p-2"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                            </div>
                        </div>

                    </div>

                    <!-- SUBMIT BUTTONS -->
                    <div class="flex flex-col sm:flex-row justify-end gap-4 pt-6 pb-10">
                        <a href="{{ route('kompetisi.show', $lomba->slug) }}" class="bg-slate-50 text-slate-600 px-6 py-3 rounded-lg font-bold text-sm text-center border border-slate-200 hover:bg-slate-100 transition-colors">Batal & Kembali</a>
                        <button type="submit" class="bg-[#1A68FF] text-white px-8 py-3 rounded-lg font-bold text-sm text-center hover:bg-blue-700 transition-colors shadow-md shadow-blue-500/20">Kirim Pendaftaran</button>
                    </div>

                </form>
            </div>
        </main>
    </div>
</div>

<script>
    // JS Murni untuk memanipulasi tampilan Drop File layaknya React/Figma
    function handleFileSelect(input, type) {
        if(input.files && input.files[0]) {
            const file = input.files[0];
            const sizeKB = Math.round(file.size / 1024);
            const sizeStr = sizeKB > 1024 ? (sizeKB/1024).toFixed(1) + ' MB' : sizeKB + ' KB';
            const maxStr = type === 'bayar' ? '5MB' : '10MB';

            document.getElementById('filename-' + type).innerText = file.name;
            document.getElementById('filesize-' + type).innerText = sizeStr + ' dari ' + maxStr;

            document.getElementById('drop-area-' + type).classList.add('hidden');
            document.getElementById('file-state-' + type).classList.remove('hidden');
        }
    }

    function removeFile(type) {
        document.getElementById('input-' + type).value = '';
        document.getElementById('drop-area-' + type).classList.remove('hidden');
        document.getElementById('file-state-' + type).classList.add('hidden');
    }
</script>
@endsection