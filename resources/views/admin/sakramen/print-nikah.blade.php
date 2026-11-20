<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Sertifikat Pernikahan Kudus - {{ $data->pengantin_pria_nama }} & {{ $data->pengantin_wanita_nama }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page { size: A4 landscape; margin: 8mm; }
        body { margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; background: #f4f3f0; color: #1a1c1a; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        .cert-container { width: 281mm; height: 194mm; margin: auto; background: #ffffff; box-sizing: border-box; position: relative; padding: 12mm 16mm; border: 8px double #775a19; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .inner-border { border: 2px solid #dfc384; height: 100%; box-sizing: border-box; padding: 9mm 14mm; text-align: center; position: relative; }
        .header-synod { font-family: 'Cinzel', serif; font-size: 13pt; letter-spacing: 3px; color: #001428; font-weight: 700; text-transform: uppercase; }
        .header-church { font-family: 'Playfair Display', serif; font-size: 20pt; font-weight: 700; color: #775a19; letter-spacing: 1px; margin: 2px 0; }
        .header-address { font-size: 8pt; color: #555; }
        .cert-title { font-family: 'Cinzel', serif; font-size: 22pt; font-weight: 800; letter-spacing: 3px; color: #001428; margin-top: 14px; margin-bottom: 2px; text-transform: uppercase; }
        .cert-no { font-family: 'Cinzel', serif; font-size: 9.5pt; color: #775a19; font-weight: 700; letter-spacing: 2px; margin-bottom: 10px; }
        .cert-statement { font-size: 10.5pt; color: #444; font-style: italic; margin-bottom: 6px; }
        .couple-box { display: flex; justify-content: center; align-items: center; gap: 20px; margin: 6px 0 12px 0; }
        .couple-name { font-family: 'Playfair Display', serif; font-size: 20pt; font-weight: 700; color: #001428; border-bottom: 2px solid #775a19; padding: 0 16px 4px 16px; }
        .couple-amp { font-family: 'Playfair Display', serif; font-size: 22pt; font-style: italic; color: #775a19; }
        .cert-body { font-size: 10.5pt; line-height: 1.5; color: #222; max-width: 86%; margin: 0 auto 10px auto; }
        .verse-box { font-size: 8.5pt; font-style: italic; color: #555; max-width: 80%; margin: 0 auto 16px auto; padding: 4px 16px; border-left: 2px solid #775a19; border-right: 2px solid #775a19; }
        .signatures { display: flex; justify-content: space-around; align-items: flex-end; margin-top: 6px; }
        .sig-block { text-align: center; width: 180px; }
        .sig-space { height: 42px; }
        .sig-name { font-weight: 700; font-size: 10.5pt; color: #001428; border-top: 1px solid #1a1c1a; padding-top: 4px; }
        .sig-role { font-size: 8.5pt; color: #775a19; font-weight: 600; text-transform: uppercase; }
        .no-print-bar { background: #001428; color: #fff; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; }
        .btn-print { background: #775a19; color: #fff; border: none; padding: 8px 18px; border-radius: 8px; font-weight: bold; cursor: pointer; }
        @media print { .no-print-bar { display: none !important; } body { background: #fff; } .cert-container { box-shadow: none; border: 8px double #775a19; } }
    </style>
</head>
<body>
    <div class="no-print-bar">
        <span>Pratinjau Sertifikat Pernikahan Kudus • Format Cetak A4 Landscape</span>
        <button onclick="window.print()" class="btn-print">Cetak Sertifikat</button>
    </div>

    <div class="cert-container">
        <div class="inner-border">
            <div class="header-synod">GEREJA BETHEL INDONESIA</div>
            <div class="header-church">GBI SHEKINAH GLORY NAMOSAIN</div>
            <div class="header-address">Jl. Ikan Arwana, RT. 004 RW. 002, Kel. Namosain, Kec. Alak, Kota Kupang, Nusa Tenggara Timur</div>

            <div class="cert-title">PIAGAM PEMBERKATAN PERNIKAHAN KUDUS</div>
            <div class="cert-no">Nomor: {{ $data->no_sertifikat }}</div>

            <div class="cert-statement">Telah diteguhkan dan diberkati dalam satu ikatan Pernikahan Kudus yang tak terpisahkan:</div>

            <div class="couple-box">
                <div class="couple-name">{{ strtoupper($data->pengantin_pria_nama) }}</div>
                <div class="couple-amp">&amp;</div>
                <div class="couple-name">{{ strtoupper($data->pengantin_wanita_nama) }}</div>
            </div>

            <div class="cert-body">
                Putra dari: <strong>Bpk. {{ $data->pengantin_pria_ayah ?: '-' }} &amp; Ibu {{ $data->pengantin_pria_ibu ?: '-' }}</strong><br>
                dengan Putri dari: <strong>Bpk. {{ $data->pengantin_wanita_ayah ?: '-' }} &amp; Ibu {{ $data->pengantin_wanita_ibu ?: '-' }}</strong><br>
                pada hari <strong>{{ $data->tanggal_pemberkatan->translatedFormat('l') }}</strong>, tanggal <strong>{{ $data->tanggal_pemberkatan->translatedFormat('d F Y') }}</strong> bertempat di <strong>{{ $data->tempat_pemberkatan }}</strong>.
            </div>

            <div class="verse-box">
                "Demikianlah mereka bukan lagi dua, melainkan satu. Karena itu, apa yang telah dipersatukan Allah, tidak boleh diceraikan manusia."<br>
                <strong>(Matius 19:6)</strong>
            </div>

            <div class="signatures">
                <div class="sig-block">
                    <div class="sig-space"></div>
                    <div class="sig-name">{{ $data->pengantin_pria_nama }}</div>
                    <div class="sig-role">Mempelai Pria</div>
                </div>

                <div class="sig-block">
                    <div class="sig-space"></div>
                    <div class="sig-name">{{ $data->pengantin_wanita_nama }}</div>
                    <div class="sig-role">Mempelai Wanita</div>
                </div>

                <div class="sig-block">
                    <div style="font-size: 8.5pt; color:#666; margin-bottom: 2px;">Kupang, {{ $data->tanggal_pemberkatan->translatedFormat('d F Y') }}</div>
                    <div class="sig-space"></div>
                    <div class="sig-name">{{ $data->pendeta_pemberkat }}</div>
                    <div class="sig-role">Pendeta Pemberkat</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

