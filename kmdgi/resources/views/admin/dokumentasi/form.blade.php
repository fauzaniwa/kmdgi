@extends('layouts.app')

@section('title', (isset($dokumentasi) ? 'Edit' : 'Tambah') . ' Dokumentasi - KMDGI 16')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">
            
            <a href="{{ route('admin.dokumentasi.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-kmdgi-primary transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Kembali ke Galeri
            </a>

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

            <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ isset($dokumentasi) ? 'Edit Media Dokumentasi' : 'Upload Dokumentasi Baru' }}</h2>
                    <p class="text-sm text-slate-500 mt-1">Pilih tipe media yang ingin diunggah (Foto, Upload Video Pendek, atau Tempel Link YouTube).</p>
                </div>

                <form id="dokumentasi-form" action="{{ isset($dokumentasi) ? route('admin.dokumentasi.update', $dokumentasi->id) : route('admin.dokumentasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @if(isset($dokumentasi))
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                        
                        <!-- KOLOM KIRI: Media Uploader -->
                        <div class="space-y-5 bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Tipe Media <span class="text-red-500">*</span></label>
                                <select name="tipe_media" id="input-tipe" required onchange="toggleMediaType()" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary cursor-pointer font-semibold text-slate-700 shadow-sm">
                                    <option value="Foto" {{ old('tipe_media', $dokumentasi->tipe_media ?? '') == 'Foto' ? 'selected' : '' }}>📸 Unggah Foto (JPG/PNG)</option>
                                    <option value="Video Upload" {{ old('tipe_media', $dokumentasi->tipe_media ?? '') == 'Video Upload' ? 'selected' : '' }}>📼 Unggah Video Lokal (Max 20MB)</option>
                                    <option value="Video YouTube" {{ old('tipe_media', $dokumentasi->tipe_media ?? '') == 'Video YouTube' ? 'selected' : '' }}>🔴 Tautkan Link YouTube</option>
                                </select>
                            </div>

                            <!-- Area 1: Upload File Local -->
                            <div id="area-upload">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Upload File Media <span class="text-red-500">*</span></label>
                                <div class="relative w-full aspect-video border-2 border-dashed border-slate-300 hover:border-kmdgi-primary rounded-xl bg-white flex items-center justify-center p-4 group cursor-pointer transition-colors overflow-hidden" onclick="document.getElementById('input-file').click()">
                                    
                                    <!-- Placeholder -->
                                    <div id="file-placeholder" class="flex flex-col items-center text-center pointer-events-none relative z-10 transition-opacity {{ (isset($dokumentasi) && $dokumentasi->file_path) ? 'opacity-0' : '' }}">
                                        <svg class="w-8 h-8 text-slate-400 mb-2 group-hover:text-kmdgi-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /></svg>
                                        <span class="text-xs font-bold text-slate-500" id="text-hint-file">Klik untuk memilih File</span>
                                    </div>

                                    <!-- Preview Image -->
                                    <img id="file-preview-img" src="{{ (isset($dokumentasi) && $dokumentasi->tipe_media === 'Foto') ? asset('storage/' . $dokumentasi->file_path) : '' }}" class="absolute inset-0 w-full h-full object-cover z-20 {{ (isset($dokumentasi) && $dokumentasi->tipe_media === 'Foto') ? '' : 'hidden' }}" />
                                    
                                    <!-- Preview Video -->
                                    <video id="file-preview-vid" src="{{ (isset($dokumentasi) && $dokumentasi->tipe_media === 'Video Upload') ? asset('storage/' . $dokumentasi->file_path) : '' }}" controls class="absolute inset-0 w-full h-full object-cover z-20 bg-black {{ (isset($dokumentasi) && $dokumentasi->tipe_media === 'Video Upload') ? '' : 'hidden' }}"></video>
                                    
                                </div>
                                <input type="file" name="file_media" id="input-file" class="hidden" onchange="previewFile(this)">
                            </div>

                            <!-- Area 2: YouTube Link -->
                            <div id="area-youtube" class="hidden space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tautan Video YouTube <span class="text-red-500">*</span></label>
                                    <input type="url" name="video_url" id="input-youtube" value="{{ old('video_url', $dokumentasi->video_url ?? '') }}" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 transition-all" placeholder="https://www.youtube.com/watch?v=..." oninput="previewYoutube(this.value)">
                                </div>
                                
                                <div class="w-full aspect-video bg-slate-200 rounded-xl overflow-hidden border border-slate-300 flex items-center justify-center relative">
                                    <span id="yt-placeholder" class="text-xs font-bold text-slate-400">Preview YouTube</span>
                                    <iframe id="yt-preview-frame" class="absolute inset-0 w-full h-full hidden" src="" frameborder="0" allowfullscreen></iframe>
                                </div>
                            </div>

                        </div>

                        <!-- KOLOM KANAN: Data Informasi -->
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Judul Momen / Foto <span class="text-red-500">*</span></label>
                                <input type="text" name="judul" value="{{ old('judul', $dokumentasi->judul ?? '') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all" placeholder="Cth: Suasana Malam Penutupan KMDGI">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kategori Kegiatan <span class="text-red-500">*</span></label>
                                    <input type="text" list="kategori-list" name="kategori_kegiatan" value="{{ old('kategori_kegiatan', $dokumentasi->kategori_kegiatan ?? '') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all" placeholder="Pilih / Ketik baru">
                                    <datalist id="kategori-list">
                                        <option value="Pra-Event"></option>
                                        <option value="Pameran & Instalasi"></option>
                                        <option value="Seminar & Talkshow"></option>
                                        <option value="Malam Puncak"></option>
                                    </datalist>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal Diambil <span class="text-red-500">*</span></label>
                                    <input type="date" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', $dokumentasi->tanggal_kegiatan ?? '') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all">
                                </div>
                            </div>

                            <div class="flex flex-col flex-grow">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Deskripsi Media / Caption (Opsional)</label>
                                <input type="hidden" name="deskripsi" id="input-deskripsi">
                                <div class="flex-grow flex flex-col border border-slate-200 rounded-xl overflow-hidden focus-within:border-kmdgi-primary focus-within:ring-1 focus-within:ring-kmdgi-primary/30 transition-all bg-white min-h-[180px]">
                                    <div id="editor-toolbar" class="bg-slate-50/80 border-b border-slate-200 py-1.5">
                                        <span class="ql-formats"><button class="ql-bold"></button><button class="ql-italic"></button></span>
                                        <span class="ql-formats"><button class="ql-clean"></button></span>
                                    </div>
                                    <div id="quill-editor" class="flex-grow h-full text-[14px] text-slate-700 p-2">{!! old('deskripsi', $dokumentasi->deskripsi ?? '') !!}</div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status Tampilan <span class="text-red-500">*</span></label>
                                <select name="is_active" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary cursor-pointer">
                                    <option value="1" {{ old('is_active', $dokumentasi->is_active ?? 1) == 1 ? 'selected' : '' }}>Publikasikan di Galeri Web</option>
                                    <option value="0" {{ old('is_active', $dokumentasi->is_active ?? 1) == 0 ? 'selected' : '' }}>Sembunyikan Sementara</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <a href="{{ route('admin.dokumentasi.index') }}" class="inline-flex justify-center bg-white border border-slate-200 text-slate-600 font-bold py-3.5 px-8 rounded-xl hover:bg-slate-50 transition-colors text-sm shadow-sm">Batalkan</a>
                        <button type="submit" class="inline-flex justify-center bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-3.5 px-8 rounded-xl transition-colors text-sm shadow-md shadow-kmdgi-primary/20">
                            {{ isset($dokumentasi) ? 'Simpan Perubahan' : 'Upload Dokumentasi' }}
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>

    @include('partials.footer')
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    // Inisialisasi Quill
    const quill = new Quill('#quill-editor', {
        modules: { toolbar: '#editor-toolbar' },
        placeholder: 'Tuliskan caption singkat untuk foto/video ini...',
        theme: 'snow'
    });

    document.getElementById('dokumentasi-form').addEventListener('submit', function(e) {
        let htmlContent = quill.root.innerHTML;
        if (htmlContent === '<p><br></p>') htmlContent = '';
        document.getElementById('input-deskripsi').value = htmlContent;
    });

    // Fitur Cerdas: Toggle Media UI
    function toggleMediaType() {
        const type = document.getElementById('input-tipe').value;
        const areaUpload = document.getElementById('area-upload');
        const areaYoutube = document.getElementById('area-youtube');
        const inputFile = document.getElementById('input-file');
        const inputYoutube = document.getElementById('input-youtube');
        const hintText = document.getElementById('text-hint-file');

        if (type === 'Video YouTube') {
            areaUpload.classList.add('hidden');
            areaYoutube.classList.remove('hidden');
            inputFile.required = false;
            
            // Re-trigger preview youtube jika url sudah ada isinya (kasus Edit)
            if(inputYoutube.value !== '') previewYoutube(inputYoutube.value);
        } else {
            areaUpload.classList.remove('hidden');
            areaYoutube.classList.add('hidden');
            inputYoutube.required = false;
            
            // Ubah Hint Text dan Attribute Accept file sesuai Tipe (Foto vs Video)
            if (type === 'Foto') {
                hintText.innerText = "Klik untuk memilih Foto (JPG/PNG)";
                inputFile.setAttribute('accept', 'image/*');
            } else {
                hintText.innerText = "Klik untuk memilih Video (MP4 - Max 20MB)";
                inputFile.setAttribute('accept', 'video/mp4,video/quicktime');
            }
        }
    }

    // Fitur Cerdas: Local File Previewer (Mendeteksi File Gambar vs Video)
    function previewFile(input) {
        const type = document.getElementById('input-tipe').value;
        const previewImg = document.getElementById('file-preview-img');
        const previewVid = document.getElementById('file-preview-vid');
        const placeholder = document.getElementById('file-placeholder');

        if (input.files && input.files[0]) {
            const fileURL = URL.createObjectURL(input.files[0]);
            placeholder.classList.add('opacity-0');

            if (type === 'Foto') {
                previewImg.src = fileURL;
                previewImg.classList.remove('hidden');
                previewVid.classList.add('hidden');
            } else {
                previewVid.src = fileURL;
                previewVid.classList.remove('hidden');
                previewImg.classList.add('hidden');
            }
        }
    }

    // Fitur Cerdas: YouTube Extractor & Previewer
    function previewYoutube(url) {
        const frame = document.getElementById('yt-preview-frame');
        const placeholder = document.getElementById('yt-placeholder');
        
        // Regex untuk mengambil ID Video dari format (youtu.be/xxx atau youtube.com/watch?v=xxx)
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
        const match = url.match(regExp);

        if (match && match[2].length === 11) {
            const videoId = match[2];
            frame.src = `https://www.youtube.com/embed/${videoId}?rel=0`;
            frame.classList.remove('hidden');
            placeholder.classList.add('hidden');
        } else {
            frame.src = "";
            frame.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    }

    // Eksekusi UI saat halaman dimuat (Penting untuk mode EDIT)
    document.addEventListener('DOMContentLoaded', () => toggleMediaType());
</script>

<style>
    .ql-container { font-family: inherit !important; font-size: inherit; }
    .ql-editor { padding: 1rem; }
    .ql-toolbar.ql-snow { border: none !important; }
    .ql-container.ql-snow { border: none !important; }
</style>
@endsection