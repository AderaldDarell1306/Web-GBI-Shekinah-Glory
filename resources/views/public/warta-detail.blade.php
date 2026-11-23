@extends('layouts.public')

@section('title', $warta->edisi . ' - ' . $warta->tema_mingguan . ' - GBI Shekinah Glory')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-10">
    <!-- Top Navigation & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-outline-variant/20 pb-4" data-aos="fade-up">
        <a href="{{ route('public.warta') }}" class="inline-flex items-center gap-2 text-xs font-bold text-secondary hover:text-secondary/80 transition">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            <span>Kembali ke Katalog Warta</span>
        </a>

        <!-- Share & Quick Nav -->
        <div class="flex items-center gap-2.5">
            @php
                $shareText = "Shalom Jemaat GBI Shekinah Glory, berikut warta & renungan edisi " . $warta->edisi . " - \"" . $warta->tema_mingguan . "\": " . url()->current();
                $waShareUrl = "https://api.whatsapp.com/send?text=" . urlencode($shareText);
            @endphp
            <a href="{{ $waShareUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-2xs" title="Bagikan ke WhatsApp Jemaat">
                <span class="material-symbols-outlined text-[16px]">share</span>
                <span>Bagikan ke WA</span>
            </a>

            @if($prevWarta)
            <a href="{{ route('public.warta.detail', $prevWarta->id) }}" class="p-1.5 rounded-xl border border-outline-variant/40 hover:bg-surface-container transition text-primary" title="Edisi Sebelumnya: {{ $prevWarta->edisi }}">
                <span class="material-symbols-outlined text-[18px]">chevron_left</span>
            </a>
            @endif

            @if($nextWarta)
            <a href="{{ route('public.warta.detail', $nextWarta->id) }}" class="p-1.5 rounded-xl border border-outline-variant/40 hover:bg-surface-container transition text-primary" title="Edisi Berikutnya: {{ $nextWarta->edisi }}">
                <span class="material-symbols-outlined text-[18px]">chevron_right</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Theme Banner (Header Edisi) -->
    <div class="bg-gradient-to-br from-primary via-[#0f2a4a] to-primary-container text-white rounded-3xl p-6 sm:p-10 shadow-md relative overflow-hidden" data-aos="fade-up">
        <div class="relative z-10 space-y-3 max-w-3xl">
            <div class="flex flex-wrap items-center gap-2 text-2xs text-secondary-container font-semibold uppercase tracking-wider">
                <span class="px-3 py-1 rounded-full bg-secondary text-white font-bold">
                    {{ $warta->edisi }}
                </span>
                <span>•</span>
                <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                    <span>{{ $warta->tanggal_warta->translatedFormat('l, d F Y') }}</span>
                </span>
            </div>

            <h1 class="font-serif-display text-2xl sm:text-4xl lg:text-5xl font-bold leading-tight">
                "{{ $warta->tema_mingguan }}"
            </h1>

            @if($warta->ayat_tema)
            <p class="text-xs sm:text-base text-secondary-fixed font-serif italic font-medium flex items-center gap-2 pt-1">
                <span class="material-symbols-outlined text-base">menu_book</span>
                <span>{{ $warta->ayat_tema }}</span>
            </p>
            @endif
        </div>

        <!-- Subtle Background Glow -->
        <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-secondary/15 blur-3xl pointer-events-none"></div>
    </div>

    <!-- BULETIN GEREJA TERSEMAT (IN-PAGE PDF VIEWER LANGSUNG) -->
    @php
        $fileBuletin = $warta->buletin_file ?: $warta->file_pdf;
        $ext = $fileBuletin ? strtolower(pathinfo($fileBuletin, PATHINFO_EXTENSION)) : '';
    @endphp

    @if(($warta->buletin_tipe ?? 'none') !== 'none' || $fileBuletin || $warta->buletin_link)
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-secondary/30 space-y-5" data-aos="fade-up" id="buletin-reader">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-outline-variant/20">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">auto_stories</span>
                </div>
                <div>
                    <span class="text-3xs uppercase font-bold tracking-widest text-secondary block">Dokumen Resmi Edisi</span>
                    <h3 class="font-serif-display text-xl sm:text-2xl font-bold text-primary">
                        {{ $warta->buletin_judul ?? 'Buletin Warta Jemaat' }}
                    </h3>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex flex-wrap items-center gap-2">
                @if($fileBuletin)
                    <a href="{{ asset('storage/' . $fileBuletin) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-primary bg-surface-container-high rounded-xl hover:bg-surface-container transition">
                        <span class="material-symbols-outlined text-base">open_in_new</span>
                        <span>Tab Baru</span>
                    </a>
                    <a href="{{ asset('storage/' . $fileBuletin) }}" download class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-secondary rounded-xl hover:bg-secondary/90 transition shadow-xs">
                        <span class="material-symbols-outlined text-base">download</span>
                        <span>Unduh PDF</span>
                    </a>
                @elseif($warta->buletin_link)
                    <a href="{{ $warta->buletin_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-secondary rounded-xl hover:bg-secondary/90 transition shadow-xs">
                        <span class="material-symbols-outlined text-base">open_in_new</span>
                        <span>Buka Link Buletin</span>
                    </a>
                @endif
            </div>
        </div>

        @if($ext === 'pdf')
            <!-- In-Page PDF Reader Langsung -->
            <div class="rounded-2xl overflow-hidden border border-outline-variant/30 shadow-inner bg-slate-900">
                <div class="bg-slate-800 text-slate-200 px-4 py-2 text-2xs flex items-center justify-between border-b border-slate-700">
                    <span class="flex items-center gap-1.5 font-medium">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Pratinjau Buletin PDF Langsung (Gunakan scroll / zoom untuk membaca)</span>
                    </span>
                    <span class="text-slate-400 font-mono text-3xs">{{ basename($fileBuletin) }}</span>
                </div>
                <iframe src="{{ asset('storage/' . $fileBuletin) }}#toolbar=1&navpanes=0" class="w-full h-[650px] sm:h-[850px] border-0 bg-white" title="Pratinjau Buletin PDF">
                    <div class="p-8 text-center text-white space-y-3">
                        <p class="text-sm">Browser Anda tidak mendukung penampil PDF langsung.</p>
                        <a href="{{ asset('storage/' . $fileBuletin) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-secondary text-white rounded-xl font-bold text-xs">
                            <span class="material-symbols-outlined text-base">download</span>
                            <span>Buka / Unduh File PDF</span>
                        </a>
                    </div>
                </iframe>
            </div>
        @elseif($warta->buletin_link)
            <!-- Link / Embed Eksternal -->
            <div class="p-6 rounded-2xl bg-surface-container-low border border-outline-variant/20 text-center space-y-3">
                <span class="material-symbols-outlined text-secondary text-4xl">link</span>
                <h4 class="font-bold text-primary text-sm">Buletin Tersedia Secara Online</h4>
                <p class="text-xs text-on-surface-variant max-w-md mx-auto">Dokumen buletin ini dapat diakses dan dibaca melalui tautan eksternal resmi (Canva / Google Drive).</p>
                <a href="{{ $warta->buletin_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-2.5 bg-secondary text-white text-xs font-bold rounded-xl hover:bg-secondary/90 transition shadow-xs">
                    <span class="material-symbols-outlined text-base">menu_book</span>
                    <span>Buka &amp; Baca Buletin Online</span>
                </a>
            </div>
        @else
            <!-- Dokumen format lain (misal .pub) -->
            <div class="p-6 rounded-2xl bg-surface-container-low border border-outline-variant/20 text-center space-y-3">
                <span class="material-symbols-outlined text-secondary text-4xl">description</span>
                <h4 class="font-bold text-primary text-sm">Dokumen Buletin ({{ strtoupper($ext) }})</h4>
                <p class="text-xs text-on-surface-variant max-w-md mx-auto">Dokumen ini telah dilampirkan oleh pengurus dan dapat diunduh untuk dibuka melalui aplikasi terkait.</p>
                <a href="{{ asset('storage/' . $fileBuletin) }}" download class="inline-flex items-center gap-2 px-5 py-2.5 bg-secondary text-white text-xs font-bold rounded-xl hover:bg-secondary/90 transition shadow-xs">
                    <span class="material-symbols-outlined text-base">download</span>
                    <span>Unduh Dokumen Buletin ({{ strtoupper($ext) }})</span>
                </a>
            </div>
        @endif
    </div>
    @endif

    <!-- Main Content: Renungan & Pokok Doa (2 Kolom) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Kolom Kiri: Khotbah & Renungan Firman (7 Cols) -->
        <div class="lg:col-span-7 space-y-8">
            @if($warta->ringkasan_khotbah)
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-4" data-aos="fade-up">
                <div class="flex items-center gap-2.5 pb-3 border-b border-outline-variant/20">
                    <div class="w-9 h-9 rounded-xl bg-primary-container text-secondary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">lightbulb</span>
                    </div>
                    <h3 class="font-serif-display text-xl font-bold text-primary">Intisari &amp; Renungan Khotbah</h3>
                </div>
                <div class="prose prose-sm max-w-none text-on-surface-variant leading-relaxed whitespace-pre-line font-sans text-xs sm:text-sm">
                    {{ $warta->ringkasan_khotbah }}
                </div>
            </div>
            @endif

            @if($warta->pengumuman_kegiatan)
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-4" data-aos="fade-up">
                <div class="flex items-center gap-2.5 pb-3 border-b border-outline-variant/20">
                    <div class="w-9 h-9 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">campaign</span>
                    </div>
                    <h3 class="font-serif-display text-xl font-bold text-primary">Warta &amp; Pengumuman Sepekan</h3>
                </div>
                <div class="prose prose-sm max-w-none text-on-surface-variant leading-relaxed whitespace-pre-line font-sans text-xs sm:text-sm">
                    {{ $warta->pengumuman_kegiatan }}
                </div>
            </div>
            @endif
        </div>

        <!-- Kolom Kanan: Pokok Doa & Ulang Tahun (5 Cols) -->
        <div class="lg:col-span-5 space-y-8">
            <!-- POKOK DOA SYAFAAT -->
            @if($warta->pokok_doa)
            <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-outline-variant/30 space-y-4" data-aos="fade-left">
                <div class="flex items-center gap-2.5 pb-3 border-b border-outline-variant/20">
                    <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">volunteer_activism</span>
                    </div>
                    <h3 class="font-serif-display text-lg font-bold text-primary">Pokok Doa Syafaat</h3>
                </div>
                <div class="prose prose-sm text-xs text-on-surface-variant leading-relaxed whitespace-pre-line">
                    {{ $warta->pokok_doa }}
                </div>
            </div>
            @endif

            <!-- ULANG TAHUN JEMAAT PADA EDISI INI -->
            <div class="bg-gradient-to-br from-amber-500/10 via-white to-surface-container-low rounded-3xl p-6 sm:p-7 shadow-sm border border-amber-500/25 space-y-4" data-aos="fade-left">
                <div class="flex items-center justify-between border-b border-amber-500/20 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">cake</span>
                        </div>
                        <h3 class="font-serif-display font-bold text-primary text-lg">Ulang Tahun Jemaat</h3>
                    </div>
                    <span class="text-2xs font-bold text-amber-900 bg-amber-200/70 px-2.5 py-0.5 rounded-full">Pekan Ini</span>
                </div>

                @if($ultahList->isNotEmpty())
                <div class="space-y-2.5 max-h-[320px] overflow-y-auto pr-1">
                    @foreach($ultahList as $u)
                    <div onclick="openBirthdayCard('{{ addslashes($u->nama_lengkap) }}', '{{ $u->umur_ke ?? '' }}', '{{ \Carbon\Carbon::parse($u->tanggal_lahir)->translatedFormat('d F') }}', '{{ $u->jenis_kelamin }}', '{{ addslashes($u->ucapan_ultah ?? '') }}')" 
                         class="p-2.5 rounded-xl bg-white border border-amber-500/20 flex items-center justify-between gap-3 shadow-2xs cursor-pointer hover:border-amber-400 hover:shadow-xs transition-all group"
                         title="Klik untuk membuka kartu ucapan khusus">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1">
                                <span class="font-bold text-xs text-primary group-hover:text-secondary transition truncate block">{{ $u->nama_lengkap }}</span>
                                <span class="material-symbols-outlined text-[13px] text-amber-500 opacity-0 group-hover:opacity-100 transition-opacity">download</span>
                            </div>
                            <span class="text-2xs text-outline">{{ $u->wilayah->nama_wilayah ?? 'Keluarga Jemaat' }}</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-lg bg-amber-100/70 text-amber-900 font-bold text-2xs whitespace-nowrap border border-amber-300/40 shrink-0">
                            {{ \Carbon\Carbon::parse($u->tanggal_lahir)->translatedFormat('d F') }}
                        </span>
                    </div>
                    @endforeach
                </div>
                <p class="text-[10px] text-center text-amber-900/80 font-medium pt-1">* Klik nama jemaat untuk unduh kartu ucapan pribadi</p>
                @else
                <p class="text-xs text-outline text-center py-4">Tidak ada jemaat yang berulang tahun pada pekan ini.</p>
                @endif
            </div>

            <!-- ARSIP LAINNYA -->
            @if(!empty($arsipLain) && $arsipLain->isNotEmpty())
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-outline-variant/30 space-y-3">
                <h4 class="font-serif-display font-bold text-primary text-base flex items-center gap-1.5 pb-2 border-b border-outline-variant/20">
                    <span class="material-symbols-outlined text-secondary text-sm">history_edu</span>
                    <span>Edisi Warta Lainnya</span>
                </h4>
                <div class="divide-y divide-outline-variant/15">
                    @foreach($arsipLain as $al)
                    <a href="{{ route('public.warta.detail', $al->id) }}" class="py-2.5 flex items-center justify-between gap-3 text-xs hover:text-secondary transition group block">
                        <div class="min-w-0">
                            <span class="font-bold text-primary group-hover:text-secondary truncate block">"{{ $al->tema_mingguan }}"</span>
                            <span class="text-2xs text-outline">{{ $al->edisi }} • {{ $al->tanggal_warta->translatedFormat('d M Y') }}</span>
                        </div>
                        <span class="material-symbols-outlined text-[14px] text-outline group-hover:translate-x-1 transition-transform">chevron_right</span>
                    </a>
                    @endforeach
                </div>
                <div class="pt-2 text-center">
                    <a href="{{ route('public.warta') }}" class="text-xs font-bold text-secondary hover:underline">Lihat Semua Arsip &rarr;</a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL KARTU UCAPAN ULANG TAHUN PERSONAL DENGAN CUSTOM    -->
