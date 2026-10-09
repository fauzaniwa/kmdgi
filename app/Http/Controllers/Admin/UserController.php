<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kampus;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    // ===========================================================================
    // [FUNGSI BARU] Menampilkan User Spesifik Berdasarkan Kampus & EXPORT EXCEL
    // ===========================================================================
    public function usersByKampus(Request $request, $kampus_id)
    {
        // Pastikan kampus ada
        $kampus = Kampus::findOrFail($kampus_id);

        // Filter user di mana kolom institusi sama dengan id kampus atau namanya
        $query = User::where(function($q) use ($kampus_id, $kampus) {
            $q->where('institusi', $kampus_id)
              ->orWhere('institusi', $kampus->nama_institusi);
        });

        // Fitur Pencarian Spesifik Kampus
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // =======================================================================
        // LOGIKA EXPORT DATA (Format HTML to Excel seperti KehadiranController)
        // =======================================================================
        if ($request->get('export') == 'true') {
            $users = $query->latest()->get(); // Ambil semua data hasil filter

            // [LOG AKTIVITAS]
            \App\Models\LogAktivitas::create([
                'user_id'    => \Illuminate\Support\Facades\Auth::id(),
                'modul'      => 'Manajemen User Kampus',
                'aksi'       => 'Export Excel',
                'deskripsi'  => 'Admin ' . \Illuminate\Support\Facades\Auth::user()->name . ' mengunduh data member delegasi institusi "' . $kampus->nama_institusi . '" ke Excel.',
                'ip_address' => $request->ip(),
            ]);

            // Format Nama File Excel
            $fileName = 'Member_' . str_replace(' ', '_', $kampus->nama_institusi) . '_' . date('Y-m-d_H-i') . '.xls';

            // Konstruksi Struktur HTML Table untuk MS Excel
            $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            $html .= '<head><meta http-equiv="content-type" content="text/html; charset=UTF-8">';
            $html .= '<style>
                        table { border-collapse: collapse; width: 100%; } 
                        th, td { border: 1px solid #cbd5e1; padding: 8px 12px; font-family: Arial, sans-serif; font-size: 11pt; vertical-align: middle; } 
                        th { background-color: #126CFD; color: #ffffff; font-weight: bold; text-align: center; }
                      </style></head>';
            $html .= '<body><table><thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Member</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Peran / Profesi</th>
                            <th>Status Akun</th>
                            <th>Waktu Terdaftar</th>
                        </tr>
                      </thead><tbody>';

            $no = 1;
            foreach ($users as $user) {
                $peran = $user->peran_delegasi ?? ($user->profesi ?? 'Belum diatur');
                
                $html .= '<tr>';
                $html .= '<td align="center">' . $no++ . '</td>';
                $html .= '<td>' . htmlspecialchars($user->name) . '</td>';
                $html .= '<td>' . htmlspecialchars($user->email) . '</td>';
                $html .= '<td align="center">' . htmlspecialchars($user->no_hp ?? '-') . '</td>';
                $html .= '<td align="center">' . htmlspecialchars($peran) . '</td>';
                $html .= '<td align="center">' . htmlspecialchars(ucfirst($user->role)) . '</td>';
                $html .= '<td align="center">' . $user->created_at->format('d M Y, H:i:s') . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody></table></body></html>';

            return response($html, 200, [
                "Content-Type"        => "application/vnd.ms-excel; charset=utf-8",
                "Content-Disposition" => "attachment; filename=\"$fileName\"",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ]);
        }
        // =======================================================================


        // =======================================================================
        // LOGIKA FILTER JUMLAH DATA (10, 50, 100, All Data)
        // =======================================================================
        $perPage = $request->get('per_page', 10);
        
        // Jika All Data, limit dipasang sebanyak total data (minimal 1 agar paginate tidak error)
        $limit = ($perPage === 'all') ? max($query->count(), 1) : (int) $perPage;

        $dataUsers = $query->latest()->paginate($limit)->withQueryString();
        // =======================================================================
        
        $dataKampus = Kampus::orderBy('nama_institusi', 'asc')->get();

        return view('admin.kampus.users', compact('dataUsers', 'kampus', 'dataKampus'));
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

        // [LOG AKTIVITAS] Menambahkan User Baru
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Manajemen User',
            'aksi'       => 'Create',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menambahkan pengguna baru: "' . $user->name . ' (' . ucfirst($user->role) . '".',
            'ip_address' => $request->ip(),
        ]);

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

        // [LOG AKTIVITAS] Mengupdate User
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Manajemen User',
            'aksi'       => 'Update',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' memperbarui data akun pengguna: "' . $user->name . ' (' . ucfirst($user->role) . '".',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroy(Request $request, $id) 
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

        // [LOG AKTIVITAS] Menghapus User
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Manajemen User',
            'aksi'       => 'Delete',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menghapus permanen akun pengguna "' . $namaUser . ' (' . ucfirst($roleUser) . '".',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Data user telah dihapus permanen!');
    }
}