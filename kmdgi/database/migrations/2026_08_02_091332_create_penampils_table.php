<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penampils', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penampil');
            $table->string('kategori_penampil'); // Band, Speaker, dll
            $table->string('cover_penampil')->nullable();
            
            $table->date('tanggal_tampil');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('lokasi_tampil');
            
            $table->text('deskripsi_penampil');
            $table->string('medsos_penampil')->nullable();
            
            $table->string('kategori_penonton'); // Umum, Delegasi, Semua
            $table->string('tipe_pendaftaran'); // Gratis, Berbayar
            $table->integer('harga_tiket')->nullable(); // Terisi jika Berbayar
            $table->string('link_pendaftaran')->nullable(); // Opsional
            
            $table->boolean('is_active')->default(true); // Tampil di web atau Draft
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penampils');
    }
};