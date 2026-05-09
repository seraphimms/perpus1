@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
<style>
    .dash-card {
        background: rgba(255,255,255,0.07);
        backdrop-filter: blur(28px);
        -webkit-backdrop-filter: blur(28px);
        border: 1px solid rgba(255,255,255,0.13);
        border-top: 1px solid rgba(255,255,255,0.20);
        border-left: 1px solid rgba(255,255,255,0.16);
        box-shadow: 0 8px 32px rgba(0,0,0,0.25), inset 0 1px 0 rgba(255,255,255,0.12);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .dash-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.30), inset 0 1px 0 rgba(255,255,255,0.16);
        border-color: rgba(255,255,255,0.20);
    }
    .stat-card-new {
        background: rgba(255,255,255,0.07);
        backdrop-filter: blur(28px);
        -webkit-backdrop-filter: blur(28px);
        border: 1px solid rgba(255,255,255,0.13);
        border-top: 1px solid rgba(255,255,255,0.22);
        border-left: 1px solid rgba(255,255,255,0.16);
        box-shadow: 0 8px 32px rgba(0,0,0,0.25), inset 0 1px 0 rgba(255,255,255,0.12);
        border-radius: 14px;
        padding: 14px 16px;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card-new::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
    }
    .stat-card-new::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.04) 0%, transparent 60%);
        pointer-events: none;
    }
    .stat-card-new:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 48px rgba(0,0,0,0.30), inset 0 1px 0 rgba(255,255,255,0.18);
    }
    .quick-card {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.09);
        border-top: 1px solid rgba(255,255,255,0.15);
        border-radius: 10px;
        padding: 9px 12px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: transform 0.18s ease, background 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease;
        position: relative;
        overflow: hidden;
    }
    .quick-card:hover {
        transform: translateY(-1px);
        background: rgba(255,255,255,0.09);
        border-color: rgba(255,255,255,0.16);
        box-shadow: 0 6px 20px rgba(0,0,0,0.20);
    }
    .quick-card:active { transform: translateY(0); }
    .activity-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 7px 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        transition: background 0.15s;
    }
    .activity-item:last-child { border-bottom: none; padding-bottom: 0; }
    .section-label {
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.30);
        margin-bottom: 10px;
    }
</style>
@endpush

@section('content')

