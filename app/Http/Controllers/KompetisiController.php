<?php

namespace App\Http\Controllers;

use App\Models\JuknisLomba;
use App\Models\Kolaborator;
use Illuminate\Http\Request;

class KompetisiController extends Controller
{
    public function index()
    {
        // Menampilkan daftar lomba (Bisa disesuaikan nanti)
        $lombas = JuknisLomba::where('is_active', 1)->latest()->get();
        return view('kompetisi.index', compact('lombas'));
    }

    public function show($slug)
    {
        $lomba = JuknisLomba::where('slug', $slug)->where('is_active', 1)->firstOrFail();

        // UBAH: dari $lomba->juri_ids menjadi $lomba->juri
        $juriIds = is_array($lomba->juri) ? $lomba->juri : [];
        $dewanJuri = Kolaborator::whereIn('id', $juriIds)->get();

        $targetCountdown = null;
        $timeline = is_string($lomba->timeline) ? json_decode($lomba->timeline, true) : ($lomba->timeline ?? []);
        if (is_array($timeline) && count($timeline) > 0) {
            foreach ($timeline as $fase) {
                if (isset($fase['tanggal']) && strtotime($fase['tanggal']) > time()) {
                    $targetCountdown = $fase['tanggal'];
                    break;
                }
            }
            if (!$targetCountdown) {
                $targetCountdown = end($timeline)['tanggal'] ?? null;
            }
        }

        return view('kompetisi.show', compact('lomba', 'dewanJuri', 'timeline', 'targetCountdown'));
    }
}
