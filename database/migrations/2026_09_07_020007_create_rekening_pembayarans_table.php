<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rekening_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank'); // Contoh: BCA, Mandiri, DANA, QRIS
            $table->string('atas_nama'); // Contoh: Panitia KMDGI 16
            $table->string('nomor_rekening')->nullable(); // Bisa kosong jika murni QRIS
            $table->string('logo_bank')->nullable(); // Gambar logo bank (opsional)
            $table->string('qr_code')->nullable(); // Gambar QRIS (opsional)
            $table->boolean('is_active')->default(1); // Status tampil
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekening_pembayarans');
    }
};