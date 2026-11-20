<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KalenderAcara extends Model
{
    use HasFactory;

    protected $table = 'kalender_acara';

    protected $fillable = [
        'judul_acara',
        'kategori',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu',
        'lokasi',
        'deskripsi',
        'warna_label',
        'tampilkan_di_web',
        'created_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tampilkan_di_web' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getKategoriLabelAttribute()
    {
        $labels = [
            'hari_raya' => 'Hari Raya Gerejawi',
            'ibadah_khusus' => 'Ibadah Khusus / KKR',
            'retret_kkr' => 'Retret & Pembinaan',
            'rapat_majelis' => 'Rapat Majelis / Sidang Jemaat',
            'kegiatan_pemuda' => 'Kegiatan Youth & Kategorial',
            'doa_puasa' => 'Doa & Puasa',
            'lainnya' => 'Kegiatan Lainnya',
        ];

        return $labels[$this->kategori] ?? ucwords(str_replace('_', ' ', $this->kategori));
    }
}

