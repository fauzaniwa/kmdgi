<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Menampilkan halaman daftar notifikasi user yang sedang login.
     */
    public function index()
    {
        // Ambil semua notifikasi milik user yang sedang login (terbaru di atas) dengan pagination 10 data per halaman
        $notifikasis = auth()->user()->notifications()->paginate(10);

        return view('notifikasi.index', compact('notifikasis'));
    }

    /**
     * Menandai satu notifikasi tertentu sudah dibaca, lalu diarahkan ke URL tujuan.
     */
    public function readAndRedirect($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            // Ubah status menjadi sudah dibaca (read_at diisi timestamp saat ini)
            $notification->markAsRead();

            // Ambil URL tujuan dari payload JSON data notifikasi
            $targetUrl = $notification->data['url'] ?? route('notifikasi.index');

            return redirect($targetUrl);
        }

        return redirect()->route('notifikasi.index')->with('error', 'Notifikasi tidak ditemukan.');
    }

    /**
     * Menandai seluruh notifikasi user sebagai sudah dibaca.
     */
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}