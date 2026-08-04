@extends('layouts.app')

@section('title', (isset($penampil) ? 'Edit' : 'Tambah') . ' Data Penampil - KMDGI 16')

@section('content')
<!-- Memanggil CSS Quill.js -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">
            
            <a href="{{ route('admin.penampil.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-kmdgi-primary transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Penampil
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
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ isset($penampil) ? 'Edit Profil Penampil' : 'Tambah Penampil Baru' }}</h2>
                    <p class="text-sm text-slate-500 mt-1">Lengkapi informasi jadwal, lokasi, dan detail acara penampil secara komprehensif.</p>
                </div>

                <form id="penampil-form" action="{{ isset($penampil) ? route('admin.penampil.update', $penampil->id) : route('admin.penampil.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @if(isset($penampil))
                        @method('PUT')
                    @endif

                    <!-- Area Gambar: Cover & Logo -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- Logo Upload (Kiri, Kecil) -->
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Logo (Opsional, 1:1)</label>
                            <div class="relative w-full aspect-square border-2 border-dashed border-slate-300 hover:border-kmdgi-primary rounded-2xl bg-slate-50 flex items-center justify-center overflow-hidden group cursor-pointer transition-colors" onclick="document.getElementById('input-logo').click()">
                                <div id="logo-placeholder" class="flex flex-col items-center pointer-events-none relative z-10 transition-opacity {{ (isset($penampil) && $penampil->logo_penampil) ? 'opacity-0' : '' }}">
                                    <svg class="w-8 h-8 text-slate-400 mb-2 group-hover:text-kmdgi-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                                    <span class="text-[10px] font-bold text-slate-500">Pilih Logo</span>
                                </div>
                                <img id="logo-preview" src="{{ (isset($penampil) && $penampil->logo_penampil) ? asset('storage/' . $penampil->logo_penampil) : '' }}" class="absolute inset-0 w-full h-full object-cover z-20 {{ (isset($penampil) && $penampil->logo_penampil) ? '' : 'hidden' }}" />
                            </div>
                            <input type="file" name="logo_penampil" id="input-logo" accept="image/*" class="hidden" onchange="previewImage(this, 'logo-preview', 'logo-placeholder', 'btn-remove-logo')">
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-[10px] text-slate-400">Max 2MB. Disarankan PNG transparan.</p>
                                <button type="button" id="btn-remove-logo" class="{{ (isset($penampil) && $penampil->logo_penampil) ? '' : 'hidden' }} text-[10px] font-bold text-red-500 hover:text-red-600" onclick="removeImage('input-logo', 'logo-preview', 'logo-placeholder', 'btn-remove-logo')">Hapus Logo</button>
                            </div>
                        </div>

                        <!-- Cover Upload (Kanan, Lebar 16:9) -->
                        <div class="col-span-1 lg:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cover Poster/Foto (Opsional, 16:9)</label>
                            <div class="relative w-full h-full min-h-[200px] border-2 border-dashed border-slate-300 hover:border-kmdgi-primary rounded-2xl bg-slate-50 flex items-center justify-center overflow-hidden group cursor-pointer transition-colors" onclick="document.getElementById('input-cover').click()">
                                <div id="cover-placeholder" class="flex flex-col items-center pointer-events-none relative z-10 transition-opacity {{ (isset($penampil) && $penampil->cover_penampil) ? 'opacity-0' : '' }}">
                                    <svg class="w-10 h-10 text-slate-400 mb-2 group-hover:text-kmdgi-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                                    <span class="text-[11px] font-bold text-slate-500">Klik untuk memilih gambar spanduk utama</span>
                                </div>
                                <img id="cover-preview" src="{{ (isset($penampil) && $penampil->cover_penampil) ? asset('storage/' . $penampil->cover_penampil) : '' }}" class="absolute inset-0 w-full h-full object-cover z-20 {{ (isset($penampil) && $penampil->cover_penampil) ? '' : 'hidden' }}" />
                            </div>
                            <input type="file" name="cover_penampil" id="input-cover" accept="image/*" class="hidden" onchange="previewImage(this, 'cover-preview', 'cover-placeholder', 'btn-remove-cover')">
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-[10px] text-slate-400">Max size 3MB. Format JPG, PNG, WEBP.</p>
                                <button type="button" id="btn-remove-cover" class="{{ (isset($penampil) && $penampil->cover_penampil) ? '' : 'hidden' }} text-[10px] font-bold text-red-500 hover:text-red-600" onclick="removeImage('input-cover', 'cover-preview', 'cover-placeholder', 'btn-remove-cover')">Hapus Cover</button>
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-100 my-2">

                    <!-- Data Utama -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Penampil / Judul Acara <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_penampil" value="{{ old('nama_penampil', $penampil->nama_penampil ?? '') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori Utama <span class="text-red-500">*</span></label>
                            <input type="text" list="kategori-list" name="kategori_penampil" value="{{ old('kategori_penampil', $penampil->kategori_penampil ?? '') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all" placeholder="Pilih atau ketik jenis penampil">
                            <datalist id="kategori-list">
                                <option value="Band/Musisi"></option>
                                <option value="Guest Speaker"></option>
                                <option value="Seni & Tari"></option>
                                <option value="Workshop"></option>
                                <option value="Pameran Interaktif"></option>
                            </datalist>
                        </div>
                    </div>

                    <!-- Jadwal & Lokasi -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Tampil <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_tampil" value="{{ old('tanggal_tampil', $penampil->tanggal_tampil ?? '') }}" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jam Mulai <span class="text-red-500">*</span></label>
                            <input type="time" name="jam_mulai" value="{{ old('jam_mulai', isset($penampil) ? substr($penampil->jam_mulai, 0, 5) : '') }}" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jam Berakhir <span class="text-red-500">*</span></label>
                            <input type="time" name="jam_selesai" value="{{ old('jam_selesai', isset($penampil) ? substr($penampil->jam_selesai, 0, 5) : '') }}" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary transition-all">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Lokasi (Panggung/Ruangan) <span class="text-red-500">*</span></label>
                            <input type="text" name="lokasi_tampil" value="{{ old('lokasi_tampil', $penampil->lokasi_tampil ?? '') }}" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary transition-all" placeholder="Cth: Main Stage Lapangan Barat">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Sosial Media (Opsional)</label>
                            <input type="text" name="medsos_penampil" value="{{ old('medsos_penampil', $penampil->medsos_penampil ?? '') }}" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary transition-all" placeholder="@username_ig">
                        </div>
                    </div>

                    <!-- Quill Rich Text Editor untuk Deskripsi -->
                    <div class="flex flex-col flex-grow">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Profil / Acara <span class="text-red-500">*</span></label>
                        <input type="hidden" name="deskripsi_penampil" id="input-deskripsi">
                        
                        <div class="flex-grow flex flex-col border border-slate-200 rounded-xl overflow-hidden focus-within:border-kmdgi-primary focus-within:ring-1 focus-within:ring-kmdgi-primary/30 transition-all bg-white min-h-[300px]">
                            <div id="editor-toolbar" class="bg-slate-50/80 border-b border-slate-200 py-2">
                                <span class="ql-formats">
                                    <button class="ql-bold"></button>
                                    <button class="ql-italic"></button>
                                    <button class="ql-underline"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-list" value="ordered"></button>
                                    <button class="ql-list" value="bullet"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-link"></button>
                                    <button class="ql-clean"></button>
                                </span>
                            </div>
                            <div id="quill-editor" class="flex-grow h-full text-[15px] text-slate-700 p-2">{!! old('deskripsi_penampil', $penampil->deskripsi_penampil ?? '') !!}</div>
                        </div>
                    </div>

                    <!-- Akses & Tiket -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Akses Penonton <span class="text-red-500">*</span></label>
                            <select name="kategori_penonton" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary cursor-pointer">
                                <option value="Semua" {{ old('kategori_penonton', $penampil->kategori_penonton ?? '') == 'Semua' ? 'selected' : '' }}>Semua Kalangan</option>
                                <option value="Delegasi" {{ old('kategori_penonton', $penampil->kategori_penonton ?? '') == 'Delegasi' ? 'selected' : '' }}>Khusus Delegasi</option>
                                <option value="Umum" {{ old('kategori_penonton', $penampil->kategori_penonton ?? '') == 'Umum' ? 'selected' : '' }}>Khusus Umum</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jenis Tiket <span class="text-red-500">*</span></label>
                            <select name="tipe_pendaftaran" id="input-tipe-tiket" required onchange="toggleTiket()" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary cursor-pointer">
                                <option value="Gratis" {{ old('tipe_pendaftaran', $penampil->tipe_pendaftaran ?? '') == 'Gratis' ? 'selected' : '' }}>Gratis (Free)</option>
                                <option value="Berbayar" {{ old('tipe_pendaftaran', $penampil->tipe_pendaftaran ?? '') == 'Berbayar' ? 'selected' : '' }}>Berbayar</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 hidden" id="tiket-details">
                            <div class="flex gap-4">
                                <div class="w-1/3">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                                    <input type="number" name="harga_tiket" id="input-harga" value="{{ old('harga_tiket', $penampil->harga_tiket ?? '') }}" class="w-full px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl text-sm focus:outline-none focus:border-amber-500 transition-all" placeholder="50000">
                                </div>
                                <div class="w-2/3">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Link Pembelian / RSVP</label>
                                    <input type="url" name="link_pendaftaran" id="input-link" value="{{ old('link_pendaftaran', $penampil->link_pendaftaran ?? '') }}" class="w-full px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl text-sm focus:outline-none focus:border-amber-500 transition-all" placeholder="https://loket.com/...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Tampilan Aplikasi <span class="text-red-500">*</span></label>
                        <select name="is_active" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary bg-white transition-all cursor-pointer">
                            <option value="1" {{ old('is_active', $penampil->is_active ?? 1) == 1 ? 'selected' : '' }}>Publikasikan ke Website</option>
                            <option value="0" {{ old('is_active', $penampil->is_active ?? 1) == 0 ? 'selected' : '' }}>Simpan sebagai Draft</option>
                        </select>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <a href="{{ route('admin.penampil.index') }}" class="inline-flex justify-center bg-white border border-slate-200 text-slate-600 font-bold py-3.5 px-8 rounded-xl hover:bg-slate-50 transition-colors text-sm shadow-sm">Batalkan</a>
                        <button type="submit" class="inline-flex justify-center bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-3.5 px-8 rounded-xl transition-colors text-sm shadow-md shadow-kmdgi-primary/20">
                            {{ isset($penampil) ? 'Simpan Perubahan' : 'Simpan Penampil Baru' }}
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
    // Universal Image Previewer
    function previewImage(input, previewId, placeholderId, btnId) {
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById(placeholderId);
        const removeBtn = document.getElementById(btnId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('opacity-0');
                removeBtn.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage(inputId, previewId, placeholderId, btnId) {
        document.getElementById(inputId).value = ""; 
        document.getElementById(previewId).classList.add('hidden');
        document.getElementById(placeholderId).classList.remove('opacity-0');
        document.getElementById(btnId).classList.add('hidden');
    }

    // Toggle Form Tiket Berbayar vs Gratis
    function toggleTiket() {
        const tipe = document.getElementById('input-tipe-tiket').value;
        const detailDiv = document.getElementById('tiket-details');
        const inputHarga = document.getElementById('input-harga');

        if(tipe === 'Berbayar') {
            detailDiv.classList.remove('hidden');
            inputHarga.required = true;
        } else {
            detailDiv.classList.add('hidden');
            inputHarga.required = false;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Init Quill
        const quill = new Quill('#quill-editor', {
            modules: { toolbar: '#editor-toolbar' },
            placeholder: 'Tuliskan deksripsi detail mengenai pengisi acara ini...',
            theme: 'snow'
        });

        // Trigger on load for Tiket UI
        toggleTiket();

        // Submit Logic (Sync Quill to Hidden Input)
        document.getElementById('penampil-form').addEventListener('submit', function(e) {
            let htmlContent = quill.root.innerHTML;
            if (htmlContent === '<p><br></p>') htmlContent = '';
            
            document.getElementById('input-deskripsi').value = htmlContent;
            
            if(htmlContent.trim() === '') {
                e.preventDefault();
                alert("Peringatan: Deskripsi Penampil tidak boleh kosong!");
                hideGlobalLoading(); 
            }
        });
    });
</script>

<style>
    .ql-container { font-family: inherit !important; font-size: inherit; }
    .ql-editor { padding: 1.5rem; }
    .ql-toolbar.ql-snow { border: none !important; }
    .ql-container.ql-snow { border: none !important; }
    .ql-editor p { margin-bottom: 0.75rem; line-height: 1.6; }
    .ql-editor ol, .ql-editor ul { padding-left: 1.25rem; margin-bottom: 1rem; line-height: 1.6;}
</style>
@endsection