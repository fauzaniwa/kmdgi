@extends('layouts.app')

@section('title', 'Rekap Kehadiran Pameran - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full min-w-0 font-sans">

            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Rekap Kehadiran Pameran</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar peserta yang telah divalidasi masuk (Hadir) untuk tiket eksibisi Pameran.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Total Hadir: {{ $kehadiranPameran->total() }}
                    </span>
                </div>
            </div>

            <!-- Bar Pencarian -->
            <form method="GET" action="{{ route('admin.kehadiran.pameran.index') }}" class="bg-white p-4 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col md:flex-row gap-3 items-center justify-between">
                <div class="relative w-full md:w-96">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peserta atau kode tiket..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-kmdgi-primary transition-colors">
                </div>

                <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 w-full md:w-auto">
                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-kmdgi-primary hover:bg-kmdgi-hover text-white text-sm font-semibold rounded-xl transition-all shadow-sm">Pencarian</button>
                    
                    <!-- TOMBOL EXPORT EXCEL -->
                    <a href="{{ route('admin.kehadiran.pameran.export', request()->query()) }}" class="w-full sm:w-auto px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-xl transition-all shadow-sm text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                        Export Excel
                    </a>

                    @if(request()->filled('search'))
                    <a href="{{ route('admin.kehadiran.pameran.index') }}" class="text-xs text-slate-400 hover:text-red-500 font-semibold px-2 py-1">Reset</a>
                    @endif
                </div>
            </form>

            <!-- Tabel Data Kehadiran -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] w-full max-w-full overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[900px]">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-4">Nama Peserta / Institusi</th>
                                <th class="py-4 px-4 text-center">Kode Tiket</th>
                                <th class="py-4 px-4 text-center">Jenis Akses</th>
                                <th class="py-4 px-6 text-center">Waktu Scan Masuk</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-[14px]">
                            @forelse($kehadiranPameran as $index => $tiket)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 text-center font-medium text-slate-400">{{ $kehadiranPameran->firstItem() + $index }}</td>
                                
                                <td class="py-4 px-4">
                                    <span class="block font-bold text-slate-900 leading-tight">{{ $tiket->user->name ?? 'User Tidak Diketahui' }}</span>
                                    <span class="block text-xs text-slate-400 mt-0.5">{{ $tiket->user->institusi ?? 'Umum' }}</span>
                                </td>

                                <td class="py-4 px-4 text-center font-mono font-bold text-kmdgi-primary tracking-wider text-xs">
                                    {{ $tiket->kode_tiket }}
                                </td>

                                <td class="py-4 px-4 font-semibold text-center text-slate-700 text-sm">
                                    <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-md text-xs border border-slate-200">Pameran KMDGI 16</span>
                                </td>

                                <td class="py-4 px-6 text-center">
                                    <span class="block text-sm font-bold text-slate-700">{{ \Carbon\Carbon::parse($tiket->waktu_kehadiran)->format('H:i:s') }} WIB</span>
                                    <span class="block text-xs text-slate-400">{{ \Carbon\Carbon::parse($tiket->waktu_kehadiran)->translatedFormat('d M Y') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-sm text-slate-400 font-medium">Belum ada peserta yang hadir / di-scan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginasi -->
                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-medium">
                    <span>Menampilkan {{ $kehadiranPameran->firstItem() ?? 0 }}-{{ $kehadiranPameran->lastItem() ?? 0 }} dari {{ $kehadiranPameran->total() }} Hadir</span>
                    <div class="flex gap-1.5">
                        <a href="{{ $kehadiranPameran->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ $kehadiranPameran->onFirstPage() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Sebelumnya</a>
                        <a href="{{ $kehadiranPameran->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ !$kehadiranPameran->hasMorePages() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Selanjutnya</a>
                    </div>
                </div>
            </div>

        </main>
    </div>
    @include('partials.footer')
</div>
@endsection