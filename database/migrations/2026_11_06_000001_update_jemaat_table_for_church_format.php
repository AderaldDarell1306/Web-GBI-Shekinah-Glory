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
        Schema::table('jemaat', function (Blueprint $table) {
            if (!Schema::hasColumn('jemaat', 'no_kk')) {
                $table->string('no_kk', 50)->nullable()->index()->after('id');
            }
            if (!Schema::hasColumn('jemaat', 'status_keluarga')) {
                $table->string('status_keluarga', 100)->nullable()->after('status_pernikahan');
            }
            if (!Schema::hasColumn('jemaat', 'nikah_masehi')) {
                $table->string('nikah_masehi', 50)->nullable()->after('tanggal_baptis');
            }
            if (!Schema::hasColumn('jemaat', 'pendidikan')) {
                $table->string('pendidikan', 100)->nullable()->after('nikah_masehi');
            }
            if (!Schema::hasColumn('jemaat', 'pekerjaan')) {
                $table->string('pekerjaan', 150)->nullable()->after('pendidikan');
            }
            if (!Schema::hasColumn('jemaat', 'keahlian_khusus')) {
                $table->string('keahlian_khusus', 150)->nullable()->after('pekerjaan');
            }
            if (!Schema::hasColumn('jemaat', 'penghasilan_per_bulan')) {
                $table->string('penghasilan_per_bulan', 100)->nullable()->after('keahlian_khusus');
            }
            if (!Schema::hasColumn('jemaat', 'lain_lain')) {
                $table->text('lain_lain')->nullable()->after('penghasilan_per_bulan');
            }
            if (!Schema::hasColumn('jemaat', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('lain_lain');
            }
        });

        Schema::table('jemaat', function (Blueprint $table) {
            $table->string('status_baptis', 50)->nullable()->default(null)->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->unsignedBigInteger('wilayah_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jemaat', function (Blueprint $table) {
            $table->dropColumn([
                'no_kk',
                'status_keluarga',
                'nikah_masehi',
                'pendidikan',
                'pekerjaan',
                'keahlian_khusus',
                'penghasilan_per_bulan',
                'lain_lain',
                'keterangan',
            ]);
        });
    }
};

