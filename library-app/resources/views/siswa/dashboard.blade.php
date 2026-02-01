@extends('layouts.app')

@section('title', 'Dashboard Siswa')

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
                {{ $anggota->nama }} ({{ $anggota->kelas }} - {{ strtoupper($anggota->jurusan) }})
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3 class="mb-4">Dashboard Siswa</h3>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5>Buku Sedang Dipinjam</h5>
                    <h2>{{ $totalPinjam }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5>Total Riwayat Peminjaman</h5>
                    <h2>{{ $totalRiwayat }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Peminjaman Buku</h5>
                    <p class="card-text">Pinjam buku dari koleksi perpustakaan</p>
                    <a href="{{ route('siswa.peminjaman') }}" class="btn btn-success">Pinjam Buku</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Pengembalian Buku</h5>
                    <p class="card-text">Kembalikan buku dan lihat riwayat peminjaman</p>
                    <a href="{{ route('siswa.pengembalian') }}" class="btn btn-success">Kembalikan Buku</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection