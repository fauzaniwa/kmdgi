@extends('layouts.app')
@section('title', (isset($event) ? 'Edit' : 'Tambah') . ' Event - KMDGI')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<div class="bg-slate-50 min-h-screen flex flex-col">
    @include('partials.navbar')
    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">
        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">
            <a href="{{ route('admin.event.index', ['edisi_id' => $edisiId]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-kmdgi-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg> Kembali
            </a>

            @if($errors->any())
            <div class="bg-red-50 text-red-600 px-4 py-3 rounded-2xl">
                <ul class="text-xs list-disc pl-5">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
            </div>
            @endif

            <form id="event-form" action="{{ isset($event) ? route('admin.event.update', $event->id) : route('admin.event.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($event)) @method('PUT') @endif
                <input type="hidden" name="edisi_kmdgi_id" value="{{ $edisiId }}">

                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3 mb-6">Informasi Utama Event</h3>

                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <!-- Poster -->
                        <div class="w-full md:w-1/3 lg:w-1/4">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Poster Event (1:1 / 4:5)</label>
                            <div class="relative w-full aspect-square border-2 border-dashed border-slate-300 rounded-2xl bg-slate-50 flex items-center justify-center p-4 cursor-pointer overflow-hidden" onclick="document.getElementById('input-poster').click()">
                                <div id="ph-poster" class="text-center {{ (isset($event) && $event->poster) ? 'hidden' : '' }}">
                                    <span class="text-[10px] font-bold text-slate-500">Pilih Poster</span>
                                </div>
                                <img id="pr-poster" src="{{ (isset($event) && $event->poster) ? asset('storage/'.$event->poster) : '' }}" class="absolute inset-0 w-full h-full object-cover {{ (isset($event) && $event->poster) ? '' : 'hidden' }}" />
                            </div>
                            <input type="file" name="poster" id="input-poster" accept="image/*" class="hidden" onchange="previewImageUtama(this, 'poster')">
                            <input type="hidden" name="remove_poster" id="rm-poster" value="0">
                            <div class="mt-3 text-center">
                                <button type="button" id="btn-poster" class="{{ (isset($event) && $event->poster) ? '' : 'hidden' }} text-[11px] font-bold text-red-500 hover:text-red-600 bg-red-50 px-3 py-1.5 rounded-lg" onclick="removeImageUtama('poster')">Hapus Poster</button>
                            </div>
                        </div>

                        <!-- Data -->
                        <div class="w-full md:w-2/3 lg:w-3/4 space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Judul Event</label>
                                    <input type="text" name="judul" id="input-judul" value="{{ old('judul', $event->judul ?? '') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Slug (URL)</label>
                                    <input type="text" name="slug" id="input-slug" value="{{ old('slug', $event->slug ?? '') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono text-kmdgi-primary">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori Peserta</label>
                                @php $kat = isset($event) ? ($event->kategori_peserta ?? []) : []; @endphp
                                <div class="flex flex-wrap gap-3 bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="kategori_peserta[]" value="Umum" {{ in_array('Umum', $kat) ? 'checked' : '' }}> Umum</label>
                                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="kategori_peserta[]" value="Delegasi" {{ in_array('Delegasi', $kat) ? 'checked' : '' }}> Delegasi</label>
                                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="kategori_peserta[]" value="Peninjau 1" {{ in_array('Peninjau 1', $kat) ? 'checked' : '' }}> Peninjau 1</label>
                                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="kategori_peserta[]" value="Peninjau 2" {{ in_array('Peninjau 2', $kat) ? 'checked' : '' }}> Peninjau 2</label>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Harga Tiket (Rp)</label>
                                    <input type="number" name="harga_tiket" value="{{ old('harga_tiket', $event->harga_tiket ?? 0) }}" min="0" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kuota Peserta</label>
                                    <input type="number" name="kuota" value="{{ old('kuota', $event->kuota ?? 0) }}" min="0" placeholder="0 = Tak Terbatas" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal</label>
                                    <input type="date" name="tanggal_pelaksanaan" value="{{ old('tanggal_pelaksanaan', $event->tanggal_pelaksanaan ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jam</label>
                                    <input type="time" name="jam_pelaksanaan" value="{{ old('jam_pelaksanaan', $event->jam_pelaksanaan ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Lokasi</label>
                                    <input type="text" name="lokasi" value="{{ old('lokasi', $event->lokasi ?? '') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLABORATOR SECTION -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4">Relasi Kolaborator</h3>
                    <p class="text-xs text-slate-500 mb-4">Centang kolaborator (Narasumber, Fasilitator, dll) yang terlibat di dalam event ini.</p>

                    @php $kols = isset($event) ? ($event->kolaborator_ids ?? []) : []; @endphp
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @forelse($semuaKolaborator as $kol)
                        <label class="flex items-start gap-3 p-3 border border-slate-200 rounded-xl bg-slate-50 cursor-pointer hover:border-kmdgi-primary transition-colors">
                            <input type="checkbox" name="kolaborator_ids[]" value="{{ $kol->id }}" class="mt-1 text-kmdgi-primary" {{ in_array($kol->id, $kols) ? 'checked' : '' }}>
                            <div class="flex items-center gap-2 overflow-hidden">
                                @if($kol->foto)
                                <img src="{{ asset('storage/'.$kol->foto) }}" class="w-8 h-8 rounded-full object-cover bg-white">
                                @else
                                <div class="w-8 h-8 rounded-full bg-slate-200 flex-shrink-0"></div>
                                @endif
                                <div class="truncate">
                                    <p class="text-xs font-bold text-slate-800 truncate">{{ $kol->nama }}</p>
                                    <p class="text-[10px] text-slate-500 truncate">{{ $kol->peran_kolaborasi }}</p>
                                </div>
                            </div>
                        </label>
                        @empty
                        <p class="text-xs text-slate-400 italic">Data Kolaborator belum ditambahkan di menu Master Data.</p>
                        @endforelse
                    </div>
                </div>

                <!-- QUILL EDITOR -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm space-y-6">
                    @php $textFields = ['deskripsi' => 'Deskripsi Lengkap Acara', 'ketentuan' => 'Ketentuan & Syarat Mengikuti Event']; @endphp
                    @foreach($textFields as $f => $l)
                    <div class="flex flex-col">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ $l }}</label>
                        <input type="hidden" name="{{$f}}" id="input-{{$f}}">
                        <div class="flex flex-col border border-slate-200 rounded-xl overflow-hidden focus-within:border-kmdgi-primary bg-white min-h-[150px]">
                            <div id="toolbar-{{$f}}" class="bg-slate-50 border-b border-slate-200 py-1.5"><span class="ql-formats"><button class="ql-bold"></button><button class="ql-italic"></button></span><span class="ql-formats"><button class="ql-list" value="ordered"></button><button class="ql-list" value="bullet"></button></span></div>
                            <div id="editor-{{$f}}" class="flex-grow text-[14px] text-slate-700 p-2">{!! $event->$f ?? '' !!}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('admin.event.index', ['edisi_id' => $edisiId]) }}" class="bg-white border border-slate-200 text-slate-600 font-bold py-3.5 px-8 rounded-xl text-sm">Batal</a>
                    <button type="submit" class="bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-3.5 px-10 rounded-xl text-sm">Simpan Event</button>
                </div>
            </form>
        </main>
    </div>
    @include('partials.footer')
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    document.getElementById('input-judul').addEventListener('input', function() {
        document.getElementById('input-slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
    });

    const fields = ['deskripsi', 'ketentuan'];
    const quills = {};
    fields.forEach(f => {
        quills[f] = new Quill('#editor-' + f, {
            modules: {
                toolbar: '#toolbar-' + f
            },
            theme: 'snow'
        });
    });

    document.getElementById('event-form').addEventListener('submit', () => {
        fields.forEach(f => {
            let html = quills[f].root.innerHTML;
            document.getElementById('input-' + f).value = html === '<p><br></p>' ? '' : html;
        });
    });

    function previewImageUtama(input, target) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = e => {
                document.getElementById('pr-' + target).src = e.target.result;
                document.getElementById('pr-' + target).classList.remove('hidden');
                document.getElementById('ph-' + target).classList.add('hidden');
                document.getElementById('btn-' + target).classList.remove('hidden');
                document.getElementById('rm-' + target).value = '0';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImageUtama(target) {
        document.getElementById('input-' + target).value = "";
        document.getElementById('pr-' + target).classList.add('hidden');
        document.getElementById('ph-' + target).classList.remove('hidden');
        document.getElementById('btn-' + target).classList.add('hidden');
        document.getElementById('rm-' + target).value = '1';
    }
</script>
<style>
    .ql-container {
        font-family: inherit !important;
        font-size: inherit;
    }

    .ql-toolbar.ql-snow,
    .ql-container.ql-snow {
        border: none !important;
    }
</style>
@endsection