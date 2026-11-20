# PRD: Website Profil GBI Shekinah Glory & Sistem Manajemen Data Jemaat

**Versi:** 1.0 (Draft untuk review) | **Tanggal:** 29 Oktober 2026 | **Penyusun:** Product Management | **Status:** Menunggu persetujuan stakeholder gereja

**Pembaca utama:** Tim UI/UX, Frontend Developer, Backend Developer, QA, dan Pengurus Gereja (Product Owner).

---

## 1. Overview & Objective

### 1.1 Latar Belakang

GBI Shekinah Glory memiliki jemaat yang tersebar di beberapa wilayah/sektor. Saat ini kebutuhan berikut belum terpenuhi secara optimal:

- **Informasi publik** (sejarah, visi-misi, jadwal ibadah, lokasi, kontak) belum tersedia dalam satu kanal resmi yang mudah ditemukan lewat mesin pencari dan mudah diakses dari ponsel.
- **Data jemaat** diasumsikan masih dikelola secara manual atau terpisah-pisah (buku catatan, spreadsheet per wilayah), sehingga rawan duplikasi, tidak konsisten, sulit dicari, dan berisiko bocor karena tidak ada kontrol akses.

Website ini dirancang berfungsi ganda: **(1) situs profil publik** dan **(2) sistem informasi internal** untuk mengelola database jemaat per wilayah, dengan akses terbatas hanya untuk Admin.

### 1.2 Tujuan Produk

| # | Tujuan | Indikator Keberhasilan (Target 3 bulan setelah rilis) |
| --- | --- | --- |
| 1 | Menyediakan kanal informasi resmi gereja yang mudah diakses publik | Informasi ibadah, lokasi, dan kontak dapat ditemukan pengunjung dalam maksimal 2 klik dari beranda |
| 2 | Memusatkan data jemaat dalam satu database terstruktur per wilayah | 100% data jemaat dari seluruh wilayah termigrasi ke sistem |
| 3 | Mempercepat pencarian dan pembaruan data jemaat | Admin menemukan data jemaat dalam kurang dari 10 detik |
| 4 | Menjaga privasi dan keamanan data jemaat | Nol data jemaat dapat diakses tanpa autentikasi |
| 5 | Menyediakan platform yang mudah dikelola pengurus non-teknis | Admin baru dapat menambah data jemaat setelah pelatihan maksimal 30 menit |

### 1.3 Ruang Lingkup (Scope)

**Dalam lingkup (MVP / Rilis 1.0):**

- Website publik: Beranda, Tentang/Sejarah, Visi & Misi, Jadwal Ibadah, Lokasi, Kontak.
- Login Admin dan dashboard backend.
- Manajemen data jemaat (CRUD), manajemen wilayah/sektor (CRUD), pencarian dan filter.
- Pengelolaan akun Admin dan audit log sederhana.

**Di luar lingkup (Rilis 1.0, dapat dipertimbangkan di rilis berikutnya):**

- Portal login untuk jemaat umum (self-service).
- Absensi ibadah, manajemen keuangan/persembahan, dan pembayaran online.
- Live streaming, e-newsletter, dan aplikasi mobile native.
- Role tambahan (misalnya Ketua Wilayah dengan akses terbatas ke wilayahnya sendiri).
- Blog/berita dan galeri kegiatan (direkomendasikan sebagai fase 2).

### 1.4 Asumsi & Ketergantungan

- Konten publik (teks sejarah, visi-misi, foto, jadwal) disediakan oleh pengurus gereja sebelum tahap UAT.
- Terdapat minimal 1-3 Admin yang ditunjuk gereja. Data awal jemaat tersedia dalam format Excel/CSV untuk dimigrasi.
- Bahasa utama antarmuka adalah **Bahasa Indonesia**.
- Pengelolaan data pribadi mengikuti **UU No. 27 Tahun 2022 tentang Pelindungan Data Pribadi (UU PDP)**. Perlu dicatat bahwa data keanggotaan keagamaan dapat dikategorikan sensitif, sehingga perlu persetujuan pengurus gereja dan konsultasi hukum bila diperlukan.

---

## 2. Target Audience / User Personas

### Persona 1: Pengunjung / Masyarakat Umum (Public)

| Atribut | Deskripsi |
| --- | --- |
| **Contoh profil** | Maria (34 th), pendatang baru di kota, mencari gereja GBI terdekat. Juga mencakup jemaat yang ingin mengecek jadwal ibadah. |
| **Perangkat** | Dominan smartphone (Android), koneksi seluler yang bisa tidak stabil |
| **Kebutuhan** | Mengetahui jadwal ibadah, lokasi, cara menghubungi gereja, dan mengenal visi-misi |
| **Pain point** | Informasi tersebar di media sosial, tidak selalu terbaru, sulit menemukan alamat/peta |
| **Hak akses** | Hanya membaca halaman publik. Tidak ada login |

### Persona 2: Admin Gereja

