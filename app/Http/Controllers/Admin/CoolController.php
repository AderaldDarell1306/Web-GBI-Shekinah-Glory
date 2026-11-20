<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PertemuanCool;
use App\Models\KehadiranCoolDetail;
use App\Models\Jemaat;
use App\Models\Wilayah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoolController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = PertemuanCool::with(['wilayah', 'creator']);

        // Batasan Hak Akses: Jika Ketua COOL, hanya lihat pertemuan wilayahnya sendiri (AC3)
        if ($user && $user->isKetuaCool() && $user->wilayah_id) {
            $query->where('wilayah_id', $user->wilayah_id);
            $selectedWilayahId = $user->wilayah_id;
        } else {
            $selectedWilayahId = $request->input('wilayah_id');
            if ($selectedWilayahId) {
                $query->where('wilayah_id', $selectedWilayahId);
            }
        }

        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        if ($bulan && $tahun) {
            $query->whereMonth('tanggal_pertemuan', $bulan)
                  ->whereYear('tanggal_pertemuan', $tahun);
        }

        $pertemuanList = $query->orderBy('tanggal_pertemuan', 'desc')->paginate(12)->withQueryString();

        $wilayahList = Wilayah::aktif()->orderBy('nama_wilayah')->get();
        $totalPertemuan = (clone $query)->count();
        $rataRataHadir = (clone $query)->avg('total_hadir') ?? 0;

        return view('admin.cool.index', compact(
            'pertemuanList',
            'wilayahList',
            'selectedWilayahId',
            'bulan',
            'tahun',
            'totalPertemuan',
            'rataRataHadir'
        ));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $wilayahList = Wilayah::aktif()->orderBy('nama_wilayah')->get();

        if ($user && $user->isKetuaCool() && $user->wilayah_id) {
            $targetWilayahId = $user->wilayah_id;
        } else {
            $targetWilayahId = $request->input('wilayah_id', $wilayahList->first()?->id);
        }

        // Ambil jemaat yang terdaftar di wilayah tersebut
        $anggotaList = Jemaat::where('wilayah_id', $targetWilayahId)
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        $targetWilayah = Wilayah::find($targetWilayahId);

        return view('admin.cool.create', compact('wilayahList', 'targetWilayahId', 'targetWilayah', 'anggotaList'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'wilayah_id' => 'required|exists:wilayah,id',
            'nama_cool' => 'nullable|string|max:100',
            'tanggal_pertemuan' => 'required|date',
            'jam_mulai' => 'nullable|string|max:30',
            'tempat' => 'nullable|string|max:150',
            'pemimpin_sharing' => 'nullable|string|max:150',
            'tema_renungan' => 'nullable|string|max:200',
            'catatan' => 'nullable|string',
            'kehadiran' => 'nullable|array',
            'keterangan_jemaat' => 'nullable|array',
        ]);

        // Jika Ketua COOL, cegah manipulasi input wilayah_id lain
        if ($user && $user->isKetuaCool() && $user->wilayah_id) {
            $validated['wilayah_id'] = $user->wilayah_id;
        }

        $pertemuan = PertemuanCool::create([
            'wilayah_id' => $validated['wilayah_id'],
            'nama_cool' => $validated['nama_cool'] ?? 'COOL ' . Wilayah::find($validated['wilayah_id'])?->nama_wilayah,
            'tanggal_pertemuan' => $validated['tanggal_pertemuan'],
            'jam_mulai' => $validated['jam_mulai'] ?? '18:00 WITA',
            'tempat' => $validated['tempat'],
            'pemimpin_sharing' => $validated['pemimpin_sharing'],
            'tema_renungan' => $validated['tema_renungan'],
            'catatan' => $validated['catatan'],
            'created_by' => Auth::id(),
            'total_hadir' => 0,
        ]);

        $totalHadir = 0;
        if (!empty($validated['kehadiran'])) {
            foreach ($validated['kehadiran'] as $jemaatId => $status) {
                if ($status === 'hadir') {
                    $totalHadir++;
                }
                KehadiranCoolDetail::create([
                    'pertemuan_cool_id' => $pertemuan->id,
                    'jemaat_id' => $jemaatId,
                    'status_kehadiran' => $status,
                    'keterangan' => $validated['keterangan_jemaat'][$jemaatId] ?? null,
                ]);
            }
        }

        $pertemuan->update(['total_hadir' => $totalHadir]);

        return redirect()->route('admin.cool.show', $pertemuan->id)->with('success', 'Catatan kehadiran COOL berhasil disimpan!');
    }

    public function show($id)
    {
        $pertemuan = PertemuanCool::with(['wilayah', 'creator', 'kehadiranDetails.jemaat'])->findOrFail($id);
        $user = Auth::user();

        // Validasi akses Ketua COOL
        if ($user && $user->isKetuaCool() && $user->wilayah_id && $pertemuan->wilayah_id != $user->wilayah_id) {
            abort(403, 'Akses ditolak: Anda hanya dapat melihat data COOL wilayah Anda sendiri.');
        }

        $hadirCount = $pertemuan->kehadiranDetails->where('status_kehadiran', 'hadir')->count();
        $izinCount = $pertemuan->kehadiranDetails->where('status_kehadiran', 'izin')->count();
        $sakitCount = $pertemuan->kehadiranDetails->where('status_kehadiran', 'sakit')->count();
        $alpaCount = $pertemuan->kehadiranDetails->where('status_kehadiran', 'alpa')->count();

        return view('admin.cool.show', compact('pertemuan', 'hadirCount', 'izinCount', 'sakitCount', 'alpaCount'));
    }

    public function destroy($id)
    {
        $pertemuan = PertemuanCool::findOrFail($id);
        $user = Auth::user();

        if ($user && $user->isKetuaCool() && $user->wilayah_id && $pertemuan->wilayah_id != $user->wilayah_id) {
            abort(403, 'Akses ditolak.');
        }

        $pertemuan->delete();
        return redirect()->route('admin.cool.index')->with('success', 'Data pertemuan COOL berhasil dihapus!');
    }
}

