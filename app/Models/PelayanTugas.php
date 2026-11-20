<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PelayanTugas extends Model
{
    use HasFactory;

    protected $table = 'pelayan_tugas';

    protected $fillable = [
        'jadwal_pelayanan_id',
        'posisi',
        'jemaat_id',
        'nama_pelayan',
        'no_wa',
        'status_konfirmasi',
        'catatan',
    ];

    public function jadwalPelayanan(): BelongsTo
    {
        return $this->belongsTo(JadwalPelayanan::class, 'jadwal_pelayanan_id');
    }

    public function jemaat(): BelongsTo
    {
        return $this->belongsTo(Jemaat::class, 'jemaat_id');
    }

    public static function posisiLabels(): array
    {
        return [
            'wl' => 'Worship Leader (WL)',
            'singer_1' => 'Singer 1',
            'singer_2' => 'Singer 2',
            'singer_3' => 'Singer 3',
            'keyboard' => 'Keyboardist / Pianist',
            'gitar_akustik' => 'Gitar Akustik',
            'gitar_elektrik' => 'Gitar Elektrik',
            'bass' => 'Bassist',
            'drum' => 'Drummer',
            'tamborin' => 'Pemain Tamborin',
            'pelayan_altar' => 'Pelayan Altar',
            'soundman' => 'Sound Engineer / Audio',
            'multimedia' => 'Operator Multimedia (EasyWorship/ProPresenter)',
            'live_streaming' => 'Operator Kamera / Streaming',
            'usher_1' => 'Penyambut Jemaat (Usher 1)',
            'usher_2' => 'Penyambut Jemaat (Usher 2)',
            'pendoa_syafaat' => 'Pendoa Syafaat',
            'kolektan' => 'Petugas Kolekte',
        ];
    }

    public static function tamborinList(): array
    {
        return [
            'Tryana Henuk',
            'Netari Henuk',
            'Prety Nafu',
            'Hadaisa Nafu',
            'Washi Fanggi',
            'Gea Ndolu',
            'Diva Leu',
            'Alfa Bengu',
            'Prety Mbu',
            'Truli Nafu',
            'Kezia Babys',
            'Salsa Alu',
            'Betris Ndolu',
            'Stevi Pade',
            'Fani Benga',
            'Cici Suy',
        ];
    }

    public static function pelayanAltarList(): array
    {
        return [
            'Bpk. Victor Lana',
            'Bpk. Fery Mbau',
            'Bpk. Yusuf Mbuir',
            'Bpk. Denri Ballo',
            'Bpk. Yonathan Fanggi',
            'Bpk. Emon Suy',
            'Bpk. Afrit Lana',
            'Sdra. Bara Suy',
            'Sdri. Rebecha Henuk',
            'Sdra. Ito Suy',
            'Sdra. Charli Lana',
            'Sdri. Rada Nuswa\'e',
            'Sdri. Celin Henuk',
            'Sdri. Jarlin Kanawadu',
            'Ebzan Henuk',
            'Helti Arriang',
        ];
    }

    public function getPosisiLabelAttribute(): string
    {
        $labels = self::posisiLabels();
        return $labels[$this->posisi] ?? ucfirst(str_replace('_', ' ', $this->posisi));
    }
}

