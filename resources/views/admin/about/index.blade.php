@extends('layouts.app')

@section('title', 'About KMDGI - Admin Panel')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">

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
                    <span class="text-sm font-bold">Gagal Menyimpan Data!</span>
                </div>
                <ul class="text-xs list-disc list-inside pl-9 mt-1 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Data About KMDGI</h2>
                    <p class="text-sm text-slate-500 mt-1">Kelola penjelasan utama mengenai apa itu KMDGI beserta galeri fotonya.</p>
                </div>
            </div>

            <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <form id="about-form" action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Seksi <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $about->title) }}" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-xl text-lg font-bold focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all">
                    </div>

                    <div class="flex flex-col flex-grow">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi / Penjelasan <span class="text-red-500">*</span></label>
                        <input type="hidden" name="description" id="input-description">
                        
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
                            </div>
                            <div id="quill-editor" class="flex-grow h-full text-[15px] text-slate-700 p-2">{!! old('description', $about->description) !!}</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Galeri Foto Pendukung (Maks 5 Gambar)</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                            
                            @for($i = 1; $i <= 5; $i++)
                                @php $imgField = 'image_'.$i; @endphp
                                <div class="flex flex-col items-center">
                                    <div class="relative w-full aspect-square border-2 border-dashed border-slate-300 hover:border-kmdgi-primary rounded-2xl bg-slate-50 flex items-center justify-center p-2 group cursor-pointer transition-colors overflow-hidden" onclick="document.getElementById('input-img-{{$i}}').click()">
                                        <div id="placeholder-{{$i}}" class="flex flex-col items-center text-center pointer-events-none relative z-10 transition-opacity {{ $about->$imgField ? 'opacity-0' : '' }}">
                                            <svg class="w-6 h-6 text-slate-400 mb-1 group-hover:text-kmdgi-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                            <span class="text-[10px] font-bold text-slate-400">Foto {{$i}}</span>
                                        </div>
                                        <img id="preview-{{$i}}" src="{{ $about->$imgField ? asset('storage/' . $about->$imgField) : '' }}" class="absolute inset-0 w-full h-full object-cover z-20 {{ $about->$imgField ? '' : 'hidden' }}" />
                                    </div>
                                    <input type="file" name="image_{{$i}}" id="input-img-{{$i}}" accept="image/*" class="hidden" onchange="previewMultipleImage(this, {{$i}})">
                                    <input type="hidden" name="remove_image_{{$i}}" id="remove-img-{{$i}}" value="0">
                                    
                                    <button type="button" id="btn-remove-{{$i}}" class="mt-2 text-[10px] font-bold text-red-500 hover:text-red-700 bg-red-50 px-2 py-1 rounded-md {{ $about->$imgField ? '' : 'hidden' }}" onclick="removeMultipleImage({{$i}})">Hapus Foto {{$i}}</button>
                                </div>
                            @endfor

                        </div>
                        <p class="text-xs text-slate-400 mt-3">Maksimal 3MB per foto. Gunakan format JPG, PNG, atau WEBP.</p>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="inline-flex justify-center bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-4 px-10 rounded-xl transition-colors text-sm shadow-md shadow-kmdgi-primary/20">
                            Simpan Data About
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
    // Universal Previewer untuk 5 Box
    function previewMultipleImage(input, index) {
        const preview = document.getElementById('preview-' + index);
        const placeholder = document.getElementById('placeholder-' + index);
        const removeBtn = document.getElementById('btn-remove-' + index);
        const removeInput = document.getElementById('remove-img-' + index);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('opacity-0');
                removeBtn.classList.remove('hidden');
                removeInput.value = '0'; // Batalkan perintah hapus jika sebelumnya ditekan
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeMultipleImage(index) {
        document.getElementById('input-img-' + index).value = ""; 
        document.getElementById('preview-' + index).classList.add('hidden');
        document.getElementById('placeholder-' + index).classList.remove('opacity-0');
        document.getElementById('btn-remove-' + index).classList.add('hidden');
        document.getElementById('remove-img-' + index).value = '1'; // Tandai untuk dihapus di database
    }

    document.addEventListener('DOMContentLoaded', function() {
        const quill = new Quill('#quill-editor', {
            modules: { toolbar: '#editor-toolbar' },
            placeholder: 'Tuliskan deskripsi mengenai Apa itu KMDGI di sini...',
            theme: 'snow'
        });

        document.getElementById('about-form').addEventListener('submit', function(e) {
            let htmlContent = quill.root.innerHTML;
            if (htmlContent === '<p><br></p>') htmlContent = '';
            document.getElementById('input-description').value = htmlContent;
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