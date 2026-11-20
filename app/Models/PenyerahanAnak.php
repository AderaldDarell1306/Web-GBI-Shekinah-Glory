<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenyerahanAnak extends Model
{
    use HasFactory;

    protected $table = 'penyerahan_anak';

    protected $fillable = [
        'no_sertifikat',
        'jemaat_id',
        'nama_anak',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'tanggal_penyerahan',
        'tempat_penyerahan',
        'nama_ayah',
        'nama_ibu',
        'pendeta_pelayan',
        'saksi',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_penyerahan' => 'date',
    ];

    public function jemaat(): BelongsTo
    {
        return $this->belongsTo(Jemaat::class, 'jemaat_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

