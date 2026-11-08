@extends('layouts.app')

@section('title', 'Manajemen Voting Booth (Most Popular Artwork) - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">

            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3.5 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 flex-shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>
            </div>
            @endif

            <!-- ================================================================= -->
            <!-- MASTER TOGGLE: RILIS SERENTAK KE HALAMAN VOTING PUBLIK            -->
            <!-- ================================================================= -->
            <div class="p-6 rounded-[2rem] border-2 transition-all {{ $config['is_voting_published'] ? 'bg-gradient-to-r from-emerald-500 to-teal-600 border-emerald-400 text-white shadow-lg shadow-emerald-500/20' : 'bg-white border-slate-200 text-slate-900 shadow-sm' }} flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-1">
                    <div class="flex items-center gap-2.5">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $config['is_voting_published'] ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-700' }}">
                            {{ $config['is_voting_published'] ? 'LIVE SERENTAK DI PUBLIK' : 'TAHAP KURASI INTERNAL' }}
                        </span>
                        <span class="text-xs font-bold {{ $config['is_voting_published'] ? 'text-emerald-100' : 'text-slate-400' }}">
                            ({{ $totalTerverifikasi }} Terverifikasi • {{ $totalMenunggu }} Menunggu Kurasi)
                        </span>
                    </div>
                    <h3 class="text-lg md:text-xl font-black tracking-tight">
                        {{ $config['is_voting_published'] ? 'Halaman Voting Booth Sedang Ditampilkan ke Publik' : 'Siap Meluncurkan Seluruh Karya ke Halaman Voting?' }}
                    </h3>
                    <p class="text-xs {{ $config['is_voting_published'] ? 'text-emerald-50' : 'text-slate-500' }} max-w-2xl leading-relaxed">
                        Pastikan seluruh karya booth delegasi telah selesai diverifikasi. Saat tombol ini diaktifkan, seluruh karya yang berstatus <strong>Terverifikasi</strong> akan langsung muncul serentak dan dapat diakses publik melalui QR Code maupun halaman galeri voting.
                    </p>
                </div>

                <div class="flex items-center gap-3 flex-shrink-0">
                    <a href="{{ route('voting.booth.index') }}" target="_blank" class="px-4 py-3 rounded-xl text-xs font-bold border transition-colors {{ $config['is_voting_published'] ? 'border-white/30 bg-white/10 hover:bg-white/20 text-white' : 'border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700' }}">
                        Lihat Halaman Publik ↗
                    </a>

                    <form action="{{ route('admin.booth.toggle_publish') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-md {{ $config['is_voting_published'] ? 'bg-white text-emerald-700 hover:bg-red-50 hover:text-red-600' : 'bg-[#126CFD] hover:bg-blue-700 text-white shadow-blue-500/20' }}">
                            {{ $config['is_voting_published'] ? 'Sembunyikan dari Publik' : '🚀 Tampilkan Serentak Sekarang' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Header & Statistik -->
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">Klasemen & Kurasi Booth Delegasi</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Karya yang diubah menjadi "Terverifikasi" akan otomatis terkunci dari pengeditan oleh delegasi.</p>
                </div>

                <div class="grid grid-cols-3 gap-3 flex-shrink-0">
                    <div class="bg-slate-50 border border-slate-200/70 px-4 py-3 rounded-2xl text-center">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Booth Masuk</span>
                        <span class="text-lg font-black text-slate-800">{{ $totalSubmisi }} <span class="text-xs font-semibold text-slate-400">/ {{ $totalKampus }}</span></span>
                    </div>
                    <div class="bg-blue-50/70 border border-blue-100 px-4 py-3 rounded-2xl text-center">
                        <span class="block text-[10px] font-bold text-blue-500 uppercase">Total Suara</span>
                        <span class="text-lg font-black text-[#126CFD]">{{ number_format($totalSuaraMasuk) }}</span>
                    </div>
                    <div class="bg-amber-50/70 border border-amber-100 px-4 py-3 rounded-2xl text-center">
                        <span class="block text-[10px] font-bold text-amber-600 uppercase">Awarding</span>
                        <span class="text-xs font-black text-amber-700 block mt-1">{{ $config['waktu_awarding']->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Panel Pengaturan Jadwal Rilis & Batas Akhir Voting -->
            <form action="{{ route('admin.booth.config') }}" method="POST" class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] space-y-4">
                @csrf
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Pengaturan Durasi Upload & Voting</h3>
                        <p class="text-xs text-slate-400">Pengunjung hanya dapat memberikan suara di dalam rentang waktu buka dan batas akhir di bawah ini.</p>
                    </div>
                    <label class="inline-flex items-center gap-2 cursor-pointer bg-red-50/80 border border-red-200/80 px-3.5 py-2 rounded-xl">
                        <input type="checkbox" name="is_manual_closed" value="1" {{ $config['is_manual_closed'] ? 'checked' : '' }} class="rounded text-red-600 focus:ring-red-500">
                        <span class="text-xs font-bold text-red-600">Kunci / Hentikan Paksa Voting</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jadwal Rilis / Buka</label>
                        <input type="datetime-local" name="waktu_buka" value="{{ $config['waktu_buka']->format('Y-m-d\TH:i') }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-[#126CFD]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Batas Akhir Voting (Tutup)</label>
                        <input type="datetime-local" name="waktu_tutup" value="{{ $config['waktu_tutup']->format('Y-m-d\TH:i') }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-[#126CFD]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jadwal Pengumuman Awarding</label>
                        <input type="datetime-local" name="waktu_awarding" value="{{ $config['waktu_awarding']->format('Y-m-d\TH:i') }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-[#126CFD]">
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-[#126CFD] hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs transition-colors shadow-sm">
                            Simpan Jadwal
                        </button>
                    </div>
                </div>
            </form>

            <!-- Baris Filter, Pencarian, Urutan & Export Excel -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex flex-col lg:flex-row items-center justify-between gap-4">
                <form method="GET" action="{{ route('admin.booth.index') }}" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <div class="relative w-full sm:w-60">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" /></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kampus / judul..." onchange="this.form.submit()" class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-[#126CFD]">
                    </div>

                    <select name="status" onchange="this.form.submit()" class="px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 focus:outline-none cursor-pointer">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="Terverifikasi" {{ request('status') == 'Terverifikasi' ? 'selected' : '' }}>Terverifikasi (Dikunci)</option>
                        <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu Kurasi</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak / Revisi</option>
                        <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Masih Draft</option>
                    </select>

                    <select name="sort" onchange="this.form.submit()" class="px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 focus:outline-none cursor-pointer">
                        <option value="terbanyak" {{ $sort == 'terbanyak' ? 'selected' : '' }}>Urutan: Suara Terbanyak</option>
                        <option value="terbaru" {{ $sort == 'terbaru' ? 'selected' : '' }}>Urutan: Upload Terbaru</option>
                        <option value="terlama" {{ $sort == 'terlama' ? 'selected' : '' }}>Urutan: Upload Tercepat</option>
                    </select>

                    <select name="per_page" onchange="this.form.submit()" class="px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 focus:outline-none cursor-pointer">
                        <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 Data</option>
                        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 Data</option>
                        <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100 Data</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua Data</option>
                    </select>
                </form>

                <a href="{{ request()->fullUrlWithQuery(['export' => 'true']) }}" class="w-full lg:w-auto inline-flex items-center justify-center gap-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white border border-emerald-200 px-4 py-2.5 rounded-xl text-xs font-bold transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export Rekap (.XLS)
                </a>
            </div>

            <!-- Tabel Klasemen & Data Booth -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-400 font-bold text-[11px] uppercase tracking-wider">
                                <th class="py-4 px-5 text-center w-20">Rank</th>
                                <th class="py-4 px-4">Perguruan Tinggi & Karya</th>
                                <th class="py-4 px-4 text-center">Dokumentasi (3 Foto)</th>
                                <th class="py-4 px-4 text-center">Waktu Upload</th>
                                <th class="py-4 px-4 text-center">Perolehan Vote</th>
                                <th class="py-4 px-4 text-center">Status Kurasi</th>
                                <th class="py-4 px-5 text-center w-44">Aksi & QR A4</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-sm">

                            @forelse($dataBooth as $index => $item)
                            @php
                                $rankNumber = $dataBooth->firstItem() + $index;
                                $logoKampus = $kampusLogos[$item->institusi] ?? null;                                
                                $publicVoteUrl = route('voting.booth.show', \Illuminate\Support\Str::slug($item->institusi));
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-4 px-5 text-center">
                                    @if($sort === 'terbanyak' && !$item->status_draft)
                                        @if($rankNumber === 1)
                                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-amber-100 text-amber-700 font-black text-sm border border-amber-300 shadow-sm">🏆 #1</span>
                                        @elseif($rankNumber === 2)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-200 text-slate-700 font-black text-xs border border-slate-300">#2</span>
                                        @elseif($rankNumber === 3)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-orange-100 text-orange-700 font-black text-xs border border-orange-200">#3</span>
                                        @else
                                            <span class="font-bold text-slate-400 text-xs">#{{ $rankNumber }}</span>
                                        @endif
                                    @else
                                        <span class="font-bold text-slate-400 text-xs">{{ $rankNumber }}</span>
                                    @endif
                                </td>

                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-xl bg-slate-100 border border-slate-200 p-1.5 flex-shrink-0 flex items-center justify-center overflow-hidden">
                                            @if($logoKampus)
                                                <img src="{{ asset('storage/' . $logoKampus) }}" class="w-full h-full object-contain">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($item->institusi) }}&background=E1EDFF&color=126CFD" class="w-full h-full object-contain rounded-lg">
                                            @endif
                                        </div>
                                        <div>
                                            <span class="text-[11px] font-extrabold text-[#126CFD] uppercase tracking-wider block">{{ $item->institusi }}</span>
                                            <span class="font-bold text-slate-900 block leading-snug">{{ $item->judul_karya_tematik ?? '(Belum ada judul)' }}</span>
                                            @if($item->kreator_karya)
                                                <span class="text-[11px] text-slate-400 line-clamp-1 block mt-0.5">Tim: {{ $item->kreator_karya }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-4">
                                    <div class="flex items-center justify-center gap-1.5">
                                        @foreach(['foto_utama' => 'Utama', 'foto_pendukung_1' => 'Foto 1', 'foto_pendukung_2' => 'Foto 2'] as $col => $label)
                                            @if($item->$col)
                                                <img src="{{ asset('storage/' . $item->$col) }}" alt="{{ $label }}" title="{{ $label }}" class="w-10 h-10 rounded-lg object-cover border border-slate-200 shadow-sm">
                                            @else
                                                <div class="w-10 h-10 rounded-lg bg-slate-100 border border-dashed border-slate-300 flex items-center justify-center text-[9px] text-slate-400 font-bold">Kosong</div>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    <span class="text-xs font-bold text-slate-700 block">{{ $item->updated_at->format('d M Y') }}</span>
                                    <span class="text-[11px] text-slate-400">{{ $item->updated_at->format('H:i:s') }} WIB</span>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    <div class="inline-flex items-center gap-1.5 bg-blue-50/90 border border-blue-100 px-3.5 py-1.5 rounded-xl">
                                        <span class="text-base font-black text-[#126CFD]">{{ number_format($item->votes_count) }}</span>
                                        <span class="text-[10px] font-bold text-slate-500 uppercase">Suara</span>
                                    </div>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @if($item->status_draft)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200">Masih Draft</span>
                                    @else
                                        <form action="{{ route('admin.booth.status', $item->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PUT')
                                            <select name="status_verifikasi" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl text-xs font-bold border cursor-pointer focus:outline-none {{ $item->status_verifikasi === 'Terverifikasi' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($item->status_verifikasi === 'Ditolak' ? 'bg-red-50 text-red-600 border-red-200' : 'bg-amber-50 text-amber-700 border-amber-200') }}">
                                                <option value="Terverifikasi" {{ $item->status_verifikasi === 'Terverifikasi' ? 'selected' : '' }}>🔒 Terverifikasi (Siap)</option>
                                                <option value="Menunggu" {{ $item->status_verifikasi === 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu (Bisa Edit)</option>
                                                <option value="Ditolak" {{ $item->status_verifikasi === 'Ditolak' ? 'selected' : '' }}>❌ Ditolak / Revisi</option>
                                            </select>
                                        </form>
                                    @endif
                                </td>

                                <td class="py-4 px-5 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Tombol Cetak / Download Poster QR A4 -->
                                        <a href="{{ route('admin.booth.qr_poster', $item->id) }}"
                                           target="_blank"
                                           title="Download / Cetak Poster QR A4"
                                           class="p-2 text-emerald-600 hover:text-white bg-emerald-50 hover:bg-emerald-500 border border-emerald-200 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
                                            </svg>
                                        </a>

                                        <!-- Detail Modal -->
                                        <button type="button"
                                                onclick="openDetailBoothModal(this)"
                                                data-institusi="{{ $item->institusi }}"
                                                data-judul="{{ $item->judul_karya_tematik }}"
                                                data-kreator="{{ $item->kreator_karya }}"
                                                data-deskripsi="{{ $item->deskripsi_singkat }}"
                                                data-votes="{{ $item->votes_count }}"
                                                data-waktu="{{ $item->updated_at->format('d M Y, H:i') }} WIB"
                                                data-foto-utama="{{ $item->foto_utama ? asset('storage/' . $item->foto_utama) : '' }}"
                                                data-foto-1="{{ $item->foto_pendukung_1 ? asset('storage/' . $item->foto_pendukung_1) : '' }}"
                                                data-foto-2="{{ $item->foto_pendukung_2 ? asset('storage/' . $item->foto_pendukung_2) : '' }}"
                                                data-poster-url="{{ route('admin.booth.qr_poster', $item->id) }}"
                                                title="Lihat Detail Foto & Deskripsi"
                                                class="p-2 text-slate-500 hover:text-[#126CFD] bg-slate-50 hover:bg-blue-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </button>

                                        <!-- Reset Suara -->
                                        <form action="{{ route('admin.booth.reset_votes', $item->id) }}" method="POST" onsubmit="return confirm('Reset seluruh suara pada booth {{ $item->institusi }} menjadi 0?')">
                                            @csrf
                                            <button type="submit" title="Reset Suara (0)" class="p-2 text-slate-400 hover:text-amber-600 bg-slate-50 hover:bg-amber-50 rounded-xl transition-colors">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                                </svg>
                                            </button>
                                        </form>

                                        <!-- Hapus -->
                                        <form action="{{ route('admin.booth.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus permanen data booth {{ $item->institusi }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Permanen" class="p-2 text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-xl transition-colors">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m1.74 12.5h3.682c1.154 0 2.13-.816 2.229-1.943l.894-11.89c.041-.517-.333-.966-.853-.966H17.5M8.5 4h7M10.017 1.75h3.966M3 7.5h18" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-sm text-slate-400 font-medium">
                                    Belum ada data booth delegasi yang diunggah.
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-medium">
                    <span>Menampilkan {{ $dataBooth->firstItem() ?? 0 }}-{{ $dataBooth->lastItem() ?? 0 }} dari {{ $dataBooth->total() }} Booth</span>
                    @if($dataBooth->hasPages())
                    <div class="flex gap-1.5">
                        <a href="{{ $dataBooth->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ $dataBooth->onFirstPage() ? 'text-slate-300 pointer-events-none' : 'text-slate-700 hover:border-[#126CFD]' }} rounded-xl">Sebelumnya</a>
                        <a href="{{ $dataBooth->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ !$dataBooth->hasMorePages() ? 'text-slate-300 pointer-events-none' : 'text-slate-700 hover:border-[#126CFD]' }} rounded-xl">Selanjutnya</a>
                    </div>
                    @endif
                </div>
            </div>

        </main>
    </div>

    @include('partials.footer')
</div>

<!-- MODAL DETAIL BOOTH -->
<div id="modal-detail-booth" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="relative bg-white rounded-[2rem] p-6 md:p-8 w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl space-y-6">
        <div class="flex items-start justify-between border-b border-slate-100 pb-4">
            <div>
                <span id="detail-institusi" class="text-xs font-black text-[#126CFD] uppercase tracking-wider block"></span>
                <h3 id="detail-judul" class="text-xl font-black text-slate-900 mt-0.5"></h3>
                <p id="detail-kreator" class="text-xs font-bold text-slate-600 mt-1"></p>
                <p id="detail-waktu" class="text-xs text-slate-400 mt-0.5"></p>
            </div>
            <button type="button" onclick="closeDetailBoothModal()" class="text-slate-400 hover:text-red-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <span class="text-[11px] font-bold text-slate-500 uppercase block mb-1.5">A. Foto Utama (Tampak Depan)</span>
                <img id="detail-foto-utama" src="" class="w-full aspect-[4/3] object-cover rounded-xl border border-slate-200 bg-slate-100">
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-500 uppercase block mb-1.5">B. Foto Pendukung 1</span>
                <img id="detail-foto-1" src="" class="w-full aspect-[4/3] object-cover rounded-xl border border-slate-200 bg-slate-100">
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-500 uppercase block mb-1.5">C. Foto Pendukung 2</span>
                <img id="detail-foto-2" src="" class="w-full aspect-[4/3] object-cover rounded-xl border border-slate-200 bg-slate-100">
            </div>
        </div>

        <div class="space-y-2 pt-2 border-t border-slate-100">
            <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider">Deskripsi Singkat Booth (Tema HAYUK)</h4>
            <p id="detail-deskripsi" class="text-xs text-slate-600 leading-relaxed whitespace-pre-line bg-slate-50 p-4 rounded-xl border border-slate-100"></p>
        </div>

        <div class="flex justify-end pt-2">
            <a id="detail-poster-link" href="#" target="_blank" class="inline-flex items-center gap-2 bg-[#126CFD] hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl text-xs shadow-md">
                🖨️ Buka & Download Poster QR (Ukuran A4)
            </a>
        </div>
    </div>
</div>

<script>
    function openDetailBoothModal(btn) {
        document.getElementById('detail-institusi').innerText = btn.getAttribute('data-institusi');
        document.getElementById('detail-judul').innerText = '"' + (btn.getAttribute('data-judul') || '-') + '"';
        document.getElementById('detail-kreator').innerText = 'Kreator / Tim: ' + (btn.getAttribute('data-kreator') || '-');
        document.getElementById('detail-waktu').innerText = 'Diunggah pada: ' + btn.getAttribute('data-waktu') + ' • Total Suara: ' + btn.getAttribute('data-votes');
        document.getElementById('detail-deskripsi').innerText = btn.getAttribute('data-deskripsi') || '-';

        document.getElementById('detail-foto-utama').src = btn.getAttribute('data-foto-utama') || '';
        document.getElementById('detail-foto-1').src = btn.getAttribute('data-foto-1') || '';
        document.getElementById('detail-foto-2').src = btn.getAttribute('data-foto-2') || '';
        document.getElementById('detail-poster-link').href = btn.getAttribute('data-poster-url');

        const modal = document.getElementById('modal-detail-booth');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDetailBoothModal() {
        const modal = document.getElementById('modal-detail-booth');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection