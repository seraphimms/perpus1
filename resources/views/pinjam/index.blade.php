@extends('layouts.app')
@section('title', 'Transaksi Peminjaman')

@section('content')
<div class="page-header-row" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;">
    <form action="{{ route('pinjam.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:8px;">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama anggota..."
           class="glass-input" style="border-radius:10px;padding:8px 14px;font-size:13px;width:180px;">
    <select name="status" class="glass-select" style="border-radius:10px;padding:8px 14px;font-size:13px;min-width:140px;">
        <option value="">Semua Status</option>
        <option value="pinjam"  {{ request('status')==='pinjam'  ? 'selected':'' }}>Sedang Dipinjam</option>
        <option value="kembali" {{ request('status')==='kembali' ? 'selected':'' }}>Sudah Kembali</option>
    </select>
    <input type="date" name="dari" value="{{ request('dari') }}"
           class="glass-input" style="border-radius:10px;padding:8px 14px;font-size:13px;">
    <input type="date" name="sampai" value="{{ request('sampai') }}"
           class="glass-input" style="border-radius:10px;padding:8px 14px;font-size:13px;">
    <button type="submit" class="btn-secondary" style="padding:8px 16px;border-radius:10px;font-size:13px;cursor:pointer;">Cari</button>
    @if(request()->hasAny(['search','status','dari','sampai']))
    <a href="{{ route('pinjam.index') }}" class="btn-secondary" style="padding:8px 16px;border-radius:10px;font-size:13px;text-decoration:none;display:inline-block;">Reset</a>
    @endif
</form>

    @if(auth()->user()->isAdmin())
    <a href="{{ route('pinjam.create') }}" class="btn-primary"
       style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:10px;font-size:13.5px;text-decoration:none;">
        <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Buat Transaksi
    </a>
    @endif
</div>

<div class="glass" style="border-radius:16px;overflow:hidden;">
    <div class="table-responsive"><table class="glass-table" style="width:100%;border-collapse:collapse;">
        <thead>
            <tr>
                <th class="hide-mobile">No. Transaksi</th>
                <th>Anggota</th>
                <th>Tgl Pinjam</th>
                <th class="hide-mobile" style="text-align:center;">Jml Buku</th>
                <th style="text-align:center;">Status</th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pinjam as $item)
            <tr>
                <td class="hide-mobile" style="font-family:monospace;color:rgba(255,255,255,0.50);font-size:13px;">
                    #{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                </td>
                <td>
                    <div style="display:flex;align-items:center;gap:9px;">
                        <div style="width:28px;height:28px;background:linear-gradient(135deg,#3b82f6,#6366f1);border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:white;flex-shrink:0;">
                            {{ strtoupper(substr($item->user->nama,0,1)) }}
                        </div>
                        <span style="color:white;font-weight:500;">{{ $item->user->nama }}</span>
                    </div>
                </td>
                <td>{{ $item->tgl_pinjam->format('d/m/Y') }}</td>
                <td style="text-align:center;">
                    <span class="badge-blue" style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                        {{ $item->detailPinjam->count() }}
                    </span>
                </td>
                <td style="text-align:center;">
                    <span class="{{ $item->status==='pinjam' ? 'badge-yellow' : 'badge-green' }}"
                          style="display:inline-block;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:500;">
                        {{ $item->status==='pinjam' ? 'Dipinjam' : 'Kembali' }}
                    </span>
                </td>
                <td style="text-align:center;">
                    <div style="display:flex;align-items:center;justify-content:center;gap:10px;">
                        <a href="{{ route('pinjam.show',$item) }}"
                           style="font-size:12.5px;color:#93c5fd;font-weight:500;text-decoration:none;">Detail</a>
                        @if($item->status==='pinjam' && auth()->user()->isAdmin())
                        <a href="{{ route('pengembalian.create',['pinjam_id'=>$item->id]) }}"
                           style="font-size:12.5px;color:#6ee7b7;font-weight:500;text-decoration:none;">Kembalikan</a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:rgba(255,255,255,0.25);padding:40px;">
                    Belum ada transaksi peminjaman.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table></div>
    @if($pinjam->hasPages())
    <div style="padding:14px 16px;border-top:1px solid rgba(255,255,255,0.07);">{{ $pinjam->links() }}</div>
    @endif
</div>
@endsection