<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    /**
     * Tampilkan Daftar Pengumuman Gereja
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $kategori = $request->input('kategori');
        $q = $request->input('q');

        $query = Pengumuman::query();

        if ($status) {
            $query->where('status', $status);
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('isi', 'like', "%{$q}%");
            });
        }

        $pengumumanList = $query->orderBy('is_pinned', 'desc')
            ->orderBy('tanggal', 'desc')
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => Pengumuman::count(),
            'aktif' => Pengumuman::where('status', 'aktif')->count(),
            'pinned' => Pengumuman::where('is_pinned', true)->count(),
            'arsip' => Pengumuman::where('status', 'arsip')->count(),
        ];

        return view('admin.pengumuman.index', compact('pengumumanList', 'status', 'kategori', 'q', 'stats'));
    }

    /**
     * Form Tambah Pengumuman Baru
     */
    public function create()
    {
        return view('admin.pengumuman.create');
    }

    /**
     * Simpan Pengumuman Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:umum,ibadah,kegiatan,pelayanan,sosial,penting',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
            'tanggal_kadaluarsa' => 'nullable|date|after_or_equal:tanggal',
            'is_pinned' => 'nullable|boolean',
            'status' => 'required|in:aktif,arsip',
            'gambar' => 'nullable|string|max:500',
            'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $gambar = $request->input('gambar');
        if ($request->hasFile('gambar_file')) {
            $gambar = $request->file('gambar_file')->store('pengumuman', 'public');
        }

        $pengumuman = Pengumuman::create([
            'judul' => $validated['judul'],
            'kategori' => $validated['kategori'],
            'isi' => $validated['isi'],
            'gambar' => $gambar,
            'tanggal' => $validated['tanggal'],
            'tanggal_kadaluarsa' => $validated['tanggal_kadaluarsa'] ?? null,
            'is_pinned' => $request->boolean('is_pinned'),
            'status' => $validated['status'],
            'created_by' => Auth::id(),
        ]);

        AuditLog::log('create', 'pengumuman', "Membuat pengumuman: {$pengumuman->judul}");

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman gereja berhasil ditambahkan!');
    }

    /**
     * Form Edit Pengumuman
     */
    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    /**
     * Perbarui Pengumuman
     */
    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:umum,ibadah,kegiatan,pelayanan,sosial,penting',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
            'tanggal_kadaluarsa' => 'nullable|date|after_or_equal:tanggal',
            'is_pinned' => 'nullable|boolean',
            'status' => 'required|in:aktif,arsip',
            'gambar' => 'nullable|string|max:500',
            'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $gambar = $request->input('gambar', $pengumuman->gambar);
        if ($request->hasFile('gambar_file')) {
            if ($pengumuman->gambar && !str_starts_with($pengumuman->gambar, 'http') && Storage::disk('public')->exists($pengumuman->gambar)) {
                Storage::disk('public')->delete($pengumuman->gambar);
            }
            $gambar = $request->file('gambar_file')->store('pengumuman', 'public');
        }

        $pengumuman->update([
            'judul' => $validated['judul'],
            'kategori' => $validated['kategori'],
            'isi' => $validated['isi'],
            'gambar' => $gambar,
            'tanggal' => $validated['tanggal'],
            'tanggal_kadaluarsa' => $validated['tanggal_kadaluarsa'] ?? null,
            'is_pinned' => $request->boolean('is_pinned'),
            'status' => $validated['status'],
        ]);

        AuditLog::log('update', 'pengumuman', "Memperbarui pengumuman: {$pengumuman->judul}");

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman gereja berhasil diperbarui!');
    }

    /**
     * Hapus Pengumuman
     */
    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        if ($pengumuman->gambar && !str_starts_with($pengumuman->gambar, 'http') && Storage::disk('public')->exists($pengumuman->gambar)) {
            Storage::disk('public')->delete($pengumuman->gambar);
        }
        $pengumuman->delete();

        AuditLog::log('delete', 'pengumuman', "Menghapus pengumuman: {$pengumuman->judul}");

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman gereja berhasil dihapus!');
    }

    /**
     * Cepat Sematkan / Lepas Sematan (Toggle Pin)
     */
    public function togglePin($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->is_pinned = !$pengumuman->is_pinned;
        $pengumuman->save();

        $msg = $pengumuman->is_pinned ? "Pengumuman disematkan ke bagian atas!" : "Sematan pengumuman dilepas.";
        return back()->with('success', $msg);
    }
}

