<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('header_publics', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable(); // Headline
            $table->text('deskripsi')->nullable(); // Sub-headline
            
            // Media Background
            $table->string('gambar_background')->nullable(); 
            $table->string('video_background')->nullable(); // URL YouTube / MP4 (Opsional)
            
            // Sistem Countdown
            $table->dateTime('waktu_countdown')->nullable(); // Target Waktu
            $table->boolean('is_active_countdown')->default(true); // Sakelar Nyala/Mati
            
            // Tombol Aksi (Call to Action)
            $table->string('teks_tombol_utama')->nullable();
            $table->string('link_tombol_utama')->nullable();
            $table->string('teks_tombol_sekunder')->nullable();
            $table->string('link_tombol_sekunder')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('header_publics');
    }
};