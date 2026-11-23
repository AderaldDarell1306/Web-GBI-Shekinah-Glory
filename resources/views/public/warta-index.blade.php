@extends('layouts.public')

@section('title', 'Katalog Warta & Buletin Jemaat - GBI Shekinah Glory')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 space-y-12">
    <!-- Header Section -->
    <div class="text-center max-w-3xl mx-auto" data-aos="fade-up">
        <span class="text-xs font-bold uppercase tracking-widest text-secondary block mb-2">Media Pembinaan Rohani &amp; Informasi</span>
        <h1 class="font-serif-display text-3xl sm:text-4xl lg:text-5xl font-bold text-primary mb-3">
            Katalog Warta &amp; Buletin Digital
        </h1>
        <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
            Arsip terbitan warta jemaat mingguan, ringkasan khotbah firman Tuhan, jadwal pelayanan liturgi, dan dokumen buletin resmi GBI Shekinah Glory Namosain.
        </p>
        <div class="w-16 h-1 bg-secondary mx-auto mt-4 rounded-full"></div>
    </div>

    <!-- Filter & Pencarian Bar -->
    <div class="bg-white rounded-3xl p-4 sm:p-5 shadow-sm border border-outline-variant/30 flex flex-col md:flex-row md:items-center justify-between gap-4" data-aos="fade-up">
        <form action="{{ route('public.warta') }}" method="GET" class="flex-1 flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari tema khotbah, ayat firman, atau edisi..." class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-outline-variant/40 bg-surface focus:border-secondary focus:outline-none transition">
            </div>

            @if(!empty($tahunList) && count($tahunList) > 1)
            <div class="w-full sm:w-44 shrink-0">
                <select name="tahun" onchange="this.form.submit()" class="w-full py-2 px-3 text-xs rounded-xl border border-outline-variant/40 bg-surface focus:border-secondary focus:outline-none transition">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $thn)
                    <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>Tahun {{ $thn }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary-container transition shrink-0">
                Cari Edisi
            </button>
            @if(request('q') || request('tahun'))
            <a href="{{ route('public.warta') }}" class="text-xs text-secondary hover:underline shrink-0">Reset</a>
            @endif
        </form>

        <div class="text-2xs text-outline text-right">
            Menampilkan <strong class="text-primary">{{ $arsipWarta->total() }}</strong> edisi warta
        </div>
    </div>

    <!-- FEATURED LATEST EDITION (Jika tidak sedang melakukan pencarian / filter) -->
    @if(!request('q') && !request('tahun') && $latestWarta && $arsipWarta->currentPage() == 1)
    <div class="bg-gradient-to-br from-primary via-[#0f2a4a] to-primary-container rounded-3xl overflow-hidden shadow-md text-white border border-secondary/30" data-aos="fade-up">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            <div class="lg:col-span-5 relative min-h-[260px] lg:min-h-full">
                <img src="{{ $latestWarta->gambar_sampul_url }}" alt="{{ $latestWarta->tema_mingguan }}" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t lg:bg-gradient-to-r from-primary via-primary/60 to-transparent"></div>
                <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                    <span class="px-3 py-1 rounded-full bg-secondary text-white text-3xs font-bold uppercase tracking-wider shadow-sm">
                        Edisi Terbaru
                    </span>
                    @if($latestWarta->buletin_file || $latestWarta->buletin_link)
                    <span class="px-3 py-1 rounded-full bg-emerald-600 text-white text-3xs font-bold uppercase tracking-wider shadow-sm flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">auto_stories</span>
                        <span>Ada Buletin PDF</span>
                    </span>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-7 p-6 sm:p-8 lg:p-10 flex flex-col justify-between space-y-6">
                <div class="space-y-3">
                    <div class="flex items-center gap-2 text-2xs text-secondary-container font-semibold">
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                        <span>{{ $latestWarta->tanggal_warta->translatedFormat('l, d F Y') }}</span>
                        <span>•</span>
                        <span>{{ $latestWarta->edisi }}</span>
                    </div>

                    <h2 class="font-serif-display text-2xl sm:text-3xl lg:text-4xl font-bold leading-tight text-white">
                        "{{ $latestWarta->tema_mingguan }}"
                    </h2>

                    @if($latestWarta->ayat_tema)
                    <p class="text-xs sm:text-sm text-secondary-fixed italic font-medium flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm">menu_book</span>
                        <span>{{ $latestWarta->ayat_tema }}</span>
                    </p>
                    @endif

                    @if($latestWarta->ringkasan_khotbah)
                    <p class="text-xs sm:text-sm text-white/80 line-clamp-3 leading-relaxed">
                        {{ Str::limit(strip_tags($latestWarta->ringkasan_khotbah), 220) }}
                    </p>
                    @endif
                </div>

                <div class="pt-4 border-t border-white/15 flex flex-wrap items-center gap-3">
                    <a href="{{ route('public.warta.detail', $latestWarta->id) }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-secondary text-white font-bold text-xs hover:bg-secondary/90 transition shadow-sm group">
                        <span>Baca Warta &amp; Buletin Lengkap</span>
                        <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>

                    @if($latestWarta->buletin_file)
                    <a href="{{ asset('storage/' . $latestWarta->buletin_file) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold transition">
                        <span class="material-symbols-outlined text-sm">download</span>
                        <span>Unduh PDF</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- GRID KATALOG EDISI WARTA -->
    <div class="space-y-6">
        <div class="flex items-center justify-between border-b border-outline-variant/20 pb-3">
            <h3 class="font-serif-display font-bold text-xl sm:text-2xl text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">library_books</span>
                <span>Daftar Edisi Warta Mingguan</span>
            </h3>
            <span class="text-2xs text-outline font-medium">Klik pada kartu untuk membaca edisi tersebut</span>
        </div>

        @if($arsipWarta->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($arsipWarta as $item)
            <div class="bg-white rounded-3xl overflow-hidden border border-outline-variant/30 shadow-2xs hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
                <div>
                    <!-- Thumbnail Header -->
                    <div class="relative w-full h-44 bg-surface-container overflow-hidden">
                        <img src="{{ $item->gambar_sampul_url }}" alt="{{ $item->tema_mingguan }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>
                        
                        <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                            <span class="px-2.5 py-0.5 rounded-full bg-primary/90 backdrop-blur-md text-white text-3xs font-bold uppercase tracking-wider">
                                {{ $item->edisi }}
                            </span>
                            @if($item->buletin_file || $item->buletin_link)
                            <span class="px-2 py-0.5 rounded-full bg-emerald-600/90 backdrop-blur-md text-white text-3xs font-bold uppercase tracking-wider flex items-center gap-0.5">
                                <span class="material-symbols-outlined text-[11px]">auto_stories</span>
                                <span>Buletin</span>
                            </span>
                            @endif
                        </div>

                        <span class="absolute bottom-2.5 left-3 text-white text-2xs font-semibold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px]">calendar_today</span>
                            <span>{{ $item->tanggal_warta->translatedFormat('d F Y') }}</span>
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 space-y-2.5">
                        <h4 class="font-serif-display font-bold text-base text-primary group-hover:text-secondary transition-colors line-clamp-2 leading-snug">
                            "{{ $item->tema_mingguan }}"
                        </h4>

                        @if($item->ayat_tema)
                        <p class="text-2xs text-secondary font-medium italic flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px]">menu_book</span>
                            <span>{{ $item->ayat_tema }}</span>
                        </p>
                        @endif

                        @if($item->ringkasan_khotbah)
                        <p class="text-xs text-on-surface-variant line-clamp-3 leading-relaxed">
                            {{ Str::limit(strip_tags($item->ringkasan_khotbah), 120) }}
                        </p>
                        @endif
                    </div>
                </div>

                <!-- Card Footer Action -->
                <div class="px-5 py-3.5 border-t border-outline-variant/15 flex items-center justify-between text-xs">
                    <span class="text-2xs text-outline">{{ $item->tanggal_warta->diffForHumans() }}</span>
                    <a href="{{ route('public.warta.detail', $item->id) }}" class="font-bold text-secondary flex items-center gap-1 hover:text-secondary/80 transition">
                        <span>Baca Edisi</span>
                        <span class="material-symbols-outlined text-[15px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pt-6">
            {{ $arsipWarta->links() }}
        </div>
        @else
        <div class="p-12 text-center rounded-3xl bg-white border border-outline-variant/30 space-y-3">
            <span class="material-symbols-outlined text-outline text-5xl">menu_book</span>
            <h4 class="font-bold text-primary text-base">Belum Ada Edisi Warta Ditemukan</h4>
            <p class="text-xs text-outline max-w-md mx-auto">Tidak ada arsip warta yang cocok dengan kata kunci atau filter yang Anda pilih.</p>
            <a href="{{ route('public.warta') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-white rounded-xl text-xs font-bold">
                Tampilkan Semua Edisi
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
