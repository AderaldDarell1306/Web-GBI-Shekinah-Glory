<?php

namespace App\Http\Controllers;

use App\Models\JadwalIbadah;
use App\Models\Jemaat;
use App\Models\TransaksiKeuangan;
use App\Models\WebsiteContent;
use App\Models\Wilayah;
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
     * Halaman Warta Jemaat Digital (Publik)
     */
    public function warta(Request $request)
    {
        $contents = WebsiteContent::all()->pluck('value', 'key');
        $id = $request->query('id');

        if ($id) {
            $warta = \App\Models\WartaJemaat::where('status', 'terbit')->find($id);
        } else {
            $warta = \App\Models\WartaJemaat::where('status', 'terbit')->orderBy('tanggal_warta', 'desc')->first();
        }

        $arsipWarta = \App\Models\WartaJemaat::where('status', 'terbit')
            ->orderBy('tanggal_warta', 'desc')
            ->take(8)
            ->get();

        $ultahList = $warta ? $warta->jemaat_ulang_tahun_minggu_ini : collect();

        return view('public.warta', compact('contents', 'warta', 'arsipWarta', 'ultahList'));
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
        ]);

        $prefix = strtoupper(substr($validated['jenis_layanan'], 0, 3));
        $kode = sprintf('%s-%s-%04d', $prefix, date('Ymd'), rand(100, 9999));
        $validated['kode_pendaftaran'] = $kode;

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
}
