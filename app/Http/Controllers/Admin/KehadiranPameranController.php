<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TiketPeserta;

class KehadiranPameranController extends Controller
{
    public function index(Request $request)
    {
        $query = TiketPeserta::with(['user'])
            ->where('jenis_tiket', 'Pameran')
            ->whereNotNull('waktu_kehadiran');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_tiket', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $kehadiranPameran = $query->orderBy('waktu_kehadiran', 'desc')->paginate(15)->withQueryString();

        return view('admin.kehadiran_pameran.index', compact('kehadiranPameran'));
    }

    public function export(Request $request)
    {
        $query = TiketPeserta::with(['user'])
            ->where('jenis_tiket', 'Pameran')
            ->whereNotNull('waktu_kehadiran');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_tiket', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $kehadiran = $query->orderBy('waktu_kehadiran', 'desc')->get();

        // [LOG AKTIVITAS]
        \App\Models\LogAktivitas::create([
            'user_id'    => \Illuminate\Support\Facades\Auth::id(),
            'modul'      => 'Rekap Kehadiran Pameran',
            'aksi'       => 'Export Excel',
            'deskripsi'  => 'Admin ' . \Illuminate\Support\Facades\Auth::user()->name . ' mengunduh data rekap kehadiran pameran ke Excel.',
            'ip_address' => $request->ip(),
        ]);

        $fileName = 'Kehadiran_Pameran_' . date('Y-m-d_H-i') . '.xls';

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta http-equiv="content-type" content="text/html; charset=UTF-8"><style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #cbd5e1; padding: 8px 12px; font-family: Arial, sans-serif; font-size: 11pt; vertical-align: top; } th { background-color: #126CFD; color: #ffffff; font-weight: bold; text-align: center; }</style></head>';
        $html .= '<body><table><thead><tr><th>No</th><th>Nama Peserta</th><th>Institusi / Asal</th><th>Kode Tiket</th><th>Jenis Akses</th><th>Waktu Scan Masuk</th></tr></thead><tbody>';

        $no = 1;
        foreach ($kehadiran as $tiket) {
            $html .= '<tr>';
            $html .= '<td align="center">' . $no++ . '</td>';
            $html .= '<td>' . htmlspecialchars($tiket->user->name ?? 'User Tidak Diketahui') . '</td>';
            $html .= '<td>' . htmlspecialchars($tiket->user->institusi ?? 'Umum') . '</td>';
            $html .= '<td align="center">' . htmlspecialchars($tiket->kode_tiket) . '</td>';
            $html .= '<td align="center">Pameran KMDGI 16</td>';
            $html .= '<td align="center">' . \Carbon\Carbon::parse($tiket->waktu_kehadiran)->format('d M Y, H:i:s') . '</td>';
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
