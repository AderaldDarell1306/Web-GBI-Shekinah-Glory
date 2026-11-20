@extends('layouts.admin')

@section('title', 'Sakramen & Dokumen Pastoral - SIMJ GBI Shekinah Glory')
@section('page_title', 'Sakramen, Pembinaan & Dokumen Pastoral')

@section('content')
<div class="space-y-6">
    <!-- Header Title & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-serif-display text-2xl font-bold text-primary">Manajemen Sakramen & Dokumen Pastoral</h2>
            <p class="text-xs text-on-surface-variant mt-0.5">Pencatatan Baptisan Air, Penyerahan Anak, Pernikahan Kudus, dan Penerbitan Surat Keterangan / Translokasi Resmi.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('public.pendaftaran') }}" target="_blank" class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white hover:bg-gray-50 text-secondary border border-secondary/30 font-bold text-xs shadow-sm transition">
                <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                <span>Portal Form Online</span>
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white rounded-2xl p-2 shadow-sm border border-outline-variant/30 flex flex-wrap gap-1.5 text-xs font-bold">
        <a href="{{ route('admin.sakramen.index', ['tab' => 'baptis']) }}" class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 {{ $tab === 'baptis' ? 'bg-primary text-white shadow-sm' : 'text-on-surface hover:bg-surface-container' }}">
            <span class="material-symbols-outlined text-[18px]">water_drop</span>
            <span>Baptisan Air</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $tab === 'baptis' ? 'bg-white/20 text-white' : 'bg-surface-container text-primary' }}">{{ $baptisList->total() }}</span>
        </a>

        <a href="{{ route('admin.sakramen.index', ['tab' => 'penyerahan']) }}" class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 {{ $tab === 'penyerahan' ? 'bg-primary text-white shadow-sm' : 'text-on-surface hover:bg-surface-container' }}">
            <span class="material-symbols-outlined text-[18px]">child_care</span>
            <span>Penyerahan Anak</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $tab === 'penyerahan' ? 'bg-white/20 text-white' : 'bg-surface-container text-primary' }}">{{ $penyerahanList->total() }}</span>
        </a>

        <a href="{{ route('admin.sakramen.index', ['tab' => 'nikah']) }}" class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 {{ $tab === 'nikah' ? 'bg-primary text-white shadow-sm' : 'text-on-surface hover:bg-surface-container' }}">
            <span class="material-symbols-outlined text-[18px]">favorite</span>
            <span>Pernikahan Kudus</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $tab === 'nikah' ? 'bg-white/20 text-white' : 'bg-surface-container text-primary' }}">{{ $nikahList->total() }}</span>
        </a>

        <a href="{{ route('admin.sakramen.index', ['tab' => 'surat']) }}" class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 {{ $tab === 'surat' ? 'bg-primary text-white shadow-sm' : 'text-on-surface hover:bg-surface-container' }}">
            <span class="material-symbols-outlined text-[18px]">description</span>
            <span>Surat Pastoral & Translokasi</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $tab === 'surat' ? 'bg-white/20 text-white' : 'bg-surface-container text-primary' }}">{{ $suratList->total() }}</span>
        </a>

        <a href="{{ route('admin.sakramen.index', ['tab' => 'pendaftaran']) }}" class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 {{ $tab === 'pendaftaran' ? 'bg-primary text-white shadow-sm' : 'text-on-surface hover:bg-surface-container' }}">
            <span class="material-symbols-outlined text-[18px]">inbox</span>
            <span>Permohonan Online</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $tab === 'pendaftaran' ? 'bg-white/20 text-white' : 'bg-surface-container text-primary' }}">{{ $pendaftaranList->total() }}</span>
        </a>
    </div>

    <!-- ================= TAB 1: BAPTISAN AIR ================= -->
    @if($tab === 'baptis')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="font-serif-display text-lg font-bold text-primary">Daftar Jemaat yang Dibaptis Air</h3>
            <button type="button" onclick="document.getElementById('modalTambahBaptis').classList.remove('hidden')" class="px-4 py-2.5 rounded-xl bg-secondary text-white font-bold text-xs shadow-md hover:bg-secondary/90 transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Catat Baptisan Baru</span>
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-on-surface">
                    <thead class="bg-surface-container-low text-outline uppercase font-bold tracking-wider border-b border-outline-variant/30">
                        <tr>
                            <th class="py-3.5 px-4">No. Sertifikat</th>
                            <th class="py-3.5 px-4">Nama Lengkap</th>
                            <th class="py-3.5 px-4">Tgl Lahir / TTL</th>
                            <th class="py-3.5 px-4">Tgl Baptis</th>
                            <th class="py-3.5 px-4">Pendeta Pelayan</th>
                            <th class="py-3.5 px-4">Saksi / Catatan</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20">
                        @forelse($baptisList as $item)
                        <tr class="hover:bg-surface-container-low/50 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-primary">{{ $item->no_sertifikat }}</td>
                            <td class="py-3.5 px-4 font-bold text-primary">
                                {{ $item->nama_lengkap }}
                                @if($item->jemaat)
                                <span class="block text-[10px] text-secondary font-mono">NIJ: {{ $item->jemaat->nomor_induk }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant">{{ $item->tempat_lahir ? $item->tempat_lahir . ', ' : '' }}{{ $item->tanggal_lahir?->format('d/m/Y') ?? '-' }}</td>
                            <td class="py-3.5 px-4 font-semibold text-primary">{{ $item->tanggal_baptis->format('d F Y') }}</td>
                            <td class="py-3.5 px-4">{{ $item->pendeta_pelayan }}</td>
                            <td class="py-3.5 px-4 text-outline">{{ $item->saksi ?: '-' }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.sakramen.baptis.print', $item->id) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-secondary text-white font-bold text-[11px] hover:bg-secondary/90 transition flex items-center gap-1 shadow-sm" title="Cetak Sertifikat Baptis">
                                        <span class="material-symbols-outlined text-[15px]">print</span>
                                        <span>Cetak</span>
                                    </a>
                                    <form action="{{ route('admin.sakramen.baptis.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data baptisan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-outline hover:text-red-600 hover:bg-red-50 transition" title="Hapus Data">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-outline">Belum ada data baptisan tercatat. Klik tombol "Catat Baptisan Baru" di atas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($baptisList->hasPages())
            <div class="p-4 border-t border-outline-variant/20">
                {{ $baptisList->links() }}
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- ================= TAB 2: PENYERAHAN ANAK ================= -->
    @if($tab === 'penyerahan')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="font-serif-display text-lg font-bold text-primary">Daftar Sakramen Penyerahan Anak</h3>
            <button type="button" onclick="document.getElementById('modalTambahPenyerahan').classList.remove('hidden')" class="px-4 py-2.5 rounded-xl bg-secondary text-white font-bold text-xs shadow-md hover:bg-secondary/90 transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Catat Penyerahan Anak</span>
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-on-surface">
                    <thead class="bg-surface-container-low text-outline uppercase font-bold tracking-wider border-b border-outline-variant/30">
                        <tr>
                            <th class="py-3.5 px-4">No. Sertifikat</th>
                            <th class="py-3.5 px-4">Nama Anak</th>
                            <th class="py-3.5 px-4">JK</th>
                            <th class="py-3.5 px-4">Nama Orang Tua</th>
                            <th class="py-3.5 px-4">Tgl Penyerahan</th>
                            <th class="py-3.5 px-4">Pendeta Pelayan</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20">
                        @forelse($penyerahanList as $item)
                        <tr class="hover:bg-surface-container-low/50 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-primary">{{ $item->no_sertifikat }}</td>
                            <td class="py-3.5 px-4 font-bold text-primary">{{ $item->nama_anak }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $item->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                    {{ $item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold block text-primary">{{ $item->nama_ayah }}</span>
                                <span class="text-outline text-[11px]">{{ $item->nama_ibu }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-primary">{{ $item->tanggal_penyerahan->format('d F Y') }}</td>
                            <td class="py-3.5 px-4">{{ $item->pendeta_pelayan }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.sakramen.penyerahan.print', $item->id) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-secondary text-white font-bold text-[11px] hover:bg-secondary/90 transition flex items-center gap-1 shadow-sm" title="Cetak Sertifikat Penyerahan Anak">
                                        <span class="material-symbols-outlined text-[15px]">print</span>
                                        <span>Cetak</span>
                                    </a>
                                    <form action="{{ route('admin.sakramen.penyerahan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data penyerahan anak ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-outline hover:text-red-600 hover:bg-red-50 transition">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-outline">Belum ada data penyerahan anak tercatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($penyerahanList->hasPages())
            <div class="p-4 border-t border-outline-variant/20">
                {{ $penyerahanList->links() }}
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- ================= TAB 3: PERNIKAHAN KUDUS ================= -->
    @if($tab === 'nikah')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="font-serif-display text-lg font-bold text-primary">Daftar Pemberkatan Pernikahan Kudus & Bimbingan Pra-Nikah</h3>
            <button type="button" onclick="document.getElementById('modalTambahNikah').classList.remove('hidden')" class="px-4 py-2.5 rounded-xl bg-secondary text-white font-bold text-xs shadow-md hover:bg-secondary/90 transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Catat Pernikahan Baru</span>
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-on-surface">
                    <thead class="bg-surface-container-low text-outline uppercase font-bold tracking-wider border-b border-outline-variant/30">
                        <tr>
                            <th class="py-3.5 px-4">No. Sertifikat</th>
                            <th class="py-3.5 px-4">Mempelai Pria & Wanita</th>
                            <th class="py-3.5 px-4">Tgl Pemberkatan</th>
                            <th class="py-3.5 px-4">Pendeta Pemberkat</th>
                            <th class="py-3.5 px-4">Status Pra-Nikah</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20">
                        @forelse($nikahList as $item)
                        <tr class="hover:bg-surface-container-low/50 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-primary">{{ $item->no_sertifikat }}</td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-primary block text-sm">{{ $item->pengantin_pria_nama }}</span>
                                <span class="text-secondary font-semibold">&amp; {{ $item->pengantin_wanita_nama }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-primary">{{ $item->tanggal_pemberkatan->format('d F Y') }}</td>
                            <td class="py-3.5 px-4">{{ $item->pendeta_pemberkat }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $item->status_bimbingan === 'selesai' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ ucfirst($item->status_bimbingan) }} Bimbingan
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.sakramen.nikah.print', $item->id) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-secondary text-white font-bold text-[11px] hover:bg-secondary/90 transition flex items-center gap-1 shadow-sm" title="Cetak Sertifikat Nikah">
                                        <span class="material-symbols-outlined text-[15px]">print</span>
                                        <span>Cetak</span>
                                    </a>
                                    <form action="{{ route('admin.sakramen.nikah.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data pernikahan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-outline hover:text-red-600 hover:bg-red-50 transition">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-outline">Belum ada data pernikahan kudus tercatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($nikahList->hasPages())
            <div class="p-4 border-t border-outline-variant/20">
                {{ $nikahList->links() }}
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- ================= TAB 4: SURAT PASTORAL & TRANSLOKASI ================= -->
    @if($tab === 'surat')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="font-serif-display text-lg font-bold text-primary">Daftar Surat Pastoral, Pengantar & Surat Pindah (Translokasi)</h3>
            <button type="button" onclick="document.getElementById('modalTambahSurat').classList.remove('hidden')" class="px-4 py-2.5 rounded-xl bg-secondary text-white font-bold text-xs shadow-md hover:bg-secondary/90 transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Terbitkan Surat Baru</span>
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-on-surface">
                    <thead class="bg-surface-container-low text-outline uppercase font-bold tracking-wider border-b border-outline-variant/30">
                        <tr>
                            <th class="py-3.5 px-4">No. Surat</th>
                            <th class="py-3.5 px-4">Jenis Surat</th>
                            <th class="py-3.5 px-4">Nama Jemaat</th>
                            <th class="py-3.5 px-4">Tujuan / Keperluan</th>
                            <th class="py-3.5 px-4">Tgl Terbit</th>
                            <th class="py-3.5 px-4">Penandatangan</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20">
                        @forelse($suratList as $item)
                        <tr class="hover:bg-surface-container-low/50 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-primary">{{ $item->no_surat }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-primary-container text-white">
                                    {{ $item->jenis_surat_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-primary">{{ $item->nama_jemaat }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant">
                                <strong class="block text-primary">{{ $item->tujuan_gereja_instansi ?: '-' }}</strong>
                                <span class="text-[11px] text-outline">{{ $item->keperluan_alasan }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-primary">{{ $item->tanggal_surat->format('d/m/Y') }}</td>
                            <td class="py-3.5 px-4">{{ $item->penandatangan }} ({{ $item->jabatan_penandatangan }})</td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.sakramen.surat.print', $item->id) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-secondary text-white font-bold text-[11px] hover:bg-secondary/90 transition flex items-center gap-1 shadow-sm" title="Cetak Surat Resmi">
                                        <span class="material-symbols-outlined text-[15px]">print</span>
                                        <span>Cetak</span>
                                    </a>
                                    <form action="{{ route('admin.sakramen.surat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus surat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-outline hover:text-red-600 hover:bg-red-50 transition">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-outline">Belum ada surat pastoral tercatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($suratList->hasPages())
            <div class="p-4 border-t border-outline-variant/20">
                {{ $suratList->links() }}
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- ================= TAB 5: PERMOHONAN ONLINE ================= -->
    @if($tab === 'pendaftaran')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="font-serif-display text-lg font-bold text-primary">Permohonan Layanan dari Portal Publik</h3>
            <span class="text-xs text-outline">Jemaat dapat mengisi formulir mandiri lewat web</span>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-on-surface">
                    <thead class="bg-surface-container-low text-outline uppercase font-bold tracking-wider border-b border-outline-variant/30">
                        <tr>
                            <th class="py-3.5 px-4">Kode / Tgl</th>
                            <th class="py-3.5 px-4">Jenis Permohonan</th>
                            <th class="py-3.5 px-4">Nama Pemohon</th>
                            <th class="py-3.5 px-4">Kontak (WhatsApp)</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-center">Kelola Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20">
                        @forelse($pendaftaranList as $item)
                        <tr class="hover:bg-surface-container-low/50 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-mono font-bold text-primary block">{{ $item->kode_pendaftaran }}</span>
                                <span class="text-[10px] text-outline">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-secondary">{{ $item->jenis_layanan_label }}</td>
                            <td class="py-3.5 px-4 font-bold text-primary">{{ $item->nama_pemohon }}</td>
                            <td class="py-3.5 px-4">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->no_wa) }}" target="_blank" class="font-semibold text-green-700 hover:underline flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">chat</span>
                                    <span>{{ $item->no_wa }}</span>
                                </a>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $item->status === 'disetujui' ? 'bg-green-100 text-green-800' : ($item->status === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('public.pendaftaran.sukses', $item->id) }}" target="_blank" class="px-2 py-1 rounded-lg bg-surface-container hover:bg-secondary hover:text-white text-secondary font-bold text-[11px] transition flex items-center gap-1 border border-outline-variant/30" title="Lihat & Cetak Dokumen Blanko">
                                        <span class="material-symbols-outlined text-[15px]">print</span>
                                        <span>Blanko</span>
                                    </a>
                                    <form action="{{ route('admin.sakramen.pendaftaran.status', $item->id) }}" method="POST" class="inline-flex items-center gap-1">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" onchange="this.form.submit()" class="text-[11px] font-semibold rounded-lg border border-outline-variant px-2 py-1 bg-surface-container-low">
                                            <option value="menunggu" {{ $item->status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="diproses" {{ $item->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="disetujui" {{ $item->status === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                            <option value="ditolak" {{ $item->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-outline">Belum ada permohonan masuk dari portal online.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($pendaftaranList->hasPages())
            <div class="p-4 border-t border-outline-variant/20">
                {{ $pendaftaranList->links() }}
            </div>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- ================= MODAL TAMBAH BAPTIS ================= -->
<div id="modalTambahBaptis" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-outline-variant/30 space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-outline-variant/30">
            <h3 class="font-serif-display text-lg font-bold text-primary">Catat Sakramen Baptisan Air</h3>
            <button type="button" onclick="document.getElementById('modalTambahBaptis').classList.add('hidden')" class="w-8 h-8 rounded-full bg-surface-container text-outline hover:text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <form action="{{ route('admin.sakramen.baptis.store') }}" method="POST" class="space-y-3.5">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-primary mb-1">Nomor Sertifikat</label>
                <input type="text" name="no_sertifikat" value="BPT-{{ date('Y') }}-{{ sprintf('%04d', rand(1, 999)) }}" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-mono font-bold">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-primary mb-1">Pilih Jemaat (Opsional / Otomatis isi)</label>
                <select name="jemaat_id" onchange="if(this.value){ var opt=this.options[this.selectedIndex]; document.getElementById('baptis_nama').value=opt.getAttribute('data-nama'); }" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                    <option value="">-- Jemaat Belum Terdaftar / Input Manual --</option>
                    @foreach($jemaats as $jm)
                    <option value="{{ $jm->id }}" data-nama="{{ $jm->nama_lengkap }}">{{ $jm->nama_lengkap }} ({{ $jm->no_kk }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-primary mb-1">Nama Lengkap yang Dibaptis</label>
                <input type="text" id="baptis_nama" name="nama_lengkap" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-bold">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" placeholder="Kupang" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Tanggal Baptis</label>
                    <input type="date" name="tanggal_baptis" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Tempat Baptis</label>
                    <input type="text" name="tempat_baptis" value="GBI Shekinah Glory Namosain" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-primary mb-1">Pendeta yang Membaptis</label>
                <input type="text" name="pendeta_pelayan" value="Pdt. Markus Suryanata" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-bold text-secondary">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Nama Ayah</label>
                    <input type="text" name="nama_ayah" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Nama Ibu</label>
                    <input type="text" name="nama_ibu" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-primary mb-1">Saksi / Catatan</label>
                <input type="text" name="saksi" placeholder="Nama saksi / keterangan" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
            </div>

            <div class="pt-3 border-t border-outline-variant/30 flex items-center justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalTambahBaptis').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-semibold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-secondary text-white font-bold text-xs shadow-md hover:bg-secondary/90 transition">Simpan &amp; Terbitkan</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL TAMBAH PENYERAHAN ANAK ================= -->
<div id="modalTambahPenyerahan" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-outline-variant/30 space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-outline-variant/30">
            <h3 class="font-serif-display text-lg font-bold text-primary">Catat Sakramen Penyerahan Anak</h3>
            <button type="button" onclick="document.getElementById('modalTambahPenyerahan').classList.add('hidden')" class="w-8 h-8 rounded-full bg-surface-container text-outline hover:text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <form action="{{ route('admin.sakramen.penyerahan.store') }}" method="POST" class="space-y-3.5">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-primary mb-1">Nomor Sertifikat</label>
                <input type="text" name="no_sertifikat" value="PA-{{ date('Y') }}-{{ sprintf('%04d', rand(1, 999)) }}" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-mono font-bold">
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="col-span-2">
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Nama Anak</label>
                    <input type="text" name="nama_anak" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" placeholder="Kupang" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Nama Ayah</label>
                    <input type="text" name="nama_ayah" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Nama Ibu</label>
                    <input type="text" name="nama_ibu" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-semibold">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Tanggal Penyerahan</label>
                    <input type="date" name="tanggal_penyerahan" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Tempat Penyerahan</label>
                    <input type="text" name="tempat_penyerahan" value="GBI Shekinah Glory Namosain" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-primary mb-1">Pendeta Pelayan</label>
                <input type="text" name="pendeta_pelayan" value="Pdt. Markus Suryanata" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-bold text-secondary">
            </div>

            <div class="pt-3 border-t border-outline-variant/30 flex items-center justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalTambahPenyerahan').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-semibold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-secondary text-white font-bold text-xs shadow-md hover:bg-secondary/90 transition">Simpan &amp; Terbitkan</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL TAMBAH NIKAH ================= -->
<div id="modalTambahNikah" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-outline-variant/30 space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-outline-variant/30">
            <h3 class="font-serif-display text-lg font-bold text-primary">Catat Pernikahan Kudus & Bimbingan Pra-Nikah</h3>
            <button type="button" onclick="document.getElementById('modalTambahNikah').classList.add('hidden')" class="w-8 h-8 rounded-full bg-surface-container text-outline hover:text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <form action="{{ route('admin.sakramen.nikah.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-primary mb-1">Nomor Sertifikat Nikah</label>
                <input type="text" name="no_sertifikat" value="NKH-{{ date('Y') }}-{{ sprintf('%04d', rand(1, 999)) }}" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-mono font-bold">
            </div>

            <div class="p-3 bg-surface-container-low rounded-xl space-y-2 border border-outline-variant/20">
                <span class="text-xs font-bold text-primary uppercase block">Mempelai Pria:</span>
                <input type="text" name="pengantin_pria_nama" placeholder="Nama Lengkap Pengantin Pria" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-bold">
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" name="pengantin_pria_ayah" placeholder="Nama Ayah Pria" class="w-full px-3 py-1.5 rounded-xl border border-outline-variant text-xs">
                    <input type="text" name="pengantin_pria_ibu" placeholder="Nama Ibu Pria" class="w-full px-3 py-1.5 rounded-xl border border-outline-variant text-xs">
                </div>
            </div>

            <div class="p-3 bg-surface-container-low rounded-xl space-y-2 border border-outline-variant/20">
                <span class="text-xs font-bold text-secondary uppercase block">Mempelai Wanita:</span>
                <input type="text" name="pengantin_wanita_nama" placeholder="Nama Lengkap Pengantin Wanita" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-bold">
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" name="pengantin_wanita_ayah" placeholder="Nama Ayah Wanita" class="w-full px-3 py-1.5 rounded-xl border border-outline-variant text-xs">
                    <input type="text" name="pengantin_wanita_ibu" placeholder="Nama Ibu Wanita" class="w-full px-3 py-1.5 rounded-xl border border-outline-variant text-xs">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Tanggal Pemberkatan</label>
                    <input type="date" name="tanggal_pemberkatan" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Tempat Pemberkatan</label>
                    <input type="text" name="tempat_pemberkatan" value="GBI Shekinah Glory Namosain" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-primary mb-1">Pendeta yang Memberkati</label>
                <input type="text" name="pendeta_pemberkat" value="Pdt. Markus Suryanata" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-bold text-secondary">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Status Bimbingan Pra-Nikah</label>
                    <select name="status_bimbingan" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                        <option value="selesai">Selesai Bimbingan</option>
                        <option value="proses">Sedang Proses</option>
                        <option value="belum">Belum Bimbingan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Upload Berkas Legalitas (PDF/Gambar)</label>
                    <input type="file" name="berkas_file" accept=".pdf,.png,.jpg,.jpeg" class="w-full text-xs">
                </div>
            </div>

            <div class="pt-3 border-t border-outline-variant/30 flex items-center justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalTambahNikah').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-semibold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-secondary text-white font-bold text-xs shadow-md hover:bg-secondary/90 transition">Simpan &amp; Terbitkan</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL TAMBAH SURAT PASTORAL ================= -->
<div id="modalTambahSurat" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-outline-variant/30 space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-outline-variant/30">
            <h3 class="font-serif-display text-lg font-bold text-primary">Terbitkan Surat Pastoral / Translokasi</h3>
            <button type="button" onclick="document.getElementById('modalTambahSurat').classList.add('hidden')" class="w-8 h-8 rounded-full bg-surface-container text-outline hover:text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <form action="{{ route('admin.sakramen.surat.store') }}" method="POST" class="space-y-3.5">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-primary mb-1">Nomor Surat</label>
                <input type="text" name="no_surat" value="{{ sprintf('%03d', rand(1, 150)) }}/GBI-SG/SP/{{ date('m/Y') }}" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-mono font-bold">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-primary mb-1">Jenis Surat</label>
                <select name="jenis_surat" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-semibold">
                    <option value="surat_pindah">Surat Pindah Jemaat (Translokasi)</option>
                    <option value="pengantar_keanggotaan">Surat Pengantar Keanggotaan</option>
                    <option value="surat_keterangan">Surat Keterangan Aktif Jemaat</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-primary mb-1">Pilih Jemaat</label>
                <select name="jemaat_id" onchange="if(this.value){ var opt=this.options[this.selectedIndex]; document.getElementById('surat_nama').value=opt.getAttribute('data-nama'); }" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                    <option value="">-- Pilih Jemaat Terdaftar --</option>
                    @foreach($jemaats as $jm)
                    <option value="{{ $jm->id }}" data-nama="{{ $jm->nama_lengkap }}">{{ $jm->nama_lengkap }} ({{ $jm->no_kk }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-primary mb-1">Nama Jemaat</label>
                <input type="text" id="surat_nama" name="nama_jemaat" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-bold">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Tujuan Gereja / Instansi</label>
                    <input type="text" name="tujuan_gereja_instansi" placeholder="Misal: GBI Tiberias Surabaya" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-primary mb-1">Alasan / Keterangan Keperluan</label>
                <textarea name="keperluan_alasan" rows="2" placeholder="Pindah domisili tugas kerja ke luar kota..." class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Nama Penandatangan</label>
                    <input type="text" name="penandatangan" value="Pdt. Markus Suryanata" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Jabatan</label>
                    <input type="text" name="jabatan_penandatangan" value="Gembala Jemaat" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                </div>
            </div>

            <div class="pt-3 border-t border-outline-variant/30 flex items-center justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalTambahSurat').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-semibold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-secondary text-white font-bold text-xs shadow-md hover:bg-secondary/90 transition">Terbitkan &amp; Cetak</button>
            </div>
        </form>
    </div>
</div>
@endsection

