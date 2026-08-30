<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kolaborator extends Model
{
    use HasFactory;

    protected $table = 'kolaborators';

    protected $fillable = [
        'urutan', 'nama', 'profesi', 'peran_kolaborasi', 'foto',
        'detail', 'link_instagram', 'link_linkedin', 'link_website', 'is_active'
    ];
}