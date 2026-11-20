<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KalenderAcara;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KalenderController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $kategori = $request->input('kategori');

        $query = KalenderAcara::query();

        if ($tahun) {
            $query->where(function($q) use ($bulan, $tahun) {
                if ($bulan) {
                    $q->where(function($sub) use ($bulan, $tahun) {
                        $sub->whereMonth('tanggal_mulai', $bulan)
                            ->whereYear('tanggal_mulai', $tahun);
                    })->orWhere(function($sub) use ($bulan, $tahun) {
                        $sub->whereNotNull('tanggal_selesai')
                            ->whereMonth('tanggal_selesai', $bulan)
                            ->whereYear('tanggal_selesai', $tahun);
                    });
                } else {
                    $q->whereYear('tanggal_mulai', $tahun);
                }
            });
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $events = $query->orderBy('tanggal_mulai', 'asc')->get();

        // Upcoming events (dari hari ini ke depan)
        $upcomingEvents = KalenderAcara::where('tanggal_mulai', '>=', now()->toDateString())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(5)
            ->get();

        // Kategori count
        $totalAcaraTahunIni = KalenderAcara::whereYear('tanggal_mulai', $tahun)->count();

        return view('admin.kalender.index', compact(
            'events',
            'upcomingEvents',
            'bulan',
            'tahun',
            'kategori',
            'totalAcaraTahunIni'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_acara' => 'required|string|max:150',
            'kategori' => 'required|string|max:50',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'waktu' => 'nullable|string|max:50',
            'lokasi' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'warna_label' => 'nullable|string|max:20',
            'tampilkan_di_web' => 'nullable|boolean',
        ]);

        KalenderAcara::create([
            'judul_acara' => $validated['judul_acara'],
            'kategori' => $validated['kategori'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
            'waktu' => $validated['waktu'] ?? '09:00 WITA',
            'lokasi' => $validated['lokasi'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'warna_label' => $validated['warna_label'] ?? '#775a19',
            'tampilkan_di_web' => $request->has('tampilkan_di_web'),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.kalender.index', [
            'bulan' => Carbon::parse($validated['tanggal_mulai'])->format('m'),
            'tahun' => Carbon::parse($validated['tanggal_mulai'])->format('Y'),
        ])->with('success', 'Acara kalender berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $event = KalenderAcara::findOrFail($id);

        $validated = $request->validate([
            'judul_acara' => 'required|string|max:150',
            'kategori' => 'required|string|max:50',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'waktu' => 'nullable|string|max:50',
            'lokasi' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'warna_label' => 'nullable|string|max:20',
        ]);

        $event->update([
            'judul_acara' => $validated['judul_acara'],
            'kategori' => $validated['kategori'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
            'waktu' => $validated['waktu'] ?? '09:00 WITA',
            'lokasi' => $validated['lokasi'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'warna_label' => $validated['warna_label'] ?? '#775a19',
            'tampilkan_di_web' => $request->has('tampilkan_di_web'),
        ]);

        return redirect()->back()->with('success', 'Acara kalender berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $event = KalenderAcara::findOrFail($id);
        $event->delete();

        return redirect()->back()->with('success', 'Acara kalender berhasil dihapus!');
    }
}

