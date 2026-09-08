@extends('layouts.app')
@section('title', 'Kelola Footer - Admin KMDGI')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">
    @include('partials.navbar')
    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">
        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full font-sans">
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <span class="text-sm font-semibold">{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">✖</button>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-between gap-4 bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Pengaturan Footer</h2>
                    <p class="text-sm text-slate-500 mt-1">Ubah warna latar, logo, ornamen, dan tautan sosial media.</p>
                </div>
            </div>

            <form action="{{ route('admin.footer.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- BAGIAN WARNA & TEKS -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Warna Latar (Background Color)</label>
                            <div class="flex items-center gap-3">
                                <input type="color" name="bg_color" value="{{ old('bg_color', $footer->bg_color ?? '#0a0a0a') }}" class="w-14 h-12 rounded cursor-pointer border-0 p-0">
                                <input type="text" value="{{ old('bg_color', $footer->bg_color ?? '#0a0a0a') }}" readonly class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono text-slate-500 outline-none flex-grow">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Teks Hak Cipta (Copyright)</label>
                            <input type="text" name="copyright_text" value="{{ old('copyright_text', $footer->copyright_text) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary">
                        </div>
                    </div>
                </div>

                <!-- BAGIAN MANAJEMEN TAUTAN / MENU -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm space-y-8">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">Manajemen Tautan Menu</h3>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-10">

                        <!-- Kolom Tautan Utama -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <label class="block text-xs font-bold text-slate-500 uppercase">Tautan Utama (Menu Kiri)</label>
                                <button type="button" onclick="addLink('menu_links')" class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg font-bold transition-colors">
                                    + Tambah Tautan
                                </button>
                            </div>

                            <div id="container-menu_links" class="space-y-4">
                                @forelse($footer->menu_links ?? [] as $index => $link)
                                <div class="flex flex-col sm:flex-row gap-2 link-row bg-slate-50/50 p-3 rounded-xl border border-slate-100 relative">
                                    <input type="text" name="menu_links[{{ $index }}][title]" value="{{ $link['title'] }}" placeholder="Label Tautan (Jadwal)" class="w-full sm:w-1/3 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-kmdgi-primary">
                                    <input type="text" name="menu_links[{{ $index }}][url]" value="{{ $link['url'] }}" placeholder="URL (/#jadwal)" class="w-full sm:flex-grow px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-kmdgi-primary">
                                    <button type="button" onclick="this.parentElement.remove()" class="absolute sm:relative -top-2 -right-2 sm:top-auto sm:right-auto p-1.5 sm:p-2 text-red-400 hover:text-white hover:bg-red-500 bg-white sm:bg-red-50 rounded-full sm:rounded-lg shadow-sm sm:shadow-none border border-red-100 sm:border-0 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                @empty
                                @endforelse
                            </div>
                        </div>

                        <!-- Kolom Tautan Profil -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <label class="block text-xs font-bold text-slate-500 uppercase">Tautan Profil (Menu Kanan)</label>
                                <button type="button" onclick="addLink('profile_links')" class="text-xs bg-emerald-50 text-emerald-600 hover:bg-emerald-100 px-3 py-1.5 rounded-lg font-bold transition-colors">
                                    + Tambah Tautan
                                </button>
                            </div>

                            <div id="container-profile_links" class="space-y-4">
                                @forelse($footer->profile_links ?? [] as $index => $link)
                                <div class="flex flex-col sm:flex-row gap-2 link-row bg-slate-50/50 p-3 rounded-xl border border-slate-100 relative">
                                    <input type="text" name="profile_links[{{ $index }}][title]" value="{{ $link['title'] }}" placeholder="Label Tautan (Dashboard)" class="w-full sm:w-1/3 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-kmdgi-primary">
                                    <input type="text" name="profile_links[{{ $index }}][url]" value="{{ $link['url'] }}" placeholder="URL (/dashboard)" class="w-full sm:flex-grow px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-kmdgi-primary">
                                    <button type="button" onclick="this.parentElement.remove()" class="absolute sm:relative -top-2 -right-2 sm:top-auto sm:right-auto p-1.5 sm:p-2 text-red-400 hover:text-white hover:bg-red-500 bg-white sm:bg-red-50 rounded-full sm:rounded-lg shadow-sm sm:shadow-none border border-red-100 sm:border-0 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                @empty
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>

                <!-- BAGIAN MEDIA SOSIAL -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm space-y-4">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4">Tautan Media Sosial</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tautan Instagram</label>
                            <input type="url" name="link_instagram" value="{{ old('link_instagram', $footer->link_instagram) }}" placeholder="https://instagram.com/..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tautan TikTok</label>
                            <input type="url" name="link_tiktok" value="{{ old('link_tiktok', $footer->link_tiktok) }}" placeholder="https://tiktok.com/..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary">
                        </div>
                    </div>
                </div>

                <!-- BAGIAN GAMBAR -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">Aset Visual Footer</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Logo -->
                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-500 uppercase">Logo Footer</label>
                            @if($footer->logo)
                            <div class="w-full aspect-video bg-slate-900 rounded-2xl flex items-center justify-center p-4 relative border border-slate-800 shadow-inner group">
                                <img src="{{ asset('storage/'.$footer->logo) }}" class="max-h-full object-contain">
                                <label class="absolute top-3 right-3 flex items-center gap-1.5 bg-red-500/90 backdrop-blur text-white text-[11px] font-bold px-3 py-1.5 rounded-lg cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity">
                                    <input type="checkbox" name="remove_logo" value="1" class="w-3 h-3 rounded border-red-300 text-red-600 focus:ring-red-500"> Hapus
                                </label>
                            </div>
                            @endif
                            <input type="file" name="logo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors">
                        </div>

                        <!-- Dekorasi Desktop -->
                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-500 uppercase">Dekorasi Desktop</label>
                            @if($footer->decoration_desktop)
                            <div class="w-full aspect-video bg-slate-900 rounded-2xl flex items-center justify-center relative overflow-hidden border border-slate-800 shadow-inner group">
                                <img src="{{ asset('storage/'.$footer->decoration_desktop) }}" class="w-full h-full object-cover opacity-50">
                                <label class="absolute top-3 right-3 flex items-center gap-1.5 bg-red-500/90 backdrop-blur text-white text-[11px] font-bold px-3 py-1.5 rounded-lg cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity">
                                    <input type="checkbox" name="remove_decoration_desktop" value="1" class="w-3 h-3 rounded border-red-300 text-red-600 focus:ring-red-500"> Hapus
                                </label>
                            </div>
                            @endif
                            <input type="file" name="decoration_desktop" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors">
                        </div>

                        <!-- Dekorasi Mobile -->
                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-500 uppercase">Dekorasi Mobile</label>
                            @if($footer->decoration_mobile)
                            <div class="w-full aspect-video bg-slate-900 rounded-2xl flex items-center justify-center relative overflow-hidden border border-slate-800 shadow-inner group">
                                <img src="{{ asset('storage/'.$footer->decoration_mobile) }}" class="w-full h-full object-cover opacity-50">
                                <label class="absolute top-3 right-3 flex items-center gap-1.5 bg-red-500/90 backdrop-blur text-white text-[11px] font-bold px-3 py-1.5 rounded-lg cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity">
                                    <input type="checkbox" name="remove_decoration_mobile" value="1" class="w-3 h-3 rounded border-red-300 text-red-600 focus:ring-red-500"> Hapus
                                </label>
                            </div>
                            @endif
                            <input type="file" name="decoration_mobile" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="w-full sm:w-auto bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-4 px-10 rounded-xl shadow-md text-sm transition-transform hover:-translate-y-1">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </main>
    </div>
    @include('partials.footer')
</div>

<script>
    function addLink(containerId) {
        const container = document.getElementById('container-' + containerId);
        // Menghitung indeks unik berdasarkan waktu agar tidak bentrok jika ada elemen yang dihapus di tengah
        const uniqueIndex = new Date().getTime();

        const html = `
            <div class="flex flex-col sm:flex-row gap-2 link-row bg-slate-50/50 p-3 rounded-xl border border-slate-100 relative">
                <input type="text" name="${containerId}[${uniqueIndex}][title]" placeholder="Label Tautan (Misal: Tentang Kami)" required class="w-full sm:w-1/3 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-kmdgi-primary">
                <input type="text" name="${containerId}[${uniqueIndex}][url]" placeholder="URL (/tentang-kami)" required class="w-full sm:flex-grow px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-kmdgi-primary">
                <button type="button" onclick="this.parentElement.remove()" class="absolute sm:relative -top-2 -right-2 sm:top-auto sm:right-auto p-1.5 sm:p-2 text-red-400 hover:text-white hover:bg-red-500 bg-white sm:bg-red-50 rounded-full sm:rounded-lg shadow-sm sm:shadow-none border border-red-100 sm:border-0 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }
</script>
@endsection