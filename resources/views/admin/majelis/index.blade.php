@extends('layouts.admin')

@section('title', 'Data Majelis Pelayanan - SIMJ GBI Shekinah Glory')
@section('page_title', 'Data Majelis Pelayanan Gereja')

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-serif-display text-2xl font-bold text-primary">Data Majelis & Penatua</h2>
            <p class="text-xs text-on-surface-variant mt-0.5">Daftar jemaat yang berstatus majelis bertugas dalam pelayanan ibadah (PRD v2.1 AC-R04).</p>
        </div>
        @if(Auth::user() && !Auth::user()->isJemaat())
        <div class="flex items-center gap-2">
            <button type="button" onclick="openCreateModal()" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-secondary hover:bg-secondary/90 text-white font-bold text-xs shadow-md transition">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                <span>Tambah Majelis</span>
            </button>
        </div>
        @endif
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 rounded-2xl bg-white border border-outline-variant/30 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-outline uppercase tracking-wider">Total Majelis</p>
                <h3 class="font-serif-display text-2xl font-bold text-primary mt-1">{{ $totalAktif + $totalPurna }} <span class="text-xs font-normal text-outline">Orang</span></h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[22px]">diversity_3</span>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-outline-variant/30 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-outline uppercase tracking-wider">Majelis Aktif</p>
                <h3 class="font-serif-display text-2xl font-bold text-emerald-700 mt-1">{{ $totalAktif }} <span class="text-xs font-normal text-outline">Orang</span></h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">verified</span>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-outline-variant/30 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-outline uppercase tracking-wider">Purna Tugas</p>
                <h3 class="font-serif-display text-2xl font-bold text-amber-700 mt-1">{{ $totalPurna }} <span class="text-xs font-normal text-outline">Orang</span></h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">history</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 shadow-xs border border-outline-variant/30">
        <form method="GET" action="{{ route('admin.majelis.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-semibold text-primary mb-1">Cari Majelis</label>
                <input type="text" name="q" value="{{ $q }}" placeholder="Nama, bidang, SK..." class="w-full text-xs rounded-xl border border-outline-variant/60 py-2.5 px-3 focus:border-secondary focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-primary mb-1">Status Keaktifan</label>
                <select name="status" class="w-full text-xs rounded-xl border border-outline-variant/60 py-2.5 px-3 focus:border-secondary focus:outline-none bg-white">
                    <option value="semua" {{ $status === 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="purna_tugas" {{ $status === 'purna_tugas' ? 'selected' : '' }}>Purna Tugas</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-primary mb-1">Jabatan Majelis</label>
                <select name="jabatan" class="w-full text-xs rounded-xl border border-outline-variant/60 py-2.5 px-3 focus:border-secondary focus:outline-none bg-white">
                    <option value="">Semua Jabatan</option>
                    @foreach($jabatanOptions as $val => $lbl)
                        <option value="{{ $val }}" {{ $jabatan === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-primary hover:bg-primary-container text-white text-xs font-bold rounded-xl transition shadow-sm">
                    Filter
                </button>
                <a href="{{ route('admin.majelis.index') }}" class="py-2.5 px-3 bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-semibold rounded-xl transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Data Majelis -->
    <div class="bg-white rounded-2xl shadow-xs border border-outline-variant/30 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-outline-variant/20 text-xs">
                <thead class="bg-surface-container-low text-primary font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 text-left">Nama Majelis</th>
                        <th class="py-3.5 px-4 text-left">Jabatan</th>
                        <th class="py-3.5 px-4 text-left">Bidang Pelayanan</th>
                        <th class="py-3.5 px-4 text-left">Periode & No. SK</th>
                        <th class="py-3.5 px-4 text-left">Kontak (HP)</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        @if(Auth::user() && !Auth::user()->isJemaat())
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10 bg-white">
                    @forelse($majelisList as $m)
                    <tr class="hover:bg-surface-container-low/50 transition">
                        <td class="py-3 px-4 font-bold text-primary">
                            {{ $m->nama_majelis }}
                            @if($m->jemaat)
                                <span class="block text-2xs text-outline font-normal">ID Jemaat: {{ $m->jemaat->no_kk }} • {{ $m->jemaat->wilayah->nama_wilayah ?? '' }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-semibold text-secondary">
                            {{ $m->jabatan }}
                        </td>
                        <td class="py-3 px-4 text-on-surface">
                            <span class="font-semibold text-primary">{{ $m->bidang_pelayanan ?? '-' }}</span>
                            @if($m->catatan)
                                <span class="block text-2xs text-secondary font-medium mt-0.5 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[13px]">assignment_turned_in</span>
                                    <span>{{ $m->catatan }}</span>
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-outline font-mono">
                            {{ $m->periode ?? '-' }}
                            @if($m->no_sk)
                                <span class="block text-2xs text-primary font-sans font-medium">SK: {{ $m->no_sk }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-on-surface">
                            {{ $m->no_hp ?? '-' }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-2xs font-bold {{ $m->status === 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600' }}">
                                {{ $m->status === 'aktif' ? 'Aktif' : 'Purna Tugas' }}
                            </span>
                        </td>
                        @if(Auth::user() && !Auth::user()->isJemaat())
                        <td class="py-3 px-4 text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-1">
                                <button type="button" onclick='openEditModal(@json($m))' class="p-1.5 rounded-lg text-outline hover:text-secondary hover:bg-secondary-container/30 transition" title="Edit">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                </button>
                                <form action="{{ route('admin.majelis.destroy', $m->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data majelis \'{{ $m->nama_majelis }}\'?');">
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
                        <td colspan="7" class="py-12 text-center text-outline">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[36px] text-outline">group_off</span>
                                <span class="font-bold text-sm text-primary">Belum Ada Data Majelis</span>
                                <p class="text-xs text-on-surface-variant">Tambahkan data majelis jemaat yang bertugas dalam pelayanan gereja.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($majelisList->hasPages())
        <div class="px-5 py-4 border-t border-outline-variant/20">
            {{ $majelisList->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Majelis -->
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl relative animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-outline-variant/30">
            <h3 class="text-base font-bold text-primary font-serif-display">Tambah Data Majelis Baru</h3>
            <button type="button" onclick="closeCreateModal()" class="text-outline hover:text-primary">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form action="{{ route('admin.majelis.store') }}" method="POST" class="mt-4 space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-primary mb-1">Pilih Dari Jemaat Terdaftar (Opsional)</label>
                <select name="jemaat_id" id="modal_jemaat_id" onchange="autoFillJemaat(this)" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
                    <option value="">-- Pilih Jemaat atau Ketik Nama Manual di Bawah --</option>
                    @foreach($jemaatList as $j)
                        <option value="{{ $j->id }}" data-nama="{{ $j->nama_lengkap }}" data-hp="{{ $j->no_hp }}">{{ $j->nama_lengkap }} ({{ $j->status_keluarga ?? 'Jemaat' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-primary mb-1">Nama Lengkap Majelis <span class="text-red-500">*</span></label>
                <input type="text" name="nama_majelis" id="modal_nama" required placeholder="Contoh: Pnt. Daniel Talaen" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">Jabatan Majelis <span class="text-red-500">*</span></label>
                    <select name="jabatan" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
                        @foreach($jabatanOptions as $val => $lbl)
                            <option value="{{ $val }}">{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Status Keaktifan *</label>
                    <select name="status" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
                        <option value="aktif">Aktif</option>
                        <option value="purna_tugas">Purna Tugas</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">Bidang Pelayanan</label>
                    <input type="text" name="bidang_pelayanan" placeholder="Liturgi, Diakonia, dll" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Periode Masa Tugas</label>
                    <input type="text" name="periode" placeholder="Contoh: 2024 - 2029" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">No. SK Peneguhan</label>
                    <input type="text" name="no_sk" placeholder="SK-MJ-01/2024" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">No. Handphone / WA</label>
                    <input type="text" name="no_hp" id="modal_hp" placeholder="08xxxxxxxxxx" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block font-bold text-primary mb-1">Catatan Tambahan</label>
                <textarea name="catatan" rows="2" placeholder="Catatan kategorial atau penugasan khusus..." class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none"></textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-outline-variant/20">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-xs font-semibold text-outline hover:bg-surface-container rounded-xl transition">Batal</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-primary hover:bg-primary-container rounded-xl shadow-sm transition">Simpan Majelis</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Majelis -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl relative animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-outline-variant/30">
            <h3 class="text-base font-bold text-primary font-serif-display">Edit Data Majelis</h3>
            <button type="button" onclick="closeEditModal()" class="text-outline hover:text-primary">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form id="editForm" method="POST" class="mt-4 space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-bold text-primary mb-1">Nama Lengkap Majelis <span class="text-red-500">*</span></label>
                <input type="text" name="nama_majelis" id="edit_nama" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">Jabatan Majelis <span class="text-red-500">*</span></label>
                    <select name="jabatan" id="edit_jabatan" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
                        @foreach($jabatanOptions as $val => $lbl)
                            <option value="{{ $val }}">{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Status Keaktifan *</label>
                    <select name="status" id="edit_status" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
                        <option value="aktif">Aktif</option>
                        <option value="purna_tugas">Purna Tugas</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">Bidang Pelayanan</label>
                    <input type="text" name="bidang_pelayanan" id="edit_bidang" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Periode Masa Tugas</label>
                    <input type="text" name="periode" id="edit_periode" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">No. SK Peneguhan</label>
                    <input type="text" name="no_sk" id="edit_sk" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">No. Handphone / WA</label>
                    <input type="text" name="no_hp" id="edit_hp" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block font-bold text-primary mb-1">Catatan Tambahan</label>
                <textarea name="catatan" id="edit_catatan" rows="2" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none"></textarea>
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
function autoFillJemaat(select) {
    const opt = select.options[select.selectedIndex];
    if (opt && opt.value) {
        document.getElementById('modal_nama').value = opt.getAttribute('data-nama') || '';
        document.getElementById('modal_hp').value = opt.getAttribute('data-hp') || '';
    }
}
function openEditModal(item) {
    const form = document.getElementById('editForm');
    form.action = `/admin/majelis/${item.id}`;
    document.getElementById('edit_nama').value = item.nama_majelis || '';
    document.getElementById('edit_jabatan').value = item.jabatan || 'Majelis Jemaat';
    document.getElementById('edit_status').value = item.status || 'aktif';
    document.getElementById('edit_bidang').value = item.bidang_pelayanan || '';
    document.getElementById('edit_periode').value = item.periode || '';
    document.getElementById('edit_sk').value = item.no_sk || '';
    document.getElementById('edit_hp').value = item.no_hp || '';
    document.getElementById('edit_catatan').value = item.catatan || '';

    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection
