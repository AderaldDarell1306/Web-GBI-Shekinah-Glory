@extends('layouts.admin')

@section('title', 'Manajemen Keuangan & Persembahan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Keuangan & Persembahan</h1>
            <p class="text-sm text-gray-500">Pencatatan kas gereja, kolekte ibadah raya, dana pembangunan, dan operasional (Tanpa Perpuluhan - PRD v2.1).</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.keuangan.laporan', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-amber-900 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition shadow-sm">
                <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Laporan Kas
            </a>
            <button type="button" onclick="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Catat Transaksi
            </button>
        </div>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Saldo Kas Total -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Kas Gereja</span>
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalSaldoKas, 0, ',', '.') }}</div>
            <p class="text-xs text-gray-400 mt-1">Saldo kumulatif keseluruhan</p>
        </div>

@php
    $bulanIndo = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
    $labelPeriodeAktif = !empty($bulan) ? ($bulanIndo[sprintf('%02d', (int)$bulan)] ?? $bulan) . ' ' . $tahun : 'Tahun ' . $tahun;
@endphp

        <!-- Pemasukan Periode -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pemasukan ({{ $labelPeriodeAktif }})</span>
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-emerald-600">Rp {{ number_format($pemasukanPeriode, 0, ',', '.') }}</div>
            <p class="text-xs text-gray-400 mt-1">{{ $labelPeriodeAktif }}</p>
        </div>

        <!-- Pengeluaran Periode -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengeluaran ({{ $labelPeriodeAktif }})</span>
                <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-rose-600">Rp {{ number_format($pengeluaranPeriode, 0, ',', '.') }}</div>
            <p class="text-xs text-gray-400 mt-1">Beban operasional & pelayanan</p>
        </div>

        <!-- Surplus / Defisit -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Surplus / Defisit</span>
                <div class="w-9 h-9 rounded-lg {{ $saldoPeriode >= 0 ? 'bg-amber-50 text-amber-600' : 'bg-red-50 text-red-600' }} flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-bold {{ $saldoPeriode >= 0 ? 'text-amber-800' : 'text-red-600' }}">
                Rp {{ number_format($saldoPeriode, 0, ',', '.') }}
            </div>
            <p class="text-xs text-gray-400 mt-1">Net kas periode ini</p>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-xs">
        <form method="GET" action="{{ route('admin.keuangan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-3">
            <!-- Bulan -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Bulan</label>
                <select name="bulan" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    <option value="" {{ empty($bulan) ? 'selected' : '' }}>Semua Bulan (Sepanjang {{ $tahun }})</option>
                    @for($m = 1; $m <= 12; $m++)
                        @php $mStr = sprintf('%02d', $m); @endphp
                        <option value="{{ $mStr }}" {{ (!empty($bulan) && $bulan == $mStr) ? 'selected' : '' }}>
                            {{ $bulanIndo[$mStr] }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Tahun -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tahun</label>
                <select name="tahun" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    @for($y = date('Y') + 1; $y >= 2024; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <!-- Jenis -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Jenis</label>
                <select name="jenis" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    <option value="">Semua Jenis</option>
                    <option value="pemasukan" {{ $jenis == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="pengeluaran" {{ $jenis == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Pos Anggaran</label>
                <select name="kategori" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    <option value="">Semua Pos (Tanpa Perpuluhan)</option>
                    <option value="persembahan_raya" {{ $kategori == 'persembahan_raya' ? 'selected' : '' }}>Persembahan Raya</option>
                    <option value="pembangunan" {{ $kategori == 'pembangunan' ? 'selected' : '' }}>Pembangunan</option>
                    <option value="diakonia" {{ $kategori == 'diakonia' ? 'selected' : '' }}>Diakonia</option>
                    <option value="operasional" {{ $kategori == 'operasional' ? 'selected' : '' }}>Operasional</option>
                    <option value="ucapan_syukur" {{ $kategori == 'ucapan_syukur' ? 'selected' : '' }}>Ucapan Syukur</option>
                    <option value="pembicara_tamu" {{ $kategori == 'pembicara_tamu' ? 'selected' : '' }}>Honorarium & Tamu</option>
                    <option value="lainnya" {{ $kategori == 'lainnya' ? 'selected' : '' }}>Lain-lain</option>
                </select>
            </div>

            <!-- Search -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Cari Keterangan</label>
                <input type="text" name="q" value="{{ $q }}" placeholder="Uraian, kode, dll..." class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full py-2 px-3 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-dark transition">
                    Filter
                </button>
                <a href="{{ route('admin.keuangan.index') }}" class="py-2 px-3 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- ======================================================== -->
    <!-- VISUALISASI GRAFIK KEUANGAN & DIAGRAM BULAT (PRD v2.1 AC-R02) -->
    <!-- ======================================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Grafik 1: Multi-Periode Bar Chart (lg:col-span-7) -->
        <div class="lg:col-span-7 bg-white rounded-2xl p-6 border border-gray-100 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-amber-50 text-secondary flex items-center justify-center font-bold">
                        <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                    </span>
                    <div>
                        <h3 class="font-serif-display text-base font-bold text-gray-900">Grafik Keuangan Multi-Periode</h3>
                        <p class="text-xs text-gray-500">Pemasukan vs Pengeluaran</p>
                    </div>
                </div>

                <!-- Filter Multi-Periode: Bulanan, Semester, Tahunan -->
                <div class="inline-flex items-center bg-gray-100 p-1 rounded-xl gap-1">
                    <a href="{{ route('admin.keuangan.index', array_merge(request()->query(), ['periode_grafik' => 'bulanan'])) }}" class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ ($periodeGrafik ?? 'bulanan') === 'bulanan' ? 'bg-primary text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                        Bulanan
                    </a>
                    <a href="{{ route('admin.keuangan.index', array_merge(request()->query(), ['periode_grafik' => 'semester'])) }}" class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ ($periodeGrafik ?? '') === 'semester' ? 'bg-primary text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                        Semester
                    </a>
                    <a href="{{ route('admin.keuangan.index', array_merge(request()->query(), ['periode_grafik' => 'tahunan'])) }}" class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ ($periodeGrafik ?? '') === 'tahunan' ? 'bg-primary text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                        Tahunan
                    </a>
                </div>
            </div>

            <div class="relative w-full h-64">
                <canvas id="chartKeuanganMultiPeriode"></canvas>
            </div>

            <div class="flex flex-wrap items-center justify-between text-xs text-gray-500 pt-2 border-t border-gray-100">
                <span class="flex items-center gap-1.5 font-semibold text-emerald-700">
                    <span class="w-2.5 h-2.5 rounded bg-emerald-500"></span> Total Masuk: Rp {{ number_format(array_sum($chartPemasukanValues ?? []), 0, ',', '.') }}
                </span>
                <span class="flex items-center gap-1.5 font-semibold text-rose-700">
                    <span class="w-2.5 h-2.5 rounded bg-rose-500"></span> Total Keluar: Rp {{ number_format(array_sum($chartPengeluaranValues ?? []), 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Grafik 2: Diagram Bulat Keuangan (lg:col-span-5) -->
        <div class="lg:col-span-5 bg-white rounded-2xl p-6 border border-gray-100 shadow-xs space-y-4 flex flex-col justify-between">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                        <span class="material-symbols-outlined text-[18px]">donut_large</span>
                    </span>
                    <div>
                        <h3 class="font-serif-display text-base font-bold text-gray-900">Diagram Bulat Keuangan</h3>
                        <p class="text-xs text-gray-500">Proporsi Kas Periode Ini</p>
                    </div>
                </div>
                <span class="text-2xs font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-full">Angka di Dalam</span>
            </div>

            <div class="relative w-full h-56 flex items-center justify-center my-auto">
                <canvas id="chartKeuanganDonut"></canvas>
            </div>

            <div class="grid grid-cols-2 gap-2 text-2xs pt-2 border-t border-gray-100">
                <div class="p-2 rounded-lg bg-emerald-50 border border-emerald-100">
                    <span class="text-emerald-800 font-bold block">Pemasukan</span>
                    <span class="text-xs font-bold text-emerald-700 font-mono">Rp {{ number_format($pemasukanPeriode, 0, ',', '.') }}</span>
                </div>
                <div class="p-2 rounded-lg bg-rose-50 border border-rose-100">
                    <span class="text-rose-800 font-bold block">Pengeluaran</span>
                    <span class="text-xs font-bold text-rose-700 font-mono">Rp {{ number_format($pengeluaranPeriode, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table of Transactions -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal & Kode</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pos / Kategori</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sumber / Penerima</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Metode</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Nominal</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($transaksi as $item)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $item->tanggal_transaksi->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-400 font-mono">{{ $item->kode_transaksi }}</div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->jenis == 'pemasukan' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                    {{ $item->jenis == 'pemasukan' ? 'Masuk' : 'Keluar' }} &bull; {{ $item->kategori_label }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $item->sumber_penerima ?? '-' }}</div>
                                @if($item->keterangan)
                                    <div class="text-xs text-gray-500 line-clamp-1">{{ $item->keterangan }}</div>
                                @endif
                                @if($item->no_bukti_nota)
                                    <div class="text-xs text-gray-400">No. Bukti: {{ $item->no_bukti_nota }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="text-xs font-semibold text-gray-600 uppercase">{{ $item->metode_pembayaran }}</span>
                                @if($item->lampiran_file)
                                    <a href="{{ asset('storage/' . $item->lampiran_file) }}" target="_blank" class="block text-xs text-primary hover:underline mt-0.5">
                                        Lihat Nota
                                    </a>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-bold {{ $item->jenis == 'pemasukan' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $item->jenis == 'pemasukan' ? '+' : '-' }} Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="inline-flex items-center gap-2">
                                    <button type="button" onclick='openEditModal(@json($item))' class="text-gray-500 hover:text-primary transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('admin.keuangan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan transaksi ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-rose-600 transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="text-sm font-medium text-gray-900">Belum ada transaksi pada periode ini</p>
                                    <p class="text-xs text-gray-400 mt-1">Silakan gunakan tombol "Catat Transaksi" untuk menambahkan catatan baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transaksi->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $transaksi->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Catat Transaksi Baru -->
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl relative animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Catat Transaksi Keuangan</h3>
            <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('admin.keuangan.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Transaksi *</label>
                    <input type="date" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Jenis *</label>
                    <select name="jenis" id="modal_jenis" required onchange="adjustCategories(this.value, 'modal_kategori')" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="pemasukan">Pemasukan (Kas Masuk)</option>
                        <option value="pengeluaran">Pengeluaran (Kas Keluar)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pos Anggaran *</label>
                    <select name="kategori" id="modal_kategori" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="persembahan_raya">Persembahan Raya (Minggu)</option>
                        <option value="pembangunan">Dana Pembangunan</option>
                        <option value="diakonia">Pelayanan Diakonia</option>
                        <option value="ucapan_syukur">Ucapan Syukur</option>
                        <option value="operasional">Operasional</option>
                        <option value="pembicara_tamu">Honorarium & Tamu</option>
                        <option value="lainnya">Lain-lain</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Metode Pembayaran *</label>
                    <select name="metode_pembayaran" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="tunai">Tunai / Amplop</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="qris">QRIS / Online</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nominal (Rp) *</label>
                <input type="number" name="nominal" min="1" step="1" required placeholder="Contoh: 500000" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Sumber / Penerima</label>
                <input type="text" name="sumber_penerima" placeholder="Misal: Amplop Kolekte Sesi 1, Pembelian Sound, dll" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Keterangan / Uraian</label>
                <textarea name="keterangan" rows="2" placeholder="Catatan opsional..." class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">No. Bukti / Nota</label>
                    <input type="text" name="no_bukti_nota" placeholder="Contoh: NOTA-128" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Upload Bukti / Nota</label>
                    <input type="file" name="lampiran_file" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark">Simpan Transaksi</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Transaksi -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl relative animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Edit Catatan Transaksi</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Transaksi *</label>
                    <input type="date" name="tanggal_transaksi" id="edit_tanggal" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Jenis *</label>
                    <select name="jenis" id="edit_jenis" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="pemasukan">Pemasukan (Kas Masuk)</option>
                        <option value="pengeluaran">Pengeluaran (Kas Keluar)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pos Anggaran *</label>
                    <select name="kategori" id="edit_kategori" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="persembahan_raya">Persembahan Raya (Minggu)</option>
                        <option value="pembangunan">Dana Pembangunan</option>
                        <option value="diakonia">Pelayanan Diakonia</option>
                        <option value="ucapan_syukur">Ucapan Syukur</option>
                        <option value="operasional">Operasional</option>
                        <option value="pembicara_tamu">Honorarium & Tamu</option>
                        <option value="lainnya">Lain-lain</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Metode Pembayaran *</label>
                    <select name="metode_pembayaran" id="edit_metode" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="tunai">Tunai / Amplop</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="qris">QRIS / Online</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nominal (Rp) *</label>
                <input type="number" name="nominal" id="edit_nominal" min="1" step="1" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Sumber / Penerima</label>
                <input type="text" name="sumber_penerima" id="edit_sumber" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Keterangan / Uraian</label>
                <textarea name="keterangan" id="edit_keterangan" rows="2" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">No. Bukti / Nota</label>
                    <input type="text" name="no_bukti_nota" id="edit_nobukti" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Ganti Nota (Opsional)</label>
                    <input type="file" name="lampiran_file" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}
function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}
function openEditModal(item) {
    const form = document.getElementById('editForm');
    form.action = `/admin/keuangan/${item.id}`;
    
    // Format YYYY-MM-DD
    const tgl = item.tanggal_transaksi ? item.tanggal_transaksi.split('T')[0] : '';
    document.getElementById('edit_tanggal').value = tgl;
    document.getElementById('edit_jenis').value = item.jenis;
    document.getElementById('edit_kategori').value = item.kategori;
    document.getElementById('edit_metode').value = item.metode_pembayaran;
    document.getElementById('edit_nominal').value = Math.round(item.nominal);
    document.getElementById('edit_sumber').value = item.sumber_penerima || '';
    document.getElementById('edit_keterangan').value = item.keterangan || '';
    document.getElementById('edit_nobukti').value = item.no_bukti_nota || '';

    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Chart Keuangan Multi-Periode (Bulanan, Semester, Tahunan)
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chartKeuanganMultiPeriode');
    if (ctx) {
        const labels = @json($chartPeriodeLabels ?? []);
        const pemasukan = @json($chartPemasukanValues ?? []);
        const pengeluaran = @json($chartPengeluaranValues ?? []);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pemasukan (Rp)',
                        data: pemasukan,
                        backgroundColor: '#10b981', // emerald-500
                        borderRadius: 6,
                        maxBarThickness: 32
                    },
                    {
                        label: 'Pengeluaran (Rp)',
                        data: pengeluaran,
                        backgroundColor: '#f43f5e', // rose-500
                        borderRadius: 6,
                        maxBarThickness: 32
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + ' Jt';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + ' Rb';
                                }
                                return 'Rp ' + value;
                            }
                        },
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // 2. Chart Keuangan Donut (Diagram Bulat Keuangan)
    const ctxDonut = document.getElementById('chartKeuanganDonut');
    if (ctxDonut) {
        const donutSlicePlugin = {
            id: 'keuanganDonutSlicePlugin',
            afterDatasetsDraw(chart) {
                const { ctx, data } = chart;
                const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                chart.getDatasetMeta(0).data.forEach((datapoint, index) => {
                    const val = data.datasets[0].data[index];
                    if (val > 0 && total > 0) {
                        const pct = Math.round((val / total) * 100);
                        const { x, y } = datapoint.tooltipPosition();
                        ctx.save();
                        ctx.fillStyle = '#ffffff';
                        ctx.font = 'bold 12px sans-serif';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.shadowColor = 'rgba(0, 0, 0, 0.8)';
                        ctx.shadowBlur = 4;
                        ctx.fillText(pct + '%', x, y);
                        ctx.restore();
                    }
                });
            }
        };

        const donutCenterPlugin = {
            id: 'keuanganDonutCenterPlugin',
            beforeDraw(chart) {
                const { ctx, chartArea } = chart;
                if (!chartArea) return;
                const { top, bottom, left, right, width, height } = chartArea;
                ctx.save();
                ctx.font = 'bold 13px sans-serif';
                ctx.fillStyle = '#001428';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText('Arus Kas', left + width / 2, top + height / 2 - 8);
                ctx.font = '10px sans-serif';
                ctx.fillStyle = '#10b981';
                ctx.fillText('{{ $bulan ? \Carbon\Carbon::createFromDate(null, (int)$bulan, 1)->translatedFormat('M') : 'Total' }} {{ $tahun }}', left + width / 2, top + height / 2 + 10);
                ctx.restore();
            }
        };

        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Pemasukan Kas', 'Pengeluaran Kas'],
                datasets: [{
                    data: [{{ (float)$pemasukanPeriode }}, {{ (float)$pengeluaranPeriode }}],
                    backgroundColor: ['#10b981', '#f43f5e'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            plugins: [donutSlicePlugin, donutCenterPlugin],
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { size: 11, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': Rp ' + Number(context.raw).toLocaleString('id-ID');
                            }
                        }
                    }
                },
                cutout: '62%'
            }
        });
    }
});
</script>
@endsection

