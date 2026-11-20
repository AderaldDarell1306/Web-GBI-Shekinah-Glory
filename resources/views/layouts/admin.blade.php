<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin Dashboard - SIMJ GBI Shekinah Glory')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <style>
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
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.04);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.35);
        }
    </style>
</head>
<body class="bg-surface-container-low text-on-surface antialiased flex min-h-screen relative">
    <!-- MOBILE BACKDROP -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-black/60 z-40 backdrop-blur-xs hidden md:hidden transition-opacity"></div>

    <!-- SIDEBAR -->
    <aside id="admin-sidebar" class="fixed md:sticky top-0 left-0 h-screen w-72 bg-primary text-white flex-shrink-0 flex flex-col justify-between border-r border-white/10 shadow-2xl z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <!-- TOP LOGO & HEADER (PINNED) -->
        <div class="p-5 flex items-center justify-between border-b border-white/10 shrink-0">
            <div class="flex items-center gap-3">
                @php
                    $adminLogo = \App\Models\WebsiteContent::where('key', 'logo')->value('value');
                    if (!$adminLogo) {
                        foreach (['png', 'jpg', 'jpeg', 'svg', 'webp'] as $ext) {
                            if (file_exists(public_path("images/logo.$ext"))) {
                                $adminLogo = asset("images/logo.$ext");
                                break;
                            }
                        }
                    }
                @endphp
                @if($adminLogo)
                    <img src="{{ $adminLogo }}" alt="Logo GBI Shekinah Glory" class="w-10 h-10 object-contain rounded-xl bg-white/10 p-1 shadow">
                @else
                    <div class="w-10 h-10 rounded-xl bg-primary-container flex items-center justify-center text-amber-300 shadow">
                        <span class="material-symbols-outlined text-[24px]">church</span>
                    </div>
                @endif
                <div class="flex flex-col min-w-0">
                    <span class="font-serif-display text-base font-bold leading-tight tracking-tight text-white truncate">Shekinah Glory</span>
                    <span class="text-[10px] tracking-widest uppercase text-amber-300/90 font-semibold truncate">✦ BE HOUSE OF GLORY ✦</span>
                </div>
            </div>

            <!-- Close button for mobile -->
            <button type="button" onclick="toggleSidebar()" class="md:hidden w-8 h-8 rounded-lg flex items-center justify-center text-white/70 hover:bg-white/10 transition">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- PROFILE CARD WIDGET -->
        <div class="px-4 pt-3 shrink-0">
            <div class="p-3 rounded-xl bg-white/5 border border-white/10 flex items-center gap-3 shadow-inner">
                <div class="w-10 h-10 rounded-full {{ (Auth::user()?->role ?? '') === 'developer' ? 'bg-amber-500' : 'bg-primary-container' }} text-white flex items-center justify-center font-bold text-sm shrink-0 shadow border border-white/15">
                    @if((Auth::user()?->role ?? '') === 'developer')
                        <span class="material-symbols-outlined text-[20px] text-amber-100">terminal</span>
                    @else
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    @endif
                </div>
                <div class="flex flex-col min-w-0 flex-1">
                    <span class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</span>
                    @if((Auth::user()?->role ?? '') === 'developer')
                        <span class="text-[10px] font-bold text-amber-300 flex items-center gap-1">
                            <span>Developer</span> • <span class="text-white/60">Full Access</span>
                        </span>
                    @elseif((Auth::user()?->role ?? '') === 'super_admin')
                        <span class="text-[10px] font-semibold text-purple-300">Super Administrator</span>
                    @elseif((Auth::user()?->role ?? '') === 'bendahara')
                        <span class="text-[10px] font-semibold text-emerald-300">Bendahara Gereja</span>
                    @elseif((Auth::user()?->role ?? '') === 'gembala')
                        <span class="text-[10px] font-semibold text-amber-300">Gembala Pembina</span>
                    @elseif((Auth::user()?->role ?? '') === 'ketua_cool')
                        <span class="text-[10px] font-semibold text-cyan-300">Ketua Sel / COOL</span>
                    @elseif((Auth::user()?->role ?? '') === 'jemaat')
                        <span class="text-[10px] font-semibold text-sky-300">Jemaat Umum (Read-Only)</span>
                    @else
                        <span class="text-[10px] text-white/60">Staf Sekretariat</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- SCROLLABLE NAVIGATION LINKS -->
        <nav class="flex-1 overflow-y-auto custom-scrollbar px-3 py-3 space-y-5 text-xs">
            <!-- 1. MENU UTAMA -->
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-white/40">
                    Menu Utama
                </div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                    <span>Dashboard Utama</span>
                </a>
            </div>

            <!-- 2. DATA JEMAAT & PEMETAAN WILAYAH -->
            @if(!Auth::user()?->isKetuaCool())
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-white/40">
                    Data Jemaat &amp; Wilayah
                </div>

                <a href="{{ route('admin.jemaat.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.jemaat.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">groups</span>
                        <span>Data Jemaat</span>
                    </div>
                    @if(Auth::user()?->isJemaat())
                        <span class="px-2 py-0.5 rounded text-[9px] font-semibold bg-white/10 text-white/60">Lihat</span>
                    @endif
                </a>

                @if(!Auth::user()?->isJemaat())
                <a href="{{ route('admin.wilayah.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.wilayah.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">map</span>
                    <span>Pemetaan 12 Wilayah</span>
                </a>

                <a href="{{ route('admin.cool.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.cool.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">diversity_3</span>
                    <span>Kehadiran Komunitas COOL</span>
                </a>
                @endif
            </div>
            @else
            <!-- Menu Khusus Ketua COOL -->
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-white/40">
                    Pelayanan Sel
                </div>
                <a href="{{ route('admin.cool.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.cool.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">diversity_3</span>
                    <span>Kehadiran Pertemuan Sel</span>
                </a>
            </div>
            @endif

            <!-- 3. PELAYANAN & LITURGI IBADAH -->
            @if(!Auth::user()?->isKetuaCool() && !Auth::user()?->isJemaat())
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-white/40">
                    Pelayanan &amp; Liturgi
                </div>

                <a href="{{ route('admin.pelayanan.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.pelayanan.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">assignment_ind</span>
                    <span>Jadwal Pelayan &amp; Rota</span>
                </a>

                <a href="{{ route('admin.majelis.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.majelis.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">badge</span>
                    <span>Data Majelis Bertugas</span>
                </a>

                <a href="{{ route('admin.warta.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.warta.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">menu_book</span>
                    <span>Warta &amp; Buletin Mingguan</span>
                </a>

                <a href="{{ route('admin.kalender.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.kalender.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">event</span>
                    <span>Kalender Kegiatan Gereja</span>
                </a>

                <a href="{{ route('admin.sakramen.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.sakramen.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">workspace_premium</span>
                    <span>Sakramen &amp; Surat Gereja</span>
                </a>
            </div>
            @elseif(Auth::user()?->isJemaat())
            <!-- Jemaat Menu Warta -->
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-white/40">
                    Warta Jemaat
                </div>
                <a href="{{ route('admin.warta.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.warta.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">menu_book</span>
                    <span>Warta &amp; Buletin Ibadah</span>
                </a>
            </div>
            @endif

            <!-- 4. KEUANGAN & ASET GEREJA -->
            @if(!Auth::user()?->isKetuaCool() && !Auth::user()?->isJemaat())
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-white/40">
                    Keuangan &amp; Tata Kelola
                </div>

                @if(Auth::user()?->canViewKeuangan())
                <a href="{{ route('admin.keuangan.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.keuangan.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
                    <span>Keuangan &amp; Laporan Kas</span>
                </a>
                @endif

                <a href="{{ route('admin.inventaris.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.inventaris.*') || request()->routeIs('admin.peminjaman-inventaris.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                    <span>Inventaris &amp; Pinjam Pakai</span>
                </a>

                <a href="{{ route('admin.arsip.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.arsip.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">folder_shared</span>
                    <span>Buku Arsip Dokumen</span>
                </a>
            </div>
            @endif

            <!-- 5. PENGELOLAAN WEBSITE PUBLIK (CMS) -->
            @if(Auth::user()?->canManageContent())
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-white/40">
                    Pengelolaan Website (CMS)
                </div>

                <a href="{{ route('admin.konten.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.konten.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">tune</span>
                    <span>Edit Konten &amp; Profil Web</span>
                </a>

                <a href="{{ route('admin.pengumuman.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.pengumuman.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">campaign</span>
                    <span>Pengumuman &amp; Agenda</span>
                </a>

                <a href="{{ route('admin.jadwal.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.jadwal.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                    <span>Jadwal Ibadah Web</span>
                </a>

                <a href="{{ route('admin.dokumentasi.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.dokumentasi.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">photo_library</span>
                    <span>Galeri Dokumentasi Foto</span>
                </a>
            </div>
            @elseif(Auth::user()?->isJemaat())
            <!-- Jemaat Menu Dokumentasi -->
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-white/40">
                    Galeri Foto
                </div>
                <a href="{{ route('public.dokumentasi') }}" target="_blank" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-white/75 hover:bg-white/10 hover:text-white transition">
                    <span class="material-symbols-outlined text-[20px]">photo_library</span>
                    <span>Lihat Galeri Foto Kegiatan</span>
                </a>
            </div>
            @endif

            <!-- 6. PENGATURAN SISTEM & HAK AKSES -->
            @if(Auth::user()?->isSuperAdmin())
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-white/40">
                    Sistem &amp; Pengguna
                </div>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-white/15 text-amber-300 font-bold border-l-4 border-amber-400 pl-3 shadow-xs' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
                    <span>Pengguna &amp; Hak Akses</span>
                </a>
            </div>
            @endif

            <!-- 7. TAUTAN KE WEB PUBLIK -->
            <div class="space-y-1 pt-1">
                <div class="px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-white/40">
                    Website Publik
                </div>
                <a href="{{ route('public.beranda') }}" target="_blank" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium text-white/75 hover:bg-white/10 hover:text-white transition group">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">public</span>
                        <span>Buka Website Gereja</span>
                    </div>
                    <span class="material-symbols-outlined text-[16px] text-white/40 group-hover:text-amber-300 group-hover:translate-x-0.5 transition">open_in_new</span>
                </a>
            </div>
        </nav>

        <!-- FOOTER / LOGOUT (PINNED) -->
        <div class="p-4 border-t border-white/10 shrink-0 bg-primary-container/40">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-red-200 bg-red-950/40 hover:bg-red-900/60 hover:text-white transition shadow-sm border border-red-500/20">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                    <span>Keluar Sistem</span>
                </button>
            </form>
            <p class="text-[10px] text-white/40 text-center mt-2.5">© {{ date('Y') }} GBI Shekinah Glory</p>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- TOPBAR -->
        <header class="h-16 bg-white/95 backdrop-blur-md border-b border-outline-variant/40 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleSidebar()" class="md:hidden w-10 h-10 rounded-xl flex items-center justify-center text-primary hover:bg-surface-container active:scale-95 transition">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
                <div class="flex flex-col">
                    <h1 class="text-sm sm:text-base font-bold text-primary truncate leading-tight">@yield('page_title', 'Sistem Informasi Manajemen Jemaat')</h1>
                    <span class="text-[10px] text-on-surface-variant hidden sm:block">GBI Shekinah Glory Namosain</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('public.beranda') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container text-xs font-semibold text-primary hover:bg-surface-container-high transition border border-outline-variant/30 shadow-2xs">
                    <span class="material-symbols-outlined text-[16px] text-secondary">open_in_new</span>
                    <span>Kunjungi Web</span>
                </a>

                <div class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container text-xs font-medium text-on-surface-variant">
                    <span class="material-symbols-outlined text-[15px] text-amber-600">calendar_today</span>
                    <span>{{ date('d M Y') }}</span>
                </div>

                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs shadow-sm ring-2 ring-primary/10">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </header>

        <!-- FLASH NOTIFICATIONS -->
        <div class="p-6 pb-0 max-w-7xl w-full mx-auto">
            @if(session('success'))
            <div class="p-4 rounded-xl bg-green-50 text-green-900 border border-green-200 flex items-center gap-3 shadow-sm mb-4">
                <span class="material-symbols-outlined text-green-600 text-[22px]">check_circle</span>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error') || (isset($errors) && $errors->any()))
            <div class="p-4 rounded-xl bg-red-50 text-red-900 border border-red-200 flex items-center gap-3 shadow-sm mb-4">
                <span class="material-symbols-outlined text-red-600 text-[22px]">error</span>
                <div class="text-sm font-medium">
                    {{ session('error') ?? (isset($errors) ? $errors->first() : '') }}
                </div>
            </div>
            @endif

            @if(session('duplicate_warning'))
            <div class="p-4 rounded-xl bg-amber-50 text-amber-900 border border-amber-300 flex items-start gap-3 shadow-sm mb-4">
                <span class="material-symbols-outlined text-amber-600 text-[24px] shrink-0 mt-0.5">warning</span>
                <div class="text-sm space-y-2">
                    <p class="font-bold">Peringatan Potensi Duplikasi Data:</p>
                    <p>{{ session('duplicate_warning')['message'] }}</p>
                    <div class="flex items-center gap-3 pt-1">
                        <a href="{{ route('admin.jemaat.show', session('duplicate_warning')['existing_id']) }}" target="_blank" class="px-3 py-1 bg-amber-200 rounded text-xs font-bold hover:bg-amber-300 transition">Lihat Data Yang Sudah Ada</a>
                        <button type="button" onclick="document.getElementById('ignore_duplicate_input').value='1'; document.getElementById('memberForm').submit();" class="px-3 py-1 bg-secondary text-white rounded text-xs font-bold hover:bg-secondary/90 transition">Tetap Simpan Sebagai Data Baru</button>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- MAIN VIEW CONTENT -->
        <main class="flex-1 p-6 max-w-7xl w-full mx-auto">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
