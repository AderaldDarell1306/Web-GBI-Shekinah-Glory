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
        // 1. Tambahkan wilayah_id pada users untuk penugasan Ketua COOL
        if (!Schema::hasColumn('users', 'wilayah_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('wilayah_id')->nullable()->after('role')->constrained('wilayah')->nullOnDelete();
            });
        }

        // 2. Tabel Pertemuan COOL
        Schema::create('pertemuan_cool', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wilayah_id')->constrained('wilayah')->cascadeOnDelete();
            $table->string('nama_cool', 100)->nullable();
            $table->date('tanggal_pertemuan');
            $table->string('jam_mulai', 30)->default('18:00 WITA');
            $table->string('tempat', 150)->nullable();
            $table->string('pemimpin_sharing', 150)->nullable();
            $table->string('tema_renungan', 200)->nullable();
            $table->text('catatan')->nullable();
            $table->integer('total_hadir')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('tanggal_pertemuan');
            $table->index('wilayah_id');
        });

        // 3. Detail Kehadiran Jemaat di Pertemuan COOL
        Schema::create('kehadiran_cool_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertemuan_cool_id')->constrained('pertemuan_cool')->cascadeOnDelete();
            $table->foreignId('jemaat_id')->constrained('jemaat')->cascadeOnDelete();
            $table->enum('status_kehadiran', ['hadir', 'izin', 'sakit', 'alpa'])->default('hadir');
            $table->string('keterangan', 150)->nullable();
            $table->timestamps();

            $table->unique(['pertemuan_cool_id', 'jemaat_id'], 'cool_pertemuan_jemaat_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_cool_detail');
        Schema::dropIfExists('pertemuan_cool');
        if (Schema::hasColumn('users', 'wilayah_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['wilayah_id']);
                $table->dropColumn('wilayah_id');
            });
        }
    }
};

