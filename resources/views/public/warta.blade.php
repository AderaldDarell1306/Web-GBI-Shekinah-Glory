@extends('layouts.public')

@section('title', 'Warta Jemaat Digital - GBI Shekinah Glory')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <!-- Header -->
    <div class="text-center max-w-3xl mx-auto mb-12" data-aos="fade-up">
        <span class="text-xs font-bold uppercase tracking-widest text-secondary mb-2 block">Media Informasi & Pembinaan Rohani</span>
        <h1 class="font-serif-display text-3xl sm:text-4xl font-bold text-primary mb-3">Warta Jemaat Digital</h1>
        <p class="text-sm text-on-surface-variant">Intisari firman Tuhan mingguan, pengumuman ibadah sepekan, pokok doa jemaat, dan ucapan selamat ulang tahun.</p>
        <div class="w-16 h-1 bg-secondary mx-auto mt-4 rounded-full"></div>
    </div>

    @if($warta)
        <!-- Edition Selector & Info Bar -->
        <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-outline-variant/30 mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4" data-aos="fade-up">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-2xl">menu_book</span>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-primary">{{ $warta->edisi }}</h2>
                    <p class="text-xs text-on-surface-variant">{{ $warta->tanggal_warta->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            <!-- Edisi Dropdown / Arsip -->
            <div class="flex items-center gap-3">
                @if($arsipWarta->count() > 1)
                    <div class="flex items-center gap-2">
                        <label for="pilih_edisi" class="text-xs font-medium text-on-surface-variant">Pilih Edisi:</label>
                        <select id="pilih_edisi" onchange="location = this.value;" class="text-xs rounded-lg border-outline-variant/40 bg-surface text-primary py-1.5 px-3 focus:border-secondary focus:ring-secondary">
                            @foreach($arsipWarta as $arsip)
                                <option value="{{ route('public.warta', ['id' => $arsip->id]) }}" {{ $arsip->id == $warta->id ? 'selected' : '' }}>
                                    {{ $arsip->edisi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if($warta->buletin_file)
                    <a href="{{ asset('storage/' . $warta->buletin_file) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-secondary rounded-xl hover:bg-secondary/90 transition shadow-xs">
                        <span class="material-symbols-outlined text-base">auto_stories</span>
                        <span>Unduh Buletin ({{ strtoupper(pathinfo($warta->buletin_file, PATHINFO_EXTENSION)) }})</span>
                    </a>
                @elseif($warta->buletin_link)
                    <a href="{{ $warta->buletin_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-secondary rounded-xl hover:bg-secondary/90 transition shadow-xs">
                        <span class="material-symbols-outlined text-base">menu_book</span>
                        <span>Baca Buletin Online</span>
                    </a>
                @endif

                @if($warta->file_pdf)
                    <a href="{{ asset('storage/' . $warta->file_pdf) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-primary rounded-xl hover:bg-primary-dark transition shadow-xs">
                        <span class="material-symbols-outlined text-base">download</span>
                        <span>Unduh PDF</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Sermon & Announcements (2 Cols) -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Theme Banner -->
                @if($warta->tema_mingguan)
                    <div class="bg-gradient-to-r from-primary to-primary-dark text-white rounded-2xl p-6 sm:p-8 shadow-sm relative overflow-hidden" data-aos="fade-up">
                        <div class="relative z-10 space-y-2">
                            <span class="text-xs uppercase font-bold tracking-widest text-secondary-container">Tema Mingguan</span>
                            <h3 class="font-serif-display text-2xl sm:text-3xl font-bold leading-tight">
                                "{{ $warta->tema_mingguan }}"
                            </h3>
                            @if($warta->ayat_tema)
                                <p class="text-xs sm:text-sm text-secondary-container font-medium italic mt-2">
                                    <span class="material-symbols-outlined text-xs align-middle">menu_book</span> {{ $warta->ayat_tema }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Sermon / Renungan -->
                @if($warta->ringkasan_khotbah)
                    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-4" data-aos="fade-up">
                        <div class="flex items-center gap-2.5 pb-3 border-b border-outline-variant/20">
                            <span class="material-symbols-outlined text-secondary text-2xl">lightbulb</span>
                            <h3 class="font-serif-display text-xl font-bold text-primary">Renungan & Inti Firman Tuhan</h3>
                        </div>
                        <div class="prose prose-sm max-w-none text-on-surface-variant leading-relaxed whitespace-pre-line font-sans text-sm">
                            {{ $warta->ringkasan_khotbah }}
                        </div>
                    </div>
                @endif

                <!-- BULETIN GEREJA (PRD v2.1 2.5: WARTA + BULETIN) -->
                @if(($warta->buletin_tipe ?? 'none') !== 'none')
                    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-secondary/30 space-y-4 relative overflow-hidden" data-aos="fade-up">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-outline-variant/20">
                            <div class="flex items-center gap-2.5">
                                <span class="material-symbols-outlined text-secondary text-2xl">auto_stories</span>
                                <div>
                                    <h3 class="font-serif-display text-xl font-bold text-primary">{{ $warta->buletin_judul ?? 'Buletin Gereja Shekinah Glory' }}</h3>
                                    <p class="text-xs text-outline">Edisi Buletin Rohani Terintegrasi Warta Digital</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-2xs font-bold bg-secondary-container text-secondary self-start sm:self-auto">
                                BULETIN RESMI
                            </span>
                        </div>

                        @if($warta->buletin_tipe === 'upload' && $warta->buletin_file)
                            @php
                                $ext = strtolower(pathinfo($warta->buletin_file, PATHINFO_EXTENSION));
                            @endphp
                            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center font-bold">
                                        <span class="material-symbols-outlined text-[24px]">
                                            {{ $ext === 'pub' ? 'newspaper' : ($ext === 'pdf' ? 'picture_as_pdf' : 'description') }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-primary">Dokumen Buletin ({{ strtoupper($ext) }})</h4>
                                        <p class="text-xs text-outline">
                                            {{ $ext === 'pub' ? 'Format Microsoft Publisher (.pub) siap diunduh & diakses' : 'Format dokumen buletin resmi jemaat siap diunduh' }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $warta->buletin_file) }}" target="_blank" class="px-4 py-2 bg-secondary hover:bg-secondary/90 text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center justify-center gap-1.5 shrink-0">
                                    <span class="material-symbols-outlined text-[16px]">download</span>
                                    <span>Unduh File Buletin (.{{ strtoupper($ext) }})</span>
                                </a>
                            </div>
                        @elseif($warta->buletin_tipe === 'link' && $warta->buletin_link)
                            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold">
                                        <span class="material-symbols-outlined text-[24px]">link</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-primary">Buletin Digital Interaktif</h4>
                                        <p class="text-xs text-outline">Tautan e-book / flipbook / dokumen buletin online</p>
                                    </div>
                                </div>
                                <a href="{{ $warta->buletin_link }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 bg-primary hover:bg-primary-container text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center justify-center gap-1.5 shrink-0">
                                    <span class="material-symbols-outlined text-[16px]">menu_book</span>
                                    <span>Baca Buletin Online</span>
                                </a>
                            </div>
                        @elseif($warta->buletin_tipe === 'manual' && $warta->buletin_content)
                            <div class="prose prose-sm max-w-none text-on-surface-variant leading-relaxed whitespace-pre-line font-sans text-sm p-4 rounded-xl bg-surface-container-low border border-outline-variant/20">
                                {{ $warta->buletin_content }}
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Announcements -->
                @if($warta->pengumuman_kegiatan)
                    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-4" data-aos="fade-up">
                        <div class="flex items-center gap-2.5 pb-3 border-b border-outline-variant/20">
                            <span class="material-symbols-outlined text-secondary text-2xl">campaign</span>
                            <h3 class="font-serif-display text-xl font-bold text-primary">Pengumuman & Agenda Kegiatan</h3>
                        </div>
                        <div class="text-on-surface-variant leading-relaxed whitespace-pre-line text-sm">
                            {{ $warta->pengumuman_kegiatan }}
                        </div>
                    </div>
                @endif

                <!-- Prayer Requests -->
                @if($warta->pokok_doa)
                    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-4" data-aos="fade-up">
                        <div class="flex items-center gap-2.5 pb-3 border-b border-outline-variant/20">
                            <span class="material-symbols-outlined text-secondary text-2xl">volunteer_activism</span>
                            <h3 class="font-serif-display text-xl font-bold text-primary">Pokok Doa Syafaat Sepekan</h3>
                        </div>
                        <div class="text-on-surface-variant leading-relaxed whitespace-pre-line text-sm">
                            {{ $warta->pokok_doa }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right: Weekly Birthday Celebration & Church Info (1 Col) -->
            <div class="space-y-6">
                <!-- Birthday Celebration Card -->
                <div class="bg-gradient-to-br from-amber-50/90 via-white to-amber-50/40 rounded-2xl p-6 shadow-sm border border-amber-200/80 space-y-4" data-aos="fade-up">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow-xs">
                            <span class="material-symbols-outlined text-2xl">cake</span>
                        </div>
                        <div>
                            <h4 class="font-serif-display text-lg font-bold text-primary">Ulang Tahun Jemaat</h4>
                            <p class="text-xs text-amber-800">Pekan {{ $warta->tanggal_warta->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="bg-white/80 p-3.5 rounded-xl border border-amber-200/60 text-xs text-amber-900 italic leading-relaxed">
                        "Tuhan memberkati engkau dan melindungi engkau; Tuhan menyinari engkau dengan wajah-Nya dan memberi engkau kasih karunia." (Bilangan 6:24-25)
                    </div>

                    <div class="space-y-2.5 pt-1">
                        @forelse($ultahList as $u)
                            <div onclick="openBirthdayCard('{{ addslashes($u->nama_lengkap) }}', '{{ $u->umur_ke }}', '{{ $u->hari_ultah }}', '{{ $u->jenis_kelamin }}')" 
                                 class="bg-white p-3.5 rounded-xl border border-amber-100 shadow-2xs flex items-center justify-between gap-3 cursor-pointer hover:border-amber-400 hover:shadow-md transition-all group"
                                 title="Klik untuk membuka & mengunduh kartu ucapan pribadi">
                                <div class="flex-1">
                                    <div class="text-xs font-bold text-primary group-hover:text-secondary transition flex items-center gap-1.5">
                                        <span>{{ $u->jenis_kelamin == 'L' ? 'Bpk/Sdr.' : 'Ibu/Sdri.' }} {{ $u->nama_lengkap }}</span>
                                        <span class="material-symbols-outlined text-[14px] text-amber-500 opacity-0 group-hover:opacity-100 transition-opacity">download</span>
                                    </div>
                                    <div class="text-2xs text-outline mt-0.5">{{ $u->hari_ultah }} &bull; {{ $u->nama_wilayah ?? ($u->wilayah?->nama_wilayah ?? 'Namosain') }}</div>
                                </div>
                                <span class="text-2xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-200 shrink-0">
                                    HUT {{ $u->umur_ke }} th
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-outline text-center py-4">Tidak ada jemaat yang berulang tahun pekan ini.</p>
                        @endforelse
                    </div>

                    <div class="text-2xs text-center text-amber-800 pt-2 font-medium">
                        * Klik nama jemaat di atas untuk melihat & mengunduh <strong>Kartu Ucapan Pribadi</strong>.
                    </div>
                </div>

                <!-- Kontak & Pokok Doa Box -->
                <div class="bg-surface-container-low rounded-2xl p-6 border border-outline-variant/30 space-y-3" data-aos="fade-up">
                    <h4 class="font-serif-display text-base font-bold text-primary">Kirimkan Pokok Doa</h4>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Apakah Anda atau keluarga membutuhkan dukungan doa? Tim pastoral GBI Shekinah Glory siap mendoakan Anda.
                    </p>
                    <a href="{{ route('public.kontak') }}" class="inline-flex items-center gap-2 text-xs font-bold text-secondary hover:underline pt-1">
                        Hubungi Tim Doa
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl p-16 text-center border border-outline-variant/30 max-w-xl mx-auto shadow-sm">
            <span class="material-symbols-outlined text-6xl text-outline mb-4">newspaper</span>
            <h3 class="font-serif-display text-2xl font-bold text-primary mb-2">Belum Ada Warta Diterbitkan</h3>
            <p class="text-sm text-on-surface-variant">Warta jemaat digital edisi terbaru sedang dipersiapkan oleh tim sekretariat gereja.</p>
        </div>
    @endif
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

        <!-- Hidden Canvas for Exporting High-Resolution Image -->
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
    document.getElementById('cardAgeBadge').textContent = `HUT ke-${age} Tahun (${day})`;

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
    const badgeText = `Ulang Tahun yang ke-${currentBirthdayData.age}`;
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
