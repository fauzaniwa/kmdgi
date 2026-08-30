<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_kmdgis', function (Blueprint $table) {
            $table->id();

            // Relasi ke Edisi
            $table->foreignId('edisi_kmdgi_id')->constrained('edisi_kmdgis')->onDelete('cascade');

            // Data Utama Event
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('poster')->nullable();
            $table->integer('harga_tiket')->default(0);
            $table->integer('kuota')->default(0); // <--- TAMBAHAN KOLOM KUOTA

            // Kategori & Relasi Kolaborator (JSON)
            $table->json('kategori_peserta')->nullable();
            $table->json('kolaborator_ids')->nullable();

            // Waktu & Tempat
            $table->date('tanggal_pelaksanaan')->nullable();
            $table->string('jam_pelaksanaan')->nullable();
            $table->string('lokasi')->nullable();

            // Rich Text
            $table->longText('deskripsi')->nullable();
            $table->longText('ketentuan')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_kmdgis');
    }
};
