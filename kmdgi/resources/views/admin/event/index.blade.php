@extends('layouts.app')
@section('title', 'Manajemen Event - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">
    @include('partials.navbar')
    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">
        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <span class="text-sm font-semibold flex items-center gap-2">✅ {{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-green-500">✖</button>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Data Event & Acara</h2>
                    <p class="text-sm text-slate-500 mt-1">Kelola tiket, jadwal, dan kolaborator untuk setiap sub-acara KMDGI.</p>
                </div>
                
                <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-xl border border-slate-200">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider pl-2">Filter Edisi:</label>
                    <select onchange="window.location.href='{{ route('admin.event.index') }}?edisi_id=' + this.value" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-bold text-kmdgi-primary focus:outline-none cursor-pointer">
                        @foreach($semuaEdisi as $e)
                            <option value="{{ $e->id }}" {{ $edisiId == $e->id ? 'selected' : '' }}>{{ $e->nama_edisi }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end">
                @if($edisiId)
                <a href="{{ route('admin.event.create', ['edisi_id' => $edisiId]) }}" class="inline-flex items-center justify-center gap-2 bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-6 rounded-xl text-sm transition-all shadow-md">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg> Tambah Event Baru
                </a>
                @endif
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 font-bold text-xs uppercase tracking-wider">
                            <th class="py-4 px-6">Event & Pelaksanaan</th>
                            <th class="py-4 px-4">Tiket & Peserta</th>
                            <th class="py-4 px-4 text-center">Status</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-[14px]">
                        @forelse($dataEvent as $event)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-slate-200 rounded-lg overflow-hidden flex-shrink-0">
                                        @if($event->poster) <img src="{{ asset('storage/'.$event->poster) }}" class="w-full h-full object-cover"> @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 text-base mb-1">{{ $event->judul }}</p>
                                        <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                                            {{ $event->tanggal_pelaksanaan ? \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->format('d M Y') : '-' }} | {{ $event->jam_pelaksanaan ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="font-bold text-emerald-600 block">{{ $event->harga_tiket == 0 ? 'Gratis (Free)' : 'Rp ' . number_format($event->harga_tiket,0,',','.') }}</span>
                                <div class="flex gap-1 mt-2 flex-wrap">
                                    @if(is_array($event->kategori_peserta))
                                        @foreach($event->kategori_peserta as $kat)
                                            <span class="text-[10px] px-2 py-0.5 bg-blue-50 text-kmdgi-primary rounded border border-blue-100">{{ $kat }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($event->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600">Publik</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500">Draft</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.event.edit', $event->id) }}" class="p-2 text-slate-400 hover:text-blue-600 bg-slate-50 rounded-xl"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg></a>
                                    
                                    <button type="button" onclick="openModal('kmdgi-global-modal', this)" data-title="Hapus Event?" data-message="Yakin menghapus event {{ $event->judul }}?" data-type="danger" data-primary-text="Hapus" data-secondary-text="Batal" data-form-id="delete-form-{{ $event->id }}" class="p-2 text-slate-400 hover:text-red-500 bg-slate-50 rounded-xl"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m1.74 12.5h3.682c1.154 0 2.13-.816 2.229-1.943l.894-11.89c.041-.517-.333-.966-.853-.966H17.5M8.5 4h7M10.017 1.75h3.966M3 7.5h18" /></svg></button>
                                    <form id="delete-form-{{ $event->id }}" action="{{ route('admin.event.destroy', $event->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-12 text-center text-slate-400">Belum ada data event untuk edisi ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </main>
    </div>
    @include('partials.footer')
</div>
@endsection