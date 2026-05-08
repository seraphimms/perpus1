<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPengembalian extends Model
{
    protected $table = 'detail_pengembalian';
    protected $fillable = ['pengembalian_id', 'detail_pinjam_id', 'tgl_kembali_aktual', 'kondisi_buku', 'denda'];

    protected function casts(): array
    {
        return ['tgl_kembali_aktual' => 'date'];
    }

    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class, 'pengembalian_id');
    }

    public function detailPinjam()
    {
        return $this->belongsTo(DetailPinjam::class, 'detail_pinjam_id');
    }
}
