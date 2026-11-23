<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Jemaat;
use App\Models\KehadiranIbadahMingguan;
use App\Models\PresensiPelayan;
use App\Models\Wilayah;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filter Wilayah jika dipilih
        $filterWilayahBesar = $request->input('wilayah_besar');
        $filterWilayahId = $request->input('wilayah_id');

        // Demografi Komprehensif (Kaum, Gender, KK, Baptis, Penyerahan Anak, Diakonia, Pendidikan, Pekerjaan)
        $demo = Jemaat::getComprehensiveDemographics($filterWilayahId, $filterWilayahBesar);

        $totalJemaat = $demo['totalJemaat'];
        $jemaatAktif = $demo['jemaatAktif'];
        $totalPria = $demo['gender']['pria'];
        $totalWanita = $demo['gender']['wanita'];
        $pctPria = $demo['pctPria'];
        $pctWanita = $demo['pctWanita'];
        $totalKk = $demo['totalKk'];
        $avgJiwaPerKk = $demo['avgJiwaPerKk'];
        $kkWilayah = $demo['kkWilayah'];
        $kategorialStats = $demo['kategorial'];
        $baptisStats = $demo['baptis'];
        $pctBaptis = $demo['pctBaptis'];
        $penyerahanStats = $demo['penyerahan'];
        $pctPenyerahan = $demo['pctPenyerahan'];
        $diakoniaStats = $demo['diakonia'];
        $pendidikanMap = $demo['pendidikanMap'];
        $topPekerjaan = $demo['topPekerjaan'];

        $totalWilayah = Wilayah::where('status', 'aktif')->count();
        $jemaatBaru30Hari = Jemaat::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        // Sebaran Jemaat per Wilayah
        $wilayahStats = Wilayah::withCount('jemaat')->orderBy('jemaat_count', 'desc')->get();
        $chartWilayahLabels = $wilayahStats->pluck('nama_wilayah')->toArray();
        $chartWilayahValues = $wilayahStats->pluck('jemaat_count')->toArray();

        // Rekapitulasi Tahunan Keaktifan Ibadah (PRD 2.4 A & AC-2206: Ibadah Raya tanpa anak vs COOL dengan anak)
        $selectedYear = (int) $request->input('year', 2026);
        $annualAttendance = KehadiranIbadahMingguan::getAnnualAttendanceStats($selectedYear);

        // Alat Ukur Keaktifan Pelayan Mingguan & Tahunan (PRD 2.4 B & AC-2207)
        $ministerStats = PresensiPelayan::getMinisterAnnualStats($selectedYear);

        // 5 jemaat terbaru
        $recentJemaat = Jemaat::with('wilayah')->latest()->take(5)->get();

        // 5 audit log terbaru
        $recentLogs = AuditLog::latest('created_at')->take(5)->get();

        $wilayahList = Wilayah::where('status', 'aktif')->orderBy('kode_prefix')->get();

        return view('admin.dashboard', compact(
            'demo',
            'totalJemaat',
            'jemaatAktif',
            'totalKk',
            'avgJiwaPerKk',
            'kkWilayah',
            'totalPria',
            'totalWanita',
            'pctPria',
            'pctWanita',
            'kategorialStats',
            'diakoniaStats',
            'baptisStats',
            'pctBaptis',
            'penyerahanStats',
            'pctPenyerahan',
            'pendidikanMap',
            'topPekerjaan',
            'totalWilayah',
            'jemaatBaru30Hari',
            'wilayahStats',
            'recentJemaat',
            'recentLogs',
            'wilayahList',
            'filterWilayahBesar',
            'filterWilayahId',
            'chartWilayahLabels',
            'chartWilayahValues',
            'annualAttendance',
            'ministerStats',
            'selectedYear'
        ));
    }
}
