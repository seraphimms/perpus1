<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Pengembalian;
use App\Models\Pinjam;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
    if (auth()->user()->isAdmin()) {
        return $this->adminDashboard();
    }
    return $this->memberDashboard();
    }

    private function adminDashboard()
{
    $totalBuku            = Buku::sum('jumlah');
    $totalMember          = User::where('jenis', 'member')->count();
    $totalPeminjamanAktif = Pinjam::where('status', 'pinjam')->count();
    $totalDenda = Pengembalian::where('status_denda', 'belum_lunas')->sum('total_denda');

    // Chart data
    $peminjamanPerBulan = Pinjam::selectRaw('MONTH(tgl_pinjam) as bulan, YEAR(tgl_pinjam) as tahun, COUNT(*) as total')
        ->whereYear('tgl_pinjam', now()->year)
        ->groupBy('tahun', 'bulan')
        ->orderBy('bulan')
        ->get();

    $labels    = [];
    $data      = [];
    $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

    for ($i = 1; $i <= 12; $i++) {
        $labels[] = $namaBulan[$i - 1];
        $found    = $peminjamanPerBulan->firstWhere('bulan', $i);
        $data[]   = $found ? $found->total : 0;
    }

    // Recent activity (gabung pinjam + pengembalian, 8 terbaru)
    $recentPinjam = Pinjam::with('user')
    ->where('created_at', '>=', now()->subHours(2))
    ->latest()
    ->take(8)
    ->get()
    ->map(fn($p) => [
        'type'       => 'pinjam',
        'nama'       => $p->user->nama,
        'keterangan' => 'Meminjam ' . $p->detailPinjam()->count() . ' buku',
        'waktu'      => $p->created_at->locale('id')->diffForHumans(),
        'created_at' => $p->created_at,
    ]);

$recentKembali = \App\Models\Pengembalian::with('pinjam.user')
    ->where('created_at', '>=', now()->subHours(2))
    ->latest()
    ->take(8)
    ->get()
    ->map(fn($k) => [
        'type'       => 'kembali',
        'nama'       => $k->pinjam->user->nama,
        'keterangan' => 'Mengembalikan buku',
        'waktu'      => $k->created_at->locale('id')->diffForHumans(),
        'created_at' => $k->created_at,
    ]);

$recentActivity = $recentPinjam->concat($recentKembali)
    ->sortByDesc('created_at')
    ->take(8)
    ->values();

    // Peminjaman terlambat
    $pinjamanTerlambat = Pinjam::with(['user', 'detailPinjam'])
        ->where('status', 'pinjam')
        ->whereHas('detailPinjam', fn($q) => $q->where('tgl_kembali_estimasi', '<', now()->toDateString()))
        ->latest()
        ->take(5)
        ->get();

    // Notifikasi peminjaman
$notifikasi = \App\Models\DetailPinjam::with(['pinjam.user', 'buku'])
    ->whereHas('pinjam', fn($q) => $q->where('status', 'pinjam'))
    ->where(function($q) {
        $q->where('tgl_kembali_estimasi', '<', now()->toDateString())
          ->orWhereBetween('tgl_kembali_estimasi', [
              now()->toDateString(),
              now()->addDays(1)->toDateString()
          ]);
    })
    ->get()
    ->map(fn($dp) => [
        'nama'    => $dp->pinjam->user->nama,
        'judul'   => $dp->buku->judul,
        'tgl'     => $dp->tgl_kembali_estimasi,
        'sisa'    => now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($dp->tgl_kembali_estimasi)->startOfDay(), false),
        'type'    => \Carbon\Carbon::parse($dp->tgl_kembali_estimasi)->isPast() ? 'terlambat' : 'hampir',
    ])
    ->sortBy('sisa')
    ->values();

$notifCount = $notifikasi->count();

    return view('dashboard', compact(
    'totalBuku', 'totalMember', 'totalPeminjamanAktif', 'totalDenda',
    'labels', 'data', 'recentActivity', 'pinjamanTerlambat',
    'notifikasi', 'notifCount'
    ));
}

    private function memberDashboard()
    {
        $user = auth()->user();

        $totalPinjaman    = $user->pinjam()->count();
        $sedangDipinjam   = $user->pinjam()->where('status', 'pinjam')->count();
        $sudahDikembalikan = $user->pinjam()->where('status', 'kembali')->count();

        // Pinjaman aktif dengan detail buku
        $pinjamanAktif = $user->pinjam()
            ->with(['detailPinjam.buku'])
            ->where('status', 'pinjam')
            ->latest()
            ->get();

        // 4 buku terbaru
        $bukuTerbaru = Buku::with('kategori')->latest()->take(4)->get();

        // Notifikasi untuk member
$notifikasi = \App\Models\DetailPinjam::with(['pinjam', 'buku'])
    ->whereHas('pinjam', fn($q) => $q->where('status', 'pinjam')->where('user_id', $user->id))
    ->where(function($q) {
        $q->where('tgl_kembali_estimasi', '<', now()->toDateString())
          ->orWhereBetween('tgl_kembali_estimasi', [
              now()->toDateString(),
              now()->addDays(1)->toDateString()
          ]);
    })
    ->get()
    ->map(fn($dp) => [
        'nama'  => $dp->buku->judul,
        'judul' => $dp->buku->judul,
        'tgl'   => $dp->tgl_kembali_estimasi,
        'sisa'  => now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($dp->tgl_kembali_estimasi)->startOfDay(), false),
        'type'  => \Carbon\Carbon::parse($dp->tgl_kembali_estimasi)->isPast() ? 'terlambat' : 'hampir',
    ])
    ->sortBy('sisa')
    ->values();

$notifCount = $notifikasi->count();

        return view('dashboard-member', compact(
        'totalPinjaman', 'sedangDipinjam', 'sudahDikembalikan',
        'pinjamanAktif', 'bukuTerbaru', 'notifikasi', 'notifCount'
    ));
    }
}