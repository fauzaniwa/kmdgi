<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booth_delegasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('edisi_kmdgi_id')->constrained('edisi_kmdgis')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('institusi');
            $table->string('judul_karya_tematik', 100)->nullable();
            $table->string('foto_utama')->nullable(); // Foto A: Tampak Depan (Thumbnail)
            $table->string('foto_pendukung_1')->nullable(); // Foto B: Sudut Berbeda / Diagonal
            $table->string('foto_pendukung_2')->nullable(); // Foto C: Detail / Elemen Interaktif
            $table->text('deskripsi_singkat')->nullable(); // Maks 150 kata
            $table->boolean('status_draft')->default(0);
            $table->string('status_verifikasi')->default('Menunggu'); // Menunggu, Terverifikasi, Ditolak
            $table->unsignedInteger('votes_count')->default(0);
            $table->timestamps();
        });

        Schema::create('booth_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booth_delegasi_id')->constrained('booth_delegasis')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booth_votes');
        Schema::dropIfExists('booth_delegasis');
    }
};