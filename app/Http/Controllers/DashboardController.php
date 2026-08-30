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
        return view('admin.dashboard');
    }

    public function admin()
    {
        return view('admin.dashboard');
    }

    public function editor()
    {
        return view('admin.dashboard');
    }
}
