<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Pengembalian;
use App\Models\Pinjam;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::sum('jumlah');
        $totalMember = User::where('jenis', 'member')->count();
        $totalPeminjamanAktif = Pinjam::where('status', 'pinjam')->count();
        $totalDenda = Pengembalian::sum('total_denda');

        $peminjamanPerBulan = Pinjam::selectRaw('MONTH(tgl_pinjam) as bulan, YEAR(tgl_pinjam) as tahun, COUNT(*) as total')
            ->whereYear('tgl_pinjam', now()->year)
            ->groupBy('tahun', 'bulan')
            ->orderBy('bulan')
            ->get();

        $labels = [];
        $data = [];
        $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = $namaBulan[$i - 1];
            $found = $peminjamanPerBulan->firstWhere('bulan', $i);
            $data[] = $found ? $found->total : 0;
        }

        return view('dashboard', compact(
            'totalBuku',
            'totalMember',
            'totalPeminjamanAktif',
            'totalDenda',
            'labels',
            'data'
        ));
    }
}
