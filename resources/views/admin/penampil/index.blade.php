@extends('layouts.app')

@section('title', 'Data Penampil - KMDGI 16')

@section('content')
<!-- Sortable JS CDN -->
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
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Manajemen Penampil & Acara</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Tarik dan geser (drag & drop) icon <svg class="w-3 h-3 inline-block mx-0.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" /></svg> di sebelah kiri tabel untuk menyesuaikan urutan.</p>
                </div>
                <a href="{{ route('admin.penampil.create') }}" class="inline-flex items-center justify-center gap-2 bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-5 rounded-xl text-sm transition-all shadow-md shadow-kmdgi-primary/10 flex-shrink-0">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                    Tambah Penampil
                </a>
            </div>

            <!-- Form Pencarian tetap ada... -->
            <form method="GET" action="{{ route('admin.penampil.index') }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col md:flex-row items-center gap-4 justify-between">
                <div class="relative w-full md:max-w-sm">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" /></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama penampil atau lokasi..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200/80 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:border-kmdgi-primary/50 focus:bg-white transition-all" onchange="this.form.submit()">
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kategori:</span>
                    <select name="kategori_penampil" onchange="this.form.submit()" class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-600 focus:outline-none cursor-pointer">
                        <option value="all" {{ request('kategori_penampil') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                        <option value="Band/Musisi" {{ request('kategori_penampil') == 'Band/Musisi' ? 'selected' : '' }}>Band/Musisi</option>
                        <option value="Guest Speaker" {{ request('kategori_penampil') == 'Guest Speaker' ? 'selected' : '' }}>Guest Speaker</option>
                        <option value="Seni & Tari" {{ request('kategori_penampil') == 'Seni & Tari' ? 'selected' : '' }}>Seni & Tari</option>
                        <option value="Workshop" {{ request('kategori_penampil') == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                    </select>
                </div>
            </form>

            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-4 px-2 w-10"></th> <!-- Kolom Ikon Drag -->
                                <th class="py-4 px-4 text-center w-12">No</th>
                                <th class="py-4 px-4 min-w-[220px]">Profil Penampil</th>
                                <th class="py-4 px-4 min-w-[180px]">Jadwal & Lokasi</th>
                                <th class="py-4 px-4 min-w-[180px]">Tiket & Akses</th>
                                <th class="py-4 px-4 text-center">Status</th>
                                <th class="py-4 px-6 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <!-- Tambahkan ID untuk SortableJS -->
                        <tbody id="sortable-tbody" class="divide-y divide-slate-100 text-slate-700 text-[14px]">

                            @forelse($dataPenampil as $index => $item)
                            <!-- Sisipkan Atribut data-id untuk mendeteksi database -->
                            <tr data-id="{{ $item->id }}" class="hover:bg-slate-50/50 transition-colors bg-white">
                                <!-- Area Handle Drag and Drop -->
                                <td class="py-4 px-2 text-center cursor-move drag-handle text-slate-300 hover:text-kmdgi-primary transition-colors" title="Geser untuk mengubah urutan">
                                    <svg class="w-5 h-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" />
                                    </svg>
                                </td>

                                <td class="py-4 px-4 text-center font-medium text-slate-400">
                                    {{ $dataPenampil->firstItem() + $index }}
                                </td>
                                
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200/60 p-0.5 flex-shrink-0 flex items-center justify-center overflow-hidden">
                                            @if($item->logo_penampil)
                                                <img src="{{ asset('storage/' . $item->logo_penampil) }}" alt="Logo" class="w-full h-full object-cover rounded-[10px]">
                                            @elseif($item->cover_penampil)
                                                <img src="{{ asset('storage/' . $item->cover_penampil) }}" alt="Cover" class="w-full h-full object-cover rounded-[10px]">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($item->nama_penampil) }}&background=E1EDFF&color=126CFD&font-size=0.33" alt="Avatar" class="w-full h-full object-cover rounded-[10px]">
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 block leading-tight truncate max-w-[150px]" title="{{ $item->nama_penampil }}">{{ $item->nama_penampil }}</span>
                                            <span class="text-[11px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded mt-1 inline-block">{{ $item->kategori_penampil }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1.5 text-xs text-slate-600 font-medium">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                                            {{ \Carbon\Carbon::parse($item->tanggal_tampil)->translatedFormat('d M Y') }}
                                        </div>
                                        <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                            {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                                        </div>
                                        <div class="flex items-center gap-1.5 text-xs text-kmdgi-primary font-medium truncate max-w-[150px]" title="{{ $item->lokasi_tampil }}">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                                            {{ $item->lokasi_tampil }}
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-4">
                                    <div class="space-y-1.5">
                                        @if($item->kategori_penonton === 'Semua')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-600 border border-purple-100 uppercase">Akses Semua</span>
                                        @elseif($item->kategori_penonton === 'Delegasi')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-kmdgi-primary border border-blue-100 uppercase">Khusus Delegasi</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase">Umum</span>
                                        @endif
                                        
                                        @if($item->tipe_pendaftaran === 'Gratis')
                                            <div class="text-xs font-bold text-slate-500 mt-1">🎟️ Gratis (Free)</div>
                                        @else
                                            <div class="text-xs font-bold text-amber-600 mt-1">🎟️ Rp {{ number_format($item->harga_tiket, 0, ',', '.') }}</div>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @if($item->is_active)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wide">Publikasi</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-slate-50 text-slate-500 border border-slate-200 uppercase tracking-wide">Draft</span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.penampil.edit', $item->id) }}" class="p-2 text-slate-400 hover:text-kmdgi-primary bg-slate-50 hover:bg-blue-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                        </a>

                                        <button type="button"
                                            onclick="openModal('kmdgi-global-modal', this)"
                                            data-title="Hapus Penampil?"
                                            data-message="Yakin ingin menghapus {{ $item->nama_penampil }} dari daftar acara? Data dan gambarnya akan terhapus."
                                            data-type="danger"
                                            data-primary-text="Hapus Permanen"
                                            data-secondary-text="Batalkan"
                                            data-form-id="delete-form-{{ $item->id }}"
                                            class="p-2 text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m1.74 12.5h3.682c1.154 0 2.13-.816 2.229-1.943l.894-11.89c.041-.517-.333-.966-.853-.966H17.5M8.5 4h7M10.017 1.75h3.966M3 7.5h18" /></svg>
                                        </button>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('admin.penampil.destroy', $item->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-sm text-slate-400 font-medium">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                        Belum ada jadwal penampil / acara yang ditambahkan.
                                    </div>
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-medium">
                    <span>Menampilkan {{ $dataPenampil->firstItem() ?? 0 }}-{{ $dataPenampil->lastItem() ?? 0 }} dari {{ $dataPenampil->total() }} Data</span>
                    <div class="flex gap-1.5">
                        <a href="{{ $dataPenampil->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ $dataPenampil->onFirstPage() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Sebelumnya</a>
                        <a href="{{ $dataPenampil->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ !$dataPenampil->hasMorePages() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Selanjutnya</a>
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
                handle: '.drag-handle', // Class yang menjadi trigger tarikan
                animation: 200,
                ghostClass: 'bg-blue-50/50', // Efek visual saat di-drag
                onEnd: function (evt) {
                    // Ambil seluruh baris yang ada di layar
                    const rows = Array.from(tbody.querySelectorAll('tr[data-id]'));
                    // Ekstrak ID dari masing-masing baris sesuai urutan baru
                    const ids = rows.map(row => row.getAttribute('data-id'));
                    
                    // Kalkulasi offset halaman (Agar halaman 2 mendapat urutan 11-20, bukan 1-10)
                    const offset = {{ ($dataPenampil->currentPage() - 1) * $dataPenampil->perPage() }};
                    
                    // Nyalakan Global Loading Screen
                    if (typeof showGlobalLoading === 'function') showGlobalLoading();

                    // Kirim Urutan ke Server tanpa memuat ulang (AJAX)
                    fetch("{{ route('admin.penampil.update_urutan') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}" // Laravel Token Security
                        },
                        body: JSON.stringify({ 
                            urutan: ids, 
                            offset: offset 
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Refresh halaman secara halus agar Kolom 'No' ter-update
                        window.location.reload(); 
                    })
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