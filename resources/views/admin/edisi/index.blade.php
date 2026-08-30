@extends('layouts.app')

@section('title', 'Tema & Latar Belakang Edisi - KMDGI 16')

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
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Manajemen Edisi KMDGI</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola identitas Latar Belakang dan Tema untuk masing-masing perhelatan KMDGI.</p>
                </div>
                <a href="{{ route('admin.edisi.create') }}" class="inline-flex items-center justify-center gap-2 bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-5 rounded-xl text-sm transition-all shadow-md shadow-kmdgi-primary/10 flex-shrink-0">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Buat Edisi Baru
                </a>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-4 min-w-[200px]">Induk Edisi & Status</th>
                                <th class="py-4 px-4 min-w-[200px]">Data Latar Belakang</th>
                                <th class="py-4 px-4 min-w-[200px]">Data Tema</th>
                                <th class="py-4 px-6 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-[14px]">

                            @forelse($dataEdisi as $index => $item)
                            <tr class="hover:bg-slate-50/50 transition-colors {{ $item->is_active ? 'bg-blue-50/30' : 'bg-white' }}">
                                <td class="py-4 px-6 text-center font-medium text-slate-400">{{ $dataEdisi->firstItem() + $index }}</td>
                                
                                <td class="py-4 px-4">
                                    <span class="font-bold text-slate-900 text-base block mb-1">{{ $item->nama_edisi }}</span>
                                    @if($item->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-kmdgi-primary text-white uppercase tracking-wide shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                            Aktif di Website
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500 uppercase tracking-wide border border-slate-200">Arsip / Draft</span>
                                    @endif
                                </td>

                                <td class="py-4 px-4">
                                    <span class="font-bold text-slate-700 text-sm block mb-1">{{ $item->lb_title ?: 'Belum diisi' }}</span>
                                    <div class="flex items-center gap-2">
                                        @if($item->lb_image) <span class="w-6 h-6 rounded-md bg-blue-100 flex items-center justify-center text-blue-600" title="Ada Gambar">📸</span> @endif
                                        <span class="text-xs text-slate-500 line-clamp-1 w-full">{{ strip_tags($item->lb_deskripsi) }}</span>
                                    </div>
                                </td>

                                <td class="py-4 px-4">
                                    <span class="font-bold text-slate-700 text-sm block mb-1">{{ $item->tema_title ?: 'Belum diisi' }}</span>
                                    <div class="flex items-center gap-2">
                                        @if($item->tema_logo) <span class="w-6 h-6 rounded-md bg-pink-100 flex items-center justify-center text-pink-600" title="Ada Logo">🌟</span> @endif
                                        <span class="text-xs text-slate-500 line-clamp-1 w-full">{{ strip_tags($item->tema_deskripsi) }}</span>
                                    </div>
                                </td>

                                <td class="py-4 px-6 text-center">
                                    <div class="flex flex-wrap items-center justify-center gap-2">
                                        @if(!$item->is_active)
                                            <form action="{{ route('admin.edisi.set_active', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="p-2 text-white bg-slate-800 hover:bg-kmdgi-primary rounded-xl transition-colors shadow-sm" title="Jadikan Edisi Utama">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.438 4.438 0 0 0 2.946-2.946 4.493 4.493 0 0 0 4.306-1.758q.244.027.487.054a14.95 14.95 0 0 1-5.981 5.981" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.edisi.edit', $item->id) }}" class="p-2 text-slate-400 hover:text-blue-600 bg-slate-50 hover:bg-blue-50 rounded-xl transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                        </a>

                                        <button type="button"
                                            onclick="openModal('kmdgi-global-modal', this)"
                                            data-title="Hapus Edisi KMDGI?"
                                            data-message="Yakin ingin menghapus seluruh data Latar Belakang dan Tema untuk {{ $item->nama_edisi }}?"
                                            data-type="danger"
                                            data-primary-text="Hapus"
                                            data-secondary-text="Batal"
                                            data-form-id="delete-form-{{ $item->id }}"
                                            class="p-2 text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-xl transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m1.74 12.5h3.682c1.154 0 2.13-.816 2.229-1.943l.894-11.89c.041-.517-.333-.966-.853-.966H17.5M8.5 4h7M10.017 1.75h3.966M3 7.5h18" /></svg>
                                        </button>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('admin.edisi.destroy', $item->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-sm text-slate-400 font-medium">Belum ada data edisi yang ditambahkan.</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-medium">
                    <span>Menampilkan {{ $dataEdisi->firstItem() ?? 0 }}-{{ $dataEdisi->lastItem() ?? 0 }} dari {{ $dataEdisi->total() }} Data</span>
                    <div class="flex gap-1.5">
                        <a href="{{ $dataEdisi->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ $dataEdisi->onFirstPage() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Sebelumnya</a>
                        <a href="{{ $dataEdisi->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ !$dataEdisi->hasMorePages() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Selanjutnya</a>
                    </div>
                </div>

            </div>
        </main>
    </div>

    @include('partials.footer')
</div>
@endsection