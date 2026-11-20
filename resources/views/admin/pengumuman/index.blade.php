@extends('layouts.admin')

@section('title', 'Manajemen Pengumuman & Berita Gereja')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-primary via-primary-container to-primary rounded-2xl p-6 sm:p-8 text-white shadow-md flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-secondary-fixed text-xs font-bold mb-2">
                <span class="material-symbols-outlined text-[16px]">campaign</span>
                <span>Pusat Informasi &amp; Berita Gereja</span>
            </div>
            <h1 class="font-serif-display text-2xl sm:text-3xl font-bold">Pengumuman &amp; Berita Gereja</h1>
            <p class="text-xs sm:text-sm text-white/80 mt-1 max-w-2xl leading-relaxed">
                Kelola pengumuman resmi ibadah khusus, kegiatan komisi (Kaum Pria, Wanita, Pemuda, Anak/PPA), aksi sosial, dan informasi penting bagi jemaat.
            </p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('admin.warta.index') }}" class="px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition flex items-center gap-1.5 border border-white/20">
                <span class="material-symbols-outlined text-[17px]">newspaper</span>
                <span>Buka Warta Ibadah</span>
            </a>
            <a href="{{ route('admin.pengumuman.create') }}" class="px-4 py-2.5 rounded-xl bg-secondary text-white font-bold text-xs hover:bg-secondary/90 transition shadow-md flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>+ Buat Pengumuman Baru</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Metric Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-white border border-outline-variant/30 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-outline uppercase tracking-wider block">Total Pengumuman</span>
                <span class="font-serif-display text-2xl font-bold text-primary">{{ $stats['total'] }}</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[20px]">campaign</span>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white border border-outline-variant/30 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">Aktif Tayang</span>
                <span class="font-serif-display text-2xl font-bold text-emerald-800">{{ $stats['aktif'] }}</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-700">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white border border-outline-variant/30 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider block">Disematkan (Pinned)</span>
                <span class="font-serif-display text-2xl font-bold text-amber-800">{{ $stats['pinned'] }}</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-700">
                <span class="material-symbols-outlined text-[20px]">push_pin</span>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white border border-outline-variant/30 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-outline uppercase tracking-wider block">Diarsipkan</span>
                <span class="font-serif-display text-2xl font-bold text-outline">{{ $stats['arsip'] }}</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-outline">
                <span class="material-symbols-outlined text-[20px]">archive</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 border border-outline-variant/30 shadow-xs">
        <form method="GET" action="{{ route('admin.pengumuman.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-3">
                <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-1">Kategori</label>
                <select name="kategori" class="w-full text-xs font-semibold rounded-xl border-outline-variant focus:border-secondary py-2">
                    <option value="">Semua Kategori</option>
                    <option value="penting" {{ $kategori == 'penting' ? 'selected' : '' }}>Penting / Khusus</option>
                    <option value="ibadah" {{ $kategori == 'ibadah' ? 'selected' : '' }}>Jadwal & Ibadah</option>
                    <option value="kegiatan" {{ $kategori == 'kegiatan' ? 'selected' : '' }}>Kegiatan & Acara</option>
                    <option value="pelayanan" {{ $kategori == 'pelayanan' ? 'selected' : '' }}>Pelayanan & Komisi</option>
                    <option value="sosial" {{ $kategori == 'sosial' ? 'selected' : '' }}>Aksi Sosial & PPA</option>
                    <option value="umum" {{ $kategori == 'umum' ? 'selected' : '' }}>Umum</option>
                </select>
            </div>

            <div class="sm:col-span-3">
                <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-1">Status</label>
                <select name="status" class="w-full text-xs font-semibold rounded-xl border-outline-variant focus:border-secondary py-2">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ $status == 'aktif' ? 'selected' : '' }}>Aktif (Tayang)</option>
                    <option value="arsip" {{ $status == 'arsip' ? 'selected' : '' }}>Arsip (Selesai)</option>
                </select>
            </div>

            <div class="sm:col-span-4">
                <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-1">Cari Kata Kunci</label>
                <input type="text" name="q" value="{{ $q }}" placeholder="Judul atau isi pengumuman..." class="w-full text-xs rounded-xl border-outline-variant focus:border-secondary py-2">
            </div>

            <div class="sm:col-span-2 flex items-center gap-1.5">
                <button type="submit" class="w-full py-2 px-3 text-xs font-bold text-white bg-primary rounded-xl hover:bg-primary-container transition shadow-xs flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-[15px]">filter_alt</span>
                    <span>Filter</span>
                </button>
                <a href="{{ route('admin.pengumuman.index') }}" class="py-2 px-2.5 text-xs font-bold text-outline bg-surface-container rounded-xl hover:bg-surface-container-high transition" title="Reset">
                    <span class="material-symbols-outlined text-[15px]">restart_alt</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($pengumumanList as $item)
        <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-all flex flex-col justify-between overflow-hidden {{ $item->is_pinned ? 'ring-2 ring-amber-400' : '' }}">
            <div>
                <!-- Visual / Flyer Thumbnail -->
                <div class="relative w-full h-40 bg-surface-container overflow-hidden">
                    <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                    <!-- Category & Pinned Badges -->
                    <div class="absolute top-3 left-3 flex items-center gap-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border shadow-xs {{ $item->kategori_badge['bg'] }}">
                            {{ $item->kategori_badge['label'] }}
                        </span>
                        @if($item->is_pinned)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-400 text-gray-900 shadow-xs flex items-center gap-0.5">
                            <span class="material-symbols-outlined text-[12px]">push_pin</span>
                            <span>Pinned</span>
                        </span>
                        @endif
                    </div>

                    <!-- Status -->
                    <div class="absolute top-3 right-3">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $item->status === 'aktif' ? 'bg-emerald-600 text-white' : 'bg-gray-600 text-white' }}">
                            {{ $item->status }}
                        </span>
                    </div>

                    <div class="absolute bottom-2 left-3 right-3 text-white text-2xs font-mono font-semibold">
                        {{ $item->tanggal->translatedFormat('l, d F Y') }}
                    </div>
                </div>

                <!-- Text Content -->
                <div class="p-5 space-y-2.5 text-xs">
                    <h3 class="font-bold text-sm text-primary line-clamp-2 leading-snug">
                        {{ $item->judul }}
                    </h3>

                    <p class="text-on-surface-variant line-clamp-3 leading-relaxed whitespace-pre-line text-[11px]">
                        {{ $item->isi }}
                    </p>

                    @if($item->tanggal_kadaluarsa)
                    <div class="text-[10px] font-semibold text-outline flex items-center gap-1 pt-1">
                        <span class="material-symbols-outlined text-[13px]">event_busy</span>
                        <span>Berlaku s.d: {{ $item->tanggal_kadaluarsa->translatedFormat('d M Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="p-3.5 bg-surface-container-low border-t border-outline-variant/20 flex items-center justify-between text-xs">
                <form action="{{ route('admin.pengumuman.toggle-pin', $item->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-2 py-1 rounded-lg text-[11px] font-bold transition flex items-center gap-1 {{ $item->is_pinned ? 'bg-amber-100 text-amber-900 hover:bg-amber-200' : 'bg-surface-container text-outline hover:text-primary' }}" title="{{ $item->is_pinned ? 'Lepas Sematan' : 'Sematkan ke Atas' }}">
                        <span class="material-symbols-outlined text-[14px]">push_pin</span>
                        <span>{{ $item->is_pinned ? 'Pinned' : 'Sematkan' }}</span>
                    </button>
                </form>

                <div class="flex items-center gap-1.5">
                    <a href="{{ route('admin.pengumuman.edit', $item->id) }}" class="px-2.5 py-1 rounded-lg bg-primary text-white font-bold hover:bg-primary-container transition flex items-center gap-1 text-[11px] shadow-2xs">
                        <span class="material-symbols-outlined text-[13px]">edit</span>
                        <span>Edit</span>
                    </a>
                    <form action="{{ route('admin.pengumuman.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1 rounded-lg text-outline hover:text-red-600 hover:bg-red-50 transition" title="Hapus">
                            <span class="material-symbols-outlined text-[16px]">delete</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-outline-variant/30 space-y-3">
            <span class="material-symbols-outlined text-5xl text-outline">campaign</span>
            <h3 class="font-serif-display text-lg font-bold text-primary">Belum Ada Pengumuman</h3>
            <p class="text-xs text-on-surface-variant max-w-md mx-auto">Klik tombol "+ Buat Pengumuman Baru" di atas untuk menambahkan berita, acara komisi, atau jadwal khusus gereja.</p>
            <a href="{{ route('admin.pengumuman.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-secondary text-white font-bold text-xs hover:bg-secondary/90 transition shadow-sm">
                <span class="material-symbols-outlined text-[17px]">add</span>
                <span>Buat Pengumuman Pertama</span>
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($pengumumanList->hasPages())
    <div class="bg-white rounded-2xl p-4 border border-outline-variant/30 shadow-xs">
        {{ $pengumumanList->links() }}
    </div>
    @endif
</div>
@endsection

