<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = 'wilayah';

    protected $fillable = [
        'nama_wilayah',
        'wilayah_besar',
        'kode_wilayah',
        'kode_prefix',
        'nama_ketua',
        'telepon_ketua',
        'deskripsi',
        'status',
    ];

    /**
     * Relasi ke Jemaat (Satu wilayah memiliki banyak jemaat)
     */
    public function jemaat(): HasMany
    {
        return $this->hasMany(Jemaat::class, 'wilayah_id');
    }

    /**
     * Scope wilayah aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
