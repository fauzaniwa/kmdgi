@extends('layouts.app')
@section('title', 'Data Peserta Lomba - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">
    @include('partials.navbar')
    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">
        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl flex items-center justify-between shadow-sm">
                <span class="text-sm font-semibold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    {{ session('success') }}
                </span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 transition-colors">✖</button>
            </div>
            @endif

            <!-- HEADER & TOMBOL MODAL EXPORT -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5 bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Data Peserta Lomba</h2>
                    <p class="text-sm text-slate-500 mt-1">Kelola verifikasi pembayaran dan tinjau submisi karya peserta lomba.</p>
                </div>

                <button type="button" onclick="openExportModal()" class="w-full lg:w-auto inline-flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 px-6 rounded-xl text-sm transition-all shadow-md shadow-emerald-500/20 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    Export & Unduh Data
                </button>
            </div>

            <!-- FILTER SECTION -->
            <form method="GET" action="{{ route('admin.peserta_lomba.index') }}" class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center">
                <div class="relative w-full md:max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama tim, kreator, atau institusi..." class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary transition-all">
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <select name="lomba_id" onchange="this.form.submit()" class="px-4 py-3 bg-blue-50 border border-blue-100 rounded-xl text-sm font-bold text-kmdgi-primary focus:outline-none cursor-pointer w-full sm:w-auto">
                        @foreach($semuaLomba as $l)
                        <option value="{{ $l->id }}" {{ $lombaId == $l->id ? 'selected' : '' }}>{{ \Illuminate\Support\Str::limit($l->judul, 30) }}</option>
                        @endforeach
                    </select>

                    <select name="status_bayar" onchange="this.form.submit()" class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 focus:outline-none cursor-pointer w-full sm:w-auto">
                        <option value="all" {{ request('status_bayar') == 'all' ? 'selected' : '' }}>Filter Pembayaran</option>
                        <option value="Menunggu Validasi" {{ request('status_bayar') == 'Menunggu Validasi' ? 'selected' : '' }}>🕒 Menunggu Validasi</option>
                        <option value="Lunas" {{ request('status_bayar') == 'Lunas' ? 'selected' : '' }}>✅ Lunas (Terima)</option>
                        <option value="Gratis" {{ request('status_bayar') == 'Gratis' ? 'selected' : '' }}>🆓 Gratis</option>
                        <option value="Ditolak" {{ request('status_bayar') == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                    </select>
                </div>
            </form>

            <!-- TABEL DATA -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[900px]">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-5 px-6 min-w-[250px]">Profil Peserta / Tim</th>
                                <th class="py-5 px-4 min-w-[200px]">Status & Validasi</th>
                                <th class="py-5 px-4 min-w-[280px]">Submisi Karya</th>
                                <th class="py-5 px-6 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-[14px]">
                            @forelse($dataPeserta as $peserta)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="py-5 px-6">
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-100 to-indigo-50 border border-blue-200 flex-shrink-0 flex items-center justify-center text-kmdgi-primary font-black text-sm shadow-sm">
                                            {{ strtoupper(substr($peserta->nama_tim_peserta, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="font-extrabold text-slate-900 text-base block leading-tight mb-1">{{ $peserta->nama_tim_peserta }}</span>
                                            <span class="text-[12px] font-medium text-slate-500 block mb-2"><span class="text-slate-400">Institusi:</span> {{ $peserta->institusi_asal ?? '-' }}</span>

                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">{{ $peserta->kategori_pendaftar }}</span>
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $peserta->no_whatsapp) }}" target="_blank" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-500 hover:text-white transition-colors flex items-center gap-1">
                                                    WhatsApp
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-[11px] text-slate-500">Akun: <span class="font-semibold text-slate-700">{{ $peserta->user->name }}</span> ({{ $peserta->user->email }})</span>
                                    </div>
                                </td>

                                <td class="py-5 px-4 align-top">
                                    <div class="bg-white border {{ $peserta->status_pembayaran === 'Menunggu Validasi' ? 'border-amber-200 shadow-sm' : 'border-slate-100' }} rounded-xl p-3">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Status Pembayaran</p>
                                        @if($peserta->status_pembayaran === 'Lunas' || $peserta->status_pembayaran === 'Gratis')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>{{ $peserta->status_pembayaran }}</span>
                                        @elseif($peserta->status_pembayaran === 'Menunggu Validasi')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-600 animate-pulse"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Menunggu Validasi</span>
                                        @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-600"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>{{ $peserta->status_pembayaran }}</span>
                                        @endif

                                        @if($peserta->bukti_pembayaran)
                                        <a href="{{ asset('storage/'.$peserta->bukti_pembayaran) }}" target="_blank" class="mt-3 flex items-center justify-center gap-2 w-full px-3 py-2 bg-slate-50 hover:bg-blue-50 text-blue-600 hover:text-blue-700 rounded-lg text-[11px] font-bold transition-colors border border-slate-200">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                            </svg>
                                            Lihat Bukti Transfer
                                        </a>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-5 px-4 align-top">
                                    @if($peserta->status_karya === 'Terkirim')
                                    <div class="bg-blue-50/50 border border-blue-100 p-4 rounded-xl">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-kmdgi-primary text-white">Status: Terkirim</span>
                                        </div>
                                        <p class="font-bold text-slate-800 text-sm leading-tight mb-1">{{ $peserta->judul_karya ?? 'Tanpa Judul' }}</p>
                                        <p class="text-[11px] font-medium text-slate-500 mb-2">Kreator: <span class="text-slate-700">{{ $peserta->kreator_karya ?? '-' }}</span></p>

                                        @if($peserta->deskripsi_karya)
                                        <p class="text-[11px] text-slate-500 line-clamp-2 italic border-l-2 border-slate-300 pl-2 bg-white/60 p-1.5 rounded">{{ $peserta->deskripsi_karya }}</p>
                                        @endif

                                        <div class="mt-3 flex gap-2">
                                            @if($peserta->link_karya)
                                            <a href="{{ $peserta->link_karya }}" target="_blank" class="flex-1 flex items-center justify-center gap-1.5 text-[10px] px-3 py-2 bg-white border border-slate-200 hover:border-kmdgi-primary text-slate-700 hover:text-kmdgi-primary font-bold rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                                                </svg> Link Eksternal
                                            </a>
                                            @endif
                                            @if($peserta->file_karya)
                                            <a href="{{ asset('storage/'.$peserta->file_karya) }}" download class="flex-1 flex items-center justify-center gap-1.5 text-[10px] px-3 py-2 bg-kmdgi-primary hover:bg-kmdgi-hover border border-kmdgi-primary text-white font-bold rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                </svg> Unduh File
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                    @elseif($peserta->status_karya === 'Diskualifikasi')
                                    <div class="bg-red-50 border border-red-100 p-4 rounded-xl flex items-center justify-center h-full">
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-bold bg-red-100 text-red-700">🚫 Diskualifikasi</span>
                                    </div>
                                    @else
                                    <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl flex flex-col items-center justify-center h-full text-center">
                                        <svg class="w-8 h-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" />
                                        </svg>
                                        <span class="text-xs font-bold text-slate-400">Belum Mengumpulkan</span>
                                    </div>
                                    @endif
                                </td>

                                <td class="py-5 px-6 text-center align-top">
                                    <button type="button" onclick="openValidasiModal({{ json_encode($peserta) }})" class="w-full mb-2 flex items-center justify-center gap-2 text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-800 hover:border-slate-800 hover:text-white px-3 py-2.5 rounded-xl transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                        </svg>
                                        Validasi
                                    </button>

                                    <button type="button" onclick="openModal('kmdgi-global-modal', this)" data-title="Hapus Peserta?" data-message="Yakin menghapus pendaftaran tim {{ htmlspecialchars($peserta->nama_tim_peserta) }}? Semua file yang diunggah akan dihapus." data-type="danger" data-primary-text="Hapus Permanen" data-secondary-text="Batal" data-form-id="delete-form-{{ $peserta->id }}" class="w-full flex items-center justify-center gap-2 text-xs font-bold bg-red-50 text-red-500 hover:bg-red-500 hover:text-white px-3 py-2.5 rounded-xl transition-all">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m1.74 12.5h3.682c1.154 0 2.13-.816 2.229-1.943l.894-11.89c.041-.517-.333-.966-.853-.966H17.5M8.5 4h7M10.017 1.75h3.966M3 7.5h18" />
                                        </svg>
                                        Hapus
                                    </button>
                                    <form id="delete-form-{{ $peserta->id }}" action="{{ route('admin.peserta_lomba.destroy', $peserta->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                        </svg>
                                        <p class="font-medium">Belum ada peserta yang terdaftar pada kategori/filter ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $dataPeserta->links() }}
            </div>
        </main>
    </div>
    @include('partials.footer')
</div>

<!-- MODAL PILIHAN EXPORT -->
<div id="modal-export" class="fixed inset-0 z-[80] hidden opacity-0 transition-opacity flex items-center justify-center">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeExportModal()"></div>
    <div class="relative bg-white rounded-[2rem] p-6 md:p-8 w-full max-w-[28rem] mx-4 shadow-2xl transform scale-95 transition-transform box-export">
        <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
            <h3 class="text-xl font-bold text-slate-900">Download Data Lomba</h3>
            <button type="button" onclick="closeExportModal()" class="text-slate-400 hover:text-slate-600">✖</button>
        </div>

        <div class="space-y-4">
            <!-- Ambil nilai filter saat ini secara diam-diam -->
            <input type="hidden" id="export-search" value="{{ request('search') }}">
            <input type="hidden" id="export-status-bayar" value="{{ request('status_bayar') }}">

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Pilih Kategori / Lomba</label>
                <select id="export-lomba-id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-kmdgi-primary cursor-pointer">
                    <option value="all">📁 Semua Lomba (Semua Kategori)</option>
                    @foreach($semuaLomba as $l)
                    <option value="{{ $l->id }}">{{ $l->judul }}</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1.5">Berkas yang diunduh akan otomatis tersortir sesuai Pencarian dan Filter Status Bayar yang sedang aktif saat ini.</p>
            </div>

            <div class="pt-4 flex flex-col gap-3">
                <div class="flex gap-3">
                    <button type="button" onclick="downloadExcel()" class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 rounded-xl shadow-md transition-colors text-sm">Unduh Excel</button>
                    <button type="button" onclick="downloadZip()" class="flex-1 bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-md transition-colors text-sm">Unduh ZIP Karya</button>
                </div>
                <button type="button" onclick="closeExportModal()" class="w-full bg-white border border-slate-200 text-slate-600 font-bold py-3.5 rounded-xl hover:bg-slate-50 shadow-sm text-sm">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL VALIDASI -->
<div id="modal-validasi" class="fixed inset-0 z-[80] hidden opacity-0 transition-opacity flex items-center justify-center">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeValidasiModal()"></div>
    <div class="relative bg-white rounded-[2rem] p-6 md:p-8 w-full max-w-[28rem] mx-4 shadow-2xl transform scale-95 transition-transform box-form">
        <h3 class="text-xl font-bold text-slate-900 mb-5 border-b border-slate-100 pb-3">Validasi & Status Peserta</h3>
        <form id="form-validasi" method="POST" class="space-y-5">
            @csrf

            <div class="bg-blue-50/50 border border-blue-100 p-4 rounded-xl text-center">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Mengevaluasi Tim</p>
                <h4 id="nama-tim-modal" class="text-lg font-extrabold text-kmdgi-primary leading-tight"></h4>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Status Pembayaran</label>
                <select name="status_pembayaran" id="input-status-bayar" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-kmdgi-primary cursor-pointer shadow-sm">
                    <option value="Menunggu Validasi">Menunggu Validasi</option>
                    <option value="Lunas">✅ Lunas (Diterima)</option>
                    <option value="Ditolak">❌ Ditolak (Bermasalah / Bukti Palsu)</option>
                    <option value="Gratis">🆓 Gratis</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Status Kelayakan Karya</label>
                <select name="status_karya" id="input-status-karya" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-kmdgi-primary cursor-pointer shadow-sm">
                    <option value="Belum Mengumpulkan">Belum Mengumpulkan</option>
                    <option value="Terkirim">📤 Terkirim (Sah & Memenuhi Syarat)</option>
                    <option value="Diskualifikasi">🚫 Diskualifikasi (Melanggar Aturan / Plagiat)</option>
                </select>
            </div>

            <div class="pt-5 flex gap-3">
                <button type="button" onclick="closeValidasiModal()" class="flex-1 bg-white border border-slate-200 text-slate-600 font-bold py-3.5 rounded-xl hover:bg-slate-50 shadow-sm">Batalkan</button>
                <button type="submit" class="flex-1 bg-kmdgi-primary text-white font-bold py-3.5 rounded-xl shadow-md hover:bg-kmdgi-hover">Simpan Keputusan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Tampilkan notifikasi error jika export zip gagal
    @if($errors->any())
        alert("{{ collect($errors->all())->first() }}");
    @endif

    function openExportModal() {
        const modal = document.getElementById('modal-export');
        const box = modal.querySelector('.box-export');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            box.classList.remove('scale-95');
        }, 10);
    }

    function closeExportModal() {
        const modal = document.getElementById('modal-export');
        const box = modal.querySelector('.box-export');
        modal.classList.add('opacity-0');
        box.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function openValidasiModal(data) {
        document.getElementById('form-validasi').action = `/admin/perlombaan/peserta/verifikasi/${data.id}`;
        document.getElementById('nama-tim-modal').innerText = data.nama_tim_peserta;
        document.getElementById('input-status-bayar').value = data.status_pembayaran;
        document.getElementById('input-status-karya').value = data.status_karya;

        const modal = document.getElementById('modal-validasi');
        const box = modal.querySelector('.box-form');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            box.classList.remove('scale-95');
        }, 10);
    }

    function closeValidasiModal() {
        const modal = document.getElementById('modal-validasi');
        const box = modal.querySelector('.box-form');
        modal.classList.add('opacity-0');
        box.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function buildExportUrl(baseUrl) {
        const lombaId = document.getElementById('export-lomba-id').value;
        const searchVal = document.getElementById('export-search').value;
        const statusBayarVal = document.getElementById('export-status-bayar').value;

        let exportUrl = new URL(baseUrl, window.location.origin);
        exportUrl.searchParams.append('export_lomba_id', lombaId);

        if (searchVal) exportUrl.searchParams.append('search', searchVal);
        if (statusBayarVal && statusBayarVal !== 'all') exportUrl.searchParams.append('status_bayar', statusBayarVal);

        return exportUrl.toString();
    }

    function downloadExcel() {
        closeExportModal();
        window.location.href = buildExportUrl("{{ route('admin.peserta_lomba.export') }}");
    }

    function downloadZip() {
        closeExportModal();
        window.location.href = buildExportUrl("{{ route('admin.peserta_lomba.export_zip') }}");
    }
</script>
@endsection