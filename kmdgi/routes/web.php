<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController; // <-- TAMBAHAN UNTUK PROFILE
use App\Http\Controllers\Admin\KampusController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\SyaratKetentuanController;
use App\Http\Controllers\Admin\KebijakanPrivasiController;
use App\Http\Controllers\Admin\PanduanDelegasiController;
use App\Http\Controllers\Admin\PenampilController;
use App\Http\Controllers\Admin\SponsorController;
use App\Http\Controllers\Admin\KolaboratorController;
use App\Http\Controllers\Admin\DokumentasiController;
use App\Http\Controllers\Admin\HeaderPublicController;
use App\Http\Controllers\Admin\AboutKmdgiController;
use App\Http\Controllers\Admin\SejarahKmdgiController;
use App\Http\Controllers\Admin\EdisiKmdgiController;
use App\Http\Controllers\Admin\DeskripsiKaryaController;
use App\Http\Controllers\Admin\JuknisLombaController;
use App\Http\Controllers\Admin\PesertaLombaController;
use App\Http\Controllers\Admin\EventKmdgiController;


// ================= HALAMAN UTAMA (Publik) =================

Route::get('/', function () {
    $header = \App\Models\HeaderPublic::first();

    $edisiAktif = \App\Models\EdisiKmdgi::where('is_active', 1)->first();
    $lombas = collect();
    $events = collect();

    if ($edisiAktif) {
        $lombas = \App\Models\JuknisLomba::where('edisi_kmdgi_id', $edisiAktif->id)->where('is_active', 1)->latest()->get();
        $events = \App\Models\EventKmdgi::where('edisi_kmdgi_id', $edisiAktif->id)->where('is_active', 1)->latest()->take(4)->get();
    }

    $penampils = \App\Models\Penampil::where('is_active', 1)->orderBy('tanggal_tampil', 'asc')->orderBy('jam_mulai', 'asc')->get()->groupBy('tanggal_tampil');
    $kolaborators = \App\Models\Kolaborator::where('is_active', 1)->orderBy('urutan', 'asc')->get();

    $sponsors = \App\Models\Sponsor::where('is_active', 1)
        ->orderBy('urutan', 'asc')
        ->orderByRaw("FIELD(tier_kelas, 'Utama (Besar)', 'Madya (Sedang)', 'Pratama (Kecil)') ASC")
        ->get()
        ->groupBy('kategori');

    $faqs = \App\Models\Faq::where('is_active', 1)->latest()->get();

    return view('welcome', compact('header', 'lombas', 'events', 'penampils', 'kolaborators', 'sponsors', 'faqs'));
})->name('home');

// ================= GUEST ROUTES (Belum Login) =================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login_proses'])->name('login-proses');

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register_proses'])->name('register-proses');
});

