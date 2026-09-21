<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KatalogEventController;
use App\Http\Controllers\KolaboratorController as PublicKolaboratorController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\KatalogKaryaController;
use App\Http\Controllers\DokumentasiController as PublicDokumentasiController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\NotifikasiController;
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
use App\Http\Controllers\Admin\VerifikasiTiketEventController;
use App\Http\Controllers\Admin\VerifikasiTiketPerformanceController;
use App\Http\Controllers\Admin\VerifikasiTiketPameranController;
use App\Http\Controllers\Admin\PesertaEventController;
use App\Http\Controllers\Admin\PesertaPerformanceController;
use App\Http\Controllers\Admin\PesertaPameranController;
use App\Http\Controllers\DelegasiSubmisiController;
use App\Http\Controllers\Admin\VerifikasiKaryaController;
use App\Http\Controllers\Admin\KomentarController;
use App\Http\Controllers\Admin\RekeningPembayaranController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DelegasiController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Admin\ScanQRController;
use App\Http\Controllers\Admin\KehadiranEventController;
use App\Http\Controllers\Admin\KehadiranPerformanceController;
use App\Http\Controllers\Admin\KehadiranPameranController;

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

    $dokumentasis = \App\Models\Dokumentasi::where('is_active', 1)->orderBy('tanggal_kegiatan', 'desc')->take(6)->get();

    return view('welcome', compact('header', 'lombas', 'events', 'penampils', 'kolaborators', 'sponsors', 'faqs', 'dokumentasis'));
})->name('home');

// <-- RUTE HALAMAN STATIS INFO KMDGI (Publik) -->
Route::get('/tentang-kami', [PageController::class, 'tentangKami'])->name('tentang-kami');
Route::get('/panduan-delegasi', [PageController::class, 'panduanDelegasi'])->name('panduan-delegasi');

// <-- RUTE DOKUMENTASI (Publik) -->
Route::get('/dokumentasi', [PublicDokumentasiController::class, 'index'])->name('dokumentasi.index');

// <-- RUTE KATALOG EVENT (Publik) -->
Route::get('/acara', [KatalogEventController::class, 'index'])->name('katalog.event');
Route::get('/acara/{slug}', [\App\Http\Controllers\KatalogEventController::class, 'show'])->name('katalog.event.show');

// <-- RUTE KOLABORATOR (Publik) -->
Route::get('/kolaborator', [PublicKolaboratorController::class, 'index'])->name('kolaborator.index');
Route::get('/kolaborator/{slug}', [PublicKolaboratorController::class, 'show'])->name('kolaborator.show');

// <-- RUTE PERFORMANCE (Publik) -->
Route::get('/performance', [PerformanceController::class, 'index'])->name('performance.index');
Route::get('/performance/{slug}', [PerformanceController::class, 'show'])->name('performance.show');

// <-- RUTE KATALOG KARYA (Publik) -->
Route::get('/katalog-karya', [KatalogKaryaController::class, 'index'])->name('katalog.karya.index');
Route::get('/katalog-karya/{slug}', [KatalogKaryaController::class, 'show'])->name('katalog.karya.show');
Route::post('/katalog-karya/{id}/like', [KatalogKaryaController::class, 'like'])->name('katalog.karya.like');
Route::post('/katalog-karya/{id}/share', [KatalogKaryaController::class, 'recordShare'])->name('katalog.karya.share');

// <-- RUTE KOMPETISI (Publik) -->
Route::get('/kompetisi', [\App\Http\Controllers\KompetisiController::class, 'index'])->name('kompetisi.index');
Route::get('/kompetisi/{slug}', [\App\Http\Controllers\KompetisiController::class, 'show'])->name('kompetisi.show');
Route::get('/kompetisi/{slug}/daftar', [\App\Http\Controllers\KompetisiController::class, 'daftar'])->name('kompetisi.daftar');
Route::post('/kompetisi/{slug}/daftar', [\App\Http\Controllers\KompetisiController::class, 'storeDaftar'])->name('kompetisi.store_daftar');

// ================= GUEST ROUTES (Belum Login) =================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login_proses'])->name('login-proses');

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register_proses'])->name('register-proses');

    Route::get('/lupa-kata-sandi', [AuthController::class, 'forgotPasswordForm'])->name('password.request');
    Route::post('/lupa-kata-sandi', [AuthController::class, 'sendOtp'])->name('password.email');

    Route::get('/verifikasi-otp', [AuthController::class, 'verifyOtpForm'])->name('password.verify-otp');
    Route::post('/verifikasi-otp', [AuthController::class, 'verifyOtp'])->name('password.verify-otp.post');

    Route::get('/reset-kata-sandi', [AuthController::class, 'resetPasswordForm'])->name('password.reset');
    Route::post('/reset-kata-sandi', [AuthController::class, 'resetPassword'])->name('password.update');
});

