@extends('layouts.public')

@section('title', 'Pendaftaran Berhasil - GBI Shekinah Glory')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12 space-y-8">
    <!-- Success Notification Card (Hidden on Print) -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-md border border-outline-variant/30 text-center space-y-4 no-print">
        <div class="w-14 h-14 rounded-full bg-green-100 text-green-700 flex items-center justify-center mx-auto shadow-inner">
            <span class="material-symbols-outlined text-[32px]">check_circle</span>
        </div>

        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-secondary block mb-1">Pendaftaran Berhasil Terkirim</span>
            <h1 class="font-serif-display text-2xl sm:text-3xl font-bold text-primary">Terima Kasih, {{ $pendaftaran->nama_pemohon }}</h1>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-1.5">Permohonan <strong>{{ $pendaftaran->jenis_layanan_label }}</strong> Anda telah masuk ke sistem pastoral kami.</p>
        </div>

        <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 inline-block text-left w-full max-w-xl text-xs space-y-2.5">
            <div class="flex justify-between border-b border-outline-variant/20 pb-1.5">
                <span class="text-outline">Nomor / Kode Registrasi:</span>
                <span class="font-mono font-bold text-primary text-sm">{{ $pendaftaran->kode_pendaftaran }}</span>
            </div>
            <div class="flex justify-between border-b border-outline-variant/20 pb-1.5">
                <span class="text-outline">Layanan:</span>
                <span class="font-semibold text-secondary">{{ $pendaftaran->jenis_layanan_label }}</span>
            </div>
            <div class="flex justify-between border-b border-outline-variant/20 pb-1.5">
                <span class="text-outline">WhatsApp Terdaftar:</span>
                <span class="font-semibold text-primary">{{ $pendaftaran->no_wa }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-outline">Status Permohonan:</span>
                <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold uppercase text-[10px]">Menunggu Verifikasi Pastoral</span>
            </div>

            <!-- Daftar Berkas Terpisah yang Berhasil Diunggah -->
            @if(!empty($pendaftaran->berkas_dokumen) && count($pendaftaran->berkas_dokumen) > 0)
            <div class="pt-3 border-t border-outline-variant/20">
                <span class="text-primary block mb-2 font-bold text-[11px] uppercase tracking-wide">
                    Berkas Persyaratan yang Diunggah ({{ count($pendaftaran->berkas_dokumen) }} File):
                </span>
                <div class="space-y-1.5">
                    @foreach($pendaftaran->berkas_dokumen as $f)
                    <div class="flex items-center justify-between text-2xs bg-white px-3 py-1.5 rounded-lg border border-outline-variant/30">
                        <div class="flex items-center gap-1.5 truncate max-w-[340px]">
                            <span class="material-symbols-outlined text-[15px] text-secondary shrink-0">attach_file</span>
                            <span class="font-bold text-secondary">{{ $f['kategori'] ?? 'Berkas' }}:</span>
                            <span class="text-gray-700 truncate font-medium">{{ $f['nama_file'] ?? 'File' }}</span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-gray-500 font-mono text-[10px]">{{ $f['ukuran'] ?? '' }}</span>
                            @if(!empty($f['path']))
                            <a href="{{ asset('storage/' . $f['path']) }}" target="_blank" class="px-2 py-0.5 rounded bg-secondary/10 hover:bg-secondary text-secondary hover:text-white font-bold text-[10px] transition">
                                Buka
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
            <button type="button" onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-secondary text-white font-bold text-xs hover:bg-secondary/90 transition shadow-md flex items-center gap-2">
                <span class="material-symbols-outlined text-[17px]">print</span>
                <span>Cetak / Simpan Dokumen Blanko (A4 PDF)</span>
            </button>
            <a href="{{ route('public.beranda') }}" class="px-5 py-2.5 rounded-xl bg-white border border-outline-variant text-primary font-bold text-xs hover:bg-surface-container transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[17px]">home</span>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </div>

    <!-- DOKUMEN CETAK RESMI BLANKO GBI SHEKINAH GLORY (A4) -->
    <div id="printableDocWrapper" class="bg-white rounded-2xl shadow-xl border border-gray-300 p-6 sm:p-10 relative overflow-hidden select-none doc-times-font doc-a4-sheet" style="font-family: 'Times New Roman', Times, serif;">
        
        <!-- WATERMARK LOGO GBI DI TENGAH KERTAS (BERWARNA) -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.10] select-none z-0">
            <img src="{{ asset('images/logo.jpg') }}" alt="Watermark Gereja Bethel Indonesia" class="w-96 h-96 object-contain">
        </div>

        <!-- KONTEN DOKUMEN -->
        <div class="relative z-10 space-y-4 text-black">
            
            <!-- KOP SURAT RESMI GBI SHEKINAH GLORY -->
            <div class="flex items-start gap-4 pb-2 border-b-2 border-[#b91c1c]">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo GBI" class="w-14 h-14 sm:w-16 sm:h-16 object-contain shrink-0 mt-0.5">
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
                        Jalan Ikan Arwana Namosain - Kupang Telpon : 085 333 650 621
                    </p>
                </div>
            </div>
            <!-- Garis Ganda Bawah Kop Surat -->
            <div class="h-0.5 bg-[#1e3a8a] -mt-3 mb-3"></div>

            @php
                $extra = $pendaftaran->data_tambahan ?? [];
            @endphp

            <!-- =================================================== -->
            <!-- TEMPLATE 1: FORM PENGAJUAN BAPTISAN (Roma 6:4)      -->
            <!-- =================================================== -->
            @if($pendaftaran->jenis_layanan === 'baptis')
            <div class="text-center my-3">
                <div class="inline-block border border-[#1e3a8a] px-6 py-1 bg-white shadow-2xs">
                    <h4 class="font-bold text-xs sm:text-sm text-gray-900 tracking-wider uppercase">
                        FORM PENGAJUAN BAPTISAN
                    </h4>
                </div>
                <div class="text-xs italic text-gray-700 mt-1">
                    (Roma 6:4)
                </div>
            </div>

            <p class="text-[11px] text-gray-700 italic mb-2">
                *Nomor Surat (Di isi petugas) : <span class="font-mono text-gray-900 font-bold">{{ $pendaftaran->kode_pendaftaran }}</span>
            </p>

            <div class="space-y-2 text-xs text-gray-900 leading-relaxed">
                <div class="font-bold text-[12px] flex items-center gap-1.5 underline">
                    <span>➢</span>
                    <span>Biodata Pribadi :</span>
                </div>

                <div class="pl-4 space-y-1.5">
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">1. Nama Lengkap</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span class="font-bold text-gray-900 uppercase">{{ $pendaftaran->nama_pemohon }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">2. Jenis Kelamin</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span>{{ $extra['jenis_kelamin'] ?? 'Laki-laki' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">3. TTL/Umur</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span>
                                {{ $extra['tempat_lahir'] ?? '' }}{{ !empty($extra['tanggal_lahir']) ? ', ' . \Carbon\Carbon::parse($extra['tanggal_lahir'])->translatedFormat('d F Y') : '' }}
                                {{ !empty($extra['umur']) ? ' / ' . $extra['umur'] : '' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">4. Alamat</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span>{{ $pendaftaran->alamat ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">5. No.Hp</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span class="font-mono">{{ $pendaftaran->no_wa ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-1 items-start">
                        <span class="col-span-5 sm:col-span-4 text-gray-800 mt-0.5">6. Nama Orang Tua</span>
                        <div class="col-span-7 sm:col-span-8 space-y-1">
                            <div class="flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                <span class="text-gray-700 w-16 text-[11px]">❖ Ayah</span>
                                <span class="mr-1.5">:</span>
                                <span class="font-semibold uppercase">{{ $extra['nama_ayah'] ?? '-' }}</span>
                            </div>
                            <div class="flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                                <span class="text-gray-700 w-16 text-[11px]">❖ Ibu</span>
                                <span class="mr-1.5">:</span>
                                <span class="font-semibold uppercase">{{ $extra['nama_ibu'] ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">7. Oikos Wilayah Pelayanan</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span class="font-semibold">{{ $extra['wilayah_pelayanan'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tanda Tangan Gembala Sidang -->
            <div class="pt-10 flex justify-end text-xs text-gray-900">
                <div class="text-center space-y-0.5 w-60">
                    <p class="text-[11px]">Kupang, <span class="border-b border-dotted border-gray-400 px-3">{{ $pendaftaran->created_at->translatedFormat('d F') }}</span> {{ $pendaftaran->created_at->format('Y') }}</p>
                    <p class="font-bold tracking-wider text-[11px] mt-1">MENGETAHUI</p>
                    <p class="font-bold tracking-wider text-[11px]">GEMBALA SIDANG</p>
                    <p class="font-bold tracking-wider text-[11px]">GBI SHEKINAH GLORY</p>
                    <div class="h-16 flex items-center justify-center">
                        <span class="text-[10px] text-gray-400 italic font-mono">( Tanda Tangan &amp; Cap )</span>
                    </div>
                    <p class="font-bold underline text-xs tracking-wide">Pdt. JUSUF LABAGAI</p>
                </div>
            </div>

            <!-- =================================================== -->
            <!-- TEMPLATE 2: FORM PENGAJUAN PENYERAHAN ANAK          -->
            <!-- =================================================== -->
            @elseif($pendaftaran->jenis_layanan === 'penyerahan_anak')
            <div class="text-center my-3">
                <div class="inline-block border border-[#1e3a8a] px-6 py-1 bg-white shadow-2xs">
                    <h4 class="font-bold text-xs sm:text-sm text-gray-900 tracking-wider uppercase">
                        FORM PENGAJUAN PENYERAHAN ANAK
                    </h4>
                </div>
                <div class="text-xs italic text-gray-700 mt-1">
                    (Markus 10:14-16)
                </div>
            </div>

            <p class="text-[11px] text-gray-700 italic mb-2">
                *Nomor Surat (Di isi petugas) : <span class="font-mono text-gray-900 font-bold">{{ $pendaftaran->kode_pendaftaran }}</span>
            </p>

            <div class="space-y-2 text-xs text-gray-900 leading-relaxed">
                <!-- 1. Biodata Anak -->
                <div class="font-bold text-[12px] flex items-center gap-1.5 underline">
                    <span>➢</span>
                    <span>Biodata Anak :</span>
                </div>
                <div class="pl-4 space-y-1.5">
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">1. Nama Lengkap</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span class="font-bold text-gray-900 uppercase">{{ $pendaftaran->nama_pemohon }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">2. TTL/Usia</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span>
                                {{ $extra['tempat_lahir'] ?? '' }}{{ !empty($extra['tanggal_lahir']) ? ', ' . \Carbon\Carbon::parse($extra['tanggal_lahir'])->translatedFormat('d F Y') : '' }}
                                {{ !empty($extra['usia']) ? ' / ' . $extra['usia'] : '' }}
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">3. Jenis kelamin</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span>{{ $extra['jenis_kelamin'] ?? 'Laki-laki' }}</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Biodata Orang Tua -->
                <div class="font-bold text-[12px] flex items-center gap-1.5 underline pt-1">
                    <span>➢</span>
                    <span>Biodata Orang Tua :</span>
                </div>
                <div class="pl-4 space-y-1.5">
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">1. Nama Lengkap Ayah</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span class="font-semibold uppercase">{{ $extra['nama_ayah'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">2. Nama Lengkap Ibu</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span class="font-semibold uppercase">{{ $extra['nama_ibu'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">3. Alamat</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span>{{ $pendaftaran->alamat ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">4. Oikos Wilayah Pelayanan</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span class="font-semibold">{{ $extra['wilayah_pelayanan'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">5. No.Hp</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span class="font-mono">{{ $pendaftaran->no_wa ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- 3. Biodata Orang Tua Saksi -->
                <div class="font-bold text-[12px] flex items-center gap-1.5 underline pt-1">
                    <span>➢</span>
                    <span>Biodata Orang Tua Saksi :</span>
                </div>
                <div class="pl-4 space-y-1.5">
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">1. Nama Saksi .1</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span>{{ $extra['nama_saksi_1'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">2. Nama Saksi .2</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span>{{ $extra['nama_saksi_2'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">3. Jenis Kelamin</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span>{{ $extra['jenis_kelamin_saksi'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">4. Alamat</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span>{{ $extra['alamat_saksi'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">5. Asal Gereja</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span>{{ $extra['asal_gereja_saksi'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 sm:col-span-4 text-gray-800">6. No.Hp</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span class="font-mono">{{ $extra['no_hp_saksi'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tanda Tangan Gembala Sidang -->
            <div class="pt-8 flex justify-end text-xs text-gray-900">
                <div class="text-center space-y-0.5 w-64">
                    <p class="text-[11px]">Kupang, <span class="border-b border-dotted border-gray-400 px-3">{{ $pendaftaran->created_at->translatedFormat('d F') }}</span> {{ $pendaftaran->created_at->format('Y') }}</p>
                    <p class="font-bold tracking-wider text-[11px] mt-1">MENGETAHUI</p>
                    <p class="font-bold tracking-wider text-[11px]">GEMBALA SIDANG</p>
                    <p class="font-bold tracking-wider text-[11px]">GBI SHEKINAH GLORY NAMOSAIN</p>
                    <div class="h-14 flex items-center justify-center">
                        <span class="text-[10px] text-gray-400 italic font-mono">( Tanda Tangan &amp; Cap )</span>
                    </div>
                    <p class="font-bold underline text-xs tracking-wide">Pdt.JUSUF LABAGAI</p>
                    <p class="text-[10px] font-mono">NIP : 1000702</p>
                </div>
            </div>

            <!-- =================================================== -->
            <!-- TEMPLATE 3: FORM PENGAJUAN PERNIKAHAN (Matius 19:6) -->
            <!-- =================================================== -->
            @elseif($pendaftaran->jenis_layanan === 'nikah')
            <!-- LEMBAR 1 -->
            <div class="space-y-3 max-w-xl mx-auto">
                <div class="text-center my-2">
                    <div class="inline-block border border-[#1e3a8a] px-6 py-1 bg-white shadow-2xs">
                        <h4 class="font-bold text-xs sm:text-sm text-gray-900 tracking-wider uppercase">
                            FORM PENGAJUAN PERNIKAHAN
                        </h4>
                    </div>
                    <div class="text-xs italic text-gray-700 mt-1">
                        (Matius 19:6)
                    </div>
                </div>

                <div class="flex items-baseline gap-1 text-[11px] text-gray-700 italic mb-2">
                    <span class="shrink-0">*Nomor Surat (Di isi petugas)</span>
                    <span class="shrink-0 mr-1">:</span>
                    <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 font-mono text-gray-900 font-bold">{{ $pendaftaran->kode_pendaftaran }}</div>
                </div>

                <!-- Calon Pengantin Pria (1-13 Plek Ketiplek) -->
                <div class="font-bold text-[12px] flex items-center gap-1.5 underline">
                    <span>➢</span>
                    <span>Biodata Calon Pengantin Pria :</span>
                </div>
                <div class="pl-2 space-y-1 text-xs text-gray-900 leading-relaxed">
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">1. Nama Lengkap</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="font-bold text-gray-900 uppercase break-words block">{{ $pendaftaran->nama_pemohon }}</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">2. TTL/Usia</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="font-semibold text-gray-900 break-words block">
                                {{ $extra['pria_tempat'] ?? '' }}{{ !empty($extra['pria_tgl']) ? ', ' . \Carbon\Carbon::parse($extra['pria_tgl'])->translatedFormat('d F Y') : '' }}
                                {{ !empty($extra['pria_usia']) ? ' / ' . $extra['pria_usia'] : '' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">3. Alamat</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="font-semibold text-gray-900 break-words block">{{ $pendaftaran->alamat ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">4. Baptis/Sidi Dll/Pada Tanggal</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="font-semibold text-gray-900 break-words block">{{ $extra['pria_baptis'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">5. Asal Gereja</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="font-semibold text-gray-900 break-words block">{{ $extra['pria_gereja'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">6. No Hp</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="font-mono font-semibold text-gray-900 break-words block">{{ $pendaftaran->no_wa ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800 mt-0.5">7. Nama Orang Tua</span>
                        <div class="flex-1 space-y-1 min-w-0">
                            <div class="flex items-baseline gap-1">
                                <span class="text-gray-700 w-16 shrink-0 text-[11px]">❖ Ayah</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span class="font-semibold uppercase break-words block">{{ $extra['pria_ayah'] ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-gray-700 w-16 shrink-0 text-[11px]">❖ Ibu</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span class="font-semibold uppercase break-words block">{{ $extra['pria_ibu'] ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">8. No Hp Ayah/Ibu</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="font-mono break-words block">{{ $extra['pria_ortu_hp'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">9. Nama Saksi</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="break-words block">{{ $extra['pria_saksi'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">10. TTL</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="break-words block">{{ $extra['pria_saksi_ttl'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">11. Alamat</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="break-words block">{{ $extra['pria_saksi_alamat'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">12. No Hp</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="font-mono break-words block">{{ $extra['pria_saksi_hp'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">13. Asal Gereja</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="break-words block">{{ $extra['pria_saksi_gereja'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Calon Pengantin Wanita (1-5 di Halaman 1 sesuai PDF) -->
                <div class="font-bold text-[12px] flex items-center gap-1.5 underline pt-2">
                    <span>➢</span>
                    <span>Biodata Calon Pengantin Wanita :</span>
                </div>
                <div class="pl-2 space-y-1 text-xs text-gray-900 leading-relaxed">
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">1. Nama Lengkap</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="font-bold text-gray-900 uppercase break-words block">{{ $extra['wanita_nama'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">2. TTL/Usia</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="font-semibold text-gray-900 break-words block">
                                {{ $extra['wanita_tempat'] ?? '' }}{{ !empty($extra['wanita_tgl']) ? ', ' . \Carbon\Carbon::parse($extra['wanita_tgl'])->translatedFormat('d F Y') : '' }}
                                {{ !empty($extra['wanita_usia']) ? ' / ' . $extra['wanita_usia'] : '' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">3. Alamat</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="font-semibold text-gray-900 break-words block">{{ $extra['wanita_alamat'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">4. Baptis/Sidi Dll/Pada Tanggal</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="font-semibold text-gray-900 break-words block">{{ $extra['wanita_baptis'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">5. Asal Gereja</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="font-semibold text-gray-900 break-words block">{{ $extra['wanita_gereja'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PEMBATAS HALAMAN 2 UNTUK NIKAH (SESUAI DOKUMEN FISIK) -->
            <div class="pt-6 border-t-2 border-dashed border-gray-400 my-4 text-center">
                <span class="px-3 py-1 bg-gray-100 text-[10px] font-sans font-bold text-gray-600 rounded-full border border-gray-300">
                    --- Halaman 2 Formulir Pernikahan ---
                </span>
            </div>

            <!-- LEMBAR 2 NIKAH -->
            <div class="space-y-4 max-w-xl mx-auto pt-2">
                <div class="pl-2 space-y-1 text-xs text-gray-900 leading-relaxed">
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">6. No Hp</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="font-mono font-semibold text-gray-900 break-words block">{{ $extra['wanita_hp'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800 mt-0.5">7. Nama Orang Tua</span>
                        <div class="flex-1 space-y-1 min-w-0">
                            <div class="flex items-baseline gap-1">
                                <span class="text-gray-700 w-16 shrink-0 text-[11px]">❖ Ayah</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span class="font-semibold uppercase break-words block">{{ $extra['wanita_ayah'] ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-gray-700 w-16 shrink-0 text-[11px]">❖ Ibu</span>
                                <span class="shrink-0 mr-1">:</span>
                                <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                                    <span class="font-semibold uppercase break-words block">{{ $extra['wanita_ibu'] ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">8. No.Hp Ayah/Ibu</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="font-mono break-words block">{{ $extra['wanita_ortu_hp'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">9. Nama Saksi</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="break-words block">{{ $extra['wanita_saksi'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">10. TTL</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="break-words block">{{ $extra['wanita_saksi_ttl'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">11. Alamat</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="break-words block">{{ $extra['wanita_saksi_alamat'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">12. No Hp</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="font-mono break-words block">{{ $extra['wanita_saksi_hp'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 py-0.5">
                        <span class="w-44 sm:w-52 shrink-0 text-gray-800">13. Asal Gereja</span>
                        <span class="shrink-0 mr-1">:</span>
                        <div class="flex-1 border-b border-dotted border-gray-400 pb-0.5 min-w-0">
                            <span class="break-words block">{{ $extra['wanita_saksi_gereja'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Tanda Tangan Gembala Sidang: DIPERBAIKI POSISI TENGAH SEIMBANG PERSIS SCAN ASLI -->
                <div class="pt-8 flex justify-center text-xs text-gray-900">
                    <div class="text-center space-y-0.5 w-72">
                        <p class="text-[11px]">Kupang, <span class="border-b border-dotted border-gray-400 px-3">{{ $pendaftaran->created_at->translatedFormat('d F') }}</span> {{ $pendaftaran->created_at->format('Y') }}</p>
                        <p class="font-bold tracking-wider text-[11px] mt-1">MENGETAHUI</p>
                        <p class="font-bold tracking-wider text-[11px]">GEMBALA SIDANG</p>
                        <p class="font-bold tracking-wider text-[11px]">GBI SHEKINAH GLORY NAMOSAIN</p>
                        <div class="h-14 flex items-center justify-center">
                            <span class="text-[10px] text-gray-400 italic font-mono">( Tanda Tangan &amp; Cap )</span>
                        </div>
                        <p class="font-bold underline text-xs tracking-wide">Pdt. JUSUF LABAGAI</p>
                        <p class="text-[10px] font-mono">NIP : 1000702</p>
                    </div>
                </div>

                <!-- Catatan Persis Scan Form Nikah -->
                <div class="p-3 rounded border border-gray-400 bg-gray-50/50 text-[10px] leading-relaxed mt-4">
                    <strong class="italic block mb-1 underline">* Catatan : untuk kedua calon mempelai melampirkan :</strong>
                    <ol class="list-decimal pl-4 space-y-0.5">
                        <li>Fotocopy KTP 1.Lembar</li>
                        <li>Foto copy Surat Baptis/Sidi 1.Lembar</li>
                        <li>Foto copy KK 1.Lembar</li>
                        <li>Foto Copy Akte Kelahiran 1.Lembar</li>
                        <li>Foto gandeng 1.Lembar</li>
                    </ol>
                </div>
            </div>

            <!-- =================================================== -->
            <!-- TEMPLATE 4: FORM ANGGOTA JEMAAT BARU                -->
            <!-- =================================================== -->
            @elseif($pendaftaran->jenis_layanan === 'jemaat_baru')
            <!-- LEMBAR 1 JEMAAT BARU -->
            <div class="space-y-2 text-[11px] text-gray-900 leading-relaxed">
                <!-- Box Judul & Petugas Sesuai Blanko Asli -->
                <div class="flex items-stretch justify-between gap-2 border border-black p-2 my-2 bg-gray-50/40">
                    <div class="border-2 border-black px-4 py-2 font-bold text-xs uppercase tracking-wider flex items-center justify-center">
                        FORM ANGGOTA JEMAAT BARU
                    </div>
                    <div class="border border-black p-1.5 text-[10px] space-y-0.5 text-left w-52 bg-white">
                        <span class="font-bold block italic underline">Diisi Oleh Petugas</span>
                        <div>Data Nomor : <span class="font-mono text-gray-900 font-bold">{{ $pendaftaran->kode_pendaftaran }}</span></div>
                        <div>Tanggal Diinput : <span class="font-mono">{{ $pendaftaran->created_at->format('d/m/Y') }}</span></div>
                    </div>
                </div>

                <!-- ➢ Biodata Pribadi -->
                <div class="font-bold text-[12px] flex items-center gap-1.5 underline">
                    <span>➢</span>
                    <span>Biodata Pribadi :</span>
                </div>
                
                <div class="pl-2 space-y-1.5">
                    <div>
                        <span class="text-gray-800 block text-[10px]">[Nama Lengkap Sesuai KTP]</span>
                        <div class="border border-black px-2 py-1 font-bold text-xs bg-white uppercase">
                            <span>{{ $pendaftaran->nama_pemohon }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-[10px]">
                        <div class="border border-black p-1 flex items-center justify-between">
                            <span>[Jenis Kelamin]:</span>
                            <span class="font-bold text-primary">{{ $extra['jenis_kelamin'] ?? 'Pria' }}</span>
                        </div>
                        <div class="border border-black p-1 flex items-center justify-between">
                            <span>[Status]:</span>
                            <span class="font-bold text-primary">{{ $extra['status_nikah'] ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-1 text-[10px] items-center">
                        <span class="col-span-3">[Tempat Lahir]:</span>
                        <div class="col-span-4 border border-black px-2 py-0.5 font-semibold">{{ $extra['tempat_lahir'] ?? '-' }}</div>
                        <span class="col-span-2 text-center">[Tgl/Bln/Thn]:</span>
                        <div class="col-span-3 border border-black px-2 py-0.5 font-semibold text-center">{{ !empty($extra['tanggal_lahir']) ? \Carbon\Carbon::parse($extra['tanggal_lahir'])->format('d/m/Y') : '-' }}</div>
                    </div>

                    <div>
                        <span class="text-gray-800 block text-[10px]">[Alamat Rumah]</span>
                        <div class="border border-black px-2 py-1 font-semibold text-[11px] flex items-center gap-2">
                            <span>Jalan:</span>
                            <span class="flex-1 font-normal">{{ $pendaftaran->alamat ?? '-' }}</span>
                            <span>RT.</span>
                            <span class="w-12 border-b border-black font-semibold text-center">{{ $extra['rt_rw'] ?? '-' }}</span>
                            <span>RW.</span>
                            <span class="w-12 border-b border-black font-semibold text-center">-</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-[10px]">
                        <div class="border border-black p-1 flex items-center gap-1">
                            <span>[Kelurahan]:</span>
                            <span class="font-semibold">{{ $extra['kelurahan'] ?? '-' }}</span>
                        </div>
                        <div class="border border-black p-1 flex items-center gap-1">
                            <span>[Kecamatan]:</span>
                            <span class="font-semibold">{{ $extra['kecamatan'] ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-[10px]">
                        <div class="border border-black p-1 flex items-center gap-1">
                            <span>[Kota / Provinsi]:</span>
                            <span class="font-semibold">{{ $extra['kota_prov'] ?? 'Kupang, NTT' }}</span>
                        </div>
                        <div class="border border-black p-1 flex items-center justify-between">
                            <span>[Golongan Darah]:</span>
                            <span class="font-bold text-red-700 font-mono">{{ $extra['golongan_darah'] ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-[10px]">
                        <div class="border border-black p-1 flex items-center justify-between">
                            <span>[No.Handphone]:</span>
                            <span class="font-mono font-bold">{{ $pendaftaran->no_wa ?? '-' }}</span>
                        </div>
                        <div class="border border-black p-1 flex items-center justify-between">
                            <span>[Pendidikan Terakhir]:</span>
                            <span class="font-semibold">{{ $extra['pendidikan'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- ➢ Keanggotaan Gereja -->
                <div class="font-bold text-[12px] flex items-center gap-1.5 underline pt-1">
                    <span>➢</span>
                    <span>Keanggotaan Gereja :</span>
                </div>
                <div class="pl-2 space-y-1">
                    <div class="border border-black p-1 text-[10px]">
                        <span class="font-semibold block mb-0.5">Baptisan:</span>
                        <div class="flex items-center gap-2">
                            <span>Tanggal:</span>
                            <span class="font-semibold border-b border-black px-2">{{ $extra['tgl_baptis'] ?? '-' }}</span>
                            <span>Di Gereja:</span>
                            <span class="font-semibold border-b border-black flex-1">{{ $extra['gereja_baptis'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="text-[10px]">
                        <span class="font-semibold block mb-0.5">Perpindahan Anggota Jemaat sebelum disini:</span>
                        <table class="w-full border-collapse border border-black text-[9px]">
                            <thead>
                                <tr class="bg-gray-100 text-center">
                                    <th class="border border-black p-1">Tanggal</th>
                                    <th class="border border-black p-1">Dari Jemaat</th>
                                    <th class="border border-black p-1">Alamat</th>
                                    <th class="border border-black p-1">Ke jemaat</th>
                                    <th class="border border-black p-1">Alamat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-black p-1 text-center font-mono">-</td>
                                    <td class="border border-black p-1">{{ $extra['riwayat_pindah'] ?? '-' }}</td>
                                    <td class="border border-black p-1">-</td>
                                    <td class="border border-black p-1 font-semibold">GBI Shekinah Glory</td>
                                    <td class="border border-black p-1">Namosain</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ➢ Data Keluarga -->
                <div class="font-bold text-[12px] flex items-center gap-1.5 underline pt-1">
                    <span>➢</span>
                    <span>Data Keluarga:</span>
                </div>
                <div class="pl-2 space-y-1 text-[10px]">
                    <div class="grid grid-cols-2 gap-2">
                        <div class="border border-black p-1">
                            <span class="text-gray-600 block text-[9px]">[Nama ayah]:</span>
                            <span class="font-semibold uppercase">{{ $extra['nama_ayah'] ?? '-' }}</span>
                        </div>
                        <div class="border border-black p-1">
                            <span class="text-gray-600 block text-[9px]">[Nama Ibu]:</span>
                            <span class="font-semibold uppercase">{{ $extra['nama_ibu'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="border border-black p-1 space-y-0.5">
                        <span class="italic text-[9px] font-semibold">[Jika menikah atau pernah menikah]</span>
                        <div class="grid grid-cols-2 gap-2">
                            <div>[Nama suami/istri]: <span class="font-semibold">{{ $extra['nama_pasangan'] ?? '-' }}</span></div>
                            <div>[Tanggal Menikah]: <span class="font-semibold">{{ $extra['tgl_nikah'] ?? '-' }}</span></div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-0.5">
                            <div>[Oleh pendeta]: <span class="font-semibold">{{ $extra['pendeta_nikah'] ?? '-' }}</span></div>
                            <div>[Di Gereja]: <span class="font-semibold">{{ $extra['gereja_nikah'] ?? '-' }}</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PEMBATAS HALAMAN 2 UNTUK ANGGOTA JEMAAT BARU -->
            <div class="pt-6 border-t-2 border-dashed border-gray-400 my-4 text-center">
                <span class="px-3 py-1 bg-gray-100 text-[10px] font-sans font-bold text-gray-600 rounded-full border border-gray-300">
                    --- Halaman 2 Formulir Anggota Jemaat Baru ---
                </span>
            </div>

            <!-- LEMBAR 2 JEMAAT BARU -->
            <div class="space-y-3 text-[10px] text-gray-900 leading-relaxed pt-2">
                <!-- Tabel Jika Dikaruniai Anak -->
                <div>
                    <span class="font-bold block mb-1">[Jika dikaruniai anak]</span>
                    <table class="w-full border-collapse border border-black text-[9px]">
                        <thead>
                            <tr class="bg-gray-100 text-center">
                                <th class="border border-black p-1 w-12">Anak ke</th>
                                <th class="border border-black p-1">Nama Anak</th>
                                <th class="border border-black p-1 w-12">L/P</th>
                                <th class="border border-black p-1">TTL</th>
                                <th class="border border-black p-1">ALAMAT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-black p-1 text-center font-mono">1</td>
                                <td class="border border-black p-1">{{ $extra['data_anak'] ?? '-' }}</td>
                                <td class="border border-black p-1 text-center">-</td>
                                <td class="border border-black p-1">-</td>
                                <td class="border border-black p-1">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ➢ Pengalaman dan aktivitas -->
                <div class="font-bold text-[12px] flex items-center gap-1.5 underline pt-1">
                    <span>➢</span>
                    <span>Pengalaman dan aktivitas:</span>
                </div>
                <div>
                    <span class="font-semibold block mb-0.5">[Pengalaman aktivitas Gerejawi]</span>
                    <table class="w-full border-collapse border border-black text-[9px]">
                        <thead>
                            <tr class="bg-gray-100 text-center">
                                <th class="border border-black p-1">Bidang Pelayanan</th>
                                <th class="border border-black p-1">Jabatan/Keterangan Tugas</th>
                                <th class="border border-black p-1">Periode</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-black p-1">{{ $extra['akt_gereja'] ?? '-' }}</td>
                                <td class="border border-black p-1">-</td>
                                <td class="border border-black p-1 text-center">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div>
                    <span class="font-semibold block mb-0.5">[Aktivitas diluar kegiatan Gereja (sosial &amp; masyarakat)]</span>
                    <table class="w-full border-collapse border border-black text-[9px]">
                        <thead>
                            <tr class="bg-gray-100 text-center">
                                <th class="border border-black p-1">Nama Organisasi</th>
                                <th class="border border-black p-1">Kegiatan</th>
                                <th class="border border-black p-1">Jabatan</th>
                                <th class="border border-black p-1">Periode</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-black p-1">{{ $extra['akt_sosial'] ?? '-' }}</td>
                                <td class="border border-black p-1">-</td>
                                <td class="border border-black p-1">-</td>
                                <td class="border border-black p-1 text-center">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Kotak Pas Foto 4x6 Warna & Tanda Tangan Persis Form Scan Asli -->
                <div class="grid grid-cols-12 gap-4 pt-4 items-end">
                    <div class="col-span-4">
                        <span class="font-bold block text-center mb-1 text-[9px]">Pas Foto 4x6 Warna</span>
                        <div class="w-24 h-32 border-2 border-black rounded mx-auto flex flex-col items-center justify-center text-center p-2 text-[9px] text-gray-500 bg-white">
                            <span class="material-symbols-outlined text-[24px] text-gray-400 mb-1">image</span>
                            <span>(Pas Foto 4x6)</span>
                        </div>
                    </div>

                    <div class="col-span-4">
                        <span class="font-bold block text-center mb-1 text-[9px]">Tanda Tangan</span>
                        <div class="w-28 h-24 border border-black rounded mx-auto flex items-center justify-center text-[8px] text-gray-400">
                            (Tanda Tangan)
                        </div>
                        <div class="mt-1 border border-black px-1.5 py-0.5 text-[9px]">
                            <span>Nama Lengkap : </span>
                            <span class="font-bold uppercase">{{ $pendaftaran->nama_pemohon }}</span>
                        </div>
                    </div>

                    <div class="col-span-4 text-center space-y-0.5">
                        <p class="text-[9px]">Kupang, <span class="border-b border-dotted border-gray-400 px-2">{{ $pendaftaran->created_at->translatedFormat('d F') }}</span> {{ $pendaftaran->created_at->format('Y') }}</p>
                        <p class="font-bold text-[9px] tracking-wider">MENGETAHUI</p>
                        <p class="font-bold text-[9px]">GEMBALA SIDANG</p>
                        <p class="font-bold text-[9px]">GBI.SHEKINAH GLORY NAMOSAIN</p>
                        <div class="h-12 flex items-center justify-center">
                            <span class="text-[8px] text-gray-400 italic">( Tanda Tangan &amp; Cap )</span>
                        </div>
                        <p class="font-bold underline text-[10px] tracking-wide">Pdt.JUSUF LABAGAI</p>
                        <p class="text-[8px] font-mono">NIP;1000702</p>
                    </div>
                </div>

                <div class="pt-2 text-[9px] text-gray-800 leading-tight border-t border-gray-400">
                    <strong>NB; Lampirkan FC. 1. KTP, 2. KK, 3. AKTA BAPTIS &amp; KELAHIRAN</strong>
                </div>
            </div>
            @endif

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
