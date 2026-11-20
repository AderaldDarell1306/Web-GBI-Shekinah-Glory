<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranLayanan extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_layanan';

    protected $fillable = [
        'kode_pendaftaran',
        'jenis_layanan',
        'nama_pemohon',
        'no_wa',
        'email',
        'alamat',
        'data_tambahan',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'data_tambahan' => 'array',
    ];

    public function getJenisLayananLabelAttribute(): string
    {
        return match ($this->jenis_layanan) {
            'baptis' => 'Baptisan Air',
            'penyerahan_anak' => 'Penyerahan Anak',
            'nikah' => 'Pemberkatan Nikah',
            'jemaat_baru' => 'Pendaftaran Jemaat Baru',
            'pindah' => 'Permohonan Surat Pindah',
            default => ucfirst($this->jenis_layanan),
        };
    }
}

