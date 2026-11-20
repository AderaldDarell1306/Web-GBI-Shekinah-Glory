<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan GBI Shekinah Glory - {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #1a1a1a;
            line-height: 1.35;
            font-size: 11pt;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
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
            letter-spacing: 1px;
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
            margin-bottom: 18px;
            font-size: 10pt;
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
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .bg-light {
            background-color: #fafafa;
        }
        .summary-card {
            border: 1px solid #444;
            padding: 12px;
            margin-bottom: 20px;
            background: #fdfdfd;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            font-size: 10.5pt;
        }
        .signatures {
            margin-top: 40px;
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
        .btn:hover {
            background: #5c4513;
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
            <h3>Laporan Arus Kas & Keuangan Gereja</h3>
            <p>Periode: {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</p>
        </div>

        <!-- Ringkasan Eksekutif -->
        <div class="summary-card">
            <div class="summary-row">
                <span>Saldo Kas Awal (Per 01 {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}):</span>
                <span class="font-bold">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row" style="color: #0d6832;">
                <span>Total Penerimaan / Pemasukan:</span>
                <span class="font-bold">+ Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row" style="color: #9c1515;">
                <span>Total Pengeluaran / Belanja:</span>
                <span class="font-bold">- Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
            </div>
            <hr style="border: 0; border-top: 1px dashed #666; margin: 6px 0;">
            <div class="summary-row">
                <span>Surplus / (Defisit) Kas Bulan Berjalan:</span>
                <span class="font-bold {{ $surplusDefisit >= 0 ? '' : 'text-danger' }}">
                    Rp {{ number_format($surplusDefisit, 0, ',', '.') }}
                </span>
            </div>
            <div class="summary-row" style="font-size: 11.5pt; font-weight: bold; background: #eee; padding: 4px 6px; margin-top: 4px;">
                <span>SALDO AKHIR KAS (Per Akhir {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}):</span>
                <span>Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Rekap Pos Anggaran -->
        <div style="display: flex; gap: 15px; margin-bottom: 20px;">
            <!-- Pos Pemasukan -->
            <div style="flex: 1;">
                <div style="font-weight: bold; font-size: 10pt; margin-bottom: 4px; text-transform: uppercase;">A. Rincian Pemasukan Per Pos</div>
                <table>
                    <thead>
                        <tr>
                            <th>Pos Pemasukan</th>
                            <th class="text-right">Jumlah (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemasukanPerPos as $kategori => $items)
                            <tr>
                                <td>{{ ucwords(str_replace('_', ' ', $kategori)) }}</td>
                                <td class="text-right">{{ number_format($items->sum('nominal'), 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center">Tidak ada pemasukan</td></tr>
                        @endforelse
                        <tr class="font-bold bg-light">
                            <td>Total Pemasukan</td>
                            <td class="text-right">{{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pos Pengeluaran -->
            <div style="flex: 1;">
                <div style="font-weight: bold; font-size: 10pt; margin-bottom: 4px; text-transform: uppercase;">B. Rincian Pengeluaran Per Pos</div>
                <table>
                    <thead>
                        <tr>
                            <th>Pos Pengeluaran</th>
                            <th class="text-right">Jumlah (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengeluaranPerPos as $kategori => $items)
                            <tr>
                                <td>{{ ucwords(str_replace('_', ' ', $kategori)) }}</td>
                                <td class="text-right">{{ number_format($items->sum('nominal'), 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center">Tidak ada pengeluaran</td></tr>
                        @endforelse
                        <tr class="font-bold bg-light">
                            <td>Total Pengeluaran</td>
                            <td class="text-right">{{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rincian Buku Kas -->
        <div style="font-weight: bold; font-size: 10.5pt; margin-bottom: 4px; text-transform: uppercase;">C. Rincian Mutasi Buku Kas</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 75px;">Tanggal</th>
                    <th style="width: 85px;">Kode / Bukti</th>
                    <th>Uraian / Keterangan</th>
                    <th style="width: 100px;" class="text-right">Masuk (Rp)</th>
                    <th style="width: 100px;" class="text-right">Keluar (Rp)</th>
                    <th style="width: 110px;" class="text-right">Saldo (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-light font-bold">
                    <td class="text-center">01/{{ $bulan }}/{{ $tahun }}</td>
                    <td class="text-center">-</td>
                    <td><em>Saldo Kas Awal</em></td>
                    <td class="text-right">-</td>
                    <td class="text-right">-</td>
                    <td class="text-right">{{ number_format($saldoAwal, 0, ',', '.') }}</td>
                </tr>
                @php $currentSaldo = $saldoAwal; @endphp
                @forelse($transaksiList as $row)
                    @php
                        if ($row->jenis == 'pemasukan') {
                            $currentSaldo += $row->nominal;
                        } else {
                            $currentSaldo -= $row->nominal;
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $row->tanggal_transaksi->format('d/m/Y') }}</td>
                        <td class="text-center" style="font-size: 8.5pt;">{{ $row->no_bukti_nota ?? $row->kode_transaksi }}</td>
                        <td>
                            <strong>[{{ $row->kategori_label }}]</strong> {{ $row->sumber_penerima ? $row->sumber_penerima . ' - ' : '' }}{{ $row->keterangan }}
                        </td>
                        <td class="text-right">{{ $row->jenis == 'pemasukan' ? number_format($row->nominal, 0, ',', '.') : '-' }}</td>
                        <td class="text-right">{{ $row->jenis == 'pengeluaran' ? number_format($row->nominal, 0, ',', '.') : '-' }}</td>
                        <td class="text-right">{{ number_format($currentSaldo, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada transaksi pada bulan ini.</td>
                    </tr>
                @endforelse
                <tr class="font-bold bg-light">
                    <td colspan="3" class="text-center">TOTAL MUTASI BULAN INI</td>
                    <td class="text-right">{{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($saldoAkhir, 0, ',', '.') }}</td>
                </tr>
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
                <div style="font-weight: bold;">Bendahara Jemaat</div>
                <div class="sig-space"></div>
                <div style="font-weight: bold; text-decoration: underline;">( .................................................. )</div>
                <div style="font-size: 9.5pt;">Pengurus Keuangan GBI</div>
            </div>
        </div>
    </div>
</body>
</html>

