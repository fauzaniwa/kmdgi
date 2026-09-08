<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanduanDelegasi extends Model
{
    use HasFactory;

    protected $table = 'panduan_delegasi';

    protected $fillable = [
        'petunjuk_teknis',
        'petunjuk_pameran',
        'hero_title',
        'hero_subtitle',
        'alur_title',
        'alur_deskripsi',
        'alur_1_title',
        'alur_1_desc',
        'alur_2_title',
        'alur_2_desc',
        'alur_3_title',
        'alur_3_desc',
        'alur_4_title',
        'alur_4_desc',
        'akun_title',
        'akun_desc',
    ];
}