| Atribut | Deskripsi |
| --- | --- |
| **Contoh profil** | Yohanes (42 th), staf/sekretariat gereja yang menangani administrasi jemaat. Kemampuan teknis menengah (terbiasa Excel dan aplikasi pesan) |
| **Perangkat** | Laptop/desktop (utama), sesekali tablet atau ponsel |
| **Kebutuhan** | Menyimpan dan memperbarui data jemaat dengan cepat, melihat jemaat per wilayah, mencari data, serta mengelola wilayah |
| **Pain point** | Data tersebar di banyak file, sulit mencari jemaat, takut data hilang atau bocor |
| **Hak akses** | Login ke dashboard. Akses penuh CRUD data jemaat dan wilayah |

### Pemangku Kepentingan Non-Pengguna

- **Gembala / Pengurus Inti:** Pemberi persetujuan konten dan kebijakan data.
- **Pengembang / Maintainer:** Menjaga operasional, backup, dan keamanan sistem.

---

## 3. User Stories

Prioritas menggunakan metode MoSCoW: **M** (Must), **S** (Should), **C** (Could).

### 3.1 Pengunjung (Public)

| ID | User Story | Prioritas |
| --- | --- | --- |
| US-P01 | Sebagai pengunjung, saya ingin melihat beranda yang menampilkan identitas gereja dan ringkasan jadwal ibadah, agar saya cepat mengenal gereja ini. | M |
| US-P02 | Sebagai pengunjung, saya ingin membaca sejarah dan profil gereja, agar saya memahami latar belakang dan perjalanan gereja. | M |
| US-P03 | Sebagai pengunjung, saya ingin membaca visi dan misi gereja, agar saya mengetahui arah dan nilai-nilai gereja. | M |
| US-P04 | Sebagai pengunjung, saya ingin melihat jadwal ibadah (hari, jam, jenis ibadah, lokasi), agar saya bisa merencanakan kehadiran. | M |
| US-P05 | Sebagai pengunjung, saya ingin melihat lokasi gereja pada peta interaktif dan tombol petunjuk arah, agar saya mudah menemukan lokasinya. | M |
| US-P06 | Sebagai pengunjung, saya ingin melihat kontak gereja (telepon/WhatsApp, email, media sosial), agar saya bisa bertanya atau meminta doa. | M |
| US-P07 | Sebagai pengunjung, saya ingin mengakses situs dengan nyaman di ponsel dan cepat dimuat, agar saya tidak boros kuota dan waktu. | M |
| US-P08 | Sebagai pengunjung, saya ingin mengirim pesan melalui formulir kontak, agar saya bisa menghubungi gereja tanpa membuka aplikasi lain. | C |
| US-P09 | Sebagai pengunjung, saya ingin tidak dapat mengakses data jemaat dan halaman admin dengan cara apa pun, agar privasi jemaat terjaga. | M |

### 3.2 Admin

| ID | User Story | Prioritas |
| --- | --- | --- |
| US-A01 | Sebagai Admin, saya ingin login dengan email dan kata sandi, agar hanya orang berwenang yang dapat masuk ke dashboard. | M |
| US-A02 | Sebagai Admin, saya ingin logout dan otomatis keluar setelah tidak aktif, agar akun tidak disalahgunakan di perangkat bersama. | M |
| US-A03 | Sebagai Admin, saya ingin melihat ringkasan di dashboard (total jemaat, jumlah per wilayah, data terbaru), agar saya memahami kondisi jemaat sekilas. | S |
| US-A04 | Sebagai Admin, saya ingin melihat daftar jemaat yang dikelompokkan per wilayah/sektor, agar mudah memantau tiap wilayah. | M |
| US-A05 | Sebagai Admin, saya ingin menambah data jemaat baru melalui formulir yang tervalidasi, agar data tercatat lengkap dan konsisten. | M |
| US-A06 | Sebagai Admin, saya ingin melihat detail data seorang jemaat, agar saya dapat memverifikasi informasinya. | M |
| US-A07 | Sebagai Admin, saya ingin mengubah data jemaat (termasuk memindahkan wilayah), agar data selalu akurat. | M |
| US-A08 | Sebagai Admin, saya ingin menghapus data jemaat dengan konfirmasi, agar tidak terjadi penghapusan tidak sengaja. | M |
| US-A09 | Sebagai Admin, saya ingin mencari jemaat berdasarkan nama (termasuk sebagian nama), agar data ditemukan dengan cepat. | M |
| US-A10 | Sebagai Admin, saya ingin memfilter jemaat berdasarkan wilayah dan atribut lain (status, jenis kelamin), agar saya mendapat daftar yang relevan. | M |
| US-A11 | Sebagai Admin, saya ingin menambah, mengubah, dan menghapus wilayah/sektor, agar struktur wilayah gereja selalu sesuai kondisi terkini. | M |
| US-A12 | Sebagai Admin, saya ingin sistem menolak penghapusan wilayah yang masih memiliki jemaat (atau meminta pemindahan data), agar tidak ada data yatim. | M |
| US-A13 | Sebagai Admin, saya ingin mengimpor data jemaat dari Excel/CSV, agar migrasi data awal tidak perlu input manual. | S |
| US-A14 | Sebagai Admin, saya ingin mengekspor daftar jemaat ke Excel/CSV, agar bisa digunakan untuk laporan pengurus. | S |
| US-A15 | Sebagai Admin, saya ingin mengedit konten halaman publik (jadwal ibadah, kontak, teks) dari dashboard, agar tidak perlu bantuan developer untuk perubahan rutin. | S |
| US-A16 | Sebagai Admin, saya ingin mengganti kata sandi dan memulihkan akun lewat email, agar akses tidak terkunci permanen. | M |
| US-A17 | Sebagai Admin, saya ingin ada catatan riwayat perubahan data (siapa, kapan, apa), agar perubahan dapat ditelusuri. | S |

