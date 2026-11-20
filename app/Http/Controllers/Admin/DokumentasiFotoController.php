<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\DokumentasiFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumentasiFotoController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->input('kategori');
        $q = $request->input('q');

        $query = DokumentasiFoto::with('uploader');

        if ($kategori) {
            $query->where('kategori_kegiatan', $kategori);
        }

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%");
            });
        }

        $fotoList = $query->orderBy('tanggal_kegiatan', 'desc')->paginate(12)->withQueryString();
        $kategoriList = DokumentasiFoto::select('kategori_kegiatan')->distinct()->pluck('kategori_kegiatan');

        return view('admin.dokumentasi.index', compact('fotoList', 'kategoriList', 'kategori', 'q'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->isJemaat()) {
            abort(403, 'Akses ditolak: Jemaat hanya memiliki akses melihat galeri.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'kategori_kegiatan' => 'required|string|max:100',
            'tanggal_kegiatan' => 'required|date',
            'file_foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:8192',
            'deskripsi' => 'nullable|string',
            'tampilkan_di_galeri' => 'nullable|boolean',
        ], [
            'judul.required' => 'Judul kegiatan wajib diisi.',
            'kategori_kegiatan.required' => 'Kategori kegiatan wajib diisi.',
            'file_foto.required' => 'Pilih file foto untuk diunggah.',
            'file_foto.max' => 'Ukuran file foto maksimal 8 MB.',
        ]);

        $path = $request->file('file_foto')->store('dokumentasi_kegiatan', 'public');

        $foto = DokumentasiFoto::create([
            'judul' => $validated['judul'],
            'kategori_kegiatan' => $validated['kategori_kegiatan'],
            'tanggal_kegiatan' => $validated['tanggal_kegiatan'],
            'file_foto' => $path,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'tampilkan_di_galeri' => $request->has('tampilkan_di_galeri') ? true : false,
            'uploaded_by' => Auth::id(),
        ]);

        AuditLog::log('create', 'dokumentasi_foto', "Mengunggah foto kegiatan: {$foto->judul}", null, $foto->toArray());

        return redirect()->route('admin.dokumentasi.index')->with('success', 'Foto kegiatan berhasil diunggah!');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user && $user->isJemaat()) {
            abort(403, 'Akses ditolak.');
        }

        $foto = DokumentasiFoto::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'kategori_kegiatan' => 'required|string|max:100',
            'tanggal_kegiatan' => 'required|date',
            'file_foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'deskripsi' => 'nullable|string',
            'tampilkan_di_galeri' => 'nullable|boolean',
        ]);

        if ($request->hasFile('file_foto')) {
            if ($foto->file_foto && Storage::disk('public')->exists($foto->file_foto)) {
                Storage::disk('public')->delete($foto->file_foto);
            }
            $foto->file_foto = $request->file('file_foto')->store('dokumentasi_kegiatan', 'public');
        }

        $foto->judul = $validated['judul'];
        $foto->kategori_kegiatan = $validated['kategori_kegiatan'];
        $foto->tanggal_kegiatan = $validated['tanggal_kegiatan'];
        $foto->deskripsi = $validated['deskripsi'] ?? null;
        $foto->tampilkan_di_galeri = $request->has('tampilkan_di_galeri') ? true : false;
        $foto->save();

        return redirect()->back()->with('success', 'Informasi foto kegiatan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if ($user && $user->isJemaat()) {
            abort(403, 'Akses ditolak.');
        }

        $foto = DokumentasiFoto::findOrFail($id);
        if ($foto->file_foto && Storage::disk('public')->exists($foto->file_foto)) {
            Storage::disk('public')->delete($foto->file_foto);
        }
        $judul = $foto->judul;
        $foto->delete();

        AuditLog::log('delete', 'dokumentasi_foto', "Menghapus foto kegiatan: {$judul}", null, null);

        return redirect()->route('admin.dokumentasi.index')->with('success', "Foto kegiatan '{$judul}' berhasil dihapus.");
    }
}
