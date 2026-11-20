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
        Schema::create('jemaat', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk', 50)->nullable()->index();
            $table->string('nomor_induk', 50)->unique();
            $table->string('nama_lengkap', 150);
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('status_keluarga', 100)->nullable(); // SUAMI (KEPALA KELUARGA), ISTRI, ANAK, JANDA, DUDA, dll
            $table->enum('status_pernikahan', ['belum_menikah', 'menikah', 'duda', 'janda'])->default('belum_menikah');
            $table->string('status_baptis', 50)->nullable(); // BAPTIS, PENYERAHAN, BELUM
            $table->date('tanggal_baptis')->nullable();
            $table->string('nikah_masehi', 50)->nullable(); // SUDAH, BELUM
            $table->string('pendidikan', 100)->nullable(); // SD, SMP, SLTP, SMA, SLTA, D3, SARJANA, dll
            $table->string('pekerjaan', 150)->nullable(); // BURUH, IRT, SWASTA, dll
            $table->string('keahlian_khusus', 150)->nullable(); // MENYANYI, MASAK, PEMAIN MUSIK, dll
            $table->string('penghasilan_per_bulan', 100)->nullable();
            $table->text('lain_lain')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp', 30)->nullable();
            $table->string('email', 100)->nullable();
            $table->foreignId('wilayah_id')->nullable()->constrained('wilayah')->nullOnDelete();
            $table->enum('status_keanggotaan', ['aktif', 'tidak_aktif', 'pindah', 'meninggal'])->default('aktif');
            $table->date('tanggal_bergabung')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            // Indexes for fast searching
            $table->index('nama_lengkap');
            $table->index('no_kk');
            $table->index('no_hp');
            $table->index('status_keanggotaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jemaat');
    }
};
