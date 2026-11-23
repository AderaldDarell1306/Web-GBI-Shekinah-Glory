@extends('layouts.admin')

@section('title', 'Data Jemaat - SIMJ GBI Shekinah Glory')
@section('page_title', 'Database Anggota Jemaat')

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-serif-display text-2xl font-bold text-primary">Daftar Data Jemaat Induk</h2>
            <p class="text-xs text-on-surface-variant mt-0.5">3 Wilayah Besar (Namosain, Rumah 7, Osmok) • 12 Sub-Wilayah • Terintegrasi Kode KK 6-Digit.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(Auth::user() && Auth::user()->canCrudJemaat())
            <!-- Download Template CSV -->
            <a href="{{ route('admin.jemaat.template') }}" class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white hover:bg-gray-50 text-secondary border border-secondary/30 font-bold text-xs shadow-sm transition" title="Download template CSV kosong">
                <span class="material-symbols-outlined text-[18px]">file_download</span>
                <span>Template CSV</span>
            </a>

            <!-- Tombol Buka Modal Import Excel / CSV -->
            <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden')" class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-primary-container text-white hover:bg-primary font-bold text-xs shadow-sm transition">
                <span class="material-symbols-outlined text-[18px]">upload_file</span>
                <span>Import Excel / CSV</span>
            </button>
            @endif

            <!-- Ekspor CSV -->
            <a href="{{ route('admin.jemaat.export', request()->query()) }}" class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white hover:bg-gray-50 text-primary border border-outline-variant font-semibold text-xs shadow-sm transition">
                <span class="material-symbols-outlined text-[18px]">download</span>
                <span>Ekspor CSV</span>
            </a>

            @if(Auth::user() && Auth::user()->canCrudJemaat())
            <!-- Tambah Baru -->
            <a href="{{ route('admin.jemaat.create') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-secondary hover:bg-secondary/90 text-white font-bold text-xs shadow-md transition">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                <span>Tambah Jemaat</span>
            </a>
            @endif
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- VISUALISASI GRAFIK & DEMOGRAFI JEMAAT (PRD v2.2 Tahap 5) -->
    <!-- ======================================================== -->
    <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-outline-variant/30 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-outline-variant/20 pb-3">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[24px]">analytics</span>
                <div>
                    <h3 class="font-serif-display text-lg font-bold text-primary">Demografi Jemaat</h3>
                    <p class="text-xs text-on-surface-variant">Kaum, Gender, Kepala Keluarga (KK), Status Baptis &amp; Penyerahan Anak, Pendidikan, serta Kategori Khusus.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-secondary bg-secondary-container/40 px-3 py-1 rounded-full">
                    Sensus Induk Terverifikasi
                </span>
            </div>
        </div>

        <!-- 5 Executive Indicator Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
            <!-- 1. Total Jemaat & Gender -->
            <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col justify-between">
                <div>
                    <span class="text-2xs font-bold text-outline uppercase tracking-wider block">Total Jiwa</span>
                    <span class="text-2xl font-bold font-serif-display text-primary mt-0.5 block">{{ number_format($demo['totalJemaat'] ?? $jemaats->total()) }}</span>
                </div>
                <div class="mt-2 pt-2 border-t border-outline-variant/20 flex items-center justify-between text-2xs">
                    <span class="text-blue-700 font-semibold">{{ $demo['gender']['pria'] ?? 0 }} Pria</span>
                    <span class="text-rose-700 font-semibold">{{ $demo['gender']['wanita'] ?? 0 }} Wanita</span>
                </div>
            </div>

            <!-- 2. Kepala Keluarga (KK) -->
            <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col justify-between">
                <div>
                    <span class="text-2xs font-bold text-outline uppercase tracking-wider block">Kepala Keluarga</span>
                    <span class="text-2xl font-bold font-serif-display text-primary mt-0.5 block">{{ number_format($demo['totalKk'] ?? 0) }}</span>
                </div>
                <div class="mt-2 pt-2 border-t border-outline-variant/20 flex items-center justify-between text-2xs text-outline">
                    <span>Rata-rata Jiwa</span>
                    <strong class="text-primary">{{ $demo['avgJiwaPerKk'] ?? 0 }} / KK</strong>
                </div>
            </div>

            <!-- 3. Status Baptis Air -->
            <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col justify-between">
                <div>
                    <span class="text-2xs font-bold text-outline uppercase tracking-wider block">Status Baptis</span>
                    <span class="text-2xl font-bold font-serif-display text-secondary mt-0.5 block">{{ $demo['baptis']['sudah'] ?? 0 }}</span>
                </div>
                <div class="mt-2 pt-2 border-t border-outline-variant/20 flex items-center justify-between text-2xs">
                    <span class="text-secondary font-bold">{{ $demo['pctBaptis'] ?? 0 }}% Sudah</span>
                    <span class="text-outline">{{ $demo['baptis']['belum'] ?? 0 }} Belum</span>
                </div>
            </div>

            <!-- 4. Penyerahan Anak -->
            <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col justify-between">
                <div>
                    <span class="text-2xs font-bold text-outline uppercase tracking-wider block">Penyerahan Anak</span>
                    <span class="text-2xl font-bold font-serif-display text-emerald-700 mt-0.5 block">{{ $demo['penyerahan']['sudah'] ?? 0 }}</span>
                </div>
                <div class="mt-2 pt-2 border-t border-outline-variant/20 flex items-center justify-between text-2xs">
                    <span class="text-emerald-700 font-bold">{{ $demo['pctPenyerahan'] ?? 0 }}% Sudah</span>
                    <span class="text-outline">{{ $demo['penyerahan']['belum'] ?? 0 }} Belum</span>
                </div>
            </div>

            <!-- 5. Kategori Khusus (Diakonia) -->
            <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col justify-between">
                <div>
                    <span class="text-2xs font-bold text-outline uppercase tracking-wider block">Kategori Khusus</span>
                    <span class="text-2xl font-bold font-serif-display text-amber-800 mt-0.5 block">{{ ($demo['diakonia']['lansia'] ?? 0) + ($demo['diakonia']['janda'] ?? 0) + ($demo['diakonia']['duda'] ?? 0) }}</span>
                </div>
                <div class="mt-2 pt-2 border-t border-outline-variant/20 flex items-center justify-between text-2xs">
                    <span class="text-amber-800 font-semibold">{{ $demo['diakonia']['lansia'] ?? 0 }} Lansia</span>
                    <span class="text-outline">{{ $demo['diakonia']['janda'] ?? 0 }} Janda • {{ $demo['diakonia']['duda'] ?? 0 }} Duda</span>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- SUB-PANEL 1: KAUM & GENDER -->
        <!-- ============================================== -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pt-1 border-t border-outline-variant/20">
            <!-- 1A: Donut Kaum -->
            <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/20 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-primary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-secondary">donut_large</span>
                        Proporsi Kaum
                    </span>
                    <span class="text-2xs text-outline font-mono">4 Kaum</span>
                </div>
                <div class="relative w-full h-44 flex items-center justify-center my-auto">
                    <canvas id="chartJemaatKaum"></canvas>
                </div>
                <div class="grid grid-cols-2 gap-1 text-[10px] mt-2 pt-2 border-t border-outline-variant/20">
                    <span class="text-blue-700 font-semibold">Bapak: {{ $demo['kategorial']['kaum_bapak'] ?? 0 }}</span>
                    <span class="text-rose-700 font-semibold">Ibu: {{ $demo['kategorial']['kaum_ibu'] ?? 0 }}</span>
                    <span class="text-amber-700 font-semibold">Pemuda: {{ $demo['kategorial']['pemuda_remaja'] ?? 0 }}</span>
                    <span class="text-emerald-700 font-semibold">Anak: {{ $demo['kategorial']['anak'] ?? 0 }}</span>
                </div>
            </div>

            <!-- 1B: Batang Kaum -->
            <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/20 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-primary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-secondary">bar_chart</span>
                        Jumlah Per Kaum
                    </span>
                    <span class="text-2xs text-outline font-mono">{{ $demo['totalJemaat'] ?? 0 }} Jiwa</span>
                </div>
                <div class="relative w-full h-44">
                    <canvas id="chartJemaatKaumBar"></canvas>
                </div>
                <div class="flex items-center justify-between text-[10px] text-outline mt-2 pt-2 border-t border-outline-variant/20">
                    <span class="font-bold text-primary">Bapak {{ $demo['kategorial']['kaum_bapak'] ?? 0 }} • Ibu {{ $demo['kategorial']['kaum_ibu'] ?? 0 }}</span>
                </div>
            </div>

            <!-- 2A: Donut Gender -->
            <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/20 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-primary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-secondary">donut_large</span>
                        Proporsi Gender
                    </span>
                    <span class="text-2xs text-outline font-mono">L vs P</span>
                </div>
                <div class="relative w-full h-44 flex items-center justify-center my-auto">
                    <canvas id="chartJemaatGender"></canvas>
                </div>
                <div class="grid grid-cols-2 gap-1 text-[10px] mt-2 pt-2 border-t border-outline-variant/20">
                    <span class="text-blue-700 font-semibold">Pria: {{ $demo['gender']['pria'] ?? 0 }} ({{ $demo['pctPria'] ?? 0 }}%)</span>
                    <span class="text-rose-700 font-semibold">Wanita: {{ $demo['gender']['wanita'] ?? 0 }} ({{ $demo['pctWanita'] ?? 0 }}%)</span>
                </div>
            </div>

            <!-- 2B: Batang Gender -->
            <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/20 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-primary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-secondary">bar_chart</span>
                        Jumlah Gender
                    </span>
                    <span class="text-2xs text-outline font-mono">Pria &amp; Wanita</span>
                </div>
                <div class="relative w-full h-44">
                    <canvas id="chartJemaatGenderBar"></canvas>
                </div>
                <div class="flex items-center justify-between text-[10px] text-outline mt-2 pt-2 border-t border-outline-variant/20">
                    <span class="font-bold text-primary">{{ $demo['gender']['pria'] ?? 0 }} Pria • {{ $demo['gender']['wanita'] ?? 0 }} Wanita</span>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- SUB-PANEL 2: KK, SAKRAMEN, PENDIDIKAN & DIAKONIA -->
        <!-- ============================================== -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pt-1 border-t border-outline-variant/20">
            <!-- 3A: Donut KK Per Wilayah -->
            <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/20 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-primary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-secondary">donut_large</span>
                        Proporsi KK Wilayah
                    </span>
                    <span class="text-2xs text-secondary font-bold">{{ $demo['totalKk'] ?? 0 }} KK</span>
                </div>
                <div class="relative w-full h-44 flex items-center justify-center my-auto">
                    <canvas id="chartJemaatKkDonut"></canvas>
                </div>
                <div class="grid grid-cols-3 gap-1 text-[9px] text-center mt-2 pt-2 border-t border-outline-variant/20">
                    <span>Namosain: {{ $demo['kkWilayah']['Namosain'] ?? 0 }}</span>
                    <span>R7: {{ $demo['kkWilayah']['Rumah 7'] ?? 0 }}</span>
                    <span>Osmok: {{ $demo['kkWilayah']['Osmok'] ?? 0 }}</span>
                </div>
            </div>

            <!-- 3B: Batang KK Per Wilayah -->
            <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/20 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-primary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-secondary">bar_chart</span>
                        Sebaran KK Wilayah
                    </span>
                    <span class="text-2xs text-outline font-mono">3 Wilayah</span>
                </div>
                <div class="relative w-full h-44">
                    <canvas id="chartJemaatKkBar"></canvas>
                </div>
                <div class="flex items-center justify-between text-[10px] text-outline mt-2 pt-2 border-t border-outline-variant/20">
                    <span class="font-bold text-primary">Namosain {{ $demo['kkWilayah']['Namosain'] ?? 0 }} • R7 {{ $demo['kkWilayah']['Rumah 7'] ?? 0 }} • Osmok {{ $demo['kkWilayah']['Osmok'] ?? 0 }}</span>
                </div>
            </div>

            <!-- 4A: Donut Baptis Air -->
            <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/20 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-primary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-secondary">donut_large</span>
                        Status Baptis
                    </span>
                    <span class="text-2xs text-secondary font-bold">{{ $demo['pctBaptis'] ?? 0 }}%</span>
                </div>
                <div class="relative w-full h-44 flex items-center justify-center my-auto">
                    <canvas id="chartJemaatBaptisDonut"></canvas>
                </div>
                <div class="flex items-center justify-between text-[10px] text-outline mt-2 pt-2 border-t border-outline-variant/20">
                    <span class="text-green-700 font-bold">Sudah: {{ $demo['baptis']['sudah'] ?? 0 }}</span>
                    <span>Belum: {{ $demo['baptis']['belum'] ?? 0 }}</span>
                </div>
            </div>

            <!-- 4B: Donut Penyerahan Anak -->
            <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/20 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-primary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-emerald-700">donut_large</span>
                        Penyerahan Anak
                    </span>
                    <span class="text-2xs text-emerald-700 font-bold">{{ $demo['pctPenyerahan'] ?? 0 }}%</span>
                </div>
                <div class="relative w-full h-44 flex items-center justify-center my-auto">
                    <canvas id="chartJemaatPenyerahanDonut"></canvas>
                </div>
                <div class="flex items-center justify-between text-[10px] text-outline mt-2 pt-2 border-t border-outline-variant/20">
                    <span class="text-emerald-700 font-bold">Sudah: {{ $demo['penyerahan']['sudah'] ?? 0 }}</span>
                    <span>Belum: {{ $demo['penyerahan']['belum'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- SUB-PANEL 3: PENDIDIKAN & KATEGORI KHUSUS -->
        <!-- ============================================== -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1 border-t border-outline-variant/20">
            <!-- 5: Batang Pendidikan -->
            <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/20 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-primary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-secondary">school</span>
                        Jenjang Pendidikan
                    </span>
                    <span class="text-2xs text-outline font-mono">{{ count($demo['pendidikanMap'] ?? []) }} Jenjang</span>
                </div>
                <div class="relative w-full h-44">
                    <canvas id="chartJemaatPendidikan"></canvas>
                </div>
                <div class="flex items-center justify-end text-[10px] text-outline mt-2 pt-2 border-t border-outline-variant/20">
                    <span class="font-bold text-primary">{{ count($demo['pendidikanMap'] ?? []) }} Tingkat Pendidikan</span>
                </div>
            </div>

            <!-- 6A: Batang Kategori Khusus -->
            <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/20 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-primary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-amber-700">bar_chart</span>
                        Rincian Kategori Khusus
                    </span>
                    <span class="text-2xs text-amber-800 font-bold">Diakonia</span>
                </div>
                <div class="relative w-full h-44">
                    <canvas id="chartJemaatDiakoniaBar"></canvas>
                </div>
                <div class="flex items-center justify-between text-[10px] text-outline mt-2 pt-2 border-t border-outline-variant/20">
                    <span>Lansia: {{ $demo['diakonia']['lansia'] ?? 0 }} • Janda: {{ $demo['diakonia']['janda'] ?? 0 }} • Duda: {{ $demo['diakonia']['duda'] ?? 0 }}</span>
                </div>
            </div>

            <!-- 6B: Donut Kategori Khusus -->
            <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/20 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-primary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-amber-700">donut_large</span>
                        Proporsi Kategori Khusus
                    </span>
                    <span class="text-2xs text-outline font-mono">Komposisi</span>
                </div>
                <div class="relative w-full h-44 flex items-center justify-center my-auto">
                    <canvas id="chartJemaatDiakoniaDonut"></canvas>
                </div>
                <div class="flex items-center justify-between text-[10px] text-outline mt-2 pt-2 border-t border-outline-variant/20">
                    <span>Lansia, Janda, Duda &amp; Yatim</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- REKAPITULASI KATEGORIAL PELAYANAN (BAPAK, IBU, PEMUDA, ANAK) -->
    <!-- ======================================================== -->
    <div class="space-y-2.5">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-secondary flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">groups</span>
                <span>Komposisi Pelayanan Kategorial Jemaat</span>
            </span>
            @if(request('kategorial'))
            <a href="{{ route('admin.jemaat.index', request()->except('kategorial', 'page')) }}" class="text-xs text-secondary font-semibold hover:underline flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">close</span>
                <span>Tampilkan Semua Kategorial</span>
            </a>
            @endif
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <!-- 1. Kaum Bapak -->
            <a href="{{ route('admin.jemaat.index', array_merge(request()->except('page'), ['kategorial' => 'bapak'])) }}" 
               class="p-4 rounded-2xl border transition-all duration-200 flex flex-col justify-between {{ request('kategorial') === 'bapak' ? 'bg-blue-600 text-white border-blue-600 shadow-md scale-[1.02]' : 'bg-white hover:bg-blue-50/50 border-outline-variant/30 text-primary hover:border-blue-300' }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold {{ request('kategorial') === 'bapak' ? 'text-blue-100' : 'text-blue-700' }} uppercase tracking-wider">Kaum Bapak</span>
                    <span class="w-8 h-8 rounded-xl {{ request('kategorial') === 'bapak' ? 'bg-white/20 text-white' : 'bg-blue-50 text-blue-700' }} flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">man</span>
                    </span>
                </div>
                <div class="mt-3">
                    <span class="text-2xl sm:text-3xl font-bold font-serif-display block leading-none">{{ $rekapKategorial['kaum_bapak'] ?? 0 }}</span>
                    <span class="text-2xs {{ request('kategorial') === 'bapak' ? 'text-blue-100' : 'text-outline' }} mt-1 block font-medium">Pria Dewasa / Kepala Keluarga</span>
                </div>
            </a>

            <!-- 2. Kaum Ibu -->
            <a href="{{ route('admin.jemaat.index', array_merge(request()->except('page'), ['kategorial' => 'ibu'])) }}" 
               class="p-4 rounded-2xl border transition-all duration-200 flex flex-col justify-between {{ request('kategorial') === 'ibu' ? 'bg-rose-600 text-white border-rose-600 shadow-md scale-[1.02]' : 'bg-white hover:bg-rose-50/50 border-outline-variant/30 text-primary hover:border-rose-300' }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold {{ request('kategorial') === 'ibu' ? 'text-rose-100' : 'text-rose-700' }} uppercase tracking-wider">Kaum Ibu</span>
                    <span class="w-8 h-8 rounded-xl {{ request('kategorial') === 'ibu' ? 'bg-white/20 text-white' : 'bg-rose-50 text-rose-700' }} flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">woman</span>
                    </span>
                </div>
                <div class="mt-3">
                    <span class="text-2xl sm:text-3xl font-bold font-serif-display block leading-none">{{ $rekapKategorial['kaum_ibu'] ?? 0 }}</span>
                    <span class="text-2xs {{ request('kategorial') === 'ibu' ? 'text-rose-100' : 'text-outline' }} mt-1 block font-medium">Wanita Dewasa / Ibu Rumah Tangga</span>
                </div>
            </a>

            <!-- 3. Pemuda & Remaja -->
            <a href="{{ route('admin.jemaat.index', array_merge(request()->except('page'), ['kategorial' => 'pemuda'])) }}" 
               class="p-4 rounded-2xl border transition-all duration-200 flex flex-col justify-between {{ request('kategorial') === 'pemuda' ? 'bg-amber-600 text-white border-amber-600 shadow-md scale-[1.02]' : 'bg-white hover:bg-amber-50/50 border-outline-variant/30 text-primary hover:border-amber-300' }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold {{ request('kategorial') === 'pemuda' ? 'text-amber-100' : 'text-amber-700' }} uppercase tracking-wider">Pemuda & Remaja</span>
                    <span class="w-8 h-8 rounded-xl {{ request('kategorial') === 'pemuda' ? 'bg-white/20 text-white' : 'bg-amber-50 text-amber-700' }} flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">diversity_1</span>
                    </span>
                </div>
                <div class="mt-3">
                    <span class="text-2xl sm:text-3xl font-bold font-serif-display block leading-none">{{ $rekapKategorial['pemuda_remaja'] ?? 0 }}</span>
                    <span class="text-2xs {{ request('kategorial') === 'pemuda' ? 'text-amber-100' : 'text-outline' }} mt-1 block font-medium">Pemuda (15–35 Thn Belum Nikah)</span>
                </div>
            </a>

            <!-- 4. Anak-anak ABI -->
            <a href="{{ route('admin.jemaat.index', array_merge(request()->except('page'), ['kategorial' => 'anak'])) }}" 
               class="p-4 rounded-2xl border transition-all duration-200 flex flex-col justify-between {{ request('kategorial') === 'anak' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md scale-[1.02]' : 'bg-white hover:bg-emerald-50/50 border-outline-variant/30 text-primary hover:border-emerald-300' }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold {{ request('kategorial') === 'anak' ? 'text-emerald-100' : 'text-emerald-700' }} uppercase tracking-wider">Anak-Anak (ABI)</span>
                    <span class="w-8 h-8 rounded-xl {{ request('kategorial') === 'anak' ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-700' }} flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">child_care</span>
                    </span>
                </div>
                <div class="mt-3">
                    <span class="text-2xl sm:text-3xl font-bold font-serif-display block leading-none">{{ $rekapKategorial['anak'] ?? 0 }}</span>
                    <span class="text-2xs {{ request('kategorial') === 'anak' ? 'text-emerald-100' : 'text-outline' }} mt-1 block font-medium">Anak Usia 0–14 Tahun</span>
                </div>
            </a>
        </div>
    </div>

    <!-- 3 WILAYAH BESAR FILTER TABS -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-outline-variant/30 space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-secondary flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">domain</span>
                <span>Pilih Wilayah Pelayanan</span>
            </span>
            @if(request('wilayah_besar') || request('wilayah_id') || request('kode_keluarga'))
            <a href="{{ route('admin.jemaat.index', request()->except('wilayah_besar', 'wilayah_id', 'kode_keluarga', 'page')) }}" class="text-xs text-secondary font-semibold hover:underline flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">close</span>
                <span>Tampilkan Semua Wilayah</span>
            </a>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2.5">
            <!-- Semua Wilayah -->
            <a href="{{ route('admin.jemaat.index', request()->except('wilayah_besar', 'wilayah_id', 'kode_keluarga', 'page')) }}" class="p-3 rounded-xl border text-xs font-bold transition flex items-center justify-between {{ !request('wilayah_besar') && !request('wilayah_id') ? 'bg-primary text-white border-primary shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container border-outline-variant/30' }}">
                <span>Semua Wilayah</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ !request('wilayah_besar') && !request('wilayah_id') ? 'bg-white/20 text-white' : 'bg-white text-primary' }}">
                    {{ $jemaats->total() }}
                </span>
            </a>

            <!-- 1. Namosain -->
            <a href="{{ route('admin.jemaat.index', array_merge(request()->except('wilayah_id', 'kode_keluarga', 'page'), ['wilayah_besar' => 'Namosain'])) }}" class="p-3 rounded-xl border text-xs font-bold transition flex items-center justify-between {{ request('wilayah_besar') === 'Namosain' ? 'bg-primary text-white border-primary shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container border-outline-variant/30' }}">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-md bg-secondary-container text-secondary flex items-center justify-center text-[10px] font-mono">1</span>
                    <span>Namosain (2 Sub)</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('wilayah_besar') === 'Namosain' ? 'bg-white/20 text-white' : 'bg-white text-primary' }}">
                    {{ $rekapWilayahBesar['Namosain'] ?? 0 }}
                </span>
            </a>

            <!-- 2. Rumah 7 -->
            <a href="{{ route('admin.jemaat.index', array_merge(request()->except('wilayah_id', 'kode_keluarga', 'page'), ['wilayah_besar' => 'Rumah 7'])) }}" class="p-3 rounded-xl border text-xs font-bold transition flex items-center justify-between {{ request('wilayah_besar') === 'Rumah 7' ? 'bg-primary text-white border-primary shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container border-outline-variant/30' }}">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-md bg-secondary-container text-secondary flex items-center justify-center text-[10px] font-mono">2</span>
                    <span>Rumah 7 (4 Sub)</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('wilayah_besar') === 'Rumah 7' ? 'bg-white/20 text-white' : 'bg-white text-primary' }}">
                    {{ $rekapWilayahBesar['Rumah 7'] ?? 0 }}
                </span>
            </a>

            <!-- 3. Osmok -->
            <a href="{{ route('admin.jemaat.index', array_merge(request()->except('wilayah_id', 'kode_keluarga', 'page'), ['wilayah_besar' => 'Osmok'])) }}" class="p-3 rounded-xl border text-xs font-bold transition flex items-center justify-between {{ request('wilayah_besar') === 'Osmok' ? 'bg-primary text-white border-primary shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container border-outline-variant/30' }}">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-md bg-secondary-container text-secondary flex items-center justify-center text-[10px] font-mono">3</span>
                    <span>Osmok (6 Sub)</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('wilayah_besar') === 'Osmok' ? 'bg-white/20 text-white' : 'bg-white text-primary' }}">
                    {{ $rekapWilayahBesar['Osmok'] ?? 0 }}
                </span>
            </a>
        </div>

        <!-- Sub-Wilayah Filter Strip (Pills) -->
        <div class="flex items-center gap-2 overflow-x-auto pt-2 border-t border-outline-variant/20 scrollbar-none">
            <span class="text-[11px] font-bold text-outline uppercase tracking-wider shrink-0">Sub-Wilayah:</span>
            @foreach($wilayahList as $w)
                @if(!request('wilayah_besar') || request('wilayah_besar') === $w->wilayah_besar)
                <a href="{{ route('admin.jemaat.index', array_merge(request()->except('page'), ['wilayah_id' => $w->id])) }}" class="px-3 py-1 rounded-full text-xs font-semibold shrink-0 transition flex items-center gap-1.5 {{ request('wilayah_id') == $w->id ? 'bg-secondary text-white shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container border border-outline-variant/30' }}">
                    <span class="font-mono text-[10px] font-bold opacity-75">{{ $w->kode_prefix }}</span>
                    <span>{{ $w->nama_wilayah }}</span>
                </a>
                @endif
            @endforeach
        </div>

        @if(request('kode_keluarga'))
        <div class="p-2.5 rounded-xl bg-secondary-container/40 border border-secondary/30 flex items-center justify-between text-xs">
            <span class="text-secondary font-bold flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">family_restroom</span>
                <span>Menampilkan anggota keluarga dengan Kode KK: <strong>{{ request('kode_keluarga') }}</strong></span>
            </span>
            <a href="{{ route('admin.jemaat.index', request()->except('kode_keluarga', 'page')) }}" class="text-primary font-bold hover:underline">Hapus Filter Keluarga</a>
        </div>
        @endif
    </div>

    <!-- Filter & Search Toolbar Card -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-outline-variant/30">
        <form method="GET" action="{{ route('admin.jemaat.index') }}" class="space-y-4">
            @if(request('wilayah_besar'))
                <input type="hidden" name="wilayah_besar" value="{{ request('wilayah_besar') }}">
            @endif
            @if(request('wilayah_id'))
                <input type="hidden" name="wilayah_id" value="{{ request('wilayah_id') }}">
            @endif
            @if(request('kategorial'))
                <input type="hidden" name="kategorial" value="{{ request('kategorial') }}">
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
                <!-- Search Box (5 cols) -->
                <div class="lg:col-span-5 relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px]">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, No KK (310101...), pekerjaan, keahlian..." class="w-full pl-11 pr-4 py-2.5 rounded-xl bg-surface-container-low text-xs border border-transparent focus:border-secondary focus:bg-white focus:outline-none transition">
                </div>

                <!-- Filter Gender (2 cols) -->
                <div class="lg:col-span-2">
                    <select name="gender" class="w-full px-3 py-2.5 rounded-xl bg-surface-container-low text-xs border border-transparent focus:border-secondary focus:bg-white focus:outline-none transition">
                        <option value="">Semua JK</option>
                        <option value="L" {{ request('gender') === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ request('gender') === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>

                <!-- Filter Status Baptis (2 cols) -->
                <div class="lg:col-span-2">
                    <select name="status_baptis" class="w-full px-3 py-2.5 rounded-xl bg-surface-container-low text-xs border border-transparent focus:border-secondary focus:bg-white focus:outline-none transition">
                        <option value="">Baptis / Penyerahan</option>
                        <option value="BAPTIS" {{ request('status_baptis') === 'BAPTIS' ? 'selected' : '' }}>BAPTIS</option>
                        <option value="PENYERAHAN" {{ request('status_baptis') === 'PENYERAHAN' ? 'selected' : '' }}>PENYERAHAN</option>
                        <option value="BELUM" {{ request('status_baptis') === 'BELUM' ? 'selected' : '' }}>BELUM</option>
                    </select>
                </div>

                <!-- Filter Status Keanggotaan (2 cols) -->
                <div class="lg:col-span-2">
                    <select name="status" class="w-full px-3 py-2.5 rounded-xl bg-surface-container-low text-xs border border-transparent focus:border-secondary focus:bg-white focus:outline-none transition">
                        <option value="">Status Anggota</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak_aktif" {{ request('status') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="pindah" {{ request('status') === 'pindah' ? 'selected' : '' }}>Pindah</option>
                        <option value="meninggal" {{ request('status') === 'meninggal' ? 'selected' : '' }}>Meninggal</option>
                    </select>
                </div>

                <!-- Buttons (1 col) -->
                <div class="lg:col-span-1 flex items-center gap-1.5">
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-primary text-white text-xs font-bold hover:bg-primary-container transition flex items-center justify-center" title="Terapkan Filter">
                        <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                    </button>
                    @if(request()->hasAny(['search', 'status', 'gender', 'status_baptis', 'wilayah_id', 'wilayah_besar', 'kode_keluarga', 'kategorial']))
                    <a href="{{ route('admin.jemaat.index') }}" class="p-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-outline text-xs flex items-center justify-center transition" title="Reset Filter">
                        <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- ======================================================== -->
    <!-- DATA JEMAAT TABLE CARD DENGAN TOGGLE MODE EXCEL SPREADSHEET -->
    <!-- ======================================================== -->
    <div id="jemaatTableContainer" class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden transition-all duration-300">
        <!-- Top Toolbar Card: Info, Mode Switcher & Pagination Size -->
        <div class="p-4 border-b border-outline-variant/20 flex flex-wrap items-center justify-between gap-3 text-xs bg-white">
            <div class="flex items-center gap-3">
                <span class="font-bold text-primary text-sm">Total Data: {{ $jemaats->total() }} jemaat</span>
                @if(request('kategorial'))
                <span class="px-2.5 py-1 rounded-full bg-secondary-container text-secondary text-2xs font-bold flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">filter_list</span>
                    <span>Filter: {{ ucfirst(request('kategorial')) }}</span>
                </span>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <!-- TOMBOL PENGUBAH TAMPILAN: MODERN VS EXCEL SPREADSHEET -->
                <div class="inline-flex items-center p-1 rounded-xl bg-surface-container border border-outline-variant/30 text-xs">
                    <button type="button" 
                            id="btnModeModern" 
                            onclick="setJemaatLayout('modern')" 
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-xs bg-white text-primary">
                        <span class="material-symbols-outlined text-[16px]">view_list</span>
                        <span>Mode Standar</span>
                    </button>
                    <button type="button" 
                            id="btnModeExcel" 
                            onclick="setJemaatLayout('excel')" 
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-on-surface-variant hover:text-emerald-700 transition">
                        <span class="material-symbols-outlined text-[16px] text-emerald-600">table_chart</span>
                        <span class="font-bold text-emerald-800">Tampilan Lembar Excel</span>
                    </button>
                </div>

                <div class="flex items-center gap-1.5 border-l border-outline-variant/30 pl-3">
                    <span class="text-outline text-2xs">Per hal:</span>
                    <select onchange="window.location.href = this.value" class="rounded-lg border border-outline-variant/40 px-2 py-1 text-xs bg-white">
                        @foreach([10, 25, 50, 100] as $size)
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}" {{ $jemaats->perPage() == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- EXCEL RIBBON & FORMULA BAR (HANYA AKTIF SAAT MODE EXCEL) -->
        <div id="excelHeaderRibbon" class="hidden bg-[#107c41] text-white px-4 py-2 flex flex-wrap items-center justify-between text-xs border-b border-[#0b5a2f]">
            <div class="flex items-center gap-2.5 font-mono">
                <span class="material-symbols-outlined text-[18px]">table_view</span>
                <span class="font-bold tracking-wide">DATA_JEMAAT_GBI_SHEKINAH_GLORY.XLSX</span>
                <span class="px-2 py-0.5 rounded bg-white/20 text-[10px] font-sans">Lembar Kerja Aktif</span>
            </div>
            <div class="flex items-center gap-3 text-2xs text-emerald-100 font-sans">
                <span>13 Kolom Sesuai File Asli</span>
                <span>•</span>
                <span>Format Sel Grid Excel</span>
            </div>
        </div>

        <div id="excelFormulaBar" class="hidden bg-[#f3f2f1] border-b border-gray-300 px-4 py-1.5 flex items-center gap-3 text-xs font-mono">
            <span class="font-bold text-gray-500 bg-white border border-gray-300 px-2 py-0.5 rounded text-[10px] shadow-2xs">fx</span>
            <span class="text-gray-600 text-[11px] truncate select-none">=DATABASE_JEMAAT(Kolom_A:KK, Kolom_B:NAMA, Kolom_C:TTL, Kolom_D:JK, Kolom_E:STATUS, Kolom_F:BAPTIS, Kolom_G:NIKAH, Kolom_H:PENDIDIKAN, Kolom_I:PEKERJAAN...)</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse" id="jemaatMainTable">
                <thead>
                    <!-- BARIS HURUF KOLOM EXCEL (A - N) HANYA SAAT MODE EXCEL -->
                    <tr id="excelColLettersRow" class="hidden bg-[#e1dfdd] text-[#323130] font-mono text-[10px] text-center border-b border-gray-300 select-none">
                        <th class="py-1 px-2 border-r border-gray-300 bg-[#edebe9] w-10"></th>
                        <th class="py-1 px-3 border-r border-gray-300 font-bold">A</th>
                        <th class="py-1 px-3 border-r border-gray-300 font-bold">B</th>
                        <th class="py-1 px-3 border-r border-gray-300 font-bold">C</th>
                        <th class="py-1 px-3 border-r border-gray-300 font-bold">D</th>
                        <th class="py-1 px-3 border-r border-gray-300 font-bold">E</th>
                        <th class="py-1 px-3 border-r border-gray-300 font-bold">F</th>
                        <th class="py-1 px-3 border-r border-gray-300 font-bold">G</th>
                        <th class="py-1 px-3 border-r border-gray-300 font-bold">H</th>
                        <th class="py-1 px-3 border-r border-gray-300 font-bold">I</th>
                        <th class="py-1 px-3 border-r border-gray-300 font-bold">J</th>
                        <th class="py-1 px-3 border-r border-gray-300 font-bold">K</th>
                        <th class="py-1 px-3 border-r border-gray-300 font-bold">L</th>
                        <th class="py-1 px-3 border-r border-gray-300 font-bold">M</th>
                        <th class="py-1 px-3 text-right">AKSI</th>
                    </tr>

                    <!-- HEADER NAMA KOLOM -->
                    <tr class="table-header-row bg-surface-container-low text-on-surface-variant border-b border-outline-variant/30 font-bold uppercase tracking-wider text-[11px]">
                        <th class="excel-row-num-cell hidden py-2 px-2 text-center border-r border-gray-300 font-mono text-gray-500 w-10">#</th>
                        <th class="col-cell py-3.5 px-4 whitespace-nowrap">KK / NIK</th>
                        <th class="col-cell py-3.5 px-4 whitespace-nowrap">Nama Lengkap</th>
                        <th class="col-cell py-3.5 px-4 whitespace-nowrap">TTL</th>
                        <th class="col-cell py-3.5 px-4 whitespace-nowrap text-center">JK</th>
                        <th class="col-cell py-3.5 px-4 whitespace-nowrap">Status Keluarga</th>
                        <th class="col-cell py-3.5 px-4 whitespace-nowrap">Penyerahan / Baptisan</th>
                        <th class="col-cell py-3.5 px-4 whitespace-nowrap">Nikah Masehi</th>
                        <th class="col-cell py-3.5 px-4 whitespace-nowrap">Pendidikan</th>
                        <th class="col-cell py-3.5 px-4 whitespace-nowrap">Pekerjaan</th>
                        <th class="col-cell py-3.5 px-4 whitespace-nowrap">Keahlian Khusus</th>
                        <th class="col-cell py-3.5 px-4 whitespace-nowrap">Penghasilan/Bln</th>
                        <th class="col-cell py-3.5 px-4 whitespace-nowrap">Lain-Lain</th>
                        <th class="col-cell py-3.5 px-4 whitespace-nowrap">Keterangan</th>
                        <th class="col-cell py-3.5 px-4 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse($jemaats as $index => $j)
                    <tr class="table-data-row hover:bg-surface-container-low/50 transition">
                        <!-- NOMOR BARIS EXCEL (1, 2, 3...) -->
                        <td class="excel-row-num-cell hidden py-2 px-2 text-center border-r border-b border-gray-300 font-mono text-gray-500 bg-[#f3f2f1] select-none text-[11px]">
                            {{ ($jemaats->currentPage() - 1) * $jemaats->perPage() + $loop->iteration }}
                        </td>

                        <!-- A. KK / NIK -->
                        <td class="col-cell py-3 px-4 whitespace-nowrap">
                            <div class="flex items-center gap-1.5 font-mono font-bold text-primary text-xs">
                                <span>{{ $j->no_kk ?? $j->nomor_induk }}</span>
                                @if($j->kode_keluarga)
                                <a href="{{ route('admin.jemaat.index', ['kode_keluarga' => $j->kode_keluarga]) }}" class="px-1 py-0.5 rounded bg-secondary-container/60 text-secondary text-[9px] font-bold hover:bg-secondary hover:text-white transition" title="Filter satu keluarga">
                                    Kel. {{ $j->kode_keluarga }}
                                </a>
                                @endif
                            </div>
                            <span class="text-[10px] text-secondary block font-semibold sub-wilayah-tag">
                                {{ $j->wilayah?->nama_wilayah ?? ($j->sub_wilayah_label ?? '-') }}
                            </span>
                        </td>

                        <!-- B. NAMA -->
                        <td class="col-cell py-3 px-4 whitespace-nowrap">
                            <a href="{{ route('admin.jemaat.show', $j->id) }}" class="font-bold text-primary hover:text-secondary text-xs block">
                                {{ $j->nama_lengkap }}
                            </a>
                        </td>

                        <!-- C. TTL -->
                        <td class="col-cell py-3 px-4 whitespace-nowrap text-on-surface text-xs">
                            {{ $j->ttl ?: '-' }}
                        </td>

                        <!-- D. JK -->
                        <td class="col-cell py-3 px-4 whitespace-nowrap text-center">
                            <span class="jk-badge w-5 h-5 rounded-full {{ $j->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }} inline-flex items-center justify-center font-bold text-[10px]">
                                {{ $j->jenis_kelamin }}
                            </span>
                        </td>

                        <!-- E. STATUS KELUARGA -->
                        <td class="col-cell py-3 px-4 whitespace-nowrap text-xs">
                            <span class="status-kel-tag px-2 py-0.5 rounded bg-surface-container font-semibold text-primary text-[11px]">
                                {{ $j->status_keluarga ?? ucfirst(str_replace('_', ' ', $j->status_pernikahan)) }}
                            </span>
                        </td>

                        <!-- F. PENYERAHAN / BAPTISAN -->
                        <td class="col-cell py-3 px-4 whitespace-nowrap text-xs font-semibold">
                            @if(strtoupper($j->status_baptis) === 'BAPTIS')
                                <span class="text-green-700 font-bold">BAPTIS</span>
                            @elseif(strtoupper($j->status_baptis) === 'PENYERAHAN')
                                <span class="text-amber-700 font-bold">PENYERAHAN</span>
                            @else
                                <span class="text-outline">{{ $j->status_baptis ?: '-' }}</span>
                            @endif
                        </td>

                        <!-- G. NIKAH MASEHI -->
                        <td class="col-cell py-3 px-4 whitespace-nowrap text-xs">
                            @if(strtoupper($j->nikah_masehi) === 'SUDAH')
                                <span class="text-green-700 font-semibold">SUDAH</span>
                            @elseif(strtoupper($j->nikah_masehi) === 'BELUM')
                                <span class="text-outline">BELUM</span>
                            @else
                                <span class="text-outline">{{ $j->nikah_masehi ?: '-' }}</span>
                            @endif
                        </td>

                        <!-- H. PENDIDIKAN -->
                        <td class="col-cell py-3 px-4 whitespace-nowrap text-xs text-on-surface">
                            {{ $j->pendidikan ?: '-' }}
                        </td>

                        <!-- I. PEKERJAAN -->
                        <td class="col-cell py-3 px-4 whitespace-nowrap text-xs text-on-surface">
                            {{ $j->pekerjaan ?: '-' }}
                        </td>

                        <!-- J. KEAHLIAN KHUSUS -->
                        <td class="col-cell py-3 px-4 whitespace-nowrap text-xs text-on-surface">
                            {{ $j->keahlian_khusus ?: '-' }}
                        </td>

                        <!-- K. PENGHASILAN PER BULAN -->
                        <td class="col-cell py-3 px-4 whitespace-nowrap font-mono text-xs text-on-surface">
                            @if(is_numeric($j->penghasilan_per_bulan))
                                Rp {{ number_format((float) $j->penghasilan_per_bulan, 0, ',', '.') }}
                            @elseif($j->penghasilan_per_bulan)
                                {{ $j->penghasilan_per_bulan }}
                            @else
                                <span class="text-outline">-</span>
                            @endif
                        </td>

                        <!-- L. LAIN-LAIN -->
                        <td class="col-cell py-3 px-4 whitespace-nowrap text-xs text-on-surface">
                            {{ $j->lain_lain ?: '-' }}
                        </td>

                        <!-- M. KETERANGAN -->
                        <td class="col-cell py-3 px-4 whitespace-nowrap text-xs">
                            @if(!empty($j->keterangan))
                                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-800 font-semibold text-[10px]">{{ $j->keterangan }}</span>
                            @else
                                <span class="text-outline">-</span>
                            @endif
                        </td>

                        <!-- AKSI -->
                        <td class="col-cell py-3 px-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('admin.jemaat.show', $j->id) }}" class="p-1 rounded text-outline hover:text-primary hover:bg-surface-container transition" title="Lihat Profil">
                                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                                </a>
                                @if(Auth::user() && Auth::user()->canCrudJemaat())
                                <a href="{{ route('admin.jemaat.edit', $j->id) }}" class="p-1 rounded text-outline hover:text-secondary hover:bg-secondary-container/30 transition" title="Ubah Data">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                </a>
                                <form action="{{ route('admin.jemaat.destroy', $j->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data jemaat \'{{ $j->nama_lengkap }}\'?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded text-outline hover:text-red-600 hover:bg-red-50 transition" title="Hapus Data">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="15" class="py-16 text-center text-outline">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-surface-container flex items-center justify-center text-outline">
                                    <span class="material-symbols-outlined text-[32px]">folder_open</span>
                                </div>
                                <span class="font-bold text-base text-primary">Data Jemaat Tidak Ditemukan</span>
                                <p class="text-xs text-on-surface-variant max-w-md">
                                    Tidak ada data jemaat yang sesuai dengan filter atau kata kunci pencarian.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- EXCEL SHEET TABS (HANYA AKTIF SAAT MODE EXCEL) -->
        <div id="excelBottomBar" class="hidden bg-[#f3f2f1] border-t border-gray-300 px-3 py-1.5 flex items-center gap-1.5 overflow-x-auto text-xs">
            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mr-2 flex items-center gap-1 shrink-0">
                <span class="material-symbols-outlined text-[14px]">tab</span>
                <span>Lembar Kerja (Sheets):</span>
            </span>
            <a href="{{ route('admin.jemaat.index') }}" class="px-3 py-1 text-[11px] font-bold transition flex items-center gap-1 shrink-0 {{ !request('wilayah_besar') ? 'bg-white border-t-2 border-[#107c41] text-[#107c41] shadow-xs' : 'bg-gray-200 hover:bg-white text-gray-700' }}">
                <span>Semua Sheet (823)</span>
            </a>
            <a href="{{ route('admin.jemaat.index', ['wilayah_besar' => 'Namosain']) }}" class="px-3 py-1 text-[11px] font-bold transition flex items-center gap-1 shrink-0 {{ request('wilayah_besar') === 'Namosain' ? 'bg-white border-t-2 border-[#107c41] text-[#107c41] shadow-xs' : 'bg-gray-200 hover:bg-white text-gray-700' }}">
                <span>NAMOSAIN (63)</span>
            </a>
            <a href="{{ route('admin.jemaat.index', ['wilayah_besar' => 'Rumah 7']) }}" class="px-3 py-1 text-[11px] font-bold transition flex items-center gap-1 shrink-0 {{ request('wilayah_besar') === 'Rumah 7' ? 'bg-white border-t-2 border-[#107c41] text-[#107c41] shadow-xs' : 'bg-gray-200 hover:bg-white text-gray-700' }}">
                <span>RUMAH 7 (329)</span>
            </a>
            <a href="{{ route('admin.jemaat.index', ['wilayah_besar' => 'Osmok']) }}" class="px-3 py-1 text-[11px] font-bold transition flex items-center gap-1 shrink-0 {{ request('wilayah_besar') === 'Osmok' ? 'bg-white border-t-2 border-[#107c41] text-[#107c41] shadow-xs' : 'bg-gray-200 hover:bg-white text-gray-700' }}">
                <span>OSMOK (431)</span>
            </a>
        </div>

        @if($jemaats->hasPages())
        <div class="p-4 border-t border-outline-variant/20 bg-surface-container-low/30">
            {{ $jemaats->links() }}
        </div>
        @endif
    </div>
</div>

<!-- STYLES KHUSUS MODE EXCEL SPREADSHEET -->
<style>
.mode-excel-active #jemaatMainTable {
    border: 1px solid #d1d5db !important;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important;
}
.mode-excel-active .col-cell {
    border: 1px solid #d1d5db !important;
    padding: 6px 10px !important;
    font-size: 11px !important;
    line-height: 1.3 !important;
}
.mode-excel-active thead .table-header-row th {
    background-color: #f3f2f1 !important;
    color: #323130 !important;
    border: 1px solid #c8c6c4 !important;
    font-weight: 600 !important;
    font-size: 11px !important;
}
.mode-excel-active .table-data-row:hover td {
    background-color: #e8f5e9 !important;
}
.mode-excel-active .excel-row-num-cell {
    display: table-cell !important;
}
.mode-excel-active #excelColLettersRow {
    display: table-row !important;
}
.mode-excel-active #excelHeaderRibbon,
.mode-excel-active #excelFormulaBar,
.mode-excel-active #excelBottomBar {
    display: flex !important;
}
.mode-excel-active .sub-wilayah-tag {
    display: none !important;
}
.mode-excel-active .jk-badge {
    background: transparent !important;
    width: auto !important;
    height: auto !important;
    font-size: 11px !important;
    color: inherit !important;
}
.mode-excel-active .status-kel-tag {
    background: transparent !important;
    padding: 0 !important;
    font-weight: normal !important;
    font-size: 11px !important;
}
</style>

