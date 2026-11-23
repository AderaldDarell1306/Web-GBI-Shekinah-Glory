@extends('layouts.public')

@section('title', 'Pendaftaran Sakramen & Layanan Jemaat - GBI Shekinah Glory')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Header Page -->
    <div class="text-center max-w-3xl mx-auto mb-8" data-aos="fade-up">
        <span class="text-xs font-bold uppercase tracking-widest text-secondary mb-2 block">Layanan Pastoral &amp; Administrasi Gereja</span>
        <h1 class="font-serif-display text-3xl sm:text-4xl font-bold text-primary mb-2">Formulir Pendaftaran &amp; Pratinjau Dokumen</h1>
        <p class="text-xs sm:text-sm text-on-surface-variant">Lengkapi data Anda di bawah ini. Dokumen resmi blanko gereja akan terisi otomatis dan dapat langsung dicetak atau diunduh.</p>
        <div class="w-16 h-1 bg-secondary mx-auto mt-4 rounded-full"></div>
    </div>

    <!-- Pilihan 4 Layanan Tabs -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8" data-aos="fade-up">
        <!-- 1. Baptis -->
        <a href="{{ route('public.pendaftaran', ['layanan' => 'baptis']) }}" class="p-3.5 rounded-2xl border text-center transition flex flex-col items-center gap-1.5 {{ $layanan === 'baptis' ? 'bg-primary text-white border-primary shadow-md scale-[1.02]' : 'bg-white text-on-surface hover:bg-surface-container border-outline-variant/30' }}">
            <span class="material-symbols-outlined text-[24px]">water_drop</span>
            <span class="text-xs font-bold leading-tight">Pengajuan Baptisan</span>
        </a>

        <!-- 2. Penyerahan Anak -->
        <a href="{{ route('public.pendaftaran', ['layanan' => 'penyerahan_anak']) }}" class="p-3.5 rounded-2xl border text-center transition flex flex-col items-center gap-1.5 {{ $layanan === 'penyerahan_anak' ? 'bg-primary text-white border-primary shadow-md scale-[1.02]' : 'bg-white text-on-surface hover:bg-surface-container border-outline-variant/30' }}">
            <span class="material-symbols-outlined text-[24px]">child_care</span>
            <span class="text-xs font-bold leading-tight">Penyerahan Anak</span>
        </a>

        <!-- 3. Pernikahan Kudus -->
        <a href="{{ route('public.pendaftaran', ['layanan' => 'nikah']) }}" class="p-3.5 rounded-2xl border text-center transition flex flex-col items-center gap-1.5 {{ $layanan === 'nikah' ? 'bg-primary text-white border-primary shadow-md scale-[1.02]' : 'bg-white text-on-surface hover:bg-surface-container border-outline-variant/30' }}">
            <span class="material-symbols-outlined text-[24px]">favorite</span>
            <span class="text-xs font-bold leading-tight">Pengajuan Pernikahan</span>
        </a>

        <!-- 4. Anggota Jemaat Baru -->
        <a href="{{ route('public.pendaftaran', ['layanan' => 'jemaat_baru']) }}" class="p-3.5 rounded-2xl border text-center transition flex flex-col items-center gap-1.5 {{ $layanan === 'jemaat_baru' ? 'bg-primary text-white border-primary shadow-md scale-[1.02]' : 'bg-white text-on-surface hover:bg-surface-container border-outline-variant/30' }}">
            <span class="material-symbols-outlined text-[24px]">person_add</span>
            <span class="text-xs font-bold leading-tight">Anggota Jemaat Baru</span>
        </a>
    </div>

    <!-- Toggle Tombol Mobile (Form vs Preview Dokumen) -->
    <div class="lg:hidden flex items-center justify-center p-1 bg-surface-container rounded-xl mb-6 border border-outline-variant/30">
        <button type="button" onclick="setMobileTab('form')" id="btnTabForm" class="flex-1 py-2 rounded-lg text-xs font-bold text-center bg-white text-primary shadow-xs transition">
            1. Isi Formulir
        </button>
        <button type="button" onclick="setMobileTab('preview')" id="btnTabPreview" class="flex-1 py-2 rounded-lg text-xs font-bold text-center text-on-surface-variant hover:text-primary transition flex items-center justify-center gap-1">
            <span class="material-symbols-outlined text-[16px] text-secondary">visibility</span>
            <span>2. Pratinjau Dokumen</span>
        </button>
    </div>

    <!-- MAIN CONTAINER GRID: SPLIT SCREEN (FORM DI KIRI, LIVE TEMPLATE PREVIEW DI KANAN) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- ======================================================== -->
        <!-- KOLOM KIRI: FORMULIR INPUT INTERAKTIF (5 COLS)            -->
        <!-- ======================================================== -->
        <div id="sectionFormInput" class="lg:col-span-5 bg-white rounded-2xl p-6 sm:p-7 shadow-md border border-outline-variant/30 space-y-6">
            <div class="border-b border-outline-variant/20 pb-3 flex items-center justify-between">
                <div>
                    <h2 class="font-serif-display text-lg font-bold text-primary flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-secondary text-[20px]">edit_note</span>
                        <span>Pengisian Formulir</span>
                    </h2>
                    <p class="text-2xs text-on-surface-variant">Ketik data Anda, template dokumen di samping akan terisi otomatis.</p>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-secondary-container/60 text-secondary text-[10px] font-bold uppercase">
                    {{ strtoupper(str_replace('_', ' ', $layanan)) }}
                </span>
            </div>

            <form id="formPendaftaran" action="{{ route('public.pendaftaran.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="jenis_layanan" value="{{ $layanan }}">

                <!-- ================= 1. BAPTISAN AIR (Roma 6:4) ================= -->
                @if($layanan === 'baptis')
                <div class="space-y-3.5">
                    <span class="text-2xs font-bold text-secondary uppercase tracking-wider block">➢ Biodata Pribadi Calon Baptis</span>
                    
                    <div>
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">1. Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="in_nama" name="nama_pemohon" required placeholder="Contoh: Hendrikus Ello" class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                    </div>

                    <div>
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">2. Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select id="in_jk" name="data_tambahan[jenis_kelamin]" required class="w-full px-3 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition bg-white">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-2xs font-bold uppercase text-primary">3. Tempat, Tanggal Lahir &amp; Umur <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-5">
                                <input type="text" id="in_tempat_lahir" name="data_tambahan[tempat_lahir]" placeholder="Tempat Lahir" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div class="col-span-4">
                                <input type="date" id="in_tgl_lahir" name="data_tambahan[tanggal_lahir]" class="w-full px-2.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div class="col-span-3">
                                <input type="text" id="in_umur" name="data_tambahan[umur]" placeholder="22 Thn" class="w-full px-2.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">4. Alamat <span class="text-red-500">*</span></label>
                        <input type="text" id="in_alamat" name="alamat" required placeholder="Contoh: Jl. Ikan Arwana, RT 004 / RW 002 Namosain" class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                    </div>

                    <div>
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">5. No.Hp <span class="text-red-500">*</span></label>
                        <input type="tel" id="in_hp" name="no_wa" required placeholder="Contoh: 081234567890" class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                    </div>

                    <div class="pt-2 border-t border-outline-variant/20 space-y-3">
                        <span class="text-2xs font-bold text-secondary uppercase tracking-wider block">6. Nama Orang Tua</span>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">❖ Ayah <span class="text-red-500">*</span></label>
                                <input type="text" id="in_ayah" name="data_tambahan[nama_ayah]" required placeholder="Nama lengkap Ayah" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">❖ Ibu <span class="text-red-500">*</span></label>
                                <input type="text" id="in_ibu" name="data_tambahan[nama_ibu]" required placeholder="Nama lengkap Ibu" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">7. Oikos Wilayah Pelayanan</label>
                            <select id="in_wilayah" name="data_tambahan[wilayah_pelayanan]" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition bg-white">
                                <option value="">-- Pilih Wilayah Pelayanan --</option>
                                @foreach($wilayahList ?? [] as $w)
                                <option value="{{ $w->nama_wilayah }}">{{ $w->nama_wilayah }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Slot Upload Terpisah: BAPTIS -->
                    <div class="pt-3 border-t border-outline-variant/20 space-y-3">
                        <span class="text-2xs font-bold text-secondary uppercase tracking-wider block">
                            Berkas Persyaratan (Upload Terpisah Sesuai Form)
                        </span>

                        <div class="space-y-2.5">
                            <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/40 space-y-1">
                                <label class="block text-2xs font-bold text-primary">1. Fotocopy KTP Pemohon</label>
                                <input type="file" name="file_ktp" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full text-xs text-gray-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-secondary file:text-white hover:file:bg-secondary/90 transition cursor-pointer">
                            </div>
                            <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/40 space-y-1">
                                <label class="block text-2xs font-bold text-primary">2. Fotocopy Kartu Keluarga (KK)</label>
                                <input type="file" name="file_kk" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full text-xs text-gray-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-secondary file:text-white hover:file:bg-secondary/90 transition cursor-pointer">
                            </div>
                            <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/40 space-y-1">
                                <label class="block text-2xs font-bold text-primary">3. Pas Foto Terbaru (3x4 / 4x6)</label>
                                <input type="file" name="file_pas_foto" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-gray-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-secondary file:text-white hover:file:bg-secondary/90 transition cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= 2. PENYERAHAN ANAK (Markus 10:14-16) ================= -->
                @elseif($layanan === 'penyerahan_anak')
                <div class="space-y-3.5">
                    <span class="text-2xs font-bold text-secondary uppercase tracking-wider block">➢ Biodata Anak</span>
                    <div>
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">1. Nama Lengkap Anak <span class="text-red-500">*</span></label>
                        <input type="text" id="in_anak_nama" name="nama_pemohon" required placeholder="Contoh: Charlos A. Benyamin" class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-2xs font-bold uppercase text-primary">2. TTL / Usia Anak <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-5">
                                <input type="text" id="in_anak_tempat" name="data_tambahan[tempat_lahir]" placeholder="Tempat Lahir" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div class="col-span-4">
                                <input type="date" id="in_anak_tgl" name="data_tambahan[tanggal_lahir]" class="w-full px-2.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div class="col-span-3">
                                <input type="text" id="in_anak_usia" name="data_tambahan[usia]" placeholder="1 Thn 2 Bln" class="w-full px-2.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">3. Jenis Kelamin Anak</label>
                        <select id="in_anak_jk" name="data_tambahan[jenis_kelamin]" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition bg-white">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="pt-2 border-t border-outline-variant/20 space-y-3">
                        <span class="text-2xs font-bold text-secondary uppercase tracking-wider block">➢ Biodata Orang Tua</span>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">1. Nama Lengkap Ayah <span class="text-red-500">*</span></label>
                                <input type="text" id="in_pa_ayah" name="data_tambahan[nama_ayah]" required placeholder="Nama Ayah" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">2. Nama Lengkap Ibu <span class="text-red-500">*</span></label>
                                <input type="text" id="in_pa_ibu" name="data_tambahan[nama_ibu]" required placeholder="Nama Ibu" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">3. Alamat <span class="text-red-500">*</span></label>
                            <input type="text" id="in_pa_alamat" name="alamat" required placeholder="Alamat rumah / domisili" class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">4. Oikos Wilayah Pelayanan</label>
                                <select id="in_pa_wilayah" name="data_tambahan[wilayah_pelayanan]" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition bg-white">
                                    <option value="">-- Pilih Wilayah --</option>
                                    @foreach($wilayahList ?? [] as $w)
                                    <option value="{{ $w->nama_wilayah }}">{{ $w->nama_wilayah }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">5. No.Hp <span class="text-red-500">*</span></label>
                                <input type="tel" id="in_pa_hp" name="no_wa" required placeholder="081234567890" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-outline-variant/20 space-y-3">
                        <span class="text-2xs font-bold text-secondary uppercase tracking-wider block">➢ Biodata Orang Tua Saksi</span>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">1. Nama Saksi .1</label>
                                <input type="text" id="in_saksi_1" name="data_tambahan[nama_saksi_1]" placeholder="Nama Saksi 1" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">2. Nama Saksi .2</label>
                                <input type="text" id="in_saksi_2" name="data_tambahan[nama_saksi_2]" placeholder="Nama Saksi 2" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">3. Jenis Kelamin Saksi</label>
                                <input type="text" id="in_saksi_jk" name="data_tambahan[jenis_kelamin_saksi]" placeholder="L / P" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">4. Alamat Saksi</label>
                                <input type="text" id="in_saksi_alamat" name="data_tambahan[alamat_saksi]" placeholder="Alamat saksi" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">5. Asal Gereja Saksi</label>
                                <input type="text" id="in_saksi_gereja" name="data_tambahan[asal_gereja_saksi]" placeholder="Contoh: GBI Shekinah Glory" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">6. No.Hp Saksi</label>
                                <input type="tel" id="in_saksi_hp" name="data_tambahan[no_hp_saksi]" placeholder="No. HP saksi" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>
                    </div>

                    <!-- Slot Upload Terpisah: PENYERAHAN ANAK -->
                    <div class="pt-3 border-t border-outline-variant/20 space-y-3">
                        <span class="text-2xs font-bold text-secondary uppercase tracking-wider block">
                            Berkas Persyaratan (Upload Terpisah Sesuai Form)
                        </span>

                        <div class="space-y-2.5">
                            <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/40 space-y-1">
                                <label class="block text-2xs font-bold text-primary">1. Fotocopy Kartu Keluarga (KK)</label>
                                <input type="file" name="file_kk" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full text-xs text-gray-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-secondary file:text-white hover:file:bg-secondary/90 transition cursor-pointer">
                            </div>
                            <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/40 space-y-1">
                                <label class="block text-2xs font-bold text-primary">2. Fotocopy Akte Lahir Anak / Surat Kenal Lahir</label>
                                <input type="file" name="file_akta_anak" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full text-xs text-gray-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-secondary file:text-white hover:file:bg-secondary/90 transition cursor-pointer">
                            </div>
                            <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/40 space-y-1">
                                <label class="block text-2xs font-bold text-primary">3. Fotocopy KTP Orang Tua (Ayah / Ibu)</label>
                                <input type="file" name="file_ktp_ortu" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full text-xs text-gray-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-secondary file:text-white hover:file:bg-secondary/90 transition cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= 3. PENGAJUAN PERNIKAHAN (Matius 19:6) ================= -->
                @elseif($layanan === 'nikah')
                <div class="space-y-5">
                    <!-- Calon Mempelai Pria (13 Poin Plek Ketiplek) -->
                    <div class="space-y-3 p-3.5 rounded-xl bg-blue-50/40 border border-blue-200/60">
                        <span class="text-xs font-bold text-blue-900 uppercase tracking-wider block flex items-center gap-1.5 border-b border-blue-200 pb-1.5">
                            <span class="material-symbols-outlined text-[17px]">male</span>
                            <span>Biodata Calon Pengantin Pria</span>
                        </span>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">1. Nama Lengkap Pria <span class="text-red-500">*</span></label>
                            <input type="text" id="in_nk_pria_nama" name="nama_pemohon" required placeholder="Nama lengkap calon mempelai pria" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-5">
                                <label class="block text-3xs font-bold uppercase text-gray-700 mb-0.5">2. Tempat Lahir</label>
                                <input type="text" id="in_nk_pria_tempat" name="data_tambahan[pria_tempat]" placeholder="Kupang" class="w-full px-2.5 py-1.5 rounded-xl border border-outline-variant text-xs">
                            </div>
                            <div class="col-span-4">
                                <label class="block text-3xs font-bold uppercase text-gray-700 mb-0.5">Tanggal Lahir</label>
                                <input type="date" id="in_nk_pria_tgl" name="data_tambahan[pria_tgl]" class="w-full px-2 py-1.5 rounded-xl border border-outline-variant text-xs">
                            </div>
                            <div class="col-span-3">
                                <label class="block text-3xs font-bold uppercase text-gray-700 mb-0.5">Usia</label>
                                <input type="text" id="in_nk_pria_usia" name="data_tambahan[pria_usia]" placeholder="28 Thn" class="w-full px-2 py-1.5 rounded-xl border border-outline-variant text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">3. Alamat Pria <span class="text-red-500">*</span></label>
                            <input type="text" id="in_nk_pria_alamat" name="alamat" required placeholder="Alamat calon pengantin pria" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">4. Baptis/Sidi Dll/Pada Tgl</label>
                                <input type="text" id="in_nk_pria_baptis" name="data_tambahan[pria_baptis]" placeholder="12 Mei 2015" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">5. Asal Gereja Pria</label>
                                <input type="text" id="in_nk_pria_gereja" name="data_tambahan[pria_gereja]" placeholder="GBI Shekinah Glory" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">6. No Hp Pria <span class="text-red-500">*</span></label>
                            <input type="tel" id="in_nk_pria_hp" name="no_wa" required placeholder="081234567890" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">7. ❖ Ayah Pria</label>
                                <input type="text" id="in_nk_pria_ayah" name="data_tambahan[pria_ayah]" placeholder="Nama Ayah Pria" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">❖ Ibu Pria</label>
                                <input type="text" id="in_nk_pria_ibu" name="data_tambahan[pria_ibu]" placeholder="Nama Ibu Pria" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">8. No Hp Ayah/Ibu Pria</label>
                            <input type="tel" id="in_nk_pria_ortu_hp" name="data_tambahan[pria_ortu_hp]" placeholder="No Hp Orang Tua Pria" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                        </div>
                        <!-- Saksi Pria (Poin 9-13) -->
                        <div class="pt-2 border-t border-blue-200/80 space-y-2">
                            <span class="text-3xs font-bold uppercase tracking-wider text-blue-800 block">Data Saksi Pria (Poin 9 - 13)</span>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-3xs font-bold text-gray-700 mb-0.5">9. Nama Saksi</label>
                                    <input type="text" id="in_nk_pria_saksi" name="data_tambahan[pria_saksi]" placeholder="Nama Saksi Pria" class="w-full px-2.5 py-1.5 rounded-xl border border-outline-variant text-xs">
                                </div>
                                <div>
                                    <label class="block text-3xs font-bold text-gray-700 mb-0.5">10. TTL Saksi</label>
                                    <input type="text" id="in_nk_pria_saksi_ttl" name="data_tambahan[pria_saksi_ttl]" placeholder="Kupang, 10-02-1980" class="w-full px-2.5 py-1.5 rounded-xl border border-outline-variant text-xs">
                                </div>
                            </div>
                            <div>
                                <label class="block text-3xs font-bold text-gray-700 mb-0.5">11. Alamat Saksi</label>
                                <input type="text" id="in_nk_pria_saksi_alamat" name="data_tambahan[pria_saksi_alamat]" placeholder="Alamat Saksi Pria" class="w-full px-2.5 py-1.5 rounded-xl border border-outline-variant text-xs">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-3xs font-bold text-gray-700 mb-0.5">12. No Hp Saksi</label>
                                    <input type="tel" id="in_nk_pria_saksi_hp" name="data_tambahan[pria_saksi_hp]" placeholder="No Hp Saksi" class="w-full px-2.5 py-1.5 rounded-xl border border-outline-variant text-xs">
                                </div>
                                <div>
                                    <label class="block text-3xs font-bold text-gray-700 mb-0.5">13. Asal Gereja Saksi</label>
                                    <input type="text" id="in_nk_pria_saksi_gereja" name="data_tambahan[pria_saksi_gereja]" placeholder="Asal Gereja Saksi" class="w-full px-2.5 py-1.5 rounded-xl border border-outline-variant text-xs">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Calon Mempelai Wanita (13 Poin Plek Ketiplek) -->
                    <div class="space-y-3 p-3.5 rounded-xl bg-rose-50/40 border border-rose-200/60">
                        <span class="text-xs font-bold text-rose-900 uppercase tracking-wider block flex items-center gap-1.5 border-b border-rose-200 pb-1.5">
                            <span class="material-symbols-outlined text-[17px]">female</span>
                            <span>Biodata Calon Pengantin Wanita</span>
                        </span>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">1. Nama Lengkap Wanita <span class="text-red-500">*</span></label>
                            <input type="text" id="in_nk_wanita_nama" name="data_tambahan[wanita_nama]" required placeholder="Nama lengkap calon mempelai wanita" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-5">
                                <label class="block text-3xs font-bold uppercase text-gray-700 mb-0.5">2. Tempat Lahir</label>
                                <input type="text" id="in_nk_wanita_tempat" name="data_tambahan[wanita_tempat]" placeholder="Kupang" class="w-full px-2.5 py-1.5 rounded-xl border border-outline-variant text-xs">
                            </div>
                            <div class="col-span-4">
                                <label class="block text-3xs font-bold uppercase text-gray-700 mb-0.5">Tanggal Lahir</label>
                                <input type="date" id="in_nk_wanita_tgl" name="data_tambahan[wanita_tgl]" class="w-full px-2 py-1.5 rounded-xl border border-outline-variant text-xs">
                            </div>
                            <div class="col-span-3">
                                <label class="block text-3xs font-bold uppercase text-gray-700 mb-0.5">Usia</label>
                                <input type="text" id="in_nk_wanita_usia" name="data_tambahan[wanita_usia]" placeholder="26 Thn" class="w-full px-2 py-1.5 rounded-xl border border-outline-variant text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">3. Alamat Wanita</label>
                            <input type="text" id="in_nk_wanita_alamat" name="data_tambahan[wanita_alamat]" placeholder="Alamat calon pengantin wanita" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">4. Baptis/Sidi Dll/Pada Tgl</label>
                                <input type="text" id="in_nk_wanita_baptis" name="data_tambahan[wanita_baptis]" placeholder="18 Juli 2017" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">5. Asal Gereja Wanita</label>
                                <input type="text" id="in_nk_wanita_gereja" name="data_tambahan[wanita_gereja]" placeholder="Asal gereja wanita" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">6. No Hp Wanita</label>
                            <input type="tel" id="in_nk_wanita_hp" name="data_tambahan[wanita_hp]" placeholder="No Hp calon pengantin wanita" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">7. ❖ Ayah Wanita</label>
                                <input type="text" id="in_nk_wanita_ayah" name="data_tambahan[wanita_ayah]" placeholder="Nama Ayah Wanita" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">❖ Ibu Wanita</label>
                                <input type="text" id="in_nk_wanita_ibu" name="data_tambahan[wanita_ibu]" placeholder="Nama Ibu Wanita" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">8. No.Hp Ayah/Ibu Wanita</label>
                            <input type="tel" id="in_nk_wanita_ortu_hp" name="data_tambahan[wanita_ortu_hp]" placeholder="No Hp Orang Tua Wanita" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                        </div>
                        <!-- Saksi Wanita (Poin 9-13) -->
                        <div class="pt-2 border-t border-rose-200/80 space-y-2">
                            <span class="text-3xs font-bold uppercase tracking-wider text-rose-800 block">Data Saksi Wanita (Poin 9 - 13)</span>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-3xs font-bold text-gray-700 mb-0.5">9. Nama Saksi</label>
                                    <input type="text" id="in_nk_wanita_saksi" name="data_tambahan[wanita_saksi]" placeholder="Nama Saksi Wanita" class="w-full px-2.5 py-1.5 rounded-xl border border-outline-variant text-xs">
                                </div>
                                <div>
                                    <label class="block text-3xs font-bold text-gray-700 mb-0.5">10. TTL Saksi</label>
                                    <input type="text" id="in_nk_wanita_saksi_ttl" name="data_tambahan[wanita_saksi_ttl]" placeholder="Kupang, 15-08-1982" class="w-full px-2.5 py-1.5 rounded-xl border border-outline-variant text-xs">
                                </div>
                            </div>
                            <div>
                                <label class="block text-3xs font-bold text-gray-700 mb-0.5">11. Alamat Saksi</label>
                                <input type="text" id="in_nk_wanita_saksi_alamat" name="data_tambahan[wanita_saksi_alamat]" placeholder="Alamat Saksi Wanita" class="w-full px-2.5 py-1.5 rounded-xl border border-outline-variant text-xs">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-3xs font-bold text-gray-700 mb-0.5">12. No Hp Saksi</label>
                                    <input type="tel" id="in_nk_wanita_saksi_hp" name="data_tambahan[wanita_saksi_hp]" placeholder="No Hp Saksi" class="w-full px-2.5 py-1.5 rounded-xl border border-outline-variant text-xs">
                                </div>
                                <div>
                                    <label class="block text-3xs font-bold text-gray-700 mb-0.5">13. Asal Gereja Saksi</label>
                                    <input type="text" id="in_nk_wanita_saksi_gereja" name="data_tambahan[wanita_saksi_gereja]" placeholder="Asal Gereja Saksi" class="w-full px-2.5 py-1.5 rounded-xl border border-outline-variant text-xs">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slot Upload Terpisah: PENGAJUAN PERNIKAHAN (Sesuai 5 Poin Catatan Blanko Fisik) -->
                    <div class="pt-3 border-t border-outline-variant/20 space-y-3">
                        <span class="text-2xs font-bold text-secondary uppercase tracking-wider block">
                            Berkas Persyaratan (Upload Terpisah Sesuai Catatan Blanko)
                        </span>

                        <div class="space-y-2.5">
                            <div class="p-2.5 rounded-xl bg-surface-container-low border border-outline-variant/40 space-y-1">
                                <label class="block text-2xs font-bold text-primary">1. Fotocopy KTP Pria &amp; Wanita</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <div>
                                        <span class="text-3xs text-gray-500 block mb-0.5">KTP Pria:</span>
                                        <input type="file" name="file_ktp_pria" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full text-2xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-2xs file:bg-blue-700 file:text-white">
                                    </div>
                                    <div>
                                        <span class="text-3xs text-gray-500 block mb-0.5">KTP Wanita:</span>
                                        <input type="file" name="file_ktp_wanita" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full text-2xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-2xs file:bg-rose-700 file:text-white">
                                    </div>
                                </div>
                            </div>

                            <div class="p-2.5 rounded-xl bg-surface-container-low border border-outline-variant/40 space-y-1">
                                <label class="block text-2xs font-bold text-primary">2. Surat Baptis / Sidi Pria &amp; Wanita</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <div>
                                        <span class="text-3xs text-gray-500 block mb-0.5">Surat Baptis Pria:</span>
                                        <input type="file" name="file_baptis_pria" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full text-2xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-2xs file:bg-blue-700 file:text-white">
                                    </div>
                                    <div>
                                        <span class="text-3xs text-gray-500 block mb-0.5">Surat Baptis Wanita:</span>
                                        <input type="file" name="file_baptis_wanita" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full text-2xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-2xs file:bg-rose-700 file:text-white">
                                    </div>
                                </div>
                            </div>

                            <div class="p-2.5 rounded-xl bg-surface-container-low border border-outline-variant/40 space-y-1">
                                <label class="block text-2xs font-bold text-primary">3. Fotocopy Kartu Keluarga (KK) Pria &amp; Wanita</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <div>
                                        <span class="text-3xs text-gray-500 block mb-0.5">KK Pria:</span>
                                        <input type="file" name="file_kk_pria" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full text-2xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-2xs file:bg-blue-700 file:text-white">
                                    </div>
                                    <div>
                                        <span class="text-3xs text-gray-500 block mb-0.5">KK Wanita:</span>
                                        <input type="file" name="file_kk_wanita" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full text-2xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-2xs file:bg-rose-700 file:text-white">
                                    </div>
                                </div>
                            </div>

                            <div class="p-2.5 rounded-xl bg-surface-container-low border border-outline-variant/40 space-y-1">
                                <label class="block text-2xs font-bold text-primary">4. Fotocopy Akte Kelahiran Pria &amp; Wanita</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <div>
                                        <span class="text-3xs text-gray-500 block mb-0.5">Akte Lahir Pria:</span>
                                        <input type="file" name="file_akta_pria" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full text-2xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-2xs file:bg-blue-700 file:text-white">
                                    </div>
                                    <div>
                                        <span class="text-3xs text-gray-500 block mb-0.5">Akte Lahir Wanita:</span>
                                        <input type="file" name="file_akta_wanita" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full text-2xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-2xs file:bg-rose-700 file:text-white">
                                    </div>
                                </div>
                            </div>

                            <div class="p-2.5 rounded-xl bg-surface-container-low border border-outline-variant/40 space-y-1">
                                <label class="block text-2xs font-bold text-primary">5. Pas Foto Gandeng Calon Pengantin (4x6)</label>
                                <input type="file" name="file_foto_gandeng" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-gray-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-secondary file:text-white hover:file:bg-secondary/90 transition cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= 4. ANGGOTA JEMAAT BARU (2 Halaman Plek Ketiplek) ================= -->
                @else
                <div class="space-y-4">
                    <!-- 1. Biodata Pribadi -->
                    <div class="space-y-3">
                        <span class="text-xs font-bold text-secondary uppercase tracking-wider block">➢ 1. Biodata Pribadi</span>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Lengkap Sesuai KTP <span class="text-red-500">*</span></label>
                            <input type="text" id="in_jb_nama" name="nama_pemohon" required placeholder="Nama lengkap sesuai KTP" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Jenis Kelamin</label>
                                <select id="in_jb_jk" name="data_tambahan[jenis_kelamin]" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition bg-white">
                                    <option value="Pria">Pria</option>
                                    <option value="Wanita">Wanita</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Status</label>
                                <select id="in_jb_status" name="data_tambahan[status_nikah]" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition bg-white">
                                    <option value="Belum Menikah">Belum Menikah</option>
                                    <option value="Menikah">Menikah</option>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-2xs font-bold uppercase text-primary">Tempat &amp; Tanggal Lahir (Tgl / Bln / Thn)</label>
                            <div class="grid grid-cols-12 gap-2">
                                <div class="col-span-6">
                                    <input type="text" id="in_jb_tempat" name="data_tambahan[tempat_lahir]" placeholder="Tempat Lahir" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                                </div>
                                <div class="col-span-6">
                                    <input type="date" id="in_jb_tgl" name="data_tambahan[tanggal_lahir]" class="w-full px-2.5 py-2 rounded-xl border border-outline-variant text-xs">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Alamat Rumah (Jalan) <span class="text-red-500">*</span></label>
                            <input type="text" id="in_jb_jalan" name="alamat" required placeholder="Jl. Ikan Arwana" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">RT / RW</label>
                                <input type="text" id="in_jb_rtrw" name="data_tambahan[rt_rw]" placeholder="004 / 002" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Kelurahan</label>
                                <input type="text" id="in_jb_kel" name="data_tambahan[kelurahan]" placeholder="Namosain" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Kecamatan</label>
                                <input type="text" id="in_jb_kec" name="data_tambahan[kecamatan]" placeholder="Alak" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Kota &amp; Provinsi</label>
                                <input type="text" id="in_jb_kotaprov" name="data_tambahan[kota_prov]" placeholder="Kupang, NTT" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Golongan Darah</label>
                                <select id="in_jb_goldar" name="data_tambahan[golongan_darah]" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition bg-white">
                                    <option value="O">O</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="AB">AB</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">No. Handphone <span class="text-red-500">*</span></label>
                                <input type="tel" id="in_jb_hp" name="no_wa" required placeholder="081234567890" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Pendidikan Terakhir</label>
                            <input type="text" id="in_jb_pendidikan" name="data_tambahan[pendidikan]" placeholder="SMA / S1 / dll" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                        </div>
                    </div>

                    <!-- 2. Keanggotaan Gereja -->
                    <div class="pt-2 border-t border-outline-variant/30 space-y-3">
                        <span class="text-xs font-bold text-secondary uppercase tracking-wider block">➢ 2. Keanggotaan Gereja</span>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Tanggal Baptis (Tgl/Bln/Thn)</label>
                                <input type="text" id="in_jb_tgl_baptis" name="data_tambahan[tgl_baptis]" placeholder="12 Mei 2018" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Di Gereja</label>
                                <input type="text" id="in_jb_gereja_baptis" name="data_tambahan[gereja_baptis]" placeholder="Nama Gereja" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Perpindahan Anggota Jemaat Sebelum Disini</label>
                            <input type="text" id="in_jb_pindah" name="data_tambahan[riwayat_pindah]" placeholder="Dari Jemaat: ... Alamat: ... Ke Jemaat: GBI Shekinah Glory" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                        </div>
                    </div>

                    <!-- 3. Data Keluarga -->
                    <div class="pt-2 border-t border-outline-variant/30 space-y-3">
                        <span class="text-xs font-bold text-secondary uppercase tracking-wider block">➢ 3. Data Keluarga</span>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Ayah</label>
                                <input type="text" id="in_jb_ayah" name="data_tambahan[nama_ayah]" placeholder="Ayah" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Ibu</label>
                                <input type="text" id="in_jb_ibu" name="data_tambahan[nama_ibu]" placeholder="Ibu" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                            </div>
                        </div>
                        <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/30 space-y-2">
                            <span class="text-3xs font-bold uppercase text-gray-700 block">Jika menikah atau pernah menikah:</span>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" id="in_jb_pasangan" name="data_tambahan[nama_pasangan]" placeholder="Nama Suami / Istri" class="w-full px-2.5 py-1.5 rounded-lg border border-outline-variant text-xs">
                                <input type="text" id="in_jb_tgl_nikah" name="data_tambahan[tgl_nikah]" placeholder="Tanggal Menikah" class="w-full px-2.5 py-1.5 rounded-lg border border-outline-variant text-xs">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" id="in_jb_pendeta" name="data_tambahan[pendeta_nikah]" placeholder="Oleh Pendeta" class="w-full px-2.5 py-1.5 rounded-lg border border-outline-variant text-xs">
                                <input type="text" id="in_jb_gereja_nikah" name="data_tambahan[gereja_nikah]" placeholder="Di Gereja" class="w-full px-2.5 py-1.5 rounded-lg border border-outline-variant text-xs">
                            </div>
                        </div>
                        <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/30 space-y-2">
                            <span class="text-3xs font-bold uppercase text-gray-700 block">Jika dikaruniai anak:</span>
                            <input type="text" id="in_jb_anak" name="data_tambahan[data_anak]" placeholder="Nama Anak, L/P, TTL, Alamat" class="w-full px-2.5 py-1.5 rounded-lg border border-outline-variant text-xs">
                        </div>
                    </div>

                    <!-- 4. Pengalaman dan Aktivitas -->
                    <div class="pt-2 border-t border-outline-variant/30 space-y-3">
                        <span class="text-xs font-bold text-secondary uppercase tracking-wider block">➢ 4. Pengalaman dan Aktivitas</span>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Pengalaman Aktivitas Gerejawi</label>
                            <input type="text" id="in_jb_akt_gereja" name="data_tambahan[akt_gereja]" placeholder="Bidang Pelayanan, Jabatan/Tugas, Periode" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Aktivitas Diluar Gereja (Sosial &amp; Masyarakat)</label>
                            <input type="text" id="in_jb_akt_luar" name="data_tambahan[akt_sosial]" placeholder="Nama Organisasi, Kegiatan, Jabatan, Periode" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                        </div>
                    </div>

                    <!-- Slot Upload Terpisah: JEMAAT BARU -->
                    <div class="pt-3 border-t border-outline-variant/20 space-y-3">
                        <span class="text-2xs font-bold text-secondary uppercase tracking-wider block">
                            Berkas Persyaratan (Upload Terpisah Sesuai Form)
                        </span>

                        <div class="space-y-2.5">
                            <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/40 space-y-1">
                                <label class="block text-2xs font-bold text-primary">1. Fotocopy KTP</label>
                                <input type="file" name="file_ktp" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full text-xs text-gray-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-secondary file:text-white hover:file:bg-secondary/90 transition cursor-pointer">
                            </div>
                            <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/40 space-y-1">
                                <label class="block text-2xs font-bold text-primary">2. Fotocopy Kartu Keluarga (KK)</label>
                                <input type="file" name="file_kk" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full text-xs text-gray-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-secondary file:text-white hover:file:bg-secondary/90 transition cursor-pointer">
                            </div>
                            <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/40 space-y-1">
                                <label class="block text-2xs font-bold text-primary">3. Fotocopy Akta Baptis</label>
                                <input type="file" name="file_akta_baptis" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full text-xs text-gray-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-secondary file:text-white hover:file:bg-secondary/90 transition cursor-pointer">
                            </div>
                            <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/40 space-y-1">
                                <label class="block text-2xs font-bold text-primary">4. Fotocopy Akta Kelahiran</label>
                                <input type="file" name="file_akta_lahir" accept=".pdf,.jpg,.jpeg,.png,.docx" class="w-full text-xs text-gray-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-secondary file:text-white hover:file:bg-secondary/90 transition cursor-pointer">
                            </div>
                            <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/40 space-y-1">
                                <label class="block text-2xs font-bold text-primary">5. Pas Foto 4x6 Warna</label>
                                <input type="file" name="file_pas_foto" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-gray-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-secondary file:text-white hover:file:bg-secondary/90 transition cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Button Form -->
                <div class="pt-4 border-t border-outline-variant/20 flex flex-col gap-2.5">
                    <button type="submit" class="w-full py-3 rounded-xl bg-secondary text-white font-bold text-xs sm:text-sm shadow-md hover:bg-secondary/90 transition flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">send</span>
                        <span>Kirim Pendaftaran Online</span>
                    </button>
                    <button type="button" onclick="printDocTemplate()" class="w-full py-2.5 rounded-xl bg-white border border-outline-variant text-primary font-bold text-xs hover:bg-surface-container transition flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-secondary">print</span>
                        <span>Cetak / Unduh Blanko Template Ini (PDF)</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- ======================================================== -->
        <!-- KOLOM KANAN: LIVE PRATINJAU DOKUMEN TEMPLATE RESMI (7 COLS)-->
        <!-- ======================================================== -->
        <div id="sectionDocPreview" class="lg:col-span-7 space-y-3 lg:sticky lg:top-24">
            <!-- Toolbar Mini Atas Dokumen -->
            <div class="flex items-center justify-between px-2 text-xs text-on-surface-variant">
                <span class="font-bold text-primary flex items-center gap-1.5 font-sans">
                    <span class="material-symbols-outlined text-secondary text-[18px]">description</span>
                    <span>Pratinjau Dokumen Fisik Blanko</span>
                </span>
                <button type="button" onclick="printDocTemplate()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-outline-variant/40 hover:bg-surface-container text-secondary font-bold text-2xs shadow-2xs transition font-sans">
                    <span class="material-symbols-outlined text-[15px]">print</span>
                    <span>Cetak Blanko Terisi</span>
                </button>
            </div>

            <!-- CONTAINER DOKUMEN KERTAS A4 -->
            <div id="printableDocWrapper" class="bg-white rounded-2xl shadow-xl border border-gray-300 p-6 sm:p-10 relative overflow-hidden select-none doc-times-font doc-a4-sheet" style="font-family: 'Times New Roman', Times, serif;">
                
                <!-- WATERMARK LOGO GBI DI TENGAH KERTAS (BERWARNA) -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.10] select-none z-0">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Watermark Gereja Bethel Indonesia" class="w-96 h-96 object-contain">
                </div>

                <!-- KONTEN DOKUMEN -->
                <div class="relative z-10 space-y-4 text-black">
                    
                    <!-- KOP SURAT RESMI GBI SHEKINAH GLORY -->
                    <div class="flex items-start gap-4 pb-2 border-b-2 border-[#b91c1c]">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo GBI" class="w-14 h-14 sm:w-16 sm:h-16 object-contain shrink-0 mt-0.5">
                        <div class="text-center flex-1">
                            <h2 class="text-[#b91c1c] font-black tracking-wider text-base sm:text-lg uppercase leading-tight">
                                GEREJA BETHEL INDONESIA
                            </h2>
                            <h3 class="text-[#1e3a8a] font-black tracking-wide text-sm sm:text-base uppercase leading-tight mt-0.5">
                                JEMAAT SHEKINAH GLORY
                            </h3>
                            <p class="text-[9px] sm:text-[10px] text-gray-800 leading-tight mt-1">
                                Badan Hukum Gereja : SK Dirjen Bimas Kristen/Protestan Dep. Agama R.I No. 41 Tahun 1972
                            </p>
                            <p class="text-[9px] sm:text-[10px] text-gray-800 leading-tight">
                                SK Dirjen Bimas (Kristen) Protestan Dep. Agama R.I. Nomor 211 Tanggal, 25 November 1989
                            </p>
                            <p class="text-[9px] sm:text-[10px] text-gray-800 leading-tight font-bold mt-0.5">
                                Jalan Ikan Arwana Namosain - Kupang Telpon : 085 333 650 621
                            </p>
                        </div>
                    </div>
                    <!-- Garis Ganda Bawah Kop Surat -->
                    <div class="h-0.5 bg-[#1e3a8a] -mt-3 mb-3"></div>

                    <!-- =================================================== -->
                    <!-- TEMPLATE 1: FORMULIR PENGAJUAN BAPTISAN (Roma 6:4)  -->
                    <!-- =================================================== -->
                    @if($layanan === 'baptis')
                    <div class="text-center my-3">
                        <div class="inline-block border border-[#1e3a8a] px-6 py-1 bg-white shadow-2xs">
                            <h4 class="font-bold text-xs sm:text-sm text-gray-900 tracking-wider uppercase">
                                FORM PENGAJUAN BAPTISAN
                            </h4>
                        </div>
                        <div class="text-xs italic text-gray-700 mt-1">
                            (Roma 6:4)
                        </div>
                    </div>

                    <p class="text-[11px] text-gray-700 italic mb-2">
                        *Nomor Surat (Di isi petugas) : <span class="font-mono text-gray-500 font-normal">...................................................</span>
                    </p>

                    <div class="space-y-2 text-xs text-gray-900 leading-relaxed">
                        <div class="font-bold text-[12px] flex items-center gap-1.5 underline">
                            <span>➢</span>
                            <span>Biodata Pribadi :</span>
                        </div>

                        <div class="pl-4 space-y-1.5">
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">1. Nama Lengkap</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_bap_nama" class="font-bold text-gray-900 uppercase">...........................................................................</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">2. Jenis Kelamin</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_bap_jk" class="font-semibold text-gray-900">Laki-laki</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">3. TTL/Umur</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_bap_ttl" class="font-semibold text-gray-900">...........................................................................</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">4. Alamat</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_bap_alamat" class="font-semibold text-gray-900">...........................................................................</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">5. No.Hp</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_bap_hp" class="font-mono font-semibold text-gray-900">...........................................................................</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-start">
                                <span class="col-span-5 sm:col-span-4 text-gray-800 mt-0.5">6. Nama Orang Tua</span>
                                <div class="col-span-7 sm:col-span-8 space-y-1">
                                    <div class="flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                        <span class="text-gray-700 w-16 text-[11px]">❖ Ayah</span>
                                        <span class="mr-1">:</span>
                                        <span id="pv_bap_ayah" class="font-semibold text-gray-900 uppercase">.............................................................</span>
                                    </div>
                                    <div class="flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                        <span class="text-gray-700 w-16 text-[11px]">❖ Ibu</span>
                                        <span class="mr-1">:</span>
                                        <span id="pv_bap_ibu" class="font-semibold text-gray-900 uppercase">.............................................................</span>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">7. Oikos Wilayah Pelayanan</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_bap_wilayah" class="font-semibold text-gray-900">...........................................................................</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-10 flex justify-end text-xs text-gray-900">
                        <div class="text-center space-y-0.5 w-60">
                            <p class="text-[11px]">Kupang, <span class="border-b border-dotted border-gray-400 px-3">{{ now()->translatedFormat('d F') }}</span> {{ date('Y') }}</p>
                            <p class="font-bold tracking-wider text-[11px] mt-1">MENGETAHUI</p>
                            <p class="font-bold tracking-wider text-[11px]">GEMBALA SIDANG</p>
                            <p class="font-bold tracking-wider text-[11px]">GBI SHEKINAH GLORY</p>
                            <div class="h-16 flex items-center justify-center">
                                <span class="text-[10px] text-gray-400 italic">( Tanda Tangan &amp; Cap )</span>
                            </div>
                            <p class="font-bold underline text-xs tracking-wide">Pdt. JUSUF LABAGAI</p>
                        </div>
                    </div>

                    <!-- =================================================== -->
                    <!-- TEMPLATE 2: FORMULIR PENYERAHAN ANAK (Markus 10:14) -->
                    <!-- =================================================== -->
                    @elseif($layanan === 'penyerahan_anak')
                    <div class="text-center my-3">
                        <div class="inline-block border border-[#1e3a8a] px-6 py-1 bg-white shadow-2xs">
                            <h4 class="font-bold text-xs sm:text-sm text-gray-900 tracking-wider uppercase">
                                FORM PENGAJUAN PENYERAHAN ANAK
                            </h4>
                        </div>
                        <div class="text-xs italic text-gray-700 mt-1">
                            (Markus 10:14-16)
                        </div>
                    </div>

                    <p class="text-[11px] text-gray-700 italic mb-2">
                        *Nomor Surat (Di isi petugas) : <span class="font-mono text-gray-500 font-normal">...................................................</span>
                    </p>

                    <div class="space-y-2 text-xs text-gray-900 leading-relaxed">
                        <div class="font-bold text-[12px] flex items-center gap-1.5 underline">
                            <span>➢</span>
                            <span>Biodata Anak :</span>
                        </div>
                        <div class="pl-4 space-y-1.5">
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">1. Nama Lengkap</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_pa_nama" class="font-bold text-gray-900 uppercase">...........................................................................</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">2. TTL/Usia</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_pa_ttl" class="font-semibold text-gray-900">...........................................................................</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">3. Jenis kelamin</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_pa_jk" class="font-semibold text-gray-900">Laki-laki</span>
                                </div>
                            </div>
                        </div>

                        <div class="font-bold text-[12px] flex items-center gap-1.5 underline pt-1">
                            <span>➢</span>
                            <span>Biodata Orang Tua :</span>
                        </div>
                        <div class="pl-4 space-y-1.5">
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">1. Nama Lengkap Ayah</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_pa_ayah" class="font-semibold text-gray-900 uppercase">...........................................................................</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">2. Nama Lengkap Ibu</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_pa_ibu" class="font-semibold text-gray-900 uppercase">...........................................................................</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">3. Alamat</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_pa_alamat" class="font-semibold text-gray-900">...........................................................................</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">4. Oikos Wilayah Pelayanan</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_pa_wilayah" class="font-semibold text-gray-900">...........................................................................</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">5. No.Hp</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_pa_hp" class="font-mono font-semibold text-gray-900">...........................................................................</span>
                                </div>
                            </div>
                        </div>

                        <div class="font-bold text-[12px] flex items-center gap-1.5 underline pt-1">
                            <span>➢</span>
                            <span>Biodata Orang Tua Saksi :</span>
                        </div>
                        <div class="pl-4 space-y-1.5">
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">1. Nama Saksi .1</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_saksi_1" class="font-semibold text-gray-900">...........................................................................</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">2. Nama Saksi .2</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_saksi_2" class="font-semibold text-gray-900">...........................................................................</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">3. Jenis Kelamin</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_saksi_jk" class="font-semibold text-gray-900">...........................................................................</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">4. Alamat</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_saksi_alamat" class="font-semibold text-gray-900">...........................................................................</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">5. Asal Gereja</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_saksi_gereja" class="font-semibold text-gray-900">...........................................................................</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 sm:col-span-4 text-gray-800">6. No.Hp</span>
                                <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                    <span class="mr-1.5">:</span>
                                    <span id="pv_saksi_hp" class="font-mono font-semibold text-gray-900">...........................................................................</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 flex justify-end text-xs text-gray-900">
                        <div class="text-center space-y-0.5 w-64">
                            <p class="text-[11px]">Kupang, <span class="border-b border-dotted border-gray-400 px-3">{{ now()->translatedFormat('d F') }}</span> {{ date('Y') }}</p>
                            <p class="font-bold tracking-wider text-[11px] mt-1">MENGETAHUI</p>
                            <p class="font-bold tracking-wider text-[11px]">GEMBALA SIDANG</p>
                            <p class="font-bold tracking-wider text-[11px]">GBI SHEKINAH GLORY NAMOSAIN</p>
                            <div class="h-14 flex items-center justify-center">
                                <span class="text-[10px] text-gray-400 italic font-mono">( Tanda Tangan &amp; Cap )</span>
                            </div>
                            <p class="font-bold underline text-xs tracking-wide">Pdt.JUSUF LABAGAI</p>
                            <p class="text-[10px] font-mono">NIP : 1000702</p>
                        </div>
                    </div>

                    <!-- =================================================== -->
                    <!-- TEMPLATE 3: FORM PENGAJUAN PERNIKAHAN (Matius 19:6) -->
                    <!-- =================================================== -->
                    @elseif($layanan === 'nikah')
                    <!-- LEMBAR 1 -->
                    <div class="space-y-4 max-w-xl mx-auto">
                        <div class="text-center my-2">
                            <div class="inline-block border border-[#1e3a8a] px-6 py-1 bg-white shadow-2xs">
                                <h4 class="font-bold text-xs sm:text-sm text-gray-900 tracking-wider uppercase">
                                    FORM PENGAJUAN PERNIKAHAN
                                </h4>
                            </div>
                            <div class="text-xs italic text-gray-700 mt-1">
                                (Matius 19:6)
                            </div>
                        </div>

                        <div class="flex items-baseline gap-1 text-[11px] text-gray-700 italic mb-2">
                            <span class="shrink-0">*Nomor Surat (Di isi petugas)</span>
                            <span class="shrink-0 mr-1">:</span>
                            <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 font-mono text-gray-500 font-normal"></div>
                        </div>

                        <!-- Calon Pengantin Pria (1-13 Plek Ketiplek) -->
                        <div class="font-bold text-[12px] flex items-center gap-1.5 underline">
                            <span>➢</span>
                            <span>Biodata Calon Pengantin Pria :</span>
                        </div>
                        <div class="pl-2 space-y-1 text-xs text-gray-900 leading-relaxed">
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">1. Nama Lengkap</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_pria_nama" class="font-bold text-gray-900 uppercase break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">2. TTL/Usia</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_pria_ttl" class="font-semibold text-gray-900 break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">3. Alamat</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_pria_alamat" class="font-semibold text-gray-900 break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">4. Baptis/Sidi Dll/Pada Tanggal</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_pria_baptis" class="font-semibold text-gray-900 break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">5. Asal Gereja</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_pria_gereja" class="font-semibold text-gray-900 break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">6. No Hp</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_pria_hp" class="font-mono font-semibold text-gray-900 break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800 mt-0.5">7. Nama Orang Tua</span>
                                <div class="flex-1 space-y-1 min-w-0">
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-gray-700 w-16 shrink-0 text-[11px]">❖ Ayah</span>
                                        <span class="shrink-0 mr-1">:</span>
                                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                            <span id="pv_nk_pria_ayah" class="font-semibold uppercase break-words block">..................................................</span>
                                        </div>
                                    </div>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-gray-700 w-16 shrink-0 text-[11px]">❖ Ibu</span>
                                        <span class="shrink-0 mr-1">:</span>
                                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                            <span id="pv_nk_pria_ibu" class="font-semibold uppercase break-words block">..................................................</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">8. No Hp Ayah/Ibu</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_pria_ortu_hp" class="font-mono break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">9. Nama Saksi</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_pria_saksi" class="break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">10. TTL</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_pria_saksi_ttl" class="break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">11. Alamat</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_pria_saksi_alamat" class="break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">12. No Hp</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_pria_saksi_hp" class="font-mono break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">13. Asal Gereja</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_pria_saksi_gereja" class="break-words block">.............................................................</span>
                                </div>
                            </div>
                        </div>

                        <!-- Calon Pengantin Wanita (1-5 di Halaman 1 sesuai PDF) -->
                        <div class="font-bold text-[12px] flex items-center gap-1.5 underline pt-2">
                            <span>➢</span>
                            <span>Biodata Calon Pengantin Wanita :</span>
                        </div>
                        <div class="pl-2 space-y-1 text-xs text-gray-900 leading-relaxed">
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">1. Nama Lengkap</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_wanita_nama" class="font-bold text-gray-900 uppercase break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">2. TTL/Usia</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_wanita_ttl" class="font-semibold text-gray-900 break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">3. Alamat</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_wanita_alamat" class="font-semibold text-gray-900 break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">4. Baptis/Sidi Dll/Pada Tanggal</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_wanita_baptis" class="font-semibold text-gray-900 break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">5. Asal Gereja</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_wanita_gereja" class="font-semibold text-gray-900 break-words block">.............................................................</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PEMBATAS HALAMAN 2 UNTUK NIKAH (SESUAI DOKUMEN FISIK) -->
                    <div class="pt-6 border-t-2 border-dashed border-gray-400 my-4 text-center">
                        <span class="px-3 py-1 bg-gray-100 text-[10px] font-sans font-bold text-gray-600 rounded-full border border-gray-300">
                            --- Halaman 2 Formulir Pernikahan ---
                        </span>
                    </div>

                    <!-- LEMBAR 2 NIKAH -->
                    <div class="space-y-4 max-w-xl mx-auto pt-2">
                        <div class="pl-2 space-y-1 text-xs text-gray-900 leading-relaxed">
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">6. No Hp</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_wanita_hp" class="font-mono font-semibold text-gray-900 break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800 mt-0.5">7. Nama Orang Tua</span>
                                <div class="flex-1 space-y-1 min-w-0">
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-gray-700 w-16 shrink-0 text-[11px]">❖ Ayah</span>
                                        <span class="shrink-0 mr-1">:</span>
                                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                            <span id="pv_nk_wanita_ayah" class="font-semibold uppercase break-words block">..................................................</span>
                                        </div>
                                    </div>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-gray-700 w-16 shrink-0 text-[11px]">❖ Ibu</span>
                                        <span class="shrink-0 mr-1">:</span>
                                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                            <span id="pv_nk_wanita_ibu" class="font-semibold uppercase break-words block">..................................................</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">8. No.Hp Ayah/Ibu</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_wanita_ortu_hp" class="font-mono break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">9. Nama Saksi</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_wanita_saksi" class="break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">10. TTL</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_wanita_saksi_ttl" class="break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">11. Alamat</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_wanita_saksi_alamat" class="break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">12. No Hp</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_wanita_saksi_hp" class="font-mono break-words block">.............................................................</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 py-0.5">
                                <span class="w-44 sm:w-52 shrink-0 text-gray-800">13. Asal Gereja</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span id="pv_nk_wanita_saksi_gereja" class="break-words block">.............................................................</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tanda Tangan Gembala Sidang: DIPERBAIKI POSISI TENGAH SEIMBANG PERSIS SCAN ASLI -->
                        <div class="pt-8 flex justify-center text-xs text-gray-900">
                            <div class="text-center space-y-0.5 w-72">
                                <p class="text-[11px]">Kupang, <span class="border-b border-dotted border-gray-400 px-3">{{ now()->translatedFormat('d F') }}</span> {{ date('Y') }}</p>
                                <p class="font-bold tracking-wider text-[11px] mt-1">MENGETAHUI</p>
                                <p class="font-bold tracking-wider text-[11px]">GEMBALA SIDANG</p>
                                <p class="font-bold tracking-wider text-[11px]">GBI SHEKINAH GLORY NAMOSAIN</p>
                                <div class="h-14 flex items-center justify-center">
                                    <span class="text-[10px] text-gray-400 italic font-mono">( Tanda Tangan &amp; Cap )</span>
                                </div>
                                <p class="font-bold underline text-xs tracking-wide">Pdt. JUSUF LABAGAI</p>
                                <p class="text-[10px] font-mono">NIP : 1000702</p>
                            </div>
                        </div>

                        <!-- Catatan Persis Scan Form Nikah -->
                        <div class="p-3 rounded border border-gray-400 bg-gray-50/50 text-[10px] leading-relaxed mt-4">
                            <strong class="italic block mb-1 underline">* Catatan : untuk kedua calon mempelai melampirkan :</strong>
                            <ol class="list-decimal pl-4 space-y-0.5">
                                <li>Fotocopy KTP 1.Lembar</li>
                                <li>Foto copy Surat Baptis/Sidi 1.Lembar</li>
                                <li>Foto copy KK 1.Lembar</li>
                                <li>Foto Copy Akte Kelahiran 1.Lembar</li>
                                <li>Foto gandeng 1.Lembar</li>
                            </ol>
                        </div>
                    </div>

                    <!-- =================================================== -->
                    <!-- TEMPLATE 4: FORM ANGGOTA JEMAAT BARU                -->
                    <!-- =================================================== -->
                    @else
                    <!-- LEMBAR 1 JEMAAT BARU -->
                    <div class="space-y-2 text-[11px] text-gray-900 leading-relaxed">
                        <!-- Box Judul & Petugas Sesuai Blanko Asli -->
                        <div class="flex items-stretch justify-between gap-2 border border-black p-2 my-2 bg-gray-50/40">
                            <div class="border-2 border-black px-4 py-2 font-bold text-xs uppercase tracking-wider flex items-center justify-center">
                                FORM ANGGOTA JEMAAT BARU
                            </div>
                            <div class="border border-black p-1.5 text-[10px] space-y-0.5 text-left w-52 bg-white">
                                <span class="font-bold block italic underline">Diisi Oleh Petugas</span>
                                <div>Data Nomor : <span class="font-mono text-gray-500">........................</span></div>
                                <div>Tanggal Diinput : <span class="font-mono text-gray-500">...................</span></div>
                            </div>
                        </div>

                        <!-- ➢ Biodata Pribadi -->
                        <div class="font-bold text-[12px] flex items-center gap-1.5 underline">
                            <span>➢</span>
                            <span>Biodata Pribadi :</span>
                        </div>
                        
                        <div class="pl-2 space-y-1.5">
                            <div>
                                <span class="text-gray-800 block text-[10px]">[Nama Lengkap Sesuai KTP]</span>
                                <div class="border border-black px-2 py-1 font-bold text-xs bg-white uppercase">
                                    <span id="pv_jb_nama">...........................................................................................................</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-[10px]">
                                <div class="border border-black p-1 flex items-center justify-between">
                                    <span>[Jenis Kelamin]:</span>
                                    <span class="font-bold text-primary" id="pv_jb_jk">Pria</span>
                                </div>
                                <div class="border border-black p-1 flex items-center justify-between">
                                    <span>[Status]:</span>
                                    <span class="font-bold text-primary" id="pv_jb_status">Belum Menikah</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-1 text-[10px] items-center">
                                <span class="col-span-3">[Tempat Lahir]:</span>
                                <div class="col-span-4 border border-black px-2 py-0.5 font-semibold" id="pv_jb_tempat">-</div>
                                <span class="col-span-2 text-center">[Tgl/Bln/Thn]:</span>
                                <div class="col-span-3 border border-black px-2 py-0.5 font-semibold text-center" id="pv_jb_ttl">-</div>
                            </div>

                            <div>
                                <span class="text-gray-800 block text-[10px]">[Alamat Rumah]</span>
                                <div class="border border-black px-2 py-1 font-semibold text-[11px] flex items-center gap-2">
                                    <span>Jalan:</span>
                                    <span id="pv_jb_jalan" class="flex-1 font-normal">........................................................................................</span>
                                    <span>RT.</span>
                                    <span id="pv_jb_rt" class="w-10 border-b border-black font-semibold text-center">-</span>
                                    <span>RW.</span>
                                    <span id="pv_jb_rw" class="w-10 border-b border-black font-semibold text-center">-</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-[10px]">
                                <div class="border border-black p-1 flex items-center gap-1">
                                    <span>[Kelurahan]:</span>
                                    <span id="pv_jb_kel" class="font-semibold">-</span>
                                </div>
                                <div class="border border-black p-1 flex items-center gap-1">
                                    <span>[Kecamatan]:</span>
                                    <span id="pv_jb_kec" class="font-semibold">-</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-[10px]">
                                <div class="border border-black p-1 flex items-center gap-1">
                                    <span>[Kota]:</span>
                                    <span id="pv_jb_kota" class="font-semibold">-</span>
                                </div>
                                <div class="border border-black p-1 flex items-center gap-1">
                                    <span>[Provinsi]:</span>
                                    <span id="pv_jb_prov" class="font-semibold">-</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-[10px]">
                                <div class="border border-black p-1 flex items-center justify-between">
                                    <span>[Golongan Darah]:</span>
                                    <span class="font-bold text-red-700 font-mono" id="pv_jb_goldar">O</span>
                                </div>
                                <div class="border border-black p-1 flex items-center justify-between">
                                    <span>[No.Handphone]:</span>
                                    <span class="font-mono font-bold" id="pv_jb_hp">-</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-1 text-[10px] items-center">
                                <span class="col-span-4">[Pendidikan Terakhir]:</span>
                                <div class="col-span-8 border border-black px-2 py-0.5 font-semibold" id="pv_jb_pendidikan">-</div>
                            </div>
                        </div>

                        <!-- ➢ Keanggotaan Gereja -->
                        <div class="font-bold text-[12px] flex items-center gap-1.5 underline pt-1">
                            <span>➢</span>
                            <span>Keanggotaan Gereja :</span>
                        </div>
                        <div class="pl-2 space-y-1">
                            <div class="border border-black p-1 text-[10px]">
                                <span class="font-semibold block mb-0.5">Baptisan:</span>
                                <div class="flex items-center gap-2">
                                    <span>Tanggal:</span>
                                    <span id="pv_jb_tgl_baptis" class="font-semibold border-b border-black px-2">-</span>
                                    <span>Di Gereja:</span>
                                    <span id="pv_jb_gereja_baptis" class="font-semibold border-b border-black flex-1">-</span>
                                </div>
                            </div>
                            <div class="text-[10px]">
                                <span class="font-semibold block mb-0.5">Perpindahan Anggota Jemaat sebelum disini:</span>
                                <table class="w-full border-collapse border border-black text-[9px]">
                                    <thead>
                                        <tr class="bg-gray-100 text-center">
                                            <th class="border border-black p-1">Tanggal</th>
                                            <th class="border border-black p-1">Dari Jemaat</th>
                                            <th class="border border-black p-1">Alamat</th>
                                            <th class="border border-black p-1">Ke jemaat</th>
                                            <th class="border border-black p-1">Alamat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border border-black p-1 text-center font-mono">-</td>
                                            <td class="border border-black p-1" id="pv_jb_pindah_dari">-</td>
                                            <td class="border border-black p-1">-</td>
                                            <td class="border border-black p-1 font-semibold">GBI Shekinah Glory</td>
                                            <td class="border border-black p-1">Namosain</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-black p-1 h-4"></td>
                                            <td class="border border-black p-1"></td>
                                            <td class="border border-black p-1"></td>
                                            <td class="border border-black p-1"></td>
                                            <td class="border border-black p-1"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- ➢ Data Keluarga -->
                        <div class="font-bold text-[12px] flex items-center gap-1.5 underline pt-1">
                            <span>➢</span>
                            <span>Data Keluarga:</span>
                        </div>
                        <div class="pl-2 space-y-1 text-[10px]">
                            <div class="grid grid-cols-2 gap-2">
                                <div class="border border-black p-1">
                                    <span class="text-gray-600 block text-[9px]">[Nama ayah]:</span>
                                    <span class="font-semibold uppercase" id="pv_jb_ayah">-</span>
                                </div>
                                <div class="border border-black p-1">
                                    <span class="text-gray-600 block text-[9px]">[Nama Ibu]:</span>
                                    <span class="font-semibold uppercase" id="pv_jb_ibu">-</span>
                                </div>
                            </div>
                            <div class="border border-black p-1 space-y-0.5">
                                <span class="italic text-[9px] font-semibold">[Jika menikah atau pernah menikah]</span>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>[Nama suami/istri]: <span class="font-semibold" id="pv_jb_pasangan">-</span></div>
                                    <div>[Tanggal Menikah]: <span class="font-semibold" id="pv_jb_tgl_nikah">-</span></div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 pt-0.5">
                                    <div>[Oleh pendeta]: <span class="font-semibold" id="pv_jb_pendeta">-</span></div>
                                    <div>[Di Gereja]: <span class="font-semibold" id="pv_jb_gereja_nikah">-</span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PEMBATAS HALAMAN 2 UNTUK ANGGOTA JEMAAT BARU -->
                    <div class="pt-6 border-t-2 border-dashed border-gray-400 my-4 text-center">
                        <span class="px-3 py-1 bg-gray-100 text-[10px] font-sans font-bold text-gray-600 rounded-full border border-gray-300">
                            --- Halaman 2 Formulir Anggota Jemaat Baru ---
                        </span>
                    </div>

                    <!-- LEMBAR 2 JEMAAT BARU -->
                    <div class="space-y-3 text-[10px] text-gray-900 leading-relaxed pt-2">
                        <!-- Tabel Jika Dikaruniai Anak -->
                        <div>
                            <span class="font-bold block mb-1">[Jika dikaruniai anak]</span>
                            <table class="w-full border-collapse border border-black text-[9px]">
                                <thead>
                                    <tr class="bg-gray-100 text-center">
                                        <th class="border border-black p-1 w-12">Anak ke</th>
                                        <th class="border border-black p-1">Nama Anak</th>
                                        <th class="border border-black p-1 w-12">L/P</th>
                                        <th class="border border-black p-1">TTL</th>
                                        <th class="border border-black p-1">ALAMAT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-black p-1 text-center font-mono">1</td>
                                        <td class="border border-black p-1" id="pv_jb_anak_1">-</td>
                                        <td class="border border-black p-1 text-center">-</td>
                                        <td class="border border-black p-1">-</td>
                                        <td class="border border-black p-1">-</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-black p-1 text-center font-mono">2</td>
                                        <td class="border border-black p-1"></td>
                                        <td class="border border-black p-1"></td>
                                        <td class="border border-black p-1"></td>
                                        <td class="border border-black p-1"></td>
                                    </tr>
                                    <tr>
                                        <td class="border border-black p-1 text-center font-mono">3</td>
                                        <td class="border border-black p-1"></td>
                                        <td class="border border-black p-1"></td>
                                        <td class="border border-black p-1"></td>
                                        <td class="border border-black p-1"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- ➢ Pengalaman dan aktivitas -->
                        <div class="font-bold text-[12px] flex items-center gap-1.5 underline pt-1">
                            <span>➢</span>
                            <span>Pengalaman dan aktivitas:</span>
                        </div>
                        <div>
                            <span class="font-semibold block mb-0.5">[Pengalaman aktivitas Gerejawi]</span>
                            <table class="w-full border-collapse border border-black text-[9px]">
                                <thead>
                                    <tr class="bg-gray-100 text-center">
                                        <th class="border border-black p-1">Bidang Pelayanan</th>
                                        <th class="border border-black p-1">Jabatan/Keterangan Tugas</th>
                                        <th class="border border-black p-1">Periode</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-black p-1" id="pv_jb_akt_gereja_bidang">-</td>
                                        <td class="border border-black p-1">-</td>
                                        <td class="border border-black p-1 text-center">-</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-black p-1 h-3.5"></td>
                                        <td class="border border-black p-1"></td>
                                        <td class="border border-black p-1"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div>
                            <span class="font-semibold block mb-0.5">[Aktivitas diluar kegiatan Gereja (sosial &amp; masyarakat)]</span>
                            <table class="w-full border-collapse border border-black text-[9px]">
                                <thead>
                                    <tr class="bg-gray-100 text-center">
                                        <th class="border border-black p-1">Nama Organisasi</th>
                                        <th class="border border-black p-1">Kegiatan</th>
                                        <th class="border border-black p-1">Jabatan</th>
                                        <th class="border border-black p-1">Periode</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-black p-1" id="pv_jb_akt_luar_org">-</td>
                                        <td class="border border-black p-1">-</td>
                                        <td class="border border-black p-1">-</td>
                                        <td class="border border-black p-1 text-center">-</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-black p-1 h-3.5"></td>
                                        <td class="border border-black p-1"></td>
                                        <td class="border border-black p-1"></td>
                                        <td class="border border-black p-1"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Kotak Pas Foto 4x6 Warna & Tanda Tangan Persis Form Scan Asli -->
                        <div class="grid grid-cols-12 gap-4 pt-4 items-end">
                            <div class="col-span-4">
                                <span class="font-bold block text-center mb-1 text-[9px]">Pas Foto 4x6 Warna</span>
                                <div class="w-24 h-32 border-2 border-black rounded mx-auto flex flex-col items-center justify-center text-center p-2 text-[9px] text-gray-500 bg-white">
                                    <span class="material-symbols-outlined text-[24px] text-gray-400 mb-1">image</span>
                                    <span>(Tempel Foto)</span>
                                </div>
                            </div>

                            <div class="col-span-4">
                                <span class="font-bold block text-center mb-1 text-[9px]">Tanda Tangan</span>
                                <div class="w-28 h-24 border border-black rounded mx-auto flex items-center justify-center text-[8px] text-gray-400">
                                    (Tanda Tangan)
                                </div>
                                <div class="mt-1 border border-black px-1.5 py-0.5 text-[9px]">
                                    <span>Nama Lengkap : </span>
                                    <span class="font-bold uppercase" id="pv_jb_nama_ttd">-</span>
                                </div>
                            </div>

                            <div class="col-span-4 text-center space-y-0.5">
                                <p class="text-[9px]">Kupang, <span class="border-b border-dotted border-gray-400 px-2">{{ now()->translatedFormat('d F') }}</span> {{ date('Y') }}</p>
                                <p class="font-bold text-[9px] tracking-wider">MENGETAHUI</p>
                                <p class="font-bold text-[9px]">GEMBALA SIDANG</p>
                                <p class="font-bold text-[9px]">GBI.SHEKINAH GLORY NAMOSAIN</p>
                                <div class="h-12 flex items-center justify-center">
                                    <span class="text-[8px] text-gray-400 italic">( Tanda Tangan &amp; Cap )</span>
                                </div>
                                <p class="font-bold underline text-[10px] tracking-wide">Pdt.JUSUF LABAGAI</p>
                                <p class="text-[8px] font-mono">NIP;1000702</p>
                            </div>
                        </div>

                        <div class="pt-2 text-[9px] text-gray-800 leading-tight border-t border-gray-400">
                            <strong>NB; Lampirkan FC. 1. KTP, 2. KK, 3. AKTA BAPTIS &amp; KELAHIRAN</strong>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>

<!-- ======================================================== -->
<!-- PRINT CSS KHUSUS CETAK DOKUMEN STANDAR A4                -->
<!-- ======================================================== -->
<style>
.doc-times-font {
    font-family: 'Times New Roman', Times, serif !important;
}
.doc-a4-sheet {
    width: 100%;
    max-width: 210mm;
    min-height: 297mm;
    box-sizing: border-box;
    margin: 0 auto;
}
@media print {
    body * {
        visibility: hidden;
    }
    header, footer, nav, .no-print, button, a, #sectionFormInput, .lg\\:hidden {
        display: none !important;
    }
    #printableDocWrapper, #printableDocWrapper * {
        visibility: visible;
        font-family: 'Times New Roman', Times, serif !important;
    }
    #printableDocWrapper {
        position: absolute;
        left: 0;
        top: 0;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 10mm 15mm !important;
        box-shadow: none !important;
        border: none !important;
    }
    @page {
        size: A4 portrait;
        margin: 10mm 12mm;
    }
}
</style>

