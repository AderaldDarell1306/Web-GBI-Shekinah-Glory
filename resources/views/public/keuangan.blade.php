@extends('layouts.public')

@section('title', 'Transparansi Laporan Keuangan - GBI Shekinah Glory')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <!-- Header Section -->
    <div class="text-center max-w-3xl mx-auto mb-12" data-aos="fade-up">
        <span class="text-xs font-bold uppercase tracking-widest text-secondary mb-2 block">Akuntabilitas &amp; Pertanggungjawaban Kas</span>
        <h1 class="font-serif-display text-3xl sm:text-4xl lg:text-5xl font-bold text-primary mb-4">Laporan Keuangan Gereja</h1>
        <p class="text-sm sm:text-base text-on-surface-variant leading-relaxed">
            Transparansi penerimaan persembahan dan pengeluaran operasional peribadatan GBI Shekinah Glory Namosain. Sesuai aturan gereja (PRD v2.1 Pasal 2.3 &amp; 2.6), laporan kas terbuka secara transparan dan bebas dari pos perpuluhan pribadi.
        </p>
        <div class="w-20 h-1 bg-secondary mx-auto mt-6 rounded-full"></div>
    </div>

    <!-- Summary Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-10" data-aos="fade-up" data-aos-delay="100">
        <!-- Pemasukan -->
        <div class="bg-white rounded-2xl p-6 border border-emerald-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-800">Pemasukan Periode Ini</p>
                <h3 class="font-serif-display text-2xl sm:text-3xl font-bold text-emerald-700 mt-1">
                    Rp {{ number_format($pemasukanPeriode, 0, ',', '.') }}
                </h3>
                <span class="text-2xs text-outline mt-1 block">Bulan {{ $bulan ? \Carbon\Carbon::createFromDate(null, (int)$bulan, 1)->translatedFormat('F') : 'Semua' }} {{ $tahun }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[26px]">arrow_downward</span>
            </div>
        </div>

        <!-- Pengeluaran -->
        <div class="bg-white rounded-2xl p-6 border border-rose-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-rose-800">Pengeluaran Periode Ini</p>
                <h3 class="font-serif-display text-2xl sm:text-3xl font-bold text-rose-700 mt-1">
                    Rp {{ number_format($pengeluaranPeriode, 0, ',', '.') }}
                </h3>
                <span class="text-2xs text-outline mt-1 block">Bulan {{ $bulan ? \Carbon\Carbon::createFromDate(null, (int)$bulan, 1)->translatedFormat('F') : 'Semua' }} {{ $tahun }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[26px]">arrow_upward</span>
            </div>
        </div>

        <!-- Saldo Bersih Kas -->
        <div class="bg-white rounded-2xl p-6 border border-primary/20 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-primary">Saldo Bersih Berjalan</p>
                <h3 class="font-serif-display text-2xl sm:text-3xl font-bold {{ $saldoPeriode >= 0 ? 'text-primary' : 'text-rose-700' }} mt-1">
                    Rp {{ number_format($saldoPeriode, 0, ',', '.') }}
                </h3>
                <span class="text-2xs text-secondary font-semibold mt-1 block">Total Kas Gereja: Rp {{ number_format($totalSaldoKas, 0, ',', '.') }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-primary-container text-secondary-container flex items-center justify-center">
                <span class="material-symbols-outlined text-[26px]">account_balance_wallet</span>
            </div>
        </div>
    </div>

    <!-- Category Breakdown Overview & Diagram Bulat -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-10" data-aos="fade-up" data-aos-delay="150">
        <!-- Diagram Bulat Arus Kas -->
        <div class="lg:col-span-4 bg-white rounded-2xl p-6 border border-outline-variant/30 shadow-xs flex flex-col justify-between">
            <h4 class="font-serif-display text-base font-bold text-primary flex items-center justify-between border-b border-outline-variant/20 pb-3 mb-4">
                <span class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px] text-secondary">donut_large</span>
                    <span>Diagram Bulat Kas</span>
                </span>
                <span class="text-2xs font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-full">Angka di Dalam</span>
            </h4>
            <div class="relative w-full h-52 flex items-center justify-center my-auto">
                <canvas id="publicChartKeuanganDonut"></canvas>
            </div>
            <div class="grid grid-cols-2 gap-2 text-2xs pt-3 mt-2 border-t border-outline-variant/20">
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

        <!-- Pemasukan Categories -->
        <div class="lg:col-span-4 bg-white rounded-2xl p-6 border border-outline-variant/30 shadow-xs flex flex-col justify-between">
            <div>
                <h4 class="font-serif-display text-base font-bold text-emerald-800 flex items-center gap-2 mb-4 border-b border-outline-variant/20 pb-3">
                    <span class="material-symbols-outlined text-[20px]">savings</span>
                    <span>Rincian Pemasukan Kas</span>
                </h4>
                <div class="space-y-3">
                    @forelse($breakdownPemasukan as $kat => $tot)
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-on-surface font-medium capitalize">{{ str_replace('_', ' ', $kat) }}</span>
                        <span class="font-bold text-emerald-700 font-mono">Rp {{ number_format($tot, 0, ',', '.') }}</span>
                    </div>
                    @empty
                    <p class="text-xs text-outline italic">Belum ada data pemasukan pada periode ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pengeluaran Categories -->
        <div class="lg:col-span-4 bg-white rounded-2xl p-6 border border-outline-variant/30 shadow-xs flex flex-col justify-between">
            <div>
                <h4 class="font-serif-display text-base font-bold text-rose-800 flex items-center gap-2 mb-4 border-b border-outline-variant/20 pb-3">
                    <span class="material-symbols-outlined text-[20px]">shopping_cart_checkout</span>
                    <span>Rincian Pengeluaran Kas</span>
                </h4>
                <div class="space-y-3">
                    @forelse($breakdownPengeluaran as $kat => $tot)
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-on-surface font-medium capitalize">{{ str_replace('_', ' ', $kat) }}</span>
                        <span class="font-bold text-rose-700 font-mono">Rp {{ number_format($tot, 0, ',', '.') }}</span>
                    </div>
                    @empty
                    <p class="text-xs text-outline italic">Belum ada data pengeluaran pada periode ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-outline-variant/30 mb-8" data-aos="fade-up" data-aos-delay="200">
        <form method="GET" action="{{ route('public.keuangan') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 sm:gap-4 items-end">
            <div class="lg:col-span-4">
                <label class="block text-xs font-bold uppercase text-primary mb-1">Pilih Bulan</label>
                <select name="bulan" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant text-xs bg-white focus:border-secondary focus:outline-none">
                    <option value="" {{ empty($bulan) ? 'selected' : '' }}>Semua Bulan (Sepanjang Tahun)</option>
                    @for($m = 1; $m <= 12; $m++)
                        @php $mStr = sprintf('%02d', $m); @endphp
                        <option value="{{ $mStr }}" {{ $bulan == $mStr ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="lg:col-span-3">
                <label class="block text-xs font-bold uppercase text-primary mb-1">Tahun Anggaran</label>
                <select name="tahun" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant text-xs bg-white focus:border-secondary focus:outline-none">
                    @for($y = date('Y') + 1; $y >= 2024; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="lg:col-span-3">
                <label class="block text-xs font-bold uppercase text-primary mb-1">Jenis Arus Kas</label>
                <select name="jenis" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant text-xs bg-white focus:border-secondary focus:outline-none">
                    <option value="">Semua (Pemasukan &amp; Pengeluaran)</option>
                    <option value="pemasukan" {{ $jenis === 'pemasukan' ? 'selected' : '' }}>Pemasukan Saja</option>
                    <option value="pengeluaran" {{ $jenis === 'pengeluaran' ? 'selected' : '' }}>Pengeluaran Saja</option>
                </select>
            </div>

            <div class="lg:col-span-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-primary text-white font-bold text-xs rounded-xl hover:bg-primary-container transition shadow-sm">
                    Tampilkan
                </button>
            </div>
        </form>
    </div>

    <!-- Transaction Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden mb-8" data-aos="fade-up" data-aos-delay="250">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-outline-variant/20 text-xs">
                <thead class="bg-surface-container-low text-primary font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 text-center w-14">No.</th>
                        <th class="py-3.5 px-4 text-left">Tanggal</th>
                        <th class="py-3.5 px-4 text-left">Kode &amp; Kategori</th>
                        <th class="py-3.5 px-4 text-left">Uraian / Keterangan</th>
                        <th class="py-3.5 px-4 text-right">Pemasukan (Rp)</th>
                        <th class="py-3.5 px-4 text-right">Pengeluaran (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10 bg-white">
                    @forelse($transaksi as $idx => $t)
                    <tr class="hover:bg-surface-container-low/50 transition">
                        <td class="py-3 px-4 text-center text-outline font-mono">
                            {{ $transaksi->firstItem() + $idx }}
                        </td>
                        <td class="py-3 px-4 text-on-surface whitespace-nowrap">
                            {{ $t->tanggal_transaksi ? $t->tanggal_transaksi->format('d/m/Y') : '-' }}
                        </td>
                        <td class="py-3 px-4 font-semibold text-primary">
                            <span class="block text-2xs text-outline font-mono">{{ $t->kode_transaksi }}</span>
                            <span>{{ $t->kategori_label }}</span>
                        </td>
                        <td class="py-3 px-4 text-on-surface">
                            <p class="font-medium text-primary">{{ $t->keterangan }}</p>
                            @if($t->sumber_penerima)
                                <span class="block text-2xs text-outline">Sumber/Penerima: {{ $t->sumber_penerima }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-emerald-700 whitespace-nowrap">
                            {{ $t->jenis === 'pemasukan' ? number_format($t->nominal, 0, ',', '.') : '-' }}
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-rose-700 whitespace-nowrap">
                            {{ $t->jenis === 'pengeluaran' ? number_format($t->nominal, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-outline">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[36px] text-outline">receipt_long</span>
                                <span class="font-bold text-sm text-primary">Tidak Ada Data Transaksi Kas</span>
                                <p class="text-xs text-on-surface-variant">Pilih bulan atau tahun lainnya untuk melihat arsip arus kas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transaksi->hasPages())
        <div class="p-4 border-t border-outline-variant/20 bg-surface-container-low/40">
            {{ $transaksi->links() }}
        </div>
        @endif
    </div>

    <!-- Official Accountability Footer Callout -->
    <div class="bg-surface-container-low rounded-2xl p-6 border border-outline-variant/30 flex flex-col sm:flex-row items-center justify-between gap-4" data-aos="fade-up">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary text-secondary-container flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[20px]">verified</span>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-primary">Pertanggungjawaban Resmi</h4>
                <p class="text-xs text-on-surface-variant mt-0.5">
                    Laporan kas telah diaudit dan disahkan oleh Bendahara Jemaat &amp; Gembala Sidang GBI Shekinah Glory Namosain.
                </p>
            </div>
        </div>
        <div class="text-right shrink-0">
            <span class="text-2xs text-outline uppercase font-semibold tracking-wider block">GBI Shekinah Glory</span>
            <span class="text-xs font-bold text-primary">Namosain, Kota Kupang, NTT</span>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('publicChartKeuanganDonut');
    if (ctx) {
        const donutSlicePlugin = {
            id: 'publicDonutSlicePlugin',
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
            id: 'publicDonutCenterPlugin',
            beforeDraw(chart) {
                if (chart.config.type !== 'doughnut') return;
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

        new Chart(ctx, {
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
