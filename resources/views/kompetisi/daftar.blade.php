@extends('layouts.app')
@section('title', 'Pendaftaran Lomba - KMDGI 16')

@section('content')
<div class="bg-white min-h-screen flex flex-col">
    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8 font-sans">
        @include('partials.sidebar')

        <main class="flex-grow w-full pb-20">
            <div class="max-w-3xl mx-auto">
                
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
                    <ul class="list-disc pl-5">@foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                </div>
                @endif

                <form action="{{ route('kompetisi.store_daftar', $lomba->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-12">
                    @csrf

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
                            <div class="mb-6">
                                <button type="button" onclick="openModalRekening()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-3.5 px-6 rounded-xl transition-all shadow-md shadow-blue-500/20 text-sm">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                                    </svg>
                                    Lihat Tujuan Transfer / QRIS
                                </button>
                            </div>

                            <label id="drop-area-bayar" class="w-full border-2 border-dashed border-slate-300 rounded-xl p-10 flex flex-col items-center justify-center cursor-pointer hover:border-[#1A68FF] transition-colors bg-white">
                                <input type="file" name="bukti_pembayaran" id="input-bayar" accept="image/*" class="hidden" required onchange="handleFileSelect(this, 'bayar')">
                                <div class="w-12 h-12 border border-blue-100 rounded-lg flex items-center justify-center text-[#1A68FF] mb-4 bg-blue-50">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                                </div>
                                <p class="text-[15px] text-slate-800 mb-1"><span class="font-bold">Klik untuk unggah</span> atau seret dan lepas</p>
                                <p class="text-[13px] text-slate-500">SVG, PNG, JPG, atau GIF (maks. 3MB)</p>
                            </label>

                            <div id="file-state-bayar" class="w-full border border-slate-200 rounded-xl p-5 hidden bg-white">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-4 w-full">
                                        <div class="w-10 h-10 bg-red-50 text-red-500 rounded flex items-center justify-center flex-shrink-0 font-bold text-xs">IMG</div>
                                        <div class="w-full">
                                            <p id="filename-bayar" class="text-sm font-bold text-slate-800 truncate mb-1">Bukti_Pembayaran.jpg</p>
                                            <div class="flex items-center text-xs text-slate-500 gap-2 mb-2">
                                                <span id="filesize-bayar">0 KB dari 3MB</span>
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

                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-slate-900">Submisi Karya {{ $lomba->judul }}</h2>
                            <div class="flex gap-2">
                                <span class="border border-slate-200 text-slate-500 text-xs px-3 py-1 rounded-full">Bisa Menyusul</span>
                            </div>
                        </div>
                        <p class="text-[15px] text-slate-600 mb-4">Lengkapi informasi detail terkait karya lomba Anda. Data dan informasi ini akan ditampilkan secara publik pada galeri pameran serta katalog resmi KMDGI 16.</p>

                        <div class="space-y-5 mb-8">
                            <div>
                                <label class="block text-sm font-bold text-slate-800 mb-2">Judul Karya</label>
                                <input type="text" name="judul_karya" value="{{ old('judul_karya') }}" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#1A68FF]" placeholder="Masukkan judul karya Anda...">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-800 mb-2">Kreator<span class="text-red-500">*</span></label>
                                <input type="text" name="kreator_karya" value="{{ old('kreator_karya') }}" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#1A68FF]" placeholder="Contoh: Budi Susanto, Siti Aminah">
                                <p class="text-[11px] text-slate-400 mt-1.5 italic">Tuliskan nama lengkap kreator. Jika lebih dari satu orang, pisahkan dengan tanda koma (,).</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-800 mb-2">Deskripsi Karya</label>
                                <textarea name="deskripsi_karya" rows="4" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#1A68FF] resize-y" placeholder="Ceritakan makna, proses kreatif, dan pesan yang ingin disampaikan dari karya ini...">{{ old('deskripsi_karya') }}</textarea>
                                <p class="text-[11px] text-slate-400 mt-1.5 italic">Ceritakan karya Anda semenarik mungkin. (Maksimal 300 kata).</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-800 mb-2">Tautan Eksternal (G-Drive / Portofolio)</label>
                                <input type="url" name="link_karya" value="{{ old('link_karya') }}" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#1A68FF]" placeholder="https://drive.google.com/...">
                            </div>
                        </div>

                        <label class="block text-sm font-bold text-slate-800 mb-2">File Karya</label>
                        <label id="drop-area-karya" class="w-full border-2 border-dashed border-slate-300 rounded-xl p-10 flex flex-col items-center justify-center cursor-pointer hover:border-[#1A68FF] transition-colors bg-white">
                            <input type="file" name="file_karya" id="input-karya" accept=".zip,.rar,.pdf" class="hidden" onchange="handleFileSelect(this, 'karya')">
                            <div class="w-12 h-12 border border-blue-100 rounded-lg flex items-center justify-center text-[#1A68FF] mb-4 bg-blue-50">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                            </div>
                            <p class="text-[15px] text-slate-800 mb-1"><span class="font-bold">Klik untuk unggah</span> atau seret dan lepas</p>
                            <p class="text-[13px] text-slate-500">ZIP, RAR, atau PDF (maks. 10MB)</p>
                        </label>

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

                    <div class="flex flex-col sm:flex-row justify-end gap-4 pt-6 pb-10">
                        <a href="{{ route('kompetisi.show', $lomba->slug) }}" class="bg-slate-50 text-slate-600 px-6 py-3 rounded-lg font-bold text-sm text-center border border-slate-200 hover:bg-slate-100 transition-colors">Batal & Kembali</a>
                        <button type="submit" class="bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-4 px-12 rounded-xl transition-all shadow-lg shadow-blue-500/30 w-full md:w-auto text-center transform hover:-translate-y-1">
                            Konfirmasi Pendaftaran Lomba
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>
</div>

