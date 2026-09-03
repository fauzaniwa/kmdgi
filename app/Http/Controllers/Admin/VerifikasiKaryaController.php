<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubmisiKarya;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class VerifikasiKaryaController extends Controller
{
    /**
     * Menampilkan Daftar Submisi Karya Berdasarkan Kategori
     */
    public function index(Request $request, $kategori)
    {
        $kategori = strtolower($kategori);
        $kategoriValid = ['tematik', 'simbiotik', 'simbolik'];

        if (!in_array($kategori, $kategoriValid)) {
            abort(404, 'Kategori karya tidak ditemukan.');
        }

        // Ambil data submisi yang sudah final (status_draft = 0)
        $query = SubmisiKarya::with('user')
            ->where('kategori_karya', $kategori)
            ->where('status_draft', 0);

        // Fitur Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_karya', 'like', "%{$search}%")
                  ->orWhere('kreator_karya', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('institusi', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Berdasarkan Status Verifikasi
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status_verifikasi', $request->status);
        }

        $dataSubmisi = $query->latest()->paginate(12)->withQueryString();

        return view('admin.verifikasi_karya.index', compact('kategori', 'dataSubmisi'));
    }

    /**
     * Mengupdate Status Verifikasi dan Catatan Revisi
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:Terverifikasi,Revisi,Ditolak',
            'catatan_revisi'    => 'required_if:status_verifikasi,Revisi,Ditolak|nullable|string|max:2000'
        ], [
            'catatan_revisi.required_if' => 'Catatan revisi wajib diisi jika status diubah menjadi Revisi atau Ditolak.'
        ]);

        $submisi = SubmisiKarya::findOrFail($id);
        $submisi->status_verifikasi = $request->status_verifikasi;
        
        if ($request->status_verifikasi === 'Terverifikasi') {
            $submisi->catatan_revisi = null;
        } else {
            $submisi->catatan_revisi = $request->catatan_revisi;
        }

        $submisi->save();

        return redirect()->back()->with('success', 'Status karya "' . $submisi->judul_karya . '" berhasil diubah menjadi ' . $request->status_verifikasi . '.');
    }

    /**
     * Mengekspor Data Submisi beserta File Media menjadi file ZIP
     */
    public function exportCsv(Request $request, $kategori)
    {
        $kategori = strtolower($kategori);
        
        $query = SubmisiKarya::with('user')
            ->where('kategori_karya', $kategori)
            ->where('status_draft', 0)
            ->latest();

        // Terapkan filter yang sama dengan yang ada di UI tabel
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_karya', 'like', "%{$search}%")
                  ->orWhere('kreator_karya', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('institusi', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status_verifikasi', $request->status);
        }

        $dataSubmisi = $query->get();

        // -----------------------------------------------------
        // 1. BUAT KONTEN FILE EXCEL (.XLS) HTML
        // -----------------------------------------------------
        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta http-equiv="content-type" content="text/html; charset=UTF-8"><style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #cbd5e1; padding: 8px 12px; font-family: Arial, sans-serif; font-size: 11pt; vertical-align: top; } th { background-color: #126CFD; color: #ffffff; font-weight: bold; text-align: center; }</style></head>';
        $html .= '<body>';
        $html .= '<table>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>No</th>';
        $html .= '<th>Institusi</th>';
        $html .= '<th>Judul Karya</th>';
        $html .= '<th>Kreator / Tim</th>';
        $html .= '<th>Status Verifikasi</th>';
        $html .= '<th>Tautan (Link) Karya</th>';
        $html .= '<th>Catatan Revisi / Penolakan</th>';
        $html .= '<th>Waktu Submit</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        $no = 1;
        foreach ($dataSubmisi as $row) {
            $html .= '<tr>';
            $html .= '<td align="center">' . $no++ . '</td>';
            $html .= '<td>' . htmlspecialchars($row->user->institusi ?? 'Umum') . '</td>';
            $html .= '<td>' . htmlspecialchars($row->judul_karya ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($row->kreator_karya ?? '-') . '</td>';
            $html .= '<td align="center">' . htmlspecialchars($row->status_verifikasi ?? 'Menunggu') . '</td>';
            $html .= '<td>' . htmlspecialchars($row->link_karya ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($row->catatan_revisi ?? '-') . '</td>';
            $html .= '<td align="center">' . htmlspecialchars($row->created_at->format('Y-m-d H:i:s')) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</body>';
        $html .= '</html>';

        // -----------------------------------------------------
        // 2. BUAT FILE ZIP (GABUNG EXCEL & MEDIA FILES)
        // -----------------------------------------------------
        $zipFileName = 'Export_Karya_' . ucfirst($kategori) . '_' . date('Y-m-d_H-i') . '.zip';
        $zipPath = storage_path('app/' . $zipFileName); // Simpan sementara di storage local

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            
            // Masukkan string HTML Excel tadi menjadi file fisik ke dalam ZIP
            $zip->addFromString('Data_Rekap_' . ucfirst($kategori) . '.xls', $html);

            foreach ($dataSubmisi as $row) {
                // Bikin format nama folder: NamaInstitusi/JudulKarya
                $namaInstitusi = Str::slug($row->user->institusi ?? 'Umum', '_');
                $judulKarya = Str::slug($row->judul_karya ?? 'Tanpa_Judul', '_');
                $folderName = "Media_Karya/{$namaInstitusi}/{$judulKarya}";

                // Masukkan Thumbnail
                if (!empty($row->thumbnail_karya) && Storage::disk('public')->exists($row->thumbnail_karya)) {
                    $filePath = Storage::disk('public')->path($row->thumbnail_karya);
                    $zip->addFile($filePath, $folderName . '/Thumbnail_' . basename($row->thumbnail_karya));
                }

                // Masukkan File Utama (.zip / .pdf)
                if (!empty($row->file_karya) && Storage::disk('public')->exists($row->file_karya)) {
                    $filePath = Storage::disk('public')->path($row->file_karya);
                    $zip->addFile($filePath, $folderName . '/FileUtama_' . basename($row->file_karya));
                }

                // Masukkan Galeri Media Tambahan (Array)
                $mediaArray = is_string($row->media_karya) ? json_decode($row->media_karya, true) : ($row->media_karya ?? []);
                if (is_array($mediaArray)) {
                    foreach ($mediaArray as $index => $mediaPath) {
                        if (!empty($mediaPath) && Storage::disk('public')->exists($mediaPath)) {
                            $filePath = Storage::disk('public')->path($mediaPath);
                            $zip->addFile($filePath, $folderName . '/MediaTambahan_' . ($index + 1) . '_' . basename($mediaPath));
                        }
                    }
                }
            }

            $zip->close();
        }

        // Return Download ZIP & Otomatis Hapus ZIP dari Server setelah terunduh
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}