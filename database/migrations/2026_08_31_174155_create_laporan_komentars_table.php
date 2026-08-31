<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_komentars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karya_komentar_id')->constrained('karya_komentars')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // User pelapor
            $table->text('alasan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_komentars');
    }
};