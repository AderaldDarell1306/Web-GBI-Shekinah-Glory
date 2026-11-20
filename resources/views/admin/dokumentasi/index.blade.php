@extends('layouts.admin')

@section('title', 'Dokumentasi Foto Kegiatan - SIMJ GBI Shekinah Glory')
@section('page_title', 'Modul Dokumentasi & Galeri Foto Kegiatan')

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-serif-display text-2xl font-bold text-primary">Galeri Dokumentasi Foto Kegiatan</h2>
            <p class="text-xs text-on-surface-variant mt-0.5">Kelola foto dokumentasi kegiatan ibadah dan pelayanan gereja untuk publik (PRD Modul 10 AC-R06).</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('public.dokumentasi') }}" target="_blank" class="flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-white hover:bg-gray-50 text-secondary border border-secondary/30 font-bold text-xs shadow-xs transition">
                <span class="material-symbols-outlined text-[18px]">visibility</span>
                <span>Lihat Galeri Publik</span>
            </a>
            @if(Auth::user() && !Auth::user()->isJemaat())
            <button type="button" onclick="openUploadModal()" class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-secondary hover:bg-secondary/90 text-white font-bold text-xs shadow-md transition">
                <span class="material-symbols-outlined text-[18px]">add_photo_alternate</span>
                <span>+ Unggah Foto Baru</span>
            </button>
            @endif
        </div>
    </div>

    <!-- Filter Category Tabs -->
    <div class="bg-white rounded-2xl p-4 shadow-xs border border-outline-variant/30 flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.dokumentasi.index') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ empty($kategori) ? 'bg-primary text-white shadow-xs' : 'bg-surface-container text-on-surface hover:bg-surface-container-high' }}">
                Semua Kategori ({{ $fotoList->total() }})
            </a>
            @foreach($kategoriList as $kat)
            <a href="{{ route('admin.dokumentasi.index', ['kategori' => $kat]) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $kategori === $kat ? 'bg-primary text-white shadow-xs' : 'bg-surface-container text-on-surface hover:bg-surface-container-high' }}">
                {{ $kat }}
            </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('admin.dokumentasi.index') }}" class="flex items-center gap-2">
            @if($kategori)
                <input type="hidden" name="kategori" value="{{ $kategori }}">
            @endif
            <input type="text" name="q" value="{{ $q }}" placeholder="Cari foto kegiatan..." class="text-xs rounded-xl border border-outline-variant/60 py-1.5 px-3 focus:border-secondary focus:outline-none">
            <button type="submit" class="py-1.5 px-3 bg-secondary text-white text-xs font-bold rounded-xl transition">
                Cari
            </button>
        </form>
    </div>

    <!-- Responsive Photo Grid (AC-R06) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        @forelse($fotoList as $foto)
        <div class="bg-white rounded-2xl border border-outline-variant/30 overflow-hidden shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
            <div class="relative overflow-hidden aspect-[4/3] bg-surface-container">
                <img src="{{ $foto->foto_url }}" alt="{{ $foto->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-full text-2xs font-bold bg-primary/80 backdrop-blur-xs text-white">
                    {{ $foto->kategori_kegiatan }}
                </span>
                @if(!$foto->tampilkan_di_galeri)
                <span class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded-md text-2xs font-bold bg-amber-500 text-white">
                    Disembunyikan
                </span>
                @endif
            </div>

            <div class="p-4 space-y-2 flex-1 flex flex-col justify-between">
                <div>
                    <span class="text-2xs text-outline font-semibold block">{{ $foto->tanggal_kegiatan->translatedFormat('d F Y') }}</span>
                    <h3 class="font-bold text-sm text-primary line-clamp-1 mt-0.5">{{ $foto->judul }}</h3>
                    @if($foto->deskripsi)
                        <p class="text-xs text-on-surface-variant line-clamp-2 mt-1">{{ $foto->deskripsi }}</p>
                    @endif
                </div>

                @if(Auth::user() && !Auth::user()->isJemaat())
                <div class="pt-3 border-t border-outline-variant/20 flex items-center justify-between text-xs">
                    <span class="text-2xs text-outline">Oleh: {{ $foto->uploader->name ?? 'Admin' }}</span>
                    <div class="flex items-center gap-1">
                        <button type="button" onclick='openEditModal(@json($foto))' class="p-1 rounded text-outline hover:text-secondary hover:bg-secondary-container/30 transition" title="Edit">
                            <span class="material-symbols-outlined text-[16px]">edit</span>
                        </button>
                        <form action="{{ route('admin.dokumentasi.destroy', $foto->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus foto \'{{ $foto->judul }}\'?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1 rounded text-outline hover:text-red-600 hover:bg-red-50 transition" title="Hapus">
                                <span class="material-symbols-outlined text-[16px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center text-outline bg-white rounded-2xl border border-outline-variant/30">
            <div class="flex flex-col items-center justify-center gap-3">
                <span class="material-symbols-outlined text-[48px] text-outline">photo_library</span>
                <span class="font-bold text-base text-primary">Belum Ada Foto Dokumentasi</span>
                <p class="text-xs text-on-surface-variant max-w-md">Unggah foto dokumentasi kegiatan gereja agar dapat dilihat oleh jemaat dan publik.</p>
            </div>
        </div>
        @endforelse
    </div>

    @if($fotoList->hasPages())
    <div class="bg-white rounded-2xl p-4 border border-outline-variant/30 shadow-xs">
        {{ $fotoList->links() }}
    </div>
    @endif
