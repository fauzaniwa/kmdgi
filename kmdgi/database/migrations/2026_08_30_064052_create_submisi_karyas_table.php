<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('submisi_karyas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('edisi_kmdgi_id')->constrained('edisi_kmdgis')->onDelete('cascade');
            $table->enum('kategori_karya', ['tematik', 'simbiotik', 'simbolik']);
            $table->string('judul_karya')->nullable();
            $table->string('kreator_karya')->nullable();
            $table->text('deskripsi_karya')->nullable();
            $table->string('link_karya')->nullable();
            $table->string('thumbnail_karya')->nullable();
            $table->string('file_karya')->nullable(); // Boleh kosong (opsional)
            $table->boolean('status_draft')->default(true); // 1 = Draft, 0 = Final
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('submisi_karyas');
    }
};