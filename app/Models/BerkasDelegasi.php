<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasDelegasi extends Model
{
    use HasFactory;
    protected $fillable = ['auth_code', 'institusi', 'bukti_pembayaran', 'formulir_pendaftaran', 'kwitansi'];
}