</div>

<!-- Modal Unggah Foto Baru (AC-R06) -->
<div id="uploadModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl relative animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-outline-variant/30">
            <h3 class="text-base font-bold text-primary font-serif-display">Unggah Foto Dokumentasi Kegiatan</h3>
            <button type="button" onclick="closeUploadModal()" class="text-outline hover:text-primary">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form action="{{ route('admin.dokumentasi.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-primary mb-1">Judul Foto / Kegiatan <span class="text-red-500">*</span></label>
                <input type="text" name="judul" required placeholder="Contoh: Ibadah Syukur Natal Bersama Jemaat" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none font-semibold">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">Kategori Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" name="kategori_kegiatan" list="kategoriListDatalist" required placeholder="Pilih atau ketik kategori..." class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                    <datalist id="kategoriListDatalist">
                        <option value="Ibadah Raya Minggu">
                        <option value="Perayaan Hari Raya Gerejawi">
                        <option value="Pelayanan Diakonia & Kasih">
                        <option value="Pemuda & Remaja (Youth)">
                        <option value="Sekolah Minggu (ABI)">
                        <option value="Retret, Doa & KKR">
                        <option value="Pembangunan & Gotong Royong">
                    </datalist>
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Tanggal Kegiatan <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_kegiatan" value="{{ date('Y-m-d') }}" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
                </div>
            </div>

            <div>
                <label class="block font-bold text-primary mb-1">Pilih File Foto (JPG/PNG/WebP, Maks 8 MB) <span class="text-red-500">*</span></label>
                <input type="file" name="file_foto" accept="image/*" required class="w-full text-xs text-outline file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-secondary/10 file:text-secondary hover:file:bg-secondary/20">
            </div>

            <div>
                <label class="block font-bold text-primary mb-1">Deskripsi / Uraian Foto (Opsional)</label>
                <textarea name="deskripsi" rows="3" placeholder="Catatan singkat tentang momen kegiatan ini..." class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="tampilkan_di_galeri" id="check_tampilkan" value="1" checked class="rounded border-outline-variant text-secondary focus:ring-secondary">
                <label for="check_tampilkan" class="font-semibold text-primary">Tampilkan di Galeri Foto Publik</label>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-outline-variant/20">
                <button type="button" onclick="closeUploadModal()" class="px-4 py-2 text-xs font-semibold text-outline hover:bg-surface-container rounded-xl transition">Batal</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-primary hover:bg-primary-container rounded-xl shadow-sm transition">Unggah Foto</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Foto -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl relative animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-outline-variant/30">
            <h3 class="text-base font-bold text-primary font-serif-display">Edit Informasi Foto Kegiatan</h3>
            <button type="button" onclick="closeEditModal()" class="text-outline hover:text-primary">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-bold text-primary mb-1">Judul Foto / Kegiatan <span class="text-red-500">*</span></label>
                <input type="text" name="judul" id="edit_judul" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none font-semibold">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-primary mb-1">Kategori Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" name="kategori_kegiatan" id="edit_kategori" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-primary mb-1">Tanggal Kegiatan <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_kegiatan" id="edit_tanggal" required class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
                </div>
            </div>

            <div>
                <label class="block font-bold text-primary mb-1">Ganti File Foto (Opsional)</label>
                <input type="file" name="file_foto" accept="image/*" class="w-full text-xs text-outline file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-secondary/10 file:text-secondary hover:file:bg-secondary/20">
            </div>

            <div>
                <label class="block font-bold text-primary mb-1">Deskripsi / Uraian Foto (Opsional)</label>
                <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="w-full py-2.5 px-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="tampilkan_di_galeri" id="edit_tampilkan" value="1" class="rounded border-outline-variant text-secondary focus:ring-secondary">
                <label for="edit_tampilkan" class="font-semibold text-primary">Tampilkan di Galeri Foto Publik</label>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-outline-variant/20">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-xs font-semibold text-outline hover:bg-surface-container rounded-xl transition">Batal</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-primary hover:bg-primary-container rounded-xl shadow-sm transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openUploadModal() {
    document.getElementById('uploadModal').classList.remove('hidden');
}
function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
}
function openEditModal(foto) {
    const form = document.getElementById('editForm');
    form.action = `/admin/dokumentasi/${foto.id}`;
    document.getElementById('edit_judul').value = foto.judul || '';
    document.getElementById('edit_kategori').value = foto.kategori_kegiatan || '';
    document.getElementById('edit_tanggal').value = foto.tanggal_kegiatan ? foto.tanggal_kegiatan.split('T')[0] : '';
    document.getElementById('edit_deskripsi').value = foto.deskripsi || '';
    document.getElementById('edit_tampilkan').checked = foto.tampilkan_di_galeri ? true : false;

    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection
