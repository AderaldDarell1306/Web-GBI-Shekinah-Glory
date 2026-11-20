<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratPastoral extends Model
{
    use HasFactory;

    protected $table = 'surat_pastoral';

    protected $fillable = [
        'no_surat',
        'jenis_surat',
        'jemaat_id',
        'nama_jemaat',
        'tanggal_surat',
        'tujuan_gereja_instansi',
        'keperluan_alasan',
        'penandatangan',
        'jabatan_penandatangan',
        'status',
        'created_by',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    public function jemaat(): BelongsTo
    {
        return $this->belongsTo(Jemaat::class, 'jemaat_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getJenisSuratLabelAttribute(): string
    {
        return match ($this->jenis_surat) {
            'surat_pindah' => 'Surat Pindah (Translokasi)',
            'pengantar_keanggotaan' => 'Surat Pengantar Keanggotaan',
            'surat_keterangan' => 'Surat Keterangan Jemaat',
            default => 'Surat Keterangan',
        };
    }
}

