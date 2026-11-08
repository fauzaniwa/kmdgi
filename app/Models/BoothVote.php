<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoothVote extends Model
{
    use HasFactory;

    protected $table = 'booth_votes';

    protected $fillable = [
        'booth_delegasi_id',
        'user_id',
        'ip_address',
        'user_agent',
    ];

    public function booth()
    {
        return $this->belongsTo(BoothDelegasi::class, 'booth_delegasi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}