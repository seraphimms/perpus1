<?php

namespace App\Http\Controllers;

use App\Models\DetailPengembalian;
use App\Models\Pengembalian;
use App\Models\Pinjam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    private const DENDA_PER_HARI = 1000;

    public function index(Request $request)
    {
        $query = Pengembalian::with(['pinjam.user']);

        if ($request->filled('search')) {
            $query->whereHas('pinjam.user', fn($q) => $q->where('nama', 'like', '%' . $request->search . '%'));
        }

        if ($request->filled('dari')) {
            $query->whereDate('tgl_kembali', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('tgl_kembali', '<=', $request->sampai);
        }

        $pengembalian = $query->latest()->paginate(10)->withQueryString();
        return view('pengembalian.index', compact('pengembalian'));
    }

    public function create(Request $request)
    {
        $pinjamAktif = Pinjam::with(['user', 'detailPinjam.buku'])
            ->where('status', 'pinjam')
            ->whereDoesntHave('pengembalian')
            ->get();

        $selectedPinjam = null;
        if ($request->filled('pinjam_id')) {
            $selectedPinjam = Pinjam::with(['user', 'detailPinjam.buku'])
                ->findOrFail($request->pinjam_id);
        }

        return view('pengembalian.create', compact('pinjamAktif', 'selectedPinjam'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pinjam_id'                      => 'required|exists:pinjam,id',
            'tgl_kembali'                    => 'required|date',
            'detail'                         => 'required|array|min:1',
            'detail.*.detail_pinjam_id'      => 'required|exists:detail_pinjam,id',
            'detail.*.kondisi_buku'          => 'required|in:baik,rusak,hilang',
        ]);

        DB::beginTransaction();
        try {
            $pinjam     = Pinjam::with('detailPinjam.buku')->findOrFail($request->pinjam_id);
            $tglKembali = \Carbon\Carbon::parse($request->tgl_kembali);

            $pengembalian = Pengembalian::create([
                'pinjam_id'    => $pinjam->id,
                'tgl_kembali'  => $tglKembali,
                'total_denda'  => 0,
                'status_denda' => 'belum_lunas',
            ]);

            $totalDenda = 0;

            foreach ($request->detail as $item) {
                $detailPinjam = $pinjam->detailPinjam->firstWhere('id', $item['detail_pinjam_id']);

                if (!$detailPinjam) continue;

                $tglEstimasi = \Carbon\Carbon::parse($detailPinjam->tgl_kembali_estimasi);
                $denda       = 0;

                // Denda keterlambatan — hanya hari kerja (Senin-Jumat)
                if ($tglKembali->gt($tglEstimasi)) {
                $hariTelat = $this->hitungHariKerja($tglEstimasi, $tglKembali);
                $denda    += $hariTelat * self::DENDA_PER_HARI * $detailPinjam->jumlah;
                }

                // Status penggantian
                $statusPenggantian = in_array($item['kondisi_buku'], ['rusak', 'hilang'])
                    ? 'belum_diganti'
                    : 'tidak_perlu';

                DetailPengembalian::create([
                    'pengembalian_id'    => $pengembalian->id,
                    'detail_pinjam_id'   => $detailPinjam->id,
                    'tgl_kembali_aktual' => $tglKembali,
                    'kondisi_buku'       => $item['kondisi_buku'],
                    'denda'              => $denda,
                    'status_penggantian' => $statusPenggantian,
                ]);

                // Hanya kondisi baik yang langsung nambah stok
                // Rusak & hilang: stok baru bertambah saat sudah diganti
                if ($item['kondisi_buku'] === 'baik') {
                $detailPinjam->buku->increment('jumlah', $detailPinjam->jumlah);
                }

                $totalDenda += $denda;
                }

            $pengembalian->update(['total_denda' => $totalDenda]);
            $pinjam->update(['status' => 'kembali']);

            DB::commit();
            return redirect()->route('pengembalian.index')->with('success', 'Pengembalian berhasil dicatat. Total denda: Rp ' . number_format($totalDenda, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Pengembalian $pengembalian)
    {
        $pengembalian->load(['pinjam.user', 'detailPengembalian.detailPinjam.buku']);
        return view('pengembalian.show', compact('pengembalian'));
    }

    public function updatePenggantian(Request $request, DetailPengembalian $detail)
    {
        $request->validate([
            'status_penggantian' => 'required|in:belum_diganti,sudah_diganti',
        ]);

        // Rusak & hilang: stok bertambah saat sudah diganti
        if (
        $request->status_penggantian === 'sudah_diganti' &&
        $detail->status_penggantian === 'belum_diganti' &&
        in_array($detail->kondisi_buku, ['rusak', 'hilang'])
        ) {
        $detail->detailPinjam->buku->increment('jumlah', $detail->detailPinjam->jumlah);
        }

        $detail->update(['status_penggantian' => $request->status_penggantian]);

       return back()->with('success', 'Status penggantian berhasil diperbarui.' .
    ($request->status_penggantian === 'sudah_diganti' ? ' Stok buku telah ditambahkan.' : '')
);
    }

    public function tandaiLunas(Pengembalian $pengembalian)
    {
        $pengembalian->update(['status_denda' => 'lunas']);
        return back()->with('success', 'Denda berhasil ditandai lunas.');
    }
    private function hitungHariKerja(\Carbon\Carbon $dari, \Carbon\Carbon $sampai): int
{
    $hariKerja = 0;
    $current   = $dari->copy()->addDay();

    while ($current->lte($sampai)) {
        // 1 = Senin, 5 = Jumat, 6 = Sabtu, 7 = Minggu
        if ($current->dayOfWeek !== 0 && $current->dayOfWeek !== 6) {
            $hariKerja++;
        }
        $current->addDay();
    }

    return $hariKerja;
}
}