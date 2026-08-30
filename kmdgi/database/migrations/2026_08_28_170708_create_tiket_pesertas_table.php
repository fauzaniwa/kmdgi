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
        Schema::create('tiket_pesertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Nullable karena tiket Pameran tidak terikat pada sub-event tertentu
            $table->foreignId('event_kmdgi_id')->nullable()->constrained('event_kmdgis')->cascadeOnDelete();
            $table->string('jenis_tiket'); // Contoh: 'Pameran', 'Seminar', 'Workshop'
            $table->string('kode_tiket', 12)->unique(); // Format KM16XXXXXDGI
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiket_pesertas');
    }
};
