<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Di dalam method up()
        // Di dalam method up()
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Kita akan gunakan ini untuk field 'nama'
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Kita tambahkan role 'peserta' sebagai default untuk yang mendaftar via web
            $table->enum('role', ['super admin', 'admin', 'editor', 'peserta'])->default('peserta');

            // Tambahan kolom khusus Form KMDGI
            $table->string('kategori'); // Delegasi atau Umum
            $table->string('peran_delegasi')->nullable(); // Ketua atau Anggota
            $table->string('institusi')->nullable();
            $table->string('auth_code')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('no_hp')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
