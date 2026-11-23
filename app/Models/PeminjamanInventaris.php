<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeminjamanInventaris extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_inventaris';

    protected $fillable = [
        'kode_peminjaman',
        'inventaris_id',
        'nama_barang_custom',
        'nama_peminjam',
        'no_kontak',
        'instansi_kegiatan',
        'tanggal_pinjam',
        'tanggal_rencana_kembali',
        'tanggal_kembali_aktual',
        'jumlah_pinjam',
        'status',
        'kondisi_sebelum',
        'kondisi_sesudah',
        'catatan',
        'petugas_id',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_rencana_kembali' => 'date',
        'tanggal_kembali_aktual' => 'date',
        'jumlah_pinjam' => 'integer',
    ];

    public function inventaris(): BelongsTo
    {
        return $this->belongsTo(Inventaris::class, 'inventaris_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
