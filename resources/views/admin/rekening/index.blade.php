@extends('layouts.app')
@section('title', 'Manajemen Rekening & QRIS - Admin KMDGI')

@section('content')
<div class="bg-slate-50 min-h-screen flex flex-col">
    @include('partials.navbar')
    <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex-grow flex flex-col lg:flex-row gap-8 py-8 font-sans">
        @include('partials.sidebar-admin')

        <main class="flex-grow space-y-6 w-full">
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl flex items-center justify-between shadow-sm">
                <span class="text-sm font-semibold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    {{ session('success') }}
                </span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">✖</button>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-2xl shadow-sm text-sm">
                <ul class="list-disc pl-5">@foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            </div>
            @endif

            <!-- HEADER -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan Tujuan Pembayaran</h2>
                    <p class="text-sm text-slate-500 mt-1">Kelola nomor rekening bank dan QRIS yang akan dilihat peserta saat mendaftar lomba.</p>
                </div>
                <button type="button" onclick="openModalTambah()" class="bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-3.5 px-6 rounded-xl text-sm transition-all shadow-md shadow-blue-500/20">
                    + Tambah Tujuan Baru
                </button>
            </div>

            <!-- TABEL DATA -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-4 px-6">Bank / Metode</th>
                                <th class="py-4 px-4">Atas Nama & Rekening</th>
                                <th class="py-4 px-4">QRIS / Logo</th>
                                <th class="py-4 px-4 text-center">Status</th>
                                <th class="py-4 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($rekenings as $rek)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    {{ $rek->nama_bank }}
                                </td>
                                <td class="py-4 px-4">
                                    <p class="font-bold text-slate-800">{{ $rek->nomor_rekening ?? 'Tanpa Rekening (QRIS Saja)' }}</p>
                                    <p class="text-xs text-slate-500">A.N: {{ $rek->atas_nama }}</p>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        @if($rek->logo_bank)
                                            <div class="w-10 h-10 bg-slate-100 rounded-lg p-1 border border-slate-200 flex items-center justify-center">
                                                <img src="{{ asset('storage/'.$rek->logo_bank) }}" class="w-full h-full object-contain">
                                            </div>
                                        @endif
                                        @if($rek->qr_code)
                                            <div class="w-10 h-10 bg-slate-100 rounded-lg p-1 border border-slate-200 flex items-center justify-center">
                                                <img src="{{ asset('storage/'.$rek->qr_code) }}" class="w-full h-full object-contain">
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if($rek->is_active)
                                        <span class="bg-emerald-50 text-emerald-600 font-bold text-xs px-2.5 py-1 rounded-lg border border-emerald-100">Aktif</span>
                                    @else
                                        <span class="bg-slate-100 text-slate-500 font-bold text-xs px-2.5 py-1 rounded-lg border border-slate-200">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center space-x-2">
                                    <button type="button" onclick="openModalEdit({{ json_encode($rek) }})" class="bg-blue-50 hover:bg-blue-100 text-[#1A68FF] font-bold text-xs px-3 py-2 rounded-xl transition-colors">Edit</button>
                                    <form action="{{ route('admin.rekening.destroy', $rek->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus tujuan pembayaran ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-500 font-bold text-xs px-3 py-2 rounded-xl transition-colors">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 italic">Belum ada tujuan pembayaran yang ditambahkan.</td>
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

<!-- MODAL FORM (TAMBAH / EDIT) -->
<div id="modal-rekening-form" class="fixed inset-0 z-[80] hidden opacity-0 transition-opacity flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModalForm()"></div>
    <div class="relative bg-white rounded-[2rem] p-6 md:p-8 w-full max-w-lg shadow-2xl transform scale-95 transition-transform box-form">
        <h3 id="modal-title" class="text-xl font-bold text-slate-900 mb-5 border-b border-slate-100 pb-3">Tambah Tujuan Pembayaran</h3>
        
        <form id="form-rekening" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Nama Bank / Metode / E-Wallet <span class="text-red-500">*</span></label>
                <input type="text" name="nama_bank" id="input-nama-bank" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#1A68FF]" placeholder="Contoh: BCA / QRIS Nasional / DANA">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Atas Nama (A.N) <span class="text-red-500">*</span></label>
                <input type="text" name="atas_nama" id="input-atas-nama" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#1A68FF]" placeholder="Contoh: Panitia KMDGI 16">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Nomor Rekening / No HP</label>
                <input type="text" name="nomor_rekening" id="input-nomor-rekening" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:border-[#1A68FF]" placeholder="Contoh: 1234567890 (Kosongkan jika hanya QRIS)">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Logo Bank (Opsional)</label>
                    <input type="file" name="logo_bank" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-[#1A68FF] cursor-pointer">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Gambar QRIS (Opsional)</label>
                    <input type="file" name="qr_code" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-[#1A68FF] cursor-pointer">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Status Tampil</label>
                <select name="is_active" id="input-is-active" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#1A68FF]">
                    <option value="1">Aktifkan (Tampilkan ke Peserta)</option>
                    <option value="0">Sembunyikan</option>
                </select>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeModalForm()" class="flex-1 bg-white border border-slate-200 text-slate-600 font-bold py-3 rounded-xl hover:bg-slate-50 text-sm">Batal</button>
                <button type="submit" class="flex-1 bg-[#1A68FF] hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm shadow-md">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModalTambah() {
        document.getElementById('modal-title').innerText = "Tambah Tujuan Pembayaran";
        document.getElementById('form-rekening').action = "{{ route('admin.rekening.index') }}"; // atau store
        document.getElementById('form-method').value = "POST";
        document.getElementById('input-nama-bank').value = "";
        document.getElementById('input-atas-nama').value = "";
        document.getElementById('input-nomor-rekening').value = "";
        document.getElementById('input-is-active').value = "1";

        // Ubah action rute store secara eksplisit
        document.getElementById('form-rekening').action = "{{ url('/admin/rekening/store') }}";

        const modal = document.getElementById('modal-rekening-form');
        const box = modal.querySelector('.box-form');
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); box.classList.remove('scale-95'); }, 10);
    }

    function openModalEdit(data) {
        document.getElementById('modal-title').innerText = "Edit Tujuan Pembayaran";
        document.getElementById('form-rekening.action');
        document.getElementById('form-rekening').action = `/admin/rekening/update/${data.id}`;
        document.getElementById('form-method').value = "PUT";
        
        document.getElementById('input-nama-bank').value = data.nama_bank;
        document.getElementById('input-atas-nama').value = data.atas_nama;
        document.getElementById('input-nomor-rekening').value = data.nomor_rekening || '';
        document.getElementById('input-is-active').value = data.is_active;

        const modal = document.getElementById('modal-rekening-form');
        const box = modal.querySelector('.box-form');
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); box.classList.remove('scale-95'); }, 10);
    }

    function closeModalForm() {
        const modal = document.getElementById('modal-rekening-form');
        const box = modal.querySelector('.box-form');
        modal.classList.add('opacity-0');
        box.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
</script>
@endsection