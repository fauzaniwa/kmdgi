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
        Schema::table('tiket_pesertas', function (Blueprint $table) {
            $table->string('status')->default('Aktif')->after('kode_tiket'); // Aktif / Menunggu Konfirmasi / Ditolak
            $table->string('bukti_pembayaran')->nullable()->after('status');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tiket_pesertas', function (Blueprint $table) {
            //
        });
    }
};
