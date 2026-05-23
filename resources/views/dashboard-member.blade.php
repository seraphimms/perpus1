@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
<style>
    .member-card {
        background: rgba(255,255,255,0.07);
        backdrop-filter: blur(28px);
        -webkit-backdrop-filter: blur(28px);
        border: 1px solid rgba(255,255,255,0.13);
        border-top: 1px solid rgba(255,255,255,0.20);
        border-left: 1px solid rgba(255,255,255,0.16);
        box-shadow: 0 8px 32px rgba(0,0,0,0.25), inset 0 1px 0 rgba(255,255,255,0.12);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .member-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.30), inset 0 1px 0 rgba(255,255,255,0.16);
    }
    .stat-member {
        background: rgba(255,255,255,0.07);
        backdrop-filter: blur(28px);
        -webkit-backdrop-filter: blur(28px);
        border: 1px solid rgba(255,255,255,0.13);
        border-top: 1px solid rgba(255,255,255,0.22);
        border-left: 1px solid rgba(255,255,255,0.16);
        box-shadow: 0 8px 32px rgba(0,0,0,0.25), inset 0 1px 0 rgba(255,255,255,0.12);
        border-radius: 16px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-member::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
    }
    .stat-member:hover { transform: translateY(-2px); }
    .pinjam-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .pinjam-row:last-child { border-bottom: none; padding-bottom: 0; }
    .section-label {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.30);
        margin-bottom: 12px;
    }
</style>
@endpush

@section('content')

