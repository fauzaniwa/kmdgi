<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('footers', function (Blueprint $table) {
            $table->id();
            $table->string('bg_color')->default('#0a0a0a'); // Hex color
            $table->string('logo')->nullable();
            $table->string('decoration_desktop')->nullable();
            $table->string('decoration_mobile')->nullable();
            $table->string('link_instagram')->nullable();
            $table->string('link_tiktok')->nullable();
            $table->string('copyright_text')->default('2026 KMDGI 16. All rights reserved.');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('footers');
    }
};