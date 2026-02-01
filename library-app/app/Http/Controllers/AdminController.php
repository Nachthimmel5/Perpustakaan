<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Dashboard Admin
    public function dashboard()
    {
        $totalBuku = Buku::count();
        $totalAnggota = Anggota::count();
        $totalTransaksi = Transaksi::count();
        
        return view('admin.dashboard', compact('totalBuku', 'totalAnggota', 'totalTransaksi'));
    }

    public function buku()
    {
        $buku = Buku::all();
        return view('admin.buku', compact('buku'));
    }

    public function bukuStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:50',
            'pengarang' => 'required|string|max:50',
            'penerbit' => 'required|string|max:50',
            'tahun' => 'required|integer',
        ]);

        Buku::create($request->all());
        return back()->with('success', 'Buku berhasil ditambahkan');
    }

    public function bukuUpdate(Request $request, $id)
    {
        $request->validate([
        'judul' => 'required|string|max:50',  // Ganti jadi judul
        'pengarang' => 'required|string|max:50',
        'penerbit' => 'required|string|max:50',
        'tahun' => 'required|integer',
    ]);

        $buku = Buku::findOrFail($id);
        $buku->update($request->all());
        return back()->with('success', 'Buku berhasil diupdate');
    }

    public function bukuDestroy($id)
    {
        Buku::findOrFail($id)->delete();
        return back()->with('success', 'Buku berhasil dihapus');
    }

    // === TRANSAKSI ===
    public function transaksi()
    {
        $transaksi = Transaksi::with(['buku', 'anggota'])->orderBy('created_at', 'desc')->get();
        $buku = Buku::all();
        $anggota = Anggota::all();
        return view('admin.transaksi', compact('transaksi', 'buku', 'anggota'));
    }

    public function transaksiStore(Request $request)
    {
        $request->validate([
            'id_buku' => 'required|exists:buku,id_buku',
            'id_anggota' => 'required|exists:anggota,id_anggota',
            'tanggal_pinjam' => 'required|date',
        ]);

        Transaksi::create([
            'id_buku' => $request->id_buku,
            'id_anggota' => $request->id_anggota,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => null, // Belum dikembalikan
        ]);

        return back()->with('success', 'Transaksi peminjaman berhasil ditambahkan');
    }

    public function transaksiUpdate(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        $request->validate([
            'tanggal_kembali' => 'required|date|after_or_equal:' . $transaksi->tanggal_pinjam,
        ]);

        $transaksi->update([
            'tanggal_kembali' => $request->tanggal_kembali,
        ]);

        return back()->with('success', 'Buku berhasil dikembalikan');
    }

    public function transaksiDestroy($id)
    {
        Transaksi::findOrFail($id)->delete();
        return back()->with('success', 'Transaksi berhasil dihapus');
    }

    // === KELOLA ANGGOTA ===
    public function anggota()
    {
        $anggota = Anggota::with('user')->get();
        return view('admin.anggota', compact('anggota'));
    }

    public function anggotaStore(Request $request)
    {
        $request->validate([
            'nis' => 'required|integer|unique:anggota',
            'nama' => 'required',
            'kelas' => 'required',
            'jurusan' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
        ]);

        $anggota = Anggota::create($request->only('nis', 'nama', 'kelas', 'jurusan'));

        User::create([
            'id_anggota' => $anggota->id_anggota,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'anggota',
        ]);

        return back()->with('success', 'Anggota berhasil ditambahkan');
    }

    public function anggotaUpdate(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);
        $anggota->update($request->only('nis', 'nama', 'kelas', 'jurusan'));
        
        if ($request->filled('password')) {
            $anggota->user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Anggota berhasil diupdate');
    }

    public function anggotaDestroy($id)
    {
        $anggota = Anggota::findOrFail($id);
        $anggota->user->delete();
        $anggota->delete();
        return back()->with('success', 'Anggota berhasil dihapus');
    }
}