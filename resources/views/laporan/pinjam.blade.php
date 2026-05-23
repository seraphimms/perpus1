@extends('layouts.app')
@section('title', 'Laporan Peminjaman')

@section('content')
{{-- Filter --}}
<div class="glass" style="border-radius:16px;padding:20px 24px;margin-bottom:16px;">
    <form action="{{ route('laporan.pinjam') }}" method="GET"
          style="display:flex;flex-wrap:wrap;align-items:flex-end;gap:12px;">
        <div>
            <label style="display:block;color:rgba(255,255,255,0.55);font-size:12px;font-weight:500;margin-bottom:6px;">Dari Tanggal</label>
            <input type="date" name="dari" value="{{ request('dari') }}"
                   class="glass-input" style="border-radius:9px;padding:8px 12px;font-size:13px;box-sizing:border-box;">
        </div>
        <div>
            <label style="display:block;color:rgba(255,255,255,0.55);font-size:12px;font-weight:500;margin-bottom:6px;">Sampai Tanggal</label>
            <input type="date" name="sampai" value="{{ request('sampai') }}"
                   class="glass-input" style="border-radius:9px;padding:8px 12px;font-size:13px;box-sizing:border-box;">
        </div>
        <div>
            <label style="display:block;color:rgba(255,255,255,0.55);font-size:12px;font-weight:500;margin-bottom:6px;">Status</label>
            <select name="status" class="glass-select"
                    style="border-radius:9px;padding:8px 12px;font-size:13px;min-width:140px;box-sizing:border-box;">
                <option value="">Semua</option>
                <option value="pinjam"  {{ request('status')==='pinjam'  ? 'selected':'' }}>Dipinjam</option>
                <option value="kembali" {{ request('status')==='kembali' ? 'selected':'' }}>Kembali</option>
            </select>
        </div>
        <div style="display:flex;gap:8px;align-items:flex-end;">
            <button type="submit" class="btn-primary"
                    style="padding:9px 18px;border-radius:9px;font-size:13px;border:none;cursor:pointer;">
                Filter
            </button>
            @if(request()->hasAny(['dari','sampai','status']))
            <a href="{{ route('laporan.pinjam') }}" class="btn-secondary"
               style="padding:9px 16px;border-radius:9px;font-size:13px;text-decoration:none;display:inline-block;">
                Reset
            </a>
            @endif
        </div>
        <div style="margin-left:auto;display:flex;gap:8px;">
        <a href="{{ route('laporan.pinjam.excel', request()->all()) }}"
        style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:9px;font-size:13px;text-decoration:none;background:linear-gradient(135deg,#10b981,#059669);color:white;font-weight:500;">
            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export Excel
        </a>
        <a href="{{ route('laporan.pinjam.pdf', request()->all()) }}"
        style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:9px;font-size:13px;text-decoration:none;background:linear-gradient(135deg,#ef4444,#dc2626);color:white;font-weight:500;">
            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export PDF
        </a>
    </div>
    </form>
</div>

{{-- Summary --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;padding:0 4px;">
    <p style="color:rgba(255,255,255,0.45);font-size:13px;">
        Menampilkan <strong style="color:white;">{{ $pinjam->count() }}</strong> transaksi
    </p>
</div>

{{-- Table --}}
<div class="glass" style="border-radius:16px;overflow:hidden;">
    <table class="glass-table" style="width:100%;border-collapse:collapse;">
        <thead>
            <tr>
                <th style="width:36px;">#</th>
                <th>No. Transaksi</th>
                <th>Anggota</th>
                <th>Tgl Pinjam</th>
                <th>Buku Dipinjam</th>
                <th style="text-align:center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pinjam as $i => $item)
            <tr>
                <td style="color:rgba(255,255,255,0.30);">{{ $i + 1 }}</td>
                <td style="font-family:monospace;color:rgba(255,255,255,0.50);font-size:13px;">
                    #{{ str_pad($item->id,5,'0',STR_PAD_LEFT) }}
                </td>
                <td style="color:white;font-weight:500;">{{ $item->user->nama }}</td>
                <td>{{ $item->tgl_pinjam->format('d/m/Y') }}</td>
                <td>
                    <div style="display:flex;flex-direction:column;gap:3px;">
                        @foreach($item->detailPinjam as $dp)
                        <span style="font-size:12.5px;color:rgba(255,255,255,0.70);">
                            {{ $dp->buku->judul }}
                            <span style="color:rgba(255,255,255,0.35);">({{ $dp->jumlah }})</span>
                        </span>
                        @endforeach
                    </div>
                </td>
                <td style="text-align:center;">
                    <span class="{{ $item->status==='pinjam' ? 'badge-yellow' : 'badge-green' }}"
                          style="display:inline-block;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:500;">
                        {{ $item->status==='pinjam' ? 'Dipinjam' : 'Kembali' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:rgba(255,255,255,0.25);padding:40px;">
                    Tidak ada data untuk filter yang dipilih.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
