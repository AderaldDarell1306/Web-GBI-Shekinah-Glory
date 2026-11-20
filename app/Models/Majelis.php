<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Majelis extends Model
{
    use HasFactory;

    protected $table = 'majelis';

    protected $fillable = [
        'jemaat_id',
        'nama_majelis',
        'jabatan',
        'bidang_pelayanan',
        'periode',
        'no_sk',
        'no_hp',
        'status',
        'catatan',
    ];

    public function jemaat(): BelongsTo
    {
        return $this->belongsTo(Jemaat::class, 'jemaat_id');
    }

    public static function jabatanOptions(): array
    {
        return [
            'Pendeta (Pdt.)' => 'Pendeta (Pdt.)',
            'Pendeta Muda (Pdm.)' => 'Pendeta Muda (Pdm.)',
            'Pendeta Pratama (Pdp.)' => 'Pendeta Pratama (Pdp.)',
            'Penatua' => 'Penatua (Elder)',
            'Diaken' => 'Diaken (Deacon)',
            'Majelis Jemaat' => 'Majelis Jemaat',
            'Pelayan Altar' => 'Pelayan Altar',
            'Pemain Tamborin' => 'Pemain Tamborin',
            'Majelis Pertimbangan' => 'Majelis Pertimbangan',
            'Majelis Pembina' => 'Majelis Pembina',
        ];
    }
}
