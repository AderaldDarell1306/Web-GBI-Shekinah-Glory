@extends('layouts.admin')

@section('title', 'Ucapan & Kartu Ulang Tahun Jemaat - SIMJ GBI Shekinah Glory')

@section('content')
<div class="space-y-6">
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-outline-variant/30 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-secondary font-bold text-xs uppercase tracking-wider mb-1">
                <span class="material-symbols-outlined text-[18px]">cake</span>
                <span>Pastoral Care &amp; Perhatian Jemaat</span>
            </div>
            <h1 class="font-serif-display text-2xl font-bold text-primary">Ucapan &amp; Kartu Ulang Tahun</h1>
            <p class="text-xs text-on-surface-variant mt-1">
                Kelola doa dan ucapan khusus personal per jemaat, pratinjau kartu ucapan HUT beresolusi tinggi, serta kirim ucapan langsung via WhatsApp.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('public.beranda') }}#jadwal" target="_blank" class="px-4 py-2.5 rounded-xl border border-outline-variant hover:bg-surface-container text-xs font-semibold text-on-surface transition flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">visibility</span>
                <span>Tinjau di Beranda Web</span>
            </a>
        </div>
    </div>

    <!-- STATISTIK KARTU KECIL -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- HUT Pekan Ini -->
        <a href="{{ route('admin.ulang-tahun.index', ['filter' => 'pekan_ini']) }}" class="p-4 rounded-2xl border transition {{ $filter === 'pekan_ini' ? 'bg-amber-500/10 border-amber-500 shadow-xs ring-1 ring-amber-400' : 'bg-white border-outline-variant/30 hover:border-amber-400' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-on-surface-variant">HUT Pekan Ini</span>
                <span class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-800 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">celebration</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-serif-display text-2xl font-bold text-primary">{{ $countPekanIni }}</span>
                <span class="text-3xs text-outline font-semibold">Jemaat</span>
            </div>
            <span class="text-3xs text-amber-800 font-bold block mt-1">Tayang di Beranda Web</span>
        </a>

        <!-- HUT Bulan Ini -->
        <a href="{{ route('admin.ulang-tahun.index', ['filter' => 'bulan_ini']) }}" class="p-4 rounded-2xl border transition {{ $filter === 'bulan_ini' ? 'bg-blue-500/10 border-blue-500 shadow-xs ring-1 ring-blue-400' : 'bg-white border-outline-variant/30 hover:border-blue-400' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-on-surface-variant">HUT Bulan Ini</span>
                <span class="w-8 h-8 rounded-xl bg-blue-500/20 text-blue-800 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-serif-display text-2xl font-bold text-primary">{{ $countBulanIni }}</span>
                <span class="text-3xs text-outline font-semibold">Jemaat</span>
            </div>
            <span class="text-3xs text-blue-700 font-medium block mt-1">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
        </a>

        <!-- Ucapan Khusus Tersimpan -->
        <a href="{{ route('admin.ulang-tahun.index', ['filter' => 'custom_only']) }}" class="p-4 rounded-2xl border transition {{ $filter === 'custom_only' ? 'bg-emerald-500/10 border-emerald-500 shadow-xs ring-1 ring-emerald-400' : 'bg-white border-outline-variant/30 hover:border-emerald-400' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-on-surface-variant">Ucapan Khusus</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-800 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">edit_note</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-serif-display text-2xl font-bold text-primary">{{ $countCustom }}</span>
                <span class="text-3xs text-outline font-semibold">Doa Personal</span>
            </div>
            <span class="text-3xs text-emerald-700 font-medium block mt-1">Tersimpan di Sistem</span>
        </a>

        <!-- Total Terdata -->
        <a href="{{ route('admin.ulang-tahun.index', ['filter' => 'semua']) }}" class="p-4 rounded-2xl border transition {{ $filter === 'semua' ? 'bg-primary/10 border-primary shadow-xs ring-1 ring-primary' : 'bg-white border-outline-variant/30 hover:border-primary' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-on-surface-variant">Total Jemaat</span>
                <span class="w-8 h-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">badge</span>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="font-serif-display text-2xl font-bold text-primary">{{ $total }}</span>
                <span class="text-3xs text-outline font-semibold">Data Sesuai Filter</span>
            </div>
            <span class="text-3xs text-outline font-medium block mt-1">Status Jemaat Aktif</span>
        </a>
    </div>

    <!-- FILTER & PENCARIAN -->
    <div class="p-4 bg-white rounded-2xl border border-outline-variant/30 space-y-3">
        <form action="{{ route('admin.ulang-tahun.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <!-- Mode Filter Tab -->
            <div class="md:col-span-5 flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0">
                <a href="{{ route('admin.ulang-tahun.index', ['filter' => 'pekan_ini', 'wilayah_id' => $wilayahId, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $filter === 'pekan_ini' ? 'bg-primary text-white shadow-xs' : 'bg-surface-container text-on-surface hover:bg-surface-container-high' }}">
                    Pekan Ini
                </a>
                <a href="{{ route('admin.ulang-tahun.index', ['filter' => 'bulan_ini', 'wilayah_id' => $wilayahId, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $filter === 'bulan_ini' ? 'bg-primary text-white shadow-xs' : 'bg-surface-container text-on-surface hover:bg-surface-container-high' }}">
                    Bulan Ini
                </a>
                <a href="{{ route('admin.ulang-tahun.index', ['filter' => 'custom_only', 'wilayah_id' => $wilayahId, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $filter === 'custom_only' ? 'bg-primary text-white shadow-xs' : 'bg-surface-container text-on-surface hover:bg-surface-container-high' }}">
                    Doa Khusus
                </a>
                <a href="{{ route('admin.ulang-tahun.index', ['filter' => 'semua', 'wilayah_id' => $wilayahId, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $filter === 'semua' ? 'bg-primary text-white shadow-xs' : 'bg-surface-container text-on-surface hover:bg-surface-container-high' }}">
                    Semua
                </a>
            </div>

            <!-- Filter Bulan Tertentu -->
            <div class="md:col-span-2">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <select name="bulan" onchange="this.form.submit()" class="w-full px-2.5 py-1.5 text-xs rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
                    <option value="">-- Pilih Bulan --</option>
                    @foreach([
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ] as $mNum => $mName)
                    <option value="{{ $mNum }}" {{ (int)$bulanFilter === $mNum ? 'selected' : '' }}>
                        {{ $mName }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Wilayah -->
            <div class="md:col-span-2">
                <select name="wilayah_id" onchange="this.form.submit()" class="w-full px-2.5 py-1.5 text-xs rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none bg-white">
                    <option value="">-- Semua Wilayah --</option>
                    @foreach($wilayahList as $w)
                    <option value="{{ $w->id }}" {{ $wilayahId == $w->id ? 'selected' : '' }}>
                        [{{ $w->kode_prefix }}] {{ $w->nama_wilayah }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Pencarian Nama -->
            <div class="md:col-span-3 flex items-center gap-1.5">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-2.5 top-2 text-outline text-[16px]">search</span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari jemaat..." class="w-full pl-8 pr-2.5 py-1.5 text-xs rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none">
                </div>
                <button type="submit" class="px-3 py-1.5 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary/90 transition">
                    Cari
                </button>
                @if($search || $wilayahId || $filter !== 'pekan_ini')
                <a href="{{ route('admin.ulang-tahun.index') }}" class="p-1.5 rounded-xl border border-outline-variant hover:bg-surface-container text-outline transition" title="Reset Filter">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- TABEL DATA JEMAAT ULANG TAHUN -->
    <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-outline-variant/20 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="font-bold text-sm text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[20px]">cake</span>
                    <span>Daftar Ulang Tahun Jemaat ({{ $paginatedJemaat->total() }} Data)</span>
                </h2>
                <span class="text-3xs text-outline">
                    Mode Tampilan: <strong>{{ ucfirst(str_replace('_', ' ', $filter)) }}</strong>
                </span>
            </div>
            <div class="text-2xs text-on-surface-variant flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                <span>Ucapan Khusus Aktif</span>
                <span class="w-2.5 h-2.5 rounded-full bg-gray-300 inline-block ml-2"></span>
                <span>Standar (Bilangan 6:24-26)</span>
            </div>
        </div>

        @if($paginatedJemaat->isEmpty())
        <div class="p-12 text-center space-y-3">
            <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center mx-auto">
                <span class="material-symbols-outlined text-[32px]">event_busy</span>
            </div>
            <h3 class="font-serif-display text-base font-bold text-primary">Tidak Ada Jemaat yang Berulang Tahun</h3>
            <p class="text-xs text-outline max-w-md mx-auto">
                Tidak ditemukan jemaat yang berulang tahun untuk kriteria atau filter yang dipilih. Coba pilih filter "Bulan Ini" atau "Semua" di atas.
            </p>
            <a href="{{ route('admin.ulang-tahun.index', ['filter' => 'bulan_ini']) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary/90 transition">
                <span>Lihat Bulan Ini</span>
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-surface-container text-on-surface-variant border-b border-outline-variant/30 font-bold text-2xs uppercase tracking-wider">
                        <th class="py-3 px-4">Jemaat</th>
                        <th class="py-3 px-4">Wilayah</th>
                        <th class="py-3 px-4">Tanggal Lahir &amp; Usia</th>
                        <th class="py-3 px-4">Ucapan Doa Ulang Tahun</th>
                        <th class="py-3 px-4 text-center">Aksi Pastoral</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @foreach($paginatedJemaat as $jemaat)
                    <tr class="hover:bg-surface-container-low transition">
                        <!-- Identitas Jemaat -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full {{ $jemaat->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }} flex items-center justify-center font-bold text-xs shrink-0 shadow-xs border border-white">
                                    {{ strtoupper(substr($jemaat->nama_lengkap, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-primary truncate max-w-[200px] flex items-center gap-1.5">
                                        <span>{{ $jemaat->nama_lengkap }}</span>
                                        <span class="text-3xs font-semibold px-1.5 py-0.2 rounded {{ $jemaat->jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                            {{ $jemaat->jenis_kelamin === 'L' ? 'L' : 'P' }}
                                        </span>
                                    </div>
                                    <div class="text-3xs text-outline font-mono mt-0.5">
                                        No. KK: {{ $jemaat->no_kk ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Wilayah -->
                        <td class="py-3.5 px-4">
                            <span class="px-2 py-1 rounded-lg bg-surface-container text-primary font-bold text-2xs whitespace-nowrap border border-outline-variant/40">
                                {{ $jemaat->wilayah?->nama_wilayah ?? 'Keluarga Jemaat' }}
                            </span>
                        </td>

                        <!-- Tanggal & Usia -->
                        <td class="py-3.5 px-4">
                            <div class="space-y-0.5">
                                <span class="font-bold text-primary block">
                                    {{ $jemaat->hari_ultah }}
                                </span>
                                <div class="flex items-center gap-1.5 text-2xs text-outline">
                                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-900 font-bold text-3xs border border-amber-300/50">
                                        HUT ke-{{ $jemaat->umur_ke }}
                                    </span>
                                    <span>(Lahir: {{ $jemaat->tgl_lahir_formatted }})</span>
                                </div>
                            </div>
                        </td>

                        <!-- Ucapan Doa Saat Ini -->
                        <td class="py-3.5 px-4 max-w-xs">
                            @if(!empty($jemaat->ucapan_ultah))
                            <div class="space-y-1">
                                <div class="flex items-center gap-1 text-emerald-700 font-bold text-3xs uppercase tracking-wider">
                                    <span class="material-symbols-outlined text-[13px]">check_circle</span>
                                    <span>Doa Khusus Personal</span>
                                </div>
                                <p class="text-2xs text-on-surface line-clamp-2 italic bg-emerald-50/50 p-2 rounded-lg border border-emerald-200">
                                    "{{ $jemaat->ucapan_ultah }}"
                                </p>
                            </div>
                            @else
                            <div class="space-y-1">
                                <span class="text-3xs font-semibold px-2 py-0.5 rounded bg-gray-100 text-gray-700 border border-gray-200">
                                    Doa Standar (Bilangan 6:24-26)
                                </span>
                                <p class="text-3xs text-outline line-clamp-1 italic">
                                    "TUHAN memberkati engkau dan melindungi engkau..."
                                </p>
                            </div>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <!-- Tombol Ubah Ucapan -->
                                <button type="button" onclick="openEditModal({{ $jemaat->id }}, '{{ addslashes($jemaat->nama_lengkap) }}', '{{ addslashes($jemaat->ucapan_ultah ?? '') }}')" class="px-2.5 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary font-bold text-2xs transition flex items-center gap-1 shadow-2xs" title="Sesuaikan Ucapan Doa">
                                    <span class="material-symbols-outlined text-[15px] text-amber-600">edit_note</span>
                                    <span>Doa</span>
                                </button>

                                <!-- Tombol Buka & Download Kartu HUT -->
                                <button type="button" onclick="openPreviewCard('{{ addslashes($jemaat->nama_lengkap) }}', '{{ $jemaat->umur_ke }}', '{{ $jemaat->hari_ultah }}', '{{ $jemaat->jenis_kelamin }}', '{{ addslashes($jemaat->ucapan_ultah ?? '') }}')" class="px-2.5 py-1.5 rounded-lg bg-secondary/10 hover:bg-secondary/20 text-secondary font-bold text-2xs transition flex items-center gap-1 border border-secondary/20" title="Buka & Download Kartu HUT">
                                    <span class="material-symbols-outlined text-[15px]">style</span>
                                    <span>Kartu</span>
                                </button>

                                <!-- Tombol Kirim WhatsApp -->
                                @php
                                    $rawHp = preg_replace('/[^0-9]/', '', $jemaat->no_hp ?? '');
                                    if (str_starts_with($rawHp, '0')) {
                                        $rawHp = '62' . substr($rawHp, 1);
                                    }
                                    $defaultBlessing = "TUHAN memberkati engkau dan melindungi engkau; TUHAN menyinari engkau dengan wajah-Nya dan memberi engkau kasih karunia; TUHAN menghadapkan wajah-Nya kepadamu dan memberi engkau damai sejahtera. (Bilangan 6:24-26)";
                                    $blessingToUse = !empty($jemaat->ucapan_ultah) ? $jemaat->ucapan_ultah : $defaultBlessing;
                                    $waGreeting = urlencode("Shalom Bapak/Ibu/Saudara {$jemaat->nama_lengkap},\n\nSegenap Gembala Sidang, Majelis, dan Jemaat GBI Shekinah Glory mengucapkan Selamat Ulang Tahun yang ke-{$jemaat->umur_ke}!\n\nDoa & Berkat Firman Tuhan untuk Bapak/Ibu/Saudara:\n\"{$blessingToUse}\"\n\nKiranya kesehatan, sukacita, dan penyertaan Tuhan senantiasa melimpah selalu. Tuhan Yesus memberkati! ✦\n— GBI Shekinah Glory");
                                @endphp
                                @if(!empty($rawHp))
                                <a href="https://wa.me/{{ $rawHp }}?text={{ $waGreeting }}" target="_blank" class="p-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition flex items-center justify-center shadow-xs" title="Kirim Ucapan via WhatsApp">
                                    <span class="material-symbols-outlined text-[15px]">send</span>
                                </a>
                                @else
                                <span class="p-1.5 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed" title="No. HP tidak terdata">
                                    <span class="material-symbols-outlined text-[15px]">phone_disabled</span>
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- PAGINASI -->
        @if($paginatedJemaat->hasPages())
        <div class="p-4 border-t border-outline-variant/20">
            {{ $paginatedJemaat->links() }}
        </div>
        @endif
        @endif
    </div>
</div>

<!-- ================= MODAL EDIT UCAPAN KHUSUS ================= -->
<div id="modalEditUcapan" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full border border-outline-variant/30 shadow-2xl p-6 space-y-4 animate-in fade-in zoom-in duration-200">
        <div class="flex items-center justify-between pb-3 border-b border-outline-variant/20">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-600 text-[22px]">cake</span>
                <div>
                    <h3 class="font-serif-display text-base font-bold text-primary">Ucapan Doa Ulang Tahun</h3>
                    <p class="text-3xs text-outline" id="editPersonSubtitle">Jemaat: -</p>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-full hover:bg-surface-container flex items-center justify-center text-outline">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <form id="formUpdateUcapan" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Pilihan Cepat Template Firman / Doa Alkitabiah -->
            <div class="space-y-1.5">
                <label class="block text-2xs font-bold uppercase tracking-wider text-primary">
                    Pilihan Cepat Ayat &amp; Doa Berkat:
                </label>
                <div class="flex flex-wrap gap-1.5">
                    <button type="button" onclick="setTemplateVerse('bilangan')" class="px-2.5 py-1 rounded-lg text-3xs font-bold bg-amber-100 text-amber-900 border border-amber-300 hover:bg-amber-200 transition">
                        Bilangan 6:24-26
                    </button>
                    <button type="button" onclick="setTemplateVerse('mazmur20')" class="px-2.5 py-1 rounded-lg text-3xs font-bold bg-blue-100 text-blue-900 border border-blue-300 hover:bg-blue-200 transition">
                        Mazmur 20:4
                    </button>
                    <button type="button" onclick="setTemplateVerse('amsal3')" class="px-2.5 py-1 rounded-lg text-3xs font-bold bg-emerald-100 text-emerald-900 border border-emerald-300 hover:bg-emerald-200 transition">
                        Amsal 3:16
                    </button>
                    <button type="button" onclick="setTemplateVerse('yeremia29')" class="px-2.5 py-1 rounded-lg text-3xs font-bold bg-purple-100 text-purple-900 border border-purple-300 hover:bg-purple-200 transition">
                        Yeremia 29:11
                    </button>
                    <button type="button" onclick="setTemplateVerse('filipi4')" class="px-2.5 py-1 rounded-lg text-3xs font-bold bg-cyan-100 text-cyan-900 border border-cyan-300 hover:bg-cyan-200 transition">
                        Filipi 4:19
                    </button>
                </div>
            </div>

            <!-- Textarea Ucapan -->
            <div class="space-y-1">
                <label for="textareaUcapan" class="block text-xs font-bold text-primary">
                    Isi Pesan / Doa Ulang Tahun Khusus:
                </label>
                <textarea name="ucapan_ultah" id="textareaUcapan" rows="4" class="w-full text-xs p-3 rounded-xl border border-outline-variant/60 focus:border-secondary focus:outline-none transition leading-relaxed" placeholder="Tuliskan ucapan atau doa personal bagi jemaat ini..."></textarea>
                <span class="text-3xs text-outline block leading-tight">
                    * Doa ini akan otomatis tercetak di kartu ucapan HUT digital dan tayang saat jemaat membuka kartu ucapan di website.
                </span>
            </div>

            <!-- Tombol Simpan & Reset -->
            <div class="flex items-center justify-between pt-3 border-t border-outline-variant/20">
                <button type="button" onclick="resetToDefaultVerse()" class="px-3 py-2 rounded-xl text-xs font-semibold text-red-600 hover:bg-red-50 transition">
                    Kembalikan ke Standar
                </button>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-semibold text-outline hover:bg-surface-container transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-primary text-white text-xs font-bold shadow-md hover:bg-primary/90 transition flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">save</span>
                        <span>Simpan Doa Khusus</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL PRATINJAU KARTU HUT (CANVAS DOWNLOAD) ================= -->
<div id="modalPreviewCard" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-3xl max-w-md w-full border border-secondary/40 shadow-2xl p-5 space-y-4 animate-in fade-in zoom-in duration-200 text-center">
        <!-- Visual Birthday Card Component (Same Luxury Style) -->
        <div id="visualBirthdayCard" class="relative rounded-2xl bg-gradient-to-br from-[#001428] via-[#0f2942] to-[#001428] border-2 border-[#ffdea5]/50 p-6 shadow-2xl text-white space-y-4 overflow-hidden">
            <!-- Background Glow -->
            <div class="absolute -top-12 -right-12 w-40 h-40 bg-secondary/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-secondary/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="space-y-1">
                <span class="text-3xs uppercase tracking-widest text-[#ffdea5] font-bold">✦ GBI SHEKINAH GLORY ✦</span>
                <h3 class="font-serif-display text-xl font-bold text-white tracking-wide">Selamat Ulang Tahun</h3>
            </div>

            <div class="py-2 space-y-1 border-y border-white/20">
                <span class="text-xs text-white/80" id="cardSalutationText">Kepada yang Terkasih:</span>
                <h2 id="cardPersonNameText" class="font-serif-display text-2xl font-bold text-white tracking-wide">
                    Nama Jemaat
                </h2>
                <div class="inline-block px-3 py-1 rounded-full bg-secondary text-white font-bold text-xs" id="cardAgeBadgeText">
                    HUT ke-XX
                </div>
            </div>

            <p class="text-xs text-[#ffdea5] font-serif italic leading-relaxed px-2 whitespace-pre-line" id="cardBlessingBodyText">
                "TUHAN memberkati engkau dan melindungi engkau; TUHAN menyinari engkau dengan wajah-Nya dan memberi engkau kasih karunia; TUHAN menghadapkan wajah-Nya kepadamu dan memberi engkau damai sejahtera." (Bilangan 6:24-26)
            </p>

            <div class="pt-2 text-2xs text-white/60 border-t border-white/10">
                Kiranya hikmat, kesehatan, sukacita, dan penyertaan Tuhan senantiasa melimpah.<br>
                <strong>— Gembala Sidang &amp; Majelis GBI Shekinah Glory</strong>
            </div>
        </div>

        <!-- Hidden Canvas for Exporting High-Res Image -->
        <canvas id="exportCardCanvas" width="1080" height="1080" class="hidden"></canvas>

        <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closePreviewCard()" class="px-4 py-2 text-xs font-semibold text-outline hover:bg-surface-container rounded-xl transition">
                Tutup
            </button>
            <button type="button" onclick="downloadPreviewCard()" class="px-5 py-2.5 bg-secondary hover:bg-secondary/90 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">download</span>
                <span>Unduh Kartu (.PNG)</span>
            </button>
        </div>
    </div>
</div>

<script>
// Template Ayat Doa
const bibleVerses = {
    bilangan: '"TUHAN memberkati engkau dan melindungi engkau; TUHAN menyinari engkau dengan wajah-Nya dan memberi engkau kasih karunia; TUHAN menghadapkan wajah-Nya kepadamu dan memberi engkau damai sejahtera." (Bilangan 6:24-26)',
    mazmur20: '"Kiranya diberikan-Nya kepadamu apa yang kaukehendaki dan dijadikan-Nya berhasil apa yang kaurancangkan." (Mazmur 20:4)',
    amsal3: '"Umur panjang ada di tangan kanannya, di tangan kirinya kekayaan dan kehormatan. Jalannya adalah jalan kenikmatan, segala jalannya sejahtera semata-mata." (Amsal 3:16-17)',
    yeremia29: '"Sebab Aku ini mengetahui rancangan-rancangan apa yang ada pada-Ku mengenai kamu, demikianlah firman TUHAN, yaitu rancangan damai sejahtera dan bukan rancangan kecelakaan, untuk memberikan kepadamu hari depan yang penuh harapan." (Yeremia 29:11)',
    filipi4: '"Allahku akan memenuhi segala keperluanmu menurut kekayaan dan kemuliaan-Nya dalam Kristus Yesus." (Filipi 4:19)'
};

let currentJemaatId = null;
let currentCardData = { name: '', age: '', day: '', gender: '', message: '' };

function openEditModal(id, name, ucapan) {
    currentJemaatId = id;
    document.getElementById('editPersonSubtitle').textContent = 'Jemaat: ' + name;
    document.getElementById('textareaUcapan').value = ucapan || '';
    
    const form = document.getElementById('formUpdateUcapan');
    form.action = '/admin/ulang-tahun/' + id;

    document.getElementById('modalEditUcapan').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('modalEditUcapan').classList.add('hidden');
}

function setTemplateVerse(key) {
    if (bibleVerses[key]) {
        document.getElementById('textareaUcapan').value = bibleVerses[key];
    }
}

function resetToDefaultVerse() {
    document.getElementById('textareaUcapan').value = '';
}

function openPreviewCard(name, age, day, gender, customUcapan) {
    const defaultVerse = bibleVerses.bilangan;
    const message = customUcapan && customUcapan.trim() !== '' ? customUcapan.trim() : defaultVerse;
    
    currentCardData = { name, age, day, gender, message };

    const salutation = gender === 'L' ? 'Bapak / Saudara' : 'Ibu / Saudari';
    document.getElementById('cardSalutationText').textContent = 'Kepada yang Terkasih ' + salutation + ':';
    document.getElementById('cardPersonNameText').textContent = name;
    document.getElementById('cardAgeBadgeText').textContent = age ? 'HUT ke-' + age + ' Tahun (' + day + ')' : 'Hari Ulang Tahun (' + day + ')';
    document.getElementById('cardBlessingBodyText').textContent = message;

    document.getElementById('modalPreviewCard').classList.remove('hidden');
}

function closePreviewCard() {
    document.getElementById('modalPreviewCard').classList.add('hidden');
}

function wrapText(context, text, x, y, maxWidth, lineHeight) {
    const words = text.split(' ');
    let line = '';
    let curY = y;
    for(let n = 0; n < words.length; n++) {
        const testLine = line + words[n] + ' ';
        const metrics = context.measureText(testLine);
        if (metrics.width > maxWidth && n > 0) {
            context.fillText(line, x, curY);
            line = words[n] + ' ';
            curY += lineHeight;
        } else {
            line = testLine;
        }
    }
    context.fillText(line, x, curY);
    return curY;
}

function downloadPreviewCard() {
    const canvas = document.getElementById('exportCardCanvas');
    const ctx = canvas.getContext('2d');

    // 1. Background Gradient
    const gradient = ctx.createLinearGradient(0, 0, 1080, 1080);
    gradient.addColorStop(0, '#001428');
    gradient.addColorStop(0.5, '#0f2942');
    gradient.addColorStop(1, '#001428');
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, 1080, 1080);

    // 2. Gold Border Frame
    ctx.lineWidth = 14;
    ctx.strokeStyle = '#e9c176';
    ctx.strokeRect(50, 50, 980, 980);

    // Inner subtle border
    ctx.lineWidth = 2;
    ctx.strokeStyle = 'rgba(255, 222, 165, 0.4)';
    ctx.strokeRect(70, 70, 940, 940);

    // 3. Header Text
    ctx.textAlign = 'center';
    ctx.fillStyle = '#ffdea5';
    ctx.font = 'bold 24px "Plus Jakarta Sans", sans-serif';
    ctx.fillText('✦ GEREJA BETHEL INDONESIA SHEKINAH GLORY ✦', 540, 170);

    ctx.font = 'italic 34px "Playfair Display", serif';
    ctx.fillStyle = '#ffffff';
    ctx.fillText('Ucapan Doa & Berkat Ulang Tahun', 540, 230);

    // 4. Salutation & Name
    const salutation = currentCardData.gender === 'L' ? 'Bapak / Saudara' : 'Ibu / Saudari';
    ctx.font = '26px "Plus Jakarta Sans", sans-serif';
    ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
    ctx.fillText('Kepada yang Terkasih ' + salutation + ':', 540, 320);

    ctx.font = 'bold 64px "Playfair Display", serif';
    ctx.fillStyle = '#ffffff';
    ctx.fillText(currentCardData.name, 540, 400);

    // 5. Age Badge
    ctx.fillStyle = '#775a19';
    const badgeText = currentCardData.age ? 'HUT ke-' + currentCardData.age + ' Tahun (' + currentCardData.day + ')' : 'Hari Ulang Tahun (' + currentCardData.day + ')';
    ctx.fillRect(340, 435, 400, 54);
    ctx.font = 'bold 24px "Plus Jakarta Sans", sans-serif';
    ctx.fillStyle = '#ffffff';
    ctx.fillText(badgeText, 540, 470);

    // 6. Blessing Verse Body Text
    ctx.font = 'italic 30px "Playfair Display", Georgia, serif';
    ctx.fillStyle = '#fed488';
    wrapText(ctx, currentCardData.message, 540, 570, 840, 48);

    // 7. Footer
    ctx.font = '24px "Plus Jakarta Sans", sans-serif';
    ctx.fillStyle = 'rgba(255, 255, 255, 0.7)';
    ctx.fillText('Kiranya damai sejahtera, kesehatan, sukacita, dan penyertaan Tuhan', 540, 880);
    ctx.fillText('senantiasa melimpah di setiap langkah kehidupan.', 540, 915);

    ctx.font = 'bold 26px "Plus Jakarta Sans", sans-serif';
    ctx.fillStyle = '#ffdea5';
    ctx.fillText('— Gembala Sidang & Majelis Jemaat GBI Shekinah Glory —', 540, 965);

    // Trigger Download
    const link = document.createElement('a');
    link.download = 'Kartu_Ucapan_HUT_' + currentCardData.name.replace(/[^a-zA-Z0-9]/g, '_') + '.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}
</script>
@endsection
