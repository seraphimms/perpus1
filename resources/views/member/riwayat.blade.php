@extends('layouts.app')
@section('title', 'Riwayat Pinjaman Saya')

@section('content')

{{-- Statistik singkat --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;">
    <div class="glass" style="border-radius:14px;padding:18px 20px;">
        <p style="color:rgba(255,255,255,0.45);font-size:12px;margin-bottom:6px;">Total Pinjaman</p>
        <p style="color:white;font-size:24px;font-weight:700;">{{ auth()->user()->pinjam()->count() }}</p>
    </div>
    <div class="glass" style="border-radius:14px;padding:18px 20px;">
        <p style="color:rgba(255,255,255,0.45);font-size:12px;margin-bottom:6px;">Sedang Dipinjam</p>
        <p style="color:#ffffff;font-size:24px;font-weight:700;">{{ auth()->user()->pinjam()->where('status','pinjam')->count() }}</p>
    </div>
    <div class="glass" style="border-radius:14px;padding:18px 20px;">
        <p style="color:rgba(255,255,255,0.45);font-size:12px;margin-bottom:6px;">Sudah Dikembalikan</p>
        <p style="color:#ffffff;font-size:24px;font-weight:700;">{{ auth()->user()->pinjam()->where('status','kembali')->count() }}</p>
    </div>
</div>

{{-- Filter --}}
<div style="display:flex;gap:8px;margin-bottom:16px;">
    <form action="{{ route('member.riwayat') }}" method="GET" style="display:flex;gap:8px;">
        <select name="status" class="glass-select" style="border-radius:10px;padding:8px 14px;font-size:13px;">
            <option value="">Semua Status</option>
            <option value="pinjam" {{ request('status') == 'pinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
            <option value="kembali" {{ request('status') == 'kembali' ? 'selected' : '' }}>Sudah Dikembalikan</option>
        </select>
        <button type="submit" class="btn-secondary" style="padding:8px 16px;border-radius:10px;font-size:13px;cursor:pointer;">Filter</button>
        @if(request('status'))
        <a href="{{ route('member.riwayat') }}" class="btn-secondary" style="padding:8px 16px;border-radius:10px;font-size:13px;text-decoration:none;display:inline-block;">Reset</a>
        @endif
    </form>
</div>

{{-- Daftar Pinjaman --}}
@forelse($pinjaman as $item)
@php
    $tglPinjam  = $item->tgl_pinjam;
    $tglKembali = $item->pengembalian?->tgl_kembali ?? now()->toDateString();
    $durasi = $tglPinjam->startOfDay()->diffInDays(\Carbon\Carbon::parse($tglKembali)->startOfDay());
    $sudahKembali = $item->status === 'kembali';

    // Cek apakah ada yang terlambat
    $terlambat = false;
    if (!$sudahKembali) {
        foreach ($item->detailPinjam as $detail) {
            if ($detail->tgl_kembali_estimasi < now()->toDateString()) {
                $terlambat = true;
                break;
            }
        }
    }
@endphp

<div class="glass" style="border-radius:16px;padding:20px;margin-bottom:12px;{{ $terlambat ? 'border-color:rgba(248,113,113,0.3);' : '' }}">
    {{-- Header transaksi --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:8px;">
        <div style="display:flex;align-items:center;gap:10px;">
            <span style="color:rgba(255,255,255,0.40);font-size:13px;font-weight:600;">#{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
            @if($sudahKembali)
                <span style="background:rgba(52,211,153,0.15);color:#6ee7b7;border:1px solid rgba(52,211,153,0.3);border-radius:20px;padding:3px 12px;font-size:12px;font-weight:500;">✓ Dikembalikan</span>
            @elseif($terlambat)
                <span style="background:rgba(248,113,113,0.15);color:#fca5a5;border:1px solid rgba(248,113,113,0.3);border-radius:20px;padding:3px 12px;font-size:12px;font-weight:500;">⚠ Terlambat</span>
            @else
                <span style="background:rgba(251,191,36,0.15);color:#fbbf24;border:1px solid rgba(251,191,36,0.3);border-radius:20px;padding:3px 12px;font-size:12px;font-weight:500;">📖 Dipinjam</span>
            @endif
        </div>

        {{-- Durasi --}}
        <div style="background:rgba(255,255,255,0.05);border-radius:10px;padding:6px 14px;font-size:12px;color:rgba(255,255,255,0.55);">
            @if($sudahKembali)
                ⏱ Durasi: <strong style="color:white;">{{ $durasi }} hari</strong>
            @else
                ⏳ Hari ke-<strong style="color:white;">{{ $tglPinjam->startOfDay()->diffInDays(now()->startOfDay()) }}</strong> dari pinjam
            @endif
        </div>
    </div>

    {{-- Info tanggal --}}
    <div style="display:flex;gap:16px;margin-bottom:14px;flex-wrap:wrap;">
        <div>
            <p style="color:rgba(255,255,255,0.35);font-size:11px;">Tanggal Pinjam</p>
            <p style="color:white;font-size:13px;font-weight:500;">{{ $item->tgl_pinjam->format('d M Y') }}</p>
        </div>
        @if($sudahKembali && $item->pengembalian)
        <div>
            <p style="color:rgba(255,255,255,0.35);font-size:11px;">Tanggal Kembali</p>
            <p style="color:#6ee7b7;font-size:13px;font-weight:500;">{{ \Carbon\Carbon::parse($item->pengembalian->tgl_kembali)->format('d M Y') }}</p>
        </div>
        @endif
    </div>

    {{-- Daftar buku --}}
    <div style="border-top:1px solid rgba(255,255,255,0.07);padding-top:12px;">
        <p style="color:rgba(255,255,255,0.35);font-size:11px;margin-bottom:8px;">BUKU DIPINJAM</p>
        @foreach($item->detailPinjam as $detail)
        @php
            $estimasi = \Carbon\Carbon::parse($detail->tgl_kembali_estimasi);
            $lewat    = !$sudahKembali && $estimasi->isPast();
            $sisaHari = !$sudahKembali ? (int) now()->startOfDay()->diffInDays($estimasi->startOfDay(), false) : null;
        @endphp
        <div style="display:flex;align-items:center;gap:10px;padding:8px 0;{{ !$loop->last ? 'border-bottom:1px solid rgba(255,255,255,0.05);' : '' }}">
            {{-- Cover mini --}}
            @if($detail->buku->cover)
            <img src="{{ Storage::url($detail->buku->cover) }}" alt="Cover"
                 style="width:28px;height:38px;object-fit:cover;border-radius:4px;flex-shrink:0;">
            @else
            <div style="width:28px;height:38px;background:rgba(255,255,255,0.05);border-radius:4px;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                <svg style="width:12px;height:12px;color:rgba(255,255,255,0.20);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            @endif

            <div style="flex:1;min-width:0;">
                <p style="color:white;font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $detail->buku->judul }}</p>
                <p style="color:rgba(255,255,255,0.40);font-size:11.5px;">{{ $detail->buku->penulis }} · {{ $detail->jumlah }} eks</p>
            </div>

            {{-- Estimasi kembali --}}
            <div style="text-align:right;flex-shrink:0;">
                <p style="color:rgba(255,255,255,0.35);font-size:11px;">Estimasi Kembali</p>
                <p style="font-size:12px;font-weight:500;color:{{ $lewat ? '#fca5a5' : ($sudahKembali ? 'rgba(255,255,255,0.55)' : '#6ee7b7') }};">
                    {{ $estimasi->format('d M Y') }}
                </p>
                @if(!$sudahKembali)
                    @if($lewat)
                        <p style="color:#fca5a5;font-size:11px;">{{ abs($sisaHari) }} hari terlambat</p>
                    @else
                        <p style="color:rgba(255,255,255,0.35);font-size:11px;">{{ $sisaHari }} hari lagi</p>
                    @endif
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@empty
<div class="glass" style="border-radius:16px;padding:40px;text-align:center;">
    <p style="color:rgba(255,255,255,0.25);font-size:14px;">Belum ada riwayat pinjaman.</p>
</div>
@endforelse

@if($pinjaman->hasPages())
<div style="margin-top:16px;">{{ $pinjaman->links() }}</div>
@endif

@endsection

@push('scripts')
@php use Illuminate\Support\Facades\Storage; @endphp
@endpush