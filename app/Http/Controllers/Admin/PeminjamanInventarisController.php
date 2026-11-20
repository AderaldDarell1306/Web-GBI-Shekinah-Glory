<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Inventaris;
use App\Models\PeminjamanInventaris;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanInventarisController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $status = $request->input('status');

        $query = PeminjamanInventaris::with(['inventaris', 'petugas']);

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('nama_peminjam', 'like', "%{$q}%")
                    ->orWhere('kode_peminjaman', 'like', "%{$q}%")
                    ->orWhere('instansi_kegiatan', 'like', "%{$q}%")
                    ->orWhereHas('inventaris', function($invQ) use ($q) {
                        $invQ->where('nama_barang', 'like', "%{$q}%")
                             ->orWhere('kode_inventaris', 'like', "%{$q}%");
                    });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $peminjamanList = $query->orderBy('tanggal_pinjam', 'desc')->paginate(15)->withQueryString();
        $inventarisList = Inventaris::where('status_ketersediaan', '!=', 'Dihapuskan')->orderBy('nama_barang')->get();

        return view('admin.inventaris.peminjaman', compact('peminjamanList', 'inventarisList', 'q', 'status'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->isJemaat()) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'nama_peminjam' => 'required|string|max:150',
            'no_kontak' => 'nullable|string|max:30',
            'instansi_kegiatan' => 'required|string|max:150',
            'tanggal_pinjam' => 'required|date',
            'tanggal_rencana_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jumlah_pinjam' => 'required|integer|min:1',
            'kondisi_sebelum' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
        ]);

        // Generate Kode Peminjaman: PINJAM-YYYYMM-XXXX
        $tglStr = Carbon::parse($validated['tanggal_pinjam'])->format('Ym');
        $count = PeminjamanInventaris::whereYear('tanggal_pinjam', Carbon::parse($validated['tanggal_pinjam'])->year)->count() + 1;
        $kode = sprintf('PINJAM-%s-%03d', $tglStr, $count);

        $peminjaman = PeminjamanInventaris::create([
            'kode_peminjaman' => $kode,
            'inventaris_id' => $validated['inventaris_id'],
            'nama_peminjam' => $validated['nama_peminjam'],
            'no_kontak' => $validated['no_kontak'],
            'instansi_kegiatan' => $validated['instansi_kegiatan'],
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_rencana_kembali' => $validated['tanggal_rencana_kembali'],
            'jumlah_pinjam' => $validated['jumlah_pinjam'],
            'status' => 'Sedang Dipinjam',
            'kondisi_sebelum' => $validated['kondisi_sebelum'] ?? 'Baik',
            'catatan' => $validated['catatan'],
            'petugas_id' => Auth::id(),
        ]);

        // Update status inventaris menjadi Dipinjam
        Inventaris::where('id', $validated['inventaris_id'])->update(['status_ketersediaan' => 'Dipinjam']);

        AuditLog::log('create', 'peminjaman_inventaris', "Mencatat peminjaman inventaris: {$peminjaman->kode_peminjaman} oleh {$peminjaman->nama_peminjam}", null, $peminjaman->toArray());

        return redirect()->route('admin.peminjaman-inventaris.index')->with('success', 'Catatan peminjaman inventaris berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user && $user->isJemaat()) {
            abort(403, 'Akses ditolak.');
        }

        $peminjaman = PeminjamanInventaris::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:Sedang Dipinjam,Sudah Dikembalikan,Terlambat',
            'tanggal_kembali_aktual' => 'nullable|date',
            'kondisi_sesudah' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
        ]);

        if ($validated['status'] === 'Sudah Dikembalikan' && empty($validated['tanggal_kembali_aktual'])) {
            $validated['tanggal_kembali_aktual'] = Carbon::today();
        }

        $peminjaman->update($validated);

        // Jika sudah dikembalikan, periksa apakah masih ada peminjaman aktif lain untuk aset ini
        if ($validated['status'] === 'Sudah Dikembalikan') {
            $activeOtherLoans = PeminjamanInventaris::where('inventaris_id', $peminjaman->inventaris_id)
                ->where('status', 'Sedang Dipinjam')
                ->where('id', '!=', $id)
                ->exists();

            if (!$activeOtherLoans) {
                Inventaris::where('id', $peminjaman->inventaris_id)->update(['status_ketersediaan' => 'Tersedia']);
            }
        }

        return redirect()->back()->with('success', 'Status peminjaman inventaris berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if ($user && $user->isJemaat()) {
            abort(403, 'Akses ditolak.');
        }

        $peminjaman = PeminjamanInventaris::findOrFail($id);
        $peminjaman->delete();

        return redirect()->back()->with('success', 'Data peminjaman inventaris berhasil dihapus.');
    }
}
