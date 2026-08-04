<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\KampusController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\SyaratKetentuanController;
use App\Http\Controllers\Admin\KebijakanPrivasiController;
use App\Http\Controllers\Admin\PanduanDelegasiController;
use App\Http\Controllers\Admin\PenampilController;
use App\Http\Controllers\Admin\SponsorController;
use App\Http\Controllers\Admin\KolaboratorController;

// ================= HALAMAN UTAMA =================
Route::get('/', function () {
    return view('welcome');
});

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

    // -----------------------------------------------------
    // 1. DASHBOARD PESERTA (User Biasa: Delegasi & Umum)
    // -----------------------------------------------------
    Route::middleware('role:peserta')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
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
    // Membungkus semua rute manajemen dengan URL '/admin/...' dan nama 'admin....'
    Route::prefix('admin')->name('admin.')->group(function () {

        // A. HAK AKSES BACA DATA: Super Admin, Admin, dan Editor
        Route::middleware('role:super admin,admin,editor')->group(function () {
            // Halaman Manajemen Data Kampus (Hanya Read/View bagi Editor)
            Route::get('/kampus', [KampusController::class, 'index'])->name('kampus.index');

            // Rute Read Users
            Route::get('/users', [UserController::class, 'index'])->name('users.index');

            // Rute Read FAQs
            Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');

            // Rute Read Syarat & Ketentuan
            Route::get('/syarat-ketentuan', [SyaratKetentuanController::class, 'index'])->name('syarat.index');

            // Rute Read Kebijakan Privasi
            Route::get('/kebijakan-privasi', [KebijakanPrivasiController::class, 'index'])->name('kebijakan.index');

            // Rute Read Panduan Delegasi
            Route::get('/penampil', [PenampilController::class, 'index'])->name('penampil.index');
        });

        // B. HAK AKSES PENUH (CRUD): Hanya Super Admin & Admin
        Route::middleware('role:super admin,admin')->group(function () {
            // Aksi Manipulasi Data Kampus
            Route::post('/kampus/store', [KampusController::class, 'store'])->name('kampus.store');
            Route::put('/kampus/update/{id}', [KampusController::class, 'update'])->name('kampus.update');
            Route::delete('/kampus/destroy/{id}', [KampusController::class, 'destroy'])->name('kampus.destroy');

            // CRUD Users
            Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
            Route::put('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/destroy/{id}', [UserController::class, 'destroy'])->name('users.destroy');

            //CRUD FAQ
            Route::post('/faqs/store', [FaqController::class, 'store'])->name('faqs.store');
            Route::put('/faqs/update/{id}', [FaqController::class, 'update'])->name('faqs.update');
            Route::delete('/faqs/destroy/{id}', [FaqController::class, 'destroy'])->name('faqs.destroy');

            // CRUD Syarat & Ketentuan
            Route::get('/syarat-ketentuan/create', [SyaratKetentuanController::class, 'create'])->name('syarat.create');
            Route::get('/syarat-ketentuan/edit/{id}', [SyaratKetentuanController::class, 'edit'])->name('syarat.edit');
            Route::post('/syarat-ketentuan/store', [SyaratKetentuanController::class, 'store'])->name('syarat.store');
            Route::put('/syarat-ketentuan/update/{id}', [SyaratKetentuanController::class, 'update'])->name('syarat.update');
            Route::delete('/syarat-ketentuan/destroy/{id}', [SyaratKetentuanController::class, 'destroy'])->name('syarat.destroy');

            // CRUD Kebijakan Privasi
            Route::get('/kebijakan-privasi/create', [KebijakanPrivasiController::class, 'create'])->name('kebijakan.create');
            Route::get('/kebijakan-privasi/edit/{id}', [KebijakanPrivasiController::class, 'edit'])->name('kebijakan.edit');
            Route::post('/kebijakan-privasi/store', [KebijakanPrivasiController::class, 'store'])->name('kebijakan.store');
            Route::put('/kebijakan-privasi/update/{id}', [KebijakanPrivasiController::class, 'update'])->name('kebijakan.update');
            Route::delete('/kebijakan-privasi/destroy/{id}', [KebijakanPrivasiController::class, 'destroy'])->name('kebijakan.destroy');

            // CRUD Panduan Delegasi 
            Route::get('/panduan-delegasi', [PanduanDelegasiController::class, 'edit'])->name('panduan.edit');
            Route::put('/panduan-delegasi', [PanduanDelegasiController::class, 'update'])->name('panduan.update');

            // CRUD Penampil & Acara
            Route::get('/penampil/create', [PenampilController::class, 'create'])->name('penampil.create');
            Route::get('/penampil/edit/{id}', [PenampilController::class, 'edit'])->name('penampil.edit');
            Route::post('/penampil/store', [PenampilController::class, 'store'])->name('penampil.store');
            Route::put('/penampil/update/{id}', [PenampilController::class, 'update'])->name('penampil.update');
            Route::delete('/penampil/destroy/{id}', [PenampilController::class, 'destroy'])->name('penampil.destroy');
            Route::post('/penampil/update-urutan', [PenampilController::class, 'updateUrutan'])->name('penampil.update_urutan');

            // CRUD Sponsor & Kemitraan
            Route::post('/sponsor/update-urutan', [SponsorController::class, 'updateUrutan'])->name('sponsor.update_urutan');
            Route::get('/sponsor', [SponsorController::class, 'index'])->name('sponsor.index');
            Route::get('/sponsor/create', [SponsorController::class, 'create'])->name('sponsor.create');
            Route::get('/sponsor/edit/{id}', [SponsorController::class, 'edit'])->name('sponsor.edit');
            Route::post('/sponsor/store', [SponsorController::class, 'store'])->name('sponsor.store');
            Route::put('/sponsor/update/{id}', [SponsorController::class, 'update'])->name('sponsor.update');
            Route::delete('/sponsor/destroy/{id}', [SponsorController::class, 'destroy'])->name('sponsor.destroy');

            // CRUD Kolaborator
            Route::post('/kolaborator/update-urutan', [KolaboratorController::class, 'updateUrutan'])->name('kolaborator.update_urutan');
            Route::get('/kolaborator', [KolaboratorController::class, 'index'])->name('kolaborator.index');
            Route::get('/kolaborator/create', [KolaboratorController::class, 'create'])->name('kolaborator.create');
            Route::get('/kolaborator/edit/{id}', [KolaboratorController::class, 'edit'])->name('kolaborator.edit');
            Route::post('/kolaborator/store', [KolaboratorController::class, 'store'])->name('kolaborator.store');
            Route::put('/kolaborator/update/{id}', [KolaboratorController::class, 'update'])->name('kolaborator.update');
            Route::delete('/kolaborator/destroy/{id}', [KolaboratorController::class, 'destroy'])->name('kolaborator.destroy');
        });
    });
});
