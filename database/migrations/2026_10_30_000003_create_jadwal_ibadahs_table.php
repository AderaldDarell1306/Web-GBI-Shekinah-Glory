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
        Schema::create('jadwal_ibadah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ibadah', 100);
            $table->string('kategori', 50)->default('Ibadah Utama');
            $table->string('hari', 30);
            $table->string('jam', 50);
            $table->string('lokasi_ruangan', 100);
            $table->text('keterangan')->nullable();
            $table->string('tipe_pelaksanaan', 50)->default('Onsite');
            $table->integer('urutan')->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_ibadah');
    }
};
