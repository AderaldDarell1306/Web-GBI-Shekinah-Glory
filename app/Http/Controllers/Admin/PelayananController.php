<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\JadwalIbadah;
use App\Models\JadwalPelayanan;
use App\Models\Jemaat;
use App\Models\Majelis;
use App\Models\PelayanTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PelayananController extends Controller
{
    /**
     * Daftar Jadwal Pelayanan Ibadah (Service Rota)
     */
    public function index(Request $request)
    {
        $today = Carbon::today();

        $query = JadwalPelayanan::with(['sesiIbadah', 'petugas.jemaat', 'creator']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('tema_ibadah', 'like', "%{$search}%")
                  ->orWhere('pengkhotbah', 'like', "%{$search}%")
                  ->orWhereHas('petugas', function ($pq) use ($search) {
                      $pq->where('nama_pelayan', 'like', "%{$search}%");
                  });
            });
        }

        $upcomingList = (clone $query)
            ->where('tanggal_pelayanan', '>=', $today)
            ->orderBy('tanggal_pelayanan', 'asc')
            ->paginate(10, ['*'], 'upcoming_page');

        $pastList = (clone $query)
            ->where('tanggal_pelayanan', '<', $today)
            ->orderBy('tanggal_pelayanan', 'desc')
            ->paginate(10, ['*'], 'past_page');

        return view('admin.pelayanan.index', compact('upcomingList', 'pastList'));
    }

    /**
     * Form Tambah Jadwal Pelayanan
     */
    public function create()
    {
        $sesiIbadahList = JadwalIbadah::where('status', 'aktif')->orderBy('urutan')->get();
        $jemaats = Jemaat::where('status_keanggotaan', 'aktif')->orderBy('nama_lengkap')->get();
        $majelisList = Majelis::where('status', 'aktif')->orderBy('bidang_pelayanan')->orderBy('nama_majelis')->get();
        $tamborinList = PelayanTugas::tamborinList();
        $pelayanAltarList = PelayanTugas::pelayanAltarList();
        $posisiLabels = PelayanTugas::posisiLabels();

        return view('admin.pelayanan.create', compact('sesiIbadahList', 'jemaats', 'majelisList', 'tamborinList', 'pelayanAltarList', 'posisiLabels'));
    }

    /**
     * Simpan Jadwal Pelayanan Baru dengan Conflict Checker (Anti-Bentrok)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jadwal_ibadah_id' => 'required|exists:jadwal_ibadah,id',
            'tanggal_pelayanan' => 'required|date',
            'tema_ibadah' => 'nullable|string|max:200',
            'pengkhotbah' => 'nullable|string|max:150',
            'catatan' => 'nullable|string',
            'status_publikasi' => 'required|in:draft,terbit',
            'petugas' => 'required|array',
        ]);

        // Cek duplikasi sesi pada tanggal yang sama
        $exists = JadwalPelayanan::where('jadwal_ibadah_id', $validated['jadwal_ibadah_id'])
            ->where('tanggal_pelayanan', $validated['tanggal_pelayanan'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Jadwal pelayanan untuk sesi ibadah dan tanggal tersebut sudah pernah dibuat.');
        }

        // ==========================================
        // CONFLICT CHECKER & VALIDASI PETUGAS RESMI
        // ==========================================
        $assigned = [];
        $posisiLabels = PelayanTugas::posisiLabels();
        $validMajelis = Majelis::where('status', 'aktif')->pluck('nama_majelis')->map(fn($n) => strtolower(trim($n)))->toArray();
        $tamborinNames = array_map(fn($n) => strtolower(trim($n)), PelayanTugas::tamborinList());
        $pelayanAltarNames = array_map(fn($n) => strtolower(trim($n)), PelayanTugas::pelayanAltarList());

        foreach ($validated['petugas'] as $posKey => $item) {
            $nama = trim($item['nama_pelayan'] ?? '');
            if (empty($nama)) continue;

            $namaLower = strtolower($nama);

            // Validasi Khusus: Posisi Pemain Tamborin (16 nama resmi)
            if ($posKey === 'tamborin') {
                $isMatchTamborin = false;
                foreach ($tamborinNames as $tn) {
                    if (str_contains($namaLower, $tn) || str_contains($tn, $namaLower)) {
                        $isMatchTamborin = true;
                        break;
                    }
                }
                if (!$isMatchTamborin) {
                    return back()->withInput()->with('error', "Untuk posisi Pemain Tamborin, nama '{$nama}' tidak terdaftar dalam Tim Tamborin Resmi. Sesuai ketentuan gereja, hanya nama-nama dari 16 pemain tamborin terdaftar yang dapat dipilih.");
                }
            }
            // Validasi Khusus: Posisi Pelayan Altar (16 nama resmi)
            elseif ($posKey === 'pelayan_altar') {
                $isMatchAltar = false;
                foreach ($pelayanAltarNames as $pan) {
                    if (str_contains($namaLower, $pan) || str_contains($pan, $namaLower)) {
                        $isMatchAltar = true;
                        break;
                    }
                }
                if (!$isMatchAltar) {
                    return back()->withInput()->with('error', "Untuk posisi Pelayan Altar, nama '{$nama}' tidak terdaftar dalam Tim Pelayan Altar Resmi.");
                }
            }
            // Semua Posisi Pelayanan Lainnya: Hanya boleh dari daftar Majelis Resmi
            else {
                $isMatchMajelis = false;
                foreach ($validMajelis as $vm) {
                    if (str_contains($namaLower, $vm) || str_contains($vm, $namaLower)) {
                        $isMatchMajelis = true;
                        break;
                    }
                }
                if (!$isMatchMajelis) {
                    $posName = $posisiLabels[$posKey] ?? $posKey;
                    return back()->withInput()->with('error', "Untuk posisi [{$posName}], nama '{$nama}' tidak terdaftar sebagai Majelis Resmi. Sesuai arahan gereja, penugasan pelayanan hanya dapat dipilih dari daftar Majelis Resmi GBI Shekinah Glory.");
                }
            }

            // Cek bentrok dalam form yang sama
            if (isset($assigned[$namaLower])) {
                $posisiSebelumnya = $posisiLabels[$assigned[$namaLower]] ?? $assigned[$namaLower];
                $posisiSekarang = $posisiLabels[$posKey] ?? $posKey;

                return back()->withInput()->with('error', "Gagal Menyimpan (Bentrok Tugas): Pelayan '{$nama}' sudah dijadwalkan sebagai [{$posisiSebelumnya}], tidak boleh ditugaskan ganda sebagai [{$posisiSekarang}] pada ibadah yang sama!");
            }

            $assigned[$namaLower] = $posKey;
        }

        // Simpan Induk Rota
        $validated['created_by'] = Auth::id();
        $rota = JadwalPelayanan::create($validated);

        // Simpan Petugas
        foreach ($validated['petugas'] as $posKey => $item) {
            $nama = trim($item['nama_pelayan'] ?? '');
            if (empty($nama)) continue;

            PelayanTugas::create([
                'jadwal_pelayanan_id' => $rota->id,
                'posisi' => $posKey,
                'jemaat_id' => !empty($item['jemaat_id']) ? $item['jemaat_id'] : null,
                'nama_pelayan' => $nama,
                'no_wa' => $item['no_wa'] ?? null,
                'status_konfirmasi' => 'menunggu',
            ]);
        }

        AuditLog::log('create', 'jadwal_pelayanan', "Membuat jadwal pelayanan ibadah tanggal {$rota->tanggal_pelayanan->format('d/m/Y')}");

        return redirect()->route('admin.pelayanan.show', $rota->id)->with('success', 'Jadwal pelayanan ibadah berhasil disimpan tanpa ada bentrok tugas!');
    }

    /**
     * Tampilkan Detail Jadwal Pelayanan & Generator Pesan Grup WhatsApp
     */
    public function show($id)
    {
        $rota = JadwalPelayanan::with(['sesiIbadah', 'petugas.jemaat', 'creator'])->findOrFail($id);
        $posisiLabels = PelayanTugas::posisiLabels();
        $groupedPetugas = $rota->petugas->groupBy('posisi');

        // Generate Pesan WhatsApp untuk Grup Jemaat/Pelayan
        $waMessage = $rota->generateWhatsAppBroadcastText();
        $waShareUrl = "https://api.whatsapp.com/send?text=" . urlencode($waMessage);

        return view('admin.pelayanan.show', compact('rota', 'posisiLabels', 'groupedPetugas', 'waMessage', 'waShareUrl'));
    }

    /**
     * Form Edit Jadwal Pelayanan
     */
    public function edit($id)
    {
        $rota = JadwalPelayanan::with(['petugas'])->findOrFail($id);
        $sesiIbadahList = JadwalIbadah::where('status', 'aktif')->orderBy('urutan')->get();
        $jemaats = Jemaat::where('status_keanggotaan', 'aktif')->orderBy('nama_lengkap')->get();
        $majelisList = Majelis::where('status', 'aktif')->orderBy('bidang_pelayanan')->orderBy('nama_majelis')->get();
        $tamborinList = PelayanTugas::tamborinList();
        $pelayanAltarList = PelayanTugas::pelayanAltarList();
        $posisiLabels = PelayanTugas::posisiLabels();

        $existingPetugas = [];
        foreach ($rota->petugas as $p) {
            $existingPetugas[$p->posisi] = [
                'nama_pelayan' => $p->nama_pelayan,
                'jemaat_id' => $p->jemaat_id,
                'no_wa' => $p->no_wa,
            ];
        }

        return view('admin.pelayanan.edit', compact('rota', 'sesiIbadahList', 'jemaats', 'majelisList', 'tamborinList', 'pelayanAltarList', 'posisiLabels', 'existingPetugas'));
    }

    /**
     * Update Jadwal Pelayanan dengan Conflict Checker
     */
    public function update(Request $request, $id)
    {
        $rota = JadwalPelayanan::findOrFail($id);

        $validated = $request->validate([
            'jadwal_ibadah_id' => 'required|exists:jadwal_ibadah,id',
            'tanggal_pelayanan' => 'required|date',
            'tema_ibadah' => 'nullable|string|max:200',
            'pengkhotbah' => 'nullable|string|max:150',
            'catatan' => 'nullable|string',
            'status_publikasi' => 'required|in:draft,terbit',
            'petugas' => 'required|array',
        ]);

        // ==========================================
        // CONFLICT CHECKER & VALIDASI PETUGAS RESMI
        // ==========================================
        $assigned = [];
        $posisiLabels = PelayanTugas::posisiLabels();
        $validMajelis = Majelis::where('status', 'aktif')->pluck('nama_majelis')->map(fn($n) => strtolower(trim($n)))->toArray();
        $tamborinNames = array_map(fn($n) => strtolower(trim($n)), PelayanTugas::tamborinList());
        $pelayanAltarNames = array_map(fn($n) => strtolower(trim($n)), PelayanTugas::pelayanAltarList());

        foreach ($validated['petugas'] as $posKey => $item) {
            $nama = trim($item['nama_pelayan'] ?? '');
            if (empty($nama)) continue;

            $namaLower = strtolower($nama);

            // Validasi Khusus: Posisi Pemain Tamborin (16 nama resmi)
            if ($posKey === 'tamborin') {
                $isMatchTamborin = false;
                foreach ($tamborinNames as $tn) {
                    if (str_contains($namaLower, $tn) || str_contains($tn, $namaLower)) {
                        $isMatchTamborin = true;
                        break;
                    }
                }
                if (!$isMatchTamborin) {
                    return back()->withInput()->with('error', "Untuk posisi Pemain Tamborin, nama '{$nama}' tidak terdaftar dalam Tim Tamborin Resmi. Sesuai ketentuan gereja, hanya nama-nama dari 16 pemain tamborin terdaftar yang dapat dipilih.");
                }
            }
            // Validasi Khusus: Posisi Pelayan Altar (16 nama resmi)
            elseif ($posKey === 'pelayan_altar') {
                $isMatchAltar = false;
                foreach ($pelayanAltarNames as $pan) {
                    if (str_contains($namaLower, $pan) || str_contains($pan, $namaLower)) {
                        $isMatchAltar = true;
                        break;
                    }
                }
                if (!$isMatchAltar) {
                    return back()->withInput()->with('error', "Untuk posisi Pelayan Altar, nama '{$nama}' tidak terdaftar dalam Tim Pelayan Altar Resmi.");
                }
            }
            // Semua Posisi Pelayanan Lainnya: Hanya boleh dari daftar Majelis Resmi
            else {
                $isMatchMajelis = false;
                foreach ($validMajelis as $vm) {
                    if (str_contains($namaLower, $vm) || str_contains($vm, $namaLower)) {
                        $isMatchMajelis = true;
                        break;
                    }
                }
                if (!$isMatchMajelis) {
                    $posName = $posisiLabels[$posKey] ?? $posKey;
                    return back()->withInput()->with('error', "Untuk posisi [{$posName}], nama '{$nama}' tidak terdaftar sebagai Majelis Resmi. Sesuai arahan gereja, penugasan pelayanan hanya dapat dipilih dari daftar Majelis Resmi GBI Shekinah Glory.");
                }
            }

            if (isset($assigned[$namaLower])) {
                $posisiSebelumnya = $posisiLabels[$assigned[$namaLower]] ?? $assigned[$namaLower];
                $posisiSekarang = $posisiLabels[$posKey] ?? $posKey;

                return back()->withInput()->with('error', "Gagal Memperbarui (Bentrok Tugas): Pelayan '{$nama}' sudah dijadwalkan sebagai [{$posisiSebelumnya}], tidak boleh ditugaskan ganda sebagai [{$posisiSekarang}]!");
            }

            $assigned[$namaLower] = $posKey;
        }

        $rota->update($validated);

        // Hapus petugas lama dan masukkan yang baru
        $rota->petugas()->delete();
        foreach ($validated['petugas'] as $posKey => $item) {
            $nama = trim($item['nama_pelayan'] ?? '');
            if (empty($nama)) continue;

            PelayanTugas::create([
                'jadwal_pelayanan_id' => $rota->id,
                'posisi' => $posKey,
                'jemaat_id' => !empty($item['jemaat_id']) ? $item['jemaat_id'] : null,
                'nama_pelayan' => $nama,
                'no_wa' => $item['no_wa'] ?? null,
                'status_konfirmasi' => 'menunggu',
            ]);
        }

        AuditLog::log('update', 'jadwal_pelayanan', "Memperbarui jadwal pelayanan ibadah tanggal {$rota->tanggal_pelayanan->format('d/m/Y')}");

        return redirect()->route('admin.pelayanan.show', $rota->id)->with('success', 'Jadwal pelayanan ibadah berhasil diperbarui tanpa bentrok!');
    }

    /**
     * Hapus Jadwal Pelayanan
     */
    public function destroy($id)
    {
        $rota = JadwalPelayanan::findOrFail($id);
        $rota->delete();

        AuditLog::log('delete', 'jadwal_pelayanan', "Menghapus jadwal pelayanan ibadah tanggal {$rota->tanggal_pelayanan->format('d/m/Y')}");

        return redirect()->route('admin.pelayanan.index')->with('success', 'Jadwal pelayanan berhasil dihapus.');
    }
}

