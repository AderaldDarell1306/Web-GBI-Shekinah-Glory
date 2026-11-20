<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKeuangan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_keuangan';

    /**
     * PRD v2.1: Pengecualian Perpuluhan
     * Perpuluhan tidak dicatat dan dikecualikan dari sistem website.
     */
    protected static function booted()
    {
        static::addGlobalScope('tanpa_perpuluhan', function ($builder) {
            $builder->whereNotIn('kategori', ['persepuluhan', 'perpuluhan']);
        });
    }

    protected $fillable = [
        'kode_transaksi',
        'tanggal_transaksi',
        'jenis',
        'kategori',
        'nominal',
        'metode_pembayaran',
        'sumber_penerima',
        'keterangan',
        'no_bukti_nota',
        'lampiran_file',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopePemasukan($query)
    {
        return $query->where('jenis', 'pemasukan');
    }

    public function scopePengeluaran($query)
    {
        return $query->where('jenis', 'pengeluaran');
    }

    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeBulanTahun($query, $bulan, $tahun)
    {
        return $query->whereMonth('tanggal_transaksi', $bulan)
                     ->whereYear('tanggal_transaksi', $tahun);
    }

    // Helper label format
    public function getKategoriLabelAttribute()
    {
        $labels = [
            'persembahan_raya' => 'Kolekte / Persembahan Raya',
            'pembangunan' => 'Dana Pembangunan',
            'diakonia' => 'Pelayanan Diakonia',
            'operasional' => 'Operasional Gereja',
            'ucapan_syukur' => 'Ucapan Syukur',
            'pembicara_tamu' => 'Honorarium & Tamu',
            'lainnya' => 'Lain-lain',
        ];

        return $labels[$this->kategori] ?? ucwords(str_replace('_', ' ', $this->kategori));
    }
}

