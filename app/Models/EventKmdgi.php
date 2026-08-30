<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventKmdgi extends Model
{
    use HasFactory;

    protected $table = 'event_kmdgis';

    protected $fillable = [
        'edisi_kmdgi_id',
        'judul',
        'slug',
        'poster',
        'harga_tiket',
        'kuota',
        'kategori_peserta',
        'kolaborator_ids',
        'tanggal_pelaksanaan',
        'jam_pelaksanaan',
        'lokasi',
        'deskripsi',
        'ketentuan',
        'is_active'
    ];
    protected $casts = [
        'kategori_peserta' => 'array',
        'kolaborator_ids'  => 'array',
    ];

    public function edisi()
    {
        return $this->belongsTo(EdisiKmdgi::class, 'edisi_kmdgi_id');
    }

    // Fungsi tambahan untuk memanggil data Kolaborator yang terpilih
    public function getKolaboratorTerkait()
    {
        if (empty($this->kolaborator_ids)) {
            return collect(); // Return koleksi kosong
        }
        return Kolaborator::whereIn('id', $this->kolaborator_ids)->get();
    }
}
