@extends('layouts.app')

@section('title', 'Konten Delegasi Kampus - KMDGI 16')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8 font-sans">

        <!-- Sidebar Delegasi -->
        @include('partials.sidebar')

        <main class="flex-grow w-full pb-20 relative">
            <div class="max-w-5xl mx-auto">

                <!-- HEADER -->
                <div class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] mb-8 relative overflow-hidden">
                    <div class="absolute -right-16 -top-16 w-64 h-64 bg-gradient-to-br from-blue-50 to-indigo-50/30 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight mb-2">
                                Konten Delegasi Kampus
                            </h1>
                            <p class="text-sm text-slate-500 max-w-xl">
                                Berikut adalah daftar seluruh karya (Tematik, Simbiotik, Simbolik) yang telah dibuat atau dikirimkan oleh institusi <strong>{{ $user->institusi }}</strong> beserta status interaksi publiknya.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- GRID CARD KARYA -->
                @if($kumpulanKarya->isEmpty())
                <div class="bg-white border border-slate-200 rounded-[2rem] p-12 flex flex-col items-center justify-center text-center shadow-sm">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                        <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Karya</h3>
                    <p class="text-sm text-slate-500 max-w-sm">Delegasi Anda belum menyimpan atau mengirimkan karya apapun. Silakan submit karya pada menu di sidebar.</p>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-8">
                    @foreach($kumpulanKarya as $karya)
                    <div class="bg-white rounded-[1.5rem] border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col relative">

                        <!-- Thumbnail Area -->
                        <div class="aspect-video w-full bg-slate-100 relative overflow-hidden">
                            @if($karya->thumbnail_karya)
                            <img src="{{ asset('storage/' . $karya->thumbnail_karya) }}" alt="Thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <span class="text-xs font-bold uppercase tracking-widest">No Thumbnail</span>
                            </div>
                            @endif

                            <!-- Kategori Badge -->
                            <div class="absolute top-4 left-4 bg-white/95 backdrop-blur border border-white text-slate-800 px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-widest shadow-sm">
                                {{ ucfirst($karya->kategori_karya) }}
                            </div>

                            <!-- Status Badge -->
                            <div class="absolute top-4 right-4">
                                @if($karya->status_draft == 1)
                                <span class="inline-block bg-slate-800/90 backdrop-blur text-white px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                    DRAFT
                                </span>
                                @else
                                @php
                                $verif = $karya->status_verifikasi ?? 'Menunggu';
                                $badgeColor = match($verif) {
                                'Terverifikasi', 'Diterima' => 'bg-emerald-500/90 text-white',
                                'Revisi', 'Ditolak' => 'bg-red-500/90 text-white',
                                default => 'bg-amber-500/90 text-white'
                                };
                                @endphp
                                <span class="inline-block {{ $badgeColor }} backdrop-blur px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                    {{ $verif }}
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Detail Area -->
                        <div class="p-6 flex-grow flex flex-col">

                            <h3 class="text-lg font-black text-slate-900 leading-tight mb-1.5 line-clamp-2 group-hover:text-kmdgi-primary transition-colors">
                                {{ $karya->judul_karya ?? 'Judul Belum Diisi' }}
                            </h3>

                            <p class="text-sm text-slate-500 mb-5 line-clamp-1">
                                Kreator: <span class="font-bold text-slate-700">{{ $karya->kreator_karya ?? '-' }}</span>
                            </p>

                            <!-- ENGAGEMENT METRICS -->
                            <div class="flex items-center gap-2 mb-6 flex-wrap">
                                <!-- Views -->
                                <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-lg text-slate-600">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span class="text-xs font-bold">{{ $karya->views_count ?? 0 }}</span>
                                </div>
                                <!-- Likes -->
                                <div class="flex items-center gap-1.5 bg-rose-50 border border-rose-100 px-3 py-1.5 rounded-lg text-rose-600">
                                    <svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                                    </svg>
                                    <span class="text-xs font-bold">{{ $karya->likes_count ?? 0 }}</span>
                                </div>
                                <!-- Comments -->
                                <div class="flex items-center gap-1.5 bg-blue-50 border border-blue-100 px-3 py-1.5 rounded-lg text-blue-600">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.333c-1.114.392-2.32.59-3.555.59a2.75 2.75 0 01-2.5-2.75c0-.62.203-1.196.532-1.68C3.21 14.544 2.25 13.33 2.25 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                                    </svg>
                                    <span class="text-xs font-bold">{{ $karya->komentars_count ?? 0 }}</span>
                                </div>
                                <!-- Shares -->
                                <div class="flex items-center gap-1.5 bg-indigo-50 border border-indigo-100 px-3 py-1.5 rounded-lg text-indigo-600">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
                                    </svg>
                                    <span class="text-xs font-bold">{{ $karya->shares_count ?? 0 }}</span>
                                </div>
                            </div>

                            <!-- Spacer -->
                            <div class="flex-grow"></div>

                            <!-- Action Buttons -->
                            <div class="flex items-center gap-3">
                                <a href="{{ route('delegasi.submisi.daftar', strtolower($karya->kategori_karya)) }}" class="flex-1 text-center bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold py-3 rounded-xl text-xs transition-colors shadow-sm">
                                    Edit Data
                                </a>
                                <button onclick="openCommentModal({{ $karya->id }})" class="flex-1 text-center bg-kmdgi-primary hover:bg-blue-700 border border-kmdgi-primary text-white font-bold py-3 rounded-xl text-xs transition-colors shadow-md shadow-blue-500/20 flex items-center justify-center gap-2">
                                    Diskusi Publik
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- MODAL KOMENTAR (Spesifik per Karya)        -->
                    <!-- ========================================== -->
                    <div id="commentModal-{{ $karya->id }}" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
                        <div class="absolute inset-0" onclick="closeCommentModal({{ $karya->id }})"></div>
                        <div class="relative bg-white rounded-[2rem] overflow-hidden w-full max-w-2xl shadow-2xl transform scale-95 transition-transform duration-300 flex flex-col h-[85vh]">

                            <!-- Header Modal -->
                            <div class="bg-white border-b border-slate-100 px-6 py-5 flex justify-between items-center relative z-10">
                                <div>
                                    <h3 class="font-black text-slate-900 text-lg">Ruang Diskusi</h3>
                                    <p class="text-xs font-medium text-slate-500 mt-0.5">{{ $karya->judul_karya }}</p>
                                </div>
                                <button type="button" onclick="closeCommentModal({{ $karya->id }})" class="text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 p-2 rounded-full transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Body: Daftar Komentar -->
                            <div class="flex-grow overflow-y-auto p-6 bg-slate-50/50 space-y-6">

                                {{-- Cek jika ada komentar (asumsi sudah diload melalui eager loading) --}}
                                @if(isset($karya->komentars) && $karya->komentars->count() > 0)
                                @foreach($karya->komentars as $komentar)
                                <div class="flex gap-4">
                                    <!-- Avatar -->
                                    <div class="w-10 h-10 rounded-full bg-blue-100 text-[#1A68FF] font-bold flex items-center justify-center flex-shrink-0 text-sm">
                                        {{ strtoupper(substr($komentar->user->name ?? 'U', 0, 1)) }}
                                    </div>

                                    <div class="flex-grow">
                                        <div class="bg-white border border-slate-200 rounded-2xl rounded-tl-none p-4 shadow-sm relative group">
                                            <div class="flex justify-between items-start mb-1">
                                                <h4 class="text-sm font-bold text-slate-800">{{ $komentar->user->name ?? 'Anonim' }}</h4>
                                                <span class="text-[10px] text-slate-400 font-medium">{{ $komentar->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-[13px] text-slate-600 leading-relaxed">{{ $komentar->isi_komentar }}</p>
                                        </div>

                                        <!-- Action Komentar Utama -->
                                        <div class="flex items-center gap-4 mt-2 ml-2">
                                            <button onclick="toggleReplyForm({{ $komentar->id }})" class="text-[11px] font-bold text-slate-500 hover:text-[#1A68FF] transition-colors">Balas (Reply)</button>
                                            <button onclick="openReportModal({{ $komentar->id }})" class="text-[11px] font-bold text-slate-400 hover:text-red-500 transition-colors flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                                                </svg>
                                                Laporkan
                                            </button>
                                        </div>

                                        <!-- Form Balas (Hidden default) -->
                                        <form action="{{ route('delegasi.submisi.komentar.store') }}" method="POST" id="reply-form-{{ $komentar->id }}" class="hidden mt-3 mb-4 relative">
                                            @csrf
                                            <input type="hidden" name="submisi_karya_id" value="{{ $karya->id }}">
                                            <input type="hidden" name="parent_id" value="{{ $komentar->id }}">
                                            <textarea name="isi_komentar" rows="2" placeholder="Tulis balasan..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-[#1A68FF] pr-12 resize-none"></textarea>
                                            <button type="submit" class="absolute right-3 bottom-3 text-white bg-[#1A68FF] p-1.5 rounded-lg hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                                </svg>
                                            </button>
                                        </form>

                                        <!-- Tampilkan Balasan (Replies) -->
                                        @if($komentar->replies && $komentar->replies->count() > 0)
                                        <div class="mt-4 space-y-4 border-l-2 border-slate-100 pl-4">
                                            @foreach($komentar->replies as $reply)
                                            <div class="flex gap-3">
                                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 font-bold flex items-center justify-center flex-shrink-0 text-xs">
                                                    {{ strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div class="flex-grow">
                                                    <div class="bg-white border border-slate-200 rounded-2xl rounded-tl-none p-3 shadow-sm">
                                                        <div class="flex justify-between items-start mb-1">
                                                            <h4 class="text-xs font-bold text-slate-800">{{ $reply->user->name ?? 'Anonim' }}</h4>
                                                            <span class="text-[9px] text-slate-400 font-medium">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-xs text-slate-600 leading-relaxed">{{ $reply->isi_komentar }}</p>
                                                    </div>
                                                    <div class="flex items-center gap-4 mt-1.5 ml-2">
                                                        <button onclick="openReportModal({{ $reply->id }})" class="text-[10px] font-bold text-slate-400 hover:text-red-500 transition-colors flex items-center gap-1">
                                                            Laporkan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <!-- State Kosong Komentar -->
                                <div class="flex flex-col items-center justify-center h-full text-center opacity-70">
                                    <svg class="w-16 h-16 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                    </svg>
                                    <p class="text-slate-500 text-sm font-medium">Belum ada komentar publik pada karya ini.</p>
                                </div>
                                @endif
                            </div>

                            <!-- Footer: Form Tulis Komentar Utama -->
                            <div class="p-5 bg-white border-t border-slate-100 flex-shrink-0">
                                <form action="{{ route('delegasi.submisi.komentar.store') }}" method="POST" class="relative">
                                    @csrf
                                    <input type="hidden" name="submisi_karya_id" value="{{ $karya->id }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 flex-shrink-0 flex items-center justify-center overflow-hidden">
                                            <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                        <textarea name="isi_komentar" rows="1" placeholder="Tambahkan komentar publik..." class="w-full bg-slate-50 border border-slate-200 rounded-full px-5 py-3 text-sm focus:outline-none focus:border-[#1A68FF] focus:bg-white resize-none overflow-hidden" style="min-height: 46px;" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                                        <button type="submit" class="bg-[#1A68FF] text-white p-3 rounded-full hover:bg-blue-700 transition-colors shadow-md shadow-blue-500/20 flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                            </svg>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

            </div>
        </main>
    </div>
    @include('partials.footer')
</div>

<!-- ========================================== -->
<!-- MODAL LAPORKAN KOMENTAR (Global)           -->
<!-- ========================================== -->
<div id="reportModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0" onclick="closeReportModal()"></div>
    <div class="relative bg-white rounded-[2rem] overflow-hidden w-full max-w-md shadow-2xl transform scale-95 transition-transform duration-300 flex flex-col">

        <div class="p-6 md:p-8 text-center">
            <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-red-50">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" />
                </svg>
            </div>
            <h3 class="text-xl font-black text-slate-900 mb-2">Laporkan Komentar</h3>
            <p class="text-sm text-slate-500 mb-6">Apakah komentar ini mengandung unsur SARA, spam, atau pelanggaran lainnya? Jelaskan alasannya.</p>

            <form action="{{ route('delegasi.submisi.komentar.report') }}" method="POST" id="formReportKomentar">
                @csrf
                <!-- ID Komentar yang dilaporkan di-inject via JS -->
                <input type="hidden" name="komentar_id" id="report_komentar_id">

                <textarea name="alasan" rows="3" required placeholder="Tulis alasan pelaporan..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100 resize-none mb-6"></textarea>

                <div class="flex items-center gap-3">
                    <button type="button" onclick="closeReportModal()" class="flex-1 bg-white border border-slate-200 text-slate-700 font-bold py-3 rounded-xl hover:bg-slate-50 transition-colors text-sm">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-red-600 border border-red-600 text-white font-bold py-3 rounded-xl hover:bg-red-700 transition-colors shadow-md shadow-red-500/20 text-sm">
                        Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // FUNGSI UNTUK MODAL DISKUSI / KOMENTAR
    function openCommentModal(id) {
        const modal = document.getElementById('commentModal-' + id);
        const modalInner = modal.querySelector('.transform');

        modal.classList.remove('hidden');
        // Sedikit delay agar transisi CSS berjalan lancar
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalInner.classList.remove('scale-95');
        }, 10);

        // Disable scroll body
        document.body.style.overflow = 'hidden';
    }

    function closeCommentModal(id) {
        const modal = document.getElementById('commentModal-' + id);
        const modalInner = modal.querySelector('.transform');

        modal.classList.add('opacity-0');
        modalInner.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);

        // Re-enable scroll body
        document.body.style.overflow = 'auto';
    }

    // FUNGSI UNTUK MENAMPILKAN FORM REPLY
    function toggleReplyForm(komentarId) {
        const form = document.getElementById('reply-form-' + komentarId);
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
            form.querySelector('textarea').focus();
        } else {
            form.classList.add('hidden');
        }
    }

    // FUNGSI UNTUK MODAL REPORT KOMENTAR
    function openReportModal(komentarId) {
        const modal = document.getElementById('reportModal');
        const modalInner = modal.querySelector('.transform');

        // Set ID komentar ke hidden input
        document.getElementById('report_komentar_id').value = komentarId;

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalInner.classList.remove('scale-95');
        }, 10);
    }

    function closeReportModal() {
        const modal = document.getElementById('reportModal');
        const modalInner = modal.querySelector('.transform');

        modal.classList.add('opacity-0');
        modalInner.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
            // Reset form
            document.getElementById('formReportKomentar').reset();
        }, 300);
    }
</script>
@endsection