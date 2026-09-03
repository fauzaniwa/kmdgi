@extends('layouts.app')
@section('title', 'Moderasi Komentar - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">

            <!-- Alert Success -->
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

            <!-- Header Title -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Antrean Moderasi Komentar</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola seluruh komentar publik. Komentar yang dilaporkan akan otomatis berada di urutan atas.</p>
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-4 min-w-[200px]">Pengirim & Waktu</th>
                                <th class="py-4 px-4 min-w-[300px]">Isi Komentar & Konteks</th>
                                <th class="py-4 px-4 min-w-[250px]">Status Laporan</th>
                                <th class="py-4 px-6 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-[14px]">

                            @forelse($komentars as $index => $komentar)
                            @php
                                $isReported = $komentar->laporans_count > 0;
                            @endphp

                            <tr class="transition-colors {{ $isReported ? 'bg-red-50/20 hover:bg-red-50/40' : 'hover:bg-slate-50/50' }}">
                                
                                <!-- No -->
                                <td class="py-4 px-6 text-center font-medium text-slate-400 align-top">
                                    {{ $komentars->firstItem() + $index }}
                                </td>
                                
                                <!-- Pengirim & Waktu -->
                                <td class="py-4 px-4 align-top">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 border border-slate-200">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($komentar->user->name ?? 'U') }}&background=random" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-[13px] leading-tight">{{ $komentar->user->name ?? 'Anonim' }}</p>
                                            <p class="text-[11px] text-slate-500">{{ $komentar->user->institusi ?? 'Umum' }}</p>
                                            <p class="text-[10px] text-slate-400 mt-1">{{ $komentar->created_at->translatedFormat('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Isi Komentar & Target Karya -->
                                <td class="py-4 px-4 align-top">
                                    <div class="mb-3">
                                        @if($komentar->parent_id)
                                            <!-- Badge Jika Komentar Ini Adalah Balasan (Reply) -->
                                            <div class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded text-[10px] font-bold mb-1.5 border border-indigo-100">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                                                Merespons komentar lain
                                            </div>
                                        @endif
                                        <p class="text-slate-800 font-medium text-[13px] leading-relaxed line-clamp-3">"{{ $komentar->isi_komentar }}"</p>
                                    </div>

                                    <div class="mt-2 pt-3 border-t {{ $isReported ? 'border-red-100' : 'border-slate-100' }}">
                                        <p class="text-[10px] text-slate-400 mb-1">Target Karya:</p>
                                        <div class="flex items-center justify-between gap-2 bg-white/60 p-2 rounded-lg border border-slate-100">
                                            <div class="flex items-center gap-1.5 truncate">
                                                <span class="bg-blue-50 border border-blue-100 text-blue-600 px-1.5 py-0.5 rounded text-[9px] uppercase tracking-wider font-bold flex-shrink-0">
                                                    {{ substr($komentar->submisiKarya->kategori_karya ?? '-', 0, 3) }}
                                                </span>
                                                <span class="font-bold text-slate-700 text-[11px] truncate" title="{{ $komentar->submisiKarya->judul_karya ?? 'Karya Dihapus' }}">
                                                    {{ $komentar->submisiKarya->judul_karya ?? 'Karya Telah Dihapus' }} 
                                                </span>
                                            </div>
                                            
                                            <!-- Tombol Eksternal untuk Cek Konteks Langsung -->
                                            @if($komentar->submisiKarya)
                                            <a href="{{ route('katalog.karya.show', \Illuminate\Support\Str::slug($komentar->submisiKarya->judul_karya)) }}#komentar" target="_blank" class="flex-shrink-0 text-slate-400 hover:text-blue-600 transition-colors p-1" title="Tinjau Konteks di Halaman Karya">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Status & Detail Laporan -->
                                <td class="py-4 px-4 align-top">
                                    @if($isReported)
                                        <div class="inline-flex items-center gap-1.5 bg-red-100 border border-red-200 text-red-600 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider mb-2">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" /></svg>
                                            Dilaporkan ({{ $komentar->laporans_count }}x)
                                        </div>
                                        <div class="max-h-24 overflow-y-auto custom-scrollbar pr-2 space-y-2 mt-1">
                                            @foreach($komentar->laporans as $laporan)
                                            <div class="bg-white/60 border border-red-100 rounded-lg p-2">
                                                <p class="text-[10px] font-bold text-slate-800">{{ $laporan->user->name ?? 'User Anonim' }}</p>
                                                <p class="text-[11px] text-red-700 leading-snug mt-0.5">{{ $laporan->alasan }}</p>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-100 text-emerald-600 px-3 py-1.5 rounded-lg text-[11px] font-bold mt-1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            Aman / Bebas Laporan
                                        </div>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="py-4 px-6 text-center align-top">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        
                                        @if($isReported)
                                        <!-- Tombol Abaikan Laporan -->
                                        <button type="button"
                                            onclick="openModal('kmdgi-global-modal', this)"
                                            data-title="Abaikan Laporan?"
                                            data-message="Apakah Anda yakin mengabaikan seluruh laporan ini? Status komentar akan dikembalikan menjadi aman."
                                            data-type="warning"
                                            data-primary-text="Abaikan Laporan"
                                            data-secondary-text="Batal"
                                            data-form-id="dismiss-form-{{ $komentar->id }}"
                                            class="w-full text-center text-[10px] font-bold text-amber-600 bg-amber-50 hover:bg-amber-100 px-2 py-2 rounded-lg transition-colors border border-amber-200">
                                            Abaikan Laporan
                                        </button>
                                        <form id="dismiss-form-{{ $komentar->id }}" action="{{ route('admin.komentar.dismiss', $komentar->id) }}" method="POST" class="hidden">
                                            @csrf
                                        </form>
                                        @endif

                                        <!-- Tombol Hapus Komentar -->
                                        <button type="button"
                                            onclick="openModal('kmdgi-global-modal', this)"
                                            data-title="Hapus Komentar?"
                                            data-message="Apakah Anda yakin ingin menghapus komentar ini secara permanen dari sistem beserta seluruh laporannya?"
                                            data-type="danger"
                                            data-primary-text="Hapus Permanen"
                                            data-secondary-text="Batal"
                                            data-form-id="delete-form-{{ $komentar->id }}"
                                            class="w-full text-center text-[10px] font-bold text-red-500 bg-white hover:bg-red-50 px-2 py-2 rounded-lg transition-colors border border-red-200">
                                            Hapus Komentar
                                        </button>
                                        <form id="delete-form-{{ $komentar->id }}" action="{{ route('admin.komentar.destroy', $komentar->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-sm text-slate-400 font-medium">
                                    <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-slate-100">
                                        <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" /></svg>
                                    </div>
                                    Belum ada data komentar.
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-medium">
                    <span>Menampilkan {{ $komentars->firstItem() ?? 0 }}-{{ $komentars->lastItem() ?? 0 }} dari {{ $komentars->total() }} Data</span>
                    <div class="flex gap-1.5">
                        <a href="{{ $komentars->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ $komentars->onFirstPage() ? 'text-slate-400 pointer-events-none opacity-60' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Sebelumnya</a>
                        <a href="{{ $komentars->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ !$komentars->hasMorePages() ? 'text-slate-400 pointer-events-none opacity-60' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Selanjutnya</a>
                    </div>
                </div>

            </div>
        </main>
    </div>

    @include('partials.footer')
</div>

<style>
    /* Agar area laporan bisa discroll tapi scrollbarnya halus dan estetis */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endsection