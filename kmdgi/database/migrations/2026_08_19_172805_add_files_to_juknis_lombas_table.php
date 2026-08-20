<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('juknis_lombas', function (Blueprint $table) {
            // Menambahkan 2 kolom baru untuk menyimpan path file PDF/Dokumen
            $table->string('file_guidebook')->nullable()->after('deskripsi');
            $table->string('file_panduan_online')->nullable()->after('file_guidebook');
        });
    }

    public function down(): void
    {
        Schema::table('juknis_lombas', function (Blueprint $table) {
            // Menghapus kolom jika di-rollback
            $table->dropColumn(['file_guidebook', 'file_panduan_online']);
        });
    }
};