<!-- ======================================================== -->
<div id="birthdayCardModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-surface rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-4 shadow-2xl border border-outline-variant/40 relative max-h-[95vh] overflow-y-auto">
        <button type="button" onclick="closeBirthdayCard()" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-surface-container hover:bg-surface-container-high flex items-center justify-center text-outline transition">
            <span class="material-symbols-outlined text-base">close</span>
        </button>

        <div class="text-center space-y-1">
            <span class="text-2xs font-bold uppercase tracking-widest text-secondary">✦ Kartu Syukur Jemaat ✦</span>
            <h3 class="font-serif-display text-xl sm:text-2xl font-bold text-primary">Kartu Ucapan Ulang Tahun</h3>
        </div>

        <!-- Visual Card Preview -->
        <div id="cardPreviewContainer" class="bg-gradient-to-br from-[#001428] via-[#0f2942] to-[#775a19] text-white p-6 sm:p-8 rounded-2xl border-2 border-[#e9c176] shadow-xl text-center space-y-3 relative">
            <div class="text-2xs text-[#ffdea5] uppercase font-bold tracking-widest">
                GBI Shekinah Glory • Be House of Glory
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

            <p class="text-xs sm:text-sm text-[#ffdea5] font-serif italic leading-relaxed px-2 whitespace-pre-line" id="cardBlessingText">
                "TUHAN memberkati engkau dan melindungi engkau; TUHAN menyinari engkau dengan wajah-Nya dan memberi engkau kasih karunia; TUHAN menghadapkan wajah-Nya kepadamu dan memberi engkau damai sejahtera."
            </p>

            <div class="pt-2 text-2xs text-white/60 border-t border-white/10">
                Kiranya hikmat, kesehatan, sukacita, dan penyertaan Tuhan senantiasa melimpah.<br>
                <strong>— Gembala Sidang &amp; Majelis GBI Shekinah Glory</strong>
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
                <span>Download Kartu (.PNG)</span>
            </button>
        </div>
    </div>
