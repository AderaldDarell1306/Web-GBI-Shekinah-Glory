@extends('layouts.admin')

@section('title', 'Jadwal Pelayanan Ibadah - SIMJ GBI Shekinah Glory')
@section('page_title', 'Penjadwalan Pelayanan Ibadah (Service Rota)')

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-serif-display text-2xl font-bold text-primary">Penjadwalan Pelayan Ibadah (Service Rota)</h2>
            <p class="text-xs text-on-surface-variant mt-0.5">Pengaturan tugas ibadah (WL, Singer, Pemusik, Multimedia, Usher) dengan Conflict Checker anti-bentrok tugas &amp; WhatsApp Broadcast ke Grup Jemaat.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.pelayanan.create') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-secondary hover:bg-secondary/90 text-white font-bold text-xs shadow-md transition">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Buat Jadwal Pelayanan Baru</span>
            </a>
        </div>
    </div>

    <!-- Alert Fitur Conflict Checker & Broadcast -->
    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-amber-900">
        <div class="flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-lg bg-amber-200 text-amber-800 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[20px]">verified_user</span>
            </span>
            <div>
                <strong class="block text-primary">Sistem Terintegrasi Conflict Checker &amp; WhatsApp Grup</strong>
                <span>Sistem secara otomatis menolak penugasan ganda pada orang yang sama di sesi ibadah yang sama, serta menyediakan generator pesan broadcast untuk Grup WhatsApp Jemaat/Pelayan.</span>
            </div>
        </div>
    </div>

    <!-- Jadwal Pelayanan Mendatang (Upcoming) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-serif-display text-lg font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">upcoming</span>
                <span>Jadwal Pelayanan Mendatang</span>
            </h3>
            <span class="text-xs text-outline">{{ $upcomingList->total() }} Sesi Terjadwal</span>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-on-surface">
                    <thead class="bg-surface-container-low text-outline uppercase font-bold tracking-wider border-b border-outline-variant/30">
                        <tr>
                            <th class="py-3.5 px-4">Tanggal &amp; Waktu</th>
                            <th class="py-3.5 px-4">Sesi Ibadah</th>
                            <th class="py-3.5 px-4">Tema &amp; Pengkhotbah</th>
                            <th class="py-3.5 px-4">Tim Pelayan Terjadwal</th>
                            <th class="py-3.5 px-4 text-center">Aksi &amp; WhatsApp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20">
                        @forelse($upcomingList as $rota)
                        <tr class="hover:bg-surface-container-low/50 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-primary block text-sm">{{ $rota->tanggal_pelayanan->translatedFormat('l, d F Y') }}</span>
                                <span class="text-secondary font-semibold font-mono text-[11px]">{{ $rota->sesiIbadah->jam }} WITA</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-primary">{{ $rota->sesiIbadah->nama_ibadah }}</span>
                                <span class="block text-[11px] text-outline">{{ $rota->sesiIbadah->lokasi_ruangan }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-primary block">{{ $rota->tema_ibadah ?: '-' }}</span>
                                <span class="text-outline text-[11px]">{{ $rota->pengkhotbah ? 'Oleh: ' . $rota->pengkhotbah : '' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full bg-secondary-container/40 text-secondary font-bold text-[11px] inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">person</span>
                                    <span>{{ $rota->petugas->count() }} Petugas Terisi</span>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.pelayanan.show', $rota->id) }}" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-sm transition flex items-center gap-1" title="Lihat Detail & Kirim ke Grup WhatsApp">
                                        <span class="material-symbols-outlined text-[15px]">chat</span>
                                        <span>Grup WA</span>
                                    </a>
                                    <a href="{{ route('admin.pelayanan.edit', $rota->id) }}" class="p-1.5 rounded-lg text-outline hover:text-primary hover:bg-surface-container transition" title="Edit Jadwal">
                                        <span class="material-symbols-outlined text-[17px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.pelayanan.destroy', $rota->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal pelayanan ibadah ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-outline hover:text-red-600 hover:bg-red-50 transition" title="Hapus">
                                            <span class="material-symbols-outlined text-[17px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-outline">Belum ada jadwal pelayanan mendatang. Klik tombol "Buat Jadwal Pelayanan Baru" di atas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($upcomingList->hasPages())
            <div class="p-4 border-t border-outline-variant/20">
                {{ $upcomingList->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Arsip Jadwal Pelayanan Lewat (Past) -->
    @if($pastList->total() > 0)
    <div class="space-y-4 pt-4 border-t border-outline-variant/20">
        <div class="flex items-center justify-between">
            <h3 class="font-serif-display text-base font-bold text-outline flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">history</span>
                <span>Arsip Jadwal Ibadah Lalu</span>
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden opacity-90">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-on-surface">
                    <thead class="bg-surface-container-low text-outline uppercase font-bold tracking-wider border-b border-outline-variant/30">
                        <tr>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Sesi Ibadah</th>
                            <th class="py-3 px-4">Tema</th>
                            <th class="py-3 px-4">Petugas</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20">
                        @foreach($pastList as $rota)
                        <tr class="hover:bg-surface-container-low/50">
                            <td class="py-3 px-4 font-semibold text-primary">{{ $rota->tanggal_pelayanan->format('d/m/Y') }}</td>
                            <td class="py-3 px-4">{{ $rota->sesiIbadah->nama_ibadah }}</td>
                            <td class="py-3 px-4 text-outline">{{ $rota->tema_ibadah ?: '-' }}</td>
                            <td class="py-3 px-4 text-outline">{{ $rota->petugas->count() }} Petugas</td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.pelayanan.show', $rota->id) }}" class="text-secondary font-bold hover:underline">Lihat Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

