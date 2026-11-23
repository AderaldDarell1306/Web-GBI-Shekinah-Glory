<?php

namespace App\Http\Controllers;

use App\Models\JadwalIbadah;
use App\Models\Jemaat;
use App\Models\TransaksiKeuangan;
use App\Models\WebsiteContent;
use App\Models\Wilayah;
use App\Models\ArsipSurat;
use App\Models\Inventaris;
use App\Models\PeminjamanInventaris;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Halaman Utama Beranda
     */
    public function index()
    {
        $jadwalList = JadwalIbadah::aktif()->get();
        $ibadahTerdekat = $jadwalList->first();
        $wilayahList = Wilayah::aktif()->withCount('jemaat')->get();

        $contents = WebsiteContent::all()->pluck('value', 'key');

        // Warta Jemaat Digital & Ulang Tahun Pekan Ini
        $latestWarta = \App\Models\WartaJemaat::where('status', 'terbit')->orderBy('tanggal_warta', 'desc')->first();
        $ultahMingguIni = $latestWarta ? $latestWarta->jemaat_ulang_tahun_minggu_ini : collect();
        $wartaCards = \App\Models\WartaJemaat::where('status', 'terbit')->orderBy('tanggal_warta', 'desc')->take(3)->get();

        // Pengumuman & Berita Gereja
        $pengumumanList = \App\Models\Pengumuman::where('status', 'aktif')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('tanggal', 'desc')
            ->take(3)
            ->get();

        // Kalender Acara & Agenda Terdekat
        $upcomingEvents = \App\Models\KalenderAcara::where('tampilkan_di_web', true)
            ->where('tanggal_mulai', '>=', \Carbon\Carbon::today())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(4)
            ->get();

        // Jadwal Pelayan Ibadah (Service Rota) Terdekat
        $upcomingRotas = \App\Models\JadwalPelayanan::with(['sesiIbadah', 'petugas'])
            ->where('status_publikasi', 'terbit')
            ->whereDate('tanggal_pelayanan', '>=', \Carbon\Carbon::today())
            ->orderBy('tanggal_pelayanan', 'asc')
            ->take(2)
            ->get();

        $totalJemaat = \App\Models\Jemaat::count();
        $totalWilayah = $wilayahList->count();
        $kategorialStats = \App\Models\Jemaat::getKategorialStats();
        $gembala = \App\Models\User::whereIn('role', ['gembala', 'super_admin'])->orderByRaw("CASE WHEN role = 'gembala' THEN 1 ELSE 2 END")->first();
        $dokumentasiList = \App\Models\DokumentasiFoto::where('tampilkan_di_galeri', true)
            ->orderBy('tanggal_kegiatan', 'desc')
            ->take(6)
            ->get();

        return view('public.beranda', compact(
            'jadwalList',
            'ibadahTerdekat',
            'wilayahList',
            'contents',
            'latestWarta',
            'wartaCards',
            'ultahMingguIni',
            'upcomingEvents',
            'upcomingRotas',
            'totalJemaat',
            'totalWilayah',
            'kategorialStats',
            'pengumumanList',
            'gembala',
            'dokumentasiList'
        ));
    }

    /**
     * Halaman Tentang & Visi Misi
     */
    public function tentang()
    {
        $contents = WebsiteContent::all()->pluck('value', 'key');
        return view('public.tentang', compact('contents'));
    }

    /**
     * Halaman Data Jemaat untuk Jemaat (Read-Only)
     * Sesuai PRD v2.1 Pasal 2.6: Jemaat dapat melihat direktori data jemaat secara transparan & aman
     */
    public function jemaat(Request $request)
    {
        $contents = WebsiteContent::all()->pluck('value', 'key');
        $wilayahList = Wilayah::where('status', 'aktif')->orderBy('kode_prefix')->get();

        $q = $request->input('q');
        $wilayahId = $request->input('wilayah_id');
        $kategori = $request->input('kategori');

        $query = Jemaat::with('wilayah')->where('status_keanggotaan', 'aktif');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_lengkap', 'like', "%{$q}%")
                    ->orWhere('no_kk', 'like', "%{$q}%");
            });
        }

        if ($wilayahId) {
            $query->where('wilayah_id', $wilayahId);
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $jemaatList = $query->orderBy('nama_lengkap', 'asc')->paginate(20)->withQueryString();

        $totalJemaat = Jemaat::where('status_keanggotaan', 'aktif')->count();
        $totalKeluarga = Jemaat::where('status_keanggotaan', 'aktif')->distinct('no_kk')->count('no_kk');
        $kategorialStats = Jemaat::getKategorialStats();

        return view('public.jemaat', compact(
            'contents',
            'jemaatList',
            'wilayahList',
            'totalJemaat',
            'totalKeluarga',
            'kategorialStats',
            'q',
            'wilayahId',
            'kategori'
        ));
    }

    /**
     * Halaman Transparansi Keuangan Gereja untuk Jemaat (Read-Only & Bebas Perpuluhan)
     * Sesuai PRD v2.1 Pasal 2.3 & 2.6: Transparansi arus kas jemaat tanpa pos perpuluhan
     */
    public function keuangan(Request $request)
    {
        $contents = WebsiteContent::all()->pluck('value', 'key');

        $latestRecord = TransaksiKeuangan::latest('tanggal_transaksi')->first();
        $defaultBulan = $latestRecord ? $latestRecord->tanggal_transaksi->format('m') : '10';
        $defaultTahun = $latestRecord ? $latestRecord->tanggal_transaksi->format('Y') : '2026';

        $bulan = $request->has('bulan') ? $request->input('bulan') : $defaultBulan;
        $tahun = $request->input('tahun', $defaultTahun);
        $jenis = $request->input('jenis');

        $query = TransaksiKeuangan::query();

        if (!empty($bulan) && !empty($tahun)) {
            $query->whereMonth('tanggal_transaksi', $bulan)
                  ->whereYear('tanggal_transaksi', $tahun);
        } elseif (!empty($tahun)) {
            $query->whereYear('tanggal_transaksi', $tahun);
        }

        if ($jenis) {
            $query->where('jenis', $jenis);
        }

        $transaksi = (clone $query)->orderBy('tanggal_transaksi', 'desc')
                                   ->orderBy('id', 'desc')
                                   ->paginate(20)
                                   ->withQueryString();

        // Rekap Periode Terpilih
        $pemasukanPeriode = (clone $query)->where('jenis', 'pemasukan')->sum('nominal');
        $pengeluaranPeriode = (clone $query)->where('jenis', 'pengeluaran')->sum('nominal');
        $saldoPeriode = $pemasukanPeriode - $pengeluaranPeriode;

        // Rekap Keseluruhan
        $totalPemasukanAll = TransaksiKeuangan::where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaranAll = TransaksiKeuangan::where('jenis', 'pengeluaran')->sum('nominal');
        $totalSaldoKas = $totalPemasukanAll - $totalPengeluaranAll;

        // Breakdown Pemasukan per Kategori periode ini
        $queryBreakdownIn = TransaksiKeuangan::whereYear('tanggal_transaksi', $tahun)->where('jenis', 'pemasukan');
        if (!empty($bulan)) {
            $queryBreakdownIn->whereMonth('tanggal_transaksi', $bulan);
        }
        $breakdownPemasukan = $queryBreakdownIn->selectRaw('kategori, SUM(nominal) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        // Breakdown Pengeluaran per Kategori periode ini
        $queryBreakdownOut = TransaksiKeuangan::whereYear('tanggal_transaksi', $tahun)->where('jenis', 'pengeluaran');
        if (!empty($bulan)) {
            $queryBreakdownOut->whereMonth('tanggal_transaksi', $bulan);
        }
        $breakdownPengeluaran = $queryBreakdownOut->selectRaw('kategori, SUM(nominal) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        return view('public.keuangan', compact(
            'contents',
            'transaksi',
            'pemasukanPeriode',
            'pengeluaranPeriode',
            'saldoPeriode',
            'totalPemasukanAll',
            'totalPengeluaranAll',
            'totalSaldoKas',
            'breakdownPemasukan',
            'breakdownPengeluaran',
            'bulan',
            'tahun',
            'jenis'
        ));
    }

    /**
     * Halaman Jadwal Ibadah & Pelayanan
     */
    public function jadwal()
    {
        $jadwalList = JadwalIbadah::aktif()->get();
        $contents = WebsiteContent::all()->pluck('value', 'key');
        $upcomingRotas = \App\Models\JadwalPelayanan::with(['sesiIbadah', 'petugas'])
            ->where('status_publikasi', 'terbit')
            ->whereDate('tanggal_pelayanan', '>=', \Carbon\Carbon::today())
            ->orderBy('tanggal_pelayanan', 'asc')
            ->take(3)
            ->get();

        return view('public.jadwal', compact('jadwalList', 'contents', 'upcomingRotas'));
    }

    /**
     * Halaman Lokasi
     */
    public function lokasi()
    {
        $contents = WebsiteContent::all()->pluck('value', 'key');
        return view('public.lokasi', compact('contents'));
    }

    /**
     * Halaman Kontak & Pokok Doa
     */
    public function kontak()
    {
        $contents = WebsiteContent::all()->pluck('value', 'key');
        return view('public.kontak', compact('contents'));
    }

    /**
     * Handle Pesan ke Sekretariat
     */
    public function kirimPesan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'telepon' => 'required|string|max:30',
            'pesan' => 'nullable|string|max:1000',
            'pokok_doa' => 'nullable|string|max:1000',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Terima kasih, pesan Anda telah diterima tim sekretariat. Kami akan segera menghubungi Anda!'
        ]);
    }

    /**
     * Katalog / Galeri Arsip Warta Jemaat & Buletin Digital
     */
    public function warta(Request $request)
    {
        // Jika ada parameter ?id=X, redirect ke URL edisi khusus /warta/{id}
        if ($request->filled('id')) {
            return redirect()->route('public.warta.detail', $request->query('id'));
        }

        $contents = WebsiteContent::all()->pluck('value', 'key');
        
        $query = \App\Models\WartaJemaat::where('status', 'terbit');

        if ($request->filled('q')) {
            $keyword = $request->query('q');
            $query->where(function($q) use ($keyword) {
                $q->where('tema_mingguan', 'like', "%{$keyword}%")
                  ->orWhere('edisi', 'like', "%{$keyword}%")
                  ->orWhere('ayat_tema', 'like', "%{$keyword}%")
                  ->orWhere('ringkasan_khotbah', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_warta', $request->query('tahun'));
        }

        $latestWarta = \App\Models\WartaJemaat::where('status', 'terbit')->orderBy('tanggal_warta', 'desc')->first();
        $arsipWarta = $query->orderBy('tanggal_warta', 'desc')->paginate(9)->withQueryString();

        $tahunList = \App\Models\WartaJemaat::where('status', 'terbit')
            ->selectRaw('YEAR(tanggal_warta) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('public.warta-index', compact('contents', 'latestWarta', 'arsipWarta', 'tahunList'));
    }

    /**
     * Halaman Baca Khusus per Edisi Warta Jemaat (/warta/{id})
     */
    public function wartaDetail(Request $request, $id)
    {
        $contents = WebsiteContent::all()->pluck('value', 'key');
        $warta = \App\Models\WartaJemaat::where('status', 'terbit')->findOrFail($id);

        $prevWarta = \App\Models\WartaJemaat::where('status', 'terbit')
            ->where('tanggal_warta', '<', $warta->tanggal_warta)
            ->orderBy('tanggal_warta', 'desc')
            ->first();

        $nextWarta = \App\Models\WartaJemaat::where('status', 'terbit')
            ->where('tanggal_warta', '>', $warta->tanggal_warta)
            ->orderBy('tanggal_warta', 'asc')
            ->first();

        $arsipLain = \App\Models\WartaJemaat::where('status', 'terbit')
            ->where('id', '!=', $warta->id)
            ->orderBy('tanggal_warta', 'desc')
            ->take(4)
            ->get();

        $ultahList = $warta->jemaat_ulang_tahun_minggu_ini;

        return view('public.warta-detail', compact('contents', 'warta', 'prevWarta', 'nextWarta', 'arsipLain', 'ultahList'));
    }

    /**
     * Portal Publik Pendaftaran Sakramen & Layanan Jemaat
     */
    public function pendaftaran(Request $request)
    {
        $contents = WebsiteContent::all()->pluck('value', 'key');
        $layanan = $request->query('layanan', 'baptis');
        $wilayahList = \App\Models\Wilayah::where('status', 'aktif')->orderBy('kode_prefix')->get();

        return view('public.pendaftaran', compact('contents', 'layanan', 'wilayahList'));
    }

    /**
     * Simpan Formulir Pendaftaran Layanan
     */
    public function submitPendaftaran(Request $request)
    {
        $validated = $request->validate([
            'jenis_layanan' => 'required|in:baptis,penyerahan_anak,nikah,jemaat_baru',
            'nama_pemohon' => 'required|string|max:150',
            'no_wa' => 'required|string|max:30',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'data_tambahan' => 'nullable|array',
            // Slot Berkas Dokumen Terpisah sesuai formulir fisik asli
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
            'file_pas_foto' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
            'file_akta_anak' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
            'file_ktp_ortu' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
            'file_ktp_pria' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
            'file_ktp_wanita' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
            'file_kk_pria' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
            'file_kk_wanita' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
            'file_baptis_pria' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
            'file_baptis_wanita' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
            'file_akta_pria' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
            'file_akta_wanita' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
            'file_foto_gandeng' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
            'file_akta_baptis' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
            'file_akta_lahir' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
            'berkas_dokumen.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240',
        ]);

        $prefix = match ($validated['jenis_layanan']) {
            'baptis' => 'BAP',
            'penyerahan_anak' => 'ANA',
            'nikah' => 'NKH',
            'jemaat_baru' => 'JMB',
            default => 'REG',
        };
        $kode = sprintf('%s-%s-%04d', $prefix, date('Ymd'), rand(100, 9999));
        $validated['kode_pendaftaran'] = $kode;

        // Slot nama/label berkas sesuai berkas yang diminta pada dokumen fisik asli
        $slotLabels = [
            'file_ktp' => 'Fotocopy KTP Pemohon',
            'file_kk' => 'Fotocopy Kartu Keluarga (KK)',
            'file_pas_foto' => 'Pas Foto',
            'file_akta_anak' => 'Fotocopy Akte Lahir Anak / Surat Kenal Lahir',
            'file_ktp_ortu' => 'Fotocopy KTP Orang Tua (Ayah / Ibu)',
            'file_ktp_pria' => 'Fotocopy KTP Calon Pengantin Pria',
            'file_ktp_wanita' => 'Fotocopy KTP Calon Pengantin Wanita',
            'file_kk_pria' => 'Fotocopy Kartu Keluarga (KK) Pria',
            'file_kk_wanita' => 'Fotocopy Kartu Keluarga (KK) Wanita',
            'file_baptis_pria' => 'Surat Baptis / Sidi Calon Pengantin Pria',
            'file_baptis_wanita' => 'Surat Baptis / Sidi Calon Pengantin Wanita',
            'file_akta_pria' => 'Fotocopy Akte Kelahiran Calon Pengantin Pria',
            'file_akta_wanita' => 'Fotocopy Akte Kelahiran Calon Pengantin Wanita',
            'file_foto_gandeng' => 'Pas Foto Gandeng Calon Pengantin (4x6)',
            'file_akta_baptis' => 'Fotocopy Akta Baptis',
            'file_akta_lahir' => 'Fotocopy Akta Kelahiran',
        ];

        $uploadedFiles = [];

        // 1. Simpan berkas dari slot terpisah
        foreach ($slotLabels as $slotKey => $slotTitle) {
            if ($request->hasFile($slotKey)) {
                $file = $request->file($slotKey);
                if ($file && $file->isValid()) {
                    $originalName = $file->getClientOriginalName();
                    $path = $file->store('pendaftaran_berkas', 'public');
                    $uploadedFiles[] = [
                        'kategori' => $slotTitle,
                        'slot_key' => $slotKey,
                        'nama_file' => $originalName,
                        'path' => $path,
                        'ukuran' => round($file->getSize() / 1024, 1) . ' KB',
                        'ekstensi' => strtolower($file->getClientOriginalExtension()),
                    ];
                }
            }
        }

        // 2. Simpan jika ada berkas dokumen multi/tambahan
        if ($request->hasFile('berkas_dokumen')) {
            foreach ($request->file('berkas_dokumen') as $file) {
                if ($file && $file->isValid()) {
                    $originalName = $file->getClientOriginalName();
                    $path = $file->store('pendaftaran_berkas', 'public');
                    $uploadedFiles[] = [
                        'kategori' => 'Dokumen Tambahan',
                        'slot_key' => 'tambahan',
                        'nama_file' => $originalName,
                        'path' => $path,
                        'ukuran' => round($file->getSize() / 1024, 1) . ' KB',
                        'ekstensi' => strtolower($file->getClientOriginalExtension()),
                    ];
                }
            }
        }

        $validated['berkas_dokumen'] = $uploadedFiles;

        $pendaftaran = \App\Models\PendaftaranLayanan::create($validated);

        return redirect()->route('public.pendaftaran.sukses', $pendaftaran->id)
            ->with('success', "Formulir pendaftaran berhasil dikirim dengan Kode Registrasi: {$kode}");
    }

    /**
     * Halaman Konfirmasi & Cetak Form Pendaftaran
     */
    public function pendaftaranSukses($id)
    {
        $contents = WebsiteContent::all()->pluck('value', 'key');
        $pendaftaran = \App\Models\PendaftaranLayanan::findOrFail($id);

        return view('public.pendaftaran-sukses', compact('contents', 'pendaftaran'));
    }

    /**
     * Galeri Foto Dokumentasi Kegiatan Gereja (PRD Modul 10 AC-R06)
     */
    public function dokumentasi(Request $request)
    {
        $contents = WebsiteContent::all()->pluck('value', 'key');
        $kategori = $request->input('kategori');

        $query = \App\Models\DokumentasiFoto::where('tampilkan_di_galeri', true);

        if ($kategori) {
            $query->where('kategori_kegiatan', $kategori);
        }

        $fotoList = $query->orderBy('tanggal_kegiatan', 'desc')->paginate(12)->withQueryString();
        $kategoriList = \App\Models\DokumentasiFoto::where('tampilkan_di_galeri', true)
            ->select('kategori_kegiatan')
            ->distinct()
            ->pluck('kategori_kegiatan');

        return view('public.dokumentasi', compact('contents', 'fotoList', 'kategoriList', 'kategori'));
    }

    /**
     * Halaman Cek & Unduh Surat Resmi via Kode Unik (PRD v2.2 2.2 B)
     */
    public function cekSurat(Request $request)
    {
        $contents = WebsiteContent::all()->pluck('value', 'key');
        $kode = trim($request->input('kode', ''));
        $surat = null;
        $error = null;

        if (!empty($kode)) {
            $surat = ArsipSurat::where('kode_akses', $kode)->first();
            if (!$surat) {
                $error = "Dokumen dengan Kode Akses '{$kode}' tidak ditemukan atau belum dipublikasikan oleh Sekretariat.";
            }
        }

        return view('public.layanan.cek-surat', compact('contents', 'kode', 'surat', 'error'));
    }

    /**
     * Formulir Pengajuan Pinjam Pakai Inventaris Gereja (PRD v2.2 2.2 C)
     */
    public function pinjamInventaris()
    {
        $contents = WebsiteContent::all()->pluck('value', 'key');
        $barangList = Inventaris::where('status_ketersediaan', 'Tersedia')
            ->where('kondisi', '!=', 'Rusak Berat')
            ->orderBy('nama_barang', 'asc')
            ->get();

        return view('public.layanan.pinjam-inventaris', compact('contents', 'barangList'));
    }

    /**
     * Submit Pengajuan Peminjaman Inventaris
     */
    public function submitPinjamInventaris(Request $request)
    {
        $validated = $request->validate([
            'nama_peminjam' => 'required|string|max:150',
            'no_kontak' => 'required|string|max:30',
            'instansi_kegiatan' => 'required|string|max:150',
            'inventaris_id' => 'nullable|exists:inventaris,id',
            'nama_barang_custom' => 'nullable|string|max:150',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_rencana_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'catatan' => 'nullable|string',
        ]);

        if (empty($validated['inventaris_id']) && empty($validated['nama_barang_custom'])) {
            return back()->withErrors(['inventaris_id' => 'Silakan pilih barang inventaris atau ketikkan nama barang yang ingin dipinjam.'])->withInput();
        }

        $kode = sprintf('PINJAM-%s-%04d', date('Ymd'), rand(1000, 9999));

        $peminjaman = PeminjamanInventaris::create([
            'kode_peminjaman' => $kode,
            'inventaris_id' => $validated['inventaris_id'] ?? null,
            'nama_barang_custom' => $validated['nama_barang_custom'] ?? null,
            'nama_peminjam' => $validated['nama_peminjam'],
            'no_kontak' => $validated['no_kontak'],
            'instansi_kegiatan' => $validated['instansi_kegiatan'],
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_rencana_kembali' => $validated['tanggal_rencana_kembali'],
            'jumlah_pinjam' => $validated['jumlah_pinjam'],
            'status' => 'Menunggu Konfirmasi',
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('public.layanan.pinjam-inventaris.sukses', $peminjaman->id)
            ->with('success', "Pengajuan peminjaman berhasil dikirim dengan Kode: {$kode}");
    }

    /**
     * Halaman Sukses Pengajuan Peminjaman Inventaris
     */
    public function pinjamInventarisSukses($id)
    {
        $contents = WebsiteContent::all()->pluck('value', 'key');
        $peminjaman = PeminjamanInventaris::with('inventaris')->findOrFail($id);

        return view('public.layanan.pinjam-inventaris-sukses', compact('contents', 'peminjaman'));
    }
}
