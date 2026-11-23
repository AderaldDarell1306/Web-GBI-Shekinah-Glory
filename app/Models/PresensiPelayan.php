<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresensiPelayan extends Model
{
    use HasFactory;

    protected $table = 'presensi_pelayan';

    protected $fillable = [
        'jadwal_pelayanan_id',
        'pelayan_tugas_id',
        'jemaat_id',
        'tanggal_ibadah',
        'kehadiran',
        'catatan',
        'recorded_by',
    ];

    protected $casts = [
        'tanggal_ibadah' => 'date',
    ];

    public function jadwalPelayanan(): BelongsTo
    {
        return $this->belongsTo(JadwalPelayanan::class, 'jadwal_pelayanan_id');
    }

    public function pelayanTugas(): BelongsTo
    {
        return $this->belongsTo(PelayanTugas::class, 'pelayan_tugas_id');
    }

    public function jemaat(): BelongsTo
    {
        return $this->belongsTo(Jemaat::class, 'jemaat_id');
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Alat Ukur Keaktifan Pelayan Per Tahun (PRD 2.4 B & AC-2207):
     * Akumulasi presensi mingguan dikalkulasi menjadi skor/grafik keaktifan pelayan per tahun.
     */
    public static function getMinisterAnnualStats(int $year = 2026): array
    {
        $all = static::with(['pelayanTugas', 'jemaat'])
            ->whereYear('tanggal_ibadah', $year)
            ->get();

        $ministerMap = [];

        foreach ($all as $item) {
            $name = $item->pelayanTugas?->nama_pelayan ?? $item->jemaat?->nama_lengkap ?? 'Pelayan';
            $peran = $item->pelayanTugas?->posisi ? PelayanTugas::posisiLabels()[$item->pelayanTugas->posisi] ?? ucfirst($item->pelayanTugas->posisi) : 'Pelayan Ibadah';

            if (!isset($ministerMap[$name])) {
                $ministerMap[$name] = [
                    'nama' => $name,
                    'peran' => $peran,
                    'total_jadwal' => 0,
                    'hadir' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'alpa' => 0,
                    'skor_keaktifan' => 0,
                ];
            }

            $ministerMap[$name]['total_jadwal']++;
            if (isset($ministerMap[$name][$item->kehadiran])) {
                $ministerMap[$name][$item->kehadiran]++;
            }
        }

        // Hitung persentase keaktifan (Hadir = 100%, Izin = 50%, Sakit = 50%, Alpa = 0%)
        foreach ($ministerMap as $k => $data) {
            if ($data['total_jadwal'] > 0) {
                $points = ($data['hadir'] * 100) + ($data['izin'] * 50) + ($data['sakit'] * 50);
                $ministerMap[$k]['skor_keaktifan'] = round($points / ($data['total_jadwal'] * 100) * 100, 1);
            }
        }

        // Urutkan berdasarkan skor keaktifan tertinggi lalu total kehadiran
        uasort($ministerMap, function ($a, $b) {
            if ($a['skor_keaktifan'] == $b['skor_keaktifan']) {
                return $b['hadir'] <=> $a['hadir'];
            }
            return $b['skor_keaktifan'] <=> $a['skor_keaktifan'];
        });

        return [
            'year' => $year,
            'totalEntries' => $all->count(),
            'totalHadir' => $all->where('kehadiran', 'hadir')->count(),
            'ministers' => array_values($ministerMap),
            'topMinisters' => array_slice(array_values($ministerMap), 0, 10),
        ];
    }
}
