@extends('layouts.admin')

@section('page_title', 'Pengaturan Konten Website')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-primary to-primary-container rounded-2xl p-6 sm:p-8 text-white shadow-lg flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-secondary-fixed text-xs font-bold mb-2">
                <span class="material-symbols-outlined text-[16px]">tune</span>
                <span>Content Management System (CMS)</span>
            </div>
            <h1 class="font-serif-display text-2xl sm:text-3xl font-bold">Pengaturan Konten & Tampilan Web</h1>
            <p class="text-sm text-white/80 mt-1 max-w-2xl">
                Ubah gambar banner, teks headline, visi misi, sejarah, lokasi, dan kontak gereja secara langsung tanpa perlu menyentuh kode.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('public.beranda') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs transition flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                <span>Lihat Hasil di Web</span>
            </a>
        </div>
    </div>

    <!-- Form Pengaturan -->
    <form action="{{ route('admin.konten.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Tab Navigation Buttons -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-outline-variant/40">
            <button type="button" onclick="switchTab('tab-banner')" id="btn-tab-banner" class="tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 transition bg-primary text-white shadow-sm shrink-0">
                <span class="material-symbols-outlined text-[18px]">image</span>
                <span>Banner &amp; Hero</span>
            </button>
            <button type="button" onclick="switchTab('tab-visimisi')" id="btn-tab-visimisi" class="tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 transition bg-white text-on-surface-variant hover:bg-surface-container shrink-0">
                <span class="material-symbols-outlined text-[18px]">lightbulb</span>
                <span>Visi, Misi &amp; Sejarah</span>
            </button>
            <button type="button" onclick="switchTab('tab-struktur')" id="btn-tab-struktur" class="tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 transition bg-white text-on-surface-variant hover:bg-surface-container shrink-0">
                <span class="material-symbols-outlined text-[18px]">account_tree</span>
                <span>Struktur Kepengurusan</span>
                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-900">Bagan</span>
            </button>
            <button type="button" onclick="switchTab('tab-warta')" id="btn-tab-warta" class="tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 transition bg-white text-on-surface-variant hover:bg-surface-container shrink-0">
                <span class="material-symbols-outlined text-[18px]">campaign</span>
                <span>Warta &amp; Pengumuman</span>
            </button>
            <button type="button" onclick="switchTab('tab-kontak')" id="btn-tab-kontak" class="tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 transition bg-white text-on-surface-variant hover:bg-surface-container shrink-0">
                <span class="material-symbols-outlined text-[18px]">call</span>
                <span>Kontak &amp; Layanan</span>
            </button>
            <button type="button" onclick="switchTab('tab-lokasi')" id="btn-tab-lokasi" class="tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 transition bg-white text-on-surface-variant hover:bg-surface-container shrink-0">
                <span class="material-symbols-outlined text-[18px]">location_on</span>
                <span>Alamat &amp; Peta</span>
            </button>
            <button type="button" onclick="switchTab('tab-umum')" id="btn-tab-umum" class="tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 transition bg-white text-on-surface-variant hover:bg-surface-container shrink-0">
                <span class="material-symbols-outlined text-[18px]">verified</span>
                <span>Identitas &amp; Slogan</span>
            </button>
        </div>

        <!-- ================= TAB 1: BANNER & HERO ================= -->
        <div id="tab-banner" class="tab-content space-y-6">
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-6">
                <div class="border-b border-outline-variant/30 pb-4">
                    <h3 class="font-serif-display text-xl font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">view_carousel</span>
                        <span>Headline & Banner Beranda</span>
                    </h3>
                    <p class="text-xs text-on-surface-variant mt-1">Sesuaikan teks pembuka dan gambar utama yang tampil pertama kali saat pengunjung membuka web.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary">Badge Ayat Banner</label>
                        <input type="text" name="hero_badge" value="{{ $contents['hero_badge'] ?? 'YESAYA 60:1 • BANGKIT & BERCAHAYA' }}" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary focus:ring-1 focus:ring-secondary text-sm">
                        <span class="text-[11px] text-on-surface-variant">Label kecil yang menyala di bagian atas judul banner.</span>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary">Judul Utama Banner</label>
                        <input type="text" name="hero_title" value="{{ $contents['hero_title'] ?? 'GBI Shekinah Glory' }}" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary focus:ring-1 focus:ring-secondary text-sm font-semibold">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary">Sub-judul / Tagline Banner</label>
                        <input type="text" name="hero_subtitle" value="{{ $contents['hero_subtitle'] ?? '"Menjadi Terang dan Tempat Kemuliaan Tuhan Hadir Nyata"' }}" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary focus:ring-1 focus:ring-secondary text-sm">
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary">Teks Sambutan Banner</label>
                        <textarea name="hero_description" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary focus:ring-1 focus:ring-secondary text-sm leading-relaxed">{{ $contents['hero_description'] ?? 'Selamat Datang di Rumah Tuhan. Kami menyambut Anda dan keluarga dengan kehangatan kasih serta sukacita Kristus di tengah persekutuan yang kudus, penuh pengharapan, dan memulihkan jiwa.' }}</textarea>
                    </div>
                </div>

                <!-- Bagian Gambar Banner -->
                <div class="pt-6 border-t border-outline-variant/30 space-y-4">
                    <h4 class="font-serif-display text-base font-bold text-primary">Gambar Visual Banner (Hero Visual)</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                        <!-- Preview Gambar -->
                        <div class="md:col-span-4">
                            <span class="block text-xs font-bold text-on-surface-variant mb-2">Preview Gambar Saat Ini:</span>
                            <div class="relative aspect-[4/3] rounded-xl overflow-hidden border border-outline-variant bg-surface-container shadow-sm">
                                <img id="hero_preview" src="{{ $contents['hero_image'] ?? asset('images/gedung_gbi_shekinah_glory.jpg') }}" alt="Preview Banner" class="w-full h-full object-cover">
                            </div>
                        </div>

                        <!-- Form Gambar -->
                        <div class="md:col-span-8 space-y-4">
                            <div class="space-y-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-primary">URL Gambar (Link Eksternal)</label>
                                <input type="url" name="hero_image" id="hero_image_input" value="{{ $contents['hero_image'] ?? '' }}" placeholder="https://images.unsplash.com/..." oninput="document.getElementById('hero_preview').src = this.value" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary focus:ring-1 focus:ring-secondary text-sm">
                                <span class="text-[11px] text-on-surface-variant">Bisa paste link foto resolusi tinggi dari Unsplash atau sumber lain.</span>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-primary">Atau Upload Gambar Baru dari Komputer</label>
                                <input type="file" name="hero_image_file" accept="image/*" onchange="previewFile(this, 'hero_preview')" class="w-full px-3 py-2 text-sm border border-outline-variant rounded-xl file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-container">
                                <span class="text-[11px] text-on-surface-variant">Format: JPG, PNG, WEBP. Maksimal 4MB.</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-primary">Badge Kartu Gambar</label>
                                    <input type="text" name="hero_image_badge" value="{{ $contents['hero_image_badge'] ?? 'Hadirat Allah Nyata' }}" class="w-full px-3 py-2 rounded-lg border border-outline-variant text-xs">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-primary">Keterangan Gambar</label>
                                    <input type="text" name="hero_image_desc" value="{{ $contents['hero_image_desc'] ?? 'Berakar dalam kebenaran Firman, bertumbuh dalam keintiman ibadah.' }}" class="w-full px-3 py-2 rounded-lg border border-outline-variant text-xs">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= TAB 2: VISI, MISI & SEJARAH ================= -->
        <div id="tab-visimisi" class="tab-content space-y-6 hidden">
            <!-- Visi Gereja -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-4">
                <div class="border-b border-outline-variant/30 pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="font-serif-display text-xl font-bold text-primary flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary">lightbulb</span>
                            <span>Visi Gereja</span>
                        </h3>
                        <p class="text-xs text-on-surface-variant">Arah pandang dan panggilan utama gereja</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-primary">Pernyataan Visi</label>
                    <textarea name="visi" rows="3" class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:border-secondary focus:ring-1 focus:ring-secondary text-sm italic font-serif leading-relaxed">{{ $contents['visi'] ?? 'Menjadi Rumah kemuliaan-Nya yang menjawab amanat agung Tuhan Yesus Kristus sebagai gereja yang Misionari, Apolistik dan Profetik.' }}</textarea>
                </div>
            </div>

            <!-- Misi Pelayanan (Dynamic List) -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-4">
                <div class="border-b border-outline-variant/30 pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="font-serif-display text-xl font-bold text-primary flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary">rocket_launch</span>
                            <span>Misi Pelayanan</span>
                        </h3>
                        <p class="text-xs text-on-surface-variant">Poin-poin konkret pelaksanaan amanat agung (otomatis tampil di Beranda & Tentang)</p>
                    </div>
                    <button type="button" onclick="tambahPoinMisi()" class="px-3 py-1.5 rounded-lg bg-secondary-container text-secondary text-xs font-bold hover:bg-secondary hover:text-white transition flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        <span>Tambah Poin Misi</span>
                    </button>
                </div>

                <div id="misi-container" class="space-y-3">
                    @foreach($misiList as $index => $misi)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-container-low border border-outline-variant/40 misi-item">
                        <div class="w-7 h-7 rounded-full bg-secondary-container text-secondary flex items-center justify-center text-xs font-bold shrink-0">
                            {{ $loop->iteration }}
                        </div>
                        <input type="text" name="misi[]" value="{{ $misi }}" class="flex-1 px-3 py-2 rounded-lg border border-outline-variant focus:border-secondary text-sm bg-white" placeholder="Tuliskan butir misi...">
                        <button type="button" onclick="hapusPoinMisi(this)" class="w-9 h-9 rounded-lg text-red-500 hover:bg-red-50 flex items-center justify-center transition" title="Hapus butir misi">
                            <span class="material-symbols-outlined text-[20px]">delete</span>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Sejarah Singkat Gereja -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-6">
                <div class="border-b border-outline-variant/30 pb-3">
                    <h3 class="font-serif-display text-xl font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">history_edu</span>
                        <span>Sejarah Gereja</span>
                    </h3>
                    <p class="text-xs text-on-surface-variant">Kisah perjalanan dan perintisan jemaat</p>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-primary">Teks Narasi Sejarah</label>
                    <textarea name="sejarah" rows="5" class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:border-secondary focus:ring-1 focus:ring-secondary text-sm leading-relaxed">{{ $contents['sejarah'] ?? '' }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start pt-4 border-t border-outline-variant/30">
                    <div class="md:col-span-4">
                        <span class="block text-xs font-bold text-on-surface-variant mb-2">Foto Sejarah Saat Ini:</span>
                        <div class="relative aspect-[4/3] rounded-xl overflow-hidden border border-outline-variant bg-surface-container shadow-sm">
                            <img id="sejarah_preview" src="{{ $contents['sejarah_image'] ?? asset('images/gedung_gbi_shekinah_glory.jpg') }}" alt="Preview Foto Sejarah" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <div class="md:col-span-8 space-y-4">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-primary">URL Foto Sejarah</label>
                            <input type="url" name="sejarah_image" id="sejarah_image_input" value="{{ $contents['sejarah_image'] ?? '' }}" oninput="document.getElementById('sejarah_preview').src = this.value" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant text-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-primary">Upload Foto Sejarah Baru</label>
                            <input type="file" name="sejarah_image_file" accept="image/*" onchange="previewFile(this, 'sejarah_preview')" class="w-full px-3 py-2 text-sm border border-outline-variant rounded-xl file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-container">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-primary">Badge Foto</label>
                                <input type="text" name="sejarah_badge" value="{{ $contents['sejarah_badge'] ?? 'Sejak 6 Oktober 2000' }}" class="w-full px-3 py-2 rounded-lg border border-outline-variant text-xs">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-primary">Caption Foto</label>
                                <input type="text" name="sejarah_caption" value="{{ $contents['sejarah_caption'] ?? 'Melayani Jemaat, Menjangkau Generasi & Membangun Kota Kupang' }}" class="w-full px-3 py-2 rounded-lg border border-outline-variant text-xs">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= TAB: STRUKTUR KEPENGURUSAN GEREJA ================= -->
        <div id="tab-struktur" class="tab-content space-y-6 hidden">
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-6">
                <div class="border-b border-outline-variant/30 pb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 text-[10px] font-bold mb-1">
                            <span class="material-symbols-outlined text-[14px]">account_tree</span>
                            <span>Struktur Organisasi Pelayanan</span>
                        </div>
                        <h3 class="font-serif-display text-xl font-bold text-primary">Struktur Kepengurusan Gereja</h3>
                        <p class="text-xs text-on-surface-variant mt-0.5">Bagan organisasi, dewan majelis, komisi kategorial, dan koordinator bidang pelayanan.</p>
                    </div>
                    <a href="{{ route('admin.majelis.index') }}" class="px-3.5 py-2 rounded-xl bg-surface-container text-primary font-bold text-xs hover:bg-surface-container-high transition flex items-center gap-1.5 border border-outline-variant/30 shrink-0">
                        <span class="material-symbols-outlined text-[16px]">badge</span>
                        <span>Kelola Data Majelis Bertugas</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                    <!-- Preview Bagan -->
                    <div class="md:col-span-5 space-y-2">
                        <span class="block text-xs font-bold text-on-surface-variant">Pratinjau Bagan Kepengurusan:</span>
                        <div class="relative aspect-[16/10] rounded-2xl overflow-hidden border border-outline-variant bg-surface-container flex items-center justify-center shadow-sm">
                            @if(!empty($contents['struktur_organisasi_image']))
                                <img id="struktur_preview" src="{{ $contents['struktur_organisasi_image'] }}" alt="Bagan Struktur Organisasi" class="w-full h-full object-contain p-2">
                            @else
                                <div id="struktur_placeholder" class="flex flex-col items-center justify-center p-6 text-center text-on-surface-variant/60">
                                    <span class="material-symbols-outlined text-[48px] text-amber-600 mb-2">account_tree</span>
                                    <span class="text-xs font-bold text-primary">Slot Struktur Tersedia</span>
                                    <span class="text-[11px] text-on-surface-variant mt-1">Upload gambar bagan kepengurusan atau gunakan status pengesahan default.</span>
                                </div>
                                <img id="struktur_preview" src="" alt="Preview" class="w-full h-full object-contain p-2 hidden">
                            @endif
                        </div>
                    </div>

                    <!-- Input Upload & URL -->
                    <div class="md:col-span-7 space-y-4">
                        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-1.5 leading-relaxed">
                            <div class="font-bold flex items-center gap-1.5 text-amber-950">
                                <span class="material-symbols-outlined text-[16px]">info</span>
                                <span>Informasi Sesuai PRD v2.1:</span>
                            </div>
                            <p>Slot struktur kepengurusan telah disiapkan pada halaman Profil Web (<a href="{{ route('public.tentang') }}" target="_blank" class="underline font-bold">Tentang Kami</a>). Jika file bagan diupload, gambar akan tampil sebagai diagram resmi. Jika belum diupload, web menampilkan status <em>"Tahap Pembaruan &amp; Pengesahan Periode Pelayanan"</em> secara elegan.</p>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-primary">Upload Gambar Bagan Struktur (PNG/JPG/WEBP)</label>
                            <input type="file" name="struktur_organisasi_image_file" accept="image/*" onchange="previewFile(this, 'struktur_preview'); document.getElementById('struktur_preview').classList.remove('hidden'); document.getElementById('struktur_placeholder')?.classList.add('hidden');" class="block w-full text-xs text-on-surface-variant file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-container cursor-pointer border border-outline-variant rounded-xl bg-surface-container-low p-1">
                            <span class="text-[11px] text-on-surface-variant block">Format gambar bagan horizontal / pohon susunan organisasi (maks 4MB).</span>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-primary">Atau URL Gambar Bagan Eksternal</label>
                            <input type="url" name="struktur_organisasi_image" value="{{ $contents['struktur_organisasi_image'] ?? '' }}" placeholder="https://... (opsional jika upload file langsung)" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant text-xs">
                        </div>
                    </div>
                </div>

                <!-- Roster Dewan Pendeta & Hamba Tuhan -->
                <div class="pt-6 border-t border-outline-variant/30 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-serif-display text-base font-bold text-primary flex items-center gap-2">
                                <span class="material-symbols-outlined text-secondary">badge</span>
                                <span>Dewan Pendeta &amp; Hamba Tuhan GBI Shekinah Glory</span>
                            </h4>
                            <p class="text-xs text-on-surface-variant">Daftar nama dan jenjang kependetaan hamba Tuhan yang melayani di gereja.</p>
                        </div>
                        <a href="{{ route('admin.majelis.index') }}" class="px-3 py-1.5 rounded-lg bg-primary text-white text-xs font-bold hover:bg-primary-container transition flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">edit</span>
                            <span>Kelola di Master Data Majelis</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/40 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-900 flex items-center justify-center font-bold text-xs shrink-0">
                                Pdt.
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-bold text-amber-800 uppercase block">Pendeta (Pdt.)</span>
                                <h5 class="text-xs font-bold text-primary truncate">Pdt. Bpk. Yusuf Labagai</h5>
                                <span class="text-[10px] text-on-surface-variant block truncate">Gembala Sidang</span>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/40 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-900 flex items-center justify-center font-bold text-xs shrink-0">
                                Pdm.
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-bold text-blue-800 uppercase block">Pendeta Muda (Pdm.)</span>
                                <h5 class="text-xs font-bold text-primary truncate">Pdm. Bpk. Merikh Labagai</h5>
                                <span class="text-[10px] text-on-surface-variant block truncate">Penggembalaan Jemaat</span>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/40 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-900 flex items-center justify-center font-bold text-xs shrink-0">
                                Pdp.
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-bold text-emerald-800 uppercase block">Pendeta Pratama (Pdp.)</span>
                                <h5 class="text-xs font-bold text-primary truncate">Pdp. Bpk. Semuel Suy</h5>
                                <span class="text-[10px] text-on-surface-variant block truncate">Ibadah Raya &amp; Komisi</span>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/40 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-900 flex items-center justify-center font-bold text-xs shrink-0">
                                Pdp.
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-bold text-purple-800 uppercase block">Pendeta Pratama (Pdp.)</span>
                                <h5 class="text-xs font-bold text-primary truncate">Pdp. Ibu Loura Hotan</h5>
                                <span class="text-[10px] text-on-surface-variant block truncate">Wanita &amp; Anak</span>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/40 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-rose-100 text-rose-900 flex items-center justify-center font-bold text-xs shrink-0">
                                Pdp.
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-bold text-rose-800 uppercase block">Pendeta Pratama (Pdp.)</span>
                                <h5 class="text-xs font-bold text-primary truncate">Pdp. Ibu Febi Nafi</h5>
                                <span class="text-[10px] text-on-surface-variant block truncate">Diakonia &amp; Doa</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= TAB: WARTA & PENGUMUMAN GEREJA ================= -->
        <div id="tab-warta" class="tab-content space-y-6 hidden">
            <!-- 1. PENGUMUMAN & BERITA GEREJA -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-5">
                <div class="border-b border-outline-variant/30 pb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 text-[10px] font-bold mb-1">
                            <span class="material-symbols-outlined text-[14px]">campaign</span>
                            <span>Berita, Agenda &amp; Pengumuman Gereja</span>
                        </div>
                        <h3 class="font-serif-display text-xl font-bold text-primary">Pengumuman &amp; Berita Gereja</h3>
                        <p class="text-xs text-on-surface-variant mt-0.5">Informasi penting, pendaftaran PPA/TK, jadwal doa khusus, dan kegiatan komisi.</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('admin.pengumuman.create') }}" class="px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                            <span>+ Buat Pengumuman Baru</span>
                        </a>
                        <a href="{{ route('admin.pengumuman.index') }}" class="px-3.5 py-2 rounded-xl bg-surface-container text-primary font-bold text-xs hover:bg-surface-container-high transition flex items-center gap-1.5 border border-outline-variant/30">
                            <span class="material-symbols-outlined text-[16px]">list</span>
                            <span>Kelola Semua Pengumuman</span>
                        </a>
                    </div>
                </div>

                <!-- Grid Pengumuman Terkini -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($latestPengumuman as $p)
                    <div class="p-4 rounded-xl border border-outline-variant/30 bg-surface-container-low/50 space-y-3 flex flex-col justify-between">
                        <div class="flex items-start gap-3">
                            <img src="{{ $p->gambar_url }}" alt="Flyer" class="w-16 h-16 rounded-lg object-cover shrink-0 border border-outline-variant/30">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase {{ $p->kategori_badge['bg'] }}">
                                        {{ $p->kategori_badge['label'] }}
                                    </span>
                                    @if($p->is_pinned)
                                    <span class="text-[10px] font-bold text-amber-700 flex items-center gap-0.5">
                                        <span class="material-symbols-outlined text-[13px]">push_pin</span>
                                        <span>Pinned</span>
                                    </span>
                                    @endif
                                </div>
                                <h4 class="font-bold text-xs text-primary truncate mt-1">{{ $p->judul }}</h4>
                                <span class="text-[10px] text-outline font-mono block">{{ $p->tanggal->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>

                        <p class="text-on-surface-variant text-[11px] line-clamp-2 leading-relaxed bg-white p-2.5 rounded-lg border border-outline-variant/20">
                            {{ $p->isi }}
                        </p>

                        <div class="flex items-center justify-between pt-1 text-[11px]">
                            <span class="text-[10px] uppercase font-bold {{ $p->status === 'aktif' ? 'text-emerald-700' : 'text-outline' }}">
                                Status: {{ $p->status }}
                            </span>
                            <a href="{{ route('admin.pengumuman.edit', $p->id) }}" class="px-2.5 py-1 rounded-lg bg-primary text-white font-bold hover:bg-primary-container transition flex items-center gap-1 text-[11px]">
                                <span class="material-symbols-outlined text-[13px]">edit</span>
                                <span>Edit Pengumuman</span>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-6 text-center text-outline text-xs">
                        Belum ada pengumuman gereja tercatat. Klik tombol "+ Buat Pengumuman Baru".
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- 2. WARTA IBADAH MINGGUAN -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-5">
                <div class="border-b border-outline-variant/30 pb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-900 text-[10px] font-bold mb-1">
                            <span class="material-symbols-outlined text-[14px]">menu_book</span>
                            <span>Renungan &amp; Liturgi Mingguan</span>
                        </div>
                        <h3 class="font-serif-display text-xl font-bold text-primary">Warta Ibadah Mingguan</h3>
                        <p class="text-xs text-on-surface-variant mt-0.5">Khotbah, pokok doa syafaat, ulang tahun jemaat sepekan, dan broadcast WA.</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('admin.warta.create') }}" class="px-3.5 py-2 rounded-xl bg-secondary text-white font-bold text-xs hover:bg-secondary/90 transition flex items-center gap-1.5 shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                            <span>+ Buat Edisi Warta Baru</span>
                        </a>
                        <a href="{{ route('admin.warta.index') }}" class="px-3.5 py-2 rounded-xl bg-surface-container text-primary font-bold text-xs hover:bg-surface-container-high transition flex items-center gap-1.5 border border-outline-variant/30">
                            <span class="material-symbols-outlined text-[16px]">list</span>
                            <span>Kelola Semua Warta</span>
                        </a>
                    </div>
                </div>

                <!-- Grid Edisi Warta Terkini -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($latestWarta as $w)
                    <div class="p-4 rounded-xl border border-outline-variant/30 bg-surface-container-low/50 space-y-3 flex flex-col justify-between">
                        <div class="flex items-start gap-3">
                            <img src="{{ $w->gambar_sampul_url }}" alt="Cover" class="w-16 h-16 rounded-lg object-cover shrink-0 border border-outline-variant/30">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[10px] font-mono font-bold text-outline">{{ $w->tanggal_warta->translatedFormat('d M Y') }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase {{ $w->status === 'terbit' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $w->status }}
                                    </span>
                                </div>
                                <h4 class="font-bold text-xs text-primary truncate mt-0.5">{{ $w->edisi }}</h4>
                                <p class="text-[11px] font-semibold text-secondary truncate">"{{ $w->tema_mingguan ?: '-' }}"</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-1 text-[11px]">
                            <a href="{{ route('admin.warta.show', $w->id) }}" class="text-secondary font-bold hover:underline flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">visibility</span>
                                <span>Lihat &amp; Broadcast</span>
                            </a>
                            <a href="{{ route('admin.warta.edit', $w->id) }}" class="px-2.5 py-1 rounded-lg bg-primary text-white font-bold hover:bg-primary-container transition flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px]">edit</span>
                                <span>Ganti Konten Warta</span>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-6 text-center text-outline text-xs">
                        Belum ada warta mingguan diterbitkan.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ================= TAB 3: KONTAK & JAM KERJA ================= -->
        <div id="tab-kontak" class="tab-content space-y-6 hidden">
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-6">
                <div class="border-b border-outline-variant/30 pb-4">
                    <h3 class="font-serif-display text-xl font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">contact_support</span>
                        <span>Informasi Kontak & Layanan Jemaat</span>
                    </h3>
                    <p class="text-xs text-on-surface-variant mt-1">Saluran komunikasi jemaat dan masyarakat umum.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary">Nomor WhatsApp Resmi</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-3 text-green-600 text-[18px]">chat</span>
                            <input type="text" name="whatsapp" value="{{ $contents['whatsapp'] ?? '+62 812-8899-7700' }}" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary">Email Resmi Sekretariat</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-3 text-secondary text-[18px]">mail</span>
                            <input type="email" name="email" value="{{ $contents['email'] ?? 'sekretariat@gbishekinahglory.org' }}" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">
                        </div>
                    </div>

                    <div class="space-y-2 sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary">Jam Operasional Kantor</label>
                        <textarea name="jam_kerja" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">{{ $contents['jam_kerja'] ?? 'Selasa – Sabtu: 09:00 – 17:00 WIB | Minggu: 07:00 – 14:00 WIB (Senin Libur)' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= TAB 4: LOKASI & GOOGLE MAPS ================= -->
        <div id="tab-lokasi" class="tab-content space-y-6 hidden">
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-6">
                <div class="border-b border-outline-variant/30 pb-4">
                    <h3 class="font-serif-display text-xl font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">pin_drop</span>
                        <span>Alamat & Lokasi Rumah Ibadah</span>
                    </h3>
                    <p class="text-xs text-on-surface-variant mt-1">Panduan alamat dan peta untuk jemaat yang akan beribadah onsite.</p>
                </div>

                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary">Alamat Lengkap Gereja</label>
                        <textarea name="alamat_lengkap" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant focus:border-secondary text-sm">{{ $contents['alamat_lengkap'] ?? 'Jl. Shekinah No. 7, Kebon Jeruk, Jakarta Barat 11530' }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary">Patokan Lokasi</label>
                        <input type="text" name="patokan_lokasi" value="{{ $contents['patokan_lokasi'] ?? '200m di belakang Graha Serbaguna, seberang Taman Asri' }}" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary">Link Google Maps (Share Link / Petunjuk Arah)</label>
                        <input type="url" name="google_maps_url" value="{{ $contents['google_maps_url'] ?? 'https://maps.app.goo.gl/uuA2zD3FbzX676JRA' }}" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant text-sm">
                        <p class="text-[11px] text-on-surface-variant">Contoh: https://maps.app.goo.gl/uuA2zD3FbzX676JRA (digunakan untuk tombol "Buka Rute")</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary">URL Embed Peta Google Maps (Iframe)</label>
                        <input type="text" name="google_maps_embed" value="{{ $contents['google_maps_embed'] ?? 'https://maps.google.com/maps?q=-10.1728964,123.5550841(GBI+SHEKINAH+GLORY+NAMOSAIN)&hl=id&z=17&output=embed' }}" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant text-sm font-mono text-xs">
                        <p class="text-[11px] text-on-surface-variant">URL sematan (embed) peta interaktif yang tampil langsung di halaman web.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= TAB 5: INFO UMUM ================= -->
        <div id="tab-umum" class="tab-content space-y-6 hidden">
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-6">
                <div class="border-b border-outline-variant/30 pb-4">
                    <h3 class="font-serif-display text-xl font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">church</span>
                        <span>Identitas Utama Gereja</span>
                    </h3>
                    <p class="text-xs text-on-surface-variant mt-1">Nama resmi sinode, tagline, dan ayat pondasi pelayanan.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary">Nama Gereja</label>
                        <input type="text" name="nama_gereja" value="{{ $contents['nama_gereja'] ?? 'GBI Shekinah Glory' }}" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant font-semibold text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary">Slogan Resmi Gereja (PRD v2.1)</label>
                        <input type="text" name="slogan" value="{{ $contents['slogan'] ?? '✦ GBI SHEKINAH GLORY • BE HOUSE OF GLORY ✦' }}" class="w-full px-4 py-2.5 rounded-xl border border-amber-400 bg-amber-50/50 font-bold text-amber-900 text-sm">
                        <span class="text-[11px] text-amber-800">Tampil di header, hero banner, dan identitas resmi profil gereja.</span>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary">Tagline Pelayanan</label>
                        <input type="text" name="tagline" value="{{ $contents['tagline'] ?? 'Menjadi Terang dan Tempat Kemuliaan Tuhan Hadir Nyata' }}" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary">Nama Gembala Sidang / Senior Pastor</label>
                        <input type="text" name="nama_gembala" value="{{ $contents['nama_gembala'] ?? 'Pdt. Yusuf Labagai' }}" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant text-sm font-semibold">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary">Tahun Berdiri Gereja</label>
                        <input type="text" name="tahun_berdiri" value="{{ $contents['tahun_berdiri'] ?? '2000' }}" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant text-sm font-semibold">
                    </div>

                    <div class="space-y-2 sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary">Ayat Kunci Pelayanan</label>
                        <textarea name="ayat_kunci" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant text-sm italic">{{ $contents['ayat_kunci'] ?? 'Bangkitlah, menjadi teranglah, sebab terangmu datang, dan kemuliaan TUHAN terbit atasmu. (Yesaya 60:1)' }}</textarea>
                    </div>

                    <!-- Media Sosial -->
                    <div class="pt-4 border-t border-outline-variant/30 sm:col-span-2 space-y-4">
                        <h4 class="font-serif-display text-base font-bold text-primary flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary">share</span>
                            <span>Tautan Media Sosial Resmi</span>
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-primary">Kanal YouTube</label>
                                <input type="url" name="youtube_url" value="{{ $contents['youtube_url'] ?? 'https://youtube.com' }}" placeholder="https://youtube.com/..." class="w-full px-3 py-2 rounded-lg border border-outline-variant text-xs">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-primary">Akun Instagram</label>
                                <input type="url" name="instagram_url" value="{{ $contents['instagram_url'] ?? 'https://instagram.com' }}" placeholder="https://instagram.com/..." class="w-full px-3 py-2 rounded-lg border border-outline-variant text-xs">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-primary">Halaman Facebook</label>
                                <input type="url" name="facebook_url" value="{{ $contents['facebook_url'] ?? 'https://facebook.com' }}" placeholder="https://facebook.com/..." class="w-full px-3 py-2 rounded-lg border border-outline-variant text-xs">
                            </div>
                        </div>
                    </div>

                    <!-- Logo Gereja -->
                    <div class="pt-6 border-t border-outline-variant/30 space-y-4 sm:col-span-2">
                        <h4 class="font-serif-display text-base font-bold text-primary flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary">image</span>
                            <span>Logo Resmi Gereja (Sudut Kiri Navbar & Dashboard)</span>
                        </h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 items-center">
                            <div class="sm:col-span-3 flex flex-col items-center sm:items-start">
                                <span class="block text-xs font-bold text-on-surface-variant mb-2">Preview Logo Saat Ini:</span>
                                @php
                                    $currentLogo = $contents['logo'] ?? null;
                                    if (!$currentLogo) {
                                        foreach (['png', 'jpg', 'jpeg', 'svg', 'webp'] as $ext) {
                                            if (file_exists(public_path("images/logo.$ext"))) {
                                                $currentLogo = asset("images/logo.$ext");
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                <div class="w-20 h-20 rounded-2xl border border-outline-variant bg-surface-container flex items-center justify-center p-2 shadow-sm overflow-hidden">
                                    <img id="logo_preview" src="{{ $currentLogo ?? '' }}" alt="Preview Logo" class="max-w-full max-h-full object-contain {{ $currentLogo ? '' : 'hidden' }}">
                                    <div id="logo_placeholder" class="{{ $currentLogo ? 'hidden' : '' }} flex flex-col items-center text-outline">
                                        <span class="material-symbols-outlined text-[32px]">church</span>
                                        <span class="text-[10px] text-center">Belum ada</span>
                                    </div>
                                </div>
                            </div>

                            <div class="sm:col-span-9 space-y-3">
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-primary">Upload File Logo Baru</label>
                                    <input type="file" name="logo_file" accept="image/png,image/jpeg,image/webp,image/svg+xml" onchange="previewFile(this, 'logo_preview'); document.getElementById('logo_preview').classList.remove('hidden'); document.getElementById('logo_placeholder')?.classList.add('hidden');" class="block w-full text-xs text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-container file:text-secondary-fixed hover:file:bg-primary-container/80 cursor-pointer border border-outline-variant rounded-xl bg-surface-container-low p-1">
                                    <span class="text-[11px] text-on-surface-variant block">Format: PNG, JPG, SVG, atau WEBP transparan (disarankan rasio 1:1, maks 4MB). Logo akan langsung tampil di pojok kiri navbar publik & dashboard admin.</span>
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-primary">Atau URL Logo Eksternal</label>
                                    <input type="text" name="logo" value="{{ $contents['logo'] ?? '' }}" placeholder="https://... atau simpan kosong jika upload file langsung di atas" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant text-xs">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Submit Button -->
        <div class="sticky bottom-6 z-20 bg-white/95 backdrop-blur-md rounded-2xl p-4 shadow-xl border border-outline-variant/40 flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-xs text-on-surface-variant hidden sm:flex">
                <span class="material-symbols-outlined text-green-600 text-[18px]">cloud_done</span>
                <span>Perubahan langsung tersinkronisasi ke seluruh halaman website publik.</span>
            </div>
            <button type="submit" class="w-full sm:w-auto px-8 py-3 rounded-xl bg-primary text-white font-bold text-sm shadow-md hover:bg-primary-container transition flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[20px]">save</span>
                <span>Simpan Semua Perubahan Konten</span>
            </button>
        </div>
    </form>
</div>

<script>
function switchTab(tabId) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    // Reset buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-primary', 'text-white', 'shadow-sm');
        btn.classList.add('bg-white', 'text-on-surface-variant');
    });

    // Show active tab
    document.getElementById(tabId).classList.remove('hidden');
    // Active button
    const activeBtn = document.getElementById('btn-' + tabId);
    activeBtn.classList.remove('bg-white', 'text-on-surface-variant');
    activeBtn.classList.add('bg-primary', 'text-white', 'shadow-sm');
}

