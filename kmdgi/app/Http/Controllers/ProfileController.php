<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User; // <-- Pastikan Model User di-import untuk melakukan query

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman form edit profil.
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Memproses pembaruan data profil pengguna yang sedang login.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // ==============================================================
        // SKENARIO 1: Request berasal dari Alert Form di Dashboard 
        // (Form "Join Delegasi" - Mencocokkan Auth Code dengan Ketua)
        // ==============================================================
        if ($request->has('institusi') && $request->has('auth_code')) {
            $request->validate([
                'institusi' => 'required|string|max:255',
                'auth_code' => 'required|string|max:255',
            ]);

            // Cek apakah ada Ketua Delegasi di kampus tersebut dengan Auth Code yang sesuai
            $ketua = User::where('institusi', $request->institusi)
                         ->where('peran_delegasi', 'Ketua')
                         ->where('auth_code', $request->auth_code)
                         ->first();

            // Jika tidak ditemukan (Auth code salah atau ketua belum daftar)
            if (!$ketua) {
                return redirect()->back()->withErrors([
                    'auth_code' => 'Auth Code tidak valid atau tidak cocok dengan Ketua Delegasi di institusi tersebut.'
                ]);
            }

            // Jika valid/cocok, gabungkan anggota ini ke dalam delegasi kampus tersebut
            $user->institusi = $request->institusi;
            $user->auth_code = $request->auth_code;
            $user->save();

            return redirect()->back()->with('success', 'Berhasil! Anda telah terhubung dengan tim delegasi ' . $request->institusi . '.');
        }


        // ==============================================================
        // SKENARIO 2: Request berasal dari Halaman Edit Profil Utama
        // ==============================================================
        
        $rules = [
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'no_hp'         => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password'      => 'nullable|string|min:8|confirmed', 
        ];

        // Field profesi hanya divalidasi jika user BUKAN Delegasi
        if ($user->kategori !== 'Delegasi') {
            $rules['profesi'] = 'nullable|string|max:255';
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->tanggal_lahir = $request->tanggal_lahir;

        if ($user->kategori !== 'Delegasi' && $request->has('profesi')) {
            $user->profesi = $request->profesi;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $user->profile_image = $request->file('profile_image')->store('profiles', 'public');
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * Menghapus akun pengguna
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun Anda telah berhasil dihapus.');
    }
}