<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('panduan_delegasi', function (Blueprint $table) {
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            
            $table->string('alur_title')->nullable();
            $table->text('alur_deskripsi')->nullable();
            
            $table->string('alur_1_title')->nullable();
            $table->text('alur_1_desc')->nullable();
            $table->string('alur_2_title')->nullable();
            $table->text('alur_2_desc')->nullable();
            $table->string('alur_3_title')->nullable();
            $table->text('alur_3_desc')->nullable();
            $table->string('alur_4_title')->nullable();
            $table->text('alur_4_desc')->nullable();
            
            $table->string('akun_title')->nullable();
            $table->text('akun_desc')->nullable();
        });
    }

    public function down()
    {
        Schema::table('panduan_delegasi', function (Blueprint $table) {
            $table->dropColumn([
                'hero_title', 'hero_subtitle', 'alur_title', 'alur_deskripsi',
                'alur_1_title', 'alur_1_desc', 'alur_2_title', 'alur_2_desc',
                'alur_3_title', 'alur_3_desc', 'alur_4_title', 'alur_4_desc',
                'akun_title', 'akun_desc'
            ]);
        });
    }
};