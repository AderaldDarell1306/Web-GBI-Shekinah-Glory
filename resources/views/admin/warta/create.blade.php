@extends('layouts.admin')

@section('title', 'Buat Edisi Warta Jemaat')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Buat Edisi Warta Jemaat</h1>
            <p class="text-sm text-gray-500">Penyusunan renungan firman, pengumuman ibadah, pokok doa, dan ucapan ulang tahun sepekan.</p>
        </div>
        <a href="{{ route('admin.warta.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition">
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.warta.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf

        <!-- Main Form (2 Cols) -->
        <div class="lg:col-span-2 space-y-5 bg-white p-6 rounded-xl border border-gray-100 shadow-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Hari Minggu Warta *</label>
                    <input type="date" name="tanggal_warta" value="{{ $targetSunday->format('Y-m-d') }}" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Judul Edisi Warta *</label>
                    <input type="text" name="edisi" value="{{ $defaultEdisi }}" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tema Mingguan</label>
                    <input type="text" name="tema_mingguan" placeholder="Contoh: Hidup yang Berbuah Lebat" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Ayat Alkitab Tema</label>
                    <input type="text" name="ayat_tema" placeholder="Contoh: Yohanes 15:5" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
            </div>

            <!-- Upload / Ganti Gambar Sampul Konten Warta -->
            <div class="p-4 rounded-xl bg-gray-50/70 border border-gray-200 space-y-3">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">image</span>
                    <label class="text-xs font-bold text-gray-800 uppercase tracking-wide">Gambar Sampul / Visual Konten Warta</label>
                </div>
                <p class="text-[11px] text-gray-500">Gambar ini tampil di kartu Beranda Web & halaman Warta Jemaat.</p>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                    <div class="sm:col-span-4">
                        <div class="relative aspect-video rounded-lg overflow-hidden border border-gray-200 bg-gray-100 shadow-2xs">
                            <img id="warta_cover_preview" src="https://images.unsplash.com/photo-1507692049790-de58290a4334?auto=format&fit=crop&w=800&q=80" alt="Cover Warta" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="sm:col-span-8 space-y-2">
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 mb-0.5">Upload Gambar Baru dari Komputer</label>
                            <input type="file" name="gambar_sampul_file" accept="image/*" onchange="if(this.files && this.files[0]){ var r = new FileReader(); r.onload = function(e){ document.getElementById('warta_cover_preview').src = e.target.result; }; r.readAsDataURL(this.files[0]); }" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 mb-0.5">Atau URL Gambar Eksternal</label>
                            <input type="text" name="gambar_sampul" placeholder="https://images.unsplash.com/..." oninput="if(this.value){ document.getElementById('warta_cover_preview').src = this.value; }" class="w-full text-xs rounded-lg border-gray-300 focus:border-primary focus:ring-primary py-1.5">
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Ringkasan Khotbah / Renungan Firman</label>
                <textarea name="ringkasan_khotbah" rows="4" placeholder="Tuliskan intisari pesan firman Tuhan hari Minggu ini..." class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Pengumuman & Agenda Ibadah Sepekan</label>
                <textarea name="pengumuman_kegiatan" rows="3" placeholder="Jadwal ibadah doa malam, kegiatan youth, latihan musik, persekutuan doa wilayah..." class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Pokok Doa Syafaat</label>
                <textarea name="pokok_doa" rows="3" placeholder="1. Jemaat yang sakit... 2. Pemulihan ekonomi... 3. Kota Kupang dan Bangsa Indonesia..." class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Ringkasan Laporan Persembahan / Kas (Opsional)</label>
                <textarea name="laporan_keuangan_ringkas" rows="2" placeholder="Contoh: Saldo Awal Rp..., Kolekte Minggu Lalu Rp..., Pengeluaran Rp..." class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary"></textarea>
            </div>

            <!-- SEKSI BULETIN GEREJA (PRD v2.1 2.5: WARTA + BULETIN) -->
            <div class="p-5 rounded-xl bg-amber-50/60 border border-amber-200/80 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[22px]">auto_stories</span>
                        <div>
                            <h3 class="font-serif-display text-sm font-bold text-gray-900">Buletin Gereja Terintegrasi</h3>
                            <p class="text-[11px] text-gray-500">Pilih metode buletin: Upload File, Tautan Link Eksternal, atau Buat Teks Manual</p>
                        </div>
                    </div>
                    <span class="text-2xs font-bold px-2 py-0.5 rounded-full bg-secondary-container text-secondary">PRD v2.1</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Metode Buletin</label>
                    <div class="grid grid-cols-4 gap-2">
                        <label class="flex items-center justify-center gap-1.5 py-2 px-2 rounded-lg border border-gray-200 cursor-pointer text-xs font-semibold bg-white hover:border-secondary transition">
                            <input type="radio" name="buletin_tipe" value="none" checked onchange="toggleBuletinInputs(this.value)">
                            <span>Tidak Ada</span>
                        </label>
                        <label class="flex items-center justify-center gap-1.5 py-2 px-2 rounded-lg border border-gray-200 cursor-pointer text-xs font-semibold bg-white hover:border-secondary transition">
                            <input type="radio" name="buletin_tipe" value="upload" onchange="toggleBuletinInputs(this.value)">
                            <span>Upload PDF</span>
                        </label>
                        <label class="flex items-center justify-center gap-1.5 py-2 px-2 rounded-lg border border-gray-200 cursor-pointer text-xs font-semibold bg-white hover:border-secondary transition">
                            <input type="radio" name="buletin_tipe" value="link" onchange="toggleBuletinInputs(this.value)">
                            <span>Tautan Link</span>
                        </label>
                        <label class="flex items-center justify-center gap-1.5 py-2 px-2 rounded-lg border border-gray-200 cursor-pointer text-xs font-semibold bg-white hover:border-secondary transition">
                            <input type="radio" name="buletin_tipe" value="manual" onchange="toggleBuletinInputs(this.value)">
                            <span>Teks Manual</span>
                        </label>
                    </div>
                </div>

                <div id="buletin_judul_wrap" class="hidden">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Judul Buletin</label>
                    <input type="text" name="buletin_judul" placeholder="Contoh: Buletin Shekinah Glory Edisi Khusus Pemulihan" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>

                <div id="buletin_upload_wrap" class="hidden">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Upload File Buletin (PDF / Publisher .PUB / Word, Maks 30 MB)</label>
                    <input type="file" name="buletin_file" accept=".pdf,.pub,.docx,.doc,.jpg,.jpeg,.png,.zip" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-secondary file:text-white hover:file:bg-secondary/90">
                    <p class="text-[11px] text-gray-400 mt-1">Mendukung file Microsoft Publisher (.pub), PDF, Word (.docx), atau Gambar.</p>
                </div>

                <div id="buletin_link_wrap" class="hidden">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tautan / Link Buletin Eksternal</label>
                    <input type="url" name="buletin_link" placeholder="https://drive.google.com/... atau https://fliphtml5.com/..." class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>

                <div id="buletin_manual_wrap" class="hidden">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Isi Konten Buletin (Teks / Artikel Rohani)</label>
                    <textarea name="buletin_content" rows="6" placeholder="Ketikkan artikel atau renungan buletin rohani secara manual di sini..." class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status Publikasi *</label>
                    <select name="status" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="terbit">Terbitkan Langsung</option>
                        <option value="draft">Simpan Sebagai Draft</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Upload File Warta Cetak (PDF)</label>
                    <input type="file" name="file_pdf" accept=".pdf" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3">
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark transition shadow-sm">
                    Simpan & Buat Broadcast Warta
                </button>
            </div>
        </div>

        <!-- Sidebar / Auto Birthday Preview -->
        <div class="space-y-4">
            <div class="bg-amber-50/70 rounded-xl p-5 border border-amber-200/80 shadow-xs space-y-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-amber-600 text-white flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-amber-950">Jemaat Ultah Pekan Ini</h3>
                        <p class="text-xs text-amber-800">Auto-deteksi dari database jemaat</p>
                    </div>
                </div>

                <div class="text-xs text-amber-900 leading-relaxed bg-white/70 p-3 rounded-lg border border-amber-200/50">
                    Sistem mendeteksi <strong>{{ $ultahList->count() }} orang jemaat</strong> yang berulang tahun antara hari Senin s.d. Minggu pada pekan warta ini. Daftar ini akan otomatis dimasukkan ke teks broadcast WhatsApp!
                </div>

                <div class="space-y-2 max-h-96 overflow-y-auto pr-1">
                    @forelse($ultahList as $u)
                        <div class="bg-white p-3 rounded-lg border border-amber-100 shadow-2xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-900">{{ $u->nama_lengkap }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-semibold">HUT ke-{{ $u->umur_ke }}</span>
                            </div>
                            <div class="text-xs text-gray-500">{{ $u->hari_ultah }} &bull; Wilayah: {{ $u->nama_wilayah ?? ($u->wilayah?->nama_wilayah ?? 'Namosain') }}</div>
                        </div>
                    @empty
                        <div class="bg-white/60 p-4 rounded-lg text-center text-xs text-gray-400">
                            Tidak ada jemaat yang berulang tahun pada pekan ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-blue-50/60 rounded-xl p-4 border border-blue-200/60 text-xs text-blue-900 space-y-1.5">
                <span class="font-bold flex items-center gap-1.5 text-blue-950">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Fitur Otomatis Warta
                </span>
                <p>Setelah disimpan, Anda dapat langsung mengklik tombol <strong>"Salin Broadcast WhatsApp"</strong> untuk mengirim warta sepekan langsung ke Grup WhatsApp Jemaat GBI Shekinah Glory.</p>
            </div>
        </div>
    </form>
</div>

<script>
function toggleBuletinInputs(val) {
    const judulWrap = document.getElementById('buletin_judul_wrap');
    const uploadWrap = document.getElementById('buletin_upload_wrap');
    const linkWrap = document.getElementById('buletin_link_wrap');
    const manualWrap = document.getElementById('buletin_manual_wrap');

    judulWrap.classList.add('hidden');
    uploadWrap.classList.add('hidden');
    linkWrap.classList.add('hidden');
    manualWrap.classList.add('hidden');

    if (val === 'upload') {
        judulWrap.classList.remove('hidden');
        uploadWrap.classList.remove('hidden');
    } else if (val === 'link') {
        judulWrap.classList.remove('hidden');
        linkWrap.classList.remove('hidden');
    } else if (val === 'manual') {
        judulWrap.classList.remove('hidden');
        manualWrap.classList.remove('hidden');
    }
}
</script>
@endsection
