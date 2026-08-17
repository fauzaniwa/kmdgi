<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumentasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            
            $table->string('kategori_kegiatan'); // Cth: Pra-Event, Pameran, Konser
            $table->date('tanggal_kegiatan');
            
            $table->enum('tipe_media', ['Foto', 'Video Upload', 'Video YouTube']);
            $table->string('file_path')->nullable(); // Terisi jika Foto/Video Upload
            $table->string('video_url')->nullable(); // Terisi jika YouTube
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumentasis');
    }
};