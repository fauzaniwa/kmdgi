<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutKmdgi;

class AboutKmdgiSeeder extends Seeder
{
    public function run(): void
    {
        AboutKmdgi::create([
            'title'       => 'Apa itu KMDGI?',
            'description' => '<p><strong>KMDGI (Kumpul Mahasiswa Desain Grafis Indonesia)</strong> adalah forum silaturahmi, apresiasi, dan apresiasi karya mahasiswa Desain Grafis / DKV seluruh Indonesia yang diselenggarakan secara rutin setiap dua tahun sekali.</p>
                              <p>Acara ini bertindak sebagai melting pot untuk bertukar pikiran, budaya, serta membahas perkembangan industri kreatif di nusantara.</p>',
        ]);
    }
}