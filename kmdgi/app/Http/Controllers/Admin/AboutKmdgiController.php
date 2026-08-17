<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutKmdgi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutKmdgiController extends Controller
{
    public function edit()
    {
        // Jika data kosong, otomatis buat 1 baris
        $about = AboutKmdgi::first();
        if (!$about) {
            $about = AboutKmdgi::create([
                'title' => 'Apa itu KMDGI?'
            ]);
        }

        return view('admin.about.index', compact('about'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_1'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'image_2'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'image_3'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'image_4'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'image_5'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $about = AboutKmdgi::first();
        
        // Ambil hanya teks untuk menghindari penimpaan file gambar dengan null jika tidak ada upload
        $data = $request->only(['title', 'description']);

        // Looping untuk memproses 5 slot gambar
        for ($i = 1; $i <= 5; $i++) {
            $field = 'image_' . $i;
            $removeField = 'remove_image_' . $i;

            if ($request->hasFile($field)) {
                // Hapus gambar lama jika ada upload baru
                if ($about->$field) Storage::disk('public')->delete($about->$field);
                $data[$field] = $request->file($field)->store('about_kmdgi', 'public');
            } elseif ($request->input($removeField) == '1') {
                // Hapus gambar jika tombol "Hapus Gambar" diklik
                if ($about->$field) Storage::disk('public')->delete($about->$field);
                $data[$field] = null;
            }
        }

        $about->update($data);

        return redirect()->back()->with('success', 'Halaman About KMDGI berhasil diperbarui!');
    }
}