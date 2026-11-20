@extends('layouts.public')

@section('title', 'Kontak & Sekretariat - GBI Shekinah Glory')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
        <span class="text-xs font-bold uppercase tracking-widest text-secondary mb-2 block">Kanal Komunikasi</span>
        <h1 class="font-serif-display text-4xl sm:text-5xl font-bold text-primary mb-4">Hubungi Sekretariat Jemaat</h1>
        <p class="text-base text-on-surface-variant">Kami siap melayani kebutuhan informasi administrasi gerejawi, pendaftaran persekutuan wilayah, dan konsultasi pelayanan.</p>
        <div class="w-20 h-1 bg-secondary mx-auto mt-6 rounded-full"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start mb-16">
        <!-- Informasi Kontak Card -->
        <div class="lg:col-span-5 space-y-6" data-aos="fade-right" data-aos-duration="850">
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-outline-variant/30 space-y-6">
                <h3 class="font-serif-display text-2xl font-bold text-primary">Informasi Kontak</h3>

                <div class="space-y-4">
                    <a href="https://wa.me/6281288997700" target="_blank" class="flex items-center gap-4 p-4 rounded-xl bg-surface-container-low hover:bg-surface-container transition">
                        <div class="w-12 h-12 rounded-xl bg-secondary-container text-secondary flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[24px]">phone_iphone</span>
                        </div>
                        <div>
                            <span class="text-xs text-outline block">Telepon / WhatsApp</span>
                            <span class="font-bold text-primary">{{ $contents['whatsapp'] ?? '+62 812-8899-7700' }}</span>
                        </div>
                    </a>

                    <a href="mailto:sekretariat@gbishekinahglory.org" class="flex items-center gap-4 p-4 rounded-xl bg-surface-container-low hover:bg-surface-container transition">
                        <div class="w-12 h-12 rounded-xl bg-primary-container text-white flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[24px]">mail</span>
                        </div>
                        <div>
                            <span class="text-xs text-outline block">Email Pelayanan</span>
                            <span class="font-bold text-primary">{{ $contents['email'] ?? 'sekretariat@gbishekinahglory.org' }}</span>
                        </div>
                    </a>

                    <div class="flex items-start gap-4 p-4 rounded-xl bg-surface-container-low">
                        <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center shrink-0 text-primary">
                            <span class="material-symbols-outlined text-[24px]">schedule</span>
                        </div>
                        <div class="text-xs text-on-surface-variant space-y-1">
                            <span class="font-bold text-primary block text-sm">Jam Operasional Kantor</span>
                            <p><strong>Selasa – Sabtu:</strong> 10:00 – 16:00 WITA</p>                            
                            <p class="text-outline italic">Senin libur pelayanan administrasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulir Pesan Sekretariat -->
        <div class="lg:col-span-7 bg-white rounded-2xl p-8 lg:p-10 shadow-sm border border-outline-variant/30" data-aos="fade-left" data-aos-duration="850">
            <h3 class="font-serif-display text-2xl font-bold text-primary mb-2">Formulir Pesan & Pertanyaan</h3>
            <p class="text-sm text-on-surface-variant mb-6">Tuliskan pesan atau pertanyaan Anda kepada sekretariat gereja. Tim kami akan segera menindaklanjuti.</p>

            <form id="form-pesan-kontak" onsubmit="submitKontakForm(event)" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-primary mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="kontak-nama" required class="w-full px-4 py-2.5 rounded-lg border border-outline-variant focus:outline-none focus:border-secondary text-sm" placeholder="Nama Anda">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-primary mb-1">No. WhatsApp / HP <span class="text-red-500">*</span></label>
                        <input type="text" name="telepon" id="kontak-telepon" required class="w-full px-4 py-2.5 rounded-lg border border-outline-variant focus:outline-none focus:border-secondary text-sm" placeholder="Contoh: 08123456789">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-primary mb-1">Pesan / Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea name="pesan" id="kontak-pesan" required rows="5" class="w-full px-4 py-2.5 rounded-lg border border-outline-variant focus:outline-none focus:border-secondary text-sm" placeholder="Tuliskan pertanyaan atau pesan Anda terkait pelayanan gereja..."></textarea>
                </div>

                <button type="submit" id="kontak-btn" class="w-full sm:w-auto px-8 py-3 rounded-lg bg-secondary text-white font-bold text-sm hover:bg-secondary/90 transition shadow-sm flex items-center justify-center gap-2 transform active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">send</span>
                    <span>Kirim Pesan ke Sekretariat</span>
                </button>

                <div id="kontak-toast" class="hidden p-4 rounded-xl bg-green-50 text-green-800 text-sm border border-green-200"></div>
            </form>
        </div>
    </div>
</div>

<script>
function submitKontakForm(e) {
    e.preventDefault();
    const btn = document.getElementById('kontak-btn');
    const toast = document.getElementById('kontak-toast');
    const nama = document.getElementById('kontak-nama').value;
    const telepon = document.getElementById('kontak-telepon').value;
    const pesan = document.getElementById('kontak-pesan').value;

    btn.disabled = true;
    btn.innerHTML = `<span class="material-symbols-outlined animate-spin text-[18px]">refresh</span> Mengirim...`;

    fetch("{{ route('public.kirim-pesan') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ nama: nama, telepon: telepon, pesan: pesan })
    })
    .then(res => res.json())
    .then(data => {
        toast.textContent = data.message;
        toast.classList.remove('hidden');
        document.getElementById('form-pesan-kontak').reset();
        btn.disabled = false;
        btn.innerHTML = `<span class="material-symbols-outlined text-[18px]">done</span> Berhasil Terkirim`;
        setTimeout(() => {
            btn.innerHTML = `<span class="material-symbols-outlined text-[18px]">send</span> Kirim Pesan ke Sekretariat`;
            toast.classList.add('hidden');
        }, 5000);
    })
    .catch(() => {
        toast.textContent = "Terjadi gangguan. Silakan hubungi langsung WhatsApp kami.";
        toast.classList.remove('hidden');
        btn.disabled = false;
        btn.innerHTML = `<span class="material-symbols-outlined text-[18px]">send</span> Kirim Pesan ke Sekretariat`;
    });
}
</script>
@endsection
