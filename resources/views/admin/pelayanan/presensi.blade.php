@extends('layouts.admin')

@section('title', 'Presensi Mingguan & Keaktifan Pelayan - SIMJ GBI Shekinah Glory')
@section('page_title', 'Presensi Pelayan & Alat Ukur Keaktifan')

@section('content')
<div class="space-y-6">
    <!-- Top Action & Info Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container text-secondary text-xs font-bold tracking-wide">
                    <span class="material-symbols-outlined text-[15px]">verified</span>
                    PRD v2.2 Modul 2.4 B
                </span>
                <span class="text-xs text-outline font-medium">Alat Ukur Tahunan Terintegrasi</span>
            </div>
            <h2 class="font-serif-display text-2xl font-bold text-primary mt-1">Presensi Mingguan Pelayan Ibadah</h2>
            <p class="text-xs text-on-surface-variant">Pencatatan kehadiran pelayan terintegrasi jadwal mingguan &amp; akumulasi skor tahun {{ $selectedYear }}.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.pelayanan.index') }}" class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white hover:bg-gray-50 text-primary border border-outline-variant font-semibold text-xs shadow-sm transition">
                <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                <span>Jadwal Pelayan (Rota)</span>
            </a>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-primary text-white hover:bg-primary/90 font-bold text-xs shadow-sm transition">
                <span class="material-symbols-outlined text-[18px]">dashboard</span>
                <span>Dashboard Admin</span>
            </a>
        </div>
    </div>

    <!-- Pilih Jadwal Ibadah Minggu Berjalan -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-outline-variant/30 space-y-4">
        <form method="GET" action="{{ route('admin.presensi.index') }}" class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="flex-1 space-y-1.5">
                <label for="jadwal_id" class="text-xs font-bold text-primary uppercase tracking-wider flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-secondary text-[18px]">event_available</span>
                    Pilih Jadwal Ibadah Mingguan:
                </label>
                <select name="jadwal_id" id="jadwal_id" onchange="this.form.submit()" class="w-full text-xs font-medium rounded-xl border-outline-variant/50 focus:border-secondary focus:ring-secondary py-2.5 px-3 bg-surface-container-low text-primary">
                    @forelse($allJadwal as $j)
                    <option value="{{ $j->id }}" {{ $jadwal && $jadwal->id === $j->id ? 'selected' : '' }}>
                        {{ $j->tanggal_pelayanan->translatedFormat('l, d F Y') }} — {{ $j->sesiIbadah->nama_ibadah ?? 'Ibadah Raya' }} ({{ $j->petugas->count() }} Petugas)
                    </option>
                    @empty
                    <option value="">Belum ada jadwal pelayanan dibuat</option>
                    @endforelse
                </select>
            </div>

            <div class="w-full md:w-48 space-y-1.5">
                <label for="year" class="text-xs font-bold text-primary uppercase tracking-wider flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-secondary text-[18px]">calendar_today</span>
                    Tahun Evaluasi:
                </label>
                <select name="year" id="year" onchange="this.form.submit()" class="w-full text-xs font-medium rounded-xl border-outline-variant/50 focus:border-secondary focus:ring-secondary py-2.5 px-3 bg-surface-container-low text-primary">
                    <option value="2026" {{ $selectedYear == 2026 ? 'selected' : '' }}>Tahun 2026</option>
                    <option value="2025" {{ $selectedYear == 2025 ? 'selected' : '' }}>Tahun 2025</option>
                </select>
            </div>
        </form>

        @if($jadwal)
        <div class="p-4 rounded-xl bg-primary/5 border border-primary/10 flex flex-col md:flex-row md:items-center justify-between gap-3 text-xs">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-primary text-sm">{{ $jadwal->tanggal_pelayanan->translatedFormat('l, d F Y') }}</span>
                    <span class="px-2 py-0.5 rounded bg-primary text-white text-[10px] font-bold">{{ $jadwal->sesiIbadah->nama_ibadah ?? 'Ibadah Raya' }}</span>
                </div>
                <p class="text-outline">
                    Tema: <strong class="text-primary">{{ $jadwal->tema_ibadah ?? 'Ibadah Raya GBI Shekinah Glory' }}</strong> • 
                    Pengkhotbah: <strong class="text-primary">{{ $jadwal->pengkhotbah ?? 'Pdt. Markus Suryanata' }}</strong>
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="setAllHadir()" class="px-3 py-1.5 rounded-lg bg-green-100 hover:bg-green-200 text-green-800 font-bold text-xs transition flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">done_all</span>
                    <span>Tandai Semua Hadir</span>
                </button>
            </div>
        </div>
        @endif
    </div>

    <!-- Form Lembar Presensi Mingguan -->
    @if($jadwal && $jadwal->petugas->isNotEmpty())
    <form method="POST" action="{{ route('admin.presensi.store') }}" class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
        @csrf
        <input type="hidden" name="jadwal_pelayanan_id" value="{{ $jadwal->id }}">

        <div class="p-5 border-b border-outline-variant/20 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="font-serif-display text-lg font-bold text-primary">Daftar Kehadiran Petugas Ibadah ({{ $jadwal->petugas->count() }} Jiwa)</h3>
                <p class="text-xs text-outline">Daftar nama otomatis dimuat dari jadwal pelayanan yang telah diatur (AC-2207).</p>
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-secondary hover:bg-secondary/90 text-white font-bold text-xs shadow-md transition flex items-center gap-2 self-start sm:self-auto">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span>Simpan Presensi Mingguan</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/20 text-outline font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Posisi / Penugasan</th>
                        <th class="py-3 px-4">Nama Pelayan</th>
                        <th class="py-3 px-4">Status Kehadiran</th>
                        <th class="py-3 px-4">Catatan / Keterangan</th>
                        <th class="py-3 px-4 text-center">Sinkronisasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20 text-on-surface">
                    @foreach($jadwal->petugas as $index => $tugas)
                    @php
                        $pres = $existingPresensi->get($tugas->id);
                        $currentStatus = $pres ? $pres->kehadiran : 'hadir';
                    @endphp
                    <tr class="hover:bg-surface-container-lowest/60 transition">
                        <td class="py-3.5 px-4 text-center font-bold text-outline">{{ $index + 1 }}</td>
                        <td class="py-3.5 px-4 font-semibold text-primary">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-surface-container text-primary font-bold text-[11px]">
                                {{ $posisiLabels[$tugas->posisi] ?? ucfirst($tugas->posisi) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-primary text-sm">
                            {{ $tugas->nama_pelayan }}
                            @if($tugas->jemaat)
                                <span class="block text-[11px] font-normal text-outline">NIJ: {{ $tugas->jemaat->nomor_induk }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <label class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border cursor-pointer text-xs font-semibold {{ $currentStatus === 'hadir' ? 'bg-green-50 border-green-400 text-green-800' : 'bg-white border-outline-variant/40 text-outline' }}">
                                    <input type="radio" name="presensi[{{ $tugas->id }}][kehadiran]" value="hadir" {{ $currentStatus === 'hadir' ? 'checked' : '' }} class="text-green-600 focus:ring-green-500 status-radio status-hadir">
                                    <span>Hadir</span>
                                </label>
                                <label class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border cursor-pointer text-xs font-semibold {{ $currentStatus === 'izin' ? 'bg-amber-50 border-amber-400 text-amber-800' : 'bg-white border-outline-variant/40 text-outline' }}">
                                    <input type="radio" name="presensi[{{ $tugas->id }}][kehadiran]" value="izin" {{ $currentStatus === 'izin' ? 'checked' : '' }} class="text-amber-600 focus:ring-amber-500 status-radio">
                                    <span>Izin</span>
                                </label>
                                <label class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border cursor-pointer text-xs font-semibold {{ $currentStatus === 'sakit' ? 'bg-blue-50 border-blue-400 text-blue-800' : 'bg-white border-outline-variant/40 text-outline' }}">
                                    <input type="radio" name="presensi[{{ $tugas->id }}][kehadiran]" value="sakit" {{ $currentStatus === 'sakit' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500 status-radio">
                                    <span>Sakit</span>
                                </label>
                                <label class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border cursor-pointer text-xs font-semibold {{ $currentStatus === 'alpa' ? 'bg-red-50 border-red-400 text-red-800' : 'bg-white border-outline-variant/40 text-outline' }}">
                                    <input type="radio" name="presensi[{{ $tugas->id }}][kehadiran]" value="alpa" {{ $currentStatus === 'alpa' ? 'checked' : '' }} class="text-red-600 focus:ring-red-500 status-radio">
                                    <span>Alpa</span>
                                </label>
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            <input type="text" name="presensi[{{ $tugas->id }}][catatan]" value="{{ $pres?->catatan ?? '' }}" placeholder="Catatan khusus / pergantian..." class="w-full text-xs rounded-lg border-outline-variant/40 py-1.5 px-2.5 focus:border-secondary focus:ring-secondary">
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($pres)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-100 text-green-800 text-[10px] font-bold">
                                    <span class="material-symbols-outlined text-[13px]">check_circle</span>
                                    Tersimpan
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">
                                    <span class="material-symbols-outlined text-[13px]">pending</span>
                                    Belum Input
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-surface-container-low border-t border-outline-variant/20 flex items-center justify-between">
            <span class="text-xs text-outline">* Presensi langsung tersimpan dan mengkalkulasi skor keaktifan pelayan tahunan secara otomatis.</span>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-secondary hover:bg-secondary/90 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span>Simpan Presensi Mingguan</span>
            </button>
        </div>
    </form>
    @else
    <div class="bg-white rounded-2xl p-10 shadow-sm border border-outline-variant/30 text-center space-y-3">
        <span class="material-symbols-outlined text-outline text-[48px]">event_busy</span>
        <h3 class="font-bold text-base text-primary">Tidak Ada Petugas Terjadwal</h3>
        <p class="text-xs text-outline max-w-md mx-auto">Silakan buat atau pilih jadwal pelayanan yang telah diisi petugasnya terlebih dahulu pada modul Service Rota.</p>
        <a href="{{ route('admin.pelayanan.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-secondary text-white font-bold text-xs shadow-md">
            <span class="material-symbols-outlined text-[18px]">add</span>
            <span>+ Buat Jadwal Rota Baru</span>
        </a>
    </div>
    @endif

    <!-- ======================================================== -->
    <!-- ALAT UKUR KEAKTIFAN PELAYAN TAHUNAN (PRD 2.4 B) -->
    <!-- ======================================================== -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-outline-variant/20 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[24px]">leaderboard</span>
                </div>
                <div>
                    <h3 class="font-serif-display text-lg font-bold text-primary">Alat Ukur Keaktifan Pelayan Tahunan ({{ $selectedYear }})</h3>
                    <p class="text-xs text-on-surface-variant">Akumulasi presensi mingguan yang dikalkulasi menjadi skor persentase komitmen melayani.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full bg-secondary-container text-secondary text-xs font-bold">
                    Total {{ count($annualStats['ministers']) }} Pelayan Terdata
                </span>
            </div>
        </div>

        <!-- 3 Executive Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 flex items-center justify-between">
                <div>
                    <span class="text-2xs font-bold text-outline uppercase tracking-wider block">Total Presensi Masuk</span>
                    <span class="text-2xl font-bold font-serif-display text-primary mt-0.5 block">{{ number_format($annualStats['totalEntries']) }}</span>
                    <span class="text-2xs text-outline">Rekap sesi tugas {{ $selectedYear }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">fact_check</span>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 flex items-center justify-between">
                <div>
                    <span class="text-2xs font-bold text-outline uppercase tracking-wider block">Total Kehadiran Aktif</span>
                    <span class="text-2xl font-bold font-serif-display text-green-700 mt-0.5 block">{{ number_format($annualStats['totalHadir']) }}</span>
                    <span class="text-2xs text-outline">Pelayan bertugas di altar</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">how_to_reg</span>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 flex items-center justify-between">
                <div>
                    <span class="text-2xs font-bold text-outline uppercase tracking-wider block">Rata-rata Komitmen</span>
                    @php
                        $avgScore = count($annualStats['ministers']) > 0 ? round(collect($annualStats['ministers'])->avg('skor_keaktifan'), 1) : 0;
                    @endphp
                    <span class="text-2xl font-bold font-serif-display text-amber-600 mt-0.5 block">{{ $avgScore }}%</span>
                    <span class="text-2xs text-outline">Skor loyalitas pelayanan</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">workspace_premium</span>
                </div>
            </div>
        </div>

        <!-- Tabel Peringkat Komitmen Pelayan -->
        <div class="space-y-3 pt-2">
            <h4 class="font-bold text-xs uppercase tracking-wider text-primary flex items-center gap-1.5">
                <span class="material-symbols-outlined text-secondary text-[18px]">military_tech</span>
                Peringkat &amp; Skor Keaktifan Pelayan (Top Evaluasi)
            </h4>

            <div class="overflow-x-auto rounded-xl border border-outline-variant/20">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant/20 text-outline font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3 px-4 w-12 text-center">Rank</th>
                            <th class="py-3 px-4">Nama Pelayan</th>
                            <th class="py-3 px-4">Posisi Utama</th>
                            <th class="py-3 px-4 text-center">Total Tugas</th>
                            <th class="py-3 px-4 text-center">Hadir</th>
                            <th class="py-3 px-4 text-center">Izin / Sakit</th>
                            <th class="py-3 px-4 text-center">Alpa</th>
                            <th class="py-3 px-4">Skor Keaktifan (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20 text-on-surface">
                        @forelse($annualStats['topMinisters'] as $rank => $m)
                        <tr class="hover:bg-surface-container-lowest transition">
                            <td class="py-3 px-4 text-center font-bold">
                                @if($rank === 0)
                                    <span class="w-6 h-6 rounded-full bg-amber-400 text-primary inline-flex items-center justify-center font-bold text-xs">1</span>
                                @elseif($rank === 1)
                                    <span class="w-6 h-6 rounded-full bg-gray-300 text-primary inline-flex items-center justify-center font-bold text-xs">2</span>
                                @elseif($rank === 2)
                                    <span class="w-6 h-6 rounded-full bg-amber-700 text-white inline-flex items-center justify-center font-bold text-xs">3</span>
                                @else
                                    <span class="text-outline">{{ $rank + 1 }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-bold text-primary">{{ $m['nama'] }}</td>
                            <td class="py-3 px-4 text-outline">{{ $m['peran'] }}</td>
                            <td class="py-3 px-4 text-center font-semibold text-primary">{{ $m['total_jadwal'] }}x</td>
                            <td class="py-3 px-4 text-center font-bold text-green-700">{{ $m['hadir'] }}</td>
                            <td class="py-3 px-4 text-center text-amber-700">{{ $m['izin'] + $m['sakit'] }}</td>
                            <td class="py-3 px-4 text-center text-red-600 font-semibold">{{ $m['alpa'] }}</td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-32 bg-surface-container rounded-full h-2 overflow-hidden">
                                        <div class="bg-secondary h-2 rounded-full" style="width: {{ $m['skor_keaktifan'] }}%"></div>
                                    </div>
                                    <span class="font-bold text-secondary text-xs">{{ $m['skor_keaktifan'] }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-outline">Belum ada rekapitulasi data presensi untuk tahun {{ $selectedYear }}.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function setAllHadir() {
    document.querySelectorAll('.status-hadir').forEach(function(el) {
        el.checked = true;
    });
}
</script>
@endpush
@endsection
