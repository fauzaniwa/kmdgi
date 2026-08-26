<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('berkas_delegasis', function (Blueprint $table) {
            $table->id();
            $table->string('auth_code')->unique(); // Kunci pengikat tim
            $table->string('institusi');
            $table->string('bukti_pembayaran')->nullable();
            $table->string('formulir_pendaftaran')->nullable();
            $table->string('kwitansi')->nullable(); // Diisi oleh admin nantinya
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berkas_delegasis');
    }
};
