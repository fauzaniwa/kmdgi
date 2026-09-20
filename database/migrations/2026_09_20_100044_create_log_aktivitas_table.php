<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Admin yang melakukan aksi
            $table->string('modul'); // Nama modul/halaman (Contoh: "About KMDGI", "Users", dll)
            $table->string('aksi'); // Jenis aksi (Contoh: "Update", "Create", "Delete")
            $table->text('deskripsi'); // Detail aktivitas
            $table->ipAddress('ip_address')->nullable(); // (Opsional) IP Address admin
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};