@extends('layouts.app')
@section('title', 'Karya ' . $kategoriLabel . ' - KMDGI 16')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<div class="bg-slate-50 min-h-screen flex flex-col">
    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">
        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">

            @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl flex items-center justify-between shadow-sm">
                <span class="text-sm font-semibold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    {{ session('success') }}
                </span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 transition-colors">✖</button>
            </div>
            @endif

            @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3 mb-1">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" />
                    </svg>
                    <span class="text-sm font-bold">Gagal Menyimpan Data!</span>
                </div>
                <ul class="text-xs list-disc list-inside pl-9 mt-1 space-y-0.5">
                    <!-- LOOP ERROR DIRAPIKAN -->
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
                        <!-- LOOP EDISI DIRAPIKAN -->
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
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4">1. Identitas Visual</h3>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Thumbnail Utama {{ $kategoriLabel }} (Opsional)</label>

                    <!-- KOTAK PREVIEW -->
                    <div class="relative w-full md:w-1/3 aspect-video border-2 border-dashed border-slate-300 hover:border-kmdgi-primary rounded-xl bg-slate-50 flex items-center justify-center p-2 group cursor-pointer overflow-hidden" onclick="document.getElementById('input-thumbnail').click()">

                        <!-- Tulisan & Ikon Placeholder -->
                        <div id="ph-thumb" class="text-center transition-opacity {{ (isset($karya) &&$karya->thumbnail) ? 'hidden' : '' }}">
                            <svg class="w-8 h-8 text-slate-400 mx-auto mb-2 group-hover:text-kmdgi-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            <span class="text-xs font-bold text-slate-400">Pilih Thumbnail</span>
                        </div>

                        <!-- Gambar Preview -->
                        <img id="pr-thumb" src="{{ (isset($karya) && $karya->thumbnail) ? asset('storage/' . $karya->thumbnail) : '' }}" class="absolute inset-0 w-full h-full object-cover z-10 bg-white {{ (isset($karya) &&$karya->thumbnail) ? '' : 'hidden' }}" />
                    </div>

                    <input type="file" name="thumbnail" id="input-thumbnail" accept="image/*" class="hidden" onchange="previewThumb(this)">
                    <input type="hidden" name="remove_thumbnail" id="rm-thumb" value="0">
                    <button type="button" id="btn-thumb" class="mt-2 text-[10px] font-bold text-red-500 hover:underline {{ (isset($karya) &&$karya->thumbnail) ? '' : 'hidden' }}" onclick="removeThumb()">Hapus Thumbnail</button>
                </div>

                <!-- 2-7. ENAM KOTAK TEXT EDITOR -->
                <div>
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3 mb-6">2. Deskripsi & Aturan Regulasi</h3>

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
                        <!-- LOOP EDITOR DIRAPIKAN -->
                         @foreach($fields as $field => $label)
                        <div class="flex flex-col">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ $label }}</label>
                            <input type="hidden" name="{{ $field }}" id="input-{{ $field }}">

                            <div class="flex-grow flex flex-col border border-slate-200 rounded-xl overflow-hidden focus-within:border-kmdgi-primary focus-within:ring-1 focus-within:ring-kmdgi-primary/30 transition-all bg-white min-h-[150px]">
                                <div id="toolbar-{{ $field }}" class="bg-slate-50/80 border-b border-slate-200 py-1.5 px-3">
                                    <span class="ql-formats">
                                        <button type="button" class="ql-bold"></button>
                                        <button type="button" class="ql-italic"></button>
                                        <button type="button" class="ql-underline"></button>
                                    </span>
                                    <span class="ql-formats">
                                        <button type="button" class="ql-list" value="ordered"></button>
                                        <button type="button" class="ql-list" value="bullet"></button>
                                    </span>
                                </div>
                                <!-- Konten Editor -->
                                <div id="editor-{{ $field }}" class="flex-grow text-[15px] text-slate-700 p-3">{!! $karya->$field ?? '' !!}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- 8. PENGATURAN DEADLINE -->
                <div>
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4">8. Pengaturan Deadline Pengumpulan</h3>
                    <div class="bg-slate-50 border border-slate-200 p-5 rounded-2xl w-full md:w-1/2">
                        <label for="deadline" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Batas Akhir Pengumpulan (Tanggal & Jam)</label>
                        <input type="datetime-local" name="deadline" id="deadline"
                            value="{{ old('deadline', (isset($karya) && $karya->deadline) ? \Carbon\Carbon::parse($karya->deadline)->format('Y-m-d\TH:i') : '') }}"
                            class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-kmdgi-primary transition-colors cursor-pointer">
                        <p class="text-[11px] text-slate-500 mt-2">* Tentukan batas akhir (tanggal & waktu) form pengumpulan akan ditutup.</p>
                    </div>
                </div>

                <!-- 9. BERKAS UNDUHAN RESMI -->
                <div>
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3 mb-5">
                        <h3 class="text-lg font-bold text-slate-800">9. Berkas Unduhan Resmi</h3>
                        <button type="button" onclick="addBerkas()" class="text-xs font-bold text-kmdgi-primary bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-xl transition-colors">+ Tambah Berkas Lainnya</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Guidebook -->
                        <div class="bg-slate-50 border border-slate-200 p-5 rounded-2xl">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">File Guidebook</label>

                            @if (isset($karya) &&$karya->file_guidebook)
                            <div id="container-gb" class="flex items-center justify-between bg-white border border-emerald-200 p-3 rounded-xl mb-3">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                    </div>
                                    <a href="{{ asset('storage/' . $karya->file_guidebook) }}" target="_blank" class="text-sm font-bold text-emerald-600 hover:underline truncate">Lihat File Saat Ini</a>
                                </div>
                                <button type="button" onclick="removeDocUtama('gb')" class="text-red-500 bg-red-50 p-1.5 rounded hover:bg-red-500 hover:text-white transition-colors" title="Hapus File"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg></button>
                            </div>
                            @endif

                            <input type="file" name="file_guidebook" accept=".pdf,.doc,.docx" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-kmdgi-primary hover:file:bg-blue-100 cursor-pointer">
                            <input type="hidden" name="remove_file_guidebook" id="rm-gb" value="0">
                        </div>

                        <!-- Panduan Online -->
                        <div class="bg-slate-50 border border-slate-200 p-5 rounded-2xl">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">File Panduan Online</label>

                            @if (isset($karya) &&$karya->file_panduan_online)
                            <div id="container-po" class="flex items-center justify-between bg-white border border-emerald-200 p-3 rounded-xl mb-3">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                    </div>
                                    <a href="{{ asset('storage/' . $karya->file_panduan_online) }}" target="_blank" class="text-sm font-bold text-emerald-600 hover:underline truncate">Lihat File Saat Ini</a>
                                </div>
                                <button type="button" onclick="removeDocUtama('po')" class="text-red-500 bg-red-50 p-1.5 rounded hover:bg-red-500 hover:text-white transition-colors" title="Hapus File"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg></button>
                            </div>
                            @endif

                            <input type="file" name="file_panduan_online" accept=".pdf,.doc,.docx" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-kmdgi-primary hover:file:bg-blue-100 cursor-pointer">
                            <input type="hidden" name="remove_file_panduan_online" id="rm-po" value="0">
                        </div>
                    </div>

                    <!-- Tempat Array Berkas Lainnya (JS Injected) -->
                    <div id="berkas-container" class="space-y-4"></div>
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

