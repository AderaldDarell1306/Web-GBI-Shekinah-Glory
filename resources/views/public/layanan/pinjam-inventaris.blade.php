@extends('layouts.public')

@section('title', 'Form Pengajuan Peminjaman Inventaris - GBI Shekinah Glory')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header Page -->
    <div class="text-center max-w-2xl mx-auto mb-10" data-aos="fade-up">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mx-auto mb-3 border border-emerald-200 shadow-2xs">
            <span class="material-symbols-outlined text-[26px]">inventory_2</span>
        </div>
        <span class="text-xs font-bold uppercase tracking-widest text-secondary mb-1 block">Layanan Mandiri Jemaat</span>
        <h1 class="font-serif-display text-3xl sm:text-4xl font-bold text-primary mb-3">Pengajuan Pinjam Pakai Inventaris</h1>
        <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
            Formulir permohonan peminjaman sarana prasarana dan inventaris milik GBI Shekinah Glory untuk keperluan kegiatan rohani, rayon/komsel, maupun acara keluarga jemaat.
        </p>
        <div class="w-16 h-1 bg-secondary mx-auto mt-4 rounded-full"></div>
    </div>

    <!-- Container Form -->
    <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-outline-variant/30" data-aos="fade-up" data-aos-delay="100">
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs space-y-1">
                <span class="font-bold block">Mohon periksa kembali isian formulir:</span>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('public.layanan.pinjam-inventaris.submit') }}" method="POST" class="space-y-6">
            @csrf

            <!-- 1. Identitas Peminjam -->
            <div>
                <h3 class="text-xs font-bold text-secondary uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-secondary-container text-secondary flex items-center justify-center text-[11px] font-extrabold">1</span>
                    <span>Identitas Pemohon / Peminjam</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Lengkap Pemohon <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_peminjam" value="{{ old('nama_peminjam') }}" required placeholder="Contoh: Bpk. Markus Bire" class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">Nomor WhatsApp Aktif <span class="text-red-500">*</span></label>
                        <input type="text" name="no_kontak" value="{{ old('no_kontak') }}" required placeholder="Contoh: 081234567890" class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                    </div>
                </div>
            </div>

            <!-- 2. Detail Keperluan & Kegiatan -->
            <div class="pt-2 border-t border-outline-variant/20">
                <h3 class="text-xs font-bold text-secondary uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-secondary-container text-secondary flex items-center justify-center text-[11px] font-extrabold">2</span>
                    <span>Tujuan &amp; Keperluan Peminjaman</span>
                </h3>
                <div>
                    <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Acara / Instansi / Keperluan <span class="text-red-500">*</span></label>
                    <input type="text" name="instansi_kegiatan" value="{{ old('instansi_kegiatan') }}" required placeholder="Contoh: Pertemuan COOL Rayon 2 / Ibadah Ucapan Syukur Keluarga" class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                </div>
            </div>

            <!-- 3. Barang Inventaris & Waktu Pinjam -->
            <div class="pt-2 border-t border-outline-variant/20">
                <h3 class="text-xs font-bold text-secondary uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-secondary-container text-secondary flex items-center justify-center text-[11px] font-extrabold">3</span>
                    <span>Barang yang Dipinjam &amp; Jadwal Pemakaian</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                    <div class="sm:col-span-2">
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">Pilih Barang dari Katalog Gereja</label>
                        <select name="inventaris_id" class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition bg-white">
                            <option value="">-- Pilih Barang Tersedia --</option>
                            @foreach($barangList as $b)
                                <option value="{{ $b->id }}" {{ old('inventaris_id') == $b->id ? 'selected' : '' }}>
                                    [{{ $b->kode_inventaris }}] {{ $b->nama_barang }} (Stok: {{ $b->jumlah }} {{ $b->satuan }} - Lokasi: {{ $b->lokasi }})
                                </option>
                            @endforeach
                        </select>
                        <span class="text-[10px] text-outline block mt-1">Atau sebutkan nama barang khusus pada kolom di bawah jika tidak ada di pilihan:</span>
                    </div>

                    <div>
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">Jumlah Unit <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_pinjam" value="{{ old('jumlah_pinjam', 1) }}" min="1" required class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Barang Lainnya (Opsional / Jika tidak ada di pilihan)</label>
                    <input type="text" name="nama_barang_custom" value="{{ old('nama_barang_custom') }}" placeholder="Contoh: Kabel Roll 50m / Kursi Lipat Tambahan" class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">Tanggal Mulai Pinjam <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">Tanggal Rencana Kembali <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_rencana_kembali" value="{{ old('tanggal_rencana_kembali', date('Y-m-d', strtotime('+1 day'))) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-2xs font-bold uppercase text-primary mb-1">Catatan Tambahan / Perlengkapan Khusus</label>
                    <textarea name="catatan" rows="3" placeholder="Tuliskan jika ada kebutuhan khusus atau tempat pengambilan..." class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">{{ old('catatan') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-outline-variant/20 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-[11px] text-outline">
                    *Dengan mengirim formulir ini, peminjam bertanggung jawab menjaga keutuhan dan kebersihan barang gereja.
                </p>
                <button type="submit" class="w-full sm:w-auto px-8 py-3 rounded-xl bg-secondary text-white font-bold text-xs hover:bg-secondary/90 transition shadow-md flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">send</span>
                    <span>Kirim Pengajuan Peminjaman</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