function previewFile(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function tambahPoinMisi() {
    const container = document.getElementById('misi-container');
    const count = container.children.length + 1;
    const div = document.createElement('div');
    div.className = 'flex items-center gap-3 p-3 rounded-xl bg-surface-container-low border border-outline-variant/40 misi-item';
    div.innerHTML = `
        <div class="w-7 h-7 rounded-full bg-secondary-container text-secondary flex items-center justify-center text-xs font-bold shrink-0">
            ${count}
        </div>
        <input type="text" name="misi[]" class="flex-1 px-3 py-2 rounded-lg border border-outline-variant focus:border-secondary text-sm bg-white" placeholder="Tuliskan butir misi baru..." autofocus>
        <button type="button" onclick="hapusPoinMisi(this)" class="w-9 h-9 rounded-lg text-red-500 hover:bg-red-50 flex items-center justify-center transition" title="Hapus butir misi">
            <span class="material-symbols-outlined text-[20px]">delete</span>
        </button>
    `;
    container.appendChild(div);
}

function hapusPoinMisi(btn) {
    const item = btn.closest('.misi-item');
    if (document.querySelectorAll('.misi-item').length > 1) {
        item.remove();
        // Recalculate numbers
        document.querySelectorAll('#misi-container .misi-item').forEach((el, index) => {
            el.querySelector('.rounded-full').textContent = index + 1;
        });
    } else {
        alert('Setidaknya harus menyisakan 1 butir misi.');
    }
}
</script>
@endsection
