<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WartaJemaat extends Model
{
    use HasFactory;

    protected $table = 'warta_jemaat';

    protected $fillable = [
        'tanggal_warta',
        'edisi',
        'tema_mingguan',
        'gambar_sampul',
        'ayat_tema',
        'ringkasan_khotbah',
        'pokok_doa',
        'pengumuman_kegiatan',
        'laporan_keuangan_ringkas',
        'file_pdf',
        'buletin_tipe',
        'buletin_judul',
        'buletin_file',
        'buletin_link',
        'buletin_content',
        'status',
        'created_by',
    ];

    protected $casts = [
        'tanggal_warta' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTemaKhotbahAttribute()
    {
        return $this->tema_mingguan;
    }

    public function getAyatBacaanAttribute()
    {
        return $this->ayat_tema;
    }

    public function getEdisiMingguAttribute()
    {
        return $this->tanggal_warta;
    }

    public function getGambarSampulUrlAttribute()
    {
        if (!empty($this->gambar_sampul)) {
            if (str_starts_with($this->gambar_sampul, 'http://') || str_starts_with($this->gambar_sampul, 'https://')) {
                return $this->gambar_sampul;
            }
            if (file_exists(public_path('storage/' . $this->gambar_sampul))) {
                return asset('storage/' . $this->gambar_sampul);
            }
            return asset($this->gambar_sampul);
        }
        if (!empty($this->thumbnail) && file_exists(public_path('storage/' . $this->thumbnail))) {
            return asset('storage/' . $this->thumbnail);
        }
        $defaults = [
            'https://images.unsplash.com/photo-1507692049790-de58290a4334?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1499209974431-9dddcece7f88?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1438232992991-995b7058bbb3?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1519491050282-cf00c82424b4?auto=format&fit=crop&w=800&q=80',
        ];
        return $defaults[($this->id ?? 0) % count($defaults)];
    }

    /**
     * Dapatkan daftar jemaat yang berulang tahun pada minggu edisi warta ini.
     * Dimulai dari Senin s.d. Minggu pada pekan tanggal_warta.
     */
    public function getJemaatUlangTahunMingguIniAttribute()
    {
        $date = Carbon::parse($this->tanggal_warta);
        $startOfWeek = $date->copy()->startOfWeek(); // Senin
        $endOfWeek = $date->copy()->endOfWeek();     // Minggu

        $jemaats = Jemaat::with('wilayah')
            ->whereNotNull('tanggal_lahir')
            ->select('id', 'nama_lengkap', 'tanggal_lahir', 'no_hp', 'wilayah_id', 'jenis_kelamin')
            ->get();

        $result = collect();

        foreach ($jemaats as $jemaat) {
            $tglLahir = Carbon::parse($jemaat->tanggal_lahir);
            // Tanggal ultah tahun ini
            $ultahTahunIni = $tglLahir->copy()->year($date->year);

            if ($ultahTahunIni->betweenIncluded($startOfWeek, $endOfWeek)) {
                $umurTahunIni = $date->year - $tglLahir->year;
                $jemaat->hari_ultah = $ultahTahunIni->translatedFormat('l, d F');
                $jemaat->tgl_ultah_carbon = $ultahTahunIni;
                $jemaat->umur_ke = $umurTahunIni;
                $jemaat->nama_wilayah = $jemaat->wilayah?->nama_wilayah ?? 'Namosain';
                $result->push($jemaat);
            }
        }

        return $result->sortBy('tgl_ultah_carbon')->values();
    }
}
