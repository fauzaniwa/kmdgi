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
        Schema::table('submisi_karyas', function (Blueprint $table) {
            $table->unsignedBigInteger('views_count')->default(0)->after('status_draft');
            $table->unsignedBigInteger('shares_count')->default(0)->after('views_count');
        });
    }

    public function down()
    {
        Schema::table('submisi_karyas', function (Blueprint $table) {
            $table->dropColumn(['views_count', 'shares_count']);
        });
    }
};
