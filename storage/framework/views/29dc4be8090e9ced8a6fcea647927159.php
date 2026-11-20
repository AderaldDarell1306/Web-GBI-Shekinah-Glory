<?php $__env->startSection('title', 'Pendaftaran Sakramen & Layanan Jemaat - GBI Shekinah Glory'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Header Page -->
    <div class="text-center max-w-3xl mx-auto mb-8" data-aos="fade-up">
        <span class="text-xs font-bold uppercase tracking-widest text-secondary mb-2 block">Layanan Pastoral &amp; Administrasi Gereja</span>
        <h1 class="font-serif-display text-3xl sm:text-4xl font-bold text-primary mb-2">Formulir Pendaftaran &amp; Pratinjau Dokumen</h1>
        <p class="text-xs sm:text-sm text-on-surface-variant">Lengkapi data Anda di bawah ini. Dokumen resmi blanko gereja akan terisi otomatis dengan format <strong>Times New Roman</strong> standar gereja dan dapat langsung dicetak atau diunduh.</p>
        <div class="w-16 h-1 bg-secondary mx-auto mt-4 rounded-full"></div>
    </div>

    <!-- Pilihan 4 Layanan Tabs (Surat Pindah Ditiadakan) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8" data-aos="fade-up">
        <!-- 1. Baptis -->
        <a href="<?php echo e(route('public.pendaftaran', ['layanan' => 'baptis'])); ?>" class="p-3.5 rounded-2xl border text-center transition flex flex-col items-center gap-1.5 <?php echo e($layanan === 'baptis' ? 'bg-primary text-white border-primary shadow-md scale-[1.02]' : 'bg-white text-on-surface hover:bg-surface-container border-outline-variant/30'); ?>">
            <span class="material-symbols-outlined text-[24px]">water_drop</span>
            <span class="text-xs font-bold leading-tight">Pengajuan Baptisan</span>
        </a>

        <!-- 2. Penyerahan Anak -->
        <a href="<?php echo e(route('public.pendaftaran', ['layanan' => 'penyerahan_anak'])); ?>" class="p-3.5 rounded-2xl border text-center transition flex flex-col items-center gap-1.5 <?php echo e($layanan === 'penyerahan_anak' ? 'bg-primary text-white border-primary shadow-md scale-[1.02]' : 'bg-white text-on-surface hover:bg-surface-container border-outline-variant/30'); ?>">
            <span class="material-symbols-outlined text-[24px]">child_care</span>
            <span class="text-xs font-bold leading-tight">Penyerahan Anak</span>
        </a>

        <!-- 3. Pernikahan Kudus -->
        <a href="<?php echo e(route('public.pendaftaran', ['layanan' => 'nikah'])); ?>" class="p-3.5 rounded-2xl border text-center transition flex flex-col items-center gap-1.5 <?php echo e($layanan === 'nikah' ? 'bg-primary text-white border-primary shadow-md scale-[1.02]' : 'bg-white text-on-surface hover:bg-surface-container border-outline-variant/30'); ?>">
            <span class="material-symbols-outlined text-[24px]">favorite</span>
            <span class="text-xs font-bold leading-tight">Pengajuan Pernikahan</span>
        </a>

        <!-- 4. Anggota Jemaat Baru -->
        <a href="<?php echo e(route('public.pendaftaran', ['layanan' => 'jemaat_baru'])); ?>" class="p-3.5 rounded-2xl border text-center transition flex flex-col items-center gap-1.5 <?php echo e($layanan === 'jemaat_baru' ? 'bg-primary text-white border-primary shadow-md scale-[1.02]' : 'bg-white text-on-surface hover:bg-surface-container border-outline-variant/30'); ?>">
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
                    <p class="text-2xs text-on-surface-variant">Ketik data Anda, template dokumen di kanan akan terisi otomatis.</p>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-secondary-container/60 text-secondary text-[10px] font-bold uppercase">
                    <?php echo e(strtoupper(str_replace('_', ' ', $layanan))); ?>

                </span>
            </div>

            <form id="formPendaftaran" action="<?php echo e(route('public.pendaftaran.submit')); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="jenis_layanan" value="<?php echo e($layanan); ?>">

                <!-- ================= 1. BAPTISAN AIR ================= -->
                <?php if($layanan === 'baptis'): ?>
                <div class="space-y-3.5">
                    <span class="text-2xs font-bold text-secondary uppercase tracking-wider block">1. Biodata Pribadi Calon Baptis</span>
                    
                    <div>
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="in_nama" name="nama_pemohon" required placeholder="Contoh: Hendrikus Ello" class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select id="in_jk" name="data_tambahan[jenis_kelamin]" required class="w-full px-3 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition bg-white">
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" id="in_tgl_lahir" name="data_tambahan[tanggal_lahir]" required class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Tempat Lahir</label>
                            <input type="text" id="in_tempat_lahir" name="data_tambahan[tempat_lahir]" placeholder="Contoh: Kupang" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Umur (Tahun)</label>
                            <input type="text" id="in_umur" name="data_tambahan[umur]" placeholder="Contoh: 22 Tahun" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">Alamat Domisili <span class="text-red-500">*</span></label>
                        <input type="text" id="in_alamat" name="alamat" required placeholder="Contoh: Jl. Ikan Arwana, RT 004 / RW 002 Namosain" class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                    </div>

                    <div>
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">Nomor WhatsApp / HP <span class="text-red-500">*</span></label>
                        <input type="tel" id="in_hp" name="no_wa" required placeholder="Contoh: 081234567890" class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                    </div>

                    <div class="pt-2 border-t border-outline-variant/20 space-y-3">
                        <span class="text-2xs font-bold text-secondary uppercase tracking-wider block">2. Data Orang Tua &amp; Oikos Pelayanan</span>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Ayah <span class="text-red-500">*</span></label>
                                <input type="text" id="in_ayah" name="data_tambahan[nama_ayah]" required placeholder="Nama lengkap Ayah" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Ibu <span class="text-red-500">*</span></label>
                                <input type="text" id="in_ibu" name="data_tambahan[nama_ibu]" required placeholder="Nama lengkap Ibu" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Oikos Wilayah Pelayanan</label>
                            <select id="in_wilayah" name="data_tambahan[wilayah_pelayanan]" class="w-full px-3 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition bg-white">
                                <option value="">-- Pilih Sub-Wilayah --</option>
                                <?php $__currentLoopData = $wilayahList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($w->nama_wilayah); ?>"><?php echo e($w->nama_wilayah); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ================= 2. PENYERAHAN ANAK ================= -->
                <?php elseif($layanan === 'penyerahan_anak'): ?>
                <div class="space-y-3.5">
                    <span class="text-2xs font-bold text-secondary uppercase tracking-wider block">1. Biodata Anak yang Diserahkan</span>
                    <div>
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Lengkap Anak <span class="text-red-500">*</span></label>
                        <input type="text" id="in_anak_nama" name="nama_pemohon" required placeholder="Contoh: Charlos A. Benyamin" class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Tempat Lahir</label>
                            <input type="text" id="in_anak_tempat" name="data_tambahan[tempat_lahir]" placeholder="Contoh: Kupang" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Tanggal Lahir</label>
                            <input type="date" id="in_anak_tgl" name="data_tambahan[tanggal_lahir]" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Usia Anak</label>
                            <input type="text" id="in_anak_usia" name="data_tambahan[usia]" placeholder="Contoh: 1 Tahun 2 Bulan" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Jenis Kelamin Anak</label>
                            <select id="in_anak_jk" name="data_tambahan[jenis_kelamin]" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition bg-white">
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-outline-variant/20 space-y-3">
                        <span class="text-2xs font-bold text-secondary uppercase tracking-wider block">2. Biodata Orang Tua Kandung</span>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Ayah <span class="text-red-500">*</span></label>
                                <input type="text" id="in_pa_ayah" name="data_tambahan[nama_ayah]" required placeholder="Nama Ayah" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Ibu <span class="text-red-500">*</span></label>
                                <input type="text" id="in_pa_ibu" name="data_tambahan[nama_ibu]" required placeholder="Nama Ibu" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Alamat Orang Tua <span class="text-red-500">*</span></label>
                            <input type="text" id="in_pa_alamat" name="alamat" required placeholder="Alamat rumah / domisili" class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">No. WhatsApp / HP <span class="text-red-500">*</span></label>
                                <input type="tel" id="in_pa_hp" name="no_wa" required placeholder="081234567890" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Oikos Pelayanan</label>
                                <select id="in_pa_wilayah" name="data_tambahan[wilayah_pelayanan]" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition bg-white">
                                    <option value="">-- Pilih Wilayah --</option>
                                    <?php $__currentLoopData = $wilayahList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($w->nama_wilayah); ?>"><?php echo e($w->nama_wilayah); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-outline-variant/20 space-y-3">
                        <span class="text-2xs font-bold text-secondary uppercase tracking-wider block">3. Biodata Orang Tua Saksi</span>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Saksi .1</label>
                                <input type="text" id="in_saksi_1" name="data_tambahan[nama_saksi_1]" placeholder="Nama Saksi 1" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Saksi .2</label>
                                <input type="text" id="in_saksi_2" name="data_tambahan[nama_saksi_2]" placeholder="Nama Saksi 2" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Jenis Kelamin Saksi</label>
                                <input type="text" id="in_saksi_jk" name="data_tambahan[jenis_kelamin_saksi]" placeholder="L / P" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Asal Gereja Saksi</label>
                                <input type="text" id="in_saksi_gereja" name="data_tambahan[asal_gereja_saksi]" placeholder="Contoh: GBI Shekinah Glory" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Alamat Saksi</label>
                                <input type="text" id="in_saksi_alamat" name="data_tambahan[alamat_saksi]" placeholder="Alamat saksi" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">No. HP Saksi</label>
                                <input type="tel" id="in_saksi_hp" name="data_tambahan[no_hp_saksi]" placeholder="No. HP saksi" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= 3. PENGAJUAN PERNIKAHAN ================= -->
                <?php elseif($layanan === 'nikah'): ?>
                <div class="space-y-4">
                    <!-- Calon Mempelai Pria -->
                    <div class="space-y-3">
                        <span class="text-xs font-bold text-blue-800 uppercase tracking-wider block flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">male</span>
                            <span>Biodata Calon Pengantin Pria</span>
                        </span>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">1. Nama Lengkap Pria <span class="text-red-500">*</span></label>
                            <input type="text" id="in_nk_pria_nama" name="nama_pemohon" required placeholder="Nama lengkap pengantin pria" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">2. TTL / Usia Pria</label>
                                <input type="text" id="in_nk_pria_ttl" name="data_tambahan[pria_ttl]" placeholder="Kupang, 12-05-1995 / 31 Thn" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">3. No. HP Pria <span class="text-red-500">*</span></label>
                                <input type="tel" id="in_nk_pria_hp" name="no_wa" required placeholder="081234567890" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">4. Alamat Pria</label>
                            <input type="text" id="in_nk_pria_alamat" name="alamat" placeholder="Alamat calon pengantin pria" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">5. Tgl Baptis / Sidi Pria</label>
                                <input type="text" id="in_nk_pria_baptis" name="data_tambahan[pria_tgl_baptis]" placeholder="Contoh: 10 Oktober 2012" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">6. Asal Gereja Pria</label>
                                <input type="text" id="in_nk_pria_gereja" name="data_tambahan[pria_asal_gereja]" placeholder="Contoh: GBI Shekinah Glory" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">7. Nama Ayah Pria</label>
                                <input type="text" id="in_nk_pria_ayah" name="data_tambahan[pria_ayah]" placeholder="Ayah Pria" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Ibu Pria</label>
                                <input type="text" id="in_nk_pria_ibu" name="data_tambahan[pria_ibu]" placeholder="Ibu Pria" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">8. No. HP Orang Tua Pria</label>
                                <input type="tel" id="in_nk_pria_ortu_hp" name="data_tambahan[pria_ortu_hp]" placeholder="No. HP Ortu Pria" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">9. Nama Saksi Pria</label>
                                <input type="text" id="in_nk_pria_saksi" name="data_tambahan[pria_saksi]" placeholder="Nama Saksi Pria" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>
                    </div>

                    <!-- Calon Mempelai Wanita -->
                    <div class="space-y-3 pt-3 border-t border-outline-variant/30">
                        <span class="text-xs font-bold text-rose-800 uppercase tracking-wider block flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">female</span>
                            <span>Biodata Calon Pengantin Wanita</span>
                        </span>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">1. Nama Lengkap Wanita <span class="text-red-500">*</span></label>
                            <input type="text" id="in_nk_wanita_nama" name="data_tambahan[nama_pasangan]" required placeholder="Nama lengkap pengantin wanita" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">2. TTL / Usia Wanita</label>
                                <input type="text" id="in_nk_wanita_ttl" name="data_tambahan[wanita_ttl]" placeholder="Kupang, 20-08-1997 / 29 Thn" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">3. No. HP Wanita</label>
                                <input type="tel" id="in_nk_wanita_hp" name="data_tambahan[wanita_hp]" placeholder="No. HP Wanita" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">4. Alamat Wanita</label>
                            <input type="text" id="in_nk_wanita_alamat" name="data_tambahan[wanita_alamat]" placeholder="Alamat calon pengantin wanita" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">5. Tgl Baptis / Sidi Wanita</label>
                                <input type="text" id="in_nk_wanita_baptis" name="data_tambahan[wanita_tgl_baptis]" placeholder="Contoh: 15 Juli 2014" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">6. Asal Gereja Wanita</label>
                                <input type="text" id="in_nk_wanita_gereja" name="data_tambahan[wanita_asal_gereja]" placeholder="Asal gereja wanita" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">7. Nama Ayah Wanita</label>
                                <input type="text" id="in_nk_wanita_ayah" name="data_tambahan[wanita_ayah]" placeholder="Ayah Wanita" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Ibu Wanita</label>
                                <input type="text" id="in_nk_wanita_ibu" name="data_tambahan[wanita_ibu]" placeholder="Ibu Wanita" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">8. No. HP Orang Tua Wanita</label>
                                <input type="tel" id="in_nk_wanita_ortu_hp" name="data_tambahan[wanita_ortu_hp]" placeholder="No. HP Ortu Wanita" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">9. Nama Saksi Wanita</label>
                                <input type="text" id="in_nk_wanita_saksi" name="data_tambahan[wanita_saksi]" placeholder="Nama Saksi Wanita" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= 4. ANGGOTA JEMAAT BARU ================= -->
                <?php else: ?>
                <div class="space-y-4">
                    <span class="text-xs font-bold text-secondary uppercase tracking-wider block">1. Biodata Pribadi Calon Anggota Jemaat</span>
                    <div>
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Lengkap Sesuai KTP <span class="text-red-500">*</span></label>
                        <input type="text" id="in_jb_nama" name="nama_pemohon" required placeholder="Nama lengkap sesuai KTP" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
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
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Status Pernikahan</label>
                            <select id="in_jb_status" name="data_tambahan[status_nikah]" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition bg-white">
                                <option value="Belum Menikah">Belum Menikah</option>
                                <option value="Menikah">Menikah</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Tempat Lahir</label>
                            <input type="text" id="in_jb_tempat" name="data_tambahan[tempat_lahir]" placeholder="Tempat lahir" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Tanggal Lahir</label>
                            <input type="date" id="in_jb_tgl" name="data_tambahan[tanggal_lahir]" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-2xs font-bold uppercase text-primary mb-1">Alamat Rumah (Jalan / Gang) <span class="text-red-500">*</span></label>
                        <input type="text" id="in_jb_jalan" name="alamat" required placeholder="Jl. Ikan Arwana No. 12" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">RT / RW</label>
                            <input type="text" id="in_jb_rtrw" name="data_tambahan[rt_rw]" placeholder="RT. 004 / RW. 002" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Kelurahan &amp; Kecamatan</label>
                            <input type="text" id="in_jb_kelkec" name="data_tambahan[kel_kec]" placeholder="Namosain, Alak" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">No. Handphone / WhatsApp <span class="text-red-500">*</span></label>
                            <input type="tel" id="in_jb_hp" name="no_wa" required placeholder="081234567890" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                        </div>
                        <div>
                            <label class="block text-2xs font-bold uppercase text-primary mb-1">Golongan Darah</label>
                            <select id="in_jb_goldar" name="data_tambahan[golongan_darah]" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition bg-white">
                                <option value="O">O</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-outline-variant/30 space-y-3">
                        <span class="text-xs font-bold text-secondary uppercase tracking-wider block">2. Keanggotaan &amp; Keluarga</span>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Ayah</label>
                                <input type="text" id="in_jb_ayah" name="data_tambahan[nama_ayah]" placeholder="Ayah" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Nama Ibu</label>
                                <input type="text" id="in_jb_ibu" name="data_tambahan[nama_ibu]" placeholder="Ibu" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Gereja Asal Sebelumnya</label>
                                <input type="text" id="in_jb_asal" name="data_tambahan[asal_gereja]" placeholder="Nama gereja asal" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                            <div>
                                <label class="block text-2xs font-bold uppercase text-primary mb-1">Pendidikan Terakhir</label>
                                <input type="text" id="in_jb_pendidikan" name="data_tambahan[pendidikan]" placeholder="SMA / S1 / dll" class="w-full px-3.5 py-2 rounded-xl border border-outline-variant text-xs focus:border-secondary transition">
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

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
                    <span>Pratinjau Dokumen Fisik Blanko (Times New Roman)</span>
                </span>
                <button type="button" onclick="printDocTemplate()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-outline-variant/40 hover:bg-surface-container text-secondary font-bold text-2xs shadow-2xs transition font-sans">
                    <span class="material-symbols-outlined text-[15px]">print</span>
                    <span>Cetak Blanko Terisi</span>
                </button>
            </div>

            <!-- CONTAINER DOKUMEN KERTAS A4 (TIMES NEW ROMAN PERSIS SESUAI ASLI) -->
            <div id="printableDocWrapper" class="bg-white rounded-2xl shadow-xl border border-gray-300 p-6 sm:p-10 relative overflow-hidden select-none doc-times-font" style="min-height: 800px; font-family: 'Times New Roman', Times, serif;">
                
                <!-- WATERMARK LOGO GBI DI TENGAH KERTAS PERSIS SEPERTI ASLINYA (BERWARNA) -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.12] select-none z-0">
                    <img src="<?php echo e(asset('images/logo.jpg')); ?>" alt="Watermark Gereja Bethel Indonesia" class="w-96 h-96 object-contain">
                </div>

                <!-- KONTEN DOKUMEN (Z-INDEX 10) -->
                <div class="relative z-10 space-y-4 text-black">
                    
                    <!-- KOP SURAT RESMI GBI SHEKINAH GLORY -->
                    <div class="flex items-start gap-4 pb-2 border-b-2 border-[#b91c1c]">
                        <img src="<?php echo e(asset('images/logo.jpg')); ?>" alt="Logo GBI" class="w-14 h-14 sm:w-16 sm:h-16 object-contain shrink-0 mt-0.5">
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
                                Jalan Ikan Arwana Namosain - Kupang Telpon : 085 333 650 621, 081 238 858 629, 082 247 292 194
                            </p>
                        </div>
                    </div>
                    <!-- Garis Ganda Bawah Kop Surat -->
                    <div class="h-0.5 bg-[#1e3a8a] -mt-3 mb-3"></div>

                    <!-- =================================================== -->
                    <!-- TEMPLATE 1: FORMULIR PENGAJUAN BAPTISAN (Roma 6:4)  -->
                    <!-- =================================================== -->
                    <?php if($layanan === 'baptis'): ?>
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

                    <div class="pt-8 flex justify-end text-xs text-gray-900">
                        <div class="text-center space-y-0.5 w-60">
                            <p class="text-[11px]">Kupang, <span class="border-b border-dotted border-gray-400 px-3"><?php echo e(now()->translatedFormat('d F')); ?></span> <?php echo e(date('Y')); ?></p>
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
                    <?php elseif($layanan === 'penyerahan_anak'): ?>
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
                                <span class="col-span-5 sm:col-span-4 text-gray-800">3. Jenis Kelamin</span>
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

                    <div class="pt-6 flex justify-end text-xs text-gray-900">
                        <div class="text-center space-y-0.5 w-64">
                            <p class="text-[11px]">Kupang, <span class="border-b border-dotted border-gray-400 px-3"><?php echo e(now()->translatedFormat('d F')); ?></span> <?php echo e(date('Y')); ?></p>
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

                    <!-- =================================================== -->
                    <!-- TEMPLATE 3: FORM PENGAJUAN PERNIKAHAN (Matius 19:6) -->
                    <!-- =================================================== -->
                    <?php elseif($layanan === 'nikah'): ?>
                    <div class="text-center my-3">
                        <div class="inline-block border border-[#1e3a8a] px-6 py-1 bg-white shadow-2xs">
                            <h4 class="font-bold text-xs sm:text-sm text-gray-900 tracking-wider uppercase">
                                FORM PENGAJUAN PERNIKAHAN
                            </h4>
                        </div>
                        <div class="text-xs italic text-gray-700 mt-1">
                            (Matius 19:6)
                        </div>
                    </div>

                    <p class="text-[11px] text-gray-700 italic mb-2">
                        *Nomor Surat (Di isi petugas) : <span class="font-mono text-gray-500 font-normal">...................................................</span>
                    </p>

                    <div class="space-y-3 text-xs text-gray-900 leading-relaxed">
                        <!-- Calon Pengantin Pria -->
                        <div class="font-bold text-[12px] flex items-center gap-1.5 underline">
                            <span>➢</span>
                            <span>Biodata Calon Pengantin Pria :</span>
                        </div>
                        <div class="pl-4 space-y-1">
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 text-gray-800">1. Nama Lengkap</span>
                                <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span id="pv_nk_pria_nama" class="font-bold text-gray-900 uppercase">.............................................................</span></div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 text-gray-800">2. TTL/Usia</span>
                                <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span id="pv_nk_pria_ttl" class="font-semibold text-gray-900">.............................................................</span></div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 text-gray-800">3. Alamat</span>
                                <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span id="pv_nk_pria_alamat" class="font-semibold text-gray-900">.............................................................</span></div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 text-gray-800">4. Baptis/Sidi Dll/Pada Tgl</span>
                                <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span id="pv_nk_pria_baptis" class="font-semibold text-gray-900">.............................................................</span></div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 text-gray-800">5. Asal Gereja</span>
                                <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span id="pv_nk_pria_gereja" class="font-semibold text-gray-900">.............................................................</span></div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 text-gray-800">6. No Hp</span>
                                <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span id="pv_nk_pria_hp" class="font-mono font-semibold text-gray-900">.............................................................</span></div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-start">
                                <span class="col-span-5 text-gray-800 mt-0.5">7. Nama Orang Tua</span>
                                <div class="col-span-7 space-y-0.5">
                                    <div class="flex items-baseline border-b border-dotted border-gray-400 pb-0.5"><span class="text-gray-700 w-16">❖ Ayah :</span> <span id="pv_nk_pria_ayah" class="font-semibold uppercase">..................................................</span></div>
                                    <div class="flex items-baseline border-b border-dotted border-gray-400 pb-0.5"><span class="text-gray-700 w-16">❖ Ibu :</span> <span id="pv_nk_pria_ibu" class="font-semibold uppercase">..................................................</span></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 text-gray-800">8. No Hp Ayah/Ibu</span>
                                <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span id="pv_nk_pria_ortu_hp" class="font-mono">.............................................................</span></div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 text-gray-800">9. Nama Saksi Pria</span>
                                <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span id="pv_nk_pria_saksi">.............................................................</span></div>
                            </div>
                        </div>

                        <!-- Calon Pengantin Wanita -->
                        <div class="font-bold text-[12px] flex items-center gap-1.5 underline pt-2">
                            <span>➢</span>
                            <span>Biodata Calon Pengantin Wanita :</span>
                        </div>
                        <div class="pl-4 space-y-1">
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 text-gray-800">1. Nama Lengkap</span>
                                <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span id="pv_nk_wanita_nama" class="font-bold text-gray-900 uppercase">.............................................................</span></div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 text-gray-800">2. TTL/Usia</span>
                                <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span id="pv_nk_wanita_ttl" class="font-semibold text-gray-900">.............................................................</span></div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 text-gray-800">3. Alamat</span>
                                <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span id="pv_nk_wanita_alamat" class="font-semibold text-gray-900">.............................................................</span></div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 text-gray-800">4. Baptis/Sidi Dll/Pada Tgl</span>
                                <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span id="pv_nk_wanita_baptis" class="font-semibold text-gray-900">.............................................................</span></div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 text-gray-800">5. Asal Gereja</span>
                                <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span id="pv_nk_wanita_gereja" class="font-semibold text-gray-900">.............................................................</span></div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 text-gray-800">6. No Hp</span>
                                <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span id="pv_nk_wanita_hp" class="font-mono font-semibold text-gray-900">.............................................................</span></div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-start">
                                <span class="col-span-5 text-gray-800 mt-0.5">7. Nama Orang Tua</span>
                                <div class="col-span-7 space-y-0.5">
                                    <div class="flex items-baseline border-b border-dotted border-gray-400 pb-0.5"><span class="text-gray-700 w-16">❖ Ayah :</span> <span id="pv_nk_wanita_ayah" class="font-semibold uppercase">..................................................</span></div>
                                    <div class="flex items-baseline border-b border-dotted border-gray-400 pb-0.5"><span class="text-gray-700 w-16">❖ Ibu :</span> <span id="pv_nk_wanita_ibu" class="font-semibold uppercase">..................................................</span></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 text-gray-800">8. No.Hp Ayah/Ibu</span>
                                <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span id="pv_nk_wanita_ortu_hp" class="font-mono">.............................................................</span></div>
                            </div>
                            <div class="grid grid-cols-12 gap-1 items-baseline">
                                <span class="col-span-5 text-gray-800">9. Nama Saksi Wanita</span>
                                <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span id="pv_nk_wanita_saksi">.............................................................</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Tanda Tangan & Lampiran Persis Seperti Form Nikah Asli -->
                    <div class="pt-6 flex justify-end text-xs text-gray-900">
                        <div class="text-center space-y-0.5 w-64">
                            <p class="text-[11px]">Kupang, <span class="border-b border-dotted border-gray-400 px-3"><?php echo e(now()->translatedFormat('d F')); ?></span> <?php echo e(date('Y')); ?></p>
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

                    <div class="p-3 rounded border border-gray-400 bg-gray-50/50 text-[10px] leading-relaxed mt-4">
                        <strong class="italic block mb-1 underline">* Catatan : untuk kedua calon mempelai melampirkan :</strong>
                        <ol class="list-decimal pl-4 space-y-0.5">
                            <li>Fotocopy KTP 1 Lembar</li>
                            <li>Foto copy Surat Baptis / Sidi 1 Lembar</li>
                            <li>Foto copy KK 1 Lembar</li>
                            <li>Foto Copy Akte Kelahiran 1 Lembar</li>
                            <li>Foto gandeng 1 Lembar</li>
                        </ol>
                    </div>

                    <!-- =================================================== -->
                    <!-- TEMPLATE 4: FORM ANGGOTA JEMAAT BARU                -->
                    <!-- =================================================== -->
                    <?php else: ?>
                    <div class="flex items-center justify-between border border-black p-2 my-2 bg-gray-50/40">
                        <div class="border-2 border-black px-4 py-1.5 font-bold text-xs uppercase tracking-wider">
                            FORM ANGGOTA JEMAAT BARU
                        </div>
                        <div class="border border-black p-2 text-[10px] space-y-0.5 text-left w-48 bg-white">
                            <span class="font-bold block italic underline">Diisi Oleh Petugas</span>
                            <div>Data Nomor : <span class="font-mono text-gray-500">........................</span></div>
                            <div>Tanggal Diinput : <span class="font-mono text-gray-500">...................</span></div>
                        </div>
                    </div>

                    <div class="space-y-2 text-[11px] text-gray-900 leading-relaxed">
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
                                <div class="border border-black p-1.5 flex items-center justify-between">
                                    <span>[Jenis Kelamin]:</span>
                                    <span class="font-bold text-primary" id="pv_jb_jk">Pria</span>
                                </div>
                                <div class="border border-black p-1.5 flex items-center justify-between">
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
                                <span class="text-gray-800 block text-[10px]">[Alamat Rumah]:</span>
                                <div class="border border-black px-2 py-1 font-semibold text-[11px]">
                                    <span id="pv_jb_alamat">...........................................................................................................</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-1 text-[10px] items-center">
                                <span class="col-span-4">[RT / RW]:</span>
                                <div class="col-span-8 border border-black px-2 py-0.5 font-semibold" id="pv_jb_rtrw">-</div>
                            </div>

                            <div class="grid grid-cols-12 gap-1 text-[10px] items-center">
                                <span class="col-span-4">[Kelurahan / Kecamatan]:</span>
                                <div class="col-span-8 border border-black px-2 py-0.5 font-semibold" id="pv_jb_kelkec">-</div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-[10px]">
                                <div class="border border-black p-1.5 flex items-center justify-between">
                                    <span>[Golongan Darah]:</span>
                                    <span class="font-bold text-red-700" id="pv_jb_goldar">O</span>
                                </div>
                                <div class="border border-black p-1.5 flex items-center justify-between">
                                    <span>[No. Handphone]:</span>
                                    <span class="font-mono font-bold" id="pv_jb_hp">-</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-1 text-[10px] items-center">
                                <span class="col-span-4">[Pendidikan Terakhir]:</span>
                                <div class="col-span-8 border border-black px-2 py-0.5 font-semibold" id="pv_jb_pendidikan">-</div>
                            </div>
                        </div>

                        <div class="font-bold text-[12px] flex items-center gap-1.5 underline pt-1">
                            <span>➢</span>
                            <span>Data Keluarga &amp; Gereja :</span>
                        </div>
                        <div class="pl-2 space-y-1">
                            <div class="grid grid-cols-2 gap-2 text-[10px]">
                                <div class="border border-black p-1">
                                    <span class="text-gray-600 block text-[9px]">[Nama Ayah]:</span>
                                    <span class="font-semibold uppercase" id="pv_jb_ayah">-</span>
                                </div>
                                <div class="border border-black p-1">
                                    <span class="text-gray-600 block text-[9px]">[Nama Ibu]:</span>
                                    <span class="font-semibold uppercase" id="pv_jb_ibu">-</span>
                                </div>
                            </div>
                            <div class="border border-black p-1 text-[10px]">
                                <span class="text-gray-600 block text-[9px]">[Gereja Asal Sebelumnya]:</span>
                                <span class="font-semibold" id="pv_jb_asal">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Kotak Foto 4x6 & Tanda Tangan Persis Form Jemaat Baru -->
                    <div class="grid grid-cols-12 gap-3 pt-3 items-end">
                        <div class="col-span-4">
                            <div class="w-24 h-32 border-2 border-dashed border-gray-400 rounded flex flex-col items-center justify-center text-center p-2 text-[9px] text-gray-500 bg-gray-50">
                                <span class="material-symbols-outlined text-[20px] text-gray-400 mb-1">image</span>
                                <span>Pas Foto 4x6 Warna</span>
                            </div>
                        </div>

                        <div class="col-span-8 space-y-1 text-center">
                            <p class="text-[10px]">Kupang, <span class="border-b border-dotted border-gray-400 px-3"><?php echo e(now()->translatedFormat('d F')); ?></span> <?php echo e(date('Y')); ?></p>
                            <p class="font-bold text-[10px] tracking-wider">MENGETAHUI GEMBALA SIDANG</p>
                            <p class="font-bold text-[10px]">GBI SHEKINAH GLORY NAMOSAIN</p>
                            <div class="h-12 flex items-center justify-center">
                                <span class="text-[9px] text-gray-400 italic">( Tanda Tangan &amp; Cap Gereja )</span>
                            </div>
                            <p class="font-bold underline text-xs tracking-wide">Pdt. JUSUF LABAGAI</p>
                            <p class="text-[9px] font-mono">NIP; 1000702</p>
                        </div>
                    </div>

                    <div class="pt-2 text-[9px] text-gray-700 italic border-t border-gray-300">
                        <strong>NB; Lampirkan FC. 1. KTP, 2. KK, 3. AKTA BAPTIS &amp; KELAHIRAN</strong>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    </div>
