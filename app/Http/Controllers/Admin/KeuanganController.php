<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransaksiKeuangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user && !$user->canViewKeuangan()) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk melihat data keuangan gereja.');
        }

        $latestRecord = TransaksiKeuangan::latest('tanggal_transaksi')->first();
        $defaultBulan = $latestRecord ? $latestRecord->tanggal_transaksi->format('m') : date('m');
        $defaultTahun = $latestRecord ? $latestRecord->tanggal_transaksi->format('Y') : date('Y');

        $bulan = $request->has('bulan') ? $request->input('bulan') : $defaultBulan;
        $tahun = $request->input('tahun', $defaultTahun);
        $jenis = $request->input('jenis');
        $kategori = $request->input('kategori');
        $q = $request->input('q');

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

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('kode_transaksi', 'like', "%{$q}%")
                    ->orWhere('sumber_penerima', 'like', "%{$q}%")
                    ->orWhere('keterangan', 'like', "%{$q}%")
                    ->orWhere('no_bukti_nota', 'like', "%{$q}%");
            });
        }

        $transaksi = (clone $query)->orderBy('tanggal_transaksi', 'desc')
                                   ->orderBy('id', 'desc')
                                   ->paginate(15)
                                   ->withQueryString();

        // Rekap Periode Terpilih
        $pemasukanPeriode = (clone $query)->where('jenis', 'pemasukan')->sum('nominal');
        $pengeluaranPeriode = (clone $query)->where('jenis', 'pengeluaran')->sum('nominal');
        $saldoPeriode = $pemasukanPeriode - $pengeluaranPeriode;

        // Rekap Keseluruhan (All Time)
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

        // PRD v2.1 AC-R02: Grafik Keuangan Multi-Periode (Bulanan, Semester, Tahunan)
        $periodeGrafik = $request->input('periode_grafik', 'bulanan');
        $chartPeriodeLabels = [];
        $chartPemasukanValues = [];
        $chartPengeluaranValues = [];

        if ($periodeGrafik === 'semester') {
            $chartPeriodeLabels = ['Semester 1 (Jan - Jun ' . $tahun . ')', 'Semester 2 (Jul - Des ' . $tahun . ')'];
            
            // Semester 1
            $inSem1 = TransaksiKeuangan::whereYear('tanggal_transaksi', $tahun)->whereMonth('tanggal_transaksi', '<=', 6)->where('jenis', 'pemasukan')->sum('nominal');
            $outSem1 = TransaksiKeuangan::whereYear('tanggal_transaksi', $tahun)->whereMonth('tanggal_transaksi', '<=', 6)->where('jenis', 'pengeluaran')->sum('nominal');
            
            // Semester 2
            $inSem2 = TransaksiKeuangan::whereYear('tanggal_transaksi', $tahun)->whereMonth('tanggal_transaksi', '>', 6)->where('jenis', 'pemasukan')->sum('nominal');
            $outSem2 = TransaksiKeuangan::whereYear('tanggal_transaksi', $tahun)->whereMonth('tanggal_transaksi', '>', 6)->where('jenis', 'pengeluaran')->sum('nominal');

            $chartPemasukanValues = [(float)$inSem1, (float)$inSem2];
            $chartPengeluaranValues = [(float)$outSem1, (float)$outSem2];
        } elseif ($periodeGrafik === 'tahunan') {
            $startYear = (int)$tahun - 4;
            for ($y = $startYear; $y <= (int)$tahun; $y++) {
                $chartPeriodeLabels[] = 'Tahun ' . $y;
                $inY = TransaksiKeuangan::whereYear('tanggal_transaksi', $y)->where('jenis', 'pemasukan')->sum('nominal');
                $outY = TransaksiKeuangan::whereYear('tanggal_transaksi', $y)->where('jenis', 'pengeluaran')->sum('nominal');
                $chartPemasukanValues[] = (float)$inY;
                $chartPengeluaranValues[] = (float)$outY;
            }
        } else {
            // Bulanan (default)
            $bulanNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            for ($m = 1; $m <= 12; $m++) {
                $chartPeriodeLabels[] = $bulanNames[$m - 1] . ' ' . $tahun;
                $inM = TransaksiKeuangan::whereYear('tanggal_transaksi', $tahun)->whereMonth('tanggal_transaksi', $m)->where('jenis', 'pemasukan')->sum('nominal');
                $outM = TransaksiKeuangan::whereYear('tanggal_transaksi', $tahun)->whereMonth('tanggal_transaksi', $m)->where('jenis', 'pengeluaran')->sum('nominal');
                $chartPemasukanValues[] = (float)$inM;
                $chartPengeluaranValues[] = (float)$outM;
            }
        }

        return view('admin.keuangan.index', compact(
            'transaksi',
            'bulan',
            'tahun',
            'jenis',
            'kategori',
            'q',
            'pemasukanPeriode',
            'pengeluaranPeriode',
            'saldoPeriode',
            'totalSaldoKas',
            'breakdownPemasukan',
            'breakdownPengeluaran',
            'periodeGrafik',
            'chartPeriodeLabels',
            'chartPemasukanValues',
            'chartPengeluaranValues'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user && !$user->canManageKeuangan()) {
            abort(403, 'Akses ditolak: Hanya Bendahara yang berwenang mencatat transaksi kas keuangan.');
        }

        $validated = $request->validate([
            'tanggal_transaksi' => 'required|date',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'kategori' => 'required|string|not_in:persepuluhan,perpuluhan|max:50',
            'nominal' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|max:30',
            'sumber_penerima' => 'nullable|string|max:150',
            'keterangan' => 'nullable|string',
            'no_bukti_nota' => 'nullable|string|max:100',
            'lampiran_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        // Auto generate kode transaksi
        $prefix = $validated['jenis'] === 'pemasukan' ? 'IN' : 'OUT';
        $tglStr = Carbon::parse($validated['tanggal_transaksi'])->format('Ym');
        $countThisMonth = TransaksiKeuangan::whereYear('tanggal_transaksi', Carbon::parse($validated['tanggal_transaksi'])->year)
            ->whereMonth('tanggal_transaksi', Carbon::parse($validated['tanggal_transaksi'])->month)
            ->count() + 1;
        $kodeTransaksi = sprintf('%s-%s-%04d', $prefix, $tglStr, $countThisMonth);

        $pathLampiran = null;
        if ($request->hasFile('lampiran_file')) {
            $pathLampiran = $request->file('lampiran_file')->store('keuangan_lampiran', 'public');
        }

        TransaksiKeuangan::create([
            'kode_transaksi' => $kodeTransaksi,
            'tanggal_transaksi' => $validated['tanggal_transaksi'],
            'jenis' => $validated['jenis'],
            'kategori' => $validated['kategori'],
            'nominal' => $validated['nominal'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'sumber_penerima' => $validated['sumber_penerima'],
            'keterangan' => $validated['keterangan'],
            'no_bukti_nota' => $validated['no_bukti_nota'],
            'lampiran_file' => $pathLampiran,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.keuangan.index', [
            'bulan' => Carbon::parse($validated['tanggal_transaksi'])->format('m'),
            'tahun' => Carbon::parse($validated['tanggal_transaksi'])->format('Y'),
        ])->with('success', 'Transaksi keuangan berhasil dicatat!');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user && !$user->canManageKeuangan()) {
            abort(403, 'Akses ditolak: Hanya Bendahara yang berwenang mengubah transaksi kas keuangan.');
        }

        $transaksi = TransaksiKeuangan::findOrFail($id);

        $validated = $request->validate([
            'tanggal_transaksi' => 'required|date',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'kategori' => 'required|string|not_in:persepuluhan,perpuluhan|max:50',
            'nominal' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|max:30',
            'sumber_penerima' => 'nullable|string|max:150',
            'keterangan' => 'nullable|string',
            'no_bukti_nota' => 'nullable|string|max:100',
            'lampiran_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        if ($request->hasFile('lampiran_file')) {
            if ($transaksi->lampiran_file && Storage::disk('public')->exists($transaksi->lampiran_file)) {
                Storage::disk('public')->delete($transaksi->lampiran_file);
            }
            $transaksi->lampiran_file = $request->file('lampiran_file')->store('keuangan_lampiran', 'public');
        }

        $transaksi->tanggal_transaksi = $validated['tanggal_transaksi'];
        $transaksi->jenis = $validated['jenis'];
        $transaksi->kategori = $validated['kategori'];
        $transaksi->nominal = $validated['nominal'];
        $transaksi->metode_pembayaran = $validated['metode_pembayaran'];
        $transaksi->sumber_penerima = $validated['sumber_penerima'];
        $transaksi->keterangan = $validated['keterangan'];
        $transaksi->no_bukti_nota = $validated['no_bukti_nota'];
        $transaksi->updated_by = Auth::id();
        $transaksi->save();

        return redirect()->back()->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if ($user && !$user->canManageKeuangan()) {
            abort(403, 'Akses ditolak: Hanya Bendahara yang berwenang menghapus transaksi kas keuangan.');
        }

        $transaksi = TransaksiKeuangan::findOrFail($id);
        if ($transaksi->lampiran_file && Storage::disk('public')->exists($transaksi->lampiran_file)) {
            Storage::disk('public')->delete($transaksi->lampiran_file);
        }
        $transaksi->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus!');
    }

    public function laporan(Request $request)
    {
        $user = Auth::user();
        if ($user && !$user->canViewKeuangan()) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk mencetak laporan keuangan.');
        }

        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $transaksiList = TransaksiKeuangan::whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->orderBy('tanggal_transaksi', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $totalPemasukan = $transaksiList->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $transaksiList->where('jenis', 'pengeluaran')->sum('nominal');
        $surplusDefisit = $totalPemasukan - $totalPengeluaran;

        // Saldo awal sebelum bulan ini
        $startOfMonth = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $pemasukanSebelumnya = TransaksiKeuangan::where('tanggal_transaksi', '<', $startOfMonth)
            ->where('jenis', 'pemasukan')
            ->sum('nominal');
        $pengeluaranSebelumnya = TransaksiKeuangan::where('tanggal_transaksi', '<', $startOfMonth)
            ->where('jenis', 'pengeluaran')
            ->sum('nominal');
        $saldoAwal = $pemasukanSebelumnya - $pengeluaranSebelumnya;
        $saldoAkhir = $saldoAwal + $surplusDefisit;

        // Group by pos anggaran
        $pemasukanPerPos = $transaksiList->where('jenis', 'pemasukan')->groupBy('kategori');
        $pengeluaranPerPos = $transaksiList->where('jenis', 'pengeluaran')->groupBy('kategori');

        return view('admin.keuangan.laporan', compact(
            'transaksiList',
            'bulan',
            'tahun',
            'totalPemasukan',
            'totalPengeluaran',
            'surplusDefisit',
            'saldoAwal',
            'saldoAkhir',
            'pemasukanPerPos',
            'pengeluaranPerPos'
        ));
    }
}

