<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Kampus; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; 
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
        $dataKampus = Kampus::orderBy('nama_institusi', 'asc')->get();
        return view('auth.register', compact('dataKampus'));
    }

    // Proses login dan pengecekan role
    public function login_proses(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $userRole = Auth::user()->role;

            if ($userRole === 'super admin') {
                return redirect()->route('superadmin.dashboard');
            } elseif ($userRole === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($userRole === 'editor') {
                return redirect()->route('editor.dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang kamu masukkan salah.',
        ])->onlyInput('email');
    }

    // Proses register
    public function register_proses(Request $request)
    {
        $request->validate([
            'nama'                  => 'required|string|max:255',
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'required|string|min:8|confirmed',
            'kategori'              => 'required|in:Delegasi,Umum',
            'tanggal_lahir'         => 'required|date',
            'no_hp'                 => 'required|string|max:20',
            'profile_image'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'profesi'               => 'nullable|string|max:255', 
        ]);

        $authCode = null;

        if ($request->kategori === 'Delegasi') {
            if ($request->peran_delegasi === 'Ketua') {
                
                // 1. Cek apakah kampus sudah memiliki Ketua
                $existingKetua = User::where('institusi', $request->institusi)
                                     ->where('peran_delegasi', 'Ketua')
                                     ->first();

                if ($existingKetua) {
                    return redirect()->back()
                                     ->withInput() 
                                     ->with('ketua_exists', $request->institusi); 
                }

                // 2. Generate Auth Code Otomatis (Contoh: KMDGIXY12)
                $authCode = 'KMDGI' . strtoupper(Str::random(4));

            } elseif ($request->peran_delegasi === 'Anggota Delegasi') {
                
                // 3. Validasi Auth Code (HANYA JIKA DIISI / OPSIONAL)
                if ($request->filled('auth_code')) {
                    $ketua = User::where('institusi', $request->institusi)
                                 ->where('peran_delegasi', 'Ketua')
                                 ->where('auth_code', $request->auth_code)
                                 ->first();

                    // Jika Auth Code diisi namun salah/tidak cocok dengan ketua di kampus tsb
                    if (!$ketua) {
                        return redirect()->back()
                                         ->withInput()
                                         ->withErrors(['auth_code' => 'Auth Code tidak valid atau tidak cocok dengan Ketua Delegasi di institusi tersebut.']);
                    }

                    $authCode = $request->auth_code;
                }
            }
        }

        // Handle Upload File Foto Profil
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profiles', 'public');
        }

        // Simpan user baru ke database
        User::create([
            'name'           => $request->nama,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => 'peserta',
            'kategori'       => $request->kategori,
            'peran_delegasi' => $request->kategori === 'Delegasi' ? $request->peran_delegasi : null,
            'institusi'      => $request->kategori === 'Delegasi' ? $request->institusi : null,
            'auth_code'      => $authCode, 
            'profesi'        => $request->kategori === 'Umum' ? $request->profesi : null, 
            'tanggal_lahir'  => $request->tanggal_lahir,
            'no_hp'          => $request->no_hp,
            'profile_image'  => $profileImagePath, 
        ]);

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