@extends('layouts.admin')

@section('page_title', 'Edit Jadwal Ibadah')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.jadwal.index') }}" class="w-9 h-9 rounded-xl bg-white border border-outline-variant/60 flex items-center justify-center text-on-surface hover:bg-surface-container transition">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div>
            <h1 class="font-serif-display text-2xl font-bold text-primary">Edit Jadwal Ibadah</h1>
            <p class="text-xs text-on-surface-variant">Perbarui rincian jam, hari, ruangan, atau keterangan ibadah.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-outline-variant/30">
        <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-primary">Nama Ibadah <span class="text-red-500">*</span></label>
                <input type="text" name="nama_ibadah" value="{{ old('nama_ibadah', $jadwal->nama_ibadah) }}" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm font-semibold">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-primary">Kategori Ibadah <span class="text-red-500">*</span></label>
                    <select name="kategori" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">
                        <option value="Ibadah Utama" {{ old('kategori', $jadwal->kategori) == 'Ibadah Utama' ? 'selected' : '' }}>Ibadah Utama</option>
                        <option value="Ibadah Pemuda & Remaja" {{ old('kategori', $jadwal->kategori) == 'Ibadah Pemuda & Remaja' ? 'selected' : '' }}>Ibadah Pemuda & Remaja</option>
                        <option value="Sekolah Minggu / Anak" {{ old('kategori', $jadwal->kategori) == 'Sekolah Minggu / Anak' ? 'selected' : '' }}>Sekolah Minggu / Anak</option>
                        <option value="Doa Malam & Puasa" {{ old('kategori', $jadwal->kategori) == 'Doa Malam & Puasa' ? 'selected' : '' }}>Doa Malam & Puasa</option>
                        <option value="Komunitas Sel (Komsel)" {{ old('kategori', $jadwal->kategori) == 'Komunitas Sel (Komsel)' ? 'selected' : '' }}>Komunitas Sel (Komsel)</option>
                        <option value="Ibadah Wanita / Pria" {{ old('kategori', $jadwal->kategori) == 'Ibadah Wanita / Pria' ? 'selected' : '' }}>Ibadah Wanita / Pria</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-primary">Tipe Pelaksanaan <span class="text-red-500">*</span></label>
                    <select name="tipe_pelaksanaan" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">
                        <option value="Onsite & Streaming" {{ old('tipe_pelaksanaan', $jadwal->tipe_pelaksanaan) == 'Onsite & Streaming' ? 'selected' : '' }}>Onsite & Streaming (Hybrid)</option>
                        <option value="Onsite" {{ old('tipe_pelaksanaan', $jadwal->tipe_pelaksanaan) == 'Onsite' ? 'selected' : '' }}>Onsite (Gedung Gereja)</option>
                        <option value="Online (Zoom / YouTube)" {{ old('tipe_pelaksanaan', $jadwal->tipe_pelaksanaan) == 'Online (Zoom / YouTube)' ? 'selected' : '' }}>Online Saja</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-primary">Hari Pelaksanaan <span class="text-red-500">*</span></label>
                    <input type="text" name="hari" value="{{ old('hari', $jadwal->hari) }}" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-primary">Waktu / Jam <span class="text-red-500">*</span></label>
                    <input type="text" name="jam" value="{{ old('jam', $jadwal->jam) }}" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-primary">Ruangan / Tempat <span class="text-red-500">*</span></label>
                    <input type="text" name="lokasi_ruangan" value="{{ old('lokasi_ruangan', $jadwal->lokasi_ruangan) }}" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-primary">Urutan Tampil (Sort Order)</label>
                    <input type="number" name="urutan" value="{{ old('urutan', $jadwal->urutan) }}" min="1" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-primary">Keterangan Tambahan (Opsional)</label>
                <textarea name="keterangan" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">{{ old('keterangan', $jadwal->keterangan) }}</textarea>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-primary">Status Tayang</label>
                <div class="flex items-center gap-6 pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="aktif" {{ old('status', $jadwal->status) == 'aktif' ? 'checked' : '' }} class="text-secondary focus:ring-secondary">
                        <span class="text-sm font-semibold text-green-700">Aktif (Tampil di Web)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="nonaktif" {{ old('status', $jadwal->status) == 'nonaktif' ? 'checked' : '' }} class="text-secondary focus:ring-secondary">
                        <span class="text-sm font-semibold text-gray-500">Nonaktif (Sembunyikan)</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-outline-variant/30 flex items-center justify-end gap-3">
                <a href="{{ route('admin.jadwal.index') }}" class="px-5 py-2.5 rounded-xl border border-outline-variant text-on-surface font-semibold text-sm hover:bg-surface-container transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white font-bold text-sm shadow hover:bg-primary-container transition flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">save</span>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
