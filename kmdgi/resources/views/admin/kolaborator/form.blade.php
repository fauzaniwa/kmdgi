@extends('layouts.app')

@section('title', (isset($kolaborator) ? 'Edit' : 'Tambah') . ' Data Kolaborator - KMDGI 16')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">
            
            <a href="{{ route('admin.kolaborator.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-kmdgi-primary transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Kolaborator
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
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ isset($kolaborator) ? 'Edit Profil Kolaborator' : 'Tambah Kolaborator Baru' }}</h2>
                    <p class="text-sm text-slate-500 mt-1">Lengkapi data pribadi, profesi, peran kolaborasi, dan tautan sosial media.</p>
                </div>

                <form id="kolaborator-form" action="{{ isset($kolaborator) ? route('admin.kolaborator.update', $kolaborator->id) : route('admin.kolaborator.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @if(isset($kolaborator))
                        @method('PUT')
                    @endif

                    <div class="flex flex-col sm:flex-row gap-8 items-start">
                        <!-- Foto Upload -->
                        <div class="w-full sm:w-1/3 lg:w-1/4">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Foto Profil (1:1) <span class="text-red-500">*</span></label>
                            <div class="relative w-full aspect-square border-2 border-dashed border-slate-300 hover:border-kmdgi-primary rounded-full bg-slate-50 flex items-center justify-center p-4 group cursor-pointer transition-colors overflow-hidden" onclick="document.getElementById('input-foto').click()">
                                <div id="foto-placeholder" class="flex flex-col items-center text-center pointer-events-none relative z-10 transition-opacity {{ (isset($kolaborator) && $kolaborator->foto) ? 'opacity-0' : '' }}">
                                    <svg class="w-8 h-8 text-slate-400 mb-2 group-hover:text-kmdgi-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                                    <span class="text-[10px] font-bold text-slate-500">Pilih Foto</span>
                                </div>
                                <img id="foto-preview" src="{{ (isset($kolaborator) && $kolaborator->foto) ? asset('storage/' . $kolaborator->foto) : '' }}" class="absolute inset-0 w-full h-full object-cover z-20 {{ (isset($kolaborator) && $kolaborator->foto) ? '' : 'hidden' }}" />
                            </div>
                            <input type="file" name="foto" id="input-foto" accept="image/*" class="hidden" onchange="previewImage(this)">
                            
                            <div class="mt-3 text-center">
                                <p class="text-[10px] text-slate-400 mb-1.5">Maks 3MB. Disarankan PNG/JPG.</p>
                                <button type="button" id="btn-remove-foto" class="{{ (isset($kolaborator) && $kolaborator->foto) ? '' : 'hidden' }} text-[11px] font-bold text-red-500 hover:text-red-600 bg-red-50 px-3 py-1.5 rounded-lg" onclick="removeImage()">Hapus Foto</button>
                            </div>
                        </div>

                        <!-- Data Utama -->
                        <div class="w-full sm:w-2/3 lg:w-3/4 space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama" value="{{ old('nama', $kolaborator->nama ?? '') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all" placeholder="Cth: Eka Sofyan Rizal">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Profesi / Pekerjaan Saat Ini <span class="text-red-500">*</span></label>
                                    <input type="text" name="profesi" value="{{ old('profesi', $kolaborator->profesi ?? '') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all" placeholder="Cth: Art Director, Typographer">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Peran Kolaborasi di KMDGI <span class="text-red-500">*</span></label>
                                    <input type="text" list="peran-list" name="peran_kolaborasi" value="{{ old('peran_kolaborasi', $kolaborator->peran_kolaborasi ?? '') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all" placeholder="Pilih / Ketik baru">
                                    <datalist id="peran-list">
                                        <option value="Kurator"></option>
                                        <option value="Dewan Juri"></option>
                                        <option value="Fasilitator"></option>
                                        <option value="Penggagas"></option>
                                    </datalist>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status Tampilan <span class="text-red-500">*</span></label>
                                    <select name="is_active" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary cursor-pointer">
                                        <option value="1" {{ old('is_active', $kolaborator->is_active ?? 1) == 1 ? 'selected' : '' }}>Tampilkan di Website</option>
                                        <option value="0" {{ old('is_active', $kolaborator->is_active ?? 1) == 0 ? 'selected' : '' }}>Simpan sebagai Draft</option>
                                    </select>
                                </div>
                            </div>

                            <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 space-y-4">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200 pb-2">Sosial Media & Tautan</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 mb-1.5">Instagram</label>
                                        <input type="text" name="link_instagram" value="{{ old('link_instagram', $kolaborator->link_instagram ?? '') }}" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-pink-500 transition-all" placeholder="@username">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 mb-1.5">LinkedIn URL</label>
                                        <input type="url" name="link_linkedin" value="{{ old('link_linkedin', $kolaborator->link_linkedin ?? '') }}" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 transition-all" placeholder="https://linkedin.com/in/...">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 mb-1.5">Website / Portofolio</label>
                                        <input type="url" name="link_website" value="{{ old('link_website', $kolaborator->link_website ?? '') }}" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-slate-800 transition-all" placeholder="https://...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col flex-grow">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Detail Kolaborator / Biografi Singkat (Opsional)</label>
                        <input type="hidden" name="detail" id="input-detail">
                        
                        <div class="flex-grow flex flex-col border border-slate-200 rounded-xl overflow-hidden focus-within:border-kmdgi-primary focus-within:ring-1 focus-within:ring-kmdgi-primary/30 transition-all bg-white min-h-[250px]">
                            <div id="editor-toolbar" class="bg-slate-50/80 border-b border-slate-200 py-2">
                                <span class="ql-formats"><button class="ql-bold"></button><button class="ql-italic"></button></span>
                                <span class="ql-formats"><button class="ql-list" value="ordered"></button><button class="ql-list" value="bullet"></button></span>
                                <span class="ql-formats"><button class="ql-link"></button><button class="ql-clean"></button></span>
                            </div>
                            <div id="quill-editor" class="flex-grow h-full text-[15px] text-slate-700 p-2">{!! old('detail', $kolaborator->detail ?? '') !!}</div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <a href="{{ route('admin.kolaborator.index') }}" class="inline-flex justify-center bg-white border border-slate-200 text-slate-600 font-bold py-3.5 px-8 rounded-xl hover:bg-slate-50 transition-colors text-sm shadow-sm">Batalkan</a>
                        <button type="submit" class="inline-flex justify-center bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-3.5 px-8 rounded-xl transition-colors text-sm shadow-md shadow-kmdgi-primary/20">
                            {{ isset($kolaborator) ? 'Simpan Perubahan' : 'Tambahkan Kolaborator' }}
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
    function previewImage(input) {
        const preview = document.getElementById('foto-preview');
        const placeholder = document.getElementById('foto-placeholder');
        const removeBtn = document.getElementById('btn-remove-foto');

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

    function removeImage() {
        document.getElementById('input-foto').value = ""; 
        document.getElementById('foto-preview').classList.add('hidden');
        document.getElementById('foto-placeholder').classList.remove('opacity-0');
        document.getElementById('btn-remove-foto').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const quill = new Quill('#quill-editor', {
            modules: { toolbar: '#editor-toolbar' },
            placeholder: 'Tuliskan biografi singkat atau latar belakang tokoh...',
            theme: 'snow'
        });

        document.getElementById('kolaborator-form').addEventListener('submit', function(e) {
            let htmlContent = quill.root.innerHTML;
            if (htmlContent === '<p><br></p>') htmlContent = '';
            document.getElementById('input-detail').value = htmlContent;
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