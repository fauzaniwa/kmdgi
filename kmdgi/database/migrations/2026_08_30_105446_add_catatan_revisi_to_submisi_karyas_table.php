<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submisi_karyas', function (Blueprint $table) {
            // Tambahkan status_verifikasi jika sebelumnya belum ada
            $table->enum('status_verifikasi', ['Menunggu', 'Terverifikasi', 'Revisi', 'Ditolak'])->default('Menunggu')->after('status_draft');
            
            // Tambahkan catatan_revisi
            $table->text('catatan_revisi')->nullable()->after('status_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::table('submisi_karyas', function (Blueprint $table) {
            $table->dropColumn(['status_verifikasi', 'catatan_revisi']);
        });
    }
};