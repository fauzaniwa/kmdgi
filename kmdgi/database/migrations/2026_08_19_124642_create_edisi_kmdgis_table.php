<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('edisi_kmdgis', function (Blueprint $table) {
            $table->id();
            
            // INDUK
            $table->string('nama_edisi'); // Cth: KMDGI 16
            $table->boolean('is_active')->default(false); // Hanya 1 yang true
            
            // LATAR BELAKANG
            $table->string('lb_title')->nullable();
            $table->longText('lb_deskripsi')->nullable();
            $table->string('lb_image')->nullable();
            
            // TEMA
            $table->string('tema_title')->nullable();
            $table->longText('tema_deskripsi')->nullable();
            $table->string('tema_logo')->nullable(); // Logo Tema (PNG Transparan)
            $table->string('tema_image')->nullable(); // Gambar Pendukung Tema
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edisi_kmdgis');
    }
};