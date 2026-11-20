<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Jemaat;
use App\Models\Wilayah;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Jemaat::query();

        // Filter Wilayah jika dipilih
        $filterWilayahBesar = $request->input('wilayah_besar');
        $filterWilayahId = $request->input('wilayah_id');

        if ($filterWilayahId) {
            $query->where('wilayah_id', $filterWilayahId);
        } elseif ($filterWilayahBesar) {
            $query->whereHas('wilayah', function ($q) use ($filterWilayahBesar) {
                $q->where('wilayah_besar', $filterWilayahBesar);
            });
        }

        $allJemaats = $query->get();
        $totalJemaat = $allJemaats->count();
        $jemaatAktif = $allJemaats->where('status_keanggotaan', 'aktif')->count();
        $totalWilayah = Wilayah::where('status', 'aktif')->count();
        $jemaatBaru30Hari = $allJemaats->where('created_at', '>=', Carbon::now()->subDays(30))->count();

        // 1. Rekap Umum (KK, Pria, Wanita)
        $keluargas = [];
        $totalPria = 0;
        $totalWanita = 0;

        // 2. Rekap Kategorial
        $kategorialStats = [
            'kaum_bapak' => 0,
            'kaum_ibu' => 0,
            'pemuda_remaja' => 0,
            'anak' => 0,
        ];

        // 3. Rekap Diakonia Khusus
        $diakoniaStats = [
            'janda' => 0,
            'duda' => 0,
            'yatim_piatu' => 0,
            'lansia' => 0,
        ];

        // 4. Status Baptis
        $baptisStats = [
            'sudah' => 0,
            'belum' => 0,
        ];

        // 5. Pendidikan & Pekerjaan
        $pendidikanMap = [];
        $pekerjaanMap = [];

        foreach ($allJemaats as $j) {
            if (!empty($j->no_kk) && strlen($j->no_kk) >= 4) {
                $keluargas[substr($j->no_kk, 0, 4)] = true;
            }

            if ($j->jenis_kelamin === 'L') {
                $totalPria++;
            } else {
                $totalWanita++;
            }

            // Kategorial
            $kat = $j->kategorial_pelayanan;
            if ($kat === 'Kaum Bapak') $kategorialStats['kaum_bapak']++;
            elseif ($kat === 'Kaum Ibu') $kategorialStats['kaum_ibu']++;
            elseif ($kat === 'Pemuda & Remaja') $kategorialStats['pemuda_remaja']++;
            elseif ($kat === 'Anak-Anak') $kategorialStats['anak']++;

            // Diakonia
            $diakonia = $j->diakonia_label;
            if ($diakonia === 'Janda') $diakoniaStats['janda']++;
            elseif ($diakonia === 'Duda') $diakoniaStats['duda']++;
            elseif (in_array($diakonia, ['Yatim', 'Piatu', 'Yatim Piatu'])) $diakoniaStats['yatim_piatu']++;
            elseif ($diakonia === 'Lansia') $diakoniaStats['lansia']++;

            // Baptis
            $sb = strtoupper($j->status_baptis ?? '');
            if (str_contains($sb, 'BAPTIS') || str_contains($sb, 'SUDAH')) {
                $baptisStats['sudah']++;
            } else {
                $baptisStats['belum']++;
            }

            // Pendidikan
            $pdk = !empty($j->pendidikan) ? strtoupper(trim($j->pendidikan)) : 'BELUM TERDATA';
            if (str_contains($pdk, 'SLTA') || str_contains($pdk, 'SMA') || str_contains($pdk, 'SMK')) $pdkGroup = 'SMA/SMK';
            elseif (str_contains($pdk, 'SMP') || str_contains($pdk, 'SLTP')) $pdkGroup = 'SMP';
            elseif (str_contains($pdk, 'SD')) $pdkGroup = 'SD';
            elseif (str_contains($pdk, 'SARJANA') || str_contains($pdk, 'S1')) $pdkGroup = 'S1 (Sarjana)';
            elseif (str_contains($pdk, 'DIPLOMA') || str_contains($pdk, 'D3')) $pdkGroup = 'Diploma (D3)';
            elseif (str_contains($pdk, 'TK') || str_contains($pdk, 'PAUD')) $pdkGroup = 'PAUD/TK';
            elseif ($pdk === 'BELUM TERDATA' || $pdk === '-') $pdkGroup = 'Belum Terdata';
            else $pdkGroup = 'Lainnya';
            $pendidikanMap[$pdkGroup] = ($pendidikanMap[$pdkGroup] ?? 0) + 1;

            // Pekerjaan
            $pkj = !empty($j->pekerjaan) ? ucwords(strtolower(trim($j->pekerjaan))) : 'Belum Bekerja / Lainnya';
            if ($pkj === '-' || $pkj === '') $pkj = 'Belum Bekerja / Lainnya';
            $pekerjaanMap[$pkj] = ($pekerjaanMap[$pkj] ?? 0) + 1;
        }

        $totalKk = count($keluargas);
        arsort($pendidikanMap);
        arsort($pekerjaanMap);
        $topPekerjaan = array_slice($pekerjaanMap, 0, 6, true);

        // Sebaran Jemaat per Wilayah
        $wilayahStats = Wilayah::withCount('jemaat')->orderBy('jemaat_count', 'desc')->get();
        $chartWilayahLabels = $wilayahStats->pluck('nama_wilayah')->toArray();
        $chartWilayahValues = $wilayahStats->pluck('jemaat_count')->toArray();

        // 5 jemaat terbaru
        $recentJemaat = Jemaat::with('wilayah')->latest()->take(5)->get();

        // 5 audit log terbaru
        $recentLogs = AuditLog::latest('created_at')->take(5)->get();

        $wilayahList = Wilayah::where('status', 'aktif')->orderBy('kode_prefix')->get();

        return view('admin.dashboard', compact(
            'totalJemaat',
            'jemaatAktif',
            'totalKk',
            'totalPria',
            'totalWanita',
            'kategorialStats',
            'diakoniaStats',
            'baptisStats',
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
            'chartWilayahValues'
        ));
    }
}
