@extends('layouts.admin')

@section('title', 'Rincian Pertemuan COOL - ' . $pertemuan->nama_cool)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary/10 text-primary">
                    {{ $pertemuan->wilayah?->nama_wilayah }} ({{ $pertemuan->wilayah?->wilayah_besar }})
                </span>
                <span class="text-xs text-gray-400 font-mono">{{ $pertemuan->tanggal_pertemuan->translatedFormat('l, d F Y') }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $pertemuan->nama_cool }}</h1>
            @if($pertemuan->tema_renungan)
                <p class="text-sm font-semibold text-secondary mt-0.5">Tema: "{{ $pertemuan->tema_renungan }}"</p>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.cool.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                Kembali
            </a>
            <form action="{{ route('admin.cool.destroy', $pertemuan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pertemuan ini?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-sm font-medium text-rose-600 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition">
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 border border-emerald-100 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase">Hadir</span>
                <div class="text-2xl font-bold text-emerald-600">{{ $hadirCount }}</div>
            </div>
            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">
                ✓
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-blue-100 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase">Izin</span>
                <div class="text-2xl font-bold text-blue-600">{{ $izinCount }}</div>
            </div>
            <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs">
                i
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-amber-100 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase">Sakit</span>
                <div class="text-2xl font-bold text-amber-600">{{ $sakitCount }}</div>
            </div>
            <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">
                +
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-rose-100 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase">Alpa</span>
                <div class="text-2xl font-bold text-rose-600">{{ $alpaCount }}</div>
            </div>
            <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xs">
                x
            </div>
        </div>
    </div>

    <!-- Info Detail Pertemuan -->
    <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-xs grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
        <div>
            <span class="text-gray-400 block font-medium">Pemimpin Sharing / Firman</span>
            <span class="text-gray-900 font-bold text-sm">{{ $pertemuan->pemimpin_sharing ?? '-' }}</span>
        </div>
        <div>
            <span class="text-gray-400 block font-medium">Tempat / Tuan Rumah</span>
            <span class="text-gray-900 font-bold text-sm">{{ $pertemuan->tempat ?? '-' }} ({{ $pertemuan->jam_mulai }})</span>
        </div>
        <div>
            <span class="text-gray-400 block font-medium">Dicatat Oleh</span>
            <span class="text-gray-900 font-bold text-sm">{{ $pertemuan->creator?->name ?? 'Admin' }}</span>
        </div>
        @if($pertemuan->catatan)
            <div class="sm:col-span-3 pt-2 border-t border-gray-50">
                <span class="text-gray-400 block font-medium mb-1">Catatan & Pokok Doa COOL:</span>
                <p class="text-gray-700 leading-relaxed bg-gray-50 p-3 rounded-lg">{{ $pertemuan->catatan }}</p>
            </div>
        @endif
    </div>

    <!-- Attendance Details Table -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-xs overflow-hidden">
        <div class="p-4 bg-gray-50/70 border-b border-gray-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-600">
                Rincian Presensi Jemaat ({{ $pertemuan->kehadiranDetails->count() }} Anggota)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Jemaat</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status Keluarga</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Presensi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white text-sm">
                    @forelse($pertemuan->kehadiranDetails as $idx => $d)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-4 py-3 text-xs text-gray-400 font-mono">{{ $idx + 1 }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="font-bold text-gray-900">{{ $d->jemaat?->nama_lengkap ?? 'Jemaat Dihapus' }}</div>
                                <div class="text-xs text-gray-400 font-mono">KK: {{ $d->jemaat?->no_kk ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">
                                {{ $d->jemaat?->status_keluarga ?? '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                @if($d->status_kehadiran == 'hadir')
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                        Hadir
                                    </span>
                                @elseif($d->status_kehadiran == 'izin')
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                        Izin
                                    </span>
                                @elseif($d->status_kehadiran == 'sakit')
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                        Sakit
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                        Alpa
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600">
                                {{ $d->keterangan ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                Tidak ada data presensi jemaat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

