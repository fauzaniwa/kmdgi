@extends('layouts.app')

@section('title', 'Verifikasi Tiket Acara - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8 font-sans">
        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full">

            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)]">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Verifikasi Tiket Acara</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar pembayaran tiket Seminar/Workshop yang <span class="font-bold text-amber-500">Menunggu Konfirmasi</span>.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.verifikasi.event.index') }}" class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-sm space-y-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="relative flex-grow">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" /></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik Kode Tiket atau Nama Peserta..." class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:border-[#1A68FF]/50" onchange="this.form.submit()">
                    </div>
                    <div class="w-full md:w-56 flex-shrink-0">
                        <select name="sort" onchange="this.form.submit()" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 font-semibold focus:outline-none cursor-pointer">
                            <option value="terbaru" {{ request('sort') == 'terbaru' || !request('sort') ? 'selected' : '' }}>⏱️ Terbaru Masuk</option>
                            <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>⏱️ Terlama (Prioritas)</option>
                        </select>
                    </div>
                </div>

                <hr class="border-slate-100">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Pilih Acara</label>
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
                            <option value="all">Semua Jenis</option>
                            <option value="Seminar" {{ request('jenis') == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                            <option value="Workshop" {{ request('jenis') == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                        </select>
                    </div>
                </div>
            </form>

            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 min-w-[200px]">Peserta & Acara</th>
                                <th class="py-4 px-4 min-w-[180px]">Waktu Pengajuan</th>
                                <th class="py-4 px-4 text-center">Bukti Transfer</th>
                                <th class="py-4 px-6 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-[14px]">
                            @forelse($tiketEvents as $tiket)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center font-bold flex-shrink-0">
                                            {{ strtoupper(substr($tiket->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 block leading-tight truncate max-w-[200px]">{{ $tiket->user->name }}</span>
                                            <span class="text-[11px] font-semibold text-[#1A68FF] bg-blue-50 px-1.5 py-0.5 rounded uppercase mt-1 inline-block">{{ $tiket->jenis_tiket }}</span>
                                            <span class="text-[11px] text-slate-500 block truncate max-w-[200px] mt-0.5" title="{{ $tiket->event->judul }}">{{ $tiket->event->judul }}</span>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            {{ \Carbon\Carbon::parse($tiket->created_at)->translatedFormat('d M Y') }}
                                        </span>
                                        <span class="text-[11px] text-slate-400 mt-1 ml-5">{{ \Carbon\Carbon::parse($tiket->created_at)->format('H:i') }} WIB</span>
                                    </div>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    <button type="button" onclick="showBukti('{{ asset('storage/' . $tiket->bukti_pembayaran) }}', '{{ $tiket->user->name }}')" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-[11px] font-bold transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        Cek Bukti
                                    </button>
                                </td>

                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('admin.verifikasi.event.approve', $tiket->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="button" 
                                                onclick="openModal('kmdgi-global-modal', this)"
                                                data-title="Setujui Pembayaran?"
                                                data-message="Pastikan dana Rp {{ number_format($tiket->event->harga_tiket, 0, ',', '.') }} dari {{ $tiket->user->name }} sudah masuk ke rekening panitia."
                                                data-type="success"
                                                data-primary-text="Ya, Aktifkan Tiket"
                                                data-secondary-text="Batal"
                                                data-form-id="approve-form-{{ $tiket->id }}"
                                                class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition-colors">
                                                Terima
                                            </button>
                                        </form>
                                        <form id="approve-form-{{ $tiket->id }}" action="{{ route('admin.verifikasi.event.approve', $tiket->id) }}" method="POST" class="hidden">
                                            @csrf
                                        </form>

                                        <form action="{{ route('admin.verifikasi.event.destroy', $tiket->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                onclick="openModal('kmdgi-global-modal', this)"
                                                data-title="Tolak Pengajuan?"
                                                data-message="Tiket dan bukti pembayaran akan dihapus. Peserta harus mengulangi pendaftaran."
                                                data-type="danger"
                                                data-primary-text="Tolak & Hapus"
                                                data-secondary-text="Batal"
                                                data-form-id="delete-form-{{ $tiket->id }}"
                                                class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-bold transition-colors">
                                                Tolak
                                            </button>
                                        </form>
                                        <form id="delete-form-{{ $tiket->id }}" action="{{ route('admin.verifikasi.event.destroy', $tiket->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h4 class="text-slate-800 font-bold mb-1">Semua Selesai!</h4>
                                        <p class="text-sm text-slate-500">Tidak ada tiket Acara yang menunggu verifikasi saat ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tiketEvents->hasPages())
                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-medium">
                    <span>Menampilkan {{ $tiketEvents->firstItem() ?? 0 }}-{{ $tiketEvents->lastItem() ?? 0 }} dari {{ $tiketEvents->total() }} Menunggu</span>
                    <div class="flex gap-1.5">
                        <a href="{{ $tiketEvents->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ $tiketEvents->onFirstPage() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl">Sebelumnya</a>
                        <a href="{{ $tiketEvents->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ !$tiketEvents->hasMorePages() ? 'text-slate-400 pointer-events-none' : 'text-slate-700 hover:text-kmdgi-primary' }} rounded-xl">Selanjutnya</a>
                    </div>
                </div>
                @endif
            </div>

        </main>
    </div>
    @include('partials.footer')
</div>

<!-- Modal Bukti -->
<div id="buktiModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0" onclick="closeBukti()"></div>
    <div class="relative bg-white rounded-[2rem] overflow-hidden w-full max-w-lg shadow-2xl transform scale-95 transition-transform duration-300" id="buktiModalContent">
        <div class="bg-white border-b border-slate-100 px-6 py-5 flex justify-between items-center relative z-10">
            <div>
                <h3 class="font-bold text-slate-900 text-lg">Bukti Transfer</h3>
                <p class="text-xs text-slate-500 mt-0.5">Peserta: <span id="buktiNama" class="font-semibold text-kmdgi-primary"></span></p>
            </div>
            <button type="button" onclick="closeBukti()" class="text-slate-400 hover:text-red-500 bg-slate-50 p-2 rounded-full"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </div>
        <div class="p-6 bg-slate-50 flex justify-center items-center min-h-[300px]">
            <img id="buktiImg" src="" class="max-w-full max-h-[60vh] object-contain rounded-xl border border-slate-200">
        </div>
    </div>
</div>

<script>
    function showBukti(url, nama) {
        document.getElementById('buktiImg').src = url;
        document.getElementById('buktiNama').innerText = nama;
        const modal = document.getElementById('buktiModal');
        const content = document.getElementById('buktiModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); content.classList.remove('scale-95'); }, 10);
    }
    function closeBukti() {
        const modal = document.getElementById('buktiModal');
        const content = document.getElementById('buktiModalContent');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); document.getElementById('buktiImg').src = ''; }, 300);
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
</style>
@endsection