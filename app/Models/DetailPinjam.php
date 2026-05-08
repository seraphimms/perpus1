<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPinjam extends Model
{
    protected $table = 'detail_pinjam';
    protected $fillable = ['pinjam_id', 'buku_id', 'jumlah', 'tgl_kembali_estimasi'];

    protected function casts(): array
    {
        return ['tgl_kembali_estimasi' => 'date'];
    }

    public function pinjam()
    {
        return $this->belongsTo(Pinjam::class, 'pinjam_id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    public function detailPengembalian()
    {
        return $this->hasOne(DetailPengembalian::class, 'detail_pinjam_id');
    }
}
