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
        // 1. Induk Rota Jadwal Pelayanan per tanggal & sesi ibadah
        Schema::create('jadwal_pelayanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_ibadah_id')->constrained('jadwal_ibadah')->cascadeOnDelete();
            $table->date('tanggal_pelayanan');
            $table->string('tema_ibadah', 200)->nullable();
            $table->string('pengkhotbah', 150)->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status_publikasi', ['draft', 'terbit'])->default('terbit');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Mencegah duplikasi induk jadwal pada ibadah dan tanggal yang sama
            $table->unique(['jadwal_ibadah_id', 'tanggal_pelayanan'], 'unique_sesi_ibadah_tanggal');
        });

        // 2. Daftar Petugas/Pelayan di setiap posisi
        Schema::create('pelayan_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_pelayanan_id')->constrained('jadwal_pelayanan')->cascadeOnDelete();
            $table->string('posisi', 50); // wl, singer_1, singer_2, keyboard, gitar_akustik, gitar_elektrik, bass, drum, multimedia, soundman, live_streaming, usher_1, usher_2, pendoa_syafaat, kolektan
            $table->foreignId('jemaat_id')->nullable()->constrained('jemaat')->nullOnDelete();
            $table->string('nama_pelayan', 150);
            $table->string('no_wa', 30)->nullable();
            $table->enum('status_konfirmasi', ['menunggu', 'bersedia', 'berhalangan'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('posisi');
            $table->index('nama_pelayan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelayan_tugas');
        Schema::dropIfExists('jadwal_pelayanan');
    }
};

