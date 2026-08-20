<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta_lombas', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Perlombaan dan Akun User (Peserta)
            $table->foreignId('juknis_lomba_id')->constrained('juknis_lombas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Identitas Pendaftar & Tim
            $table->string('nama_tim_peserta');
            $table->string('institusi_asal')->nullable();
            $table->string('kategori_pendaftar');
            $table->string('no_whatsapp');
            
            // Sistem Pembayaran
            $table->string('status_pembayaran')->default('Gratis'); 
            $table->string('bukti_pembayaran')->nullable();
            
            // Sistem Pengumpulan Karya (BARU)
            $table->string('judul_karya')->nullable();
            $table->string('kreator_karya')->nullable(); // Bisa nama orang/tim penyusun
            $table->text('deskripsi_karya')->nullable();
            $table->string('file_karya')->nullable(); // Jika upload file PDF/JPG langsung
            $table->string('link_karya')->nullable(); // Jika kirim link G-Drive/YouTube
            
            $table->string('status_karya')->default('Belum Mengumpulkan'); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta_lombas');
    }
};