@extends('layouts.public')

@section('title', 'GBI Shekinah Glory - Menjadi Terang dan Tempat Kemuliaan Tuhan Hadir Nyata')

@section('content')
<div class="flex flex-col w-full overflow-hidden">

    <!-- ======================================================== -->
    <!-- 1. HERO SECTION (BANNER UTAMA)                           -->
    <!-- ======================================================== -->
    <section class="relative min-h-[92vh] sm:min-h-screen w-full flex items-center justify-center -mt-20 pt-28 pb-16 px-4 sm:px-6 lg:px-8 text-white overflow-hidden">
        <!-- Background Interior Gereja Megah dengan Dark Overlay Vignette -->
        <div class="absolute inset-0 bg-cover bg-center scale-105 transition-transform duration-1000" 
             style="background-image: url('{{ $contents['hero_image'] ?? asset('images/gedung_gbi_shekinah_glory.jpg') }}');">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/60 to-black/90"></div>

        <!-- Atmospheric Glows -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[600px] h-[350px] bg-amber-500/10 rounded-full blur-[140px] pointer-events-none"></div>

        <!-- Content Container (Center Aligned) -->
        <div class="relative z-10 max-w-4xl mx-auto flex flex-col items-center text-center space-y-6 pt-10" data-aos="fade-up" data-aos-duration="900">
            <!-- Kicker / Badge Atas Teks Beraksen Emas -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span class="text-3xs sm:text-2xs font-bold uppercase tracking-widest text-amber-300">
                    {{ $contents['slogan'] ?? '✦ GBI SHEKINAH GLORY • BE HOUSE OF GLORY ✦' }}
                </span>
            </div>

            <!-- Judul Utama & Tagline -->
            <div class="space-y-3">
                <h1 class="font-serif-display text-4xl sm:text-6xl lg:text-7xl font-bold tracking-tight text-white leading-[1.1] drop-shadow-md">
                    {{ $contents['hero_title'] ?? 'GBI Shekinah Glory' }}
                    <span class="block text-2xl sm:text-4xl lg:text-5xl font-normal text-amber-200 mt-1">Namosain Kupang</span>
                </h1>
                <p class="font-serif-display text-base sm:text-xl italic text-amber-100/90 leading-snug max-w-2xl mx-auto">
                    {{ $contents['hero_subtitle'] ?? '"Melayani dengan Kasih, Bertumbuh dalam Hadirat-Nya"' }}
                </p>
            </div>

            <!-- Narasi Singkat Ramah -->
            <p class="text-xs sm:text-sm text-white/80 max-w-2xl mx-auto leading-relaxed">
                {{ $contents['hero_description'] ?? 'Sejak 2008, kami menjadi rumah rohani bagi keluarga di Kota Kupang — merayakan hadirat Tuhan lewat ibadah yang intim, pembinaan firman yang murni, dan persekutuan yang saling menguatkan.' }}
            </p>

            <!-- Pill Ibadah Terdekat -->
            <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-black/45 backdrop-blur-md border border-white/25 text-xs sm:text-sm text-white shadow-lg">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                <span>
                    <strong class="text-emerald-300">Ibadah Terdekat:</strong> 
                    @if($ibadahTerdekat)
                        {{ $ibadahTerdekat->nama_ibadah }} • {{ $ibadahTerdekat->hari }}, {{ $ibadahTerdekat->jam }} WITA
                    @else
                        Ibadah Raya • Minggu, 08:00 WITA
                    @endif
                </span>
            </div>

            <!-- Action Buttons Berdampingan -->
            <div class="flex flex-wrap items-center justify-center gap-3.5 pt-2">
                <a href="#jadwal-ibadah" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm shadow-xl transition transform active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                    <span>Lihat Jadwal Ibadah</span>
                </a>
                <a href="{{ route('public.pendaftaran') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white hover:bg-surface-container text-primary font-bold text-xs sm:text-sm shadow-xl transition transform active:scale-95 border border-white/50">
                    <span class="material-symbols-outlined text-[18px] text-blue-600">how_to_reg</span>
                    <span>Pendaftaran Layanan</span>
                </a>
            </div>

            <!-- Stat Bar di Bagian Bawah Banner (Tanpa Tulisan COOL) -->
            <div class="w-full max-w-2xl pt-10 mt-6 border-t border-white/15 grid grid-cols-3 gap-4 text-center">
                <div class="space-y-0.5">
                    <span class="font-bold text-2xl sm:text-4xl text-white block font-serif-display">2000</span>
                    <span class="text-3xs sm:text-2xs uppercase tracking-wider text-white/70 font-semibold block">Tahun Berdiri</span>
                </div>
                <div class="space-y-0.5 border-x border-white/15">
                    <span class="font-bold text-2xl sm:text-4xl text-white block font-serif-display">{{ $totalWilayah ?? 12 }}</span>
                    <span class="text-3xs sm:text-2xs uppercase tracking-wider text-white/70 font-semibold block">Wilayah Pelayanan</span>
                </div>
                <div class="space-y-0.5">
                    <span class="font-bold text-2xl sm:text-4xl text-white block font-serif-display">{{ $totalJemaat ?? 155 }}</span>
                    <span class="text-3xs sm:text-2xs uppercase tracking-wider text-white/70 font-semibold block">Jiwa Jemaat</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================================================== -->
    <!-- 2. TENTANG KAMI, SEJARAH, VISI MISI & WILAYAH            -->
    <!-- ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full space-y-16" id="tentang-kami">
        <!-- Section Header -->
        <div class="flex flex-col items-center text-center" data-aos="fade-up">
            <span class="text-xs font-bold uppercase tracking-widest text-amber-600 mb-2">PROFIL GEREJA</span>
            <h2 class="font-serif-display text-3xl sm:text-4xl font-bold text-primary">Sejarah & Identitas Kami</h2>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-2 max-w-xl">
                Perjalanan iman dan pelayanan kasih bagi jemaat di Kota Kupang dalam hadirat kemuliaan Tuhan.
            </p>
            <div class="w-16 h-1 bg-amber-500 rounded-full mt-4"></div>
        </div>

        <!-- Baris 1: Narasi Sejarah, Visi Misi Simpel & Foto Gereja -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
            <!-- Kolom Kiri: Narasi Sejarah, Visi Misi & Gembala (7 Cols) -->
            <div class="lg:col-span-7 space-y-6" data-aos="fade-right">
                <div class="space-y-3.5 text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                    <h3 class="font-serif-display text-2xl font-bold text-primary">Bertumbuh dalam Kasih, Mengakar dalam Kebenaran</h3>
                    <p>
                        <strong>Gereja Bethel Indonesia Shekinah Glory Kupang-NTT</strong> terletak di Jl. Ikan Arwana, RT. 004 / RW. 002, Kelurahan Namosain, Kecamatan Alak, Kota Kupang. Gereja ini didirikan pada tanggal <strong>6 Oktober 2000</strong> dengan jemaat awal sebanyak 108 keluarga, berlandaskan komitmen teguh untuk menghadirkan hadirat kemuliaan Tuhan (<em>Shekinah</em>) dan menjawab amanat agung Kristus.
                    </p>
                    <p>
                        Kini persekutuan jemaat telah bertumbuh menaungi <strong>{{ $totalJemaat ?? 823 }} jiwa jemaat terdaftar</strong> yang tersebar di 12 sub-wilayah pelayanan (terdiri dari <strong>{{ $kategorialStats['kaum_bapak'] ?? 209 }} Kaum Bapak</strong>, <strong>{{ $kategorialStats['kaum_ibu'] ?? 216 }} Kaum Ibu</strong>, <strong>{{ $kategorialStats['pemuda_remaja'] ?? 270 }} Pemuda & Remaja</strong>, serta <strong>{{ $kategorialStats['anak'] ?? 128 }} Anak-anak ABI</strong>). Selain pelayanan ibadah, gereja juga aktif melayani pembinaan generasi melalui <strong>Pusat Pengembangan Anak Holistik (PPA)</strong> dan lembaga pendidikan usia dini <strong>Tiranus Kindergarten Christian School</strong> yang telah meluluskan lebih dari 300 alumni.
                    </p>
                </div>

                <!-- Blok VISI & MISI Dibuat Simpel & Bersih -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                    <!-- Kartu Visi -->
                    <div class="p-5 rounded-2xl bg-amber-50/50 border border-amber-200/70 space-y-2">
                        <div class="flex items-center gap-2 text-amber-700 font-bold text-xs uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[18px]">lightbulb</span>
                            <span>VISI GEREJA</span>
                        </div>
                        <p class="text-xs text-primary leading-relaxed italic">
                            "Menjadi rumah kemuliaan Tuhan yang menjawab amanat agung Tuhan Yesus Kristus sebagai gereja misionaris apostolik dan profetik."
                        </p>
                    </div>

                    <!-- Kartu Misi -->
                    <div class="p-5 rounded-2xl bg-amber-50/50 border border-amber-200/70 space-y-2">
                        <div class="flex items-center gap-2 text-amber-700 font-bold text-xs uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[18px]">menu_book</span>
                            <span>MISI PELAYANAN</span>
                        </div>
                        <ul class="text-xs text-on-surface-variant space-y-1.5">
                            <li class="flex items-start gap-1.5">
                                <span class="text-amber-600 text-xs mt-0.5">•</span>
                                <span>Menjangkau & memperlengkapi jemaat dengan pelayanan 5 Jawatan.</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="text-amber-600 text-xs mt-0.5">•</span>
                                <span>Memenuhi 5 panggilan gereja (Menyembah, Bersekutu, Bersaksi, Melayani, Memuridkan).</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="text-amber-600 text-xs mt-0.5">•</span>
                                <span>Mengutus jemaat menjadi saksi Kristus yang efektif (Agent of Change).</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Kartu Gembala Sidang Simpel -->
                <div class="p-4 rounded-2xl bg-white border border-outline-variant/30 shadow-2xs flex items-center gap-4 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-full bg-amber-50 border border-amber-200 text-amber-800 flex items-center justify-center shrink-0 shadow-2xs">
                        <span class="material-symbols-outlined text-[24px]">church</span>
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-bold text-sm sm:text-base text-primary truncate">
                            {{ $contents['nama_gembala'] ?? 'Pdt. Yusuf Labagai' }}
                        </h4>
                        <span class="text-2xs text-outline block">Pendeta (Pdt.) / Gembala Sidang GBI Shekinah Glory</span>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Foto Sanctuary Tunggal yang Bersih & Megah (5 Cols) -->
            <div class="lg:col-span-5" data-aos="fade-left">
                <div class="relative rounded-3xl overflow-hidden shadow-xl border border-outline-variant/30 aspect-[4/3] sm:aspect-square group bg-primary">
                    <img src="{{ $contents['sejarah_image'] ?? asset('images/gedung_gbi_shekinah_glory.jpg') }}" 
                         alt="Gedung GBI Shekinah Glory Namosain" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/95 via-primary/30 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 p-4 rounded-2xl bg-black/45 backdrop-blur-md border border-white/20 text-white space-y-1">
                        <span class="text-2xs font-bold text-amber-300 uppercase tracking-widest block">SEJAK 6 OKTOBER 2000</span>
                        <h4 class="font-serif-display text-base font-bold text-white">Rumah Rohani di Kota Kupang</h4>
                        <p class="text-2xs text-white/80">"Bangkitlah, menjadi teranglah, sebab terangmu datang, dan kemuliaan TUHAN terbit atasmu." (Yesaya 60:1)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris 2: Sebaran Wilayah Pelayanan & Jumlah Jemaat (Grid Modern Tanpa Kata COOL) -->
        <div class="pt-8 border-t border-outline-variant/20 space-y-6" data-aos="fade-up">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="font-serif-display font-bold text-xl sm:text-2xl text-primary">Sebaran Wilayah Pelayanan Jemaat</h3>
                    <p class="text-xs sm:text-sm text-on-surface-variant">Komunitas persekutuan jemaat yang tersebar di wilayah Namosain, Osmok, dan Rumah 7.</p>
                </div>
                <span class="px-3.5 py-1.5 rounded-full bg-amber-100 text-amber-900 text-xs font-bold self-start sm:self-auto">
                    {{ $totalWilayah ?? 12 }} Sub-Wilayah • {{ $totalJemaat ?? 155 }} Jiwa Jemaat
                </span>
            </div>

            <!-- Grid Kartu Wilayah Modern (Seluruh 12 Sub-Wilayah) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($wilayahList as $index => $wilayah)
                <div class="bg-white rounded-2xl p-4 border border-outline-variant/25 shadow-2xs hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-200/50 text-amber-700 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[18px]">location_on</span>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-xs text-primary truncate">{{ $wilayah->nama_wilayah }}</h4>
                            <span class="text-3xs text-outline block truncate">Ketua: {{ $wilayah->ketua_wilayah ?? 'Sekretariat' }}</span>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="font-serif-display text-base font-bold text-primary block leading-none">{{ $wilayah->jemaat_count }}</span>
                        <span class="text-3xs uppercase tracking-wider text-outline font-semibold">Jiwa</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ======================================================== -->
    <!-- 3. JADWAL IBADAH & PETUGAS PELAYAN (SERVICE ROTA)         -->
    <!-- ======================================================== -->
    <section class="bg-surface-container-low/50 py-20 border-y border-outline-variant/20" id="jadwal-ibadah">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <!-- Header Jadwal -->
            <div class="flex flex-col items-center text-center" data-aos="fade-up">
                <span class="text-xs font-bold uppercase tracking-widest text-secondary mb-2">Waktu Kebersamaan</span>
                <h2 class="font-serif-display text-3xl sm:text-4xl font-bold text-primary">Jadwal Kebaktian Rutin</h2>
                <p class="text-xs sm:text-sm text-on-surface-variant mt-2 max-w-lg">
                    Mari bersekutu, menyembah, dan bertumbuh bersama dalam hadirat Tuhan di rumah-Nya.
                </p>
                <div class="w-16 h-1 bg-secondary rounded-full mt-4"></div>
            </div>

            <!-- Grid Jadwal Kebaktian -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($jadwalList as $jadwal)
                <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between border border-outline-variant/30"
                     data-aos="fade-up" data-aos-delay="{{ 100 * $loop->iteration }}">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-secondary-container text-on-secondary-container">
                                {{ $jadwal->kategori }}
                            </span>
                            <span class="text-xs text-outline">{{ $jadwal->tipe_pelaksanaan }}</span>
                        </div>
                        <h3 class="font-serif-display text-xl font-bold text-primary mb-2">{{ $jadwal->nama_ibadah }}</h3>
                        <p class="text-xs text-on-surface-variant mb-4 leading-relaxed">{{ $jadwal->keterangan }}</p>
                    </div>

                    <div class="space-y-2 pt-4 border-t border-outline-variant/30 text-xs text-on-surface">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-secondary">event</span>
                            <span class="font-semibold">{{ $jadwal->hari }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-secondary">schedule</span>
                            <span class="font-semibold">{{ $jadwal->jam }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-secondary">pin_drop</span>
                            <span class="text-on-surface-variant">{{ $jadwal->lokasi_ruangan }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- JADWAL PETUGAS PELAYAN IBADAH (SERVICE ROTA) -->
            @if($upcomingRotas->isNotEmpty())
            <div class="pt-10 border-t border-outline-variant/30 space-y-8" data-aos="fade-up">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-secondary block">Roster Pelayanan Minggu Ini</span>
                        <h3 class="font-serif-display text-2xl sm:text-3xl font-bold text-primary">Petugas Pelayan Ibadah</h3>
                        <p class="text-xs sm:text-sm text-on-surface-variant mt-1">Daftar hamba Tuhan yang melayani pujian penyembahan, firman, dan kelancaran ibadah.</p>
                    </div>
                    <a href="{{ route('public.jadwal') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-secondary bg-white border border-outline-variant/30 rounded-xl hover:bg-surface-container transition shadow-2xs self-start sm:self-auto">
                        <span>Lihat Seluruh Roster</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($upcomingRotas as $rota)
                    <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-sm border border-outline-variant/30 space-y-4 hover:shadow-md transition">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-outline-variant/20 pb-3.5">
                            <div>
                                <span class="font-bold text-primary block text-lg">{{ $rota->sesiIbadah->nama_ibadah }}</span>
                                <span class="text-xs text-secondary font-semibold">{{ $rota->tanggal_pelayanan->translatedFormat('l, d F Y') }} • Pukul {{ $rota->sesiIbadah->jam }} WITA</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-secondary-container/40 text-secondary text-2xs font-bold self-start sm:self-auto">
                                {{ $rota->sesiIbadah->lokasi_ruangan }}
                            </span>
                        </div>

                        @if(!empty($rota->tema_ibadah))
                        <div class="p-3.5 rounded-xl bg-surface-container-low text-xs border border-outline-variant/15">
                            <span class="text-outline block text-2xs uppercase tracking-wider font-semibold">Tema Firman Tuhan:</span>
                            <strong class="text-primary font-serif-display text-base">"{{ $rota->tema_ibadah }}"</strong>
                            @if($rota->pengkhotbah)
                            <div class="flex items-center gap-1 text-secondary font-semibold mt-1">
                                <span class="material-symbols-outlined text-[14px]">person</span>
                                <span>Pengkhotbah: {{ $rota->pengkhotbah }}</span>
                            </div>
                            @endif
                        </div>
                        @endif

                        <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                            @foreach($rota->petugas->take(8) as $pt)
                            <div class="p-2.5 rounded-xl bg-surface-container-low/70 flex items-center gap-2.5 border border-outline-variant/10">
                                <div class="w-2 h-2 rounded-full bg-secondary shrink-0"></div>
                                <div class="min-w-0">
                                    <span class="text-[10px] text-outline block uppercase tracking-wider font-semibold leading-none">{{ $pt->posisi_label }}</span>
                                    <span class="font-bold text-primary truncate block text-xs mt-1">{{ $pt->nama_pelayan }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- ======================================================== -->
    <!-- PENGUMUMAN & BERITA GEREJA TERKINI                       -->
    <!-- ======================================================== -->
    @if(isset($pengumumanList) && $pengumumanList->count() > 0)
    <section class="py-14 bg-surface-container-low/60 border-b border-outline-variant/20" id="pengumuman-gereja">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4" data-aos="fade-up">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-secondary block mb-1">Informasi &amp; Berita Terkini</span>
                    <h2 class="font-serif-display text-2xl sm:text-3xl font-bold text-primary">Pengumuman Gereja</h2>
                    <p class="text-xs sm:text-sm text-on-surface-variant mt-0.5">Berita penting, pendaftaran pelayanan, aksi sosial, dan agenda kegiatan GBI Shekinah Glory.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-aos="fade-up">
                @foreach($pengumumanList as $item)
                <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-outline-variant/30 hover:shadow-lg transition-all flex flex-col justify-between">
                    <div>
                        <div class="relative w-full h-44 bg-surface-container overflow-hidden">
                            <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/15 to-transparent"></div>
                            
                            <div class="absolute top-3 left-3 flex items-center gap-1.5">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border shadow-xs {{ $item->kategori_badge['bg'] }}">
                                    {{ $item->kategori_badge['label'] }}
                                </span>
                                @if($item->is_pinned)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-400 text-gray-900 shadow-xs flex items-center gap-0.5">
                                    <span class="material-symbols-outlined text-[12px]">push_pin</span>
                                    <span>Penting</span>
                                </span>
                                @endif
                            </div>

                            <div class="absolute bottom-2.5 left-3 text-white text-2xs font-mono font-semibold">
                                {{ $item->tanggal->translatedFormat('d F Y') }}
                            </div>
                        </div>

                        <div class="p-5 space-y-2">
                            <h3 class="font-bold text-sm sm:text-base text-primary line-clamp-2 leading-snug">
                                {{ $item->judul }}
                            </h3>
                            <p class="text-xs text-on-surface-variant line-clamp-3 leading-relaxed whitespace-pre-line">
                                {{ $item->isi }}
                            </p>
                        </div>
                    </div>

                    @if($item->tanggal_kadaluarsa)
                    <div class="px-5 py-3 bg-surface-container-low/60 border-t border-outline-variant/20 flex items-center gap-1.5 text-[11px] text-outline font-medium">
                        <span class="material-symbols-outlined text-[14px] text-secondary">event_available</span>
                        <span>Berlaku s.d {{ $item->tanggal_kadaluarsa->translatedFormat('d M Y') }}</span>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- ======================================================== -->
    <!-- 4. WARTA JEMAAT (CARD KESAMPING DENGAN GAMBAR KONTEN)     -->
    <!-- ======================================================== -->
    <section class="py-20 bg-surface" id="warta-jemaat">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6" data-aos="fade-up">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-secondary block mb-1">Santapan Rohani & Renungan</span>
                    <h2 class="font-serif-display text-3xl sm:text-4xl font-bold text-primary">Warta Jemaat</h2>
                    <p class="text-xs sm:text-sm text-on-surface-variant mt-1 max-w-xl">
                        Tema firman mingguan, bacaan Alkitab, dan renungan rohani jemaat. Klik kartu untuk membaca naskah selengkapnya.
                    </p>
                </div>
                <a href="{{ route('public.warta') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white text-xs font-bold hover:bg-primary/90 transition shadow-sm self-start md:self-auto">
                    <span class="material-symbols-outlined text-[18px]">library_books</span>
                    <span>Seluruh Arsip Warta</span>
                </a>
            </div>

            <!-- Card Grid Warta Berjejer ke Samping (Mirip Format Jadwal tapi Ada Gambar Konten) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" data-aos="fade-up">
                @forelse($wartaCards as $warta)
                <a href="{{ route('public.warta', ['id' => $warta->id]) }}" 
                   class="group bg-white rounded-3xl overflow-hidden shadow-sm border border-outline-variant/30 hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                    
                    <div>
                        <!-- Cover Gambar Konten Warta -->
                        <div class="relative w-full h-48 bg-surface-container overflow-hidden">
                            <img src="{{ $warta->gambar_sampul_url }}" 
                                 alt="{{ $warta->tema_mingguan }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/25 to-transparent"></div>
                            
                            <!-- Badge Edisi Tanggal di atas Gambar -->
                            <div class="absolute top-3 left-3 px-3 py-1 rounded-full bg-black/60 backdrop-blur-md text-white text-2xs font-bold uppercase tracking-wider border border-white/20">
                                {{ $warta->tanggal_warta ? $warta->tanggal_warta->translatedFormat('d M Y') : 'Edisi Terbaru' }}
                            </div>

                            @if($loop->first)
                            <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-amber-500 text-white text-3xs font-bold uppercase tracking-wider shadow-sm">
                                Terkini
                            </div>
                            @endif

                            <!-- Ayat Bacaan di atas Gradient Gambar -->
                            @if(!empty($warta->ayat_tema))
                            <div class="absolute bottom-3 left-3 right-3 text-white">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-white/20 backdrop-blur-md text-2xs font-semibold">
                                    <span class="material-symbols-outlined text-[14px]">menu_book</span>
                                    <span>{{ $warta->ayat_tema }}</span>
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- Konten Teks Warta -->
                        <div class="p-6 space-y-3">
                            <h3 class="font-serif-display text-xl font-bold text-primary group-hover:text-secondary transition-colors leading-snug line-clamp-2">
                                {{ $warta->tema_mingguan ?? 'Teguh Berakar di Dalam Kristus' }}
                            </h3>

                            <p class="text-xs text-on-surface-variant line-clamp-3 leading-relaxed">
                                {{ Str::limit(strip_tags($warta->ringkasan_khotbah), 140) }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer Kartu Warta -->
                    <div class="px-6 pb-6 pt-2 border-t border-outline-variant/15 flex items-center justify-between text-xs font-bold text-secondary">
                        <span class="inline-flex items-center gap-1 group-hover:underline">
                            <span>Baca Selengkapnya</span>
                            <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </span>
                        @if($warta->file_pdf)
                        <span class="text-3xs text-outline font-semibold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px] text-red-500">picture_as_pdf</span>
                            <span>PDF</span>
                        </span>
                        @endif
                    </div>
                </a>
                @empty
                <div class="col-span-3 bg-white rounded-3xl p-10 border border-outline-variant/30 text-center space-y-3">
                    <span class="material-symbols-outlined text-5xl text-outline">newspaper</span>
                    <h4 class="font-serif-display text-xl font-bold text-primary">Warta Minggu Ini Sedang Dipersiapkan</h4>
                    <p class="text-xs text-on-surface-variant">Warta digital akan segera diperbarui oleh sekretariat gereja.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ======================================================== -->
    <!-- 5. AGENDA KEGIATAN & ACARA GEREJA SEPEKAN                -->
    <!-- ======================================================== -->
    <section class="py-20 bg-surface-container-low/40 border-t border-outline-variant/20" id="agenda-gereja">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6" data-aos="fade-up">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-secondary block mb-1">Rangkaian Acara &amp; Jadwal Sepekan</span>
                    <h2 class="font-serif-display text-3xl sm:text-4xl font-bold text-primary">Agenda Kegiatan Gereja</h2>
                    <p class="text-xs sm:text-sm text-on-surface-variant mt-1 max-w-xl">
                        Jadwal kegiatan persekutuan, kalender acara liturgi sepekan, dan perayaan syukur keluarga jemaat.
                    </p>
                </div>
            </div>

            <!-- Content Grid 12 Kolom: Card Agenda Kegiatan di Kiri, Kalender & Ultah di Kanan -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Sisi Kiri: Card-Card Agenda Kegiatan dengan GAMBAR KONTEN (7 Cols) -->
                <div class="lg:col-span-7 space-y-5" data-aos="fade-right">
                    <h3 class="font-serif-display font-bold text-xl text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">event_note</span>
                        <span>Agenda Kegiatan Sepekan</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Card Pengumuman 1: Doa Fajar (Dengan Gambar Konten) -->
                        <div class="group bg-white rounded-3xl overflow-hidden border border-outline-variant/30 shadow-2xs hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                            <div>
                                <div class="relative w-full h-36 bg-surface-container overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1544717302-de2939b7ef71?auto=format&fit=crop&w=600&q=80" 
                                         alt="Doa Fajar Bersama" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                                    <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded-full bg-blue-600/90 backdrop-blur-md text-white text-3xs font-bold uppercase tracking-wider">
                                        Doa Syafaat
                                    </span>
                                    <span class="absolute bottom-2.5 left-3 text-white text-2xs font-semibold flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">schedule</span>
                                        <span>Sabtu • 05:00 WITA</span>
                                    </span>
                                </div>

                                <div class="p-5 space-y-2">
                                    <h4 class="font-bold text-sm text-primary group-hover:text-secondary transition-colors">
                                        Doa Fajar & Mezbah Doa
                                    </h4>
                                    <p class="text-xs text-on-surface-variant leading-relaxed">
                                        Bersehati menaikkan doa permohonan dan ucapan syukur bagi kota, bangsa, serta keluarga jemaat.
                                    </p>
                                </div>
                            </div>
                            
                            <div class="px-5 pb-4 pt-2 border-t border-outline-variant/15 flex items-center gap-1.5 text-2xs text-outline">
                                <span class="material-symbols-outlined text-[14px] text-secondary">location_on</span>
                                <span>Gedung Gereja Namosain</span>
                            </div>
                        </div>

                        <!-- Card Pengumuman 2: Persekutuan Wilayah (Dengan Gambar Konten) -->
                        <div class="group bg-white rounded-3xl overflow-hidden border border-outline-variant/30 shadow-2xs hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                            <div>
                                <div class="relative w-full h-36 bg-surface-container overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&w=600&q=80" 
                                         alt="Persekutuan Wilayah" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                                    <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded-full bg-emerald-600/90 backdrop-blur-md text-white text-3xs font-bold uppercase tracking-wider">
                                        Wilayah Jemaat
                                    </span>
                                    <span class="absolute bottom-2.5 left-3 text-white text-2xs font-semibold flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                        <span>Pertengahan Pekan</span>
                                    </span>
                                </div>

                                <div class="p-5 space-y-2">
                                    <h4 class="font-bold text-sm text-primary group-hover:text-secondary transition-colors">
                                        Pertemuan Wilayah Jemaat
                                    </h4>
                                    <p class="text-xs text-on-surface-variant leading-relaxed">
                                        Persekutuan keluarga jemaat di 12 sub-wilayah (Namosain, Osmok, Rumah 7). Hubungi ketua wilayah untuk info lokasi.
                                    </p>
                                </div>
                            </div>

                            <div class="px-5 pb-4 pt-2 border-t border-outline-variant/15 flex items-center gap-1.5 text-2xs text-outline">
                                <span class="material-symbols-outlined text-[14px] text-secondary">groups</span>
                                <span>12 Sub-Wilayah Pelayanan</span>
                            </div>
                        </div>

                        <!-- Card Pengumuman 3: Tim Pujian & Multimedia (Dengan Gambar Konten) -->
                        <div class="group bg-white rounded-3xl overflow-hidden border border-outline-variant/30 shadow-2xs hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                            <div>
                                <div class="relative w-full h-36 bg-surface-container overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=600&q=80" 
                                         alt="Latihan Tim Musik" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                                    <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded-full bg-purple-600/90 backdrop-blur-md text-white text-3xs font-bold uppercase tracking-wider">
                                        Praise & Worship
                                    </span>
                                    <span class="absolute bottom-2.5 left-3 text-white text-2xs font-semibold flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">schedule</span>
                                        <span>Jumat • 18:00 WITA</span>
                                    </span>
                                </div>

                                <div class="p-5 space-y-2">
                                    <h4 class="font-bold text-sm text-primary group-hover:text-secondary transition-colors">
                                        Latihan Tim Pujian & Musik
                                    </h4>
                                    <p class="text-xs text-on-surface-variant leading-relaxed">
                                        Persiapan ibadah raya bagi pemusik, singers, worship leader, dan tim multimedia. Harap hadir tepat waktu.
                                    </p>
                                </div>
                            </div>

                            <div class="px-5 pb-4 pt-2 border-t border-outline-variant/15 flex items-center gap-1.5 text-2xs text-outline">
                                <span class="material-symbols-outlined text-[14px] text-secondary">music_note</span>
                                <span>Ruang Ibadah Utama</span>
                            </div>
                        </div>

                        <!-- Card Pengumuman 4: Sakramen & Warta Sepekan (Dengan Gambar Konten) -->
                        <a href="{{ route('public.warta') }}" 
                           class="group bg-white rounded-3xl overflow-hidden border border-outline-variant/30 shadow-2xs hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                            <div>
                                <div class="relative w-full h-36 bg-surface-container overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1499209974431-9dddcece7f88?auto=format&fit=crop&w=600&q=80" 
                                         alt="Warta Kegiatan Gereja" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                                    <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded-full bg-amber-600/90 backdrop-blur-md text-white text-3xs font-bold uppercase tracking-wider">
                                        Warta Lisan
                                    </span>
                                    <span class="absolute bottom-2.5 left-3 text-white text-2xs font-semibold flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">info</span>
                                        <span>Pekan Berjalan</span>
                                    </span>
                                </div>

                                <div class="p-5 space-y-2">
                                    <h4 class="font-bold text-sm text-primary group-hover:text-secondary transition-colors">
                                        Warta & Pengumuman Khusus
                                    </h4>
                                    <p class="text-xs text-on-surface-variant leading-relaxed line-clamp-2">
                                        {{ $latestWarta && !empty($latestWarta->pengumuman_kegiatan) ? Str::limit(strip_tags($latestWarta->pengumuman_kegiatan), 110) : 'Informasi baptisan kudus, penyerahan anak, dan pendaftaran keanggotaan jemaat baru.' }}
                                    </p>
                                </div>
                            </div>

                            <div class="px-5 pb-4 pt-2 border-t border-outline-variant/15 flex items-center justify-between text-2xs font-bold text-secondary">
                                <span>Lihat Rincian Warta</span>
                                <span class="material-symbols-outlined text-[15px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Sisi Kanan: Agenda Liturgi & Ulang Tahun Jemaat (5 Cols) -->
                <div class="lg:col-span-5 space-y-6" data-aos="fade-left">
                    <!-- AGENDA KALENDER GEREJA -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-outline-variant/30 space-y-4">
                        <div class="flex items-center justify-between border-b border-outline-variant/20 pb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-primary-container text-secondary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                                </div>
                                <h3 class="font-serif-display font-bold text-primary text-lg">Agenda Mendatang</h3>
                            </div>
                            <span class="text-2xs font-bold text-outline uppercase tracking-wider">Liturgi & Acara</span>
                        </div>

                        @if($upcomingEvents->isNotEmpty())
                        <div class="divide-y divide-outline-variant/15 space-y-1">
                            @foreach($upcomingEvents as $evt)
                            <div class="pt-3.5 first:pt-0 flex items-start gap-3.5">
                                <div class="w-12 h-12 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col items-center justify-center shrink-0 text-primary">
                                    <span class="text-2xs uppercase tracking-wider font-bold text-secondary leading-none">{{ $evt->tanggal_mulai->translatedFormat('M') }}</span>
                                    <span class="font-serif-display text-base font-bold leading-tight">{{ $evt->tanggal_mulai->format('d') }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-0.5 rounded text-3xs font-bold uppercase tracking-wider {{ $evt->warna_badge_css }}">
                                            {{ $evt->kategori_label }}
                                        </span>
                                        @if($evt->waktu_mulai)
                                        <span class="text-3xs text-outline font-semibold">{{ substr($evt->waktu_mulai, 0, 5) }} WITA</span>
                                        @endif
                                    </div>
                                    <h4 class="font-bold text-primary text-xs sm:text-sm truncate">{{ $evt->judul }}</h4>
                                    @if($evt->lokasi)
                                    <p class="text-2xs text-on-surface-variant flex items-center gap-1 mt-0.5">
                                        <span class="material-symbols-outlined text-[13px] text-outline">location_on</span>
                                        <span class="truncate">{{ $evt->lokasi }}</span>
                                    </p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-xs text-outline text-center py-4">Belum ada agenda khusus yang terjadwal dalam waktu dekat.</p>
                        @endif
                    </div>

                    <!-- ULANG TAHUN JEMAAT PEKAN INI -->
                    <div class="bg-gradient-to-br from-amber-500/10 via-white to-surface-container-low rounded-3xl p-6 shadow-sm border border-amber-500/20 space-y-4">
                        <div class="flex items-center justify-between border-b border-amber-500/15 pb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[20px]">cake</span>
                                </div>
                                <h3 class="font-serif-display font-bold text-primary text-lg">Ulang Tahun Jemaat</h3>
                            </div>
                            <span class="text-2xs font-bold text-amber-800 bg-amber-100 px-2.5 py-0.5 rounded-full">Pekan Ini</span>
                        </div>

                        <p class="text-2xs text-on-surface-variant leading-relaxed italic">
                            "TUHAN memberkati engkau dan melindungi engkau; TUHAN menyinari engkau dengan wajah-Nya dan memberi engkau kasih karunia." (Bilangan 6:24-25)
                        </p>

                        @if($ultahMingguIni->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-2.5 max-h-56 overflow-y-auto pr-1">
                            @foreach($ultahMingguIni as $ultah)
                            <div onclick="openBirthdayCard('{{ addslashes($ultah->nama_lengkap) }}', '{{ $ultah->umur_ke ?? '' }}', '{{ \Carbon\Carbon::parse($ultah->tanggal_lahir)->translatedFormat('d F') }}', '{{ $ultah->jenis_kelamin }}')" 
                                 class="p-2.5 rounded-xl bg-white/95 border border-amber-500/15 flex items-center justify-between gap-3 shadow-2xs cursor-pointer hover:border-amber-400 hover:shadow-xs transition-all group"
                                 title="Klik untuk membuka & download kartu ucapan pribadi">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1">
                                        <span class="font-bold text-xs text-primary group-hover:text-secondary transition truncate block">{{ $ultah->nama_lengkap }}</span>
                                        <span class="material-symbols-outlined text-[13px] text-amber-500 opacity-0 group-hover:opacity-100 transition-opacity">download</span>
                                    </div>
                                    <span class="text-2xs text-outline">{{ $ultah->wilayah->nama_wilayah ?? 'Keluarga Jemaat' }}</span>
                                </div>
                                <span class="px-2 py-1 rounded-lg bg-amber-50 text-amber-900 font-bold text-2xs whitespace-nowrap border border-amber-200/50 shrink-0">
                                    {{ \Carbon\Carbon::parse($ultah->tanggal_lahir)->translatedFormat('d F') }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        <p class="text-[10px] text-center text-amber-800 font-medium pt-1">* Klik nama jemaat untuk unduh kartu ucapan pribadi</p>
                        @else
                        <div class="p-4 rounded-xl bg-white/70 text-center text-xs text-outline">
                            Tidak ada jemaat yang berulang tahun pada pekan ini.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================================================== -->
    <!-- 6. DOKUMENTASI KEGIATAN GEREJA (GALERI FOTO)             -->
    <!-- ======================================================== -->
    <section class="py-20 bg-surface-container-low/40 border-t border-outline-variant/20" id="dokumentasi-gereja">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6" data-aos="fade-up">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-secondary mb-2 block">✦ Galeri &amp; Dokumentasi ✦</span>
                    <h2 class="font-serif-display text-3xl sm:text-4xl font-bold text-primary">Dokumentasi Kegiatan Gereja</h2>
                    <p class="text-xs sm:text-sm text-on-surface-variant mt-2 max-w-xl">
                        Merekam jejak pelayanan, ibadah raya, dan kehangatan persekutuan keluarga Allah di GBI Shekinah Glory Namosain.
                    </p>
                </div>
                <a href="{{ route('public.dokumentasi') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white text-xs font-bold hover:bg-primary-container transition shadow-sm shrink-0">
                    <span class="material-symbols-outlined text-[18px]">photo_library</span>
                    <span>Lihat Seluruh Galeri Foto</span>
                </a>
            </div>

            <!-- Grid Foto Tanpa Card Putih (Full Edge Photo + Gradient Title & Tanggal) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="100">
                @forelse($dokumentasiList ?? [] as $foto)
                <a href="{{ route('public.dokumentasi') }}" class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 aspect-[16/11] flex flex-col justify-between p-5 bg-surface-container-high block">
                    <!-- Background Photo -->
                    <img src="{{ $foto->foto_url }}" alt="{{ $foto->judul }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-108 transition-transform duration-700 ease-out">
                    
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent group-hover:from-black/90 group-hover:via-black/35 transition-colors duration-300"></div>

                    <!-- Top Badge: Kategori -->
                    <div class="relative z-10 flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full text-2xs font-bold bg-primary/80 backdrop-blur-md text-white border border-white/10 shadow-xs">
                            {{ $foto->kategori_kegiatan }}
                        </span>
                        <span class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-1 group-hover:translate-y-0">
                            <span class="material-symbols-outlined text-[18px]">arrow_outward</span>
                        </span>
                    </div>

                    <!-- Bottom Info: Tanggal & Judul (Tanpa Deskripsi, Tanpa Card Putih) -->
                    <div class="relative z-10 space-y-1">
                        <div class="flex items-center gap-1.5 text-secondary-fixed text-2xs font-semibold">
                            <span class="material-symbols-outlined text-[13px]">calendar_today</span>
                            <span>{{ $foto->tanggal_kegiatan->translatedFormat('d F Y') }}</span>
                        </div>
                        <h3 class="font-serif-display text-base sm:text-lg font-bold text-white group-hover:text-secondary-fixed transition-colors line-clamp-2 leading-snug drop-shadow-xs">
                            {{ $foto->judul }}
                        </h3>
                    </div>
                </a>
                @empty
                <div class="col-span-full py-12 text-center text-outline bg-white rounded-2xl border border-outline-variant/30">
                    <span class="material-symbols-outlined text-[42px] text-outline mb-2">photo_library</span>
                    <p class="text-sm font-bold text-primary">Belum Ada Dokumentasi Kegiatan</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ======================================================== -->
    <!-- 7. LOKASI & KONTAK                                       -->
    <!-- ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20" id="lokasi-kontak">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Lokasi & Map Column -->
            <div class="lg:col-span-6 flex flex-col space-y-6" data-aos="fade-right" data-aos-duration="850">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-secondary mb-1 block">Temukan Kami</span>
                    <h2 class="font-serif-display text-3xl font-bold text-primary">Lokasi Gereja</h2>
                </div>

                <div class="bg-white rounded-2xl overflow-hidden shadow-md border border-outline-variant/30 hover:shadow-xl transition-shadow">
                    <div class="w-full h-64 relative bg-surface-container">
                        <iframe 
                            id="gmap_beranda"
                            src="{{ $contents['google_maps_embed'] ?? 'https://maps.google.com/maps?q=-10.1728964,123.5550841(GBI+SHEKINAH+GLORY+NAMOSAIN)&hl=id&z=17&output=embed' }}" 
                            class="w-full h-full border-0" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Lokasi GBI Shekinah Glory">
                        </iframe>
                    </div>
                    <div class="p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <span class="font-semibold text-primary block text-sm">{{ $contents['nama_gereja'] ?? 'GBI Shekinah Glory Namosain' }}</span>
                            <p class="text-xs text-on-surface-variant max-w-sm">{{ $contents['alamat_lengkap'] ?? 'Jl. Ikan Arwana, RT. 004 / RW. 002, Kelurahan Namosain, Kecamatan Alak, Kota Kupang, NTT' }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" onclick="document.getElementById('gmap_beranda').src=document.getElementById('gmap_beranda').src" class="p-2 rounded-xl border border-outline-variant/40 bg-surface-container text-primary hover:bg-surface-container-high transition" title="Muat Ulang Peta jika koneksi berubah">
                                <span class="material-symbols-outlined text-[18px]">refresh</span>
                            </button>
                            <a href="{{ $contents['google_maps_url'] ?? 'https://maps.app.goo.gl/uuA2zD3FbzX676JRA' }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-xl bg-secondary text-white text-xs font-semibold hover:bg-secondary/90 transition flex items-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-[16px]">directions</span>
                                <span>Petunjuk Arah</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kontak & Sekretariat Column -->
            <div class="lg:col-span-6 flex flex-col space-y-6" data-aos="fade-left" data-aos-duration="850">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-secondary mb-1 block">Hubungi Pelayanan</span>
                    <h2 class="font-serif-display text-3xl font-bold text-primary">Sekretariat Jemaat</h2>
                </div>

                <div class="space-y-4">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contents['whatsapp'] ?? '6282247292194') }}" target="_blank" rel="noopener noreferrer" class="p-4 rounded-2xl bg-white border border-outline-variant/30 hover:border-secondary hover:shadow-md transition flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[24px]">chat</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="text-xs text-outline block">WhatsApp Sekretariat & Pastoral</span>
                            <span class="font-semibold text-primary truncate">{{ $contents['whatsapp'] ?? '+62 822-4729-2194' }}</span>
                        </div>
                        <span class="material-symbols-outlined text-secondary">arrow_forward</span>
                    </a>

                    <a href="mailto:{{ $contents['email'] ?? 'gbishekinahglory16@gmail.com' }}" class="p-4 rounded-2xl bg-white border border-outline-variant/30 hover:border-secondary hover:shadow-md transition flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[24px]">mail</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="text-xs text-outline block">Email Sekretariat</span>
                            <span class="font-semibold text-primary truncate">{{ $contents['email'] ?? 'gbishekinahglory16@gmail.com' }}</span>
                        </div>
                        <span class="material-symbols-outlined text-secondary">arrow_forward</span>
                    </a>

                    <div class="p-4 rounded-2xl bg-surface-container-low flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center shrink-0 text-primary">
                            <span class="material-symbols-outlined text-[24px]">schedule</span>
                        </div>
                        <div class="text-xs text-on-surface-variant space-y-1">
                            <span class="font-semibold text-primary block text-sm">Jam Pelayanan Kantor</span>
                            <p><strong>Selasa – Sabtu:</strong> 10:00 – 16:00 WITA</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Kartu Ucapan Ulang Tahun Pribadi (PRD v2.1 AC-R03) -->
<div id="birthdayCardModal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative max-w-lg w-full bg-white rounded-3xl overflow-hidden shadow-2xl space-y-4 p-6 sm:p-8 animate-in fade-in duration-200">
        <button type="button" onclick="closeBirthdayCard()" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-surface-container hover:bg-surface-container-high text-outline flex items-center justify-center transition">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>

        <!-- Preview Kartu Ucapan Visual -->
        <div id="cardVisualPreview" class="relative rounded-2xl overflow-hidden p-6 sm:p-8 text-center text-white bg-gradient-to-br from-primary via-primary-container to-secondary border-2 border-secondary-fixed/40 shadow-inner space-y-4">
            <div class="space-y-1">
                <span class="inline-block text-2xs uppercase tracking-widest text-secondary-fixed font-bold">
                    ✦ GBI SHEKINAH GLORY • BE HOUSE OF GLORY ✦
                </span>
                <h3 class="font-serif-display text-lg sm:text-xl font-bold tracking-wide text-secondary-fixed">
                    SELAMAT HARI ULANG TAHUN
                </h3>
            </div>

            <div class="py-2 space-y-1 border-y border-white/20">
                <span class="text-xs text-white/80" id="cardSalutation">Kepada Terkasih:</span>
                <h2 id="cardPersonName" class="font-serif-display text-2xl sm:text-3xl font-bold text-white tracking-wide">
                    Nama Jemaat
                </h2>
                <div class="inline-block px-3 py-1 rounded-full bg-secondary text-white font-bold text-xs" id="cardAgeBadge">
                    HUT ke-XX
                </div>
            </div>

            <p class="text-xs sm:text-sm text-secondary-fixed font-serif italic leading-relaxed px-2">
                "TUHAN memberkati engkau dan melindungi engkau; TUHAN menyinari engkau dengan wajah-Nya dan memberi engkau kasih karunia; TUHAN menghadapkan wajah-Nya kepadamu dan memberi engkau damai sejahtera."
            </p>
            <span class="text-2xs text-white/70 block font-sans font-semibold">(Bilangan 6:24-26)</span>

            <div class="pt-2 text-2xs text-white/60 border-t border-white/10">
                Kiranya hikmat, kesehatan, sukacita, dan penyertaan Tuhan senantiasa melimpah.<br>
                <strong>— Gembala Sidang & Majelis GBI Shekinah Glory</strong>
            </div>
        </div>

        <!-- Hidden Canvas for Exporting Image -->
        <canvas id="exportBirthdayCanvas" width="1080" height="1080" class="hidden"></canvas>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="button" onclick="closeBirthdayCard()" class="px-4 py-2 text-xs font-semibold text-outline hover:bg-surface-container rounded-xl transition">
                Tutup
            </button>
            <button type="button" onclick="downloadBirthdayCard()" class="px-5 py-2.5 bg-secondary hover:bg-secondary/90 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">download</span>
                <span>Download Kartu Ucapan (.PNG)</span>
            </button>
        </div>
    </div>
</div>

<script>
let currentBirthdayData = { name: '', age: '', day: '', gender: '' };

function openBirthdayCard(name, age, day, gender) {
    currentBirthdayData = { name, age, day, gender };
    
    const salutation = gender === 'L' ? 'Bapak / Saudara' : 'Ibu / Saudari';
    document.getElementById('cardSalutation').textContent = `Kepada yang Terkasih ${salutation}:`;
    document.getElementById('cardPersonName').textContent = name;
    document.getElementById('cardAgeBadge').textContent = age ? `HUT ke-${age} Tahun (${day})` : `Hari Ulang Tahun (${day})`;

    document.getElementById('birthdayCardModal').classList.remove('hidden');
}

function closeBirthdayCard() {
    document.getElementById('birthdayCardModal').classList.add('hidden');
}

function downloadBirthdayCard() {
    const canvas = document.getElementById('exportBirthdayCanvas');
    const ctx = canvas.getContext('2d');
    const w = canvas.width;
    const h = canvas.height;

    // Background Gradient (Deep Navy to Rich Gold)
    const bgGrad = ctx.createLinearGradient(0, 0, w, h);
    bgGrad.addColorStop(0, '#001428');
    bgGrad.addColorStop(0.6, '#0f2942');
    bgGrad.addColorStop(1, '#775a19');
    ctx.fillStyle = bgGrad;
    ctx.fillRect(0, 0, w, h);

    // Decorative Gold Border
    ctx.strokeStyle = '#e9c176';
    ctx.lineWidth = 14;
    ctx.strokeRect(40, 40, w - 80, h - 80);

    ctx.strokeStyle = 'rgba(255, 222, 165, 0.4)';
    ctx.lineWidth = 4;
    ctx.strokeRect(55, 55, w - 110, h - 110);

    // Header Tagline
    ctx.textAlign = 'center';
    ctx.fillStyle = '#ffdea5';
    ctx.font = 'bold 26px sans-serif';
    ctx.fillText('✦ GBI SHEKINAH GLORY • BE HOUSE OF GLORY ✦', w / 2, 130);

    // Main Greeting
    ctx.font = 'bold 52px serif';
    ctx.fillText('SELAMAT HARI ULANG TAHUN', w / 2, 210);

    // Salutation
    const salutation = currentBirthdayData.gender === 'L' ? 'Bapak / Saudara' : 'Ibu / Saudari';
    ctx.fillStyle = 'rgba(255, 255, 255, 0.85)';
    ctx.font = 'normal 32px sans-serif';
    ctx.fillText(`Kepada yang terkasih dalam Kristus:`, w / 2, 320);

    // Person Name
    ctx.fillStyle = '#ffffff';
    ctx.font = 'bold 64px serif';
    ctx.fillText(currentBirthdayData.name, w / 2, 400);

    // Age Badge pill
    const badgeText = currentBirthdayData.age ? `Ulang Tahun yang ke-${currentBirthdayData.age}` : 'Selamat Hari Ulang Tahun';
    ctx.font = 'bold 30px sans-serif';
    const textWidth = ctx.measureText(badgeText).width;
    
    ctx.fillStyle = '#775a19';
    ctx.beginPath();
    ctx.roundRect((w - textWidth - 60) / 2, 450, textWidth + 60, 56, 28);
    ctx.fill();
    ctx.strokeStyle = '#ffdea5';
    ctx.lineWidth = 2;
    ctx.stroke();

    ctx.fillStyle = '#ffffff';
    ctx.fillText(badgeText, w / 2, 488);

    // Scripture Verse Box
    ctx.fillStyle = 'rgba(0, 20, 40, 0.4)';
    ctx.beginPath();
    ctx.roundRect(100, 560, w - 200, 310, 24);
    ctx.fill();
    ctx.strokeStyle = 'rgba(233, 193, 118, 0.3)';
    ctx.stroke();

    ctx.fillStyle = '#ffdea5';
    ctx.font = 'italic 30px serif';
    ctx.fillText('"TUHAN memberkati engkau dan melindungi engkau;', w / 2, 630);
    ctx.fillText('TUHAN menyinari engkau dengan wajah-Nya', w / 2, 680);
    ctx.fillText('dan memberi engkau kasih karunia;', w / 2, 730);
    ctx.fillText('TUHAN menghadapkan wajah-Nya kepadamu', w / 2, 780);
    ctx.fillText('dan memberi engkau damai sejahtera."', w / 2, 830);

    ctx.fillStyle = 'rgba(255, 255, 255, 0.7)';
    ctx.font = 'bold 24px sans-serif';
    ctx.fillText('— Bilangan 6:24-26 —', w / 2, 890);

    // Footer
    ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
    ctx.font = 'normal 24px sans-serif';
    ctx.fillText('Doa & Berkat dari:', w / 2, 960);
    ctx.fillStyle = '#ffdea5';
    ctx.font = 'bold 26px serif';
    ctx.fillText('Gembala Sidang & Seluruh Majelis Jemaat GBI Shekinah Glory', w / 2, 1000);

    // Export & Download
    const dataUrl = canvas.toDataURL('image/png');
    const safeName = currentBirthdayData.name.replace(/[^a-zA-Z0-9]/g, '_');
    const link = document.createElement('a');
    link.download = `Kartu_Ucapan_HUT_${safeName}.png`;
    link.href = dataUrl;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection
