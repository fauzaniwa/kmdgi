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
        Schema::create('karya_komentars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submisi_karya_id')->constrained('submisi_karyas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Pembuat komentar
            $table->foreignId('parent_id')->nullable()->constrained('karya_komentars')->cascadeOnDelete(); // Untuk balas komentar (Reply)
            $table->text('isi_komentar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karya_komentars');
    }
};
