@extends('layouts.app')

@section('title', 'Member Delegasi Kampus - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">

            <!-- Tombol Kembali & Info Kampus -->
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col md:flex-row gap-6 items-start md:items-center justify-between relative overflow-hidden">
                <!-- Ornamen Latar Belakang -->
                <div class="absolute right-0 top-0 w-64 h-64 bg-gradient-to-br from-kmdgi-primary/5 to-transparent rounded-full -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
                
                <div class="flex items-center gap-5 z-10">
                    <a href="{{ route('admin.kampus.index') }}" class="w-12 h-12 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-500 rounded-2xl flex items-center justify-center transition-colors flex-shrink-0" title="Kembali ke Data Kampus">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                    </a>
                    
                    <div class="flex items-center gap-4 border-l border-slate-200 pl-5">
                        <div class="w-14 h-14 rounded-xl bg-slate-50 border border-slate-200/60 p-2 flex items-center justify-center">
                            @if($kampus->logo_institusi)
                            <img src="{{ asset('storage/' . $kampus->logo_institusi) }}" alt="Logo" class="w-full h-full object-contain">
                            @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($kampus->nama_institusi) }}&background=E1EDFF&color=126CFD" alt="Avatar" class="w-full h-full object-contain rounded-lg">
                            @endif
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900 tracking-tight leading-tight">{{ $kampus->nama_institusi }}</h2>
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                {{ $kampus->lokasi_kota }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-end z-10 w-full md:w-auto">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Member</span>
                    <span class="text-2xl font-black text-kmdgi-primary">{{ $dataUsers->total() }}</span>
                </div>
            </div>

            <!-- Form Filter & Pencarian -->
            <form method="GET" action="{{ url('/admin/kampus/' . $kampus->id . '/users') }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col sm:flex-row items-center gap-4 justify-between">
                <div class="relative w-full sm:max-w-sm">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email member..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200/80 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:border-kmdgi-primary/50 focus:bg-white transition-all" onchange="this.form.submit()">
                </div>
            </form>

            <!-- Tabel Data -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-4">Nama Member</th>
                                <th class="py-4 px-4">Kontak</th>
                                <th class="py-4 px-4">Peran Delegasi</th>
                                <th class="py-4 px-4 text-center">Status Akun</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-[14px]">

                            @forelse($dataUsers as $index => $user)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 text-center font-medium text-slate-400">{{ $dataUsers->firstItem() + $index }}</td>

                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex-shrink-0 overflow-hidden">
                                            @if($user->profile_image)
                                            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Foto" class="w-full h-full object-cover">
                                            @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=F8FAFC&color=475569" alt="Avatar" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 block leading-tight">{{ $user->name }}</span>
                                            <span class="text-[11px] text-slate-400">Terdaftar: {{ $user->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-4 space-y-1">
                                    <div class="text-sm font-medium text-slate-700 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                        </svg>
                                        {{ $user->email }}
                                    </div>
                                    @if($user->no_hp)
                                    <div class="text-[12px] text-slate-500 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.896-1.596-5.48-4.18-7.076-7.076l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                        </svg>
                                        {{ $user->no_hp }}
                                    </div>
                                    @else
                                    <div class="text-[12px] text-slate-400 italic">No HP tidak dicantumkan</div>
                                    @endif
                                </td>

                                <td class="py-4 px-4">
                                    @if($user->kategori === 'Delegasi' && $user->peran_delegasi)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100">
                                            {{ $user->peran_delegasi }}
                                        </span>
                                    @elseif($user->kategori === 'Umum')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            Peserta Umum ({{ $user->profesi ?? 'Umum' }})
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Belum diatur</span>
                                    @endif
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @if($user->role === 'super admin' || $user->role === 'admin')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-200 uppercase tracking-wide">Panitia / {{ $user->role }}</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200 uppercase tracking-wide">Peserta</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-sm text-slate-400 font-medium">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                        </svg>
                                        Belum ada member/user yang terdaftar dari institusi ini.
                                    </div>
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($dataUsers->hasPages())
                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-medium">
                    <span>Menampilkan {{ $dataUsers->firstItem() ?? 0 }}-{{ $dataUsers->lastItem() ?? 0 }} dari {{ $dataUsers->total() }} Member</span>
                    <div class="flex gap-1.5">
                        <a href="{{ $dataUsers->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ $dataUsers->onFirstPage() ? 'text-slate-400 cursor-not-allowed pointer-events-none' : 'text-slate-700 hover:border-kmdgi-primary hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Sebelumnya</a>
                        <a href="{{ $dataUsers->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ !$dataUsers->hasMorePages() ? 'text-slate-400 cursor-not-allowed pointer-events-none' : 'text-slate-700 hover:border-kmdgi-primary hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Selanjutnya</a>
                    </div>
                </div>
                @endif
            </div>

        </main>
    </div>

    @include('partials.footer')
</div>
@endsection