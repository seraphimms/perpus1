<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; margin: 0; padding: 15px; }
        h1 { font-size: 16px; text-align: center; margin-bottom: 2px; }
        .subtitle { text-align: center; color: #666; font-size: 11px; margin-bottom: 4px; }
        .period { text-align: center; font-size: 10px; color: #888; margin-bottom: 15px; }

        /* Grafik */
        .chart-wrap { margin-bottom: 20px; }
        .chart-title { font-size: 12px; font-weight: bold; margin-bottom: 8px; }
        .chart-bars { display: flex; align-items: flex-end; gap: 6px; height: 80px; border-bottom: 1px solid #ddd; padding-bottom: 4px; }
        .bar-col { display: flex; flex-direction: column; align-items: center; flex: 1; }
        .bar { background: #3b4ec8; border-radius: 3px 3px 0 0; width: 100%; min-height: 2px; }
        .bar-label { font-size: 8px; color: #666; margin-top: 3px; text-align: center; }
        .bar-val { font-size: 8px; color: #333; font-weight: bold; margin-bottom: 2px; }

        /* Tabel */
        table { width: 100%; border-collapse: collapse; }
        th { background: #1e2a45; color: white; padding: 6px 8px; text-align: left; font-size: 10px; }
        td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tr:nth-child(even) td { background: #f8fafc; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-pinjam  { background: #fef3c7; color: #92400e; }
        .badge-kembali { background: #d1fae5; color: #065f46; }
        .footer { margin-top: 15px; font-size: 10px; color: #888; text-align: right; }

        /* Ringkasan */
        .summary { display: flex; gap: 10px; margin-bottom: 15px; }
        .summary-box { flex: 1; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 12px; text-align: center; }
        .summary-box .val { font-size: 18px; font-weight: bold; color: #1e2a45; }
        .summary-box .lbl { font-size: 9px; color: #888; }
    </style>
</head>
<body>
    <h1>Laporan Peminjaman Buku</h1>
    <p class="subtitle">Perpustakaan SMP Muhammadiyah 1 Cilacap</p>
    <p class="period">
        Periode: {{ $dari ? date('d/m/Y', strtotime($dari)) : 'Semua' }}
        s/d {{ $sampai ? date('d/m/Y', strtotime($sampai)) : 'Semua' }}
        &mdash; Total {{ $pinjam->count() }} transaksi
    </p>

    {{-- Tabel --}}
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Transaksi</th>
                <th>Anggota</th>
                <th>Tgl Pinjam</th>
                <th>Buku Dipinjam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pinjam as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>#{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $item->user->nama }}</td>
                <td>{{ $item->tgl_pinjam->format('d/m/Y') }}</td>
                <td>
                    @foreach($item->detailPinjam as $dp)
                    {{ $dp->buku->judul }} ({{ $dp->jumlah }}){{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </td>
                <td>
                    <span class="badge {{ $item->status === 'pinjam' ? 'badge-pinjam' : 'badge-kembali' }}">
                        {{ $item->status === 'pinjam' ? 'Dipinjam' : 'Kembali' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#999;">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Grafik --}}
@if($grafikData->count() > 0)
<div class="chart-wrap">
    <div class="chart-title">Grafik Peminjaman per Bulan</div>
    <table style="width:100%;border-collapse:collapse;height:100px;">
        <tr style="vertical-align:bottom;height:80px;">
            @foreach($grafikData as $bulan => $total)
            <td style="text-align:center;vertical-align:bottom;padding:0 3px;">
                <div style="font-size:8px;font-weight:bold;margin-bottom:2px;">{{ $total }}</div>
                <div style="background:#3b4ec8;width:100%;height:{{ round(($total / $maxGrafik) * 60) }}px;border-radius:2px 2px 0 0;"></div>
            </td>
            @endforeach
        </tr>
        <tr>
            @foreach($grafikData as $bulan => $total)
            <td style="text-align:center;font-size:8px;color:#666;padding:3px 2px;border-top:1px solid #ddd;">{{ $bulan }}</td>
            @endforeach
        </tr>
    </table>
</div>
@endif

    {{-- Ringkasan --}}
    <div class="summary">
        <div class="summary-box">
            <div class="val">{{ $pinjam->count() }}</div>
            <div class="lbl">Total Transaksi</div>
        </div>
        <div class="summary-box">
            <div class="val">{{ $pinjam->where('status','pinjam')->count() }}</div>
            <div class="lbl">Masih Dipinjam</div>
        </div>
        <div class="summary-box">
            <div class="val">{{ $pinjam->where('status','kembali')->count() }}</div>
            <div class="lbl">Sudah Kembali</div>
        </div>
        <div class="summary-box">
            <div class="val">{{ $pinjam->sum(fn($p) => $p->detailPinjam->sum('jumlah')) }}</div>
            <div class="lbl">Total Buku Dipinjam</div>
        </div>
    </div>


    <p class="footer">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>