<!-- SCRIPT PENGUBAH TAMPILAN MODERN VS EXCEL -->
<script>
function setJemaatLayout(mode) {
    const container = document.getElementById('jemaatTableContainer');
    const btnModern = document.getElementById('btnModeModern');
    const btnExcel = document.getElementById('btnModeExcel');

    if (mode === 'excel') {
        container.classList.add('mode-excel-active');
        btnExcel.className = 'flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-xs bg-[#107c41] text-white';
        btnExcel.querySelector('span.material-symbols-outlined').className = 'material-symbols-outlined text-[16px] text-white';
        btnExcel.querySelector('span:last-child').className = 'text-white font-bold';

        btnModern.className = 'flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-on-surface-variant hover:text-primary transition';
        localStorage.setItem('jemaat_layout_mode', 'excel');
    } else {
        container.classList.remove('mode-excel-active');
        btnModern.className = 'flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-xs bg-white text-primary';

        btnExcel.className = 'flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-on-surface-variant hover:text-emerald-700 transition';
        btnExcel.querySelector('span.material-symbols-outlined').className = 'material-symbols-outlined text-[16px] text-emerald-600';
        btnExcel.querySelector('span:last-child').className = 'font-bold text-emerald-800';
        localStorage.setItem('jemaat_layout_mode', 'modern');
    }
}

