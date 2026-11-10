<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TiketPeserta;
use App\Models\EventKmdgi;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class KehadiranEventController extends Controller
{
    private function buildQuery(Request $request)
    {
        // Tarik data tiket Event (event_kmdgi_id tidak null) yang sudah Hadir (waktu_kehadiran tidak null)
        $query = TiketPeserta::with(['user', 'event'])
            ->whereNotNull('event_kmdgi_id')
            ->whereNotNull('waktu_kehadiran');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_tiket', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%")
                           ->orWhere('institusi', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('event_id') && $request->event_id !== 'all') {
            $query->where('event_kmdgi_id', $request->event_id);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->buildQuery($request);

        // Urutkan berdasarkan waktu kehadiran terbaru
        $kehadiranEvent = $query->orderBy('waktu_kehadiran', 'desc')->paginate(15)->withQueryString();
        $listEvents = EventKmdgi::orderBy('judul', 'asc')->get();

        return view('admin.kehadiran_event.index', compact('kehadiranEvent', 'listEvents'));
    }

    public function export(Request $request)
    {
        $query = $this->buildQuery($request);
        $kehadiran = $query->orderBy('waktu_kehadiran', 'desc')->get();

        // [LOG AKTIVITAS]
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Rekap Kehadiran Event',
            'aksi'       => 'Export Excel',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' mengunduh data rekap kehadiran event ke Excel.',
            'ip_address' => $request->ip(),
        ]);

        $fileName = 'Kehadiran_Event_' . date('Y-m-d_H-i') . '.xls';

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta http-equiv="content-type" content="text/html; charset=UTF-8"><style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #cbd5e1; padding: 8px 12px; font-family: Arial, sans-serif; font-size: 11pt; vertical-align: top; } th { background-color: #126CFD; color: #ffffff; font-weight: bold; text-align: center; }</style></head>';
        $html .= '<body><table><thead><tr><th>No</th><th>Nama Peserta</th><th>Institusi / Asal</th><th>Kode Tiket</th><th>Jenis Sesi</th><th>Nama Acara</th><th>Waktu Scan Masuk</th></tr></thead><tbody>';

        $no = 1;
        foreach ($kehadiran as $tiket) {
            $html .= '<tr>';
            $html .= '<td align="center">' . $no++ . '</td>';
            $html .= '<td>' . htmlspecialchars($tiket->user->name ?? 'User Tidak Diketahui') . '</td>';
            $html .= '<td>' . htmlspecialchars($tiket->user->institusi ?? 'Umum') . '</td>';
            $html .= '<td align="center"><b>' . htmlspecialchars($tiket->kode_tiket) . '</b></td>';
            $html .= '<td align="center">' . htmlspecialchars($tiket->jenis_tiket ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($tiket->event->judul ?? '-') . '</td>';
            $html .= '<td align="center">' . \Carbon\Carbon::parse($tiket->waktu_kehadiran)->format('d M Y, H:i:s') . ' WIB</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></body></html>';

        return response($html, 200, [
            "Content-Type"        => "application/vnd.ms-excel; charset=utf-8",
            "Content-Disposition" => "attachment; filename=\"$fileName\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ]);
    }
}