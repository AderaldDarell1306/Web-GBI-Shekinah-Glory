<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'inventaris';

    protected $fillable = [
        'nomor',
        'jenis_inventaris',
        'kode_inventaris',
        'nama_barang',
        'lokasi',
        'jumlah',
        'satuan',
        'kondisi',
        'status_ketersediaan',
        'tanggal_perolehan',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'jumlah' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function getLokasiOptions(): array
    {
        return [
            'kantor' => 'Kantor Gereja',
            'gereja' => 'Gedung Ibadah / Gereja',
            'gudang' => 'Gudang Pastori / Gereja',
        ];
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(PeminjamanInventaris::class, 'inventaris_id');
    }
}
