<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TiketPeserta;
use App\Models\EventKmdgi;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth

class PesertaEventController extends Controller
{
    private function buildQuery(Request $request)
    {
        // Hanya ambil tiket Event yang sudah "Aktif" (terverifikasi / gratis)
        $query = TiketPeserta::with(['user', 'event'])
            ->whereNotNull('event_kmdgi_id')
            ->where('status', 'Aktif');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_tiket', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('institusi', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('event_id') && $request->event_id !== 'all') {
            $query->where('event_kmdgi_id', $request->event_id);
        }

        if ($request->filled('jenis') && $request->jenis !== 'all') {
            $query->where('jenis_tiket', $request->jenis);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->buildQuery($request);
        
        $pesertaEvents = $query->latest()->paginate(15)->withQueryString();
        $listEvents = EventKmdgi::orderBy('judul', 'asc')->get();

        return view('admin.peserta_event.index', compact('pesertaEvents', 'listEvents'));
    }

    public function export(Request $request)
    {
        $query = $this->buildQuery($request);
        $dataPeserta = $query->latest()->get();

        // ===========================================================================
        // [LOG AKTIVITAS] Mencatat Ekspor Data Peserta Event ke Excel
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Peserta Event',
            'aksi'       => 'Export',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' mengunduh (export) data rekapitulasi peserta event ke format Excel.',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        $fileName = 'Data_Peserta_Event_' . date('Y-m-d_H-i') . '.xls';

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta http-equiv="content-type" content="text/html; charset=UTF-8"><style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #cbd5e1; padding: 8px 12px; font-family: Arial, sans-serif; font-size: 11pt; vertical-align: top; } th { background-color: #126CFD; color: #ffffff; font-weight: bold; text-align: center; }</style></head>';
        $html .= '<body>';
        $html .= '<table>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>No</th>';
        $html .= '<th>Kode Tiket</th>';
        $html .= '<th>Nama Peserta</th>';
        $html .= '<th>Email Akun</th>';
        $html .= '<th>No WhatsApp</th>';
        $html .= '<th>Institusi Asal</th>';
        $html .= '<th>Jenis Sesi</th>';
        $html .= '<th>Judul Acara</th>';
        $html .= '<th>Waktu Aktifasi Tiket</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        $no = 1;
        foreach ($dataPeserta as $p) {
            $html .= '<tr>';
            $html .= '<td align="center">' . $no++ . '</td>';
            $html .= '<td align="center"><b>' . htmlspecialchars($p->kode_tiket) . '</b></td>';
            $html .= '<td>' . htmlspecialchars($p->user->name ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($p->user->email ?? '-') . '</td>';
            $html .= '<td>`' . htmlspecialchars($p->user->no_hp ?? '-') . '</td>'; // Tanda backtick agar no HP aman dari format rumus Excel
            $html .= '<td>' . htmlspecialchars($p->user->institusi ?? '-') . '</td>';
            $html .= '<td align="center">' . htmlspecialchars($p->jenis_tiket) . '</td>';
            $html .= '<td>' . htmlspecialchars($p->event->judul ?? '-') . '</td>';
            $html .= '<td align="center">' . htmlspecialchars($p->updated_at->format('Y-m-d H:i:s')) . '</td>';
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
}