<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('footers', function (Blueprint $table) {
            // Kita gunakan JSON agar admin bisa menambah/menghapus link sebebasnya
            $table->json('menu_links')->nullable();
            $table->json('profile_links')->nullable();
        });
    }

    public function down()
    {
        Schema::table('footers', function (Blueprint $table) {
            $table->dropColumn(['menu_links', 'profile_links']);
        });
    }
};