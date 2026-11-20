<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wilayah;
use App\Models\Jemaat;
use App\Models\JadwalIbadah;
use App\Models\WebsiteContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin Akun Utama
        $admin = User::firstOrCreate(
            ['email' => 'admin@gbishekinahglory.org'],
            [
                'name' => 'Pdt. Markus Suryanata',
                'password' => Hash::make('Admin123!@#'),
                'role' => 'super_admin',
                'telepon' => '081288997700',
                'status' => 'aktif',
            ]
        );

        $developer = User::firstOrCreate(
            ['email' => 'developer@gbishekinahglory.org'],
            [
                'name' => 'Developer System',
                'password' => Hash::make('Developer123!@#'),
                'role' => 'developer',
                'telepon' => '081299990000',
                'status' => 'aktif',
            ]
        );

        $staff = User::firstOrCreate(
            ['email' => 'sekretariat@gbishekinahglory.org'],
            [
                'name' => 'Yohanes Prasetya',
                'password' => Hash::make('Sekretariat123!@#'),
                'role' => 'admin',
                'telepon' => '081399887711',
                'status' => 'aktif',
            ]
        );

        // 2. Data Wilayah / Sektor
        // 2. Data Wilayah / Sektor (3 Wilayah Besar: Namosain, Rumah 7, Osmok - Total 12 Sub-Wilayah)
        $wilayahData = [
            // NAMOSAIN (2 Sub-Wilayah)
            ['kode_prefix' => '11', 'kode_wilayah' => 'NAM-01', 'wilayah_besar' => 'Namosain', 'nama_wilayah' => 'Namosain - Sub Wilayah 1', 'deskripsi' => 'Cakupan jemaat Namosain Sektor 1 (Prefix KK: 11xxxx)', 'status' => 'aktif'],
            ['kode_prefix' => '12', 'kode_wilayah' => 'NAM-02', 'wilayah_besar' => 'Namosain', 'nama_wilayah' => 'Namosain - Sub Wilayah 2', 'deskripsi' => 'Cakupan jemaat Namosain Sektor 2 (Prefix KK: 12xxxx)', 'status' => 'aktif'],

            // RUMAH 7 (4 Sub-Wilayah)
            ['kode_prefix' => '21', 'kode_wilayah' => 'RM7-01', 'wilayah_besar' => 'Rumah 7', 'nama_wilayah' => 'Rumah 7 - Sub Wilayah 1', 'deskripsi' => 'Cakupan jemaat Rumah 7 Sektor 1 (Prefix KK: 21xxxx)', 'status' => 'aktif'],
            ['kode_prefix' => '22', 'kode_wilayah' => 'RM7-02', 'wilayah_besar' => 'Rumah 7', 'nama_wilayah' => 'Rumah 7 - Sub Wilayah 2', 'deskripsi' => 'Cakupan jemaat Rumah 7 Sektor 2 (Prefix KK: 22xxxx)', 'status' => 'aktif'],
            ['kode_prefix' => '23', 'kode_wilayah' => 'RM7-03', 'wilayah_besar' => 'Rumah 7', 'nama_wilayah' => 'Rumah 7 - Sub Wilayah 3', 'deskripsi' => 'Cakupan jemaat Rumah 7 Sektor 3 (Prefix KK: 23xxxx)', 'status' => 'aktif'],
            ['kode_prefix' => '24', 'kode_wilayah' => 'RM7-04', 'wilayah_besar' => 'Rumah 7', 'nama_wilayah' => 'Rumah 7 - Sub Wilayah 4', 'deskripsi' => 'Cakupan jemaat Rumah 7 Sektor 4 (Prefix KK: 24xxxx)', 'status' => 'aktif'],

            // OSMOK (6 Sub-Wilayah)
            ['kode_prefix' => '31', 'kode_wilayah' => 'OSM-01', 'wilayah_besar' => 'Osmok', 'nama_wilayah' => 'Osmok - Sub Wilayah 1', 'deskripsi' => 'Cakupan jemaat Osmok Sektor 1 (Prefix KK: 31xxxx)', 'status' => 'aktif'],
            ['kode_prefix' => '32', 'kode_wilayah' => 'OSM-02', 'wilayah_besar' => 'Osmok', 'nama_wilayah' => 'Osmok - Sub Wilayah 2', 'deskripsi' => 'Cakupan jemaat Osmok Sektor 2 (Prefix KK: 32xxxx)', 'status' => 'aktif'],
            ['kode_prefix' => '33', 'kode_wilayah' => 'OSM-03', 'wilayah_besar' => 'Osmok', 'nama_wilayah' => 'Osmok - Sub Wilayah 3', 'deskripsi' => 'Cakupan jemaat Osmok Sektor 3 (Prefix KK: 33xxxx)', 'status' => 'aktif'],
            ['kode_prefix' => '34', 'kode_wilayah' => 'OSM-04', 'wilayah_besar' => 'Osmok', 'nama_wilayah' => 'Osmok - Sub Wilayah 4', 'deskripsi' => 'Cakupan jemaat Osmok Sektor 4 (Prefix KK: 34xxxx)', 'status' => 'aktif'],
            ['kode_prefix' => '35', 'kode_wilayah' => 'OSM-05', 'wilayah_besar' => 'Osmok', 'nama_wilayah' => 'Osmok - Sub Wilayah 5', 'deskripsi' => 'Cakupan jemaat Osmok Sektor 5 (Prefix KK: 35xxxx)', 'status' => 'aktif'],
            ['kode_prefix' => '36', 'kode_wilayah' => 'OSM-06', 'wilayah_besar' => 'Osmok', 'nama_wilayah' => 'Osmok - Sub Wilayah 6', 'deskripsi' => 'Cakupan jemaat Osmok Sektor 6 (Prefix KK: 36xxxx)', 'status' => 'aktif'],
        ];

        $wilayahModels = [];
        foreach ($wilayahData as $w) {
            $wilayahModels[] = Wilayah::updateOrCreate(['kode_wilayah' => $w['kode_wilayah']], $w);
        }

        // 3. Jadwal Ibadah
        $jadwalData = [
            [
                'nama_ibadah' => 'Ibadah Raya Umum',
                'kategori' => 'Ibadah Utama',
                'hari' => 'Minggu',
                'jam' => '08:00 & 10:30 WIB (2 Sesi)',
                'lokasi_ruangan' => 'Main Hall (Lt. 3)',
                'keterangan' => 'Kebaktian umum tatap muka untuk seluruh jemaat dan keluarga. Disediakan penerjemah bahasa isyarat di Sesi 2.',
                'tipe_pelaksanaan' => 'Onsite',
                'urutan' => 1,
                'status' => 'aktif',
            ],
            [
                'nama_ibadah' => 'Shekinah Youth Service',
                'kategori' => 'Pemuda & Remaja',
                'hari' => 'Sabtu',
                'jam' => '17:00 WIB',
                'lokasi_ruangan' => 'Youth Chapel (Lt. 2)',
                'keterangan' => 'Komunitas anak muda yang dinamis, relevan, pujian penyembahan kreatif, dan firman yang mengubahkan secara tatap muka.',
                'tipe_pelaksanaan' => 'Onsite',
                'urutan' => 2,
                'status' => 'aktif',
            ],
            [
                'nama_ibadah' => 'Kids Glory (Sekolah Minggu)',
                'kategori' => 'Anak-Anak',
                'hari' => 'Minggu',
                'jam' => '10:30 WIB (Bersamaan Sesi 2)',
                'lokasi_ruangan' => 'Kids Sanctuary (Lt. 1)',
                'keterangan' => 'Pendidikan karakter rohani yang menyenangkan, kreatif, dan penuh kasih untuk usia 2 - 12 tahun.',
                'tipe_pelaksanaan' => 'Onsite',
                'urutan' => 3,
                'status' => 'aktif',
            ],
            [
                'nama_ibadah' => 'Menara Doa & Pemulihan',
                'kategori' => 'Doa Syafaat',
                'hari' => 'Rabu',
                'jam' => '19:00 WIB',
                'lokasi_ruangan' => 'Ruang Doa Betesda',
                'keterangan' => 'Mencari hadirat Tuhan, doa kesembuhan jasmani & batin, serta syafaat bagi bangsa dan keluarga jemaat di sanctuary.',
                'tipe_pelaksanaan' => 'Onsite',
                'urutan' => 4,
                'status' => 'aktif',
            ],
        ];

        foreach ($jadwalData as $j) {
            JadwalIbadah::firstOrCreate(['nama_ibadah' => $j['nama_ibadah']], $j);
        }

        // 4. Konten Profil Website
        $kontenData = [
            'nama_gereja' => ['label' => 'Nama Gereja', 'value' => 'GBI Shekinah Glory', 'type' => 'text'],
            'tagline' => ['label' => 'Tagline', 'value' => 'Menjadi Terang dan Tempat Kemuliaan Tuhan Hadir Nyata', 'type' => 'text'],
            'ayat_kunci' => ['label' => 'Ayat Kunci', 'value' => 'Bangkitlah, menjadi teranglah, sebab terangmu datang, dan kemuliaan TUHAN terbit atasmu. (Yesaya 60:1)', 'type' => 'text'],
            'hero_badge' => ['label' => 'Badge Ayat Banner', 'value' => 'YESAYA 60:1 • BANGKIT & BERCAHAYA', 'type' => 'text'],
            'hero_title' => ['label' => 'Judul Banner Beranda', 'value' => 'GBI Shekinah Glory', 'type' => 'text'],
            'hero_subtitle' => ['label' => 'Sub-judul Banner', 'value' => '"Menjadi Terang dan Tempat Kemuliaan Tuhan Hadir Nyata"', 'type' => 'text'],
            'hero_description' => ['label' => 'Deskripsi Sambutan Banner', 'value' => 'Selamat Datang di Rumah Tuhan. Kami menyambut Anda dan keluarga dengan kehangatan kasih serta sukacita Kristus di tengah persekutuan yang kudus, penuh pengharapan, dan memulihkan jiwa.', 'type' => 'richtext'],
            'hero_image' => ['label' => 'Gambar Banner Beranda', 'value' => 'https://images.unsplash.com/photo-1543807535-eceef0bc6599?auto=format&fit=crop&w=800&q=80', 'type' => 'image'],
            'hero_image_badge' => ['label' => 'Badge Kartu Gambar Banner', 'value' => 'Hadirat Allah Nyata', 'type' => 'text'],
            'hero_image_desc' => ['label' => 'Keterangan Gambar Banner', 'value' => 'Berakar dalam kebenaran Firman, bertumbuh dalam keintiman ibadah.', 'type' => 'text'],
            'sejarah' => [
                'label' => 'Sejarah Gereja',
                'value' => 'GBI Shekinah Glory dirintis atas dasar kerinduan mendalam menghadirkan hadirat Tuhan (Shekinah) yang nyata bagi setiap jiwa. Berawal dari persekutuan doa kecil keluarga pada tahun 2008 di bilangan Kebon Jeruk, Tuhan membimbing komunitas ini tumbuh menjadi wadah pertumbuhan rohani lintas generasi, merawat yang terluka, serta memperlengkapi umat untuk berdampak bagi kota dan bangsa.',
                'type' => 'richtext'
            ],
            'sejarah_image' => ['label' => 'Foto Sejarah Gereja', 'value' => 'https://images.unsplash.com/photo-1519491050282-cf00c82424b4?auto=format&fit=crop&w=1000&q=80', 'type' => 'image'],
            'sejarah_badge' => ['label' => 'Badge Foto Sejarah', 'value' => 'Sejak Tahun 2008', 'type' => 'text'],
            'sejarah_caption' => ['label' => 'Caption Foto Sejarah', 'value' => '16+ Tahun Mengabdi, Menjangkau Jiwa & Membangun Keluarga', 'type' => 'text'],
            'visi' => [
                'label' => 'Visi Gereja',
                'value' => 'Menjadi Rumah kemuliaan-Nya yang menjawab amanat agung Tuhan Yesus Kristus sebagai gereja yang Misionari, Apolistik dan Profetik.',
                'type' => 'text'
            ],
            'misi' => [
                'label' => 'Misi Pelayanan',
                'value' => json_encode([
                    'Membangun kehidupan doa, pujian, dan penyembahan yang berpusat pada Kristus.',
                    'Memuridkan setiap generasi melalui komunitas sel dan pembinaan rohani yang berkesinambungan.',
                    'Menjangkau jiwa-jiwa baru dan melayani masyarakat sekitar dengan kepedulian nyata.',
                    'Memperlengkapi keluarga kristiani yang berdaya tahan, harmonis, dan penuh damai sejahtera.'
                ]),
                'type' => 'json'
            ],
            'alamat_lengkap' => ['label' => 'Alamat Gereja', 'value' => 'Jl. Shekinah No. 7, Kebon Jeruk, Jakarta Barat 11530', 'type' => 'text'],
            'patokan_lokasi' => ['label' => 'Patokan Lokasi', 'value' => '200m di belakang Graha Serbaguna, seberang Taman Asri', 'type' => 'text'],
            'google_maps_url' => ['label' => 'Google Maps Link', 'value' => 'https://maps.google.com/?q=GBI+Shekinah+Glory', 'type' => 'text'],
            'whatsapp' => ['label' => 'No. WhatsApp', 'value' => '+62 812-8899-7700', 'type' => 'text'],
            'email' => ['label' => 'Email Resmi', 'value' => 'sekretariat@gbishekinahglory.org', 'type' => 'text'],
            'jam_kerja' => ['label' => 'Jam Operasional Kantor Pastoral', 'value' => 'Selasa – Sabtu: 09:00 – 17:00 WIB | Minggu: 07:00 – 14:00 WIB (Senin Libur)', 'type' => 'text'],
        ];

        foreach ($kontenData as $key => $data) {
            WebsiteContent::updateOrCreate(
                ['key' => $key],
                ['label' => $data['label'], 'value' => $data['value'], 'type' => $data['type']]
            );
        }

        // 5. Data Awal Jemaat dikosongkan (User akan menginput data jemaat sendiri sesuai format gereja)
        // Table 'jemaat' dibiarkan bersih sebagai template siap pakai.
    }
}
