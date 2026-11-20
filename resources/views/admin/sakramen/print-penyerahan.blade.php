<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Sertifikat Penyerahan Anak - {{ $data->nama_anak }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page { size: A4 landscape; margin: 8mm; }
        body { margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; background: #f4f3f0; color: #1a1c1a; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        .cert-container { width: 281mm; height: 194mm; margin: auto; background: #ffffff; box-sizing: border-box; position: relative; padding: 12mm 16mm; border: 8px double #775a19; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .inner-border { border: 2px solid #dfc384; height: 100%; box-sizing: border-box; padding: 10mm 14mm; text-align: center; position: relative; }
        .header-synod { font-family: 'Cinzel', serif; font-size: 13pt; letter-spacing: 3px; color: #001428; font-weight: 700; text-transform: uppercase; }
        .header-church { font-family: 'Playfair Display', serif; font-size: 20pt; font-weight: 700; color: #775a19; letter-spacing: 1px; margin: 2px 0; }
        .header-address { font-size: 8pt; color: #555; }
        .cert-title { font-family: 'Cinzel', serif; font-size: 23pt; font-weight: 800; letter-spacing: 3px; color: #001428; margin-top: 16px; margin-bottom: 4px; text-transform: uppercase; }
        .cert-no { font-family: 'Cinzel', serif; font-size: 10pt; color: #775a19; font-weight: 700; letter-spacing: 2px; margin-bottom: 12px; }
        .cert-statement { font-size: 11pt; color: #444; font-style: italic; margin-bottom: 8px; }
        .cert-name { font-family: 'Playfair Display', serif; font-size: 25pt; font-weight: 700; color: #001428; border-bottom: 2px solid #775a19; display: inline-block; padding: 0 36px 4px 36px; margin: 4px 0 12px 0; }
        .cert-body { font-size: 11pt; line-height: 1.6; color: #222; max-width: 85%; margin: 0 auto 14px auto; }
        .verse-box { font-size: 9pt; font-style: italic; color: #555; max-width: 78%; margin: 0 auto 20px auto; padding: 6px 16px; border-left: 2px solid #775a19; border-right: 2px solid #775a19; }
        .signatures { display: flex; justify-content: space-around; align-items: flex-end; margin-top: 10px; }
        .sig-block { text-align: center; width: 220px; }
        .sig-space { height: 46px; }
        .sig-name { font-weight: 700; font-size: 11pt; color: #001428; border-top: 1px solid #1a1c1a; padding-top: 4px; }
        .sig-role { font-size: 9pt; color: #775a19; font-weight: 600; text-transform: uppercase; }
        .no-print-bar { background: #001428; color: #fff; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; }
        .btn-print { background: #775a19; color: #fff; border: none; padding: 8px 18px; border-radius: 8px; font-weight: bold; cursor: pointer; }
        @media print { .no-print-bar { display: none !important; } body { background: #fff; } .cert-container { box-shadow: none; border: 8px double #775a19; } }
    </style>
</head>
<body>
    <div class="no-print-bar">
        <span>Pratinjau Sertifikat Penyerahan Anak • Format Cetak A4 Landscape</span>
        <button onclick="window.print()" class="btn-print">Cetak Sertifikat</button>
    </div>

    <div class="cert-container">
        <div class="inner-border">
            <div class="header-synod">GEREJA BETHEL INDONESIA</div>
            <div class="header-church">GBI SHEKINAH GLORY NAMOSAIN</div>
            <div class="header-address">Jl. Ikan Arwana, RT. 004 RW. 002, Kel. Namosain, Kec. Alak, Kota Kupang, Nusa Tenggara Timur</div>

            <div class="cert-title">SURAT PENYERAHAN ANAK</div>
            <div class="cert-no">Nomor: {{ $data->no_sertifikat }}</div>

            <div class="cert-statement">Dengan ini menerangkan dan menyaksikan bahwa anak:</div>

            <div class="cert-name">{{ strtoupper($data->nama_anak) }}</div>

            <div class="cert-body">
                Jenis Kelamin: <strong>{{ $data->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</strong> • Lahir di <strong>{{ $data->tempat_lahir ?? 'Kupang' }}</strong>, tanggal <strong>{{ $data->tanggal_lahir ? $data->tanggal_lahir->translatedFormat('d F Y') : '-' }}</strong><br>
                Anak dari Orang Tua Terkasih: <strong>Bpk. {{ $data->nama_ayah }}</strong> &amp; <strong>Ibu {{ $data->nama_ibu }}</strong><br>
                Telah ditahbiskan dan diserahkan ke dalam hadirat serta pemeliharaan Tuhan Yang Maha Esa<br>
                pada hari <strong>{{ $data->tanggal_penyerahan->translatedFormat('l') }}</strong>, tanggal <strong>{{ $data->tanggal_penyerahan->translatedFormat('d F Y') }}</strong> di <strong>{{ $data->tempat_penyerahan }}</strong>.
            </div>

            <div class="verse-box">
                "Biarkan anak-anak itu datang kepada-Ku, jangan menghalang-halangi mereka, sebab orang-orang yang seperti itulah yang empunya Kerajaan Allah."<br>
                <strong>(Markus 10:14)</strong>
            </div>

            <div class="signatures">
                <div class="sig-block">
                    <div class="sig-space"></div>
                    <div class="sig-name">{{ $data->nama_ayah }} / {{ $data->nama_ibu }}</div>
                    <div class="sig-role">Orang Tua Anak</div>
                </div>

                <div class="sig-block">
                    <div style="font-size: 9pt; color:#666; margin-bottom: 2px;">Kupang, {{ $data->tanggal_penyerahan->translatedFormat('d F Y') }}</div>
                    <div class="sig-space"></div>
                    <div class="sig-name">{{ $data->pendeta_pelayan }}</div>
                    <div class="sig-role">Pendeta Pelayan</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

