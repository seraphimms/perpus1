<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Pinjam;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function pinjam(Request $request)
    {
        $query = Pinjam::with(['user', 'detailPinjam.buku']);

        if ($request->filled('dari')) {
            $query->whereDate('tgl_pinjam', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('tgl_pinjam', '<=', $request->sampai);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pinjam = $query->latest()->get();

        return view('laporan.pinjam', compact('pinjam'));
    }

    public function pinjamPdf(Request $request)
    {
        $query = Pinjam::with(['user', 'detailPinjam.buku']);

        if ($request->filled('dari')) {
            $query->whereDate('tgl_pinjam', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('tgl_pinjam', '<=', $request->sampai);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pinjam = $query->latest()->get();
        $dari = $request->dari;
        $sampai = $request->sampai;

        $pdf = Pdf::loadView('laporan.pinjam-pdf', compact('pinjam', 'dari', 'sampai'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-peminjaman-' . now()->format('Ymd') . '.pdf');
    }

    public function pengembalian(Request $request)
    {
        $query = Pengembalian::with(['pinjam.user', 'detailPengembalian.detailPinjam.buku']);

        if ($request->filled('dari')) {
            $query->whereDate('tgl_kembali', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('tgl_kembali', '<=', $request->sampai);
        }

        $pengembalian = $query->latest()->get();

        return view('laporan.pengembalian', compact('pengembalian'));
    }

    public function pengembalianPdf(Request $request)
    {
        $query = Pengembalian::with(['pinjam.user', 'detailPengembalian.detailPinjam.buku']);

        if ($request->filled('dari')) {
            $query->whereDate('tgl_kembali', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('tgl_kembali', '<=', $request->sampai);
        }

        $pengembalian = $query->latest()->get();
        $dari = $request->dari;
        $sampai = $request->sampai;

        $pdf = Pdf::loadView('laporan.pengembalian-pdf', compact('pengembalian', 'dari', 'sampai'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pengembalian-' . now()->format('Ymd') . '.pdf');
    }
}
