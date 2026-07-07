@extends('layouts.app')

@section('title', 'Manajemen Data Kampus - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Data Kampus Delegasi</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola informasi institusi, akun media sosial, dan status keanggotaan kluster pameran.</p>
                </div>
                <button type="button" class="inline-flex items-center justify-center gap-2 bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-5 rounded-xl text-sm transition-all shadow-md shadow-kmdgi-primary/10 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Kampus
                </button>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col md:flex-row items-center gap-4 justify-between">
                <div class="relative w-full md:max-w-xs">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602z" />
                        </svg>
                    </span>
                    <input type="text" placeholder="Cari nama institusi atau kota..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200/80 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:border-kmdgi-primary/50 focus:bg-white transition-all">
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Filter Status:</span>
                    <button class="bg-slate-100 text-slate-800 text-xs font-semibold px-3 py-1.5 rounded-lg whitespace-nowrap hover:bg-slate-200 transition-colors">Semua</button>
                    <button class="bg-blue-50 text-kmdgi-primary text-xs font-semibold px-3 py-1.5 rounded-lg whitespace-nowrap">Anggota</button>
                    <button class="bg-amber-50 text-amber-700 text-xs font-semibold px-3 py-1.5 rounded-lg whitespace-nowrap">Peninjau 1</button>
                    <button class="bg-purple-50 text-purple-700 text-xs font-semibold px-3 py-1.5 rounded-lg whitespace-nowrap">Peninjau 2</button>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-4">Logo & Institusi</th>
                                <th class="py-4 px-4">Lokasi Kota</th>
                                <th class="py-4 px-4">Media Sosial</th>
                                <th class="py-4 px-4 text-center">Status Cluster</th>
                                <th class="py-4 px-6 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-[14px]">

                            @forelse($dataKampus as $index => $kampus)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 text-center font-medium text-slate-400">
                                    {{ $dataKampus->firstItem() + $index }}
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200/60 p-1 flex-shrink-0 flex items-center justify-center">
                                            @if($kampus->logo_institusi)
                                            <img src="{{ asset('storage/' . $kampus->logo_institusi) }}" alt="Logo" class="w-full h-full object-contain rounded-md">
                                            @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($kampus->nama_institusi) }}&background=E1EDFF&color=126CFD" alt="Avatar" class="w-full h-full object-contain rounded-md">
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 block leading-tight">{{ $kampus->nama_institusi }}</span>
                                            <span class="text-[11px] text-slate-400">ID Delegasi: #KMDGI-{{ str_pad($kampus->id, 2, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    </div>

                                    <form id="delete-form-{{ $kampus->id }}" action="{{ route('admin.kampus.destroy', $kampus->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                                <td class="py-4 px-4 font-medium text-slate-600">{{ $kampus->lokasi_kota }}</td>
                                <td class="py-4 px-4 space-y-0.5">
                                    <div class="flex items-center gap-1.5 text-xs">
                                        <span class="text-slate-400 font-semibold w-12">Kampus:</span>
                                        <a href="https://instagram.com/{{ $kampus->medsos_kampus }}" target="_blank" class="text-kmdgi-primary hover:underline font-medium">{{ $kampus->medsos_kampus ?? '-' }}</a>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-xs">
                                        <span class="text-slate-400 font-semibold w-12">Prodi:</span>
                                        <a href="https://instagram.com/{{ $kampus->ig_prodi }}" target="_blank" class="text-slate-500 hover:text-kmdgi-primary hover:underline font-medium">{{ $kampus->ig_prodi ?? '-' }}</a>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if($kampus->status_keanggotaan === 'Anggota')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-kmdgi-primary border border-blue-100">Anggota</span>
                                    @elseif($kampus->status_keanggotaan === 'Peninjau 1')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">Peninjau 1</span>
                                    @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-100">Peninjau 2</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button class="p-2 text-slate-400 hover:text-kmdgi-primary bg-slate-50 hover:bg-blue-50 rounded-xl transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                            </svg>
                                        </button>

                                        <button type="button"
                                            onclick="openModal('kmdgi-global-modal', this)"
                                            data-title="Hapus Data Kampus?"
                                            data-message="Apakah Anda yakin ingin menghapus data delegasi {{ $kampus->nama_institusi }}? Tindakan ini akan menghapus seluruh data karya yang terikat pada kampus ini."
                                            data-type="danger"
                                            data-primary-text="Hapus Permanen"
                                            data-secondary-text="Batalkan"
                                            data-form-id="delete-form-{{ $kampus->id }}"
                                            class="p-2 text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-xl transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m1.74 12.5h3.682c1.154 0 2.13-.816 2.229-1.943l.894-11.89c.041-.517-.333-.966-.853-.966H17.5M8.5 4h7M10.017 1.75h3.966M3 7.5h18" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-sm text-slate-400 font-medium">
                                    Tidak ada data kampus delegasi yang ditemukan.
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-medium">
                    <span>Menampilkan 1-3 dari 24 Kampus Delegasi</span>
                    <div class="flex gap-1.5">
                        <button class="px-3 py-2 bg-white border border-slate-200 text-slate-400 rounded-xl cursor-not-allowed shadow-sm">Sebelumnya</button>
                        <button class="px-3 py-2 bg-white border border-slate-200 text-slate-700 hover:border-kmdgi-primary hover:text-kmdgi-primary rounded-xl transition-colors shadow-sm">Selanjutnya</button>
                    </div>
                </div>

            </div>
        </main>
    </div>

    @include('partials.footer')
</div>
@endsection