<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deskripsi_karyas', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Edisi KMDGI (Induk)
            $table->foreignId('edisi_kmdgi_id')->constrained('edisi_kmdgis')->onDelete('cascade');
            
            // Kategori Karya (Tematik, Simbiotik, Simbolik)
            $table->string('kategori_karya'); 
            
            $table->string('thumbnail')->nullable();
            
            // 6 Text Box (Rich Text)
            $table->longText('deskripsi')->nullable();
            $table->longText('general_aturan')->nullable();
            $table->longText('ketentuan_karya')->nullable();
            $table->longText('teknis_pelaksanaan')->nullable();
            $table->longText('sistem_penilaian')->nullable();
            $table->longText('nominasi_kriteria')->nullable();
            
            $table->timestamps();

            // Mencegah duplikasi: 1 Edisi hanya punya 1 Tematik, 1 Simbiotik, 1 Simbolik
            $table->unique(['edisi_kmdgi_id', 'kategori_karya']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deskripsi_karyas');
    }
};