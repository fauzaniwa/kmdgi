<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $table = 'sponsors';

    protected $fillable = [
        'urutan',
        'nama_mitra',
        'logo',
        'deskripsi',
        'kategori',
        'tier_kelas',
        'link_tautan',
        'is_active',
    ];
}