{{-- ── Row 1: Sapaan ── --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
    <div>
        <h2 style="color:white;font-size:20px;font-weight:700;">Selamat datang, {{ auth()->user()->nama }}! 👋</h2>
        <p style="color:rgba(255,255,255,0.40);font-size:13px;margin-top:3px;">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
    </div>
    <a href="{{ route('profil.index') }}"
       style="display:flex;align-items:center;gap:8px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);border-radius:12px;padding:8px 14px;text-decoration:none;transition:background 0.2s;"
       onmouseover="this.style.background='rgba(255,255,255,0.10)'" onmouseout="this.style.background='rgba(255,255,255,0.06)'">
        <div style="width:28px;height:28px;background:linear-gradient(135deg,#3b82f6,#6366f1);border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;color:white;">
            {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
        </div>
        <div>
            <p style="color:white;font-size:12px;font-weight:500;">{{ auth()->user()->nama }}</p>
            <p style="color:rgba(255,255,255,0.35);font-size:10px;">Lihat profil →</p>
        </div>
    </a>
</div>

{{-- ── Row 2: Stat Cards ── --}}
<div class="stat-grid-responsive" style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:14px;">
    <div class="stat-member">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <div style="width:42px;height:42px;background:linear-gradient(135deg,#3b82f6,#60a5fa);border-radius:11px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(59,130,246,0.40);">
                <svg style="width:20px;height:20px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <a href="{{ route('member.riwayat') }}" style="color:rgba(255,255,255,0.25);font-size:11px;text-decoration:none;" onmouseover="this.style.color='rgba(255,255,255,0.6)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">Lihat →</a>
        </div>
        <p style="color:rgba(255,255,255,0.45);font-size:12px;margin-bottom:4px;">Total Pinjaman</p>
        <p style="color:white;font-size:32px;font-weight:700;line-height:1;">{{ $totalPinjaman }}</p>
    </div>

    <div class="stat-member">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <div style="width:42px;height:42px;background:linear-gradient(135deg,#f59e0b,#fbbf24);border-radius:11px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(245,158,11,0.40);">
                <svg style="width:20px;height:20px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
        </div>
        <p style="color:rgba(255,255,255,0.45);font-size:12px;margin-bottom:4px;">Sedang Dipinjam</p>
        <p style="color:#fbbf24;font-size:32px;font-weight:700;line-height:1;">{{ $sedangDipinjam }}</p>
    </div>

    <div class="stat-member">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <div style="width:42px;height:42px;background:linear-gradient(135deg,#10b981,#34d399);border-radius:11px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(16,185,129,0.40);">
                <svg style="width:20px;height:20px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>
        <p style="color:rgba(255,255,255,0.45);font-size:12px;margin-bottom:4px;">Sudah Dikembalikan</p>
        <p style="color:#34d399;font-size:32px;font-weight:700;line-height:1;">{{ $sudahDikembalikan }}</p>
    </div>
</div>

{{-- ── Row 3: Pinjaman Aktif + Buku Terbaru ── --}}
<div class="dashboard-bottom-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

    {{-- Pinjaman Aktif --}}
    <div class="member-card" style="border-radius:16px;padding:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <p class="section-label" style="margin-bottom:0;">Pinjaman Aktif</p>
            <a href="{{ route('member.riwayat') }}" style="color:rgba(255,255,255,0.25);font-size:11px;text-decoration:none;" onmouseover="this.style.color='rgba(255,255,255,0.55)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">Semua →</a>
        </div>

        @if($pinjamanAktif->isEmpty())
        <div style="text-align:center;padding:30px 0;">
            <div style="width:44px;height:44px;background:rgba(52,211,153,0.12);border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                <svg style="width:22px;height:22px;color:#6ee7b7;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p style="color:rgba(255,255,255,0.30);font-size:13px;">Tidak ada pinjaman aktif.</p>
        </div>
        @else
        @foreach($pinjamanAktif as $pinjam)
        @foreach($pinjam->detailPinjam->take(4) as $detail)
        @php
            $estimasi  = \Carbon\Carbon::parse($detail->tgl_kembali_estimasi)->startOfDay();
            $sisaHari  = (int) now()->startOfDay()->diffInDays($estimasi, false);
            $terlambat = $sisaHari < 0;
        @endphp
        <div class="pinjam-row">
            @if($detail->buku->cover)
            <img src="{{ Storage::url($detail->buku->cover) }}" alt="Cover"
                 style="width:34px;height:46px;object-fit:cover;border-radius:5px;flex-shrink:0;">
            @else
            <div style="width:34px;height:46px;background:rgba(255,255,255,0.06);border-radius:5px;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                <svg style="width:14px;height:14px;color:rgba(255,255,255,0.20);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            @endif
            <div style="flex:1;min-width:0;">
                <p style="color:white;font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $detail->buku->judul }}</p>
                <p style="color:rgba(255,255,255,0.40);font-size:11px;margin-top:2px;">{{ $detail->buku->penulis }}</p>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <p style="color:rgba(255,255,255,0.30);font-size:11px;">{{ $estimasi->format('d M Y') }}</p>
                @if($terlambat)
                <span style="display:inline-block;margin-top:3px;background:rgba(239,68,68,0.15);color:#fca5a5;border:1px solid rgba(239,68,68,0.25);border-radius:20px;padding:2px 10px;font-size:11px;">⚠ {{ abs($sisaHari) }} hari telat</span>
                @elseif($sisaHari <= 3)
                <span style="display:inline-block;margin-top:3px;background:rgba(245,158,11,0.15);color:#fbbf24;border:1px solid rgba(245,158,11,0.25);border-radius:20px;padding:2px 10px;font-size:11px;">⏰ {{ $sisaHari }} hari lagi</span>
                @else
                <span style="display:inline-block;margin-top:3px;background:rgba(52,211,153,0.15);color:#6ee7b7;border:1px solid rgba(52,211,153,0.25);border-radius:20px;padding:2px 10px;font-size:11px;">✓ {{ $sisaHari }} hari lagi</span>
                @endif
            </div>
        </div>
        @endforeach
        @endforeach
        @endif
    </div>

    {{-- Buku Terbaru --}}
    <div class="member-card" style="border-radius:16px;padding:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <p class="section-label" style="margin-bottom:0;">Buku Terbaru</p>
            <a href="{{ route('buku.index') }}" style="color:rgba(255,255,255,0.25);font-size:11px;text-decoration:none;" onmouseover="this.style.color='rgba(255,255,255,0.55)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">Semua →</a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;">
            @foreach($bukuTerbaru as $buku)
            <a href="{{ route('buku.index') }}" style="text-decoration:none;">
                <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:10px;transition:background 0.2s,transform 0.2s;"
                     onmouseover="this.style.background='rgba(255,255,255,0.09)';this.style.transform='translateY(-2px)'"
                     onmouseout="this.style.background='rgba(255,255,255,0.04)';this.style.transform='translateY(0)'">
                    @if($buku->cover)
                    <img src="{{ Storage::url($buku->cover) }}" alt="Cover"
                         style="width:100%;height:90px;object-fit:cover;border-radius:8px;margin-bottom:8px;">
                    @else
                    <div style="width:100%;height:90px;background:rgba(255,255,255,0.05);border-radius:8px;margin-bottom:8px;display:flex;align-items:center;justify-content:center;">
                        <svg style="width:22px;height:22px;color:rgba(255,255,255,0.20);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    @endif
                    <p style="color:white;font-size:11px;font-weight:500;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $buku->judul }}</p>
                    <p style="color:rgba(255,255,255,0.35);font-size:10px;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $buku->penulis }}</p>
                    <span style="display:inline-block;margin-top:5px;background:rgba(59,130,246,0.15);color:#93c5fd;border-radius:20px;padding:2px 8px;font-size:10px;">{{ $buku->kategori->nama }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>

</div>

@endsection