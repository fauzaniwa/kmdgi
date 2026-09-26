@extends('layouts.app')

@section('title', 'Panduan Submisi Karya - KMDGI 16')

@section('content')

@php
    $edisiAktif = \App\Models\EdisiKmdgi::where('is_active', 1)->first();
    $deskripsiKarya = null;
    $submisiUser = null;
    $statusVerifikasi = 'Belum'; // Default
    
    if ($edisiAktif) {
        $deskripsiKarya = \App\Models\DeskripsiKarya::where('edisi_kmdgi_id', $edisiAktif->id)
            ->where('kategori_karya', strtolower($kategori))
            ->first();
            
        if(\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Schema::hasTable('submisi_karyas')) {
            // PERBAIKAN: Ambil ID semua delegasi dari institusi/kampus yang sama
            $userLogin = \Illuminate\Support\Facades\Auth::user();
            $userIdsSatuKampus = \App\Models\User::where('institusi', $userLogin->institusi)->pluck('id');

            // Cek submisi berdasarkan sekumpulan ID dari kampus tersebut
            $submisiUser = \Illuminate\Support\Facades\DB::table('submisi_karyas')
                ->whereIn('user_id', $userIdsSatuKampus)
                ->where('edisi_kmdgi_id', $edisiAktif->id)
                ->where('kategori_karya', strtolower($kategori))
                ->first();

            if($submisiUser && $submisiUser->status_draft == 0) {
                // Menarik data status_verifikasi jika kolomnya sudah dibuat, jika belum anggap 'Menunggu'
                $statusVerifikasi = $submisiUser->status_verifikasi ?? 'Menunggu';
            }
        }
    }
@endphp

