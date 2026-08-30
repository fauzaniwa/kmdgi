<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kebijakan_privasi', function (Blueprint $table) {
            $table->id();
            $table->string('judul'); // Cth: "Pengumpulan Data", "Penggunaan Cookies"
            $table->longText('konten'); // Konten HTML dari editor
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kebijakan_privasi');
    }
};