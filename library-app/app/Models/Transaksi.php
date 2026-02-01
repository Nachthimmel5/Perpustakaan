<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_transaksi',
        'id_anggota',
        'id_buku',
        'tanggal_pinjam',
        'tanggal_kembali'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
    ];

    public function anggota(){
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    public function buku(){
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }
}
