<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Jemaat;
use App\Models\SakramenBaptis;
use App\Models\PenyerahanAnak;
use App\Models\Pernikahan;
use App\Models\SuratPastoral;
use App\Models\PendaftaranLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SakramenController extends Controller
{
    /**
     * Dashboard Utama Sakramen & Dokumen Pastoral
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'baptis');

        $baptisList = SakramenBaptis::with('jemaat')->latest('tanggal_baptis')->paginate(15, ['*'], 'baptis_page');
        $penyerahanList = PenyerahanAnak::with('jemaat')->latest('tanggal_penyerahan')->paginate(15, ['*'], 'penyerahan_page');
        $nikahList = Pernikahan::with(['pengantinPria', 'pengantinWanita'])->latest('tanggal_pemberkatan')->paginate(15, ['*'], 'nikah_page');
        $suratList = SuratPastoral::with('jemaat')->latest('tanggal_surat')->paginate(15, ['*'], 'surat_page');
        $pendaftaranList = PendaftaranLayanan::latest()->paginate(15, ['*'], 'pendaftaran_page');

        // Daftar jemaat aktif untuk pilihan dropdown
        $jemaats = Jemaat::where('status_keanggotaan', 'aktif')->orderBy('nama_lengkap')->get();

        return view('admin.sakramen.index', compact(
            'tab',
            'baptisList',
            'penyerahanList',
            'nikahList',
            'suratList',
            'pendaftaranList',
            'jemaats'
        ));
    }

    // ==========================================
    // 1. SAKRAMEN BAPTISAN AIR
    // ==========================================
    public function storeBaptis(Request $request)
    {
        $validated = $request->validate([
            'no_sertifikat' => 'required|string|max:100|unique:sakramen_baptis,no_sertifikat',
            'jemaat_id' => 'nullable|exists:jemaat,id',
            'nama_lengkap' => 'required|string|max:150',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'tanggal_baptis' => 'required|date',
            'tempat_baptis' => 'required|string|max:150',
            'pendeta_pelayan' => 'required|string|max:150',
            'nama_ayah' => 'nullable|string|max:150',
            'nama_ibu' => 'nullable|string|max:150',
            'saksi' => 'nullable|string|max:150',
            'catatan' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();
        $baptis = SakramenBaptis::create($validated);

        // Jika jemaat_id ada, update juga status_baptis di tabel jemaat
        if (!empty($validated['jemaat_id'])) {
            Jemaat::where('id', $validated['jemaat_id'])->update([
                'status_baptis' => 'Sudah Baptis',
                'tanggal_baptis' => $validated['tanggal_baptis'],
            ]);
        }

        AuditLog::log('create', 'sakramen_baptis', "Mencatat baptisan air: {$baptis->nama_lengkap} (No: {$baptis->no_sertifikat})");

        return redirect()->route('admin.sakramen.index', ['tab' => 'baptis'])->with('success', "Data baptisan air {$baptis->nama_lengkap} berhasil disimpan!");
    }

    public function printBaptis($id)
    {
        $data = SakramenBaptis::with('jemaat')->findOrFail($id);
        return view('admin.sakramen.print-baptis', compact('data'));
    }

    public function destroyBaptis($id)
    {
        $data = SakramenBaptis::findOrFail($id);
        $data->delete();
        AuditLog::log('delete', 'sakramen_baptis', "Menghapus data baptisan: {$data->nama_lengkap}");
        return back()->with('success', 'Data baptisan berhasil dihapus.');
    }

    // ==========================================
    // 2. PENYERAHAN ANAK
    // ==========================================
    public function storePenyerahan(Request $request)
    {
        $validated = $request->validate([
            'no_sertifikat' => 'required|string|max:100|unique:penyerahan_anak,no_sertifikat',
            'jemaat_id' => 'nullable|exists:jemaat,id',
            'nama_anak' => 'required|string|max:150',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'tanggal_penyerahan' => 'required|date',
            'tempat_penyerahan' => 'required|string|max:150',
            'nama_ayah' => 'required|string|max:150',
            'nama_ibu' => 'required|string|max:150',
            'pendeta_pelayan' => 'required|string|max:150',
            'saksi' => 'nullable|string|max:150',
            'catatan' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();
        $anak = PenyerahanAnak::create($validated);

        AuditLog::log('create', 'penyerahan_anak', "Mencatat penyerahan anak: {$anak->nama_anak} (No: {$anak->no_sertifikat})");

        return redirect()->route('admin.sakramen.index', ['tab' => 'penyerahan'])->with('success', "Data penyerahan anak {$anak->nama_anak} berhasil disimpan!");
    }

    public function printPenyerahan($id)
    {
        $data = PenyerahanAnak::with('jemaat')->findOrFail($id);
        return view('admin.sakramen.print-penyerahan', compact('data'));
    }

    public function destroyPenyerahan($id)
    {
        $data = PenyerahanAnak::findOrFail($id);
        $data->delete();
        AuditLog::log('delete', 'penyerahan_anak', "Menghapus data penyerahan anak: {$data->nama_anak}");
        return back()->with('success', 'Data penyerahan anak berhasil dihapus.');
    }

    // ==========================================
    // 3. PERNIKAHAN KUDUS & PRA-NIKAH
    // ==========================================
    public function storeNikah(Request $request)
    {
        $validated = $request->validate([
            'no_sertifikat' => 'required|string|max:100|unique:pernikahan,no_sertifikat',
            'pengantin_pria_jemaat_id' => 'nullable|exists:jemaat,id',
            'pengantin_pria_nama' => 'required|string|max:150',
            'pengantin_pria_ayah' => 'nullable|string|max:150',
            'pengantin_pria_ibu' => 'nullable|string|max:150',
            'pengantin_wanita_jemaat_id' => 'nullable|exists:jemaat,id',
            'pengantin_wanita_nama' => 'required|string|max:150',
            'pengantin_wanita_ayah' => 'nullable|string|max:150',
            'pengantin_wanita_ibu' => 'nullable|string|max:150',
            'tanggal_pemberkatan' => 'required|date',
            'tempat_pemberkatan' => 'required|string|max:150',
            'pendeta_pemberkat' => 'required|string|max:150',
            'saksi_1' => 'nullable|string|max:150',
            'saksi_2' => 'nullable|string|max:150',
            'status_bimbingan' => 'required|in:belum,proses,selesai',
            'catatan_konseling' => 'nullable|string',
            'berkas_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        if ($request->hasFile('berkas_file')) {
            $path = $request->file('berkas_file')->store('uploads/nikah', 'public');
            $validated['berkas_legalitas'] = $path;
        }

        $validated['created_by'] = Auth::id();
        $nikah = Pernikahan::create($validated);

        // Update status jemaat jika ada
        if (!empty($validated['pengantin_pria_jemaat_id'])) {
            Jemaat::where('id', $validated['pengantin_pria_jemaat_id'])->update(['status_pernikahan' => 'menikah', 'nikah_masehi' => 'Sudah']);
        }
        if (!empty($validated['pengantin_wanita_jemaat_id'])) {
            Jemaat::where('id', $validated['pengantin_wanita_jemaat_id'])->update(['status_pernikahan' => 'menikah', 'nikah_masehi' => 'Sudah']);
        }

        AuditLog::log('create', 'pernikahan', "Mencatat pernikahan kudus: {$nikah->pengantin_pria_nama} & {$nikah->pengantin_wanita_nama}");

        return redirect()->route('admin.sakramen.index', ['tab' => 'nikah'])->with('success', "Data pernikahan kudus berhasil disimpan!");
    }

    public function printNikah($id)
    {
        $data = Pernikahan::with(['pengantinPria', 'pengantinWanita'])->findOrFail($id);
        return view('admin.sakramen.print-nikah', compact('data'));
    }

    public function destroyNikah($id)
    {
        $data = Pernikahan::findOrFail($id);
        $data->delete();
        AuditLog::log('delete', 'pernikahan', "Menghapus data pernikahan kudus: {$data->no_sertifikat}");
        return back()->with('success', 'Data pernikahan kudus berhasil dihapus.');
    }

    // ==========================================
    // 4. SURAT PASTORAL & TRANSLOKASI
    // ==========================================
    public function storeSurat(Request $request)
    {
        $validated = $request->validate([
            'no_surat' => 'required|string|max:100|unique:surat_pastoral,no_surat',
            'jenis_surat' => 'required|in:surat_keterangan,surat_pindah,pengantar_keanggotaan',
            'jemaat_id' => 'nullable|exists:jemaat,id',
            'nama_jemaat' => 'required|string|max:150',
            'tanggal_surat' => 'required|date',
            'tujuan_gereja_instansi' => 'nullable|string|max:200',
            'keperluan_alasan' => 'nullable|string',
            'penandatangan' => 'required|string|max:150',
            'jabatan_penandatangan' => 'required|string|max:100',
        ]);

        $validated['created_by'] = Auth::id();
        $surat = SuratPastoral::create($validated);

        // Jika surat pindah, ubah status jemaat menjadi pindah
        if ($validated['jenis_surat'] === 'surat_pindah' && !empty($validated['jemaat_id'])) {
            Jemaat::where('id', $validated['jemaat_id'])->update(['status_keanggotaan' => 'pindah']);
        }

        AuditLog::log('create', 'surat_pastoral', "Menerbitkan surat pastoral: {$surat->no_surat} ({$surat->jenis_surat_label})");

        return redirect()->route('admin.sakramen.index', ['tab' => 'surat'])->with('success', "Surat pastoral {$surat->no_surat} berhasil dibuat!");
    }

    public function printSurat($id)
    {
        $data = SuratPastoral::with('jemaat')->findOrFail($id);
        return view('admin.sakramen.print-surat', compact('data'));
    }

    public function destroySurat($id)
    {
        $data = SuratPastoral::findOrFail($id);
        $data->delete();
        AuditLog::log('delete', 'surat_pastoral', "Menghapus surat pastoral: {$data->no_surat}");
        return back()->with('success', 'Surat pastoral berhasil dihapus.');
    }

    // ==========================================
    // 5. UPDATE STATUS PENDAFTARAN LAYANAN
    // ==========================================
    public function updateStatusPendaftaran(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,disetujui,ditolak',
            'catatan_admin' => 'nullable|string',
        ]);

        $item = PendaftaranLayanan::findOrFail($id);
        $item->update([
            'status' => $request->input('status'),
            'catatan_admin' => $request->input('catatan_admin'),
        ]);

        return back()->with('success', "Status pendaftaran {$item->kode_pendaftaran} berhasil diperbarui.");
    }
}

