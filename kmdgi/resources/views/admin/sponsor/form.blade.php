@extends('layouts.app')

@section('title', (isset($sponsor) ? 'Edit' : 'Tambah') . ' Data Mitra - KMDGI 16')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">
            
            <a href="{{ route('admin.sponsor.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-kmdgi-primary transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Mitra
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
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ isset($sponsor) ? 'Edit Profil Mitra' : 'Tambah Mitra Baru' }}</h2>
                    <p class="text-sm text-slate-500 mt-1">Lengkapi informasi logo, kategori kelas, dan tautan dari perusahaan / institusi mitra.</p>
                </div>

                <form id="sponsor-form" action="{{ isset($sponsor) ? route('admin.sponsor.update', $sponsor->id) : route('admin.sponsor.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @if(isset($sponsor))
                        @method('PUT')
                    @endif

                    <div class="flex flex-col sm:flex-row gap-8 items-start">
                        <!-- Logo Upload Box -->
                        <div class="w-full sm:w-1/3 md:w-1/4">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Logo Kemitraan <span class="text-red-500">*</span></label>
                            <div class="relative w-full aspect-square border-2 border-dashed border-slate-300 hover:border-kmdgi-primary rounded-2xl bg-slate-50 flex items-center justify-center p-4 group cursor-pointer transition-colors" onclick="document.getElementById('input-logo').click()">
                                <div id="logo-placeholder" class="flex flex-col items-center text-center pointer-events-none relative z-10 transition-opacity {{ (isset($sponsor) && $sponsor->logo) ? 'opacity-0' : '' }}">
                                    <svg class="w-8 h-8 text-slate-400 mb-2 group-hover:text-kmdgi-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /></svg>
                                    <span class="text-[10px] font-bold text-slate-500">Unggah file (PNG transparan)</span>
                                </div>
                                <img id="logo-preview" src="{{ (isset($sponsor) && $sponsor->logo) ? asset('storage/' . $sponsor->logo) : '' }}" class="absolute inset-4 w-[calc(100%-2rem)] h-[calc(100%-2rem)] object-contain z-20 {{ (isset($sponsor) && $sponsor->logo) ? '' : 'hidden' }}" />
                            </div>
                            <input type="file" name="logo" id="input-logo" accept="image/*" class="hidden" onchange="previewImage(this)" {{ !isset($sponsor) ? 'required' : '' }}>
                            
                            <div class="mt-2 text-center">
                                <button type="button" id="btn-remove-logo" class="{{ (isset($sponsor) && $sponsor->logo) ? '' : 'hidden' }} text-[11px] font-bold text-red-500 hover:text-red-600 bg-red-50 px-3 py-1.5 rounded-lg" onclick="removeImage()">Hapus Gambar Logo</button>
                            </div>
                        </div>

                        <!-- Data Utama Form -->
                        <div class="w-full sm:w-2/3 md:w-3/4 space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Perusahaan / Instansi <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_mitra" value="{{ old('nama_mitra', $sponsor->nama_mitra ?? '') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all" placeholder="Cth: PT. Telkom Indonesia">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kategori Mitra <span class="text-red-500">*</span></label>
                                    <input type="text" list="kategori-list" name="kategori" value="{{ old('kategori', $sponsor->kategori ?? '') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all" placeholder="Pilih / Ketik baru">
                                    <datalist id="kategori-list">
                                        <option value="Sponsor"></option>
                                        <option value="Media Partner"></option>
                                        <option value="Community Partner"></option>
                                        <option value="Government"></option>
                                    </datalist>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Klasifikasi Kelas Logo <span class="text-red-500">*</span></label>
                                    <select name="tier_kelas" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary cursor-pointer">
                                        <option value="Utama (Besar)" {{ old('tier_kelas', $sponsor->tier_kelas ?? '') == 'Utama (Besar)' ? 'selected' : '' }}>🥇 Utama (Logo Besar)</option>
                                        <option value="Madya (Sedang)" {{ old('tier_kelas', $sponsor->tier_kelas ?? '') == 'Madya (Sedang)' ? 'selected' : '' }}>🥈 Madya (Logo Sedang)</option>
                                        <option value="Pratama (Kecil)" {{ old('tier_kelas', $sponsor->tier_kelas ?? '') == 'Pratama (Kecil)' ? 'selected' : '' }}>🥉 Pratama (Logo Kecil)</option>
                                    </select>
                                    <p class="text-[10px] text-slate-400 mt-1">Menentukan dimensi logo di halaman Landing Page.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tautan Website / IG (Opsional)</label>
                                    <input type="url" name="link_tautan" value="{{ old('link_tautan', $sponsor->link_tautan ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all" placeholder="https://...">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status Tampilan <span class="text-red-500">*</span></label>
                                    <select name="is_active" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary cursor-pointer">
                                        <option value="1" {{ old('is_active', $sponsor->is_active ?? 1) == 1 ? 'selected' : '' }}>Tampilkan di Website</option>
                                        <option value="0" {{ old('is_active', $sponsor->is_active ?? 1) == 0 ? 'selected' : '' }}>Sembunyikan</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="flex flex-col flex-grow">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Perusahaan (Opsional)</label>
                        <input type="hidden" name="deskripsi" id="input-deskripsi">
                        
                        <div class="flex-grow flex flex-col border border-slate-200 rounded-xl overflow-hidden focus-within:border-kmdgi-primary focus-within:ring-1 focus-within:ring-kmdgi-primary/30 transition-all bg-white min-h-[250px]">
                            <div id="editor-toolbar" class="bg-slate-50/80 border-b border-slate-200 py-2">
                                <span class="ql-formats"><button class="ql-bold"></button><button class="ql-italic"></button></span>
                                <span class="ql-formats"><button class="ql-list" value="ordered"></button><button class="ql-list" value="bullet"></button></span>
                                <span class="ql-formats"><button class="ql-link"></button><button class="ql-clean"></button></span>
                            </div>
                            <div id="quill-editor" class="flex-grow h-full text-[15px] text-slate-700 p-2">{!! old('deskripsi', $sponsor->deskripsi ?? '') !!}</div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <a href="{{ route('admin.sponsor.index') }}" class="inline-flex justify-center bg-white border border-slate-200 text-slate-600 font-bold py-3.5 px-8 rounded-xl hover:bg-slate-50 transition-colors text-sm shadow-sm">Batalkan</a>
                        <button type="submit" class="inline-flex justify-center bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-3.5 px-8 rounded-xl transition-colors text-sm shadow-md shadow-kmdgi-primary/20">
                            {{ isset($sponsor) ? 'Simpan Perubahan' : 'Tambahkan Kemitraan' }}
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
        const preview = document.getElementById('logo-preview');
        const placeholder = document.getElementById('logo-placeholder');
        const removeBtn = document.getElementById('btn-remove-logo');

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
        document.getElementById('input-logo').value = ""; 
        document.getElementById('logo-preview').classList.add('hidden');
        document.getElementById('logo-placeholder').classList.remove('opacity-0');
        document.getElementById('btn-remove-logo').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const quill = new Quill('#quill-editor', {
            modules: { toolbar: '#editor-toolbar' },
            placeholder: 'Tuliskan deskripsi singkat mengenai sponsor/mitra ini (opsional)...',
            theme: 'snow'
        });

        document.getElementById('sponsor-form').addEventListener('submit', function(e) {
            let htmlContent = quill.root.innerHTML;
            if (htmlContent === '<p><br></p>') htmlContent = '';
            document.getElementById('input-deskripsi').value = htmlContent;
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