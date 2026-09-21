<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kampus;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Fitur Pencarian (Nama, Email, atau Institusi)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('institusi', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Role
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Filter Kategori
        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori', $request->kategori);
        }

        // Pagination
        $dataUsers = $query->latest()->paginate(10)->withQueryString();
        
        // Ambil data kampus untuk dropdown pilihan di form
        $dataKampus = Kampus::orderBy('nama_institusi', 'asc')->get();

        return view('admin.users.index', compact('dataUsers', 'dataKampus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:8',
            'role'          => 'required|in:super admin,admin,editor,peserta',
            'kategori'      => 'nullable|string|in:Delegasi,Umum',
            'tanggal_lahir' => 'nullable|date',
            'no_hp'         => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi foto
            'profesi'       => 'nullable|string|max:255', // Validasi profesi
        ]);

        $data = $request->except('password');
        $data['password'] = Hash::make($request->password);

        // Handle upload foto profil
        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        // Logika Filter Data
        if ($request->role !== 'peserta') {
            $data['kategori'] = null;
            $data['peran_delegasi'] = null;
            $data['institusi'] = null;
            $data['auth_code'] = null;
            $data['profesi'] = null;
        } else {
            if ($request->kategori === 'Umum') {
                $data['peran_delegasi'] = null;
                $data['institusi'] = null;
                $data['auth_code'] = null;
            } elseif ($request->kategori === 'Delegasi') {
                $data['profesi'] = null; // Delegasi tidak perlu data profesi
            }
        }

        $user = User::create($data);

        // ===========================================================================
        // [LOG AKTIVITAS] Menambahkan User Baru
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Manajemen User',
            'aksi'       => 'Create',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menambahkan pengguna baru: "' . $user->name . ' (' . ucfirst($user->role) . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Data user berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email,'.$id,
            'password'      => 'nullable|string|min:8',
            'role'          => 'required|in:super admin,admin,editor,peserta',
            'kategori'      => 'nullable|string|in:Delegasi,Umum',
            'tanggal_lahir' => 'nullable|date',
            'no_hp'         => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'profesi'       => 'nullable|string|max:255',
        ]);

        $data = $request->except('password');
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle update foto profil (Hapus yang lama jika ada gambar baru)
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        // Logika Filter Data
        if ($request->role !== 'peserta') {
            $data['kategori'] = null;
            $data['peran_delegasi'] = null;
            $data['institusi'] = null;
            $data['auth_code'] = null;
            $data['profesi'] = null;
        } else if ($request->kategori === 'Umum') {
            $data['peran_delegasi'] = null;
            $data['institusi'] = null;
            $data['auth_code'] = null;
        } elseif ($request->kategori === 'Delegasi') {
            $data['profesi'] = null;
        }

        $user->update($data);

        // ===========================================================================
        // [LOG AKTIVITAS] Mengupdate User
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Manajemen User',
            'aksi'       => 'Update',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' memperbarui data akun pengguna: "' . $user->name . ' (' . ucfirst($user->role) . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroy(Request $request, $id) // <-- Tambahkan parameter Request
    {
        $user = User::findOrFail($id);
        
        // Proteksi agar Super Admin tidak menghapus dirinya sendiri secara tidak sengaja
        if (auth()->id() == $user->id) {
            return redirect()->back()->withErrors(['Error' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $namaUser = $user->name;
        $roleUser = $user->role;

        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->delete();

        // ===========================================================================
        // [LOG AKTIVITAS] Menghapus User
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Manajemen User',
            'aksi'       => 'Delete',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menghapus permanen akun pengguna "' . $namaUser . ' (' . ucfirst($roleUser) . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Data user telah dihapus permanen!');
    }
}