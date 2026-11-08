<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoothDelegasi extends Model
{
    use HasFactory;

    protected $table = 'booth_delegasis';

    protected $fillable = [
        'edisi_kmdgi_id',
        'user_id',
        'institusi',
        'judul_karya_tematik',
        'kreator_karya', // <-- Kolom Baru: Kreator / Tim (dipisah koma)
        'foto_utama',
        'foto_pendukung_1',
        'foto_pendukung_2',
        'deskripsi_singkat',
        'status_draft',
        'status_verifikasi',
        'votes_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function edisi()
    {
        return $this->belongsTo(EdisiKmdgi::class, 'edisi_kmdgi_id');
    }

    public function votes()
    {
        return $this->hasMany(BoothVote::class, 'booth_delegasi_id');
    }

    public function komentars()
    {
        return $this->hasMany(KaryaKomentar::class, 'booth_delegasi_id');
    }
}