---

## 4. Functional Requirements

### 4.1 Front-End Publik

**FR-P01 Beranda**

- Hero section berisi nama gereja, logo, tagline, dan foto/banner.
- Ringkasan jadwal ibadah terdekat dan tombol cepat ke Lokasi dan Kontak.
- Cuplikan Tentang Kami dan Visi-Misi dengan tautan ke halaman lengkap.

**FR-P02 Tentang & Sejarah**

- Narasi sejarah gereja (rich text), dapat dilengkapi foto dan linimasa (timeline).
- Profil singkat gereja dan, bila diinginkan pengurus, profil gembala/pengurus (perlu persetujuan yang bersangkutan).

**FR-P03 Visi & Misi**

- Visi (teks) dan Misi (daftar poin), serta nilai-nilai gereja bila ada.

**FR-P04 Jadwal Ibadah**

- Tabel/kartu jadwal berisi: nama ibadah, hari, jam, lokasi/wilayah, dan keterangan (misalnya ibadah raya, ibadah pemuda, ibadah anak, ibadah doa).
- Jadwal dikelompokkan per hari atau per jenis ibadah. Dapat menampilkan jadwal ibadah wilayah/sektor bila ada.
- Konten jadwal dapat diubah oleh Admin tanpa coding (lihat FR-A09).

**FR-P05 Lokasi**

- Alamat lengkap, peta tersemat (embed peta), dan tombol "Petunjuk Arah" yang membuka aplikasi peta di ponsel.
- Informasi tambahan: area parkir, patokan lokasi (opsional).

**FR-P06 Kontak**

- Nomor telepon/WhatsApp (tautan klik-untuk-hubungi), email, jam operasional sekretariat, dan tautan media sosial.
- (Could) Formulir kontak dengan proteksi spam (CAPTCHA/honeypot) dan pesan masuk ke email gereja.

**FR-P07 Komponen Global**

- Header navigasi responsif (menu hamburger di mobile), footer berisi alamat singkat dan kontak, serta tautan "Login Admin" yang tidak mencolok (atau tidak ditampilkan dan diakses lewat URL khusus sesuai keputusan pengurus).
- Halaman 404 dan halaman error yang ramah pengguna.
- Metadata SEO (title, description, Open Graph) dan sitemap.xml serta robots.txt. Halaman admin tidak boleh diindeks mesin pencari.

### 4.2 Back-End Admin

**FR-A01 Autentikasi**

- Login dengan email + kata sandi. Kata sandi disimpan ter-hash (bcrypt/argon2).
- Pembatasan percobaan login (rate limiting): kunci sementara setelah 5 kali gagal berturut-turut.
- Lupa/reset kata sandi via tautan email berbatas waktu (maksimal 30 menit) dan sekali pakai.
- Sesi otomatis berakhir setelah 30 menit tidak aktif. Tombol logout tersedia di semua halaman dashboard.
- (Should) Autentikasi dua faktor (2FA) opsional untuk Admin.

**FR-A02 Dashboard**

- Kartu ringkasan: total jemaat, jumlah jemaat aktif, jumlah wilayah, dan penambahan data 30 hari terakhir.
- Grafik sederhana jumlah jemaat per wilayah.
- Pintasan: Tambah Jemaat, Tambah Wilayah.

**FR-A03 Daftar Jemaat Berdasarkan Wilayah**

- Tampilan daftar jemaat dapat dikelompokkan per wilayah/sektor (accordion/tab atau grup berjudul wilayah, berikut jumlah jemaat per wilayah).
- Kolom minimum pada tabel: Nama Lengkap, Wilayah, Jenis Kelamin, No. HP, Status Keanggotaan, Aksi (Lihat, Ubah, Hapus).
- Pagination (default 25 baris/halaman, opsi 10/25/50/100) dan pengurutan kolom (sort).

**FR-A04 Tambah Data Jemaat (Create)**

Atribut data jemaat yang diusulkan (dapat disesuaikan setelah diskusi dengan pengurus):

