<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertemuanCool extends Model
{
    use HasFactory;

    protected $table = 'pertemuan_cool';

    protected $fillable = [
        'wilayah_id',
        'nama_cool',
        'tanggal_pertemuan',
        'jam_mulai',
        'tempat',
        'pemimpin_sharing',
        'tema_renungan',
        'catatan',
        'total_hadir',
        'created_by',
    ];

    protected $casts = [
        'tanggal_pertemuan' => 'date',
    ];

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function kehadiranDetails()
    {
        return $this->hasMany(KehadiranCoolDetail::class, 'pertemuan_cool_id');
    }
}

