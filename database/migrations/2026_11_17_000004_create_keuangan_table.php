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
        Schema::create('transaksi_keuangan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 50)->unique();
            $table->date('tanggal_transaksi');
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->string('kategori', 50); // persepuluhan, persembahan_raya, pembangunan, diakonia, operasional, ucapan_syukur, pembicara_tamu, lainnya
            $table->decimal('nominal', 15, 2);
            $table->string('metode_pembayaran', 30)->default('tunai'); // tunai, transfer, qris
            $table->string('sumber_penerima', 150)->nullable(); // Misal: Amplop Persepuluhan, Kolekte Sesi 1, Toko Bahan Bangunan
            $table->text('keterangan')->nullable();
            $table->string('no_bukti_nota', 100)->nullable();
            $table->string('lampiran_file')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('tanggal_transaksi');
            $table->index('jenis');
            $table->index('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_keuangan');
    }
};

