@extends('layouts.admin')

@section('title', 'Buat Jadwal Pelayanan - SIMJ GBI Shekinah Glory')
@section('page_title', 'Buat Jadwal Pelayanan Baru (Service Rota)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="font-serif-display text-2xl font-bold text-primary">Formulir Jadwal Pelayanan Ibadah</h2>
            <p class="text-xs text-on-surface-variant">Pastikan tidak ada petugas ganda pada posisi berbeda di jam ibadah yang sama.</p>
        </div>
        <a href="{{ route('admin.pelayanan.index') }}" class="px-3.5 py-2 rounded-xl border border-outline-variant text-xs font-semibold hover:bg-surface-container transition">
            Kembali ke Daftar
        </a>
    </div>

    <form action="{{ route('admin.pelayanan.store') }}" method="POST" class="space-y-6" id="formRota">
        @csrf

        <!-- Informasi Dasar Sesi Ibadah -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-4">
            <h3 class="font-serif-display text-base font-bold text-primary border-b border-outline-variant/20 pb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">event</span>
                <span>1. Waktu &amp; Sesi Ibadah</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Sesi Ibadah <span class="text-red-500">*</span></label>
                    <select name="jadwal_ibadah_id" required class="w-full px-3 py-2.5 rounded-xl border border-outline-variant text-xs font-bold text-primary bg-surface-container-low">
                        @foreach($sesiIbadahList as $sesi)
                        <option value="{{ $sesi->id }}">{{ $sesi->nama_ibadah }} (Hari {{ $sesi->hari }}, Jam {{ $sesi->jam }} WITA)</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Tanggal Pelayanan <span class="text-red-500">*</span></label>
                    @php
                        $nextSunday = \Carbon\Carbon::today()->next(\Carbon\Carbon::SUNDAY)->format('Y-m-d');
                    @endphp
                    <input type="date" name="tanggal_pelayanan" value="{{ old('tanggal_pelayanan', $nextSunday) }}" required class="w-full px-3 py-2.5 rounded-xl border border-outline-variant text-xs font-bold">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Tema Khotbah / Firman</label>
                    <input type="text" name="tema_ibadah" value="{{ old('tema_ibadah') }}" placeholder="Contoh: Hidup yang Dipimpin Roh Kudus" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Pengkhotbah / Pembicara</label>
                    <input type="text" name="pengkhotbah" value="{{ old('pengkhotbah', 'Pdt. Markus Suryanata') }}" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-semibold">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Catatan Tambahan untuk Petugas</label>
                    <input type="text" name="catatan" value="{{ old('catatan', 'Briefing doa bersama dimulai 30 menit sebelum ibadah.') }}" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-primary mb-1">Status Publikasi</label>
                    <select name="status_publikasi" class="w-full px-3 py-2 rounded-xl border border-outline-variant text-xs font-semibold">
                        <option value="terbit">Terbit (Tampil di Web &amp; Siap Kirim WA)</option>
                        <option value="draft">Draft (Simpan Sementara)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Penugasan Tim Pelayan (Praise, Worship & Crew) -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-outline-variant/20 pb-3">
                <h3 class="font-serif-display text-base font-bold text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary">assignment_ind</span>
                    <span>2. Penugasan Petugas / Service Crew</span>
                </h3>
                <span class="text-xs text-amber-800 bg-amber-100 px-3 py-1 rounded-full font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">shield</span>
                    <span>Conflict Checker Aktif</span>
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($posisiLabels as $posKey => $posLabel)
                @php
                    $isTamborin = ($posKey === 'tamborin');
                    $isAltar = ($posKey === 'pelayan_altar');
                @endphp
                <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/20 space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-primary uppercase flex items-center gap-1.5">
                            @if($isTamborin)
                                <span class="material-symbols-outlined text-[16px] text-outline">music_note</span>
                            @elseif($isAltar)
                                <span class="material-symbols-outlined text-[16px] text-outline">church</span>
                            @endif
                            <span>{{ $posLabel }}</span>
                        </label>
                        <span class="text-[10px] text-outline font-mono">{{ $posKey }}</span>
                    </div>

                    <div class="space-y-1.5">
                        @if($isTamborin)
                            {{-- KHUSUS POSISI PEMAIN TAMBORIN: PILIHAN 16 NAMA TIM TAMBORIN RESMI --}}
                            <select onchange="handleSelectCustom(this, '{{ $posKey }}')" class="w-full px-2.5 py-1.5 rounded-lg border border-outline-variant text-xs bg-white text-primary">
                                <option value="">-- Pilih dari 16 Pemain Tamborin Resmi --</option>
                                @foreach($tamborinList as $tmb)
                                <option value="{{ $tmb }}" data-nama="{{ $tmb }}">
                                    {{ $tmb }}
                                </option>
                                @endforeach
                            </select>
                            <div class="flex gap-2">
                                <input type="hidden" name="petugas[{{ $posKey }}][jemaat_id]" id="jemaat_id_{{ $posKey }}" value="">
                                <input type="text" name="petugas[{{ $posKey }}][nama_pelayan]" id="nama_{{ $posKey }}" readonly placeholder="Pilih dari daftar pemain tamborin..." class="pelayan-input w-2/3 px-2.5 py-1.5 rounded-lg border border-outline-variant text-xs font-semibold text-primary bg-white">
                                <input type="text" name="petugas[{{ $posKey }}][no_wa]" id="wa_{{ $posKey }}" placeholder="No. WA" class="w-1/3 px-2.5 py-1.5 rounded-lg border border-outline-variant text-xs text-outline">
                            </div>
                        @elseif($isAltar)
                            {{-- KHUSUS POSISI PELAYAN ALTAR: PILIHAN 16 NAMA TIM PELAYAN ALTAR RESMI --}}
                            <select onchange="handleSelectCustom(this, '{{ $posKey }}')" class="w-full px-2.5 py-1.5 rounded-lg border border-outline-variant text-xs bg-white text-primary">
                                <option value="">-- Pilih dari 16 Pelayan Altar Resmi --</option>
                                @foreach($pelayanAltarList as $pa)
                                <option value="{{ $pa }}" data-nama="{{ $pa }}">
                                    {{ $pa }}
                                </option>
                                @endforeach
                            </select>
                            <div class="flex gap-2">
                                <input type="hidden" name="petugas[{{ $posKey }}][jemaat_id]" id="jemaat_id_{{ $posKey }}" value="">
                                <input type="text" name="petugas[{{ $posKey }}][nama_pelayan]" id="nama_{{ $posKey }}" readonly placeholder="Pilih dari daftar pelayan altar..." class="pelayan-input w-2/3 px-2.5 py-1.5 rounded-lg border border-outline-variant text-xs font-semibold text-primary bg-white">
                                <input type="text" name="petugas[{{ $posKey }}][no_wa]" id="wa_{{ $posKey }}" placeholder="No. WA" class="w-1/3 px-2.5 py-1.5 rounded-lg border border-outline-variant text-xs text-outline">
                            </div>
                        @else
                            {{-- SEMUA POSISI PELAYANAN LAINNYA: HANYA NAMA MAJELIS RESMI YANG DAPAT DIPILIH --}}
                            <select onchange="handleSelectMajelis(this, '{{ $posKey }}')" class="w-full px-2.5 py-1.5 rounded-lg border border-outline-variant text-xs bg-white text-primary">
                                <option value="">-- Pilih Nama Majelis Resmi --</option>
                                @php
                                    $groupedMajelis = $majelisList->groupBy(function($item) {
                                        return $item->bidang_pelayanan ?: 'Lainnya';
                                    });
                                @endphp
                                @foreach($groupedMajelis as $wilayah => $mList)
                                <optgroup label="📍 {{ $wilayah }}">
                                    @foreach($mList as $mj)
                                    <option value="{{ $mj->id }}" data-nama="{{ $mj->nama_majelis }}" data-wa="{{ $mj->no_hp }}" data-jemaat-id="{{ $mj->jemaat_id }}">
                                        {{ $mj->nama_majelis }} ({{ $mj->jabatan }}{{ $mj->catatan ? ' • ' . $mj->catatan : '' }})
                                    </option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>

                            <div class="flex gap-2">
                                <input type="hidden" name="petugas[{{ $posKey }}][jemaat_id]" id="jemaat_id_{{ $posKey }}" value="">
                                <input type="text" name="petugas[{{ $posKey }}][nama_pelayan]" id="nama_{{ $posKey }}" readonly placeholder="Pilih dari daftar majelis..." class="pelayan-input w-2/3 px-2.5 py-1.5 rounded-lg border border-outline-variant text-xs font-semibold text-primary bg-white">
                                <input type="text" name="petugas[{{ $posKey }}][no_wa]" id="wa_{{ $posKey }}" placeholder="No. WA" class="w-1/3 px-2.5 py-1.5 rounded-lg border border-outline-variant text-xs text-outline">
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3">
            <a href="{{ route('admin.pelayanan.index') }}" class="px-5 py-2.5 rounded-xl border border-outline-variant text-xs font-bold hover:bg-surface-container transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-secondary text-white font-bold text-xs shadow-md hover:bg-secondary/90 transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[17px]">save</span>
                <span>Simpan Jadwal &amp; Siapkan Pesan WA</span>
            </button>
        </div>
    </form>
</div>

<script>
function handleSelectMajelis(selectElem, posKey) {
    var opt = selectElem.options[selectElem.selectedIndex];
    var namaInput = document.getElementById('nama_' + posKey);
    var waInput = document.getElementById('wa_' + posKey);
    var idInput = document.getElementById('jemaat_id_' + posKey);

    if (opt.value) {
        namaInput.value = opt.getAttribute('data-nama') || '';
        waInput.value = opt.getAttribute('data-wa') || '';
        idInput.value = opt.getAttribute('data-jemaat-id') || '';
    } else {
        namaInput.value = '';
        waInput.value = '';
        idInput.value = '';
    }
}

function handleSelectCustom(selectElem, posKey) {
    var opt = selectElem.options[selectElem.selectedIndex];
    var namaInput = document.getElementById('nama_' + posKey);
    var waInput = document.getElementById('wa_' + posKey);
    var idInput = document.getElementById('jemaat_id_' + posKey);

    if (opt.value) {
        namaInput.value = opt.getAttribute('data-nama') || '';
        waInput.value = opt.getAttribute('data-wa') || '';
        idInput.value = '';
    } else {
        namaInput.value = '';
        waInput.value = '';
        idInput.value = '';
    }
}

function handleSelectJemaat(selectElem, posKey) {
    var opt = selectElem.options[selectElem.selectedIndex];
    var namaInput = document.getElementById('nama_' + posKey);
    var waInput = document.getElementById('wa_' + posKey);
    var idInput = document.getElementById('jemaat_id_' + posKey);

    if (opt.value) {
        namaInput.value = opt.getAttribute('data-nama') || '';
        waInput.value = opt.getAttribute('data-wa') || '';
        var jId = opt.getAttribute('data-jemaat-id');
        idInput.value = jId !== null ? jId : opt.value;
    } else {
        idInput.value = '';
        namaInput.value = '';
        waInput.value = '';
    }
}
</script>
@endsection