// Inisialisasi layout berdasarkan preferensi user tersimpan di browser
document.addEventListener('DOMContentLoaded', function() {
    const savedMode = localStorage.getItem('jemaat_layout_mode');
    if (savedMode === 'excel') {
        setJemaatLayout('excel');
    }
});
</script>

<!-- MODAL IMPORT EXCEL / CSV -->
<div id="importModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-outline-variant/30 space-y-5">
        <div class="flex items-center justify-between pb-3 border-b border-outline-variant/30">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-primary-container text-secondary-fixed flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">upload_file</span>
                </div>
                <div>
                    <h3 class="font-serif-display text-lg font-bold text-primary">Import Data Jemaat</h3>
                    <p class="text-[11px] text-on-surface-variant">Mendukung file Excel (.xlsx) langsung dengan banyak sheet atau file CSV</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-surface-container text-outline hover:text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <form action="{{ route('admin.jemaat.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/30 text-xs text-on-surface-variant space-y-2.5">
                <div class="flex items-center gap-1.5 text-secondary font-bold">
                    <span class="material-symbols-outlined text-[16px]">verified</span>
                    <span>Bisa Upload File Excel Asli (.xlsx) Langsung</span>
                </div>
                <p class="text-[11px] text-on-surface leading-relaxed">
                    Sistem kini mendukung file <strong>.xlsx</strong> dengan banyak sheet secara otomatis (misal sheet OSMOK 1-6, R7 1-4, NAMOSAIN 1-2).
                </p>
                <div class="p-2.5 rounded-lg bg-secondary-container/40 text-[11px] text-on-surface space-y-1">
                    <strong class="text-primary block">Sistem Otomatis Deteksi Wilayah dari 2 Digit Pertama KK:</strong>
                    • <code>11xxxx</code> & <code>12xxxx</code>: Namosain Sub 1-2<br>
                    • <code>21xxxx</code> s/d <code>24xxxx</code>: Rumah 7 Sub 1-4<br>
                    • <code>31xxxx</code> s/d <code>36xxxx</code>: Osmok Sub 1-6
                </div>
                <p class="text-[11px]">
                    Ingin menggunakan format kosong? <a href="{{ route('admin.jemaat.template') }}" class="text-secondary font-bold underline">Download Template Kosong</a>.
                </p>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-primary uppercase">Pilih File Excel / CSV (.xlsx, .csv)</label>
                <input type="file" name="file_csv" required accept=".xlsx,.csv,.txt" class="block w-full text-xs text-on-surface-variant file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-primary-container cursor-pointer border border-outline-variant rounded-xl bg-surface-container-low p-1.5">
            </div>

            <div class="pt-3 border-t border-outline-variant/30 flex items-center justify-end gap-2">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-semibold hover:bg-surface-container transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-secondary hover:bg-secondary/90 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">cloud_upload</span>
                    <span>Mulai Import Data</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Plugin Universal: Menampilkan Angka di Dalam Irisan Donut & di Ujung/Atas Batang
    const universalDataLabelsPlugin = {
        id: 'universalDataLabels',
        afterDatasetsDraw(chart) {
            if (chart.config.type === 'line') return;
            const { ctx } = chart;
            chart.data.datasets.forEach((dataset, datasetIndex) => {
                const meta = chart.getDatasetMeta(datasetIndex);
                if (!meta || meta.hidden) return;
                meta.data.forEach((element, index) => {
                    const val = dataset.data[index];
                    if (val === undefined || val === null || val <= 0) return;
                    ctx.save();
                    if (chart.config.type === 'doughnut' || chart.config.type === 'pie') {
                        let x, y;
                        if (typeof element.tooltipPosition === 'function') {
                            const pos = element.tooltipPosition();
                            x = pos.x;
                            y = pos.y;
                        } else if (element.x !== undefined && element.startAngle !== undefined) {
                            const midAngle = element.startAngle + (element.endAngle - element.startAngle) / 2;
                            const midRadius = element.innerRadius + (element.outerRadius - element.innerRadius) / 2;
                            x = element.x + Math.cos(midAngle) * midRadius;
                            y = element.y + Math.sin(midAngle) * midRadius;
                        }
                        if (x !== undefined && y !== undefined) {
                            ctx.fillStyle = '#ffffff';
                            ctx.font = 'bold 10px sans-serif';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.shadowColor = 'rgba(0, 0, 0, 0.75)';
                            ctx.shadowBlur = 3;
                            ctx.fillText(Number(val).toLocaleString('id-ID'), x, y);
                        }
                    } else if (chart.config.type === 'bar') {
                        ctx.fillStyle = '#001428';
                        ctx.font = 'bold 9px sans-serif';
                        if (chart.options.indexAxis === 'y') {
                            ctx.textAlign = 'left';
                            ctx.textBaseline = 'middle';
                            ctx.fillText(Number(val).toLocaleString('id-ID'), element.x + 3, element.y);
                        } else {
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';
                            ctx.fillText(Number(val).toLocaleString('id-ID'), element.x, element.y - 2);
                        }
                    }
                    ctx.restore();
                });
            });
        }
    };

    // Plugin Universal: Menampilkan Angka Total di Tengah Donut
    const donutCenterTotalPlugin = {
        id: 'donutCenterTotal',
        beforeDraw(chart) {
            if (chart.config.type !== 'doughnut') return;
            const { ctx, chartArea } = chart;
            if (!chartArea) return;
            const { top, left, width, height } = chartArea;
            const dataset = chart.data.datasets[0];
            if (!dataset || !dataset.data) return;
            const total = dataset.data.reduce((a, b) => a + (typeof b === 'number' ? b : 0), 0);
            if (total <= 0) return;
            ctx.save();
            ctx.font = 'bold 13px sans-serif';
            ctx.fillStyle = '#001428';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(Number(total).toLocaleString('id-ID'), left + width / 2, top + height / 2 - 4);
            ctx.font = 'bold 8px sans-serif';
            ctx.fillStyle = '#775a19';
            ctx.fillText('TOTAL', left + width / 2, top + height / 2 + 7);
            ctx.restore();
        }
    };

    Chart.register(universalDataLabelsPlugin, donutCenterTotalPlugin);

    // 1A. Chart Kategorial (Donut)
    const ctxKaum = document.getElementById('chartJemaatKaum');
    if (ctxKaum) {
        new Chart(ctxKaum, {
            type: 'doughnut',
            data: {
                labels: ['Kaum Bapak', 'Kaum Ibu', 'Pemuda & Remaja', 'Anak-Anak'],
                datasets: [{
                    data: [
                        {{ $demo['kategorial']['kaum_bapak'] ?? 0 }},
                        {{ $demo['kategorial']['kaum_ibu'] ?? 0 }},
                        {{ $demo['kategorial']['pemuda_remaja'] ?? 0 }},
                        {{ $demo['kategorial']['anak'] ?? 0 }}
                    ],
                    backgroundColor: ['#2563eb', '#e11d48', '#f59e0b', '#059669'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '60%'
            }
        });
    }

    // 1B. Chart Kategorial (Batang)
    const ctxKaumBar = document.getElementById('chartJemaatKaumBar');
    if (ctxKaumBar) {
        new Chart(ctxKaumBar, {
            type: 'bar',
            data: {
                labels: ['Bapak', 'Ibu', 'Pemuda', 'Anak'],
                datasets: [{
                    label: 'Jiwa',
                    data: [
                        {{ $demo['kategorial']['kaum_bapak'] ?? 0 }},
                        {{ $demo['kategorial']['kaum_ibu'] ?? 0 }},
                        {{ $demo['kategorial']['pemuda_remaja'] ?? 0 }},
                        {{ $demo['kategorial']['anak'] ?? 0 }}
                    ],
                    backgroundColor: ['#2563eb', '#e11d48', '#f59e0b', '#059669'],
                    borderRadius: 4,
                    maxBarThickness: 24
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grace: '12%', grid: { color: '#f3f4f6' }, ticks: { font: { size: 8 } } },
                    x: { grid: { display: false }, ticks: { font: { size: 9 } } }
                }
            }
        });
    }

    // 2A. Chart Gender (Donut)
    const ctxGen = document.getElementById('chartJemaatGender');
    if (ctxGen) {
        new Chart(ctxGen, {
            type: 'doughnut',
            data: {
                labels: ['Laki-Laki', 'Perempuan'],
                datasets: [{
                    data: [
                        {{ $demo['gender']['pria'] ?? 0 }},
                        {{ $demo['gender']['wanita'] ?? 0 }}
                    ],
                    backgroundColor: ['#2563eb', '#e11d48'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '60%'
            }
        });
    }

    // 2B. Chart Gender (Batang)
    const ctxGenBar = document.getElementById('chartJemaatGenderBar');
    if (ctxGenBar) {
        new Chart(ctxGenBar, {
            type: 'bar',
            data: {
                labels: ['Laki-Laki', 'Perempuan'],
                datasets: [{
                    label: 'Jiwa',
                    data: [
                        {{ $demo['gender']['pria'] ?? 0 }},
                        {{ $demo['gender']['wanita'] ?? 0 }}
                    ],
                    backgroundColor: ['#2563eb', '#e11d48'],
                    borderRadius: 4,
                    maxBarThickness: 26
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grace: '12%', grid: { color: '#f3f4f6' }, ticks: { font: { size: 8 } } },
                    x: { grid: { display: false }, ticks: { font: { size: 9 } } }
                }
            }
        });
    }

    // 3A. Chart KK (Donut Sebaran Wilayah)
    const ctxKkDonut = document.getElementById('chartJemaatKkDonut');
    if (ctxKkDonut) {
        new Chart(ctxKkDonut, {
            type: 'doughnut',
            data: {
                labels: ['Namosain', 'Rumah 7', 'Osmok'],
                datasets: [{
                    data: [
                        {{ $demo['kkWilayah']['Namosain'] ?? 0 }},
                        {{ $demo['kkWilayah']['Rumah 7'] ?? 0 }},
                        {{ $demo['kkWilayah']['Osmok'] ?? 0 }}
                    ],
                    backgroundColor: ['#775a19', '#2563eb', '#059669'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '60%'
            }
        });
    }

    // 3B. Chart KK (Batang Sebaran Wilayah)
    const ctxKkBar = document.getElementById('chartJemaatKkBar');
    if (ctxKkBar) {
        new Chart(ctxKkBar, {
            type: 'bar',
            data: {
                labels: ['Namosain', 'Rumah 7', 'Osmok'],
                datasets: [{
                    label: 'KK',
                    data: [
                        {{ $demo['kkWilayah']['Namosain'] ?? 0 }},
                        {{ $demo['kkWilayah']['Rumah 7'] ?? 0 }},
                        {{ $demo['kkWilayah']['Osmok'] ?? 0 }}
                    ],
                    backgroundColor: ['#775a19', '#2563eb', '#059669'],
                    borderRadius: 4,
                    maxBarThickness: 22
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grace: '12%', grid: { color: '#f3f4f6' }, ticks: { font: { size: 8 } } },
                    x: { grid: { display: false }, ticks: { font: { size: 9 } } }
                }
            }
        });
    }

    // 4A. Chart Baptis (Donut)
    const ctxBapDonut = document.getElementById('chartJemaatBaptisDonut');
    if (ctxBapDonut) {
        new Chart(ctxBapDonut, {
            type: 'doughnut',
            data: {
                labels: ['Sudah', 'Belum'],
                datasets: [{
                    data: [
                        {{ $demo['baptis']['sudah'] ?? 0 }},
                        {{ $demo['baptis']['belum'] ?? 0 }}
                    ],
                    backgroundColor: ['#059669', '#d1d5db'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '60%'
            }
        });
    }

    // 4B. Chart Penyerahan Anak (Donut)
    const ctxPenDonut = document.getElementById('chartJemaatPenyerahanDonut');
    if (ctxPenDonut) {
        new Chart(ctxPenDonut, {
            type: 'doughnut',
            data: {
                labels: ['Sudah', 'Belum'],
                datasets: [{
                    data: [
                        {{ $demo['penyerahan']['sudah'] ?? 0 }},
                        {{ $demo['penyerahan']['belum'] ?? 0 }}
                    ],
                    backgroundColor: ['#059669', '#d1d5db'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '60%'
            }
        });
    }

    // 5. Chart Pendidikan (Horizontal Bar)
    const ctxPdk = document.getElementById('chartJemaatPendidikan');
    if (ctxPdk) {
        const pdkData = @json($demo['pendidikanMap'] ?? []);
        new Chart(ctxPdk, {
            type: 'bar',
            data: {
                labels: Object.keys(pdkData),
                datasets: [{
                    label: 'Jiwa',
                    data: Object.values(pdkData),
                    backgroundColor: '#775a19',
                    borderRadius: 4,
                    maxBarThickness: 14
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, grace: '12%', grid: { color: '#f3f4f6' }, ticks: { font: { size: 8 } } },
                    y: { grid: { display: false }, ticks: { font: { size: 8 } } }
                }
            }
        });
    }

    // 6A. Chart Diakonia (Batang)
    const ctxDiakBar = document.getElementById('chartJemaatDiakoniaBar');
    if (ctxDiakBar) {
        new Chart(ctxDiakBar, {
            type: 'bar',
            data: {
                labels: ['Lansia', 'Janda', 'Duda', 'Yatim'],
                datasets: [{
                    label: 'Jiwa',
                    data: [
                        {{ $demo['diakonia']['lansia'] ?? 0 }},
                        {{ $demo['diakonia']['janda'] ?? 0 }},
                        {{ $demo['diakonia']['duda'] ?? 0 }},
                        {{ $demo['diakonia']['yatim_piatu'] ?? 0 }}
                    ],
                    backgroundColor: ['#d97706', '#775a19', '#001428', '#2563eb'],
                    borderRadius: 4,
                    maxBarThickness: 18
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grace: '12%', grid: { color: '#f3f4f6' }, ticks: { font: { size: 8 } } },
                    x: { grid: { display: false }, ticks: { font: { size: 8 } } }
                }
            }
        });
    }

    // 6B. Chart Diakonia (Donut)
    const ctxDiakDonut = document.getElementById('chartJemaatDiakoniaDonut');
    if (ctxDiakDonut) {
        new Chart(ctxDiakDonut, {
            type: 'doughnut',
            data: {
                labels: ['Lansia', 'Janda', 'Duda', 'Yatim'],
                datasets: [{
                    data: [
                        {{ $demo['diakonia']['lansia'] ?? 0 }},
                        {{ $demo['diakonia']['janda'] ?? 0 }},
                        {{ $demo['diakonia']['duda'] ?? 0 }},
                        {{ $demo['diakonia']['yatim_piatu'] ?? 0 }}
                    ],
                    backgroundColor: ['#d97706', '#775a19', '#001428', '#2563eb'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '60%'
            }
        });
    }
});
</script>
@endpush
@endsection
