<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $data->jenis_surat_label }} - {{ $data->nama_jemaat }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page { size: A4 portrait; margin: 15mm 20mm; }
        body { margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; background: #f4f3f0; color: #111; font-size: 11pt; line-height: 1.6; }
        .letter-sheet { width: 210mm; min-height: 297mm; margin: 10px auto; background: #fff; padding: 20mm; box-sizing: border-box; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .kop-table { width: 100%; border-bottom: 3px double #111; padding-bottom: 12px; margin-bottom: 20px; }
        .kop-logo { width: 75px; text-align: center; vertical-align: middle; }
        .kop-church { text-align: center; }
        .kop-synod { font-size: 11pt; font-weight: 700; letter-spacing: 1.5px; color: #001428; text-transform: uppercase; }
        .kop-name { font-size: 18pt; font-weight: 800; color: #775a19; margin: 2px 0; }
        .kop-address { font-size: 8.5pt; color: #444; }
        .letter-meta { margin-bottom: 25px; }
        .letter-title { text-align: center; font-size: 13pt; font-weight: 700; text-decoration: underline; text-transform: uppercase; margin: 20px 0 4px 0; }
        .letter-no { text-align: center; font-size: 10pt; color: #444; margin-bottom: 25px; }
        .field-table { width: 100%; margin: 15px 0 20px 0; }
        .field-table td { padding: 4px 0; vertical-align: top; }
        .field-label { width: 180px; font-weight: 600; color: #333; }
        .signatures { margin-top: 50px; display: flex; justify-content: flex-end; }
        .sig-block { text-align: center; width: 240px; }
        .sig-space { height: 65px; }
        .no-print-bar { background: #001428; color: #fff; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; }
        .btn-print { background: #775a19; color: #fff; border: none; padding: 8px 18px; border-radius: 8px; font-weight: bold; cursor: pointer; }
        @media print { .no-print-bar { display: none !important; } body { background: #fff; } .letter-sheet { box-shadow: none; margin: 0; padding: 10mm; } }
    </style>
</head>
<body>
    <div class="no-print-bar">
        <span>Pratinjau Surat Resmi Pastoral • Format Cetak A4 Portrait</span>
        <button onclick="window.print()" class="btn-print">Cetak Surat Sekarang</button>
    </div>

    <div class="letter-sheet">
        <!-- KOP SURAT RESMI -->
        <table class="kop-table">
            <tr>
                <td class="kop-church">
                    <div class="kop-synod">GEREJA BETHEL INDONESIA</div>
                    <div class="kop-name">GBI SHEKINAH GLORY NAMOSAIN</div>
                    <div class="kop-address">
                        Badan Hukum Gereja: SK Dirjen Bimas Kristen Protestan Depag RI No. 41 Tahun 1970<br>
                        Jl. Ikan Arwana, RT. 004 RW. 002, Kelurahan Namosain, Kecamatan Alak, Kota Kupang - NTT
                    </div>
                </td>
            </tr>
        </table>

        <!-- NOMOR & JUDUL SURAT -->
        <div class="letter-title">{{ strtoupper($data->jenis_surat_label) }}</div>
        <div class="letter-no">Nomor: {{ $data->no_surat }}</div>

        <!-- ISI SURAT -->
        <p>Salam sejahtera dalam kasih Tuhan Yesus Kristus,</p>

        <p>Gembala Jemaat dan Majelis GBI Shekinah Glory Namosain Kota Kupang dengan ini menerangkan bahwa:</p>

        <table class="field-table">
            <tr>
                <td class="field-label">Nama Lengkap</td>
                <td width="15">:</td>
                <td><strong>{{ strtoupper($data->nama_jemaat) }}</strong></td>
            </tr>
            @if($data->jemaat)
            <tr>
                <td class="field-label">Nomor Induk Jemaat (NIJ)</td>
                <td>:</td>
                <td>{{ $data->jemaat->nomor_induk }}</td>
            </tr>
            <tr>
                <td class="field-label">Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td>{{ $data->jemaat->ttl ?: '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $data->jemaat->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <td class="field-label">Alamat / Wilayah</td>
                <td>:</td>
                <td>{{ $data->jemaat->alamat ?: ($data->jemaat->wilayah ? $data->jemaat->wilayah->nama_wilayah : '-') }}</td>
            </tr>
            @endif
            @if(!empty($data->tujuan_gereja_instansi))
            <tr>
                <td class="field-label">Tujuan Gereja / Instansi</td>
                <td>:</td>
                <td><strong>{{ $data->tujuan_gereja_instansi }}</strong></td>
            </tr>
            @endif
        </table>

        @if($data->jenis_surat === 'surat_pindah')
        <p>
            Adalah benar anggota jemaat yang terdaftar pada GBI Shekinah Glory Namosain. Bersama surat ini, kami menyerahkan penggembalaan yang bersangkutan kepada <strong>{{ $data->tujuan_gereja_instansi ?: 'Gereja setempat' }}</strong> sehubungan dengan kepindahan tempat tinggal/domisili. Kami mendoakan kiranya Tuhan Yesus senantiasa memberkati dan menyertai pertumbuhan iman rohaninya di tempat pelayanan yang baru.
        </p>
        @else
        <p>
            Adalah benar yang bersangkutan merupakan anggota jemaat dalam persekutuan GBI Shekinah Glory Namosain. Surat ini diterbitkan sebagai keterangan resmi untuk keperluan: <strong>{{ $data->keperluan_alasan ?: 'Administrasi dan Penggembalaan Jemaat' }}</strong>.
        </p>
        @endif

        <p>Demikian surat keterangan ini kami terbitkan dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>

        <!-- TANDA TANGAN -->
        <div class="signatures">
            <div class="sig-block">
                <div>Kupang, {{ $data->tanggal_surat->translatedFormat('d F Y') }}</div>
                <div style="font-weight: 600; margin-top: 4px;">Gembala Jemaat,</div>
                <div class="sig-space"></div>
                <div style="font-weight: 700; text-decoration: underline;">{{ $data->penandatangan }}</div>
                <div style="font-size: 9pt; color: #555;">{{ $data->jabatan_penandatangan }}</div>
            </div>
        </div>
    </div>
</body>
</html>

