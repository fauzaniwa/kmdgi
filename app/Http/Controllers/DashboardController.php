<?php

namespace App\Http\Controllers;
use App\Models\SubmisiKarya;
use App\Models\KaryaKomentar;
use App\Models\Kampus;
use Illuminate\Support\Facades\Auth;

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

    // Tambahkan fungsi ini di dalam class DashboardController:
    public function likedPosts()
    {
        $user = Auth::user();

        // Tarik karya yang pernah di-like oleh user ini
        $karyas = SubmisiKarya::with(['user'])
            ->withCount(['komentars', 'likes'])
            ->whereHas('likes', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->paginate(12);

        $namaInstitusiList = $karyas->pluck('user.institusi')->filter()->unique();
        $kampusLogos = Kampus::whereIn('nama_institusi', $namaInstitusiList)->pluck('logo_institusi', 'nama_institusi');

        return view('dashboard.liked_posts', compact('karyas', 'kampusLogos', 'user'));
    }

    public function myComments()
    {
        $user = Auth::user();

        // Tarik komentar milik user ini beserta data karya tujuannya
        $komentars = KaryaKomentar::with(['submisiKarya.user'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('dashboard.my_comments', compact('komentars', 'user'));
    }
}
