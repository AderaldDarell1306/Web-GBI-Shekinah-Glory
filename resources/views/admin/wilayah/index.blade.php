@extends('layouts.admin')

@section('title', 'Manajemen Wilayah & Sektor - SIMJ GBI Shekinah Glory')
@section('page_title', 'Struktur Wilayah & Sektor Pelayanan')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-serif-display text-2xl font-bold text-primary">Daftar Sektor & Wilayah Pelayanan</h2>
            <p class="text-xs text-on-surface-variant mt-0.5">
                Struktur 3 Wilayah Besar (Namosain, Rumah 7, Osmok) dengan 12 Sub-Wilayah yang terintegrasi dengan kode KK 6 digit.
            </p>
        </div>
        <button type="button" onclick="document.getElementById('modal-tambah-wilayah').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-secondary hover:bg-secondary/90 text-white font-bold text-xs shadow-md transition">
            <span class="material-symbols-outlined text-[18px]">add_location_alt</span>
            <span>Tambah Sub-Wilayah Baru</span>
        </button>
    </div>

    <!-- Ringkasan Card 3 Wilayah Besar -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @php
            $groupedWilayah = $wilayahs->groupBy('wilayah_besar');
        @endphp

        <!-- 1. Namosain -->
        <div class="bg-gradient-to-br from-primary to-primary-container text-white p-5 rounded-2xl shadow-sm border border-white/10 space-y-3">
            <div class="flex items-center justify-between">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-white/20 text-secondary-fixed">
                    Prefix KK: 11 - 12
                </span>
                <span class="text-xs font-bold text-secondary-fixed">Wilayah 1</span>
            </div>
            <div>
                <h3 class="font-serif-display text-xl font-bold">Namosain</h3>
                <p class="text-xs text-white/75 mt-0.5">2 Sub-Wilayah / Sektor Pelayanan</p>
            </div>
            <div class="pt-2 border-t border-white/10 flex items-center justify-between text-xs">
                <span class="text-white/70">Total Jemaat:</span>
                <span class="font-bold text-secondary-fixed font-mono text-sm">
                    {{ $groupedWilayah->get('Namosain', collect())->sum('jemaat_count') }} Jiwa
                </span>
            </div>
        </div>

        <!-- 2. Rumah 7 -->
        <div class="bg-gradient-to-br from-primary to-primary-container text-white p-5 rounded-2xl shadow-sm border border-white/10 space-y-3">
            <div class="flex items-center justify-between">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-white/20 text-secondary-fixed">
                    Prefix KK: 21 - 24
                </span>
                <span class="text-xs font-bold text-secondary-fixed">Wilayah 2</span>
            </div>
            <div>
                <h3 class="font-serif-display text-xl font-bold">Rumah 7</h3>
                <p class="text-xs text-white/75 mt-0.5">4 Sub-Wilayah / Sektor Pelayanan</p>
            </div>
            <div class="pt-2 border-t border-white/10 flex items-center justify-between text-xs">
                <span class="text-white/70">Total Jemaat:</span>
                <span class="font-bold text-secondary-fixed font-mono text-sm">
                    {{ $groupedWilayah->get('Rumah 7', collect())->sum('jemaat_count') }} Jiwa
                </span>
            </div>
        </div>

        <!-- 3. Osmok -->
        <div class="bg-gradient-to-br from-primary to-primary-container text-white p-5 rounded-2xl shadow-sm border border-white/10 space-y-3">
            <div class="flex items-center justify-between">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-white/20 text-secondary-fixed">
                    Prefix KK: 31 - 36
                </span>
                <span class="text-xs font-bold text-secondary-fixed">Wilayah 3</span>
            </div>
            <div>
                <h3 class="font-serif-display text-xl font-bold">Osmok</h3>
                <p class="text-xs text-white/75 mt-0.5">6 Sub-Wilayah / Sektor Pelayanan</p>
            </div>
            <div class="pt-2 border-t border-white/10 flex items-center justify-between text-xs">
                <span class="text-white/70">Total Jemaat:</span>
                <span class="font-bold text-secondary-fixed font-mono text-sm">
                    {{ $groupedWilayah->get('Osmok', collect())->sum('jemaat_count') }} Jiwa
                </span>
            </div>
        </div>
    </div>

    <!-- DAFTAR SUB-WILAYAH PER WILAYAH BESAR -->
    @foreach(['Namosain', 'Rumah 7', 'Osmok'] as $namaWb)
    @php
        $subs = $groupedWilayah->get($namaWb, collect());
    @endphp
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b border-outline-variant/30 pb-2">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-secondary-container text-secondary flex items-center justify-center font-bold text-xs">
                    {{ $loop->iteration }}
                </div>
                <h3 class="font-serif-display text-lg font-bold text-primary">Wilayah Besar {{ $namaWb }}</h3>
                <span class="text-xs text-outline font-semibold">({{ $subs->count() }} Sub-Wilayah)</span>
            </div>
            <a href="{{ route('admin.jemaat.index', ['wilayah_besar' => $namaWb]) }}" class="text-xs text-secondary font-bold hover:underline flex items-center gap-1">
                <span>Lihat Semua Jemaat {{ $namaWb }}</span>
                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($subs as $w)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-outline-variant/30 flex flex-col justify-between space-y-4 hover:border-secondary transition">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-1.5">
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-mono font-bold bg-primary text-secondary-fixed">
                                Prefix KK: {{ $w->kode_prefix }}
                            </span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-mono text-outline border border-outline-variant/40">
                                {{ $w->kode_wilayah }}
                            </span>
                        </div>
                        <span class="text-[10px] font-bold {{ $w->status === 'aktif' ? 'text-green-700' : 'text-gray-400' }} uppercase">
                            {{ $w->status }}
                        </span>
                    </div>
                    <h4 class="font-serif-display text-base font-bold text-primary mb-1">{{ $w->nama_wilayah }}</h4>
                    <p class="text-xs text-on-surface-variant leading-relaxed line-clamp-2">{{ $w->deskripsi ?? 'Cakupan pelayanan komsel' }}</p>
                </div>

                <div class="space-y-1.5 pt-3 border-t border-outline-variant/20 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-outline">Ketua Sektor:</span>
                        <span class="font-bold text-primary">{{ $w->nama_ketua ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-outline">No. Telepon:</span>
                        <span class="font-semibold text-primary">{{ $w->telepon_ketua ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between bg-surface-container-low p-2 rounded-lg mt-2">
                        <span class="text-outline">Total Jemaat:</span>
                        <a href="{{ route('admin.jemaat.index', ['wilayah_id' => $w->id]) }}" class="font-bold text-secondary hover:underline">
                            {{ $w->jemaat_count }} Jiwa
                        </a>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-outline-variant/20">
                    <button type="button" onclick="editWilayah({{ json_encode($w) }})" class="p-1.5 rounded-lg text-outline hover:text-secondary hover:bg-surface-container transition" title="Ubah Wilayah">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </button>
                    <form action="{{ route('admin.wilayah.destroy', $w->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus wilayah \'{{ $w->nama_wilayah }}\'?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 rounded-lg text-outline hover:text-red-600 hover:bg-red-50 transition" title="Hapus Wilayah">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-3 p-6 text-center text-outline text-xs bg-white rounded-xl border border-dashed">
                Belum ada sub-wilayah untuk {{ $namaWb }}.
            </div>
            @endforelse
        </div>
    </div>
    @endforeach
</div>

<!-- MODAL TAMBAH WILAYAH -->
<div id="modal-tambah-wilayah" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="font-serif-display text-lg font-bold text-primary">Tambah Sub-Wilayah Baru</h3>
            <button type="button" onclick="document.getElementById('modal-tambah-wilayah').classList.add('hidden')" class="text-outline hover:text-primary">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.wilayah.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-primary mb-1">Wilayah Besar <span class="text-red-500">*</span></label>
                <select name="wilayah_besar" required class="w-full px-3 py-2 rounded-xl border border-outline-variant focus:outline-none focus:border-secondary bg-white">
                    <option value="Namosain">Namosain (Prefix 11-12)</option>
                    <option value="Rumah 7">Rumah 7 (Prefix 21-24)</option>
                    <option value="Osmok">Osmok (Prefix 31-36)</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">Prefix 2 Digit KK <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_prefix" required placeholder="Contoh: 31" maxlength="10" class="w-full px-3 py-2 rounded-xl border border-outline-variant focus:outline-none focus:border-secondary font-mono">
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Kode Sektor <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_wilayah" required placeholder="Contoh: OSM-01" class="w-full px-3 py-2 rounded-xl border border-outline-variant focus:outline-none focus:border-secondary uppercase">
                </div>
            </div>
            <div>
                <label class="block font-bold text-primary mb-1">Nama Sub-Wilayah <span class="text-red-500">*</span></label>
                <input type="text" name="nama_wilayah" required placeholder="Contoh: Osmok - Sub Wilayah 1" class="w-full px-3 py-2 rounded-xl border border-outline-variant focus:outline-none focus:border-secondary">
            </div>
            <div>
                <label class="block font-bold text-primary mb-1">Nama Ketua Sektor</label>
                <input type="text" name="nama_ketua" placeholder="Nama Penanggung Jawab" class="w-full px-3 py-2 rounded-xl border border-outline-variant focus:outline-none focus:border-secondary">
            </div>
            <div>
                <label class="block font-bold text-primary mb-1">No. HP Ketua</label>
                <input type="text" name="telepon_ketua" placeholder="08xxxxxxxxxx" class="w-full px-3 py-2 rounded-xl border border-outline-variant focus:outline-none focus:border-secondary">
            </div>
            <div>
                <label class="block font-bold text-primary mb-1">Deskripsi / Cakupan Area</label>
                <textarea name="deskripsi" rows="2" placeholder="Daftar jalan atau cakupan area..." class="w-full px-3 py-2 rounded-xl border border-outline-variant focus:outline-none focus:border-secondary"></textarea>
            </div>
            <div>
                <label class="block font-bold text-primary mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-outline-variant focus:outline-none focus:border-secondary bg-white">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t">
                <button type="button" onclick="document.getElementById('modal-tambah-wilayah').classList.add('hidden')" class="px-4 py-2 rounded-lg border text-outline hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-primary hover:bg-primary-container text-white font-bold">Simpan Sub-Wilayah</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT WILAYAH -->
<div id="modal-edit-wilayah" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="font-serif-display text-lg font-bold text-primary">Ubah Data Sub-Wilayah</h3>
            <button type="button" onclick="document.getElementById('modal-edit-wilayah').classList.add('hidden')" class="text-outline hover:text-primary">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="form-edit-wilayah" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-bold text-primary mb-1">Wilayah Besar</label>
                <select name="wilayah_besar" id="edit-wilayah-besar" class="w-full px-3 py-2 rounded-xl border border-outline-variant focus:outline-none focus:border-secondary bg-white">
                    <option value="Namosain">Namosain</option>
                    <option value="Rumah 7">Rumah 7</option>
                    <option value="Osmok">Osmok</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">Prefix 2 Digit KK</label>
                    <input type="text" name="kode_prefix" id="edit-prefix" class="w-full px-3 py-2 rounded-xl border border-outline-variant focus:outline-none focus:border-secondary font-mono">
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Kode Sektor</label>
                    <input type="text" name="kode_wilayah" id="edit-kode" required class="w-full px-3 py-2 rounded-xl border border-outline-variant focus:outline-none focus:border-secondary uppercase">
                </div>
            </div>
            <div>
                <label class="block font-bold text-primary mb-1">Nama Sub-Wilayah <span class="text-red-500">*</span></label>
                <input type="text" name="nama_wilayah" id="edit-nama" required class="w-full px-3 py-2 rounded-xl border border-outline-variant focus:outline-none focus:border-secondary">
            </div>
            <div>
                <label class="block font-bold text-primary mb-1">Nama Ketua Sektor</label>
                <input type="text" name="nama_ketua" id="edit-ketua" class="w-full px-3 py-2 rounded-xl border border-outline-variant focus:outline-none focus:border-secondary">
            </div>
            <div>
                <label class="block font-bold text-primary mb-1">No. HP Ketua</label>
                <input type="text" name="telepon_ketua" id="edit-telepon" class="w-full px-3 py-2 rounded-xl border border-outline-variant focus:outline-none focus:border-secondary">
            </div>
            <div>
                <label class="block font-bold text-primary mb-1">Deskripsi / Cakupan Area</label>
                <textarea name="deskripsi" id="edit-deskripsi" rows="2" class="w-full px-3 py-2 rounded-xl border border-outline-variant focus:outline-none focus:border-secondary"></textarea>
            </div>
            <div>
                <label class="block font-bold text-primary mb-1">Status</label>
                <select name="status" id="edit-status" class="w-full px-3 py-2 rounded-xl border border-outline-variant focus:outline-none focus:border-secondary bg-white">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t">
                <button type="button" onclick="document.getElementById('modal-edit-wilayah').classList.add('hidden')" class="px-4 py-2 rounded-lg border text-outline hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-secondary hover:bg-secondary/90 text-white font-bold">Perbarui Sub-Wilayah</button>
            </div>
        </form>
    </div>
</div>

<script>
function editWilayah(w) {
    document.getElementById('form-edit-wilayah').action = `/admin/wilayah/${w.id}`;
    document.getElementById('edit-wilayah-besar').value = w.wilayah_besar || 'Namosain';
    document.getElementById('edit-prefix').value = w.kode_prefix || '';
    document.getElementById('edit-nama').value = w.nama_wilayah;
    document.getElementById('edit-kode').value = w.kode_wilayah;
    document.getElementById('edit-ketua').value = w.nama_ketua || '';
    document.getElementById('edit-telepon').value = w.telepon_ketua || '';
    document.getElementById('edit-deskripsi').value = w.deskripsi || '';
    document.getElementById('edit-status').value = w.status;
    document.getElementById('modal-edit-wilayah').classList.remove('hidden');
}
</script>
@endsection
