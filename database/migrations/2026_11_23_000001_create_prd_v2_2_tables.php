<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for PRD v2.2.
     */
    public function up(): void
    {
        // 1. Modul Surat Ekspedisi: Tambah kolom lampiran & kode akses untuk jemaat
        if (Schema::hasTable('arsip_surat')) {
            Schema::table('arsip_surat', function (Blueprint $table) {
                if (!Schema::hasColumn('arsip_surat', 'lampiran')) {
                    $table->string('lampiran', 100)->nullable()->after('perihal');
                }
                if (!Schema::hasColumn('arsip_surat', 'kode_akses')) {
                    $table->string('kode_akses', 50)->nullable()->unique()->after('file_surat');
                }
            });
        }

        // 2. Modul Peminjaman Inventaris: Penyesuaian agar mendukung pengajuan publik
        if (Schema::hasTable('peminjaman_inventaris')) {
            Schema::table('peminjaman_inventaris', function (Blueprint $table) {
                if (!Schema::hasColumn('peminjaman_inventaris', 'nama_barang_custom')) {
                    $table->string('nama_barang_custom', 150)->nullable()->after('inventaris_id');
                }
                // Ubah status agar mendukung 'Menunggu Konfirmasi'
                $table->string('status', 50)->default('Menunggu Konfirmasi')->change();
            });
        }

        // 3. Modul Pendaftaran Layanan: Multi-File Upload
        if (Schema::hasTable('pendaftaran_layanan')) {
            Schema::table('pendaftaran_layanan', function (Blueprint $table) {
                if (!Schema::hasColumn('pendaftaran_layanan', 'berkas_dokumen')) {
                    $table->json('berkas_dokumen')->nullable()->after('data_tambahan');
                }
            });
        }

        // 4. Modul Presensi Mingguan & Keaktifan Pelayan Ibadah (PRD 2.4 B)
        if (!Schema::hasTable('presensi_pelayan')) {
            Schema::create('presensi_pelayan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('jadwal_pelayanan_id')->constrained('jadwal_pelayanan')->cascadeOnDelete();
                $table->foreignId('pelayan_tugas_id')->constrained('pelayan_tugas')->cascadeOnDelete();
                $table->foreignId('jemaat_id')->nullable()->constrained('jemaat')->nullOnDelete();
                $table->date('tanggal_ibadah');
                $table->enum('kehadiran', ['hadir', 'izin', 'sakit', 'alpa'])->default('hadir');
                $table->text('catatan')->nullable();
                $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index(['tanggal_ibadah', 'kehadiran']);
            });
        }

        // 5. Modul Rekap Kehadiran & Keaktifan Ibadah Mingguan / Tahunan Per Kaum (PRD 2.4 A)
        if (!Schema::hasTable('kehadiran_ibadah_mingguan')) {
            Schema::create('kehadiran_ibadah_mingguan', function (Blueprint $table) {
                $table->id();
                $table->date('tanggal_ibadah');
                $table->enum('jenis_ibadah', ['ibadah_raya', 'ibadah_cool'])->default('ibadah_raya');
                $table->integer('tahun')->default(2026);
                $table->integer('minggu_ke')->default(1);
                $table->integer('hadir_kaum_bapak')->default(0);
                $table->integer('hadir_kaum_ibu')->default(0);
                $table->integer('hadir_pemuda')->default(0);
                $table->integer('hadir_anak')->default(0);
                $table->integer('total_hadir')->default(0);
                $table->text('catatan')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index(['tahun', 'jenis_ibadah']);
                $table->index('tanggal_ibadah');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_ibadah_mingguan');
        Schema::dropIfExists('presensi_pelayan');

        if (Schema::hasTable('pendaftaran_layanan')) {
            Schema::table('pendaftaran_layanan', function (Blueprint $table) {
                $table->dropColumn('berkas_dokumen');
            });
        }

        if (Schema::hasTable('peminjaman_inventaris')) {
            Schema::table('peminjaman_inventaris', function (Blueprint $table) {
                $table->dropColumn('nama_barang_custom');
            });
        }

        if (Schema::hasTable('arsip_surat')) {
            Schema::table('arsip_surat', function (Blueprint $table) {
                $table->dropColumn(['lampiran', 'kode_akses']);
            });
        }
    }
};
