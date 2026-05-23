@extends('layouts.app')
@section('title', 'Laporan Pengembalian')

@section('content')
{{-- Filter --}}
<div class="glass" style="border-radius:16px;padding:20px 24px;margin-bottom:16px;">
    <form action="{{ route('laporan.pengembalian') }}" method="GET"
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
        <div style="display:flex;gap:8px;align-items:flex-end;">
            <button type="submit" class="btn-primary"
                    style="padding:9px 18px;border-radius:9px;font-size:13px;border:none;cursor:pointer;">
                Filter
            </button>
            @if(request()->hasAny(['dari','sampai']))
            <a href="{{ route('laporan.pengembalian') }}" class="btn-secondary"
               style="padding:9px 16px;border-radius:9px;font-size:13px;text-decoration:none;display:inline-block;">
                Reset
            </a>
            @endif
        </div>
            <div style="margin-left:auto;display:flex;gap:8px;">
        <a href="{{ route('laporan.pengembalian.excel', request()->all()) }}"
        style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:9px;font-size:13px;text-decoration:none;background:linear-gradient(135deg,#10b981,#059669);color:white;font-weight:500;">
            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export Excel
        </a>
        <a href="{{ route('laporan.pengembalian.pdf', request()->all()) }}"
        style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:9px;font-size:13px;text-decoration:none;background:linear-gradient(135deg,#ef4444,#dc2626);color:white;font-weight:500;">
            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export PDF
        </a>
</div>
    </form>
</div>

{{-- Summary --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
    <div class="glass" style="border-radius:12px;padding:14px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:36px;height:36px;background:linear-gradient(135deg,#3b82f6,#6366f1);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:18px;height:18px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
        </div>
        <div>
            <p style="color:rgba(255,255,255,0.45);font-size:12px;">Total Transaksi</p>
            <p style="color:white;font-weight:700;font-size:20px;">{{ $pengembalian->count() }}</p>
        </div>
    </div>
    <div class="glass" style="border-radius:12px;padding:14px 18px;display:flex;align-items:center;gap:12px;">
        <div style="width:36px;height:36px;background:linear-gradient(135deg,#ef4444,#f87171);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:18px;height:18px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
        </div>
        <div>
            <p style="color:rgba(255,255,255,0.45);font-size:12px;">Total Denda</p>
            <p style="color:#fca5a5;font-weight:700;font-size:18px;">
                Rp {{ number_format($pengembalian->sum('total_denda'), 0, ',', '.') }}
            </p>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="glass" style="border-radius:16px;overflow:hidden;">
    <table class="glass-table" style="width:100%;border-collapse:collapse;">
        <thead>
            <tr>
                <th style="width:36px;">#</th>
                <th>No. Pinjam</th>
                <th>Anggota</th>
                <th>Tgl Kembali</th>
                <th>Buku & Kondisi</th>
                <th style="text-align:right;">Total Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengembalian as $i => $item)
            <tr>
                <td style="color:rgba(255,255,255,0.30);">{{ $i + 1 }}</td>
                <td style="font-family:monospace;color:rgba(255,255,255,0.50);font-size:13px;">
                    #{{ str_pad($item->pinjam->id,5,'0',STR_PAD_LEFT) }}
                </td>
                <td style="color:white;font-weight:500;">{{ $item->pinjam->user->nama }}</td>
                <td>{{ $item->tgl_kembali->format('d/m/Y') }}</td>
                <td>
                    <div style="display:flex;flex-direction:column;gap:4px;">
                        @foreach($item->detailPengembalian as $dp)
                        <div style="display:flex;align-items:center;gap:7px;">
                            <span style="font-size:12.5px;color:rgba(255,255,255,0.70);">{{ $dp->detailPinjam->buku->judul }}</span>
                            @php
                                $kc = match($dp->kondisi_buku) {
                                    'baik'   => 'badge-green',
                                    'rusak'  => 'badge-yellow',
                                    'hilang' => 'badge-red',
                                    default  => 'badge-blue'
                                };
                            @endphp
                            <span class="{{ $kc }}" style="display:inline-block;padding:1px 8px;border-radius:20px;font-size:11px;">
                                {{ $dp->kondisi_buku }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </td>
                <td style="text-align:right;font-weight:600;{{ $item->total_denda > 0 ? 'color:#fca5a5;' : 'color:rgba(255,255,255,0.40);' }}">
                    Rp {{ number_format($item->total_denda, 0, ',', '.') }}
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
