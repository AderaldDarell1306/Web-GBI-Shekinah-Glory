@extends('layouts.admin')

@section('title', 'Tambah Data Jemaat - SIMJ GBI Shekinah Glory')
@section('page_title', 'Form Registrasi Data Jemaat')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Breadcrumb & Mode Badge -->
    <div class="flex items-center justify-between">
        <nav class="flex items-center gap-2 text-xs text-outline">
            <a href="{{ route('admin.jemaat.index') }}" class="hover:text-primary transition flex items-center gap-1 font-semibold">
                <span class="material-symbols-outlined text-[16px]">groups</span>
                <span>Data Jemaat</span>
            </a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="font-bold text-primary">Input Jemaat Baru</span>
        </nav>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/50 text-secondary font-bold text-xs">
            <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
            Format Resmi Database Gereja
        </span>
    </div>

    <!-- Main Form Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <!-- Form Header -->
        <div class="p-6 sm:p-8 bg-surface-container-low/60 border-b border-outline-variant/30 flex items-center justify-between">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-primary-container text-secondary-fixed flex items-center justify-center shadow">
                        <span class="material-symbols-outlined text-[20px]">person_add</span>
                    </div>
                    <h2 class="font-serif-display text-2xl font-bold text-primary">Formulir Input Data Jemaat</h2>
                </div>
                <p class="text-xs text-on-surface-variant max-w-2xl">
                    Formulir ini mengikuti standar format database gereja (KK, Nama, TTL, JK, Status Keluarga, Baptisan, Nikah Masehi, Pendidikan, Pekerjaan, Keahlian, Penghasilan, dan Keterangan).
                </p>
            </div>
            <a href="{{ route('admin.jemaat.index') }}" class="w-9 h-9 rounded-full bg-surface-container hover:bg-surface-container-high text-outline flex items-center justify-center transition">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </a>
        </div>

        <form action="{{ route('admin.jemaat.store') }}" method="POST" id="memberForm" class="p-6 sm:p-8 space-y-8">
            @csrf
            <input type="hidden" name="ignore_duplicate" id="ignore_duplicate_input" value="0">

            <!-- BAGIAN A: IDENTITAS & KELUARGA (KK, NAMA, TTL, JK, STATUS) -->
            <section class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-outline-variant/30">
                    <span class="material-symbols-outlined text-secondary text-[22px]">badge</span>
                    <div>
                        <h3 class="font-serif-display text-lg font-bold text-primary">Identitas Pokok & Keluarga</h3>
                        <p class="text-[11px] text-outline">Nomor KK, Nama Lengkap, Tempat & Tanggal Lahir, Jenis Kelamin, serta Hubungan Keluarga</p>
                    </div>
                </div>

                <!-- PILIH KEPALA KELUARGA (TAMBAH ANGGOTA KE KK YANG ADA - PRD v2.1) -->
                <div class="p-4 rounded-2xl bg-secondary/5 border border-secondary/20 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-secondary">family_restroom</span>
                            <span>Tambah Anggota ke KK yang Sudah Ada (Pilih / Cari Kepala Keluarga)</span>
                        </label>
                        <span class="text-2xs bg-secondary-container text-secondary font-bold px-2 py-0.5 rounded-full">Fitur Tambah Anggota KK</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-8">
                            <input type="text" id="cari_kk_input" list="kkDatalist" placeholder="Ketik nama Kepala Keluarga atau No. KK untuk mencari..." class="w-full px-3.5 py-2.5 rounded-xl border border-secondary/40 text-xs focus:border-secondary focus:ring-1 focus:ring-secondary focus:outline-none bg-white">
                            <datalist id="kkDatalist">
                                @if(isset($kepalaKeluargaList))
                                    @foreach($kepalaKeluargaList as $kk)
                                        <option value="{{ $kk->nama_lengkap }} (KK: {{ $kk->no_kk }})" data-kk="{{ $kk->no_kk }}" data-wilayah="{{ $kk->wilayah_id }}" data-alamat="{{ $kk->alamat }}" data-nama="{{ $kk->nama_lengkap }}">
                                    @endforeach
                                @endif
                            </datalist>
                        </div>
                        <div class="md:col-span-4 flex items-center gap-2">
                            <button type="button" id="btn_apply_kk" class="w-full py-2.5 px-4 bg-secondary text-white font-bold text-xs rounded-xl hover:bg-secondary/90 transition shadow-sm flex items-center justify-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                <span>Terapkan ke KK Ini</span>
                            </button>
                            <button type="button" id="btn_reset_kk" class="py-2.5 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition" title="Batal / Buat KK Baru">
                                Reset
                            </button>
                        </div>
                    </div>
                    <div id="kk_selected_info" class="hidden text-xs text-secondary-dark bg-secondary/10 p-2.5 rounded-lg border border-secondary/20 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-secondary">check</span>
                            <span>Kepala Keluarga: <strong id="info_nama_kk"></strong> • Kode KK: <code id="info_kode_kk" class="font-bold"></code> • Urutan Anggota Berikutnya: <code id="info_next_seq" class="font-bold text-primary"></code></span>
                        </div>
                        <span class="text-2xs text-outline">* Nomor KK & Wilayah otomatis terisi di formulir</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <!-- KK (Kode / Nomor KK) -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="no_kk" class="block text-xs font-bold text-primary">No. KK / ID Jemaat (6 Digit)</label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3 text-outline text-[18px]">family_restroom</span>
                            <input type="text" name="no_kk" id="no_kk" value="{{ old('no_kk') }}" placeholder="Contoh: 310101" maxlength="20" class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition font-mono font-bold tracking-wider">
                        </div>
                        <span class="text-[10px] text-outline block leading-tight">
                            Format 6 Digit: <strong>[2 digit Wilayah][2 digit No. KK][2 digit Anggota]</strong><br>
                            Contoh: <code>310101</code> = Osmok Sub 1, KK 01, Urutan 01.
                        </span>
                    </div>

                    <!-- NIK (Nomor Induk Kependudukan) -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="nik" class="block text-xs font-bold text-primary">NIK (16 Digit KTP)</label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3 text-outline text-[18px]">badge</span>
                            <input type="text" name="nik" id="nik" value="{{ old('nik') }}" placeholder="5371xxxxxxxxxxxx" maxlength="20" class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition font-mono">
                        </div>
                    </div>

                    <!-- Tipe Keanggotaan -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="tipe_keanggotaan" class="block text-xs font-bold text-primary">Tipe Keanggotaan</label>
                        <select name="tipe_keanggotaan" id="tipe_keanggotaan" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                            <option value="jemaat_tetap" {{ old('tipe_keanggotaan') === 'jemaat_tetap' ? 'selected' : '' }}>Jemaat Tetap</option>
                            <option value="simpatisan" {{ old('tipe_keanggotaan') === 'simpatisan' ? 'selected' : '' }}>Simpatisan</option>
                            <option value="mutasi" {{ old('tipe_keanggotaan') === 'mutasi' ? 'selected' : '' }}>Mutasi / Pindahan</option>
                        </select>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="md:col-span-8 space-y-1">
                        <label for="nama_lengkap" class="block text-xs font-bold text-primary">Nama Lengkap <span class="text-red-500">*</span></label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3 text-outline text-[18px]">person</span>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" required value="{{ old('nama_lengkap') }}" placeholder="Contoh: DANIEL TALAEN" class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition font-semibold">
                        </div>
                    </div>

                    <!-- Kategori Khusus Diakonia -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="kategori_khusus" class="block text-xs font-bold text-primary">Kategori Khusus / Diakonia</label>
                        <select name="kategori_khusus" id="kategori_khusus" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                            <option value="umum" {{ old('kategori_khusus') === 'umum' ? 'selected' : '' }}>Umum / Tidak Ada</option>
                            <option value="janda" {{ old('kategori_khusus') === 'janda' ? 'selected' : '' }}>Janda</option>
                            <option value="duda" {{ old('kategori_khusus') === 'duda' ? 'selected' : '' }}>Duda</option>
                            <option value="yatim" {{ old('kategori_khusus') === 'yatim' ? 'selected' : '' }}>Yatim</option>
                            <option value="piatu" {{ old('kategori_khusus') === 'piatu' ? 'selected' : '' }}>Piatu</option>
                            <option value="yatim_piatu" {{ old('kategori_khusus') === 'yatim_piatu' ? 'selected' : '' }}>Yatim Piatu</option>
                            <option value="lansia" {{ old('kategori_khusus') === 'lansia' ? 'selected' : '' }}>Lansia</option>
                        </select>
                    </div>

                    <!-- Tempat Lahir -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="tempat_lahir" class="block text-xs font-bold text-primary">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Contoh: KUPANG" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="tanggal_lahir" class="block text-xs font-bold text-primary">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" max="{{ date('Y-m-d') }}" value="{{ old('tanggal_lahir') }}" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                    </div>

                    <!-- Jenis Kelamin (JK) -->
                    <div class="md:col-span-4 space-y-1">
                        <label class="block text-xs font-bold text-primary">Jenis Kelamin (JK) <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-2 bg-surface-container-low p-1 rounded-xl border border-outline-variant/30">
                            <label class="flex items-center justify-center gap-1.5 py-2 px-3 rounded-lg cursor-pointer text-xs font-semibold text-center transition {{ old('jenis_kelamin', 'L') === 'L' ? 'bg-primary text-white shadow-sm' : 'text-on-surface hover:bg-white' }}">
                                <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin', 'L') === 'L' ? 'checked' : '' }} class="sr-only" onchange="this.parentElement.parentElement.querySelectorAll('label').forEach(l => l.classList.remove('bg-primary', 'text-white', 'shadow-sm')); this.parentElement.classList.add('bg-primary', 'text-white', 'shadow-sm');">
                                <span class="material-symbols-outlined text-[16px]">male</span>
                                <span>L (Laki-laki)</span>
                            </label>
                            <label class="flex items-center justify-center gap-1.5 py-2 px-3 rounded-lg cursor-pointer text-xs font-semibold text-center transition {{ old('jenis_kelamin') === 'P' ? 'bg-primary text-white shadow-sm' : 'text-on-surface hover:bg-white' }}">
                                <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') === 'P' ? 'checked' : '' }} class="sr-only" onchange="this.parentElement.parentElement.querySelectorAll('label').forEach(l => l.classList.remove('bg-primary', 'text-white', 'shadow-sm')); this.parentElement.classList.add('bg-primary', 'text-white', 'shadow-sm');">
                                <span class="material-symbols-outlined text-[16px]">female</span>
                                <span>P (Perempuan)</span>
                            </label>
                        </div>
                    </div>

                    <!-- STATUS (Status Hubungan Keluarga) -->
                    <div class="md:col-span-6 space-y-1">
                        <label for="status_keluarga" class="block text-xs font-bold text-primary">Status Dalam Keluarga (STATUS)</label>
                        <input type="text" name="status_keluarga" id="status_keluarga" list="statusKeluargaList" value="{{ old('status_keluarga') }}" placeholder="Contoh: SUAMI (KEPALA KELUARGA), ISTRI, ANAK, JANDA, DUDA" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                        <datalist id="statusKeluargaList">
                            <option value="SUAMI (KEPALA KELUARGA)">
                            <option value="ISTRI">
                            <option value="ANAK">
                            <option value="JANDA">
                            <option value="DUDA">
                            <option value="ORANG TUA">
                            <option value="FAMILI LAIN">
                        </datalist>
                    </div>

                    <!-- NIKAH MASEHI -->
                    <div class="md:col-span-6 space-y-1">
                        <label for="nikah_masehi" class="block text-xs font-bold text-primary">Nikah Masehi</label>
                        <select name="nikah_masehi" id="nikah_masehi" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition bg-white">
                            <option value="">-- Pilih Status Nikah Masehi --</option>
                            <option value="SUDAH" {{ old('nikah_masehi') === 'SUDAH' ? 'selected' : '' }}>SUDAH</option>
                            <option value="BELUM" {{ old('nikah_masehi') === 'BELUM' ? 'selected' : '' }}>BELUM</option>
                        </select>
                    </div>
                </div>
            </section>

            <!-- BAGIAN B: SAKRAMEN & STATUS GEREJAWI -->
            <section class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-outline-variant/30">
                    <span class="material-symbols-outlined text-secondary text-[22px]">church</span>
                    <div>
                        <h3 class="font-serif-display text-lg font-bold text-primary">Status Sakramen & Pelayanan</h3>
                        <p class="text-[11px] text-outline">Status penyerahan anak, baptisan selam, dan penempatan wilayah</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <!-- PENYERAHAN / BAPTISAN -->
                    <div class="md:col-span-6 space-y-1">
                        <label for="status_baptis" class="block text-xs font-bold text-primary">Penyerahan / Baptisan</label>
                        <select name="status_baptis" id="status_baptis" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition bg-white">
                            <option value="">-- Pilih Status --</option>
                            <option value="BAPTIS" {{ old('status_baptis') === 'BAPTIS' ? 'selected' : '' }}>BAPTIS (Baptis Selam)</option>
                            <option value="PENYERAHAN" {{ old('status_baptis') === 'PENYERAHAN' ? 'selected' : '' }}>PENYERAHAN (Penyerahan Anak)</option>
                            <option value="BELUM" {{ old('status_baptis') === 'BELUM' ? 'selected' : '' }}>BELUM</option>
                        </select>
                    </div>

                    <!-- Wilayah / Sektor -->
                    <div class="md:col-span-6 space-y-1">
                        <label for="wilayah_id" class="block text-xs font-bold text-primary">Wilayah / Sektor Pelayanan</label>
                        <select name="wilayah_id" id="wilayah_id" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition bg-white">
                            <option value="">-- Otomatis Dari 2 Digit Awal KK atau Pilih Manual --</option>
                            @php
                                $grouped = $wilayahList->groupBy('wilayah_besar');
                            @endphp
                            @foreach($grouped as $wb => $list)
                            <optgroup label="Wilayah Besar: {{ $wb }}">
                                @foreach($list as $w)
                                <option value="{{ $w->id }}" data-prefix="{{ $w->kode_prefix }}" {{ old('wilayah_id') == $w->id ? 'selected' : '' }}>
                                    [{{ $w->kode_prefix }}] {{ $w->nama_wilayah }} ({{ $w->kode_wilayah }})
                                </option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                        <span class="text-[10px] text-outline">Otomatis terpilih saat Anda mengetikkan 2 digit pertama nomor KK.</span>
                    </div>
                </div>
            </section>

            <!-- BAGIAN C: SOSIAL, EKONOMI & KEAHLIAN KHUSUS -->
            <section class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-outline-variant/30">
                    <span class="material-symbols-outlined text-secondary text-[22px]">work</span>
                    <div>
                        <h3 class="font-serif-display text-lg font-bold text-primary">Sosial, Profesi & Keahlian Khusus</h3>
                        <p class="text-[11px] text-outline">Pendidikan terakhir, pekerjaan, talenta/keahlian untuk pelayanan, dan penghasilan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <!-- PENDIDIKAN -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="pendidikan" class="block text-xs font-bold text-primary">Pendidikan Terakhir</label>
                        <input type="text" name="pendidikan" id="pendidikan" list="pendidikanList" value="{{ old('pendidikan') }}" placeholder="Contoh: SD, SMP, SMA, D3, SARJANA" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                        <datalist id="pendidikanList">
                            <option value="SD">
                            <option value="SMP">
                            <option value="SLTP">
                            <option value="SMA">
                            <option value="SLTA">
                            <option value="SMK">
                            <option value="D3">
                            <option value="SARJANA">
                            <option value="PASCA SARJANA">
                        </datalist>
                    </div>

                    <!-- PEKERJAAN -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="pekerjaan" class="block text-xs font-bold text-primary">Pekerjaan</label>
                        <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan') }}" placeholder="Contoh: BURUH, IRT, SWASTA, WIRAUSAHA" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                    </div>

                    <!-- KEAHLIAN KHUSUS -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="keahlian_khusus" class="block text-xs font-bold text-primary">Keahlian Khusus / Talenta</label>
                        <input type="text" name="keahlian_khusus" id="keahlian_khusus" value="{{ old('keahlian_khusus') }}" placeholder="Contoh: MENYANYI, MASAK, PEMAIN MUSIK" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                        <span class="text-[10px] text-outline">Sangat berguna untuk pelibatan dalam bidang pelayanan gereja.</span>
                    </div>

                    <!-- PENGHASILAN PER BULAN -->
                    <div class="md:col-span-6 space-y-1">
                        <label for="penghasilan_per_bulan" class="block text-xs font-bold text-primary">Penghasilan Per Bulan</label>
                        <input type="text" name="penghasilan_per_bulan" id="penghasilan_per_bulan" value="{{ old('penghasilan_per_bulan') }}" placeholder="Contoh: 1000000 atau 2400000" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition font-mono">
                    </div>

                    <!-- LAIN-LAIN -->
                    <div class="md:col-span-6 space-y-1">
                        <label for="lain_lain" class="block text-xs font-bold text-primary">Lain-lain</label>
                        <input type="text" name="lain_lain" id="lain_lain" value="{{ old('lain_lain') }}" placeholder="Catatan tambahan lain-lain..." class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                    </div>

                    <!-- KETERANGAN -->
                    <div class="md:col-span-12 space-y-1">
                        <label for="keterangan" class="block text-xs font-bold text-primary">Keterangan</label>
                        <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan', 'BERJEMAAT') }}" placeholder="Contoh: BERJEMAAT" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                    </div>
                </div>
            </section>

            <!-- FORM ACTION BUTTONS -->
            <div class="pt-6 border-t border-outline-variant/30 flex items-center justify-end gap-3">
                <a href="{{ route('admin.jemaat.index') }}" class="px-5 py-2.5 rounded-xl border border-outline-variant text-xs font-semibold hover:bg-surface-container transition">
                    Batal
                </a>
                <button type="submit" class="px-7 py-2.5 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-md transition flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <span>Simpan Data Jemaat</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const noKkInput = document.getElementById('no_kk');
    const wilayahSelect = document.getElementById('wilayah_id');
    const cariKkInput = document.getElementById('cari_kk_input');
    const btnApply = document.getElementById('btn_apply_kk');
    const btnReset = document.getElementById('btn_reset_kk');
    const kkInfoBox = document.getElementById('kk_selected_info');
    const infoNama = document.getElementById('info_nama_kk');
    const infoKode = document.getElementById('info_kode_kk');
    const infoNext = document.getElementById('info_next_seq');
    const statusKeluargaInput = document.getElementById('status_keluarga');
    const alamatInput = document.getElementById('alamat');

    const maxSeqMap = @json($maxSeqByPrefix ?? []);

    // Sync Wilayah from 2-digit KK
    noKkInput?.addEventListener('input', function() {
        const val = this.value.trim();
        if (val.length >= 2) {
            const prefix = val.substring(0, 2);
            for (let i = 0; i < wilayahSelect.options.length; i++) {
                if (wilayahSelect.options[i].getAttribute('data-prefix') === prefix) {
                    wilayahSelect.selectedIndex = i;
                    break;
                }
            }
        }
    });

    function applySelectedKk() {
        const val = cariKkInput.value.trim();
        if (!val) return;

        const options = document.getElementById('kkDatalist')?.querySelectorAll('option') || [];
        let matched = null;
        for (let opt of options) {
            if (opt.value === val || opt.getAttribute('data-nama') === val || opt.getAttribute('data-kk') === val) {
                matched = opt;
                break;
            }
        }

        if (!matched) {
            // Coba ekstrak no KK dari tanda kurung
            const matchKk = val.match(/KK:\s*([0-9]+)/i);
            if (matchKk) {
                const kkCode = matchKk[1];
                for (let opt of options) {
                    if (opt.getAttribute('data-kk') === kkCode) {
                        matched = opt;
                        break;
                    }
                }
            }
        }

        if (matched) {
            const rawKk = matched.getAttribute('data-kk') || '';
            const nama = matched.getAttribute('data-nama') || '';
            const wilayahId = matched.getAttribute('data-wilayah') || '';
            const alamat = matched.getAttribute('data-alamat') || '';

            // 4 digit awal KK
            const prefix4 = rawKk.substring(0, 4);
            const currentMax = maxSeqMap[prefix4] ? parseInt(maxSeqMap[prefix4]) : (rawKk.length >= 6 ? parseInt(rawKk.substring(4, 6)) : 1);
            const nextSeqNum = currentMax + 1;
            const nextSeqStr = String(nextSeqNum).padStart(2, '0');
            const newKk = prefix4 + nextSeqStr;

            // Isi No KK dengan nomor anggota keluarga berikutnya
            if (noKkInput) {
                noKkInput.value = newKk;
                // Trigger input event to update wilayah
                noKkInput.dispatchEvent(new Event('input'));
            }

            // Set Wilayah jika ada
            if (wilayahId && wilayahSelect) {
                wilayahSelect.value = wilayahId;
            }

            // Set Alamat jika ada dan field alamat belum diisi
            if (alamat && alamatInput && !alamatInput.value) {
                alamatInput.value = alamat;
            }

            // Sugesti status keluarga
            if (statusKeluargaInput && !statusKeluargaInput.value) {
                statusKeluargaInput.value = 'ANAK';
            }

            // Tampilkan info
            if (infoNama) infoNama.textContent = nama;
            if (infoKode) infoKode.textContent = prefix4 + ' (Kepala: ' + rawKk + ')';
            if (infoNext) infoNext.textContent = newKk;
            if (kkInfoBox) kkInfoBox.classList.remove('hidden');
        } else {
            alert('Kepala Keluarga tidak ditemukan. Pastikan Anda memilih dari daftar opsi yang tersedia.');
        }
    }

    btnApply?.addEventListener('click', applySelectedKk);
    cariKkInput?.addEventListener('change', applySelectedKk);

    btnReset?.addEventListener('click', function() {
        if (cariKkInput) cariKkInput.value = '';
        if (kkInfoBox) kkInfoBox.classList.add('hidden');
        if (noKkInput) noKkInput.value = '';
    });
});
</script>
@endsection
