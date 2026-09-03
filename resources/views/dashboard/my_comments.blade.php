@extends('layouts.app')
@section('title', 'Komentar Saya - KMDGI 16')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen flex flex-col">
    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8 font-sans">
        @include('partials.sidebar')

        <main class="flex-grow w-full pb-20">
            <div class="max-w-5xl mx-auto">

                @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-bold border border-emerald-100 flex items-center justify-between">
                    {{ session('success') }}
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
                </div>
                @endif

                <div class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] mb-8 relative overflow-hidden">
                    <div class="absolute -right-16 -top-16 w-64 h-64 bg-gradient-to-br from-blue-50 to-sky-50/30 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10">
                        <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight mb-2">Komentar Saya</h1>
                        <p class="text-sm text-slate-500 max-w-xl">Riwayat seluruh komentar dan diskusi publik Anda di berbagai karya pameran KMDGI 16.</p>
                    </div>
                </div>

                @if($komentars->isEmpty())
                <div class="bg-white border border-slate-200 rounded-[2rem] p-12 flex flex-col items-center justify-center text-center shadow-sm">
                    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-4 border border-blue-100">
                        <svg class="w-10 h-10 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.333c-1.114.392-2.32.59-3.555.59a2.75 2.75 0 01-2.5-2.75c0-.62.203-1.196.532-1.68C3.21 14.544 2.25 13.33 2.25 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Komentar</h3>
                    <p class="text-sm text-slate-500 max-w-sm">Anda belum memberikan komentar apapun. Yuk, bagikan tanggapan positif Anda!</p>
                </div>
                @else
                <div class="space-y-6">
                    @foreach($komentars as $komentar)
                    <div class="bg-white rounded-[1.5rem] border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start gap-4 mb-4">
                            <div>
                                <p class="text-xs font-bold text-slate-400 mb-1">{{ $komentar->created_at->translatedFormat('d M Y - H:i') }}</p>
                                <p class="text-sm text-slate-800 leading-relaxed font-medium">"{{ $komentar->isi_komentar }}"</p>
                            </div>
                            
                            <!-- Tombol Hapus -->
                            <button onclick="confirmDelete({{ $komentar->id }})" class="text-slate-300 hover:text-red-500 bg-slate-50 hover:bg-red-50 p-2 rounded-xl transition-colors shrink-0" title="Hapus Komentar">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            </button>
                        </div>

                        <!-- Card Rujukan Karya -->
                        @if($komentar->submisiKarya)
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0 bg-slate-200">
                                    <img src="{{ asset('storage/' . $komentar->submisiKarya->thumbnail_karya) }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Dikomentari pada karya</p>
                                    <p class="text-xs font-bold text-slate-900 line-clamp-1">{{ $komentar->submisiKarya->judul_karya }}</p>
                                </div>
                            </div>
                            <a href="{{ route('katalog.karya.show', \Illuminate\Support\Str::slug($komentar->submisiKarya->judul_karya)) }}#komentar" class="text-[10px] font-bold text-[#1A68FF] bg-blue-50 border border-blue-100 px-3 py-1.5 rounded-lg whitespace-nowrap hover:bg-[#1A68FF] hover:text-white transition-colors">
                                Lihat Karya
                            </a>
                        </div>
                        @else
                        <div class="bg-red-50 text-red-600 text-xs font-bold p-3 rounded-xl border border-red-100">Karya telah dihapus oleh pengunggah.</div>
                        @endif
                    </div>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $komentars->links() }}
                </div>
                @endif
            </div>
        </main>
    </div>
    @include('partials.footer')
</div>

<!-- FORM HIDDEN UNTUK DELETE KOMENTAR -->
<form id="delete-komentar-form" action="{{ route('delegasi.submisi.komentar.destroy') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
    <input type="hidden" name="komentar_id" id="delete_komentar_id">
</form>

<script>
    function confirmDelete(id) {
        if(confirm('Apakah Anda yakin ingin menghapus komentar ini? Tindakan ini tidak dapat dibatalkan.')) {
            document.getElementById('delete_komentar_id').value = id;
            document.getElementById('delete-komentar-form').submit();
        }
    }
</script>
@endsection