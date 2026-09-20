@extends('layouts.app') {{-- Sesuaikan dengan nama layout utama Anda --}}

@section('title', 'Notifikasi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 min-h-[calc(100vh-80px)]">
    
    <!-- [BARU] Tombol Kembali -->
    <div class="mb-6">
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors duration-200">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Bagian Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Notifikasi</h1>
            <p class="text-slate-500 mt-1 text-sm">Pemberitahuan terbaru mengenai aktivitas dan status Anda.</p>
        </div>
        
        <!-- Cek apakah ada notifikasi yang belum dibaca -->
        @if(auth()->user()->unreadNotifications->count() > 0)
        <form action="{{ route('notifikasi.markAllRead') }}" method="POST">
            @csrf
            <button type="submit" class="text-sm font-semibold text-kmdgi-primary hover:text-white bg-blue-50 hover:bg-kmdgi-primary px-4 py-2.5 rounded-xl transition-all duration-200">
                Tandai semua dibaca
            </button>
        </form>
        @endif
    </div>

    <!-- Container List Notifikasi -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        
        @forelse($notifikasis as $notifikasi)
            @php
                // Mengecek apakah notifikasi sudah dibaca (read_at != null)
                $isUnread = is_null($notifikasi->read_at);
                $data = $notifikasi->data;
                
                // [BARU] Menentukan URL tujuan untuk seluruh Card
                // Jika route name di web.php Anda menggunakan 'notifikasi.read', sesuaikan pemanggilannya
                $targetUrl = isset($data['url']) 
                    ? ($isUnread ? route('notifikasi.read', $notifikasi->id) : $data['url']) 
                    : '#';
            @endphp

            <!-- [DIUBAH] Menggunakan tag <a> (block) agar seluruh Card bisa di-klik -->
            <a href="{{ $targetUrl }}" class="block p-5 border-b border-slate-100 hover:bg-slate-50 transition-colors duration-200 {{ $isUnread ? 'bg-blue-50/30' : '' }}">
                <div class="flex gap-4 items-start">
                    
                    <!-- Ikon Notifikasi -->
                    <div class="flex-shrink-0 mt-0.5">
                        @if(isset($data['type']) && $data['type'] == 'success')
                            <div class="w-10 h-10 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center text-emerald-600 shadow-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        @elseif(isset($data['type']) && $data['type'] == 'warning')
                            <div class="w-10 h-10 rounded-full bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-600 shadow-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-full bg-blue-100 border border-blue-200 flex items-center justify-center text-kmdgi-primary shadow-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        @endif
                    </div>

                    <!-- Konten Notifikasi -->
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-2">
                            <h3 class="text-sm text-slate-900 truncate {{ $isUnread ? 'font-bold' : 'font-semibold' }}">
                                {{ $data['title'] ?? 'Pemberitahuan Baru' }}
                            </h3>
                            <!-- Timestamp -->
                            <span class="text-xs font-medium text-slate-400 whitespace-nowrap">
                                {{ $notifikasi->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        <p class="text-sm text-slate-600 mt-1 leading-relaxed">
                            {{ $data['message'] ?? 'Tidak ada pesan detail.' }}
                        </p>

                        <!-- [DIUBAH] Tag <a> diubah menjadi <span> untuk menghindari error HTML "Nested Link" (link di dalam link) -->
                        @if(isset($data['url']))
                        <div class="mt-3">
                            <span class="text-sm font-semibold text-kmdgi-primary inline-flex items-center gap-1 group-hover:text-kmdgi-hover transition-colors">
                                Lihat Detail
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Indikator Belum Dibaca -->
                    @if($isUnread)
                    <div class="flex-shrink-0 flex items-center h-full pt-1">
                        <div class="w-2.5 h-2.5 bg-kmdgi-primary rounded-full shadow-sm"></div>
                    </div>
                    @endif
                    
                </div>
            </a>
        @empty
            <!-- Tampilan Jika Kosong -->
            <div class="py-16 px-6 text-center flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                    <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-900 mb-1">Belum ada notifikasi</h3>
                <p class="text-sm text-slate-500">Pemberitahuan terkait akun dan aktivitas Anda akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifikasis->hasPages())
    <div class="mt-6">
        {{ $notifikasis->links() }}
    </div>
    @endif

</div>
@endsection