// ================= AUTH ROUTES (Sudah Login) =================
Route::middleware('auth')->group(function () {

    // Global Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Manajemen Profil User (Semua role yang login bisa akses ke sini)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/profile/password', [ProfileController::class, 'update'])->name('password.update');
    // -----------------------------------------------------
    // 1. DASHBOARD PESERTA (User Biasa: Delegasi & Umum)
    // -----------------------------------------------------
    Route::middleware('role:peserta')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // MANAGE TIM DELEGASI
        Route::get('/delegasi/tim', [\App\Http\Controllers\DelegasiController::class, 'manageTim'])->name('delegasi.tim');
        Route::patch('/delegasi/tim/{id}/update', [\App\Http\Controllers\DelegasiController::class, 'updateMember'])->name('delegasi.tim.update');
        Route::post('/delegasi/tim/{id}/remove', [\App\Http\Controllers\DelegasiController::class, 'removeMember'])->name('delegasi.tim.remove');

        Route::get('/delegasi/status', [\App\Http\Controllers\DelegasiController::class, 'statusKampus'])->name('delegasi.status');

        Route::get('/delegasi/berkas', [\App\Http\Controllers\DelegasiController::class, 'berkasTim'])->name('delegasi.berkas');
        Route::post('/delegasi/berkas/upload', [\App\Http\Controllers\DelegasiController::class, 'uploadBerkas'])->name('delegasi.berkas.upload');
    });
    // -----------------------------------------------------
    // 2. DASHBOARD PANEL BACK-END (Super Admin, Admin, Editor)
    // -----------------------------------------------------
    Route::middleware('role:super admin')->group(function () {
        Route::get('/superadmin/dashboard', [DashboardController::class, 'superadmin'])->name('superadmin.dashboard');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    });

    Route::middleware('role:editor')->group(function () {
        Route::get('/editor/dashboard', [DashboardController::class, 'editor'])->name('editor.dashboard');
    });

    // -----------------------------------------------------
    // 3. MANAJEMEN DATA MASTER & KONTEN (Workspace Admin)
    // -----------------------------------------------------
    Route::prefix('admin')->name('admin.')->group(function () {

        // A. HAK AKSES BACA DATA: Super Admin, Admin, dan Editor
        Route::middleware('role:super admin,admin,editor')->group(function () {
            Route::get('/kampus', [KampusController::class, 'index'])->name('kampus.index');
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');
            Route::get('/syarat-ketentuan', [SyaratKetentuanController::class, 'index'])->name('syarat.index');
            Route::get('/kebijakan-privasi', [KebijakanPrivasiController::class, 'index'])->name('kebijakan.index');
            Route::get('/penampil', [PenampilController::class, 'index'])->name('penampil.index');
            Route::get('/sponsor', [SponsorController::class, 'index'])->name('sponsor.index');
            Route::get('/kolaborator', [KolaboratorController::class, 'index'])->name('kolaborator.index');
            Route::get('/dokumentasi', [DokumentasiController::class, 'index'])->name('dokumentasi.index');
            Route::get('/kmdgi/sejarah', [SejarahKmdgiController::class, 'index'])->name('sejarah.index');
            Route::get('/kmdgi/edisi', [EdisiKmdgiController::class, 'index'])->name('edisi.index');
        });

        // B. HAK AKSES PENUH (CRUD): Hanya Super Admin & Admin
        Route::middleware('role:super admin,admin')->group(function () {

            // Kampus
            Route::post('/kampus/store', [KampusController::class, 'store'])->name('kampus.store');
            Route::put('/kampus/update/{id}', [KampusController::class, 'update'])->name('kampus.update');
            Route::delete('/kampus/destroy/{id}', [KampusController::class, 'destroy'])->name('kampus.destroy');

            // Users
            Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
            Route::put('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/destroy/{id}', [UserController::class, 'destroy'])->name('users.destroy');

            // FAQ
            Route::post('/faqs/store', [FaqController::class, 'store'])->name('faqs.store');
            Route::put('/faqs/update/{id}', [FaqController::class, 'update'])->name('faqs.update');
            Route::delete('/faqs/destroy/{id}', [FaqController::class, 'destroy'])->name('faqs.destroy');

            // Syarat Ketentuan
            Route::get('/syarat-ketentuan/create', [SyaratKetentuanController::class, 'create'])->name('syarat.create');
            Route::get('/syarat-ketentuan/edit/{id}', [SyaratKetentuanController::class, 'edit'])->name('syarat.edit');
            Route::post('/syarat-ketentuan/store', [SyaratKetentuanController::class, 'store'])->name('syarat.store');
            Route::put('/syarat-ketentuan/update/{id}', [SyaratKetentuanController::class, 'update'])->name('syarat.update');
            Route::delete('/syarat-ketentuan/destroy/{id}', [SyaratKetentuanController::class, 'destroy'])->name('syarat.destroy');

            // Kebijakan Privasi
            Route::get('/kebijakan-privasi/create', [KebijakanPrivasiController::class, 'create'])->name('kebijakan.create');
            Route::get('/kebijakan-privasi/edit/{id}', [KebijakanPrivasiController::class, 'edit'])->name('kebijakan.edit');
            Route::post('/kebijakan-privasi/store', [KebijakanPrivasiController::class, 'store'])->name('kebijakan.store');
            Route::put('/kebijakan-privasi/update/{id}', [KebijakanPrivasiController::class, 'update'])->name('kebijakan.update');
            Route::delete('/kebijakan-privasi/destroy/{id}', [KebijakanPrivasiController::class, 'destroy'])->name('kebijakan.destroy');

            // Pengaturan Settings (Panduan, Header, About KMDGI)
            Route::get('/pengaturan/panduan-delegasi', [PanduanDelegasiController::class, 'edit'])->name('panduan.edit');
            Route::put('/pengaturan/panduan-delegasi', [PanduanDelegasiController::class, 'update'])->name('panduan.update');
            Route::get('/pengaturan/header', [HeaderPublicController::class, 'edit'])->name('header.edit');
            Route::put('/pengaturan/header', [HeaderPublicController::class, 'update'])->name('header.update');
            Route::get('/kmdgi/about', [AboutKmdgiController::class, 'edit'])->name('about.edit');
            Route::put('/kmdgi/about', [AboutKmdgiController::class, 'update'])->name('about.update');

            // Penampil & Sponsor & Kolaborator & Dokumentasi (Create, Update, Delete)
            Route::get('/penampil/create', [PenampilController::class, 'create'])->name('penampil.create');
            Route::get('/penampil/edit/{id}', [PenampilController::class, 'edit'])->name('penampil.edit');
            Route::post('/penampil/store', [PenampilController::class, 'store'])->name('penampil.store');
            Route::put('/penampil/update/{id}', [PenampilController::class, 'update'])->name('penampil.update');
            Route::delete('/penampil/destroy/{id}', [PenampilController::class, 'destroy'])->name('penampil.destroy');
            Route::post('/penampil/update-urutan', [PenampilController::class, 'updateUrutan'])->name('penampil.update_urutan');

            Route::get('/sponsor/create', [SponsorController::class, 'create'])->name('sponsor.create');
            Route::get('/sponsor/edit/{id}', [SponsorController::class, 'edit'])->name('sponsor.edit');
            Route::post('/sponsor/store', [SponsorController::class, 'store'])->name('sponsor.store');
            Route::put('/sponsor/update/{id}', [SponsorController::class, 'update'])->name('sponsor.update');
            Route::delete('/sponsor/destroy/{id}', [SponsorController::class, 'destroy'])->name('sponsor.destroy');
            Route::post('/sponsor/update-urutan', [SponsorController::class, 'updateUrutan'])->name('sponsor.update_urutan');

            Route::get('/kolaborator/create', [KolaboratorController::class, 'create'])->name('kolaborator.create');
            Route::get('/kolaborator/edit/{id}', [KolaboratorController::class, 'edit'])->name('kolaborator.edit');
            Route::post('/kolaborator/store', [KolaboratorController::class, 'store'])->name('kolaborator.store');
            Route::put('/kolaborator/update/{id}', [KolaboratorController::class, 'update'])->name('kolaborator.update');
            Route::delete('/kolaborator/destroy/{id}', [KolaboratorController::class, 'destroy'])->name('kolaborator.destroy');
            Route::post('/kolaborator/update-urutan', [KolaboratorController::class, 'updateUrutan'])->name('kolaborator.update_urutan');

            Route::get('/dokumentasi/create', [DokumentasiController::class, 'create'])->name('dokumentasi.create');
            Route::get('/dokumentasi/edit/{id}', [DokumentasiController::class, 'edit'])->name('dokumentasi.edit');
            Route::post('/dokumentasi/store', [DokumentasiController::class, 'store'])->name('dokumentasi.store');
            Route::put('/dokumentasi/update/{id}', [DokumentasiController::class, 'update'])->name('dokumentasi.update');
            Route::delete('/dokumentasi/destroy/{id}', [DokumentasiController::class, 'destroy'])->name('dokumentasi.destroy');

            // Sejarah KMDGI
            Route::get('/kmdgi/sejarah/create', [SejarahKmdgiController::class, 'create'])->name('sejarah.create');
            Route::get('/kmdgi/sejarah/edit/{id}', [SejarahKmdgiController::class, 'edit'])->name('sejarah.edit');
            Route::post('/kmdgi/sejarah/store', [SejarahKmdgiController::class, 'store'])->name('sejarah.store');
            Route::put('/kmdgi/sejarah/update/{id}', [SejarahKmdgiController::class, 'update'])->name('sejarah.update');
            Route::delete('/kmdgi/sejarah/destroy/{id}', [SejarahKmdgiController::class, 'destroy'])->name('sejarah.destroy');

            // Edisi KMDGI
            Route::get('/kmdgi/edisi/create', [EdisiKmdgiController::class, 'create'])->name('edisi.create');
            Route::get('/kmdgi/edisi/edit/{id}', [EdisiKmdgiController::class, 'edit'])->name('edisi.edit');
            Route::post('/kmdgi/edisi/store', [EdisiKmdgiController::class, 'store'])->name('edisi.store');
            Route::put('/kmdgi/edisi/update/{id}', [EdisiKmdgiController::class, 'update'])->name('edisi.update');
            Route::delete('/kmdgi/edisi/destroy/{id}', [EdisiKmdgiController::class, 'destroy'])->name('edisi.destroy');
            Route::post('/kmdgi/edisi/set-active/{id}', [EdisiKmdgiController::class, 'setActive'])->name('edisi.set_active');

            // Deskripsi Karya
            Route::get('/kmdgi/karya/{kategori}', [DeskripsiKaryaController::class, 'edit'])->name('karya.edit');
            Route::put('/kmdgi/karya/{kategori}', [DeskripsiKaryaController::class, 'update'])->name('karya.update');

            // CRUD Juknis Lomba
            Route::get('/perlombaan/juknis', [JuknisLombaController::class, 'index'])->name('juknis.index');
            Route::get('/perlombaan/juknis/create', [JuknisLombaController::class, 'create'])->name('juknis.create');
            Route::get('/perlombaan/juknis/edit/{id}', [JuknisLombaController::class, 'edit'])->name('juknis.edit');
            Route::post('/perlombaan/juknis/store', [JuknisLombaController::class, 'store'])->name('juknis.store');
            Route::put('/perlombaan/juknis/update/{id}', [JuknisLombaController::class, 'update'])->name('juknis.update');
            Route::delete('/perlombaan/juknis/destroy/{id}', [JuknisLombaController::class, 'destroy'])->name('juknis.destroy');

            // Data Peserta Lomba
            Route::get('/perlombaan/peserta/export', [PesertaLombaController::class, 'export'])->name('peserta_lomba.export');
            Route::get('/perlombaan/peserta', [PesertaLombaController::class, 'index'])->name('peserta_lomba.index');
            Route::post('/perlombaan/peserta/verifikasi/{id}', [PesertaLombaController::class, 'verifikasiPembayaran'])->name('peserta_lomba.verifikasi');
            Route::delete('/perlombaan/peserta/destroy/{id}', [PesertaLombaController::class, 'destroy'])->name('peserta_lomba.destroy');

            // CRUD Data Event
            Route::get('/event', [EventKmdgiController::class, 'index'])->name('event.index');
            Route::get('/event/create', [EventKmdgiController::class, 'create'])->name('event.create');
            Route::get('/event/edit/{id}', [EventKmdgiController::class, 'edit'])->name('event.edit');
            Route::post('/event/store', [EventKmdgiController::class, 'store'])->name('event.store');
            Route::put('/event/update/{id}', [EventKmdgiController::class, 'update'])->name('event.update');
            Route::delete('/event/destroy/{id}', [EventKmdgiController::class, 'destroy'])->name('event.destroy');
        });
    });
});
