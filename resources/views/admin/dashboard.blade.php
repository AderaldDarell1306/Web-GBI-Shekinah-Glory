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
                    <span class="text-white/60 text-xs">| Basis Data Jemaat Terpusat</span>
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
                <a href="{{ route('admin.sakramen.index') }}" class="flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-primary-container text-white hover:bg-primary-container/80 font-bold text-xs shadow-md transition border border-white/10">
                    <span class="material-symbols-outlined text-[17px]">workspace_premium</span>
                    <span>Modul Sakramen</span>
                </a>
                <a href="{{ route('admin.pelayanan.index') }}" class="flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-amber-400 text-primary hover:bg-amber-300 font-bold text-xs shadow-md transition">
                    <span class="material-symbols-outlined text-[17px]">groups</span>
                    <span>Jadwal Pelayan (Rota)</span>
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

    <!-- 4 Executive Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Metric 1: Total Jemaat & Gender -->
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-outline-variant/30 relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold text-outline uppercase tracking-wider">Total Jemaat</p>
                    <h3 class="font-serif-display text-3xl font-bold text-primary mt-1">{{ number_format($totalJemaat) }} <span class="text-xs font-normal text-outline">Jiwa</span></h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-surface-container flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[24px]">groups</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-outline-variant/20 flex items-center justify-between text-xs">
                <span class="text-primary font-semibold flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span> {{ $totalPria }} Pria
                </span>
                <span class="text-primary font-semibold flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-pink-500"></span> {{ $totalWanita }} Wanita
                </span>
            </div>
        </div>

        <!-- Metric 2: Total Kepala Keluarga (KK) -->
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-outline-variant/30 relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold text-outline uppercase tracking-wider">Kepala Keluarga (KK)</p>
                    <h3 class="font-serif-display text-3xl font-bold text-primary mt-1">{{ number_format($totalKk) }} <span class="text-xs font-normal text-outline">Keluarga</span></h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-surface-container flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined text-[24px]">home</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-outline-variant/20 flex items-center justify-between text-xs">
                <span class="text-secondary font-bold flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">family_restroom</span>
                    Buku Induk KK
                </span>
                <span class="text-outline">Rata-rata {{ $totalKk > 0 ? round($totalJemaat / $totalKk, 1) : 0 }} jiwa/KK</span>
            </div>
        </div>

        <!-- Metric 3: Jemaat Aktif & Rasio -->
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-outline-variant/30 relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold text-outline uppercase tracking-wider">Jemaat Aktif</p>
                    <h3 class="font-serif-display text-3xl font-bold text-primary mt-1">{{ number_format($jemaatAktif) }} <span class="text-xs font-normal text-outline">Jiwa</span></h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-surface-container flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[24px]">verified</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-outline-variant/20 flex items-center justify-between text-xs">
                @php
                    $percentAktif = $totalJemaat > 0 ? round(($jemaatAktif / $totalJemaat) * 100, 1) : 0;
                @endphp
                <span class="text-green-700 font-bold flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span>
                    {{ $percentAktif }}%
                </span>
                <span class="text-outline">Keaktifan Pelayanan</span>
            </div>
        </div>

        <!-- Metric 4: Status Sakramen Baptis -->
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-outline-variant/30 relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold text-outline uppercase tracking-wider">Status Baptis Air</p>
                    <h3 class="font-serif-display text-3xl font-bold text-secondary mt-1">{{ $baptisStats['sudah'] }} <span class="text-xs font-normal text-outline">Sudah</span></h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-secondary-container/40 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined text-[24px]">water_drop</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-outline-variant/20 flex items-center justify-between text-xs">
                <span class="text-outline">Belum Baptis: <strong class="text-primary">{{ $baptisStats['belum'] }}</strong></span>
                <span class="text-secondary font-bold">{{ $totalJemaat > 0 ? round(($baptisStats['sudah'] / $totalJemaat) * 100) : 0 }}% Dibaptis</span>
            </div>
        </div>
    </div>

    <!-- SEKSI 1: ANALITIK KATEGORIAL PELAYANAN (PRD Modul 1 & v2.1) -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-outline-variant/20 pb-3">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[22px]">diversity_3</span>
                <div>
                    <h3 class="font-serif-display text-lg font-bold text-primary">Pemetaan Kategorial Jemaat</h3>
                    <p class="text-xs text-on-surface-variant">Kategorisasi otomatis berdasarkan usia & status pernikahan (PRD v2.1)</p>
                </div>
            </div>
            <span class="text-xs text-secondary font-semibold bg-secondary-container/40 px-3 py-1 rounded-full">
                Anak (0–14 Thn) • Pemuda (15–35 Thn Belum Nikah) • Kaum Bapak/Ibu (Menikah / &gt;35 Thn)
            </span>
        </div>

        <!-- 4 Metric Cards Kategorial -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Kaum Bapak -->
            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-primary uppercase tracking-wide">Kaum Bapak</span>
                    <span class="material-symbols-outlined text-primary text-[20px]">person</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-serif-display text-primary">{{ $kategorialStats['kaum_bapak'] }}</span>
                    <span class="text-xs text-outline">jiwa ({{ $totalJemaat > 0 ? round(($kategorialStats['kaum_bapak'] / $totalJemaat) * 100) : 0 }}%)</span>
                </div>
                <div class="w-full bg-surface-container rounded-full h-1.5 overflow-hidden">
                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $totalJemaat > 0 ? ($kategorialStats['kaum_bapak'] / $totalJemaat) * 100 : 0 }}%"></div>
                </div>
            </div>

            <!-- Kaum Ibu -->
            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-rose-700 uppercase tracking-wide">Kaum Ibu</span>
                    <span class="material-symbols-outlined text-rose-600 text-[20px]">face_3</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-serif-display text-rose-700">{{ $kategorialStats['kaum_ibu'] }}</span>
                    <span class="text-xs text-outline">jiwa ({{ $totalJemaat > 0 ? round(($kategorialStats['kaum_ibu'] / $totalJemaat) * 100) : 0 }}%)</span>
                </div>
                <div class="w-full bg-surface-container rounded-full h-1.5 overflow-hidden">
                    <div class="bg-rose-600 h-1.5 rounded-full" style="width: {{ $totalJemaat > 0 ? ($kategorialStats['kaum_ibu'] / $totalJemaat) * 100 : 0 }}%"></div>
                </div>
            </div>

            <!-- Pemuda & Remaja -->
            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-700 uppercase tracking-wide">Pemuda & Remaja</span>
                    <span class="material-symbols-outlined text-amber-700 text-[20px]">group</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-serif-display text-amber-800">{{ $kategorialStats['pemuda_remaja'] }}</span>
                    <span class="text-xs text-outline">jiwa ({{ $totalJemaat > 0 ? round(($kategorialStats['pemuda_remaja'] / $totalJemaat) * 100) : 0 }}%)</span>
                </div>
                <div class="w-full bg-surface-container rounded-full h-1.5 overflow-hidden">
                    <div class="bg-amber-600 h-1.5 rounded-full" style="width: {{ $totalJemaat > 0 ? ($kategorialStats['pemuda_remaja'] / $totalJemaat) * 100 : 0 }}%"></div>
                </div>
            </div>

            <!-- Anak-Anak -->
            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wide">Anak-Anak (SM)</span>
                    <span class="material-symbols-outlined text-emerald-700 text-[20px]">child_care</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-serif-display text-emerald-800">{{ $kategorialStats['anak'] }}</span>
                    <span class="text-xs text-outline">jiwa ({{ $totalJemaat > 0 ? round(($kategorialStats['anak'] / $totalJemaat) * 100) : 0 }}%)</span>
                </div>
                <div class="w-full bg-surface-container rounded-full h-1.5 overflow-hidden">
                    <div class="bg-emerald-600 h-1.5 rounded-full" style="width: {{ $totalJemaat > 0 ? ($kategorialStats['anak'] / $totalJemaat) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- VISUALISASI GRAFIK INTERAKTIF (PRD v2.1 AC-R01) -->
        <!-- ======================================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 pt-2 border-t border-outline-variant/20">
            <!-- Grafik 1: Donut Chart Kategorial (dengan Angka di Dalam) -->
            <div class="lg:col-span-4 flex flex-col justify-between p-4 rounded-xl bg-surface-container-low border border-outline-variant/20">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-bold text-xs uppercase tracking-wider text-primary flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px] text-secondary">donut_large</span>
                        Proporsi Kategorial
                    </h4>
                    <span class="text-2xs font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-full">Angka di Dalam</span>
                </div>
                <div class="relative w-full h-56 flex items-center justify-center my-auto">
                    <canvas id="chartKategorial"></canvas>
                </div>
                <div class="grid grid-cols-2 gap-1.5 text-2xs mt-3 pt-2 border-t border-outline-variant/20">
                    <span class="flex items-center gap-1.5 text-primary"><span class="w-2.5 h-2.5 rounded-sm bg-blue-600 shrink-0"></span> Bapak: <strong>{{ $kategorialStats['kaum_bapak'] }}</strong></span>
                    <span class="flex items-center gap-1.5 text-rose-700"><span class="w-2.5 h-2.5 rounded-sm bg-rose-600 shrink-0"></span> Ibu: <strong>{{ $kategorialStats['kaum_ibu'] }}</strong></span>
                    <span class="flex items-center gap-1.5 text-amber-700"><span class="w-2.5 h-2.5 rounded-sm bg-amber-500 shrink-0"></span> Pemuda: <strong>{{ $kategorialStats['pemuda_remaja'] }}</strong></span>
                    <span class="flex items-center gap-1.5 text-emerald-700"><span class="w-2.5 h-2.5 rounded-sm bg-emerald-600 shrink-0"></span> Anak: <strong>{{ $kategorialStats['anak'] }}</strong></span>
                </div>
            </div>

            <!-- Grafik 2: Bar Chart Kategorial (Grafik Batang Kategorial) -->
            <div class="lg:col-span-4 flex flex-col justify-between p-4 rounded-xl bg-surface-container-low border border-outline-variant/20">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-bold text-xs uppercase tracking-wider text-primary flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px] text-secondary">bar_chart</span>
                        Grafik Batang Kategorial
                    </h4>
                    <span class="text-2xs text-outline font-mono">{{ $totalJemaat }} Jiwa</span>
                </div>
                <div class="relative w-full h-56">
                    <canvas id="chartKategorialBar"></canvas>
                </div>
                <div class="flex items-center justify-between text-2xs text-outline mt-3 pt-2 border-t border-outline-variant/20">
                    <span>* Perbandingan komparasi jumlah per kategori</span>
                    <span class="font-bold text-primary">Total: {{ $totalJemaat }} Jiwa</span>
                </div>
            </div>

            <!-- Grafik 3: Horizontal Bar Chart Persebaran Sub-Wilayah -->
            <div class="lg:col-span-4 flex flex-col justify-between p-4 rounded-xl bg-surface-container-low border border-outline-variant/20">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-bold text-xs uppercase tracking-wider text-primary flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px] text-secondary">location_city</span>
                        Persebaran Sub-Wilayah
                    </h4>
                    <span class="text-2xs text-secondary font-bold">{{ count($chartWilayahLabels) }} Sub-Wilayah</span>
                </div>
                <div class="relative w-full h-56">
                    <canvas id="chartWilayah"></canvas>
                </div>
                <p class="text-[11px] text-outline mt-2 pt-2 border-t border-outline-variant/20 flex items-center justify-between">
                    <span>* Data terakumulasi wilayah</span>
                    <a href="{{ route('admin.wilayah.index') }}" class="text-secondary font-bold hover:underline">Kelola &rarr;</a>
                </p>
            </div>
        </div>
    </div>

    <!-- SEKSI 2: DIAKONIA & DEMOGRAFI LANJUTAN -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Target Pelayanan Diakonia Khusus -->
        <div class="lg:col-span-4 bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-4">
            <div class="flex items-center gap-2 border-b border-outline-variant/20 pb-3">
                <span class="material-symbols-outlined text-secondary text-[22px]">volunteer_activism</span>
                <div>
                    <h3 class="font-serif-display text-base font-bold text-primary">Target Diakonia Khusus</h3>
                    <p class="text-[11px] text-on-surface-variant">Prioritas pendampingan & santunan kasih</p>
                </div>
            </div>

            <div class="space-y-3">
                <div class="p-3 rounded-xl bg-surface-container-low flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-secondary/10 text-secondary flex items-center justify-center font-bold text-xs">J</span>
                        <div>
                            <span class="font-bold text-xs text-primary block">Janda</span>
                            <span class="text-[10px] text-outline">Keluarga Orang Tua Tunggal</span>
                        </div>
                    </div>
                    <span class="text-base font-bold font-serif-display text-primary">{{ $diakoniaStats['janda'] }} Jiwa</span>
                </div>

                <div class="p-3 rounded-xl bg-surface-container-low flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-xs">D</span>
                        <div>
                            <span class="font-bold text-xs text-primary block">Duda</span>
                            <span class="text-[10px] text-outline">Keluarga Orang Tua Tunggal</span>
                        </div>
                    </div>
                    <span class="text-base font-bold font-serif-display text-primary">{{ $diakoniaStats['duda'] }} Jiwa</span>
                </div>

                <div class="p-3 rounded-xl bg-surface-container-low flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-700 flex items-center justify-center font-bold text-xs">Y</span>
                        <div>
                            <span class="font-bold text-xs text-primary block">Yatim / Piatu</span>
                            <span class="text-[10px] text-outline">Anak Asuh / Prioritas Pendidikan</span>
                        </div>
                    </div>
                    <span class="text-base font-bold font-serif-display text-primary">{{ $diakoniaStats['yatim_piatu'] }} Jiwa</span>
                </div>
            </div>
        </div>

        <!-- Demografi Tingkat Pendidikan -->
        <div class="lg:col-span-4 bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-4">
            <div class="flex items-center gap-2 border-b border-outline-variant/20 pb-3">
                <span class="material-symbols-outlined text-secondary text-[22px]">school</span>
                <div>
                    <h3 class="font-serif-display text-base font-bold text-primary">Tingkat Pendidikan</h3>
                    <p class="text-[11px] text-on-surface-variant">Profil jenjang pendidikan anggota</p>
                </div>
            </div>

            <div class="space-y-2.5">
                @foreach($pendidikanMap as $jenjang => $totalPdk)
                @php
                    $pctPdk = $totalJemaat > 0 ? round(($totalPdk / $totalJemaat) * 100) : 0;
                @endphp
                <div class="space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="font-semibold text-primary">{{ $jenjang }}</span>
                        <span class="text-outline font-bold">{{ $totalPdk }} ({{ $pctPdk }}%)</span>
                    </div>
                    <div class="w-full bg-surface-container rounded-full h-1.5 overflow-hidden">
                        <div class="bg-secondary h-1.5 rounded-full" style="width: {{ max($pctPdk, 3) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top Pekerjaan Jemaat -->
        <div class="lg:col-span-4 bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-4">
            <div class="flex items-center gap-2 border-b border-outline-variant/20 pb-3">
                <span class="material-symbols-outlined text-secondary text-[22px]">work</span>
                <div>
                    <h3 class="font-serif-display text-base font-bold text-primary">Sebaran Pekerjaan</h3>
                    <p class="text-[11px] text-on-surface-variant">Profesi & bidang keahlian jemaat</p>
                </div>
            </div>

            <div class="divide-y divide-outline-variant/20 text-xs">
                @foreach($topPekerjaan as $pekerjaan => $totalPkj)
                <div class="py-2.5 flex items-center justify-between">
                    <span class="font-semibold text-primary truncate pr-2">{{ $pekerjaan }}</span>
                    <span class="px-2 py-0.5 rounded-full bg-surface-container font-bold text-primary shrink-0">{{ $totalPkj }} Jiwa</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 2 Column Section: Distribusi Wilayah & Aktivitas Terkini -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Distribusi Sektor / Wilayah -->
        <div class="lg:col-span-6 bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[22px]">map</span>
                    <h3 class="font-serif-display text-lg font-bold text-primary">Sebaran Jemaat per Sektor</h3>
                </div>
                <a href="{{ route('admin.wilayah.index') }}" class="text-xs font-bold text-secondary hover:underline">Kelola Sektor</a>
            </div>

            <div class="space-y-3 pt-2">
                @foreach($wilayahStats as $w)
                @php
                    $pct = $totalJemaat > 0 ? round(($w->jemaat_count / $totalJemaat) * 100) : 0;
                @endphp
                <div class="space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="font-semibold text-primary">{{ $w->nama_wilayah }}</span>
                        <span class="text-outline font-bold">{{ $w->jemaat_count }} Jiwa ({{ $pct }}%)</span>
                    </div>
                    <div class="w-full bg-surface-container rounded-full h-2 overflow-hidden">
                        <div class="bg-primary h-2 rounded-full transition-all duration-500" style="width: {{ max($pct, 4) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- 5 Jemaat Terbaru -->
        <div class="lg:col-span-6 bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[22px]">badge</span>
                    <h3 class="font-serif-display text-lg font-bold text-primary">Jemaat Terdaftar Baru-baru Ini</h3>
                </div>
                <a href="{{ route('admin.jemaat.index') }}" class="text-xs font-bold text-secondary hover:underline">Lihat Semua</a>
            </div>

            <div class="divide-y divide-outline-variant/20 pt-1">
                @forelse($recentJemaat as $j)
                <div class="py-3 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-secondary-container/40 text-secondary flex items-center justify-center font-bold text-xs shrink-0">
                            {{ $j->jenis_kelamin === 'L' ? 'L' : 'P' }}
                        </div>
                        <div>
                            <span class="font-bold text-primary block text-sm">{{ $j->nama_lengkap }}</span>
                            <span class="text-outline">{{ $j->nomor_induk }} • {{ $j->wilayah->nama_wilayah ?? '-' }}</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.jemaat.show', $j->id) }}" class="p-1.5 rounded-lg text-outline hover:text-primary hover:bg-surface-container transition">
                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                    </a>
                </div>
                @empty
                <p class="text-xs text-outline py-4 text-center">Belum ada data jemaat terdaftar.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Plugin Khusus: Gambar Angka di Dalam Irisan Donut Tanpa Perlu Hover
    const donutSliceLabelsPlugin = {
        id: 'donutSliceLabelsPlugin',
        afterDatasetsDraw(chart) {
            const { ctx, data } = chart;
            chart.getDatasetMeta(0).data.forEach((datapoint, index) => {
                const val = data.datasets[0].data[index];
                if (val > 0) {
                    const { x, y } = datapoint.tooltipPosition();
                    ctx.save();
                    ctx.fillStyle = '#ffffff';
                    ctx.font = 'bold 12px sans-serif';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.shadowColor = 'rgba(0, 0, 0, 0.8)';
                    ctx.shadowBlur = 4;
                    ctx.fillText(val, x, y);
                    ctx.restore();
                }
            });
        }
    };

    // Plugin Khusus: Angka Total di Tengah Donut
    const donutCenterTextPlugin = {
        id: 'donutCenterTextPlugin',
        beforeDraw(chart) {
            if (chart.config.type !== 'doughnut') return;
            const { ctx, chartArea } = chart;
            if (!chartArea) return;
            const { top, bottom, left, right, width, height } = chartArea;
            ctx.save();
            ctx.font = 'bold 18px sans-serif';
            ctx.fillStyle = '#001428';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
            ctx.fillText(total, left + width / 2, top + height / 2 - 8);
            ctx.font = 'bold 10px sans-serif';
            ctx.fillStyle = '#775a19';
            ctx.fillText('JIWA', left + width / 2, top + height / 2 + 10);
            ctx.restore();
        }
    };

    // Plugin Khusus: Gambar Angka di Atas Batang Bar Chart
    const barTopLabelsPlugin = {
        id: 'barTopLabelsPlugin',
        afterDatasetsDraw(chart) {
            if (chart.config.type !== 'bar' || chart.options.indexAxis === 'y') return;
            const { ctx, data } = chart;
            chart.getDatasetMeta(0).data.forEach((bar, index) => {
                const val = data.datasets[0].data[index];
                if (val !== undefined && val !== null) {
                    ctx.save();
                    ctx.fillStyle = '#001428';
                    ctx.font = 'bold 11px sans-serif';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';
                    ctx.fillText(val + ' Jiwa', bar.x, bar.y - 4);
                    ctx.restore();
                }
            });
        }
    };

    // 1. Chart Kategorial (Doughnut dengan Angka di Dalam)
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
                    backgroundColor: [
                        '#2563eb', // Blue (Bapak)
                        '#e11d48', // Rose (Ibu)
                        '#f59e0b', // Amber (Pemuda)
                        '#059669'  // Emerald (Anak)
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            plugins: [donutSliceLabelsPlugin, donutCenterTextPlugin],
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const val = context.raw;
                                const pct = total > 0 ? Math.round((val / total) * 100) : 0;
                                return ` ${context.label}: ${val} Jiwa (${pct}%)`;
                            }
                        }
                    }
                },
                cutout: '62%'
            }
        });
    }

    // 2. Chart Kategorial (Grafik Batang Tegak)
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
                    backgroundColor: [
                        '#2563eb',
                        '#e11d48',
                        '#f59e0b',
                        '#059669'
                    ],
                    borderRadius: 6,
                    maxBarThickness: 32
                }]
            },
            plugins: [barTopLabelsPlugin],
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: { size: 10 }
                        },
                        grid: { color: '#f3f4f6' }
                    },
                    x: {
                        ticks: {
                            font: { size: 11, weight: 'bold' }
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 2. Chart Persebaran Wilayah (Horizontal Bar)
    const ctxWil = document.getElementById('chartWilayah');
    if (ctxWil) {
        const labelsWil = @json($chartWilayahLabels);
        const dataWil = @json($chartWilayahValues);

        new Chart(ctxWil, {
            type: 'bar',
            data: {
                labels: labelsWil,
                datasets: [{
                    label: 'Jumlah Jemaat (Jiwa)',
                    data: dataWil,
                    backgroundColor: '#775a19',
                    borderRadius: 6,
                    maxBarThickness: 20
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` ${context.raw} Jiwa`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            color: '#e5e7eb'
                        },
                        ticks: {
                            precision: 0,
                            font: {
                                size: 10
                            }
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
