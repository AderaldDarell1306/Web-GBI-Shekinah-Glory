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
        // 1. Tabel Sakramen Baptis Air
        Schema::create('sakramen_baptis', function (Blueprint $table) {
            $table->id();
            $table->string('no_sertifikat', 100)->unique();
            $table->foreignId('jemaat_id')->nullable()->constrained('jemaat')->nullOnDelete();
            $table->string('nama_lengkap', 150);
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->date('tanggal_baptis');
            $table->string('tempat_baptis', 150)->default('GBI Shekinah Glory Namosain');
            $table->string('pendeta_pelayan', 150);
            $table->string('nama_ayah', 150)->nullable();
            $table->string('nama_ibu', 150)->nullable();
            $table->string('saksi', 150)->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 2. Tabel Sakramen Penyerahan Anak
        Schema::create('penyerahan_anak', function (Blueprint $table) {
            $table->id();
            $table->string('no_sertifikat', 100)->unique();
            $table->foreignId('jemaat_id')->nullable()->constrained('jemaat')->nullOnDelete();
            $table->string('nama_anak', 150);
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->date('tanggal_penyerahan');
            $table->string('tempat_penyerahan', 150)->default('GBI Shekinah Glory Namosain');
            $table->string('nama_ayah', 150);
            $table->string('nama_ibu', 150);
            $table->string('pendeta_pelayan', 150);
            $table->string('saksi', 150)->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 3. Tabel Pernikahan Kudus & Bimbingan Pra-Nikah
        Schema::create('pernikahan', function (Blueprint $table) {
            $table->id();
            $table->string('no_sertifikat', 100)->unique();
            $table->foreignId('pengantin_pria_jemaat_id')->nullable()->constrained('jemaat')->nullOnDelete();
            $table->string('pengantin_pria_nama', 150);
            $table->string('pengantin_pria_ayah', 150)->nullable();
            $table->string('pengantin_pria_ibu', 150)->nullable();
            $table->foreignId('pengantin_wanita_jemaat_id')->nullable()->constrained('jemaat')->nullOnDelete();
            $table->string('pengantin_wanita_nama', 150);
            $table->string('pengantin_wanita_ayah', 150)->nullable();
            $table->string('pengantin_wanita_ibu', 150)->nullable();
            $table->date('tanggal_pemberkatan');
            $table->string('tempat_pemberkatan', 150)->default('GBI Shekinah Glory Namosain');
            $table->string('pendeta_pemberkat', 150);
            $table->string('saksi_1', 150)->nullable();
            $table->string('saksi_2', 150)->nullable();
            $table->enum('status_bimbingan', ['belum', 'proses', 'selesai'])->default('selesai');
            $table->text('catatan_konseling')->nullable();
            $table->string('berkas_legalitas')->nullable(); // Upload file path
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 4. Tabel Surat Pastoral & Translokasi
        Schema::create('surat_pastoral', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat', 100)->unique();
            $table->enum('jenis_surat', ['surat_keterangan', 'surat_pindah', 'pengantar_keanggotaan']);
            $table->foreignId('jemaat_id')->nullable()->constrained('jemaat')->nullOnDelete();
            $table->string('nama_jemaat', 150);
            $table->date('tanggal_surat');
            $table->string('tujuan_gereja_instansi', 200)->nullable();
            $table->text('keperluan_alasan')->nullable();
            $table->string('penandatangan', 150)->default('Pdt. Markus Suryanata');
            $table->string('jabatan_penandatangan', 100)->default('Gembala Jemaat');
            $table->enum('status', ['draft', 'terbit'])->default('terbit');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 5. Tabel Pendaftaran Layanan Sakramen & Jemaat Baru Mandiri
        Schema::create('pendaftaran_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pendaftaran', 50)->unique();
            $table->enum('jenis_layanan', ['baptis', 'penyerahan_anak', 'nikah', 'jemaat_baru', 'pindah']);
            $table->string('nama_pemohon', 150);
            $table->string('no_wa', 30);
            $table->string('email', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->json('data_tambahan')->nullable();
            $table->enum('status', ['menunggu', 'diproses', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_layanan');
        Schema::dropIfExists('surat_pastoral');
        Schema::dropIfExists('pernikahan');
        Schema::dropIfExists('penyerahan_anak');
        Schema::dropIfExists('sakramen_baptis');
    }
};

