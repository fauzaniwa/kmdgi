<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutKmdgi;
use App\Models\SejarahKmdgi;
use App\Models\EdisiKmdgi;
use App\Models\Kampus;
use App\Models\DeskripsiKarya;
use App\Models\PanduanDelegasi;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class PageController extends Controller
{
    /**
     * Halaman Statis / Dinamis: Tentang KMDGI
     */
    public function tentangKami(Request $request)
    {
        $about = AboutKmdgi::first();
        $sejarahList = SejarahKmdgi::orderBy('tahun', 'asc')->get();
        $semuaEdisi = EdisiKmdgi::orderBy('id', 'desc')->get();

        if ($request->filled('edisi')) {
            $edisiAktif = EdisiKmdgi::with(['kampus' => function($q) {
                $q->whereIn('edisi_kampus.status_keanggotaan', ['Anggota', 'Anggota Penuh', 'Peninjau 1', 'Peninjau 2'])
                  ->orderBy('nama_institusi', 'asc');
            }])->findOrFail($request->edisi);
        } else {
            $edisiAktif = EdisiKmdgi::with(['kampus' => function($q) {
                $q->whereIn('edisi_kampus.status_keanggotaan', ['Anggota', 'Anggota Penuh', 'Peninjau 1', 'Peninjau 2'])
                  ->orderBy('nama_institusi', 'asc');
            }])->where('is_active', 1)->first();

            if (!$edisiAktif && $semuaEdisi->count() > 0) {
                $edisiAktif = EdisiKmdgi::with(['kampus' => function($q) {
                    $q->whereIn('edisi_kampus.status_keanggotaan', ['Anggota', 'Anggota Penuh', 'Peninjau 1', 'Peninjau 2'])
                      ->orderBy('nama_institusi', 'asc');
                }])->orderBy('id', 'desc')->first();
            }
        }

        $kampusDelegasi = collect();
        if ($edisiAktif && $edisiAktif->kampus) {
            $kampusDelegasi = $edisiAktif->kampus->groupBy('lokasi_kota')->sortKeys();
        }

        $karyaTematik = null;
        $karyaSimbiotik = null;
        $karyaSimbolik = null;

        if ($edisiAktif) {
            $karyaTematik = DeskripsiKarya::where('edisi_kmdgi_id', $edisiAktif->id)->where('kategori_karya', 'Tematik')->first();
            $karyaSimbiotik = DeskripsiKarya::where('edisi_kmdgi_id', $edisiAktif->id)->where('kategori_karya', 'Simbiotik')->first();
            $karyaSimbolik = DeskripsiKarya::where('edisi_kmdgi_id', $edisiAktif->id)->where('kategori_karya', 'Simbolik')->first();
        }

        return view('pages.tentang-kami', compact(
            'about', 
            'sejarahList', 
            'semuaEdisi', 
            'edisiAktif', 
            'kampusDelegasi', 
            'karyaTematik', 
            'karyaSimbiotik', 
            'karyaSimbolik'
        ));
    }

    /**
     * Halaman Statis / Dinamis: Panduan Delegasi
     */
    public function panduanDelegasi()
    {
        // 1. Ambil Data Konfigurasi Administrasi Tim Delegasi dari Cache (sinkron dengan DelegasiController)
        $configBerkas = [
            'deadline_pembayaran'       => Carbon::parse(Cache::get('deadline_pembayaran', '2026-09-30T23:59')),
            'deadline_formulir'         => Carbon::parse(Cache::get('deadline_formulir', '2026-10-15T23:59')),
            'text_bukti_pembayaran'     => Cache::get('text_bukti_pembayaran', 'Struk/Resi transfer pendaftaran delegasi resmi (Format: JPG/PNG/PDF, Max 5MB).'),
            'text_formulir_pendaftaran' => Cache::get('text_formulir_pendaftaran', 'Formulir resmi bertanda tangan Ketua Delegasi (Format: PDF, Max 5MB).'),
            'text_buku_panduan'         => Cache::get('text_buku_panduan', 'Pahami seluruh aturan, timeline, dan syarat berkas yang harus diunggah.'),
            'file_buku_panduan'         => Cache::get('file_buku_panduan', null)
        ];

        // 2. Ambil Panduan Delegasi yang diinput Admin
        $panduan = PanduanDelegasi::first();

        // 3. Ambil Deskripsi Masing-Masing Kategori Karya untuk Edisi Aktif (Tematik, Simbiotik, Simbolik)
        $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();

        $karyaTematik = null;
        $karyaSimbiotik = null;
        $karyaSimbolik = null;

        if ($edisiAktif) {
            $karyaTematik = DeskripsiKarya::where('edisi_kmdgi_id', $edisiAktif->id)->where('kategori_karya', 'Tematik')->first();
            $karyaSimbiotik = DeskripsiKarya::where('edisi_kmdgi_id', $edisiAktif->id)->where('kategori_karya', 'Simbiotik')->first();
            $karyaSimbolik = DeskripsiKarya::where('edisi_kmdgi_id', $edisiAktif->id)->where('kategori_karya', 'Simbolik')->first();
        }

        return view('pages.panduan-delegasi', compact(
            'configBerkas',
            'panduan',
            'edisiAktif',
            'karyaTematik',
            'karyaSimbiotik',
            'karyaSimbolik'
        ));
    }
}