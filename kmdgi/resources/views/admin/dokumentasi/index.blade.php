@extends('layouts.app')

@section('title', 'Dokumentasi Acara - KMDGI 16')

@section('content')
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

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Dokumentasi & Galeri</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola arsip foto, video cuplikan, dan aftermovie rangkaian acara KMDGI.</p>
                </div>
                <a href="{{ route('admin.dokumentasi.create') }}" class="inline-flex items-center justify-center gap-2 bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-5 rounded-xl text-sm transition-all shadow-md shadow-kmdgi-primary/10 flex-shrink-0">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Upload Media
                </a>
            </div>

            <form method="GET" action="{{ route('admin.dokumentasi.index') }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col md:flex-row items-center gap-4 justify-between">
                <div class="relative w-full md:max-w-sm">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" /></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul dokumentasi..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200/80 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:border-kmdgi-primary/50 focus:bg-white transition-all" onchange="this.form.submit()">
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kategori:</span>
                    <select name="kategori" onchange="this.form.submit()" class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-600 focus:outline-none cursor-pointer">
                        <option value="all" {{ request('kategori') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                        <option value="Pra-Event" {{ request('kategori') == 'Pra-Event' ? 'selected' : '' }}>Pra-Event</option>
                        <option value="Pameran & Instalasi" {{ request('kategori') == 'Pameran & Instalasi' ? 'selected' : '' }}>Pameran & Instalasi</option>
                        <option value="Seminar & Talkshow" {{ request('kategori') == 'Seminar & Talkshow' ? 'selected' : '' }}>Seminar & Talkshow</option>
                        <option value="Malam Puncak" {{ request('kategori') == 'Malam Puncak' ? 'selected' : '' }}>Malam Puncak</option>
                    </select>
                </div>
            </form>

            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-4 min-w-[200px]">Preview Media</th>
                                <th class="py-4 px-4 min-w-[250px]">Informasi Dokumentasi</th>
                                <th class="py-4 px-4 text-center">Status</th>
                                <th class="py-4 px-6 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-[14px]">

                            @forelse($dataDokumentasi as $index => $item)
                            <tr class="hover:bg-slate-50/50 transition-colors bg-white">
                                <td class="py-4 px-6 text-center font-medium text-slate-400">
                                    {{ $dataDokumentasi->firstItem() + $index }}
                                </td>
                                
                                <td class="py-4 px-4">
                                    <div class="w-32 h-20 bg-slate-100 border border-slate-200 rounded-xl overflow-hidden shadow-sm relative flex items-center justify-center">
                                        @if($item->tipe_media === 'Foto')
                                            <img src="{{ asset('storage/' . $item->file_path) }}" alt="Preview" class="w-full h-full object-cover">
                                        @elseif($item->tipe_media === 'Video Upload')
                                            <!-- Tampilkan ikon Video Play jika file berupa video MP4 -->
                                            <video src="{{ asset('storage/' . $item->file_path) }}" class="w-full h-full object-cover opacity-50"></video>
                                            <svg class="absolute w-8 h-8 text-white drop-shadow-md z-10" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        @elseif($item->tipe_media === 'Video YouTube')
                                            <!-- Ikon YouTube Merah -->
                                            <div class="bg-red-50 w-full h-full flex flex-col items-center justify-center text-red-500">
                                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-4 px-4">
                                    <span class="font-bold text-slate-900 block leading-tight mb-1">{{ $item->judul }}</span>
                                    <div class="flex flex-wrap gap-2 mt-1.5">
                                        <span class="text-[10px] font-bold text-kmdgi-primary bg-blue-50 px-2 py-0.5 rounded border border-blue-100">{{ $item->kategori_kegiatan }}</span>
                                        <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">{{ \Carbon\Carbon::parse($item->tanggal_kegiatan)->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-2 line-clamp-2">{{ strip_tags($item->deskripsi) }}</p>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @if($item->is_active)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wide">Publikasi</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-slate-50 text-slate-500 border border-slate-200 uppercase tracking-wide">Draft</span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.dokumentasi.edit', $item->id) }}" class="p-2 text-slate-400 hover:text-kmdgi-primary bg-slate-50 hover:bg-blue-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                        </a>

                                        <button type="button"
                                            onclick="openModal('kmdgi-global-modal', this)"
                                            data-title="Hapus Dokumentasi?"
                                            data-message="Yakin ingin menghapus data {{ $item->judul }}? File media juga akan terhapus."
                                            data-type="danger"
                                            data-primary-text="Hapus"
                                            data-secondary-text="Batal"
                                            data-form-id="delete-form-{{ $item->id }}"
                                            class="p-2 text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m1.74 12.5h3.682c1.154 0 2.13-.816 2.229-1.943l.894-11.89c.041-.517-.333-.966-.853-.966H17.5M8.5 4h7M10.017 1.75h3.966M3 7.5h18" /></svg>
                                        </button>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('admin.dokumentasi.destroy', $item->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-sm text-slate-400 font-medium">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                        Belum ada data dokumentasi yang ditambahkan.
                                    </div>
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-medium">
                    <span>Menampilkan {{ $dataDokumentasi->firstItem() ?? 0 }}-{{ $dataDokumentasi->lastItem() ?? 0 }} dari {{ $dataDokumentasi->total() }} Data</span>
                    <div class="flex gap-1.5">
                        <a href="{{ $dataDokumentasi->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ $dataDokumentasi->onFirstPage() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Sebelumnya</a>
                        <a href="{{ $dataDokumentasi->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ !$dataDokumentasi->hasMorePages() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Selanjutnya</a>
                    </div>
                </div>

            </div>
        </main>
    </div>

    @include('partials.footer')
</div>
@endsection