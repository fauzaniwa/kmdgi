@extends('layouts.app')

@section('title', 'Log Aktivitas Admin - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <!-- PERBAIKAN DI SINI: Menambahkan class min-w-0 agar flex item tidak melebar melebihi layar -->
        <main class="flex-grow space-y-6 w-full min-w-0 font-sans">

            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Log Aktivitas Admin</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Pantau seluruh rekam jejak, perubahan data, dan tindakan administratif seluruh admin platform.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-blue-50 text-kmdgi-primary border border-blue-100">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Auditing Aktif
                    </span>
                </div>
            </div>

            <!-- Bar Pencarian & Filter -->
            <form method="GET" action="{{ route('admin.log-aktivitas.index') }}" class="bg-white p-4 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col md:flex-row gap-3 items-center justify-between">
                <div class="relative w-full md:w-96">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama admin, deskripsi, atau IP..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-kmdgi-primary transition-colors">
                </div>

                <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 w-full md:w-auto">
                    <!-- Filter Modul -->
                    <select name="modul" class="w-full sm:w-auto px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-kmdgi-primary transition-colors">
                        <option value="all">Semua Modul</option>
                        @foreach($listModul as $modul)
                        <option value="{{ $modul }}" {{ request('modul') == $modul ? 'selected' : '' }}>{{ $modul }}</option>
                        @endforeach
                    </select>

                    <!-- Filter Aksi -->
                    <select name="aksi" class="w-full sm:w-auto px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-kmdgi-primary transition-colors">
                        <option value="all">Semua Aksi</option>
                        @foreach($listAksi as $aksi)
                        <option value="{{ $aksi }}" {{ request('aksi') == $aksi ? 'selected' : '' }}>{{ $aksi }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-kmdgi-primary hover:bg-kmdgi-hover text-white text-sm font-semibold rounded-xl transition-all shadow-sm">
                        Filter
                    </button>

                    @if(request()->filled('search') || request()->filled('modul') || request()->filled('aksi'))
                    <a href="{{ route('admin.log-aktivitas.index') }}" class="text-xs text-slate-400 hover:text-red-500 font-semibold px-2 py-1">Reset</a>
                    @endif
                </div>
            </form>

            <!-- Tabel Data Log -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] overflow-hidden">
                
                <!-- PERBAIKAN DI SINI: Area overflow-x-auto untuk tabel -->
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse min-w-[1000px]"> <!-- Tambahan min-w agar tabel tidak terlalu sempit -->
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-4 min-w-[180px]">Admin / Pengguna</th>
                                <th class="py-4 px-4 min-w-[140px]">Modul Sistem</th>
                                <th class="py-4 px-4 text-center min-w-[100px]">Aksi</th>
                                <th class="py-4 px-4 min-w-[320px]">Deskripsi Aktivitas</th>
                                <th class="py-4 px-4 text-center min-w-[130px]">IP Address</th>
                                <th class="py-4 px-6 text-center min-w-[150px]">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-[14px]">

                            @forelse($dataLog as $index => $log)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 text-center font-medium text-slate-400">
                                    {{ $dataLog->firstItem() + $index }}
                                </td>
                                
                                <td class="py-4 px-4 font-bold text-slate-900 leading-tight">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-50 text-kmdgi-primary flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            {{ strtoupper(substr($log->user->name ?? 'A', 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="block text-slate-900 font-semibold text-sm">{{ $log->user->name ?? 'System / Dihapus' }}</span>
                                            <span class="block text-[11px] text-slate-400 font-normal">{{ $log->user->email ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-4 font-semibold text-slate-700 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs bg-slate-100 text-slate-600">
                                        {{ $log->modul }}
                                    </span>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @php
                                        $aksiLower = strtolower($log->aksi);
                                        $badgeColor = 'bg-slate-100 text-slate-600 border-slate-200';
                                        if (str_contains($aksiLower, 'create') || str_contains($aksiLower, 'approve')) {
                                            $badgeColor = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                                        } elseif (str_contains($aksiLower, 'update') || str_contains($aksiLower, 'verifikasi')) {
                                            $badgeColor = 'bg-blue-50 text-blue-600 border-blue-100';
                                        } elseif (str_contains($aksiLower, 'delete') || str_contains($aksiLower, 'reject')) {
                                            $badgeColor = 'bg-red-50 text-red-600 border-red-100';
                                        } elseif (str_contains($aksiLower, 'export')) {
                                            $badgeColor = 'bg-amber-50 text-amber-600 border-amber-100';
                                        }
                                    @endphp

                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-[11px] font-bold border {{ $badgeColor }} uppercase tracking-wider">
                                        {{ $log->aksi }}
                                    </span>
                                </td>

                                <td class="py-4 px-4 text-slate-600 text-sm">
                                    {{ $log->deskripsi }}
                                </td>

                                <td class="py-4 px-4 text-center text-xs font-mono text-slate-400">
                                    {{ $log->ip_address ?? '-' }}
                                </td>

                                <td class="py-4 px-6 text-center text-xs text-slate-400 font-medium">
                                    {{ $log->created_at->format('d M Y, H:i') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-sm text-slate-400 font-medium">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                        </svg>
                                        Belum ada catatan log aktivitas admin yang terekam.
                                    </div>
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
                
                <!-- Paginasi -->
                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-medium">
                    <span>Menampilkan {{ $dataLog->firstItem() ?? 0 }}-{{ $dataLog->lastItem() ?? 0 }} dari {{ $dataLog->total() }} Data Log</span>
                    <div class="flex gap-1.5">
                        <a href="{{ $dataLog->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ $dataLog->onFirstPage() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Sebelumnya</a>
                        <a href="{{ $dataLog->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ !$dataLog->hasMorePages() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Selanjutnya</a>
                    </div>
                </div>

            </div>
        </main>
    </div>

    @include('partials.footer')
</div>
@endsection