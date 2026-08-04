@extends('layouts.app')

@section('title', 'Syarat & Ketentuan - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">

            <!-- Flash Message -->
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

            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Syarat & Ketentuan</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola dokumen persyaratan, regulasi acara, dan kebijakan privasi KMDGI.</p>
                </div>
                <!-- Tombol ini sekarang menggunakan A href, bukan onClick JS -->
                <a href="{{ route('admin.syarat.create') }}" class="inline-flex items-center justify-center gap-2 bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-5 rounded-xl text-sm transition-all shadow-md shadow-kmdgi-primary/10 flex-shrink-0">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    Tambah Dokumen
                </a>
            </div>

            <!-- Tabel Data -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-4 min-w-[200px]">Judul Dokumen</th>
                                <th class="py-4 px-4 min-w-[300px]">Preview Konten</th>
                                <th class="py-4 px-4 text-center">Status</th>
                                <th class="py-4 px-6 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-[14px]">

                            @forelse($dataSyarat as $index => $syarat)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 text-center font-medium text-slate-400">
                                    {{ $dataSyarat->firstItem() + $index }}
                                </td>
                                
                                <td class="py-4 px-4 font-bold text-slate-900 leading-tight">
                                    {{ $syarat->judul }}
                                </td>

                                <td class="py-4 px-4 text-slate-500 text-sm">
                                    <span class="line-clamp-2">{{ strip_tags($syarat->konten) }}</span>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @if($syarat->is_active)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wide">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-slate-50 text-slate-500 border border-slate-200 uppercase tracking-wide">Draft</span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Tombol Edit sekarang menjadi href tag -->
                                        <a href="{{ route('admin.syarat.edit', $syarat->id) }}" title="Edit Dokumen" class="p-2 text-slate-400 hover:text-kmdgi-primary bg-slate-50 hover:bg-blue-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                        </a>

                                        <!-- Delete Data masih memakai JS Global Modal -->
                                        <button type="button"
                                            onclick="openModal('kmdgi-global-modal', this)"
                                            data-title="Hapus Dokumen?"
                                            data-message="Apakah Anda yakin ingin menghapus data '{{ $syarat->judul }}' secara permanen?"
                                            data-type="danger"
                                            data-primary-text="Hapus Permanen"
                                            data-secondary-text="Batalkan"
                                            data-form-id="delete-form-syarat-{{ $syarat->id }}"
                                            class="p-2 text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m1.74 12.5h3.682c1.154 0 2.13-.816 2.229-1.943l.894-11.89c.041-.517-.333-.966-.853-.966H17.5M8.5 4h7M10.017 1.75h3.966M3 7.5h18" /></svg>
                                        </button>
                                        <form id="delete-form-syarat-{{ $syarat->id }}" action="{{ route('admin.syarat.destroy', $syarat->id) }}" method="POST" class="hidden">
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
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                        Belum ada dokumen Syarat & Ketentuan.
                                    </div>
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
                
                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-medium">
                    <span>Menampilkan {{ $dataSyarat->firstItem() ?? 0 }}-{{ $dataSyarat->lastItem() ?? 0 }} dari {{ $dataSyarat->total() }} Data</span>
                    <div class="flex gap-1.5">
                        <a href="{{ $dataSyarat->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ $dataSyarat->onFirstPage() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Sebelumnya</a>
                        <a href="{{ $dataSyarat->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ !$dataSyarat->hasMorePages() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Selanjutnya</a>
                    </div>
                </div>

            </div>
        </main>
    </div>

    @include('partials.footer')
</div>
@endsection