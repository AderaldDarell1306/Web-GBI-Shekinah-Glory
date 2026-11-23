@extends('layouts.public')

@section('title', 'Cek & Unduh Dokumen Surat Resmi - GBI Shekinah Glory')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header Page -->
    <div class="text-center max-w-2xl mx-auto mb-10" data-aos="fade-up">
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center mx-auto mb-3 border border-amber-200 shadow-2xs">
            <span class="material-symbols-outlined text-[26px]">mark_email_read</span>
        </div>
        <span class="text-xs font-bold uppercase tracking-widest text-secondary mb-1 block">Layanan Mandiri Jemaat</span>
        <h1 class="font-serif-display text-3xl sm:text-4xl font-bold text-primary mb-3">Cek & Unduh Surat Resmi</h1>
        <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
            Masukkan <strong>Kode Akses Dokumen</strong> yang telah Anda terima dari Sekretariat GBI Shekinah Glory untuk memvalidasi dan mengunduh berkas surat digital resmi Anda.
        </p>
        <div class="w-16 h-1 bg-secondary mx-auto mt-4 rounded-full"></div>
    </div>

    <!-- Form Input Kode Akses -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 mb-8" data-aos="fade-up" data-aos-delay="100">
        <form action="{{ route('public.layanan.surat.cek') }}" method="POST" class="max-w-xl mx-auto space-y-4">
            @csrf
            <div>
                <label for="kode" class="block text-xs font-bold uppercase tracking-wider text-primary mb-2">
                    Kode Akses Surat / Dokumen <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" 
                           id="kode" 
                           name="kode" 
                           value="{{ old('kode', $kode) }}" 
                           required 
                           placeholder="Contoh: SK-2026-01 atau EXP-8891" 
                           class="w-full pl-11 pr-28 py-3.5 rounded-2xl border border-outline-variant text-sm font-mono uppercase tracking-wider focus:border-secondary focus:ring-1 focus:ring-secondary transition bg-surface-container-lowest">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                        <span class="material-symbols-outlined text-[20px]">key</span>
                    </div>
                    <button type="submit" class="absolute inset-y-1.5 right-1.5 px-5 rounded-xl bg-secondary text-white font-bold text-xs hover:bg-secondary/90 transition shadow-xs flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">search</span>
                        <span>Periksa</span>
                    </button>
                </div>
                <p class="text-[11px] text-outline mt-2">
                    *Kode akses bersifat unik dan rahasia, diberikan langsung oleh Sekretariat Gereja kepada jemaat bersangkutan.
                </p>
            </div>
        </form>
    </div>

    <!-- Hasil Pencarian Surat -->
    @if($error)
        <div class="bg-red-50 border border-red-200 rounded-2xl p-5 text-center text-xs text-red-700 max-w-xl mx-auto mb-8 flex items-center justify-center gap-2" data-aos="fade-up">
            <span class="material-symbols-outlined text-[20px] text-red-600">error</span>
            <span>{{ $error }}</span>
        </div>
    @endif

    @if($surat)
        <div class="bg-white rounded-3xl shadow-md border border-outline-variant/40 p-6 sm:p-8 space-y-6" data-aos="fade-up">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-outline-variant/20 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-primary text-secondary-fixed flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[26px]">description</span>
                    </div>
                    <div>
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase tracking-wider">
                            Dokumen Divalidasi
                        </span>
                        <h3 class="font-serif-display font-bold text-lg sm:text-xl text-primary mt-1">{{ $surat->perihal }}</h3>
                    </div>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-2xs text-outline uppercase block">Kode Akses:</span>
                    <span class="font-mono font-bold text-secondary text-sm">{{ $surat->kode_akses }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 space-y-1">
                    <span class="text-outline text-3xs uppercase font-bold">Nomor Dokumen / Surat</span>
                    <p class="font-bold text-primary text-sm font-mono">{{ $surat->nomor_surat }}</p>
                </div>
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 space-y-1">
                    <span class="text-outline text-3xs uppercase font-bold">Tanggal Surat</span>
                    <p class="font-semibold text-primary">{{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</p>
                </div>
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 space-y-1">
                    <span class="text-outline text-3xs uppercase font-bold">Kategori & Lampiran</span>
                    <p class="font-semibold text-primary">
                        {{ strtoupper($surat->jenis_surat) }} • {{ $surat->kategori_label ?? $surat->kategori_surat }}
                        @if($surat->lampiran)
                            ({{ $surat->lampiran }})
                        @endif
                    </p>
                </div>
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 space-y-1">
                    <span class="text-outline text-3xs uppercase font-bold">Ditujukan Kepada</span>
                    <p class="font-semibold text-primary">{{ $surat->tujuan_penerima }}</p>
                </div>
            </div>

            @if($surat->ringkasan_isi)
                <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-200/60 text-xs">
                    <span class="font-bold text-amber-900 block mb-1">Keterangan / Ringkasan Isi:</span>
                    <p class="text-amber-950 leading-relaxed">{{ $surat->ringkasan_isi }}</p>
                </div>
            @endif

            <!-- Tombol Aksi & Unduh -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-outline-variant/20">
                <div class="w-full sm:w-auto flex flex-wrap gap-2">
                    @if($surat->file_surat)
                        <a href="{{ asset('storage/' . $surat->file_surat) }}" target="_blank" download class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-secondary text-white font-bold text-xs hover:bg-secondary/90 transition shadow-sm w-full sm:w-auto">
                            <span class="material-symbols-outlined text-[18px]">download</span>
                            <span>Unduh File Surat (PDF/Berkas)</span>
                        </a>
                        <a href="{{ asset('storage/' . $surat->file_surat) }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-4 py-3 rounded-xl border border-outline-variant text-primary font-bold text-xs hover:bg-surface-container transition w-full sm:w-auto">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                            <span>Buka Pratinjau</span>
                        </a>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-gray-100 text-gray-600 text-xs font-medium">
                            <span class="material-symbols-outlined text-[16px]">info</span>
                            <span>Dokumen fisik tersedia di Sekretariat Gereja.</span>
                        </span>
                    @endif
                </div>

                <!-- Bagikan / Salin Tautan Akses -->
                <button type="button" onclick="salinLinkSurat('{{ url('/layanan/surat?kode=' . $surat->kode_akses) }}')" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-surface-container text-on-surface-variant font-bold text-xs hover:text-primary transition w-full sm:w-auto justify-center">
                    <span class="material-symbols-outlined text-[16px]">share</span>
                    <span id="btnSalinText">Salin Tautan Akses</span>
                </button>
            </div>
        </div>
    @endif
</div>

<script>
function salinLinkSurat(url) {
    navigator.clipboard.writeText(url).then(() => {
        const btn = document.getElementById('btnSalinText');
        const originalText = btn.innerText;
        btn.innerText = 'Tautan Berhasil Disalin!';
        setTimeout(() => { btn.innerText = originalText; }, 2500);
    });
}
</script>
@endsection
