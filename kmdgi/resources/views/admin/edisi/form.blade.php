@extends('layouts.app')

@section('title', (isset($edisi) ? 'Edit' : 'Tambah') . ' Edisi KMDGI')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">
            
            <a href="{{ route('admin.edisi.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-kmdgi-primary transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Kembali ke Manajemen Edisi
            </a>

            <form id="edisi-form" action="{{ isset($edisi) ? route('admin.edisi.update', $edisi->id) : route('admin.edisi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($edisi))
                    @method('PUT')
                @endif

                <!-- 1. IDENTITAS INDUK -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 border-b border-slate-100 pb-3">1. Induk Edisi Acara</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama / Seri KMDGI <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_edisi" value="{{ old('nama_edisi', $edisi->nama_edisi ?? '') }}" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-xl text-lg font-bold focus:outline-none focus:border-kmdgi-primary transition-all" placeholder="Cth: KMDGI 16">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Tampilan Website <span class="text-red-500">*</span></label>
                            <select name="is_active" required class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:border-kmdgi-primary cursor-pointer text-kmdgi-primary">
                                <option value="1" {{ old('is_active', $edisi->is_active ?? 1) == 1 ? 'selected' : '' }}>Aktifkan di Landing Page</option>
                                <option value="0" {{ old('is_active', $edisi->is_active ?? 1) == 0 ? 'selected' : '' }}>Simpan Sebagai Draft</option>
                            </select>
                            <p class="text-[10px] text-slate-400 mt-2">Hanya boleh ada 1 edisi yang aktif di publik.</p>
                        </div>
                    </div>
                </div>

                <!-- 2. LATAR BELAKANG -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 border-b border-slate-100 pb-3">2. Latar Belakang Pelaksanaan</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Latar Belakang</label>
                            <input type="text" name="lb_title" value="{{ old('lb_title', $edisi->lb_title ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary transition-all" placeholder="Cth: Mengapa Kami Mengangkat Tema Ini?">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi / Story Latar Belakang</label>
                            <input type="hidden" name="lb_deskripsi" id="input-lb-desc">
                            <div class="flex flex-col border border-slate-200 rounded-xl focus-within:border-kmdgi-primary transition-all bg-white min-h-[200px]">
                                <div id="toolbar-lb" class="bg-slate-50 border-b border-slate-200 py-1.5"><span class="ql-formats"><button class="ql-bold"></button><button class="ql-italic"></button></span><span class="ql-formats"><button class="ql-list" value="ordered"></button><button class="ql-list" value="bullet"></button></span></div>
                                <div id="editor-lb" class="flex-grow text-[14px] text-slate-700 p-2">{!! old('lb_deskripsi', $edisi->lb_deskripsi ?? '') !!}</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Foto / Ilustrasi Latar Belakang (Opsional)</label>
                            <div class="relative w-full md:w-1/2 aspect-video border-2 border-dashed border-slate-300 hover:border-kmdgi-primary rounded-xl bg-slate-50 flex items-center justify-center p-2 group cursor-pointer overflow-hidden" onclick="document.getElementById('input-lb-img').click()">
                                <div id="ph-lb" class="text-center transition-opacity {{ (isset($edisi) && $edisi->lb_image) ? 'opacity-0' : '' }}">
                                    <span class="text-xs font-bold text-slate-400">Pilih Gambar</span>
                                </div>
                                <img id="pr-lb" src="{{ (isset($edisi) && $edisi->lb_image) ? asset('storage/' . $edisi->lb_image) : '' }}" class="absolute inset-0 w-full h-full object-cover {{ (isset($edisi) && $edisi->lb_image) ? '' : 'hidden' }}" />
                            </div>
                            <input type="file" name="lb_image" id="input-lb-img" accept="image/*" class="hidden" onchange="previewDyn(this, 'lb')">
                            <input type="hidden" name="remove_lb_image" id="rm-lb" value="0">
                            <button type="button" id="btn-lb" class="mt-2 text-[10px] font-bold text-red-500 hover:underline {{ (isset($edisi) && $edisi->lb_image) ? '' : 'hidden' }}" onclick="removeDyn('lb')">Hapus Gambar Latar Belakang</button>
                        </div>
                    </div>
                </div>

                <!-- 3. TEMA KMDGI -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 border-b border-slate-100 pb-3">3. Konsep Tema KMDGI</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Tema</label>
                            <input type="text" name="tema_title" value="{{ old('tema_title', $edisi->tema_title ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary transition-all" placeholder="Cth: SIMPUL">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi / Filosofi Tema</label>
                            <input type="hidden" name="tema_deskripsi" id="input-tema-desc">
                            <div class="flex flex-col border border-slate-200 rounded-xl focus-within:border-kmdgi-primary transition-all bg-white min-h-[200px]">
                                <div id="toolbar-tema" class="bg-slate-50 border-b border-slate-200 py-1.5"><span class="ql-formats"><button class="ql-bold"></button><button class="ql-italic"></button></span><span class="ql-formats"><button class="ql-list" value="ordered"></button><button class="ql-list" value="bullet"></button></span></div>
                                <div id="editor-tema" class="flex-grow text-[14px] text-slate-700 p-2">{!! old('tema_deskripsi', $edisi->tema_deskripsi ?? '') !!}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Logo Tema -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Logo Tema (PNG Transparan)</label>
                                <div class="relative w-full aspect-square md:aspect-video border-2 border-dashed border-slate-300 hover:border-kmdgi-primary rounded-xl bg-slate-50 flex items-center justify-center p-4 group cursor-pointer overflow-hidden" onclick="document.getElementById('input-tema-logo').click()">
                                    <div id="ph-logo" class="text-center transition-opacity {{ (isset($edisi) && $edisi->tema_logo) ? 'opacity-0' : '' }}">
                                        <span class="text-xs font-bold text-slate-400">Pilih Logo Tema</span>
                                    </div>
                                    <img id="pr-logo" src="{{ (isset($edisi) && $edisi->tema_logo) ? asset('storage/' . $edisi->tema_logo) : '' }}" class="absolute inset-2 w-[calc(100%-1rem)] h-[calc(100%-1rem)] object-contain {{ (isset($edisi) && $edisi->tema_logo) ? '' : 'hidden' }}" />
                                </div>
                                <input type="file" name="tema_logo" id="input-tema-logo" accept="image/*" class="hidden" onchange="previewDyn(this, 'logo')">
                                <input type="hidden" name="remove_tema_logo" id="rm-logo" value="0">
                                <button type="button" id="btn-logo" class="mt-2 text-[10px] font-bold text-red-500 hover:underline {{ (isset($edisi) && $edisi->tema_logo) ? '' : 'hidden' }}" onclick="removeDyn('logo')">Hapus Logo Tema</button>
                            </div>

                            <!-- Gambar Ilustrasi Tema -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Gambar Grafis Tema Pendukung</label>
                                <div class="relative w-full aspect-video border-2 border-dashed border-slate-300 hover:border-kmdgi-primary rounded-xl bg-slate-50 flex items-center justify-center p-2 group cursor-pointer overflow-hidden" onclick="document.getElementById('input-tema-img').click()">
                                    <div id="ph-img" class="text-center transition-opacity {{ (isset($edisi) && $edisi->tema_image) ? 'opacity-0' : '' }}">
                                        <span class="text-xs font-bold text-slate-400">Pilih Gambar Tema</span>
                                    </div>
                                    <img id="pr-img" src="{{ (isset($edisi) && $edisi->tema_image) ? asset('storage/' . $edisi->tema_image) : '' }}" class="absolute inset-0 w-full h-full object-cover {{ (isset($edisi) && $edisi->tema_image) ? '' : 'hidden' }}" />
                                </div>
                                <input type="file" name="tema_image" id="input-tema-img" accept="image/*" class="hidden" onchange="previewDyn(this, 'img')">
                                <input type="hidden" name="remove_tema_image" id="rm-img" value="0">
                                <button type="button" id="btn-img" class="mt-2 text-[10px] font-bold text-red-500 hover:underline {{ (isset($edisi) && $edisi->tema_image) ? '' : 'hidden' }}" onclick="removeDyn('img')">Hapus Gambar Tema</button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="inline-flex justify-center bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-4 px-12 rounded-xl transition-colors text-sm shadow-md shadow-kmdgi-primary/20">
                        {{ isset($edisi) ? 'Simpan Perubahan Edisi' : 'Ciptakan Edisi Baru' }}
                    </button>
                </div>
            </form>

        </main>
    </div>
    @include('partials.footer')
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    // Inisialisasi 2 Editor Sekaligus
    const quillLb = new Quill('#editor-lb', { modules: { toolbar: '#toolbar-lb' }, theme: 'snow' });
    const quillTema = new Quill('#editor-tema', { modules: { toolbar: '#toolbar-tema' }, theme: 'snow' });

    // Sync Data sebelum disubmit
    document.getElementById('edisi-form').addEventListener('submit', function() {
        let lbHtml = quillLb.root.innerHTML;
        let temaHtml = quillTema.root.innerHTML;
        
        document.getElementById('input-lb-desc').value = lbHtml === '<p><br></p>' ? '' : lbHtml;
        document.getElementById('input-tema-desc').value = temaHtml === '<p><br></p>' ? '' : temaHtml;
    });

    // Universal Dynamic Image Uploader (Satu fungsi untuk 3 Kotak)
    function previewDyn(input, type) {
        const pr = document.getElementById('pr-' + type);
        const ph = document.getElementById('ph-' + type);
        const btn = document.getElementById('btn-' + type);
        const rm = document.getElementById('rm-' + type);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                pr.src = e.target.result;
                pr.classList.remove('hidden');
                ph.classList.add('opacity-0');
                btn.classList.remove('hidden');
                rm.value = '0';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeDyn(type) {
        document.getElementById('input-' + (type === 'lb' ? 'lb-img' : (type==='logo' ? 'tema-logo' : 'tema-img'))).value = ""; 
        document.getElementById('pr-' + type).classList.add('hidden');
        document.getElementById('ph-' + type).classList.remove('opacity-0');
        document.getElementById('btn-' + type).classList.add('hidden');
        document.getElementById('rm-' + type).value = '1';
    }
</script>

<style>
    .ql-container { font-family: inherit !important; font-size: inherit; }
    .ql-editor { padding: 1rem; }
    .ql-toolbar.ql-snow { border: none !important; }
    .ql-container.ql-snow { border: none !important; }
</style>
@endsection