@extends('layouts.app')

@section('title', 'Manage Tim Delegasi - KMDGI 16')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col relative">

    @include('partials.navbar')

    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8">
        
        @include('partials.sidebar')

        <main class="flex-grow space-y-8 w-full relative z-10">
            
            <!-- Header Halaman -->
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Manage Tim Delegasi</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola anggota kontingen dari <span class="font-bold text-slate-700">{{ Auth::user()->institusi }}</span>.</p>
            </div>

            <!-- Notifikasi Pesan Sukses -->
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
            @endif

            <!-- Notifikasi Error Validasi -->
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-2xl shadow-sm">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Kode Auth Banner Info -->
            <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-5 flex items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-indigo-900">Auth Code Tim</h4>
                        <code class="text-xs font-mono font-bold text-indigo-600 tracking-wider">{{ Auth::user()->auth_code }}</code>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-black text-indigo-900">{{ $anggotaTim->count() }}</span>
                    <span class="text-xs text-indigo-700 block -mt-1 font-semibold">Anggota</span>
                </div>
            </div>

            <!-- Tabel Data Anggota -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Profil Anggota</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kontak</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Bergabung Pada</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($anggotaTim as $anggota)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden flex-shrink-0">
                                            @if($anggota->profile_image)
                                                <img src="{{ asset('storage/' . $anggota->profile_image) }}" alt="Avatar" class="w-full h-full object-cover">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($anggota->name) }}&background=126CFD&color=fff" alt="Avatar" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-sm">{{ $anggota->name }}</div>
                                            <div class="text-[11px] text-slate-500 bg-slate-100 inline-block px-2 py-0.5 rounded-md mt-1">{{ $anggota->peran_delegasi }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-700">{{ $anggota->email }}</div>
                                    <div class="text-xs text-slate-500">{{ $anggota->no_hp ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-slate-600">{{ $anggota->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Tombol Edit -->
                                        <button type="button" 
                                            onclick="openEditModal('{{ $anggota->id }}', '{{ addslashes($anggota->name) }}', '{{ $anggota->email }}', '{{ $anggota->no_hp }}')"
                                            class="inline-flex items-center justify-center text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg text-xs font-bold transition-colors" title="Edit Profil">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        
                                        <!-- Tombol Keluarkan -->
                                        <button type="button" 
                                            onclick="openDeleteModal('{{ $anggota->id }}', '{{ addslashes($anggota->name) }}')"
                                            class="inline-flex items-center justify-center text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-lg text-xs font-bold transition-colors">
                                            Keluarkan
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                    <div class="inline-flex w-12 h-12 rounded-full bg-slate-100 items-center justify-center text-slate-400 mb-3">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    </div>
                                    <p class="text-sm font-medium">Belum ada anggota yang bergabung menggunakan Auth Code Anda.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    @include('partials.footer')
</div>

<!-- ========================================== -->
<!-- MODAL EDIT PROFIL ANGGOTA                  -->
<!-- ========================================== -->
<div id="editModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-all duration-300 opacity-0 pointer-events-none">
    <div class="bg-white rounded-3xl p-6 md:p-8 max-w-md w-full shadow-2xl transform transition-all scale-95" id="editModalContent">
        
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-black text-slate-900 leading-tight">Edit Anggota</h3>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-red-500 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PATCH')
            
            <div class="space-y-4">
                <div>
                    <label for="edit_name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_name" name="name" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1A68FF]/50 focus:border-[#1A68FF] transition-all">
                </div>
                <div>
                    <label for="edit_email" class="block text-sm font-semibold text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="edit_email" name="email" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1A68FF]/50 focus:border-[#1A68FF] transition-all">
                </div>
                <div>
                    <label for="edit_no_hp" class="block text-sm font-semibold text-slate-700 mb-1">No. Handphone/WA</label>
                    <input type="text" id="edit_no_hp" name="no_hp" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1A68FF]/50 focus:border-[#1A68FF] transition-all">
                </div>
            </div>

            <div class="flex gap-3 mt-8">
                <button type="button" onclick="closeEditModal()" class="w-1/3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-4 rounded-xl transition-colors text-sm">
                    Batal
                </button>
                <button type="submit" class="w-2/3 bg-kmdgi-primary hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors shadow-sm text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL KONFIRMASI KELUARKAN ANGGOTA         -->
<!-- ========================================== -->
<div id="deleteModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-all duration-300 opacity-0 pointer-events-none">
    <div class="bg-white rounded-3xl p-6 md:p-8 max-w-sm w-full shadow-2xl transform transition-all scale-95 text-center" id="deleteModalContent">
        
        <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-red-100">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        
        <h3 class="text-xl font-black text-slate-900 leading-tight mb-2">Keluarkan Anggota?</h3>
        <p class="text-sm text-slate-500 mb-6 leading-relaxed">
            Apakah Anda yakin ingin mengeluarkan <strong id="delete_name_text" class="text-slate-800"></strong> dari kontingen? Anggota ini harus mendaftar ulang Auth Code jika ingin bergabung kembali.
        </p>
        
        <form id="deleteForm" method="POST" action="">
            @csrf
            <!-- Endpoint aslinya membutuhkan POST (karena di web.php menggunakan POST) -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="button" onclick="closeDeleteModal()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-4 rounded-xl transition-colors text-sm">
                    Batal
                </button>
                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-4 rounded-xl transition-colors shadow-sm shadow-red-500/20 text-sm">
                    Ya, Keluarkan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // FUNGSI UNTUK MODAL EDIT
    const editModal = document.getElementById('editModal');
    const editModalContent = document.getElementById('editModalContent');
    const editForm = document.getElementById('editForm');

    function openEditModal(id, name, email, no_hp) {
        // Set Action URL
        editForm.action = `/delegasi/tim/${id}/update`;
        
        // Populate Data
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_no_hp').value = no_hp;

        // Show Modal
        editModal.classList.remove('opacity-0', 'pointer-events-none');
        editModalContent.classList.remove('scale-95');
        editModalContent.classList.add('scale-100');
    }

    function closeEditModal() {
        editModal.classList.add('opacity-0', 'pointer-events-none');
        editModalContent.classList.remove('scale-100');
        editModalContent.classList.add('scale-95');
    }

    // FUNGSI UNTUK MODAL HAPUS/KELUARKAN
    const deleteModal = document.getElementById('deleteModal');
    const deleteModalContent = document.getElementById('deleteModalContent');
    const deleteForm = document.getElementById('deleteForm');

    function openDeleteModal(id, name) {
        // Set Action URL
        deleteForm.action = `/delegasi/tim/${id}/remove`;
        
        // Tampilkan nama di dalam text warning
        document.getElementById('delete_name_text').innerText = name;

        // Show Modal
        deleteModal.classList.remove('opacity-0', 'pointer-events-none');
        deleteModalContent.classList.remove('scale-95');
        deleteModalContent.classList.add('scale-100');
    }

    function closeDeleteModal() {
        deleteModal.classList.add('opacity-0', 'pointer-events-none');
        deleteModalContent.classList.remove('scale-100');
        deleteModalContent.classList.add('scale-95');
    }

    // Tutup modal jika user klik area gelap (backdrop)
    window.onclick = function(event) {
        if (event.target == editModal) {
            closeEditModal();
        }
        if (event.target == deleteModal) {
            closeDeleteModal();
        }
    }
</script>
@endsection