| Atribut | Tipe | Wajib | Catatan |
| --- | --- | --- | --- |
| ID Jemaat | Otomatis (unik) | Ya | Dihasilkan sistem |
| Nama Lengkap | Teks | Ya | Maks. 150 karakter |
| Jenis Kelamin | Pilihan | Ya | Laki-laki / Perempuan |
| Tempat & Tanggal Lahir | Teks + Tanggal | Tanggal lahir: Ya | Validasi tanggal tidak di masa depan |
| Alamat | Teks panjang | Tidak |  |
| No. HP/WhatsApp | Teks | Tidak | Validasi format nomor Indonesia |
| Email | Teks | Tidak | Validasi format email |
| Status Pernikahan | Pilihan | Tidak | Belum menikah / Menikah / Duda / Janda |
| Status Baptis | Pilihan + Tanggal | Tidak | Sudah / Belum |
| Wilayah/Sektor | Relasi ke Wilayah | Ya | Dipilih dari daftar wilayah |
| Status Keanggotaan | Pilihan | Ya | Aktif / Tidak Aktif / Pindah / Meninggal |
| Tanggal Bergabung | Tanggal | Tidak |  |
| Catatan | Teks panjang | Tidak | Bersifat internal |
| Dibuat/Diubah oleh dan waktu | Otomatis | Ya | Untuk audit |

- Validasi sisi klien dan sisi server. Pesan kesalahan dalam Bahasa Indonesia yang jelas.
- Deteksi potensi duplikat (nama + tanggal lahir yang sama) dengan peringatan sebelum menyimpan.
- Minimalkan pengumpulan data: hanya data yang benar-benar dibutuhkan gereja yang diwajibkan.

**FR-A05 Lihat Detail Jemaat (Read)**

- Halaman detail menampilkan seluruh atribut, riwayat perubahan singkat, dan tombol Ubah/Hapus.

**FR-A06 Ubah Data Jemaat (Update)**

- Formulir terisi data lama. Admin dapat memindahkan jemaat ke wilayah lain.
- Perubahan dicatat pada audit log (nilai lama, nilai baru, pengguna, waktu).
- Perlindungan konflik edit: bila dua Admin mengubah data yang sama, sistem memberi peringatan agar tidak saling menimpa.

**FR-A07 Hapus Data Jemaat (Delete)**

- Dialog konfirmasi yang menyebut nama jemaat yang akan dihapus.
- Menggunakan **soft delete** (data ditandai terhapus dan dapat dipulihkan Admin dalam 30 hari). Penghapusan permanen dilakukan terpisah dengan konfirmasi tambahan.
- Disarankan mengubah Status Keanggotaan (Pindah/Meninggal/Tidak Aktif) sebagai alternatif penghapusan agar data historis tetap utuh.

**FR-A08 Pencarian & Filter**

- Kolom pencarian berdasarkan nama (tidak sensitif huruf besar-kecil, mendukung pencarian sebagian kata). Opsional: pencarian juga pada No. HP.
- Filter: Wilayah (pilihan tunggal/ganda), Status Keanggotaan, Jenis Kelamin, Status Baptis.
- Pencarian dan filter dapat dikombinasikan, ditampilkan sebagai chip filter aktif, dengan tombol "Reset Filter".
- Menampilkan jumlah hasil dan pesan "Data tidak ditemukan" bila kosong.
- Parameter pencarian tersimpan di URL agar hasil dapat dibuka kembali.

**FR-A09 Manajemen Wilayah/Sektor**

- CRUD wilayah dengan atribut: Nama Wilayah (unik), Kode Wilayah, Nama Ketua/Penanggung Jawab (opsional), Deskripsi/Cakupan Area, dan Status (Aktif/Nonaktif).
- Aturan: wilayah yang masih memiliki jemaat tidak dapat dihapus. Sistem menawarkan pemindahan massal jemaat ke wilayah lain atau menonaktifkan wilayah.
- Menampilkan jumlah jemaat tiap wilayah.

**FR-A10 Pengelolaan Konten Publik (Should)**

- Admin dapat mengubah teks Sejarah, Visi-Misi, jadwal ibadah, alamat, kontak, dan banner dari dashboard menggunakan editor teks sederhana (WYSIWYG).
- Unggah gambar dengan batas ukuran (maks. 2 MB) dan kompresi otomatis.

**FR-A11 Impor & Ekspor (Should)**

- Impor CSV/XLSX dengan template baku, pratinjau, dan laporan baris yang gagal/valid sebelum data benar-benar disimpan.
- Ekspor daftar (hasil filter aktif) ke CSV/XLSX. Aksi ekspor dicatat di audit log, dan dibatasi untuk Admin saja.

**FR-A12 Manajemen Akun Admin**

- Admin dapat mengubah profil dan kata sandi sendiri.
- (Should) Admin utama dapat menambah/menonaktifkan akun Admin lain.

**FR-A13 Audit Log**

- Pencatatan: login/logout, login gagal, tambah/ubah/hapus data jemaat dan wilayah, impor, ekspor.
- Dapat dilihat oleh Admin utama, bersifat hanya-baca (tidak dapat diubah atau dihapus lewat antarmuka).