<div class="bg-[#F8FAFC] min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8 font-sans">
        
        <!-- Sidebar Delegasi -->
        @include('partials.sidebar')

        <main class="flex-grow w-full pb-20">
            
            <!-- Alert Session (Sukses Submit) -->
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-5 py-4 rounded-2xl shadow-sm mb-6 flex items-start gap-4 animate-fade-in">
                <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                <div>
                    <h3 class="font-bold text-sm">Berhasil!</h3>
                    <p class="text-sm mt-1">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <!-- HEADER PANDUAN DELEGASI -->
            <div class="relative bg-[#1A1A1A] rounded-[2rem] p-8 md:p-12 overflow-hidden mb-10 shadow-lg">
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-gradient-to-br from-indigo-500/30 via-purple-500/20 to-blue-500/0 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-10 -left-10 w-64 h-64 bg-gradient-to-tr from-blue-600/30 to-purple-500/0 rounded-full blur-3xl pointer-events-none"></div>
                <h1 class="text-3xl md:text-[2.5rem] font-black text-white relative z-10 tracking-tight">Panduan Delegasi</h1>
            </div>

            <!-- PETUNJUK TEKNIS -->
            <div class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] mb-8">
                <h2 class="text-xl md:text-2xl font-black text-slate-900 mb-6 tracking-tight">Petunjuk Teknis</h2>
                <div class="text-[14px] text-slate-700 columns-1 lg:columns-2 gap-12 rich-text prose prose-sm prose-slate max-w-none prose-p:break-inside-avoid prose-li:break-inside-avoid">
                    {!! $panduanDelegasi->petunjuk_teknis ?? '<p class="text-slate-400 italic">Petunjuk teknis belum diisi oleh panitia.</p>' !!}
                </div>
            </div>

            <!-- PETUNJUK PAMERAN -->
            <div class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] mb-14">
                <h2 class="text-xl md:text-2xl font-black text-slate-900 mb-2 tracking-tight">Petunjuk Pameran</h2>
                <h3 class="text-base font-bold text-slate-800 mb-6">Do's & Dont's</h3>
                <div class="text-[14px] text-slate-700 columns-1 lg:columns-2 gap-12 rich-text prose prose-sm prose-slate max-w-none prose-p:break-inside-avoid prose-li:break-inside-avoid">
                    {!! $panduanDelegasi->petunjuk_pameran ?? '<p class="text-slate-400 italic">Petunjuk pameran belum diisi oleh panitia.</p>' !!}
                </div>
            </div>

            <!-- KARYA TEMATIK, SIMBIOTIK, SIMBOLIK -->
            <div>
                <h2 class="text-xl md:text-2xl font-black text-slate-900 mb-6 tracking-tight">Karya Tematik, Simbiotik, dan Simbolik</h2>
                
                @php $tabs = ['tematik' => 'Tematik', 'simbiotik' => 'Simbiotik', 'simbolik' => 'Simbolik']; @endphp

                <div class="flex space-x-6 md:space-x-10 border-b border-slate-200 mb-8 overflow-x-auto custom-scrollbar px-2">
                    @foreach($tabs as $key => $label)
                    <a href="{{ route('delegasi.submisi.panduan', $key) }}" class="flex items-center gap-2 pb-4 text-[14px] md:text-[15px] whitespace-nowrap transition-all border-b-2 {{ $kategori === $key ? 'border-slate-900 text-slate-900 font-bold' : 'border-transparent text-slate-400 font-medium hover:text-slate-800 hover:border-slate-300' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                        {{ $label }}
                    </a>
                    @endforeach
                </div>

                @if($deskripsiKarya)
                    <div class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] animate-fade-in">
                        <div class="mb-10">
                            <h3 class="text-lg md:text-xl font-bold text-slate-900 mb-3">Karya {{ ucfirst($kategori) }}</h3>
                            <div class="text-[14px] text-slate-600 leading-relaxed max-w-4xl rich-text prose prose-sm prose-slate">
                                {!! $deskripsiKarya->deskripsi ?? '-' !!}
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14">
                            <div>
                                <h4 class="text-[15px] font-bold text-slate-900 tracking-wide mb-4">Ketentuan Karya {{ ucfirst($kategori) }}</h4>
                                <div class="text-[14px] text-slate-600 rich-text prose prose-sm prose-slate max-w-none">
                                    {!! $deskripsiKarya->ketentuan_karya ?? '<p class="italic text-slate-400">Belum ada data.</p>' !!}
                                </div>
                            </div>
                            <div>
                                <h4 class="text-[15px] font-bold text-slate-900 tracking-wide mb-4">Sistem Penilaian</h4>
                                <div class="text-[14px] text-slate-600 rich-text prose prose-sm prose-slate max-w-none">
                                    {!! $deskripsiKarya->sistem_penilaian ?? '<p class="italic text-slate-400">Belum ada data.</p>' !!}
                                </div>
                            </div>
                        </div>

                        <hr class="border-slate-100 my-10">

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14">
                            <div>
                                <h4 class="text-[15px] font-bold text-slate-900 tracking-wide mb-4">Teknis Pelaksanaan</h4>
                                <div class="text-[14px] text-slate-600 rich-text prose prose-sm prose-slate max-w-none">
                                    {!! $deskripsiKarya->teknis_pelaksanaan ?? '<p class="italic text-slate-400">Belum ada data.</p>' !!}
                                </div>
                            </div>
                            <div>
                                <h4 class="text-[15px] font-bold text-slate-900 tracking-wide mb-4">Nominasi dan Kriteria Karya</h4>
                                <div class="text-[14px] text-slate-600 rich-text prose prose-sm prose-slate max-w-none">
                                    {!! $deskripsiKarya->nominasi_kriteria ?? '<p class="italic text-slate-400">Belum ada data.</p>' !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- AREA VALIDASI SUBMISI (DINAMIS)            -->
                    <!-- ========================================== -->
                    
                    @if($submisiUser && $submisiUser->status_draft == 0)
                        
                        @if($statusVerifikasi == 'Terverifikasi' || $statusVerifikasi == 'Diterima')
                            <!-- STATE 1: HIJAU (LOLOS VERIFIKASI) -->
                            <div class="bg-emerald-50 rounded-[1.5rem] p-6 md:p-8 mt-8 flex flex-col lg:flex-row items-center justify-between gap-6 border border-emerald-200 shadow-sm animate-fade-in">
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-12 h-12 bg-emerald-500 text-white rounded-full flex items-center justify-center flex-shrink-0 shadow-md shadow-emerald-500/20">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-[15px] font-bold text-emerald-900 mb-0.5">Karya Telah Terverifikasi!</p>
                                        <p class="text-[13px] text-emerald-700 leading-relaxed">Selamat! Karya Anda telah disetujui oleh panitia dan siap ditampilkan di Pameran KMDGI 16.</p>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto flex-shrink-0">
                                    <a href="{{ route('delegasi.submisi.daftar', $kategori) }}" class="w-full sm:w-auto text-center bg-white border border-emerald-300 text-emerald-700 hover:bg-emerald-100 font-bold py-3.5 px-6 rounded-xl transition-all duration-300 text-sm">Cek Berkas Terkirim</a>
                                    <button disabled class="w-full sm:w-auto text-center bg-emerald-500 text-white font-bold py-3.5 px-6 rounded-xl cursor-not-allowed text-sm shadow-sm">Terverifikasi</button>
                                </div>
                            </div>

                        @elseif($statusVerifikasi == 'Revisi' || $statusVerifikasi == 'Ditolak')
                            <!-- STATE 2: MERAH (BUTUH REVISI) -->
                            <div class="bg-red-50 rounded-[1.5rem] p-6 md:p-8 mt-8 flex flex-col lg:flex-row items-center justify-between gap-6 border border-red-200 shadow-sm animate-fade-in">
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-12 h-12 bg-red-500 text-white rounded-full flex items-center justify-center flex-shrink-0 shadow-md shadow-red-500/20">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-[15px] font-bold text-red-900 mb-0.5">Karya Membutuhkan Revisi</p>
                                        <p class="text-[13px] text-red-700 leading-relaxed">Panitia menemukan ketidaksesuaian pada berkas Anda. Silakan periksa dan kirim ulang sebelum deadline.</p>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto flex-shrink-0">
                                    <a href="{{ route('delegasi.submisi.daftar', $kategori) }}" class="w-full sm:w-auto text-center bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 px-8 rounded-xl transition-all duration-300 text-sm shadow-md shadow-red-500/20">Perbaiki Berkas</a>
                                </div>
                            </div>

                        @else
                            <!-- STATE 3: KUNING (MENUNGGU VERIFIKASI) -->
                            <div class="bg-amber-50 rounded-[1.5rem] p-6 md:p-8 mt-8 flex flex-col lg:flex-row items-center justify-between gap-6 border border-amber-200 shadow-sm animate-fade-in">
                                <div class="flex items-center gap-4 w-full">
                                    <div class="w-12 h-12 bg-amber-500 text-white rounded-full flex items-center justify-center flex-shrink-0 shadow-md shadow-amber-500/20">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-[15px] font-bold text-amber-900 mb-0.5">Terkirim & Menunggu Verifikasi</p>
                                        <p class="text-[13px] text-amber-700 leading-relaxed">Data Anda telah masuk ke sistem dan saat ini sedang dalam antrean pengecekan oleh panitia pameran.</p>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto flex-shrink-0">
                                    <a href="{{ route('delegasi.submisi.daftar', $kategori) }}" class="w-full sm:w-auto text-center bg-white border border-amber-300 text-amber-700 hover:bg-amber-100 font-bold py-3.5 px-6 rounded-xl transition-all duration-300 text-sm">Cek Berkas</a>
                                    <button disabled class="w-full sm:w-auto text-center bg-amber-500 text-white font-bold py-3.5 px-6 rounded-xl opacity-80 cursor-not-allowed text-sm shadow-sm">Diproses...</button>
                                </div>
                            </div>
                        @endif

                    @else
                        
                        <!-- JIKA BELUM SUBMIT ATAU MASIH DRAFT -->
                        <div class="bg-white rounded-[1.5rem] p-6 md:p-8 mt-8 flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-200 shadow-sm animate-fade-in">
                            <label class="flex items-start gap-4 cursor-pointer group flex-1 w-full">
                                <div class="relative flex items-center mt-0.5 flex-shrink-0">
                                    <input type="checkbox" id="agree-checkbox" class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded border-2 border-slate-300 checked:bg-[#1A1A1A] checked:border-[#1A1A1A]">
                                    <span class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" stroke="currentColor" stroke-width="1"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    </span>
                                </div>
                                <div>
                                    <p class="text-[14px] font-bold text-slate-800 mb-0.5">Saya telah membaca dan menyetujui seluruh ketentuan Karya {{ ucfirst($kategori) }}.</p>
                                    <p class="text-[13px] text-slate-500">Dengan mencentang, saya menyatakan siap mematuhi aturan KMDGI 16.</p>
                                </div>
                            </label>

                            <a href="{{ route('delegasi.submisi.daftar', $kategori) }}" id="btn-lanjut" class="w-full md:w-auto text-center bg-slate-100 text-slate-400 font-bold py-3.5 px-10 rounded-xl transition-all duration-300 pointer-events-none flex-shrink-0 text-[14px]">
                                {{ ($submisiUser && $submisiUser->status_draft == 1) ? 'Lanjutkan Submisi' : 'Mulai Submisi' }}
                            </a>
                        </div>

                    @endif

                @else
                    <div class="py-16 text-center border-2 border-dashed border-slate-200 rounded-3xl mt-4 bg-white">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                        <h3 class="text-lg font-bold text-slate-800 mb-1">Data Belum Tersedia</h3>
                        <p class="text-sm text-slate-500">Panitia belum melengkapi deskripsi untuk karya {{ ucfirst($kategori) }}.</p>
                    </div>
                @endif
            </div>

        </main>
    </div>
    @include('partials.footer')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('agree-checkbox');
        const btnLanjut = document.getElementById('btn-lanjut');

        if(checkbox && btnLanjut) {
            checkbox.addEventListener('change', function() {
                if(this.checked) {
                    btnLanjut.classList.remove('bg-slate-100', 'text-slate-400', 'pointer-events-none');
                    btnLanjut.classList.add('bg-[#1A1A1A]', 'hover:bg-black', 'text-white', 'shadow-md', 'shadow-slate-900/10');
                } else {
                    btnLanjut.classList.add('bg-slate-100', 'text-slate-400', 'pointer-events-none');
                    btnLanjut.classList.remove('bg-[#1A1A1A]', 'hover:bg-black', 'text-white', 'shadow-md', 'shadow-slate-900/10');
                }
            });
        }
    });
