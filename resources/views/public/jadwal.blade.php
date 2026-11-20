@extends('layouts.public')

@section('title', 'Jadwal Ibadah Onsite - GBI Shekinah Glory')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
        <span class="text-xs font-bold uppercase tracking-widest text-secondary mb-2 block">Ibadah Tatap Muka (Onsite)</span>
        <h1 class="font-serif-display text-4xl sm:text-5xl font-bold text-primary mb-4">Jadwal Ibadah & Pelayanan</h1>
        <p class="text-base text-on-surface-variant">Mari hadir bersama keluarga dalam hadirat Tuhan yang kudus, pujian penyembahan yang intim, dan pengajaran Firman yang mengubahkan jiwa.</p>
        <div class="w-20 h-1 bg-secondary mx-auto mt-6 rounded-full"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
        @foreach($jadwalList as $jadwal)
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-outline-variant/30 flex flex-col justify-between hover:border-secondary transition duration-300 hover:shadow-lg"
             data-aos="fade-up" data-aos-delay="{{ 100 * $loop->iteration }}">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-secondary-container text-secondary">
                        {{ $jadwal->kategori }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs text-secondary font-semibold">
                        <span class="w-2 h-2 rounded-full bg-secondary"></span>
                        {{ $jadwal->tipe_pelaksanaan }}
                    </span>
                </div>
                <h3 class="font-serif-display text-2xl font-bold text-primary mb-2">{{ $jadwal->nama_ibadah }}</h3>
                <p class="text-sm text-on-surface-variant leading-relaxed mb-6">{{ $jadwal->keterangan }}</p>
            </div>

            <div class="bg-surface-container-low rounded-xl p-4 space-y-3 text-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-secondary shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                    </div>
                    <div>
                        <span class="text-xs text-outline block">Hari Ibadah</span>
                        <span class="font-bold text-primary">{{ $jadwal->hari }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-secondary shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">schedule</span>
                    </div>
                    <div>
                        <span class="text-xs text-outline block">Waktu / Pukul</span>
                        <span class="font-bold text-primary">{{ $jadwal->jam }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-secondary shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">meeting_room</span>
                    </div>
                    <div>
                        <span class="text-xs text-outline block">Ruangan & Lokasi</span>
                        <span class="font-semibold text-primary">{{ $jadwal->lokasi_ruangan }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Petugas Pelayanan Mendatang (Service Rota) -->
    @if(isset($upcomingRotas) && $upcomingRotas->isNotEmpty())
    <div class="mb-16 space-y-6" data-aos="fade-up">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-outline-variant/20 pb-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-secondary block">Pelayanan Pekan Ini</span>
                <h2 class="font-serif-display text-2xl sm:text-3xl font-bold text-primary">Petugas Pelayan Ibadah</h2>
            </div>
            <span class="text-xs text-outline bg-surface-container px-3 py-1.5 rounded-full">GBI Shekinah Glory Namosain</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($upcomingRotas as $rota)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-outline-variant/30 space-y-4">
                <div class="flex items-center justify-between border-b border-outline-variant/20 pb-3">
                    <div>
                        <span class="font-bold text-primary block text-base">{{ $rota->sesiIbadah->nama_ibadah }}</span>
                        <span class="text-xs text-secondary font-semibold">{{ $rota->tanggal_pelayanan->translatedFormat('l, d F Y') }} • Pukul {{ $rota->sesiIbadah->jam }} WITA</span>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-secondary-container/40 text-secondary text-[10px] font-bold">
                        {{ $rota->sesiIbadah->lokasi_ruangan }}
                    </span>
                </div>

                @if(!empty($rota->tema_ibadah))
                <div class="p-3 rounded-xl bg-surface-container-low text-xs">
                    <span class="text-outline block">Tema Firman Tuhan:</span>
                    <strong class="text-primary font-serif-display text-sm">"{{ $rota->tema_ibadah }}"</strong>
                    @if($rota->pengkhotbah)
                    <span class="block text-secondary font-semibold mt-0.5">Pengkhotbah: {{ $rota->pengkhotbah }}</span>
                    @endif
                </div>
                @endif

                <div class="grid grid-cols-2 gap-2 text-xs">
                    @foreach($rota->petugas->take(8) as $pt)
                    <div class="p-2 rounded-lg bg-surface-container-low/60 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-secondary"></span>
                        <div class="min-w-0">
                            <span class="text-[10px] text-outline block uppercase leading-none">{{ $pt->posisi_label }}</span>
                            <span class="font-semibold text-primary truncate block text-xs mt-0.5">{{ $pt->nama_pelayan }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Onsite Gathering Welcome Callout -->
    <div class="bg-primary text-white rounded-2xl p-8 lg:p-12 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6"
         data-aos="fade-up" data-aos-delay="200">
        <div class="space-y-2 text-center md:text-left">
            <span class="text-xs uppercase font-bold tracking-widest text-secondary-fixed">GBI Shekinah Glory</span>
            <h3 class="font-serif-display text-2xl sm:text-3xl font-bold">Mari Bersekutu & Beribadah Bersama Secara Onsite</h3>
            <p class="text-sm text-white/80 max-w-xl">Semua kebaktian dilaksanakan secara tatap muka (onsite). Rasakan berkat persekutuan nyata bersama saudara seiman, ruang ibadah nyaman ber-AC, Kids Glory, dan area parkir aman.</p>
        </div>
        <a href="{{ route('public.lokasi') }}" class="px-6 py-3.5 rounded-xl bg-secondary hover:bg-secondary/90 text-white font-bold text-sm shadow-md transition flex items-center gap-2 shrink-0 transform active:scale-95">
            <span class="material-symbols-outlined text-[20px]">near_me</span>
            <span>Petunjuk Lokasi Gedung</span>
        </a>
    </div>
</div>
@endsection
