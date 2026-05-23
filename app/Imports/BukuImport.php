<?php

namespace App\Imports;

use App\Models\Buku;
use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;

class BukuImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    public int $importedCount = 0;
    public int $skippedCount  = 0;

    public function model(array $row)
    {
        if (empty($row['judul']) || empty($row['penulis'])) {
            $this->skippedCount++;
            return null;
        }

        $kategori = Kategori::where('nama', $row['kategori'] ?? '')->first();

        if (!$kategori) {
            $this->skippedCount++;
            return null;
        }

        $this->importedCount++;

        return new Buku([
            'judul'       => $row['judul'],
            'penulis'     => $row['penulis'],
            'penerbit'    => $row['penerbit'] ?? '-',
            'tahun'       => is_numeric($row['tahun'] ?? '') ? (int) $row['tahun'] : date('Y'),
            'isbn'        => $row['isbn'] ?? null,
            'jumlah'      => is_numeric($row['jumlah'] ?? '') ? (int) $row['jumlah'] : 1,
            'kategori_id' => $kategori->id,
        ]);
    }
}