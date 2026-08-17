<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 'deskripsi', 'kategori_kegiatan', 'tanggal_kegiatan',
        'tipe_media', 'file_path', 'video_url', 'is_active'
    ];
}