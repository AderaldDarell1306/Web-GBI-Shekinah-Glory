<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Jemaat;
use App\Models\Majelis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MajelisController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $status = $request->input('status', 'aktif');
        $jabatan = $request->input('jabatan');

        $query = Majelis::with('jemaat');

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('nama_majelis', 'like', "%{$q}%")
                    ->orWhere('bidang_pelayanan', 'like', "%{$q}%")
                    ->orWhere('no_sk', 'like', "%{$q}%");
            });
        }

        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }

        if ($jabatan) {
            $query->where('jabatan', $jabatan);
        }

        $majelisList = $query->orderBy('bidang_pelayanan')->orderBy('nama_majelis')->paginate(25)->withQueryString();
        $jemaatList = Jemaat::where('status_keanggotaan', 'aktif')->orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'no_hp', 'status_keluarga']);
        $jabatanOptions = Majelis::jabatanOptions();

        $totalAktif = Majelis::where('status', 'aktif')->count();
        $totalPurna = Majelis::where('status', 'purna_tugas')->count();

        return view('admin.majelis.index', compact(
            'majelisList',
            'jemaatList',
            'jabatanOptions',
            'q',
            'status',
            'jabatan',
            'totalAktif',
            'totalPurna'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->isJemaat()) {
            abort(403, 'Akses ditolak: Jemaat hanya memiliki hak akses Read-Only.');
        }

        $validated = $request->validate([
            'jemaat_id' => 'nullable|exists:jemaat,id',
            'nama_majelis' => 'required|string|max:150',
            'jabatan' => 'required|string|max:100',
            'bidang_pelayanan' => 'nullable|string|max:150',
            'periode' => 'nullable|string|max:50',
            'no_sk' => 'nullable|string|max:100',
            'no_hp' => 'nullable|string|max:30',
            'status' => 'required|in:aktif,purna_tugas',
            'catatan' => 'nullable|string',
        ]);

        if (!empty($validated['jemaat_id']) && empty($validated['nama_majelis'])) {
            $j = Jemaat::find($validated['jemaat_id']);
            if ($j) {
                $validated['nama_majelis'] = $j->nama_lengkap;
                if (empty($validated['no_hp'])) {
                    $validated['no_hp'] = $j->no_hp;
                }
            }
        }

        $majelis = Majelis::create($validated);
        AuditLog::log('create', 'majelis', "Menambahkan data majelis: {$majelis->nama_majelis} ({$majelis->jabatan})", null, $majelis->toArray());

        return redirect()->route('admin.majelis.index')->with('success', 'Data Majelis berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user && $user->isJemaat()) {
            abort(403, 'Akses ditolak: Jemaat hanya memiliki hak akses Read-Only.');
        }

        $majelis = Majelis::findOrFail($id);
        $oldValues = $majelis->toArray();

        $validated = $request->validate([
            'jemaat_id' => 'nullable|exists:jemaat,id',
            'nama_majelis' => 'required|string|max:150',
            'jabatan' => 'required|string|max:100',
            'bidang_pelayanan' => 'nullable|string|max:150',
            'periode' => 'nullable|string|max:50',
            'no_sk' => 'nullable|string|max:100',
            'no_hp' => 'nullable|string|max:30',
            'status' => 'required|in:aktif,purna_tugas',
            'catatan' => 'nullable|string',
        ]);

        $majelis->update($validated);
        AuditLog::log('update', 'majelis', "Mengubah data majelis: {$majelis->nama_majelis}", $oldValues, $majelis->toArray());

        return redirect()->route('admin.majelis.index')->with('success', 'Data Majelis berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if ($user && $user->isJemaat()) {
            abort(403, 'Akses ditolak: Jemaat hanya memiliki hak akses Read-Only.');
        }

        $majelis = Majelis::findOrFail($id);
        $nama = $majelis->nama_majelis;
        $majelis->delete();

        AuditLog::log('delete', 'majelis', "Menghapus data majelis: {$nama}", null, null);

        return redirect()->route('admin.majelis.index')->with('success', "Data Majelis {$nama} berhasil dihapus.");
    }
}
