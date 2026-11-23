<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Agenda Arsip Surat GBI Shekinah Glory - Tahun {{ $tahun }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 15mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            line-height: 1.3;
            font-size: 10pt;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1080px;
            margin: 0 auto;
            padding: 10px;
            position: relative;
        }
        .header-kop {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            border-bottom: 2px solid #b91c1c;
            padding-bottom: 8px;
            margin-bottom: 3px;
        }
        .header-kop img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
        .header-text {
            flex: 1;
            text-align: center;
        }
        .header-text h2 {
            margin: 0;
            color: #b91c1c;
            font-size: 14pt;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .header-text h3 {
            margin: 2px 0 0 0;
            color: #1e3a8a;
            font-size: 12pt;
            font-weight: 900;
            text-transform: uppercase;
        }
        .header-text p {
            margin: 2px 0 0 0;
            font-size: 8pt;
            color: #333;
        }
        .blue-line {
            height: 2px;
            background-color: #1e3a8a;
            margin-bottom: 14px;
        }
        .title-box {
            text-align: center;
            margin-bottom: 14px;
        }
        .title-box h3 {
            font-size: 12pt;
            margin: 0;
            text-decoration: underline;
            text-transform: uppercase;
            font-weight: bold;
        }
        .title-box p {
            margin: 3px 0 0;
            font-size: 9.5pt;
            font-style: italic;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 8.5pt;
        }
        table th, table td {
            border: 1px solid #333;
            padding: 5px 6px;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }
        .text-center {
            text-align: center;
        }
        .font-mono {
            font-family: monospace;
        }
        .signatures {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
            font-size: 9.5pt;
        }
        .sig-block {
            text-align: center;
            width: 250px;
        }
        .sig-space {
            height: 55px;
        }
        .print-btn-bar {
            background: #001428;
            color: #fff;
            padding: 10px;
            text-align: center;
            margin-bottom: 15px;
        }
        .btn {
            background: #775a19;
            color: #fff;
            padding: 6px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 9.5pt;
            font-weight: bold;
            margin: 0 4px;
        }
        @media print {
            .print-btn-bar {
                display: none;
            }
            body {
                padding: 0;
            }
            .container {
                max-width: 100%;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="print-btn-bar">
        <button class="btn" onclick="window.print()">Cetak / Simpan PDF (A4 Landscape)</button>
        <button class="btn" style="background: #444;" onclick="window.close()">Tutup Jendela</button>
    </div>

    <div class="container">
        <!-- Kop Surat Resmi GBI Shekinah Glory -->
        <div class="header-kop">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo GBI">
            <div class="header-text">
                <h2>GEREJA BETHEL INDONESIA</h2>
                <h3>JEMAAT SHEKINAH GLORY</h3>
                <p>Badan Hukum Gereja : SK Dirjen Bimas Kristen/Protestan Dep. Agama R.I No. 41 Tahun 1972</p>
                <p>SK Dirjen Bimas (Kristen) Protestan Dep. Agama R.I. Nomor 211 Tanggal, 25 November 1989</p>
                <p><strong>Jalan Ikan Arwana Namosain - Kupang Telpon : 085 333 650 621, 081 238 858 629, 082 247 292 194</strong></p>
            </div>
        </div>
        <div class="blue-line"></div>

        <!-- Judul Laporan -->
        <div class="title-box">
            <h3>BUKU AGENDA EKSPEDISI SURAT MASUK &amp; KELUAR</h3>
            <p>Tahun Kalender: {{ $tahun }} {{ $jenis ? '• Jenis: Surat ' . ucfirst($jenis) : '• Semua Surat Masuk & Keluar' }}</p>
        </div>

        <!-- Tabel Agenda Surat -->
        <table>
            <thead>
                <tr>
                    <th style="width: 25px;">No</th>
                    <th style="width: 65px;">Tanggal</th>
                    <th style="width: 120px;">Nomor Surat</th>
                    <th style="width: 55px;">Jenis</th>
                    <th style="width: 85px;">Kategori</th>
                    <th style="width: 110px;">Pengirim</th>
                    <th style="width: 110px;">Tujuan / Penerima</th>
                    <th>Perihal &amp; Ringkasan</th>
                    <th style="width: 70px;">Lampiran</th>
                    <th style="width: 80px;">Akses Jemaat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratList as $idx => $s)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="text-center">{{ $s->tanggal_surat->format('d/m/Y') }}</td>
                        <td class="font-bold font-mono">{{ $s->nomor_surat }}</td>
                        <td class="text-center" style="text-transform: uppercase; font-size: 7.5pt; font-weight: bold;">
                            {{ $s->jenis_surat }}
                        </td>
                        <td>{{ $s->kategori_label }}</td>
                        <td>{{ $s->pengirim }}</td>
                        <td>{{ $s->tujuan_penerima }}</td>
                        <td>
                            <strong>{{ $s->perihal }}</strong>
                            @if($s->ringkasan_isi)
                                <div style="font-size: 7.5pt; color: #555; margin-top: 1px;">{{ $s->ringkasan_isi }}</div>
                            @endif
                        </td>
                        <td class="text-center">{{ $s->lampiran ?? '-' }}</td>
                        <td class="text-center font-mono font-bold" style="font-size: 8pt; color: #775a19;">
                            {{ $s->kode_akses ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center" style="padding: 20px; font-style: italic;">
                            Tidak ada arsip surat yang tercatat pada tahun kalender {{ $tahun }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Lembar Tanda Tangan -->
        <div class="signatures">
            <div class="sig-block">
                <div>Sekretariat Jemaat,</div>
                <div style="font-weight: bold;">Tata Usaha &amp; Arsiparis</div>
                <div class="sig-space"></div>
                <div style="font-weight: bold; text-decoration: underline;">( Seksi Tata Usaha GBI )</div>
                <div style="font-size: 8.5pt;">Petugas Persuratan Gereja</div>
            </div>

            <div class="sig-block">
                <div>Kupang, {{ now()->translatedFormat('d F Y') }}</div>
                <div style="font-weight: bold;">MENGETAHUI,</div>
                <div style="font-weight: bold;">GEMBALA SIDANG GBI SHEKINAH GLORY</div>
                <div class="sig-space"></div>
                <div style="font-weight: bold; text-decoration: underline;">Pdt. JUSUF LABAGAI</div>
                <div style="font-size: 8.5pt; font-family: monospace;">NIP : 1000702</div>
            </div>
        </div>
    </div>
</body>
</html>
