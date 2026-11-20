<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalIbadah extends Model
{
    use HasFactory;

    protected $table = 'jadwal_ibadah';

    protected $fillable = [
        'nama_ibadah',
        'kategori',
        'hari',
        'jam',
        'lokasi_ruangan',
        'keterangan',
        'tipe_pelaksanaan',
        'urutan',
        'status',
    ];

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif')->orderBy('urutan', 'asc');
    }
}
