@extends('layouts.app')

@section('title', 'Sejarah KMDGI - KMDGI 16')

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
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Perjalanan Sejarah KMDGI</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola rekam jejak pelaksanaan KMDGI dari awal hingga saat ini.</p>
                </div>
                <a href="{{ route('admin.sejarah.create') }}" class="inline-flex items-center justify-center gap-2 bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-5 rounded-xl text-sm transition-all shadow-md shadow-kmdgi-primary/10 flex-shrink-0">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Tambah Data Sejarah (Bulk)
                </a>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-4 min-w-[100px] text-center">Tahun</th>
                                <th class="py-4 px-4 min-w-[200px]">Judul Event</th>
                                <th class="py-4 px-4 min-w-[200px]">Galeri Tersimpan</th>
                                <th class="py-4 px-6 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-[14px]">

                            @forelse($dataSejarah as $index => $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 text-center font-medium text-slate-400">
                                    {{ $dataSejarah->firstItem() + $index }}
                                </td>
                                
                                <td class="py-4 px-4 text-center">
                                    <span class="font-bold text-kmdgi-primary bg-blue-50 px-3 py-1 rounded-lg border border-blue-100">{{ $item->tahun }}</span>
                                </td>

                                <td class="py-4 px-4">
                                    <span class="font-bold text-slate-900 block leading-tight">{{ $item->title }}</span>
                                    <p class="text-[11px] text-slate-500 mt-1 line-clamp-1">{{ strip_tags($item->description) }}</p>
                                </td>

                                <td class="py-4 px-4">
                                    <div class="flex -space-x-2 overflow-hidden">
                                        @php
                                            $images = array_filter([$item->image_1, $item->image_2, $item->image_3, $item->image_4, $item->image_5]);
                                        @endphp
                                        
                                        @forelse($images as $img)
                                            <img class="inline-block h-8 w-8 rounded-md ring-2 ring-white object-cover" src="{{ asset('storage/' . $img) }}" alt=""/>
                                        @empty
                                            <span class="text-xs text-slate-400 italic">Tidak ada foto</span>
                                        @endforelse
                                    </div>
                                </td>

                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.sejarah.edit', $item->id) }}" class="p-2 text-slate-400 hover:text-kmdgi-primary bg-slate-50 hover:bg-blue-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                        </a>

                                        <button type="button"
                                            onclick="openModal('kmdgi-global-modal', this)"
                                            data-title="Hapus Sejarah?"
                                            data-message="Yakin ingin menghapus riwayat {{ $item->title }} ({{ $item->tahun }})? Semua foto di dalamnya akan terhapus."
                                            data-type="danger"
                                            data-primary-text="Hapus"
                                            data-secondary-text="Batal"
                                            data-form-id="delete-form-{{ $item->id }}"
                                            class="p-2 text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m1.74 12.5h3.682c1.154 0 2.13-.816 2.229-1.943l.894-11.89c.041-.517-.333-.966-.853-.966H17.5M8.5 4h7M10.017 1.75h3.966M3 7.5h18" /></svg>
                                        </button>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('admin.sejarah.destroy', $item->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-sm text-slate-400 font-medium">Belum ada data sejarah KMDGI.</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-medium">
                    <span>Menampilkan {{ $dataSejarah->firstItem() ?? 0 }}-{{ $dataSejarah->lastItem() ?? 0 }} dari {{ $dataSejarah->total() }} Data</span>
                    <div class="flex gap-1.5">
                        <a href="{{ $dataSejarah->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ $dataSejarah->onFirstPage() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Sebelumnya</a>
                        <a href="{{ $dataSejarah->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ !$dataSejarah->hasMorePages() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Selanjutnya</a>
                    </div>
                </div>

            </div>
        </main>
    </div>

    @include('partials.footer')
</div>
@endsection