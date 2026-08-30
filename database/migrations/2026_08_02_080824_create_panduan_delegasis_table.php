<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panduan_delegasi', function (Blueprint $table) {
            $table->id();
            $table->longText('petunjuk_teknis')->nullable();
            $table->longText('petunjuk_pameran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panduan_delegasi');
    }
};