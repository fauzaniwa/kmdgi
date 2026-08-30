@extends('layouts.app')

@section('title', (isset($sejarah) ? 'Edit' : 'Tambah') . ' Sejarah - KMDGI 16')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">
            
            <a href="{{ route('admin.sejarah.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-kmdgi-primary transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Sejarah
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

            <div class="mb-2">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ isset($sejarah) ? 'Edit Sejarah KMDGI' : 'Buat Rekam Jejak Sejarah (Maksimal 5 Sekaligus)' }}</h2>
                <p class="text-sm text-slate-500 mt-1">Lengkapi tahun pelaksanaan, nama kegiatan, narasi, dan unggah foto-foto dokumentasinya.</p>
            </div>

            <form id="sejarah-form" action="{{ isset($sejarah) ? route('admin.sejarah.update', $sejarah->id) : route('admin.sejarah.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($sejarah))
                    @method('PUT')
                @endif

                <div id="dynamic-form-container" class="space-y-8">
                    <!-- LOOPING 5 FORM (Hanya jika mode CREATE) -->
                    @php $loopLimit = isset($sejarah) ? 1 : 5; @endphp
                    
                    @for($b = 0; $b < $loopLimit; $b++)
                        <div id="sejarah-block-{{$b}}" class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-200 shadow-sm transition-all {{ $b > 0 ? 'hidden' : '' }}">
                            
                            @if(!isset($sejarah))
                                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                                    <h3 class="font-bold text-slate-800">Form Sejarah #{{ $b + 1 }}</h3>
                                    @if($b > 0)
                                        <button type="button" onclick="hideBlock({{$b}})" class="text-xs text-red-500 font-bold hover:underline">Batal / Hapus Form Ini</button>
                                    @endif
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                                <div class="md:col-span-1">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tahun / Periode <span class="text-red-500">*</span></label>
                                    <input type="text" name="{{ isset($sejarah) ? 'tahun' : "sejarah[{$b}][tahun]" }}" value="{{ isset($sejarah) ? old('tahun', $sejarah->tahun) : '' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary" placeholder="Cth: 1993" {{ $b == 0 ? 'required' : '' }}>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Event & Tuan Rumah <span class="text-red-500">*</span></label>
                                    <input type="text" name="{{ isset($sejarah) ? 'title' : "sejarah[{$b}][title]" }}" value="{{ isset($sejarah) ? old('title', $sejarah->title) : '' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary" placeholder="Cth: KMDGI 1 - Universitas Trisakti Jakarta" {{ $b == 0 ? 'required' : '' }}>
                                </div>
                            </div>

                            <div class="flex flex-col mb-6">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cerita / Deskripsi Sejarah</label>
                                <input type="hidden" name="{{ isset($sejarah) ? 'description' : "sejarah[{$b}][description]" }}" id="input-desc-{{$b}}">
                                
                                <div class="flex flex-col border border-slate-200 rounded-xl overflow-hidden focus-within:border-kmdgi-primary transition-all bg-white min-h-[150px]">
                                    <div id="toolbar-{{$b}}" class="bg-slate-50/80 border-b border-slate-200 py-1">
                                        <span class="ql-formats"><button class="ql-bold"></button><button class="ql-italic"></button></span>
                                        <span class="ql-formats"><button class="ql-list" value="ordered"></button><button class="ql-list" value="bullet"></button></span>
                                    </div>
                                    <div id="editor-{{$b}}" class="flex-grow text-[14px] text-slate-700 p-2">{!! isset($sejarah) ? old('description', $sejarah->description) : '' !!}</div>
                                </div>
                            </div>

                            <!-- 5 Slot Gambar -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Dokumentasi (Maks 5 Foto)</label>
                                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        @php 
                                            $imgField = 'image_'.$i; 
                                            $inputName = isset($sejarah) ? $imgField : "sejarah[{$b}][{$imgField}]";
                                            $removeName = isset($sejarah) ? "remove_{$imgField}" : "sejarah[{$b}][remove_{$imgField}]";
                                        @endphp
                                        <div class="flex flex-col items-center">
                                            <div class="relative w-full aspect-square border-2 border-dashed border-slate-300 hover:border-kmdgi-primary rounded-xl bg-slate-50 flex items-center justify-center p-2 group cursor-pointer transition-colors overflow-hidden" onclick="document.getElementById('input-img-{{$b}}-{{$i}}').click()">
                                                <div id="placeholder-{{$b}}-{{$i}}" class="flex flex-col items-center text-center pointer-events-none relative z-10 transition-opacity {{ (isset($sejarah) && $sejarah->$imgField) ? 'opacity-0' : '' }}">
                                                    <svg class="w-5 h-5 text-slate-400 mb-1 group-hover:text-kmdgi-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                                    <span class="text-[9px] font-bold text-slate-400">Foto {{$i}}</span>
                                                </div>
                                                <img id="preview-{{$b}}-{{$i}}" src="{{ (isset($sejarah) && $sejarah->$imgField) ? asset('storage/' . $sejarah->$imgField) : '' }}" class="absolute inset-0 w-full h-full object-cover z-20 {{ (isset($sejarah) && $sejarah->$imgField) ? '' : 'hidden' }}" />
                                            </div>
                                            <input type="file" name="{{$inputName}}" id="input-img-{{$b}}-{{$i}}" accept="image/*" class="hidden" onchange="previewImg(this, {{$b}}, {{$i}})">
                                            <input type="hidden" name="{{$removeName}}" id="remove-img-{{$b}}-{{$i}}" value="0">
                                            
                                            <button type="button" id="btn-remove-{{$b}}-{{$i}}" class="mt-1.5 text-[9px] font-bold text-red-500 hover:text-red-700 bg-red-50 px-2 py-1 rounded {{ (isset($sejarah) && $sejarah->$imgField) ? '' : 'hidden' }}" onclick="removeImg({{$b}}, {{$i}})">Hapus</button>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                        </div>
                    @endfor
                </div>

                <div class="pt-2 flex flex-col sm:flex-row justify-between items-center gap-4">
                    @if(!isset($sejarah))
                        <button type="button" onclick="showNextBlock()" id="btn-add-form" class="flex items-center gap-2 text-sm font-bold text-kmdgi-primary bg-blue-50 px-5 py-3 rounded-xl hover:bg-blue-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Tambah Form Sejarah Baru
                        </button>
                    @else
                        <div></div>
                    @endif

                    <div class="flex gap-3 w-full sm:w-auto">
                        <a href="{{ route('admin.sejarah.index') }}" class="flex-1 sm:flex-none text-center bg-white border border-slate-200 text-slate-600 font-bold py-3.5 px-8 rounded-xl hover:bg-slate-50 transition-colors text-sm shadow-sm">Batal</a>
                        <button type="submit" class="flex-1 sm:flex-none text-center bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-3.5 px-8 rounded-xl transition-colors text-sm shadow-md shadow-kmdgi-primary/20">
                            {{ isset($sejarah) ? 'Simpan Perubahan' : 'Upload Sekaligus' }}
                        </button>
                    </div>
                </div>
            </form>

        </main>
    </div>

    @include('partials.footer')
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    // Inisialisasi Array Editor Quill untuk 5 Form
    const quills = [];
    const loopLimit = {{ isset($sejarah) ? 1 : 5 }};

    document.addEventListener('DOMContentLoaded', function() {
        for(let b = 0; b < loopLimit; b++) {
            quills[b] = new Quill('#editor-' + b, {
                modules: { toolbar: '#toolbar-' + b },
                theme: 'snow'
            });
        }

        // Sinkronisasi Data Quill ke Input Hidden sebelum Submit
        document.getElementById('sejarah-form').addEventListener('submit', function(e) {
            for(let b = 0; b < loopLimit; b++) {
                let html = quills[b].root.innerHTML;
                if(html === '<p><br></p>') html = '';
                document.getElementById('input-desc-' + b).value = html;
            }
        });
    });

    // Fitur Buka Tutup Form Bulk
    let currentBlock = 0;
    function showNextBlock() {
        if(currentBlock < 4) {
            currentBlock++;
            document.getElementById('sejarah-block-' + currentBlock).classList.remove('hidden');
            if(currentBlock === 4) {
                document.getElementById('btn-add-form').classList.add('hidden'); // Hilangkan tombol jika limit
            }
        }
    }

    function hideBlock(blockIndex) {
        document.getElementById('sejarah-block-' + blockIndex).classList.add('hidden');
        // Bersihkan inputan
        document.querySelector(`input[name="sejarah[${blockIndex}][tahun]"]`).value = "";
        document.querySelector(`input[name="sejarah[${blockIndex}][title]"]`).value = "";
        quills[blockIndex].root.innerHTML = "";
        document.getElementById('btn-add-form').classList.remove('hidden');
        currentBlock--;
    }

    // Fungsi Preview Gambar 2D Array (Block & Index Gambar)
    function previewImg(input, b, i) {
        const preview = document.getElementById(`preview-${b}-${i}`);
        const placeholder = document.getElementById(`placeholder-${b}-${i}`);
        const removeBtn = document.getElementById(`btn-remove-${b}-${i}`);
        const removeInput = document.getElementById(`remove-img-${b}-${i}`);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('opacity-0');
                removeBtn.classList.remove('hidden');
                removeInput.value = '0'; 
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImg(b, i) {
        document.getElementById(`input-img-${b}-${i}`).value = ""; 
        document.getElementById(`preview-${b}-${i}`).classList.add('hidden');
        document.getElementById(`placeholder-${b}-${i}`).classList.remove('opacity-0');
        document.getElementById(`btn-remove-${b}-${i}`).classList.add('hidden');
        document.getElementById(`remove-img-${b}-${i}`).value = '1'; 
    }
</script>

<style>
    .ql-container { font-family: inherit !important; font-size: inherit; }
    .ql-editor { padding: 0.75rem 1rem; }
    .ql-toolbar.ql-snow { border: none !important; }
    .ql-container.ql-snow { border: none !important; }
</style>
@endsection