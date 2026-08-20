<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Membuat tabel Pivot (Penghubung)
        Schema::create('edisi_kampus', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel kampus
            $table->foreignId('kampus_id')->constrained('kampus')->onDelete('cascade');
            
            // Relasi ke tabel edisi_kmdgis 
            $table->foreignId('edisi_kmdgi_id')->constrained('edisi_kmdgis')->onDelete('cascade');
            
            // Status untuk edisi tersebut
            $table->string('status_keanggotaan'); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edisi_kampus');
    }
};