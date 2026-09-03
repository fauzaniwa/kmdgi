@extends('layouts.app')

@section('title', 'Posting Karya ' . ucfirst($kategori) . ' - KMDGI 16')

@section('content')

@php
$edisiAktif = \App\Models\EdisiKmdgi::where('is_active', 1)->first();
$deskripsiKarya = $edisiAktif ? \App\Models\DeskripsiKarya::where('edisi_kmdgi_id', $edisiAktif->id)->where('kategori_karya', strtolower($kategori))->first() : null;

// Status Verifikasi (Aman dari error jika draft masih kosong)
$statusVerifikasi = (isset($draft) && $draft) ? ($draft->status_verifikasi ?? 'Menunggu') : 'Menunggu';

// Form akan terkunci (Read-Only) JIKA status_draft == 0 (Terkirim)
// DAN status verifikasinya BUKAN Revisi / Ditolak.
$isFinal = isset($draft) && $draft && $draft->status_draft == 0 && !in_array($statusVerifikasi, ['Revisi', 'Ditolak']);

// Cek Ekstensi File Karya Utama (Untuk Preview Modal)
$fileKaryaExt = '';
$fileKaryaType = 'other';
if(isset($draft) && $draft->file_karya){
$fileKaryaExt = strtolower(pathinfo($draft->file_karya, PATHINFO_EXTENSION));
$fileKaryaType = in_array($fileKaryaExt, ['jpg', 'jpeg', 'png', 'gif', 'svg']) ? 'image' : ($fileKaryaExt == 'pdf' ? 'pdf' : 'other');
}

// Data Array untuk Galeri Media Tambahan
$rawMedia = isset($draft) && $draft->media_karya ? (is_string($draft->media_karya) ? json_decode($draft->media_karya, true) : $draft->media_karya) : [];
$mediaTambahan = is_array($rawMedia) ? array_filter($rawMedia) : [];
@endphp

