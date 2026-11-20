<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipSurat extends Model
{
    use HasFactory;

    protected $table = 'arsip_surat';

    protected $fillable = [
        'jenis_surat',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_terima_kirim',
        'pengirim',
        'tujuan_penerima',
        'perihal',
        'kategori_surat',
        'tahun_kalender',
        'file_surat',
        'ringkasan_isi',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_terima_kirim' => 'date',
        'tahun_kalender' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getKategoriLabelAttribute()
    {
        $labels = [
            'undangan' => 'Surat Undangan',
            'dinas_pemerintah' => 'Dinas / Instansi Pemerintah',
            'sinode' => 'Surat BPD / Sinode GBI',
            'rekomendasi' => 'Surat Rekomendasi',
            'pastoral' => 'Pastoral & Pelayanan',
            'proposal' => 'Proposal & Kerjasama',
            'translokasi' => 'Translokasi / Pindah Jemaat',
            'pemberitahuan' => 'Pemberitahuan Resmi',
            'umum' => 'Surat Umum',
            'lainnya' => 'Lain-lain',
        ];

        return $labels[$this->kategori_surat] ?? ucwords(str_replace('_', ' ', $this->kategori_surat));
    }
}

