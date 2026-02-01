<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Transaksi;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bikin Admin
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'id_anggota' => null,
        ]);

        // 2. Bikin Anggota
        $anggota1 = Anggota::create([
            'nis' => 12345,
            'nama' => 'Budi Santoso',
            'kelas' => '12 RPL 1',
            'jurusan' => 'rpl',
        ]);

        $anggota2 = Anggota::create([
            'nis' => 12346,
            'nama' => 'Siti Aminah',
            'kelas' => '11 TKJ 2',
            'jurusan' => 'tkj',
        ]);

        // 3. Bikin User untuk Anggota
        User::create([
            'username' => 'budi',
            'password' => Hash::make('budi123'),
            'role' => 'anggota',
            'id_anggota' => $anggota1->id_anggota,
        ]);

        User::create([
            'username' => 'siti',
            'password' => Hash::make('siti123'),
            'role' => 'anggota',
            'id_anggota' => $anggota2->id_anggota,
        ]);

        // 4. Bikin Buku
        $buku1 = Buku::create([
            'judul' => 'Pemrograman Web dengan Laravel',
            'pengarang' => 'John Doe',
            'penerbit' => 'Informatika',
            'tahun' => 2023,
        ]);

        $buku2 = Buku::create([
            'judul' => 'Dasar-Dasar PHP',
            'pengarang' => 'Jane Smith',
            'penerbit' => 'Andi Publisher',
            'tahun' => 2022,
        ]);

        Buku::create([
            'judul' => 'MySQL untuk Pemula',
            'pengarang' => 'Ahmad Zain',
            'penerbit' => 'Elex Media',
            'tahun' => 2021,
        ]);

        // 5. Bikin Transaksi (contoh)
        Transaksi::create([
            'id_buku' => $buku1->id_buku,
            'id_anggota' => $anggota1->id_anggota,
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => null, // Masih dipinjam
        ]);

        Transaksi::create([
            'id_buku' => $buku2->id_buku,
            'id_anggota' => $anggota2->id_anggota,
            'tanggal_pinjam' => now()->subDays(5),
            'tanggal_kembali' => now(), // Udah dikembalikan
        ]);
    }
}