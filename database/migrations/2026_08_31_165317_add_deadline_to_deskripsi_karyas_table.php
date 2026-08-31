<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('deskripsi_karyas', function (Blueprint $table) {
            // Menambahkan kolom deadline (bisa null jika belum diset)
            $table->dateTime('deadline')->nullable()->after('nominasi_kriteria');
        });
    }

    public function down()
    {
        Schema::table('deskripsi_karyas', function (Blueprint $table) {
            $table->dropColumn('deadline');
        });
    }
};