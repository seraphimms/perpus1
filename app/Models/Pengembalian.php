<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';
    protected $fillable = ['pinjam_id', 'tgl_kembali', 'total_denda'];

    protected function casts(): array
    {
        return ['tgl_kembali' => 'date'];
    }

    public function pinjam()
    {
        return $this->belongsTo(Pinjam::class, 'pinjam_id');
    }

    public function detailPengembalian()
    {
        return $this->hasMany(DetailPengembalian::class, 'pengembalian_id');
    }
}