@php
$berkasJson = [];
if (isset($karya) &&$karya->berkas_lainnya) {
$berkasJson = is_string($karya->berkas_lainnya) ? json_decode($karya->berkas_lainnya, true) :$karya->berkas_lainnya;
}
@endphp

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
                theme: 'snow',
                placeholder: 'Tuliskan ' + field.replace('_', ' ') + ' di sini...'
            });
        });

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
                document.getElementById('ph-thumb').classList.add('hidden');
                document.getElementById('btn-thumb').classList.remove('hidden');
                document.getElementById('rm-thumb').value = '0';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeThumb() {
        document.getElementById('input-thumbnail').value = "";
        document.getElementById('pr-thumb').src = "";
        document.getElementById('pr-thumb').classList.add('hidden');
        document.getElementById('ph-thumb').classList.remove('hidden');
        document.getElementById('btn-thumb').classList.add('hidden');
        document.getElementById('rm-thumb').value = '1';
    }

    function removeDocUtama(type) {
        document.getElementById('container-' + type).classList.add('hidden');
        document.getElementById('rm-' + type).value = '1';
    }

    // DYNAMIC BERKAS LAINNYA
    let bIdx = 0;

    // Inject array menggunakan json directive yang aman
    const bData = @json($berkasJson ? : []);

    function addBerkas(data = {
        nama: '',
        file: ''
    }) {
        let fileHtml = data.file ? `<div class="mt-2 text-xs text-emerald-600 bg-emerald-50 px-2 py-1 rounded inline-block truncate max-w-xs">✅ File tersimpan</div> <input type="hidden" name="berkas_lainnya[${bIdx}][old_file]" value="${data.file}">` : '';

        const html = `
            <div id="br-${bIdx}" class="flex flex-col md:flex-row gap-4 bg-slate-50/50 p-4 rounded-xl border border-slate-200 relative group hover:border-kmdgi-primary transition-colors">
                <button type="button" onclick="document.getElementById('br-${bIdx}').remove()" class="absolute -top-3 -right-2 bg-red-100 text-red-500 hover:bg-red-500 hover:text-white rounded-full w-6 h-6 flex items-center justify-center font-bold text-xs transition-colors shadow-sm opacity-0 group-hover:opacity-100">X</button>
                
                <div class="w-full md:w-1/3">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nama / Keterangan Berkas</label>
                    <input type="text" name="berkas_lainnya[${bIdx}][nama]" value="${data.nama}" placeholder="Cth: Template Presentasi" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-kmdgi-primary" required>
                </div>
                
                <div class="w-full md:w-2/3">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Unggah Berkas Baru</label>
                    <input type="file" name="berkas_lainnya[${bIdx}][file]" accept=".pdf,.doc,.docx,.zip,.rar" class="w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-kmdgi-primary hover:file:bg-blue-100 cursor-pointer">
                    ${fileHtml}
                </div>
            </div>`;
        document.getElementById('berkas-container').insertAdjacentHTML('beforeend', html);
        bIdx++;
    }

    // Inisialisasi Data Default saat Load
    if (Array.isArray(bData)) {
        bData.forEach(d => addBerkas(d));
    }
</script>

<style>
    .ql-container {
        font-family: inherit !important;
        font-size: inherit;
    }

    .ql-editor {
        padding: 1.5rem;
    }

    .ql-toolbar.ql-snow {
        border: none !important;
    }

    .ql-container.ql-snow {
        border: none !important;
    }

    .ql-editor p {
        margin-bottom: 0.75rem;
        line-height: 1.6;
    }

    .ql-editor ol,
    .ql-editor ul {
        padding-left: 1.25rem;
        margin-bottom: 1rem;
        line-height: 1.6;
    }
</style>
@endsection