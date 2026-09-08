@extends('layouts.app')
@section('title', 'Kelola Panduan Delegasi - Admin KMDGI')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<div class="bg-slate-50 min-h-screen flex flex-col">
    @include('partials.navbar')
    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">
        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">
            
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <span class="text-sm font-semibold">{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">✖</button>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-between gap-4 bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Regulasi Panduan Delegasi</h2>
                    <p class="text-sm text-slate-500 mt-1">Sesuaikan teks tampilan dan petunjuk teknis di halaman publik.</p>
                </div>
            </div>

            <form id="panduan-form" action="{{ route('admin.panduan.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- SECTION 1: HEADER HERO -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm space-y-4">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">1. Teks Judul Utama (Hero Section)</h3>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Judul Utama</label>
                        <input type="text" name="hero_title" value="{{ old('hero_title', $panduan->hero_title) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Sub-judul / Deskripsi Pendek</label>
                        <textarea name="hero_subtitle" rows="2" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary">{{ old('hero_subtitle', $panduan->hero_subtitle) }}</textarea>
                    </div>
                </div>

                <!-- SECTION 2: 4 POIN ALUR DELEGASI -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">2. Poin Utama Regulasi Tim</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Judul Bagian</label>
                            <input type="text" name="alur_title" value="{{ old('alur_title', $panduan->alur_title) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Deskripsi Bagian</label>
                            <textarea name="alur_deskripsi" rows="2" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary">{{ old('alur_deskripsi', $panduan->alur_deskripsi) }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                        @for($i = 1; $i <= 4; $i++)
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                            <span class="inline-block px-2 py-1 bg-white text-slate-700 text-xs font-bold rounded shadow-sm">Poin {{ $i }}</span>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Judul Poin</label>
                                <input type="text" name="alur_{{ $i }}_title" value="{{ old('alur_'.$i.'_title', $panduan->{'alur_'.$i.'_title'}) }}" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-kmdgi-primary">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Deskripsi Poin</label>
                                <textarea name="alur_{{ $i }}_desc" rows="3" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-kmdgi-primary">{{ old('alur_'.$i.'_desc', $panduan->{'alur_'.$i.'_desc'}) }}</textarea>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

                <!-- SECTION 3: AKSES AKUN -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm space-y-4">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">3. Pesan Akses Akun (Login)</h3>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Judul Panel</label>
                        <input type="text" name="akun_title" value="{{ old('akun_title', $panduan->akun_title) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Deskripsi Ajakan</label>
                        <textarea name="akun_desc" rows="2" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary">{{ old('akun_desc', $panduan->akun_desc) }}</textarea>
                    </div>
                </div>

                <!-- SECTION 4: KETENTUAN UMUM DELEGASI (QUILL EDITOR) -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm space-y-4">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">4. Ketentuan Umum Tambahan (Teks Detail Lengkap)</h3>
                    
                    <input type="hidden" name="petunjuk_teknis" id="input-teknis">
                    <div class="flex-grow flex flex-col border border-slate-200 rounded-xl overflow-hidden focus-within:border-kmdgi-primary focus-within:ring-1 transition-all bg-white min-h-[350px]">
                        <div id="toolbar-teknis" class="bg-slate-50/80 border-b border-slate-200 py-2">
                            <span class="ql-formats"><select class="ql-header"><option value="2">Heading 1</option><option value="3">Heading 2</option><option selected>Normal Text</option></select></span>
                            <span class="ql-formats"><button class="ql-bold"></button><button class="ql-italic"></button><button class="ql-underline"></button></span>
                            <span class="ql-formats"><button class="ql-list" value="ordered"></button><button class="ql-list" value="bullet"></button></span>
                            <span class="ql-formats"><button class="ql-link"></button><button class="ql-clean"></button></span>
                        </div>
                        <div id="editor-teknis" class="flex-grow h-full text-[15px] text-slate-700 p-2">{!! old('petunjuk_teknis', $panduan->petunjuk_teknis ?? '') !!}</div>
                    </div>
                </div>

                <!-- SECTION 5: KETENTUAN PAMERAN (OPSIONAL) -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm space-y-4">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">5. Petunjuk Tambahan Pameran (Opsional)</h3>
                    
                    <input type="hidden" name="petunjuk_pameran" id="input-pameran">
                    <div class="flex-grow flex flex-col border border-slate-200 rounded-xl overflow-hidden focus-within:border-emerald-500 focus-within:ring-1 transition-all bg-white min-h-[300px]">
                        <div id="toolbar-pameran" class="bg-slate-50/80 border-b border-slate-200 py-2">
                            <span class="ql-formats"><select class="ql-header"><option value="2">Heading 1</option><option value="3">Heading 2</option><option selected>Normal Text</option></select></span>
                            <span class="ql-formats"><button class="ql-bold"></button><button class="ql-italic"></button><button class="ql-underline"></button></span>
                            <span class="ql-formats"><button class="ql-list" value="ordered"></button><button class="ql-list" value="bullet"></button></span>
                            <span class="ql-formats"><button class="ql-link"></button><button class="ql-clean"></button></span>
                        </div>
                        <div id="editor-pameran" class="flex-grow h-full text-[15px] text-slate-700 p-2">{!! old('petunjuk_pameran', $panduan->petunjuk_pameran ?? '') !!}</div>
                    </div>
                </div>

                <!-- TOMBOL SIMPAN -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-4 px-10 rounded-xl shadow-md text-sm">
                        Simpan Semua Perubahan
                    </button>
                </div>
            </form>
        </main>
    </div>
    @include('partials.footer')
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quillTeknis = new Quill('#editor-teknis', { modules: { toolbar: '#toolbar-teknis' }, theme: 'snow' });
        const quillPameran = new Quill('#editor-pameran', { modules: { toolbar: '#toolbar-pameran' }, theme: 'snow' });

        document.getElementById('panduan-form').addEventListener('submit', function(e) {
            let htmlTeknis = quillTeknis.root.innerHTML;
            if (htmlTeknis === '<p><br></p>') htmlTeknis = '';
            document.getElementById('input-teknis').value = htmlTeknis;
            
            let htmlPameran = quillPameran.root.innerHTML;
            if (htmlPameran === '<p><br></p>') htmlPameran = '';
            document.getElementById('input-pameran').value = htmlPameran;
            
            if(htmlTeknis.trim() === '') {
                e.preventDefault();
                alert("Ketentuan Umum Utama tidak boleh kosong!");
            }
        });
    });
</script>
<style>
    .ql-container { font-family: inherit !important; font-size: inherit; }
    .ql-editor { padding: 1.5rem; }
    .ql-toolbar.ql-snow { border: none !important; }
    .ql-container.ql-snow { border: none !important; }
</style>
@endsection