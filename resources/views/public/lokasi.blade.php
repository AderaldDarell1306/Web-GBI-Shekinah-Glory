@extends('layouts.public')

@section('title', 'Lokasi & Petunjuk Arah - GBI Shekinah Glory')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
        <span class="text-xs font-bold uppercase tracking-widest text-secondary mb-2 block">Kunjungi Gereja Kami</span>
        <h1 class="font-serif-display text-4xl sm:text-5xl font-bold text-primary mb-4">Lokasi & Petunjuk Arah</h1>
        <p class="text-base text-on-surface-variant">Pintu rumah Tuhan selalu terbuka lebar menyambut kehadiran Anda dan keluarga untuk beribadah secara tatap muka (onsite).</p>
        <div class="w-20 h-1 bg-secondary mx-auto mt-6 rounded-full"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start mb-16">
        <!-- Peta Interaktif & Keterangan Lokasi -->
        <div class="lg:col-span-7 bg-white rounded-2xl overflow-hidden shadow-md border border-outline-variant/30" data-aos="fade-right" data-aos-duration="850">
            <div class="w-full h-80 sm:h-96 relative bg-surface-container">
                <iframe 
                    id="gmap_lokasi"
                    src="{{ $contents['google_maps_embed'] ?? 'https://maps.google.com/maps?q=-10.1728964,123.5550841(GBI+SHEKINAH+GLORY+NAMOSAIN)&hl=id&z=17&output=embed' }}" 
                    class="w-full h-full border-0" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Peta Lokasi GBI Shekinah Glory Namosain">
                </iframe>
                <button type="button" onclick="document.getElementById('gmap_lokasi').src=document.getElementById('gmap_lokasi').src" class="absolute bottom-3 right-3 px-3 py-1.5 rounded-lg bg-white/90 hover:bg-white text-primary text-xs font-bold shadow-md backdrop-blur-xs flex items-center gap-1 border border-outline-variant/40" title="Muat Ulang Peta jika koneksi jaringan berubah">
                    <span class="material-symbols-outlined text-[15px]">refresh</span>
                    <span>Muat Ulang Peta</span>
                </button>
            </div>

            <div class="p-6 sm:p-8 space-y-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="font-serif-display text-2xl font-bold text-primary">GBI Shekinah Glory Namosain</h3>
                        <p class="text-xs text-secondary font-semibold uppercase tracking-wider mt-0.5">Namosain, Alak, Kota Kupang, Nusa Tenggara Timur</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-secondary-container/60 text-secondary text-xs font-bold shrink-0">
                        Kupang, NTT
                    </span>
                </div>

                <p class="text-base text-on-surface-variant">{{ $contents['alamat_lengkap'] ?? 'Jl. Ikan Arwana, RT. 004 RW. 002, Kelurahan Namosain, Kecamatan Alak, Kota Kupang, NTT' }}</p>

                @if(!empty($contents['patokan_lokasi']) && $contents['patokan_lokasi'] !== '.')
                <div class="p-4 rounded-xl bg-surface-container-low flex items-start gap-3 text-sm text-secondary">
                    <span class="material-symbols-outlined shrink-0 text-[20px]">info</span>
                    <span><strong>Patokan Navigasi:</strong> {{ $contents['patokan_lokasi'] }}</span>
                </div>
                @endif

                <a href="{{ $contents['google_maps_url'] ?? 'https://maps.app.goo.gl/uuA2zD3FbzX676JRA' }}" target="_blank" rel="noopener noreferrer" class="w-full py-3.5 rounded-xl bg-secondary text-white font-bold text-sm flex items-center justify-center gap-2 hover:bg-secondary/90 transition shadow-sm transform active:scale-95">
                    <span class="material-symbols-outlined text-[20px]">directions</span>
                    <span>Buka Rute di Google Maps / Waze</span>
                </a>
            </div>
        </div>

        <!-- Fasilitas & Aksesibilitas -->
        <div class="lg:col-span-5 space-y-6" data-aos="fade-left" data-aos-duration="850">
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-outline-variant/30 space-y-4">
                <h3 class="font-serif-display text-xl font-bold text-primary">Fasilitas Gereja</h3>
                <div class="space-y-4 text-sm text-on-surface-variant">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-secondary-container text-secondary flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[18px]">local_parking</span>
                        </div>
                        <div>
                            <span class="font-bold text-primary block">Area Parkir Luas</span>
                            <span>Tersedia parkir mobil & motor beraspal dengan pengamanan 24 jam di basement dan halaman gedung.</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-secondary-container text-secondary flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[18px]">accessible</span>
                        </div>
                        <div>
                            <span class="font-bold text-primary block">Ramah Disabilitas & Lansia</span>
                            <span>Akses ramp kursi roda, lift menuju lantai utama, dan toilet khusus disabilitas tersedia di setiap lantai.</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-secondary-container text-secondary flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[18px]">child_care</span>
                        </div>
                        <div>
                            <span class="font-bold text-primary block">Ruang Ibu & Bayi (Nursery)</span>
                            <span>Ruang ber-AC yang nyaman dengan fasilitas ganti popok dan monitor audio-visual ibadah internal.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hubungi Callout -->
            <div class="bg-surface-container-low rounded-2xl p-6 border border-outline-variant/30">
                <h4 class="font-bold text-primary mb-2">Butuh Bantuan Navigasi / Penjemputan?</h4>
                <p class="text-xs text-on-surface-variant mb-4">Bagi jemaat lansia atau yang membutuhkan informasi rute transportasi umum menuju gedung gereja, silakan hubungi tim sekretariat.</p>
                <a href="https://wa.me/6281288997700" target="_blank" class="inline-flex items-center gap-2 text-sm font-bold text-secondary hover:underline">
                    <span>Hubungi WhatsApp Sekretariat</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
