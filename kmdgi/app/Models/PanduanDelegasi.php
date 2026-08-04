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
    ];
}