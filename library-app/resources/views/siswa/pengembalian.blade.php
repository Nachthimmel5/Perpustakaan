@extends('layouts.app')

@section('title', 'Pengembalian Buku')

@section('content')
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Perpustakaan Siswa</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('siswa.dashboard') }}">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('siswa.peminjaman') }}">Peminjaman Buku</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('siswa.pengembalian') }}">Pengembalian Buku</a></li>
            </ul>
            <span class="navbar-text text-white me-3">
                {{ $anggota->nama }}
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3 class="mb-3">Pengembalian Buku</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Buku yang sedang dipinjam -->
    <h5 class="mb-3">Buku yang Sedang Dipinjam</h5>
    @if($sedangDipinjam->count() > 0)
    <table class="table table-bordered">
        <thead class="table-success">
            <tr>
                <th>Judul Buku</th>
                <th>Pengarang</th>
                <th>Tanggal Pinjam</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sedangDipinjam as $pinjam)
            <tr>
                <td>{{ $pinjam->buku->judul }}</td>
                <td>{{ $pinjam->buku->pengarang }}</td>
                <td>{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y H:i') }}</td>
                <td>
                    <form method="POST" action="{{ route('siswa.pengembalian.store', $pinjam->id_transaksi) }}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Kembalikan buku ini?')">Kembalikan</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="alert alert-info">Anda tidak sedang meminjam buku</div>
    @endif

    <!-- Riwayat Peminjaman -->
    <h5 class="mb-3 mt-4">Riwayat Peminjaman</h5>
    @if($riwayat->count() > 0)
    <table class="table table-bordered">
        <thead class="table-secondary">
            <tr>
                <th>Judul Buku</th>
                <th>Pengarang</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
            </tr>
        </thead>
        <tbody>
            @foreach($riwayat as $r)
            <tr>
                <td>{{ $r->buku->judul }}</td>
                <td>{{ $r->buku->pengarang }}</td>
                <td>{{ \Carbon\Carbon::parse($r->tanggal_pinjam)->format('d/m/Y H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($r->tanggal_kembali)->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="alert alert-info">Belum ada riwayat peminjaman</div>
    @endif
</div>
@endsection