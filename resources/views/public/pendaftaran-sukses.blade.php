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

        <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/20 inline-block text-left w-full max-w-lg text-xs space-y-2">
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
                <span class="text-outline">Status:</span>
                <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold uppercase text-[10px]">Menunggu Verifikasi Pastoral</span>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
            <button type="button" onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-secondary text-white font-bold text-xs hover:bg-secondary/90 transition shadow-md flex items-center gap-2">
                <span class="material-symbols-outlined text-[17px]">print</span>
                <span>Cetak / Simpan Dokumen Blanko (PDF)</span>
            </button>
            <a href="{{ route('public.beranda') }}" class="px-5 py-2.5 rounded-xl bg-white border border-outline-variant text-primary font-bold text-xs hover:bg-surface-container transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[17px]">home</span>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </div>

    <!-- DOKUMEN CETAK RESMI BLANKO GBI SHEKINAH GLORY (A4) - TIMES NEW ROMAN -->
    <div id="printableDocWrapper" class="bg-white rounded-2xl shadow-xl border border-gray-300 p-6 sm:p-10 relative overflow-hidden select-none doc-times-font" style="min-height: 800px; font-family: 'Times New Roman', Times, serif;">
        
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
                    <p class="text-[9px] sm:text-[10px] text-gray-800 leading-tight font-medium mt-0.5">
                        Jalan Ikan Arwana Namosain - Kupang Telpon : 085 333 650 621, 081 238 858 629, 082 247 292 194
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
                            <span>{{ $extra['jenis_kelamin'] ?? '-' }}</span>
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
            <div class="pt-8 flex justify-end text-xs text-gray-900">
                <div class="text-center space-y-0.5 w-60">
                    <p class="text-[11px]">Kupang, <span class="border-b border-dotted border-gray-400 px-3">{{ $pendaftaran->created_at->translatedFormat('d F') }}</span> {{ $pendaftaran->created_at->format('Y') }}</p>
                    <p class="font-bold tracking-wider text-[11px] mt-1">MENGETAHUI</p>
                    <p class="font-bold tracking-wider text-[11px]">GEMBALA SIDANG</p>
                    <p class="font-bold tracking-wider text-[11px]">GBI SHEKINAH GLORY NAMOSAIN</p>
                    <div class="h-16 flex items-center justify-center">
                        <span class="text-[10px] text-gray-400 italic font-mono">( Tanda Tangan &amp; Cap )</span>
                    </div>
                    <p class="font-bold underline text-xs tracking-wide">Pdt. JUSUF LABAGAI</p>
                    <p class="text-[10px] font-mono">NIP : 1000702</p>
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
                        <span class="col-span-5 sm:col-span-4 text-gray-800">3. Jenis Kelamin</span>
                        <div class="col-span-7 sm:col-span-8 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">
                            <span class="mr-1.5">:</span>
                            <span>{{ $extra['jenis_kelamin'] ?? '-' }}</span>
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
            <div class="pt-6 flex justify-end text-xs text-gray-900">
                <div class="text-center space-y-0.5 w-64">
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

            <!-- =================================================== -->
            <!-- TEMPLATE 3: FORM PENGAJUAN PERNIKAHAN (Matius 19:6) -->
            <!-- =================================================== -->
            @elseif($pendaftaran->jenis_layanan === 'nikah')
            <div class="text-center my-3">
                <div class="inline-block border border-[#1e3a8a] px-6 py-1 bg-white shadow-2xs">
                    <h4 class="font-bold text-xs sm:text-sm text-gray-900 tracking-wider uppercase">
                        FORM PENGAJUAN PERNIKAHAN
                    </h4>
                </div>
                <div class="text-xs italic text-gray-700 mt-1">
                    (Matius 19:6)
                </div>
            </div>

            <p class="text-[11px] text-gray-700 italic mb-2">
                *Nomor Surat (Di isi petugas) : <span class="font-mono text-gray-900 font-bold">{{ $pendaftaran->kode_pendaftaran }}</span>
            </p>

            <div class="space-y-3 text-xs text-gray-900 leading-relaxed">
                <!-- Calon Pengantin Pria -->
                <div class="font-bold text-[12px] flex items-center gap-1.5 underline">
                    <span>➢</span>
                    <span>Biodata Calon Pengantin Pria :</span>
                </div>
                <div class="pl-4 space-y-1">
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 text-gray-800">1. Nama Lengkap</span>
                        <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span class="font-bold text-gray-900 uppercase">{{ $extra['pria_nama'] ?? $pendaftaran->nama_pemohon }}</span></div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 text-gray-800">2. TTL/Usia</span>
                        <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span>{{ $extra['pria_ttl'] ?? '-' }}</span></div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 text-gray-800">3. Alamat</span>
                        <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span>{{ $extra['pria_alamat'] ?? ($pendaftaran->alamat ?? '-') }}</span></div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 text-gray-800">4. Baptis/Sidi Dll/Pada Tgl</span>
                        <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span>{{ $extra['pria_baptis'] ?? '-' }}</span></div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 text-gray-800">5. Asal Gereja</span>
                        <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span>{{ $extra['pria_gereja'] ?? '-' }}</span></div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 text-gray-800">6. No Hp</span>
                        <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span class="font-mono">{{ $extra['pria_hp'] ?? ($pendaftaran->no_wa ?? '-') }}</span></div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-start">
                        <span class="col-span-5 text-gray-800 mt-0.5">7. Nama Orang Tua</span>
                        <div class="col-span-7 space-y-0.5">
                            <div class="flex items-baseline border-b border-dotted border-gray-400 pb-0.5"><span class="text-gray-700 w-16">❖ Ayah :</span> <span class="font-semibold uppercase">{{ $extra['pria_ayah'] ?? '-' }}</span></div>
                            <div class="flex items-baseline border-b border-dotted border-gray-400 pb-0.5"><span class="text-gray-700 w-16">❖ Ibu :</span> <span class="font-semibold uppercase">{{ $extra['pria_ibu'] ?? '-' }}</span></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 text-gray-800">8. No Hp Ayah/Ibu</span>
                        <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span class="font-mono">{{ $extra['pria_ortu_hp'] ?? '-' }}</span></div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 text-gray-800">9. Nama Saksi Pria</span>
                        <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span>{{ $extra['pria_saksi'] ?? '-' }}</span></div>
                    </div>
                </div>

                <!-- Calon Pengantin Wanita -->
                <div class="font-bold text-[12px] flex items-center gap-1.5 underline pt-2">
                    <span>➢</span>
                    <span>Biodata Calon Pengantin Wanita :</span>
                </div>
                <div class="pl-4 space-y-1">
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 text-gray-800">1. Nama Lengkap</span>
                        <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span class="font-bold text-gray-900 uppercase">{{ $extra['wanita_nama'] ?? '-' }}</span></div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 text-gray-800">2. TTL/Usia</span>
                        <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span>{{ $extra['wanita_ttl'] ?? '-' }}</span></div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 text-gray-800">3. Alamat</span>
                        <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span>{{ $extra['wanita_alamat'] ?? '-' }}</span></div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 text-gray-800">4. Baptis/Sidi Dll/Pada Tgl</span>
                        <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span>{{ $extra['wanita_baptis'] ?? '-' }}</span></div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 text-gray-800">5. Asal Gereja</span>
                        <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span>{{ $extra['wanita_gereja'] ?? '-' }}</span></div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 text-gray-800">6. No Hp</span>
                        <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span class="font-mono">{{ $extra['wanita_hp'] ?? '-' }}</span></div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-start">
                        <span class="col-span-5 text-gray-800 mt-0.5">7. Nama Orang Tua</span>
                        <div class="col-span-7 space-y-0.5">
                            <div class="flex items-baseline border-b border-dotted border-gray-400 pb-0.5"><span class="text-gray-700 w-16">❖ Ayah :</span> <span class="font-semibold uppercase">{{ $extra['wanita_ayah'] ?? '-' }}</span></div>
                            <div class="flex items-baseline border-b border-dotted border-gray-400 pb-0.5"><span class="text-gray-700 w-16">❖ Ibu :</span> <span class="font-semibold uppercase">{{ $extra['wanita_ibu'] ?? '-' }}</span></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 text-gray-800">8. No.Hp Ayah/Ibu</span>
                        <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span class="font-mono">{{ $extra['wanita_ortu_hp'] ?? '-' }}</span></div>
                    </div>
                    <div class="grid grid-cols-12 gap-1 items-baseline">
                        <span class="col-span-5 text-gray-800">9. Nama Saksi Wanita</span>
                        <div class="col-span-7 flex items-baseline border-b border-dotted border-gray-400 pb-0.5">: <span>{{ $extra['wanita_saksi'] ?? '-' }}</span></div>
                    </div>
                </div>
            </div>

            <!-- Tanda Tangan & Lampiran Persis Seperti Form Nikah Asli -->
            <div class="pt-6 flex justify-end text-xs text-gray-900">
                <div class="text-center space-y-0.5 w-64">
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

            <div class="p-3 rounded border border-gray-400 bg-gray-50/50 text-[10px] leading-relaxed mt-4">
                <strong class="italic block mb-1 underline">* Catatan : untuk kedua calon mempelai melampirkan :</strong>
                <ol class="list-decimal pl-4 space-y-0.5">
                    <li>Fotocopy KTP 1 Lembar</li>
                    <li>Foto copy Surat Baptis / Sidi 1 Lembar</li>
                    <li>Foto copy KK 1 Lembar</li>
                    <li>Foto Copy Akte Kelahiran 1 Lembar</li>
                    <li>Foto gandeng 1 Lembar</li>
                </ol>
            </div>

            <!-- =================================================== -->
            <!-- TEMPLATE 4: FORM ANGGOTA JEMAAT BARU                -->
            <!-- =================================================== -->
            @elseif($pendaftaran->jenis_layanan === 'jemaat_baru')
            <div class="flex items-center justify-between border border-black p-2 my-2 bg-gray-50/40">
                <div class="border-2 border-black px-4 py-1.5 font-bold text-xs uppercase tracking-wider">
                    FORM ANGGOTA JEMAAT BARU
                </div>
                <div class="border border-black p-2 text-[10px] space-y-0.5 text-left w-48 bg-white">
                    <span class="font-bold block italic underline">Diisi Oleh Petugas</span>
                    <div>Data Nomor : <span class="font-mono text-gray-900 font-bold">{{ $pendaftaran->kode_pendaftaran }}</span></div>
                    <div>Tanggal Diinput : <span class="font-mono">{{ $pendaftaran->created_at->format('d/m/Y') }}</span></div>
                </div>
            </div>

            <div class="space-y-2 text-[11px] text-gray-900 leading-relaxed">
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
                        <div class="border border-black p-1.5 flex items-center justify-between">
                            <span>[Jenis Kelamin]:</span>
                            <span class="font-bold text-primary">{{ $extra['jenis_kelamin'] ?? '-' }}</span>
                        </div>
                        <div class="border border-black p-1.5 flex items-center justify-between">
                            <span>[Status]:</span>
                            <span class="font-bold text-primary">{{ $extra['status_pernikahan'] ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-1 text-[10px] items-center">
                        <span class="col-span-3">[Tempat Lahir]:</span>
                        <div class="col-span-4 border border-black px-2 py-0.5 font-semibold">{{ $extra['tempat_lahir'] ?? '-' }}</div>
                        <span class="col-span-2 text-center">[Tgl/Bln/Thn]:</span>
                        <div class="col-span-3 border border-black px-2 py-0.5 font-semibold text-center">{{ !empty($extra['tanggal_lahir']) ? \Carbon\Carbon::parse($extra['tanggal_lahir'])->format('d/m/Y') : '-' }}</div>
                    </div>

                    <div>
                        <span class="text-gray-800 block text-[10px]">[Alamat Rumah]:</span>
                        <div class="border border-black px-2 py-1 font-semibold text-[11px]">
                            <span>{{ $pendaftaran->alamat ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-1 text-[10px] items-center">
                        <span class="col-span-4">[RT / RW]:</span>
                        <div class="col-span-8 border border-black px-2 py-0.5 font-semibold">{{ $extra['rt_rw'] ?? '-' }}</div>
                    </div>

                    <div class="grid grid-cols-12 gap-1 text-[10px] items-center">
                        <span class="col-span-4">[Kelurahan / Kecamatan]:</span>
                        <div class="col-span-8 border border-black px-2 py-0.5 font-semibold">{{ $extra['kelurahan_kecamatan'] ?? '-' }}</div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-[10px]">
                        <div class="border border-black p-1.5 flex items-center justify-between">
                            <span>[Golongan Darah]:</span>
                            <span class="font-bold text-red-700">{{ $extra['golongan_darah'] ?? '-' }}</span>
                        </div>
                        <div class="border border-black p-1.5 flex items-center justify-between">
                            <span>[No. Handphone]:</span>
                            <span class="font-mono font-bold">{{ $pendaftaran->no_wa ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-1 text-[10px] items-center">
                        <span class="col-span-4">[Pendidikan Terakhir]:</span>
                        <div class="col-span-8 border border-black px-2 py-0.5 font-semibold">{{ $extra['pendidikan'] ?? '-' }}</div>
                    </div>
                </div>

                <div class="font-bold text-[12px] flex items-center gap-1.5 underline pt-1">
                    <span>➢</span>
                    <span>Data Keluarga &amp; Gereja :</span>
                </div>
                <div class="pl-2 space-y-1">
                    <div class="grid grid-cols-2 gap-2 text-[10px]">
                        <div class="border border-black p-1">
                            <span class="text-gray-600 block text-[9px]">[Nama Ayah]:</span>
                            <span class="font-semibold uppercase">{{ $extra['nama_ayah'] ?? '-' }}</span>
                        </div>
                        <div class="border border-black p-1">
                            <span class="text-gray-600 block text-[9px]">[Nama Ibu]:</span>
                            <span class="font-semibold uppercase">{{ $extra['nama_ibu'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="border border-black p-1 text-[10px]">
                        <span class="text-gray-600 block text-[9px]">[Gereja Asal Sebelumnya]:</span>
                        <span class="font-semibold">{{ $extra['asal_gereja'] ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Kotak Foto 4x6 & Tanda Tangan Persis Form Jemaat Baru -->
            <div class="grid grid-cols-12 gap-3 pt-3 items-end">
                <div class="col-span-4">
                    <div class="w-24 h-32 border-2 border-dashed border-gray-400 rounded flex flex-col items-center justify-center text-center p-2 text-[9px] text-gray-500 bg-gray-50">
                        <span class="material-symbols-outlined text-[20px] text-gray-400 mb-1">image</span>
                        <span>Pas Foto 4x6 Warna</span>
                    </div>
                </div>

                <div class="col-span-8 space-y-1 text-center">
                    <p class="text-[10px]">Kupang, <span class="border-b border-dotted border-gray-400 px-3">{{ $pendaftaran->created_at->translatedFormat('d F') }}</span> {{ $pendaftaran->created_at->format('Y') }}</p>
                    <p class="font-bold text-[10px] tracking-wider">MENGETAHUI GEMBALA SIDANG</p>
                    <p class="font-bold text-[10px]">GBI SHEKINAH GLORY NAMOSAIN</p>
                    <div class="h-12 flex items-center justify-center">
                        <span class="text-[9px] text-gray-400 italic">( Tanda Tangan &amp; Cap Gereja )</span>
                    </div>
                    <p class="font-bold underline text-xs tracking-wide">Pdt. JUSUF LABAGAI</p>
                    <p class="text-[9px] font-mono">NIP; 1000702</p>
                </div>
            </div>

            <div class="pt-2 text-[9px] text-gray-700 italic border-t border-gray-300">
                <strong>NB; Lampirkan FC. 1. KTP, 2. KK, 3. AKTA BAPTIS &amp; KELAHIRAN</strong>
            </div>

            <!-- Fallback jika ada jenis lain -->
            @else
            <div class="text-center my-3">
                <div class="inline-block border border-[#1e3a8a] px-6 py-1 bg-white shadow-2xs">
                    <h4 class="font-bold text-xs sm:text-sm text-gray-900 tracking-wider uppercase">
                        FORMULIR {{ strtoupper(str_replace('_', ' ', $pendaftaran->jenis_layanan)) }}
                    </h4>
                </div>
            </div>

            <p class="text-[11px] text-gray-700 italic mb-2">
                *Nomor Registrasi : <span class="font-mono text-gray-900 font-bold">{{ $pendaftaran->kode_pendaftaran }}</span>
            </p>

            <div class="space-y-3 text-xs text-gray-900 leading-relaxed pt-2">
                <div class="grid grid-cols-12 gap-1 items-baseline">
                    <span class="col-span-4 text-gray-800">Nama Lengkap</span>
                    <div class="col-span-8 border-b border-dotted border-gray-400 pb-0.5">: <strong class="uppercase">{{ $pendaftaran->nama_pemohon }}</strong></div>
                </div>
                <div class="grid grid-cols-12 gap-1 items-baseline">
                    <span class="col-span-4 text-gray-800">No. WhatsApp / HP</span>
                    <div class="col-span-8 border-b border-dotted border-gray-400 pb-0.5">: <span class="font-mono">{{ $pendaftaran->no_wa }}</span></div>
                </div>
                <div class="grid grid-cols-12 gap-1 items-baseline">
                    <span class="col-span-4 text-gray-800">Alamat Domisili</span>
                    <div class="col-span-8 border-b border-dotted border-gray-400 pb-0.5">: <span>{{ $pendaftaran->alamat ?? '-' }}</span></div>
                </div>
            </div>

            <div class="pt-12 flex justify-end text-xs text-gray-900">
                <div class="text-center space-y-0.5 w-60">
                    <p>Kupang, {{ $pendaftaran->created_at->translatedFormat('d F Y') }}</p>
                    <p class="font-bold tracking-wider mt-1">MENGETAHUI</p>
                    <p class="font-bold tracking-wider">GEMBALA SIDANG</p>
                    <div class="h-16"></div>
                    <p class="font-bold underline">Pdt. JUSUF LABAGAI</p>
                    <p class="text-[10px] font-mono">NIP : 1000702</p>
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
        margin: 0 !important;
        padding: 15mm !important;
        box-shadow: none !important;
        border: none !important;
    }
}
</style>
@endsection
