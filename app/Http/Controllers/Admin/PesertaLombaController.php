<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesertaLomba;
use App\Models\JuknisLomba;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use App\Notifications\GeneralNotification; // <-- [NOTIFIKASI] Import Notifikasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class PesertaLombaController extends Controller
{
    public function index(Request $request)
    {
        $semuaLomba = JuknisLomba::orderBy('id', 'desc')->get();
        $lombaId = $request->input('lomba_id', $semuaLomba->first()->id ?? null);

        $query = PesertaLomba::with(['user', 'lomba']);

        if ($lombaId) {
            $query->where('juknis_lomba_id', $lombaId);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_tim_peserta', 'like', '%' . $search . '%')
                  ->orWhere('institusi_asal', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status_bayar') && $request->status_bayar !== 'all') {
            $query->where('status_pembayaran', $request->status_bayar);
        }

        $dataPeserta = $query->latest()->paginate(15)->withQueryString();

        return view('admin.peserta_lomba.index', compact('dataPeserta', 'semuaLomba', 'lombaId'));
    }

    public function export(Request $request)
    {
        $lombaId = $request->input('export_lomba_id');
        $query = PesertaLomba::with(['user', 'lomba']);

        if ($lombaId && $lombaId !== 'all') {
            $query->where('juknis_lomba_id', $lombaId);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_tim_peserta', 'like', '%' . $search . '%')
                  ->orWhere('institusi_asal', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status_bayar') && $request->status_bayar !== 'all') {
            $query->where('status_pembayaran', $request->status_bayar);
        }

        $peserta = $query->latest()->get();

        // ===========================================================================
        // [LOG AKTIVITAS] Export Excel Peserta Lomba
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Peserta Lomba',
            'aksi'       => 'Export Excel',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' mengunduh (export) data rekapitulasi peserta lomba ke format Excel.',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        $fileName = 'Data_Peserta_Lomba_' . date('Y-m-d_H-i') . '.xls';

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta http-equiv="content-type" content="text/html; charset=UTF-8"><style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #cbd5e1; padding: 8px 12px; font-family: Arial, sans-serif; font-size: 11pt; vertical-align: top; } th { background-color: #126CFD; color: #ffffff; font-weight: bold; text-align: center; }</style></head>';
        $html .= '<body>';
        $html .= '<table>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>No</th>';
        $html .= '<th>Judul Lomba</th>';
        $html .= '<th>Nama Tim / Peserta</th>';
        $html .= '<th>Institusi Asal</th>';
        $html .= '<th>Kategori Pendaftar</th>';
        $html .= '<th>No WhatsApp</th>';
        $html .= '<th>Nama Akun User</th>';
        $html .= '<th>Email Akun</th>';
        $html .= '<th>Status Pembayaran</th>';
        $html .= '<th>Status Karya</th>';
        $html .= '<th>Judul Karya</th>';
        $html .= '<th>Kreator Karya</th>';
        $html .= '<th>Deskripsi Karya</th>';
        $html .= '<th>Tautan / Link Karya</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        $no = 1;
        foreach ($peserta as $p) {
            $html .= '<tr>';
            $html .= '<td align="center">' . $no++ . '</td>';
            $html .= '<td>' . htmlspecialchars($p->lomba->judul ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($p->nama_tim_peserta) . '</td>';
            $html .= '<td>' . htmlspecialchars($p->institusi_asal ?? '-') . '</td>';
            $html .= '<td align="center">' . htmlspecialchars($p->kategori_pendaftar) . '</td>';
            $html .= '<td>`' . htmlspecialchars($p->no_whatsapp) . '</td>';
            $html .= '<td>' . htmlspecialchars($p->user->name ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($p->user->email ?? '-') . '</td>';
            $html .= '<td align="center">' . htmlspecialchars($p->status_pembayaran) . '</td>';
            $html .= '<td align="center">' . htmlspecialchars($p->status_karya) . '</td>';
            $html .= '<td>' . htmlspecialchars($p->judul_karya ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($p->kreator_karya ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($p->deskripsi_karya ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($p->link_karya ?? '-') . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</body>';
        $html .= '</html>';

        return response($html, 200, [
            "Content-Type"        => "application/vnd.ms-excel; charset=utf-8",
            "Content-Disposition" => "attachment; filename=\"$fileName\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ]);
    }

    public function exportZipKarya(Request $request)
    {
        $lombaId = $request->input('export_lomba_id');
        
        $query = PesertaLomba::with(['lomba', 'user'])->whereNotNull('file_karya');

        if ($lombaId && $lombaId !== 'all') {
            $query->where('juknis_lomba_id', $lombaId);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_tim_peserta', 'like', '%' . $search . '%')
                  ->orWhere('institusi_asal', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status_bayar') && $request->status_bayar !== 'all') {
            $query->where('status_pembayaran', $request->status_bayar);
        }

        $pesertas = $query->get();

        if ($pesertas->isEmpty()) {
            return redirect()->back()->withErrors(['Tidak ada satupun file karya fisik (.zip, .pdf) yang tersedia untuk diunduh pada filter ini.']);
        }

        $zip = new ZipArchive();
        $zipFileName = 'Kumpulan_Karya_Lomba_' . date('Ymd_His') . '.zip';
        $zipFilePath = storage_path('app/public/' . $zipFileName);

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($pesertas as $p) {
                $filePath = storage_path('app/public/' . $p->file_karya);
                
                if (file_exists($filePath)) {
                    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                    
                    $safeTeamName = preg_replace('/[^A-Za-z0-9\-\s_]/', '', $p->nama_tim_peserta);
                    $safeTeamName = trim($safeTeamName);
                    
                    $lombaPrefix = $p->lomba ? preg_replace('/[^A-Za-z0-9\-]/', '', substr($p->lomba->judul, 0, 15)) : 'KMDGI';
                    
                    $folderName = $safeTeamName . '_' . $p->id;
                    $fileNameInsideZip = $folderName . '/' . $lombaPrefix . '_Karya_' . $safeTeamName . '.' . $extension;
                    
                    $zip->addFile($filePath, $fileNameInsideZip);
                }
            }
            $zip->close();

            // ===========================================================================
            // [LOG AKTIVITAS] Export ZIP File Karya Peserta Lomba
            // ===========================================================================
            LogAktivitas::create([
                'user_id'    => Auth::id(),
                'modul'      => 'Peserta Lomba',
                'aksi'       => 'Export ZIP Karya',
                'deskripsi'  => 'Admin ' . Auth::user()->name . ' mengunduh arsip ZIP file karya fisik peserta lomba.',
                'ip_address' => $request->ip(),
            ]);
            // ===========================================================================

        } else {
            return redirect()->back()->withErrors(['Gagal membuat file ZIP. Pastikan server memiliki permission untuk menulis data.']);
        }

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    public function verifikasiPembayaran(Request $request, $id)
    {
        $peserta = PesertaLomba::with(['lomba', 'user'])->findOrFail($id);
        
        $request->validate([
            'status_pembayaran' => 'required|in:Lunas,Ditolak,Menunggu Validasi,Gratis',
            'status_karya'      => 'required|in:Belum Mengumpulkan,Terkirim,Diskualifikasi'
        ]);

        $peserta->update([
            'status_pembayaran' => $request->status_pembayaran,
            'status_karya'      => $request->status_karya
        ]);

        // ===========================================================================
        // [NOTIFIKASI] Kirim Notifikasi ke User Terkait Status Pendaftaran Lomba
        // ===========================================================================
        if ($peserta->user) {
            $namaLomba = $peserta->lomba ? $peserta->lomba->judul_lomba : 'Perlombaan';
            
            if ($request->status_pembayaran === 'Lunas' || $request->status_pembayaran === 'Gratis') {
                $peserta->user->notify(new GeneralNotification(
                    'Pendaftaran Lomba Divalidasi (Lunas)',
                    "Status pembayaran untuk tim \"{$peserta->nama_tim_peserta}\" pada lomba \"{$namaLomba}\" telah dinyatakan LUNAS/VALID.",
                    'success',
                    route('peserta.status-lomba')
                ));
            } elseif ($request->status_pembayaran === 'Ditolak') {
                $peserta->user->notify(new GeneralNotification(
                    'Bukti Pembayaran Lomba Ditolak',
                    "Maaf, bukti pembayaran untuk tim \"{$peserta->nama_tim_peserta}\" ditolak oleh panitia. Silakan unggah ulang bukti pembayaran yang benar.",
                    'danger',
                    route('peserta.status-lomba')
                ));
            }
        }
        // ===========================================================================

        // ===========================================================================
        // [LOG AKTIVITAS] Verifikasi Pembayaran & Status Peserta Lomba
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Peserta Lomba',
            'aksi'       => 'Verifikasi',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' memperbarui status tim "' . $peserta->nama_tim_peserta . '" (Pembayaran: ' . $request->status_pembayaran . ', Karya: ' . $request->status_karya . ').',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Status pendaftar berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $peserta = PesertaLomba::findOrFail($id);
        $namaTim = $peserta->nama_tim_peserta; // Simpan nama tim untuk log

        if ($peserta->bukti_pembayaran) Storage::disk('public')->delete($peserta->bukti_pembayaran);
        if ($peserta->file_karya) Storage::disk('public')->delete($peserta->file_karya);
        
        $peserta->delete();

        // ===========================================================================
        // [LOG AKTIVITAS] Menghapus Peserta Lomba
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Peserta Lomba',
            'aksi'       => 'Delete',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' menghapus permanen data peserta lomba tim "' . $namaTim . '".',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Data peserta lomba dihapus permanen.');
    }
}