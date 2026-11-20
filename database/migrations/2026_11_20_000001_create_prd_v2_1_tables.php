<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for PRD v2.1.
     */
    public function up(): void
    {
        // 1. Data Majelis Pelayanan (PRD 2.4)
        if (!Schema::hasTable('majelis')) {
            Schema::create('majelis', function (Blueprint $table) {
                $table->id();
                $table->foreignId('jemaat_id')->nullable()->constrained('jemaat')->nullOnDelete();
                $table->string('nama_majelis', 150);
                $table->string('jabatan', 100)->default('Majelis Jemaat'); // Penatua, Diaken, Majelis Jemaat, Majelis Pembina, dll.
                $table->string('bidang_pelayanan', 150)->nullable(); // Liturgi & Ibadah, Diakonia, Sarpras, Penggembalaan
                $table->string('periode', 50)->nullable(); // contoh: "2024 - 2029"
                $table->string('no_sk', 100)->nullable();
                $table->string('no_hp', 30)->nullable();
                $table->enum('status', ['aktif', 'purna_tugas'])->default('aktif');
                $table->text('catatan')->nullable();
                $table->timestamps();

                $table->index('nama_majelis');
                $table->index('status');
            });
        }

        // 2. Tambah Kolom Buletin ke Warta Jemaat (PRD 2.5)
        if (Schema::hasTable('warta_jemaat')) {
            Schema::table('warta_jemaat', function (Blueprint $table) {
                if (!Schema::hasColumn('warta_jemaat', 'buletin_tipe')) {
                    $table->enum('buletin_tipe', ['none', 'upload', 'link', 'manual'])->default('none')->after('file_pdf');
                }
                if (!Schema::hasColumn('warta_jemaat', 'buletin_judul')) {
                    $table->string('buletin_judul', 200)->nullable()->after('buletin_tipe');
                }
                if (!Schema::hasColumn('warta_jemaat', 'buletin_file')) {
                    $table->string('buletin_file', 255)->nullable()->after('buletin_judul');
                }
                if (!Schema::hasColumn('warta_jemaat', 'buletin_link')) {
                    $table->string('buletin_link', 500)->nullable()->after('buletin_file');
                }
                if (!Schema::hasColumn('warta_jemaat', 'buletin_content')) {
                    $table->longText('buletin_content')->nullable()->after('buletin_link');
                }
            });
        }

        // 3. Modul 9: Inventaris Gereja (PRD 3.1)
        if (!Schema::hasTable('inventaris')) {
            Schema::create('inventaris', function (Blueprint $table) {
                $table->id();
                $table->string('nomor', 50); // No.
                $table->string('jenis_inventaris', 100); // Jenis Inv. (Elektronik, Audio, Musik, Mebel, dll)
                $table->string('kode_inventaris', 100)->unique(); // Kode Inv. (e.g. INV-KTR-001)
                $table->string('nama_barang', 150);
                $table->enum('lokasi', ['Kantor', 'Gereja', 'Gudang'])->default('Gereja'); // Lokasi wajib: Kantor, Gereja, Gudang
                $table->integer('jumlah')->default(1);
                $table->string('satuan', 30)->default('Unit');
                $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik');
                $table->enum('status_ketersediaan', ['Tersedia', 'Dipinjam', 'Dalam Perbaikan', 'Dihapuskan'])->default('Tersedia');
                $table->date('tanggal_perolehan')->nullable();
                $table->text('keterangan')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index('kode_inventaris');
                $table->index('lokasi');
                $table->index('jenis_inventaris');
            });
        }

        // 4. Modul 9: Laporan Pinjam Pakai Inventaris Gereja (PRD 3.1)
        if (!Schema::hasTable('peminjaman_inventaris')) {
            Schema::create('peminjaman_inventaris', function (Blueprint $table) {
                $table->id();
                $table->string('kode_peminjaman', 50)->unique();
                $table->foreignId('inventaris_id')->constrained('inventaris')->cascadeOnDelete();
                $table->string('nama_peminjam', 150);
                $table->string('no_kontak', 30)->nullable();
                $table->string('instansi_kegiatan', 150); // Acara / Rayon / Tujuan Pemakaian
                $table->date('tanggal_pinjam');
                $table->date('tanggal_rencana_kembali');
                $table->date('tanggal_kembali_aktual')->nullable();
                $table->integer('jumlah_pinjam')->default(1);
                $table->enum('status', ['Sedang Dipinjam', 'Sudah Dikembalikan', 'Terlambat'])->default('Sedang Dipinjam');
                $table->string('kondisi_sebelum', 100)->default('Baik');
                $table->string('kondisi_sesudah', 100)->nullable();
                $table->text('catatan')->nullable();
                $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index('kode_peminjaman');
                $table->index('status');
                $table->index('tanggal_pinjam');
            });
        }

        // 5. Modul 10: Dokumentasi Foto Kegiatan Gereja (PRD 3.2)
        if (!Schema::hasTable('dokumentasi_foto')) {
            Schema::create('dokumentasi_foto', function (Blueprint $table) {
                $table->id();
                $table->string('judul', 200);
                $table->string('kategori_kegiatan', 100)->default('Ibadah Raya'); // Ibadah Raya, Doa Semalam Suntuk, Retret, Baksos, Pemuda, SM
                $table->date('tanggal_kegiatan');
                $table->string('file_foto', 255);
                $table->text('deskripsi')->nullable();
                $table->boolean('tampilkan_di_galeri')->default(true);
                $table->integer('urutan')->default(0);
                $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index('kategori_kegiatan');
                $table->index('tanggal_kegiatan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasi_foto');
        Schema::dropIfExists('peminjaman_inventaris');
        Schema::dropIfExists('inventaris');

        if (Schema::hasTable('warta_jemaat')) {
            Schema::table('warta_jemaat', function (Blueprint $table) {
                $table->dropColumn(['buletin_tipe', 'buletin_judul', 'buletin_file', 'buletin_link', 'buletin_content']);
            });
        }

        Schema::dropIfExists('majelis');
    }
};
