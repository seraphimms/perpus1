<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\DetailPinjam;
use App\Models\Pinjam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PinjamController extends Controller
{
    public function index(Request $request)
    {
        $query = Pinjam::with(['user', 'detailPinjam.buku']);

    if ($request->filled('search')) {
        $query->whereHas('user', fn($q) => $q->where('nama', 'like', '%' . $request->search . '%'));
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('dari')) {
        $query->whereDate('tgl_pinjam', '>=', $request->dari);
    }

    if ($request->filled('sampai')) {
        $query->whereDate('tgl_pinjam', '<=', $request->sampai);
    }

    $pinjam = $query->latest()->paginate(10)->withQueryString();
    return view('pinjam.index', compact('pinjam'));
}

    public function create()
    {
        $members = User::where('jenis', 'member')->orderBy('nama')->get();
        $buku = Buku::where('jumlah', '>', 0)->orderBy('judul')->get();
        return view('pinjam.create', compact('members', 'buku'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tgl_pinjam' => 'required|date',
            'buku' => 'required|array|min:1',
            'buku.*.buku_id' => 'required|exists:buku,id',
            'buku.*.jumlah' => 'required|integer|min:1',
            'buku.*.tgl_kembali_estimasi' => 'required|date|after:tgl_pinjam',
        ], [
            'buku.required' => 'Minimal satu buku harus dipilih.',
            'buku.*.buku_id.required' => 'Buku harus dipilih.',
            'buku.*.tgl_kembali_estimasi.after' => 'Tanggal kembali harus setelah tanggal pinjam.',
        ]);

        DB::beginTransaction();
        try {
            $pinjam = Pinjam::create([
                'user_id' => $request->user_id,
                'tgl_pinjam' => $request->tgl_pinjam,
                'status' => 'pinjam',
            ]);

            foreach ($request->buku as $item) {
                $buku = Buku::findOrFail($item['buku_id']);

                if ($buku->jumlah < $item['jumlah']) {
                    DB::rollBack();
                    return back()->with('error', "Stok buku \"{$buku->judul}\" tidak mencukupi (tersedia: {$buku->jumlah}).")->withInput();
                }

                DetailPinjam::create([
                    'pinjam_id' => $pinjam->id,
                    'buku_id' => $item['buku_id'],
                    'jumlah' => $item['jumlah'],
                    'tgl_kembali_estimasi' => $item['tgl_kembali_estimasi'],
                ]);

                $buku->decrement('jumlah', $item['jumlah']);
            }

            DB::commit();
            return redirect()->route('pinjam.index')->with('success', 'Transaksi peminjaman berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Pinjam $pinjam)
    {
        $pinjam->load(['user', 'detailPinjam.buku', 'pengembalian.detailPengembalian.detailPinjam.buku']);
        return view('pinjam.show', compact('pinjam'));
    }
}
