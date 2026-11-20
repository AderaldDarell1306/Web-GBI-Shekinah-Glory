@extends('layouts.admin')

@section('title', 'Warta Jemaat & Pengumuman Gereja')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-primary via-primary-container to-primary rounded-2xl p-6 sm:p-8 text-white shadow-md flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-secondary-fixed text-xs font-bold mb-2">
                <span class="material-symbols-outlined text-[16px]">campaign</span>
                <span>Manajemen Warta, Pengumuman &amp; Renungan</span>
            </div>
            <h1 class="font-serif-display text-2xl sm:text-3xl font-bold">Warta Jemaat &amp; Pengumuman</h1>
            <p class="text-xs sm:text-sm text-white/80 mt-1 max-w-2xl leading-relaxed">
                Kelola renungan mingguan, gambar sampul warta, agenda kegiatan sepekan, pengumuman ibadah, dan kirim pesan warta otomatis ke WhatsApp jemaat.
            </p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('public.warta') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition flex items-center gap-1.5 border border-white/20">
                <span class="material-symbols-outlined text-[17px]">open_in_new</span>
                <span>Lihat di Web</span>
            </a>
            <a href="{{ route('admin.warta.create') }}" class="px-4 py-2.5 rounded-xl bg-secondary text-white font-bold text-xs hover:bg-secondary/90 transition shadow-md flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>+ Buat Edisi &amp; Pengumuman Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 border border-outline-variant/30 shadow-xs">
        <form method="GET" action="{{ route('admin.warta.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-4">
                <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-1">Status Publikasi</label>
                <select name="status" class="w-full text-xs font-semibold rounded-xl border-outline-variant focus:border-secondary focus:ring-secondary py-2">
                    <option value="">Semua Status</option>
                    <option value="terbit" {{ $status == 'terbit' ? 'selected' : '' }}>Terbit (Aktif di Web)</option>
                    <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft (Belum Tampil)</option>
                </select>
            </div>

            <div class="sm:col-span-5">
                <label class="block text-xs font-bold text-primary uppercase tracking-wider mb-1">Cari Tema / Ayat / Pengumuman</label>
                <input type="text" name="q" value="{{ $q }}" placeholder="Ketik kata kunci tema firman atau pengumuman..." class="w-full text-xs rounded-xl border-outline-variant focus:border-secondary focus:ring-secondary py-2">
            </div>

            <div class="sm:col-span-3 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 text-xs font-bold text-white bg-primary rounded-xl hover:bg-primary-container transition shadow-xs flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">filter_alt</span>
                    <span>Filter</span>
                </button>
                <a href="{{ route('admin.warta.index') }}" class="py-2 px-3 text-xs font-bold text-outline bg-surface-container rounded-xl hover:bg-surface-container-high transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Warta & Pengumuman List Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($wartaList as $w)
            <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-all flex flex-col justify-between overflow-hidden">
                <div>
                    <!-- Visual Cover Gambar Konten -->
                    <div class="relative w-full h-44 bg-surface-container overflow-hidden">
                        <img src="{{ $w->gambar_sampul_url }}" alt="{{ $w->tema_mingguan }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-3 left-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider shadow-xs {{ $w->status == 'terbit' ? 'bg-emerald-500 text-white' : 'bg-amber-400 text-gray-900' }}">
                                {{ ucfirst($w->status) }}
                            </span>
                        </div>

                        <!-- Tanggal Warta -->
                        <div class="absolute top-3 right-3 text-2xs font-mono font-bold px-2 py-0.5 rounded bg-black/60 text-white backdrop-blur-xs">
                            {{ $w->tanggal_warta->translatedFormat('d M Y') }}
                        </div>

                        <!-- Judul Edisi & Tema di Atas Cover -->
                        <div class="absolute bottom-3 left-3 right-3 text-white">
                            <span class="text-[10px] text-white/80 block uppercase tracking-wider font-semibold">{{ $w->edisi }}</span>
                            <h3 class="font-bold text-sm text-white line-clamp-1 drop-shadow-sm">"{{ $w->tema_mingguan ?: 'Tema Khotbah Mingguan' }}"</h3>
                        </div>
                    </div>

                    <!-- Isi Informasi, Khotbah & Pengumuman -->
                    <div class="p-5 space-y-3.5 text-xs">
                        @if($w->ayat_tema)
                            <div class="flex items-center gap-1.5 text-secondary font-semibold text-[11px]">
                                <span class="material-symbols-outlined text-[15px]">menu_book</span>
                                <span>{{ $w->ayat_tema }}</span>
                            </div>
                        @endif

                        <!-- Cuplikan Pengumuman Kegiatan -->
                        <div class="p-3 rounded-xl bg-amber-50/70 border border-amber-200/60 space-y-1">
                            <div class="flex items-center gap-1.5 font-bold text-amber-900 text-[11px]">
                                <span class="material-symbols-outlined text-[16px] text-amber-700">campaign</span>
                                <span>Pengumuman &amp; Agenda:</span>
                            </div>
                            @if($w->pengumuman_kegiatan)
                                <p class="text-amber-950 text-[11px] leading-relaxed line-clamp-3 whitespace-pre-line pl-1 border-l-2 border-amber-400">
                                    {{ Str::limit($w->pengumuman_kegiatan, 140) }}
                                </p>
                            @else
                                <p class="text-amber-800/70 text-[10px] italic">Belum ada rincian pengumuman yang ditulis pada edisi ini.</p>
                            @endif
                        </div>

                        <!-- Ringkasan Khotbah / Renungan -->
                        @if($w->ringkasan_khotbah)
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-outline block">Ringkasan Renungan:</span>
                                <p class="text-on-surface-variant line-clamp-2 leading-relaxed">
                                    {{ Str::limit(strip_tags($w->ringkasan_khotbah), 100) }}
                                </p>
                            </div>
                        @endif

                        <!-- Birthday Tag -->
                        @php $countUltah = $w->jemaat_ulang_tahun_minggu_ini->count(); @endphp
                        <div class="flex items-center gap-1.5 text-[11px] text-primary font-semibold pt-1">
                            <span class="material-symbols-outlined text-[16px] text-secondary">cake</span>
                            <span>{{ $countUltah }} jemaat berulang tahun pekan ini</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Aksi -->
                <div class="p-4 bg-surface-container-low border-t border-outline-variant/20 flex items-center justify-between text-xs">
                    <a href="{{ route('admin.warta.show', $w->id) }}" class="font-bold text-secondary hover:underline inline-flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">chat</span>
                        <span>Broadcast WA</span>
                    </a>

                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.warta.edit', $w->id) }}" class="px-2.5 py-1 rounded-lg bg-white border border-outline-variant/40 hover:bg-secondary hover:text-white text-primary font-bold transition flex items-center gap-1 text-[11px] shadow-2xs" title="Ganti Konten, Gambar & Pengumuman">
                            <span class="material-symbols-outlined text-[14px]">edit</span>
                            <span>Ganti Konten</span>
                        </a>
                        <form action="{{ route('admin.warta.destroy', $w->id) }}" method="POST" onsubmit="return confirm('Hapus edisi warta ini?')" class="inline">
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
                <span class="material-symbols-outlined text-5xl text-outline">newspaper</span>
                <h3 class="font-serif-display text-lg font-bold text-primary">Belum Ada Edisi Warta</h3>
                <p class="text-xs text-on-surface-variant max-w-md mx-auto">Klik tombol "Buat Edisi &amp; Pengumuman Baru" di atas untuk menambahkan renungan, pengumuman agenda, dan foto sampul warta.</p>
                <a href="{{ route('admin.warta.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-secondary text-white font-bold text-xs hover:bg-secondary/90 transition shadow-sm">
                    <span class="material-symbols-outlined text-[17px]">add</span>
                    <span>Buat Warta Pertama</span>
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($wartaList->hasPages())
    <div class="bg-white rounded-2xl p-4 border border-outline-variant/30 shadow-xs">
        {{ $wartaList->links() }}
    </div>
    @endif
</div>
@endsection
