<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GBI Shekinah Glory - Menjadi Terang dan Tempat Kemuliaan Tuhan Hadir Nyata')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.jpg') }}">
    <meta name="description" content="Website resmi GBI Shekinah Glory. Informasi jadwal ibadah raya, pemuda, anak, lokasi gereja, serta pelayanan jemaat.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#001428",
                        "primary-container": "#0f2942",
                        secondary: "#775a19",
                        "secondary-container": "#fed488",
                        "secondary-fixed": "#ffdea5",
                        "secondary-fixed-dim": "#e9c176",
                        surface: "#faf9f6",
                        "surface-container": "#efeeeb",
                        "surface-container-low": "#f4f3f0",
                        "surface-container-high": "#e9e8e5",
                        "surface-container-lowest": "#ffffff",
                        "on-surface": "#1a1c1a",
                        "on-surface-variant": "#43474d",
                        outline: "#74777e",
                        "outline-variant": "#c3c6ce",
                    },
                    fontFamily: {
                        display: ["Playfair Display", "serif"],
                        headline: ["Playfair Display", "serif"],
                        body: ["Plus Jakarta Sans", "sans-serif"],
                    }
                }
            }
        };
    </script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-weight: normal;
            font-style: normal;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
            font-feature-settings: 'liga';
        }
    </style>
