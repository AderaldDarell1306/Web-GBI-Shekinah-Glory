@extends('layouts.admin')

@section('title', 'Peminjaman Inventaris - SIMJ GBI Shekinah Glory')
@section('page_title', 'Pencatatan Peminjaman Inventaris')

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.inventaris.index') }}" class="w-9 h-9 rounded-xl bg-white border border-outline-variant/40 flex items-center justify-center text-outline hover:text-primary transition">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h2 class="font-serif-display text-2xl font-bold text-primary">Pinjam Pakai Inventaris Gereja</h2>
                <p class="text-xs text-on-surface-variant mt-0.5">Catatan peminjam, barang, tanggal pinjam, dan status pengembalian.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.inventaris.laporan-pinjam') }}" target="_blank" class="flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200 font-bold text-xs shadow-xs transition">
                <span class="material-symbols-outlined text-[18px] text-amber-700">print</span>
                <span>Cetak Laporan</span>
            </a>
            <button type="button" onclick="openPinjamModal()" class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-secondary hover:bg-secondary/90 text-white font-bold text-xs shadow-md transition">
                <span class="material-symbols-outlined text-[18px]">add_task</span>
                <span>+ Catat Peminjaman</span>
            </button>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 shadow-xs border border-outline-variant/30">
        <form method="GET" action="{{ route('admin.peminjaman-inventaris.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs font-semibold text-primary mb-1">Cari Peminjam / Barang</label>
                <input type="text" name="q" value="{{ $q }}" placeholder="Nama peminjam, kode pinjam, kegiatan..." class="w-full text-xs rounded-xl border border-outline-variant/60 py-2.5 px-3 focus:border-secondary focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-primary mb-1">Status Peminjaman</label>
                <select name="status" class="w-full text-xs rounded-xl border border-outline-variant/60 py-2.5 px-3 focus:border-secondary focus:outline-none bg-white">
                    <option value="">Semua Status</option>
                    <option value="Sedang Dipinjam" {{ $status === 'Sedang Dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="Sudah Dikembalikan" {{ $status === 'Sudah Dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                    <option value="Terlambat" {{ $status === 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-primary hover:bg-primary-container text-white text-xs font-bold rounded-xl transition shadow-sm">
                    Filter
                </button>
                <a href="{{ route('admin.peminjaman-inventaris.index') }}" class="py-2.5 px-3 bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-semibold rounded-xl transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Peminjaman -->
    <div class="bg-white rounded-2xl shadow-xs border border-outline-variant/30 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-outline-variant/20 text-xs">
                <thead class="bg-surface-container-low text-primary font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 text-left">Kode Pinjam</th>
                        <th class="py-3.5 px-4 text-left">Nama Peminjam & Kontak</th>
                        <th class="py-3.5 px-4 text-left">Barang Dipinjam</th>
                        <th class="py-3.5 px-4 text-left">Kegiatan / Keperluan</th>
                        <th class="py-3.5 px-4 text-center">Tgl Pinjam - Rencana Kembali</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi / Pengembalian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10 bg-white">
                    @forelse($peminjamanList as $p)
                    <tr class="hover:bg-surface-container-low/50 transition">
                        <td class="py-3 px-4 font-mono font-bold text-primary">
                            {{ $p->kode_peminjaman }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-bold text-primary block text-sm">{{ $p->nama_peminjam }}</span>
                            <span class="text-2xs text-outline">{{ $p->no_kontak ?? '-' }}</span>
                        </td>
                        <td class="py-3 px-4 font-semibold text-secondary">
                            {{ $p->inventaris->nama_barang ?? '-' }}
                            <span class="block text-2xs text-outline font-mono">Kode: {{ $p->inventaris->kode_inventaris ?? '-' }} ({{ $p->jumlah_pinjam }} Unit)</span>
                        </td>
                        <td class="py-3 px-4 text-on-surface">
                            {{ $p->instansi_kegiatan }}
                            @if($p->catatan)
                                <span class="block text-2xs text-outline line-clamp-1">{{ $p->catatan }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="font-bold text-primary block">{{ $p->tanggal_pinjam->format('d/m/Y') }}</span>
                            <span class="text-2xs text-outline">s.d. {{ $p->tanggal_rencana_kembali->format('d/m/Y') }}</span>
                            @if($p->tanggal_kembali_aktual)
                                <span class="block text-2xs text-emerald-700 font-bold">Kembali: {{ $p->tanggal_kembali_aktual->format('d/m/Y') }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-2xs font-bold
                                {{ $p->status === 'Menunggu Konfirmasi' ? 'bg-amber-100 text-amber-800' : '' }}
                                {{ $p->status === 'Sedang Dipinjam' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $p->status === 'Sudah Dikembalikan' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                {{ $p->status === 'Terlambat' ? 'bg-rose-100 text-rose-800' : '' }}">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('public.layanan.pinjam-inventaris.sukses', $p->id) }}" target="_blank" class="px-2 py-1 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary font-bold text-2xs transition flex items-center gap-1" title="Cetak Blanko Peminjaman (A4)">
                                    <span class="material-symbols-outlined text-[14px]">print</span>
                                    <span>Blanko A4</span>
                                </a>
                                @if($p->status !== 'Sudah Dikembalikan')
                                <button type="button" onclick='openReturnModal(@json($p))' class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-2xs transition shadow-xs flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">check</span>
                                    <span>Kembalikan</span>
                                </button>
                                @endif
                                <form action="{{ route('admin.peminjaman-inventaris.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus catatan peminjaman ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded text-outline hover:text-red-600 hover:bg-red-50 transition" title="Hapus">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-outline">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[36px] text-outline">assignment_turned_in</span>
                                <span class="font-bold text-sm text-primary">Belum Ada Catatan Peminjaman</span>
                                <p class="text-xs text-on-surface-variant">Gunakan tombol di atas untuk mencatat peminjaman inventaris gereja.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($peminjamanList->hasPages())
        <div class="px-5 py-4 border-t border-outline-variant/20">
            {{ $peminjamanList->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Catat Peminjaman -->
<div id="pinjamModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl relative animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-outline-variant/30">
            <h3 class="text-base font-bold text-primary font-serif-display">Formulir Pinjam Pakai Inventaris</h3>
            <button type="button" onclick="closePinjamModal()" class="text-outline hover:text-primary">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form action="{{ route('admin.peminjaman-inventaris.store') }}" method="POST" class="mt-4 space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-primary mb-1">Pilih Barang Inventaris <span class="text-red-500">*</span></label>
                <select name="inventaris_id" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white font-semibold">
                    <option value="">-- Pilih Barang yang Akan Dipinjam --</option>
                    @foreach($inventarisList as $inv)
                        <option value="{{ $inv->id }}">{{ $inv->nama_barang }} ({{ $inv->kode_inventaris }}) • Lokasi: {{ $inv->lokasi }} [Tersedia: {{ $inv->jumlah }} {{ $inv->satuan }}]</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">Nama Peminjam <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_peminjam" required placeholder="Contoh: Tim Kaum Pemuda" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">No. Kontak / WA</label>
                    <input type="text" name="no_kontak" placeholder="08xxxxxxxxxx" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block font-bold text-primary mb-1">Instansi / Kegiatan Keperluan <span class="text-red-500">*</span></label>
                <input type="text" name="instansi_kegiatan" required placeholder="Contoh: Ibadah Rayon Namosain / Retret Pemuda" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">Tgl Pinjam <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Rencana Kembali <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_rencana_kembali" value="{{ date('Y-m-d') }}" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Jumlah Unit <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_pinjam" value="1" min="1" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none font-bold">
                </div>
            </div>

            <div>
                <label class="block font-bold text-primary mb-1">Kondisi Sebelum Dipinjam</label>
                <input type="text" name="kondisi_sebelum" value="Baik & Berfungsi Normal" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
            </div>

            <div>
                <label class="block font-bold text-primary mb-1">Catatan Tambahan</label>
                <textarea name="catatan" rows="2" placeholder="Catatan kelengkapan kabel, remote, adaptor..." class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none"></textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-outline-variant/20">
                <button type="button" onclick="closePinjamModal()" class="px-4 py-2 text-xs font-semibold text-outline hover:bg-surface-container rounded-xl transition">Batal</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-primary hover:bg-primary-container rounded-xl shadow-sm transition">Simpan Peminjaman</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Pengembalian Inventaris -->
<div id="returnModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl relative animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-outline-variant/30">
            <h3 class="text-base font-bold text-primary font-serif-display">Konfirmasi Pengembalian Barang</h3>
            <button type="button" onclick="closeReturnModal()" class="text-outline hover:text-primary">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form id="returnForm" method="POST" class="mt-4 space-y-4 text-xs">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="Sudah Dikembalikan">

            <div>
                <label class="block font-bold text-primary mb-1">Tanggal Pengembalian Aktual <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_kembali_aktual" value="{{ date('Y-m-d') }}" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
            </div>

            <div>
                <label class="block font-bold text-primary mb-1">Kondisi Sesudah Dikembalikan</label>
                <input type="text" name="kondisi_sesudah" value="Baik & Lengkap" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
            </div>

            <div>
                <label class="block font-bold text-primary mb-1">Catatan Pengembalian</label>
                <textarea name="catatan" rows="2" placeholder="Barang diterima lengkap dan berfungsi normal..." class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none"></textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-outline-variant/20">
                <button type="button" onclick="closeReturnModal()" class="px-4 py-2 text-xs font-semibold text-outline hover:bg-surface-container rounded-xl transition">Batal</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm transition">Selesaikan Pengembalian</button>
            </div>
        </form>
    </div>
</div>

<script>
function openPinjamModal() {
    document.getElementById('pinjamModal').classList.remove('hidden');
}
function closePinjamModal() {
    document.getElementById('pinjamModal').classList.add('hidden');
}
function openReturnModal(item) {
    const form = document.getElementById('returnForm');
    form.action = `/admin/peminjaman-inventaris/${item.id}`;
    document.getElementById('returnModal').classList.remove('hidden');
}
function closeReturnModal() {
    document.getElementById('returnModal').classList.add('hidden');
}
</script>
@endsection
