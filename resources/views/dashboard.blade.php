@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
{{-- Stat Cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">

    <div class="glass stat-card" style="border-radius:16px;padding:20px 20px 18px;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
            <div style="width:44px;height:44px;background:linear-gradient(135deg,#3b82f6,#60a5fa);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(59,130,246,0.35);">
                <svg style="width:22px;height:22px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
        </div>
        <p style="color:rgba(255,255,255,0.50);font-size:12px;margin-bottom:4px;">Total Buku</p>
        <p style="color:white;font-size:26px;font-weight:700;line-height:1;">{{ number_format($totalBuku) }}</p>
    </div>

    <div class="glass stat-card" style="border-radius:16px;padding:20px 20px 18px;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
            <div style="width:44px;height:44px;background:linear-gradient(135deg,#10b981,#34d399);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(16,185,129,0.35);">
                <svg style="width:22px;height:22px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
        <p style="color:rgba(255,255,255,0.50);font-size:12px;margin-bottom:4px;">Total Member</p>
        <p style="color:white;font-size:26px;font-weight:700;line-height:1;">{{ number_format($totalMember) }}</p>
    </div>

    <div class="glass stat-card" style="border-radius:16px;padding:20px 20px 18px;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
            <div style="width:44px;height:44px;background:linear-gradient(135deg,#f59e0b,#fbbf24);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(245,158,11,0.35);">
                <svg style="width:22px;height:22px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>
        <p style="color:rgba(255,255,255,0.50);font-size:12px;margin-bottom:4px;">Peminjaman Aktif</p>
        <p style="color:white;font-size:26px;font-weight:700;line-height:1;">{{ number_format($totalPeminjamanAktif) }}</p>
    </div>

    <div class="glass stat-card" style="border-radius:16px;padding:20px 20px 18px;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
            <div style="width:44px;height:44px;background:linear-gradient(135deg,#ef4444,#f87171);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(239,68,68,0.35);">
                <svg style="width:22px;height:22px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p style="color:rgba(255,255,255,0.50);font-size:12px;margin-bottom:4px;">Total Denda</p>
        <p style="color:white;font-size:22px;font-weight:700;line-height:1;">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
    </div>
</div>

{{-- Chart --}}
<div class="glass" style="border-radius:16px;padding:24px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <div>
            <h2 style="color:white;font-size:15px;font-weight:600;">Grafik Peminjaman</h2>
            <p style="color:rgba(255,255,255,0.40);font-size:12px;margin-top:2px;">Per bulan — Tahun {{ now()->year }}</p>
        </div>
        <div style="background:rgba(99,102,241,0.15);border:1px solid rgba(99,102,241,0.25);border-radius:8px;padding:4px 12px;">
            <span style="color:#a5b4fc;font-size:12px;font-weight:500;">{{ now()->year }}</span>
        </div>
    </div>
    <canvas id="chartPeminjaman" height="70"></canvas>
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('chartPeminjaman').getContext('2d');
const grad = ctx.createLinearGradient(0, 0, 0, 200);
grad.addColorStop(0, 'rgba(99,102,241,0.5)');
grad.addColorStop(1, 'rgba(99,102,241,0.02)');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($labels) !!},
        datasets: [{
            label: 'Peminjaman',
            data: {!! json_encode($data) !!},
            backgroundColor: 'rgba(99,102,241,0.65)',
            borderColor: 'rgba(99,102,241,1)',
            borderWidth: 0,
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(10,15,40,0.90)',
                titleColor: 'rgba(255,255,255,0.70)',
                bodyColor: '#fff',
                borderColor: 'rgba(99,102,241,0.4)',
                borderWidth: 1,
                padding: 10,
                cornerRadius: 8,
            }
        },
        scales: {
            x: {
                grid: { color: 'rgba(255,255,255,0.04)', drawBorder: false },
                ticks: { color: 'rgba(255,255,255,0.40)', font: { size: 12 } }
            },
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(255,255,255,0.06)', drawBorder: false },
                ticks: { color: 'rgba(255,255,255,0.40)', stepSize: 1, font: { size: 12 } }
            }
        }
    }
});
</script>
@endpush
