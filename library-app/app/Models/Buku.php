<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';
    protected $primaryKey = 'id_buku';

    protected $fillable = [
        'judul', 'pengarang', 'penerbit', 'tahun'
    ];

    public function transaksi(){
        return $this->hasMany(Transaksi::class, 'id_buku', 'id_buku');
    }
}
