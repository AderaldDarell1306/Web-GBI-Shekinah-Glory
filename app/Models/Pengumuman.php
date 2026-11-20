<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'kategori',
        'isi',
        'gambar',
        'tanggal',
        'tanggal_kadaluarsa',
        'is_pinned',
        'status',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_kadaluarsa' => 'date',
        'is_pinned' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getGambarUrlAttribute(): string
    {
        if (!empty($this->gambar)) {
            if (str_starts_with($this->gambar, 'http://') || str_starts_with($this->gambar, 'https://')) {
                return $this->gambar;
            }
            if (file_exists(public_path('storage/' . $this->gambar))) {
                return asset('storage/' . $this->gambar);
            }
            return asset($this->gambar);
        }

        $defaults = [
            'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1438232992991-995b7058bbb3?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1519491050282-cf00c82424b4?auto=format&fit=crop&w=800&q=80',
        ];
        return $defaults[($this->id ?? 0) % count($defaults)];
    }

    public function getKategoriBadgeAttribute(): array
    {
        return match ($this->kategori) {
            'penting' => ['bg' => 'bg-red-100 text-red-800 border-red-200', 'label' => 'Penting / Khusus'],
            'ibadah' => ['bg' => 'bg-blue-100 text-blue-800 border-blue-200', 'label' => 'Jadwal & Ibadah'],
            'kegiatan' => ['bg' => 'bg-purple-100 text-purple-800 border-purple-200', 'label' => 'Kegiatan & Acara'],
            'pelayanan' => ['bg' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'label' => 'Pelayanan & Komisi'],
            'sosial' => ['bg' => 'bg-amber-100 text-amber-800 border-amber-200', 'label' => 'Aksi Sosial & PPA'],
            default => ['bg' => 'bg-gray-100 text-gray-800 border-gray-200', 'label' => 'Umum'],
        };
    }
}

