<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumentasiFoto extends Model
{
    use HasFactory;

    protected $table = 'dokumentasi_foto';

    protected $fillable = [
        'judul',
        'kategori_kegiatan',
        'tanggal_kegiatan',
        'file_foto',
        'deskripsi',
        'tampilkan_di_galeri',
        'urutan',
        'uploaded_by',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'tampilkan_di_galeri' => 'boolean',
        'urutan' => 'integer',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public static function getKategoriOptions(): array
    {
        return [
            'ibadah_raya' => 'Ibadah Raya',
            'kaum_bapak' => 'Kaum Bapak',
            'kaum_ibu' => 'Kaum Ibu',
            'pemuda_remaja' => 'Pemuda & Remaja',
            'anak_sekolah_minggu' => 'Sekolah Minggu / Anak',
            'bakti_sosial' => 'Bakti Sosial & Diakonia',
            'umum' => 'Kegiatan Umum / Lainnya',
        ];
    }

    public function getFotoUrlAttribute(): string
    {
        if (str_starts_with($this->file_foto, 'http://') || str_starts_with($this->file_foto, 'https://')) {
            return $this->file_foto;
        }
        return asset('storage/' . $this->file_foto);
    }
}
