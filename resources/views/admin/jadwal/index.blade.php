@extends('layouts.admin')

@section('page_title', 'Manajemen Jadwal Ibadah')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-serif-display text-2xl font-bold text-primary">Jadwal Kebaktian & Ibadah</h1>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                Kelola jadwal ibadah rutin, ibadah pemuda, komsel, dan kebaktian khusus yang tampil di web publik.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('public.jadwal') }}" target="_blank" class="px-4 py-2.5 rounded-xl border border-outline-variant bg-white text-on-surface font-semibold text-xs hover:bg-surface-container transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                <span>Lihat di Web</span>
            </a>
            <a href="{{ route('admin.jadwal.create') }}" class="px-4 py-2.5 rounded-xl bg-primary text-white font-semibold text-xs shadow hover:bg-primary-container transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Tambah Jadwal Baru</span>
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-[11px] font-bold uppercase tracking-wider text-primary border-b border-outline-variant/40">
                        <th class="py-3.5 px-4 w-12 text-center">Urutan</th>
                        <th class="py-3.5 px-4">Nama Ibadah & Kategori</th>
                        <th class="py-3.5 px-4">Hari & Jam</th>
                        <th class="py-3.5 px-4">Ruangan & Pelaksanaan</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20 text-sm">
                    @forelse($jadwalList as $jadwal)
                    <tr class="hover:bg-surface-container-low/50 transition">
                        <td class="py-4 px-4 text-center font-bold text-xs text-on-surface-variant">
                            #{{ $jadwal->urutan }}
                        </td>
                        <td class="py-4 px-4">
                            <div class="font-bold text-primary">{{ $jadwal->nama_ibadah }}</div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-secondary-container/60 text-secondary">
                                    {{ $jadwal->kategori }}
                                </span>
                                @if($jadwal->keterangan)
                                <span class="text-xs text-on-surface-variant truncate max-w-xs" title="{{ $jadwal->keterangan }}">
                                    • {{ $jadwal->keterangan }}
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="font-semibold text-on-surface">{{ $jadwal->hari }}</div>
                            <div class="text-xs text-on-surface-variant">{{ $jadwal->jam }}</div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="font-semibold text-on-surface">{{ $jadwal->lokasi_ruangan }}</div>
                            <span class="inline-block mt-0.5 text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-surface-container text-on-surface-variant">
                                {{ $jadwal->tipe_pelaksanaan }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <form action="{{ route('admin.jadwal.toggle-status', $jadwal->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition {{ $jadwal->status === 'aktif' ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}" title="Klik untuk ubah status">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $jadwal->status === 'aktif' ? 'bg-green-600' : 'bg-gray-400' }}"></span>
                                    <span>{{ ucfirst($jadwal->status) }}</span>
                                </button>
                            </form>
                        </td>
                        <td class="py-4 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" class="w-8 h-8 rounded-lg bg-surface-container hover:bg-primary hover:text-white text-primary flex items-center justify-center transition" title="Edit Jadwal">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal {{ $jadwal->nama_ibadah }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-600 hover:text-white text-red-600 flex items-center justify-center transition" title="Hapus Jadwal">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl text-on-surface-variant/40 block mb-2">event_busy</span>
                            <p class="font-semibold text-sm">Belum ada data jadwal ibadah.</p>
                            <a href="{{ route('admin.jadwal.create') }}" class="text-xs text-secondary font-bold underline mt-2 inline-block">Tambah Jadwal Sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
