@extends('layouts.app')
@section('title', 'Transaksi Pengembalian')

@section('content')
<div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;">
    <form action="{{ route('pengembalian.index') }}" method="GET" style="display:flex;gap:8px;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama anggota..."
               class="glass-input" style="border-radius:10px;padding:8px 14px;font-size:13px;width:210px;">
        <button type="submit" class="btn-secondary" style="padding:8px 16px;border-radius:10px;font-size:13px;cursor:pointer;">Cari</button>
        @if(request('search'))
        <a href="{{ route('pengembalian.index') }}" class="btn-secondary"
           style="padding:8px 16px;border-radius:10px;font-size:13px;text-decoration:none;display:inline-block;">Reset</a>
        @endif
    </form>

    @if(auth()->user()->isAdmin())
    <a href="{{ route('pengembalian.create') }}" class="btn-success"
       style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:10px;font-size:13.5px;text-decoration:none;">
        <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Catat Pengembalian
    </a>
    @endif
</div>

<div class="glass" style="border-radius:16px;overflow:hidden;">
    <table class="glass-table" style="width:100%;border-collapse:collapse;">
        <thead>
            <tr>
                <th style="width:36px;">#</th>
                <th>No. Pinjam</th>
                <th>Anggota</th>
                <th>Tgl Kembali</th>
                <th style="text-align:right;">Total Denda</th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengembalian as $i => $item)
            <tr>
                <td style="color:rgba(255,255,255,0.30);">{{ $pengembalian->firstItem() + $i }}</td>
                <td style="font-family:monospace;color:rgba(255,255,255,0.50);font-size:13px;">
                    #{{ str_pad($item->pinjam->id, 5, '0', STR_PAD_LEFT) }}
                </td>
                <td>
                    <div style="display:flex;align-items:center;gap:9px;">
                        <div style="width:28px;height:28px;background:linear-gradient(135deg,#10b981,#34d399);border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:white;flex-shrink:0;">
                            {{ strtoupper(substr($item->pinjam->user->nama,0,1)) }}
                        </div>
                        <span style="color:white;font-weight:500;">{{ $item->pinjam->user->nama }}</span>
                    </div>
                </td>
                <td>{{ $item->tgl_kembali->format('d/m/Y') }}</td>
                <td style="text-align:right;font-weight:600;{{ $item->total_denda > 0 ? 'color:#fca5a5;' : 'color:rgba(255,255,255,0.50);' }}">
                    Rp {{ number_format($item->total_denda, 0, ',', '.') }}
                </td>
                <td style="text-align:center;">
                    <a href="{{ route('pengembalian.show',$item) }}"
                       style="font-size:12.5px;color:#93c5fd;font-weight:500;text-decoration:none;">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:rgba(255,255,255,0.25);padding:40px;">
                    Belum ada data pengembalian.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($pengembalian->hasPages())
    <div style="padding:14px 16px;border-top:1px solid rgba(255,255,255,0.07);">{{ $pengembalian->links() }}</div>
    @endif
</div>
@endsection
