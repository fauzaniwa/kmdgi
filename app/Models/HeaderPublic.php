<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderPublic extends Model
{
    use HasFactory;

    protected $table = 'header_publics';

    // Properti ini WAJIB ada agar Laravel mengizinkan fungsi ::create() atau ::update()
    protected $fillable = [
        'judul', 
        'deskripsi', 
        'gambar_background', 
        'video_background',
        'waktu_countdown', 
        'is_active_countdown',
        'teks_tombol_utama', 
        'link_tombol_utama',
        'teks_tombol_sekunder', 
        'link_tombol_sekunder'
    ];
}