@extends('layouts.admin')

@section('title', 'Detail Jemaat - SIMJ GBI Shekinah Glory')
@section('page_title', 'Profil Detail Jemaat')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <nav class="flex items-center gap-2 text-xs text-outline">
            <a href="{{ route('admin.jemaat.index') }}" class="hover:text-primary transition flex items-center gap-1 font-semibold">
                <span class="material-symbols-outlined text-[16px]">groups</span>
                <span>Data Jemaat</span>
            </a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="font-bold text-primary">Detail: {{ $jemaat->nama_lengkap }}</span>
        </nav>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.jemaat.edit', $jemaat->id) }}" class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-secondary text-white font-bold text-xs shadow-sm hover:bg-secondary/90 transition">
                <span class="material-symbols-outlined text-[16px]">edit</span>
                <span>Ubah Data</span>
            </a>
            <a href="{{ route('admin.jemaat.index') }}" class="px-4 py-2 rounded-xl bg-white border border-outline-variant text-xs font-semibold hover:bg-surface-container transition">
                Kembali
            </a>
        </div>
    </div>

    <!-- Main Profile Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <!-- Header Strip with Background -->
        <div class="p-6 sm:p-8 bg-gradient-to-r from-primary to-primary-container text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-secondary-container text-secondary flex items-center justify-center font-bold text-2xl shadow-lg shrink-0">
                    {{ $jemaat->jenis_kelamin }}
                </div>
                <div>
                    <h2 class="font-serif-display text-2xl font-bold text-white">{{ $jemaat->nama_lengkap }}</h2>
                    <p class="text-xs text-secondary-fixed font-mono mt-0.5">
                        No. KK: {{ $jemaat->no_kk ?? $jemaat->nomor_induk }} • NIJ: {{ $jemaat->nomor_induk }}
                    </p>
                    <p class="text-xs text-white/70 mt-1">Status Keluarga: <strong>{{ $jemaat->status_keluarga ?? '-' }}</strong></p>
                </div>
            </div>
            <div>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/10 text-white border border-white/20">
                    {{ $jemaat->keterangan ?: 'Status: ' . $jemaat->status_keanggotaan }}
                </span>
            </div>
        </div>

        <!-- Detail Attributes Grid -->
        <div class="p-6 sm:p-8 space-y-6 divide-y divide-outline-variant/20">
            <!-- 1. Identitas Pokok Sesuai Format Gereja -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-secondary mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">badge</span>
                    <span>Identitas Pokok & Keluarga</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs">
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">No. KK</span>
                        <span class="font-mono font-bold text-primary text-sm">{{ $jemaat->no_kk ?? '-' }}</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">NIK</span>
                        <span class="font-mono font-bold text-primary text-sm">{{ $jemaat->nik ?? '-' }}</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">Nama Lengkap</span>
                        <span class="font-bold text-primary text-sm">{{ $jemaat->nama_lengkap }}</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">Kategorial &amp; Diakonia</span>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="px-2 py-0.5 rounded-md bg-secondary-container/60 text-secondary font-bold text-[11px]">{{ $jemaat->kategorial_pelayanan }}</span>
                            @if($jemaat->diakonia_label)
                            <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-800 font-bold text-[11px]">{{ $jemaat->diakonia_label }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">TTL (Tempat, Tgl Lahir)</span>
                        <span class="font-bold text-primary text-sm">{{ $jemaat->ttl ?: '-' }} ({{ $jemaat->umur ? $jemaat->umur . ' thn' : '-' }})</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">Jenis Kelamin (JK)</span>
                        <span class="font-bold text-primary text-sm">{{ $jemaat->jenis_kelamin === 'L' ? 'L (Laki-laki)' : 'P (Perempuan)' }}</span>
                    </div>
                </div>
            </div>

            <!-- 2. Wilayah Pelayanan & Keluarga -->
            <div class="pt-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-secondary mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">domain</span>
                    <span>Wilayah Pelayanan & Kode Keluarga</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs">
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">Wilayah Besar</span>
                        <span class="font-bold text-primary text-sm">{{ $jemaat->wilayah_besar ?? ($jemaat->wilayah?->wilayah_besar ?? '-') }}</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">Sub-Wilayah / Sektor</span>
                        <span class="font-bold text-secondary text-sm">{{ $jemaat->wilayah?->nama_wilayah ?? ($jemaat->sub_wilayah_label ?? '-') }}</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">Kode Keluarga (KK)</span>
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-primary text-sm">{{ $jemaat->kode_keluarga ?? '-' }}</span>
                            @if($jemaat->kode_keluarga)
                            <a href="{{ route('admin.jemaat.index', ['kode_keluarga' => $jemaat->kode_keluarga]) }}" class="text-[10px] text-secondary font-bold hover:underline">
                                (Lihat Keluarga)
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">Nomor Urut Anggota</span>
                        <span class="font-mono font-bold text-primary text-sm">Anggota #{{ $jemaat->urutan_anggota ?? '01' }}</span>
                    </div>
                </div>
            </div>

            <!-- 3. Hubungan Keluarga & Sakramen -->
            <div class="pt-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-secondary mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">church</span>
                    <span>Status Keluarga, Sakramen & Nikah Masehi</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">Status Hubungan Keluarga</span>
                        <span class="font-bold text-primary text-sm">{{ $jemaat->status_keluarga ?? '-' }}</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">Penyerahan / Baptisan</span>
                        <span class="font-bold text-primary text-sm">{{ $jemaat->status_baptis ?: '-' }}</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">Nikah Masehi</span>
                        <span class="font-bold text-primary text-sm">{{ $jemaat->nikah_masehi ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- 3. Profesi, Keahlian & Sosial Ekonomi -->
            <div class="pt-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-secondary mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">work</span>
                    <span>Pendidikan, Profesi & Talenta Pelayanan</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs">
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">Pendidikan Terakhir</span>
                        <span class="font-bold text-primary text-sm">{{ $jemaat->pendidikan ?: '-' }}</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">Pekerjaan</span>
                        <span class="font-bold text-primary text-sm">{{ $jemaat->pekerjaan ?: '-' }}</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">Keahlian Khusus</span>
                        <span class="font-bold text-secondary text-sm">{{ $jemaat->keahlian_khusus ?: '-' }}</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">Penghasilan Per Bulan</span>
                        <span class="font-mono font-bold text-primary text-sm">
                            @if(is_numeric($jemaat->penghasilan_per_bulan))
                                Rp {{ number_format((float) $jemaat->penghasilan_per_bulan, 0, ',', '.') }}
                            @elseif($jemaat->penghasilan_per_bulan)
                                {{ $jemaat->penghasilan_per_bulan }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- 4. Lain-lain & Keterangan -->
            <div class="pt-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-secondary mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">info</span>
                    <span>Keterangan & Catatan Lain</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">Lain-lain</span>
                        <p class="font-semibold text-primary text-sm">{{ $jemaat->lain_lain ?: '-' }}</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-surface-container-low">
                        <span class="text-outline block mb-1">Keterangan Tambahan</span>
                        <p class="font-semibold text-primary text-sm">{{ $jemaat->keterangan ?: '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Riwayat Audit -->
            <div class="pt-6 text-xs text-outline flex flex-wrap items-center justify-between gap-4">
                <span>Didaftarkan: {{ $jemaat->created_at?->format('d M Y H:i') }}</span>
                @if($jemaat->updated_at)
                <span>Terakhir diperbarui: {{ $jemaat->updated_at->format('d M Y H:i') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
