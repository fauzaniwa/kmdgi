@extends('layouts.app')
@section('title', (isset($juknis) ? 'Edit' : 'Tambah') . ' Lomba - KMDGI')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<div class="bg-slate-50 min-h-screen flex flex-col">
    @include('partials.navbar')
    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">
        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">
            
            <a href="{{ route('admin.juknis.index', ['edisi_id' => $edisiId]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-kmdgi-primary transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Kembali ke Data Perlombaan
            </a>

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3 mb-1">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" /></svg>
                    <span class="text-sm font-bold">Gagal Menyimpan Data!</span>
                </div>
                <ul class="text-xs list-disc list-inside pl-9 mt-1 space-y-0.5">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ isset($juknis) ? 'Edit Juknis Lomba' : 'Tambah Juknis Lomba Baru' }}</h2>
                    <p class="text-sm text-slate-500 mt-1">Lengkapi informasi perlombaan dari detail hingga kriteria juri.</p>
                </div>
                <div class="bg-blue-50 border border-blue-100 px-4 py-2.5 rounded-xl flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-kmdgi-primary font-bold shadow-sm">🎯</span>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Terikat Pada Edisi</p>
                        <p class="text-sm font-bold text-kmdgi-primary">{{ $edisi->nama_edisi }}</p>
                    </div>
                </div>
            </div>

            <form id="lomba-form" action="{{ isset($juknis) ? route('admin.juknis.update', $juknis->id) : route('admin.juknis.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($juknis)) @method('PUT') @endif
                
                <input type="hidden" name="edisi_kmdgi_id" value="{{ $edisiId }}">

                <!-- SEGMEN 1: DATA UTAMA -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3 mb-6">1. Data Utama Perlombaan</h3>
                    
                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <div class="w-full md:w-1/3 lg:w-1/4">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Poster Lomba (Vertical) <span class="text-red-500">*</span></label>
                            <div class="relative w-full aspect-[3/4] border-2 border-dashed border-slate-300 hover:border-kmdgi-primary rounded-2xl bg-slate-50 flex items-center justify-center p-4 group cursor-pointer transition-colors overflow-hidden" onclick="document.getElementById('input-poster').click()">
                                <div id="ph-poster" class="flex flex-col items-center text-center pointer-events-none relative z-10 transition-opacity {{ (isset($juknis) && $juknis->poster) ? 'opacity-0' : '' }}">
                                    <svg class="w-8 h-8 text-slate-400 mb-2 group-hover:text-kmdgi-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                    <span class="text-[10px] font-bold text-slate-500">Pilih Poster (Max 3MB)</span>
                                </div>
                                <img id="pr-poster" src="{{ (isset($juknis) && $juknis->poster) ? asset('storage/'.$juknis->poster) : '' }}" class="absolute inset-0 w-full h-full object-cover z-20 {{ (isset($juknis) && $juknis->poster) ? '' : 'hidden' }}" />
                            </div>
                            <input type="file" name="poster" id="input-poster" accept="image/*" class="hidden" onchange="previewImageUtama(this, 'poster')">
                            <input type="hidden" name="remove_poster" id="rm-poster" value="0">
                            
                            <div class="mt-3 text-center">
                                <button type="button" id="btn-poster" class="{{ (isset($juknis) && $juknis->poster) ? '' : 'hidden' }} text-[11px] font-bold text-red-500 hover:text-red-600 bg-red-50 px-3 py-1.5 rounded-lg transition-colors" onclick="removeImageUtama('poster')">Hapus Poster</button>
                            </div>
                        </div>

                        <div class="w-full md:w-2/3 lg:w-3/4 space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Judul Lomba <span class="text-red-500">*</span></label>
                                    <input type="text" name="judul" id="input-judul" value="{{ old('judul', $juknis->judul ?? '') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all" placeholder="Cth: Lomba Desain Poster">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Slug (URL Kustom) <span class="text-red-500">*</span></label>
                                    <input type="text" name="slug" id="input-slug" value="{{ old('slug', $juknis->slug ?? '') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono text-kmdgi-primary focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all" placeholder="lomba-desain-poster">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori Peserta yang Diizinkan <span class="text-red-500">*</span></label>
                                @php $kat = isset($juknis) ? ($juknis->kategori_peserta ?? []) : []; @endphp
                                <div class="flex flex-wrap gap-3 bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer hover:text-kmdgi-primary"><input type="checkbox" name="kategori_peserta[]" value="Umum" class="w-4 h-4 text-kmdgi-primary bg-white border-slate-300 rounded focus:ring-kmdgi-primary" {{ in_array('Umum', $kat) ? 'checked' : '' }}> Umum</label>
                                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer hover:text-kmdgi-primary"><input type="checkbox" name="kategori_peserta[]" value="Delegasi" class="w-4 h-4 text-kmdgi-primary bg-white border-slate-300 rounded focus:ring-kmdgi-primary" {{ in_array('Delegasi', $kat) ? 'checked' : '' }}> Delegasi</label>
                                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer hover:text-kmdgi-primary"><input type="checkbox" name="kategori_peserta[]" value="Peninjau 1" class="w-4 h-4 text-kmdgi-primary bg-white border-slate-300 rounded focus:ring-kmdgi-primary" {{ in_array('Peninjau 1', $kat) ? 'checked' : '' }}> Peninjau 1</label>
                                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer hover:text-kmdgi-primary"><input type="checkbox" name="kategori_peserta[]" value="Peninjau 2" class="w-4 h-4 text-kmdgi-primary bg-white border-slate-300 rounded focus:ring-kmdgi-primary" {{ in_array('Peninjau 2', $kat) ? 'checked' : '' }}> Peninjau 2</label>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Biaya Pendaftaran (Rp) <span class="text-red-500">*</span></label>
                                    <input type="number" name="biaya_pendaftaran" value="{{ old('biaya_pendaftaran', $juknis->biaya_pendaftaran ?? 0) }}" min="0" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all">
                                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Biarkan angka 0 jika perlombaan ini gratis.</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status Publikasi Lomba <span class="text-red-500">*</span></label>
                                    <select name="is_active" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary cursor-pointer transition-all">
                                        <option value="1" {{ (isset($juknis) && $juknis->is_active == 1) ? 'selected' : '' }}>Aktifkan & Tampilkan</option>
                                        <option value="0" {{ (isset($juknis) && $juknis->is_active == 0) ? 'selected' : '' }}>Sembunyikan sebagai Draft</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col flex-grow">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Utama Perlombaan</label>
                        <input type="hidden" name="deskripsi" id="input-deskripsi">
                        <div class="flex-grow flex flex-col border border-slate-200 rounded-xl overflow-hidden focus-within:border-kmdgi-primary transition-all bg-white min-h-[200px]">
                            <div id="toolbar-deskripsi" class="bg-slate-50/80 border-b border-slate-200 py-1.5"><span class="ql-formats"><button class="ql-bold"></button><button class="ql-italic"></button></span><span class="ql-formats"><button class="ql-list" value="ordered"></button><button class="ql-list" value="bullet"></button></span></div>
                            <div id="editor-deskripsi" class="flex-grow text-[14px] text-slate-700 p-2">{!! old('deskripsi', $juknis->deskripsi ?? '') !!}</div>
                        </div>
                    </div>
                </div>

                <!-- SEGMEN 2: TIMELINE -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3 mb-5">
                        <h3 class="text-lg font-bold text-slate-800">2. Linimasa / Timeline</h3>
                        <button type="button" onclick="addTimeline()" class="text-xs font-bold text-kmdgi-primary bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-xl transition-colors">+ Tambah Fase</button>
                    </div>
                    <div id="timeline-container" class="space-y-4"></div>
                </div>

                <!-- SEGMEN 3: HADIAH -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3 mb-5">
                        <h3 class="text-lg font-bold text-slate-800">3. Kategori Pemenang & Hadiah</h3>
                        <button type="button" onclick="addHadiah()" class="text-xs font-bold text-amber-600 bg-amber-50 hover:bg-amber-100 px-3 py-2 rounded-xl transition-colors">+ Tambah Juara</button>
                    </div>
                    <div id="hadiah-container" class="space-y-4"></div>
                </div>

                <!-- SEGMEN 4: JURI DARI DATA KOLABORATOR -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4">4. Susunan Dewan Juri</h3>
                    <p class="text-xs text-slate-500 mb-4">Centang kolaborator yang bertugas sebagai dewan juri pada perlombaan ini.</p>

                    @php 
                        // PERBAIKAN: Gunakan $juknis->juri, bukan $juknis->juri_ids
                        $juriTerpilih = isset($juknis) && is_array($juknis->juri) ? $juknis->juri : []; 
                    @endphp
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if(isset($semuaKolaborator) && $semuaKolaborator->count() > 0)
                            @foreach($semuaKolaborator as $kol)
                            <label class="flex items-start gap-3 p-3 border border-slate-200 rounded-xl bg-slate-50 cursor-pointer hover:border-kmdgi-primary transition-colors">
                                <input type="checkbox" name="juri_ids[]" value="{{ $kol->id }}" class="mt-1 text-kmdgi-primary" {{ in_array($kol->id, $juriTerpilih) ? 'checked' : '' }}>
                                <div class="flex items-center gap-2 overflow-hidden">
                                    @if($kol->foto)
                                    <img src="{{ asset('storage/'.$kol->foto) }}" class="w-8 h-8 rounded-full object-cover bg-white flex-shrink-0">
                                    @else
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex-shrink-0"></div>
                                    @endif
                                    <div class="truncate">
                                        <p class="text-xs font-bold text-slate-800 truncate">{{ $kol->nama }}</p>
                                        <p class="text-[10px] text-slate-500 truncate">{{ $kol->peran_kolaborasi }}</p>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        @else
                            <p class="text-xs text-slate-400 italic col-span-full">Data Kolaborator belum ditambahkan di menu Master Data Admin.</p>
                        @endif
                    </div>
                </div>

                <!-- SEGMEN 5: DOKUMEN JUKNIS -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] space-y-8">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">5. Detail Petunjuk Teknis & Syarat</h3>
                    
                    @php 
                        $textFields = [
                            'syarat' => 'Syarat Peserta Lomba', 
                            'ketentuan' => 'Ketentuan Orisinalitas & Karya', 
                            'teknik_pelaksanaan' => 'Teknik Pelaksanaan & Pengumpulan', 
                            'ketentuan_umum' => 'Ketentuan Umum Pendaftaran', 
                            'ketentuan_khusus' => 'Ketentuan Khusus Penilaian'
                        ]; 
                    @endphp
                    
                    @foreach($textFields as $f => $l)
                        <div class="flex flex-col">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ $l }}</label>
                            <input type="hidden" name="{{$f}}" id="input-{{$f}}">
                            <div class="flex flex-col border border-slate-200 rounded-xl overflow-hidden focus-within:border-kmdgi-primary transition-all bg-white min-h-[150px]">
                                <div id="toolbar-{{$f}}" class="bg-slate-50/80 border-b border-slate-200 py-1.5"><span class="ql-formats"><button class="ql-bold"></button><button class="ql-italic"></button></span><span class="ql-formats"><button class="ql-list" value="ordered"></button><button class="ql-list" value="bullet"></button></span></div>
                                <div id="editor-{{$f}}" class="flex-grow text-[14px] text-slate-700 p-2">{!! $juknis->$f ?? '' !!}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- SEGMEN 6: BERKAS UNDUHAN -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">6. Berkas Unduhan Resmi</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Guidebook -->
                        <div class="bg-slate-50 border border-slate-200 p-5 rounded-2xl">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">File Guidebook</label>
                            
                            @if(isset($juknis) && $juknis->file_guidebook)
                                <div id="container-gb" class="flex items-center justify-between bg-white border border-emerald-200 p-3 rounded-xl mb-3">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                        </div>
                                        <a href="{{ asset('storage/' . $juknis->file_guidebook) }}" target="_blank" class="text-sm font-bold text-emerald-600 hover:underline truncate">Lihat File Saat Ini</a>
                                    </div>
                                    <button type="button" onclick="removeDoc('gb')" class="text-red-500 bg-red-50 p-1.5 rounded hover:bg-red-500 hover:text-white transition-colors" title="Hapus File"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></button>
                                </div>
                            @endif
                            
                            <input type="file" name="file_guidebook" accept=".pdf,.doc,.docx" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-kmdgi-primary hover:file:bg-blue-100 cursor-pointer">
                            <input type="hidden" name="remove_file_guidebook" id="rm-gb" value="0">
                            <p class="text-[10px] text-slate-400 mt-2">Format yang diizinkan: PDF, DOCX (Maks: 5MB)</p>
                        </div>

                        <!-- Panduan Online -->
                        <div class="bg-slate-50 border border-slate-200 p-5 rounded-2xl">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">File Panduan Online</label>
                            
                            @if(isset($juknis) && $juknis->file_panduan_online)
                                <div id="container-po" class="flex items-center justify-between bg-white border border-emerald-200 p-3 rounded-xl mb-3">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                        </div>
                                        <a href="{{ asset('storage/' . $juknis->file_panduan_online) }}" target="_blank" class="text-sm font-bold text-emerald-600 hover:underline truncate">Lihat File Saat Ini</a>
                                    </div>
                                    <button type="button" onclick="removeDoc('po')" class="text-red-500 bg-red-50 p-1.5 rounded hover:bg-red-500 hover:text-white transition-colors" title="Hapus File"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></button>
                                </div>
                            @endif
                            
                            <input type="file" name="file_panduan_online" accept=".pdf,.doc,.docx" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-kmdgi-primary hover:file:bg-blue-100 cursor-pointer">
                            <input type="hidden" name="remove_file_panduan_online" id="rm-po" value="0">
                            <p class="text-[10px] text-slate-400 mt-2">Format yang diizinkan: PDF, DOCX (Maks: 5MB)</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.juknis.index', ['edisi_id' => $edisiId]) }}" class="inline-flex justify-center bg-white border border-slate-200 text-slate-600 font-bold py-3.5 px-8 rounded-xl hover:bg-slate-50 transition-colors text-sm shadow-sm">Batalkan</a>
                    <button type="submit" class="inline-flex justify-center bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-3.5 px-10 rounded-xl transition-colors text-sm shadow-md shadow-kmdgi-primary/20">
                        {{ isset($juknis) ? 'Simpan Perubahan Lomba' : 'Terbitkan Perlombaan Baru' }}
                    </button>
                </div>
            </form>
        </main>
    </div>
    @include('partials.footer')
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    // AUTO SLUG GENERATOR
    document.getElementById('input-judul').addEventListener('input', function() {
        document.getElementById('input-slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
    });

    // INIT QUILL EDITORS
    const fields = ['deskripsi', 'syarat', 'ketentuan', 'teknik_pelaksanaan', 'ketentuan_umum', 'ketentuan_khusus'];
    const quills = {};
    fields.forEach(f => {
        quills[f] = new Quill('#editor-'+f, { modules: { toolbar: '#toolbar-'+f }, theme: 'snow' });
    });
    
    document.getElementById('lomba-form').addEventListener('submit', () => {
        fields.forEach(f => {
            let html = quills[f].root.innerHTML;
            document.getElementById('input-'+f).value = html === '<p><br></p>' ? '' : html;
        });
    });

    // POSTER UTAMA PREVIEW
    function previewImageUtama(input, target) {
        if(input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = e => {
                document.getElementById('pr-'+target).src = e.target.result;
                document.getElementById('pr-'+target).classList.remove('hidden');
                document.getElementById('ph-'+target).classList.add('opacity-0');
                document.getElementById('btn-'+target).classList.remove('hidden');
                document.getElementById('rm-'+target).value = '0';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImageUtama(target) {
        document.getElementById('input-'+target).value = ""; 
        document.getElementById('pr-'+target).classList.add('hidden');
        document.getElementById('ph-'+target).classList.remove('opacity-0');
        document.getElementById('btn-'+target).classList.add('hidden');
        document.getElementById('rm-'+target).value = '1';
    }

    function removeDoc(type) {
        document.getElementById('container-' + type).classList.add('hidden');
        document.getElementById('rm-' + type).value = '1';
    }

    function previewDynImg(input, type, idx) {
        if(input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = e => {
                document.getElementById('pr-'+type+'-'+idx).src = e.target.result;
                document.getElementById('pr-'+type+'-'+idx).classList.remove('hidden');
                document.getElementById('ph-'+type+'-'+idx).classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // DYNAMIC TIMELINE
    let tIdx = 0;
    const tData = {!! isset($juknis) && is_array($juknis->timeline) ? json_encode($juknis->timeline) : '[]' !!};
    function addTimeline(data = {tanggal:'', head:'', deskripsi:''}) {
        const html = `
            <div id="tr-${tIdx}" class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-slate-50 p-5 rounded-2xl border border-slate-200 relative group transition-all hover:border-blue-200 hover:shadow-sm">
                <button type="button" onclick="document.getElementById('tr-${tIdx}').remove()" class="absolute -top-3 -right-2 bg-red-100 text-red-500 hover:bg-red-500 hover:text-white rounded-full w-6 h-6 flex items-center justify-center font-bold text-xs transition-colors shadow-sm opacity-0 group-hover:opacity-100">X</button>
                <div class="col-span-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1 block">Tenggat Waktu</label>
                    <input type="date" name="timeline[${tIdx}][tanggal]" value="${data.tanggal}" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary" required>
                </div>
                <div class="col-span-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1 block">Judul Fase</label>
                    <input type="text" name="timeline[${tIdx}][head]" value="${data.head}" placeholder="Cth: Pendaftaran Gelombang 1" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary" required>
                </div>
                <div class="col-span-2">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1 block">Keterangan / Aktivitas</label>
                    <input type="text" name="timeline[${tIdx}][deskripsi]" value="${data.deskripsi}" placeholder="Cth: Pengumpulan karya secara online" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary">
                </div>
            </div>`;
        document.getElementById('timeline-container').insertAdjacentHTML('beforeend', html);
        tIdx++;
    }

    // DYNAMIC HADIAH
    let hIdx = 0;
    const hData = {!! isset($juknis) && is_array($juknis->hadiah) ? json_encode($juknis->hadiah) : '[]' !!};
    function addHadiah(data = {peringkat:'', keterangan:'', isi:'', icon:''}) {
        const html = `
            <div id="hr-${hIdx}" class="flex flex-col md:flex-row gap-5 bg-amber-50/50 p-5 rounded-2xl border border-amber-100 relative group transition-all hover:border-amber-300 hover:shadow-sm">
                <button type="button" onclick="document.getElementById('hr-${hIdx}').remove()" class="absolute -top-3 -right-2 bg-red-100 text-red-500 hover:bg-red-500 hover:text-white rounded-full w-6 h-6 flex items-center justify-center font-bold text-xs transition-colors shadow-sm opacity-0 group-hover:opacity-100">X</button>
                
                <div class="w-full md:w-24 flex-shrink-0">
                    <label class="text-[10px] font-bold text-amber-700 uppercase tracking-wider mb-1 block">Ikon Piala</label>
                    <div class="relative w-full aspect-square border-2 border-dashed border-amber-300 hover:border-amber-500 rounded-xl bg-white flex items-center justify-center overflow-hidden cursor-pointer group/img" onclick="document.getElementById('input-hadiah-icon-${hIdx}').click()">
                        <div id="ph-hadiah-icon-${hIdx}" class="text-center ${data.icon ? 'hidden' : ''}">
                            <span class="text-[10px] font-bold text-amber-500 group-hover/img:text-amber-600">Unggah</span>
                        </div>
                        <img id="pr-hadiah-icon-${hIdx}" src="${data.icon ? '/storage/'+data.icon : ''}" class="absolute inset-0 w-full h-full object-cover z-20 ${data.icon ? '' : 'hidden'}" />
                    </div>
                    <input type="file" name="hadiah[${hIdx}][icon]" id="input-hadiah-icon-${hIdx}" accept="image/*" class="hidden" onchange="previewDynImg(this, 'hadiah-icon', ${hIdx})">
                    <input type="hidden" name="hadiah[${hIdx}][old_icon]" value="${data.icon}">
                </div>

                <div class="flex-grow grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-amber-700 uppercase tracking-wider mb-1 block">Peringkat / Kategori Juara</label>
                        <input type="text" name="hadiah[${hIdx}][peringkat]" value="${data.peringkat}" placeholder="Cth: Juara 1" class="w-full px-4 py-2.5 bg-white border border-amber-200 rounded-xl text-sm focus:outline-none focus:border-amber-400" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-amber-700 uppercase tracking-wider mb-1 block">Nama Bentuk Apresiasi</label>
                        <input type="text" name="hadiah[${hIdx}][keterangan]" value="${data.keterangan}" placeholder="Cth: Uang Tunai / Sponsorship" class="w-full px-4 py-2.5 bg-white border border-amber-200 rounded-xl text-sm focus:outline-none focus:border-amber-400">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-[10px] font-bold text-amber-700 uppercase tracking-wider mb-1 block">Isi Detail Hadiah</label>
                        <input type="text" name="hadiah[${hIdx}][isi]" value="${data.isi}" placeholder="Cth: Rp 5.000.000 + Sertifikat Pemenang + Piala KMDGI" class="w-full px-4 py-2.5 bg-white border border-amber-200 rounded-xl text-sm focus:outline-none focus:border-amber-400">
                    </div>
                </div>
            </div>`;
        document.getElementById('hadiah-container').insertAdjacentHTML('beforeend', html);
        hIdx++;
    }

    tData.forEach(d => addTimeline(d));
    hData.forEach(d => addHadiah(d));
    
    if(tData.length === 0) addTimeline();
    if(hData.length === 0) addHadiah();
</script>

<style>
    .ql-container { font-family: inherit !important; font-size: inherit; }
    .ql-editor { padding: 1rem; }
    .ql-toolbar.ql-snow { border: none !important; }
    .ql-container.ql-snow { border: none !important; }
</style>
@endsection