<!-- ======================================================== -->
<!-- SCRIPT REAL-TIME DUA ARAH: INPUT MENYALUR KE TEMPLATE    -->
<!-- ======================================================== -->
<script>
function syncField(inputId, previewId, fallback = '-') {
    const input = document.getElementById(inputId);
    const prev = document.getElementById(previewId);
    if (input && prev) {
        input.addEventListener('input', function() {
            prev.innerText = this.value.trim() !== '' ? this.value : fallback;
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // 1. Sync Baptisan
    syncField('in_nama', 'pv_bap_nama', '...........................................................................');
    syncField('in_alamat', 'pv_bap_alamat', '...........................................................................');
    syncField('in_hp', 'pv_bap_hp', '...........................................................................');
    syncField('in_ayah', 'pv_bap_ayah', '.............................................................');
    syncField('in_ibu', 'pv_bap_ibu', '.............................................................');

    const inJk = document.getElementById('in_jk');
    if (inJk) {
        inJk.addEventListener('change', function() {
            const p = document.getElementById('pv_bap_jk');
            if (p) p.innerText = this.value;
        });
    }

    const inWilayah = document.getElementById('in_wilayah');
    if (inWilayah) {
        inWilayah.addEventListener('change', function() {
            const p = document.getElementById('pv_bap_wilayah');
            if (p) p.innerText = this.value || '...........................................................................';
        });
    }

    function updateBaptisTtl() {
        const tpt = document.getElementById('in_tempat_lahir');
        const tgl = document.getElementById('in_tgl_lahir');
        const umur = document.getElementById('in_umur');
        const prevTtl = document.getElementById('pv_bap_ttl');
        if (prevTtl) {
            let parts = [];
            if (tpt && tpt.value.trim() !== '') parts.push(tpt.value.trim());
            if (tgl && tgl.value) {
                const d = new Date(tgl.value);
                if (!isNaN(d.getTime())) {
                    parts.push(d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }));
                    if (umur && !umur.value) {
                        const diff = new Date().getFullYear() - d.getFullYear();
                        umur.placeholder = diff + ' Thn';
                    }
                }
            }
            let res = parts.join(', ');
            if (umur && umur.value.trim() !== '') {
                res += (res ? ' / ' : '') + umur.value.trim();
            }
            prevTtl.innerText = res || '...........................................................................';
        }
    }

    ['in_tempat_lahir', 'in_tgl_lahir', 'in_umur'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', updateBaptisTtl);
            el.addEventListener('change', updateBaptisTtl);
        }
    });

    // 2. Sync Penyerahan Anak
    syncField('in_anak_nama', 'pv_pa_nama', '...........................................................................');
    syncField('in_pa_ayah', 'pv_pa_ayah', '...........................................................................');
    syncField('in_pa_ibu', 'pv_pa_ibu', '...........................................................................');
    syncField('in_pa_alamat', 'pv_pa_alamat', '...........................................................................');
    syncField('in_pa_hp', 'pv_pa_hp', '...........................................................................');
    syncField('in_saksi_1', 'pv_saksi_1', '...........................................................................');
    syncField('in_saksi_2', 'pv_saksi_2', '...........................................................................');
    syncField('in_saksi_jk', 'pv_saksi_jk', '...........................................................................');
    syncField('in_saksi_gereja', 'pv_saksi_gereja', '...........................................................................');
    syncField('in_saksi_alamat', 'pv_saksi_alamat', '...........................................................................');
    syncField('in_saksi_hp', 'pv_saksi_hp', '...........................................................................');

    const inAnakJk = document.getElementById('in_anak_jk');
    if (inAnakJk) {
        inAnakJk.addEventListener('change', function() {
            const p = document.getElementById('pv_pa_jk');
            if (p) p.innerText = this.value;
        });
    }

    const inPaWilayah = document.getElementById('in_pa_wilayah');
    if (inPaWilayah) {
        inPaWilayah.addEventListener('change', function() {
            const p = document.getElementById('pv_pa_wilayah');
            if (p) p.innerText = this.value || '...........................................................................';
        });
    }

    function updateAnakTtl() {
        const tpt = document.getElementById('in_anak_tempat');
        const tgl = document.getElementById('in_anak_tgl');
        const usia = document.getElementById('in_anak_usia');
        const prevTtl = document.getElementById('pv_pa_ttl');
        if (prevTtl) {
            let parts = [];
            if (tpt && tpt.value.trim() !== '') parts.push(tpt.value.trim());
            if (tgl && tgl.value) {
                const d = new Date(tgl.value);
                if (!isNaN(d.getTime())) {
                    parts.push(d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }));
                }
            }
            let res = parts.join(', ');
            if (usia && usia.value.trim() !== '') {
                res += (res ? ' / ' : '') + usia.value.trim();
            }
            prevTtl.innerText = res || '...........................................................................';
        }
    }

    ['in_anak_tempat', 'in_anak_tgl', 'in_anak_usia'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', updateAnakTtl);
            el.addEventListener('change', updateAnakTtl);
        }
    });

    // 3. Sync Pengajuan Nikah (Pria & Wanita 13 Poin)
    syncField('in_nk_pria_nama', 'pv_nk_pria_nama', '.............................................................');
    syncField('in_nk_pria_alamat', 'pv_nk_pria_alamat', '.............................................................');
    syncField('in_nk_pria_baptis', 'pv_nk_pria_baptis', '.............................................................');
    syncField('in_nk_pria_gereja', 'pv_nk_pria_gereja', '.............................................................');
    syncField('in_nk_pria_hp', 'pv_nk_pria_hp', '.............................................................');
    syncField('in_nk_pria_ayah', 'pv_nk_pria_ayah', '..................................................');
    syncField('in_nk_pria_ibu', 'pv_nk_pria_ibu', '..................................................');
    syncField('in_nk_pria_ortu_hp', 'pv_nk_pria_ortu_hp', '.............................................................');
    syncField('in_nk_pria_saksi', 'pv_nk_pria_saksi', '.............................................................');
    syncField('in_nk_pria_saksi_ttl', 'pv_nk_pria_saksi_ttl', '.............................................................');
    syncField('in_nk_pria_saksi_alamat', 'pv_nk_pria_saksi_alamat', '.............................................................');
    syncField('in_nk_pria_saksi_hp', 'pv_nk_pria_saksi_hp', '.............................................................');
    syncField('in_nk_pria_saksi_gereja', 'pv_nk_pria_saksi_gereja', '.............................................................');

    function updatePriaTtl() {
        const tpt = document.getElementById('in_nk_pria_tempat');
        const tgl = document.getElementById('in_nk_pria_tgl');
        const usia = document.getElementById('in_nk_pria_usia');
        const prevTtl = document.getElementById('pv_nk_pria_ttl');
        if (prevTtl) {
            let parts = [];
            if (tpt && tpt.value.trim() !== '') parts.push(tpt.value.trim());
            if (tgl && tgl.value) {
                const d = new Date(tgl.value);
                if (!isNaN(d.getTime())) {
                    parts.push(d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }));
                }
            }
            let res = parts.join(', ');
            if (usia && usia.value.trim() !== '') {
                res += (res ? ' / ' : '') + usia.value.trim();
            }
            prevTtl.innerText = res || '.............................................................';
        }
    }
    ['in_nk_pria_tempat', 'in_nk_pria_tgl', 'in_nk_pria_usia'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', updatePriaTtl);
            el.addEventListener('change', updatePriaTtl);
        }
    });

    syncField('in_nk_wanita_nama', 'pv_nk_wanita_nama', '.............................................................');
    syncField('in_nk_wanita_alamat', 'pv_nk_wanita_alamat', '.............................................................');
    syncField('in_nk_wanita_baptis', 'pv_nk_wanita_baptis', '.............................................................');
    syncField('in_nk_wanita_gereja', 'pv_nk_wanita_gereja', '.............................................................');
    syncField('in_nk_wanita_hp', 'pv_nk_wanita_hp', '.............................................................');
    syncField('in_nk_wanita_ayah', 'pv_nk_wanita_ayah', '..................................................');
    syncField('in_nk_wanita_ibu', 'pv_nk_wanita_ibu', '..................................................');
    syncField('in_nk_wanita_ortu_hp', 'pv_nk_wanita_ortu_hp', '.............................................................');
    syncField('in_nk_wanita_saksi', 'pv_nk_wanita_saksi', '.............................................................');
    syncField('in_nk_wanita_saksi_ttl', 'pv_nk_wanita_saksi_ttl', '.............................................................');
    syncField('in_nk_wanita_saksi_alamat', 'pv_nk_wanita_saksi_alamat', '.............................................................');
    syncField('in_nk_wanita_saksi_hp', 'pv_nk_wanita_saksi_hp', '.............................................................');
    syncField('in_nk_wanita_saksi_gereja', 'pv_nk_wanita_saksi_gereja', '.............................................................');

    function updateWanitaTtl() {
        const tpt = document.getElementById('in_nk_wanita_tempat');
        const tgl = document.getElementById('in_nk_wanita_tgl');
        const usia = document.getElementById('in_nk_wanita_usia');
        const prevTtl = document.getElementById('pv_nk_wanita_ttl');
        if (prevTtl) {
            let parts = [];
            if (tpt && tpt.value.trim() !== '') parts.push(tpt.value.trim());
            if (tgl && tgl.value) {
                const d = new Date(tgl.value);
                if (!isNaN(d.getTime())) {
                    parts.push(d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }));
                }
            }
            let res = parts.join(', ');
            if (usia && usia.value.trim() !== '') {
                res += (res ? ' / ' : '') + usia.value.trim();
            }
            prevTtl.innerText = res || '.............................................................';
        }
    }
    ['in_nk_wanita_tempat', 'in_nk_wanita_tgl', 'in_nk_wanita_usia'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', updateWanitaTtl);
            el.addEventListener('change', updateWanitaTtl);
        }
    });

    // 4. Sync Anggota Jemaat Baru
    syncField('in_jb_nama', 'pv_jb_nama', '...........................................................................................................');
    syncField('in_jb_nama', 'pv_jb_nama_ttd', '-');
    syncField('in_jb_tempat', 'pv_jb_tempat', '-');
    syncField('in_jb_jalan', 'pv_jb_jalan', '........................................................................................');
    syncField('in_jb_rtrw', 'pv_jb_rt', '-');
    syncField('in_jb_kel', 'pv_jb_kel', '-');
    syncField('in_jb_kec', 'pv_jb_kec', '-');
    syncField('in_jb_hp', 'pv_jb_hp', '-');
    syncField('in_jb_pendidikan', 'pv_jb_pendidikan', '-');
    syncField('in_jb_tgl_baptis', 'pv_jb_tgl_baptis', '-');
    syncField('in_jb_gereja_baptis', 'pv_jb_gereja_baptis', '-');
    syncField('in_jb_pindah', 'pv_jb_pindah_dari', '-');
    syncField('in_jb_ayah', 'pv_jb_ayah', '-');
    syncField('in_jb_ibu', 'pv_jb_ibu', '-');
    syncField('in_jb_pasangan', 'pv_jb_pasangan', '-');
    syncField('in_jb_tgl_nikah', 'pv_jb_tgl_nikah', '-');
    syncField('in_jb_pendeta', 'pv_jb_pendeta', '-');
    syncField('in_jb_gereja_nikah', 'pv_jb_gereja_nikah', '-');
    syncField('in_jb_anak', 'pv_jb_anak_1', '-');
    syncField('in_jb_akt_gereja', 'pv_jb_akt_gereja_bidang', '-');
    syncField('in_jb_akt_luar', 'pv_jb_akt_luar_org', '-');

    const inJbKotaprov = document.getElementById('in_jb_kotaprov');
    if (inJbKotaprov) {
        inJbKotaprov.addEventListener('input', function() {
            const val = this.value.trim();
            const parts = val.split(',');
            document.getElementById('pv_jb_kota').innerText = parts[0] ? parts[0].trim() : '-';
            document.getElementById('pv_jb_prov').innerText = parts[1] ? parts[1].trim() : (parts[0] ? parts[0].trim() : '-');
        });
    }

    const inJbJk = document.getElementById('in_jb_jk');
    if (inJbJk) inJbJk.addEventListener('change', function() { document.getElementById('pv_jb_jk').innerText = this.value; });

    const inJbStatus = document.getElementById('in_jb_status');
    if (inJbStatus) inJbStatus.addEventListener('change', function() { document.getElementById('pv_jb_status').innerText = this.value; });

    const inJbGoldar = document.getElementById('in_jb_goldar');
    if (inJbGoldar) inJbGoldar.addEventListener('change', function() { document.getElementById('pv_jb_goldar').innerText = this.value; });

    const inJbTgl = document.getElementById('in_jb_tgl');
    if (inJbTgl) {
        inJbTgl.addEventListener('change', function() {
            if (this.value) {
                const d = new Date(this.value);
                document.getElementById('pv_jb_ttl').innerText = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'numeric', year: 'numeric' });
            }
        });
    }
});

function printDocTemplate() {
    window.print();
}

function setMobileTab(tab) {
    const secForm = document.getElementById('sectionFormInput');
    const secPrev = document.getElementById('sectionDocPreview');
    const btnForm = document.getElementById('btnTabForm');
    const btnPrev = document.getElementById('btnTabPreview');

    if (tab === 'form') {
        secForm.classList.remove('hidden');
        secPrev.classList.add('hidden');
        btnForm.className = 'flex-1 py-2 rounded-lg text-xs font-bold text-center bg-white text-primary shadow-xs transition';
        btnPrev.className = 'flex-1 py-2 rounded-lg text-xs font-bold text-center text-on-surface-variant hover:text-primary transition flex items-center justify-center gap-1';
    } else {
        secForm.classList.add('hidden');
        secPrev.classList.remove('hidden');
        btnPrev.className = 'flex-1 py-2 rounded-lg text-xs font-bold text-center bg-white text-primary shadow-xs transition flex items-center justify-center gap-1';
        btnForm.className = 'flex-1 py-2 rounded-lg text-xs font-bold text-center text-on-surface-variant hover:text-primary transition';
    }
}
</script>
@endsection
