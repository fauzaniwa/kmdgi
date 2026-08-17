<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penampil extends Model
{
    use HasFactory;

    protected $table = 'penampils';

    protected $fillable = [
        'urutan',
        'nama_penampil',
        'kategori_penampil',
        'logo_penampil',
        'cover_penampil',

        // Data Baru
        'asal_penampil',
        'tahun_dibentuk',
        'genre_musik',
        'embed_spotify',

        'tanggal_tampil',
        'jam_mulai',
        'jam_selesai',
        'lokasi_tampil',
        'deskripsi_penampil',
        'medsos_penampil',
        'kategori_penonton',
        'tipe_pendaftaran',
        'harga_tiket',
        'link_pendaftaran',
        'is_active'
    ];
}
