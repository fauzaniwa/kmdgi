<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kampus extends Model
{
    use HasFactory;

    // Mengunci nama tabel database murni bahasa Indonesia
    protected $table = 'kampus';

    // Berkas kolom yang diizinkan untuk manipulasi data massal
    protected $fillable = [
        'nama_institusi',
        'logo_institusi',
        'lokasi_kota',
        'medsos_kampus',
        'ig_prodi',
        'link_wa',
    ];

    // FUNGSI RELASI PIVOT KE EDISI KMDGI
    public function edisi()
    {
        return $this->belongsToMany(EdisiKmdgi::class, 'edisi_kampus', 'kampus_id', 'edisi_kmdgi_id')
                    ->withPivot('status_keanggotaan')
                    ->withTimestamps();
    }
}