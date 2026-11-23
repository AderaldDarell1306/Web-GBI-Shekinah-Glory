@extends('layouts.public')

@section('title', 'Tentang Kami & Sejarah - GBI Shekinah Glory')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
        <span class="text-xs font-bold uppercase tracking-widest text-secondary mb-2 block">Profil & Sejarah Gereja</span>
        <h1 class="font-serif-display text-4xl sm:text-5xl font-bold text-primary mb-4">Perjalanan Kasih & Kemuliaan Tuhan</h1>
        <p class="text-base text-on-surface-variant">Mengenal visi, panggilan, dan perjalanan pelayanan GBI Shekinah Glory sejak dirintis hingga hari ini.</p>
        <div class="w-20 h-1 bg-secondary mx-auto mt-6 rounded-full"></div>
    </div>

    <!-- Sejarah Detail -->
    <div class="bg-white rounded-2xl p-8 lg:p-12 shadow-sm border border-outline-variant/30 mb-12" data-aos="fade-up" data-aos-delay="100">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-7 space-y-4">
                <span class="px-3 py-1 rounded-full bg-secondary-container text-secondary text-xs font-bold uppercase">Sejarah Pelayanan • Sejak 6 Oktober 2000</span>
                <h2 class="font-serif-display text-3xl font-bold text-primary">Bertumbuh dalam Kasih, Mengakar dalam Kebenaran</h2>
                <p class="text-on-surface-variant leading-relaxed text-justify">
                    Gereja Bethel Indonesia Shekinah Glory Kupang-NTT terletak di Jl. Ikan Arwana, RT. 004 / RW. 002, Kelurahan Namosain, Kecamatan Alak, Kota Kupang, Nusa Tenggara Timur. Gereja ini didirikan pada tanggal <strong>6 Oktober 2000</strong> dengan jemaat mula-mula sebanyak 108 keluarga, berlandaskan komitmen teguh untuk menghadirkan hadirat kemuliaan Tuhan (<em>Shekinah</em>) dan menjawab amanat agung Kristus.
                </p>
                <p class="text-on-surface-variant leading-relaxed text-justify">
                    Dalam anugerah Tuhan, jumlah keluarga terdaftar kini telah bertumbuh menjadi <strong>222 Kepala Keluarga</strong> dengan total <strong>817 individu</strong> (185 laki-laki dewasa, 204 perempuan dewasa, 149 pemuda, dan 279 anak-anak). Lebih dari 80% jemaat berasal dari kelompok etnis Rote dan suku-suku lain di NTT, dengan mayoritas berprofesi sebagai pekerja mandiri, pedagang pasar, dan pengrajin lokal yang berdedikasi melayani Tuhan.
                </p>
                <p class="text-on-surface-variant leading-relaxed text-justify">
                    Selain Pusat Pengembangan Anak Holistik (PPA), gereja juga menaungi lembaga pendidikan usia dini <strong>Tiranus Kindergarten Christian School</strong> yang telah beroperasi sejak tahun 1998 dan meluluskan lebih dari 300 alumni.
                </p>
            </div>
            <div class="lg:col-span-5 space-y-4">
                <div class="rounded-2xl overflow-hidden shadow-md border border-outline-variant/30 h-72 bg-primary">
                    <img src="{{ asset('images/gedung_gbi_shekinah_glory.jpg') }}" alt="Gedung GBI Shekinah Glory Namosain" class="w-full h-full object-cover">
                </div>
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 flex items-center gap-4">
                    <img src="{{ asset('images/pastor.jpg') }}" alt="Pdt. Yusuf Labagai" class="w-14 h-16 object-cover rounded-xl shadow-xs">
                    <div>
                        <span class="text-3xs uppercase tracking-wider font-bold text-secondary block">Gembala Sidang</span>
                        <h4 class="font-serif-display font-bold text-sm text-primary">{{ $contents['nama_gembala'] ?? 'Pdt. Yusuf Labagai' }}</h4>
                        <span class="text-2xs text-outline">Senior Pastor GBI Shekinah Glory</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visi & Misi Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
        <!-- Visi -->
        <div class="bg-primary text-white rounded-2xl p-7 shadow-md flex flex-col justify-between" data-aos="fade-up" data-aos-delay="100">
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center text-secondary-fixed">
                    <span class="material-symbols-outlined text-[28px]">lightbulb</span>
                </div>
                <h3 class="font-serif-display text-xl font-bold">Visi Gereja</h3>
                <p class="text-xs sm:text-sm text-white/90 italic leading-relaxed pt-1">
                    "{{ $contents['visi'] ?? 'Menjadi rumah kemuliaan Tuhan yang menjawab amanat agung Tuhan Yesus Kristus sebagai gereja misionaris apostolik dan profetik.' }}"
                </p>
            </div>
            <div class="pt-4 mt-4 border-t border-white/10 text-2xs text-secondary-fixed">
                Misionaris • Apostolik • Profetik
            </div>
        </div>

        <!-- Misi -->
        <div class="bg-primary text-white rounded-2xl p-7 shadow-md flex flex-col justify-between" data-aos="fade-up" data-aos-delay="150">
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center text-secondary-fixed">
                    <span class="material-symbols-outlined text-[28px]">rocket_launch</span>
                </div>
                <h3 class="font-serif-display text-xl font-bold">Misi Pelayanan</h3>
                <p class="text-xs sm:text-sm text-white/90 leading-relaxed pt-1">
                    Menjangkau &amp; Memperlengkapi setiap orang kudus dengan <strong>pelayanan 5 Jawatan</strong> (Rasul, Nabi, Penginjil, Guru, Gembala) untuk memenuhi <strong>5 panggilan gereja</strong>:
                </p>
                <div class="space-y-1 text-2xs text-secondary-fixed font-semibold">
                    <p>• Menyembah (Proskuneo) &amp; Bersekutu (Koinonia)</p>
                    <p>• Bersaksi (Marturia) &amp; Melayani (Diakonia)</p>
                    <p>• Memuridkan (Disciples) &amp; Agent of Change</p>
                </div>
            </div>
            <div class="pt-4 mt-4 border-t border-white/10 text-2xs text-secondary-fixed font-bold">
                Amanat Agung Kristus
            </div>
        </div>
    </div>

    <!-- SEKSI VALUE (NILAI-NILAI INTI GEREJA) - FORMAT MEMANJANG PENUH SEIRAMA VISI & MISI -->
    <div class="bg-primary text-white rounded-2xl p-7 sm:p-9 shadow-md flex flex-col justify-between mb-16 w-full" data-aos="fade-up">
        <div class="space-y-4 w-full">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center text-secondary-fixed shrink-0">
                    <span class="material-symbols-outlined text-[28px]">verified</span>
                </div>
                <div>
                    <h3 class="font-serif-display text-xl sm:text-2xl font-bold">Values</h3>
                    <span class="text-2xs text-secondary-fixed font-semibold tracking-wider uppercase block">Fondasi Karakter &amp; Gaya Hidup Jemaat</span>
                </div>
            </div>
            <!-- Kalimat Narasi Memanjang Penuh ke Kanan -->
            <p class="text-xs sm:text-sm text-white/95 leading-relaxed sm:leading-loose text-justify italic pt-1 w-full font-serif-display">
                " KeTuhanan Kristus, Hubungan terikat janji, Melayani dengan Hati Hamba, Integritas, Sebagai Kepunyaan Tuhan."
            </p>
        </div>       
    </div>

    <!-- Slogan & Identitas Utama: Be House of Glory -->
    <div class="rounded-3xl bg-gradient-to-r from-primary via-primary-container to-primary text-white p-8 sm:p-12 text-center shadow-lg mb-16 relative overflow-hidden" data-aos="fade-up">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-secondary/20 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 space-y-3 max-w-2xl mx-auto">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 text-secondary-fixed text-xs font-bold tracking-widest uppercase">
                ✦ Identitas &amp; Visi Pelayanan ✦
            </span>
            <h2 class="font-serif-display text-3xl sm:text-5xl font-bold tracking-tight text-white">
                "Be House of Glory"
            </h2>
            <p class="text-xs sm:text-sm text-white/80 leading-relaxed font-serif italic">
                Menjadi Rumah Kemuliaan Tuhan yang menjawab amanat agung Tuhan Yesus Kristus sebagai gereja misionaris, apostolik, dan profetik bagi Kota Kupang dan bangsa-bangsa.
            </p>
        </div>
    </div>

    <!-- Slot Struktur Kepengurusan Gereja -->
    <div class="bg-white rounded-3xl p-8 sm:p-12 shadow-sm border border-outline-variant/30 text-center space-y-4 mb-16" data-aos="fade-up">
        <div class="w-14 h-14 rounded-2xl bg-surface-container flex items-center justify-center text-primary mx-auto">
            <span class="material-symbols-outlined text-[32px]">account_tree</span>
        </div>
        <h3 class="font-serif-display text-2xl font-bold text-primary">Struktur Kepengurusan Gereja</h3>

        @if(!empty($contents['struktur_organisasi_image']))
            <div class="max-w-4xl mx-auto pt-4">
                <a href="{{ $contents['struktur_organisasi_image'] }}" target="_blank" class="block group relative rounded-2xl overflow-hidden border border-outline-variant/40 bg-surface-container shadow-sm">
                    <img src="{{ $contents['struktur_organisasi_image'] }}" alt="Bagan Struktur Kepengurusan GBI Shekinah Glory" class="w-full max-h-[500px] object-contain mx-auto group-hover:scale-[1.01] transition duration-300">
                    <div class="absolute inset-0 bg-primary/20 opacity-0 group-hover:opacity-100 flex items-center justify-center transition text-white font-bold text-xs gap-2">
                        <span class="material-symbols-outlined text-[20px]">zoom_in</span>
                        <span>Klik untuk Memperbesar Gambar</span>
                    </div>
                </a>
                <p class="text-[11px] text-on-surface-variant mt-2">Bagan resmi susunan kepengurusan, majelis, dan komisi pelayanan GBI Shekinah Glory.</p>
            </div>
        @else
            <span class="inline-block px-3 py-1 rounded-full bg-secondary-container/50 text-secondary text-xs font-bold">
                Tahap Pembaruan &amp; Pengesahan Periode Pelayanan
            </span>
            <p class="text-xs sm:text-sm text-on-surface-variant max-w-xl mx-auto leading-relaxed">
                Bagan susunan kepengurusan, majelis jemaat, dan badan pelayan komisi GBI Shekinah Glory Namosain sedang dalam tahap penyusunan dan pengesahan untuk dipublikasikan pada pembaruan mendatang.
            </p>
        @endif
    </div>
</div>
@endsection
