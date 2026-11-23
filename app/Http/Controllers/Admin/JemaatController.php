<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Jemaat;
use App\Models\Wilayah;
use App\Support\SimpleXlsxReader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JemaatController extends Controller
{
    /**
     * Tampilkan daftar jemaat dengan pencarian dan filter
     */
    public function index(Request $request)
    {
        $query = Jemaat::with('wilayah');
        $user = Auth::user();

        // RBAC: Jika Ketua COOL, hanya melihat jemaat dalam wilayah pengampuannya (AC3)
        if ($user && $user->isKetuaCool() && $user->wilayah_id) {
            $query->where('wilayah_id', $user->wilayah_id);
        }

        // Pencarian Nama / No KK / NIJ / Pekerjaan / Keahlian / Status Keluarga
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_kk', 'like', "%{$search}%")
                  ->orWhere('nomor_induk', 'like', "%{$search}%")
                  ->orWhere('pekerjaan', 'like', "%{$search}%")
                  ->orWhere('keahlian_khusus', 'like', "%{$search}%")
                  ->orWhere('status_keluarga', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        // Filter Wilayah Besar (Namosain, Rumah 7, Osmok)
        if ($wilayahBesar = $request->input('wilayah_besar')) {
            $query->whereHas('wilayah', function ($q) use ($wilayahBesar) {
                $q->where('wilayah_besar', $wilayahBesar);
            });
        }

        // Filter Wilayah Spesifik
        if ($wilayahId = $request->input('wilayah_id')) {
            $query->where('wilayah_id', $wilayahId);
        }

        // Filter Berdasarkan 4 Digit KK (Satu Keluarga Utuh)
        if ($kodeKeluarga = $request->input('kode_keluarga')) {
            $query->where('no_kk', 'like', "{$kodeKeluarga}%");
        }

        // Filter Status Keanggotaan
        if ($status = $request->input('status')) {
            $query->where('status_keanggotaan', $status);
        }

        // Filter Jenis Kelamin
        if ($gender = $request->input('gender')) {
            $query->where('jenis_kelamin', $gender);
        }

        // Filter Status Baptis
        if ($baptis = $request->input('status_baptis')) {
            $query->where('status_baptis', $baptis);
        }

        // Filter Kategorial Pelayanan (Kaum Bapak, Kaum Ibu, Pemuda & Remaja, Anak/ABI)
        if ($kategorial = $request->input('kategorial')) {
            if ($kategorial === 'bapak') {
                $query->where(function ($q) {
                    $q->where('keterangan', 'like', '%BAPAK%')
                      ->orWhere('status_keluarga', 'like', '%BAPAK%')
                      ->orWhere('status_keluarga', 'like', '%KEPALA%')
                      ->orWhere(function ($sub) {
                          $sub->where('jenis_kelamin', 'L')
                              ->whereIn('status_pernikahan', ['menikah', 'duda']);
                      });
                });
            } elseif ($kategorial === 'ibu') {
                $query->where(function ($q) {
                    $q->where('keterangan', 'like', '%IBU%')
                      ->orWhere('status_keluarga', 'like', '%IBU%')
                      ->orWhere('status_keluarga', 'like', '%ISTRI%')
                      ->orWhere(function ($sub) {
                          $sub->where('jenis_kelamin', 'P')
                              ->whereIn('status_pernikahan', ['menikah', 'janda']);
                      });
                });
            } elseif ($kategorial === 'pemuda') {
                $query->where(function ($q) {
                    $q->where('keterangan', 'like', '%PEMUDA%')
                      ->orWhere('keterangan', 'like', '%REMAJA%')
                      ->orWhere('keterangan', 'like', '%PEMMUDA%')
                      ->orWhere(function ($sub) {
                          $sub->where('status_pernikahan', 'belum_menikah')
                              ->where('status_keluarga', 'like', '%ANAK%')
                              ->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 12 AND 30');
                      });
                });
            } elseif ($kategorial === 'anak') {
                $query->where(function ($q) {
                    $q->where('keterangan', 'like', '%ABI%')
                      ->orWhere('status_baptis', 'like', '%PENYERAHAN%')
                      ->orWhereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) < 12');
                });
            }
        }

        // Sorting
        $sortColumn = $request->input('sort', 'no_kk');
        $sortDirection = $request->input('direction', 'asc');
        $allowedSorts = ['no_kk', 'nama_lengkap', 'nomor_induk', 'tanggal_lahir', 'status_keluarga', 'status_keanggotaan', 'created_at'];

        if (in_array($sortColumn, $allowedSorts)) {
            $query->orderBy($sortColumn, $sortDirection === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('no_kk', 'asc')->orderBy('nama_lengkap', 'asc');
        }

        $perPage = (int) $request->input('per_page', 25);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 25;

        $jemaats = $query->paginate($perPage)->withQueryString();

        $wilayahList = Wilayah::where('status', 'aktif')->orderBy('kode_prefix')->get();
        $wilayahGrouped = $wilayahList->groupBy('wilayah_besar');

        // Rekapitulasi per 3 Wilayah Besar
        $rekapWilayahBesar = [
            'Namosain' => Jemaat::whereHas('wilayah', fn($q) => $q->where('wilayah_besar', 'Namosain'))->count(),
            'Rumah 7' => Jemaat::whereHas('wilayah', fn($q) => $q->where('wilayah_besar', 'Rumah 7'))->count(),
            'Osmok' => Jemaat::whereHas('wilayah', fn($q) => $q->where('wilayah_besar', 'Osmok'))->count(),
        ];

        // Rekapitulasi Kategorial Pelayanan
        $rekapKategorial = Jemaat::getKategorialStats($wilayahId, $wilayahBesar);

        // Rekapitulasi Demografi Lengkap (PRD v2.2 Tahap 5)
        $demo = Jemaat::getComprehensiveDemographics($wilayahId, $wilayahBesar);

        return view('admin.jemaat.index', compact('jemaats', 'wilayahList', 'wilayahGrouped', 'rekapWilayahBesar', 'rekapKategorial', 'demo'));
    }

    /**
     * Formulir Tambah Jemaat Baru
     */
    public function create()
    {
        if (!Auth::user()->canCrudJemaat()) {
            abort(403, 'Akses ditolak: Akun Jemaat / non-admin hanya memiliki hak akses Read-Only untuk data jemaat.');
        }

        $wilayahList = Wilayah::where('status', 'aktif')->orderBy('nama_wilayah')->get();

        // Ambil daftar kepala keluarga untuk fitur "Tambah anggota keluarga ke KK yang sudah ada" (PRD v2.1)
        $kepalaKeluargaList = Jemaat::where(function($q) {
            $q->where('status_keluarga', 'like', '%KEPALA%')
              ->orWhere('status_keluarga', 'like', '%SUAMI%')
              ->orWhere('no_kk', 'like', '%01');
        })
        ->orderBy('nama_lengkap')
        ->get(['id', 'nama_lengkap', 'no_kk', 'wilayah_id', 'alamat', 'status_keluarga']);

        // Peta nomor urut anggota tertinggi per 4-digit nomor KK
        $maxSeqByPrefix = Jemaat::selectRaw('SUBSTRING(no_kk, 1, 4) as prefix, MAX(CAST(SUBSTRING(no_kk, 5, 2) AS UNSIGNED)) as max_seq')
            ->whereRaw('LENGTH(no_kk) >= 6')
            ->groupBy('prefix')
            ->pluck('max_seq', 'prefix');

        // Peta No. KK Baru berikutnya per Wilayah ID (Otomatis: [2 digit Prefix][2 digit No. Keluarga Baru][01])
        $nextKKByWilayah = [];
        foreach ($wilayahList as $w) {
            $prefix = $w->kode_prefix;
            $maxFam = Jemaat::selectRaw('MAX(CAST(SUBSTRING(no_kk, 3, 2) AS UNSIGNED)) as max_fam')
                ->where('no_kk', 'like', $prefix . '%')
                ->whereRaw('LENGTH(no_kk) >= 6')
                ->value('max_fam');
            $nextFamNum = ($maxFam ? (int)$maxFam : 0) + 1;
            $nextFamStr = str_pad($nextFamNum, 2, '0', STR_PAD_LEFT);
            $nextKKByWilayah[$w->id] = $prefix . $nextFamStr . '01';
        }

        // Peta seluruh No. KK yang sudah terdaftar untuk validasi anti-duplikasi instan di browser
        $existingKKs = Jemaat::whereNotNull('no_kk')->where('no_kk', '!=', '')->pluck('nama_lengkap', 'no_kk');

        // Generate Nomor Induk Jemaat Otomatis jika diperlukan: JMT-YYYY-XXXX
        $currentYear = date('Y');
        $latestRecord = Jemaat::withTrashed()
            ->whereYear('created_at', $currentYear)
            ->latest('id')
            ->first();

        $nextNumber = 1;
        if ($latestRecord && preg_match('/JMT-\d{4}-(\d+)/', $latestRecord->nomor_induk, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }
        $autoNij = sprintf('JMT-%s-%04d', $currentYear, $nextNumber);

        return view('admin.jemaat.create', compact('wilayahList', 'autoNij', 'kepalaKeluargaList', 'maxSeqByPrefix', 'nextKKByWilayah', 'existingKKs'));
    }

    /**
     * Simpan Data Jemaat Baru
     */
    public function store(Request $request)
    {
        if (!Auth::user()->canCrudJemaat()) {
            abort(403, 'Akses ditolak: Akun Jemaat / non-admin hanya memiliki hak akses Read-Only untuk data jemaat.');
        }

        $validated = $request->validate([
            'no_kk' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:20',
            'nama_lengkap' => 'required|string|max:150',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date|before_or_equal:today',
            'status_keluarga' => 'nullable|string|max:100',
            'status_pernikahan' => 'nullable|in:belum_menikah,menikah,duda,janda',
            'status_baptis' => 'nullable|string|max:50',
            'tanggal_baptis' => 'nullable|date',
            'nikah_masehi' => 'nullable|string|max:50',
            'pendidikan' => 'nullable|string|max:100',
            'pekerjaan' => 'nullable|string|max:150',
            'keahlian_khusus' => 'nullable|string|max:150',
            'penghasilan_per_bulan' => 'nullable|string|max:100',
            'lain_lain' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'wilayah_id' => 'nullable|exists:wilayah,id',
            'status_keanggotaan' => 'required|in:aktif,tidak_aktif,pindah,meninggal',
            'tipe_keanggotaan' => 'nullable|in:jemaat_tetap,simpatisan,mutasi',
            'kategori_khusus' => 'nullable|string|max:50',
            'tanggal_bergabung' => 'nullable|date',
            'catatan' => 'nullable|string',
            'ucapan_ultah' => 'nullable|string',
            'ignore_duplicate' => 'nullable|boolean',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh di masa depan.',
        ]);

        // Cek duplikasi No. KK / ID Jemaat
        if (!empty($validated['no_kk'])) {
            $existingKkJemaat = Jemaat::where('no_kk', $validated['no_kk'])->first();
            if ($existingKkJemaat) {
                return back()->withInput()->withErrors([
                    'no_kk' => "Nomor KK/ID Jemaat '{$validated['no_kk']}' sudah terdaftar atas nama {$existingKkJemaat->nama_lengkap}. Harap gunakan nomor urut berikutnya agar tidak terjadi pendobelan data.",
                ]);
            }
        }

        // Cek potensi duplikat
        if (!$request->boolean('ignore_duplicate') && !empty($validated['tanggal_lahir'])) {
            $duplicate = Jemaat::where('nama_lengkap', $validated['nama_lengkap'])
                ->whereDate('tanggal_lahir', $validated['tanggal_lahir'])
                ->first();

            if ($duplicate) {
                return back()->withInput()->with('duplicate_warning', [
                    'message' => "Terdeteksi potensi duplikat: Jemaat dengan nama '{$duplicate->nama_lengkap}' dan tanggal lahir sama sudah terdaftar (No KK: {$duplicate->no_kk}, NIJ: {$duplicate->nomor_induk}).",
                    'existing_id' => $duplicate->id,
                ]);
            }
        }

        // Otomatis tentukan wilayah_id dari 2 digit pertama no_kk (misal: 31xxxx -> Osmok Sub Wilayah 1)
        if (empty($validated['wilayah_id']) && !empty($validated['no_kk']) && strlen($validated['no_kk']) >= 2) {
            $prefix = substr($validated['no_kk'], 0, 2);
            $matchedWilayah = Wilayah::where('kode_prefix', $prefix)->first();
            if ($matchedWilayah) {
                $validated['wilayah_id'] = $matchedWilayah->id;
            }
        }

        // Generate NIJ Final
        $currentYear = date('Y');
        $latestRecord = Jemaat::withTrashed()
            ->whereYear('created_at', $currentYear)
            ->latest('id')
            ->first();

        $nextNumber = 1;
        if ($latestRecord && preg_match('/JMT-\d{4}-(\d+)/', $latestRecord->nomor_induk, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        $baseNij = !empty($validated['no_kk']) ? 'JMT-' . $validated['no_kk'] : sprintf('JMT-%s-%04d', $currentYear, $nextNumber);
        $finalNij = $baseNij;
        $counter = 1;
        while (Jemaat::withTrashed()->where('nomor_induk', $finalNij)->exists()) {
            $finalNij = $baseNij . '-' . $counter;
            $counter++;
        }

        $validated['nomor_induk'] = $finalNij;
        $validated['created_by'] = Auth::id();

        $jemaat = Jemaat::create($validated);

        AuditLog::log('create', 'jemaat', "Menambahkan jemaat baru: {$jemaat->nama_lengkap} (KK: {$jemaat->no_kk})", null, $jemaat->toArray());

        return redirect()->route('admin.jemaat.index')->with('success', "Data jemaat {$jemaat->nama_lengkap} berhasil disimpan!");
    }

    /**
     * Tampilkan Detail Jemaat
     */
    public function show($id)
    {
        $jemaat = Jemaat::withTrashed()->with(['wilayah', 'creator', 'updater'])->findOrFail($id);
        return view('admin.jemaat.show', compact('jemaat'));
    }

    /**
     * Formulir Ubah Data Jemaat
     */
    public function edit($id)
    {
        if (!Auth::user()->canCrudJemaat()) {
            abort(403, 'Akses ditolak: Akun Jemaat / non-admin hanya memiliki hak akses Read-Only untuk data jemaat.');
        }

        $jemaat = Jemaat::findOrFail($id);
        $wilayahList = Wilayah::where('status', 'aktif')->orderBy('nama_wilayah')->get();
        return view('admin.jemaat.edit', compact('jemaat', 'wilayahList'));
    }

    /**
     * Simpan Perubahan Data Jemaat
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->canCrudJemaat()) {
            abort(403, 'Akses ditolak: Akun Jemaat / non-admin hanya memiliki hak akses Read-Only untuk data jemaat.');
        }

        $jemaat = Jemaat::findOrFail($id);
        $oldValues = $jemaat->toArray();

        $validated = $request->validate([
            'no_kk' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:20',
            'nama_lengkap' => 'required|string|max:150',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date|before_or_equal:today',
            'status_keluarga' => 'nullable|string|max:100',
            'status_pernikahan' => 'nullable|in:belum_menikah,menikah,duda,janda',
            'status_baptis' => 'nullable|string|max:50',
            'tanggal_baptis' => 'nullable|date',
            'nikah_masehi' => 'nullable|string|max:50',
            'pendidikan' => 'nullable|string|max:100',
            'pekerjaan' => 'nullable|string|max:150',
            'keahlian_khusus' => 'nullable|string|max:150',
            'penghasilan_per_bulan' => 'nullable|string|max:100',
            'lain_lain' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'wilayah_id' => 'nullable|exists:wilayah,id',
            'status_keanggotaan' => 'required|in:aktif,tidak_aktif,pindah,meninggal',
            'tipe_keanggotaan' => 'nullable|in:jemaat_tetap,simpatisan,mutasi',
            'kategori_khusus' => 'nullable|string|max:50',
            'tanggal_bergabung' => 'nullable|date',
            'catatan' => 'nullable|string',
            'ucapan_ultah' => 'nullable|string',
        ]);

        // Cek duplikasi No. KK / ID Jemaat (kecuali untuk data jemaat ini sendiri)
        if (!empty($validated['no_kk'])) {
            $existingKkJemaat = Jemaat::where('no_kk', $validated['no_kk'])
                ->where('id', '!=', $jemaat->id)
                ->first();
            if ($existingKkJemaat) {
                return back()->withInput()->withErrors([
                    'no_kk' => "Nomor KK/ID Jemaat '{$validated['no_kk']}' sudah digunakan oleh {$existingKkJemaat->nama_lengkap}. Harap gunakan nomor urut lain agar tidak terjadi pendobelan data.",
                ]);
            }
        }

        // Otomatis tentukan wilayah_id dari 2 digit pertama no_kk jika belum diisi
        if (empty($validated['wilayah_id']) && !empty($validated['no_kk']) && strlen($validated['no_kk']) >= 2) {
            $prefix = substr($validated['no_kk'], 0, 2);
            $matchedWilayah = Wilayah::where('kode_prefix', $prefix)->first();
            if ($matchedWilayah) {
                $validated['wilayah_id'] = $matchedWilayah->id;
            }
        }

        $validated['updated_by'] = Auth::id();
        $jemaat->update($validated);

        AuditLog::log('update', 'jemaat', "Mengubah data jemaat: {$jemaat->nama_lengkap} (KK: {$jemaat->no_kk})", $oldValues, $jemaat->toArray());

        return redirect()->route('admin.jemaat.index')->with('success', "Data jemaat {$jemaat->nama_lengkap} berhasil diperbarui!");
    }

    /**
     * Hapus Data Jemaat (Soft Delete)
     */
    public function destroy($id)
    {
        if (!Auth::user()->canCrudJemaat()) {
            abort(403, 'Akses ditolak: Akun Jemaat / non-admin hanya memiliki hak akses Read-Only untuk data jemaat.');
        }

        $jemaat = Jemaat::findOrFail($id);
        $nama = $jemaat->nama_lengkap;
        $nij = $jemaat->nomor_induk;

        $jemaat->delete();

        AuditLog::log('delete', 'jemaat', "Menghapus (soft delete) jemaat: {$nama} ({$nij})", $jemaat->toArray(), null);

        return redirect()->route('admin.jemaat.index')->with('success', "Data jemaat {$nama} berhasil dihapus.");
    }

    /**
     * Download Template CSV Kosong Format Gereja
     */
    public function downloadTemplate()
    {
        $filename = 'template_data_jemaat_gbi_shekinah_glory.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Microsoft Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'KK',
                'NAMA',
                'TTL',
                'JK',
                'STATUS',
                'PENYERAHAN/BAPTISAN',
                'NIKAH MASEHI',
                'PENDIDIKAN',
                'PEKERJAAN',
                'KEAHLIAN KHUSUS',
                'PENGHASILAN PER BULAN',
                'LAIN-LAIN',
                'KETERANGAN',
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import Data Jemaat dari File Excel (.xlsx) atau CSV Sesuai Template Format Gereja
     */
    public function importCsv(Request $request)
    {
        if (!Auth::user()->canCrudJemaat()) {
            abort(403, 'Akses ditolak: Akun Jemaat / non-admin hanya memiliki hak akses Read-Only untuk data jemaat.');
        }

        $request->validate([
            'file_csv' => 'required|file|max:20480',
        ], [
            'file_csv.required' => 'Pilih file Excel (.xlsx) atau CSV (.csv) untuk diimpor.',
        ]);

        $file = $request->file('file_csv');
        $ext = strtolower($file->getClientOriginalExtension());
        $realPath = $file->getRealPath();

        $rows = [];
        if ($ext === 'xlsx') {
            try {
                $rows = SimpleXlsxReader::read($realPath);
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal membaca file Excel (.xlsx): ' . $e->getMessage());
            }
        } else {
            // Baca file CSV
            $handle = fopen($realPath, 'r');
            if (!$handle) {
                return back()->with('error', 'Gagal membuka file CSV.');
            }

            // Strip UTF-8 BOM jika ada
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            // Auto-detect delimiter (koma ',' atau titik-koma ';' dari Excel Indonesia)
            $sampleLine = fgets($handle);
            rewind($handle);
            if ($bom === "\xEF\xBB\xBF") {
                fread($handle, 3);
            }
            $semicolons = substr_count($sampleLine, ';');
            $commas = substr_count($sampleLine, ',');
            $delimiter = ($semicolons > $commas) ? ';' : ',';

            while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }

        if (empty($rows)) {
            return back()->with('error', 'File kosong atau format data tidak dapat dibaca.');
        }

        $header = array_shift($rows);
        if (!$header) {
            return back()->with('error', 'Header kolom tidak ditemukan pada file.');
        }

        // Petakan indeks header
        $map = [
            'kk' => false,
            'nama' => false,
            'ttl' => false,
            'jk' => false,
            'status' => false,
            'baptis' => false,
            'nikah' => false,
            'pendidikan' => false,
            'pekerjaan' => false,
            'keahlian' => false,
            'penghasilan' => false,
            'lain' => false,
            'keterangan' => false,
        ];

        foreach ($header as $idx => $col) {
            $cleanCol = strtoupper(trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $col)));
            if ($cleanCol === 'KK' || $cleanCol === 'NIK' || str_contains($cleanCol, 'KK') || str_contains($cleanCol, 'KODE')) $map['kk'] = $idx;
            elseif ($cleanCol === 'NAMA' || str_contains($cleanCol, 'NAMA')) $map['nama'] = $idx;
            elseif ($cleanCol === 'TTL' || str_contains($cleanCol, 'LAHIR')) $map['ttl'] = $idx;
            elseif ($cleanCol === 'JK' || str_contains($cleanCol, 'KELAMIN')) $map['jk'] = $idx;
            elseif ($cleanCol === 'STATUS') $map['status'] = $idx;
            elseif (str_contains($cleanCol, 'BAPTIS') || str_contains($cleanCol, 'PENYERAHAN')) $map['baptis'] = $idx;
            elseif (str_contains($cleanCol, 'NIKAH')) $map['nikah'] = $idx;
            elseif (str_contains($cleanCol, 'PENDIDIKAN')) $map['pendidikan'] = $idx;
            elseif (str_contains($cleanCol, 'PEKERJAAN')) $map['pekerjaan'] = $idx;
            elseif (str_contains($cleanCol, 'KEAHLIAN')) $map['keahlian'] = $idx;
            elseif (str_contains($cleanCol, 'PENGHASILAN')) $map['penghasilan'] = $idx;
            elseif (str_contains($cleanCol, 'LAIN')) $map['lain'] = $idx;
            elseif (str_contains($cleanCol, 'KETERANGAN')) $map['keterangan'] = $idx;
        }

        $imported = 0;
        $updated = 0;
        $currentYear = date('Y');

        foreach ($rows as $row) {
            if (empty(array_filter($row, fn($val) => trim((string)$val) !== ''))) continue; // Lewati baris kosong

            $nama = $map['nama'] !== false && isset($row[$map['nama']]) ? trim($row[$map['nama']]) : null;
            if (empty($nama)) continue;

            $kk = $map['kk'] !== false && isset($row[$map['kk']]) ? trim($row[$map['kk']]) : null;
            $ttlRaw = $map['ttl'] !== false && isset($row[$map['ttl']]) ? trim($row[$map['ttl']]) : '';
            $jk = $map['jk'] !== false && isset($row[$map['jk']]) ? strtoupper(trim($row[$map['jk']])) : 'L';
            if ($jk !== 'L' && $jk !== 'P') {
                $jk = str_starts_with(strtoupper($jk), 'P') ? 'P' : 'L';
            }

            $statusKeluarga = $map['status'] !== false && isset($row[$map['status']]) ? trim($row[$map['status']]) : null;
            $baptis = $map['baptis'] !== false && isset($row[$map['baptis']]) ? trim($row[$map['baptis']]) : null;
            $nikah = $map['nikah'] !== false && isset($row[$map['nikah']]) ? trim($row[$map['nikah']]) : null;
            $pendidikan = $map['pendidikan'] !== false && isset($row[$map['pendidikan']]) ? trim($row[$map['pendidikan']]) : null;
            $pekerjaan = $map['pekerjaan'] !== false && isset($row[$map['pekerjaan']]) ? trim($row[$map['pekerjaan']]) : null;
            $keahlian = $map['keahlian'] !== false && isset($row[$map['keahlian']]) ? trim($row[$map['keahlian']]) : null;
            $penghasilan = $map['penghasilan'] !== false && isset($row[$map['penghasilan']]) ? trim($row[$map['penghasilan']]) : null;
            $lainLain = $map['lain'] !== false && isset($row[$map['lain']]) ? trim($row[$map['lain']]) : null;
            $keterangan = $map['keterangan'] !== false && isset($row[$map['keterangan']]) ? trim($row[$map['keterangan']]) : null;

            // Urai Tempat dan Tanggal Lahir dari format "TEMPAT, DD-MM-YYYY"
            $tempatLahir = null;
            $tanggalLahir = null;
            if (!empty($ttlRaw)) {
                $parts = explode(',', $ttlRaw, 2);
                if (count($parts) === 2) {
                    $tempatLahir = trim($parts[0]);
                    $dateStr = trim($parts[1]);
                } else {
                    $dateStr = trim($parts[0]);
                }

                if (!empty($dateStr)) {
                    // Tangani format numeric Excel serial date
                    if (is_numeric($dateStr) && (float)$dateStr > 1000 && (float)$dateStr < 100000) {
                        $tanggalLahir = gmdate('Y-m-d', ((int)$dateStr - 25569) * 86400);
                    } elseif (preg_match('/0*(\d{1,2})[-\/.](\d{1,2})[-\/.](\d{3,4})/', $dateStr, $m)) {
                        // Tangani DD-MM-YYYY, DD-MM-YYY, atau typo "015-06-1968"
                        $year = (int)$m[3];
                        if ($year < 1000) {
                            $year = ($year == 201) ? 2018 : $year * 10;
                        }
                        $tanggalLahir = sprintf('%04d-%02d-%02d', $year, (int)$m[2], (int)$m[1]);
                    } elseif (preg_match('/(\d{4})[-\/.](\d{1,2})[-\/.](\d{1,2})/', $dateStr, $m)) {
                        // Tangani YYYY-MM-DD
                        $tanggalLahir = sprintf('%04d-%02d-%02d', (int)$m[1], (int)$m[2], (int)$m[3]);
                    } else {
                        try {
                            $tanggalLahir = Carbon::parse($dateStr)->format('Y-m-d');
                        } catch (\Exception $e) {
                            $tanggalLahir = null;
                        }
                    }
                }
            }

            // Penentuan status pernikahan
            $statusNikah = 'belum_menikah';
            if ($statusKeluarga) {
                $sk = strtoupper($statusKeluarga);
                if (str_contains($sk, 'SUAMI') || str_contains($sk, 'ISTRI') || str_contains($sk, 'KEPALA')) {
                    $statusNikah = 'menikah';
                } elseif (str_contains($sk, 'JANDA')) {
                    $statusNikah = 'janda';
                } elseif (str_contains($sk, 'DUDA')) {
                    $statusNikah = 'duda';
                }
            }

            // Auto-detect wilayah_id dari 2 digit pertama nomor KK (misal 31 -> Osmok Sub Wilayah 1)
            $wilayahId = null;
            if (!empty($kk) && strlen($kk) >= 2) {
                $prefix = substr($kk, 0, 2);
                $matchedWilayah = Wilayah::where('kode_prefix', $prefix)->first();
                if ($matchedWilayah) {
                    $wilayahId = $matchedWilayah->id;
                }
            }

            // Cek apakah jemaat sudah ada (berdasarkan no_kk atau nomor_induk)
            $existing = null;
            if (!empty($kk)) {
                $existing = Jemaat::where('no_kk', $kk)->first();
            }

            if ($existing) {
                $existing->update([
                    'nama_lengkap' => $nama,
                    'jenis_kelamin' => $jk,
                    'tempat_lahir' => $tempatLahir,
                    'tanggal_lahir' => $tanggalLahir,
                    'status_keluarga' => $statusKeluarga,
                    'status_pernikahan' => $statusNikah,
                    'status_baptis' => $baptis,
                    'nikah_masehi' => $nikah,
                    'pendidikan' => $pendidikan,
                    'pekerjaan' => $pekerjaan,
                    'keahlian_khusus' => $keahlian,
                    'penghasilan_per_bulan' => $penghasilan,
                    'lain_lain' => $lainLain,
                    'keterangan' => $keterangan,
                    'wilayah_id' => $wilayahId ?: $existing->wilayah_id,
                    'status_keanggotaan' => 'aktif',
                    'updated_by' => Auth::id(),
                ]);
                $updated++;
            } else {
                // Generate nomor induk unik
                $baseNij = $kk ? "JMT-{$kk}" : sprintf('JMT-%s-%04d', $currentYear, $imported + 1);
                $nij = $baseNij;
                $c = 1;
                while (Jemaat::withTrashed()->where('nomor_induk', $nij)->exists()) {
                    $nij = $baseNij . '-' . $c;
                    $c++;
                }

                Jemaat::create([
                    'no_kk' => $kk,
                    'nomor_induk' => $nij,
                    'nama_lengkap' => $nama,
                    'jenis_kelamin' => $jk,
                    'tempat_lahir' => $tempatLahir,
                    'tanggal_lahir' => $tanggalLahir,
                    'status_keluarga' => $statusKeluarga,
                    'status_pernikahan' => $statusNikah,
                    'status_baptis' => $baptis,
                    'nikah_masehi' => $nikah,
                    'pendidikan' => $pendidikan,
                    'pekerjaan' => $pekerjaan,
                    'keahlian_khusus' => $keahlian,
                    'penghasilan_per_bulan' => $penghasilan,
                    'lain_lain' => $lainLain,
                    'keterangan' => $keterangan,
                    'wilayah_id' => $wilayahId,
                    'status_keanggotaan' => 'aktif',
                    'created_by' => Auth::id(),
                ]);
                $imported++;
            }
        }

        $sourceLabel = ($ext === 'xlsx') ? 'Excel (.xlsx)' : 'CSV';
        AuditLog::log('import', 'jemaat', "Mengimpor data jemaat dari file {$sourceLabel}: {$imported} baru, {$updated} diperbarui");

        $msg = "Berhasil memproses file {$sourceLabel}: {$imported} data jemaat baru ditambahkan";
        if ($updated > 0) {
            $msg .= ", {$updated} data diperbarui.";
        } else {
            $msg .= ".";
        }

        return redirect()->route('admin.jemaat.index')->with('success', $msg);
    }

    /**
     * Ekspor Data Jemaat ke CSV Sesuai Template Format Gereja
     */
    public function exportCsv(Request $request)
    {
        $query = Jemaat::with('wilayah');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_kk', 'like', "%{$search}%")
                  ->orWhere('nomor_induk', 'like', "%{$search}%")
                  ->orWhere('pekerjaan', 'like', "%{$search}%")
                  ->orWhere('keahlian_khusus', 'like', "%{$search}%")
                  ->orWhere('status_keluarga', 'like', "%{$search}%");
            });
        }

        if ($wilayahId = $request->input('wilayah_id')) {
            $query->where('wilayah_id', $wilayahId);
        }

        if ($status = $request->input('status')) {
            $query->where('status_keanggotaan', $status);
        }

        $jemaats = $query->orderBy('no_kk', 'asc')->orderBy('nama_lengkap', 'asc')->get();

        AuditLog::log('export', 'jemaat', "Mengekspor " . $jemaats->count() . " data jemaat ke CSV format gereja");

        $filename = 'data_jemaat_gbi_shekinah_glory_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($jemaats) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'KK',
                'NAMA',
                'TTL',
                'JK',
                'STATUS',
                'PENYERAHAN/BAPTISAN',
                'NIKAH MASEHI',
                'PENDIDIKAN',
                'PEKERJAAN',
                'KEAHLIAN KHUSUS',
                'PENGHASILAN PER BULAN',
                'LAIN-LAIN',
                'KETERANGAN',
            ]);

            foreach ($jemaats as $j) {
                fputcsv($file, [
                    $j->no_kk ?? $j->nomor_induk,
                    $j->nama_lengkap,
                    $j->ttl,
                    $j->jenis_kelamin,
                    $j->status_keluarga ?? ucfirst(str_replace('_', ' ', $j->status_pernikahan)),
                    $j->status_baptis,
                    $j->nikah_masehi,
                    $j->pendidikan,
                    $j->pekerjaan,
                    $j->keahlian_khusus,
                    $j->penghasilan_per_bulan,
                    $j->lain_lain,
                    $j->keterangan,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
