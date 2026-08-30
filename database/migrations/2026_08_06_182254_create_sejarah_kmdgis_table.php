<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sejarah_kmdgis', function (Blueprint $table) {
            $table->id();
            $table->string('tahun'); // Cth: "1993", "1995-1996"
            $table->string('title'); // Cth: "KMDGI 1", "KMDGI 2"
            $table->longText('description')->nullable();
            
            // Maksimal 5 Gambar per Sejarah
            $table->string('image_1')->nullable();
            $table->string('image_2')->nullable();
            $table->string('image_3')->nullable();
            $table->string('image_4')->nullable();
            $table->string('image_5')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sejarah_kmdgis');
    }
};