<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jemaat;
use App\Models\Wilayah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlangTahunController extends Controller
{
    /**
     * Halaman Utama Pengelolaan Ucapan & Kartu Ulang Tahun Jemaat
     */
    public function index(Request $request)
    {
        $now = Carbon::now();
        $filter = $request->input('filter', 'pekan_ini');
        $wilayahId = $request->input('wilayah_id');
        $bulanFilter = $request->input('bulan', $now->month);
        $search = $request->input('search');

        $wilayahList = Wilayah::where('status', 'aktif')->orderBy('nama_wilayah')->get();

        // Query Dasar
        $query = Jemaat::with('wilayah')
            ->whereNotNull('tanggal_lahir')
            ->where('status_keanggotaan', 'aktif');

        if ($wilayahId) {
            $query->where('wilayah_id', $wilayahId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_kk', 'like', "%{$search}%");
            });
        }

        $allJemaats = $query->get();

        // Hitung Statistik
        $startOfWeek = $now->copy()->startOfWeek();
        $endOfWeek = $now->copy()->endOfWeek();

        $countPekanIni = 0;
        $countBulanIni = 0;
        $countCustom = 0;

        foreach ($allJemaats as $j) {
            $tgl = Carbon::parse($j->tanggal_lahir);
            $ultahThisYear = $tgl->copy()->year($now->year);
            if ($ultahThisYear->betweenIncluded($startOfWeek, $endOfWeek)) {
                $countPekanIni++;
            }
            if ($tgl->month === $now->month) {
                $countBulanIni++;
            }
            if (!empty($j->ucapan_ultah)) {
                $countCustom++;
            }
        }

        // Filter Data Sesuai Pilihan Tab
        $result = collect();

        foreach ($allJemaats as $jemaat) {
            $tglLahir = Carbon::parse($jemaat->tanggal_lahir);
            $ultahTahunIni = $tglLahir->copy()->year($now->year);
            $umurTahunIni = $now->year - $tglLahir->year;

            $jemaat->tgl_ultah_carbon = $ultahTahunIni;
            $jemaat->umur_ke = $umurTahunIni;
            $jemaat->hari_ultah = $ultahTahunIni->translatedFormat('l, d F');
            $jemaat->tgl_lahir_formatted = $tglLahir->translatedFormat('d F Y');

            $matched = false;

            if ($filter === 'pekan_ini') {
                if ($ultahTahunIni->betweenIncluded($startOfWeek, $endOfWeek)) {
                    $matched = true;
                }
            } elseif ($filter === 'bulan_ini') {
                if ($tglLahir->month === (int)$now->month) {
                    $matched = true;
                }
            } elseif ($filter === 'pilih_bulan') {
                if ($tglLahir->month === (int)$bulanFilter) {
                    $matched = true;
                }
            } elseif ($filter === 'custom_only') {
                if (!empty($jemaat->ucapan_ultah)) {
                    $matched = true;
                }
            } else { // semua
                $matched = true;
            }

            if ($matched) {
                $result->push($jemaat);
            }
        }

        // Urutkan berdasarkan tanggal terdekat
        $sortedJemaat = $result->sortBy(function ($item) use ($filter) {
            return $item->tgl_ultah_carbon->format('m-d');
        })->values();

        // Paginate manually (12 per halaman)
        $page = (int)$request->input('page', 1);
        $perPage = 15;
        $total = $sortedJemaat->count();
        $paginatedJemaat = new \Illuminate\Pagination\LengthAwarePaginator(
            $sortedJemaat->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.ulang-tahun.index', compact(
            'paginatedJemaat',
            'wilayahList',
            'filter',
            'wilayahId',
            'bulanFilter',
            'search',
            'countPekanIni',
            'countBulanIni',
            'countCustom',
            'total'
        ));
    }

    /**
     * Simpan / Perbarui Ucapan Khusus Jemaat
     */
    public function update(Request $request, $id)
    {
        $jemaat = Jemaat::findOrFail($id);

        $request->validate([
            'ucapan_ultah' => 'nullable|string|max:1000',
        ]);

        $jemaat->ucapan_ultah = $request->input('ucapan_ultah');
        $jemaat->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Ucapan ulang tahun untuk {$jemaat->nama_lengkap} berhasil disimpan.",
                'ucapan_ultah' => $jemaat->ucapan_ultah,
            ]);
        }

        return back()->with('success', "Ucapan doa ulang tahun untuk {$jemaat->nama_lengkap} berhasil disimpan.");
    }

    /**
     * Reset Ucapan Kembali ke Standar Gereja (Bilangan 6:24-26)
     */
    public function reset(Request $request, $id)
    {
        $jemaat = Jemaat::findOrFail($id);
        $jemaat->ucapan_ultah = null;
        $jemaat->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Ucapan doa ulang tahun {$jemaat->nama_lengkap} telah direset ke doa umum gereja.",
            ]);
        }

        return back()->with('success', "Ucapan doa {$jemaat->nama_lengkap} telah direset ke doa umum.");
    }
}
