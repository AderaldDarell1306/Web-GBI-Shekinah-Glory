@extends('layouts.admin')

@section('title', 'Dashboard - SIMJ GBI Shekinah Glory')
@section('page_title', 'Dashboard Eksekutif Data Jemaat')

@section('content')
<div class="space-y-6">
    <!-- Top Welcome Banner -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary via-primary-container to-primary text-white p-6 sm:p-8 shadow-md">
        <div class="absolute -right-10 -bottom-14 w-60 h-60 rounded-full bg-secondary-fixed/10 blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary/30 text-secondary-fixed text-xs font-bold tracking-wide">
                        <span class="w-2 h-2 rounded-full bg-secondary-fixed animate-pulse"></span>
                        SINKRONISASI AKTIF
                    </span>
                    <span class="text-white/60 text-xs">| Basis Data Jemaat Terpusat SIMJ v2.2</span>
                </div>
                <h2 class="font-serif-display text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Selamat Datang, {{ Auth::user()->name }}
                </h2>
                <p class="text-xs sm:text-sm text-white/80 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px] text-secondary-fixed">place</span>
                    <span>GBI Shekinah Glory Namosain • Kota Kupang, NTT</span>
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('admin.presensi.index') }}" class="flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-amber-400 text-primary hover:bg-amber-300 font-bold text-xs shadow-md transition">
                    <span class="material-symbols-outlined text-[17px]">fact_check</span>
                    <span>Presensi Pelayan</span>
                </a>
                <a href="{{ route('admin.sakramen.index') }}" class="flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-primary-container text-white hover:bg-primary-container/80 font-bold text-xs shadow-md transition border border-white/10">
                    <span class="material-symbols-outlined text-[17px]">workspace_premium</span>
                    <span>Modul Sakramen</span>
                </a>
                <a href="{{ route('admin.jemaat.create') }}" class="flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-secondary text-white hover:bg-secondary/90 font-bold text-xs shadow-md transition">
                    <span class="material-symbols-outlined text-[17px]">person_add</span>
                    <span>+ Jemaat</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Wilayah Tabs (Modul 1: Pemetaan Data) -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-outline-variant/30 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary text-[20px]">filter_alt</span>
            <span class="text-xs font-bold text-primary uppercase tracking-wider">Filter Pemetaan Wilayah:</span>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ empty($filterWilayahBesar) && empty($filterWilayahId) ? 'bg-primary text-white shadow-sm' : 'bg-surface-container text-on-surface hover:bg-surface-container-high' }}">
                Semua Wilayah ({{ $totalJemaat }})
            </a>
            <a href="{{ route('admin.dashboard', ['wilayah_besar' => 'Namosain']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $filterWilayahBesar === 'Namosain' ? 'bg-primary text-white shadow-sm' : 'bg-surface-container text-on-surface hover:bg-surface-container-high' }}">
                Namosain
            </a>
            <a href="{{ route('admin.dashboard', ['wilayah_besar' => 'Rumah 7']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $filterWilayahBesar === 'Rumah 7' ? 'bg-primary text-white shadow-sm' : 'bg-surface-container text-on-surface hover:bg-surface-container-high' }}">
                Rumah 7
            </a>
            <a href="{{ route('admin.dashboard', ['wilayah_besar' => 'Osmok']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $filterWilayahBesar === 'Osmok' ? 'bg-primary text-white shadow-sm' : 'bg-surface-container text-on-surface hover:bg-surface-container-high' }}">
                Osmok
            </a>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 5 EXECUTIVE STATS CARDS (RINGKASAN UTAMA) -->
    <!-- ======================================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Metric 1: Total Jemaat & Gender -->
        <div class="rounded-2xl bg-white p-4 shadow-sm border border-outline-variant/30 flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-2xs font-bold text-outline uppercase tracking-wider">Total Anggota Jemaat</p>
                    <h3 class="font-serif-display text-2xl sm:text-3xl font-bold text-primary mt-1">{{ number_format($totalJemaat) }} <span class="text-xs font-normal text-outline">Jiwa</span></h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[22px]">groups</span>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-outline-variant/20 flex items-center justify-between text-2xs">
                <span class="text-blue-700 font-semibold flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span> {{ $totalPria }} Pria ({{ $pctPria }}%)
                </span>
                <span class="text-rose-700 font-semibold flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-rose-600"></span> {{ $totalWanita }} Wanita ({{ $pctWanita }}%)
                </span>
            </div>
        </div>

        <!-- Metric 2: Total Kepala Keluarga (KK) -->
        <div class="rounded-2xl bg-white p-4 shadow-sm border border-outline-variant/30 flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-2xs font-bold text-outline uppercase tracking-wider">Kepala Keluarga (KK)</p>
                    <h3 class="font-serif-display text-2xl sm:text-3xl font-bold text-primary mt-1">{{ number_format($totalKk) }} <span class="text-xs font-normal text-outline">KK</span></h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined text-[22px]">home</span>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-outline-variant/20 flex items-center justify-between text-2xs">
                <span class="text-secondary font-bold flex items-center gap-1">
                    <span class="material-symbols-outlined text-[13px]">family_restroom</span>
                    Buku Induk
                </span>
                <span class="text-outline">Rata-rata {{ $avgJiwaPerKk }} jiwa/KK</span>
            </div>
        </div>

        <!-- Metric 3: Status Baptis Air -->
        <div class="rounded-2xl bg-white p-4 shadow-sm border border-outline-variant/30 flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-2xs font-bold text-outline uppercase tracking-wider">Status Baptis Air</p>
                    <h3 class="font-serif-display text-2xl sm:text-3xl font-bold text-secondary mt-1">{{ $baptisStats['sudah'] }} <span class="text-xs font-normal text-outline">Sudah</span></h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-secondary-container/40 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined text-[22px]">water_drop</span>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-outline-variant/20 flex items-center justify-between text-2xs">
                <span class="text-outline">Belum: <strong class="text-primary">{{ $baptisStats['belum'] }}</strong></span>
                <span class="text-secondary font-bold">{{ $pctBaptis }}% Dibaptis</span>
            </div>
        </div>

        <!-- Metric 4: Status Penyerahan Anak -->
        <div class="rounded-2xl bg-white p-4 shadow-sm border border-outline-variant/30 flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-2xs font-bold text-outline uppercase tracking-wider">Penyerahan Anak</p>
                    <h3 class="font-serif-display text-2xl sm:text-3xl font-bold text-emerald-700 mt-1">{{ $penyerahanStats['sudah'] }} <span class="text-xs font-normal text-outline">Sudah</span></h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-700">
                    <span class="material-symbols-outlined text-[22px]">child_friendly</span>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-outline-variant/20 flex items-center justify-between text-2xs">
                <span class="text-outline">Belum: <strong class="text-primary">{{ $penyerahanStats['belum'] }}</strong></span>
                <span class="text-emerald-700 font-bold">{{ $pctPenyerahan }}% Diserahkan</span>
            </div>
        </div>

        <!-- Metric 5: Kategori Khusus (Diakonia & Lansia) -->
        <div class="rounded-2xl bg-white p-4 shadow-sm border border-outline-variant/30 flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-2xs font-bold text-outline uppercase tracking-wider">Kategori Khusus</p>
                    <h3 class="font-serif-display text-2xl sm:text-3xl font-bold text-primary mt-1">{{ $diakoniaStats['lansia'] + $diakoniaStats['janda'] + $diakoniaStats['duda'] }} <span class="text-xs font-normal text-outline">Jiwa</span></h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-700">
                    <span class="material-symbols-outlined text-[22px]">volunteer_activism</span>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-outline-variant/20 flex items-center justify-between text-2xs">
                <span class="text-amber-800 font-semibold">{{ $diakoniaStats['lansia'] }} Lansia</span>
                <span class="text-outline">{{ $diakoniaStats['janda'] }} Janda • {{ $diakoniaStats['duda'] }} Duda</span>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- BAGIAN 1: KOMPOSISI KATEGORIAL (PER KAUM) -->
    <!-- ======================================================== -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-5">
        <div class="flex items-center justify-between border-b border-outline-variant/20 pb-3">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[22px]">diversity_3</span>
                <div>
                    <h3 class="font-serif-display text-base sm:text-lg font-bold text-primary">Komposisi Kategorial Jemaat (Per Kaum)</h3>
                    <p class="text-xs text-on-surface-variant">Visualisasi untuk Kaum Bapak, Kaum Ibu, Pemuda &amp; Remaja, Anak-Anak</p>
                </div>
            </div>
            <span class="text-xs text-secondary font-semibold bg-secondary-container/40 px-3 py-1 rounded-full">
                4 Kaum Lengkap
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Proporsi Kaum (Donut) -->
            <div class="lg:col-span-6 p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-bold text-xs uppercase tracking-wider text-primary flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px] text-secondary">donut_large</span>
                        Proporsi Kategorial Per Kaum
                    </h4>
                </div>
                <div class="relative w-full h-56 flex items-center justify-center my-auto">
                    <canvas id="chartKategorial"></canvas>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-2xs mt-3 pt-2.5 border-t border-outline-variant/20">
                    <span class="flex items-center gap-1.5 text-primary"><span class="w-2.5 h-2.5 rounded-sm bg-blue-600 shrink-0"></span> Bapak: <strong>{{ $kategorialStats['kaum_bapak'] }}</strong></span>
                    <span class="flex items-center gap-1.5 text-rose-700"><span class="w-2.5 h-2.5 rounded-sm bg-rose-600 shrink-0"></span> Ibu: <strong>{{ $kategorialStats['kaum_ibu'] }}</strong></span>
                    <span class="flex items-center gap-1.5 text-amber-700"><span class="w-2.5 h-2.5 rounded-sm bg-amber-500 shrink-0"></span> Pemuda: <strong>{{ $kategorialStats['pemuda_remaja'] }}</strong></span>
                    <span class="flex items-center gap-1.5 text-emerald-700"><span class="w-2.5 h-2.5 rounded-sm bg-emerald-600 shrink-0"></span> Anak: <strong>{{ $kategorialStats['anak'] }}</strong></span>
                </div>
            </div>

            <!-- Komparasi Kaum (Batang) -->
            <div class="lg:col-span-6 p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-bold text-xs uppercase tracking-wider text-primary flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px] text-secondary">bar_chart</span>
                        Komparasi Jumlah Jiwa Per Kaum
                    </h4>
                    <span class="text-2xs text-outline font-mono">Total {{ $totalJemaat }} Jiwa</span>
                </div>
                <div class="relative w-full h-56">
                    <canvas id="chartKategorialBar"></canvas>
                </div>
                <div class="flex items-center justify-end text-2xs text-outline mt-3 pt-2.5 border-t border-outline-variant/20">
                    <span class="font-bold text-primary">Bapak {{ $kategorialStats['kaum_bapak'] }} • Ibu {{ $kategorialStats['kaum_ibu'] }} • Pemuda {{ $kategorialStats['pemuda_remaja'] }} • Anak {{ $kategorialStats['anak'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- BAGIAN 2: JUMLAH ANGGOTA (GENDER) & KEPALA KELUARGA (KK) -->
    <!-- ======================================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- 2A: GENDER (LAKI-LAKI VS PEREMPUAN) -->
        <div class="lg:col-span-6 bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-4">
            <div class="flex items-center justify-between border-b border-outline-variant/20 pb-3">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[22px]">wc</span>
                    <div>
                        <h4 class="font-serif-display text-base font-bold text-primary">Komposisi Gender Jemaat (L/P)</h4>
                        <p class="text-2xs text-on-surface-variant">Perbandingan Laki-Laki vs Perempuan</p>
                    </div>
                </div>
                <span class="text-2xs font-bold text-primary bg-surface-container px-2.5 py-1 rounded-full">{{ $totalJemaat }} Jiwa</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Donut Gender -->
                <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col justify-between">
                    <span class="text-2xs font-bold text-outline uppercase tracking-wider block mb-1">Proporsi Gender</span>
                    <div class="relative w-full h-44 flex items-center justify-center my-auto">
                        <canvas id="chartGender"></canvas>
                    </div>
                </div>

                <!-- Batang Gender -->
                <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col justify-between">
                    <span class="text-2xs font-bold text-outline uppercase tracking-wider block mb-1">Komparasi Gender</span>
                    <div class="relative w-full h-44">
                        <canvas id="chartGenderBar"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 text-xs pt-2 border-t border-outline-variant/20">
                <div class="p-2.5 rounded-xl bg-blue-50/70 border border-blue-200">
                    <span class="text-2xs font-bold text-blue-700 block">Laki-Laki (Pria)</span>
                    <span class="text-base font-bold text-blue-900 block font-serif-display">{{ $totalPria }} Jiwa</span>
                    <span class="text-2xs text-blue-700 font-medium">{{ $pctPria }}% dari total</span>
                </div>
                <div class="p-2.5 rounded-xl bg-rose-50/70 border border-rose-200">
                    <span class="text-2xs font-bold text-rose-700 block">Perempuan (Wanita)</span>
                    <span class="text-base font-bold text-rose-900 block font-serif-display">{{ $totalWanita }} Jiwa</span>
                    <span class="text-2xs text-rose-700 font-medium">{{ $pctWanita }}% dari total</span>
                </div>
            </div>
        </div>

        <!-- 2B: KEPALA KELUARGA (KK) PER WILAYAH -->
        <div class="lg:col-span-6 bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-4">
            <div class="flex items-center justify-between border-b border-outline-variant/20 pb-3">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[22px]">home</span>
                    <div>
                        <h4 class="font-serif-display text-base font-bold text-primary">Sebaran Kepala Keluarga (KK)</h4>
                        <p class="text-2xs text-on-surface-variant">Sebaran KK per 3 Wilayah Besar</p>
                    </div>
                </div>
                <span class="text-2xs font-bold text-secondary bg-secondary-container/40 px-2.5 py-1 rounded-full">{{ $totalKk }} KK</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Donut KK -->
                <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col justify-between">
                    <span class="text-2xs font-bold text-outline uppercase tracking-wider block mb-1">Proporsi KK Wilayah</span>
                    <div class="relative w-full h-44 flex items-center justify-center my-auto">
                        <canvas id="chartKkDonut"></canvas>
                    </div>
                </div>

                <!-- Batang KK -->
                <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col justify-between">
                    <span class="text-2xs font-bold text-outline uppercase tracking-wider block mb-1">Sebaran KK Wilayah</span>
                    <div class="relative w-full h-44">
                        <canvas id="chartKkBar"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-2 text-center text-xs pt-2 border-t border-outline-variant/20">
                <div class="p-2 rounded-xl bg-surface-container-low border border-outline-variant/20">
                    <span class="text-2xs text-outline block">Namosain</span>
                    <strong class="text-primary text-sm font-serif-display">{{ $kkWilayah['Namosain'] ?? 0 }} KK</strong>
                </div>
                <div class="p-2 rounded-xl bg-surface-container-low border border-outline-variant/20">
                    <span class="text-2xs text-outline block">Rumah 7</span>
                    <strong class="text-primary text-sm font-serif-display">{{ $kkWilayah['Rumah 7'] ?? 0 }} KK</strong>
                </div>
                <div class="p-2 rounded-xl bg-surface-container-low border border-outline-variant/20">
                    <span class="text-2xs text-outline block">Osmok</span>
                    <strong class="text-primary text-sm font-serif-display">{{ $kkWilayah['Osmok'] ?? 0 }} KK</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- BAGIAN 3: STATUS BAPTIS AIR & PENYERAHAN ANAK -->
    <!-- ======================================================== -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-5">
        <div class="flex items-center justify-between border-b border-outline-variant/20 pb-3">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[22px]">workspace_premium</span>
                <div>
                    <h3 class="font-serif-display text-base sm:text-lg font-bold text-primary">Status Baptis Air &amp; Penyerahan Anak</h3>
                    <p class="text-xs text-on-surface-variant">Kelengkapan sakramen jemaat dewasa &amp; penyerahan anak</p>
                </div>
            </div>
            <a href="{{ route('admin.sakramen.index') }}" class="text-xs font-bold text-secondary hover:underline">Modul Sakramen &rarr;</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <!-- 3A: Donut Baptis Air -->
            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-primary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-secondary">donut_large</span>
                        Baptis Air
                    </span>
                    <span class="text-2xs text-secondary font-bold">{{ $pctBaptis }}% Sudah</span>
                </div>
                <div class="relative w-full h-44 flex items-center justify-center my-auto">
                    <canvas id="chartBaptisDonut"></canvas>
                </div>
                <div class="flex items-center justify-between text-2xs pt-2.5 border-t border-outline-variant/20">
                    <span class="text-green-700 font-bold">Sudah: {{ $baptisStats['sudah'] }}</span>
                    <span class="text-outline">Belum: {{ $baptisStats['belum'] }}</span>
                </div>
            </div>

            <!-- 3B: Donut Penyerahan Anak -->
            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-primary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-emerald-700">donut_large</span>
                        Penyerahan Anak
                    </span>
                    <span class="text-2xs text-emerald-700 font-bold">{{ $pctPenyerahan }}% Sudah</span>
                </div>
                <div class="relative w-full h-44 flex items-center justify-center my-auto">
                    <canvas id="chartPenyerahanDonut"></canvas>
                </div>
                <div class="flex items-center justify-between text-2xs pt-2.5 border-t border-outline-variant/20">
                    <span class="text-emerald-700 font-bold">Sudah: {{ $penyerahanStats['sudah'] }}</span>
                    <span class="text-outline">Belum: {{ $penyerahanStats['belum'] }} (dari {{ $penyerahanStats['total_anak'] }})</span>
                </div>
            </div>

            <!-- 3C: Batang Komparasi Sakramen -->
            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col justify-between sm:col-span-2 lg:col-span-1">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-primary flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-secondary">bar_chart</span>
                        Komparasi Sakramen
                    </span>
                    <span class="text-2xs text-outline font-mono">Sudah vs Belum</span>
                </div>
                <div class="relative w-full h-44">
                    <canvas id="chartSakramenBar"></canvas>
                </div>
                <div class="flex items-center justify-between text-2xs pt-2.5 border-t border-outline-variant/20 text-outline">
                    <span>Sudah (Hijau) • Belum (Abu-abu)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- BAGIAN 4: TINGKAT PENDIDIKAN & KATEGORI KHUSUS (DIAKONIA) -->
    <!-- ======================================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- 4A: PENDIDIKAN (BATANG) -->
        <div class="lg:col-span-6 bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-4">
            <div class="flex items-center justify-between border-b border-outline-variant/20 pb-3">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[22px]">school</span>
                    <div>
                        <h4 class="font-serif-display text-base font-bold text-primary">Jenjang Pendidikan Terakhir</h4>
                        <p class="text-2xs text-on-surface-variant">Jenjang Pendidikan Anggota Jemaat</p>
                    </div>
                </div>
                <span class="text-2xs font-bold text-secondary bg-secondary-container/40 px-2.5 py-1 rounded-full">{{ count($pendidikanMap) }} Jenjang</span>
            </div>

            <div class="relative w-full h-64">
                <canvas id="chartPendidikan"></canvas>
            </div>

            <div class="flex items-center justify-end text-2xs text-outline pt-2 border-t border-outline-variant/20">
                <span class="font-bold text-primary">Mayoritas: {{ array_key_first($pendidikanMap) }} ({{ reset($pendidikanMap) }} Jiwa)</span>
            </div>
        </div>

        <!-- 4B: KATEGORI KHUSUS / DIAKONIA -->
        <div class="lg:col-span-6 bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-4">
            <div class="flex items-center justify-between border-b border-outline-variant/20 pb-3">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[22px]">volunteer_activism</span>
                    <div>
                        <h4 class="font-serif-display text-base font-bold text-primary">Kategori Khusus (Diakonia &amp; Lansia)</h4>
                        <p class="text-2xs text-on-surface-variant">Warga Prioritas Pendampingan Kasih</p>
                    </div>
                </div>
                <span class="text-2xs font-bold text-amber-800 bg-amber-100 px-2.5 py-1 rounded-full">{{ array_sum($diakoniaStats) }} Jiwa</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Batang Diakonia -->
                <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col justify-between">
                    <span class="text-2xs font-bold text-outline uppercase tracking-wider block mb-1">Rincian Kategori Khusus</span>
                    <div class="relative w-full h-44">
                        <canvas id="chartDiakoniaBar"></canvas>
                    </div>
                </div>

                <!-- Donut Diakonia -->
                <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col justify-between">
                    <span class="text-2xs font-bold text-outline uppercase tracking-wider block mb-1">Proporsi Kategori Khusus</span>
                    <div class="relative w-full h-44 flex items-center justify-center my-auto">
                        <canvas id="chartDiakoniaDonut"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-4 gap-2 text-center text-2xs pt-2 border-t border-outline-variant/20">
                <div class="p-1.5 rounded-lg bg-surface-container-low border border-outline-variant/20">
                    <span class="text-outline block">Lansia</span>
                    <strong class="text-amber-800 text-xs">{{ $diakoniaStats['lansia'] }}</strong>
                </div>
                <div class="p-1.5 rounded-lg bg-surface-container-low border border-outline-variant/20">
                    <span class="text-outline block">Janda</span>
                    <strong class="text-secondary text-xs">{{ $diakoniaStats['janda'] }}</strong>
                </div>
                <div class="p-1.5 rounded-lg bg-surface-container-low border border-outline-variant/20">
                    <span class="text-outline block">Duda</span>
                    <strong class="text-primary text-xs">{{ $diakoniaStats['duda'] }}</strong>
                </div>
                <div class="p-1.5 rounded-lg bg-surface-container-low border border-outline-variant/20">
                    <span class="text-outline block">Yatim/Piatu</span>
                    <strong class="text-blue-700 text-xs">{{ $diakoniaStats['yatim_piatu'] }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- BAGIAN 5: REKAPITULASI KEAKTIFAN TAHUNAN -->
    <!-- ======================================================== -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-outline-variant/20 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center">
                    <span class="material-symbols-outlined text-[24px]">insights</span>
                </div>
                <div>
                    <h3 class="font-serif-display text-lg font-bold text-primary">Keaktifan Ibadah Tahunan ({{ $selectedYear }})</h3>
                    <p class="text-xs text-on-surface-variant">
                        Tren kehadiran Ibadah Raya Minggu dan Ibadah Rumah Tangga (COOL) sepanjang tahun {{ $selectedYear }}.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" id="btnToggleRaya" onclick="switchAttendanceView('raya')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition bg-primary text-white shadow-sm">
                    Ibadah Raya
                </button>
                <button type="button" id="btnToggleCool" onclick="switchAttendanceView('cool')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition bg-surface-container text-on-surface hover:bg-surface-container-high">
                    Rumah Tangga (COOL)
                </button>
                <button type="button" id="btnToggleBoth" onclick="switchAttendanceView('both')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition bg-surface-container text-on-surface hover:bg-surface-container-high">
                    Komparasi Keduanya
                </button>
            </div>
        </div>

        <!-- 4 Summary Cards Keaktifan Tahunan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20">
                <span class="text-2xs font-bold text-primary uppercase tracking-wider block">Total Kehadiran Ibadah Raya</span>
                <span class="text-2xl font-bold font-serif-display text-primary mt-1 block">{{ number_format($annualAttendance['ibadahRaya']['total']) }} <span class="text-xs font-normal text-outline">Jiwa</span></span>
                <span class="text-2xs text-outline mt-1 block">Rata-rata {{ $annualAttendance['avgRayaMingguan'] }} jiwa/minggu</span>
            </div>

            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20">
                <span class="text-2xs font-bold text-secondary uppercase tracking-wider block">Total Ibadah Rumah Tangga (COOL)</span>
                <span class="text-2xl font-bold font-serif-display text-secondary mt-1 block">{{ number_format($annualAttendance['ibadahCool']['total']) }} <span class="text-xs font-normal text-outline">Jiwa</span></span>
                <span class="text-2xs text-outline mt-1 block">Anak-Anak: {{ number_format($annualAttendance['ibadahCool']['anak']) }} jiwa</span>
            </div>

            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20">
                <span class="text-2xs font-bold text-green-700 uppercase tracking-wider block">Komitmen Kehadiran Minggu</span>
                @php
                    $komitmenPct = $jemaatAktif > 0 ? round(($annualAttendance['avgRayaMingguan'] / $jemaatAktif) * 100, 1) : 0;
                @endphp
                <span class="text-2xl font-bold font-serif-display text-green-700 mt-1 block">{{ $komitmenPct }}%</span>
                <span class="text-2xs text-outline mt-1 block">Rasio terhadap {{ $jemaatAktif }} jemaat aktif</span>
            </div>

            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20">
                <span class="text-2xs font-bold text-amber-700 uppercase tracking-wider block">Keaktifan Pelayan Ibadah</span>
                <span class="text-2xl font-bold font-serif-display text-amber-800 mt-1 block">{{ count($ministerStats['ministers']) }} <span class="text-xs font-normal text-outline">Pelayan</span></span>
                <a href="{{ route('admin.presensi.index') }}" class="text-2xs text-amber-700 font-bold hover:underline mt-1 block">Buka Lembar Presensi &rarr;</a>
            </div>
        </div>

        <!-- Canvas Grafik Line / Bar Trend Bulanan -->
        <div class="relative w-full h-72 rounded-xl bg-surface-container-lowest p-4 border border-outline-variant/20">
            <canvas id="chartKeaktifanTahunan"></canvas>
        </div>

        <!-- Detail Pemecahan Per Kaum Kehadiran -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-outline-variant/20 text-xs">
            <div class="p-4 rounded-xl bg-blue-50/60 border border-blue-200">
                <h5 class="font-bold text-blue-900 mb-2 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">church</span>
                    Rincian Kehadiran Ibadah Raya
                </h5>
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="bg-white p-2 rounded-lg shadow-2xs">
                        <span class="text-2xs text-outline block">Kaum Bapak</span>
                        <strong class="text-blue-900 text-sm">{{ number_format($annualAttendance['ibadahRaya']['bapak']) }}</strong>
                    </div>
                    <div class="bg-white p-2 rounded-lg shadow-2xs">
                        <span class="text-2xs text-outline block">Kaum Ibu</span>
                        <strong class="text-rose-900 text-sm">{{ number_format($annualAttendance['ibadahRaya']['ibu']) }}</strong>
                    </div>
                    <div class="bg-white p-2 rounded-lg shadow-2xs">
                        <span class="text-2xs text-outline block">Pemuda</span>
                        <strong class="text-amber-900 text-sm">{{ number_format($annualAttendance['ibadahRaya']['pemuda']) }}</strong>
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-emerald-50/60 border border-emerald-200">
                <h5 class="font-bold text-emerald-900 mb-2 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">cottage</span>
                    Rincian Kehadiran Ibadah Rumah Tangga (COOL)
                </h5>
                <div class="grid grid-cols-4 gap-2 text-center">
                    <div class="bg-white p-2 rounded-lg shadow-2xs">
                        <span class="text-2xs text-outline block">Bapak</span>
                        <strong class="text-blue-900 text-sm">{{ number_format($annualAttendance['ibadahCool']['bapak']) }}</strong>
                    </div>
                    <div class="bg-white p-2 rounded-lg shadow-2xs">
                        <span class="text-2xs text-outline block">Ibu</span>
                        <strong class="text-rose-900 text-sm">{{ number_format($annualAttendance['ibadahCool']['ibu']) }}</strong>
                    </div>
                    <div class="bg-white p-2 rounded-lg shadow-2xs">
                        <span class="text-2xs text-outline block">Pemuda</span>
                        <strong class="text-amber-900 text-sm">{{ number_format($annualAttendance['ibadahCool']['pemuda']) }}</strong>
                    </div>
                    <div class="bg-white p-2 rounded-lg shadow-2xs">
                        <span class="text-2xs text-outline block">Anak</span>
                        <strong class="text-emerald-900 text-sm">{{ number_format($annualAttendance['ibadahCool']['anak']) }}</strong>
                    </div>
                </div>
            </div>
        </div>
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
                            ctx.font = 'bold 11px sans-serif';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.shadowColor = 'rgba(0, 0, 0, 0.75)';
                            ctx.shadowBlur = 4;
                            ctx.fillText(Number(val).toLocaleString('id-ID'), x, y);
                        }
                    } else if (chart.config.type === 'bar') {
                        ctx.fillStyle = '#001428';
                        ctx.font = 'bold 10px sans-serif';
                        if (chart.options.indexAxis === 'y') {
                            ctx.textAlign = 'left';
                            ctx.textBaseline = 'middle';
                            ctx.fillText(Number(val).toLocaleString('id-ID'), element.x + 4, element.y);
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
            ctx.font = 'bold 14px sans-serif';
            ctx.fillStyle = '#001428';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(Number(total).toLocaleString('id-ID'), left + width / 2, top + height / 2 - 5);
            ctx.font = 'bold 8px sans-serif';
            ctx.fillStyle = '#775a19';
            ctx.fillText('TOTAL', left + width / 2, top + height / 2 + 8);
            ctx.restore();
        }
    };

    Chart.register(universalDataLabelsPlugin, donutCenterTotalPlugin);

    // 1. Chart Kategorial (Donut)
    const ctxKat = document.getElementById('chartKategorial');
    if (ctxKat) {
        new Chart(ctxKat, {
            type: 'doughnut',
            data: {
                labels: ['Kaum Bapak', 'Kaum Ibu', 'Pemuda & Remaja', 'Anak-Anak'],
                datasets: [{
                    data: [
                        {{ $kategorialStats['kaum_bapak'] }},
                        {{ $kategorialStats['kaum_ibu'] }},
                        {{ $kategorialStats['pemuda_remaja'] }},
                        {{ $kategorialStats['anak'] }}
                    ],
                    backgroundColor: ['#2563eb', '#e11d48', '#f59e0b', '#059669'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '62%'
            }
        });
    }

    // 2. Chart Kategorial (Batang)
    const ctxKatBar = document.getElementById('chartKategorialBar');
    if (ctxKatBar) {
        new Chart(ctxKatBar, {
            type: 'bar',
            data: {
                labels: ['Bapak', 'Ibu', 'Pemuda', 'Anak'],
                datasets: [{
                    label: 'Jumlah Jiwa',
                    data: [
                        {{ $kategorialStats['kaum_bapak'] }},
                        {{ $kategorialStats['kaum_ibu'] }},
                        {{ $kategorialStats['pemuda_remaja'] }},
                        {{ $kategorialStats['anak'] }}
                    ],
                    backgroundColor: ['#2563eb', '#e11d48', '#f59e0b', '#059669'],
                    borderRadius: 6,
                    maxBarThickness: 36
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grace: '12%', grid: { color: '#f3f4f6' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 3. Chart Gender (Donut)
    const ctxGen = document.getElementById('chartGender');
    if (ctxGen) {
        new Chart(ctxGen, {
            type: 'doughnut',
            data: {
                labels: ['Laki-Laki', 'Perempuan'],
                datasets: [{
                    data: [{{ $totalPria }}, {{ $totalWanita }}],
                    backgroundColor: ['#2563eb', '#e11d48'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '62%'
            }
        });
    }

    // 4. Chart Gender (Batang)
    const ctxGenBar = document.getElementById('chartGenderBar');
    if (ctxGenBar) {
        new Chart(ctxGenBar, {
            type: 'bar',
            data: {
                labels: ['Laki-Laki', 'Perempuan'],
                datasets: [{
                    label: 'Jiwa',
                    data: [{{ $totalPria }}, {{ $totalWanita }}],
                    backgroundColor: ['#2563eb', '#e11d48'],
                    borderRadius: 6,
                    maxBarThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grace: '12%', grid: { color: '#f3f4f6' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 5. Chart KK (Donut Sebaran Wilayah)
    const ctxKkDonut = document.getElementById('chartKkDonut');
    if (ctxKkDonut) {
        new Chart(ctxKkDonut, {
            type: 'doughnut',
            data: {
                labels: ['Namosain', 'Rumah 7', 'Osmok'],
                datasets: [{
                    data: [
                        {{ $kkWilayah['Namosain'] ?? 0 }},
                        {{ $kkWilayah['Rumah 7'] ?? 0 }},
                        {{ $kkWilayah['Osmok'] ?? 0 }}
                    ],
                    backgroundColor: ['#775a19', '#2563eb', '#059669'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '62%'
            }
        });
    }

    // 6. Chart KK (Batang Sebaran Wilayah)
    const ctxKkBar = document.getElementById('chartKkBar');
    if (ctxKkBar) {
        new Chart(ctxKkBar, {
            type: 'bar',
            data: {
                labels: ['Namosain', 'Rumah 7', 'Osmok'],
                datasets: [{
                    label: 'Kepala Keluarga',
                    data: [
                        {{ $kkWilayah['Namosain'] ?? 0 }},
                        {{ $kkWilayah['Rumah 7'] ?? 0 }},
                        {{ $kkWilayah['Osmok'] ?? 0 }}
                    ],
                    backgroundColor: ['#775a19', '#2563eb', '#059669'],
                    borderRadius: 6,
                    maxBarThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grace: '12%', grid: { color: '#f3f4f6' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 7. Chart Baptis (Donut)
    const ctxBapDonut = document.getElementById('chartBaptisDonut');
    if (ctxBapDonut) {
        new Chart(ctxBapDonut, {
            type: 'doughnut',
            data: {
                labels: ['Sudah Baptis', 'Belum Baptis'],
                datasets: [{
                    data: [{{ $baptisStats['sudah'] }}, {{ $baptisStats['belum'] }}],
                    backgroundColor: ['#059669', '#d1d5db'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '62%'
            }
        });
    }

    // 8. Chart Penyerahan Anak (Donut)
    const ctxPenDonut = document.getElementById('chartPenyerahanDonut');
    if (ctxPenDonut) {
        new Chart(ctxPenDonut, {
            type: 'doughnut',
            data: {
                labels: ['Sudah Diserahkan', 'Belum Diserahkan'],
                datasets: [{
                    data: [{{ $penyerahanStats['sudah'] }}, {{ $penyerahanStats['belum'] }}],
                    backgroundColor: ['#059669', '#d1d5db'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '62%'
            }
        });
    }

    // 9. Chart Sakramen Komparasi (Batang)
    const ctxSakBar = document.getElementById('chartSakramenBar');
    if (ctxSakBar) {
        new Chart(ctxSakBar, {
            type: 'bar',
            data: {
                labels: ['Baptis Air', 'Penyerahan Anak'],
                datasets: [
                    {
                        label: 'Sudah',
                        data: [{{ $baptisStats['sudah'] }}, {{ $penyerahanStats['sudah'] }}],
                        backgroundColor: '#059669',
                        borderRadius: 6,
                        maxBarThickness: 24
                    },
                    {
                        label: 'Belum',
                        data: [{{ $baptisStats['belum'] }}, {{ $penyerahanStats['belum'] }}],
                        backgroundColor: '#9ca3af',
                        borderRadius: 6,
                        maxBarThickness: 24
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 10, font: { size: 9 } } }
                },
                scales: {
                    y: { beginAtZero: true, grace: '12%', grid: { color: '#f3f4f6' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 10. Chart Pendidikan (Batang Horisontal)
    const ctxPdk = document.getElementById('chartPendidikan');
    if (ctxPdk) {
        const pdkData = @json($pendidikanMap);
        new Chart(ctxPdk, {
            type: 'bar',
            data: {
                labels: Object.keys(pdkData),
                datasets: [{
                    label: 'Jiwa',
                    data: Object.values(pdkData),
                    backgroundColor: '#775a19',
                    borderRadius: 5,
                    maxBarThickness: 16
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, grace: '12%', grid: { color: '#f3f4f6' } },
                    y: { grid: { display: false }, ticks: { font: { size: 10 } } }
                }
            }
        });
    }

    // 11. Chart Diakonia (Batang)
    const ctxDiakBar = document.getElementById('chartDiakoniaBar');
    if (ctxDiakBar) {
        new Chart(ctxDiakBar, {
            type: 'bar',
            data: {
                labels: ['Lansia', 'Janda', 'Duda', 'Yatim'],
                datasets: [{
                    label: 'Jiwa',
                    data: [
                        {{ $diakoniaStats['lansia'] }},
                        {{ $diakoniaStats['janda'] }},
                        {{ $diakoniaStats['duda'] }},
                        {{ $diakoniaStats['yatim_piatu'] }}
                    ],
                    backgroundColor: ['#d97706', '#775a19', '#001428', '#2563eb'],
                    borderRadius: 5,
                    maxBarThickness: 24
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grace: '12%', grid: { color: '#f3f4f6' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 12. Chart Diakonia (Donut)
    const ctxDiakDonut = document.getElementById('chartDiakoniaDonut');
    if (ctxDiakDonut) {
        new Chart(ctxDiakDonut, {
            type: 'doughnut',
            data: {
                labels: ['Lansia', 'Janda', 'Duda', 'Yatim'],
                datasets: [{
                    data: [
                        {{ $diakoniaStats['lansia'] }},
                        {{ $diakoniaStats['janda'] }},
                        {{ $diakoniaStats['duda'] }},
                        {{ $diakoniaStats['yatim_piatu'] }}
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
                cutout: '62%'
            }
        });
    }

    // 13. Chart Keaktifan Ibadah Tahunan (Line & Area)
    const ctxAtt = document.getElementById('chartKeaktifanTahunan');
    if (ctxAtt) {
        const months = @json($annualAttendance['monthsLabels']);
        const dataRaya = @json($annualAttendance['rayaMonthly']);
        const dataCool = @json($annualAttendance['coolMonthly']);

        window.attendanceChart = new Chart(ctxAtt, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Ibadah Raya Minggu',
                        data: dataRaya,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#2563eb',
                        pointRadius: 4
                    },
                    {
                        label: 'Ibadah Rumah Tangga (COOL)',
                        data: dataCool,
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.08)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#059669',
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { boxWidth: 14, font: { size: 11, weight: 'bold' } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` ${context.dataset.label}: ${context.raw} Hadir`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: { font: { size: 10 } }
                    },
                    x: {
                        grid: { color: '#f3f4f6' },
                        ticks: { font: { size: 10, weight: 'bold' } }
                    }
                }
            }
        });
    }
});

function switchAttendanceView(mode) {
    const chart = window.attendanceChart;
    if (!chart) return;

    const btnRaya = document.getElementById('btnToggleRaya');
    const btnCool = document.getElementById('btnToggleCool');
    const btnBoth = document.getElementById('btnToggleBoth');

    [btnRaya, btnCool, btnBoth].forEach(btn => {
        btn.className = 'px-3.5 py-2 rounded-xl text-xs font-bold transition bg-surface-container text-on-surface hover:bg-surface-container-high';
    });

    if (mode === 'raya') {
        chart.setDatasetVisibility(0, true);
        chart.setDatasetVisibility(1, false);
        btnRaya.className = 'px-3.5 py-2 rounded-xl text-xs font-bold transition bg-primary text-white shadow-sm';
    } else if (mode === 'cool') {
        chart.setDatasetVisibility(0, false);
        chart.setDatasetVisibility(1, true);
        btnCool.className = 'px-3.5 py-2 rounded-xl text-xs font-bold transition bg-secondary text-white shadow-sm';
    } else {
        chart.setDatasetVisibility(0, true);
        chart.setDatasetVisibility(1, true);
        btnBoth.className = 'px-3.5 py-2 rounded-xl text-xs font-bold transition bg-primary text-white shadow-sm';
    }
    chart.update();
}
</script>
@endpush
@endsection
