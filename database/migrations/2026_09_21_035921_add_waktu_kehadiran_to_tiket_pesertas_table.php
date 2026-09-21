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
        Schema::table('tiket_pesertas', function (Blueprint $table) {
            $table->timestamp('waktu_kehadiran')->nullable()->after('status');
        });
    }
    public function down()
    {
        Schema::table('tiket_pesertas', function (Blueprint $table) {
            $table->dropColumn('waktu_kehadiran');
        });
    }
};