### 4.3 Matriks Peran & Hak Akses

| Fitur | Public | Admin |
| --- | :-: | :-: |
| Melihat halaman profil gereja | Ya | Ya |
| Mengirim formulir kontak (jika ada) | Ya | Ya |
| Login ke dashboard | Tidak | Ya |
| Melihat daftar & detail data jemaat | **Tidak** | Ya |
| Tambah / Ubah / Hapus data jemaat | **Tidak** | Ya |
| Tambah / Ubah / Hapus wilayah | **Tidak** | Ya |
| Impor / Ekspor data | **Tidak** | Ya |
| Mengelola konten publik | **Tidak** | Ya |
| Melihat audit log | **Tidak** | Ya (Admin utama) |

Prinsip: **deny by default**. Setiap endpoint API dan halaman backend wajib memverifikasi autentikasi dan otorisasi di sisi server, bukan hanya menyembunyikan menu di antarmuka.

### 4.4 Model Data (Konseptual)

- **Wilayah** (1) ─── (N) **Jemaat**: setiap jemaat terdaftar pada tepat satu wilayah.
- **Admin** (1) ─── (N) **AuditLog**.
- **KontenHalaman** dan **JadwalIbadah** menyimpan konten publik yang dapat diubah Admin.

---

## 5. Non-Functional Requirements

### 5.1 Keamanan & Privasi Data Jemaat (Prioritas Tertinggi)

| ID | Persyaratan |
| --- | --- |
| NFR-S01 | Seluruh trafik wajib HTTPS (TLS 1.2+) dengan HSTS. HTTP otomatis dialihkan ke HTTPS. |
| NFR-S02 | Kata sandi di-hash dengan bcrypt/argon2, tidak pernah disimpan sebagai teks biasa. Kebijakan kata sandi minimal 10 karakter dengan kombinasi huruf dan angka. |
| NFR-S03 | Otorisasi sisi server pada setiap endpoint. Tidak ada data jemaat yang dikirim ke klien tanpa sesi Admin valid. API data jemaat tidak boleh dapat diakses anonim. |
| NFR-S04 | Perlindungan terhadap kerentanan OWASP Top 10: SQL Injection (query terparameter/ORM), XSS (output escaping, CSP), CSRF (token), IDOR, dan clickjacking (X-Frame-Options). |
| NFR-S05 | Rate limiting pada login dan formulir publik. Cookie sesi berflag HttpOnly, Secure, dan SameSite. |
| NFR-S06 | Data pribadi jemaat (misalnya No. HP, alamat, tanggal lahir) dienkripsi saat disimpan (encryption at rest) melalui enkripsi disk/database, dan kolom paling sensitif dapat dienkripsi di level aplikasi. |
| NFR-S07 | Halaman dan API admin tidak diindeks mesin pencari (noindex, dan dikecualikan di robots.txt). Data jemaat tidak boleh muncul di URL publik, log aplikasi, atau pesan error. |
| NFR-S08 | Audit log untuk seluruh aksi sensitif (lihat FR-A13). Log disimpan minimal 12 bulan. |
| NFR-S09 | Backup otomatis harian, terenkripsi, retensi minimal 30 hari, disimpan terpisah dari server utama. Uji pemulihan (restore test) minimal tiap kuartal. Target RPO maksimal 24 jam dan RTO maksimal 4 jam. |
| NFR-S10 | Kepatuhan: pengumpulan, penyimpanan, dan penghapusan data pribadi mengikuti prinsip UU PDP (tujuan terbatas, minimisasi data, hak koreksi dan penghapusan). Disediakan kebijakan privasi internal dan dasar persetujuan jemaat atas pengelolaan datanya (diputuskan bersama pengurus). |
| NFR-S11 | Dependensi dan sistem operasional diperbarui rutin. Dilakukan pemindaian kerentanan sebelum rilis (dan berkala). |
| NFR-S12 | Lingkungan pengembangan/staging tidak boleh memakai data jemaat asli. Gunakan data dummy. |

### 5.2 Performa

| ID | Persyaratan |
| --- | --- |
| NFR-PF01 | Halaman publik: Largest Contentful Paint (LCP) ≤ 2,5 detik pada koneksi 4G; skor Lighthouse Performance ≥ 85 di mobile. |
| NFR-PF02 | Ukuran halaman publik awal ≤ 1,5 MB. Gambar dioptimalkan (WebP/AVIF), lazy loading, dan caching/CDN untuk aset statis. |
| NFR-PF03 | Respons dashboard: halaman daftar jemaat termuat ≤ 2 detik, pencarian/filter ≤ 1 detik untuk hingga 5.000 data jemaat (persentil ke-95). |
| NFR-PF04 | Waktu respons API CRUD ≤ 500 ms (persentil ke-95) pada beban normal. |
| NFR-PF05 | Database diberi indeks pada kolom pencarian dan relasi (nama, wilayah\_id, status). |

