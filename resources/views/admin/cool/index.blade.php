@extends('layouts.admin')

@section('title', 'Kehadiran & Pembinaan COOL')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Komunitas Sel (COOL) & Kehadiran</h1>
            <p class="text-sm text-gray-500">Pencatatan persekutuan keluarga jemaat, tema sharing, dan absensi kehadiran per wilayah.</p>
        </div>
        <div>
            <a href="{{ route('admin.cool.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Input Kehadiran COOL Baru
            </a>
        </div>
    </div>

    <!-- Status Banner for Ketua COOL -->
    @if(Auth::user()->isKetuaCool())
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-500 text-white flex items-center justify-center font-bold text-xs shrink-0">
                COOL
            </div>
            <div>
                <h4 class="text-xs font-bold text-amber-900 uppercase tracking-wider">Akses Wilayah Anda</h4>
                <p class="text-xs text-amber-800">
                    Anda login sebagai Ketua COOL wilayah: <strong>{{ Auth::user()->wilayah?->nama_wilayah ?? 'Semua Wilayah' }}</strong>. Data jemaat dan absensi otomatis disesuaikan untuk wilayah binaan Anda.
                </p>
            </div>
        </div>
    @endif

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-xs">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Pertemuan COOL</div>
            <div class="text-2xl font-bold text-gray-900">{{ $totalPertemuan }} <span class="text-xs font-normal text-gray-400">Pertemuan</span></div>
            <p class="text-xs text-gray-400 mt-1">Pada periode yang dipilih</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-xs">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Rata-Rata Kehadiran</div>
            <div class="text-2xl font-bold text-emerald-600">{{ number_format($rataRataHadir, 1) }} <span class="text-xs font-normal text-gray-400">Jiwa / Acara</span></div>
            <p class="text-xs text-gray-400 mt-1">Keaktifan anggota persekutuan</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-xs">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status Persekutuan</div>
            <div class="text-2xl font-bold text-amber-700">Aktif & Berjalan</div>
            <p class="text-xs text-gray-400 mt-1">Persekutuan Doa & Family Cell</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-xs">
        <form method="GET" action="{{ route('admin.cool.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            @if(!Auth::user()->isKetuaCool())
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Wilayah COOL</label>
                    <select name="wilayah_id" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="">Semua Wilayah</option>
                        @foreach($wilayahList as $w)
                            <option value="{{ $w->id }}" {{ $selectedWilayahId == $w->id ? 'selected' : '' }}>
                                {{ $w->nama_wilayah }} ({{ $w->wilayah_besar }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Bulan</label>
                <select name="bulan" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        @php $mStr = sprintf('%02d', $m); @endphp
                        <option value="{{ $mStr }}" {{ $bulan == $mStr ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tahun</label>
                <select name="tahun" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    @for($y = date('Y') + 1; $y >= 2024; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full py-2 px-3 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-dark transition">
                    Filter
                </button>
                <a href="{{ route('admin.cool.index') }}" class="py-2 px-3 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Meeting Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($pertemuanList as $p)
            <div class="bg-white rounded-xl border border-gray-100 shadow-xs hover:shadow-md transition flex flex-col justify-between overflow-hidden">
                <div class="p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary/10 text-primary">
                            {{ $p->wilayah?->nama_wilayah ?? 'Umum' }}
                        </span>
                        <span class="text-xs text-gray-400 font-mono">{{ $p->tanggal_pertemuan->format('d M Y') }}</span>
                    </div>

                    <div>
                        <h3 class="text-base font-bold text-gray-900 line-clamp-1">{{ $p->nama_cool }}</h3>
                        @if($p->tema_renungan)
                            <div class="text-xs font-semibold text-secondary mt-0.5 line-clamp-1">Tema: "{{ $p->tema_renungan }}"</div>
                        @endif
                    </div>

                    <div class="text-xs text-gray-500 space-y-1 pt-1">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Pemimpin: {{ $p->pemimpin_sharing ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            <span>Tempat: {{ $p->tempat ?? 'Rumah Jemaat' }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-gray-50/80 border-t border-gray-100 flex items-center justify-between text-xs">
                    <span class="font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                        {{ $p->total_hadir }} Orang Hadir
                    </span>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.cool.show', $p->id) }}" class="px-2.5 py-1 bg-white border border-gray-200 rounded-md font-semibold text-primary hover:bg-gray-50">
                            Rincian Absensi
                        </a>
                        <form action="{{ route('admin.cool.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data pertemuan COOL ini?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1 text-gray-400 hover:text-rose-600" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl p-12 text-center border border-gray-100 shadow-xs">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <p class="text-sm font-medium text-gray-900">Belum ada riwayat persekutuan COOL pada periode ini</p>
                <p class="text-xs text-gray-400 mt-1">Gunakan tombol "Input Kehadiran COOL Baru" untuk mendata persekutuan jemaat.</p>
            </div>
        @endforelse
    </div>

    @if($pertemuanList->hasPages())
        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-xs">
            {{ $pertemuanList->links() }}
        </div>
    @endif
</div>
@endsection

