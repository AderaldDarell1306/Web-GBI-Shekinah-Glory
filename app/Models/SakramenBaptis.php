<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SakramenBaptis extends Model
{
    use HasFactory;

    protected $table = 'sakramen_baptis';

    protected $fillable = [
        'no_sertifikat',
        'jemaat_id',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'tanggal_baptis',
        'tempat_baptis',
        'pendeta_pelayan',
        'nama_ayah',
        'nama_ibu',
        'saksi',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_baptis' => 'date',
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

