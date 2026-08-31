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
        Schema::create('karya_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submisi_karya_id')->constrained('submisi_karyas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // ID yang ngelike
            $table->timestamps();

            // Mencegah 1 user ngelike 2 kali di karya yang sama
            $table->unique(['submisi_karya_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karya_likes');
    }
};
