<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class KaryaLike extends Model
{
    protected $table = 'karya_likes';
    protected $fillable = ['submisi_karya_id', 'user_id'];
}
