<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    /**
     * Daftar Wilayah / Sektor
     */
    public function index()
    {
        $wilayahs = Wilayah::withCount('jemaat')->orderBy('kode_prefix')->get();
        return view('admin.wilayah.index', compact('wilayahs'));
    }

    /**
     * Simpan Wilayah Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'wilayah_besar' => 'required|string|max:50',
            'kode_prefix' => 'nullable|string|max:10',
            'nama_wilayah' => 'required|string|max:100|unique:wilayah,nama_wilayah',
            'kode_wilayah' => 'required|string|max:20|unique:wilayah,kode_wilayah',
            'nama_ketua' => 'nullable|string|max:150',
            'telepon_ketua' => 'nullable|string|max:30',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_wilayah.unique' => 'Nama wilayah sudah terdaftar.',
            'kode_wilayah.unique' => 'Kode wilayah sudah digunakan.',
        ]);

        $wilayah = Wilayah::create($validated);
        AuditLog::log('create', 'wilayah', "Menambahkan wilayah baru: {$wilayah->nama_wilayah} ({$wilayah->kode_wilayah})");

        return back()->with('success', "Wilayah {$wilayah->nama_wilayah} berhasil ditambahkan!");
    }

    /**
     * Update Wilayah
     */
    public function update(Request $request, $id)
    {
        $wilayah = Wilayah::findOrFail($id);
        $oldValues = $wilayah->toArray();

        $validated = $request->validate([
            'wilayah_besar' => 'nullable|string|max:50',
            'kode_prefix' => 'nullable|string|max:10',
            'nama_wilayah' => "required|string|max:100|unique:wilayah,nama_wilayah,{$id}",
            'kode_wilayah' => "required|string|max:20|unique:wilayah,kode_wilayah,{$id}",
            'nama_ketua' => 'nullable|string|max:150',
            'telepon_ketua' => 'nullable|string|max:30',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $wilayah->update($validated);
        AuditLog::log('update', 'wilayah', "Memperbarui wilayah: {$wilayah->nama_wilayah}", $oldValues, $wilayah->toArray());

        return back()->with('success', "Wilayah {$wilayah->nama_wilayah} berhasil diperbarui!");
    }

    /**
     * Hapus Wilayah (Ditolak jika masih ada jemaat - PRD FR-A09 & AC-W02)
     */
    public function destroy($id)
    {
        $wilayah = Wilayah::withCount('jemaat')->findOrFail($id);

        if ($wilayah->jemaat_count > 0) {
            return back()->withErrors([
                'delete_error' => "Wilayah '{$wilayah->nama_wilayah}' tidak dapat dihapus karena masih memiliki {$wilayah->jemaat_count} jemaat terdaftar. Silakan pindahkan jemaat ke wilayah lain terlebih dahulu atau nonaktifkan wilayah ini.",
            ]);
        }

        $nama = $wilayah->nama_wilayah;
        $wilayah->delete();
        AuditLog::log('delete', 'wilayah', "Menghapus wilayah: {$nama}");

        return back()->with('success', "Wilayah {$nama} berhasil dihapus.");
    }
}