</div>

<script>
let currentBirthdayData = { name: '', age: '', day: '', gender: '', message: '' };

function openBirthdayCard(name, age, day, gender, customUcapan) {
    const defaultVerse = '"TUHAN memberkati engkau dan melindungi engkau; TUHAN menyinari engkau dengan wajah-Nya dan memberi engkau kasih karunia; TUHAN menghadapkan wajah-Nya kepadamu dan memberi engkau damai sejahtera." (Bilangan 6:24-26)';
    
    const message = customUcapan && customUcapan.trim() !== '' ? customUcapan.trim() : defaultVerse;
    currentBirthdayData = { name, age, day, gender, message };
    
    const salutation = gender === 'L' ? 'Bapak / Saudara' : 'Ibu / Saudari';
    document.getElementById('cardSalutation').textContent = `Kepada yang Terkasih ${salutation}:`;
    document.getElementById('cardPersonName').textContent = name;
    document.getElementById('cardAgeBadge').textContent = age ? `HUT ke-${age} Tahun (${day})` : `Hari Ulang Tahun (${day})`;
    document.getElementById('cardBlessingText').textContent = message;

    document.getElementById('birthdayCardModal').classList.remove('hidden');
}

function closeBirthdayCard() {
    document.getElementById('birthdayCardModal').classList.add('hidden');
}

function wrapText(context, text, x, y, maxWidth, lineHeight) {
    const words = text.split(' ');
    let line = '';
    let curY = y;
    for(let n = 0; n < words.length; n++) {
        const testLine = line + words[n] + ' ';
        const metrics = context.measureText(testLine);
        const testWidth = metrics.width;
        if (testWidth > maxWidth && n > 0) {
            context.fillText(line.trim(), x, curY);
            line = words[n] + ' ';
            curY += lineHeight;
        } else {
            line = testLine;
        }
    }
    context.fillText(line.trim(), x, curY);
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
    ctx.fillStyle = 'rgba(0, 20, 40, 0.45)';
    ctx.beginPath();
    ctx.roundRect(100, 560, w - 200, 310, 24);
    ctx.fill();
    ctx.strokeStyle = 'rgba(233, 193, 118, 0.3)';
    ctx.stroke();

    ctx.fillStyle = '#ffdea5';
    ctx.font = 'italic 28px serif';
    wrapText(ctx, currentBirthdayData.message, w / 2, 640, w - 260, 44);

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
