<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JadwalPelayanan;
use App\Models\WebsiteContent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppReminderCommand extends Command
{
    /**
     * Nama dan signature command console.
     */
    protected $signature = 'jadwal:reminder-wa {--force : Paksa kirim meskipun bukan hari Jumat}';

    /**
     * Deskripsi command console.
     */
    protected $description = 'Kirim pengingat jadwal pelayanan ibadah Minggu ke Grup WhatsApp Jemaat/Pelayan';

    /**
     * Eksekusi command console.
     */
    public function handle()
    {
        $this->info("Menjalankan pengingat jadwal pelayanan ibadah ke Grup WhatsApp...");

        $today = Carbon::today();
        // Cari hari Minggu terdekat
        $upcomingSunday = Carbon::today()->next(Carbon::SUNDAY);

        $schedules = JadwalPelayanan::with(['sesiIbadah', 'petugas'])
            ->whereDate('tanggal_pelayanan', $upcomingSunday)
            ->where('status_publikasi', 'terbit')
            ->get();

        if ($schedules->isEmpty()) {
            $this->warn("Tidak ada jadwal pelayanan yang terbit untuk hari Minggu terdekat ({$upcomingSunday->format('d/m/Y')}).");
            return 0;
        }

        $waGatewayUrl = WebsiteContent::where('key', 'wa_gateway_url')->value('value');
        $waGatewayToken = WebsiteContent::where('key', 'wa_gateway_token')->value('value');
        $waGroupId = WebsiteContent::where('key', 'wa_group_id')->value('value');

        foreach ($schedules as $rota) {
            $message = $rota->generateWhatsAppBroadcastText();
            $this->line("--------------------------------------------------");
            $this->info("Jadwal: {$rota->sesiIbadah->nama_ibadah} ({$rota->tanggal_pelayanan->format('d/m/Y')})");
            $this->line($message);

            // Jika API WhatsApp dikonfigurasi, kirim langsung via HTTP POST
            if (!empty($waGatewayUrl) && !empty($waGatewayToken) && !empty($waGroupId)) {
                try {
                    $response = Http::withHeaders([
                        'Authorization' => $waGatewayToken,
                    ])->post($waGatewayUrl, [
                        'target' => $waGroupId,
                        'message' => $message,
                    ]);

                    $this->info("Berhasil mengirim ke Grup WA ({$waGroupId}): HTTP " . $response->status());
                    Log::info("WhatsApp reminder sent to group {$waGroupId} for Rota ID {$rota->id}");
                } catch (\Exception $e) {
                    $this->error("Gagal mengirim ke Gateway WA: " . $e->getMessage());
                    Log::error("Failed sending WA reminder: " . $e->getMessage());
                }
            } else {
                $this->comment("[Simulasi / Manual Ready]: Token WhatsApp Gateway atau Group ID belum diisi di Pengaturan CMS. Pesan siap dikirim manual/via link.");
            }
        }

        $this->info("Pengingat WhatsApp selesai diproses.");
        return 0;
    }
}

