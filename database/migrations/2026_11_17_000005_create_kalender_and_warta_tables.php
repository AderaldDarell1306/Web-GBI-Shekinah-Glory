<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Kalender Liturgi & Acara Tahunan
        Schema::create('kalender_acara', function (Blueprint $table) {
            $table->id();
            $table->string('judul_acara', 150);
            $table->string('kategori', 50)->default('hari_raya'); // hari_raya, ibadah_khusus, retret_kkr, rapat_majelis, kegiatan_pemuda, doa_puasa, lainnya
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('waktu', 50)->nullable(); // e.g. "09:00 - 12:00 WITA" atau "Sepanjang Hari"
            $table->string('lokasi', 150)->default('GBI Shekinah Glory Namosain');
            $table->text('deskripsi')->nullable();
            $table->string('warna_label', 20)->default('#775a19'); // warna badge di kalender
            $table->boolean('tampilkan_di_web')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('tanggal_mulai');
            $table->index('kategori');
        });

        // 2. Warta Jemaat Digital
        Schema::create('warta_jemaat', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_warta'); // Tanggal Minggu Warta
            $table->string('edisi', 100); // e.g. "Edisi Minggu, 22 November 2026"
            $table->string('tema_mingguan', 200)->nullable();
            $table->string('ayat_tema', 150)->nullable();
            $table->longText('ringkasan_khotbah')->nullable();
            $table->text('pokok_doa')->nullable();
            $table->text('pengumuman_kegiatan')->nullable();
            $table->text('laporan_keuangan_ringkas')->nullable();
            $table->string('file_pdf')->nullable(); // Upload warta cetak jika ada
            $table->enum('status', ['draft', 'terbit'])->default('terbit');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('tanggal_warta');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warta_jemaat');
        Schema::dropIfExists('kalender_acara');
    }
};

