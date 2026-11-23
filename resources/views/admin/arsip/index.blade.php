@extends('layouts.admin')

@section('title', 'Buku Arsip Surat Keluar & Masuk')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Arsip Surat Masuk & Keluar</h1>
            <p class="text-sm text-gray-500">Pencatatan korespondensi resmi, surat dinas, sinode, dan translokasi per tahun kalender.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.arsip.cetak', ['tahun' => $tahun, 'jenis' => $jenis]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-amber-900 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition shadow-sm">
                <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Buku Agenda
            </a>
            <button type="button" onclick="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Arsipkan Surat Baru
            </button>
        </div>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Surat Masuk (Tahun {{ $tahun }})</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs">
                    IN
                </div>
            </div>
            <div class="text-2xl font-bold text-blue-600">{{ $totalMasukTahunIni }} <span class="text-xs font-normal text-gray-400">Berkas</span></div>
            <p class="text-xs text-gray-400 mt-1">Dari Sinode, instansi & pihak luar</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Surat Keluar (Tahun {{ $tahun }})</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">
                    OUT
                </div>
            </div>
            <div class="text-2xl font-bold text-emerald-600">{{ $totalKeluarTahunIni }} <span class="text-xs font-normal text-gray-400">Berkas</span></div>
            <p class="text-xs text-gray-400 mt-1">Diterbitkan oleh sekretariat GBI</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Seluruh Arsip</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900">{{ $totalArsipAll }} <span class="text-xs font-normal text-gray-400">Dokumen</span></div>
            <p class="text-xs text-gray-400 mt-1">Tersimpan dalam database</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-xs">
        <form method="GET" action="{{ route('admin.arsip.index') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tahun Kalender</label>
                <select name="tahun" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    @for($y = date('Y') + 1; $y >= 2024; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Surat</label>
                <select name="jenis" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    <option value="">Semua (Masuk & Keluar)</option>
                    <option value="masuk" {{ $jenis == 'masuk' ? 'selected' : '' }}>Surat Masuk</option>
                    <option value="keluar" {{ $jenis == 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Kategori</label>
                <select name="kategori" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    <option value="">Semua Kategori</option>
                    <option value="undangan" {{ $kategori == 'undangan' ? 'selected' : '' }}>Surat Undangan</option>
                    <option value="sinode" {{ $kategori == 'sinode' ? 'selected' : '' }}>BPD / Sinode GBI</option>
                    <option value="dinas_pemerintah" {{ $kategori == 'dinas_pemerintah' ? 'selected' : '' }}>Dinas / Pemerintah</option>
                    <option value="pastoral" {{ $kategori == 'pastoral' ? 'selected' : '' }}>Pastoral & Pelayanan</option>
                    <option value="rekomendasi" {{ $kategori == 'rekomendasi' ? 'selected' : '' }}>Rekomendasi</option>
                    <option value="proposal" {{ $kategori == 'proposal' ? 'selected' : '' }}>Proposal Kerjasama</option>
                    <option value="lainnya" {{ $kategori == 'lainnya' ? 'selected' : '' }}>Lain-lain</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Cari Perihal / No Surat</label>
                <input type="text" name="q" value="{{ $q }}" placeholder="Nomor, pengirim, dll..." class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full py-2 px-3 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-dark transition">
                    Filter
                </button>
                <a href="{{ route('admin.arsip.index') }}" class="py-2 px-3 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table of Archives -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No & Tanggal</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis & Kategori</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pengirim / Tujuan</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Hal &amp; Lampiran</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Akses Jemaat (Kode)</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Berkas Scan</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($suratList as $item)
                        <tr class="hover:bg-gray-50/80 transition text-sm">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-bold text-gray-900 font-mono">{{ $item->nomor_surat }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $item->tanggal_surat->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $item->jenis_surat == 'masuk' ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }}">
                                    Surat {{ ucfirst($item->jenis_surat) }}
                                </span>
                                <div class="text-xs text-gray-500 mt-1">{{ $item->kategori_label }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="text-xs text-gray-400">Dari: <strong class="text-gray-900">{{ $item->pengirim }}</strong></div>
                                <div class="text-xs text-gray-400 mt-0.5">Kepada: <strong class="text-gray-900">{{ $item->tujuan_penerima }}</strong></div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-semibold text-gray-900">{{ $item->perihal }}</div>
                                @if($item->lampiran)
                                    <div class="text-xs text-amber-700 font-medium mt-0.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        <span>Lampiran: {{ $item->lampiran }}</span>
                                    </div>
                                @endif
                                @if($item->ringkasan_isi)
                                    <div class="text-xs text-gray-500 line-clamp-1 mt-0.5">{{ $item->ringkasan_isi }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                @if($item->kode_akses)
                                    <div class="inline-flex flex-col items-center gap-1">
                                        <span class="px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 font-mono font-bold text-xs">
                                            {{ $item->kode_akses }}
                                        </span>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <button type="button" onclick="salinKode('{{ $item->kode_akses }}')" class="text-[11px] text-gray-500 hover:text-primary transition underline font-medium" title="Salin Kode">
                                                Salin
                                            </button>
                                            <span class="text-gray-300">•</span>
                                            <a href="https://api.whatsapp.com/send?text={{ urlencode('Syalom, surat resmi Anda dengan perihal \"' . $item->perihal . '\" telah terbit dari Sekretariat GBI Shekinah Glory. Anda dapat mengakses & mengunduh berkas surat melalui website kami di menu Layanan -> Cek & Unduh Surat dengan Kode Akses: ' . $item->kode_akses . ' atau klik: ' . url('/layanan/surat?kode=' . $item->kode_akses)) }}" target="_blank" class="text-[11px] text-emerald-600 hover:text-emerald-700 transition font-bold" title="Kirim Kode Akses via WhatsApp">
                                                Kirim WA
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Internal</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                @if($item->file_surat)
                                    <a href="{{ asset('storage/' . $item->file_surat) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-amber-50 text-amber-800 hover:bg-amber-100 text-xs font-semibold border border-amber-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Buka Berkas
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic">Tanpa Scan</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="inline-flex items-center gap-2">
                                    <button type="button" onclick='openEditModal(@json($item))' class="text-gray-500 hover:text-primary transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('admin.arsip.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen arsip ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-rose-600 transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    <p class="text-sm font-medium text-gray-900">Belum ada arsip surat pada tahun kalender {{ $tahun }}</p>
                                    <p class="text-xs text-gray-400 mt-1">Gunakan tombol "Arsipkan Surat Baru" untuk mencatat surat masuk atau keluar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($suratList->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $suratList->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Catat Arsip Surat Baru -->
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-xl relative animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Arsipkan Surat Masuk / Keluar</h3>
            <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('admin.arsip.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Jenis Surat *</label>
                    <select name="jenis_surat" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="masuk">Surat Masuk (Diterima)</option>
                        <option value="keluar">Surat Keluar (Diterbitkan)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori Dokumen *</label>
                    <select name="kategori_surat" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="undangan">Surat Undangan</option>
                        <option value="sinode">BPD / Sinode GBI</option>
                        <option value="dinas_pemerintah">Dinas / Instansi Pemerintah</option>
                        <option value="pastoral">Pastoral & Pelayanan</option>
                        <option value="rekomendasi">Surat Rekomendasi</option>
                        <option value="translokasi">Translokasi / Pindah</option>
                        <option value="proposal">Proposal Kerjasama</option>
                        <option value="umum">Surat Umum</option>
                        <option value="lainnya">Lain-lain</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Surat Resmi *</label>
                    <input type="text" name="nomor_surat" required placeholder="Contoh: 015/BPD-GBI/NTT/XI/2026" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Surat *</label>
                    <input type="date" name="tanggal_surat" value="{{ date('Y-m-d') }}" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pengirim *</label>
                    <input type="text" name="pengirim" required placeholder="Dari siapa / instansi" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tujuan / Penerima *</label>
                    <input type="text" name="tujuan_penerima" required placeholder="Ditujukan kepada siapa" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Perihal / Judul Surat *</label>
                <input type="text" name="perihal" required placeholder="Contoh: Undangan Sidang Majelis Daerah GBI NTT" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Lampiran Dokumen</label>
                    <input type="text" name="lampiran" placeholder="Contoh: 1 Berkas / 2 Lembar / -" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1 flex items-center justify-between">
                        <span>Kode Akses Jemaat</span>
                        <button type="button" onclick="generateKodeAkses('create_kode_akses')" class="text-[10px] text-amber-700 hover:text-amber-800 font-bold underline">
                            + Generate
                        </button>
                    </label>
                    <input type="text" name="kode_akses" id="create_kode_akses" placeholder="Contoh: SK-2026-001" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary font-mono uppercase">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Ringkasan / Uraian Isi Surat</label>
                <textarea name="ringkasan_isi" rows="2" placeholder="Intisari surat, tindak lanjut yang diperlukan, atau tempat penyimpanan berkas fisik..." class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status Dokumen *</label>
                    <select name="status" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="tersimpan">Tersimpan di Arsip</option>
                        <option value="proses">Dalam Proses Tindak Lanjut</option>
                        <option value="selesai">Selesai Ditindaklanjuti</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Upload Berkas Scan (PDF / Gambar)</label>
                    <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark">Simpan Arsip</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Arsip Surat -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-xl relative animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Edit Dokumen Arsip Surat</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Jenis Surat *</label>
                    <select name="jenis_surat" id="edit_jenis" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="masuk">Surat Masuk (Diterima)</option>
                        <option value="keluar">Surat Keluar (Diterbitkan)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori Dokumen *</label>
                    <select name="kategori_surat" id="edit_kategori" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="undangan">Surat Undangan</option>
                        <option value="sinode">BPD / Sinode GBI</option>
                        <option value="dinas_pemerintah">Dinas / Instansi Pemerintah</option>
                        <option value="pastoral">Pastoral & Pelayanan</option>
                        <option value="rekomendasi">Surat Rekomendasi</option>
                        <option value="translokasi">Translokasi / Pindah</option>
                        <option value="proposal">Proposal Kerjasama</option>
                        <option value="umum">Surat Umum</option>
                        <option value="lainnya">Lain-lain</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Surat Resmi *</label>
                    <input type="text" name="nomor_surat" id="edit_nomor" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Surat *</label>
                    <input type="date" name="tanggal_surat" id="edit_tanggal" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pengirim *</label>
                    <input type="text" name="pengirim" id="edit_pengirim" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tujuan / Penerima *</label>
                    <input type="text" name="tujuan_penerima" id="edit_tujuan" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Perihal / Judul Surat *</label>
                <input type="text" name="perihal" id="edit_perihal" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Lampiran Dokumen</label>
                    <input type="text" name="lampiran" id="edit_lampiran" placeholder="Contoh: 1 Berkas / -" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1 flex items-center justify-between">
                        <span>Kode Akses Jemaat</span>
                        <button type="button" onclick="generateKodeAkses('edit_kode_akses')" class="text-[10px] text-amber-700 hover:text-amber-800 font-bold underline">
                            + Generate
                        </button>
                    </label>
                    <input type="text" name="kode_akses" id="edit_kode_akses" placeholder="Contoh: SK-2026-001" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary font-mono uppercase">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Ringkasan / Uraian Isi Surat</label>
                <textarea name="ringkasan_isi" id="edit_ringkasan" rows="2" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status Dokumen *</label>
                    <select name="status" id="edit_status" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="tersimpan">Tersimpan di Arsip</option>
                        <option value="proses">Dalam Proses Tindak Lanjut</option>
                        <option value="selesai">Selesai Ditindaklanjuti</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Ganti Berkas Scan (Opsional)</label>
                    <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}
function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}
function openEditModal(item) {
    const form = document.getElementById('editForm');
    form.action = `/admin/arsip/${item.id}`;

    document.getElementById('edit_jenis').value = item.jenis_surat;
    document.getElementById('edit_kategori').value = item.kategori_surat;
    document.getElementById('edit_nomor').value = item.nomor_surat;
    document.getElementById('edit_tanggal').value = item.tanggal_surat ? item.tanggal_surat.split('T')[0] : '';
    document.getElementById('edit_pengirim').value = item.pengirim;
    document.getElementById('edit_tujuan').value = item.tujuan_penerima;
    document.getElementById('edit_perihal').value = item.perihal;
    document.getElementById('edit_lampiran').value = item.lampiran || '';
    document.getElementById('edit_kode_akses').value = item.kode_akses || '';
    document.getElementById('edit_ringkasan').value = item.ringkasan_isi || '';
    document.getElementById('edit_status').value = item.status;

    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function generateKodeAkses(targetId) {
    const yr = new Date().getFullYear();
    const rnd = Math.floor(1000 + Math.random() * 9000);
    document.getElementById(targetId).value = 'SK-' + yr + '-' + rnd;
}

function salinKode(kode) {
    navigator.clipboard.writeText(kode).then(() => {
        alert('Kode Akses ' + kode + ' berhasil disalin ke clipboard!');
    });
}
</script>
@endsection

