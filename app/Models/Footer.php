<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    use HasFactory;

    protected $fillable = [
        'bg_color',
        'logo',
        'decoration_desktop',
        'decoration_mobile',
        'link_instagram',
        'link_tiktok',
        'copyright_text',
        'menu_links',     // Kolom baru
        'profile_links',  // Kolom baru
    ];

    protected $casts = [
        'menu_links' => 'array',
        'profile_links' => 'array',
    ];
}