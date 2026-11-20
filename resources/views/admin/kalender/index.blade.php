@extends('layouts.admin')

@section('title', 'Kalender Liturgi & Acara Tahunan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Kalender Liturgi & Acara Gereja</h1>
            <p class="text-sm text-gray-500">Jadwal hari raya gerejawi, retret, doa puasa, rapat majelis, dan agenda tahunan GBI Shekinah Glory.</p>
        </div>
        <div>
            <button type="button" onclick="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Agenda Acara
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-xs">
        <form method="GET" action="{{ route('admin.kalender.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Bulan</label>
                <select name="bulan" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        @php $mStr = sprintf('%02d', $m); @endphp
                        <option value="{{ $mStr }}" {{ $bulan == $mStr ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tahun</label>
                <select name="tahun" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    @for($y = date('Y') + 1; $y >= 2024; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Kategori Acara</label>
                <select name="kategori" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    <option value="">Semua Kategori</option>
                    <option value="hari_raya" {{ $kategori == 'hari_raya' ? 'selected' : '' }}>Hari Raya Gerejawi</option>
                    <option value="ibadah_khusus" {{ $kategori == 'ibadah_khusus' ? 'selected' : '' }}>Ibadah Khusus / KKR</option>
                    <option value="retret_kkr" {{ $kategori == 'retret_kkr' ? 'selected' : '' }}>Retret & Pembinaan</option>
                    <option value="rapat_majelis" {{ $kategori == 'rapat_majelis' ? 'selected' : '' }}>Rapat Majelis / Sidang</option>
                    <option value="kegiatan_pemuda" {{ $kategori == 'kegiatan_pemuda' ? 'selected' : '' }}>Youth & Kategorial</option>
                    <option value="doa_puasa" {{ $kategori == 'doa_puasa' ? 'selected' : '' }}>Doa & Puasa</option>
                    <option value="lainnya" {{ $kategori == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full py-2 px-3 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-dark transition">
                    Tampilkan
                </button>
                <a href="{{ route('admin.kalender.index') }}" class="py-2 px-3 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Event List Cards (2 Cols) -->
        <div class="lg:col-span-2 space-y-4">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500">
                Daftar Agenda ({{ $events->count() }} Kegiatan)
            </h2>

            @forelse($events as $ev)
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-xs hover:shadow-md transition flex flex-col sm:flex-row sm:items-start justify-between gap-4" style="border-left: 5px solid {{ $ev->warna_label }};">
                    <div class="flex items-start gap-4">
                        <!-- Date Badge -->
                        <div class="flex-shrink-0 w-16 h-16 rounded-xl bg-amber-50 text-amber-900 border border-amber-200 flex flex-col items-center justify-center text-center">
                            <span class="text-xs font-bold uppercase tracking-wider text-amber-700">{{ $ev->tanggal_mulai->format('M') }}</span>
                            <span class="text-2xl font-black">{{ $ev->tanggal_mulai->format('d') }}</span>
                        </div>

                        <!-- Details -->
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-base font-bold text-gray-900">{{ $ev->judul_acara }}</h3>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium" style="background-color: {{ $ev->warna_label }}15; color: {{ $ev->warna_label }};">
                                    {{ $ev->kategori_label }}
                                </span>
                                @if($ev->tampilkan_di_web)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">Tayang di Web</span>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500 pt-1">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $ev->waktu ?? 'WITA' }}
                                    @if($ev->tanggal_selesai && $ev->tanggal_selesai->ne($ev->tanggal_mulai))
                                        (s.d. {{ $ev->tanggal_selesai->format('d M Y') }})
                                    @endif
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $ev->lokasi }}
                                </span>
                            </div>

                            @if($ev->deskripsi)
                                <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $ev->deskripsi }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 self-end sm:self-start">
                        <button type="button" onclick='openEditModal(@json($ev))' class="p-1.5 text-gray-400 hover:text-primary rounded-lg hover:bg-gray-50 transition" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <form action="{{ route('admin.kalender.destroy', $ev->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda acara ini?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl p-12 text-center border border-gray-100 shadow-xs">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm font-medium text-gray-900">Belum ada acara tercatat pada periode ini</p>
                    <p class="text-xs text-gray-400 mt-1">Tambahkan hari raya gerejawi, ibadah khusus, atau retret jemaat.</p>
                </div>
            @endforelse
        </div>

        <!-- Sidebar / Upcoming Highlight -->
        <div class="space-y-4">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500">
                5 Acara Terdekat
            </h2>

            <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-xs space-y-4">
                @forelse($upcomingEvents as $up)
                    <div class="flex items-start gap-3 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                        <div class="w-2.5 h-2.5 rounded-full mt-1.5 flex-shrink-0" style="background-color: {{ $up->warna_label }};"></div>
                        <div class="space-y-0.5">
                            <h4 class="text-sm font-semibold text-gray-900 leading-snug">{{ $up->judul_acara }}</h4>
                            <div class="text-xs text-amber-800 font-medium">{{ $up->tanggal_mulai->format('l, d M Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $up->waktu }} &bull; {{ $up->lokasi }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-4">Tidak ada agenda mendatang terdekat.</p>
                @endforelse
            </div>

            <!-- Panduan Warna Label -->
            <div class="bg-amber-50/60 rounded-xl p-4 border border-amber-200/60">
                <h4 class="text-xs font-bold text-amber-900 uppercase tracking-wider mb-2">Panduan Label Liturgi</h4>
                <div class="space-y-2 text-xs text-amber-800">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[#775a19]"></span>
                        <span>Emas/Coklat: Hari Raya Utama (Natal, Paskah)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[#16a34a]"></span>
                        <span>Hijau: Masa Biasa / Pertumbuhan Rohani</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[#dc2626]"></span>
                        <span>Merah: Pentakosta / KKR / Pelayanan Roh Kudus</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[#7c3aed]"></span>
                        <span>Ungu: Masa Pra-Paskah / Doa Puasa</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Acara -->
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl relative animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Tambah Agenda Kalender</h3>
            <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('admin.kalender.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Judul Acara / Kegiatan *</label>
                <input type="text" name="judul_acara" required placeholder="Contoh: Ibadah Perayaan Paskah Subuh" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori *</label>
                    <select name="kategori" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="hari_raya">Hari Raya Gerejawi</option>
                        <option value="ibadah_khusus">Ibadah Khusus / KKR</option>
                        <option value="retret_kkr">Retret & Pembinaan</option>
                        <option value="rapat_majelis">Rapat Majelis / Sidang</option>
                        <option value="kegiatan_pemuda">Youth & Kategorial</option>
                        <option value="doa_puasa">Doa & Puasa</option>
                        <option value="lainnya">Kegiatan Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Warna Label</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="warna_label" value="#775a19" class="h-9 w-12 rounded border-gray-300 cursor-pointer">
                        <span class="text-xs text-gray-500">Pilih penanda warna</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Mulai *</label>
                    <input type="date" name="tanggal_mulai" value="{{ date('Y-m-d') }}" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Selesai (Opsional)</label>
                    <input type="date" name="tanggal_selesai" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Waktu</label>
                    <input type="text" name="waktu" placeholder="Contoh: 09:00 - 12:00 WITA" value="09:00 WITA" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Lokasi *</label>
                    <input type="text" name="lokasi" required value="GBI Shekinah Glory Namosain" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi & Catatan Acara</label>
                <textarea name="deskripsi" rows="3" placeholder="Uraian tema, pembicara, pakaian ibadah, atau persiapan..." class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="tampilkan_di_web" id="tampilkan_web" value="1" checked class="rounded border-gray-300 text-primary focus:ring-primary">
                <label for="tampilkan_web" class="text-xs text-gray-700">Tampilkan kegiatan ini di Kalender Profil Website Publik</label>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark">Simpan Acara</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Acara -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl relative animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Edit Agenda Kalender</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="editForm" method="POST" class="mt-4 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Judul Acara / Kegiatan *</label>
                <input type="text" name="judul_acara" id="edit_judul" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori *</label>
                    <select name="kategori" id="edit_kategori" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="hari_raya">Hari Raya Gerejawi</option>
                        <option value="ibadah_khusus">Ibadah Khusus / KKR</option>
                        <option value="retret_kkr">Retret & Pembinaan</option>
                        <option value="rapat_majelis">Rapat Majelis / Sidang</option>
                        <option value="kegiatan_pemuda">Youth & Kategorial</option>
                        <option value="doa_puasa">Doa & Puasa</option>
                        <option value="lainnya">Kegiatan Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Warna Label</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="warna_label" id="edit_warna" class="h-9 w-12 rounded border-gray-300 cursor-pointer">
                        <span class="text-xs text-gray-500">Pilih warna</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Mulai *</label>
                    <input type="date" name="tanggal_mulai" id="edit_mulai" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Selesai (Opsional)</label>
                    <input type="date" name="tanggal_selesai" id="edit_selesai" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Waktu</label>
                    <input type="text" name="waktu" id="edit_waktu" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Lokasi *</label>
                    <input type="text" name="lokasi" id="edit_lokasi" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi & Catatan Acara</label>
                <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="tampilkan_di_web" id="edit_tampilkan_web" value="1" class="rounded border-gray-300 text-primary focus:ring-primary">
                <label for="edit_tampilkan_web" class="text-xs text-gray-700">Tampilkan kegiatan ini di Kalender Profil Website Publik</label>
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
    form.action = `/admin/kalender/${item.id}`;
    
    document.getElementById('edit_judul').value = item.judul_acara || '';
    document.getElementById('edit_kategori').value = item.kategori || 'hari_raya';
    document.getElementById('edit_warna').value = item.warna_label || '#775a19';
    document.getElementById('edit_mulai').value = item.tanggal_mulai ? item.tanggal_mulai.split('T')[0] : '';
    document.getElementById('edit_selesai').value = item.tanggal_selesai ? item.tanggal_selesai.split('T')[0] : '';
    document.getElementById('edit_waktu').value = item.waktu || '';
    document.getElementById('edit_lokasi').value = item.lokasi || '';
    document.getElementById('edit_deskripsi').value = item.deskripsi || '';
    document.getElementById('edit_tampilkan_web').checked = Boolean(item.tampilkan_di_web);

    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection

