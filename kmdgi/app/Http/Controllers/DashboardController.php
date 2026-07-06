<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
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