@php
    $qrisItems = isset($rekenings) ? $rekenings->filter(function($r) {
        return !empty($r->qr_code) || str_contains(strtoupper($r->nama_bank), 'QRIS');
    }) : collect();

    $bankItems = isset($rekenings) ? $rekenings->reject(function($r) {
        return !empty($r->qr_code) || str_contains(strtoupper($r->nama_bank), 'QRIS');
    }) : collect();
@endphp

<div id="modal-rekening" class="fixed inset-0 z-[100] hidden opacity-0 transition-opacity flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModalRekening()"></div>
    <div class="relative bg-white rounded-[2rem] w-full max-w-xl max-h-[90vh] overflow-y-auto shadow-2xl transform scale-95 transition-transform box-rekening flex flex-col">
        
        <div class="sticky top-0 bg-white/95 backdrop-blur-md z-20 px-6 sm:px-8 py-5 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black text-slate-900">Metode Pembayaran</h3>
                <p class="text-xs text-slate-400 mt-0.5">Pilih metode transfer pembayaran pendaftaran resmi</p>
            </div>
            <button type="button" onclick="closeModalRekening()" class="text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 w-8 h-8 rounded-full flex items-center justify-center transition-colors">✖</button>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            @if(isset($rekenings) && $rekenings->count() > 0)

                @if($qrisItems->count() > 0)
                <div class="space-y-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Pembayaran Instan (Direkomendasikan)</span>

                    @foreach($qrisItems as $qris)
                    <div class="bg-gradient-to-br from-blue-50/70 via-indigo-50/40 to-white rounded-2xl border-2 border-blue-200/80 p-5 sm:p-6 shadow-sm flex flex-col items-center text-center relative overflow-hidden">
                        
                        <div class="flex items-center justify-between w-full mb-4">
                            <div class="flex items-center gap-2">
                                <span class="bg-[#1A68FF] text-white text-[10px] font-black uppercase px-2.5 py-1 rounded-md tracking-wider">QRIS</span>
                                <h4 class="font-extrabold text-slate-800 text-sm sm:text-base">{{ $qris->nama_bank }}</h4>
                            </div>
                            @if($qris->logo_bank)
                            <img src="{{ asset('storage/'.$qris->logo_bank) }}" class="h-6 w-auto object-contain" alt="Logo">
                            @endif
                        </div>

                        @if($qris->qr_code)
                        <div class="w-48 h-48 sm:w-56 sm:h-56 bg-white rounded-2xl p-3 border border-slate-200 shadow-md flex items-center justify-center mb-4">
                            <img src="{{ asset('storage/'.$qris->qr_code) }}" alt="QRIS {{ $qris->nama_bank }}" class="w-full h-full object-contain">
                        </div>
                        @else
                        <div class="w-44 h-44 bg-slate-100 rounded-2xl border-2 border-dashed border-slate-300 flex items-center justify-center text-slate-400 text-xs italic mb-4">
                            QRIS Preview Belum Tersedia
                        </div>
                        @endif

                        <div class="space-y-1">
                            <p class="text-xs text-slate-500 font-medium">Atas Nama Penerima / Merchant:</p>
                            <p class="text-sm font-black text-slate-800">{{ $qris->atas_nama }}</p>
                        </div>

                        @if($qris->nomor_rekening)
                        <div class="mt-3 inline-flex items-center gap-2 bg-white/80 border border-blue-200 px-3 py-1.5 rounded-lg text-xs">
                            <span class="text-slate-500">ID / Kode:</span>
                            <span class="font-mono font-bold text-[#1A68FF]">{{ $qris->nomor_rekening }}</span>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $qris->nomor_rekening }}'); alert('Kode disalin!');" class="text-[10px] font-bold text-slate-400 hover:text-[#1A68FF] ml-1">Salin</button>
                        </div>
                        @endif

                        <p class="text-[11px] text-slate-400 mt-3">Mendukung BCA, Mandiri, BNI, BRI, GoPay, OVO, DANA, ShopeePay, & Semua Mobile Banking</p>
                    </div>
                    @endforeach
                </div>
                @endif

                @if($bankItems->count() > 0)
                <div class="space-y-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Transfer Bank Manual / Rekening</span>

                    <div class="space-y-2">
                        @foreach($bankItems as $index => $bank)
                        <div class="border border-slate-200 rounded-xl overflow-hidden bg-white transition-colors hover:border-slate-300">
                            <button type="button" onclick="togglePaymentAccordion({{ $index }})" class="w-full px-5 py-4 flex items-center justify-between text-left focus:outline-none bg-slate-50/50 hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    @if($bank->logo_bank)
                                    <div class="w-8 h-8 rounded-lg bg-white border border-slate-100 p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ asset('storage/'.$bank->logo_bank) }}" class="w-full h-full object-contain" alt="{{ $bank->nama_bank }}">
                                    </div>
                                    @else
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#1A68FF] font-black text-xs flex items-center justify-center shrink-0">
                                        {{ substr($bank->nama_bank, 0, 2) }}
                                    </div>
                                    @endif
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm leading-tight">{{ $bank->nama_bank }}</p>
                                        <p class="text-[11px] text-slate-400">A.N: {{ $bank->atas_nama }}</p>
                                    </div>
                                </div>
                                <svg id="arrow-acc-{{ $index }}" class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            <div id="content-acc-{{ $index }}" class="hidden px-5 py-4 border-t border-slate-100 bg-white space-y-3">
                                <div>
                                    <span class="text-[11px] font-medium text-slate-400 block mb-1">Nomor Rekening Tujuan:</span>
                                    <div class="flex items-center justify-between bg-slate-50 p-3 rounded-xl border border-slate-100">
                                        <span class="font-mono text-base font-extrabold text-[#1A68FF] tracking-wider">{{ $bank->nomor_rekening ?? '-' }}</span>
                                        @if($bank->nomor_rekening)
                                        <button type="button" onclick="navigator.clipboard.writeText('{{ $bank->nomor_rekening }}'); alert('Nomor Rekening Disalin!');" class="text-xs font-bold text-slate-600 hover:text-[#1A68FF] bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm hover:bg-blue-50 transition-colors">
                                            Salin No Rek
                                        </button>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-[12px] text-slate-500">
                                    Penerima: <strong class="text-slate-800">{{ $bank->atas_nama }}</strong>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            @else
                <div class="text-center py-12 bg-slate-50 rounded-2xl border border-slate-200 text-slate-500">
                    <p class="text-sm font-medium">Metode pembayaran belum dikonfigurasi oleh Panitia.</p>
                </div>
            @endif
        </div>

        <div class="p-6 bg-slate-50 border-t border-slate-100 flex justify-end rounded-b-[2rem]">
            <button type="button" onclick="closeModalRekening()" class="w-full sm:w-auto bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 font-bold text-sm px-6 py-2.5 rounded-xl transition-colors shadow-sm">
                Tutup Panduan
            </button>
        </div>

    </div>
</div>

<script>
    function handleFileSelect(input, type) {
        if(input.files && input.files[0]) {
            const file = input.files[0];
            const sizeKB = Math.round(file.size / 1024);
            const sizeStr = sizeKB > 1024 ? (sizeKB/1024).toFixed(1) + ' MB' : sizeKB + ' KB';
            const maxStr = type === 'bayar' ? '3MB' : '10MB';

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

    // Modal Tampilkan Rekening & QRIS
    function openModalRekening() {
        const modal = document.getElementById('modal-rekening');
        const box = modal.querySelector('.box-rekening');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            box.classList.remove('scale-95');
        }, 10);
    }

    function closeModalRekening() {
        const modal = document.getElementById('modal-rekening');
        const box = modal.querySelector('.box-rekening');
        modal.classList.add('opacity-0');
        box.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    // Toggle Dropdown / Akordeon Transfer Bank
    function togglePaymentAccordion(index) {
        const content = document.getElementById('content-acc-' + index);
        const arrow = document.getElementById('arrow-acc-' + index);

        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            arrow.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        }
    }
</script>
@endsection