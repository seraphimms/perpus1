<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengembalian</title>
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
        .bar { background: #10b981; border-radius: 3px 3px 0 0; width: 100%; min-height: 2px; }
        .bar-label { font-size: 8px; color: #666; margin-top: 3px; text-align: center; }
        .bar-val { font-size: 8px; color: #333; font-weight: bold; margin-bottom: 2px; }

        /* Ringkasan */
        .summary { display: flex; gap: 10px; margin-bottom: 15px; }
        .summary-box { flex: 1; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 12px; text-align: center; }
        .summary-box .val { font-size: 18px; font-weight: bold; color: #1e2a45; }
        .summary-box .val.red { color: #dc2626; }
        .summary-box .lbl { font-size: 9px; color: #888; }

        /* Tabel */
        table { width: 100%; border-collapse: collapse; }
        th { background: #1e2a45; color: white; padding: 6px 8px; text-align: left; font-size: 10px; }
        td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tr:nth-child(even) td { background: #f8fafc; }
        tfoot td { background: #f1f5f9; font-weight: bold; }
        .denda { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 15px; font-size: 10px; color: #888; text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Pengembalian Buku</h1>
    <p class="subtitle">Perpustakaan SMP Muhammadiyah 1 Cilacap</p>
    <p class="period">
        Periode: {{ $dari ? date('d/m/Y', strtotime($dari)) : 'Semua' }}
        s/d {{ $sampai ? date('d/m/Y', strtotime($sampai)) : 'Semua' }}
        &mdash; Total {{ $pengembalian->count() }} transaksi
    </p>

    {{-- Tabel --}}
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Pinjam</th>
                <th>Anggota</th>
                <th>Tgl Kembali</th>
                <th>Buku</th>
                <th style="text-align:right;">Total Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengembalian as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>#{{ str_pad($item->pinjam->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $item->pinjam->user->nama }}</td>
                <td>{{ $item->tgl_kembali->format('d/m/Y') }}</td>
                <td>
                    @foreach($item->detailPengembalian as $dp)
                    {{ $dp->detailPinjam->buku->judul }} ({{ $dp->kondisi_buku }}){{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </td>
                <td style="text-align:right;" class="{{ $item->total_denda > 0 ? 'denda' : '' }}">
                    Rp {{ number_format($item->total_denda, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#999;">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align:right;">Total Denda Keseluruhan</td>
                <td style="text-align:right;" class="denda">
                    Rp {{ number_format($pengembalian->sum('total_denda'), 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
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
            <div class="val">{{ $pengembalian->count() }}</div>
            <div class="lbl">Total Pengembalian</div>
        </div>
        <div class="summary-box">
            <div class="val">{{ $pengembalian->where('total_denda', '>', 0)->count() }}</div>
            <div class="lbl">Transaksi Kena Denda</div>
        </div>
        <div class="summary-box">
            <div class="val">{{ $pengembalian->where('total_denda', 0)->count() }}</div>
            <div class="lbl">Tepat Waktu</div>
        </div>
        <div class="summary-box">
            <div class="val red">Rp {{ number_format($pengembalian->sum('total_denda'), 0, ',', '.') }}</div>
            <div class="lbl">Total Denda</div>
        </div>
    </div>

    <p class="footer">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>