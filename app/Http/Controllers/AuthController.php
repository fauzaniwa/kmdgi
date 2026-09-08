<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Kampus;
use App\Models\TiketPeserta;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\KebijakanPrivasi;
use App\Models\SyaratKetentuan;

class AuthController extends Controller
{
    // Menampilkan halaman Login
    public function index()
    {
        // Menarik data legalitas yang aktif untuk ditampilkan di modal pop-up
        $syarat = SyaratKetentuan::where('is_active', 1)->first();
        $privasi = KebijakanPrivasi::where('is_active', 1)->first();

        // Pastikan hanya memanggil return satu kali di bawah ini dengan menyertakan variabel
        return view('auth.login', compact('syarat', 'privasi'));
    }

    // Menampilkan halaman Register
    public function register()
    {
        $dataKampus = Kampus::orderBy('nama_institusi', 'asc')->get();

        // Tarik data legalitas yang aktif
        $syarat = SyaratKetentuan::where('is_active', 1)->first();
        $privasi = KebijakanPrivasi::where('is_active', 1)->first();

        return view('auth.register', compact('dataKampus', 'syarat', 'privasi'));
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

                // 2. Generate Auth Code Otomatis
                $authCode = 'KMDGI' . strtoupper(Str::random(4));
            } elseif ($request->peran_delegasi === 'Anggota Delegasi') {

                // 3. Validasi Auth Code
                if ($request->filled('auth_code')) {
                    $ketua = User::where('institusi', $request->institusi)
                        ->where('peran_delegasi', 'Ketua')
                        ->where('auth_code', $request->auth_code)
                        ->first();

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

        // SIMPAN USER
        $user = User::create([
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

        // GENERATE TIKET PAMERAN DEFAULT
        do {
            $kodeUnik = strtoupper(Str::random(5));
            $kodeTiket = 'KM16' . $kodeUnik . 'DGI';
        } while (TiketPeserta::where('kode_tiket', $kodeTiket)->exists());

        // Simpan tiket default ke tabel
        TiketPeserta::create([
            'user_id'        => $user->id,
            'event_kmdgi_id' => null, // Pameran tidak terikat spesifik ke event
            'jenis_tiket'    => 'Pameran',
            'kode_tiket'     => $kodeTiket,
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
