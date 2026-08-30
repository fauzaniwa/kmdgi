<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tiket_pesertas', function (Blueprint $table) {
            // Menambahkan kolom penampil_id, nullable karena tiket bisa untuk event lain
            $table->foreignId('penampil_id')->nullable()->constrained('penampils')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('tiket_pesertas', function (Blueprint $table) {
            $table->dropForeign(['penampil_id']);
            $table->dropColumn('penampil_id');
        });
    }
};