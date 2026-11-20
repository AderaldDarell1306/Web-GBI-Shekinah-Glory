@extends('layouts.admin')

@section('title', 'Input Kehadiran Pertemuan COOL')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Input Kehadiran Pertemuan COOL</h1>
            <p class="text-sm text-gray-500">Catat absensi persekutuan sel jemaat wilayah {{ $targetWilayah?->nama_wilayah ?? '' }}.</p>
        </div>
        <a href="{{ route('admin.cool.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition">
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.cool.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Info Pertemuan Card -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-xs space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-primary border-b border-gray-100 pb-2">
                1. Informasi Persekutuan COOL
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Wilayah COOL -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Wilayah COOL *</label>
                    @if(Auth::user()->isKetuaCool() && Auth::user()->wilayah_id)
                        <input type="hidden" name="wilayah_id" value="{{ Auth::user()->wilayah_id }}">
                        <input type="text" readonly value="{{ Auth::user()->wilayah?->nama_wilayah }} ({{ Auth::user()->wilayah?->wilayah_besar }})" class="w-full text-sm rounded-lg bg-gray-50 border-gray-200 text-gray-600 font-bold">
                    @else
                        <select name="wilayah_id" onchange="window.location.href = '{{ route('admin.cool.create') }}?wilayah_id=' + this.value" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary font-bold">
                            @foreach($wilayahList as $w)
                                <option value="{{ $w->id }}" {{ $targetWilayahId == $w->id ? 'selected' : '' }}>
                                    {{ $w->nama_wilayah }} ({{ $w->wilayah_besar }})
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <!-- Tanggal Pertemuan -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Pertemuan *</label>
                    <input type="date" name="tanggal_pertemuan" value="{{ date('Y-m-d') }}" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>

                <!-- Jam Mulai -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Jam Pelaksanaan</label>
                    <input type="text" name="jam_mulai" value="18:30 WITA" placeholder="Contoh: 18:30 WITA" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Tempat / Tuan Rumah -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tempat / Rumah Jemaat</label>
                    <input type="text" name="tempat" placeholder="Contoh: Rumah Kel. Bpk. John Manafe" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>

                <!-- Pemimpin Sharing / Pengerja -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pemimpin Renungan / Sharing</label>
                    <input type="text" name="pemimpin_sharing" placeholder="Nama pengerja / fasilitator" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>

                <!-- Tema Renungan -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tema Renungan Firman</label>
                    <input type="text" name="tema_renungan" placeholder="Contoh: Kasih Persaudaraan" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Pertemuan / Pokok Doa COOL</label>
                <textarea name="catatan" rows="2" placeholder="Catatan pergumulan, jemaat baru hadir, atau pokok doa kelompok..." class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary"></textarea>
            </div>
        </div>

        <!-- Tabel Checklist Kehadiran Jemaat -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/70">
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-primary">
                        2. Presensi Anggota COOL Wilayah ({{ $anggotaList->count() }} Jemaat)
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Checklist kehadiran masing-masing anggota keluarga jemaat.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="setAllAttendance('hadir')" class="px-2.5 py-1 text-xs font-semibold text-emerald-800 bg-emerald-100 rounded hover:bg-emerald-200">
                        Set Semua Hadir
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Jemaat</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status Keluarga / Usia</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status Kehadiran</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Keterangan / Alasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white text-sm">
                        @forelse($anggotaList as $idx => $j)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-4 py-3 text-xs text-gray-400 font-mono">{{ $idx + 1 }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="font-bold text-gray-900">{{ $j->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-400 font-mono">KK: {{ $j->no_kk ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700">
                                        {{ $j->status_keluarga ?? 'Anggota' }}
                                    </span>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $j->umur ? $j->umur . ' tahun' : '' }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-3 text-xs font-semibold">
                                        <label class="inline-flex items-center gap-1 text-emerald-700 cursor-pointer">
                                            <input type="radio" name="kehadiran[{{ $j->id }}]" value="hadir" checked class="attendance-radio hadir-radio text-emerald-600 focus:ring-emerald-500">
                                            <span>Hadir</span>
                                        </label>
                                        <label class="inline-flex items-center gap-1 text-blue-700 cursor-pointer">
                                            <input type="radio" name="kehadiran[{{ $j->id }}]" value="izin" class="attendance-radio izin-radio text-blue-600 focus:ring-blue-500">
                                            <span>Izin</span>
                                        </label>
                                        <label class="inline-flex items-center gap-1 text-amber-700 cursor-pointer">
                                            <input type="radio" name="kehadiran[{{ $j->id }}]" value="sakit" class="attendance-radio sakit-radio text-amber-600 focus:ring-amber-500">
                                            <span>Sakit</span>
                                        </label>
                                        <label class="inline-flex items-center gap-1 text-rose-700 cursor-pointer">
                                            <input type="radio" name="kehadiran[{{ $j->id }}]" value="alpa" class="attendance-radio alpa-radio text-rose-600 focus:ring-rose-500">
                                            <span>Alpa</span>
                                        </label>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="keterangan_jemaat[{{ $j->id }}]" placeholder="Catatan opsional..." class="w-full text-xs rounded-lg border-gray-200 py-1.5 focus:border-primary focus:ring-primary">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                    Belum ada data jemaat yang terdaftar pada wilayah ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs text-gray-500">Pastikan absensi diperiksa dengan teliti sebelum disimpan.</span>
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark transition shadow-sm">
                    Simpan Pertemuan & Absensi COOL
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function setAllAttendance(status) {
    document.querySelectorAll('.' + status + '-radio').forEach(radio => {
        radio.checked = true;
    });
}
</script>
@endsection

