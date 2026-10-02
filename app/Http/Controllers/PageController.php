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

        // 1. Tentukan Edisi Aktif (Berdasarkan Request atau Default yang Is_Active)
        if ($request->filled('edisi')) {
            $edisiAktif = EdisiKmdgi::findOrFail($request->edisi);
        } else {
            $edisiAktif = EdisiKmdgi::where('is_active', 1)->first();

            if (!$edisiAktif && $semuaEdisi->count() > 0) {
                $edisiAktif = EdisiKmdgi::orderBy('id', 'desc')->first();
            }
        }

        // 2. Ambil Kampus Delegasi berdasarkan kolom JSON 'riwayat_status'
        $kampusDelegasi = collect();
        if ($edisiAktif) {
            $validStatuses = ['Anggota', 'Anggota Penuh', 'Peninjau 1', 'Peninjau 2'];

            // Tarik data kampus yang key ID edisinya memiliki value status valid
            // Syntax riwayat_status->id adalah cara Laravel query kolom JSON
            $kampusDelegasiRaw = Kampus::whereIn('riwayat_status->' . $edisiAktif->id, $validStatuses)
                                       ->orderBy('nama_institusi', 'asc')
                                       ->get();

            // Grouping berdasarkan kota dan urutkan abjad kota (sortKeys)
            $kampusDelegasi = $kampusDelegasiRaw->groupBy('lokasi_kota')->sortKeys();
        }

        // 3. Ambil Deskripsi Karya
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
        // 1. Ambil Data Konfigurasi Administrasi Tim Delegasi dari Cache
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

        // 3. Ambil Deskripsi Masing-Masing Kategori Karya untuk Edisi Aktif
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

    public function maps()
    {
        return view('maps');
    }
}