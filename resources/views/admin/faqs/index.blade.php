@extends('layouts.app')

@section('title', 'Manajemen F&Q - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">

        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">

            <!-- Flash Message & Error Alerts -->
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

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3 mb-1">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" /></svg>
                    <span class="text-sm font-bold">Terjadi Kesalahan!</span>
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
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Data Frequently Asked Questions</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola daftar pertanyaan dan jawaban yang sering diajukan peserta.</p>
                </div>
                <button type="button" onclick="openFaqModal('create')" class="inline-flex items-center justify-center gap-2 bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-5 rounded-xl text-sm transition-all shadow-md shadow-kmdgi-primary/10 flex-shrink-0">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" /></svg>
                    Tambah F&Q
                </button>
            </div>

            <!-- Form Filter & Pencarian -->
            <form method="GET" action="{{ route('admin.faqs.index') }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col md:flex-row items-center gap-4 justify-between">
                <div class="relative w-full md:max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" /></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kata kunci pertanyaan atau jawaban..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200/80 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:border-kmdgi-primary/50 focus:bg-white transition-all" onchange="this.form.submit()">
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kategori:</span>
                    <select name="kategori" onchange="this.form.submit()" class="px-3 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-600 focus:outline-none cursor-pointer">
                        <option value="all" {{ request('kategori') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                        <option value="Sistem Pendaftaran" {{ request('kategori') == 'Sistem Pendaftaran' ? 'selected' : '' }}>Sistem Pendaftaran</option>
                        <option value="Ketentuan Karya" {{ request('kategori') == 'Ketentuan Karya' ? 'selected' : '' }}>Ketentuan Karya</option>
                        <option value="Delegasi & Kampus" {{ request('kategori') == 'Delegasi & Kampus' ? 'selected' : '' }}>Delegasi & Kampus</option>
                        <option value="Umum" {{ request('kategori') == 'Umum' ? 'selected' : '' }}>Informasi Umum</option>
                    </select>
                </div>
            </form>

            <!-- Tabel Data -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-4 min-w-[250px]">Pertanyaan & Kategori</th>
                                <th class="py-4 px-4 min-w-[300px]">Jawaban</th>
                                <th class="py-4 px-4 text-center">Status</th>
                                <th class="py-4 px-6 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-[14px]">

                            @forelse($dataFaqs as $index => $faq)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 text-center font-medium text-slate-400">
                                    {{ $dataFaqs->firstItem() + $index }}
                                </td>
                                
                                <td class="py-4 px-4">
                                    <span class="font-bold text-slate-900 block leading-tight">{{ $faq->pertanyaan }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 mt-1.5 rounded text-[10px] font-bold bg-slate-100 text-slate-500 uppercase tracking-wide">
                                        {{ $faq->kategori }}
                                    </span>
                                </td>

                                <td class="py-4 px-4">
                                    <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed" title="{{ $faq->jawaban }}">
                                        {{ $faq->jawaban }}
                                    </p>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @if($faq->is_active)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wide">Ditampilkan</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-slate-50 text-slate-500 border border-slate-200 uppercase tracking-wide">Disembunyikan</span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" onclick="openFaqModal('edit', {{ json_encode($faq) }})" title="Edit F&Q" class="p-2 text-slate-400 hover:text-kmdgi-primary bg-slate-50 hover:bg-blue-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                        </button>

                                        <button type="button"
                                            onclick="openModal('kmdgi-global-modal', this)"
                                            data-title="Hapus F&Q?"
                                            data-message="Apakah Anda yakin ingin menghapus data FAQ ini secara permanen?"
                                            data-type="danger"
                                            data-primary-text="Hapus Permanen"
                                            data-secondary-text="Batalkan"
                                            data-form-id="delete-form-faq-{{ $faq->id }}"
                                            title="Hapus F&Q"
                                            class="p-2 text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m1.74 12.5h3.682c1.154 0 2.13-.816 2.229-1.943l.894-11.89c.041-.517-.333-.966-.853-.966H17.5M8.5 4h7M10.017 1.75h3.966M3 7.5h18" /></svg>
                                        </button>
                                        <form id="delete-form-faq-{{ $faq->id }}" action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-sm text-slate-400 font-medium">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" /></svg>
                                        Tidak ada data Frequently Asked Questions.
                                    </div>
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-medium">
                    <span>Menampilkan {{ $dataFaqs->firstItem() ?? 0 }}-{{ $dataFaqs->lastItem() ?? 0 }} dari {{ $dataFaqs->total() }} F&Q</span>
                    <div class="flex gap-1.5">
                        <a href="{{ $dataFaqs->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ $dataFaqs->onFirstPage() ? 'text-slate-400 cursor-not-allowed pointer-events-none' : 'text-slate-700 hover:border-kmdgi-primary hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Sebelumnya</a>
                        <a href="{{ $dataFaqs->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ !$dataFaqs->hasMorePages() ? 'text-slate-400 cursor-not-allowed pointer-events-none' : 'text-slate-700 hover:border-kmdgi-primary hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Selanjutnya</a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('partials.footer')
</div>

<!-- ========================================== -->
<!-- MODAL FORM: TAMBAH & EDIT FAQ              -->
<!-- ========================================== -->
<div id="modal-form-faq" class="fixed inset-0 z-[80] hidden opacity-0 transition-opacity duration-300 ease-in-out flex items-center justify-center">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeFaqModal()"></div>
    
    <div class="relative bg-white rounded-[2rem] p-6 md:p-8 w-full max-w-[36rem] mx-4 shadow-2xl transform scale-95 transition-transform duration-300 ease-in-out box-form max-h-[90vh] overflow-y-auto custom-scrollbar">
        <div class="flex justify-between items-center mb-6">
            <h3 id="modal-form-title" class="text-xl font-bold text-slate-900">Tambah F&Q Baru</h3>
            <button type="button" onclick="closeFaqModal()" class="text-slate-400 hover:text-red-500 transition-colors">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form id="faq-form" action="{{ route('admin.faqs.store') }}" method="POST" class="space-y-5">
            @csrf
            <div id="method-container"></div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Pertanyaan <span class="text-red-500">*</span></label>
                <input type="text" name="pertanyaan" id="input-pertanyaan" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all" placeholder="Tuliskan pertanyaan...">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jawaban Singkat & Jelas <span class="text-red-500">*</span></label>
                <textarea name="jawaban" id="input-jawaban" rows="4" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all custom-scrollbar" placeholder="Tuliskan jawaban dari pertanyaan di atas..."></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kategori Topik <span class="text-red-500">*</span></label>
                    <select name="kategori" id="input-kategori" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all cursor-pointer">
                        <option value="Umum">Informasi Umum</option>
                        <option value="Sistem Pendaftaran">Sistem Pendaftaran</option>
                        <option value="Ketentuan Karya">Ketentuan Karya</option>
                        <option value="Delegasi & Kampus">Delegasi & Kampus</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status Publikasi <span class="text-red-500">*</span></label>
                    <select name="is_active" id="input-status" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all cursor-pointer">
                        <option value="1">Aktif (Ditampilkan)</option>
                        <option value="0">Draft (Disembunyikan)</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeFaqModal()" class="flex-1 bg-white border border-slate-200 text-slate-600 font-bold py-3.5 px-4 rounded-xl hover:bg-slate-50 transition-colors text-sm">Batalkan</button>
                <button type="submit" id="btn-submit-form" class="flex-1 bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-3.5 px-4 rounded-xl transition-colors text-sm shadow-sm shadow-kmdgi-primary/20">Simpan F&Q</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openFaqModal(action, data = null) {
        const modal = document.getElementById('modal-form-faq');
        const box = modal.querySelector('.box-form');
        const form = document.getElementById('faq-form');
        const title = document.getElementById('modal-form-title');
        const methodContainer = document.getElementById('method-container');
        const btnSubmit = document.getElementById('btn-submit-form');

        form.reset();

        if (action === 'create') {
            title.innerText = 'Tambah F&Q Baru';
            form.action = "{{ route('admin.faqs.store') }}";
            methodContainer.innerHTML = ''; 
            btnSubmit.innerText = 'Tambahkan';
        } else if (action === 'edit' && data) {
            title.innerText = 'Edit F&Q';
            form.action = `/admin/faqs/update/${data.id}`;
            methodContainer.innerHTML = '@method("PUT")';
            btnSubmit.innerText = 'Simpan Perubahan';

            document.getElementById('input-pertanyaan').value = data.pertanyaan;
            document.getElementById('input-jawaban').value = data.jawaban;
            document.getElementById('input-kategori').value = data.kategori;
            document.getElementById('input-status').value = data.is_active ? "1" : "0";
        }

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function closeFaqModal() {
        const modal = document.getElementById('modal-form-faq');
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