{{-- ── Row 1: Stat Cards ── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:14px;">

    <div class="stat-card-new" style="padding:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <div style="width:44px;height:44px;background:linear-gradient(135deg,#3b82f6,#60a5fa);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(59,130,246,0.40);">
                <svg style="width:22px;height:22px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <a href="{{ route('buku.index') }}" style="color:rgba(255,255,255,0.25);font-size:11px;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='rgba(255,255,255,0.6)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">Lihat →</a>
        </div>
        <p style="color:rgba(255,255,255,0.45);font-size:12px;margin-bottom:4px;">Total Buku</p>
        <p style="color:white;font-size:28px;font-weight:700;line-height:1;">{{ number_format($totalBuku) }}</p>
    </div>

    <div class="stat-card-new" style="padding:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <div style="width:44px;height:44px;background:linear-gradient(135deg,#10b981,#34d399);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(16,185,129,0.40);">
                <svg style="width:22px;height:22px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <a href="{{ route('users.index') }}" style="color:rgba(255,255,255,0.25);font-size:11px;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='rgba(255,255,255,0.6)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">Lihat →</a>
        </div>
        <p style="color:rgba(255,255,255,0.45);font-size:12px;margin-bottom:4px;">Total Member</p>
        <p style="color:white;font-size:28px;font-weight:700;line-height:1;">{{ number_format($totalMember) }}</p>
    </div>

    <div class="stat-card-new" style="padding:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <div style="width:44px;height:44px;background:linear-gradient(135deg,#f59e0b,#fbbf24);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(245,158,11,0.40);">
                <svg style="width:22px;height:22px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <a href="{{ route('pinjam.index') }}" style="color:rgba(255,255,255,0.25);font-size:11px;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='rgba(255,255,255,0.6)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">Lihat →</a>
        </div>
        <p style="color:rgba(255,255,255,0.45);font-size:12px;margin-bottom:4px;">Peminjaman Aktif</p>
        <p style="color:white;font-size:28px;font-weight:700;line-height:1;">{{ number_format($totalPeminjamanAktif) }}</p>
    </div>

    <div class="stat-card-new" style="padding:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <div style="width:44px;height:44px;background:linear-gradient(135deg,#ef4444,#f87171);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(239,68,68,0.40);">
                <svg style="width:22px;height:22px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <a href="{{ route('laporan.pengembalian') }}" style="color:rgba(255,255,255,0.25);font-size:11px;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='rgba(255,255,255,0.6)'" onmouseout="this.style.color='rgba(255,255,255,0.25)'">Lihat →</a>
        </div>
        <p style="color:rgba(255,255,255,0.45);font-size:12px;margin-bottom:4px;">Total Denda</p>
        <p style="color:white;font-size:26px;font-weight:700;line-height:1;">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
    </div>
</div>

{{-- ── Row 2: Quick Access + Chart + Recent Activity ── --}}
<div style="display:grid;grid-template-columns:220px 1fr 260px;gap:12px;">

    {{-- Quick Access --}}
    <div class="dash-card" style="border-radius:14px;padding:16px;">
        <p class="section-label">Quick Access</p>
        <div style="display:flex;flex-direction:column;gap:6px;">
            <a href="{{ route('pinjam.create') }}" class="quick-card">
                <div style="width:30px;height:30px;background:linear-gradient(135deg,#6366f1,#818cf8);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 3px 8px rgba(99,102,241,0.35);">
                    <svg style="width:14px;height:14px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <p style="color:white;font-size:12px;font-weight:500;">Buat Peminjaman</p>
                    <p style="color:rgba(255,255,255,0.30);font-size:10px;">Transaksi baru</p>
                </div>
            </a>
            <a href="{{ route('pengembalian.create') }}" class="quick-card">
                <div style="width:30px;height:30px;background:linear-gradient(135deg,#10b981,#34d399);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 3px 8px rgba(16,185,129,0.35);">
                    <svg style="width:14px;height:14px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/></svg>
                </div>
                <div>
                    <p style="color:white;font-size:12px;font-weight:500;">Catat Pengembalian</p>
                    <p style="color:rgba(255,255,255,0.30);font-size:10px;">Proses kembali buku</p>
                </div>
            </a>
            <a href="{{ route('buku.create') }}" class="quick-card">
                <div style="width:30px;height:30px;background:linear-gradient(135deg,#3b82f6,#60a5fa);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 3px 8px rgba(59,130,246,0.35);">
                    <svg style="width:14px;height:14px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <p style="color:white;font-size:12px;font-weight:500;">Tambah Buku</p>
                    <p style="color:rgba(255,255,255,0.30);font-size:10px;">Input koleksi baru</p>
                </div>
            </a>
            <a href="{{ route('users.create') }}" class="quick-card">
                <div style="width:30px;height:30px;background:linear-gradient(135deg,#f59e0b,#fbbf24);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 3px 8px rgba(245,158,11,0.35);">
                    <svg style="width:14px;height:14px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </div>
                <div>
                    <p style="color:white;font-size:12px;font-weight:500;">Tambah Member</p>
                    <p style="color:rgba(255,255,255,0.30);font-size:10px;">Daftarkan anggota</p>
                </div>
            </a>
            <a href="{{ route('laporan.pinjam') }}" class="quick-card">
                <div style="width:30px;height:30px;background:linear-gradient(135deg,#8b5cf6,#a78bfa);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 3px 8px rgba(139,92,246,0.35);">
                    <svg style="width:14px;height:14px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p style="color:white;font-size:12px;font-weight:500;">Lihat Laporan</p>
                    <p style="color:rgba(255,255,255,0.30);font-size:10px;">Rekap & statistik</p>
                </div>
            </a>
        </div>
    </div>

    {{-- Chart --}}
    <div class="dash-card" style="border-radius:14px;padding:16px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <div>
                <p class="section-label" style="margin-bottom:2px;">Statistik</p>
                <h2 style="color:white;font-size:14px;font-weight:600;">Grafik Peminjaman</h2>
                <p style="color:rgba(255,255,255,0.35);font-size:11px;margin-top:1px;">Per bulan — {{ now()->year }}</p>
            </div>
            <div style="background:rgba(99,102,241,0.15);border:1px solid rgba(99,102,241,0.25);border-radius:8px;padding:3px 12px;">
                <span style="color:#a5b4fc;font-size:11px;font-weight:500;">{{ now()->year }}</span>
            </div>
        </div>
        <canvas id="chartPeminjaman" height="95"></canvas>
    </div>

    {{-- Recent Activity --}}
    <div class="dash-card" style="border-radius:14px;padding:16px;">
        <p class="section-label">Aktivitas Terbaru <span style="color:rgba(255,255,255,0.20);font-weight:400;text-transform:none;letter-spacing:0;">(2 jam)</span></p>
        @if($recentActivity->isEmpty())
        <div style="text-align:center;padding:24px 0;">
            <div style="width:36px;height:36px;background:rgba(255,255,255,0.06);border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 8px;">
                <svg style="width:17px;height:17px;color:rgba(255,255,255,0.25);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p style="color:rgba(255,255,255,0.25);font-size:12px;">Tidak ada aktivitas.</p>
        </div>
        @else
        @foreach($recentActivity as $activity)
        <div class="activity-item">
            <div style="width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;
                {{ $activity['type'] === 'pinjam' ? 'background:rgba(99,102,241,0.15);' : 'background:rgba(16,185,129,0.15);' }}">
                @if($activity['type'] === 'pinjam')
                <svg style="width:13px;height:13px;color:#a5b4fc;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                @else
                <svg style="width:13px;height:13px;color:#6ee7b7;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                @endif
            </div>
            <div style="flex:1;min-width:0;">
                <p style="color:white;font-size:12px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $activity['nama'] }}</p>
                <p style="color:rgba(255,255,255,0.35);font-size:10px;margin-top:1px;">{{ $activity['keterangan'] }}</p>
                <p style="color:rgba(255,255,255,0.20);font-size:10px;">{{ $activity['waktu'] }}</p>
            </div>
        </div>
        @endforeach
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
window.addEventListener('load', function() {
    const ctx = document.getElementById('chartPeminjaman').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Peminjaman',
                data: {!! json_encode($data) !!},
                backgroundColor: 'rgba(99,102,241,0.60)',
                borderColor: 'rgba(99,102,241,0.90)',
                borderWidth: 0,
                borderRadius: 5,
                borderSkipped: false,
            }]
        },
        options: {
            animation: {
            duration: 2000,
            easing: 'easeOutBounce',
            delay: (context) => context.dataIndex * 100,
            },
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(10,15,40,0.92)',
                    titleColor: 'rgba(255,255,255,0.65)',
                    bodyColor: '#fff',
                    borderColor: 'rgba(99,102,241,0.35)',
                    borderWidth: 1,
                    padding: 8,
                    cornerRadius: 8,
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255,255,255,0.04)', drawBorder: false },
                    ticks: { color: 'rgba(255,255,255,0.35)', font: { size: 10 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false },
                    ticks: { color: 'rgba(255,255,255,0.35)', stepSize: 1, font: { size: 10 } }
                }
            }
        }
    });
});
</script>
@endpush