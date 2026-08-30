<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TiketPeserta;
use App\Models\Penampil;
use Illuminate\Http\Request;

class PesertaPerformanceController extends Controller
{
    private function buildQuery(Request $request)
    {
        $query = TiketPeserta::with(['user', 'penampil'])
            ->whereNotNull('penampil_id')
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

        if ($request->filled('penampil_id') && $request->penampil_id !== 'all') {
            $query->where('penampil_id', $request->penampil_id);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->buildQuery($request);
        
        $pesertaPerformances = $query->latest()->paginate(15)->withQueryString();
        $listPenampil = Penampil::orderBy('nama_penampil', 'asc')->get();

        return view('admin.peserta_performance.index', compact('pesertaPerformances', 'listPenampil'));
    }

    public function export(Request $request)
    {
        $query = $this->buildQuery($request);
        $dataPeserta = $query->latest()->get();

        $fileName = 'Data_Peserta_Performance_' . date('Y-m-d_H-i') . '.xls';

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
        $html .= '<th>Jenis Tiket</th>';
        $html .= '<th>Nama Penampil</th>';
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
            $html .= '<td>`' . htmlspecialchars($p->user->no_hp ?? '-') . '</td>'; 
            $html .= '<td>' . htmlspecialchars($p->user->institusi ?? '-') . '</td>';
            $html .= '<td align="center">' . htmlspecialchars($p->jenis_tiket) . '</td>';
            $html .= '<td>' . htmlspecialchars($p->penampil->nama_penampil ?? '-') . '</td>';
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