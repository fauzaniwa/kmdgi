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
        Schema::table('kampus', function (Blueprint $table) {
            // Menambahkan kolom riwayat_status dengan tipe JSON
            $table->json('riwayat_status')->nullable()->after('status_keanggotaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kampus', function (Blueprint $table) {
            $table->dropColumn('riwayat_status');
        });
    }
};