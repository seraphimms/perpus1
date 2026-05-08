<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pinjam extends Model
{
    protected $table = 'pinjam';
    protected $fillable = ['user_id', 'tgl_pinjam', 'status'];

    protected function casts(): array
    {
        return ['tgl_pinjam' => 'date'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailPinjam()
    {
        return $this->hasMany(DetailPinjam::class, 'pinjam_id');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'pinjam_id');
    }
}