### 5.3 Skalabilitas & Ketersediaan

| ID | Persyaratan |
| --- | --- |
| NFR-SC01 | Arsitektur mendukung pertumbuhan hingga minimal 20.000 data jemaat dan 50 wilayah tanpa perubahan arsitektur. |
| NFR-SC02 | Dukungan lonjakan trafik publik (misalnya saat perayaan besar) hingga minimal 200 pengguna bersamaan dengan memanfaatkan caching/CDN. |
| NFR-SC03 | Ketersediaan (uptime) target ≥ 99,5% per bulan. |
| NFR-SC04 | Pemisahan frontend publik dan backend admin pada level modul/route sehingga dapat diskalakan terpisah. Opsi penambahan role baru (misalnya Ketua Wilayah) tidak memerlukan perancangan ulang skema (role-based access control dirancang extensible). |
| NFR-SC05 | Monitoring dan alerting dasar (uptime, error rate) serta log terpusat. |

### 5.4 Usability, Aksesibilitas, dan Kompatibilitas

- **Responsif** (mobile-first): didukung layar 360 px hingga desktop. Dashboard admin optimal di tablet dan desktop.
- **Aksesibilitas** mengacu WCAG 2.1 level AA: kontras warna cukup, navigasi keyboard, teks alternatif gambar, label formulir jelas.
- **Browser** didukung: dua versi mayor terbaru Chrome, Safari, Firefox, Edge, serta Chrome Android dan Safari iOS.
- Antarmuka Bahasa Indonesia, tipografi terbaca (ukuran isi minimal 16 px), dan dashboard dirancang sederhana untuk pengguna non-teknis.

### 5.5 Maintainability

- Kode terdokumentasi, memakai version control, dan alur CI/CD dengan lingkungan terpisah (development, staging, production).
- Cakupan pengujian otomatis minimal 70% untuk logika bisnis inti (CRUD, autentikasi, otorisasi).
- Dokumentasi teknis dan panduan pengguna Admin (dalam Bahasa Indonesia) diserahkan saat serah terima.

---

## 6. Sitemap / Information Architecture

### 6.1 Struktur Navigasi

```
WEBSITE GBI SHEKINAH GLORY
│
├── PUBLIK (tanpa login)
│   ├── Beranda (/)
│   ├── Tentang Kami (/tentang)
│   │   ├── Sejarah Gereja (/tentang/sejarah)
│   │   └── Visi & Misi (/tentang/visi-misi)
│   ├── Jadwal Ibadah (/jadwal-ibadah)
│   ├── Lokasi (/lokasi)
│   ├── Kontak (/kontak)
│   └── Halaman 404 / Error
│
└── ADMIN (wajib login, /admin)
    ├── Login (/admin/login)
    │   └── Lupa Kata Sandi (/admin/lupa-sandi)
    ├── Dashboard (/admin)
    ├── Data Jemaat (/admin/jemaat)
    │   ├── Daftar per Wilayah + Cari & Filter
    │   ├── Tambah Jemaat (/admin/jemaat/baru)
    │   ├── Detail Jemaat (/admin/jemaat/{id})
    │   ├── Ubah Jemaat (/admin/jemaat/{id}/ubah)
    │   └── Impor / Ekspor (Should)
    ├── Wilayah/Sektor (/admin/wilayah)
    │   ├── Daftar Wilayah
    │   ├── Tambah Wilayah
    │   └── Ubah Wilayah
    ├── Konten Website (/admin/konten) (Should)
    │   ├── Sejarah, Visi-Misi
    │   ├── Jadwal Ibadah
    │   └── Kontak & Lokasi
    ├── Pengaturan
    │   ├── Profil & Ganti Kata Sandi
    │   ├── Kelola Akun Admin (Should)
    │   └── Audit Log (Should)
    └── Logout
```

### 6.2 Navigasi

- **Menu utama publik:** Beranda | Tentang | Jadwal Ibadah | Lokasi | Kontak.
- **Menu dashboard admin (sidebar):** Dashboard | Data Jemaat | Wilayah | Konten Website | Pengaturan.
- **Alur kunci Admin:** Login → Dashboard → Data Jemaat → (Cari/Filter) → Tambah/Lihat/Ubah/Hapus.
- Akses ke URL `/admin/*` tanpa sesi valid dialihkan ke halaman login (HTTP 302/401), tanpa membocorkan informasi apa pun.

---

## 7. Acceptance Criteria

Setiap fitur dinyatakan **selesai (Done)** apabila seluruh kriteria berikut terpenuhi, lolos QA, dan disetujui Product Owner pada UAT. Format memakai Given-When-Then.

### 7.1 Halaman Publik