</script>

<style>
    .animate-fade-in { animation: fadeIn 0.4s ease-in-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .custom-scrollbar::-webkit-scrollbar { width: 0px; height: 0px; }

    /* Custom CSS Overrides untuk me-restore format HTML dari Text Editor CMS */
    .rich-text ul {
        list-style-type: disc !important;
        padding-left: 1.25rem !important;
        margin-top: 0.5rem !important;
        margin-bottom: 0.5rem !important;
    }
    .rich-text ol {
        list-style-type: decimal !important;
        padding-left: 1.25rem !important;
        margin-top: 0.5rem !important;
        margin-bottom: 0.5rem !important;
    }
    .rich-text li {
        margin-bottom: 0.35rem !important;
    }
    .rich-text strong, .rich-text b {
        font-weight: 700 !important;
        color: #0f172a !important;
    }
    .rich-text em, .rich-text i {
        font-style: italic !important;
    }
    .rich-text p {
        margin-bottom: 0.5rem !important;
    }
    .rich-text h1, .rich-text h2, .rich-text h3, .rich-text h4 {
        font-weight: 700 !important;
        color: #0f172a !important;
        margin-top: 1rem !important;
        margin-bottom: 0.5rem !important;
    }
    .rich-text h1 { font-size: 1.5rem !important; }
    .rich-text h2 { font-size: 1.25rem !important; }
    .rich-text h3 { font-size: 1.125rem !important; }
    .rich-text h4 { font-size: 1rem !important; }
    .rich-text blockquote {
        border-left: 3px solid #cbd5e1 !important;
        padding-left: 1rem !important;
        font-style: italic !important;
        margin: 0.75rem 0 !important;
    }
</style>
@endsection