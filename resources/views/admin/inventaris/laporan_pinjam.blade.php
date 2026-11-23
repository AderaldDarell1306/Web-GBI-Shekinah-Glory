<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pinjam Pakai Inventaris - GBI Shekinah Glory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Times New Roman', Times, serif; }
        .font-sans { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background-color: #fff; padding: 0; }
            @page { 
                size: A4 landscape; 
                margin: 10mm 15mm; 
            }
            .page-sheet {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 text-black p-4 sm:p-8">

    <!-- Action Bar (No Print) -->
    <div class="max-w-6xl mx-auto mb-6 flex items-center justify-between no-print font-sans">
        <a href="{{ route('admin.inventaris.index') }}" class="px-4 py-2 rounded-xl bg-white border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition shadow-xs flex items-center gap-1.5">
            &larr; Kembali ke Daftar Inventaris
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-[#001428] hover:bg-[#0f2942] text-white text-xs font-bold transition shadow-sm flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / Simpan PDF (A4 Landscape)</span>
            </button>
        </div>
    </div>

    <!-- Printable Document Sheet (A4 Landscape) -->
    <div class="max-w-6xl mx-auto bg-white p-8 sm:p-12 border border-gray-300 rounded-2xl shadow-md relative overflow-hidden page-sheet" style="font-family: 'Times New Roman', Times, serif;">
        
        <!-- Watermark Logo GBI -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.08] select-none z-0">
            <img src="{{ asset('images/logo.jpg') }}" alt="Watermark GBI" class="w-96 h-96 object-contain">
        </div>

        <div class="relative z-10 space-y-4">
            <!-- Kop Surat Resmi GBI Shekinah Glory -->
            <div class="flex items-start gap-4 pb-2 border-b-2 border-[#b91c1c]">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo GBI" class="w-16 h-16 object-contain shrink-0 mt-0.5">
                <div class="text-center flex-1">
                    <h2 class="text-[#b91c1c] font-black tracking-wider text-base sm:text-lg uppercase leading-tight font-serif">
                        GEREJA BETHEL INDONESIA
                    </h2>
                    <h3 class="text-[#1e3a8a] font-black tracking-wide text-sm sm:text-base uppercase leading-tight font-serif mt-0.5">
                        JEMAAT SHEKINAH GLORY
                    </h3>
                    <p class="text-[9px] sm:text-[10px] text-gray-800 leading-tight mt-1">
                        Badan Hukum Gereja : SK Dirjen Bimas Kristen/Protestan Dep. Agama R.I No. 41 Tahun 1972
                    </p>
                    <p class="text-[9px] sm:text-[10px] text-gray-800 leading-tight">
                        SK Dirjen Bimas (Kristen) Protestan Dep. Agama R.I. Nomor 211 Tanggal, 25 November 1989
                    </p>
                    <p class="text-[9px] sm:text-[10px] text-gray-800 leading-tight font-bold mt-0.5">
                        Jalan Ikan Arwana Namosain - Kupang Telpon : 085 333 650 621, 081 238 858 629, 082 247 292 194
                    </p>
                </div>
            </div>
            <!-- Garis Ganda Bawah Kop Surat -->
            <div class="h-0.5 bg-[#1e3a8a] -mt-3 mb-4"></div>

            <!-- Judul Laporan -->
            <div class="text-center my-4">
                <h3 class="text-base sm:text-lg font-bold uppercase tracking-wider text-black underline decoration-1 underline-offset-4">
                    REKAPITULASI LAPORAN PINJAM PAKAI INVENTARIS GEREJA
                </h3>
                <p class="text-xs text-gray-700 mt-1">
                    Periode: {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }} • Status: {{ $status ? $status : 'Semua Status Peminjaman' }}
                </p>
            </div>

            <!-- Tabel Data Laporan -->
            <table class="w-full text-xs border border-gray-400 divide-y divide-gray-400 mb-6">
                <thead class="bg-gray-100 font-bold uppercase tracking-wider text-gray-900 border-b border-gray-400">
                    <tr>
                        <th class="py-2 px-2.5 border-r border-gray-400 text-center w-10">No</th>
                        <th class="py-2 px-2.5 border-r border-gray-400 text-left w-28">Kode Pinjam</th>
                        <th class="py-2 px-2.5 border-r border-gray-400 text-left">Nama Peminjam / Kontak</th>
                        <th class="py-2 px-2.5 border-r border-gray-400 text-left">Barang &amp; Kode Inv.</th>
                        <th class="py-2 px-2.5 border-r border-gray-400 text-center w-20">Jumlah</th>
                        <th class="py-2 px-2.5 border-r border-gray-400 text-left">Keperluan Acara</th>
                        <th class="py-2 px-2.5 border-r border-gray-400 text-center w-40">Tgl Pinjam - Kembali</th>
                        <th class="py-2 px-2.5 text-center w-28">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @forelse($peminjamanList as $idx => $p)
                    <tr>
                        <td class="py-2 px-2.5 border-r border-gray-400 text-center">{{ $idx + 1 }}</td>
                        <td class="py-2 px-2.5 border-r border-gray-400 font-mono font-bold">{{ $p->kode_peminjaman }}</td>
                        <td class="py-2 px-2.5 border-r border-gray-400">
                            <strong class="text-black">{{ $p->nama_peminjam }}</strong>
                            <span class="block text-[10px] text-gray-600">{{ $p->no_kontak }}</span>
                        </td>
                        <td class="py-2 px-2.5 border-r border-gray-400">
                            <strong>{{ $p->inventaris->nama_barang ?? $p->nama_barang_custom }}</strong>
                            @if(!empty($p->inventaris->kode_inventaris))
                                <span class="block text-[10px] text-gray-600 font-mono">Kode: {{ $p->inventaris->kode_inventaris }} ({{ $p->inventaris->lokasi ?? '' }})</span>
                            @endif
                        </td>
                        <td class="py-2 px-2.5 border-r border-gray-400 text-center font-bold">{{ $p->jumlah_pinjam }} Unit</td>
                        <td class="py-2 px-2.5 border-r border-gray-400">{{ $p->instansi_kegiatan }}</td>
                        <td class="py-2 px-2.5 border-r border-gray-400 text-center">
                            {{ $p->tanggal_pinjam->format('d/m/Y') }} s/d {{ $p->tanggal_rencana_kembali->format('d/m/Y') }}
                            @if($p->tanggal_kembali_aktual)
                                <span class="block text-[10px] text-emerald-800 font-bold">Kembali: {{ $p->tanggal_kembali_aktual->format('d/m/Y') }}</span>
                            @endif
                        </td>
                        <td class="py-2 px-2.5 text-center font-semibold">
                            {{ $p->status }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-500 italic">
                            Tidak ada catatan peminjaman inventaris pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Tanda Tangan / Legalisasi -->
            <div class="grid grid-cols-2 gap-8 text-xs text-center pt-6 border-t border-gray-300">
                <div>
                    <p class="text-gray-700">Mengetahui,<br><strong>Pengurus Bidang Sarana &amp; Inventaris</strong></p>
                    <div class="h-16"></div>
                    <p class="font-bold text-black border-t border-gray-400 inline-block px-10 pt-1">( Seksi Sarpras / Logistik )</p>
                </div>
                <div>
                    <p class="text-gray-700">Kupang, {{ date('d F Y') }}<br><strong>Gembala Sidang GBI Shekinah Glory</strong></p>
                    <div class="h-16"></div>
                    <p class="font-bold text-black border-t border-gray-400 inline-block px-10 pt-1">( Pdt. JUSUF LABAGAI )</p>
                    <p class="text-[10px] text-gray-600 font-mono">NIP : 1000702</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
