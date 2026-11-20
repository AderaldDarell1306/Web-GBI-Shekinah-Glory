<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Inventaris;
use App\Models\PeminjamanInventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventarisController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $lokasi = $request->input('lokasi'); // Kantor, Gereja, Gudang
        $jenis = $request->input('jenis');
        $kondisi = $request->input('kondisi');

        $query = Inventaris::with('creator');

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('nama_barang', 'like', "%{$q}%")
                    ->orWhere('kode_inventaris', 'like', "%{$q}%")
                    ->orWhere('nomor', 'like', "%{$q}%")
                    ->orWhere('jenis_inventaris', 'like', "%{$q}%");
            });
        }

        if ($lokasi) {
            $query->where('lokasi', $lokasi);
        }

        if ($jenis) {
            $query->where('jenis_inventaris', $jenis);
        }

        if ($kondisi) {
            $query->where('kondisi', $kondisi);
        }

        $inventarisList = $query->orderBy('nomor')->paginate(15)->withQueryString();

        // Statistik
        $totalAset = Inventaris::count();
        $totalKantor = Inventaris::where('lokasi', 'Kantor')->count();
        $totalGereja = Inventaris::where('lokasi', 'Gereja')->count();
        $totalGudang = Inventaris::where('lokasi', 'Gudang')->count();
        $totalDipinjam = PeminjamanInventaris::where('status', 'Sedang Dipinjam')->count();

        // Jenis list untuk filter
        $jenisList = Inventaris::select('jenis_inventaris')->distinct()->pluck('jenis_inventaris');

        return view('admin.inventaris.index', compact(
            'inventarisList',
            'q',
            'lokasi',
            'jenis',
            'kondisi',
            'totalAset',
            'totalKantor',
            'totalGereja',
            'totalGudang',
            'totalDipinjam',
            'jenisList'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->isJemaat()) {
            abort(403, 'Akses ditolak: Jemaat tidak memiliki wewenang mengelola inventaris.');
        }

        // AC-R05: mewajibkan input No., Jenis Inv., Kode Inv., dan memilih Lokasi (Kantor/Gereja/Gudang)
        $validated = $request->validate([
            'nomor' => 'required|string|max:50',
            'jenis_inventaris' => 'required|string|max:100',
            'kode_inventaris' => 'required|string|max:100|unique:inventaris,kode_inventaris',
            'nama_barang' => 'required|string|max:150',
            'lokasi' => 'required|in:Kantor,Gereja,Gudang',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:30',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'status_ketersediaan' => 'required|in:Tersedia,Dipinjam,Dalam Perbaikan,Dihapuskan',
            'tanggal_perolehan' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ], [
            'nomor.required' => 'Nomor (No.) inventaris wajib diisi.',
            'jenis_inventaris.required' => 'Jenis Inventaris wajib diisi.',
            'kode_inventaris.required' => 'Kode Inventaris wajib diisi.',
            'kode_inventaris.unique' => 'Kode Inventaris sudah digunakan.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'lokasi.required' => 'Lokasi wajib dipilih (Kantor, Gereja, atau Gudang).',
            'lokasi.in' => 'Lokasi harus salah satu dari: Kantor, Gereja, Gudang.',
        ]);

        $validated['created_by'] = Auth::id();

        $inv = Inventaris::create($validated);
        AuditLog::log('create', 'inventaris', "Menambahkan aset inventaris: {$inv->nama_barang} ({$inv->kode_inventaris})", null, $inv->toArray());

        return redirect()->route('admin.inventaris.index')->with('success', 'Barang inventaris berhasil dicatat!');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user && $user->isJemaat()) {
            abort(403, 'Akses ditolak: Jemaat tidak memiliki wewenang mengelola inventaris.');
        }

        $inv = Inventaris::findOrFail($id);
        $oldValues = $inv->toArray();

        $validated = $request->validate([
            'nomor' => 'required|string|max:50',
            'jenis_inventaris' => 'required|string|max:100',
            'kode_inventaris' => 'required|string|max:100|unique:inventaris,kode_inventaris,' . $id,
            'nama_barang' => 'required|string|max:150',
            'lokasi' => 'required|in:Kantor,Gereja,Gudang',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:30',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'status_ketersediaan' => 'required|in:Tersedia,Dipinjam,Dalam Perbaikan,Dihapuskan',
            'tanggal_perolehan' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $inv->update($validated);
        AuditLog::log('update', 'inventaris', "Memperbarui aset inventaris: {$inv->nama_barang}", $oldValues, $inv->toArray());

        return redirect()->route('admin.inventaris.index')->with('success', 'Data inventaris berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if ($user && $user->isJemaat()) {
            abort(403, 'Akses ditolak: Jemaat tidak memiliki wewenang mengelola inventaris.');
        }

        $inv = Inventaris::findOrFail($id);
        $nama = $inv->nama_barang;
        $inv->delete();

        AuditLog::log('delete', 'inventaris', "Menghapus aset inventaris: {$nama}", null, null);

        return redirect()->route('admin.inventaris.index')->with('success', "Aset inventaris {$nama} berhasil dihapus.");
    }

    /**
     * Halaman Cetak Laporan Pinjam Pakai Inventaris Gereja (AC-R05)
     */
    public function laporanPinjam(Request $request)
    {
        $status = $request->input('status');
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $query = PeminjamanInventaris::with(['inventaris', 'petugas']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($bulan && $tahun) {
            $query->whereMonth('tanggal_pinjam', $bulan)->whereYear('tanggal_pinjam', $tahun);
        }

        $peminjamanList = $query->orderBy('tanggal_pinjam', 'desc')->get();

        return view('admin.inventaris.laporan_pinjam', compact('peminjamanList', 'status', 'bulan', 'tahun'));
    }
}
