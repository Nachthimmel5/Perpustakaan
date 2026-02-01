<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SiswaController extends Controller
{
    // Dashboard Siswa
    public function dashboard()
    {
        $user = Auth::user();
        $anggota = $user->anggota;
        
        $totalPinjam = Transaksi::where('id_anggota', $anggota->id_anggota)
                                ->whereNull('tanggal_kembali')
                                ->count();
        
        $totalRiwayat = Transaksi::where('id_anggota', $anggota->id_anggota)->count();
        
        return view('siswa.dashboard', compact('anggota', 'totalPinjam', 'totalRiwayat'));
    }

    // Peminjaman Buku
    public function peminjaman()
    {
        $user = Auth::user();
        $anggota = $user->anggota;
        $buku = Buku::all();
        
        // Buku yang sedang dipinjam siswa ini
        $sedangDipinjam = Transaksi::with('buku')
                                   ->where('id_anggota', $anggota->id_anggota)
                                   ->whereNull('tanggal_kembali')
                                   ->get();
        
        return view('siswa.peminjaman', compact('buku', 'sedangDipinjam', 'anggota'));
    }

    public function peminjamanStore(Request $request)
    {
        $user = Auth::user();
        $anggota = $user->anggota;

        $request->validate([
            'id_buku' => 'required|exists:buku,id_buku',
        ]);

        // Cek apakah buku sedang dipinjam oleh siswa ini
        $sudahPinjam = Transaksi::where('id_anggota', $anggota->id_anggota)
                                ->where('id_buku', $request->id_buku)
                                ->whereNull('tanggal_kembali')
                                ->exists();

        if ($sudahPinjam) {
            return back()->with('error', 'Anda sudah meminjam buku ini');
        }

        Transaksi::create([
            'id_buku' => $request->id_buku,
            'id_anggota' => $anggota->id_anggota,
            'tanggal_pinjam' => Carbon::now(),
            'tanggal_kembali' => null,
        ]);

        return back()->with('success', 'Buku berhasil dipinjam');
    }

    // Pengembalian Buku
    public function pengembalian()
    {
        $user = Auth::user();
        $anggota = $user->anggota;
        
        // Buku yang sedang dipinjam
        $sedangDipinjam = Transaksi::with('buku')
                                   ->where('id_anggota', $anggota->id_anggota)
                                   ->whereNull('tanggal_kembali')
                                   ->get();
        
        // Riwayat peminjaman
        $riwayat = Transaksi::with('buku')
                            ->where('id_anggota', $anggota->id_anggota)
                            ->whereNotNull('tanggal_kembali')
                            ->orderBy('tanggal_kembali', 'desc')
                            ->get();
        
        return view('siswa.pengembalian', compact('sedangDipinjam', 'riwayat', 'anggota'));
    }

    public function pengembalianStore(Request $request, $id)
    {
        $user = Auth::user();
        $anggota = $user->anggota;

        $transaksi = Transaksi::where('id_transaksi', $id)
                              ->where('id_anggota', $anggota->id_anggota)
                              ->whereNull('tanggal_kembali')
                              ->firstOrFail();

        $transaksi->update([
            'tanggal_kembali' => Carbon::now(),
        ]);

        return back()->with('success', 'Buku berhasil dikembalikan');
    }
}