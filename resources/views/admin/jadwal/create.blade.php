@extends('layouts.admin')

@section('page_title', 'Tambah Jadwal Ibadah Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.jadwal.index') }}" class="w-9 h-9 rounded-xl bg-white border border-outline-variant/60 flex items-center justify-center text-on-surface hover:bg-surface-container transition">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div>
            <h1 class="font-serif-display text-2xl font-bold text-primary">Tambah Jadwal Ibadah</h1>
            <p class="text-xs text-on-surface-variant">Tambahkan kegiatan ibadah rutin atau persekutuan baru ke website.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-outline-variant/30">
        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-primary">Nama Ibadah <span class="text-red-500">*</span></label>
                <input type="text" name="nama_ibadah" value="{{ old('nama_ibadah') }}" placeholder="Contoh: Ibadah Raya 1 (Umum)" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm font-semibold">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-primary">Kategori Ibadah <span class="text-red-500">*</span></label>
                    <select name="kategori" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">
                        <option value="Ibadah Utama" {{ old('kategori') == 'Ibadah Utama' ? 'selected' : '' }}>Ibadah Utama</option>
                        <option value="Ibadah Pemuda & Remaja" {{ old('kategori') == 'Ibadah Pemuda & Remaja' ? 'selected' : '' }}>Ibadah Pemuda & Remaja</option>
                        <option value="Sekolah Minggu / Anak" {{ old('kategori') == 'Sekolah Minggu / Anak' ? 'selected' : '' }}>Sekolah Minggu / Anak</option>
                        <option value="Doa Malam & Puasa" {{ old('kategori') == 'Doa Malam & Puasa' ? 'selected' : '' }}>Doa Malam & Puasa</option>
                        <option value="Komunitas Sel (Komsel)" {{ old('kategori') == 'Komunitas Sel (Komsel)' ? 'selected' : '' }}>Komunitas Sel (Komsel)</option>
                        <option value="Ibadah Wanita / Pria" {{ old('kategori') == 'Ibadah Wanita / Pria' ? 'selected' : '' }}>Ibadah Wanita / Pria</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-primary">Tipe Pelaksanaan <span class="text-red-500">*</span></label>
                    <select name="tipe_pelaksanaan" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">
                        <option value="Onsite & Streaming" {{ old('tipe_pelaksanaan') == 'Onsite & Streaming' ? 'selected' : '' }}>Onsite & Streaming (Hybrid)</option>
                        <option value="Onsite" {{ old('tipe_pelaksanaan') == 'Onsite' ? 'selected' : '' }}>Onsite (Gedung Gereja)</option>
                        <option value="Online (Zoom / YouTube)" {{ old('tipe_pelaksanaan') == 'Online (Zoom / YouTube)' ? 'selected' : '' }}>Online Saja</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-primary">Hari Pelaksanaan <span class="text-red-500">*</span></label>
                    <input type="text" name="hari" value="{{ old('hari', 'Minggu') }}" placeholder="Contoh: Setiap Minggu / Setiap Jumat" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-primary">Waktu / Jam <span class="text-red-500">*</span></label>
                    <input type="text" name="jam" value="{{ old('jam') }}" placeholder="Contoh: 08:00 – 10:00 WIB" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-primary">Ruangan / Tempat <span class="text-red-500">*</span></label>
                    <input type="text" name="lokasi_ruangan" value="{{ old('lokasi_ruangan') }}" placeholder="Contoh: Aula Utama Gereja" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-primary">Urutan Tampil (Sort Order)</label>
                    <input type="number" name="urutan" value="{{ old('urutan', 1) }}" min="1" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-primary">Keterangan Tambahan (Opsional)</label>
                <textarea name="keterangan" rows="3" placeholder="Contoh: Dilengkapi pelayanan perjamuan kudus di minggu pertama setiap bulan." class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">{{ old('keterangan') }}</textarea>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-primary">Status Tayang</label>
                <div class="flex items-center gap-6 pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="aktif" checked class="text-secondary focus:ring-secondary">
                        <span class="text-sm font-semibold text-green-700">Aktif (Tampil di Web)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="nonaktif" class="text-secondary focus:ring-secondary">
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
                    <span>Simpan Jadwal</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
