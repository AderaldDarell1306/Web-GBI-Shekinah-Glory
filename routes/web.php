<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\ArsipSuratController;
use App\Http\Controllers\Admin\CoolController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\JemaatController;
use App\Http\Controllers\Admin\KalenderController;
use App\Http\Controllers\Admin\KeuanganController;
use App\Http\Controllers\Admin\PelayananController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\SakramenController;
use App\Http\Controllers\Admin\WartaController;
use App\Http\Controllers\Admin\WebsiteContentController;
use App\Http\Controllers\Admin\WilayahController;
use App\Http\Controllers\Admin\MajelisController;
use App\Http\Controllers\Admin\InventarisController;
use App\Http\Controllers\Admin\PeminjamanInventarisController;
use App\Http\Controllers\Admin\DokumentasiFotoController;
use App\Http\Controllers\Admin\PresensiPelayanController;
use App\Http\Controllers\Admin\UlangTahunController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - GBI Shekinah Glory
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. RUTE PUBLIK (Tanpa Autentikasi)
// ==========================================
Route::get('/', [PublicController::class, 'index'])->name('public.beranda');
Route::get('/tentang', [PublicController::class, 'tentang'])->name('public.tentang');
Route::get('/jemaat', [PublicController::class, 'jemaat'])->name('public.jemaat');
Route::get('/keuangan', [PublicController::class, 'keuangan'])->name('public.keuangan');
Route::get('/lokasi', [PublicController::class, 'lokasi'])->name('public.lokasi');
Route::get('/jadwal-ibadah', [PublicController::class, 'jadwal'])->name('public.jadwal');
Route::get('/kontak', [PublicController::class, 'kontak'])->name('public.kontak');
Route::post('/kontak/kirim-pesan', [PublicController::class, 'kirimPesan'])->name('public.kirim-pesan');
Route::post('/kontak/kirim-doa', [PublicController::class, 'kirimPesan'])->name('public.kirim-doa');

// Portal Pendaftaran Mandiri Jemaat (Sakramen, Jemaat Baru)
Route::get('/pendaftaran', [PublicController::class, 'pendaftaran'])->name('public.pendaftaran');
Route::post('/pendaftaran', [PublicController::class, 'submitPendaftaran'])->name('public.pendaftaran.submit');
Route::get('/pendaftaran/sukses/{id}', [PublicController::class, 'pendaftaranSukses'])->name('public.pendaftaran.sukses');

// Modul Layanan Publik Tambahan (PRD v2.2 - Cek Surat & Pinjam Inventaris)
Route::prefix('layanan')->name('public.layanan.')->group(function () {
    Route::get('/surat', [PublicController::class, 'cekSurat'])->name('surat');
    Route::post('/surat/cek', [PublicController::class, 'cekSurat'])->name('surat.cek');
    Route::get('/pinjam-inventaris', [PublicController::class, 'pinjamInventaris'])->name('pinjam-inventaris');
    Route::post('/pinjam-inventaris', [PublicController::class, 'submitPinjamInventaris'])->name('pinjam-inventaris.submit');
    Route::get('/pinjam-inventaris/sukses/{id}', [PublicController::class, 'pinjamInventarisSukses'])->name('pinjam-inventaris.sukses');
});

// Warta Jemaat Digital & Buletin (Publik)
Route::get('/warta', [PublicController::class, 'warta'])->name('public.warta');
Route::get('/warta/{id}', [PublicController::class, 'wartaDetail'])->name('public.warta.detail');

// Galeri Dokumentasi Foto Kegiatan (Publik - PRD Modul 10 AC-R06)
Route::get('/dokumentasi', [PublicController::class, 'dokumentasi'])->name('public.dokumentasi');

