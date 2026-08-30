@extends('layouts.app')

@section('title', 'Panduan Delegasi - KMDGI 16')

@section('content')
<!-- Memanggil CSS Quill.js -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">

            <!-- Flash Message -->
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
                    <span class="text-sm font-bold">Gagal Menyimpan Panduan!</span>
                </div>
                <ul class="text-xs list-disc list-inside pl-9 mt-1 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Header Halaman & Info Update -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Buku Panduan Delegasi</h2>
                    <p class="text-sm text-slate-500 mt-1">Kelola Petunjuk Teknis dan Petunjuk Pameran dalam satu halaman ini.</p>
                </div>
                <div class="flex items-center gap-3 bg-slate-50 px-4 py-3 rounded-xl border border-slate-200">
                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Terakhir Diperbarui</p>
                        <p class="text-xs font-semibold text-slate-700">
                            {{ \Carbon\Carbon::parse($panduan->updated_at)->translatedFormat('l, d F Y - H:i') }} WIB
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Edit Konten (Tanpa Tabel) -->
            <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <form id="panduan-form" action="{{ route('admin.panduan.update') }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Area: Petunjuk Teknis -->
                    <div class="flex flex-col flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="flex w-6 h-6 bg-blue-100 text-kmdgi-primary items-center justify-center rounded-full text-xs font-bold">1</span>
                            <label class="block text-sm font-bold text-slate-800 uppercase tracking-wider">Petunjuk Teknis (Juknis)</label>
                        </div>
                        <input type="hidden" name="petunjuk_teknis" id="input-teknis">
                        
                        <div class="flex-grow flex flex-col border border-slate-200 rounded-xl overflow-hidden focus-within:border-kmdgi-primary focus-within:ring-1 focus-within:ring-kmdgi-primary/30 transition-all bg-white min-h-[350px]">
                            <div id="toolbar-teknis" class="bg-slate-50/80 border-b border-slate-200 py-2">
                                <span class="ql-formats">
                                    <select class="ql-header"><option value="2">Heading 1</option><option value="3">Heading 2</option><option selected>Normal Text</option></select>
                                </span>
                                <span class="ql-formats"><button class="ql-bold"></button><button class="ql-italic"></button><button class="ql-underline"></button></span>
                                <span class="ql-formats"><button class="ql-list" value="ordered"></button><button class="ql-list" value="bullet"></button></span>
                                <span class="ql-formats"><button class="ql-link"></button><button class="ql-clean"></button></span>
                            </div>
                            <div id="editor-teknis" class="flex-grow h-full text-[15px] text-slate-700 p-2">{!! old('petunjuk_teknis', $panduan->petunjuk_teknis ?? '') !!}</div>
                        </div>
                    </div>

                    <!-- Area: Petunjuk Pameran -->
                    <div class="flex flex-col flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="flex w-6 h-6 bg-emerald-100 text-emerald-600 items-center justify-center rounded-full text-xs font-bold">2</span>
                            <label class="block text-sm font-bold text-slate-800 uppercase tracking-wider">Petunjuk Pameran (Jukran)</label>
                        </div>
                        <input type="hidden" name="petunjuk_pameran" id="input-pameran">
                        
                        <div class="flex-grow flex flex-col border border-slate-200 rounded-xl overflow-hidden focus-within:border-emerald-500 focus-within:ring-1 focus-within:ring-emerald-500/30 transition-all bg-white min-h-[350px]">
                            <div id="toolbar-pameran" class="bg-slate-50/80 border-b border-slate-200 py-2">
                                <span class="ql-formats">
                                    <select class="ql-header"><option value="2">Heading 1</option><option value="3">Heading 2</option><option selected>Normal Text</option></select>
                                </span>
                                <span class="ql-formats"><button class="ql-bold"></button><button class="ql-italic"></button><button class="ql-underline"></button></span>
                                <span class="ql-formats"><button class="ql-list" value="ordered"></button><button class="ql-list" value="bullet"></button></span>
                                <span class="ql-formats"><button class="ql-link"></button><button class="ql-clean"></button></span>
                            </div>
                            <div id="editor-pameran" class="flex-grow h-full text-[15px] text-slate-700 p-2">{!! old('petunjuk_pameran', $panduan->petunjuk_pameran ?? '') !!}</div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-6 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <button type="submit" class="inline-flex justify-center bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-4 px-10 rounded-xl transition-colors text-sm shadow-md shadow-kmdgi-primary/20">
                            Simpan Perubahan Dokumen
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
        // Inisialisasi Editor 1 (Teknis)
        const quillTeknis = new Quill('#editor-teknis', {
            modules: { toolbar: '#toolbar-teknis' },
            placeholder: 'Ketikkan rincian Petunjuk Teknis di sini...',
            theme: 'snow'
        });

        // Inisialisasi Editor 2 (Pameran)
        const quillPameran = new Quill('#editor-pameran', {
            modules: { toolbar: '#toolbar-pameran' },
            placeholder: 'Ketikkan rincian Petunjuk Pameran di sini...',
            theme: 'snow'
        });

        document.getElementById('panduan-form').addEventListener('submit', function(e) {
            // Ambil dan filter konten Teknis
            let htmlTeknis = quillTeknis.root.innerHTML;
            if (htmlTeknis === '<p><br></p>') htmlTeknis = '';
            document.getElementById('input-teknis').value = htmlTeknis;
            
            // Ambil dan filter konten Pameran
            let htmlPameran = quillPameran.root.innerHTML;
            if (htmlPameran === '<p><br></p>') htmlPameran = '';
            document.getElementById('input-pameran').value = htmlPameran;
            
            // Validasi Kosong
            if(htmlTeknis.trim() === '' || htmlPameran.trim() === '') {
                e.preventDefault();
                alert("Peringatan: Isi Petunjuk Teknis dan Petunjuk Pameran tidak boleh ada yang kosong!");
                hideGlobalLoading(); 
            }
        });
    });
</script>

<style>
    /* Styling Editor agar match dengan Font KMDGI (Bypass bawaan Quill) */
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