| ID | Kriteria Penerimaan |
| --- | --- |
| AC-P01 | **Given** pengunjung membuka beranda, **When** halaman dimuat, **Then** logo, nama gereja, ringkasan jadwal ibadah, dan tautan menu utama tampil tanpa error. |
| AC-P02 | **Given** pengunjung membuka Sejarah, Visi & Misi, Jadwal Ibadah, Lokasi, atau Kontak, **When** halaman dimuat, **Then** konten sesuai data terbaru dan tampil benar di layar 360 px hingga 1920 px tanpa scroll horizontal. |
| AC-P03 | **Given** pengunjung di ponsel membuka halaman Lokasi, **When** menekan "Petunjuk Arah", **Then** aplikasi peta terbuka dengan titik lokasi gereja yang benar. |
| AC-P04 | **Given** pengunjung menekan nomor telepon/WhatsApp, **When** tautan dipilih, **Then** aplikasi telepon/WhatsApp terbuka dengan nomor yang benar. |
| AC-P05 | **Given** pengunjung (tanpa login) mengakses URL `/admin` atau endpoint data jemaat, **When** permintaan dikirim, **Then** sistem menolak/mengalihkan ke login dan tidak ada data jemaat yang terkirim. |
| AC-P06 | Lighthouse (mobile) untuk halaman publik: Performance ≥ 85, Accessibility ≥ 90, SEO ≥ 90, Best Practices ≥ 90. |
| AC-P07 | Setiap halaman publik memiliki title dan meta description unik, dan halaman admin ber-noindex. |

### 7.2 Autentikasi Admin

| ID | Kriteria Penerimaan |
| --- | --- |
| AC-A01 | **Given** Admin memasukkan kredensial valid, **When** menekan Login, **Then** diarahkan ke dashboard. Dengan kredensial salah, tampil pesan generik "Email atau kata sandi salah" (tanpa menyebut mana yang salah). |
| AC-A02 | **Given** 5 kali login gagal berturut-turut, **When** percobaan ke-6 dilakukan, **Then** akun/IP dikunci sementara (minimal 15 menit) dan kejadian tercatat di audit log. |
| AC-A03 | **Given** Admin tidak aktif 30 menit, **When** melakukan aksi berikutnya, **Then** sesi berakhir dan Admin diminta login ulang. |
| AC-A04 | **Given** Admin menekan Logout, **When** menekan tombol Back pada browser, **Then** halaman terproteksi tidak dapat diakses tanpa login ulang. |
| AC-A05 | Tautan reset kata sandi kedaluwarsa dalam 30 menit dan tidak dapat dipakai dua kali. |

### 7.3 Manajemen Data Jemaat

| ID | Kriteria Penerimaan |
| --- | --- |
| AC-J01 (Create) | **Given** Admin mengisi seluruh kolom wajib dengan benar, **When** menekan Simpan, **Then** data tersimpan, muncul notifikasi sukses, dan jemaat tampil pada daftar wilayah yang dipilih. |
| AC-J02 (Validasi) | **Given** kolom wajib kosong atau format tidak valid (email, No. HP, tanggal), **When** Admin menekan Simpan, **Then** data tidak tersimpan dan pesan kesalahan spesifik tampil di kolom terkait. Validasi juga berlaku di sisi server. |
| AC-J03 (Duplikat) | **Given** data dengan nama dan tanggal lahir yang sama sudah ada, **When** Admin menyimpan, **Then** sistem menampilkan peringatan duplikat dan meminta konfirmasi. |
| AC-J04 (Read) | **Given** Admin membuka daftar jemaat, **When** halaman dimuat, **Then** data tampil terkelompok per wilayah dengan jumlah jemaat tiap wilayah, dengan pagination dan pengurutan berfungsi. |
| AC-J05 (Update) | **Given** Admin mengubah data (termasuk wilayah) lalu menyimpan, **Then** perubahan langsung tampil di daftar dan detail, serta tercatat di audit log (nilai lama/baru, pengguna, waktu). |
| AC-J06 (Delete) | **Given** Admin menekan Hapus, **When** dialog konfirmasi muncul dan Admin menyetujui, **Then** data hilang dari daftar (soft delete), tercatat di audit log, dan dapat dipulihkan dalam 30 hari. Jika Admin membatalkan, tidak ada perubahan. |
| AC-J07 (Otorisasi) | Seluruh operasi CRUD melalui API ditolak (HTTP 401/403) untuk permintaan tanpa sesi Admin valid. Hal ini diverifikasi dengan pengujian otomatis. |

### 7.4 Pencarian & Filter

| ID | Kriteria Penerimaan |
| --- | --- |
| AC-S01 | **Given** Admin mengetik sebagian nama (misalnya "yoh"), **When** pencarian dijalankan, **Then** hasil berisi seluruh nama yang mengandung kata tersebut (tidak sensitif huruf besar-kecil). |
| AC-S02 | **Given** Admin memilih filter wilayah, **Then** hanya jemaat dari wilayah tersebut yang tampil. Kombinasi pencarian nama + filter wilayah + status menghasilkan irisan data yang benar. |
| AC-S03 | **Given** tidak ada data yang cocok, **Then** tampil pesan "Data tidak ditemukan" dengan opsi Reset Filter. |
| AC-S04 | Dengan 5.000 data uji, waktu respons pencarian/filter ≤ 1 detik pada persentil ke-95. |

