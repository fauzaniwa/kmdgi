<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penampils', function (Blueprint $table) {
            // Memberikan nilai default 9999 agar secara default turun ke bawah dan diurutkan oleh tanggal
            $table->integer('urutan')->default(9999)->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('penampils', function (Blueprint $table) {
            $table->dropColumn('urutan');
        });
    }
};