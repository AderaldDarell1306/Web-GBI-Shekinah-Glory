@extends('layouts.public')

@section('title', 'Bukti Pengajuan Peminjaman Inventaris - GBI Shekinah Glory')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">

    <!-- Action Bar / Notifikasi Sukses (Hidden on Print) -->
    <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-md border border-outline-variant/30 text-center space-y-4 no-print" data-aos="fade-up">
        <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto shadow-inner">
            <span class="material-symbols-outlined text-[28px]">check_circle</span>
        </div>

        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-secondary block mb-1">Pengajuan Berhasil Terkirim</span>
            <h1 class="font-serif-display text-2xl sm:text-3xl font-bold text-primary">Terima Kasih, {{ $peminjaman->nama_peminjam }}</h1>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                Formulir peminjaman inventaris Anda telah tercatat dengan Kode: <strong class="font-mono text-primary">{{ $peminjaman->kode_peminjaman }}</strong>.
            </p>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
            <button type="button" onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-secondary text-white font-bold text-xs hover:bg-secondary/90 transition shadow-md flex items-center gap-2">
                <span class="material-symbols-outlined text-[17px]">print</span>
                <span>Cetak / Simpan Blanko Peminjaman (A4 PDF)</span>
            </button>
            <a href="{{ route('public.beranda') }}" class="px-5 py-2.5 rounded-xl bg-white border border-outline-variant text-primary font-bold text-xs hover:bg-surface-container transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[17px]">home</span>
                <span>Kembali ke Beranda</span>
            </a>
            <a href="{{ route('public.layanan.pinjam-inventaris') }}" class="px-4 py-2.5 rounded-xl bg-surface-container text-on-surface-variant font-bold text-xs hover:text-primary transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[17px]">add</span>
                <span>Ajukan Lagi</span>
            </a>
        </div>
    </div>

    <!-- DOKUMEN CETAK RESMI BLANKO PEMINJAMAN INVENTARIS (A4) - TIMES NEW ROMAN PERSIS FORM PENDAFTARAN -->
    <div id="printableDocWrapper" class="bg-white rounded-2xl shadow-xl border border-gray-300 p-6 sm:p-10 relative overflow-hidden select-none doc-times-font doc-a4-sheet" style="font-family: 'Times New Roman', Times, serif;">
        
        <!-- WATERMARK LOGO GBI DI TENGAH KERTAS PERSIS SEPERTI ASLINYA (BERWARNA) -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.12] select-none z-0">
            <img src="{{ asset('images/logo.jpg') }}" alt="Watermark Gereja Bethel Indonesia" class="w-96 h-96 object-contain">
        </div>

        <!-- KONTEN DOKUMEN -->
        <div class="relative z-10 space-y-4 text-black">
            
            <!-- KOP SURAT RESMI GBI SHEKINAH GLORY PERSIS DI BLANKO ASLI -->
            <div class="flex items-start gap-4 pb-2 border-b-2 border-[#b91c1c]">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo GBI" class="w-14 h-14 sm:w-16 sm:h-16 object-contain shrink-0 mt-0.5">
                <div class="text-center flex-1">
                    <h2 class="text-[#b91c1c] font-black tracking-wider text-base sm:text-lg uppercase leading-tight">
                        GEREJA BETHEL INDONESIA
                    </h2>
                    <h3 class="text-[#1e3a8a] font-black tracking-wide text-sm sm:text-base uppercase leading-tight mt-0.5">
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

            <!-- JUDUL BLANKO PEMINJAMAN -->
            <div class="text-center my-3">
                <div class="inline-block border border-[#1e3a8a] px-6 py-1 bg-white shadow-2xs">
                    <h4 class="font-bold text-xs sm:text-sm text-gray-900 tracking-wider uppercase">
                        SURAT BUKTI PEMINJAMAN INVENTARIS GEREJA
                    </h4>
                </div>
                <div class="text-xs italic text-gray-700 mt-1">
                    Departemen Sarana, Prasarana &amp; Logistik GBI Shekinah Glory
                </div>
            </div>

            <p class="text-[11px] text-gray-700 italic mb-2">
                *Nomor Registrasi Pinjam : <span class="font-mono text-gray-900 font-bold">{{ $peminjaman->kode_peminjaman }}</span>
            </p>

            <!-- 1. IDENTITAS PEMINJAM -->
            <div class="space-y-2 text-xs text-gray-900 leading-relaxed">
                <div class="font-bold text-[12px] flex items-center gap-1.5 underline">
                    <span>➢</span>
                    <span>Data Pemohon &amp; Kegiatan :</span>
                </div>

                <div class="pl-4 space-y-1.5">
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">1. Nama Lengkap Pemohon</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span class="font-bold text-gray-900 uppercase">{{ $peminjaman->nama_peminjam }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">2. No. Kontak / WhatsApp</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span>{{ $peminjaman->no_kontak }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">3. Keperluan / Kegiatan</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span class="font-semibold text-gray-900">{{ $peminjaman->instansi_kegiatan }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">4. Tanggal Mulai Pinjam</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">5. Rencana Tanggal Kembali</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($peminjaman->tanggal_rencana_kembali)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. TABEL BARANG YANG DIPINJAM -->
            <div class="space-y-2 text-xs text-gray-900 leading-relaxed pt-2">
                <div class="font-bold text-[12px] flex items-center gap-1.5 underline">
                    <span>➢</span>
                    <span>Rincian Barang / Inventaris yang Dipinjam :</span>
                </div>

                <div class="pl-4">
                    <table class="w-full border border-gray-400 text-xs text-left">
                        <thead class="bg-gray-100 font-bold border-b border-gray-400">
                            <tr>
                                <th class="border-r border-gray-400 p-2 text-center w-10">No</th>
                                <th class="border-r border-gray-400 p-2">Kode &amp; Nama Barang</th>
                                <th class="border-r border-gray-400 p-2 text-center w-24">Jumlah</th>
                                <th class="border-r border-gray-400 p-2 text-center w-28">Kondisi Sebelum</th>
                                <th class="p-2">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border-r border-gray-400 p-2 text-center font-bold">1</td>
                                <td class="border-r border-gray-400 p-2">
                                    <strong class="text-gray-900">{{ $peminjaman->inventaris->nama_barang ?? $peminjaman->nama_barang_custom }}</strong>
                                    @if(!empty($peminjaman->inventaris->kode_inventaris))
                                        <span class="block text-[10px] text-gray-600 font-mono">Kode: {{ $peminjaman->inventaris->kode_inventaris }} (Lokasi: {{ $peminjaman->inventaris->lokasi }})</span>
                                    @endif
                                </td>
                                <td class="border-r border-gray-400 p-2 text-center font-bold">
                                    {{ $peminjaman->jumlah_pinjam }} {{ $peminjaman->inventaris->satuan ?? 'Unit' }}
                                </td>
                                <td class="border-r border-gray-400 p-2 text-center">
                                    {{ $peminjaman->kondisi_sebelum ?? 'Baik' }}
                                </td>
                                <td class="p-2 text-[11px]">
                                    {{ $peminjaman->catatan ?? '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. KETENTUAN PEMINJAMAN -->
            <div class="p-3 bg-gray-50 border border-gray-300 rounded-lg text-[11px] text-gray-800 leading-relaxed space-y-1 mt-4">
                <span class="font-bold underline block">Ketentuan &amp; Syarat Peminjaman Inventaris Gereja:</span>
                <p>1. Peminjam bertanggung jawab penuh atas kebersihan, kelengkapan, dan keamanan barang selama masa pemakaian.</p>
                <p>2. Barang wajib dikembalikan tepat waktu sesuai tanggal rencana kembali yang telah disepakati.</p>
                <p>3. Apabila terjadi kerusakan atau kehilangan, peminjam wajib memperbaiki atau mengganti barang yang setara.</p>
                <p>4. Blanko ini sah setelah ditandatangani oleh pemohon dan disetujui oleh pengurus inventaris / Gembala Sidang.</p>
            </div>

            <!-- 4. KOLOM TANDA TANGAN (PERSIS BLANKO ASLI GBI) -->
            <div class="grid grid-cols-3 gap-2 text-center text-xs text-gray-900 pt-6">
                <div>
                    <p>Pemohon / Peminjam,</p>
                    <div class="h-16"></div>
                    <p class="font-bold underline uppercase">{{ $peminjaman->nama_peminjam }}</p>
                    <p class="text-[10px] text-gray-600">Tanda Tangan &amp; Nama Terang</p>
                </div>
                <div>
                    <p>Pengurus Sarpras / Inventaris,</p>
                    <div class="h-16"></div>
                    <p class="font-bold underline">Sekretariat GBI</p>
                    <p class="text-[10px] text-gray-600">Petugas Sarana Prasarana</p>
                </div>
                <div>
                    <p>Kupang, {{ \Carbon\Carbon::parse($peminjaman->created_at)->translatedFormat('d F Y') }}</p>
                    <p class="font-bold tracking-wider">MENGETAHUI,</p>
                    <p class="font-bold tracking-wider">GEMBALA SIDANG</p>
                    <div class="h-12"></div>
                    <p class="font-bold underline">Pdt. JUSUF LABAGAI</p>
                    <p class="text-[10px] font-mono">NIP : 1000702</p>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.doc-times-font {
    font-family: 'Times New Roman', Times, serif !important;
}
.doc-a4-sheet {
    width: 100%;
    max-width: 210mm;
    min-height: 297mm;
    box-sizing: border-box;
    margin: 0 auto;
}

@media print {
    body * {
        visibility: hidden;
    }
    header, footer, nav, .no-print, button, a {
        display: none !important;
    }
    #printableDocWrapper, #printableDocWrapper * {
        visibility: visible;
        font-family: 'Times New Roman', Times, serif !important;
    }
    #printableDocWrapper {
        position: absolute;
        left: 0;
        top: 0;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 10mm 15mm !important;
        box-shadow: none !important;
        border: none !important;
    }
    @page {
        size: A4 portrait;
        margin: 10mm 12mm;
    }
}
</style>
@endsection
