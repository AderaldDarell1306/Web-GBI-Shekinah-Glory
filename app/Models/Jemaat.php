<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jemaat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jemaat';

    protected $fillable = [
        'no_kk',
        'nik',
        'nomor_induk',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'status_keluarga',
        'status_pernikahan',
        'status_baptis',
        'tanggal_baptis',
        'nikah_masehi',
        'pendidikan',
        'pekerjaan',
        'keahlian_khusus',
        'penghasilan_per_bulan',
        'lain_lain',
        'keterangan',
        'alamat',
        'no_hp',
        'email',
        'wilayah_id',
        'status_keanggotaan',
        'tipe_keanggotaan',
        'kategori_khusus',
        'tanggal_bergabung',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_baptis' => 'date',
        'tanggal_bergabung' => 'date',
    ];

    /**
     * Format TTL (Tempat, Tanggal Lahir)
     */
    public function getTtlAttribute(): string
    {
        $parts = [];
        if (!empty($this->tempat_lahir)) {
            $parts[] = trim($this->tempat_lahir);
        }
        if (!empty($this->tanggal_lahir)) {
            $parts[] = $this->tanggal_lahir->format('d-m-Y');
        }
        return implode(', ', $parts);
    }

    /**
     * Relasi ke Wilayah
     */
    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    /**
     * Relasi ke Admin pembuat
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke Admin pengubah
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Helper status badge
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_keanggotaan) {
            'aktif' => 'Aktif',
            'tidak_aktif' => 'Tidak Aktif',
            'pindah' => 'Pindah',
            'meninggal' => 'Meninggal',
            default => 'Aktif',
        };
    }

    /**
     * Decode Wilayah Besar dari kode KK (1=Namosain, 2=Rumah 7, 3=Osmok)
     */
    public function getWilayahBesarAttribute(): ?string
    {
        if ($this->wilayah && $this->wilayah->wilayah_besar) {
            return $this->wilayah->wilayah_besar;
        }
        $code = (string) ($this->no_kk ?? '');
        if (strlen($code) >= 1) {
            return match ($code[0]) {
                '1' => 'Namosain',
                '2' => 'Rumah 7',
                '3' => 'Osmok',
                default => null,
            };
        }
        return null;
    }

    /**
     * Decode Sub Wilayah dari 2 digit pertama KK (misal: 31 -> Osmok Sub Wilayah 1)
     */
    public function getSubWilayahLabelAttribute(): ?string
    {
        if ($this->wilayah) {
            return $this->wilayah->nama_wilayah;
        }
        $code = (string) ($this->no_kk ?? '');
        if (strlen($code) >= 2) {
            $prefix = substr($code, 0, 2);
            $found = Wilayah::where('kode_prefix', $prefix)->first();
            return $found?->nama_wilayah;
        }
        return null;
    }

    /**
     * Kode KK Keluarga (4 digit pertama: Wilayah + Sub + No KK)
     * Misal '3101' untuk seluruh anggota keluarga nomor 01 di Osmok 1
     */
    public function getKodeKeluargaAttribute(): ?string
    {
        $code = (string) ($this->no_kk ?? '');
        return strlen($code) >= 4 ? substr($code, 0, 4) : null;
    }

    /**
     * Hitung umur saat ini (atau berdasarkan tahun kelahiran)
     */
    public function getUmurAttribute(): ?int
    {
        if (!$this->tanggal_lahir) {
            return null;
        }
        return (int) date('Y') - (int) $this->tanggal_lahir->format('Y');
    }

    /**
     * Kategori Usia / Pelayanan Jemaat (Sesuai Aturan Bisnis PRD v2.1):
     * - Anak-Anak: usia 0 s.d 14 tahun
     * - Pemuda & Remaja: usia 15 s.d 35 tahun yang belum menikah
     * - Konversi Menikah: usia 15 s.d 35 tahun yang berstatus Menikah otomatis masuk Kaum Bapak/Ibu
     * - Usia > 35 tahun: Kaum Bapak (L) atau Kaum Ibu (P)
     */
    public function getKategorialPelayananAttribute(): string
    {
        $umur = $this->umur;
        $jk = strtoupper($this->jenis_kelamin ?? 'L');
        $sp = strtolower(trim((string)($this->status_pernikahan ?? 'belum_menikah')));
        $isMenikah = in_array($sp, ['menikah', 'kawin']);

        // 1. Usia 0 - 14 tahun otomatis Anak-Anak
        if ($umur !== null && $umur <= 14) {
            return 'Anak-Anak';
        }

        // 2. Usia 15 - 35 tahun
        if ($umur !== null && $umur >= 15 && $umur <= 35) {
            // Jika berstatus menikah -> konversi otomatis ke Kaum Bapak / Ibu
            if ($isMenikah) {
                return $jk === 'L' ? 'Kaum Bapak' : 'Kaum Ibu';
            }
            // Belum menikah -> Pemuda & Remaja
            return 'Pemuda & Remaja';
        }

        // 3. Usia > 35 tahun atau default dewasa
        return $jk === 'L' ? 'Kaum Bapak' : 'Kaum Ibu';
    }

    /**
     * Apakah termasuk target pelayanan Diakonia Khusus (Janda, Duda, Yatim, Piatu, Yatim Piatu)
     */
    public function getDiakoniaLabelAttribute(): ?string
    {
        if (!empty($this->kategori_khusus) && $this->kategori_khusus !== 'umum') {
            return match ($this->kategori_khusus) {
                'janda' => 'Janda',
                'duda' => 'Duda',
                'yatim' => 'Yatim',
                'piatu' => 'Piatu',
                'yatim_piatu' => 'Yatim Piatu',
                'lansia' => 'Lansia',
                default => ucfirst($this->kategori_khusus),
            };
        }

        $sk = strtoupper($this->status_keluarga ?? '');
        $sp = $this->status_pernikahan ?? '';

        if ($sp === 'janda' || str_contains($sk, 'JANDA')) {
            return 'Janda';
        }
        if ($sp === 'duda' || str_contains($sk, 'DUDA')) {
            return 'Duda';
        }
        if (str_contains($sk, 'YATIM PIATU')) {
            return 'Yatim Piatu';
        }
        if (str_contains($sk, 'YATIM')) {
            return 'Yatim';
        }
        if (str_contains($sk, 'PIATU')) {
            return 'Piatu';
        }

        return null;
    }

    /**
     * Nomor urut anggota keluarga dalam KK (2 digit terakhir)
     */
    public function getUrutanAnggotaAttribute(): ?string
    {
        $code = (string) ($this->no_kk ?? '');
        return strlen($code) >= 6 ? substr($code, 4, 2) : null;
    }

    /**
     * Rekapitulasi Kategorial Pelayanan
     */
    public static function getKategorialStats(?int $wilayahId = null, ?string $wilayahBesar = null): array
    {
        $query = static::query();
        if ($wilayahId) {
            $query->where('wilayah_id', $wilayahId);
        } elseif ($wilayahBesar) {
            $query->whereHas('wilayah', fn($q) => $q->where('wilayah_besar', $wilayahBesar));
        }

        $all = $query->select(['id', 'jenis_kelamin', 'status_keluarga', 'status_pernikahan', 'tanggal_lahir', 'keterangan', 'kategori_khusus'])->get();

        $stats = [
            'kaum_bapak' => 0,
            'kaum_ibu' => 0,
            'pemuda_remaja' => 0,
            'anak' => 0,
            'lansia_khusus' => 0,
        ];

        foreach ($all as $j) {
            $ket = strtoupper(trim((string)$j->keterangan));
            if ($j->kategori_khusus === 'janda' || $j->kategori_khusus === 'duda' || ($j->umur && $j->umur >= 60)) {
                $stats['lansia_khusus']++;
            }

            // Aturan Bisnis PRD v2.1: Prioritaskan kalkulasi otomatis berdasarkan umur & status nikah
            $kat = $j->kategorial_pelayanan;
            if ($j->umur !== null) {
                if ($kat === 'Kaum Bapak') $stats['kaum_bapak']++;
                elseif ($kat === 'Kaum Ibu') $stats['kaum_ibu']++;
                elseif ($kat === 'Pemuda & Remaja') $stats['pemuda_remaja']++;
                elseif ($kat === 'Anak-Anak') $stats['anak']++;
            } else {
                // Fallback jika tanggal lahir belum terisi
                if (str_contains($ket, 'BAPAK')) {
                    $stats['kaum_bapak']++;
                } elseif (str_contains($ket, 'IBU')) {
                    $stats['kaum_ibu']++;
                } elseif (str_contains($ket, 'PEMUDA') || str_contains($ket, 'PEMMUDA') || str_contains($ket, 'REMAJA')) {
                    $stats['pemuda_remaja']++;
                } elseif (str_contains($ket, 'ABI') || str_contains($ket, 'ANAK')) {
                    $stats['anak']++;
                } else {
                    if ($kat === 'Kaum Bapak') $stats['kaum_bapak']++;
                    elseif ($kat === 'Kaum Ibu') $stats['kaum_ibu']++;
                    elseif ($kat === 'Pemuda & Remaja') $stats['pemuda_remaja']++;
                    elseif ($kat === 'Anak-Anak') $stats['anak']++;
                }
            }
        }

        return $stats;
    }
}
