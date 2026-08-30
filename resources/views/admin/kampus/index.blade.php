@extends('layouts.app')

@section('title', 'Manajemen Data Kampus - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">

            <!-- Flash Message Sukses -->
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

            <!-- Pesan Error Validasi -->
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3 mb-1">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" /></svg>
                    <span class="text-sm font-bold">Terjadi Kesalahan Validasi!</span>
                </div>
                <ul class="text-xs list-disc list-inside pl-9 mt-1 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Data Kampus Delegasi</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola riwayat keanggotaan institusi dan kontak perwakilan.</p>
                </div>
                <button type="button" onclick="openKampusModal('create')" class="inline-flex items-center justify-center gap-2 bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-5 rounded-xl text-sm transition-all shadow-md shadow-kmdgi-primary/10 flex-shrink-0">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Tambah Kampus
                </button>
            </div>

            <!-- Form Filter & Pencarian -->
            <form method="GET" action="{{ route('admin.kampus.index') }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col md:flex-row items-center gap-4 justify-between">
                <div class="relative w-full md:max-w-xs">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" /></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari institusi atau kota..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200/80 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:border-kmdgi-primary/50 focus:bg-white transition-all" onchange="this.form.submit()">
                    <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Filter Status Edisi Aktif:</span>
                    <button type="submit" onclick="this.form.status.value='all'" class="text-xs font-semibold px-3 py-1.5 rounded-lg whitespace-nowrap transition-colors {{ request('status', 'all') === 'all' ? 'bg-slate-200 text-slate-800' : 'bg-slate-50 text-slate-500 hover:bg-slate-100' }}">Semua</button>
                    <button type="submit" onclick="this.form.status.value='Anggota'" class="text-xs font-semibold px-3 py-1.5 rounded-lg whitespace-nowrap transition-colors {{ request('status') === 'Anggota' ? 'bg-blue-100 text-kmdgi-primary ring-1 ring-blue-300' : 'bg-blue-50/50 text-slate-500 hover:bg-blue-50' }}">Anggota</button>
                    <button type="submit" onclick="this.form.status.value='Peninjau 1'" class="text-xs font-semibold px-3 py-1.5 rounded-lg whitespace-nowrap transition-colors {{ request('status') === 'Peninjau 1' ? 'bg-amber-100 text-amber-700 ring-1 ring-amber-300' : 'bg-amber-50/50 text-slate-500 hover:bg-amber-50' }}">Peninjau 1</button>
                    <button type="submit" onclick="this.form.status.value='Peninjau 2'" class="text-xs font-semibold px-3 py-1.5 rounded-lg whitespace-nowrap transition-colors {{ request('status') === 'Peninjau 2' ? 'bg-purple-100 text-purple-700 ring-1 ring-purple-300' : 'bg-purple-50/50 text-slate-500 hover:bg-purple-50' }}">Peninjau 2</button>
                </div>
            </form>

            <!-- Tabel Data -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-4">Logo & Institusi</th>
                                <th class="py-4 px-4">Lokasi Kota</th>
                                <th class="py-4 px-4">Kontak & Medsos</th>
                                <th class="py-4 px-4 text-center">Status Cluster (Edisi Aktif)</th>
                                <th class="py-4 px-6 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-[14px]">

                            @forelse($dataKampus as $index => $kampus)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 text-center font-medium text-slate-400">{{ $dataKampus->firstItem() + $index }}</td>
                                
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200/60 p-1.5 flex-shrink-0 flex items-center justify-center relative overflow-hidden" style="background-image: radial-gradient(#e2e8f0 1px, transparent 1px); background-size: 8px 8px;">
                                            @if($kampus->logo_institusi)
                                                <img src="{{ asset('storage/' . $kampus->logo_institusi) }}" alt="Logo" class="w-full h-full object-contain relative z-10 drop-shadow-sm">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($kampus->nama_institusi) }}&background=E1EDFF&color=126CFD" alt="Avatar" class="w-full h-full object-contain rounded-lg relative z-10">
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 block leading-tight">{{ $kampus->nama_institusi }}</span>
                                            <span class="text-[11px] text-slate-400">ID: #KMDGI-{{ str_pad($kampus->id, 2, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-4 font-medium text-slate-600">{{ $kampus->lokasi_kota }}</td>
                                
                                <td class="py-4 px-4 space-y-1.5">
                                    @if($kampus->medsos_kampus)
                                    <div class="flex items-center gap-1.5 text-xs">
                                        <span class="text-slate-400 font-semibold w-10">Kampus</span>
                                        <a href="https://instagram.com/{{ ltrim($kampus->medsos_kampus, '@') }}" target="_blank" class="text-kmdgi-primary hover:underline font-medium truncate max-w-[120px]">{{ '@' . ltrim($kampus->medsos_kampus, '@') }}</a>
                                    </div>
                                    @endif
                                    
                                    @if($kampus->ig_prodi)
                                    <div class="flex items-center gap-1.5 text-xs">
                                        <span class="text-slate-400 font-semibold w-10">Prodi</span>
                                        <a href="https://instagram.com/{{ ltrim($kampus->ig_prodi, '@') }}" target="_blank" class="text-slate-500 hover:text-kmdgi-primary hover:underline font-medium truncate max-w-[120px]">{{ '@' . ltrim($kampus->ig_prodi, '@') }}</a>
                                    </div>
                                    @endif

                                    @if($kampus->link_wa)
                                    <div class="flex items-center gap-1.5 text-xs">
                                        <span class="text-slate-400 font-semibold w-10">WAG</span>
                                        <a href="{{ $kampus->link_wa }}" target="_blank" class="text-emerald-600 hover:underline font-medium truncate max-w-[120px]">Buka Grup</a>
                                    </div>
                                    @endif

                                    @if(!$kampus->medsos_kampus && !$kampus->ig_prodi && !$kampus->link_wa)
                                        <span class="text-xs text-slate-400 italic">Belum ada kontak terdaftar</span>
                                    @endif
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @if($kampus->status_keanggotaan === 'Anggota')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-kmdgi-primary border border-blue-100">Anggota</span>
                                    @elseif($kampus->status_keanggotaan === 'Peninjau 1')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">Peninjau 1</span>
                                    @elseif($kampus->status_keanggotaan === 'Peninjau 2')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-100">Peninjau 2</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-slate-50 text-slate-400 border border-slate-200">Tidak Terdaftar</span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" onclick="openKampusModal('edit', {{ json_encode($kampus) }})" title="Edit Kampus" class="p-2 text-slate-400 hover:text-kmdgi-primary bg-slate-50 hover:bg-blue-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                        </button>

                                        <button type="button"
                                            onclick="openModal('kmdgi-global-modal', this)"
                                            data-title="Hapus Data Kampus?"
                                            data-message="Apakah Anda yakin ingin menghapus delegasi {{ $kampus->nama_institusi }}? Seluruh berkas logo akan dihapus secara permanen."
                                            data-type="danger"
                                            data-primary-text="Hapus Permanen"
                                            data-secondary-text="Batalkan"
                                            data-form-id="delete-form-{{ $kampus->id }}"
                                            title="Hapus Kampus"
                                            class="p-2 text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m1.74 12.5h3.682c1.154 0 2.13-.816 2.229-1.943l.894-11.89c.041-.517-.333-.966-.853-.966H17.5M8.5 4h7M10.017 1.75h3.966M3 7.5h18" /></svg>
                                        </button>
                                        <form id="delete-form-{{ $kampus->id }}" action="{{ route('admin.kampus.destroy', $kampus->id) }}" method="POST" class="hidden">
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
                                        <svg class="w-12 h-12 text-slate-300 mb-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" /></svg>
                                        Tidak ada data kampus delegasi yang ditemukan.
                                    </div>
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-medium">
                    <span>Menampilkan {{ $dataKampus->firstItem() ?? 0 }}-{{ $dataKampus->lastItem() ?? 0 }} dari {{ $dataKampus->total() }} Kampus</span>
                    <div class="flex gap-1.5">
                        <a href="{{ $dataKampus->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ $dataKampus->onFirstPage() ? 'text-slate-400 cursor-not-allowed pointer-events-none' : 'text-slate-700 hover:border-kmdgi-primary hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Sebelumnya</a>
                        <a href="{{ $dataKampus->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ !$dataKampus->hasMorePages() ? 'text-slate-400 cursor-not-allowed pointer-events-none' : 'text-slate-700 hover:border-kmdgi-primary hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Selanjutnya</a>
                    </div>
                </div>

            </div>
        </main>
    </div>

    @include('partials.footer')
