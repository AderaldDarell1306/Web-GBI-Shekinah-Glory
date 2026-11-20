<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wilayah', function (Blueprint $table) {
            if (!Schema::hasColumn('wilayah', 'wilayah_besar')) {
                $table->string('wilayah_besar', 50)->nullable()->after('nama_wilayah');
            }
            if (!Schema::hasColumn('wilayah', 'kode_prefix')) {
                $table->string('kode_prefix', 10)->nullable()->index()->after('kode_wilayah');
            }
        });
    }

    public function down(): void
    {
        Schema::table('wilayah', function (Blueprint $table) {
            $table->dropColumn(['wilayah_besar', 'kode_prefix']);
        });
    }
};

