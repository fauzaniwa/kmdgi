<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Menampilkan halaman Login
    public function index()
    {
        return view('auth.login');
    }

    // Menampilkan halaman Register
    public function register()
    {
        // Mengambil semua data kampus dari database
        $campuses = \Illuminate\Support\Facades\DB::table('campuses')->get();

        // Kirim data ke view register
        return view('auth.register', compact('campuses'));
    }

    // Proses login dan pengecekan role
    public function login_proses(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek apakah user menekan checkbox "Ingat Saya"
        $remember = $request->has('remember');

        // 2. Coba autentikasi
        if (Auth::attempt($credentials, $remember)) {
            
            // 3. Regenerate session untuk mencegah Session Fixation attack
            $request->session()->regenerate();

            // 4. Ambil data role user yang sedang login
            $userRole = Auth::user()->role;

            // 5. Arahkan ke dashboard yang sesuai berdasarkan role
            if ($userRole === 'super admin') {
                return redirect()->route('superadmin.dashboard');
            } elseif ($userRole === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($userRole === 'editor') {
                return redirect()->route('editor.dashboard');
            } else {
                // Default untuk role 'peserta' (Kategori Delegasi & Umum)
                return redirect()->route('dashboard');
            }
        }

        // 6. Jika autentikasi gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau kata sandi yang kamu masukkan salah.',
        ])->onlyInput('email');
    }

    // Proses register
    public function register_proses(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'nama'                  => 'required|string|max:255',
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'required|string|min:8|confirmed', // Validasi min 8 karakter & cocok dengan password_confirmation
            'kategori'              => 'required|in:Delegasi,Umum',
            'tanggal_lahir'         => 'required|date',
            'no_hp'                 => 'required|string|max:20',
        ]);

        // 2. Simpan user baru ke database
        User::create([
            'name'           => $request->nama,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => 'peserta', // Default role pendaftar baru
            'kategori'       => $request->kategori,
            // Logika Conditional: Jika Umum, maka field delegasi dikosongkan (null)
            'peran_delegasi' => $request->kategori === 'Delegasi' ? $request->peran_delegasi : null,
            'institusi'      => $request->kategori === 'Delegasi' ? $request->institusi : null,
            // Logika Conditional: Auth code hanya masuk jika ia Anggota Delegasi
            'auth_code'      => ($request->kategori === 'Delegasi' && $request->peran_delegasi === 'Anggota Delegasi') ? $request->auth_code : null,
            'tanggal_lahir'  => $request->tanggal_lahir,
            'no_hp'          => $request->no_hp,
        ]);

        // 3. Arahkan kembali ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan masuk menggunakan akun kamu.');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}