<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiketPeserta extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'event_kmdgi_id', 
        'penampil_id', // <-- WAJIB ADA AGAR TIKET PERFORMANCE BISA DISIMPAN
        'jenis_tiket', 
        'kode_tiket',
        'status', 
        'bukti_pembayaran' 
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Event (Jika ini tiket Seminar/Workshop)
    public function event()
    {
        return $this->belongsTo(EventKmdgi::class, 'event_kmdgi_id');
    }

    // Relasi ke Penampil (Jika ini tiket Performance/Penampil)
    public function penampil()
    {
        return $this->belongsTo(Penampil::class, 'penampil_id');
    }
}