<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Jika kamu menggunakan middleware pembagian role atau akses default peserta:
    public function index()
    {
        // Mengembalikan ke view dashboard yang baru kita buat
        return view('dashboard');
    }
    public function superadmin()
    {
        return "Ini adalah halaman Dashboard Super Admin"; // Ganti dengan return view('nama_view') nantinya
    }

    public function admin()
    {
        return "Ini adalah halaman Dashboard Admin";
    }

    public function editor()
    {
        return "Ini adalah halaman Dashboard Editor";
    }
}
