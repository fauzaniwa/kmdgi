<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kampus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_institusi');
            $table->string('logo_institusi')->nullable(); // Menyimpan path file gambar logo
            $table->string('lokasi_kota');
            $table->string('medsos_kampus')->nullable(); // Akun Instagram Institusi/Kampus
            $table->string('ig_prodi')->nullable();      // Akun Instagram Prodi DKV/Despro
            $table->enum('status_keanggotaan', ['Anggota', 'Peninjau 1', 'Peninjau 2']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kampus');
    }
};