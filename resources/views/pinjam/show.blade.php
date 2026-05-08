@extends('layouts.app')
@section('title', 'Detail Peminjaman #' . str_pad($pinjam->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div style="max-width:680px;display:flex;flex-direction:column;gap:16px;">

    {{-- Info Transaksi --}}
    <div class="glass" style="border-radius:16px;padding:24px;">
        <p style="color:rgba(255,255,255,0.40);font-size:11px;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;margin-bottom:16px;">Informasi Transaksi</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:y 10px;row-gap:10px;">
            <div>
                <p style="color:rgba(255,255,255,0.40);font-size:12px;margin-bottom:3px;">No. Transaksi</p>
                <p style="color:white;font-weight:600;font-family:monospace;">#{{ str_pad($pinjam->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div>
                <p style="color:rgba(255,255,255,0.40);font-size:12px;margin-bottom:3px;">Status</p>
                <span class="{{ $pinjam->status==='pinjam' ? 'badge-yellow' : 'badge-green' }}"
                      style="display:inline-block;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:500;">
                    {{ $pinjam->status==='pinjam' ? 'Sedang Dipinjam' : 'Sudah Dikembalikan' }}
                </span>
            </div>
            <div>
                <p style="color:rgba(255,255,255,0.40);font-size:12px;margin-bottom:3px;">Anggota</p>
                <p style="color:white;font-weight:500;">{{ $pinjam->user->nama }}</p>
            </div>
            <div>
                <p style="color:rgba(255,255,255,0.40);font-size:12px;margin-bottom:3px;">Email</p>
                <p style="color:rgba(255,255,255,0.75);">{{ $pinjam->user->email }}</p>
            </div>
            <div>
                <p style="color:rgba(255,255,255,0.40);font-size:12px;margin-bottom:3px;">Tanggal Pinjam</p>
                <p style="color:white;font-weight:500;">{{ $pinjam->tgl_pinjam->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Daftar Buku --}}
    <div class="glass" style="border-radius:16px;overflow:hidden;">
        <div style="padding:16px 20px;border-bottom:1px solid rgba(255,255,255,0.07);">
            <p style="color:rgba(255,255,255,0.40);font-size:11px;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;">Daftar Buku</p>
        </div>
        <table class="glass-table" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th style="text-align:center;">Jml</th>
                    <th>Est. Kembali</th>
                    <th style="text-align:center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pinjam->detailPinjam as $dp)
                <tr>
                    <td>
                        <p style="color:white;font-weight:500;">{{ $dp->buku->judul }}</p>
                        <p style="color:rgba(255,255,255,0.35);font-size:12px;margin-top:2px;">{{ $dp->buku->penulis }}</p>
                    </td>
                    <td style="text-align:center;">{{ $dp->jumlah }}</td>
                    <td style="color:rgba(255,255,255,0.75);">{{ $dp->tgl_kembali_estimasi->format('d/m/Y') }}</td>
                    <td style="text-align:center;">
                        @if($dp->detailPengembalian)
                            <span class="badge-green" style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:11.5px;">Kembali</span>
                        @elseif($dp->tgl_kembali_estimasi->isPast())
                            <span class="badge-red" style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:11.5px;">Terlambat</span>
                        @else
                            <span class="badge-yellow" style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:11.5px;">Dipinjam</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Data Pengembalian (jika ada) --}}
    @if($pinjam->pengembalian)
    <div class="glass" style="border-radius:16px;padding:20px;border-color:rgba(52,211,153,0.20);background:rgba(52,211,153,0.04);">
        <p style="color:rgba(255,255,255,0.40);font-size:11px;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;margin-bottom:14px;">Data Pengembalian</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
            <div>
                <p style="color:rgba(255,255,255,0.40);font-size:12px;margin-bottom:3px;">Tanggal Kembali</p>
                <p style="color:white;font-weight:500;">{{ $pinjam->pengembalian->tgl_kembali->format('d/m/Y') }}</p>
            </div>
            <div>
                <p style="color:rgba(255,255,255,0.40);font-size:12px;margin-bottom:3px;">Total Denda</p>
                <p style="color:#fca5a5;font-weight:700;font-size:16px;">
                    Rp {{ number_format($pinjam->pengembalian->total_denda, 0, ',', '.') }}
                </p>
            </div>
        </div>
        <a href="{{ route('pengembalian.show', $pinjam->pengembalian) }}"
           style="font-size:13px;color:#6ee7b7;text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
            Lihat Detail Pengembalian
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    @endif

    {{-- Actions --}}
    <div style="display:flex;gap:10px;">
        <a href="{{ route('pinjam.index') }}" class="btn-secondary"
           style="padding:10px 20px;border-radius:10px;font-size:13.5px;text-decoration:none;display:inline-block;">
            ← Kembali
        </a>
        @if($pinjam->status==='pinjam' && auth()->user()->isAdmin())
        <a href="{{ route('pengembalian.create', ['pinjam_id'=>$pinjam->id]) }}" class="btn-success"
           style="padding:10px 20px;border-radius:10px;font-size:13.5px;text-decoration:none;display:inline-flex;align-items:center;gap:7px;">
            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3"/></svg>
            Proses Pengembalian
        </a>
        @endif
    </div>
</div>
@endsection
