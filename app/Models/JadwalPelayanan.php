<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalPelayanan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pelayanan';

    protected $fillable = [
        'jadwal_ibadah_id',
        'tanggal_pelayanan',
        'tema_ibadah',
        'pengkhotbah',
        'catatan',
        'status_publikasi',
        'created_by',
    ];

    protected $casts = [
        'tanggal_pelayanan' => 'date',
    ];

    public function sesiIbadah(): BelongsTo
    {
        return $this->belongsTo(JadwalIbadah::class, 'jadwal_ibadah_id');
    }

    public function petugas(): HasMany
    {
        return $this->hasMany(PelayanTugas::class, 'jadwal_pelayanan_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Format WhatsApp Rota Text untuk dikirimkan ke Grup WhatsApp Jemaat/Pelayan
     */
    public function generateWhatsAppBroadcastText(): string
    {
        $sesi = $this->sesiIbadah;
        $tglFormat = $this->tanggal_pelayanan->translatedFormat('l, d F Y');

        $text = "🕊️ *JADWAL PELAYANAN IBADAH*\n";
        $text .= "*GBI SHEKINAH GLORY NAMOSAIN*\n";
        $text .= "━━━━━━━━━━━━━━━━━━━━\n";
        $text .= "📅 *Hari/Tgl:* {$tglFormat}\n";
        $text .= "⏰ *Waktu:* {$sesi->jam} WITA\n";
        $text .= "⛪ *Ibadah:* {$sesi->nama_ibadah} ({$sesi->lokasi_ruangan})\n";
        if (!empty($this->tema_ibadah)) {
            $text .= "📖 *Tema:* \"{$this->tema_ibadah}\"\n";
        }
        if (!empty($this->pengkhotbah)) {
            $text .= "🎤 *Pengkhotbah:* {$this->pengkhotbah}\n";
        }
        $text .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $text .= "👥 *SUSUNAN PETUGAS / SERVICE CREW:*\n";

        $posisiLabels = PelayanTugas::posisiLabels();
        $grouped = $this->petugas->groupBy('posisi');

        foreach ($posisiLabels as $posKey => $posLabel) {
            if ($grouped->has($posKey)) {
                $names = $grouped->get($posKey)->pluck('nama_pelayan')->implode(', ');
                $text .= "• *{$posLabel}:* {$names}\n";
            }
        }

        if (!empty($this->catatan)) {
            $text .= "\n📝 *Catatan Khusus:* {$this->catatan}\n";
        }

        $text .= "\n_\"Layanilah seorang akan yang lain, sesuai dengan karunia yang telah diperoleh tiap-tiap orang sebagai pengurus yang baik dari kasih karunia Allah.\" (1 Petrus 4:10)_\n\n";
        $text .= "Tuhan Yesus Memberkati Pelayanan Kita Bersama! 🙏✨";

        return $text;
    }
}

