<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';
    protected $fillable = ['judul', 'penulis', 'penerbit', 'tahun', 'isbn', 'jumlah', 'kategori_id', 'cover', 'deskripsi'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function detailPinjam()
    {
        return $this->hasMany(DetailPinjam::class, 'buku_id');
    }
}
