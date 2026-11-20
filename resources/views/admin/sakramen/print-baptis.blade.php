<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Sertifikat Baptisan Air - {{ $data->nama_lengkap }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;800&family=Great+Vibes&family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f4f3f0;
            color: #1a1c1a;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .cert-container {
            width: 281mm;
            height: 194mm;
            margin: auto;
            background: #ffffff;
            box-sizing: border-box;
            position: relative;
            padding: 12mm 16mm;
            border: 8px double #775a19;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .inner-border {
            border: 2px solid #dfc384;
            height: 100%;
            box-sizing: border-box;
            padding: 10mm 14mm;
            text-align: center;
            position: relative;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.04;
            font-size: 260px;
            font-family: 'Cinzel', serif;
            color: #001428;
            pointer-events-none;
            user-select: none;
        }
        .header-synod {
            font-family: 'Cinzel', serif;
            font-size: 13pt;
            letter-spacing: 3px;
            color: #001428;
            font-weight: 700;
            text-transform: uppercase;
        }
        .header-church {
            font-family: 'Playfair Display', serif;
            font-size: 20pt;
            font-weight: 700;
            color: #775a19;
            letter-spacing: 1px;
            margin: 2px 0;
        }
        .header-address {
            font-size: 8pt;
            color: #555;
            letter-spacing: 0.5px;
        }
        .cert-title {
            font-family: 'Cinzel', serif;
            font-size: 24pt;
            font-weight: 800;
            letter-spacing: 4px;
            color: #001428;
            margin-top: 18px;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .cert-no {
            font-family: 'Cinzel', serif;
            font-size: 10pt;
            color: #775a19;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 12px;
        }
        .cert-statement {
            font-size: 11pt;
            color: #444;
            font-style: italic;
            margin-bottom: 10px;
        }
        .cert-name {
            font-family: 'Playfair Display', serif;
            font-size: 26pt;
            font-weight: 700;
            color: #001428;
            border-bottom: 2px solid #775a19;
            display: inline-block;
            padding: 0 40px 4px 40px;
            margin: 4px 0 14px 0;
            letter-spacing: 1px;
        }
        .cert-body {
            font-size: 11pt;
            line-height: 1.6;
            color: #222;
            max-width: 85%;
            margin: 0 auto 16px auto;
        }
        .verse-box {
            font-size: 9pt;
            font-style: italic;
            color: #555;
            max-width: 78%;
            margin: 0 auto 24px auto;
            padding: 6px 16px;
            border-left: 2px solid #775a19;
            border-right: 2px solid #775a19;
        }
        .signatures {
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
            margin-top: 10px;
        }
        .sig-block {
            text-align: center;
            width: 220px;
        }
        .sig-space {
            height: 48px;
        }
        .sig-name {
            font-weight: 700;
            font-size: 11pt;
            color: #001428;
            border-top: 1px solid #1a1c1a;
            padding-top: 4px;
        }
        .sig-role {
            font-size: 9pt;
            color: #775a19;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .no-print-bar {
            background: #001428;
            color: #fff;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }
        .btn-print {
            background: #775a19;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }
        @media print {
            .no-print-bar { display: none !important; }
            body { background: #fff; }
            .cert-container { box-shadow: none; border: 8px double #775a19; }
        }
    </style>
</head>
<body>
    <div class="no-print-bar">
        <span>Pratinjau Sertifikat Resmi Baptisan Air • Format Cetak A4 Landscape</span>
        <div>
            <button onclick="window.print()" class="btn-print">Cetak Sertifikat Sekarang</button>
        </div>
    </div>

    <div class="cert-container">
        <div class="inner-border">
            <div class="watermark">✝</div>

            <div class="header-synod">GEREJA BETHEL INDONESIA</div>
            <div class="header-church">GBI SHEKINAH GLORY NAMOSAIN</div>
            <div class="header-address">Jl. Ikan Arwana, RT. 004 RW. 002, Kel. Namosain, Kec. Alak, Kota Kupang, Nusa Tenggara Timur</div>

            <div class="cert-title">SURAT BAPTISAN AIR</div>
            <div class="cert-no">Nomor: {{ $data->no_sertifikat }}</div>

            <div class="cert-statement">Dengan ini menerangkan dan menyaksikan bahwa:</div>

            <div class="cert-name">{{ strtoupper($data->nama_lengkap) }}</div>

            <div class="cert-body">
                Lahir di <strong>{{ $data->tempat_lahir ?? 'Kupang' }}</strong>, pada tanggal <strong>{{ $data->tanggal_lahir ? $data->tanggal_lahir->translatedFormat('d F Y') : '-' }}</strong><br>
                Telah menerima Sakramen <strong>Baptisan Air Selam</strong> dalam Nama <strong>Allah Bapa, Anak-Nya Yesus Kristus, dan Roh Kudus</strong><br>
                pada hari <strong>{{ $data->tanggal_baptis->translatedFormat('l') }}</strong>, tanggal <strong>{{ $data->tanggal_baptis->translatedFormat('d F Y') }}</strong> bertempat di <strong>{{ $data->tempat_baptis }}</strong>.
            </div>

            <div class="verse-box">
                "Sebab kamu telah dikuburkan bersama-sama dengan Dia dalam baptisan, dan dalam Dia kamu turut dibangkitkan juga oleh kepercayaanmu kepada kerja kuasa Allah, yang telah membangkitkan Dia dari orang mati."<br>
                <strong>(Kolose 2:12)</strong>
            </div>

            <div class="signatures">
                <div class="sig-block">
                    <div class="sig-space"></div>
                    <div class="sig-name">{{ $data->saksi ?: 'Sekretaris Jemaat' }}</div>
                    <div class="sig-role">Saksi Baptisan</div>
                </div>

                <div class="sig-block">
                    <div style="font-size: 9pt; color:#666; margin-bottom: 2px;">Kupang, {{ $data->tanggal_baptis->translatedFormat('d F Y') }}</div>
                    <div class="sig-space"></div>
                    <div class="sig-name">{{ $data->pendeta_pelayan }}</div>
                    <div class="sig-role">Pendeta yang Membaptis</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

