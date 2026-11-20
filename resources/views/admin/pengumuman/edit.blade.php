@extends('layouts.admin')

@section('title', 'Edit Pengumuman Gereja')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-serif-display text-2xl font-bold text-primary">Edit Pengumuman Gereja</h1>
            <p class="text-xs sm:text-sm text-outline mt-0.5">Perbarui rincian, poster flyer, tanggal tayang, atau status pengumuman.</p>
        </div>
        <a href="{{ route('admin.pengumuman.index') }}" class="px-3.5 py-2 text-xs font-bold text-outline bg-surface-container rounded-xl hover:bg-surface-container-high transition flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            <span>Kembali ke Daftar Pengumuman</span>
        </a>
    </div>

    <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 sm:p-8 rounded-2xl border border-outline-variant/30 shadow-sm space-y-5">
        @csrf
        @method('PUT')

        <div class="space-y-1.5">
            <label class="block text-xs font-bold text-primary uppercase tracking-wider">Judul Pengumuman *</label>
            <input type="text" name="judul" value="{{ old('judul', $pengumuman->judul) }}" required class="w-full text-sm font-semibold rounded-xl border-outline-variant focus:border-secondary py-2.5 px-3.5">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-primary uppercase tracking-wider">Kategori Pengumuman *</label>
                <select name="kategori" required class="w-full text-xs font-semibold rounded-xl border-outline-variant focus:border-secondary py-2.5 px-3">
                    <option value="umum" {{ old('kategori', $pengumuman->kategori) == 'umum' ? 'selected' : '' }}>Umum</option>
                    <option value="penting" {{ old('kategori', $pengumuman->kategori) == 'penting' ? 'selected' : '' }}>Penting / Khusus</option>
                    <option value="ibadah" {{ old('kategori', $pengumuman->kategori) == 'ibadah' ? 'selected' : '' }}>Jadwal & Ibadah</option>
                    <option value="kegiatan" {{ old('kategori', $pengumuman->kategori) == 'kegiatan' ? 'selected' : '' }}>Kegiatan & Acara Komisi</option>
                    <option value="pelayanan" {{ old('kategori', $pengumuman->kategori) == 'pelayanan' ? 'selected' : '' }}>Pelayanan & Pengerja</option>
                    <option value="sosial" {{ old('kategori', $pengumuman->kategori) == 'sosial' ? 'selected' : '' }}>Aksi Sosial & PPA</option>
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-primary uppercase tracking-wider">Tanggal Pengumuman *</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', $pengumuman->tanggal->format('Y-m-d')) }}" required class="w-full text-xs font-semibold rounded-xl border-outline-variant focus:border-secondary py-2 px-3">
            </div>
        </div>

        <div class="space-y-1.5">
            <label class="block text-xs font-bold text-primary uppercase tracking-wider">Isi Rincian Pengumuman *</label>
            <textarea name="isi" rows="6" required class="w-full text-xs rounded-xl border-outline-variant focus:border-secondary p-3.5 leading-relaxed">{{ old('isi', $pengumuman->isi) }}</textarea>
        </div>

        <!-- Bagian Foto / Poster Flyer Pengumuman -->
        <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/30 space-y-3">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[20px]">image</span>
                <label class="text-xs font-bold text-primary uppercase tracking-wider">Poster Flyer / Foto Visual Pengumuman</label>
            </div>
            <p class="text-[11px] text-outline">Tambahkan gambar flyer agar pengumuman lebih menarik saat dilihat jemaat di website.</p>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                <div class="sm:col-span-4">
                    <div class="relative aspect-[4/3] rounded-xl overflow-hidden border border-outline-variant/40 bg-surface-container shadow-2xs">
                        <img id="pengumuman_preview" src="{{ $pengumuman->gambar_url }}" alt="Preview Flyer" class="w-full h-full object-cover">
                    </div>
                </div>

                <div class="sm:col-span-8 space-y-2.5">
                    <div>
                        <label class="block text-[11px] font-bold text-primary mb-1">Ganti File Gambar dari Komputer</label>
                        <input type="file" name="gambar_file" accept="image/*" onchange="if(this.files && this.files[0]){ var r = new FileReader(); r.onload = function(e){ document.getElementById('pengumuman_preview').src = e.target.result; }; r.readAsDataURL(this.files[0]); }" class="w-full text-xs text-outline file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-primary mb-1">Atau Gunakan Link / URL Gambar</label>
                        <input type="text" name="gambar" value="{{ old('gambar', $pengumuman->gambar) }}" placeholder="https://..." oninput="if(this.value){ document.getElementById('pengumuman_preview').src = this.value; }" class="w-full text-xs rounded-xl border-outline-variant focus:border-secondary py-1.5 px-3">
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-outline-variant/20">
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-primary uppercase tracking-wider">Tanggal Kadaluarsa / Berakhir (Opsional)</label>
                <input type="date" name="tanggal_kadaluarsa" value="{{ old('tanggal_kadaluarsa', $pengumuman->tanggal_kadaluarsa?->format('Y-m-d')) }}" class="w-full text-xs rounded-xl border-outline-variant focus:border-secondary py-2 px-3">
                <span class="text-[10px] text-outline">Batas waktu berlakunya pengumuman (misal tanggal acara selesai).</span>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-primary uppercase tracking-wider">Status Publikasi *</label>
                <select name="status" required class="w-full text-xs font-semibold rounded-xl border-outline-variant focus:border-secondary py-2 px-3">
                    <option value="aktif" {{ old('status', $pengumuman->status) == 'aktif' ? 'selected' : '' }}>Aktif (Tayang di Web)</option>
                    <option value="arsip" {{ old('status', $pengumuman->status) == 'arsip' ? 'selected' : '' }}>Arsip (Selesai / Sembunyikan)</option>
                </select>
            </div>
        </div>

        <div class="pt-2">
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned', $pengumuman->is_pinned) ? 'checked' : '' }} class="w-4 h-4 rounded text-secondary focus:ring-secondary border-outline-variant">
                <span class="text-xs font-bold text-primary">Sematkan di Atas (Pin to Top)</span>
            </label>
            <p class="text-[10px] text-outline pl-6">Pengumuman yang disematkan akan selalu tampil di baris teratas Beranda.</p>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-outline-variant/20">
            <a href="{{ route('admin.pengumuman.index') }}" class="px-4 py-2.5 rounded-xl border border-outline-variant text-outline hover:text-primary text-xs font-bold transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-secondary text-white font-bold text-xs hover:bg-secondary/90 transition shadow-md flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[17px]">save</span>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>
</div>
@endsection