// ==========================================
// 2. RUTE AUTENTIKASI ADMIN
// ==========================================
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/admin/masuk', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// ==========================================
// 3. RUTE DASHBOARD & MANAJEMEN INTERNAL (Wajib Login)
// ==========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Jemaat
    Route::get('/jemaat/template/download', [JemaatController::class, 'downloadTemplate'])->name('jemaat.template');
    Route::post('/jemaat/import/csv', [JemaatController::class, 'importCsv'])->name('jemaat.import');
    Route::get('/jemaat/export/csv', [JemaatController::class, 'exportCsv'])->name('jemaat.export');
    Route::resource('jemaat', JemaatController::class);

    // Modul Khusus: Ucapan & Kartu Ulang Tahun Jemaat (Dedicated Menu)
    Route::get('/ulang-tahun', [UlangTahunController::class, 'index'])->name('ulang-tahun.index');
    Route::put('/ulang-tahun/{id}', [UlangTahunController::class, 'update'])->name('ulang-tahun.update');
    Route::post('/ulang-tahun/{id}/reset', [UlangTahunController::class, 'reset'])->name('ulang-tahun.reset');

    // Modul 2: Sakramen, Dokumen Pastoral & Cetak Sertifikat
    Route::prefix('sakramen')->name('sakramen.')->group(function () {
        Route::get('/', [SakramenController::class, 'index'])->name('index');
        
        // Baptis Air
        Route::post('/baptis', [SakramenController::class, 'storeBaptis'])->name('baptis.store');
        Route::get('/baptis/{id}/print', [SakramenController::class, 'printBaptis'])->name('baptis.print');
        Route::delete('/baptis/{id}', [SakramenController::class, 'destroyBaptis'])->name('baptis.destroy');

        // Penyerahan Anak
        Route::post('/penyerahan', [SakramenController::class, 'storePenyerahan'])->name('penyerahan.store');
        Route::get('/penyerahan/{id}/print', [SakramenController::class, 'printPenyerahan'])->name('penyerahan.print');
        Route::delete('/penyerahan/{id}', [SakramenController::class, 'destroyPenyerahan'])->name('penyerahan.destroy');

        // Pernikahan Kudus & Bimbingan Pra-Nikah
        Route::post('/nikah', [SakramenController::class, 'storeNikah'])->name('nikah.store');
        Route::get('/nikah/{id}/print', [SakramenController::class, 'printNikah'])->name('nikah.print');
        Route::delete('/nikah/{id}', [SakramenController::class, 'destroyNikah'])->name('nikah.destroy');

        // Surat Pastoral & Translokasi
        Route::post('/surat', [SakramenController::class, 'storeSurat'])->name('surat.store');
        Route::get('/surat/{id}/print', [SakramenController::class, 'printSurat'])->name('surat.print');
        Route::delete('/surat/{id}', [SakramenController::class, 'destroySurat'])->name('surat.destroy');

        // Update status pendaftaran sakramen online
        Route::put('/pendaftaran/{id}/status', [SakramenController::class, 'updateStatusPendaftaran'])->name('pendaftaran.status');
    });

    // Modul 4: Penjadwalan Pelayanan Ibadah (Service Rota) & Anti-Bentrok
    Route::get('/presensi-pelayan', [PresensiPelayanController::class, 'index'])->name('presensi.index');
    Route::post('/presensi-pelayan', [PresensiPelayanController::class, 'store'])->name('presensi.store');
    Route::resource('pelayanan', PelayananController::class);

    // Modul 3: Keuangan & Persembahan (Finance & Tithe Management)
    Route::get('/keuangan/laporan', [KeuanganController::class, 'laporan'])->name('keuangan.laporan');
    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
    Route::post('/keuangan', [KeuanganController::class, 'store'])->name('keuangan.store');
    Route::put('/keuangan/{id}', [KeuanganController::class, 'update'])->name('keuangan.update');
    Route::delete('/keuangan/{id}', [KeuanganController::class, 'destroy'])->name('keuangan.destroy');

    // Modul 5: Kalender Liturgi & Acara Tahunan
    Route::get('/kalender', [KalenderController::class, 'index'])->name('kalender.index');
    Route::post('/kalender', [KalenderController::class, 'store'])->name('kalender.store');
    Route::put('/kalender/{id}', [KalenderController::class, 'update'])->name('kalender.update');
    Route::delete('/kalender/{id}', [KalenderController::class, 'destroy'])->name('kalender.destroy');

    // Modul 6: Warta Digital & Broadcast Jemaat
    Route::resource('warta', WartaController::class);

    // Modul Pengumuman & Berita Gereja
    Route::post('/pengumuman/{id}/toggle-pin', [PengumumanController::class, 'togglePin'])->name('pengumuman.toggle-pin');
    Route::resource('pengumuman', PengumumanController::class);

    // Modul 7: Komunitas Sel (COOL) & Absensi Kehadiran Jemaat
    Route::resource('cool', CoolController::class);

    // Modul 8: Arsip Surat Keluar & Masuk
    Route::get('/arsip/cetak', [ArsipSuratController::class, 'cetakBukuAgenda'])->name('arsip.cetak');
    Route::get('/arsip', [ArsipSuratController::class, 'index'])->name('arsip.index');
    Route::post('/arsip', [ArsipSuratController::class, 'store'])->name('arsip.store');
    Route::put('/arsip/{id}', [ArsipSuratController::class, 'update'])->name('arsip.update');
    Route::delete('/arsip/{id}', [ArsipSuratController::class, 'destroy'])->name('arsip.destroy');

    // Manajemen Wilayah / Sektor
    Route::get('/wilayah', [WilayahController::class, 'index'])->name('wilayah.index');
    Route::post('/wilayah', [WilayahController::class, 'store'])->name('wilayah.store');
    Route::put('/wilayah/{id}', [WilayahController::class, 'update'])->name('wilayah.update');
    Route::delete('/wilayah/{id}', [WilayahController::class, 'destroy'])->name('wilayah.destroy');

    // Manajemen Jadwal Ibadah
    Route::resource('jadwal', JadwalController::class);
    Route::post('/jadwal/{id}/toggle-status', [JadwalController::class, 'toggleStatus'])->name('jadwal.toggle-status');

    // Manajemen Pengguna & Hak Akses (RBAC)
    Route::resource('users', UserController::class);

    // PRD v2.1 2.4: Data Majelis Pelayanan
    Route::resource('majelis', MajelisController::class);

    // PRD v2.1 Modul 9: Inventaris Gereja & Laporan Pinjam Pakai
    Route::get('/inventaris/laporan-pinjam', [InventarisController::class, 'laporanPinjam'])->name('inventaris.laporan-pinjam');
    Route::resource('inventaris', InventarisController::class);
    Route::resource('peminjaman-inventaris', PeminjamanInventarisController::class);

    // PRD v2.1 Modul 10: Dokumentasi Foto Kegiatan Gereja
    Route::resource('dokumentasi', DokumentasiFotoController::class);

    // CMS & Pengaturan Konten Website (Banner, Visi Misi, Kontak, Lokasi, Sejarah)
    Route::get('/konten', [WebsiteContentController::class, 'index'])->name('konten.index');
    Route::post('/konten', [WebsiteContentController::class, 'update'])->name('konten.update');
});
