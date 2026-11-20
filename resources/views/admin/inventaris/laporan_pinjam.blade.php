<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pinjam Pakai Inventaris - GBI Shekinah Glory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        @media print {
            .no-print { display: none !important; }
            body { background-color: #fff; padding: 0; }
            @page { margin: 1.5cm; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 p-4 sm:p-8">

    <!-- Action Bar (No Print) -->
    <div class="max-w-5xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('admin.inventaris.index') }}" class="px-4 py-2 rounded-lg bg-white border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition shadow-xs flex items-center gap-1.5">
            &larr; Kembali ke Daftar Inventaris
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2 rounded-lg bg-amber-700 hover:bg-amber-800 text-white text-xs font-bold transition shadow-sm flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / Cetak ke PDF</span>
            </button>
        </div>
    </div>

    <!-- Printable Document Sheet -->
    <div class="max-w-5xl mx-auto bg-white p-8 sm:p-12 border border-gray-200 rounded-2xl shadow-sm print:border-none print:shadow-none print:p-0">
        <!-- Kop Surat Resmi Gereja -->
        <div class="border-b-2 border-double border-gray-800 pb-5 mb-6 text-center">
            <div class="flex items-center justify-center gap-4 mb-2">
                <img src="{{ asset('images/logo_gbi.png') }}" alt="Logo GBI" class="w-14 h-14 object-contain">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-wide uppercase text-gray-900 font-serif-display">Gereja Bethel Indonesia (GBI)</h1>
                    <h2 class="text-base sm:text-lg font-bold tracking-wider uppercase text-amber-800">Jemaat Shekinah Glory Namosain</h2>
                </div>
            </div>
            <p class="text-xs text-gray-600">Jl. Yos Sudarso, Kel. Namosain, Kec. Alak, Kota Kupang, Nusa Tenggara Timur</p>
            <p class="text-2xs text-gray-500 font-medium tracking-wider uppercase mt-1">✦ Be House of Glory ✦</p>
        </div>

        <!-- Judul Laporan -->
        <div class="text-center my-6">
            <h3 class="text-lg font-bold uppercase tracking-wider text-gray-900 font-serif-display underline decoration-1 underline-offset-4">Laporan Pinjam Pakai Inventaris Gereja</h3>
            <p class="text-xs text-gray-600 mt-1">Periode: {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }} • Status: {{ $status ? $status : 'Semua Status Peminjaman' }}</p>
        </div>

        <!-- Tabel Data Laporan -->
        <table class="w-full text-xs border border-gray-300 divide-y divide-gray-300 mb-8">
            <thead class="bg-gray-100 font-bold uppercase tracking-wider text-gray-700">
                <tr>
                    <th class="py-2.5 px-3 border-r border-gray-300 text-center w-12">No.</th>
                    <th class="py-2.5 px-3 border-r border-gray-300 text-left">Kode Pinjam</th>
                    <th class="py-2.5 px-3 border-r border-gray-300 text-left">Nama Peminjam</th>
                    <th class="py-2.5 px-3 border-r border-gray-300 text-left">Barang & Kode Inv.</th>
                    <th class="py-2.5 px-3 border-r border-gray-300 text-center">Jumlah</th>
                    <th class="py-2.5 px-3 border-r border-gray-300 text-left">Keperluan Kegiatan</th>
                    <th class="py-2.5 px-3 border-r border-gray-300 text-center">Tgl Pinjam - Rencana Kembali</th>
                    <th class="py-2.5 px-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($peminjamanList as $idx => $p)
                <tr>
                    <td class="py-2.5 px-3 border-r border-gray-300 text-center">{{ $idx + 1 }}</td>
                    <td class="py-2.5 px-3 border-r border-gray-300 font-mono font-bold">{{ $p->kode_peminjaman }}</td>
                    <td class="py-2.5 px-3 border-r border-gray-300 font-semibold">{{ $p->nama_peminjam }}</td>
                    <td class="py-2.5 px-3 border-r border-gray-300">
                        <strong>{{ $p->inventaris->nama_barang ?? '-' }}</strong>
                        <span class="block text-2xs text-gray-500 font-mono">{{ $p->inventaris->kode_inventaris ?? '-' }} ({{ $p->inventaris->lokasi ?? '' }})</span>
                    </td>
                    <td class="py-2.5 px-3 border-r border-gray-300 text-center font-bold">{{ $p->jumlah_pinjam }} Unit</td>
                    <td class="py-2.5 px-3 border-r border-gray-300">{{ $p->instansi_kegiatan }}</td>
                    <td class="py-2.5 px-3 border-r border-gray-300 text-center">
                        {{ $p->tanggal_pinjam->format('d/m/Y') }} - {{ $p->tanggal_rencana_kembali->format('d/m/Y') }}
                        @if($p->tanggal_kembali_aktual)
                            <span class="block text-2xs text-emerald-700 font-bold">Kembali: {{ $p->tanggal_kembali_aktual->format('d/m/Y') }}</span>
                        @endif
                    </td>
                    <td class="py-2.5 px-3 text-center font-semibold">
                        {{ $p->status }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-8 text-center text-gray-500">
                        Tidak ada catatan peminjaman inventaris pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Tanda Tangan / Legalisasi -->
        <div class="grid grid-cols-2 gap-8 text-xs text-center pt-8 border-t border-gray-200">
            <div>
                <p class="text-gray-600 mb-16">Mengetahui,<br><strong>Pengurus Bidang Sarana & Inventaris</strong></p>
                <p class="font-bold text-gray-900 border-t border-gray-400 inline-block px-8 pt-1">( .................................................... )</p>
            </div>
            <div>
                <p class="text-gray-600 mb-16">Kupang, {{ date('d F Y') }}<br><strong>Gembala Sidang / Pembina</strong></p>
                <p class="font-bold text-gray-900 border-t border-gray-400 inline-block px-8 pt-1">( Pdt. Daniel Talaen )</p>
            </div>
        </div>
    </div>

</body>
</html>