<div class="bg-[#F8FAFC] min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8 font-sans">

        @include('partials.sidebar')

        <main class="flex-grow w-full pb-20">

            <div class="max-w-4xl mx-auto">

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-2xl shadow-sm mb-6 flex items-start gap-4">
                    <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" />
                    </svg>
                    <ul class="text-sm font-medium list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] mb-8 flex flex-col md:flex-row md:items-start justify-between gap-6 relative overflow-hidden">
                    <div class="absolute -right-16 -top-16 w-64 h-64 bg-gradient-to-br from-blue-50 to-indigo-50/30 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="relative z-10 flex-grow">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="inline-block border border-amber-200 text-amber-600 bg-amber-50 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">
                                Deadline: {{ (isset($deskripsiKarya) && $deskripsiKarya->deadline) ? \Carbon\Carbon::parse($deskripsiKarya->deadline)->translatedFormat('d M Y, H:i') . ' WIB' : 'Belum Ditentukan' }}
                            </span>
                            <span class="inline-block border border-emerald-200 text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">
                                Wajib
                            </span>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight mb-2">
                            {{ $isFinal ? 'Data Karya ' . ucfirst($kategori) : 'Posting Karya ' . ucfirst($kategori) }}
                        </h1>
                        <p class="text-sm text-slate-500 max-w-xl">
                            {{ $isFinal ? 'Berikut adalah data karya Anda yang telah dikirim ke sistem panitia.' : 'Lengkapi informasi detail terkait karya Anda. Data ini akan ditampilkan secara publik pada galeri pameran.' }}
                        </p>

                        <div class="flex flex-wrap items-center gap-2 mt-4" id="status-badges-container">
                            @if($isFinal)
                            @if($statusVerifikasi == 'Terverifikasi' || $statusVerifikasi == 'Diterima')
                            <div class="inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-2 rounded-lg text-xs font-bold shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Karya Telah Terverifikasi! (Read-Only)
                            </div>
                            @else
                            <div class="inline-flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-700 px-4 py-2 rounded-lg text-xs font-bold shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Terkirim & Menunggu Verifikasi (Read-Only)
                            </div>
                            @endif
                            @elseif(isset($draft) && $draft && $draft->status_draft == 0 && in_array($statusVerifikasi, ['Revisi', 'Ditolak']))
                            <div class="inline-flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-lg text-xs font-bold shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" />
                                </svg>
                                Mode {{ $statusVerifikasi }} Berkas Terbuka
                            </div>
                            @elseif(isset($draft) && $draft && $draft->status_draft == 1)
                            <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-2 rounded-lg text-xs font-bold shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                </svg>
                                Melanjutkan dari Draft (Belum Terkirim)
                            </div>
                            @endif
                        </div>

                        @if(isset($draft) && $draft && in_array($statusVerifikasi, ['Revisi', 'Ditolak']) && !empty($draft->catatan_revisi))
                        <div class="mt-5 p-5 bg-red-50/50 border border-red-200 rounded-xl shadow-sm max-w-2xl">
                            <div class="flex items-start gap-3">
                                <div class="bg-red-100 p-2 rounded-full flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-red-900">Catatan dari Admin / Kurator:</h4>
                                    <p class="text-sm text-red-800 mt-1 whitespace-pre-line leading-relaxed">{{ $draft->catatan_revisi }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>

                    <div class="relative z-10 flex-shrink-0 pt-4 md:pt-0 flex flex-col gap-3 w-full md:w-auto">
                        <a href="{{ route('delegasi.submisi.panduan', $kategori) }}" class="inline-flex items-center justify-center gap-2 bg-white border border-slate-200 text-slate-700 hover:text-[#1A68FF] hover:border-blue-200 hover:bg-blue-50 font-bold py-3 px-6 rounded-xl text-sm transition-all shadow-sm w-full">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                            Lihat Panduan
                        </a>

                        @if(isset($deskripsiKarya))
                        @if(!empty($deskripsiKarya->file_guidebook))
                        <a href="{{ asset('storage/' . $deskripsiKarya->file_guidebook) }}" download class="inline-flex items-center justify-center gap-2 bg-[#1A68FF] text-white border border-[#1A68FF] hover:bg-blue-700 hover:border-blue-700 font-bold py-3 px-6 rounded-xl text-sm transition-all shadow-sm w-full">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Download Panduan
                        </a>
                        @endif

                        @if(!empty($deskripsiKarya->berkas_lainnya))
                        @php
                        $berkasLainnya = is_string($deskripsiKarya->berkas_lainnya)
                        ? json_decode($deskripsiKarya->berkas_lainnya, true)
                        : $deskripsiKarya->berkas_lainnya;
                        @endphp

                        @if(is_array($berkasLainnya) && count($berkasLainnya) > 0)
                        @foreach($berkasLainnya as $berkas)
                        @if(!empty($berkas['file']))
                        <a href="{{ asset('storage/' . $berkas['file']) }}" download class="inline-flex items-center justify-center gap-2 bg-slate-50 text-slate-700 border border-slate-200 hover:bg-slate-100 hover:border-slate-300 font-bold py-3 px-6 rounded-xl text-sm transition-all shadow-sm w-full">
                            <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Unduh {{ $berkas['nama'] ?? 'Berkas' }}
                        </a>
                        @endif
                        @endforeach
                        @endif
                        @endif
                        @endif
                    </div>
                </div>

            </div>

            <form id="form-submisi" action="{{ route('delegasi.submisi.store', $kategori) }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if(isset($draft) && $draft)
                <input type="hidden" name="draft_id" value="{{ $draft->id }}">
                @endif
                <input type="hidden" name="status_draft" id="status_draft_input" value="0">

                <div class="space-y-8">

                    <!-- SECTION 1: IDENTITAS KARYA -->
                    <div class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
                        <div class="flex items-center gap-3 mb-8 pb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-[#1A68FF] font-black flex items-center justify-center text-sm">1</div>
                            <h3 class="text-lg font-bold text-slate-900 tracking-tight">Identitas Karya</h3>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-[13px] font-bold text-slate-800 mb-2">Judul Karya <span class="text-red-500 edit-lock-helper {{ $isFinal ? 'hidden' : 'inline' }}">*</span></label>
                                <input type="text" name="judul_karya" value="{{ old('judul_karya', $draft->judul_karya ?? '') }}" {{ $isFinal ? 'disabled' : 'required' }} placeholder="Masukkan judul karya Anda..." class="w-full px-5 py-3.5 border border-slate-200 rounded-xl text-sm transition-all outline-none {{ $isFinal ? 'bg-slate-100 text-slate-500 cursor-not-allowed opacity-80' : 'bg-slate-50 hover:bg-slate-100/50 focus:bg-white focus:border-[#1A68FF] focus:ring-4 focus:ring-[#1A68FF]/10 text-slate-800 placeholder-slate-400' }}">
                            </div>

                            <div>
                                <label class="block text-[13px] font-bold text-slate-800 mb-2">Nama Kreator <span class="text-red-500 edit-lock-helper {{ $isFinal ? 'hidden' : 'inline' }}">*</span></label>
                                <input type="text" name="kreator_karya" value="{{ old('kreator_karya', $draft->kreator_karya ?? '') }}" {{ $isFinal ? 'disabled' : 'required' }} placeholder="Contoh: Budi Susanto, Siti Aminah" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl text-sm transition-all outline-none {{ $isFinal ? 'bg-slate-100 text-slate-500 cursor-not-allowed opacity-80' : 'bg-slate-50 hover:bg-slate-100/50 focus:bg-white focus:border-[#1A68FF] focus:ring-4 focus:ring-[#1A68FF]/10 text-slate-800 placeholder-slate-400' }}">
                                <p class="edit-lock-helper text-[11px] font-medium text-slate-500 mt-2 items-center gap-1.5 {{ $isFinal ? 'hidden' : 'flex' }}">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                    </svg>
                                    Tulis nama lengkap. Pisahkan dengan tanda koma (,) jika lebih dari satu.
                                </p>
                            </div>

                            <div>
                                <label class="block text-[13px] font-bold text-slate-800 mb-2">Deskripsi Karya <span class="text-red-500 edit-lock-helper {{ $isFinal ? 'hidden' : 'inline' }}">*</span></label>
                                <textarea name="deskripsi_karya" {{ $isFinal ? 'disabled' : 'required' }} rows="5" placeholder="Ceritakan makna, proses kreatif, dan pesan yang ingin disampaikan..." class="w-full px-5 py-3.5 border border-slate-200 rounded-xl text-sm transition-all outline-none resize-none leading-relaxed {{ $isFinal ? 'bg-slate-100 text-slate-500 cursor-not-allowed opacity-80' : 'bg-slate-50 hover:bg-slate-100/50 focus:bg-white focus:border-[#1A68FF] focus:ring-4 focus:ring-[#1A68FF]/10 text-slate-800 placeholder-slate-400' }}">{{ old('deskripsi_karya', $draft->deskripsi_karya ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: THUMBNAIL PREVIEW DIRECT GAMBAR -->
                    <div class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-[#1A68FF] font-black flex items-center justify-center text-sm">2</div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Thumbnail (Preview Utama)</h3>
                            </div>
                        </div>

                        <p class="edit-lock-helper text-sm text-slate-500 mb-6 {{ $isFinal ? 'hidden' : 'block' }}">Unggah gambar yang merepresentasikan karya Anda. Gambar ini akan digunakan untuk kebutuhan katalog dan galeri situs.</p>

                        @php
                        $hasDraftThumb = isset($draft) && $draft->thumbnail_karya;
                        @endphp

                        <input type="hidden" name="remove_thumbnail" id="remove_thumbnail" value="0">
                        <!-- Accept diset tegas hanya format gambar -->
                        <input type="file" name="thumbnail_karya" id="thumbnail_karya" accept="image/png, image/jpeg, image/jpg, image/gif" class="hidden" onchange="handleFileSelect(this, 'thumbnail')" {{ $isFinal ? 'disabled' : '' }}>

                        <div id="dropzone-thumbnail" onclick="document.getElementById('thumbnail_karya').click()" class="{{ ($hasDraftThumb || $isFinal) ? 'hidden' : 'flex' }} w-full md:w-1/2 aspect-video border-2 border-dashed border-slate-300 rounded-[1.5rem] flex-col items-center justify-center cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 transition-all group">
                            <div class="w-14 h-14 bg-white border border-slate-200 text-slate-400 group-hover:text-[#1A68FF] group-hover:border-blue-200 rounded-full flex items-center justify-center mb-4 shadow-sm transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                </svg>
                            </div>
                            <p class="text-sm text-slate-700 mb-1"><span class="font-bold text-[#1A68FF]">Klik untuk unggah</span> gambar</p>
                            <p class="text-[11px] text-slate-500">Mendukung format PNG, JPG, JPEG, GIF</p>
                        </div>

                        <!-- Preview Langsung Memunculkan Gambar -->
                        <div id="preview-thumbnail" class="{{ ($hasDraftThumb || $isFinal) ? 'block' : 'hidden' }} relative w-full md:w-1/2 aspect-video rounded-[1.5rem] overflow-hidden border border-slate-200 shadow-sm group bg-slate-100">
                            <img id="img-preview-thumbnail" src="{{ $hasDraftThumb ? asset('storage/' . $draft->thumbnail_karya) : '' }}" class="w-full h-full object-cover" alt="Thumbnail Preview" />
                            
                            <!-- Tombol Silang (Hapus) Overlay -->
                            <div class="edit-only-btn absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm {{ $isFinal ? 'hidden' : '' }}">
                                <button type="button" onclick="removeFile('thumbnail')" class="w-12 h-12 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 shadow-lg transform scale-90 group-hover:scale-100 transition-all" title="Hapus Gambar">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- SECTION 3: GALERI MEDIA KARYA -->
                    <div class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
                        <div class="flex items-center gap-3 mb-4 pb-4 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-[#1A68FF] font-black flex items-center justify-center text-sm">3</div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Galeri Media Karya (Maks. 8)</h3>
                            </div>
                        </div>

                        <div class="edit-lock-helper mb-6 flex-col md:flex-row md:items-center justify-between gap-4 {{ $isFinal ? 'hidden' : 'flex' }}" id="gallery-info-text">
                            <p class="text-sm text-slate-500">Pilih beberapa foto/video sekaligus untuk memperlihatkan detail karya Anda (Maksimal 8 slot).</p>
                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg whitespace-nowrap"><span id="media-count">{{ count($mediaTambahan) }}</span> / 8 Media</span>
                        </div>

                        <div id="media-gallery-grid" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <!-- Existing Media -->
                            @foreach($mediaTambahan as $i => $mediaPath)
                                @php
                                    $mediaExt = strtolower(pathinfo($mediaPath, PATHINFO_EXTENSION));
                                    $isVideo = in_array($mediaExt, ['mp4', 'webm', 'mov']);
                                @endphp
                                <div class="relative aspect-square border border-slate-200 rounded-2xl overflow-hidden shadow-sm bg-slate-900 group existing-media-item" id="existing_media_{{$i}}">
                                    @if($isVideo)
                                        <video src="{{ asset('storage/' . $mediaPath) }}" class="w-full h-full object-cover"></video>
                                    @else
                                        <img src="{{ asset('storage/' . $mediaPath) }}" class="w-full h-full object-cover">
                                    @endif

                                    <div class="edit-only-btn absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm {{ $isFinal ? 'hidden' : '' }}">
                                        <button type="button" onclick="removeExistingMedia({{$i}})" class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 shadow-lg transform scale-90 group-hover:scale-100 transition-all" title="Hapus Gambar Ini">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                    <input type="hidden" name="remove_existing[{{$i}}]" id="remove_existing_{{$i}}" value="0">
                                </div>
                            @endforeach

                            <!-- Add Button -->
                            <div id="add-media-btn" class="{{ (count($mediaTambahan) >= 8 || $isFinal) ? 'hidden' : 'flex' }} relative aspect-square border-2 border-dashed border-slate-300 rounded-2xl flex-col items-center justify-center cursor-pointer hover:border-[#1A68FF] hover:bg-blue-50/50 transition-all text-slate-400 hover:text-[#1A68FF]" onclick="document.getElementById('media_baru_input').click()">
                                <svg class="w-8 h-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-center">Pilih<br>File Baru</span>
                            </div>
                            <!-- Accept diset tegas gambar dan video -->
                            <input type="file" name="media_baru[]" id="media_baru_input" multiple accept="image/png, image/jpeg, image/jpg, image/gif, video/mp4, video/webm" class="hidden" onchange="handleMultipleMedia(this)" {{ $isFinal ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <!-- SECTION 4: KARYA UTAMA -->
                    <div class="bg-blue-50/50 rounded-[2rem] p-8 md:p-10 border border-blue-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
                        <div class="flex items-start gap-4 mb-8">
                            <div class="w-8 h-8 flex-shrink-0 mt-0.5 rounded-full bg-[#1A68FF] text-white font-black flex items-center justify-center text-sm shadow-md shadow-blue-500/30">4</div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Berkas Karya Utama</h3>
                                <p class="edit-lock-helper text-sm text-slate-600 mt-1 leading-relaxed {{ $isFinal ? 'hidden' : 'block' }}">
                                    Pilih salah satu metode pengumpulan: <b>Lampirkan Tautan URL</b> (direkomendasikan untuk karya Video/Game/Website) <span class="text-red-500 font-bold">ATAU</span> <b>Unggah File</b> secara langsung ke sistem kami.
                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-[1.5rem] border border-slate-200 p-6 md:p-8 shadow-sm">

                            <div>
                                <label class="block text-[13px] font-bold text-slate-800 mb-2">Opsi 1: Tautan / URL Karya</label>
                                <div class="relative flex flex-col md:flex-row items-center gap-3">
                                    <div class="relative flex-grow w-full">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                                            </svg>
                                        </div>
                                        <input type="url" name="link_karya" value="{{ old('link_karya', $draft->link_karya ?? '') }}" {{ $isFinal ? 'disabled' : '' }} placeholder="Contoh: https://drive.google.com/..." class="w-full pl-11 pr-5 py-3.5 border border-slate-200 rounded-xl text-sm transition-all outline-none {{ $isFinal ? 'bg-slate-100 text-slate-500 cursor-not-allowed opacity-80' : 'bg-slate-50 hover:bg-slate-100/50 focus:bg-white focus:border-[#1A68FF] focus:ring-4 focus:ring-[#1A68FF]/10 text-slate-800 placeholder-slate-400' }}">
                                    </div>

                                    @if($isFinal && !empty($draft->link_karya))
                                    <a href="{{ $draft->link_karya }}" target="_blank" class="flex items-center justify-center gap-2 bg-[#1A68FF] text-white px-5 py-3.5 rounded-xl font-bold text-[13px] hover:bg-blue-700 transition-colors shadow-md shadow-blue-500/20 w-full md:w-auto flex-shrink-0">
                                        Buka Tautan
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                        </svg>
                                    </a>
                                    @endif
                                </div>
                                <p class="edit-lock-helper text-[11px] text-amber-600 font-medium mt-2 {{ $isFinal ? 'hidden' : 'block' }}">Pastikan akses link G-Drive/Figma/dsb telah di-set "Anyone with the link can view".</p>
                            </div>

                            <div class="flex items-center gap-4 py-8">
                                <div class="flex-grow h-px bg-slate-200"></div>
                                <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-3 py-1 rounded-md border border-slate-100">Atau</span>
                                <div class="flex-grow h-px bg-slate-200"></div>
                            </div>

                            <div>
                                <label class="block text-[13px] font-bold text-slate-800 mb-2">Opsi 2: Unggah File Langsung</label>

                                @php
                                $hasDraftFile = isset($draft) && $draft->file_karya;
                                @endphp

                                <input type="hidden" name="remove_file" id="remove_file" value="0">
                                <!-- Accept diset tegas menolak docs -->
                                <input type="file" name="file_karya" id="file_karya" accept=".zip,.rar,.pdf" class="hidden" onchange="handleFileSelect(this, 'file')" {{ $isFinal ? 'disabled' : '' }}>

                                <div id="dropzone-file" onclick="document.getElementById('file_karya').click()" class="{{ ($hasDraftFile || $isFinal) ? 'hidden' : 'flex' }} border-2 border-dashed border-slate-300 rounded-[1.5rem] p-8 flex-col items-center justify-center cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 transition-all group">
                                    <div class="w-12 h-12 bg-white border border-slate-200 text-slate-400 group-hover:text-[#1A68FF] group-hover:border-blue-200 rounded-full flex items-center justify-center mb-3 shadow-sm transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                                        </svg>
                                    </div>
                                    <p class="text-[13px] text-slate-700 mb-1"><span class="font-bold text-[#1A68FF]">Pilih File Berkas</span> (Maks. 20MB)</p>
                                    <p class="text-[11px] text-slate-500">Mendukung format: ZIP, RAR, PDF.</p>
                                </div>

                                <div id="preview-file" class="{{ ($hasDraftFile || ($isFinal && $hasDraftFile)) ? 'block' : 'hidden' }} border border-slate-200 rounded-[1.5rem] p-5 md:p-6 bg-slate-50">
                                    <div class="flex items-center gap-5">
                                        <div class="w-12 h-12 flex-shrink-0 bg-slate-800 text-white rounded-xl flex items-center justify-center font-bold text-[11px] uppercase tracking-wider shadow-sm">FILE</div>
                                        <div class="flex-grow">
                                            <div class="flex justify-between items-start mb-2.5">
                                                <div>
                                                    <p id="filename-file" class="text-sm font-bold text-slate-900 truncate max-wxs md:max-w-md">{{ $hasDraftFile ? basename($draft->file_karya) : 'karya_utama.zip' }}</p>
                                                    <p class="text-[11px] font-medium text-slate-500 flex items-center gap-2 mt-0.5">
                                                        <span id="filesize-file">{{ $hasDraftFile ? ($isFinal ? 'Berkas Final' : 'Draft Tersimpan') : '' }}</span>
                                                    </p>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    @if($hasDraftFile)
                                                    <button type="button" onclick="viewFile('{{ asset('storage/' . $draft->file_karya) }}', '{{ $fileKaryaType }}', '{{ basename($draft->file_karya) }}')" class="flex items-center gap-1.5 text-[#1A68FF] hover:text-blue-700 bg-white border border-blue-200 hover:bg-blue-50 rounded-lg px-3 py-1.5 transition-colors shadow-sm text-[11px] font-bold" title="Lihat Berkas Utama">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                        Lihat
                                                    </button>
                                                    @endif

                                                    <button type="button" onclick="removeFile('file')" class="btn-remove-file text-slate-400 hover:text-red-500 bg-white border border-slate-200 hover:border-red-200 rounded-lg p-1.5 transition-colors shadow-sm {{ $isFinal ? 'hidden' : 'flex' }}" title="Hapus File">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <div class="w-full bg-slate-200 rounded-full h-1.5">
                                                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 100%"></div>
                                                </div>
                                                <span class="text-[11px] text-slate-500 font-bold">100%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($isFinal && !$hasDraftFile)
                                <div id="no-file-alert" class="border border-slate-200 rounded-[1.5rem] p-5 md:p-6 bg-slate-50 text-center">
                                    <p class="text-sm font-bold text-slate-600">Tidak ada file yang diunggah.</p>
                                    <p class="text-[11px] text-slate-500 mt-1">Anda menggunakan metode Tautan/URL (Opsi 1) untuk pengumpulan karya ini.</p>
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>

                </div>

                <!-- ACTION BUTTONS: DYNAMIC TOGGLE -->
                <div id="action-buttons-final" class="flex flex-col sm:flex-row justify-end gap-3 mt-10 {{ $isFinal ? 'flex' : 'hidden' }}">
                    <a href="{{ route('delegasi.submisi.panduan', $kategori) }}" class="flex items-center justify-center gap-2 bg-white text-slate-600 border border-slate-200 font-bold text-[13px] px-8 py-4 rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                        Kembali ke Panduan
                    </a>
                    <button type="button" onclick="enableEditMode()" class="flex items-center justify-center gap-2 bg-[#1A1A1A] text-white font-bold text-[13px] px-8 py-4 rounded-xl hover:bg-black transition-all shadow-md focus:outline-none">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                        Edit Seluruh Data
                    </button>
                </div>

                <div id="action-buttons-edit" class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-10 {{ $isFinal ? 'hidden' : 'flex' }}">
                    <button type="button" onclick="triggerSubmitModal(true, this)" data-title="Simpan sebagai Draft?" data-message="Data Anda akan disimpan sementara. Anda bisa kembali untuk melengkapinya nanti sebelum mengirim secara final." data-type="info" data-primary-text="Ya, Simpan Draft" data-secondary-text="Batal" data-form-id="form-submisi" class="flex items-center justify-center gap-2 bg-white text-slate-600 border border-slate-200 font-bold text-[13px] px-8 py-4 rounded-xl hover:bg-slate-50 transition-colors">
                        Simpan Draft
                    </button>
                    <button type="button" onclick="triggerSubmitModal(false, this)" data-title="Kirim Submisi Final?" data-message="Data akan dikirim ke sistem panitia. Data ini akan ditampilkan pada Galeri Pameran KMDGI." data-type="success" data-primary-text="Kirim Final" data-secondary-text="Batal" data-form-id="form-submisi" class="bg-[#1A1A1A] text-white font-bold text-[13px] px-10 py-4 rounded-xl hover:bg-black transition-all shadow-md">
                        Kirim Submisi Final
                    </button>
                </div>
            </form>
        </main>
    </div>
</div>
@include('partials.footer')

<!-- MODAL PREVIEW FILE LAINNYA -->
<div id="filePreviewModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0" onclick="closeFilePreview()"></div>
    <div class="relative bg-white rounded-[2rem] overflow-hidden w-full max-w-4xl shadow-2xl transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]" id="filePreviewContent">

        <div class="bg-white border-b border-slate-100 px-6 py-5 flex justify-between items-center relative z-10 flex-shrink-0">
            <div>
                <h3 class="font-bold text-slate-900 text-lg" id="previewTitle">Preview Berkas</h3>
                <p class="text-xs font-mono font-medium text-slate-500 mt-0.5" id="previewName">namafile.png</p>
            </div>
            <button type="button" onclick="closeFilePreview()" class="text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 p-2 rounded-full transition-colors focus:outline-none">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6 bg-slate-50 flex-grow flex justify-center items-center overflow-auto relative min-h-[300px]" id="previewContainer">
        </div>

        <div class="p-5 bg-white border-t border-slate-100 flex justify-end flex-shrink-0">
            <a href="#" id="previewDownloadBtn" target="_blank" download class="inline-flex items-center gap-2 bg-[#1A68FF] text-white font-bold py-2.5 px-6 rounded-xl hover:bg-blue-700 shadow-md shadow-blue-500/20 transition-all text-[13px]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Unduh File Original
            </a>
        </div>
    </div>
</div>

<!-- MODAL MAKSIMAL 8 FILE -->
<div id="maxFilesModal" class="fixed inset-0 z-[150] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0" onclick="closeMaxFilesModal()"></div>
    <div class="relative bg-white rounded-[2rem] p-8 max-w-sm w-full shadow-2xl transform scale-95 transition-transform duration-300 text-center" id="maxFilesContent">
        <div class="w-16 h-16 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-amber-50">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" />
            </svg>
        </div>
        <h3 class="text-lg font-black text-slate-900 mb-1">Batas Maksimal Tercapai</h3>
        <p class="text-[13px] text-slate-500 leading-relaxed mb-6">Anda hanya dapat memilih total maksimal 8 media untuk galeri ini. Beberapa file otomatis diabaikan.</p>
        <button type="button" onclick="closeMaxFilesModal()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition-all focus:outline-none">
            Saya Mengerti
        </button>
    </div>
</div>

<script>
    // JS MENGAKTIFKAN MODE EDIT
    function enableEditMode() {
        document.querySelectorAll('input[type="text"], input[type="url"], textarea').forEach(el => {
            el.disabled = false;
            el.classList.remove('bg-slate-100', 'text-slate-500', 'cursor-not-allowed', 'opacity-80');
            el.classList.add('bg-slate-50', 'hover:bg-slate-100/50', 'focus:bg-white', 'focus:border-[#1A68FF]', 'text-slate-800');
        });

        document.querySelectorAll('input[type="file"]').forEach(el => el.disabled = false);

        document.querySelectorAll('.edit-lock-helper, .btn-remove-file').forEach(el => {
            el.classList.remove('hidden');
            if(el.tagName === 'P') el.classList.add('flex');
        });

        document.querySelectorAll('.edit-only-btn').forEach(el => {
            el.classList.remove('hidden');
        });

        const hasDraftThumb = {{ $hasDraftThumb ? 'true' : 'false' }};
        if(!hasDraftThumb && document.getElementById('remove_thumbnail').value !== '0') {
            document.getElementById('dropzone-thumbnail').classList.remove('hidden');
            document.getElementById('dropzone-thumbnail').classList.add('flex');
            document.getElementById('preview-thumbnail').classList.add('hidden');
            document.getElementById('preview-thumbnail').classList.remove('block');
        }

        const hasDraftFile = {{ $hasDraftFile ? 'true' : 'false' }};
        if(!hasDraftFile && document.getElementById('remove_file').value !== '0') {
            document.getElementById('dropzone-file').classList.remove('hidden');
            document.getElementById('dropzone-file').classList.add('flex');
            document.getElementById('preview-file').classList.add('hidden');
            const noFileAlert = document.getElementById('no-file-alert');
            if(noFileAlert) noFileAlert.classList.add('hidden');
        }

        document.getElementById('status-badges-container').classList.add('hidden');
        document.getElementById('action-buttons-final').classList.add('hidden');
        document.getElementById('action-buttons-final').classList.remove('flex');
        document.getElementById('action-buttons-edit').classList.remove('hidden');
        document.getElementById('action-buttons-edit').classList.add('flex');

        updateMediaCountUI();
    }

    function viewFile(url, type, name) {
        const modal = document.getElementById('filePreviewModal');
        const content = document.getElementById('filePreviewContent');
        const container = document.getElementById('previewContainer');

        document.getElementById('previewName').innerText = name;
        document.getElementById('previewDownloadBtn').href = url;

        container.innerHTML = ''; 

        if (type === 'image') {
            container.innerHTML = `<img src="${url}" alt="${name}" class="max-w-full max-h-full object-contain rounded-xl shadow-sm border border-slate-200 bg-white">`;
        } else if (type === 'pdf') {
            container.innerHTML = `<iframe src="${url}" class="w-full h-[60vh] rounded-xl border border-slate-200 shadow-sm bg-white"></iframe>`;
        } else {
            container.innerHTML = `
                <div class="text-center bg-white p-10 rounded-3xl border border-slate-200 shadow-sm">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /></svg>
                    </div>
                    <p class="text-slate-800 font-bold mb-1">Preview tidak tersedia</p>
                    <p class="text-[13px] text-slate-500 max-w-sm mx-auto">Format file ini (.zip / tipe lainnya) tidak dapat dipreview secara langsung di browser. Silakan unduh file untuk melihat isinya.</p>
                </div>
            `;
        }

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeFilePreview() {
        const modal = document.getElementById('filePreviewModal');
        const content = document.getElementById('filePreviewContent');

        modal.classList.add('opacity-0');
        content.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('previewContainer').innerHTML = ''; 
        }, 300);
    }

    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    // FUNGSI HANDLE SINGLE FILE (THUMBNAIL & FILE UTAMA)
    function handleFileSelect(input, type) {
        const dropzone = document.getElementById('dropzone-' + type);
        const preview = document.getElementById('preview-' + type);
        const removeFlag = document.getElementById('remove_' + type);

        if (input.files && input.files[0]) {
            const file = input.files[0];

            if (type === 'thumbnail') {
                if (!file.type.startsWith('image/')) {
                    alert('Hanya format gambar (PNG, JPG, GIF) yang diizinkan!');
                    input.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('img-preview-thumbnail').src = e.target.result;
                    dropzone.classList.add('hidden');
                    dropzone.classList.remove('flex');
                    preview.classList.remove('hidden');
                    preview.classList.add('block');
                }
                reader.readAsDataURL(file);
            } else {
                const filenameLabel = document.getElementById('filename-' + type);
                const filesizeLabel = document.getElementById('filesize-' + type);
                if(filenameLabel) filenameLabel.innerText = file.name;
                if(filesizeLabel) filesizeLabel.innerText = formatBytes(file.size);

                dropzone.classList.add('hidden');
                dropzone.classList.remove('flex');
                preview.classList.remove('hidden');
                preview.classList.add('block');
            }
            
            if(removeFlag) removeFlag.value = '0';
        }
    }

    function removeFile(type) {
        const input = document.getElementById(type + '_karya');
        const dropzone = document.getElementById('dropzone-' + type);
        const preview = document.getElementById('preview-' + type);
        const removeFlag = document.getElementById('remove_' + type);

        input.value = "";
        preview.classList.add('hidden');
        preview.classList.remove('block');
        dropzone.classList.remove('hidden');
        dropzone.classList.add('flex');

        if (type === 'thumbnail') {
            document.getElementById('img-preview-thumbnail').src = '';
        }

        if(removeFlag) removeFlag.value = '1';
    }

    // ==========================================
    // JS UNTUK GALERI MULTIPLE MEDIA (MAX 8)
    // ==========================================
    const dtMedia = new DataTransfer();

    function countExistingMedia() {
        return document.querySelectorAll('.existing-media-item:not(.hidden)').length;
    }

    function updateMediaCountUI() {
        const total = countExistingMedia() + dtMedia.items.length;
        const countSpan = document.getElementById('media-count');
        const addBtn = document.getElementById('add-media-btn');
        
        if(countSpan) countSpan.innerText = total;
        if(addBtn) {
            if (total >= 8) {
                addBtn.classList.add('hidden');
                addBtn.classList.remove('flex');
            } else {
                addBtn.classList.remove('hidden');
                addBtn.classList.add('flex');
            }
        }
    }

    function removeExistingMedia(index) {
        document.getElementById(`existing_media_${index}`).classList.add('hidden');
        document.getElementById(`remove_existing_${index}`).value = '1';
        updateMediaCountUI();
    }

    function handleMultipleMedia(input) {
        let currentTotal = countExistingMedia() + dtMedia.items.length;
        let limitExceeded = false;
        
        for (let i = 0; i < input.files.length; i++) {
            if (currentTotal >= 8) {
                limitExceeded = true;
                break;
            }
            dtMedia.items.add(input.files[i]);
            currentTotal++;
        }
        
        input.files = dtMedia.files; 
        renderNewMediaPreviews();
        
        if(limitExceeded) {
            const modal = document.getElementById('maxFilesModal');
            const content = document.getElementById('maxFilesContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);
        }
    }

    function closeMaxFilesModal() {
        const modal = document.getElementById('maxFilesModal');
        const content = document.getElementById('maxFilesContent');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function renderNewMediaPreviews() {
        document.querySelectorAll('.new-media-preview-item').forEach(e => e.remove());
        
        const grid = document.getElementById('media-gallery-grid');
        const addBtn = document.getElementById('add-media-btn');

        Array.from(dtMedia.files).forEach((file, index) => {
            const isVideo = file.type.startsWith('video/');
            const url = URL.createObjectURL(file);
            
            const div = document.createElement('div');
            div.className = 'relative aspect-square border-2 border-slate-200 rounded-2xl overflow-hidden shadow-sm bg-slate-900 group new-media-preview-item animate-fade-in';
            
            let mediaHtml = isVideo 
                ? `<video src="${url}" class="w-full h-full object-cover" muted></video>`
                : `<img src="${url}" class="w-full h-full object-cover">`;
                
            div.innerHTML = `
                ${mediaHtml}
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                    <button type="button" onclick="removeNewMedia(${index})" class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 shadow-lg transform scale-90 group-hover:scale-100 transition-all" title="Batal Tambah">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            `;
            grid.insertBefore(div, addBtn);
        });
        updateMediaCountUI();
    }

    function removeNewMedia(index) {
        dtMedia.items.remove(index);
        document.getElementById('media_baru_input').files = dtMedia.files;
        renderNewMediaPreviews(); 
    }

    function triggerSubmitModal(isDraft, element) {
        const form = document.getElementById('form-submisi');
        document.getElementById('status_draft_input').value = isDraft ? '1' : '0';

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        openModal('kmdgi-global-modal', element);
    }
</script>

<style>
    .animate-fade-in { animation: fadeIn 0.4s ease-in-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
</style>
@endsection