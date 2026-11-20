<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\WebsiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WebsiteContentController extends Controller
{
    /**
     * Tampilkan Halaman Pengaturan Konten Website
     */
    public function index()
    {
        $rawContents = WebsiteContent::all();
        $contents = [];
        foreach ($rawContents as $item) {
            $contents[$item->key] = $item->value;
        }

        // Parse array misi
        $misiList = [];
        if (!empty($contents['misi'])) {
            $decoded = json_decode($contents['misi'], true);
            if (is_array($decoded)) {
                $misiList = $decoded;
            }
        }

        if (empty($misiList)) {
            $misiList = [
                'Menjangkau & Memperlengkapi setiap orang kudus dengan pelayanan 5 Jawatan (Rasul, Nabi, Penginjil, Guru, Gembala).',
                'Memenuhi 5 panggilan gereja: Menyembah (Proskuneo), Bersekutu (Koinonia), Bersaksi (Marturia), Melayani (Diakonia), Memuridkan (Disciples).',
                'Mengutus menjadi saksi Yesus Kristus yang efektif, Agent of Change.'
            ];
        }

        $latestWarta = \App\Models\WartaJemaat::orderBy('tanggal_warta', 'desc')->take(4)->get();
        $latestPengumuman = \App\Models\Pengumuman::orderBy('is_pinned', 'desc')->orderBy('tanggal', 'desc')->take(4)->get();

        return view('admin.konten.index', compact('contents', 'misiList', 'latestWarta', 'latestPengumuman'));
    }

    /**
     * Simpan Pembaruan Konten Website
     */
    public function update(Request $request)
    {
        $request->validate([
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'hero_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'sejarah_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'struktur_organisasi_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
        ]);

        $keys = [
            'logo',
            'nama_gereja',
            'tagline',
            'slogan',
            'nama_gembala',
            'tahun_berdiri',
            'ayat_kunci',
            'hero_badge',
            'hero_title',
            'hero_subtitle',
            'hero_description',
            'hero_image',
            'hero_image_badge',
            'hero_image_desc',
            'sejarah',
            'sejarah_image',
            'sejarah_badge',
            'sejarah_caption',
            'visi',
            'struktur_organisasi_image',
            'alamat_lengkap',
            'patokan_lokasi',
            'google_maps_url',
            'google_maps_embed',
            'whatsapp',
            'email',
            'jam_kerja',
            'youtube_url',
            'facebook_url',
            'instagram_url',
        ];

        // 1. Simpan field teks biasa
        foreach ($keys as $key) {
            if ($request->has($key)) {
                WebsiteContent::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $request->input($key),
                        'label' => ucwords(str_replace('_', ' ', $key)),
                    ]
                );
            }
        }

        // 2. Upload Logo Gereja (jika diunggah)
        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('uploads/logo', 'public');
            $url = asset('storage/' . $path);
            WebsiteContent::updateOrCreate(
                ['key' => 'logo'],
                ['value' => $url, 'type' => 'image']
            );
        }

        // 3. Upload Gambar Banner (jika diunggah)
        if ($request->hasFile('hero_image_file')) {
            $path = $request->file('hero_image_file')->store('uploads/banners', 'public');
            $url = asset('storage/' . $path);
            WebsiteContent::updateOrCreate(
                ['key' => 'hero_image'],
                ['value' => $url, 'type' => 'image']
            );
        }

        // 4. Upload Gambar Sejarah (jika diunggah)
        if ($request->hasFile('sejarah_image_file')) {
            $path = $request->file('sejarah_image_file')->store('uploads/contents', 'public');
            $url = asset('storage/' . $path);
            WebsiteContent::updateOrCreate(
                ['key' => 'sejarah_image'],
                ['value' => $url, 'type' => 'image']
            );
        }

        // 5. Upload Gambar Struktur Kepengurusan (jika diunggah)
        if ($request->hasFile('struktur_organisasi_image_file')) {
            $path = $request->file('struktur_organisasi_image_file')->store('uploads/contents', 'public');
            $url = asset('storage/' . $path);
            WebsiteContent::updateOrCreate(
                ['key' => 'struktur_organisasi_image'],
                ['value' => $url, 'type' => 'image']
            );
        }

        // 6. Simpan Misi (Array of strings disimpan dalam format JSON)
        if ($request->has('misi') && is_array($request->input('misi'))) {
            $misiItems = array_values(array_filter(array_map('trim', $request->input('misi')), function($val) {
                return !empty($val);
            }));

            WebsiteContent::updateOrCreate(
                ['key' => 'misi'],
                [
                    'label' => 'Misi Pelayanan',
                    'value' => json_encode($misiItems, JSON_UNESCAPED_UNICODE),
                    'type' => 'json'
                ]
            );
        }

        // Audit Log
        if (class_exists(AuditLog::class)) {
            AuditLog::log('update', 'website_content', 'Memperbarui konten publik website gereja');
        }

        return redirect()->route('admin.konten.index')->with('success', 'Konten website berhasil disimpan dan langsung tayang!');
    }
}