</div>

<!-- ======================================================== -->
<!-- PRINT CSS KHUSUS CETAK DOKUMEN STANDAR A4 (TIMES NEW ROMAN)-->
<!-- ======================================================== -->
<style>
.doc-times-font {
    font-family: 'Times New Roman', Times, serif !important;
}

@media print {
    body * {
        visibility: hidden;
    }
    header, footer, nav, #sectionFormInput, .btn, button, .lg\\:hidden {
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
        margin: 0 !important;
        padding: 15mm 20mm !important;
        box-shadow: none !important;
        border: none !important;
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
            document.getElementById('pv_bap_jk').innerText = this.value;
        });
    }

    const inWilayah = document.getElementById('in_wilayah');
    if (inWilayah) {
        inWilayah.addEventListener('change', function() {
            document.getElementById('pv_bap_wilayah').innerText = this.value || '...........................................................................';
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
                        umur.placeholder = diff + ' Tahun';
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
            document.getElementById('pv_pa_jk').innerText = this.value;
        });
    }

    const inPaWilayah = document.getElementById('in_pa_wilayah');
    if (inPaWilayah) {
        inPaWilayah.addEventListener('change', function() {
            document.getElementById('pv_pa_wilayah').innerText = this.value || '...........................................................................';
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

    // 3. Sync Pengajuan Nikah
    syncField('in_nk_pria_nama', 'pv_nk_pria_nama', '.............................................................');
    syncField('in_nk_pria_ttl', 'pv_nk_pria_ttl', '.............................................................');
    syncField('in_nk_pria_alamat', 'pv_nk_pria_alamat', '.............................................................');
    syncField('in_nk_pria_baptis', 'pv_nk_pria_baptis', '.............................................................');
    syncField('in_nk_pria_gereja', 'pv_nk_pria_gereja', '.............................................................');
    syncField('in_nk_pria_hp', 'pv_nk_pria_hp', '.............................................................');
    syncField('in_nk_pria_ayah', 'pv_nk_pria_ayah', '..................................................');
    syncField('in_nk_pria_ibu', 'pv_nk_pria_ibu', '..................................................');
    syncField('in_nk_pria_ortu_hp', 'pv_nk_pria_ortu_hp', '.............................................................');
    syncField('in_nk_pria_saksi', 'pv_nk_pria_saksi', '.............................................................');

    syncField('in_nk_wanita_nama', 'pv_nk_wanita_nama', '.............................................................');
    syncField('in_nk_wanita_ttl', 'pv_nk_wanita_ttl', '.............................................................');
    syncField('in_nk_wanita_alamat', 'pv_nk_wanita_alamat', '.............................................................');
    syncField('in_nk_wanita_baptis', 'pv_nk_wanita_baptis', '.............................................................');
    syncField('in_nk_wanita_gereja', 'pv_nk_wanita_gereja', '.............................................................');
    syncField('in_nk_wanita_hp', 'pv_nk_wanita_hp', '.............................................................');
    syncField('in_nk_wanita_ayah', 'pv_nk_wanita_ayah', '..................................................');
    syncField('in_nk_wanita_ibu', 'pv_nk_wanita_ibu', '..................................................');
    syncField('in_nk_wanita_ortu_hp', 'pv_nk_wanita_ortu_hp', '.............................................................');
    syncField('in_nk_wanita_saksi', 'pv_nk_wanita_saksi', '.............................................................');

    // 4. Sync Anggota Jemaat Baru
    syncField('in_jb_nama', 'pv_jb_nama', '...........................................................................................................');
    syncField('in_jb_tempat', 'pv_jb_tempat', '-');
    syncField('in_jb_jalan', 'pv_jb_alamat', '...........................................................................................................');
    syncField('in_jb_rtrw', 'pv_jb_rtrw', '-');
    syncField('in_jb_kelkec', 'pv_jb_kelkec', '-');
    syncField('in_jb_hp', 'pv_jb_hp', '-');
    syncField('in_jb_ayah', 'pv_jb_ayah', '-');
    syncField('in_jb_ibu', 'pv_jb_ibu', '-');
    syncField('in_jb_asal', 'pv_jb_asal', '-');
    syncField('in_jb_pendidikan', 'pv_jb_pendidikan', '-');

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\AderaldDarell\Documents\PMP\Flutter\webprofilgbishekinahglory\resources\views/public/pendaftaran.blade.php ENDPATH**/ ?>