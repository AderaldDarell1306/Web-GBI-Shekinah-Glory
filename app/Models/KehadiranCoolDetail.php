<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehadiranCoolDetail extends Model
{
    use HasFactory;

    protected $table = 'kehadiran_cool_detail';

    protected $fillable = [
        'pertemuan_cool_id',
        'jemaat_id',
        'status_kehadiran',
        'keterangan',
    ];

    public function pertemuanCool()
    {
        return $this->belongsTo(PertemuanCool::class, 'pertemuan_cool_id');
    }

    public function jemaat()
    {
        return $this->belongsTo(Jemaat::class, 'jemaat_id');
    }
}

