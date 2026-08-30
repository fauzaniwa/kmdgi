<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmisiKarya extends Model
{
    use HasFactory;

    protected $table = 'submisi_karyas';

    protected $fillable = [
        'user_id',
        'edisi_kmdgi_id',
        'kategori_karya',
        'judul_karya',
        'kreator_karya',
        'deskripsi_karya',
        'link_karya',
        'thumbnail_karya',
        'file_karya',
        'status_draft',
        'status_verifikasi', // <-- Tambahkan ini
        'catatan_revisi',    // <-- Tambahkan ini
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function edisi()
    {
        return $this->belongsTo(EdisiKmdgi::class, 'edisi_kmdgi_id');
    }
}