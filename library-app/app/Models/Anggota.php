<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table = 'anggota';
    protected $primaryKey = 'id_anggota';

    protected $fillable = [
        'nis',
        'nama',
        'kelas',
        'jurusan',
    ];

    public function user(){
        return $this->hasOne(User::class, 'id_anggota','id_anggota');
    }

    public function transaksi(){
        return $this->hasMany(Transaksi::class, 'id_anggota', 'id_anggota');
    }
}
