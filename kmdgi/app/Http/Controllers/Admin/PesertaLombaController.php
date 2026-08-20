<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesertaLomba;
use App\Models\JuknisLomba;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PesertaLombaController extends Controller
{
    public function index(Request $request)
    {
        $semuaLomba = JuknisLomba::orderBy('id', 'desc')->get();
        $lombaId = $request->input('lomba_id', $semuaLomba->first()->id ?? null);

        $query = PesertaLomba::with(['user', 'lomba']);

        if ($lombaId) $query->where('juknis_lomba_id', $lombaId);

        if ($request->filled('search')) {
            $query->where('nama_tim_peserta', 'like', '%' . $request->search . '%')
                ->orWhere('institusi_asal', 'like', '%' . $request->search . '%');
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

        // Jika dipilih spesifik lomba tertentu, filter. Jika "all", ambil semua.
        if ($lombaId && $lombaId !== 'all') {
            $query->where('juknis_lomba_id', $lombaId);
        }

        if ($request->filled('search')) {
            $query->where('nama_tim_peserta', 'like', '%' . $request->search . '%')
                ->orWhere('institusi_asal', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status_bayar') && $request->status_bayar !== 'all') {
            $query->where('status_pembayaran', $request->status_bayar);
        }

        $peserta = $query->latest()->get();
        $fileName = 'Data_Peserta_Lomba_' . date('Y-m-d_H-i') . '.xls';

        // Menggunakan XML Spreadsheet Excel Native agar ringan, cepat, dan valid
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
        $html .= '<th>Deskripsi Karya</th>'; // <-- TAMBAHAN DESKRIPSI KARYA
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
            $html .= '<td>`' . htmlspecialchars($p->no_whatsapp) . '</td>'; // Tanda backtick agar no HP aman
            $html .= '<td>' . htmlspecialchars($p->user->name ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($p->user->email ?? '-') . '</td>';
            $html .= '<td align="center">' . htmlspecialchars($p->status_pembayaran) . '</td>';
            $html .= '<td align="center">' . htmlspecialchars($p->status_karya) . '</td>';
            $html .= '<td>' . htmlspecialchars($p->judul_karya ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($p->kreator_karya ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($p->deskripsi_karya ?? '-') . '</td>'; // <-- TAMBAHAN DESKRIPSI KARYA
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

    public function verifikasiPembayaran(Request $request, $id)
    {
        $peserta = PesertaLomba::findOrFail($id);
        $request->validate([
            'status_pembayaran' => 'required|in:Lunas,Ditolak,Menunggu Validasi,Gratis',
            'status_karya' => 'required|in:Belum Mengumpulkan,Terkirim,Diskualifikasi'
        ]);

        $peserta->update([
            'status_pembayaran' => $request->status_pembayaran,
            'status_karya'      => $request->status_karya
        ]);

        return redirect()->back()->with('success', 'Status pendaftar berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $peserta = PesertaLomba::findOrFail($id);
        if ($peserta->bukti_pembayaran) Storage::disk('public')->delete($peserta->bukti_pembayaran);
        if ($peserta->file_karya) Storage::disk('public')->delete($peserta->file_karya);
        $peserta->delete();

        return redirect()->back()->with('success', 'Data peserta lomba dihapus permanen.');
    }
}
