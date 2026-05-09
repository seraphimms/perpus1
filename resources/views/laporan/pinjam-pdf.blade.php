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
        table { width: 100%; border-collapse: collapse; }
        th { background: #000000; color: white; padding: 6px 8px; text-align: left; font-size: 10px; }
        td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tr:nth-child(even) td { background: #f8fafc; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-pinjam { background: #fef3c7; color: #92400e; }
        .badge-kembali { background: #d1fae5; color: #065f46; }
        .footer { margin-top: 15px; font-size: 10px; color: #888; text-align: right; }
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

    <p class="footer">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>
