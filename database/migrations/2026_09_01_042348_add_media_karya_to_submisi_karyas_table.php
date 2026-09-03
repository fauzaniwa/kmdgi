<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('submisi_karyas', function (Blueprint $table) {
            // Menyimpan path file dalam format JSON array
            $table->json('media_karya')->nullable()->after('file_karya');
        });
    }

    public function down()
    {
        Schema::table('submisi_karyas', function (Blueprint $table) {$table->dropColumn('media_karya');
        });
    }
};