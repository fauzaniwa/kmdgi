@extends('layouts.app')

@section('title', 'Verifikasi Karya ' . ucfirst($kategori) . ' - KMDGI 16')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8 font-sans">
        
        @include('partials.sidebar-admin')

        <main class="flex-grow w-full pb-20">

            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-5 py-4 rounded-2xl shadow-sm mb-6 flex items-center gap-4 animate-fade-in">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-2xl shadow-sm mb-6 flex items-center gap-4 animate-fade-in">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
                <ul class="text-sm font-semibold list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="inline-block border border-blue-200 text-[#1A68FF] bg-blue-50 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">
                            Panel Kurasi
                        </span>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Karya {{ ucfirst($kategori) }}</h2>
                    <p class="text-sm text-slate-500 mt-1">Kelola dan verifikasi karya yang diajukan oleh delegasi kampus.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.verifikasi_karya.index', $kategori) }}" class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-sm mb-8 space-y-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="relative flex-grow">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" /></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Judul, Kreator, atau Institusi..." class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#1A68FF] focus:bg-white transition-all" onchange="this.form.submit()">
                    </div>
                    <div class="w-full md:w-56 flex-shrink-0">
                        <select name="status" onchange="this.form.submit()" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 font-semibold focus:outline-none focus:border-[#1A68FF] cursor-pointer">
                            <option value="all">Semua Status</option>
                            <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                            <option value="Terverifikasi" {{ request('status') == 'Terverifikasi' ? 'selected' : '' }}>✅ Terverifikasi</option>
                            <option value="Revisi" {{ request('status') == 'Revisi' ? 'selected' : '' }}>🔄 Butuh Revisi</option>
                            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                        </select>
                    </div>
                </div>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($dataSubmisi as $submisi)
                    <div class="bg-white border border-slate-200 rounded-[1.5rem] overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col">
                        
                        <div class="relative w-full h-48 bg-slate-100 border-b border-slate-100 flex-shrink-0">
                            @if($submisi->thumbnail_karya)
                                <img src="{{ asset('storage/' . $submisi->thumbnail_karya) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                    <svg class="w-10 h-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                                    <span class="text-xs font-medium">Tanpa Thumbnail</span>
                                </div>
                            @endif

                            <div class="absolute top-4 right-4">
                                @if($submisi->status_verifikasi == 'Terverifikasi')
                                    <span class="bg-emerald-500 text-white px-3 py-1 rounded-lg text-[10px] font-bold shadow-sm">Terverifikasi</span>
                                @elseif($submisi->status_verifikasi == 'Revisi')
                                    <span class="bg-red-500 text-white px-3 py-1 rounded-lg text-[10px] font-bold shadow-sm">Butuh Revisi</span>
                                @elseif($submisi->status_verifikasi == 'Ditolak')
                                    <span class="bg-slate-800 text-white px-3 py-1 rounded-lg text-[10px] font-bold shadow-sm">Ditolak</span>
                                @else
                                    <span class="bg-amber-400 text-white px-3 py-1 rounded-lg text-[10px] font-bold shadow-sm">Menunggu</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-slate-900 text-lg leading-tight line-clamp-2 mb-2" title="{{ $submisi->judul_karya }}">{{ $submisi->judul_karya ?? '-' }}</h3>
                                
                                <div class="flex items-start gap-2 mb-2">
                                    <svg class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                                    <p class="text-xs text-slate-600 line-clamp-1">{{ $submisi->kreator_karya ?? '-' }}</p>
                                </div>
                                <div class="flex items-start gap-2 mb-4">
                                    <svg class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 0 0-.491 6.347A48.627 48.627 0 0 1 12 20.904a48.627 48.627 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.905 59.905 0 0 1 12 3.493a59.902 59.902 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" /></svg>
                                    <p class="text-xs text-slate-600 line-clamp-1 font-medium">{{ $submisi->user->institusi ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <button type="button" 
                                onclick='openReviewModal(@json($submisi))'
                                class="w-full mt-4 bg-slate-100 hover:bg-[#1A68FF] text-slate-700 hover:text-white border border-slate-200 hover:border-[#1A68FF] py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm">
                                Review Karya
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center border-2 border-dashed border-slate-200 rounded-3xl bg-white">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                        <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Karya</h3>
                        <p class="text-sm text-slate-500">Tidak ada pengajuan karya yang masuk atau sesuai filter.</p>
                    </div>
                @endforelse
            </div>

            @if($dataSubmisi->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $dataSubmisi->links() }}
                </div>
            @endif

        </main>
    </div>
    @include('partials.footer')
</div>

<div id="reviewModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0" onclick="closeReviewModal()"></div>
    <div class="relative bg-white rounded-[2rem] overflow-hidden w-full max-w-3xl shadow-2xl transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]" id="reviewModalContent">
        
        <div class="bg-white border-b border-slate-100 px-8 py-6 flex justify-between items-center relative z-10 flex-shrink-0">
            <div>
                <h3 class="font-black text-slate-900 text-xl tracking-tight">Review Submisi Karya</h3>
                <p class="text-xs font-medium text-slate-500 mt-1" id="m-institusi">Institusi</p>
            </div>
            <button type="button" onclick="closeReviewModal()" class="text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 p-2.5 rounded-full transition-colors focus:outline-none">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        
        <div class="p-8 bg-slate-50 flex-grow overflow-auto relative space-y-8">
            
            <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Informasi Karya</h4>
                <div class="space-y-4">
                    <div>
                        <p class="text-[11px] text-slate-500 font-bold uppercase">Judul Karya</p>
                        <p class="text-sm font-bold text-slate-900 mt-1" id="m-judul">-</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-slate-500 font-bold uppercase">Kreator / Tim</p>
                        <p class="text-sm font-medium text-slate-800 mt-1" id="m-kreator">-</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-slate-500 font-bold uppercase">Deskripsi Makna</p>
                        <p class="text-[13px] text-slate-600 mt-1 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100" id="m-deskripsi">-</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Lampiran Berkas</h4>
                <div class="flex flex-col sm:flex-row gap-4">
                    
                    <div id="m-link-container" class="flex-1 bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-center justify-between hidden">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white text-blue-600 rounded-full flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-blue-900 uppercase">Tautan URL</p>
                                <p class="text-xs text-blue-700 mt-0.5">Tautan Eksternal</p>
                            </div>
                        </div>
                        <a href="#" id="m-btn-link" target="_blank" class="bg-[#1A68FF] hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-lg shadow-sm transition-colors">Buka</a>
                    </div>

                    <div id="m-file-container" class="flex-1 bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex items-center justify-between hidden">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white text-emerald-600 rounded-full flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-emerald-900 uppercase">File Karya</p>
                                <p class="text-xs text-emerald-700 mt-0.5" id="m-file-name">file.zip</p>
                            </div>
                        </div>
                        <a href="#" id="m-btn-file" target="_blank" download class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-lg shadow-sm transition-colors">Unduh</a>
                    </div>

                    <p id="m-no-file" class="text-sm text-slate-500 italic hidden">Tidak ada lampiran.</p>
                </div>
            </div>

            <form id="form-keputusan" method="POST" action="">
                @csrf
                <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm space-y-5">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Keputusan Verifikasi</h4>
                    
                    <label class="block text-sm font-bold text-slate-800">Ubah Status</label>
                    <div class="grid grid-cols-3 gap-3">
                        
                        <label class="cursor-pointer relative">
                            <input type="radio" name="status_verifikasi" value="Terverifikasi" class="peer sr-only" onchange="toggleCatatanRevisi(this.value)" required>
                            <div class="text-center bg-white border border-slate-200 text-slate-500 rounded-xl py-3 px-2 hover:bg-emerald-50 peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 transition-all">
                                <svg class="w-6 h-6 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                <span class="text-xs font-bold">Terima (Terverifikasi)</span>
                            </div>
                        </label>
                        
                        <label class="cursor-pointer relative">
                            <input type="radio" name="status_verifikasi" value="Revisi" class="peer sr-only" onchange="toggleCatatanRevisi(this.value)">
                            <div class="text-center bg-white border border-slate-200 text-slate-500 rounded-xl py-3 px-2 hover:bg-red-50 peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 transition-all">
                                <svg class="w-6 h-6 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" /></svg>
                                <span class="text-xs font-bold">Minta Revisi</span>
                            </div>
                        </label>

                        <label class="cursor-pointer relative">
                            <input type="radio" name="status_verifikasi" value="Ditolak" class="peer sr-only" onchange="toggleCatatanRevisi(this.value)">
                            <div class="text-center bg-white border border-slate-200 text-slate-500 rounded-xl py-3 px-2 hover:bg-slate-100 peer-checked:bg-slate-800 peer-checked:border-slate-800 peer-checked:text-white transition-all">
                                <svg class="w-6 h-6 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                <span class="text-xs font-bold">Tolak Permanen</span>
                            </div>
                        </label>

                    </div>

                    <div id="container-catatan-revisi" class="transition-all duration-300 pt-2 hidden">
                        <label for="catatan_revisi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Catatan Revisi / Alasan Penolakan <span class="text-red-500" id="bintang-wajib">*</span>
                        </label>
                        <textarea 
                            name="catatan_revisi" 
                            id="m-catatan-revisi" 
                            rows="4" 
                            placeholder="Tuliskan poin-poin yang perlu diperbaiki oleh delegasi atau alasan karya ditolak..."
                            class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-[#1A68FF] focus:bg-white transition-all resize-none"
                        ></textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeReviewModal()" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition-colors">Batal</button>
                    <button type="submit" class="px-8 py-3 bg-[#1A1A1A] text-white rounded-xl text-sm font-bold hover:bg-black transition-colors shadow-md">Simpan Keputusan</button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.4s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    function openReviewModal(data) {
        // Isi Data Identitas
        document.getElementById('m-judul').innerText = data.judul_karya || '-';
        document.getElementById('m-kreator').innerText = data.kreator_karya || '-';
        document.getElementById('m-deskripsi').innerText = data.deskripsi_karya || '-';
        document.getElementById('m-institusi').innerText = data.user && data.user.institusi ? data.user.institusi : 'Institusi Tidak Diketahui';

        // Tautan / URL Link
        const linkContainer = document.getElementById('m-link-container');
        const btnLink = document.getElementById('m-btn-link');
        if(data.link_karya) {
            linkContainer.classList.remove('hidden');
            btnLink.href = data.link_karya;
        } else {
            linkContainer.classList.add('hidden');
        }

        // File Karya
        const fileContainer = document.getElementById('m-file-container');
        const btnFile = document.getElementById('m-btn-file');
        const fileName = document.getElementById('m-file-name');
        if(data.file_karya) {
            fileContainer.classList.remove('hidden');
            btnFile.href = '/storage/' + data.file_karya;
            
            let splitPath = data.file_karya.split('/');
            fileName.innerText = splitPath[splitPath.length - 1];
        } else {
            fileContainer.classList.add('hidden');
        }

        // Tampilkan teks 'Tidak ada lampiran' jika keduanya kosong
        const noFile = document.getElementById('m-no-file');
        if(!data.link_karya && !data.file_karya) {
            noFile.classList.remove('hidden');
        } else {
            noFile.classList.add('hidden');
        }

        // Atur Route Action Form Update Status
        const formKeputusan = document.getElementById('form-keputusan');
        formKeputusan.action = `/admin/verifikasi-karya/update/${data.id}`;

        // Set Radio Button dan Catatan Revisi
        const radios = document.getElementsByName('status_verifikasi');
        let currentStatus = data.status_verifikasi || 'Terverifikasi';

        radios.forEach(r => {
            r.checked = (r.value === currentStatus);
        });

        // Set teks catatan revisi dari database
        const textareaRevisi = document.getElementById('m-catatan-revisi');
        textareaRevisi.value = data.catatan_revisi || '';

        // Atur Tampilan Textarea berdasarkan status awal
        toggleCatatanRevisi(currentStatus);

        // Buka Modal
        const modal = document.getElementById('reviewModal');
        const content = document.getElementById('reviewModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeReviewModal() {
        const modal = document.getElementById('reviewModal');
        const content = document.getElementById('reviewModalContent');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function toggleCatatanRevisi(status) {
        const container = document.getElementById('container-catatan-revisi');
        const textarea = document.getElementById('m-catatan-revisi');

        if (status === 'Revisi' || status === 'Ditolak') {
            container.classList.remove('hidden');
            textarea.setAttribute('required', 'required');
        } else {
            container.classList.add('hidden');
            textarea.removeAttribute('required');
        }
    }
</script>

@endsection