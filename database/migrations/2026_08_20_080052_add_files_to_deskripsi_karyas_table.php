<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('deskripsi_karyas', function (Blueprint $table) {
            $table->string('file_guidebook')->nullable();
            $table->string('file_panduan_online')->nullable();
            $table->json('berkas_lainnya')->nullable(); // Menampung array berkas dinamis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deskripsi_karyas', function (Blueprint $table) {
            //
        });
    }
};
