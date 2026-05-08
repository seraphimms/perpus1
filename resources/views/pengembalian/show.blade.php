@extends('layouts.app')
@section('title', 'Detail Pengembalian')

@section('content')
<div style="max-width:680px;display:flex;flex-direction:column;gap:16px;">

    {{-- Info Pengembalian --}}
    <div class="glass" style="border-radius:16px;padding:24px;">
        <p style="color:rgba(255,255,255,0.40);font-size:11px;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;margin-bottom:16px;">Informasi Pengembalian</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;row-gap:14px;">
            <div>
                <p style="color:rgba(255,255,255,0.40);font-size:12px;margin-bottom:3px;">No. Transaksi Pinjam</p>
                <p style="color:white;font-weight:600;font-family:monospace;">#{{ str_pad($pengembalian->pinjam->id,5,'0',STR_PAD_LEFT) }}</p>
            </div>
            <div>
                <p style="color:rgba(255,255,255,0.40);font-size:12px;margin-bottom:3px;">Tanggal Kembali</p>
                <p style="color:white;font-weight:500;">{{ $pengembalian->tgl_kembali->format('d/m/Y') }}</p>
            </div>
            <div>
                <p style="color:rgba(255,255,255,0.40);font-size:12px;margin-bottom:3px;">Anggota</p>
                <p style="color:white;font-weight:500;">{{ $pengembalian->pinjam->user->nama }}</p>
            </div>
            <div>
                <p style="color:rgba(255,255,255,0.40);font-size:12px;margin-bottom:3px;">Total Denda</p>
                <p style="font-weight:700;font-size:20px;{{ $pengembalian->total_denda > 0 ? 'color:#fca5a5;' : 'color:#6ee7b7;' }}">
                    Rp {{ number_format($pengembalian->total_denda, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Rincian Per Buku --}}
    <div class="glass" style="border-radius:16px;overflow:hidden;">
        <div style="padding:16px 20px;border-bottom:1px solid rgba(255,255,255,0.07);">
            <p style="color:rgba(255,255,255,0.40);font-size:11px;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;">Rincian Per Buku</p>
        </div>
        <table class="glass-table" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th style="text-align:center;">Kondisi</th>
                    <th>Tgl Kembali</th>
                    <th style="text-align:right;">Denda</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengembalian->detailPengembalian as $dp)
                <tr>
                    <td style="color:white;font-weight:500;">{{ $dp->detailPinjam->buku->judul }}</td>
                    <td style="text-align:center;">
                        @php
                            $kondisiClass = match($dp->kondisi_buku) {
                                'baik'   => 'badge-green',
                                'rusak'  => 'badge-yellow',
                                'hilang' => 'badge-red',
                                default  => 'badge-blue'
                            };
                        @endphp
                        <span class="{{ $kondisiClass }}"
                              style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:500;">
                            {{ ucfirst($dp->kondisi_buku) }}
                        </span>
                    </td>
                    <td>{{ $dp->tgl_kembali_aktual->format('d/m/Y') }}</td>
                    <td style="text-align:right;font-weight:600;{{ $dp->denda > 0 ? 'color:#fca5a5;' : 'color:rgba(255,255,255,0.40);' }}">
                        Rp {{ number_format($dp->denda, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:rgba(255,255,255,0.04);">
                    <td colspan="3"
                        style="padding:12px 16px;text-align:right;color:rgba(255,255,255,0.60);font-size:13px;font-weight:600;border-top:1px solid rgba(255,255,255,0.07);">
                        Total Denda
                    </td>
                    <td style="padding:12px 16px;text-align:right;font-weight:700;font-size:15px;color:#fca5a5;border-top:1px solid rgba(255,255,255,0.07);">
                        Rp {{ number_format($pengembalian->total_denda, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Actions --}}
    <div style="display:flex;gap:10px;">
        <a href="{{ route('pengembalian.index') }}" class="btn-secondary"
           style="padding:10px 20px;border-radius:10px;font-size:13.5px;text-decoration:none;display:inline-block;">
            ← Kembali
        </a>
        <a href="{{ route('pinjam.show', $pengembalian->pinjam) }}"
           style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:10px;font-size:13.5px;text-decoration:none;background:rgba(99,102,241,0.15);border:1px solid rgba(99,102,241,0.25);color:#a5b4fc;">
            Lihat Transaksi Pinjam
        </a>
    </div>
</div>
@endsection
