@extends('layouts.admin')

@section('title', 'Detail Jadwal Pelayanan - SIMJ GBI Shekinah Glory')
@section('page_title', 'Detail Jadwal Pelayanan & Broadcast WhatsApp')

@section('content')
<div class="space-y-6">
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $rota->status_publikasi === 'terbit' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                    {{ $rota->status_publikasi }}
                </span>
                <span class="text-xs text-outline">• Dibuat oleh {{ $rota->creator->name ?? 'Admin' }}</span>
            </div>
            <h2 class="font-serif-display text-2xl font-bold text-primary mt-1">
                {{ $rota->sesiIbadah->nama_ibadah }} — {{ $rota->tanggal_pelayanan->translatedFormat('l, d F Y') }}
            </h2>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.pelayanan.edit', $rota->id) }}" class="px-3.5 py-2 rounded-xl border border-outline-variant text-xs font-semibold hover:bg-surface-container transition flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">edit</span>
                <span>Edit Jadwal</span>
            </a>
            <a href="{{ route('admin.pelayanan.index') }}" class="px-3.5 py-2 rounded-xl bg-surface-container text-xs font-semibold hover:bg-surface-container-high transition">
                Kembali ke Daftar
            </a>
        </div>
    </div>

    <!-- 2 Column Section: Broadcast WhatsApp & Susunan Petugas -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Kolom Kiri: Generator Pesan WhatsApp untuk Grup Jemaat/Pelayan -->
        <div class="lg:col-span-5 bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-4">
            <div class="flex items-center justify-between border-b border-outline-variant/20 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                        <span class="material-symbols-outlined text-[20px]">chat</span>
                    </span>
                    <div>
                        <h3 class="font-serif-display text-base font-bold text-primary">Format Broadcast Grup WA</h3>
                        <p class="text-[11px] text-on-surface-variant">Siap dikirimkan ke Grup WhatsApp Jemaat/Pelayan</p>
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-surface-container-low font-mono text-xs whitespace-pre-wrap text-primary border border-outline-variant/20 select-all leading-relaxed" id="waMessageText">{{ $waMessage }}</div>

            <div class="space-y-2 pt-1">
                <a href="{{ $waShareUrl }}" target="_blank" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">send</span>
                    <span>Kirim Langsung ke WhatsApp</span>
                </a>

                <button type="button" onclick="copyWaText()" class="w-full py-2.5 rounded-xl border border-outline-variant bg-white text-primary font-bold text-xs hover:bg-surface-container transition flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">content_copy</span>
                    <span id="btnCopyLabel">Salin Teks Pesan ke Clipboard</span>
                </button>
            </div>

            <div class="p-3 rounded-xl bg-secondary-container/40 text-[11px] text-on-surface flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-secondary shrink-0 mt-0.5">info</span>
                <span>Klik <strong>"Kirim Langsung"</strong> untuk memilih Grup WhatsApp di aplikasi WA Anda, atau <strong>"Salin Teks"</strong> untuk paste langsung ke chat grup.</span>
            </div>
        </div>

        <!-- Kolom Kanan: Roster Susunan Petugas Terjadwal -->
        <div class="lg:col-span-7 bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-4">
            <div class="border-b border-outline-variant/20 pb-3">
                <h3 class="font-serif-display text-base font-bold text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary">groups</span>
                    <span>Susunan Petugas Terjadwal ({{ $rota->petugas->count() }} Orang)</span>
                </h3>
                <p class="text-[11px] text-on-surface-variant mt-0.5">
                    Ibadah: <strong>{{ $rota->sesiIbadah->nama_ibadah }}</strong> • Jam: <strong>{{ $rota->sesiIbadah->jam }} WITA</strong> • Ruangan: <strong>{{ $rota->sesiIbadah->lokasi_ruangan }}</strong>
                </p>
            </div>

            <div class="divide-y divide-outline-variant/20 text-xs">
                @foreach($posisiLabels as $posKey => $posLabel)
                @if($groupedPetugas->has($posKey))
                <div class="py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center font-bold text-primary text-[10px]">
                            {{ strtoupper(substr($posKey, 0, 2)) }}
                        </span>
                        <div>
                            <span class="text-[11px] text-outline uppercase font-semibold block">{{ $posLabel }}</span>
                            @foreach($groupedPetugas->get($posKey) as $petugas)
                            <span class="font-bold text-primary text-sm block">
                                {{ $petugas->nama_pelayan }}
                                @if($petugas->no_wa)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $petugas->no_wa) }}" target="_blank" class="text-[11px] text-emerald-700 font-normal hover:underline ml-1">
                                    ({{ $petugas->no_wa }})
                                </a>
                                @endif
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-700 border border-green-200">
                        Terjadwal
                    </span>
                </div>
                @endif
                @endforeach
            </div>

            @if(!empty($rota->catatan))
            <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs text-on-surface">
                <strong>Catatan Khusus Pelayanan:</strong>
                <p class="mt-0.5 text-outline">{{ $rota->catatan }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function copyWaText() {
    var text = document.getElementById('waMessageText').innerText;
    navigator.clipboard.writeText(text).then(function() {
        var btn = document.getElementById('btnCopyLabel');
        btn.innerText = 'Tersalin ke Clipboard! ✓';
        setTimeout(function() {
            btn.innerText = 'Salin Teks Pesan ke Clipboard';
        }, 2500);
    });
}
</script>
@endsection

