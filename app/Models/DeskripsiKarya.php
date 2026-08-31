<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeskripsiKarya extends Model
{
    use HasFactory;

    protected $table = 'deskripsi_karyas';
    protected $fillable = [
        'edisi_kmdgi_id',
        'kategori_karya',
        'thumbnail',
        'deskripsi',
        'general_aturan',
        'ketentuan_karya',
        'teknis_pelaksanaan',
        'sistem_penilaian',
        'nominasi_kriteria',
        'deadline', // Tambahan Kolom Baru untuk Deadline
        'file_guidebook',
        'file_panduan_online',
        'berkas_lainnya'
    ];

    protected $casts = [
        'berkas_lainnya' => 'array',
        'deadline' => 'datetime', // Cast ke datetime agar mudah diformat
    ];

    public function edisi()
    {
        return $this->belongsTo(EdisiKmdgi::class, 'edisi_kmdgi_id');
    }
}