</head>
<body class="bg-surface text-on-surface antialiased flex flex-col min-h-screen">
    <!-- NAVBAR DESKTOP & MOBILE (FORMAT AWAL DENGAN TRANSISI SCROLL HALUS) -->
    <header id="main-header" class="fixed top-0 left-0 right-0 w-full z-50 transition-all duration-300 bg-surface/90 backdrop-blur-xl border-b border-outline-variant/30 py-3.5 sm:py-4">
        <div id="header-container" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between transition-all duration-300">
            <a href="{{ route('public.beranda') }}" class="flex items-center gap-3">
                @php
                    $logoPath = null;
                    if (!empty($contents['logo'])) {
                        $logoPath = $contents['logo'];
                    } else {
                        foreach (['png', 'jpg', 'jpeg', 'svg', 'webp'] as $ext) {
                            if (file_exists(public_path("images/logo.$ext"))) {
                                $logoPath = asset("images/logo.$ext");
                                break;
                            }
                        }
                    }
                @endphp

                @if($logoPath)
                    <img src="{{ $logoPath }}" alt="{{ $contents['nama_gereja'] ?? 'GBI Shekinah Glory' }}" class="w-12 h-12 sm:w-14 sm:h-14 object-contain rounded-xl shadow-sm">
                @else
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-primary-container flex items-center justify-center text-secondary-container shadow-md">
                        <span class="material-symbols-outlined text-[28px]">church</span>
                    </div>
                @endif
                <div class="flex flex-col">
                    <span class="font-serif-display text-lg sm:text-xl font-bold text-primary leading-none tracking-tight">{{ $contents['nama_gereja'] ?? 'GBI Shekinah Glory' }}</span>
                    <span class="text-[10px] tracking-wider uppercase font-semibold text-amber-700 mt-0.5">✦ Be House of Glory ✦</span>
                </div>
            </a>

            <!-- Nav Links Desktop (Pilihan Utama: Beranda, Tentang, Jemaat, Keuangan, Lokasi, Pendaftaran) -->
            <nav class="hidden lg:flex items-center gap-8 font-medium text-sm">
                <a href="{{ route('public.beranda') }}" class="{{ request()->routeIs('public.beranda') && !request()->has('page') ? 'text-primary font-bold border-b-2 border-secondary pb-1' : 'text-on-surface-variant hover:text-primary' }} transition-colors">
                    Beranda
                </a>
                <a href="{{ request()->routeIs('public.beranda') ? '#tentang-kami' : url('/#tentang-kami') }}" class="text-on-surface-variant hover:text-primary transition-colors">
                    Tentang
                </a>
                <a href="{{ route('public.jemaat') }}" class="{{ request()->routeIs('public.jemaat') ? 'text-primary font-bold border-b-2 border-secondary pb-1' : 'text-on-surface-variant hover:text-primary' }} transition-colors">
                    Jemaat
                </a>
                <a href="{{ route('public.keuangan') }}" class="{{ request()->routeIs('public.keuangan') ? 'text-primary font-bold border-b-2 border-secondary pb-1' : 'text-on-surface-variant hover:text-primary' }} transition-colors">
                    Keuangan
                </a>
                <a href="{{ request()->routeIs('public.beranda') ? '#lokasi-kontak' : url('/#lokasi-kontak') }}" class="text-on-surface-variant hover:text-primary transition-colors">
                    Lokasi
                </a>
                
                <!-- DROPDOWN MENU LAYANAN (MENGGANTIKAN PENDAFTARAN - PRD v2.2) -->
                <div class="relative group" id="desktopLayananDropdown">
                    <button type="button" class="flex items-center gap-1 {{ (request()->routeIs('public.pendaftaran*') || request()->is('layanan*')) ? 'text-primary font-bold border-b-2 border-secondary pb-1' : 'text-on-surface-variant hover:text-primary' }} transition-colors py-1 focus:outline-none">
                        <span>Layanan</span>
                        <span class="material-symbols-outlined text-[16px] transition-transform duration-200 group-hover:rotate-180">expand_more</span>
                    </button>
                    <div class="absolute left-0 top-full pt-2 w-56 hidden group-hover:block transition-all z-50">
                        <div class="bg-white rounded-xl shadow-xl border border-outline-variant/30 py-2 px-1.5 space-y-0.5">
                            <!-- 1. Pendaftaran Sakramen & Pelayanan -->
                            <a href="{{ route('public.pendaftaran') }}" class="block px-3.5 py-2.5 rounded-lg hover:bg-surface-container text-xs font-semibold text-primary transition">
                                Formulir Pendaftaran
                            </a>
                            <!-- 2. Cek & Unduh Surat via Kode Unik -->
                            <a href="{{ route('public.layanan.surat') }}" class="block px-3.5 py-2.5 rounded-lg hover:bg-surface-container text-xs font-semibold text-primary transition">
                                Cek &amp; Unduh Surat
                            </a>
                            <!-- 3. Form Peminjaman Inventaris -->
                            <a href="{{ route('public.layanan.pinjam-inventaris') }}" class="block px-3.5 py-2.5 rounded-lg hover:bg-surface-container text-xs font-semibold text-primary transition">
                                Peminjaman Inventaris
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ request()->routeIs('public.beranda') ? '#lokasi-kontak' : url('/#lokasi-kontak') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-secondary text-white text-xs font-bold hover:bg-secondary/90 transition shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">call</span>
                    <span>Hubungi Kami</span>
                </a>
                <button type="button" aria-label="Buka Menu" onclick="document.getElementById('mobile-drawer').classList.toggle('hidden')" class="lg:hidden w-10 h-10 rounded-lg flex items-center justify-center text-primary hover:bg-surface-container transition">
                    <span class="material-symbols-outlined text-[26px]">menu</span>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu (Pilihan Utama: Beranda, Tentang, Jemaat, Keuangan, Lokasi, Layanan) -->
        <nav id="mobile-drawer" class="hidden lg:hidden bg-white/95 backdrop-blur-xl border-t border-outline-variant/30 px-6 py-4 shadow-xl flex flex-col gap-2">
            <a href="{{ route('public.beranda') }}" onclick="document.getElementById('mobile-drawer').classList.add('hidden')" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('public.beranda') ? 'text-primary font-bold bg-surface-container' : 'text-on-surface-variant hover:text-secondary' }}">
                <span class="material-symbols-outlined text-[20px]">church</span>
                <span>Beranda</span>
            </a>
            <a href="{{ request()->routeIs('public.beranda') ? '#tentang-kami' : url('/#tentang-kami') }}" onclick="document.getElementById('mobile-drawer').classList.add('hidden')" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-on-surface-variant hover:text-secondary">
                <span class="material-symbols-outlined text-[20px]">groups</span>
                <span>Tentang</span>
            </a>
            <a href="{{ route('public.jemaat') }}" onclick="document.getElementById('mobile-drawer').classList.add('hidden')" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('public.jemaat') ? 'text-primary font-bold bg-surface-container' : 'text-on-surface-variant hover:text-secondary' }}">
                <span class="material-symbols-outlined text-[20px]">person_search</span>
                <span>Jemaat</span>
            </a>
            <a href="{{ route('public.keuangan') }}" onclick="document.getElementById('mobile-drawer').classList.add('hidden')" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('public.keuangan') ? 'text-primary font-bold bg-surface-container' : 'text-on-surface-variant hover:text-secondary' }}">
                <span class="material-symbols-outlined text-[20px]">payments</span>
                <span>Keuangan</span>
            </a>
            <a href="{{ request()->routeIs('public.beranda') ? '#lokasi-kontak' : url('/#lokasi-kontak') }}" onclick="document.getElementById('mobile-drawer').classList.add('hidden')" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-on-surface-variant hover:text-secondary">
                <span class="material-symbols-outlined text-[20px]">location_on</span>
                <span>Lokasi</span>
            </a>

            <!-- Dropdown / List Layanan Mobile -->
            <div class="pt-2 pb-1 border-t border-outline-variant/30 my-1 space-y-0.5">
                <span class="px-3 text-[10px] font-bold text-secondary uppercase tracking-wider block mb-1">Layanan</span>
                <a href="{{ route('public.pendaftaran') }}" onclick="document.getElementById('mobile-drawer').classList.add('hidden')" class="block px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('public.pendaftaran*') ? 'text-primary bg-surface-container' : 'text-on-surface-variant hover:text-secondary' }}">
                    Formulir Pendaftaran
                </a>
                <a href="{{ route('public.layanan.surat') }}" onclick="document.getElementById('mobile-drawer').classList.add('hidden')" class="block px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('public.layanan.surat*') ? 'text-primary bg-surface-container' : 'text-on-surface-variant hover:text-secondary' }}">
                    Cek &amp; Unduh Surat
                </a>
                <a href="{{ route('public.layanan.pinjam-inventaris') }}" onclick="document.getElementById('mobile-drawer').classList.add('hidden')" class="block px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('public.layanan.pinjam-inventaris*') ? 'text-primary bg-surface-container' : 'text-on-surface-variant hover:text-secondary' }}">
                    Peminjaman Inventaris
                </a>
            </div>
            <div class="pt-2 border-t border-outline-variant/30 flex flex-col gap-2">
                <a href="{{ request()->routeIs('public.beranda') ? '#lokasi-kontak' : url('/#lokasi-kontak') }}" onclick="document.getElementById('mobile-drawer').classList.add('hidden')" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl bg-secondary text-white text-xs font-bold shadow-xs">
                    <span class="material-symbols-outlined text-[16px]">call</span>
                    <span>Hubungi Kami</span>
                </a>
                <a href="{{ route('admin.login') }}" class="flex items-center gap-3 px-3 py-2 text-xs text-outline hover:text-secondary">
                    <span class="material-symbols-outlined text-[16px]">admin_panel_settings</span>
                    <span>Portal Administrator</span>
                </a>
            </div>
        </nav>
    </header>

    <!-- CONTENT WRAPPER -->
    <main class="flex-1 pt-20">
        @yield('content')
    </main>

    <!-- FOOTER RESMI & PROFESIONAL -->
    <footer class="bg-primary text-white border-t border-white/10 pt-16 pb-20 lg:pb-12 px-4 sm:px-6 lg:px-8 mt-20 relative overflow-hidden">
        <!-- Subtle Glow -->
        <div class="absolute -top-24 right-10 w-96 h-96 bg-secondary/10 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <!-- Top Identity Bar -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-12 border-b border-white/10">
                <div class="flex items-center gap-4">
                    @if($logoPath)
                        <img src="{{ $logoPath }}" alt="Logo GBI Shekinah Glory" class="w-14 h-14 object-contain rounded-xl bg-white/10 p-1.5 shadow-md">
                    @else
                        <div class="w-14 h-14 rounded-xl bg-white/10 flex items-center justify-center text-secondary-fixed shadow-md">
                            <span class="material-symbols-outlined text-[32px]">church</span>
                        </div>
                    @endif
                    <div>
                        <h3 class="font-serif-display text-2xl font-bold tracking-tight text-white">
                            {{ $contents['nama_gereja'] ?? 'GBI Shekinah Glory Namosain' }}
                        </h3>
                        <p class="text-xs text-secondary-fixed font-semibold tracking-wider uppercase mt-0.5">
                            Badan Hukum Gereja: SK Dirjen Bimas Kristen Protestan Depag RI No. 41 Th 1972
                        </p>
                        <p class="text-xs text-secondary-fixed font-semibold tracking-wider uppercase mt-0.5">
                            SK Dirjen Bimas (Kristen) Protestan Dep. Agama R.I. Nomor 211 Tanggal, 25 November 1989
                        </p>
                        <p class="text-xs text-secondary-fixed font-semibold tracking-wider uppercase mt-0.5">
                            Jalan Ikan Arwana Namosain - Kupang Telpon : 085 333 650 621, 081 238 858 629, 082 247 292 194
                        </p>
                    </div>
                </div>
            </div>

            <!-- 3-Column Grid (Kebaktian Dihapus, Tampilan Lebih Lapang & Elegan) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 py-12 border-b border-white/10 text-xs sm:text-sm">
                <!-- Col 1: Profil & Komunitas (5 Cols) -->
                <div class="lg:col-span-5 space-y-4">
                    <h4 class="font-serif-display text-base font-bold text-secondary-fixed uppercase tracking-wider">Persekutuan & Pelayanan</h4>
                    <p class="text-white/70 leading-relaxed text-xs">
                        GBI Shekinah Glory Namosain berkomitmen memuridkan setiap generasi melalui hadirat Tuhan yang intim, kebenaran firman yang murni, persekutuan komunitas sel (COOL), dan pelayanan kasih nyata bagi masyarakat Kota Kupang.
                    </p>
                    <div class="flex flex-wrap items-center gap-2.5 pt-2">
                        <!-- Facebook (Gbi Shekinah Glory) -->
                        <a href="https://www.facebook.com/search/top?q=Gbi%20Shekinah%20Glory" target="_blank" rel="noopener noreferrer" class="h-9 px-3 rounded-xl bg-white/10 hover:bg-[#1877F2] transition flex items-center gap-2 text-white group" title="Facebook: Gbi Shekinah Glory">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span class="text-xs font-semibold">Gbi Shekinah Glory</span>
                        </a>

                        <!-- Instagram (gbi_shekinahglory) -->
                        <a href="https://www.instagram.com/gbi_shekinahglory" target="_blank" rel="noopener noreferrer" class="h-9 px-3 rounded-xl bg-white/10 hover:bg-gradient-to-r hover:from-[#833ab4] hover:via-[#fd1d1d] hover:to-[#fcb045] transition flex items-center gap-2 text-white group" title="Instagram: @gbi_shekinahglory">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                            <span class="text-xs font-semibold">gbi_shekinahglory</span>
                        </a>

                        <!-- WhatsApp (082247292194) -->
                        <a href="https://wa.me/6282247292194" target="_blank" rel="noopener noreferrer" class="h-9 px-3 rounded-xl bg-white/10 hover:bg-[#25D366] transition flex items-center gap-2 text-white group" title="WhatsApp: 082247292194">
                            <span class="material-symbols-outlined text-[18px]">chat</span>
                            <span class="text-xs font-semibold">082247292194</span>
                        </a>

                        <!-- Google Maps -->
                        <a href="{{ $contents['google_maps_url'] ?? 'https://maps.app.goo.gl/uuA2zD3FbzX676JRA' }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-white/10 hover:bg-secondary transition flex items-center justify-center text-white" title="Lokasi Google Maps">
                            <span class="material-symbols-outlined text-[18px]">near_me</span>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Navigasi Cepat (3 Cols) -->
                <div class="lg:col-span-3 space-y-4">
                    <h4 class="font-serif-display text-base font-bold text-secondary-fixed uppercase tracking-wider">Navigasi Halaman</h4>
                    <ul class="space-y-2 text-xs text-white/75">
                        <li><a href="{{ route('public.beranda') }}" class="hover:text-secondary-fixed transition flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-secondary-fixed"></span> Beranda</a></li>
                        <li><a href="{{ route('public.tentang') }}" class="hover:text-secondary-fixed transition flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-secondary-fixed"></span> Tentang Kami</a></li>
                        <li><a href="{{ route('public.jemaat') }}" class="hover:text-secondary-fixed transition flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-secondary-fixed"></span> Data Jemaat</a></li>
                        <li><a href="{{ route('public.keuangan') }}" class="hover:text-secondary-fixed transition flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-secondary-fixed"></span> Laporan Keuangan</a></li>
                        <li><a href="{{ route('public.lokasi') }}" class="hover:text-secondary-fixed transition flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-secondary-fixed"></span> Lokasi &amp; Kontak</a></li>
                        <li><a href="{{ route('public.warta') }}" class="hover:text-secondary-fixed transition flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-secondary-fixed"></span> Warta &amp; Buletin</a></li>
                        <li><a href="{{ route('public.dokumentasi') }}" class="hover:text-secondary-fixed transition flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-secondary-fixed"></span> Galeri Dokumentasi</a></li>
                        <li><a href="{{ route('public.pendaftaran') }}" class="hover:text-secondary-fixed transition flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-secondary-fixed"></span> Pendaftaran Layanan</a></li>
                    </ul>
                </div>

                <!-- Col 3: Kontak & Sekretariat (4 Cols) -->
                <div class="lg:col-span-4 space-y-4">
                    <h4 class="font-serif-display text-base font-bold text-secondary-fixed uppercase tracking-wider">Sekretariat Jemaat</h4>
                    <div class="space-y-2.5 text-xs text-white/75">
                        <div class="flex items-start gap-2.5">
                            <span class="material-symbols-outlined text-[18px] text-secondary-fixed shrink-0 mt-0.5">location_on</span>
                            <span>{{ $contents['alamat_lengkap'] ?? 'Jl. Ikan Arwana, RT. 004 / RW. 002, Kelurahan Namosain, Kecamatan Alak, Kota Kupang, Nusa Tenggara Timur' }}</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-outlined text-[18px] text-secondary-fixed shrink-0">phone_iphone</span>
                            <span>{{ $contents['whatsapp'] ?? '082247292194' }}</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-outlined text-[18px] text-secondary-fixed shrink-0">email</span>
                            <span>{{ $contents['email'] ?? 'sekretariat@gbishekinahglory.org' }}</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-outlined text-[18px] text-secondary-fixed shrink-0">schedule</span>
                            <span>Selasa – Sabtu: 10:00 – 16:00 WITA</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright & SIMJ Bar -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-white/60">
                <p>© {{ date('Y') }} GBI Shekinah Glory Namosain - Kupang. All Rights Reserved.</p>
                <div class="flex items-center gap-4">
                    <span class="italic text-secondary-fixed-dim font-serif-display">"Solus Christus • Sola Gratia • Sola Fide"</span>
                    <span class="text-white/20">|</span>
                    <a href="{{ route('admin.login') }}" class="inline-flex items-center gap-1.5 text-white/70 hover:text-secondary-fixed transition font-medium">
                        <span class="material-symbols-outlined text-[15px]">lock</span>
                        <span>Portal SIM Jemaat</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- STICKY BOTTOM NAV BAR FOR MOBILE -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-surface/95 backdrop-blur-xl border-t border-outline-variant/40 shadow-lg">
        <div class="flex justify-around items-center h-16 px-2">
            <a href="{{ route('public.beranda') }}" class="flex flex-col items-center justify-center flex-1 h-full {{ request()->routeIs('public.beranda') ? 'text-secondary font-bold' : 'text-on-surface-variant' }}">
                <span class="material-symbols-outlined text-[22px]">church</span>
                <span class="text-[11px] mt-0.5">Beranda</span>
            </a>
            <a href="{{ route('public.jadwal') }}" class="flex flex-col items-center justify-center flex-1 h-full {{ request()->routeIs('public.jadwal') ? 'text-secondary font-bold' : 'text-on-surface-variant' }}">
                <span class="material-symbols-outlined text-[22px]">calendar_month</span>
                <span class="text-[11px] mt-0.5">Ibadah</span>
            </a>
            <a href="{{ route('public.kontak') }}" class="flex flex-col items-center justify-center flex-1 h-full {{ request()->routeIs('public.kontak') ? 'text-secondary font-bold' : 'text-on-surface-variant' }}">
                <span class="material-symbols-outlined text-[22px]">contact_support</span>
                <span class="text-[11px] mt-0.5">Kontak</span>
            </a>
            <a href="{{ route('public.lokasi') }}" class="flex flex-col items-center justify-center flex-1 h-full {{ request()->routeIs('public.lokasi') ? 'text-secondary font-bold' : 'text-on-surface-variant' }}">
                <span class="material-symbols-outlined text-[22px]">pin_drop</span>
                <span class="text-[11px] mt-0.5">Lokasi</span>
            </a>
        </div>
    </nav>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 750,
                easing: 'ease-out-cubic',
                once: true,
                offset: 50,
            });

            // Transisi Navbar saat Scroll
            const header = document.getElementById('main-header');
            
            function handleNavbarScroll() {
                if (!header) return;
                if (window.scrollY > 20) {
                    header.classList.add('bg-white/95', 'shadow-md', 'py-2.5');
                    header.classList.remove('bg-surface/90', 'py-3.5', 'sm:py-4');
                } else {
                    header.classList.remove('bg-white/95', 'shadow-md', 'py-2.5');
                    header.classList.add('bg-surface/90', 'py-3.5', 'sm:py-4');
                }
            }

            window.addEventListener('scroll', handleNavbarScroll, { passive: true });
            handleNavbarScroll(); // cek posisi awal saat reload
        });
    </script>
</body>
</html>
