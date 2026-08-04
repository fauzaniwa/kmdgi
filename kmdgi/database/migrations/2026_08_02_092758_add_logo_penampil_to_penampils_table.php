<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penampils', function (Blueprint $table) {
            // Menambahkan kolom logo setelah kategori
            $table->string('logo_penampil')->nullable()->after('kategori_penampil');
        });
    }

    public function down(): void
    {
        Schema::table('penampils', function (Blueprint $table) {
            $table->dropColumn('logo_penampil');
        });
    }
};