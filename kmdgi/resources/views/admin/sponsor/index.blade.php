@extends('layouts.app')

@section('title', 'Data Sponsor & Kemitraan - KMDGI 16')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

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
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Manajemen Sponsor & Mitra</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Tarik dan geser icon <svg class="w-3 h-3 inline-block mx-0.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" /></svg> untuk menyesuaikan urutan prioritas logo.</p>
                </div>
                <a href="{{ route('admin.sponsor.create') }}" class="inline-flex items-center justify-center gap-2 bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-5 rounded-xl text-sm transition-all shadow-md shadow-kmdgi-primary/10 flex-shrink-0">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Tambah Mitra
                </a>
            </div>

            <form method="GET" action="{{ route('admin.sponsor.index') }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col md:flex-row items-center gap-4 justify-between">
                <div class="relative w-full md:max-w-sm">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" /></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama perusahaan atau instansi..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200/80 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:border-kmdgi-primary/50 focus:bg-white transition-all" onchange="this.form.submit()">
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kategori:</span>
                    <select name="kategori" onchange="this.form.submit()" class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-600 focus:outline-none cursor-pointer">
                        <option value="all" {{ request('kategori') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                        <option value="Sponsor" {{ request('kategori') == 'Sponsor' ? 'selected' : '' }}>Sponsor</option>
                        <option value="Media Partner" {{ request('kategori') == 'Media Partner' ? 'selected' : '' }}>Media Partner</option>
                        <option value="Community Partner" {{ request('kategori') == 'Community Partner' ? 'selected' : '' }}>Community Partner</option>
                    </select>
                </div>
            </form>

            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-4 px-2 w-10"></th>
                                <th class="py-4 px-4 text-center w-12">No</th>
                                <th class="py-4 px-4 min-w-[250px]">Identitas Mitra</th>
                                <th class="py-4 px-4 min-w-[150px]">Klasifikasi / Kelas</th>
                                <th class="py-4 px-4 min-w-[180px]">Tautan & Status</th>
                                <th class="py-4 px-6 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-tbody" class="divide-y divide-slate-100 text-slate-700 text-[14px]">

                            @forelse($dataSponsor as $index => $item)
                            <tr data-id="{{ $item->id }}" class="hover:bg-slate-50/50 transition-colors bg-white">
                                <td class="py-4 px-2 text-center cursor-move drag-handle text-slate-300 hover:text-kmdgi-primary transition-colors">
                                    <svg class="w-5 h-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" />
                                    </svg>
                                </td>

                                <td class="py-4 px-4 text-center font-medium text-slate-400">
                                    {{ $dataSponsor->firstItem() + $index }}
                                </td>
                                
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-12 bg-white border border-slate-200 rounded-lg p-1.5 flex-shrink-0 flex items-center justify-center overflow-hidden shadow-sm">
                                            <img src="{{ asset('storage/' . $item->logo) }}" alt="Logo" class="max-w-full max-h-full object-contain">
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 block leading-tight truncate max-w-[180px]">{{ $item->nama_mitra }}</span>
                                            <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded mt-1 inline-block">{{ $item->kategori }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-4">
                                    @if($item->tier_kelas == 'Utama (Besar)')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-200 uppercase tracking-wide">🏆 {{ $item->tier_kelas }}</span>
                                    @elseif($item->tier_kelas == 'Madya (Sedang)')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-wide">🥈 {{ $item->tier_kelas }}</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-orange-50 text-orange-700 border border-orange-200 uppercase tracking-wide">🥉 {{ $item->tier_kelas }}</span>
                                    @endif
                                </td>

                                <td class="py-4 px-4 space-y-2">
                                    @if($item->link_tautan)
                                        <a href="{{ $item->link_tautan }}" target="_blank" class="flex items-center gap-1.5 text-xs text-kmdgi-primary font-medium hover:underline truncate max-w-[150px]">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" /></svg>
                                            Kunjungi Web
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Tidak ada tautan</span>
                                    @endif

                                    <div>
                                        @if($item->is_active)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tampil di Web</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-slate-400"><span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span> Disembunyikan</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.sponsor.edit', $item->id) }}" class="p-2 text-slate-400 hover:text-kmdgi-primary bg-slate-50 hover:bg-blue-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                        </a>

                                        <button type="button"
                                            onclick="openModal('kmdgi-global-modal', this)"
                                            data-title="Hapus Kemitraan?"
                                            data-message="Yakin ingin menghapus data {{ $item->nama_mitra }} dari daftar sponsor? Gambar logonya juga akan terhapus permanen."
                                            data-type="danger"
                                            data-primary-text="Hapus"
                                            data-secondary-text="Batal"
                                            data-form-id="delete-form-{{ $item->id }}"
                                            class="p-2 text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m1.74 12.5h3.682c1.154 0 2.13-.816 2.229-1.943l.894-11.89c.041-.517-.333-.966-.853-.966H17.5M8.5 4h7M10.017 1.75h3.966M3 7.5h18" /></svg>
                                        </button>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('admin.sponsor.destroy', $item->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-sm text-slate-400 font-medium">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                        Belum ada data sponsor / mitra yang ditambahkan.
                                    </div>
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-medium">
                    <span>Menampilkan {{ $dataSponsor->firstItem() ?? 0 }}-{{ $dataSponsor->lastItem() ?? 0 }} dari {{ $dataSponsor->total() }} Data</span>
                    <div class="flex gap-1.5">
                        <a href="{{ $dataSponsor->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ $dataSponsor->onFirstPage() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Sebelumnya</a>
                        <a href="{{ $dataSponsor->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ !$dataSponsor->hasMorePages() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Selanjutnya</a>
                    </div>
                </div>

            </div>
        </main>
    </div>

    @include('partials.footer')
</div>

<!-- ========================================== -->
<!-- SCRIPT DRAG & DROP SORTING AJAX            -->
<!-- ========================================== -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tbody = document.getElementById('sortable-tbody');
        
        if (tbody) {
            new Sortable(tbody, {
                handle: '.drag-handle',
                animation: 200,
                ghostClass: 'bg-blue-50/50', 
                onEnd: function (evt) {
                    const rows = Array.from(tbody.querySelectorAll('tr[data-id]'));
                    const ids = rows.map(row => row.getAttribute('data-id'));
                    const offset = {{ ($dataSponsor->currentPage() - 1) * $dataSponsor->perPage() }};
                    
                    if (typeof showGlobalLoading === 'function') showGlobalLoading();

                    fetch("{{ route('admin.sponsor.update_urutan') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ 
                            urutan: ids, 
                            offset: offset 
                        })
                    })
                    .then(response => response.json())
                    .then(data => { window.location.reload(); })
                    .catch(error => {
                        console.error('Error Sorting:', error);
                        if (typeof hideGlobalLoading === 'function') hideGlobalLoading();
                        alert("Gagal merubah urutan! Silakan coba lagi.");
                    });
                }
            });
        }
    });
</script>
@endsection