<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JuknisLomba extends Model
{
    use HasFactory;

    protected $table = 'juknis_lombas';

    protected $fillable = [
        'edisi_kmdgi_id', 'poster', 'judul', 'slug', 'deskripsi',
        
        // 2 Kolom Baru untuk Berkas Unduhan
        'file_guidebook', 'file_panduan_online',
        
        'kategori_peserta', 'biaya_pendaftaran', 'deadline_awal', 'deadline_akhir',
        'timeline', 'hadiah', 'juri',
        'syarat', 'ketentuan', 'teknik_pelaksanaan', 'ketentuan_umum', 'ketentuan_khusus', 'is_active'
    ];

    // Beritahu Laravel untuk otomatis mengubah JSON dari DB menjadi Array PHP
    protected $casts = [
        'kategori_peserta' => 'array',
        'timeline'         => 'array',
        'hadiah'           => 'array',
        'juri'             => 'array',
    ];

    public function edisi()
    {
        return $this->belongsTo(EdisiKmdgi::class, 'edisi_kmdgi_id');
    }
}