@extends('layouts.app')
@section('title', 'Manajemen Berkas - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col relative">
    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">
        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans relative z-10">

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <span class="text-sm font-semibold flex items-center gap-2">✅ {{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-green-500">✖</button>
            </div>
            @endif

            <!-- KOTAK PENGATURAN DEADLINE -->
            <!-- KOTAK KONFIGURASI BERKAS & PANDUAN -->
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col gap-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Konfigurasi Instruksi & Berkas</h2>
                    <p class="text-sm text-slate-500 mt-1">Atur tenggat waktu, teks instruksi pendaftaran, dan unggah Buku Panduan KMDGI di sini.</p>
                </div>

                <form action="{{ route('admin.berkas.config') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Kolom 1: Bukti Bayar & Formulir -->
                        <div class="space-y-4 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                            <h4 class="text-sm font-bold text-slate-700">1. Bukti Pembayaran</h4>
                            <div>
                                <label class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Teks Instruksi</label>
                                <textarea name="text_bukti_pembayaran" rows="2" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-700 focus:outline-none focus:border-kmdgi-primary">{{ $config['text_bukti_pembayaran'] }}</textarea>
                            </div>
                            <div>
                                <label class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Tenggat Waktu (Deadline)</label>
                                <input type="datetime-local" name="deadline_pembayaran" value="{{ $config['deadline_pembayaran'] }}" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-700 focus:outline-none focus:border-kmdgi-primary">
                            </div>
                        </div>

                        <div class="space-y-4 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                            <h4 class="text-sm font-bold text-slate-700">2. Formulir Pendaftaran</h4>
                            <div>
                                <label class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Teks Instruksi</label>
                                <textarea name="text_formulir_pendaftaran" rows="2" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-700 focus:outline-none focus:border-kmdgi-primary">{{ $config['text_formulir_pendaftaran'] }}</textarea>
                            </div>
                            <div>
                                <label class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Tenggat Waktu (Deadline)</label>
                                <input type="datetime-local" name="deadline_formulir" value="{{ $config['deadline_formulir'] }}" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-700 focus:outline-none focus:border-kmdgi-primary">
                            </div>
                        </div>

                        <!-- Kolom 2 Bawah: Buku Panduan -->
                        <div class="space-y-4 bg-slate-900 p-5 rounded-2xl border border-slate-800 lg:col-span-2">
                            <h4 class="text-sm font-bold text-white">3. Buku Panduan Delegasi</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Teks Instruksi</label>
                                    <textarea name="text_buku_panduan" rows="2" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-200 focus:outline-none focus:border-kmdgi-primary">{{ $config['text_buku_panduan'] }}</textarea>
                                </div>
                                <div>
                                    <label class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Upload File Panduan (PDF)</label>
                                    <input type="file" name="file_buku_panduan" accept=".pdf" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-1.5 text-sm text-slate-200 focus:outline-none focus:border-kmdgi-primary mb-2">
                                    @if($config['file_buku_panduan'])
                                    <a href="{{ asset('storage/' . $config['file_buku_panduan']) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-emerald-400 hover:text-emerald-300 font-bold"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg> Lihat Panduan Saat Ini</a>
                                    @else
                                    <span class="text-xs text-slate-500 font-semibold italic">Belum ada file panduan yang diunggah.</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-6 rounded-xl text-sm transition-all shadow-md">Simpan Konfigurasi</button>
                    </div>
                </form>
            </div>

            <!-- KOTAK TABEL UTAMA -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Data Berkas Tim Delegasi</h2>
                    <p class="text-sm text-slate-500 mt-1">Verifikasi pembayaran dan formulir pendaftaran kampus.</p>
                </div>

                <form action="{{ route('admin.berkas.index') }}" method="GET" class="relative w-full md:w-72">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Institusi/Auth Code..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-kmdgi-primary/30 focus:border-kmdgi-primary transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </form>
            </div>

            <!-- TABEL KONTEN -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 font-bold text-xs uppercase tracking-wider">
                            <th class="py-4 px-6">Tim Delegasi</th>
                            <th class="py-4 px-4 text-center">Bukti Bayar</th>
                            <th class="py-4 px-4 text-center">Formulir</th>
                            <th class="py-4 px-4 text-center">Status</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-[14px]">
                        @forelse($dataBerkas as $berkas)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6">
                                <p class="font-bold text-slate-900 text-base mb-1">{{ $berkas->institusi }}</p>
                                <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                    <span class="font-mono bg-slate-200 text-slate-700 px-1.5 py-0.5 rounded">{{ $berkas->auth_code }}</span>
                                </div>
                            </td>

                            <td class="py-4 px-4 text-center">
                                @if($berkas->bukti_pembayaran)
                                <button type="button" onclick="openPreviewModal('{{ asset('storage/' . $berkas->bukti_pembayaran) }}', 'Bukti Pembayaran - {{ $berkas->institusi }}')" class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors border border-blue-200">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg> Preview
                                </button>
                                @else
                                <span class="text-[11px] font-medium text-slate-400">Kosong</span>
                                @endif
                            </td>

                            <td class="py-4 px-4 text-center">
                                @if($berkas->formulir_pendaftaran)
                                <button type="button" onclick="openPreviewModal('{{ asset('storage/' . $berkas->formulir_pendaftaran) }}', 'Formulir Pendaftaran - {{ $berkas->institusi }}')" class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-600 bg-purple-50 hover:bg-purple-100 px-3 py-1.5 rounded-lg transition-colors border border-purple-200">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg> Preview
                                </button>
                                @else
                                <span class="text-[11px] font-medium text-slate-400">Kosong</span>
                                @endif
                            </td>

                            <td class="py-4 px-4 text-center">
                                @if($berkas->kwitansi)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">Terverifikasi</span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200">Pending</span>
                                @endif
                            </td>

                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Tombol Upload Kwitansi -->
                                    <button type="button" onclick="openKwitansiModal('{{ $berkas->id }}', '{{ addslashes($berkas->institusi) }}')" class="p-2 text-slate-500 hover:text-emerald-600 bg-white border border-slate-200 shadow-sm rounded-xl transition-colors" title="Upload Kwitansi">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                        </svg>
                                    </button>

                                    <!-- Tombol Reset Berkas (Tolak) -->
                                    <button type="button" onclick="openResetModal('{{ $berkas->id }}', '{{ addslashes($berkas->institusi) }}')" class="p-2 text-slate-500 hover:text-red-500 bg-white border border-slate-200 shadow-sm rounded-xl transition-colors" title="Reset/Tolak Berkas">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">Belum ada data berkas yang masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4 border-t border-slate-100">
                    {{ $dataBerkas->links() }}
                </div>
            </div>

        </main>
    </div>
    @include('partials.footer')
</div>

<!-- ========================================== -->
<!-- MODAL PREVIEW DOKUMEN                      -->
<!-- ========================================== -->
<div id="previewModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-8 bg-slate-900/80 backdrop-blur-sm transition-all duration-300 opacity-0 pointer-events-none">
    <div class="bg-white rounded-3xl overflow-hidden w-full max-w-5xl h-[85vh] shadow-2xl flex flex-col transform transition-all scale-95" id="previewModalContent">
        <div class="flex justify-between items-center bg-slate-50 px-6 py-4 border-b border-slate-200 shrink-0">
            <h3 class="text-lg font-bold text-slate-900 truncate pr-4" id="previewModalTitle">Preview Dokumen</h3>
            <div class="flex items-center gap-3">
                <a href="#" id="previewDownloadBtn" download class="inline-flex items-center gap-1.5 text-xs font-bold bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg> Download
                </a>
                <button type="button" onclick="closePreviewModal()" class="text-slate-500 hover:text-red-500 transition-colors p-1 bg-white border border-slate-200 rounded-lg shadow-sm">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="flex-grow bg-slate-200/50 p-4 overflow-hidden relative">
            <!-- Iframe untuk menampilkan PDF / Gambar -->
            <iframe id="previewIframe" src="" class="w-full h-full rounded-xl border border-slate-300 shadow-inner bg-white"></iframe>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL UPLOAD KWITANSI                      -->
<!-- ========================================== -->
<div id="kwitansiModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-all duration-300 opacity-0 pointer-events-none">
    <div class="bg-white rounded-3xl p-6 md:p-8 max-w-md w-full shadow-2xl transform transition-all scale-95" id="kwitansiModalContent">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-black text-slate-900 leading-tight">Upload Kwitansi</h3>
            <button type="button" onclick="closeKwitansiModal()" class="text-slate-400 hover:text-red-500 transition-colors"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg></button>
        </div>
        <p class="text-sm text-slate-500 mb-4">Pilih file kwitansi (PDF/JPG) untuk disahkan kepada <strong id="modalInstitusi" class="text-slate-800"></strong>.</p>
        <form id="kwitansiForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <input type="file" name="kwitansi" required accept=".pdf,.jpg,.jpeg,.png" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-kmdgi-primary">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeKwitansiModal()" class="w-1/3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-4 rounded-xl transition-colors text-sm">Batal</button>
                <button type="submit" class="w-2/3 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-3 px-4 rounded-xl transition-colors shadow-sm text-sm">Sahkan Tim</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL RESET BERKAS                         -->
<!-- ========================================== -->
<div id="resetModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-all duration-300 opacity-0 pointer-events-none">
    <div class="bg-white rounded-3xl p-6 md:p-8 max-w-sm w-full shadow-2xl transform transition-all scale-95 text-center" id="resetModalContent">
        <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-red-100">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
        </div>
        <h3 class="text-xl font-black text-slate-900 leading-tight mb-2">Tolak/Reset Berkas?</h3>
        <p class="text-sm text-slate-500 mb-6 leading-relaxed">
            Pilih berkas mana yang tidak valid dari <strong id="resetInstitusi" class="text-slate-800"></strong>. File yang dipilih akan dihapus agar peserta dapat mengunggah ulang.
        </p>
        <form id="resetForm" method="POST" action="">
            @csrf
            <select name="jenis" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none mb-6 font-semibold" required>
                <option value="">Pilih Berkas...</option>
                <option value="pembayaran">Bukti Pembayaran</option>
                <option value="formulir">Formulir Pendaftaran</option>
            </select>
            <div class="flex gap-3">
                <button type="button" onclick="closeResetModal()" class="w-1/2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-4 rounded-xl transition-colors text-sm">Batal</button>
                <button type="submit" class="w-1/2 bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-4 rounded-xl transition-colors shadow-sm text-sm">Reset Berkas</button>
            </div>
        </form>
    </div>
</div>

<script>
    // PREVIEW DOKUMEN SCRIPT
    const previewModal = document.getElementById('previewModal');
    const previewModalContent = document.getElementById('previewModalContent');
    const previewIframe = document.getElementById('previewIframe');
    const previewModalTitle = document.getElementById('previewModalTitle');
    const previewDownloadBtn = document.getElementById('previewDownloadBtn');

    function openPreviewModal(url, title) {
        previewIframe.src = url;
        previewModalTitle.innerText = title;
        previewDownloadBtn.href = url;

        previewModal.classList.remove('opacity-0', 'pointer-events-none');
        previewModalContent.classList.remove('scale-95');
        previewModalContent.classList.add('scale-100');
    }

    function closePreviewModal() {
        previewModal.classList.add('opacity-0', 'pointer-events-none');
        previewModalContent.classList.remove('scale-100');
        previewModalContent.classList.add('scale-95');
        // Kosongkan iframe agar tidak memakan memory di belakang layar
        setTimeout(() => previewIframe.src = "", 300);
    }

    // UPLOAD KWITANSI SCRIPT
    const kwitansiModal = document.getElementById('kwitansiModal');
    const kwitansiModalContent = document.getElementById('kwitansiModalContent');
    const kwitansiForm = document.getElementById('kwitansiForm');

    function openKwitansiModal(id, institusi) {
        kwitansiForm.action = `/admin/manajemen-berkas/${id}/kwitansi`;
        document.getElementById('modalInstitusi').innerText = institusi;
        kwitansiModal.classList.remove('opacity-0', 'pointer-events-none');
        kwitansiModalContent.classList.remove('scale-95');
        kwitansiModalContent.classList.add('scale-100');
    }

    function closeKwitansiModal() {
        kwitansiModal.classList.add('opacity-0', 'pointer-events-none');
        kwitansiModalContent.classList.remove('scale-100');
        kwitansiModalContent.classList.add('scale-95');
    }

    // RESET BERKAS SCRIPT
    const resetModal = document.getElementById('resetModal');
    const resetModalContent = document.getElementById('resetModalContent');
    const resetForm = document.getElementById('resetForm');

    function openResetModal(id, institusi) {
        resetForm.action = `/admin/manajemen-berkas/${id}/reset`;
        document.getElementById('resetInstitusi').innerText = institusi;
        resetModal.classList.remove('opacity-0', 'pointer-events-none');
        resetModalContent.classList.remove('scale-95');
        resetModalContent.classList.add('scale-100');
    }

    function closeResetModal() {
        resetModal.classList.add('opacity-0', 'pointer-events-none');
        resetModalContent.classList.remove('scale-100');
        resetModalContent.classList.add('scale-95');
    }
</script>
@endsection