// ================= AUTH ROUTES (Sudah Login) =================
Route::middleware('auth')->group(function () {

    // Global Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Manajemen Profil User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/profile/password', [ProfileController::class, 'update'])->name('password.update');

    // Menu Interaksi Akun
    Route::get('/karya-disukai', [DashboardController::class, 'likedPosts'])->name('liked-posts');
    Route::get('/komentar-saya', [DashboardController::class, 'myComments'])->name('my-comments');

    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/baca/{id}', [NotificationController::class, 'readAndRedirect'])->name('notifikasi.read');
    Route::post('/notifikasi/baca-semua', [NotificationController::class, 'markAllRead'])->name('notifikasi.markAllRead');

    Route::post('/delegasi/submisi/komentar', [DelegasiSubmisiController::class, 'storeKomentar'])->name('delegasi.submisi.komentar.store');
    Route::post('/delegasi/submisi/komentar/report', [DelegasiSubmisiController::class, 'reportKomentar'])->name('delegasi.submisi.komentar.report');

    // -----------------------------------------------------
    // 1. DASHBOARD PESERTA (User Biasa: Delegasi & Umum)
    // -----------------------------------------------------
    Route::middleware('role:peserta')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/peserta/status-lomba', [\App\Http\Controllers\DashboardController::class, 'statusLomba'])->name('peserta.status-lomba');
        Route::get('/peserta/perlombaan/{id}/edit', [\App\Http\Controllers\KompetisiController::class, 'editDaftar'])->name('peserta.lomba.edit');
        Route::put('/peserta/perlombaan/{id}', [\App\Http\Controllers\KompetisiController::class, 'updateDaftar'])->name('peserta.lomba.update');

        Route::get('/delegasi/tim', [\App\Http\Controllers\DelegasiController::class, 'manageTim'])->name('delegasi.tim');
        Route::patch('/delegasi/tim/{id}/update', [\App\Http\Controllers\DelegasiController::class, 'updateMember'])->name('delegasi.tim.update');
        Route::post('/delegasi/tim/{id}/remove', [\App\Http\Controllers\DelegasiController::class, 'removeMember'])->name('delegasi.tim.remove');
        Route::get('/delegasi/manage-tim', [DelegasiController::class, 'manageTim'])->name('delegasi.manage-tim');
        Route::put('/delegasi/manage-tim/{id}', [DelegasiController::class, 'updateMember'])->name('delegasi.update-member');
        Route::delete('/delegasi/manage-tim/{id}', [DelegasiController::class, 'removeMember'])->name('delegasi.remove-member');

        Route::get('/delegasi/status', [\App\Http\Controllers\DelegasiController::class, 'statusKampus'])->name('delegasi.status');
        Route::get('/delegasi/berkas', [\App\Http\Controllers\DelegasiController::class, 'berkasTim'])->name('delegasi.berkas');
        Route::post('/delegasi/berkas/upload', [\App\Http\Controllers\DelegasiController::class, 'uploadBerkas'])->name('delegasi.berkas.upload');

        Route::post('/acara/{id}/daftar', [\App\Http\Controllers\KatalogEventController::class, 'daftarTiket'])->name('katalog.event.daftar');
        Route::post('/performance/{slug}/daftar', [\App\Http\Controllers\PerformanceController::class, 'daftarTiket'])->name('performance.daftar');

        Route::get('/delegasi/submisi/karya-kampus', [App\Http\Controllers\DelegasiSubmisiController::class, 'karyaKampus'])->name('delegasi.submisi.karya');
        Route::get('/delegasi/submisi/{kategori}', [DelegasiSubmisiController::class, 'panduan'])->name('delegasi.submisi.panduan');
        Route::get('/delegasi/submisi/{kategori}/daftar', [DelegasiSubmisiController::class, 'formDaftar'])->name('delegasi.submisi.daftar');
        Route::post('/delegasi/submisi/{kategori}/daftar', [DelegasiSubmisiController::class, 'storeDaftar'])->name('delegasi.submisi.store');
        Route::delete('/delegasi/submisi/komentar/destroy', [DelegasiSubmisiController::class, 'destroyKomentar'])->name('delegasi.submisi.komentar.destroy');
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

            // ==========================================
            // FITUR BARU: SCAN QR CODE KEHADIRAN
            // ==========================================
            Route::get('/scan-qr', [ScanQRController::class, 'index'])->name('scan-qr.index');
            Route::post('/scan-qr/process', [ScanQRController::class, 'process'])->name('scan-qr.process');

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

            // Syarat Ketentuan & Kebijakan
            Route::get('/syarat-ketentuan/create', [SyaratKetentuanController::class, 'create'])->name('syarat.create');
            Route::get('/syarat-ketentuan/edit/{id}', [SyaratKetentuanController::class, 'edit'])->name('syarat.edit');
            Route::post('/syarat-ketentuan/store', [SyaratKetentuanController::class, 'store'])->name('syarat.store');
            Route::put('/syarat-ketentuan/update/{id}', [SyaratKetentuanController::class, 'update'])->name('syarat.update');
            Route::delete('/syarat-ketentuan/destroy/{id}', [SyaratKetentuanController::class, 'destroy'])->name('syarat.destroy');

            Route::get('/kebijakan-privasi/create', [KebijakanPrivasiController::class, 'create'])->name('kebijakan.create');
            Route::get('/kebijakan-privasi/edit/{id}', [KebijakanPrivasiController::class, 'edit'])->name('kebijakan.edit');
            Route::post('/kebijakan-privasi/store', [KebijakanPrivasiController::class, 'store'])->name('kebijakan.store');
            Route::put('/kebijakan-privasi/update/{id}', [KebijakanPrivasiController::class, 'update'])->name('kebijakan.update');
            Route::delete('/kebijakan-privasi/destroy/{id}', [KebijakanPrivasiController::class, 'destroy'])->name('kebijakan.destroy');

            // Pengaturan Settings
            Route::get('/pengaturan/panduan-delegasi', [PanduanDelegasiController::class, 'edit'])->name('panduan.edit');
            Route::put('/pengaturan/panduan-delegasi', [PanduanDelegasiController::class, 'update'])->name('panduan.update');
            Route::get('/pengaturan/header', [HeaderPublicController::class, 'edit'])->name('header.edit');
            Route::put('/pengaturan/header', [HeaderPublicController::class, 'update'])->name('header.update');
            Route::get('/kmdgi/about', [AboutKmdgiController::class, 'edit'])->name('about.edit');
            Route::put('/kmdgi/about', [AboutKmdgiController::class, 'update'])->name('about.update');

            // Penampil & Sponsor & Kolaborator & Dokumentasi
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

            // Sejarah & Edisi KMDGI
            Route::get('/kmdgi/sejarah/create', [SejarahKmdgiController::class, 'create'])->name('sejarah.create');
            Route::get('/kmdgi/sejarah/edit/{id}', [SejarahKmdgiController::class, 'edit'])->name('sejarah.edit');
            Route::post('/kmdgi/sejarah/store', [SejarahKmdgiController::class, 'store'])->name('sejarah.store');
            Route::put('/kmdgi/sejarah/update/{id}', [SejarahKmdgiController::class, 'update'])->name('sejarah.update');
            Route::delete('/kmdgi/sejarah/destroy/{id}', [SejarahKmdgiController::class, 'destroy'])->name('sejarah.destroy');

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
            Route::get('/perlombaan/peserta/export-zip', [PesertaLombaController::class, 'exportZipKarya'])->name('peserta_lomba.export_zip');
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

            // MANAJEMEN BERKAS TIM
            Route::get('/manajemen-berkas', [\App\Http\Controllers\Admin\BerkasController::class, 'index'])->name('berkas.index');
            Route::post('/manajemen-berkas/{id}/kwitansi', [\App\Http\Controllers\Admin\BerkasController::class, 'uploadKwitansi'])->name('berkas.upload_kwitansi');
            Route::post('/manajemen-berkas/{id}/reset', [\App\Http\Controllers\Admin\BerkasController::class, 'resetBerkas'])->name('berkas.reset');
            Route::post('/manajemen-berkas/config', [\App\Http\Controllers\Admin\BerkasController::class, 'updateConfig'])->name('berkas.config');

            // VERIFIKASI TIKET
            Route::get('/verifikasi/event', [VerifikasiTiketEventController::class, 'index'])->name('verifikasi.event.index');
            Route::post('/verifikasi/event/{id}/approve', [VerifikasiTiketEventController::class, 'approve'])->name('verifikasi.event.approve');
            Route::delete('/verifikasi/event/{id}', [VerifikasiTiketEventController::class, 'destroy'])->name('verifikasi.event.destroy');

            Route::get('/verifikasi/performance', [VerifikasiTiketPerformanceController::class, 'index'])->name('verifikasi.performance.index');
            Route::post('/verifikasi/performance/{id}/approve', [VerifikasiTiketPerformanceController::class, 'approve'])->name('verifikasi.performance.approve');
            Route::delete('/verifikasi/performance/{id}', [VerifikasiTiketPerformanceController::class, 'destroy'])->name('verifikasi.performance.destroy');

            Route::get('/verifikasi/pameran', [VerifikasiTiketPameranController::class, 'index'])->name('verifikasi.pameran.index');
            Route::post('/verifikasi/pameran/{id}/approve', [VerifikasiTiketPameranController::class, 'approve'])->name('verifikasi.pameran.approve');
            Route::delete('/verifikasi/pameran/{id}', [VerifikasiTiketPameranController::class, 'destroy'])->name('verifikasi.pameran.destroy');

            // DATA PESERTA
            Route::get('/peserta/event', [PesertaEventController::class, 'index'])->name('peserta.event.index');
            Route::get('/peserta/event/export', [PesertaEventController::class, 'export'])->name('peserta.event.export');

            Route::get('/peserta/performance', [PesertaPerformanceController::class, 'index'])->name('peserta.performance.index');
            Route::get('/peserta/performance/export', [PesertaPerformanceController::class, 'export'])->name('peserta.performance.export');

            Route::get('/peserta/pameran', [PesertaPameranController::class, 'index'])->name('peserta.pameran.index');
            Route::get('/peserta/pameran/export', [PesertaPameranController::class, 'export'])->name('peserta.pameran.export');

            // KEHADIRAN PESERTA
            // Kehadiran Event
            Route::get('/kehadiran/event/export', [KehadiranEventController::class, 'export'])->name('kehadiran.event.export');
            Route::get('/kehadiran/event', [KehadiranEventController::class, 'index'])->name('kehadiran.event.index');

            // Kehadiran Performance
            Route::get('/kehadiran/performance/export', [KehadiranPerformanceController::class, 'export'])->name('kehadiran.performance.export');
            Route::get('/kehadiran/performance', [KehadiranPerformanceController::class, 'index'])->name('kehadiran.performance.index');

            // Kehadiran Pameran
            Route::get('/kehadiran/pameran/export', [KehadiranPameranController::class, 'export'])->name('kehadiran.pameran.export');
            Route::get('/kehadiran/pameran', [KehadiranPameranController::class, 'index'])->name('kehadiran.pameran.index');
            // VERIFIKASI KARYA PAMERAN
            Route::get('/verifikasi-karya/{kategori}', [VerifikasiKaryaController::class, 'index'])->name('verifikasi_karya.index');
            Route::post('/verifikasi-karya/update/{id}', [VerifikasiKaryaController::class, 'updateStatus'])->name('verifikasi_karya.update');
            Route::get('/verifikasi-karya/{kategori}/export', [VerifikasiKaryaController::class, 'exportCsv'])->name('verifikasi_karya.export');
            Route::get('/verifikasi-karya-publik/{kategori}', [VerifikasiKaryaController::class, 'index'])->name('verifikasi_karya_publik.index');

            // MODERASI KOMENTAR
            Route::get('/moderasi-komentar', [KomentarController::class, 'index'])->name('komentar.index');
            Route::delete('/moderasi-komentar/{id}', [KomentarController::class, 'destroy'])->name('komentar.destroy');
            Route::post('/moderasi-komentar/{id}/dismiss', [KomentarController::class, 'dismissReport'])->name('komentar.dismiss');

            // MANAJEMEN REKENING & QRIS PEMBAYARAN LOMBA
            Route::get('/rekening', [RekeningPembayaranController::class, 'index'])->name('rekening.index');
            Route::post('/rekening/store', [RekeningPembayaranController::class, 'store'])->name('rekening.store');
            Route::put('/rekening/update/{id}', [RekeningPembayaranController::class, 'update'])->name('rekening.update');
            Route::delete('/rekening/destroy/{id}', [RekeningPembayaranController::class, 'destroy'])->name('rekening.destroy');

            // Pengaturan Footer
            Route::get('/pengaturan/footer', [\App\Http\Controllers\Admin\FooterController::class, 'edit'])->name('footer.edit');
            Route::put('/pengaturan/footer', [\App\Http\Controllers\Admin\FooterController::class, 'update'])->name('footer.update');
        });

        // C. HAK AKSES KHUSUS: Hanya Super Admin
        Route::middleware('role:super admin')->group(function () {
            Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('log-aktivitas.index');
        });
    });
});
