<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KaryaKomentar extends Model
{
    protected $table = 'karya_komentars';
    protected $fillable = ['submisi_karya_id', 'user_id', 'parent_id', 'isi_komentar'];

    // Relasi ke user yang berkomentar
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi untuk mendapatkan balasan (replies) dari komentar ini
    public function replies()
    {
        return $this->hasMany(KaryaKomentar::class, 'parent_id')->with('user')->oldest();
    }

    // Relasi ke Karya yang dikomentari
    public function submisiKarya()
    {
        return $this->belongsTo(SubmisiKarya::class, 'submisi_karya_id');
    }

    // Relasi ke Laporan
    public function laporans()
    {
        return $this->hasMany(LaporanKomentar::class, 'karya_komentar_id');
    }
}
