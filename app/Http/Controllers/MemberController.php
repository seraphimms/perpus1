<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->pinjam()
            ->with(['detailPinjam.buku', 'pengembalian'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pinjaman = $query->paginate(10)->withQueryString();

        return view('member.riwayat', compact('pinjaman'));
    }
}