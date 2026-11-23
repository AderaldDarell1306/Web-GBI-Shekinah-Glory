<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WartaJemaat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WartaController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $q = $request->input('q');

        $query = WartaJemaat::query();

        if ($status) {
            $query->where('status', $status);
        }

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('edisi', 'like', "%{$q}%")
                    ->orWhere('tema_mingguan', 'like', "%{$q}%")
                    ->orWhere('ayat_tema', 'like', "%{$q}%")
                    ->orWhere('ringkasan_khotbah', 'like', "%{$q}%");
            });
        }

        $wartaList = $query->orderBy('tanggal_warta', 'desc')->paginate(12)->withQueryString();

        return view('admin.warta.index', compact('wartaList', 'status', 'q'));
    }

    public function create()
    {
        // Tanggal minggu terdekat (hari Minggu ini atau Minggu berikutnya)
        $today = Carbon::today();
        $targetSunday = $today->isSunday() ? $today : $today->next(Carbon::SUNDAY);
        $defaultEdisi = "Edisi Minggu, " . $targetSunday->translatedFormat('d F Y');

        // Buat mock warta sementara untuk menghitung ulang tahun minggu ini
        $tempWarta = new WartaJemaat(['tanggal_warta' => $targetSunday]);
        $ultahList = $tempWarta->jemaat_ulang_tahun_minggu_ini;

        return view('admin.warta.create', compact('targetSunday', 'defaultEdisi', 'ultahList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_warta' => 'required|date',
            'edisi' => 'required|string|max:100',
            'tema_mingguan' => 'nullable|string|max:200',
            'gambar_sampul' => 'nullable|string|max:500',
            'gambar_sampul_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'ayat_tema' => 'nullable|string|max:150',
            'ringkasan_khotbah' => 'nullable|string',
            'pokok_doa' => 'nullable|string',
            'pengumuman_kegiatan' => 'nullable|string',
            'laporan_keuangan_ringkas' => 'nullable|string',
            'status' => 'required|in:draft,terbit',
            'file_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'buletin_tipe' => 'nullable|in:none,upload,link,manual',
            'buletin_judul' => 'nullable|string|max:200',
            'buletin_file' => 'nullable|file|max:30720',
            'buletin_link' => 'nullable|string|max:500',
            'buletin_content' => 'nullable|string',
        ]);

        $pdfPath = null;
        if ($request->hasFile('file_pdf')) {
            $pdfPath = $request->file('file_pdf')->store('warta_pdf', 'public');
        }

        $buletinFilePath = null;
        if ($request->hasFile('buletin_file')) {
            $buletinFilePath = $request->file('buletin_file')->store('buletin_pdf', 'public');
        }

        $gambarSampul = $request->input('gambar_sampul');
        if ($request->hasFile('gambar_sampul_file')) {
            $gambarSampul = $request->file('gambar_sampul_file')->store('warta_images', 'public');
        }

        $warta = WartaJemaat::create([
            'tanggal_warta' => $validated['tanggal_warta'],
            'edisi' => $validated['edisi'],
            'tema_mingguan' => $validated['tema_mingguan'],
            'gambar_sampul' => $gambarSampul,
            'ayat_tema' => $validated['ayat_tema'],
            'ringkasan_khotbah' => $validated['ringkasan_khotbah'],
            'pokok_doa' => $validated['pokok_doa'],
            'pengumuman_kegiatan' => $validated['pengumuman_kegiatan'],
            'laporan_keuangan_ringkas' => $validated['laporan_keuangan_ringkas'],
            'status' => $validated['status'],
            'file_pdf' => $pdfPath,
            'buletin_tipe' => $validated['buletin_tipe'] ?? 'none',
            'buletin_judul' => $validated['buletin_judul'] ?? null,
            'buletin_file' => $buletinFilePath,
            'buletin_link' => $validated['buletin_link'] ?? null,
            'buletin_content' => $validated['buletin_content'] ?? null,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.warta.index')->with('success', 'Warta Jemaat berhasil disimpan!');
    }

    public function show($id)
    {
        $warta = WartaJemaat::findOrFail($id);
        $ultahList = $warta->jemaat_ulang_tahun_minggu_ini;

        // Generate teks WhatsApp yang diformat khusus untuk Grup WhatsApp Jemaat
        $waBroadcastText = $this->generateGroupWhatsAppText($warta, $ultahList);

        return view('admin.warta.show', compact('warta', 'ultahList', 'waBroadcastText'));
    }

    public function edit($id)
    {
        $warta = WartaJemaat::findOrFail($id);
        $ultahList = $warta->jemaat_ulang_tahun_minggu_ini;

        return view('admin.warta.edit', compact('warta', 'ultahList'));
    }

    public function update(Request $request, $id)
    {
        $warta = WartaJemaat::findOrFail($id);

        $validated = $request->validate([
            'tanggal_warta' => 'required|date',
            'edisi' => 'required|string|max:100',
            'tema_mingguan' => 'nullable|string|max:200',
            'gambar_sampul' => 'nullable|string|max:500',
            'gambar_sampul_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'ayat_tema' => 'nullable|string|max:150',
            'ringkasan_khotbah' => 'nullable|string',
            'pokok_doa' => 'nullable|string',
            'pengumuman_kegiatan' => 'nullable|string',
            'laporan_keuangan_ringkas' => 'nullable|string',
            'status' => 'required|in:draft,terbit',
            'file_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'buletin_tipe' => 'nullable|in:none,upload,link,manual',
            'buletin_judul' => 'nullable|string|max:200',
            'buletin_file' => 'nullable|file|max:30720',
            'buletin_link' => 'nullable|string|max:500',
            'buletin_content' => 'nullable|string',
        ]);

        if ($request->hasFile('file_pdf')) {
            if ($warta->file_pdf && Storage::disk('public')->exists($warta->file_pdf)) {
                Storage::disk('public')->delete($warta->file_pdf);
            }
            $warta->file_pdf = $request->file('file_pdf')->store('warta_pdf', 'public');
        }

        if ($request->hasFile('buletin_file')) {
            if ($warta->buletin_file && Storage::disk('public')->exists($warta->buletin_file)) {
                Storage::disk('public')->delete($warta->buletin_file);
            }
            $warta->buletin_file = $request->file('buletin_file')->store('buletin_pdf', 'public');
        }

        $gambarSampul = $request->input('gambar_sampul', $warta->gambar_sampul);
        if ($request->hasFile('gambar_sampul_file')) {
            if ($warta->gambar_sampul && !str_starts_with($warta->gambar_sampul, 'http') && Storage::disk('public')->exists($warta->gambar_sampul)) {
                Storage::disk('public')->delete($warta->gambar_sampul);
            }
            $gambarSampul = $request->file('gambar_sampul_file')->store('warta_images', 'public');
        }

        $warta->update([
            'tanggal_warta' => $validated['tanggal_warta'],
            'edisi' => $validated['edisi'],
            'tema_mingguan' => $validated['tema_mingguan'],
            'gambar_sampul' => $gambarSampul,
            'ayat_tema' => $validated['ayat_tema'],
            'ringkasan_khotbah' => $validated['ringkasan_khotbah'],
            'pokok_doa' => $validated['pokok_doa'],
            'pengumuman_kegiatan' => $validated['pengumuman_kegiatan'],
            'laporan_keuangan_ringkas' => $validated['laporan_keuangan_ringkas'],
            'status' => $validated['status'],
            'buletin_tipe' => $validated['buletin_tipe'] ?? $warta->buletin_tipe,
            'buletin_judul' => $validated['buletin_judul'] ?? $warta->buletin_judul,
            'buletin_file' => $warta->buletin_file,
            'buletin_link' => $validated['buletin_link'] ?? $warta->buletin_link,
            'buletin_content' => $validated['buletin_content'] ?? $warta->buletin_content,
        ]);

        return redirect()->route('admin.warta.index')->with('success', 'Warta Jemaat berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $warta = WartaJemaat::findOrFail($id);
        if ($warta->file_pdf && Storage::disk('public')->exists($warta->file_pdf)) {
            Storage::disk('public')->delete($warta->file_pdf);
        }
        $warta->delete();

        return redirect()->route('admin.warta.index')->with('success', 'Warta Jemaat berhasil dihapus!');
    }

    /**
     * Format broadcast WhatsApp khusus untuk Grup WhatsApp Jemaat
     */
    private function generateGroupWhatsAppText(WartaJemaat $warta, $ultahList)
    {
        $lines = [];
        $lines[] = "✨ *WARTA JEMAAT & WARTA SEPEKAN* ✨";
        $lines[] = "*GBI SHEKINAH GLORY NAMOSAIN - KUPANG*";
        $lines[] = "🗓 " . $warta->edisi;
        $lines[] = "━━━━━━━━━━━━━━━━━━━━━━━";
        $lines[] = "";

        if ($warta->tema_mingguan) {
            $lines[] = "📖 *TEMA MINGGUAN:*";
            $lines[] = "\"*" . trim($warta->tema_mingguan) . "*\"";
            if ($warta->ayat_tema) {
                $lines[] = "📜 *" . trim($warta->ayat_tema) . "*";
            }
            $lines[] = "";
        }

        if ($warta->ringkasan_khotbah) {
            $lines[] = "🎙 *RENUNGAN & INTI FIRMAN TUHAN:*";
            $lines[] = trim($warta->ringkasan_khotbah);
            $lines[] = "";
        }

        // Ucapan Ulang Tahun Jemaat Pekan Ini
        $lines[] = "🎂 *UCAPAN SELAMAT ULANG TAHUN SEPEKAN:*";
        $lines[] = "_\"Tuhan memberkati engkau dan melindungi engkau; Tuhan menyinari engkau dengan wajah-Nya dan memberi engkau kasih karunia.\"_ (Bilangan 6:24-25)";
        $lines[] = "";

        if ($ultahList && $ultahList->count() > 0) {
            $lines[] = "Gembala Sidang & Seluruh Jemaat mengucapkan Selamat Ulang Tahun kepada Saudara/i terkasih:";
            foreach ($ultahList as $index => $u) {
                $num = $index + 1;
                $panggilan = $u->jenis_kelamin == 'L' ? 'Bpk/Sdr.' : 'Ibu/Sdri.';
                $wilayahName = $u->nama_wilayah ?? ($u->wilayah?->nama_wilayah ?? 'Namosain');
                $lines[] = "{$num}. *{$panggilan} {$u->nama_lengkap}* ({$wilayahName})";
                $lines[] = "   🗓 {$u->hari_ultah} (HUT ke-{$u->umur_ke})";
            }
            $lines[] = "";
            $lines[] = "Kiranya Tuhan Yesus melimpahkan kesehatan, umur panjang, sukacita, dan berkat yang melimpah! Amin. 🙏✨";
        } else {
            $lines[] = "Tidak ada jemaat yang tercatat berulang tahun pada pekan ini.";
        }
        $lines[] = "";

        if ($warta->pengumuman_kegiatan) {
            $lines[] = "📢 *PENGUMUMAN & AGENDA SEPEKAN:*";
            $lines[] = trim($warta->pengumuman_kegiatan);
            $lines[] = "";
        }

        if ($warta->pokok_doa) {
            $lines[] = "🙏 *POKOK DOA SYAFAAT BERSAMA:*";
            $lines[] = trim($warta->pokok_doa);
            $lines[] = "";
        }

        if ($warta->laporan_keuangan_ringkas) {
            $lines[] = "💰 *LAPORAN KAS & PERSEMBAHAN RINGKAS:*";
            $lines[] = trim($warta->laporan_keuangan_ringkas);
            $lines[] = "";
        }

        $lines[] = "━━━━━━━━━━━━━━━━━━━━━━━";
        $lines[] = "🕊 *Mari bersekutu dan melayani bersama.*";
        $lines[] = "Tuhan Yesus Memberkati Kita Semua! Haleluya.";

        return implode("\n", $lines);
    }
}
