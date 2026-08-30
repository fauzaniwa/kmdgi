<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaLomba extends Model
{
    use HasFactory;

    protected $table = 'peserta_lombas';

    protected $fillable = [
        'juknis_lomba_id', 'user_id', 'nama_tim_peserta', 'institusi_asal', 
        'kategori_pendaftar', 'no_whatsapp', 'status_pembayaran', 'bukti_pembayaran', 
        
        // Data Karya Baru
        'judul_karya', 'kreator_karya', 'deskripsi_karya', 'file_karya', 'link_karya', 'status_karya'
    ];

    public function lomba()
    {
        return $this->belongsTo(JuknisLomba::class, 'juknis_lomba_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}