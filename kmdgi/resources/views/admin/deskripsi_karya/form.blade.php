@extends('layouts.app')

@section('title', 'Karya ' . $kategoriLabel . ' - KMDGI 16')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <span class="text-sm font-semibold flex items-center gap-2">✅ {{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">✖</button>
            </div>
            @endif

            <!-- FILTER EDISI -->
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Karya {{ $kategoriLabel }}</h2>
                    <p class="text-sm text-slate-500 mt-1">Kelola aturan dan deskripsi karya yang terikat dengan identitas edisi.</p>
                </div>

                <div class="w-full sm:w-auto bg-slate-50 p-2 rounded-xl border border-slate-200 flex items-center gap-3">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider pl-2 whitespace-nowrap">Edit Untuk Edisi:</label>
                    <select onchange="window.location.href='{{ route('admin.karya.edit', $kategori) }}?edisi_id=' + this.value" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-bold text-kmdgi-primary focus:outline-none cursor-pointer shadow-sm">
                        @foreach($semuaEdisi as $edisi)
                        <option value="{{ $edisi->id }}" {{ $edisiId == $edisi->id ? 'selected' : '' }}>
                            {{ $edisi->nama_edisi }} {{ $edisi->is_active ? '(Aktif)' : '' }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- FORM UTAMA -->
            <form id="karya-form" action="{{ route('admin.karya.update', $kategori) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] space-y-8">
                @csrf
                @method('PUT')
                <input type="hidden" name="edisi_kmdgi_id" value="{{ $edisiId }}">

                <!-- 1. THUMBNAIL -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">1. Thumbnail Utama {{ $kategoriLabel }} (Opsional)</label>
                    <div class="relative w-full md:w-1/3 aspect-video border-2 border-dashed border-slate-300 hover:border-kmdgi-primary rounded-xl bg-slate-50 flex items-center justify-center p-2 group cursor-pointer overflow-hidden" onclick="document.getElementById('input-thumbnail').click()">
                        <div id="ph-thumb" class="text-center transition-opacity {{ $karya->thumbnail ? 'opacity-0' : '' }}">
                            <span class="text-xs font-bold text-slate-400">Pilih Thumbnail</span>
                        </div>
                        <img id="pr-thumb" src="{{ $karya->thumbnail ? asset('storage/' . $karya->thumbnail) : '' }}" class="absolute inset-0 w-full h-full object-cover {{ $karya->thumbnail ? '' : 'hidden' }}" />
                    </div>
                    <input type="file" name="thumbnail" id="input-thumbnail" accept="image/*" class="hidden" onchange="previewThumb(this)">
                    <input type="hidden" name="remove_thumbnail" id="rm-thumb" value="0">
                    <button type="button" id="btn-thumb" class="mt-2 text-[10px] font-bold text-red-500 hover:underline {{ $karya->thumbnail ? '' : 'hidden' }}" onclick="removeThumb()">Hapus Thumbnail</button>
                </div>

                <!-- 2-7. ENAM KOTAK TEXT EDITOR (LOOPING) -->
                @php
                $fields = [
                'deskripsi' => '2. Deskripsi Singkat',
                'general_aturan' => '3. General Aturan',
                'ketentuan_karya' => '4. Ketentuan Karya',
                'teknis_pelaksanaan' => '5. Teknis Pelaksanaan',
                'sistem_penilaian' => '6. Sistem Penilaian',
                'nominasi_kriteria' => '7. Nominasi dan Kriteria Karya'
                ];
                @endphp

                <div class="space-y-6">
                    @foreach($fields as $field => $label)
                    <div class="flex flex-col">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ $label }}</label>
                        <input type="hidden" name="{{ $field }}" id="input-{{ $field }}">

                        <div class="flex-grow flex flex-col border border-slate-200 rounded-xl overflow-hidden focus-within:border-kmdgi-primary transition-all bg-white min-h-[150px]">
                            <div id="toolbar-{{ $field }}" class="bg-slate-50 border-b border-slate-200 py-1.5">
                                <span class="ql-formats"><button class="ql-bold"></button><button class="ql-italic"></button></span>
                                <span class="ql-formats"><button class="ql-list" value="ordered"></button><button class="ql-list" value="bullet"></button></span>
                            </div>
                            <div id="editor-{{ $field }}" class="flex-grow text-[14px] text-slate-700 p-2">{!! $karya->$field ?? '' !!}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="inline-flex justify-center bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-4 px-12 rounded-xl transition-colors text-sm shadow-md shadow-kmdgi-primary/20">
                        Simpan Data {{ $kategoriLabel }}
                    </button>
                </div>
            </form>

        </main>
    </div>
    @include('partials.footer')
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    // Konfigurasi 6 Quill Editors
    const textFields = ['deskripsi', 'general_aturan', 'ketentuan_karya', 'teknis_pelaksanaan', 'sistem_penilaian', 'nominasi_kriteria'];
    const quills = {};

    document.addEventListener('DOMContentLoaded', function() {
        textFields.forEach(field => {
            quills[field] = new Quill('#editor-' + field, {
                modules: {
                    toolbar: '#toolbar-' + field
                },
                theme: 'snow'
            });
        });

        // Sinkronisasi seluruh editor saat submit
        document.getElementById('karya-form').addEventListener('submit', function() {
            textFields.forEach(field => {
                let html = quills[field].root.innerHTML;
                document.getElementById('input-' + field).value = html === '<p><br></p>' ? '' : html;
            });
        });
    });

    // Preview Image Thumbnail
    function previewThumb(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('pr-thumb').src = e.target.result;
                document.getElementById('pr-thumb').classList.remove('hidden');
                document.getElementById('ph-thumb').classList.add('opacity-0');
                document.getElementById('btn-thumb').classList.remove('hidden');
                document.getElementById('rm-thumb').value = '0';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeThumb() {
        document.getElementById('input-thumbnail').value = "";
        document.getElementById('pr-thumb').classList.add('hidden');
        document.getElementById('ph-thumb').classList.remove('opacity-0');
        document.getElementById('btn-thumb').classList.add('hidden');
        document.getElementById('rm-thumb').value = '1';
    }
</script>

<style>
    .ql-container {
        font-family: inherit !important;
        font-size: inherit;
    }

    .ql-editor {
        padding: 1rem;
    }

    .ql-toolbar.ql-snow {
        border: none !important;
    }

    .ql-container.ql-snow {
        border: none !important;
    }
</style>
@endsection