<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKomentar extends Model
{
    use HasFactory;

    protected $table = 'laporan_komentars';
    protected $fillable = ['karya_komentar_id', 'user_id', 'alasan'];

    // Relasi ke User yang melaporkan
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Komentar yang dilaporkan
    public function komentar()
    {
        return $this->belongsTo(KaryaKomentar::class, 'karya_komentar_id');
    }
}