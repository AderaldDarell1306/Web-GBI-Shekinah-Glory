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
        Schema::create('arsip_surat', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_surat', ['masuk', 'keluar']);
            $table->string('nomor_surat', 100);
            $table->date('tanggal_surat');
            $table->date('tanggal_terima_kirim')->nullable();
            $table->string('pengirim', 150); // e.g. BPD GBI NTT, Kelurahan Namosain, atau Majelis GBI
            $table->string('tujuan_penerima', 150); // e.g. Gembala Sidang, Panitia Paskah, Lurah Alak
            $table->string('perihal', 255);
            $table->string('kategori_surat', 50)->default('umum'); // undangan, dinas_pemerintah, sinode, rekomendasi, pastoral, proposal, translokasi, lainnya
            $table->integer('tahun_kalender')->default(2026);
            $table->string('file_surat')->nullable(); // PDF / scan gambar berkas
            $table->text('ringkasan_isi')->nullable();
            $table->enum('status', ['tersimpan', 'proses', 'selesai'])->default('tersimpan');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('jenis_surat');
            $table->index('tahun_kalender');
            $table->index('tanggal_surat');
            $table->index('kategori_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_surat');
    }
};

