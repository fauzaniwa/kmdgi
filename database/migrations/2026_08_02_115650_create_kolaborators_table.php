<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kolaborators', function (Blueprint $table) {
            $table->id();
            $table->integer('urutan')->default(9999);
            $table->string('nama');
            $table->string('profesi'); // Cth: "Senior Art Director", "Dosen DKV ITB"
            $table->string('peran_kolaborasi'); // Cth: "Kurator", "Juri"
            $table->string('foto')->nullable();
            
            $table->longText('detail')->nullable(); // Rich Text Editor
            
            // Sosial Media Terpisah agar mempermudah front-end menampilkan ikon yang sesuai
            $table->string('link_instagram')->nullable();
            $table->string('link_linkedin')->nullable();
            $table->string('link_website')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kolaborators');
    }
};