</div>

<!-- ========================================== -->
<!-- MODAL FORM: TAMBAH & EDIT KAMPUS           -->
<!-- ========================================== -->
<div id="modal-form-kampus" class="fixed inset-0 z-[80] hidden opacity-0 transition-opacity duration-300 ease-in-out flex items-center justify-center py-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeKampusModal()"></div>
    
    <div class="relative bg-white rounded-[2rem] p-6 md:p-8 w-full max-w-[36rem] mx-4 shadow-2xl transform scale-95 transition-transform duration-300 ease-in-out box-form max-h-full overflow-y-auto custom-scrollbar flex flex-col">
        <div class="flex justify-between items-center mb-6 flex-shrink-0">
            <h3 id="modal-form-title" class="text-xl font-bold text-slate-900">Tambah Kampus Baru</h3>
            <button type="button" onclick="closeKampusModal()" class="text-slate-400 hover:text-red-500 transition-colors">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form id="kampus-form" action="{{ route('admin.kampus.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col space-y-5">
            @csrf
            <div id="method-container"></div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Logo Institusi <span class="text-red-500">*Wajib PNG</span></label>
                <div class="relative w-full h-32 border-2 border-dashed border-slate-300 hover:border-kmdgi-primary rounded-2xl bg-slate-50 flex items-center justify-center overflow-hidden group transition-colors cursor-pointer" onclick="document.getElementById('input-logo').click()">
                    <div class="absolute inset-0 z-0 opacity-40 pointer-events-none" style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 10px 10px;"></div>
                    <div id="upload-placeholder" class="flex flex-col items-center pointer-events-none relative z-10 transition-opacity">
                        <svg class="w-8 h-8 text-slate-400 mb-2 group-hover:text-kmdgi-primary transition-colors flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                        <span class="text-xs font-bold text-slate-500 group-hover:text-kmdgi-primary">Klik untuk memilih file PNG</span>
                    </div>
                    <img id="logo-preview" src="" class="hidden absolute inset-0 w-full h-full object-contain p-3 z-20 drop-shadow-md bg-white/40 backdrop-blur-[2px]" />
                </div>
                <input type="file" name="logo_institusi" id="input-logo" accept="image/png" class="hidden" onchange="previewLogo(this)">
                <div class="flex justify-between items-center mt-1.5">
                    <p class="text-[11px] text-slate-400">Max 2MB. Transparan.</p>
                    <button type="button" id="btn-remove-logo" class="hidden text-[11px] font-bold text-red-500 hover:text-red-600" onclick="removeLogoPreview()">Hapus Pilihan</button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Institusi</label>
                    <input type="text" name="nama_institusi" id="input-nama" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Lokasi Kota</label>
                    <input type="text" name="lokasi_kota" id="input-kota" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">IG Kampus <span class="text-slate-300 lowercase font-normal">(Opsional)</span></label>
                    <input type="text" name="medsos_kampus" id="input-medsos" placeholder="itb1920" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">IG Prodi <span class="text-slate-300 lowercase font-normal">(Opsional)</span></label>
                    <input type="text" name="ig_prodi" id="input-prodi" placeholder="fsrditb" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tautan Grup WhatsApp <span class="text-slate-300 lowercase font-normal">(Opsional)</span></label>
                    <input type="url" name="link_wa" id="input-wa" placeholder="https://chat.whatsapp.com/..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                </div>
            </div>

            <!-- BLOK BARU: Dinamis dari Data Edisi KMDGI -->
            <div class="pt-4 border-t border-slate-100">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-3">Status Keanggotaan Per Edisi KMDGI</label>
                
                @if($dataEdisi->isEmpty())
                    <p class="text-xs text-red-500 italic">Belum ada data Edisi KMDGI. Buat Edisi terlebih dahulu.</p>
                @else
                    <div class="space-y-3 max-h-[220px] overflow-y-auto custom-scrollbar pr-2">
                        @foreach($dataEdisi as $edisi)
                        <div class="flex items-center justify-between bg-slate-50 p-3 rounded-xl border border-slate-200">
                            <div>
                                <span class="text-sm font-bold text-slate-700">{{ $edisi->nama_edisi }}</span>
                                @if($edisi->is_active)
                                    <span class="inline-block text-[9px] font-bold bg-kmdgi-primary text-white px-2 py-0.5 rounded-full ml-1">Edisi Saat Ini</span>
                                @endif
                            </div>
                            <select name="riwayat_status[{{ $edisi->id }}]" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-600 focus:outline-none focus:border-kmdgi-primary cursor-pointer">
                                <option value="Tidak Terdaftar">-- Tidak Terdaftar --</option>
                                <option value="Anggota">Anggota</option>
                                <option value="Peninjau 1">Peninjau 1</option>
                                <option value="Peninjau 2">Peninjau 2</option>
                            </select>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="pt-4 flex gap-3 flex-shrink-0 mt-auto">
                <button type="button" onclick="closeKampusModal()" class="flex-1 bg-white border border-slate-200 text-slate-600 font-bold py-3.5 px-4 rounded-xl hover:bg-slate-50 transition-colors text-sm">Batalkan</button>
                <button type="submit" id="btn-submit-form" class="flex-1 bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-3.5 px-4 rounded-xl transition-colors text-sm shadow-sm shadow-kmdgi-primary/20">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewLogo(input) {
        const preview = document.getElementById('logo-preview');
        const placeholder = document.getElementById('upload-placeholder');
        const removeBtn = document.getElementById('btn-remove-logo');

        if (input.files && input.files[0]) {
            if (input.files[0].type !== 'image/png') {
                alert('Peringatan: File harus berformat PNG!');
                removeLogoPreview();
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('opacity-0'); 
                removeBtn.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeLogoPreview() {
        document.getElementById('input-logo').value = ""; 
        document.getElementById('logo-preview').src = "";
        document.getElementById('logo-preview').classList.add('hidden');
        document.getElementById('upload-placeholder').classList.remove('opacity-0');
        document.getElementById('btn-remove-logo').classList.add('hidden');
    }

    function openKampusModal(action, data = null) {
        const modal = document.getElementById('modal-form-kampus');
        const box = modal.querySelector('.box-form');
        const form = document.getElementById('kampus-form');
        const title = document.getElementById('modal-form-title');
        const methodContainer = document.getElementById('method-container');
        const btnSubmit = document.getElementById('btn-submit-form');

        form.reset();
        removeLogoPreview();
        
        // Reset seluruh Select Status Edisi menjadi "Tidak Terdaftar"
        document.querySelectorAll('select[name^="riwayat_status"]').forEach(sel => sel.value = 'Tidak Terdaftar');

        if (action === 'create') {
            title.innerText = 'Tambah Kampus Baru';
            form.action = "{{ route('admin.kampus.store') }}";
            methodContainer.innerHTML = ''; 
            btnSubmit.innerText = 'Tambahkan';
        } else if (action === 'edit' && data) {
            title.innerText = 'Edit Data Kampus';
            form.action = `/admin/kampus/update/${data.id}`;
            methodContainer.innerHTML = '@method("PUT")';
            btnSubmit.innerText = 'Simpan Perubahan';

            document.getElementById('input-nama').value = data.nama_institusi;
            document.getElementById('input-kota').value = data.lokasi_kota;
            
            const medsos = data.medsos_kampus ? data.medsos_kampus.replace(/^@/, '') : '';
            const igProdi = data.ig_prodi ? data.ig_prodi.replace(/^@/, '') : '';
            
            document.getElementById('input-medsos').value = medsos;
            document.getElementById('input-prodi').value = igProdi;
            document.getElementById('input-wa').value = data.link_wa || '';

            // Menangkap Data JSON dan Menyesuaikan Dropdown
            if (data.riwayat_status) {
                let riwayat = typeof data.riwayat_status === 'string' ? JSON.parse(data.riwayat_status) : data.riwayat_status;
                for (const [edisiId, status] of Object.entries(riwayat)) {
                    let sel = document.querySelector(`select[name="riwayat_status[${edisiId}]"]`);
                    if(sel) sel.value = status;
                }
            }

            if(data.logo_institusi) {
                const preview = document.getElementById('logo-preview');
                const placeholder = document.getElementById('upload-placeholder');
                
                preview.src = `/storage/${data.logo_institusi}`;
                preview.classList.remove('hidden');
                placeholder.classList.add('opacity-0');
            }
        }

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function closeKampusModal() {
        const modal = document.getElementById('modal-form-kampus');
        const box = modal.querySelector('.box-form');
        
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        box.classList.remove('scale-100');
        box.classList.add('scale-95');

        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
</style>
@endsection