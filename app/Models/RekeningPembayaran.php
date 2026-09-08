<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningPembayaran extends Model
{
    use HasFactory;

    protected $table = 'rekening_pembayarans';

    protected $fillable = [
        'nama_bank', 'atas_nama', 'nomor_rekening', 'logo_bank', 'qr_code', 'is_active'
    ];
}