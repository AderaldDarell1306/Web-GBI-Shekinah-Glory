@extends('layouts.admin')

@section('title', 'Inventaris Gereja - SIMJ GBI Shekinah Glory')
@section('page_title', 'Sistem Manajemen Inventaris & Aset Fisik')

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-serif-display text-2xl font-bold text-primary">Daftar Inventaris Gereja</h2>
            <p class="text-xs text-on-surface-variant mt-0.5">Pengelolaan aset fisik: Nomor, Jenis Inv., Kode Inv., dan Lokasi (Kantor, Gereja, Gudang) - PRD Modul 9.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <!-- Link ke Laporan Pinjam Pakai -->
            <a href="{{ route('admin.inventaris.laporan-pinjam') }}" target="_blank" class="flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200 font-bold text-xs shadow-xs transition">
                <span class="material-symbols-outlined text-[18px] text-amber-700">print</span>
                <span>Cetak Laporan Pinjam Pakai</span>
            </a>

            <!-- Link ke Peminjaman -->
            <a href="{{ route('admin.peminjaman-inventaris.index') }}" class="flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-surface-container hover:bg-surface-container-high text-primary font-bold text-xs shadow-xs transition">
                <span class="material-symbols-outlined text-[18px]">assignment_return</span>
                <span>Kelola Peminjaman ({{ $totalDipinjam }})</span>
            </a>

            @if(Auth::user() && !Auth::user()->isJemaat())
            <!-- Tombol Tambah Barang -->
            <button type="button" onclick="openCreateModal()" class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-secondary hover:bg-secondary/90 text-white font-bold text-xs shadow-md transition">
                <span class="material-symbols-outlined text-[18px]">add_box</span>
                <span>+ Tambah Inventaris</span>
            </button>
            @endif
        </div>
    </div>

    <!-- 4 Metric Cards Lokasi & Aset -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl bg-white border border-outline-variant/30 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-outline uppercase tracking-wider">Total Aset Fisik</p>
                <h3 class="font-serif-display text-2xl font-bold text-primary mt-1">{{ $totalAset }} <span class="text-xs font-normal text-outline">Item</span></h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[22px]">inventory_2</span>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-outline-variant/30 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-outline uppercase tracking-wider">Lokasi Kantor</p>
                <h3 class="font-serif-display text-2xl font-bold text-blue-700 mt-1">{{ $totalKantor }} <span class="text-xs font-normal text-outline">Item</span></h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">desk</span>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-outline-variant/30 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-outline uppercase tracking-wider">Lokasi Gereja</p>
                <h3 class="font-serif-display text-2xl font-bold text-emerald-700 mt-1">{{ $totalGereja }} <span class="text-xs font-normal text-outline">Item</span></h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">church</span>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-outline-variant/30 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-outline uppercase tracking-wider">Lokasi Gudang</p>
                <h3 class="font-serif-display text-2xl font-bold text-amber-700 mt-1">{{ $totalGudang }} <span class="text-xs font-normal text-outline">Item</span></h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">warehouse</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 shadow-xs border border-outline-variant/30">
        <form method="GET" action="{{ route('admin.inventaris.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <div>
                <label class="block text-xs font-semibold text-primary mb-1">Cari Barang / Kode</label>
                <input type="text" name="q" value="{{ $q }}" placeholder="Nama, kode, no, jenis..." class="w-full text-xs rounded-xl border border-outline-variant/60 py-2.5 px-3 focus:border-secondary focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-primary mb-1">Lokasi Penyimpanan</label>
                <select name="lokasi" class="w-full text-xs rounded-xl border border-outline-variant/60 py-2.5 px-3 focus:border-secondary focus:outline-none bg-white">
                    <option value="">Semua Lokasi</option>
                    <option value="Kantor" {{ $lokasi === 'Kantor' ? 'selected' : '' }}>Kantor</option>
                    <option value="Gereja" {{ $lokasi === 'Gereja' ? 'selected' : '' }}>Gereja</option>
                    <option value="Gudang" {{ $lokasi === 'Gudang' ? 'selected' : '' }}>Gudang</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-primary mb-1">Jenis Inventaris</label>
                <select name="jenis" class="w-full text-xs rounded-xl border border-outline-variant/60 py-2.5 px-3 focus:border-secondary focus:outline-none bg-white">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisList as $j)
                        <option value="{{ $j }}" {{ $jenis === $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-primary mb-1">Kondisi</label>
                <select name="kondisi" class="w-full text-xs rounded-xl border border-outline-variant/60 py-2.5 px-3 focus:border-secondary focus:outline-none bg-white">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik" {{ $kondisi === 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak Ringan" {{ $kondisi === 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="Rusak Berat" {{ $kondisi === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-primary hover:bg-primary-container text-white text-xs font-bold rounded-xl transition shadow-sm">
                    Filter
                </button>
                <a href="{{ route('admin.inventaris.index') }}" class="py-2.5 px-3 bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-semibold rounded-xl transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Inventaris -->
    <div class="bg-white rounded-2xl shadow-xs border border-outline-variant/30 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-outline-variant/20 text-xs">
                <thead class="bg-surface-container-low text-primary font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 text-center w-16">No.</th>
                        <th class="py-3.5 px-4 text-left">Kode Inv.</th>
                        <th class="py-3.5 px-4 text-left">Jenis Inv.</th>
                        <th class="py-3.5 px-4 text-left">Nama Barang</th>
                        <th class="py-3.5 px-4 text-center">Lokasi</th>
                        <th class="py-3.5 px-4 text-center">Jumlah</th>
                        <th class="py-3.5 px-4 text-center">Kondisi</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        @if(Auth::user() && !Auth::user()->isJemaat())
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10 bg-white">
                    @forelse($inventarisList as $item)
                    <tr class="hover:bg-surface-container-low/50 transition">
                        <td class="py-3 px-4 text-center font-bold text-outline">
                            {{ $item->nomor }}
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-primary">
                            {{ $item->kode_inventaris }}
                        </td>
                        <td class="py-3 px-4 text-on-surface font-semibold">
                            {{ $item->jenis_inventaris }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-bold text-primary block text-sm">{{ $item->nama_barang }}</span>
                            @if($item->keterangan)
                                <span class="text-2xs text-outline line-clamp-1">{{ $item->keterangan }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-2xs font-bold 
                                {{ $item->lokasi === 'Kantor' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                {{ $item->lokasi === 'Gereja' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                {{ $item->lokasi === 'Gudang' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}">
                                <span class="material-symbols-outlined text-[14px]">
                                    {{ $item->lokasi === 'Kantor' ? 'desk' : ($item->lokasi === 'Gereja' ? 'church' : 'warehouse') }}
                                </span>
                                {{ $item->lokasi }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center font-bold text-primary">
                            {{ $item->jumlah }} {{ $item->satuan }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-2xs font-semibold
                                {{ $item->kondisi === 'Baik' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                {{ $item->kondisi === 'Rusak Ringan' ? 'bg-amber-50 text-amber-700' : '' }}
                                {{ $item->kondisi === 'Rusak Berat' ? 'bg-rose-50 text-rose-700' : '' }}">
                                {{ $item->kondisi }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-2xs font-bold
                                {{ $item->status_ketersediaan === 'Tersedia' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                {{ $item->status_ketersediaan === 'Dipinjam' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $item->status_ketersediaan === 'Dalam Perbaikan' ? 'bg-amber-100 text-amber-800' : '' }}
                                {{ $item->status_ketersediaan === 'Dihapuskan' ? 'bg-gray-100 text-gray-600' : '' }}">
                                {{ $item->status_ketersediaan }}
                            </span>
                        </td>
                        @if(Auth::user() && !Auth::user()->isJemaat())
                        <td class="py-3 px-4 text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-1">
                                <button type="button" onclick='openEditModal(@json($item))' class="p-1.5 rounded-lg text-outline hover:text-secondary hover:bg-secondary-container/30 transition" title="Edit">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                </button>
                                <form action="{{ route('admin.inventaris.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus aset \'{{ $item->nama_barang }}\'?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-outline hover:text-red-600 hover:bg-red-50 transition" title="Hapus">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-12 text-center text-outline">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[36px] text-outline">inventory_2</span>
                                <span class="font-bold text-sm text-primary">Belum Ada Data Inventaris</span>
                                <p class="text-xs text-on-surface-variant">Tambahkan data barang inventaris gereja sesuai lokasi penyimpanan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($inventarisList->hasPages())
        <div class="px-5 py-4 border-t border-outline-variant/20">
            {{ $inventarisList->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Inventaris (PRD v2.1 AC-R05) -->
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl relative animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-outline-variant/30">
            <h3 class="text-base font-bold text-primary font-serif-display">Tambah Barang Inventaris Baru</h3>
            <button type="button" onclick="closeCreateModal()" class="text-outline hover:text-primary">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form action="{{ route('admin.inventaris.store') }}" method="POST" class="mt-4 space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">No. <span class="text-red-500">*</span></label>
                    <input type="text" name="nomor" required placeholder="Contoh: 1" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none font-bold">
                </div>
                <div class="col-span-2">
                    <label class="block font-bold text-primary mb-1">Kode Inv. <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_inventaris" required placeholder="Contoh: INV-GRJ-001" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none font-mono font-bold">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">Jenis Inv. <span class="text-red-500">*</span></label>
                    <input type="text" name="jenis_inventaris" list="jenisInvList" required placeholder="Elektronik, Musik, dll" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                    <datalist id="jenisInvList">
                        <option value="Elektronik / Multimedia">
                        <option value="Sound System / Audio">
                        <option value="Alat Musik">
                        <option value="Mebel / Perabot">
                        <option value="Perlengkapan Ibadah">
                        <option value="Kendaraan / Operasional">
                    </datalist>
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Lokasi Penyimpanan <span class="text-red-500">*</span></label>
                    <select name="lokasi" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white font-bold text-secondary">
                        <option value="Kantor">Kantor</option>
                        <option value="Gereja" selected>Gereja</option>
                        <option value="Gudang">Gudang</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold text-primary mb-1">Nama Barang / Aset <span class="text-red-500">*</span></label>
                <input type="text" name="nama_barang" required placeholder="Contoh: Mixer Audio Yamaha 16 Channel" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none font-semibold">
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">Jumlah <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah" value="1" min="1" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Satuan <span class="text-red-500">*</span></label>
                    <input type="text" name="satuan" value="Unit" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Kondisi <span class="text-red-500">*</span></label>
                    <select name="kondisi" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
                        <option value="Baik" selected>Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">Status Ketersediaan *</label>
                    <select name="status_ketersediaan" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
                        <option value="Tersedia" selected>Tersedia</option>
                        <option value="Dipinjam">Dipinjam</option>
                        <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                        <option value="Dihapuskan">Dihapuskan</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Tanggal Perolehan</label>
                    <input type="date" name="tanggal_perolehan" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
                </div>
            </div>

            <div>
                <label class="block font-bold text-primary mb-1">Keterangan / Spesifikasi</label>
                <textarea name="keterangan" rows="2" placeholder="Nomor seri, merk, kelengkapan, dll..." class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none"></textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-outline-variant/20">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-xs font-semibold text-outline hover:bg-surface-container rounded-xl transition">Batal</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-primary hover:bg-primary-container rounded-xl shadow-sm transition">Simpan Inventaris</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Inventaris -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl relative animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-outline-variant/30">
            <h3 class="text-base font-bold text-primary font-serif-display">Edit Barang Inventaris</h3>
            <button type="button" onclick="closeEditModal()" class="text-outline hover:text-primary">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form id="editForm" method="POST" class="mt-4 space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">No. <span class="text-red-500">*</span></label>
                    <input type="text" name="nomor" id="edit_nomor" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none font-bold">
                </div>
                <div class="col-span-2">
                    <label class="block font-bold text-primary mb-1">Kode Inv. <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_inventaris" id="edit_kode" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none font-mono font-bold">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">Jenis Inv. <span class="text-red-500">*</span></label>
                    <input type="text" name="jenis_inventaris" id="edit_jenis" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Lokasi Penyimpanan <span class="text-red-500">*</span></label>
                    <select name="lokasi" id="edit_lokasi" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white font-bold text-secondary">
                        <option value="Kantor">Kantor</option>
                        <option value="Gereja">Gereja</option>
                        <option value="Gudang">Gudang</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold text-primary mb-1">Nama Barang / Aset <span class="text-red-500">*</span></label>
                <input type="text" name="nama_barang" id="edit_nama" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none font-semibold">
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">Jumlah <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah" id="edit_jumlah" min="1" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Satuan <span class="text-red-500">*</span></label>
                    <input type="text" name="satuan" id="edit_satuan" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Kondisi <span class="text-red-500">*</span></label>
                    <select name="kondisi" id="edit_kondisi" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
                        <option value="Baik">Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">Status Ketersediaan *</label>
                    <select name="status_ketersediaan" id="edit_status" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
                        <option value="Tersedia">Tersedia</option>
                        <option value="Dipinjam">Dipinjam</option>
                        <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                        <option value="Dihapuskan">Dihapuskan</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Tanggal Perolehan</label>
                    <input type="date" name="tanggal_perolehan" id="edit_tanggal" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
                </div>
            </div>

            <div>
                <label class="block font-bold text-primary mb-1">Keterangan / Spesifikasi</label>
                <textarea name="keterangan" id="edit_keterangan" rows="2" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none"></textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-outline-variant/20">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-xs font-semibold text-outline hover:bg-surface-container rounded-xl transition">Batal</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-primary hover:bg-primary-container rounded-xl shadow-sm transition">Simpan Perubahan</button>
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
    form.action = `/admin/inventaris/${item.id}`;
    document.getElementById('edit_nomor').value = item.nomor || '';
    document.getElementById('edit_kode').value = item.kode_inventaris || '';
    document.getElementById('edit_jenis').value = item.jenis_inventaris || '';
    document.getElementById('edit_nama').value = item.nama_barang || '';
    document.getElementById('edit_lokasi').value = item.lokasi || 'Gereja';
    document.getElementById('edit_jumlah').value = item.jumlah || 1;
    document.getElementById('edit_satuan').value = item.satuan || 'Unit';
    document.getElementById('edit_kondisi').value = item.kondisi || 'Baik';
    document.getElementById('edit_status').value = item.status_ketersediaan || 'Tersedia';
    document.getElementById('edit_tanggal').value = item.tanggal_perolehan ? item.tanggal_perolehan.split('T')[0] : '';
    document.getElementById('edit_keterangan').value = item.keterangan || '';

    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection
