<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EdisiKmdgi extends Model
{
    use HasFactory;

    protected $table = 'edisi_kmdgis';

    protected $fillable = [
        'nama_edisi', 'is_active',
        'lb_title', 'lb_deskripsi', 'lb_image',
        'tema_title', 'tema_deskripsi', 'tema_logo', 'tema_image'
    ];

    // FUNGSI BALASAN RELASI
    public function kampus()
    {
        return $this->belongsToMany(Kampus::class, 'edisi_kampus', 'edisi_kmdgi_id', 'kampus_id')
                    ->withPivot('status_keanggotaan')
                    ->withTimestamps();
    }
}