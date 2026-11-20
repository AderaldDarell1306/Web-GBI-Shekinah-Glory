<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Agenda Arsip Surat GBI Shekinah Glory - Tahun {{ $tahun }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #1a1a1a;
            line-height: 1.35;
            font-size: 10.5pt;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1050px;
            margin: 0 auto;
            padding: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 15pt;
            margin: 0;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header h2 {
            font-size: 17pt;
            margin: 3px 0;
            color: #775a19;
            font-weight: bold;
        }
        .header p {
            margin: 2px 0;
            font-size: 9.5pt;
            color: #444;
        }
        .title-box {
            text-align: center;
            margin-bottom: 20px;
        }
        .title-box h3 {
            font-size: 13pt;
            margin: 0;
            text-decoration: underline;
            text-transform: uppercase;
            font-weight: bold;
        }
        .title-box p {
            margin: 4px 0 0;
            font-size: 10.5pt;
            font-style: italic;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9.5pt;
        }
        table th, table td {
            border: 1px solid #333;
            padding: 6px 8px;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: bold;
        }
        .signatures {
            margin-top: 35px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .sig-block {
            text-align: center;
            width: 250px;
        }
        .sig-space {
            height: 70px;
        }
        .print-btn-bar {
            background: #f0f0f0;
            padding: 12px;
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
        }
        .btn {
            background: #775a19;
            color: #fff;
            padding: 8px 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 10.5pt;
            font-weight: bold;
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
        <button class="btn" onclick="window.print()">Cetak / Simpan PDF</button>
        <button class="btn" style="background: #666; margin-left: 8px;" onclick="window.close()">Tutup</button>
    </div>

    <div class="container">
        <!-- Header / Kop -->
        <div class="header">
            <h1>Gereja Beth-El Indonesia</h1>
            <h2>Jemaat Shekinah Glory Namosain</h2>
            <p>Jln. Yos Sudarso, RT 012 / RW 004, Kelurahan Namosain, Kec. Alak, Kota Kupang, NTT</p>
            <p>Badan Hukum Gereja: SK Dirjen Bimas Kristen Protestan Depag RI No. 41 Th 1972</p>
        </div>

        <!-- Judul Laporan -->
        <div class="title-box">
            <h3>Buku Agenda Korespondensi & Arsip Surat</h3>
            <p>Tahun Kalender: {{ $tahun }} {{ $jenis ? '(' . ucfirst($jenis) . ')' : '' }}</p>
        </div>

        <!-- Tabel Agenda Surat -->
        <table>
            <thead>
                <tr>
                    <th style="width: 35px;">No</th>
                    <th style="width: 75px;">Tanggal</th>
                    <th style="width: 130px;">Nomor Surat</th>
                    <th style="width: 65px;">Jenis</th>
                    <th style="width: 100px;">Kategori</th>
                    <th style="width: 130px;">Pengirim</th>
                    <th style="width: 130px;">Tujuan / Penerima</th>
                    <th>Perihal & Ringkasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratList as $idx => $s)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="text-center">{{ $s->tanggal_surat->format('d/m/Y') }}</td>
                        <td class="font-bold" style="font-size: 8.5pt;">{{ $s->nomor_surat }}</td>
                        <td class="text-center" style="text-transform: uppercase; font-size: 8pt; font-weight: bold;">
                            {{ $s->jenis_surat }}
                        </td>
                        <td>{{ $s->kategori_label }}</td>
                        <td>{{ $s->pengirim }}</td>
                        <td>{{ $s->tujuan_penerima }}</td>
                        <td>
                            <strong>{{ $s->perihal }}</strong>
                            @if($s->ringkasan_isi)
                                <div style="font-size: 8.5pt; color: #555; margin-top: 2px;">{{ $s->ringkasan_isi }}</div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada arsip surat yang tercatat pada tahun kalender {{ $tahun }}.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Lembar Tanda Tangan -->
        <div class="signatures">
            <div class="sig-block">
                <div>Mengetahui,</div>
                <div style="font-weight: bold;">Gembala Sidang</div>
                <div class="sig-space"></div>
                <div style="font-weight: bold; text-decoration: underline;">Pdt. Jonathan L. Manafe</div>
                <div style="font-size: 9.5pt;">Gembala Jemaat</div>
            </div>

            <div class="sig-block">
                <div>Kupang, {{ now()->translatedFormat('d F Y') }}</div>
                <div style="font-weight: bold;">Sekretariat Jemaat</div>
                <div class="sig-space"></div>
                <div style="font-weight: bold; text-decoration: underline;">( .................................................. )</div>
                <div style="font-size: 9.5pt;">Tata Usaha & Arsiparis GBI</div>
            </div>
        </div>
    </div>
</body>
</html>

