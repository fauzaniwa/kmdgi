<?php

namespace App\Http\Controllers;

use App\Models\SubmisiKarya;
use App\Models\BoothDelegasi;
use App\Models\Kampus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShareInstagramController extends Controller
{
    public function generator($slug)
    {
        // 1. Cek di BoothDelegasi (Tematik)
        $booth = BoothDelegasi::with(['user'])
            ->get()
            ->first(fn($item) => Str::slug($item->institusi) === $slug || ($item->judul_karya_tematik && Str::slug($item->judul_karya_tematik) === $slug));

        if ($booth) {
            $karya = (object)[
                'judul'     => $booth->judul_karya_tematik,
                'kreator'   => $booth->kreator_karya,
                'institusi' => $booth->institusi,
                'thumb'     => $booth->foto_utama ? asset('storage/' . $booth->foto_utama) : asset('images/default-hero.jpg'),
                'kategori'  => 'Tematik',
                'url'       => route('voting.booth.show', Str::slug($booth->institusi)),
            ];
            $logoKampus = Kampus::where('nama_institusi', $booth->institusi)->value('logo_institusi');
            $karya->logo = $logoKampus ? asset('storage/' . $logoKampus) : null;

            return view('katalog.karya.share_ig', compact('karya'));
        }

        // 2. Cek di SubmisiKarya Reguler
        $submisi = SubmisiKarya::with(['user'])
            ->get()
            ->first(fn($item) => Str::slug($item->judul_karya) === $slug);

        if ($submisi) {
            $inst = $submisi->user->institusi ?? 'Institusi Umum';
            $karya = (object)[
                'judul'     => $submisi->judul_karya,
                'kreator'   => $submisi->kreator_karya,
                'institusi' => $inst,
                'thumb'     => $submisi->thumbnail_karya ? asset('storage/' . $submisi->thumbnail_karya) : asset('images/default-hero.jpg'),
                'kategori'  => ucfirst($submisi->kategori_karya),
                'url'       => route('katalog.karya.show', Str::slug($submisi->judul_karya)),
            ];
            $logoKampus = Kampus::where('nama_institusi', $inst)->value('logo_institusi');
            $karya->logo = $logoKampus ? asset('storage/' . $logoKampus) : null;

            return view('katalog.karya.share_ig', compact('karya'));
        }

        abort(404, 'Karya tidak ditemukan.');
    }
}