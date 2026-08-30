@extends('layouts.app')

@section('title', 'Manajemen Data User - KMDGI 16')

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
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Data User & Kepanitiaan</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola akun pengguna, hak akses sistem, profil, dan detail peserta delegasi/umum.</p>
                </div>
                <button type="button" onclick="openUserModal('create')" class="inline-flex items-center justify-center gap-2 bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-semibold py-3 px-5 rounded-xl text-sm transition-all shadow-md shadow-kmdgi-primary/10 flex-shrink-0">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>
                    Tambah User
                </button>
            </div>

            <!-- Form Filter & Pencarian -->
            <form method="GET" action="{{ route('admin.users.index') }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] flex flex-col xl:flex-row items-center gap-4 justify-between">
                <div class="relative w-full xl:max-w-xs">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" /></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau institusi..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200/80 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:border-kmdgi-primary/50 focus:bg-white transition-all" onchange="this.form.submit()">
                </div>

                <div class="flex flex-wrap items-center gap-4 w-full xl:w-auto">
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Role:</span>
                        <select name="role" onchange="this.form.submit()" class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg text-slate-600 focus:outline-none cursor-pointer">
                            <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>Semua Role</option>
                            <option value="peserta" {{ request('role') == 'peserta' ? 'selected' : '' }}>Peserta (Delegasi/Umum)</option>
                            <option value="editor" {{ request('role') == 'editor' ? 'selected' : '' }}>Editor</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="super admin" {{ request('role') == 'super admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kategori:</span>
                        <select name="kategori" onchange="this.form.submit()" class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg text-slate-600 focus:outline-none cursor-pointer">
                            <option value="all" {{ request('kategori') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                            <option value="Delegasi" {{ request('kategori') == 'Delegasi' ? 'selected' : '' }}>Delegasi</option>
                            <option value="Umum" {{ request('kategori') == 'Umum' ? 'selected' : '' }}>Umum</option>
                        </select>
                    </div>
                </div>
            </form>

            <!-- Tabel Data -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.005)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-4 min-w-[200px]">Data Pengguna</th>
                                <th class="py-4 px-4 text-center">Tipe Akses (Role)</th>
                                <th class="py-4 px-4 min-w-[180px]">Status & Info</th>
                                <th class="py-4 px-4 min-w-[150px]">Detail Kontak</th>
                                <th class="py-4 px-6 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-[14px]">

                            @forelse($dataUsers as $index => $user)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 text-center font-medium text-slate-400">
                                    {{ $dataUsers->firstItem() + $index }}
                                </td>
                                
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <!-- Menampilkan Foto Profil jika ada, jika tidak gunakan UI Avatar -->
                                        <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200/60 p-0.5 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                            @if($user->profile_image)
                                                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profil" class="w-full h-full object-cover rounded-full">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=F0F6FF&color=126CFD" alt="Avatar" class="w-full h-full object-cover rounded-full">
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 block leading-tight truncate max-w-[200px]">{{ $user->name }}</span>
                                            <span class="text-xs text-slate-400 block truncate max-w-[200px]">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @if($user->role === 'super admin')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-100 uppercase tracking-wide">Super Admin</span>
                                    @elseif($user->role === 'admin')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-100 uppercase tracking-wide">Admin</span>
                                    @elseif($user->role === 'editor')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-100 uppercase tracking-wide">Editor</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-wide">Peserta</span>
                                    @endif
                                </td>

                                <td class="py-4 px-4">
                                    @if($user->role === 'peserta')
                                        @if($user->kategori === 'Delegasi')
                                            <div class="space-y-0.5">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="inline-block w-2 h-2 rounded-full bg-kmdgi-primary"></span>
                                                    <span class="font-semibold text-kmdgi-primary text-xs uppercase tracking-wide">Delegasi ({{ $user->peran_delegasi ?? 'Anggota' }})</span>
                                                </div>
                                                <p class="text-xs text-slate-500 font-medium truncate max-w-[180px] pl-3.5">{{ $user->institusi ?? 'Institusi Belum Diatur' }}</p>
                                            </div>
                                        @else
                                            <div class="space-y-0.5">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                                                    <span class="font-semibold text-emerald-600 text-xs uppercase tracking-wide">Peserta Umum</span>
                                                </div>
                                                <!-- Menampilkan Profesi Khusus Umum -->
                                                <p class="text-xs text-slate-500 font-medium truncate max-w-[180px] pl-3.5">Profesi: {{ $user->profesi ?? '-' }}</p>
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-xs text-slate-400 italic">Akun Pengelola Internal</span>
                                    @endif
                                </td>

                                <td class="py-4 px-4 space-y-1">
                                    @if($user->no_hp)
                                        <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" /></svg>
                                            {{ $user->no_hp }}
                                        </div>
                                    @endif
                                    @if($user->tanggal_lahir)
                                        <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                                            {{ \Carbon\Carbon::parse($user->tanggal_lahir)->format('d M Y') }}
                                        </div>
                                    @endif
                                    @if(!$user->no_hp && !$user->tanggal_lahir)
                                        <span class="text-xs text-slate-400 italic">Data belum lengkap</span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" onclick="openUserModal('edit', {{ json_encode($user) }})" title="Edit User" class="p-2 text-slate-400 hover:text-kmdgi-primary bg-slate-50 hover:bg-blue-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                        </button>

                                        @if(auth()->id() != $user->id)
                                        <button type="button"
                                            onclick="openModal('kmdgi-global-modal', this)"
                                            data-title="Hapus Pengguna?"
                                            data-message="Apakah Anda yakin ingin menghapus akun {{ $user->name }}? Akses login dan semua datanya akan hilang."
                                            data-type="danger"
                                            data-primary-text="Hapus Permanen"
                                            data-secondary-text="Batalkan"
                                            data-form-id="delete-form-user-{{ $user->id }}"
                                            title="Hapus User"
                                            class="p-2 text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m1.74 12.5h3.682c1.154 0 2.13-.816 2.229-1.943l.894-11.89c.041-.517-.333-.966-.853-.966H17.5M8.5 4h7M10.017 1.75h3.966M3 7.5h18" /></svg>
                                        </button>
                                        <form id="delete-form-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-sm text-slate-400 font-medium">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                                        Tidak ada data pengguna yang ditemukan.
                                    </div>
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-medium">
                    <span>Menampilkan {{ $dataUsers->firstItem() ?? 0 }}-{{ $dataUsers->lastItem() ?? 0 }} dari {{ $dataUsers->total() }} Pengguna</span>
                    <div class="flex gap-1.5">
                        <a href="{{ $dataUsers->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ $dataUsers->onFirstPage() ? 'text-slate-400 cursor-not-allowed pointer-events-none' : 'text-slate-700 hover:border-kmdgi-primary hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Sebelumnya</a>
                        <a href="{{ $dataUsers->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 {{ !$dataUsers->hasMorePages() ? 'text-slate-400 cursor-not-allowed pointer-events-none' : 'text-slate-700 hover:border-kmdgi-primary hover:text-kmdgi-primary' }} rounded-xl transition-colors shadow-sm">Selanjutnya</a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('partials.footer')
</div>

<!-- ========================================== -->
<!-- MODAL FORM: TAMBAH & EDIT USER             -->
<!-- ========================================== -->
<div id="modal-form-user" class="fixed inset-0 z-[80] hidden opacity-0 transition-opacity duration-300 ease-in-out flex items-center justify-center">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeUserModal()"></div>
    
    <div class="relative bg-white rounded-[2rem] p-6 md:p-8 w-full max-w-[36rem] mx-4 shadow-2xl transform scale-95 transition-transform duration-300 ease-in-out box-form max-h-[90vh] overflow-y-auto custom-scrollbar">
        <div class="flex justify-between items-center mb-6">
            <h3 id="modal-form-title" class="text-xl font-bold text-slate-900">Tambah User Baru</h3>
            <button type="button" onclick="closeUserModal()" class="text-slate-400 hover:text-red-500 transition-colors">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form id="user-form" action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div id="method-container"></div>

            <!-- Upload Foto Profil (Desain Melingkar) -->
            <div class="flex flex-col items-center sm:items-start sm:flex-row gap-5">
                <div class="relative w-24 h-24 rounded-full border-2 border-dashed border-slate-300 hover:border-kmdgi-primary bg-slate-50 flex flex-shrink-0 items-center justify-center overflow-hidden group cursor-pointer transition-colors" onclick="document.getElementById('input-profile').click()">
                    <div id="profile-placeholder" class="flex flex-col items-center pointer-events-none relative z-10 transition-opacity">
                        <svg class="w-6 h-6 text-slate-400 group-hover:text-kmdgi-primary transition-colors flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" /></svg>
                    </div>
                    <img id="profile-preview" src="" class="hidden absolute inset-0 w-full h-full object-cover z-20" />
                </div>
                <input type="file" name="profile_image" id="input-profile" accept="image/*" class="hidden" onchange="previewProfileImage(this)">
                <div class="text-center sm:text-left pt-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Foto Profil (Opsional)</label>
                    <p class="text-[11px] text-slate-400">Gunakan format JPG/PNG dengan max 2MB. Resolusi disarankan 1:1 (Persegi).</p>
                    <button type="button" id="btn-remove-profile" class="hidden mt-2 text-[11px] font-bold text-red-500 hover:text-red-600" onclick="removeProfilePreview()">Hapus Pilihan</button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="input-name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email Akses <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="input-email" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">No Handphone</label>
                    <input type="text" name="no_hp" id="input-nohp" placeholder="08..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="input-tgl" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Password / Kata Sandi</label>
                <input type="password" name="password" id="input-password" placeholder="Minimal 8 karakter..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all">
                <p id="password-hint" class="text-[10px] text-slate-400 mt-1 hidden">Kosongkan jika tidak ingin mengubah kata sandi.</p>
            </div>

            <hr class="border-slate-100 my-4">

            <!-- Dynamic Role & Kategori Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tipe Akses (Role) <span class="text-red-500">*</span></label>
                    <select name="role" id="input-role" required onchange="toggleFormFields()" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all cursor-pointer">
                        <option value="peserta">Peserta (Umum/Delegasi)</option>
                        <option value="editor">Editor</option>
                        <option value="admin">Admin</option>
                        <option value="super admin">Super Admin</option>
                    </select>
                </div>

                <div id="kategori-container">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kategori Peserta</label>
                    <select name="kategori" id="input-kategori" onchange="toggleFormFields()" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary focus:bg-white transition-all cursor-pointer">
                        <option value="Umum">Peserta Umum</option>
                        <option value="Delegasi">Delegasi Kampus</option>
                    </select>
                </div>
            </div>

            <!-- Dynamic Delegasi Data Section -->
            <div id="delegasi-fields" class="hidden space-y-4 bg-blue-50/50 p-4 rounded-xl border border-blue-100 mt-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Institusi Delegasi</label>
                    <select name="institusi" id="input-institusi" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary transition-all cursor-pointer">
                        <option value="">-- Pilih Kampus --</option>
                        @foreach($dataKampus as $kampus)
                            <option value="{{ $kampus->nama_institusi }}">{{ $kampus->nama_institusi }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Peran Delegasi</label>
                        <select name="peran_delegasi" id="input-peran" onchange="toggleFormFields()" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary transition-all cursor-pointer">
                            <option value="Anggota Delegasi">Anggota</option>
                            <option value="Ketua Delegasi">Ketua Delegasi</option>
                        </select>
                    </div>
                    <div id="auth-code-container">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kode Autentikasi (Anggota)</label>
                        <input type="text" name="auth_code" id="input-authcode" placeholder="Kode dari Ketua..." class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-kmdgi-primary transition-all">
                    </div>
                </div>
            </div>

            <!-- Dynamic Umum Data Section (Profesi) -->
            <div id="umum-fields" class="hidden mt-4">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Profesi / Pekerjaan Saat Ini</label>
                <input type="text" name="profesi" id="input-profesi" placeholder="Misal: Mahasiswa ITB, Freelance Designer, dll" class="w-full px-4 py-3 bg-emerald-50 border border-emerald-100 rounded-xl text-sm focus:outline-none focus:border-emerald-500 transition-all">
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeUserModal()" class="flex-1 bg-white border border-slate-200 text-slate-600 font-bold py-3.5 px-4 rounded-xl hover:bg-slate-50 transition-colors text-sm">Batalkan</button>
                <button type="submit" id="btn-submit-form" class="flex-1 bg-kmdgi-primary hover:bg-kmdgi-hover text-white font-bold py-3.5 px-4 rounded-xl transition-colors text-sm shadow-sm shadow-kmdgi-primary/20">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Live Preview Profil Image
    function previewProfileImage(input) {
        const preview = document.getElementById('profile-preview');
        const placeholder = document.getElementById('profile-placeholder');
        const removeBtn = document.getElementById('btn-remove-profile');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('opacity-0');
                removeBtn.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeProfilePreview() {
        const input = document.getElementById('input-profile');
        const preview = document.getElementById('profile-preview');
        const placeholder = document.getElementById('profile-placeholder');
        const removeBtn = document.getElementById('btn-remove-profile');

        input.value = ""; 
        preview.src = "";
        preview.classList.add('hidden');
        placeholder.classList.remove('opacity-0');
        removeBtn.classList.add('hidden');
    }

    // Logika Pintar untuk Menampilkan/Menyembunyikan Form Sesuai Role & Kategori
    function toggleFormFields() {
        const role = document.getElementById('input-role').value;
        const katContainer = document.getElementById('kategori-container');
        const kategori = document.getElementById('input-kategori').value;
        const delFields = document.getElementById('delegasi-fields');
        const umumFields = document.getElementById('umum-fields');
        const peran = document.getElementById('input-peran').value;
        const authContainer = document.getElementById('auth-code-container');

        if(role === 'peserta') {
            katContainer.classList.remove('hidden');
            
            if(kategori === 'Delegasi') {
                delFields.classList.remove('hidden');
                umumFields.classList.add('hidden');
                // Tampilkan Auth Code hanya jika dia Anggota
                if(peran === 'Anggota Delegasi') {
                    authContainer.classList.remove('hidden');
                } else {
                    authContainer.classList.add('hidden');
                }
            } else if (kategori === 'Umum') {
                delFields.classList.add('hidden');
                umumFields.classList.remove('hidden');
            }
        } else {
            // Jika Admin/Editor/Super Admin, semua opsi peserta disembunyikan
            katContainer.classList.add('hidden');
            delFields.classList.add('hidden');
            umumFields.classList.add('hidden');
        }
    }

    function openUserModal(action, data = null) {
        const modal = document.getElementById('modal-form-user');
        const box = modal.querySelector('.box-form');
        const form = document.getElementById('user-form');
        const title = document.getElementById('modal-form-title');
        const methodContainer = document.getElementById('method-container');
        const btnSubmit = document.getElementById('btn-submit-form');
        const passHint = document.getElementById('password-hint');
        const passInput = document.getElementById('input-password');

        form.reset();
        removeProfilePreview();

        if (action === 'create') {
            title.innerText = 'Tambah User Baru';
            form.action = "{{ route('admin.users.store') }}";
            methodContainer.innerHTML = ''; 
            btnSubmit.innerText = 'Tambahkan';
            passHint.classList.add('hidden');
            passInput.required = true;
        } else if (action === 'edit' && data) {
            title.innerText = 'Edit Data User';
            form.action = `/admin/users/update/${data.id}`;
            methodContainer.innerHTML = '@method("PUT")';
            btnSubmit.innerText = 'Simpan Perubahan';
            
            passHint.classList.remove('hidden');
            passInput.required = false;

            document.getElementById('input-name').value = data.name;
            document.getElementById('input-email').value = data.email;
            document.getElementById('input-nohp').value = data.no_hp || '';
            document.getElementById('input-tgl').value = data.tanggal_lahir || '';
            document.getElementById('input-role').value = data.role;
            
            if(data.kategori) document.getElementById('input-kategori').value = data.kategori;
            if(data.institusi) document.getElementById('input-institusi').value = data.institusi;
            if(data.peran_delegasi) document.getElementById('input-peran').value = data.peran_delegasi;
            if(data.auth_code) document.getElementById('input-authcode').value = data.auth_code;
            if(data.profesi) document.getElementById('input-profesi').value = data.profesi;

            if(data.profile_image) {
                const preview = document.getElementById('profile-preview');
                const placeholder = document.getElementById('profile-placeholder');
                
                preview.src = `/storage/${data.profile_image}`;
                preview.classList.remove('hidden');
                placeholder.classList.add('opacity-0');
            }
        }

        toggleFormFields();

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function closeUserModal() {
        const modal = document.getElementById('modal-form-user');
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