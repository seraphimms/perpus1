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

class PengembalianExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $pengembalian;
    protected $dari;
    protected $sampai;

    public function __construct($pengembalian, $dari, $sampai)
    {
        $this->pengembalian = $pengembalian;
        $this->dari         = $dari;
        $this->sampai       = $sampai;
    }

    public function collection()
    {
        return $this->pengembalian->map(function($item, $i) {
            return [
                'No'           => $i + 1,
                'No Pinjam'    => '#' . str_pad($item->pinjam->id, 5, '0', STR_PAD_LEFT),
                'Anggota'      => $item->pinjam->user->nama,
                'Tgl Kembali'  => $item->tgl_kembali->format('d/m/Y'),
                'Buku'         => $item->detailPengembalian->map(fn($dp) => $dp->detailPinjam->buku->judul . ' (' . $dp->kondisi_buku . ')')->implode(', '),
                'Total Denda'  => 'Rp ' . number_format($item->total_denda, 0, ',', '.'),
                'Status Denda' => $item->status_denda === 'lunas' ? 'Lunas' : 'Belum Lunas',
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Laporan Pengembalian Buku', '', '', '', '', '', ''],
            ['Perpustakaan SMP Muhammadiyah 1 Cilacap', '', '', '', '', '', ''],
            [
                'Periode: ' . ($this->dari ? date('d/m/Y', strtotime($this->dari)) : 'Semua') .
                ' s/d ' . ($this->sampai ? date('d/m/Y', strtotime($this->sampai)) : 'Semua') .
                ' — Total ' . $this->pengembalian->count() . ' transaksi' .
                ' · Total Denda: Rp ' . number_format($this->pengembalian->sum('total_denda'), 0, ',', '.'),
                '', '', '', '', '', ''
            ],
            ['No', 'No. Pinjam', 'Anggota', 'Tgl Kembali', 'Buku', 'Total Denda', 'Status Denda'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');

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
        return 'Laporan Pengembalian';
    }
}