@extends('layouts.app')

@section('title', (isset($syarat) ? 'Edit' : 'Tambah') . ' Syarat & Ketentuan - KMDGI 16')

@section('content')
<!-- Memanggil CSS Quill.js -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">
            
            <!-- Tombol Kembali -->
            <a href="{{ route('admin.syarat.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-kmdgi-primary transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Syarat
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
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ isset($syarat) ? 'Edit Dokumen Syarat & Ketentuan' : 'Buat Dokumen Syarat & Ketentuan' }}</h2>
                    <p class="text-sm text-slate-500 mt-1">Gunakan editor teks di bawah ini untuk membuat atau mengubah poin-point persyaratan.</p>
                </div>

                <form id="syarat-form" action="{{ isset($syarat) ? route('admin.syarat.update', $syarat->id) : route('admin.syarat.store') }}" method="POST" class="space-y-6">
                    @csrf
                    @if(isset($syarat))
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Dokumen <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" value="{{ old('judul', $syarat->judul ?? '') }}" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all" placeholder="Cth: Ketentuan Peserta Pameran">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Publikasi <span class="text-red-500">*</span></label>
                            <select name="is_active" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all cursor-pointer">
                                <option value="1" {{ old('is_active', $syarat->is_active ?? 1) == 1 ? 'selected' : '' }}>Aktif (Tampilkan)</option>
                                <option value="0" {{ old('is_active', $syarat->is_active ?? 1) == 0 ? 'selected' : '' }}>Draft (Sembunyikan)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Area Rich Text Editor Quill.js -->
                    <div class="flex flex-col flex-grow">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Isi Konten Dokumen <span class="text-red-500">*</span></label>
                        <input type="hidden" name="konten" id="input-konten">
                        
                        <div class="flex-grow flex flex-col border border-slate-200 rounded-xl overflow-hidden focus-within:border-kmdgi-primary focus-within:ring-1 focus-within:ring-kmdgi-primary/30 transition-all bg-white min-h-[400px]">
                            <!-- Toolbar -->
                            <div id="editor-toolbar" class="bg-slate-50/80 border-b border-slate-200 py-2">
                                <span class="ql-formats">
                                    <select class="ql-header">
                                        <option value="2">Heading 1</option>
                                        <option value="3">Heading 2</option>
                                        <option selected>Normal Text</option>
                                    </select>
                                </span>
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
                            <!-- Editor Content Area -->
                            <div id="quill-editor" class="flex-grow h-full text-[15px] text-slate-700 p-2">{!! old('konten', $syarat->konten ?? '') !!}</div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <a href="{{ route('admin.syarat.index') }}" class="inline-flex justify-center bg-white border border-slate-200 text-slate-600 font-bold py-3.5 px-8 rounded-xl hover:bg-slate-50 transition-colors text-sm shadow-sm">Batalkan</a>
                        <button type="submit" class="inline-flex justify-center bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-3.5 px-8 rounded-xl transition-colors text-sm shadow-md shadow-kmdgi-primary/20">
                            {{ isset($syarat) ? 'Simpan Perubahan' : 'Buat Dokumen Sekarang' }}
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>

    @include('partials.footer')
</div>

<!-- Memanggil JS Quill -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quill = new Quill('#quill-editor', {
            modules: { toolbar: '#editor-toolbar' },
            placeholder: 'Ketikkan rincian persyaratan di sini...',
            theme: 'snow'
        });

        document.getElementById('syarat-form').addEventListener('submit', function(e) {
            let htmlContent = quill.root.innerHTML;
            if (htmlContent === '<p><br></p>') htmlContent = '';
            
            document.getElementById('input-konten').value = htmlContent;
            
            if(htmlContent.trim() === '') {
                e.preventDefault();
                alert("Peringatan: Isi Konten Dokumen tidak boleh kosong!");
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
    .ql-editor h2 { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.75rem; color: #0f172a;}
    .ql-editor h3 { font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; color: #1e293b;}
    .ql-editor p { margin-bottom: 0.75rem; line-height: 1.6; }
    .ql-editor ol, .ql-editor ul { padding-left: 1.25rem; margin-bottom: 1rem; line-height: 1.6;}
    .ql-editor li { margin-bottom: 0.25rem; }
</style>
@endsection