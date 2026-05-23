<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PinjamExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $pinjam;
    protected $dari;
    protected $sampai;

    public function __construct($pinjam, $dari, $sampai)
    {
        $this->pinjam  = $pinjam;
        $this->dari    = $dari;
        $this->sampai  = $sampai;
    }

    public function collection()
    {
        return $this->pinjam->map(function($item, $i) {
            return [
                'No'            => $i + 1,
                'No Transaksi'  => '#' . str_pad($item->id, 5, '0', STR_PAD_LEFT),
                'Anggota'       => $item->user->nama,
                'Tgl Pinjam'    => $item->tgl_pinjam->format('d/m/Y'),
                'Buku Dipinjam' => $item->detailPinjam->map(fn($dp) => $dp->buku->judul . ' (' . $dp->jumlah . ')')->implode(', '),
                'Status'        => $item->status === 'pinjam' ? 'Dipinjam' : 'Kembali',
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Laporan Peminjaman Buku', '', '', '', '', ''],
            ['Perpustakaan SMP Muhammadiyah 1 Cilacap', '', '', '', '', ''],
            [
                'Periode: ' . ($this->dari ? date('d/m/Y', strtotime($this->dari)) : 'Semua') .
                ' s/d ' . ($this->sampai ? date('d/m/Y', strtotime($this->sampai)) : 'Semua') .
                ' — Total ' . $this->pinjam->count() . ' transaksi',
                '', '', '', '', ''
            ],
            ['No', 'No. Transaksi', 'Anggota', 'Tgl Pinjam', 'Buku Dipinjam', 'Status'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');

        return [
            1 => [
                'font'      => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            2 => [
                'font'      => ['size' => 11],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            3 => [
                'font'      => ['size' => 10, 'color' => ['rgb' => '888888']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '000000'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Peminjaman';
    }
}