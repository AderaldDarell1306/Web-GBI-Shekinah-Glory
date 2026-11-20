@extends('layouts.public')

@section('title', 'Data & Direktori Jemaat - GBI Shekinah Glory')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <!-- Header Section -->
    <div class="text-center max-w-3xl mx-auto mb-12" data-aos="fade-up">
        <span class="text-xs font-bold uppercase tracking-widest text-secondary mb-2 block">Persekutuan & Komunitas Tubuh Kristus</span>
        <h1 class="font-serif-display text-3xl sm:text-4xl lg:text-5xl font-bold text-primary mb-4">Data &amp; Direktori Jemaat</h1>
        <p class="text-sm sm:text-base text-on-surface-variant leading-relaxed">
            Daftar jemaat dan keluarga yang bertumbuh bersama dalam persekutuan iman di GBI Shekinah Glory Namosain. Akses terbuka dan transparan (Read-Only) khusus bagi jemaat gereja.
        </p>
        <div class="w-20 h-1 bg-secondary mx-auto mt-6 rounded-full"></div>
    </div>

    <!-- Quick Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-10" data-aos="fade-up" data-aos-delay="100">
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-outline-variant/30 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-outline">Total Jemaat Aktif</p>
                <h3 class="font-serif-display text-2xl sm:text-3xl font-bold text-primary mt-1">{{ number_format($totalJemaat) }}</h3>
                <span class="text-2xs text-secondary font-semibold">Jiwa Terdaftar</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[26px]">groups</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-outline-variant/30 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-outline">Kepala Keluarga (KK)</p>
                <h3 class="font-serif-display text-2xl sm:text-3xl font-bold text-primary mt-1">{{ number_format($totalKeluarga) }}</h3>
                <span class="text-2xs text-secondary font-semibold">Keluarga</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[26px]">home</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-outline-variant/30 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-outline">Wilayah Pelayanan</p>
                <h3 class="font-serif-display text-2xl sm:text-3xl font-bold text-primary mt-1">{{ $wilayahList->count() }}</h3>
                <span class="text-2xs text-secondary font-semibold">Sektor / Sub-Wilayah</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[26px]">map</span>
            </div>
        </div>
    </div>

    <!-- Kategorial Distribution Badges -->
    <div class="bg-surface-container-low rounded-2xl p-4 sm:p-6 border border-outline-variant/30 mb-8 flex flex-wrap items-center justify-between gap-4" data-aos="fade-up" data-aos-delay="150">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary text-[22px]">pie_chart</span>
            <span class="text-xs font-bold text-primary uppercase tracking-wider">Komposisi Kategori Pelayanan:</span>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                <span class="material-symbols-outlined text-[15px]">man</span>
                <span>Kaum Bapak: {{ $kategorialStats['kaum_bapak'] ?? 0 }}</span>
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                <span class="material-symbols-outlined text-[15px]">woman</span>
                <span>Kaum Ibu: {{ $kategorialStats['kaum_ibu'] ?? 0 }}</span>
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                <span class="material-symbols-outlined text-[15px]">diversity_1</span>
                <span>Pemuda (Youth): {{ $kategorialStats['pemuda_remaja'] ?? 0 }}</span>
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                <span class="material-symbols-outlined text-[15px]">child_care</span>
                <span>Sekolah Minggu (Anak): {{ $kategorialStats['anak'] ?? 0 }}</span>
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                <span class="material-symbols-outlined text-[15px]">elderly</span>
                <span>Lansia: {{ $kategorialStats['lansia_khusus'] ?? 0 }}</span>
            </span>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-outline-variant/30 mb-8" data-aos="fade-up" data-aos-delay="200">
        <form method="GET" action="{{ route('public.jemaat') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 sm:gap-4 items-end">
            <div class="lg:col-span-5">
                <label class="block text-xs font-bold uppercase text-primary mb-1">Cari Nama Jemaat / No. KK</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-[18px] text-outline">search</span>
                    <input type="text" name="q" value="{{ $q }}" placeholder="Ketik nama lengkap atau nomor KK..." class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary focus:outline-none">
                </div>
            </div>

            <div class="lg:col-span-3">
                <label class="block text-xs font-bold uppercase text-primary mb-1">Wilayah Pelayanan</label>
                <select name="wilayah_id" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant text-xs bg-white focus:border-secondary focus:outline-none">
                    <option value="">Semua Wilayah</option>
                    @foreach($wilayahList as $w)
                    <option value="{{ $w->id }}" {{ $wilayahId == $w->id ? 'selected' : '' }}>{{ $w->nama_wilayah }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <label class="block text-xs font-bold uppercase text-primary mb-1">Kategori</label>
                <select name="kategori" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant text-xs bg-white focus:border-secondary focus:outline-none">
                    <option value="">Semua Kategori</option>
                    <option value="bapak" {{ $kategori === 'bapak' ? 'selected' : '' }}>Kaum Bapak</option>
                    <option value="ibu" {{ $kategori === 'ibu' ? 'selected' : '' }}>Kaum Ibu</option>
                    <option value="pemuda" {{ $kategori === 'pemuda' ? 'selected' : '' }}>Pemuda</option>
                    <option value="anak" {{ $kategori === 'anak' ? 'selected' : '' }}>Anak</option>
                    <option value="lansia" {{ $kategori === 'lansia' ? 'selected' : '' }}>Lansia</option>
                </select>
            </div>

            <div class="lg:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-primary text-white font-bold text-xs rounded-xl hover:bg-primary-container transition shadow-sm">
                    Terapkan
                </button>
                @if($q || $wilayahId || $kategori)
                <a href="{{ route('public.jemaat') }}" class="py-2.5 px-3 bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-semibold rounded-xl transition" title="Reset Filter">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden mb-8" data-aos="fade-up" data-aos-delay="250">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-outline-variant/20 text-xs">
                <thead class="bg-surface-container-low text-primary font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 text-center w-14">No.</th>
                        <th class="py-3.5 px-4 text-left">Nama Lengkap Jemaat</th>
                        <th class="py-3.5 px-4 text-left">Wilayah Pelayanan</th>
                        <th class="py-3.5 px-4 text-left">Kategori Pelayanan</th>
                        <th class="py-3.5 px-4 text-left">Status Keluarga</th>
                        <th class="py-3.5 px-4 text-center">Keanggotaan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10 bg-white">
                    @forelse($jemaatList as $idx => $jm)
                    <tr class="hover:bg-surface-container-low/50 transition">
                        <td class="py-3.5 px-4 text-center text-outline font-mono">
                            {{ $jemaatList->firstItem() + $idx }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-primary">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[17px] text-secondary">person</span>
                                <span>{{ $jm->nama_lengkap }}</span>
                            </div>
                            @if(!empty($jm->no_kk))
                                <span class="block text-2xs text-outline font-normal ml-6">No. KK: {{ substr($jm->no_kk, 0, 6) }}******</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-on-surface font-semibold">
                            <span class="inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px] text-amber-700">pin_drop</span>
                                <span>{{ $jm->wilayah->nama_wilayah ?? 'Belum Ditentukan' }}</span>
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            @php
                                $badgeStyle = match($jm->kategori) {
                                    'bapak' => 'bg-blue-50 text-blue-800 border-blue-200',
                                    'ibu' => 'bg-rose-50 text-rose-800 border-rose-200',
                                    'pemuda' => 'bg-amber-50 text-amber-800 border-amber-200',
                                    'anak' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                    'lansia' => 'bg-purple-50 text-purple-800 border-purple-200',
                                    default => 'bg-gray-50 text-gray-800 border-gray-200',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-2xs font-bold border {{ $badgeStyle }}">
                                {{ $jm->kategori_label ?? ucfirst($jm->kategori) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-on-surface-variant font-medium">
                            {{ $jm->status_keluarga ? ucfirst($jm->status_keluarga) : '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-2xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Aktif
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-outline">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[36px] text-outline">person_off</span>
                                <span class="font-bold text-sm text-primary">Data Jemaat Tidak Ditemukan</span>
                                <p class="text-xs text-on-surface-variant">Silakan sesuaikan kata kunci pencarian atau filter wilayah.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jemaatList->hasPages())
        <div class="p-4 border-t border-outline-variant/20 bg-surface-container-low/40">
            {{ $jemaatList->links() }}
        </div>
        @endif
    </div>

    <!-- Security & Privacy Notice -->
    <div class="bg-white rounded-2xl p-6 border border-outline-variant/30 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4" data-aos="fade-up">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[20px]">shield</span>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-primary">Perlindungan Privasi Jemaat</h4>
                <p class="text-xs text-on-surface-variant mt-0.5">
                    Halaman ini berstatus <strong>Read-Only</strong>. Nomor Induk Kependudukan (NIK), riwayat persembahan pribadi, dan dokumen pastoral dilindungi secara ketat. Khusus pengurus gereja yang memerlukan pengelolaan data penuh, silakan masuk ke portal admin.
                </p>
            </div>
        </div>
        <a href="{{ route('admin.login') }}" class="px-4 py-2 rounded-xl bg-surface-container text-primary font-bold text-xs hover:bg-surface-container-high transition shrink-0 flex items-center gap-1.5 border border-outline-variant/30">
            <span class="material-symbols-outlined text-[16px]">lock</span>
            <span>Masuk Portal Admin</span>
        </a>
    </div>
</div>
@endsection
