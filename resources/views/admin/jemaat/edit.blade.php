@extends('layouts.admin')

@section('title', 'Ubah Data Jemaat - SIMJ GBI Shekinah Glory')
@section('page_title', 'Perbarui Data Anggota Jemaat')

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
            <span class="font-bold text-primary">Edit: {{ $jemaat->nama_lengkap }}</span>
        </nav>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/50 text-secondary font-bold text-xs">
            <span class="material-symbols-outlined text-[14px]">edit</span>
            Mode Perubahan Data
        </span>
    </div>

    <!-- Main Form Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <!-- Form Header -->
        <div class="p-6 sm:p-8 bg-surface-container-low/60 border-b border-outline-variant/30 flex items-center justify-between">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-primary-container text-secondary-fixed flex items-center justify-center shadow">
                        <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
                    </div>
                    <h2 class="font-serif-display text-2xl font-bold text-primary">Edit Profil: {{ $jemaat->nama_lengkap }}</h2>
                </div>
                <p class="text-xs text-on-surface-variant">
                    Perbarui data sesuai format database gereja (KK, Nama, TTL, JK, Status, Sakramen, Profesi, Keahlian, Penghasilan, dan Keterangan).
                </p>
            </div>
            <a href="{{ route('admin.jemaat.index') }}" class="w-9 h-9 rounded-full bg-surface-container hover:bg-surface-container-high text-outline flex items-center justify-center transition">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </a>
        </div>

        <form action="{{ route('admin.jemaat.update', $jemaat->id) }}" method="POST" class="p-6 sm:p-8 space-y-8">
            @csrf
            @method('PUT')

            <!-- BAGIAN A: IDENTITAS & KELUARGA -->
            <section class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-outline-variant/30">
                    <span class="material-symbols-outlined text-secondary text-[22px]">badge</span>
                    <div>
                        <h3 class="font-serif-display text-lg font-bold text-primary">Identitas Pokok & Keluarga</h3>
                        <p class="text-[11px] text-outline">Nomor KK, Nama Lengkap, Tempat & Tanggal Lahir, Jenis Kelamin, serta Hubungan Keluarga</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <!-- KK -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="no_kk" class="block text-xs font-bold text-primary">No. KK / ID Jemaat (6 Digit)</label>
                        <input type="text" name="no_kk" id="no_kk" value="{{ old('no_kk', $jemaat->no_kk) }}" placeholder="Contoh: 310101" class="w-full px-3 py-2.5 rounded-xl border @error('no_kk') border-red-500 bg-red-50/50 @else border-outline-variant/60 @enderror text-xs focus:border-secondary focus:outline-none transition font-mono font-bold">
                        @error('no_kk')
                        <p class="text-3xs text-red-600 font-semibold mt-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>
                            <span>{{ $message }}</span>
                        </p>
                        @enderror
                    </div>

                    <!-- NIK -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="nik" class="block text-xs font-bold text-primary">NIK (16 Digit KTP)</label>
                        <input type="text" name="nik" id="nik" value="{{ old('nik', $jemaat->nik) }}" placeholder="5371xxxxxxxxxxxx" maxlength="20" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition font-mono">
                    </div>

                    <!-- Tipe Keanggotaan -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="tipe_keanggotaan" class="block text-xs font-bold text-primary">Tipe Keanggotaan</label>
                        <select name="tipe_keanggotaan" id="tipe_keanggotaan" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                            <option value="jemaat_tetap" {{ old('tipe_keanggotaan', $jemaat->tipe_keanggotaan) === 'jemaat_tetap' ? 'selected' : '' }}>Jemaat Tetap</option>
                            <option value="simpatisan" {{ old('tipe_keanggotaan', $jemaat->tipe_keanggotaan) === 'simpatisan' ? 'selected' : '' }}>Simpatisan</option>
                            <option value="mutasi" {{ old('tipe_keanggotaan', $jemaat->tipe_keanggotaan) === 'mutasi' ? 'selected' : '' }}>Mutasi / Pindahan</option>
                        </select>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="md:col-span-8 space-y-1">
                        <label for="nama_lengkap" class="block text-xs font-bold text-primary">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" required value="{{ old('nama_lengkap', $jemaat->nama_lengkap) }}" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition font-semibold">
                    </div>

                    <!-- Kategori Khusus Diakonia -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="kategori_khusus" class="block text-xs font-bold text-primary">Kategori Khusus / Diakonia</label>
                        <select name="kategori_khusus" id="kategori_khusus" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                            <option value="umum" {{ old('kategori_khusus', $jemaat->kategori_khusus) === 'umum' ? 'selected' : '' }}>Umum / Tidak Ada</option>
                            <option value="janda" {{ old('kategori_khusus', $jemaat->kategori_khusus) === 'janda' ? 'selected' : '' }}>Janda</option>
                            <option value="duda" {{ old('kategori_khusus', $jemaat->kategori_khusus) === 'duda' ? 'selected' : '' }}>Duda</option>
                            <option value="yatim" {{ old('kategori_khusus', $jemaat->kategori_khusus) === 'yatim' ? 'selected' : '' }}>Yatim</option>
                            <option value="piatu" {{ old('kategori_khusus', $jemaat->kategori_khusus) === 'piatu' ? 'selected' : '' }}>Piatu</option>
                            <option value="yatim_piatu" {{ old('kategori_khusus', $jemaat->kategori_khusus) === 'yatim_piatu' ? 'selected' : '' }}>Yatim Piatu</option>
                            <option value="lansia" {{ old('kategori_khusus', $jemaat->kategori_khusus) === 'lansia' ? 'selected' : '' }}>Lansia</option>
                        </select>
                    </div>

                    <!-- Tempat Lahir -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="tempat_lahir" class="block text-xs font-bold text-primary">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $jemaat->tempat_lahir) }}" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="tanggal_lahir" class="block text-xs font-bold text-primary">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" max="{{ date('Y-m-d') }}" value="{{ old('tanggal_lahir', $jemaat->tanggal_lahir?->format('Y-m-d')) }}" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="md:col-span-4 space-y-1">
                        <label class="block text-xs font-bold text-primary">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition bg-white">
                            <option value="L" {{ old('jenis_kelamin', $jemaat->jenis_kelamin) === 'L' ? 'selected' : '' }}>L (Laki-laki)</option>
                            <option value="P" {{ old('jenis_kelamin', $jemaat->jenis_kelamin) === 'P' ? 'selected' : '' }}>P (Perempuan)</option>
                        </select>
                    </div>

                    <!-- Status Hubungan Keluarga -->
                    <div class="md:col-span-6 space-y-1">
                        <label for="status_keluarga" class="block text-xs font-bold text-primary">Status Dalam Keluarga (STATUS)</label>
                        <input type="text" name="status_keluarga" id="status_keluarga" list="statusKeluargaList" value="{{ old('status_keluarga', $jemaat->status_keluarga) }}" placeholder="Contoh: SUAMI (KEPALA KELUARGA), ISTRI, ANAK, JANDA, DUDA" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
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

                    <!-- Nikah Masehi -->
                    <div class="md:col-span-6 space-y-1">
                        <label for="nikah_masehi" class="block text-xs font-bold text-primary">Nikah Masehi</label>
                        <select name="nikah_masehi" id="nikah_masehi" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition bg-white">
                            <option value="">-- Pilih Status --</option>
                            <option value="SUDAH" {{ old('nikah_masehi', $jemaat->nikah_masehi) === 'SUDAH' ? 'selected' : '' }}>SUDAH</option>
                            <option value="BELUM" {{ old('nikah_masehi', $jemaat->nikah_masehi) === 'BELUM' ? 'selected' : '' }}>BELUM</option>
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
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <!-- Penyerahan / Baptisan -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="status_baptis" class="block text-xs font-bold text-primary">Penyerahan / Baptisan</label>
                        <select name="status_baptis" id="status_baptis" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition bg-white">
                            <option value="">-- Pilih --</option>
                            <option value="BAPTIS" {{ old('status_baptis', $jemaat->status_baptis) === 'BAPTIS' ? 'selected' : '' }}>BAPTIS</option>
                            <option value="PENYERAHAN" {{ old('status_baptis', $jemaat->status_baptis) === 'PENYERAHAN' ? 'selected' : '' }}>PENYERAHAN</option>
                            <option value="BELUM" {{ old('status_baptis', $jemaat->status_baptis) === 'BELUM' ? 'selected' : '' }}>BELUM</option>
                        </select>
                    </div>

                    <!-- Wilayah / Sektor -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="wilayah_id" class="block text-xs font-bold text-primary">Wilayah / Sektor</label>
                        <select name="wilayah_id" id="wilayah_id" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition bg-white">
                            <option value="">-- Otomatis Dari Kode KK atau Pilih --</option>
                            @php
                                $grouped = $wilayahList->groupBy('wilayah_besar');
                            @endphp
                            @foreach($grouped as $wb => $list)
                            <optgroup label="Wilayah Besar: {{ $wb }}">
                                @foreach($list as $w)
                                <option value="{{ $w->id }}" data-prefix="{{ $w->kode_prefix }}" {{ old('wilayah_id', $jemaat->wilayah_id) == $w->id ? 'selected' : '' }}>
                                    [{{ $w->kode_prefix }}] {{ $w->nama_wilayah }}
                                </option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Keanggotaan -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="status_keanggotaan" class="block text-xs font-bold text-primary">Status Keanggotaan</label>
                        <select name="status_keanggotaan" id="status_keanggotaan" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition bg-white">
                            <option value="aktif" {{ old('status_keanggotaan', $jemaat->status_keanggotaan) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="tidak_aktif" {{ old('status_keanggotaan', $jemaat->status_keanggotaan) === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            <option value="pindah" {{ old('status_keanggotaan', $jemaat->status_keanggotaan) === 'pindah' ? 'selected' : '' }}>Pindah</option>
                            <option value="meninggal" {{ old('status_keanggotaan', $jemaat->status_keanggotaan) === 'meninggal' ? 'selected' : '' }}>Meninggal</option>
                        </select>
                    </div>
                </div>
            </section>

            <!-- BAGIAN C: PENDIDIKAN, PEKERJAAN & KEAHLIAN -->
            <section class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-outline-variant/30">
                    <span class="material-symbols-outlined text-secondary text-[22px]">work</span>
                    <div>
                        <h3 class="font-serif-display text-lg font-bold text-primary">Pendidikan, Profesi & Keahlian Khusus</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <!-- Pendidikan -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="pendidikan" class="block text-xs font-bold text-primary">Pendidikan Terakhir</label>
                        <input type="text" name="pendidikan" id="pendidikan" value="{{ old('pendidikan', $jemaat->pendidikan) }}" placeholder="Contoh: SD, SMP, SMA, D3, SARJANA" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                    </div>

                    <!-- Pekerjaan -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="pekerjaan" class="block text-xs font-bold text-primary">Pekerjaan</label>
                        <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan', $jemaat->pekerjaan) }}" placeholder="Contoh: BURUH, IRT, SWASTA" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                    </div>

                    <!-- Keahlian Khusus -->
                    <div class="md:col-span-4 space-y-1">
                        <label for="keahlian_khusus" class="block text-xs font-bold text-primary">Keahlian Khusus / Talenta</label>
                        <input type="text" name="keahlian_khusus" id="keahlian_khusus" value="{{ old('keahlian_khusus', $jemaat->keahlian_khusus) }}" placeholder="Contoh: MENYANYI, MASAK, PEMAIN MUSIK" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                    </div>

                    <!-- Penghasilan Per Bulan -->
                    <div class="md:col-span-6 space-y-1">
                        <label for="penghasilan_per_bulan" class="block text-xs font-bold text-primary">Penghasilan Per Bulan</label>
                        <input type="text" name="penghasilan_per_bulan" id="penghasilan_per_bulan" value="{{ old('penghasilan_per_bulan', $jemaat->penghasilan_per_bulan) }}" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition font-mono">
                    </div>

                    <!-- Lain-lain -->
                    <div class="md:col-span-6 space-y-1">
                        <label for="lain_lain" class="block text-xs font-bold text-primary">Lain-lain</label>
                        <input type="text" name="lain_lain" id="lain_lain" value="{{ old('lain_lain', $jemaat->lain_lain) }}" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
                    </div>

                    <!-- Keterangan -->
                    <div class="md:col-span-12 space-y-1">
                        <label for="keterangan" class="block text-xs font-bold text-primary">Keterangan</label>
                        <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan', $jemaat->keterangan) }}" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant/60 text-xs focus:border-secondary focus:outline-none transition">
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
                    <span>Simpan Perubahan Data</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('no_kk')?.addEventListener('input', function() {
    const val = this.value.trim();
    if (val.length >= 2) {
        const prefix = val.substring(0, 2);
        const select = document.getElementById('wilayah_id');
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].getAttribute('data-prefix') === prefix) {
                select.selectedIndex = i;
                break;
            }
        }
    }
});
</script>
@endsection
