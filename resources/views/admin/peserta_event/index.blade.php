@extends('layouts.app')

@section('title', 'Data Peserta Acara - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8 font-sans">
        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full">

            <!-- Header Halaman & Tombol Export -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Data Peserta Acara (Event)</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar seluruh peserta yang tiketnya sudah berstatus Aktif.</p>
                </div>
                
                <!-- TOMBOL EXPORT (Mengirimkan query saat ini ke URL export) -->
                <a href="{{ route('admin.peserta.event.export', request()->query()) }}" class="inline-flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-5 rounded-xl text-sm transition-colors shadow-sm shadow-emerald-500/20 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    Export ke Excel (CSV)
                </a>
            </div>

            <!-- Form Filter -->
            <form method="GET" action="{{ route('admin.peserta.event.index') }}" class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-sm space-y-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="relative flex-grow">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" /></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama, Email, Institusi, Kode Tiket..." class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#1A68FF]/50" onchange="this.form.submit()">
                    </div>
                </div>

                <hr class="border-slate-100">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Filter Acara</label>
                        <select name="event_id" onchange="this.form.submit()" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-600 focus:outline-none cursor-pointer">
                            <option value="all">Semua Acara</option>
                            @foreach($listEvents as $event)
                                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>{{ $event->judul }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Jenis Sesi</label>
                        <select name="jenis" onchange="this.form.submit()" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-600 focus:outline-none cursor-pointer">
                            <option value="all">Semua Jenis Sesi</option>
                            <option value="Seminar" {{ request('jenis') == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                            <option value="Workshop" {{ request('jenis') == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                        </select>
                    </div>
                </div>
            </form>

            <!-- Tabel Data Peserta -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-4 min-w-[200px]">Data Peserta</th>
                                <th class="py-4 px-4 min-w-[150px]">Kontak & Institusi</th>
                                <th class="py-4 px-4 min-w-[200px]">Acara & Tiket</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-[14px]">
                            @forelse($pesertaEvents as $index => $peserta)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 text-center font-medium text-slate-400">
                                    {{ $pesertaEvents->firstItem() + $index }}
                                </td>
                                
                                <td class="py-4 px-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900 block leading-tight truncate max-w-[200px]">{{ $peserta->user->name ?? '-' }}</span>
                                        <span class="text-xs text-slate-500 mt-0.5">{{ $peserta->user->email ?? '-' }}</span>
                                    </div>
                                </td>

                                <td class="py-4 px-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-xs text-slate-600 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" /></svg>
                                            {{ $peserta->user->no_hp ?? '-' }}
                                        </span>
                                        <span class="text-xs text-slate-600 flex items-center gap-1.5 truncate max-w-[150px]" title="{{ $peserta->user->institusi }}">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" /></svg>
                                            {{ $peserta->user->institusi ?? 'Umum' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="py-4 px-4">
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-semibold text-[#1A68FF] bg-blue-50 px-2 py-0.5 rounded uppercase w-fit mb-1">{{ $peserta->jenis_tiket }}</span>
                                        <span class="text-sm font-bold text-slate-800 line-clamp-1" title="{{ $peserta->event->judul }}">{{ $peserta->event->judul }}</span>
                                        <span class="text-xs text-slate-500 font-mono mt-0.5">{{ $peserta->kode_tiket }}</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z" /></svg>
                                        <span class="text-sm text-slate-400 font-medium">Tidak ada data peserta acara.</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($pesertaEvents->hasPages())
                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-medium">
                    <span>Menampilkan {{ $pesertaEvents->firstItem() ?? 0 }}-{{ $pesertaEvents->lastItem() ?? 0 }} dari {{ $pesertaEvents->total() }} Peserta</span>
                    <div class="flex gap-1.5">
                        <a href="{{ $pesertaEvents->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ $pesertaEvents->onFirstPage() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl">Sebelumnya</a>
                        <a href="{{ $pesertaEvents->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ !$pesertaEvents->hasMorePages() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl">Selanjutnya</a>
                    </div>
                </div>
                @endif
            </div>

        </main>
    </div>
    @include('partials.footer')
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
</style>
@endsection