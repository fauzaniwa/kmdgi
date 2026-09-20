<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Footer;
use App\Models\LogAktivitas; // <-- [LOG] Import Model Log Aktivitas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- [LOG] Import Auth
use Illuminate\Support\Facades\Storage;

class FooterController extends Controller
{
    public function edit()
    {
        $footer = Footer::first();
        if (!$footer) {
            $footer = Footer::create([
                'bg_color' => '#0a0a0a',
                'copyright_text' => '2026 KMDGI 16. All rights reserved.',
                'menu_links' => [
                    ['title' => 'Jadwal', 'url' => '/#jadwal'],
                    ['title' => 'Galeri Karya', 'url' => '/katalog-karya'],
                    ['title' => 'Tentang KMDGI', 'url' => '/tentang-kami'],
                    ['title' => 'Panduan Delegasi', 'url' => '/panduan-delegasi'],
                ],
                'profile_links' => [
                    ['title' => 'Dashboard', 'url' => '/dashboard'],
                    ['title' => 'Postingan disukai', 'url' => '/karya-disukai'],
                    ['title' => 'Delegasi', 'url' => '/delegasi/status'],
                    ['title' => 'Submisi karya', 'url' => '/delegasi/submisi/karya-kampus'],
                    ['title' => 'Setting', 'url' => '/profile'],
                ]
            ]);
        }

        return view('admin.footer.index', compact('footer'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'bg_color'           => 'required|string|max:10',
            'copyright_text'     => 'required|string|max:255',
            'link_instagram'     => 'nullable|url|max:255',
            'link_tiktok'        => 'nullable|url|max:255',
            'logo'               => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'decoration_desktop' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:3072',
            'decoration_mobile'  => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            // Validasi format link
            'menu_links.*.title' => 'required_with:menu_links.*.url|string',
            'menu_links.*.url'   => 'required_with:menu_links.*.title|string',
            'profile_links.*.title' => 'required_with:profile_links.*.url|string',
            'profile_links.*.url'   => 'required_with:profile_links.*.title|string',
        ]);

        $footer = Footer::first();
        
        // Membersihkan array link dari elemen yang kosong
        $menuLinks = array_filter($request->menu_links ?? [], function($link) {
            return !empty($link['title']) && !empty($link['url']);
        });
        
        $profileLinks = array_filter($request->profile_links ?? [], function($link) {
            return !empty($link['title']) && !empty($link['url']);
        });

        $data = $request->only(['bg_color', 'copyright_text', 'link_instagram', 'link_tiktok']);
        
        // Memastikan key array ter-reset ke 0, 1, 2...
        $data['menu_links'] = array_values($menuLinks);
        $data['profile_links'] = array_values($profileLinks);

        // Handle Upload Logo
        if ($request->hasFile('logo')) {
            if ($footer->logo) Storage::disk('public')->delete($footer->logo);
            $data['logo'] = $request->file('logo')->store('footer_assets', 'public');
        } elseif ($request->remove_logo == '1') {
            if ($footer->logo) Storage::disk('public')->delete($footer->logo);
            $data['logo'] = null;
        }

        // Handle Dekorasi Desktop
        if ($request->hasFile('decoration_desktop')) {
            if ($footer->decoration_desktop) Storage::disk('public')->delete($footer->decoration_desktop);
            $data['decoration_desktop'] = $request->file('decoration_desktop')->store('footer_assets', 'public');
        } elseif ($request->remove_decoration_desktop == '1') {
            if ($footer->decoration_desktop) Storage::disk('public')->delete($footer->decoration_desktop);
            $data['decoration_desktop'] = null;
        }

        // Handle Dekorasi Mobile
        if ($request->hasFile('decoration_mobile')) {
            if ($footer->decoration_mobile) Storage::disk('public')->delete($footer->decoration_mobile);
            $data['decoration_mobile'] = $request->file('decoration_mobile')->store('footer_assets', 'public');
        } elseif ($request->remove_decoration_mobile == '1') {
            if ($footer->decoration_mobile) Storage::disk('public')->delete($footer->decoration_mobile);
            $data['decoration_mobile'] = null;
        }

        $footer->update($data);

        // ===========================================================================
        // [LOG AKTIVITAS] Mengupdate Footer Website
        // ===========================================================================
        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'modul'      => 'Pengaturan Footer',
            'aksi'       => 'Update',
            'deskripsi'  => 'Admin ' . Auth::user()->name . ' memperbarui pengaturan tampilan dan tautan pada Footer Website.',
            'ip_address' => $request->ip(),
        ]);
        // ===========================================================================

        return redirect()->back()->with('success', 'Pengaturan Footer berhasil diperbarui!');
    }
}