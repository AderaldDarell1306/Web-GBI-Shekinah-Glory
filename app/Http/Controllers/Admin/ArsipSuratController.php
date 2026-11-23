<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArsipSurat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArsipSuratController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $jenis = $request->input('jenis');
        $kategori = $request->input('kategori');
        $q = $request->input('q');

        $query = ArsipSurat::query();

        if ($tahun) {
            $query->where('tahun_kalender', $tahun);
        }

        if ($jenis) {
            $query->where('jenis_surat', $jenis);
        }

        if ($kategori) {
            $query->where('kategori_surat', $kategori);
        }

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('nomor_surat', 'like', "%{$q}%")
                    ->orWhere('pengirim', 'like', "%{$q}%")
                    ->orWhere('tujuan_penerima', 'like', "%{$q}%")
                    ->orWhere('perihal', 'like', "%{$q}%")
                    ->orWhere('ringkasan_isi', 'like', "%{$q}%");
            });
        }

        $suratList = $query->orderBy('tanggal_surat', 'desc')->paginate(15)->withQueryString();

        // Metrics tahun berjalan
        $totalMasukTahunIni = ArsipSurat::where('tahun_kalender', $tahun)->where('jenis_surat', 'masuk')->count();
        $totalKeluarTahunIni = ArsipSurat::where('tahun_kalender', $tahun)->where('jenis_surat', 'keluar')->count();
        $totalArsipAll = ArsipSurat::count();

        return view('admin.arsip.index', compact(
            'suratList',
            'tahun',
            'jenis',
            'kategori',
            'q',
            'totalMasukTahunIni',
            'totalKeluarTahunIni',
            'totalArsipAll'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat' => 'required|in:masuk,keluar',
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tanggal_terima_kirim' => 'nullable|date',
            'pengirim' => 'required|string|max:150',
            'tujuan_penerima' => 'required|string|max:150',
            'perihal' => 'required|string|max:255',
            'lampiran' => 'nullable|string|max:100',
            'kategori_surat' => 'required|string|max:50',
            'kode_akses' => 'nullable|string|max:50|unique:arsip_surat,kode_akses',
            'ringkasan_isi' => 'nullable|string',
            'status' => 'required|in:tersimpan,proses,selesai',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('file_surat')) {
            $filePath = $request->file('file_surat')->store('arsip_dokumen', 'public');
        }

        $tahunKalender = Carbon::parse($validated['tanggal_surat'])->year;

        ArsipSurat::create([
            'jenis_surat' => $validated['jenis_surat'],
            'nomor_surat' => $validated['nomor_surat'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'tanggal_terima_kirim' => $validated['tanggal_terima_kirim'] ?? $validated['tanggal_surat'],
            'pengirim' => $validated['pengirim'],
            'tujuan_penerima' => $validated['tujuan_penerima'],
            'perihal' => $validated['perihal'],
            'lampiran' => $validated['lampiran'] ?? null,
            'kategori_surat' => $validated['kategori_surat'],
            'kode_akses' => !empty($validated['kode_akses']) ? strtoupper(trim($validated['kode_akses'])) : null,
            'tahun_kalender' => $tahunKalender,
            'file_surat' => $filePath,
            'ringkasan_isi' => $validated['ringkasan_isi'],
            'status' => $validated['status'],
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.arsip.index', ['tahun' => $tahunKalender])
            ->with('success', 'Dokumen surat berhasil dicatat ke dalam buku arsip!');
    }

    public function update(Request $request, $id)
    {
        $surat = ArsipSurat::findOrFail($id);

        $validated = $request->validate([
            'jenis_surat' => 'required|in:masuk,keluar',
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tanggal_terima_kirim' => 'nullable|date',
            'pengirim' => 'required|string|max:150',
            'tujuan_penerima' => 'required|string|max:150',
            'perihal' => 'required|string|max:255',
            'lampiran' => 'nullable|string|max:100',
            'kategori_surat' => 'required|string|max:50',
            'kode_akses' => 'nullable|string|max:50|unique:arsip_surat,kode_akses,' . $id,
            'ringkasan_isi' => 'nullable|string',
            'status' => 'required|in:tersimpan,proses,selesai',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('file_surat')) {
            if ($surat->file_surat && Storage::disk('public')->exists($surat->file_surat)) {
                Storage::disk('public')->delete($surat->file_surat);
            }
            $surat->file_surat = $request->file('file_surat')->store('arsip_dokumen', 'public');
        }

        $surat->jenis_surat = $validated['jenis_surat'];
        $surat->nomor_surat = $validated['nomor_surat'];
        $surat->tanggal_surat = $validated['tanggal_surat'];
        $surat->tanggal_terima_kirim = $validated['tanggal_terima_kirim'] ?? $validated['tanggal_surat'];
        $surat->pengirim = $validated['pengirim'];
        $surat->tujuan_penerima = $validated['tujuan_penerima'];
        $surat->perihal = $validated['perihal'];
        $surat->lampiran = $validated['lampiran'] ?? null;
        $surat->kategori_surat = $validated['kategori_surat'];
        $surat->kode_akses = !empty($validated['kode_akses']) ? strtoupper(trim($validated['kode_akses'])) : null;
        $surat->tahun_kalender = Carbon::parse($validated['tanggal_surat'])->year;
        $surat->ringkasan_isi = $validated['ringkasan_isi'];
        $surat->status = $validated['status'];
        $surat->updated_by = Auth::id();
        $surat->save();

        return redirect()->back()->with('success', 'Data arsip surat berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $surat = ArsipSurat::findOrFail($id);
        if ($surat->file_surat && Storage::disk('public')->exists($surat->file_surat)) {
            Storage::disk('public')->delete($surat->file_surat);
        }
        $surat->delete();

        return redirect()->back()->with('success', 'Dokumen surat berhasil dihapus dari arsip!');
    }

    public function cetakBukuAgenda(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $jenis = $request->input('jenis');

        $query = ArsipSurat::where('tahun_kalender', $tahun);
        if ($jenis) {
            $query->where('jenis_surat', $jenis);
        }

        $suratList = $query->orderBy('tanggal_surat', 'asc')->get();

        return view('admin.arsip.cetak-agenda', compact('suratList', 'tahun', 'jenis'));
    }
}

