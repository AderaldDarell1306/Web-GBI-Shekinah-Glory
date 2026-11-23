<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\JadwalPelayanan;
use App\Models\PelayanTugas;
use App\Models\PresensiPelayan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiPelayanController extends Controller
{
    /**
     * Lembar Presensi Mingguan & Alat Ukur Keaktifan Pelayan (PRD 2.4 B & AC-2207)
     */
    public function index(Request $request)
    {
        $allJadwal = JadwalPelayanan::with('sesiIbadah')
            ->orderBy('tanggal_pelayanan', 'desc')
            ->get();

        $selectedJadwalId = $request->input('jadwal_id');
        $jadwal = null;

        if ($selectedJadwalId) {
            $jadwal = JadwalPelayanan::with(['sesiIbadah', 'petugas.jemaat'])->find($selectedJadwalId);
        }

        if (!$jadwal && $allJadwal->isNotEmpty()) {
            $jadwal = JadwalPelayanan::with(['sesiIbadah', 'petugas.jemaat'])
                ->where('tanggal_pelayanan', '>=', Carbon::today()->subDays(6))
                ->orderBy('tanggal_pelayanan', 'asc')
                ->first() ?? $allJadwal->first();
        }

        $existingPresensi = [];
        if ($jadwal) {
            $existingPresensi = PresensiPelayan::where('jadwal_pelayanan_id', $jadwal->id)
                ->get()
                ->keyBy('pelayan_tugas_id');
        }

        $selectedYear = (int) $request->input('year', 2026);
        $annualStats = PresensiPelayan::getMinisterAnnualStats($selectedYear);
        $posisiLabels = PelayanTugas::posisiLabels();

        return view('admin.pelayanan.presensi', compact(
            'allJadwal',
            'jadwal',
            'existingPresensi',
            'annualStats',
            'selectedYear',
            'posisiLabels'
        ));
    }

    /**
     * Simpan / Perbarui Presensi Mingguan Petugas Pelayanan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jadwal_pelayanan_id' => 'required|exists:jadwal_pelayanan,id',
            'presensi' => 'required|array',
            'presensi.*.kehadiran' => 'required|in:hadir,izin,sakit,alpa',
            'presensi.*.catatan' => 'nullable|string|max:255',
        ]);

        $jadwal = JadwalPelayanan::with('petugas')->findOrFail($validated['jadwal_pelayanan_id']);
        $recordedCount = 0;

        foreach ($jadwal->petugas as $tugas) {
            $input = $validated['presensi'][$tugas->id] ?? null;
            if (!$input) continue;

            PresensiPelayan::updateOrCreate(
                [
                    'jadwal_pelayanan_id' => $jadwal->id,
                    'pelayan_tugas_id' => $tugas->id,
                ],
                [
                    'jemaat_id' => $tugas->jemaat_id,
                    'tanggal_ibadah' => $jadwal->tanggal_pelayanan,
                    'kehadiran' => $input['kehadiran'] ?? 'hadir',
                    'catatan' => $input['catatan'] ?? null,
                    'recorded_by' => Auth::id(),
                ]
            );
            $recordedCount++;
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'PRESENSI_PELAYAN',
            'model' => 'PresensiPelayan',
            'details' => "Mencatat presensi {$recordedCount} pelayan untuk ibadah tanggal {$jadwal->tanggal_pelayanan->format('d/m/Y')}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.presensi.index', ['jadwal_id' => $jadwal->id])
            ->with('success', "Presensi {$recordedCount} pelayan ibadah berhasil dicatat dan disinkronkan ke alat ukur keaktifan tahunan.");
    }
}
