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
            if (!Schema::hasColumn('jemaat', 'nik')) {
                $table->string('nik', 20)->nullable()->index()->after('no_kk');
            }
            if (!Schema::hasColumn('jemaat', 'tipe_keanggotaan')) {
                $table->enum('tipe_keanggotaan', ['jemaat_tetap', 'simpatisan', 'mutasi'])->default('jemaat_tetap')->after('status_keanggotaan');
            }
            if (!Schema::hasColumn('jemaat', 'kategori_khusus')) {
                $table->string('kategori_khusus', 50)->nullable()->after('tipe_keanggotaan'); // janda, duda, yatim, piatu, yatim_piatu, lansia, umum
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jemaat', function (Blueprint $table) {
            $table->dropColumn(['nik', 'tipe_keanggotaan', 'kategori_khusus']);
        });
    }
};

