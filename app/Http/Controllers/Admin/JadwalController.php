<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\JadwalIbadah;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Tampilkan Daftar Semua Jadwal Ibadah
     */
    public function index()
    {
        $jadwalList = JadwalIbadah::orderBy('urutan', 'asc')->orderBy('id', 'asc')->get();
        return view('admin.jadwal.index', compact('jadwalList'));
    }

    /**
     * Tampilkan Form Tambah Jadwal
     */
    public function create()
    {
        return view('admin.jadwal.create');
    }

    /**
     * Simpan Jadwal Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ibadah' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'hari' => 'required|string|max:30',
            'jam' => 'required|string|max:50',
            'lokasi_ruangan' => 'required|string|max:100',
            'tipe_pelaksanaan' => 'required|string|max:50',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if (!isset($validated['urutan'])) {
            $maxUrutan = JadwalIbadah::max('urutan') ?? 0;
            $validated['urutan'] = $maxUrutan + 1;
        }

        $jadwal = JadwalIbadah::create($validated);

        if (class_exists(AuditLog::class)) {
            AuditLog::log('create', 'jadwal_ibadah', "Menambahkan jadwal ibadah baru: {$jadwal->nama_ibadah}");
        }

        return redirect()->route('admin.jadwal.index')->with('success', "Jadwal '{$jadwal->nama_ibadah}' berhasil ditambahkan!");
    }

    /**
     * Tampilkan Form Edit Jadwal
     */
    public function edit($id)
    {
        $jadwal = JadwalIbadah::findOrFail($id);
        return view('admin.jadwal.edit', compact('jadwal'));
    }

    /**
     * Simpan Perubahan Jadwal
     */
    public function update(Request $request, $id)
    {
        $jadwal = JadwalIbadah::findOrFail($id);

        $validated = $request->validate([
            'nama_ibadah' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'hari' => 'required|string|max:30',
            'jam' => 'required|string|max:50',
            'lokasi_ruangan' => 'required|string|max:100',
            'tipe_pelaksanaan' => 'required|string|max:50',
            'keterangan' => 'nullable|string',
            'urutan' => 'nullable|integer',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $jadwal->update($validated);

        if (class_exists(AuditLog::class)) {
            AuditLog::log('update', 'jadwal_ibadah', "Memperbarui jadwal ibadah: {$jadwal->nama_ibadah}");
        }

        return redirect()->route('admin.jadwal.index')->with('success', "Jadwal '{$jadwal->nama_ibadah}' berhasil diperbarui!");
    }

    /**
     * Hapus Jadwal Ibadah
     */
    public function destroy($id)
    {
        $jadwal = JadwalIbadah::findOrFail($id);
        $nama = $jadwal->nama_ibadah;
        $jadwal->delete();

        if (class_exists(AuditLog::class)) {
            AuditLog::log('delete', 'jadwal_ibadah', "Menghapus jadwal ibadah: {$nama}");
        }

        return redirect()->route('admin.jadwal.index')->with('success', "Jadwal '{$nama}' berhasil dihapus!");
    }

    /**
     * Toggle Cepat Status Aktif / Nonaktif
     */
    public function toggleStatus($id)
    {
        $jadwal = JadwalIbadah::findOrFail($id);
        $jadwal->status = $jadwal->status === 'aktif' ? 'nonaktif' : 'aktif';
        $jadwal->save();

        if (class_exists(AuditLog::class)) {
            AuditLog::log('update', 'jadwal_ibadah', "Mengubah status jadwal {$jadwal->nama_ibadah} menjadi {$jadwal->status}");
        }

        return redirect()->back()->with('success', "Status jadwal '{$jadwal->nama_ibadah}' diubah menjadi " . strtoupper($jadwal->status));
    }
}
