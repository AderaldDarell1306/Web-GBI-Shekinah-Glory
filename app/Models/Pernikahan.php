<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pernikahan extends Model
{
    use HasFactory;

    protected $table = 'pernikahan';

    protected $fillable = [
        'no_sertifikat',
        'pengantin_pria_jemaat_id',
        'pengantin_pria_nama',
        'pengantin_pria_ayah',
        'pengantin_pria_ibu',
        'pengantin_wanita_jemaat_id',
        'pengantin_wanita_nama',
        'pengantin_wanita_ayah',
        'pengantin_wanita_ibu',
        'tanggal_pemberkatan',
        'tempat_pemberkatan',
        'pendeta_pemberkat',
        'saksi_1',
        'saksi_2',
        'status_bimbingan',
        'catatan_konseling',
        'berkas_legalitas',
        'created_by',
    ];

    protected $casts = [
        'tanggal_pemberkatan' => 'date',
    ];

    public function pengantinPria(): BelongsTo
    {
        return $this->belongsTo(Jemaat::class, 'pengantin_pria_jemaat_id');
    }

    public function pengantinWanita(): BelongsTo
    {
        return $this->belongsTo(Jemaat::class, 'pengantin_wanita_jemaat_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

