<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Pinjam;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Exports\PinjamExport;
use App\Exports\PengembalianExport;
use Maatwebsite\Excel\Facades\Excel;

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

        $pinjam     = $query->latest()->get();
        $dari       = $request->dari;
        $sampai     = $request->sampai;
        $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
$grafikData = collect();
for ($i = 1; $i <= 12; $i++) {
    $total = $pinjam->filter(fn($p) => $p->tgl_pinjam->month === $i)->count();
    $grafikData->put($namaBulan[$i - 1], $total);
}
$maxGrafik = $grafikData->max() ?: 1;

        $pdf = Pdf::loadView('laporan.pinjam-pdf', compact('pinjam', 'dari', 'sampai', 'grafikData', 'maxGrafik'))
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
        $dari         = $request->dari;
        $sampai       = $request->sampai;
        $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $grafikData = collect();
        for ($i = 1; $i <= 12; $i++) {
            $total = $pengembalian->filter(fn($p) => \Carbon\Carbon::parse($p->tgl_kembali)->month === $i)->count();
            $grafikData->put($namaBulan[$i - 1], $total);
        }
        $maxGrafik = $grafikData->max() ?: 1;

        $pdf = Pdf::loadView('laporan.pengembalian-pdf', compact('pengembalian', 'dari', 'sampai', 'grafikData', 'maxGrafik'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pengembalian-' . now()->format('Ymd') . '.pdf');
    }
    public function pinjamExcel(Request $request)
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

    return Excel::download(
        new PinjamExport($pinjam, $request->dari, $request->sampai),
        'laporan-peminjaman-' . now()->format('Ymd') . '.xlsx'
    );
}

public function pengembalianExcel(Request $request)
{
    $query = Pengembalian::with(['pinjam.user', 'detailPengembalian.detailPinjam.buku']);

    if ($request->filled('dari')) {
        $query->whereDate('tgl_kembali', '>=', $request->dari);
    }
    if ($request->filled('sampai')) {
        $query->whereDate('tgl_kembali', '<=', $request->sampai);
    }

    $pengembalian = $query->latest()->get();

    return Excel::download(
        new PengembalianExport($pengembalian, $request->dari, $request->sampai),
        'laporan-pengembalian-' . now()->format('Ymd') . '.xlsx'
    );
}
}