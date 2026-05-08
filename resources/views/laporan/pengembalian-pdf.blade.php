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
        table { width: 100%; border-collapse: collapse; }
        th { background: #065f46; color: white; padding: 6px 8px; text-align: left; font-size: 10px; }
        td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tr:nth-child(even) td { background: #f8fafc; }
        tfoot td { background: #f1f5f9; font-weight: bold; }
        .denda { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 15px; font-size: 10px; color: #888; text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Pengembalian Buku</h1>
    <p class="subtitle">Perpustakaan SMP Muhammadiyah 1</p>
    <p class="period">
        Periode: {{ $dari ? date('d/m/Y', strtotime($dari)) : 'Semua' }}
        s/d {{ $sampai ? date('d/m/Y', strtotime($sampai)) : 'Semua' }}
        &mdash; Total {{ $pengembalian->count() }} transaksi
    </p>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Pinjam</th>
                <th>Anggota</th>
                <th>Tgl Kembali</th>
                <th>Buku</th>
                <th style="text-align:right">Total Denda</th>
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
                <td style="text-align:right" class="{{ $item->total_denda > 0 ? 'denda' : '' }}">
                    Rp {{ number_format($item->total_denda, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#999;">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align:right">Total Denda Keseluruhan</td>
                <td style="text-align:right" class="denda">Rp {{ number_format($pengembalian->sum('total_denda'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <p class="footer">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>
