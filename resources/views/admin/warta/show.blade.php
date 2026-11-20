@extends('layouts.admin')

@section('title', $warta->edisi)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $warta->status == 'terbit' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                    {{ ucfirst($warta->status) }}
                </span>
                <span class="text-xs text-gray-400 font-mono">{{ $warta->tanggal_warta->format('l, d F Y') }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $warta->edisi }}</h1>
            @if($warta->tema_mingguan)
                <p class="text-sm font-medium text-primary mt-0.5">Tema: "{{ $warta->tema_mingguan }}" ({{ $warta->ayat_tema }})</p>
            @endif
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('public.warta', ['id' => $warta->id]) }}" target="_blank" class="px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition shadow-xs flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Lihat Tampilan Publik
            </a>
            <a href="{{ route('admin.warta.edit', $warta->id) }}" class="px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition shadow-xs flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.warta.index') }}" class="px-3.5 py-2 text-xs font-semibold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                Kembali
            </a>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Content Preview (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Renungan Khotbah -->
            @if($warta->ringkasan_khotbah)
                <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-xs space-y-3">
                    <div class="flex items-center gap-2 text-primary font-bold text-sm uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Renungan & Inti Firman Tuhan
                    </div>
                    <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $warta->ringkasan_khotbah }}
                    </div>
                </div>
            @endif

            <!-- Selamat Ulang Tahun Jemaat (Card) -->
            <div class="bg-gradient-to-br from-amber-50/80 via-white to-amber-50/40 rounded-xl p-6 border border-amber-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Selamat Ulang Tahun Pekan Ini</h3>
                            <p class="text-xs text-amber-800 italic">"Tuhan memberkati engkau dan melindungi engkau..." (Bilangan 6:24-25)</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-amber-900 px-3 py-1 bg-amber-100 rounded-full">
                        {{ $ultahList->count() }} Jemaat
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    @forelse($ultahList as $u)
                        <div onclick="openBirthdayCard('{{ addslashes($u->nama_lengkap) }}', '{{ $u->umur_ke }}', '{{ $u->hari_ultah }}', '{{ $u->jenis_kelamin }}')" 
                             class="bg-white p-3.5 rounded-lg border border-amber-100 shadow-2xs flex items-start justify-between gap-3 cursor-pointer hover:border-amber-400 hover:shadow-md transition-all group"
                             title="Klik untuk membuka & download kartu ucapan pribadi">
                            <div>
                                <div class="text-sm font-bold text-gray-900 group-hover:text-secondary transition flex items-center gap-1.5">
                                    <span>{{ $u->jenis_kelamin == 'L' ? 'Bpk/Sdr.' : 'Ibu/Sdri.' }} {{ $u->nama_lengkap }}</span>
                                    <span class="material-symbols-outlined text-[15px] text-amber-500 opacity-0 group-hover:opacity-100 transition-opacity">download</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">Wilayah: {{ $u->nama_wilayah ?? ($u->wilayah?->nama_wilayah ?? 'Namosain') }}</div>
                                <div class="text-xs font-semibold text-amber-700 mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $u->hari_ultah }}
                                </div>
                            </div>
                            <span class="text-xs px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 font-bold border border-amber-200 shrink-0">
                                HUT ke-{{ $u->umur_ke }}
                            </span>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-6 text-xs text-gray-400">
                            Tidak ada anggota jemaat yang berulang tahun pada pekan ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Buletin Gereja Display (PRD v2.1 2.5) -->
            @if(($warta->buletin_tipe ?? 'none') !== 'none')
                <div class="bg-white rounded-xl p-6 border border-amber-200/80 shadow-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-secondary font-bold text-sm uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[20px]">auto_stories</span>
                            <span>{{ $warta->buletin_judul ?? 'Buletin Gereja Terintegrasi' }}</span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-2xs font-bold bg-secondary-container text-secondary">
                            Metode: {{ strtoupper($warta->buletin_tipe) }}
                        </span>
                    </div>

                    @if($warta->buletin_tipe === 'upload' && $warta->buletin_file)
                        <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-200 flex items-center justify-between">
                            <span class="text-xs text-gray-700 font-medium">Dokumen Buletin Resmi Terlampir (PDF)</span>
                            <a href="{{ asset('storage/' . $warta->buletin_file) }}" target="_blank" class="px-3.5 py-1.5 rounded-lg bg-secondary text-white text-xs font-bold hover:bg-secondary/90 transition shadow-xs flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                                <span>Lihat File Buletin</span>
                            </a>
                        </div>
                    @elseif($warta->buletin_tipe === 'link' && $warta->buletin_link)
                        <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-200 flex items-center justify-between">
                            <span class="text-xs text-gray-700 font-medium truncate max-w-sm">Tautan Buletin: <code class="font-mono text-2xs">{{ $warta->buletin_link }}</code></span>
                            <a href="{{ $warta->buletin_link }}" target="_blank" class="px-3.5 py-1.5 rounded-lg bg-primary text-white text-xs font-bold hover:bg-primary-container transition shadow-xs flex items-center gap-1.5 shrink-0">
                                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                                <span>Buka Link Buletin</span>
                            </a>
                        </div>
                    @elseif($warta->buletin_tipe === 'manual' && $warta->buletin_content)
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 text-xs text-gray-700 whitespace-pre-line leading-relaxed">
                            {{ $warta->buletin_content }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Pengumuman & Pokok Doa Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Pengumuman -->
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-xs space-y-3">
                    <div class="flex items-center gap-2 text-blue-600 font-bold text-sm uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        Pengumuman Kegiatan
                    </div>
                    <div class="text-xs text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $warta->pengumuman_kegiatan ?? 'Belum ada pengumuman kegiatan tambahan.' }}
                    </div>
                </div>

                <!-- Pokok Doa -->
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-xs space-y-3">
                    <div class="flex items-center gap-2 text-rose-600 font-bold text-sm uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        Pokok Doa Syafaat
                    </div>
                    <div class="text-xs text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $warta->pokok_doa ?? 'Mari doakan kesehatan jemaat dan pelayanan gereja sepekan.' }}
                    </div>
                </div>
            </div>

            <!-- Ringkasan Keuangan -->
            @if($warta->laporan_keuangan_ringkas)
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-xs space-y-2">
                    <div class="flex items-center gap-2 text-emerald-600 font-bold text-sm uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Laporan Persembahan & Kas Ringkas
                    </div>
                    <div class="text-xs text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $warta->laporan_keuangan_ringkas }}
                    </div>
                </div>
            @endif
        </div>

        <!-- WhatsApp Broadcast Box (1 Col) -->
        <div class="space-y-4">
            <div class="bg-emerald-900 text-white rounded-2xl p-6 shadow-md space-y-4 relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/30 flex items-center justify-center text-emerald-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white">Broadcast WhatsApp Grup</h3>
                            <p class="text-xs text-emerald-200">Format siap kirim ke Grup Jemaat</p>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-emerald-100 leading-relaxed">
                    Teks ini sudah dirancang otomatis dengan struktur rapi, emotikon elegan, renungan firman, dan ucapan ulang tahun lengkap untuk dibagikan ke <strong>Grup WhatsApp GBI Shekinah Glory</strong>.
                </p>

                <!-- Text Area for WhatsApp Content -->
                <div class="relative">
                    <textarea id="waText" rows="13" readonly class="w-full text-xs font-mono bg-emerald-950/70 border border-emerald-700/60 rounded-xl p-3 text-emerald-50 leading-relaxed focus:ring-0 focus:outline-none select-all">{{ $waBroadcastText }}</textarea>
                </div>

                <div class="space-y-2 pt-1">
                    <button type="button" onclick="copyWhatsAppBroadcast()" class="w-full py-2.5 px-4 text-xs font-bold text-emerald-950 bg-emerald-300 hover:bg-emerald-200 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                        <span id="copyBtnText">Salin Teks untuk Grup WA</span>
                    </button>

                    <a href="https://web.whatsapp.com/send?text={{ rawurlencode($waBroadcastText) }}" target="_blank" class="w-full py-2 px-4 text-xs font-semibold text-white bg-emerald-700/60 hover:bg-emerald-700 rounded-xl transition flex items-center justify-center gap-1.5 border border-emerald-600/40">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Buka di WhatsApp Web
                    </a>
                </div>
            </div>

            <!-- PDF Download Box (If Uploaded) -->
            @if($warta->file_pdf)
                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-xs flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xs">
                            PDF
                        </div>
                        <div>
                            <div class="text-xs font-bold text-gray-900">Warta Cetak (PDF)</div>
                            <div class="text-xs text-gray-400">Tersedia untuk diunduh</div>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $warta->file_pdf) }}" target="_blank" class="px-3 py-1.5 text-xs font-semibold text-white bg-rose-600 rounded-lg hover:bg-rose-700 transition">
                        Unduh
                    </a>
                </div>
            @endif
        </div>
    </div>
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
function copyWhatsAppBroadcast() {
    const copyText = document.getElementById("waText");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value).then(() => {
        const btn = document.getElementById("copyBtnText");
        btn.innerText = "✓ Teks Berhasil Disalin!";
        setTimeout(() => {
            btn.innerText = "Salin Teks untuk Grup WA";
        }, 3000);
    });
}

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