### 7.5 Manajemen Wilayah

| ID | Kriteria Penerimaan |
| --- | --- |
| AC-W01 | Admin dapat menambah, mengubah, dan menonaktifkan/menghapus wilayah. Nama wilayah unik (sistem menolak duplikat). |
| AC-W02 | **Given** wilayah masih memiliki jemaat, **When** Admin mencoba menghapusnya, **Then** sistem menolak dan menawarkan pemindahan jemaat atau penonaktifan wilayah. |
| AC-W03 | Wilayah baru langsung tersedia pada pilihan formulir jemaat dan filter. |

### 7.6 Kriteria Non-Fungsional (Quality Gate Rilis)

| ID | Kriteria |
| --- | --- |
| AC-N01 | Seluruh trafik HTTPS. Pemindaian keamanan (OWASP ZAP atau setara) tidak menemukan kerentanan tingkat High/Critical yang belum diperbaiki. |
| AC-N02 | Backup otomatis berjalan dan uji pemulihan berhasil dilakukan minimal sekali sebelum go-live. |
| AC-N03 | Uji beban: 200 pengguna bersamaan pada halaman publik dengan error rate < 1% dan waktu respons p95 ≤ 2 detik. |
| AC-N04 | Pengujian lintas perangkat/browser sesuai daftar kompatibilitas lulus tanpa cacat berprioritas tinggi. |
| AC-N05 | Tidak ada bug Critical/High terbuka. Cakupan test otomatis logika inti ≥ 70%. |
| AC-N06 | Data jemaat awal berhasil dimigrasi: jumlah record di sistem sama dengan sumber data (rekonsiliasi disetujui Admin). |
| AC-N07 | Admin lulus pelatihan, dengan panduan pengguna terserah-terima dan UAT ditandatangani Product Owner. |

### 7.7 Definition of Done (Umum)

Sebuah fitur dianggap selesai bila: (1) kode melewati code review, (2) pengujian otomatis dan manual lulus, (3) sesuai desain UI/UX yang disetujui, (4) memenuhi kriteria penerimaan di atas, (5) dokumentasi diperbarui, dan (6) telah disetujui Product Owner di lingkungan staging.

---

## Lampiran A: Usulan Tahapan Pengembangan

| Fase | Cakupan | Perkiraan |
| --- | --- | --- |
| 0. Discovery | Konfirmasi kebutuhan, atribut data jemaat, konten, dan kebijakan privasi bersama pengurus | 1-2 minggu |
| 1. Desain | Wireframe, desain UI/UX, dan persetujuan | 2 minggu |
| 2. Pengembangan Publik | Halaman profil publik dan SEO | 2-3 minggu |
| 3. Pengembangan Admin | Autentikasi, CRUD jemaat dan wilayah, pencarian/filter | 4 minggu |
| 4. Hardening & QA | Uji keamanan, performa, UAT, migrasi data | 2 minggu |
| 5. Rilis & Serah Terima | Go-live, pelatihan Admin, dokumentasi, monitoring | 1 minggu |

*Perkiraan bersifat indikatif dan perlu divalidasi tim developer.*

## Lampiran B: Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
| --- | --- | --- |
| Kebocoran data pribadi jemaat | Tinggi | Otorisasi server-side, enkripsi, audit log, pelatihan Admin, pemindaian keamanan |
| Kualitas data lama buruk (duplikat, tidak lengkap) | Sedang | Template impor, deteksi duplikat, tahap pembersihan data sebelum migrasi |
| Konten publik terlambat disediakan | Sedang | Tetapkan tenggat konten di Fase 0, gunakan konten placeholder |
| Admin kesulitan menggunakan sistem | Sedang | UI sederhana, pelatihan, panduan bergambar |
| Ketergantungan pada satu Admin/developer | Sedang | Minimal 2 Admin, dokumentasi lengkap, kode di repositori milik gereja |
| Data hilang akibat kegagalan server | Tinggi | Backup harian terenkripsi dan uji pemulihan berkala |

## Lampiran C: Pertanyaan Terbuka untuk Pengurus Gereja

1. Atribut data jemaat apa lagi yang dibutuhkan (misalnya keluarga/KK, pekerjaan, pelayanan/komsel, riwayat sidi)?
2. Berapa perkiraan jumlah jemaat dan jumlah wilayah saat ini?
3. Siapa saja yang akan menjadi Admin, dan apakah perlu Admin utama dengan hak lebih luas?
4. Apakah perlu membatasi akses Ketua Wilayah hanya pada wilayahnya di rilis berikutnya?
5. Bagaimana kebijakan persetujuan jemaat atas penyimpanan data pribadi mereka?
6. Apakah perlu halaman berita, renungan, atau galeri pada fase berikutnya?
7. Apakah gereja sudah memiliki domain dan hosting, atau perlu direkomendasikan?
