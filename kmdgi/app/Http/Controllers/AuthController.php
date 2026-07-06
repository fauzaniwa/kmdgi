<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('auth.register');
    }

    // Placeholder untuk proses login nantinya
    public function login_proses(Request $request)
    {
        // Logika login akan kita buat di sini
    }

    // Placeholder untuk proses register nantinya
    public function register_proses(Request $request)
    {
        // Logika register akan kita buat di sini
    }

    // Placeholder untuk proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}