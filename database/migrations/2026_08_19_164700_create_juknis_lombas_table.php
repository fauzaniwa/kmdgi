<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('juknis_lombas', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Edisi KMDGI
            $table->foreignId('edisi_kmdgi_id')->constrained('edisi_kmdgis')->onDelete('cascade');
            
            // Data Utama
            $table->string('poster')->nullable();
            $table->string('judul');
            $table->string('slug')->unique(); // URL Friendly
            $table->text('deskripsi')->nullable();
            
            // Konfigurasi Pendaftaran
            $table->json('kategori_peserta')->nullable(); // Array: ["Delegasi", "Umum"]
            $table->integer('biaya_pendaftaran')->default(0); // 0 = Gratis
            $table->date('deadline_awal')->nullable();
            $table->date('deadline_akhir')->nullable();
            
            // Array Dinamis (JSON)
            $table->json('timeline')->nullable();
            $table->json('hadiah')->nullable();
            $table->json('juri')->nullable();
            
            // Rich Text Editors (Quill)
            $table->longText('syarat')->nullable();
            $table->longText('ketentuan')->nullable();
            $table->longText('teknik_pelaksanaan')->nullable();
            $table->longText('ketentuan_umum')->nullable();
            $table->longText('ketentuan_khusus')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('juknis_lombas');
    }
};