<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();
            $table->integer('urutan')->default(9999); // Untuk sistem Drag & Drop
            $table->string('nama_mitra');
            $table->string('logo');
            $table->text('deskripsi')->nullable();
            
            // Misal: Sponsor, Partnership, Media Partner, Community Partner
            $table->string('kategori'); 
            
            // Misal: Utama (Besar), Madya (Sedang), Pratama (Kecil)
            $table->string('tier_kelas'); 
            
            $table->string('link_tautan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsors');
    }
};