@extends('layouts.public')

@section('title', 'Dokumentasi & Galeri Foto - GBI Shekinah Glory')

@section('content')
<!-- Hero Header -->
<section class="relative bg-primary py-20 text-white overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-fixed/20 text-secondary-fixed text-xs font-bold tracking-wider uppercase">
            ✦ Galeri Momen Kebersamaan ✦
        </span>
        <h1 class="font-serif-display text-3xl sm:text-5xl font-bold tracking-tight">
            Dokumentasi Kegiatan Gereja
        </h1>
        <p class="text-sm sm:text-base text-gray-300 max-w-2xl mx-auto font-light">
            Menyaksikan karya kemuliaan Tuhan melalui persekutuan, ibadah raya, pelayanan kasih, dan kebersamaan keluarga Allah di GBI Shekinah Glory.
        </p>
    </div>
</section>

<!-- Content Gallery Section -->
<section class="py-12 bg-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Filter Kategori Tabs -->
        <div class="flex flex-wrap items-center justify-center gap-2">
            <a href="{{ route('public.dokumentasi') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition shadow-xs {{ empty($kategori) ? 'bg-primary text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' }}">
                Semua Kegiatan
            </a>
            @foreach($kategoriList as $kat)
            <a href="{{ route('public.dokumentasi', ['kategori' => $kat]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition shadow-xs {{ $kategori === $kat ? 'bg-primary text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' }}">
                {{ $kat }}
            </a>
            @endforeach
        </div>

        <!-- Responsive Gallery Grid (Tanpa Card Putih, Hanya Foto + Tanggal & Judul) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($fotoList as $foto)
            <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 aspect-[4/3] flex flex-col justify-between p-4 cursor-pointer bg-gray-900" onclick='openLightbox(@json($foto))'>
                <!-- Background Image -->
                <img src="{{ $foto->foto_url }}" alt="{{ $foto->judul }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-108 transition-transform duration-700 ease-out">
                
                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent group-hover:from-black/90 group-hover:via-black/35 transition-colors duration-300"></div>

                <!-- Top Badges -->
                <div class="relative z-10 flex items-center justify-between">
                    <span class="px-2.5 py-1 rounded-full text-2xs font-bold bg-primary/80 backdrop-blur-md text-white border border-white/10 shadow-xs">
                        {{ $foto->kategori_kegiatan }}
                    </span>
                    <span class="w-7 h-7 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <span class="material-symbols-outlined text-[16px]">zoom_in</span>
                    </span>
                </div>

                <!-- Bottom Info: Tanggal & Judul (Tanpa Deskripsi, Tanpa Card Putih) -->
                <div class="relative z-10 space-y-0.5">
                    <div class="flex items-center gap-1.5 text-secondary-fixed text-2xs font-semibold">
                        <span class="material-symbols-outlined text-[12px]">calendar_today</span>
                        <span>{{ $foto->tanggal_kegiatan->translatedFormat('d F Y') }}</span>
                    </div>
                    <h3 class="font-serif-display text-sm font-bold text-white group-hover:text-secondary-fixed transition-colors line-clamp-2 leading-snug drop-shadow-xs">
                        {{ $foto->judul }}
                    </h3>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center text-gray-400 bg-white rounded-2xl border border-gray-100">
                <span class="material-symbols-outlined text-[48px] text-gray-300 mb-2">image_not_supported</span>
                <p class="text-base font-bold text-gray-700">Belum Ada Foto Pada Kategori Ini</p>
                <p class="text-xs text-gray-400 mt-1">Silakan pilih kategori kegiatan lain di atas.</p>
            </div>
            @endforelse
        </div>

        @if($fotoList->hasPages())
        <div class="pt-6">
            {{ $fotoList->links() }}
        </div>
        @endif
    </div>
</section>

<!-- Lightbox Modal -->
<div id="lightboxModal" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-sm flex items-center justify-center p-4" onclick="closeLightbox()">
    <div class="relative max-w-4xl w-full bg-white rounded-2xl overflow-hidden shadow-2xl" onclick="event.stopPropagation()">
        <button type="button" onclick="closeLightbox()" class="absolute top-3 right-3 z-10 w-9 h-9 rounded-full bg-black/60 hover:bg-black text-white flex items-center justify-center transition">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>

        <div class="max-h-[70vh] bg-black flex items-center justify-center overflow-hidden">
            <img id="lightboxImg" src="" alt="" class="max-h-[70vh] w-auto max-w-full object-contain">
        </div>

        <div class="p-5 bg-white space-y-1">
            <div class="flex items-center gap-2">
                <span id="lightboxKategori" class="px-2.5 py-0.5 rounded-full text-2xs font-bold bg-primary text-white"></span>
                <span id="lightboxTanggal" class="text-xs text-gray-400 font-semibold"></span>
            </div>
            <h3 id="lightboxJudul" class="font-serif-display text-lg sm:text-xl font-bold text-gray-900"></h3>
        </div>
    </div>
</div>

<script>
function openLightbox(foto) {
    document.getElementById('lightboxImg').src = foto.foto_url;
    document.getElementById('lightboxJudul').textContent = foto.judul;
    document.getElementById('lightboxKategori').textContent = foto.kategori_kegiatan;
    
    // Format tanggal
    if (foto.tanggal_kegiatan) {
        const d = new Date(foto.tanggal_kegiatan);
        document.getElementById('lightboxTanggal').textContent = d.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
    } else {
        document.getElementById('lightboxTanggal').textContent = '';
    }

    document.getElementById('lightboxModal').classList.remove('hidden');
}

function closeLightbox() {
    document.getElementById('lightboxModal').classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
});
</script>
@endsection
